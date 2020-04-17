<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_pembayaran extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if (!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('transaksi/m_pembayaran');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
	}
	public function index()
	{
		$this->load->view('core/header');
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Transaksi Service > Pembayaran Tagihan', 'subTitle' => 'List']);
		$this->load->view('Proyek/Transaksi/Pembayaran/view');
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function add($unit_id = 0)
	{

		$project = $this->m_core->project();
		$unit = (object) [];
		$this->load->model('Setting/m_parameter_project');
		$max_backdate_pembayaran = $this->m_parameter_project->get($project->id,"max_backdate_pembayaran");
		$backdate = date('Y-m-d',strtotime(date("Y-m-d") . "-".($max_backdate_pembayaran)." days"));
 
		if ($unit_id != 0) {
			$unit = $this->db
				->select("unit.id, CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) as text")
				->from('unit')
				->join(
					'blok',
					'blok.id = unit.blok_id'
				)
				->join(
					'kawasan',
					'kawasan.id = blok.kawasan_id'
				)
				->join(
					'customer',
					'customer.id = unit.pemilik_customer_id'
				)
				->where('unit.project_id', $GLOBALS['project']->id)
				->where("unit.id", $unit_id)
				->get()->row();
		} else {
			$unit->id = 0;
		}
		$data = $this->m_pembayaran->get_all_unit();
		$cara_pembayaran = $this->db
								->select("
											case 
												when isnull(bank_id,0) = 0 THEN
													cara_pembayaran.id
												else jenis_cara_pembayaran_id 
											end as id,
											jenis_cara_pembayaran_id,
											code,
											name,
											sum(biaya_admin) as biaya_admin")
								->from("cara_pembayaran")
								->where("delete", 0)
								->where("project_id",$project->id)
								->group_by("case 
											when isnull(bank_id,0) = 0 THEN
												cara_pembayaran.id
											else jenis_cara_pembayaran_id 
											end,
											jenis_cara_pembayaran_id,
											code,
											name")
								->distinct()

								->get()->result();
		$bank = $this->db->select("
								cara_pembayaran.jenis_cara_pembayaran_id,
								cara_pembayaran.id,
								cara_pembayaran.biaya_admin,
								bank.name")
							->from("cara_pembayaran")
							->join("bank",
									"bank.id = cara_pembayaran.bank_id")
							->where("cara_pembayaran.delete", 0)
							->where("cara_pembayaran.project_id",$project->id)
							->where("isnull(bank.id,0) != 0")
							->distinct()
							->get()->result();
		$this->load->view('core/header');
		$this->load->model('alert');
		$this->alert->css();

		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Transaksi Service > Pembayaran Tagihan', 'subTitle' => 'List']);
		$this->load->view('Proyek/Transaksi/Pembayaran/add', [
			"data" 				=> $data,
			"cara_pembayaran"	=> $cara_pembayaran,
			"unit"				=> $unit,
			"unit_id" 			=> $unit_id,
			"bank"				=> $bank,
			"backdate"			=> $backdate
		]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function ajax_save()
	{
		$bayarTMP = $this->input->post("bayar");
		$bayar_depositTMP = $this->input->post("bayar_deposit");
		$unit_id = $this->input->post("unit_id");
		$cara_pembayaran_id = $this->input->post("cara_pembayaran");
		$project = $this->m_core->project();
		$dateForm = $this->input->post("date"); 
		$diskon = $this->input->post("diskon"); 
		$mulai_diskon = $this->input->post("mulai_diskon"); 
		
		$user_id = $this->db->select("id")
			->from("user")
			->where("username", $this->session->userdata["username"])
			->get()->row()->id;
			$biaya_admin = $this->input->post("biaya_admin");
		echo(json_encode($this->m_pembayaran->save($bayarTMP,$bayar_depositTMP,$unit_id,$cara_pembayaran_id,$project->id,$user_id,$biaya_admin,$dateForm,$diskon,$mulai_diskon)));
		
		//pembayaran, pembayaran_detail, tagihan per service
	}
	public function ajax_get_unit()
	{
		$data = $this->input->get("data");	 
		echo (json_encode($this->m_pembayaran->ajax_get_unit($data)));
	}
	public function ajax_get_deposit($unit_id)
	{
		$customer = $this->db->select("customer.id")
			->from("unit")
			->join(
				"customer",
				"customer.id = unit.pemilik_customer_id"
			)
			->where("unit.id", $unit_id)
			->get()->row()->id;
		echo json_encode($this->m_deposit->ajax_get_deposit($customer));
	}
	public function ajax_get_tagihan()
	{
		$project = $this->m_core->project();
		$dateForm = $this->input->post("date");

		$unit_id = $this->input->post("unit_id");
		if($dateForm)
			$periode_now = substr($dateForm,6,4)."-".substr($dateForm,3,2)."-01";
		else
			$periode_now = date("Y-m-01");

		$periode_pemakaian = date("Y-m-01", strtotime("-1 Months"));

		// $unit = $this->db->select("
		// 							pemilik.name as pemilik, 
		// 							penghuni.name as penghuni,
		// 							unit.luas_bangunan,
		// 							isnull(unit.luas_taman,0) as luas_taman,
		// 							unit.luas_tanah,
		// 							concat(jenis_golongan.code,' - ',jenis_golongan.description) as golongan,
		// 							'Rumah' as purpose_use,
		// 							'-' as type_unit,
		// 							convert(varchar,tgl_st,103) as tgl_st
		// 							")
		// 					->from('unit')
		// 					->join('customer as pemilik','pemilik.id = unit.pemilik_customer_id')
		// 					->join('customer as penghuni','penghuni.id = unit.penghuni_customer_id')
		// 					->join('jenis_golongan','jenis_golongan.id = unit.gol_id')
		//                     ->where('unit.id',$unit_id)
		// 					->get()->row();

		$tagihan_air = $this->db->select("
								CASE
									WHEN v_tagihan_air.periode >= '$periode_now' THEN 1
									ELSE 0
								END as is_tagihan,
								v_tagihan_air.total_tanpa_ppn,
								v_tagihan_air.tagihan_id,
								service.id as service_id,
								service.service_jenis_id as service_jenis_id,
								v_tagihan_air.periode,
								isnull(v_tagihan_air.total,0) as nilai_tagihan,
								isnull(CASE
								WHEN service.denda_flag = 0 THEN 0
								WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
								WHEN v_tagihan_air.periode > '$periode_now' THEN 0
									ELSE
									CASE					
										WHEN v_tagihan_air.denda_jenis_service = 1 
											THEN v_tagihan_air.denda_nilai_service 
										WHEN v_tagihan_air.denda_jenis_service = 2 
											THEN v_tagihan_air.denda_nilai_service *
												(DateDiff
													( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$periode_now' ) 
													+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) 
												)
										WHEN v_tagihan_air.denda_jenis_service = 3 
											THEN 
												(v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) 
												* (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
												+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
									END 
								END,0) AS nilai_denda,
								isnull(v_tagihan_air.total,0)+
								isnull(CASE
									WHEN service.denda_flag = 0 THEN 0
									WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
									WHEN v_tagihan_air.periode > '$periode_now' THEN 0
									ELSE
									CASE
										WHEN v_tagihan_air.denda_jenis_service = 1 
											THEN v_tagihan_air.denda_nilai_service 
										WHEN v_tagihan_air.denda_jenis_service = 2 
											THEN v_tagihan_air.denda_nilai_service *
												(DateDiff
													( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$periode_now' ) 
													+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) 
												)
										WHEN v_tagihan_air.denda_jenis_service = 3 
											THEN (v_tagihan_air.denda_nilai_service * v_tagihan_air.total/ 100 ) 
											* (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
											+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
									END 
								END,0) AS total,
								isnull(CONVERT(int,v_pemutihan.nilai_tagihan_type),-1) as pemutihan_nilai_tagihan_type,
								isnull(CONVERT(int,v_pemutihan.nilai_tagihan),-1) as pemutihan_nilai_tagihan,
								isnull(CONVERT(int,v_pemutihan.nilai_denda_type),-1) as pemutihan_nilai_denda_type,
								isnull(CONVERT(int,v_pemutihan.nilai_denda),-1) as pemutihan_nilai_denda")
			->from("v_tagihan_air")
			// ->where("v_tagihan_air.periode <= '$periode_now'")
			->join(
				"service",
				"service.project_id = $project->id
				AND service.service_jenis_id = 2
				AND service.active = 1
				AND service.delete = 0"
			)
			->join("v_pemutihan",
					"v_pemutihan.masa_akhir >= GETDATE()
					AND v_pemutihan.masa_awal <= GETDATE()
					AND v_pemutihan.periode_akhir >= v_tagihan_air.periode 
					AND v_pemutihan.periode_awal <= v_tagihan_air.periode 
					AND v_pemutihan.service_jenis_id = 2
					AND v_pemutihan.unit_id  = v_tagihan_air.unit_id",
					"LEFT")
			->where("v_tagihan_air.status_tagihan = 0")
			->where("v_tagihan_air.unit_id = $unit_id")
			->order_by("v_pemutihan.tgl_tambah,periode")
			->get()->result();
		$tagihan_lingkungan = $this->db->select("
								CASE
									WHEN v_tagihan_lingkungan.periode >= '$periode_now' THEN 1
									ELSE 0
								END as is_tagihan,
								v_tagihan_lingkungan.total_tanpa_ppn,
								v_tagihan_lingkungan.tagihan_id,
								service.id as service_id,
								service.service_jenis_id as service_jenis_id,
								v_tagihan_lingkungan.periode,
								isnull(v_tagihan_lingkungan.total,0) as nilai_tagihan,
								isnull(CASE
									WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN
										CASE
											WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
												THEN v_tagihan_lingkungan.denda_nilai_service 
											WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
												THEN 
													v_tagihan_lingkungan.denda_nilai_service * 
														(DateDiff
															( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
															+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
														)
											WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
												THEN 
													( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
													* (DateDiff( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
													+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
										END 	
									WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
									WHEN v_tagihan_lingkungan.periode > '$periode_now' THEN 0
									ELSE
										CASE
											WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
												THEN v_tagihan_lingkungan.denda_nilai_service 
											WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
												THEN v_tagihan_lingkungan.denda_nilai_service * 
													(DateDiff
														( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
														+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
													)
											WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
												THEN 
													( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
													* (DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) 
													+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
										END 
									END,0) AS nilai_denda,,
								isnull(v_tagihan_lingkungan.total,0)+
								isnull(CASE
									WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN
										CASE
											WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
												THEN v_tagihan_lingkungan.denda_nilai_service 
											WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
												THEN v_tagihan_lingkungan.denda_nilai_service * 
													(DateDiff
														( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
														+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
													)
											WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
												THEN 
												( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
												* (DateDiff( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
												+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
										END 	
									WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
									WHEN v_tagihan_lingkungan.periode > '$periode_now' THEN 0
									ELSE
										CASE
											WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
												THEN v_tagihan_lingkungan.denda_nilai_service 
											WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
												THEN v_tagihan_lingkungan.denda_nilai_service * 
												(DateDiff
													( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
													+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
												)
											WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
												THEN 
													( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
													* ( 
														DateDiff ( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
														+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
													)
										END 
									END,0) AS total,
								isnull(CONVERT(int,v_pemutihan.nilai_tagihan_type),-1) as pemutihan_nilai_tagihan_type,
								isnull(CONVERT(int,v_pemutihan.nilai_tagihan),-1) as pemutihan_nilai_tagihan,
								isnull(CONVERT(int,v_pemutihan.nilai_denda_type),-1) as pemutihan_nilai_denda_type,
								isnull(CONVERT(int,v_pemutihan.nilai_denda),-1) as pemutihan_nilai_denda")	
			->from("v_tagihan_lingkungan")
			// ->where("v_tagihan_lingkungan.periode <= '$periode_now'")
			->join(
				"service",
				"service.project_id = $project->id
				AND service.service_jenis_id = 1
				AND service.active = 1
				AND service.delete = 0"
			)
			->join("v_pemutihan",
					"v_pemutihan.masa_akhir >= GETDATE()
					AND v_pemutihan.masa_awal <= GETDATE()
					AND v_pemutihan.periode_akhir >= v_tagihan_lingkungan.periode 
					AND v_pemutihan.periode_awal <= v_tagihan_lingkungan.periode 
					AND v_pemutihan.service_jenis_id = 1
					AND v_pemutihan.unit_id  = v_tagihan_lingkungan.unit_id",
					"LEFT")
			->join("unit_lingkungan",
				"unit_lingkungan.unit_id = v_tagihan_lingkungan.unit_id")
			->where("v_tagihan_lingkungan.status_tagihan = 0")
			->where("v_tagihan_lingkungan.unit_id = $unit_id")
			->order_by("v_pemutihan.tgl_tambah,periode")

			->get()->result();
		$jumlah_tagihan_service = 0;
		$jumlah_tunggakan_bulan = 0;
		$jumlah_tunggakan = 0;
		$jumlah_denda = 0;
		$jumlah_penalti = 0;
		$jumlah_tagihan = 0;

		
		$tagihan_air_tmp = $tagihan_air;
		$tagihan_air = [];
		$view_pemutihan_nilai_tagihan = 0;
		$view_pemutihan_nilai_denda = 0;
		$sisa_nilai_tagihan = 0;
		$sisa_nilai_denda = 0;
		foreach ($tagihan_air_tmp as $k => $v) {
			if($v->periode!=$tagihan_air_tmp[(count($tagihan_air_tmp)-1)<($k+1)?(count($tagihan_air_tmp)-1):($k+1)]->periode || $k == count($tagihan_air_tmp)-1){
				// if($v->pemutihan_nilai_tagihan_type = 0){
				// 	$v->view_pemutihan_nilai_tagihan += $v->pemutihan_nilai_tagihan;
				// 	$v->sisa_nilai_tagihan = $v->pemutihan_nilai_tagihan - $v->view_pemutihan_nilai_tagihan;
				// }else{

				// }
				// if($v->pemutihan_nilai_denda_type = 0){
					
				// }		
				if($v->pemutihan_nilai_tagihan_type == 0){
					$view_pemutihan_nilai_tagihan += ((int)$v->pemutihan_nilai_tagihan);
					$sisa_nilai_tagihan += ((int)$v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
				}elseif($v->pemutihan_nilai_tagihan_type == 1){
					$view_pemutihan_nilai_tagihan += (((int)$v->pemutihan_nilai_tagihan)*((int)$sisa_nilai_tagihan)/100);
					$sisa_nilai_tagihan += ((int)$v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
				}else{
					$view_pemutihan_nilai_tagihan += 0;
					$sisa_nilai_tagihan += 0;
				}
				if($v->pemutihan_nilai_denda_type == 0){
					$view_pemutihan_nilai_denda += ((int)$v->pemutihan_nilai_denda);
					$sisa_nilai_denda += ((int)$v->nilai_denda) - $view_pemutihan_nilai_denda;
				}elseif($v->pemutihan_nilai_denda_type == 1){
					$view_pemutihan_nilai_denda += (((int)$v->pemutihan_nilai_denda)*((int)$sisa_nilai_denda)/100);
					$sisa_nilai_denda += ((int)$v->nilai_denda) - $view_pemutihan_nilai_denda;
				}else{
					$view_pemutihan_nilai_denda += 0;
					$sisa_nilai_denda += 0;
				}
				$sisa_nilai_tagihan = $sisa_nilai_tagihan>0?$sisa_nilai_tagihan:0;
				$sisa_nilai_denda = $sisa_nilai_denda>0?$sisa_nilai_denda:0;

				$v->view_pemutihan_nilai_tagihan	= $view_pemutihan_nilai_tagihan>$v->total_tanpa_ppn?$v->total_tanpa_ppn:$view_pemutihan_nilai_tagihan;
				$v->view_pemutihan_nilai_denda  	= $view_pemutihan_nilai_denda>$v->nilai_denda?$v->nilai_denda:$view_pemutihan_nilai_denda;
				$v->sisa_nilai_tagihan 				= $sisa_nilai_tagihan;
				$v->sisa_nilai_denda 				= $sisa_nilai_denda;
			
				array_push($tagihan_air,$v); 

			}else{
				$view_pemutihan_nilai_tagihan = 0;
				$view_pemutihan_nilai_denda = 0;
				$sisa_nilai_tagihan = 0;
				$sisa_nilai_denda = 0;
				if($v->pemutihan_nilai_tagihan_type == 0){
					$view_pemutihan_nilai_tagihan = ((int)$v->pemutihan_nilai_tagihan);
					$sisa_nilai_tagihan = ((int)$v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
				}else{
					$view_pemutihan_nilai_tagihan = (((int)$v->pemutihan_nilai_tagihan)*((int)$v->total_tanpa_ppn)/100);
					$sisa_nilai_tagihan = ((int)$v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
				}
				if($v->pemutihan_nilai_denda_type == 0){
					$view_pemutihan_nilai_denda = ((int)$v->pemutihan_nilai_denda);
					$sisa_nilai_denda = ((int)$v->nilai_denda) - $view_pemutihan_nilai_denda;
				}else{
					$view_pemutihan_nilai_denda = (((int)$v->pemutihan_nilai_denda)*((int)$v->nilai_denda)/100);
					$sisa_nilai_denda = ((int)$v->nilai_denda) - $view_pemutihan_nilai_denda;
				}
				$sisa_nilai_tagihan = $sisa_nilai_tagihan>0?$sisa_nilai_tagihan:0;
				$sisa_nilai_denda = $sisa_nilai_denda>0?$sisa_nilai_denda:0;

				// echo($view_pemutihan_nilai_tagihan."<br>");
			}
		}

		$tagihan_lingkungan_tmp = $tagihan_lingkungan;
		$tagihan_lingkungan = [];
		$view_pemutihan_nilai_tagihan = 0;
		$view_pemutihan_nilai_denda = 0;
		$sisa_nilai_tagihan = 0;
		$sisa_nilai_denda = 0;
		foreach ($tagihan_lingkungan_tmp as $k => $v) {
			if($v->periode!=$tagihan_lingkungan_tmp[(count($tagihan_lingkungan_tmp)-1)<($k+1)?(count($tagihan_lingkungan_tmp)-1):($k+1)]->periode || $k == count($tagihan_lingkungan_tmp)-1){
				if($v->pemutihan_nilai_tagihan_type == 0){
					$view_pemutihan_nilai_tagihan += ((int)$v->pemutihan_nilai_tagihan);
					$sisa_nilai_tagihan += ((int)$v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
				}else if($v->pemutihan_nilai_tagihan_type == 1){
					$view_pemutihan_nilai_tagihan += (((int)$v->pemutihan_nilai_tagihan)*((int)$sisa_nilai_tagihan)/100);
					$sisa_nilai_tagihan += ((int)$v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
				}
				if($v->pemutihan_nilai_denda_type == 0){
					$view_pemutihan_nilai_denda += ((int)$v->pemutihan_nilai_denda);
					$sisa_nilai_denda += ((int)$v->nilai_denda) - $view_pemutihan_nilai_denda;
				}elseif($v->pemutihan_nilai_denda_type == 1){
					$view_pemutihan_nilai_denda += (((int)$v->pemutihan_nilai_denda)*((int)$sisa_nilai_denda)/100);
					$sisa_nilai_denda += ((int)$v->nilai_denda) - $view_pemutihan_nilai_denda;
				}
				$sisa_nilai_tagihan = $sisa_nilai_tagihan>0?$sisa_nilai_tagihan:0;
				$sisa_nilai_denda = $sisa_nilai_denda>0?$sisa_nilai_denda:0;

				$v->view_pemutihan_nilai_tagihan	= $view_pemutihan_nilai_tagihan>$v->total_tanpa_ppn?$v->total_tanpa_ppn:$view_pemutihan_nilai_tagihan;
				$v->view_pemutihan_nilai_denda  	= $view_pemutihan_nilai_denda>$v->nilai_denda?$v->nilai_denda:$view_pemutihan_nilai_denda;
				$v->sisa_nilai_tagihan 				= $sisa_nilai_tagihan;
				$v->sisa_nilai_denda 				= $sisa_nilai_denda;
			
				array_push($tagihan_lingkungan,$v); 

			}else{
				$view_pemutihan_nilai_tagihan = 0;
				$view_pemutihan_nilai_denda = 0;
				$sisa_nilai_tagihan = 0;
				$sisa_nilai_denda = 0;
				if($v->pemutihan_nilai_tagihan_type == 0){
					$view_pemutihan_nilai_tagihan = ((int)$v->pemutihan_nilai_tagihan);
					$sisa_nilai_tagihan = ((int)$v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
				}else if($v->pemutihan_nilai_tagihan_type == 1){
					$view_pemutihan_nilai_tagihan = (((int)$v->pemutihan_nilai_tagihan)*((int)$v->total_tanpa_ppn)/100);
					$sisa_nilai_tagihan = ((int)$v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
				}
				if($v->pemutihan_nilai_denda_type == 0){
					$view_pemutihan_nilai_denda = ((int)$v->pemutihan_nilai_denda);
					$sisa_nilai_denda = ((int)$v->nilai_denda) - $view_pemutihan_nilai_denda;
				}elseif($v->pemutihan_nilai_denda_type == 1){
					$view_pemutihan_nilai_denda = (((int)$v->pemutihan_nilai_denda)*((int)$v->nilai_denda)/100);
					$sisa_nilai_denda = ((int)$v->nilai_denda) - $view_pemutihan_nilai_denda;
				}

				$sisa_nilai_tagihan = $sisa_nilai_tagihan>0?$sisa_nilai_tagihan:0;
				$sisa_nilai_denda = $sisa_nilai_denda>0?$sisa_nilai_denda:0;

				// echo($view_pemutihan_nilai_tagihan."<br>");
			}
		}
		if ($tagihan_air)
			$jumlah_tagihan_service++;
		if ($tagihan_lingkungan)
			$jumlah_tagihan_service++;
		$unit = (object) [];
		$unit->jumlah_tagihan_service = $jumlah_tagihan_service;
		$unit->tagihan_air = $tagihan_air;
		$unit->tagihan_lingkungan = $tagihan_lingkungan;
		echo json_encode($unit);

		// echo json_encode($this->m_pembayaran->ajax_get_tagihan($this->input->get("unit_id")));
	}
	public function ajax_diskon(){
		$data_diskon =$this->input->get("diskon");
		// var_dump($this->input->get("unit_id"));
		foreach ($data_diskon as $k => $v) {
			// var_dump("K ".$k);
			// var_dump("V ".$v);
			
			$diskon = $this->db	->select("diskon.*")
						->from("diskon")
						->join("unit",
								"(diskon.purpose_use_id = unit.purpose_use_id 
								or isnull(diskon.purpose_use_id,0) = 0)")
						->join("customer",
								"customer.id = unit.pemilik_customer_id
								AND (diskon.gol_diskon_id = customer.gol_diskon_id or diskon.gol_diskon_id = 0)")
						->where("(service_id = $k or service_jenis_id = 0)")
						->where("unit.id",$this->input->get("unit_id"))
						->where("unit.id",$this->input->get("unit_id"))
						->where("minimal_bulan <=",$v)
						->order_by("
								diskon.gol_diskon_id DESC,
								diskon.purpose_use_id DESC,
								diskon.service_id DESC,
								diskon.paket_service_id DESC,
								diskon.minimal_bulan DESC
								
						")->get()->row();
			// var_dump($diskon);
			// echo($this->db->last_query());	
			echo json_encode($diskon);

		}
	}
}
