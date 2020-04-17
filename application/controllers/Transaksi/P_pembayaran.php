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
		$this->load->model('transaksi/m_deposit');
		
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();

		ini_set('memory_limit','256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');

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
		$jenis_unit = $this->input->get('jenis_unit')?$this->input->get('jenis_unit'):1;

		$project = $this->m_core->project();
		$unit = (object) [];
		$unit_virtual = (object)[];
		$this->load->model('Setting/m_parameter_project');
		$max_backdate_pembayaran = $this->m_parameter_project->get($project->id,"max_backdate_pembayaran");
		$backdate = date('Y-m-d',strtotime(date("Y-m-d") . "-".($max_backdate_pembayaran)." days"));
		$data = $this->m_pembayaran->get_all_unit();
		$cara_pembayaran = $this->db
		->select("
					case 
						when isnull(bank_id,0) = 0 THEN
							cara_pembayaran.id
						else jenis_cara_pembayaran_id 
					end as id,
					jenis_cara_pembayaran_id,
					cara_pembayaran_jenis.code,
					cara_pembayaran_jenis.name,
					sum(biaya_admin) as biaya_admin")
		->from("cara_pembayaran")
		->join('cara_pembayaran_jenis',
				"cara_pembayaran_jenis.id = cara_pembayaran.jenis_cara_pembayaran_id")
		->where("delete", 0)
		->where("project_id",$project->id)
		->group_by("case 
					when isnull(bank_id,0) = 0 THEN
						cara_pembayaran.id
					else jenis_cara_pembayaran_id 
					end,
					jenis_cara_pembayaran_id,
					cara_pembayaran_jenis.code,
					cara_pembayaran_jenis.name")
		->distinct()

		->get()->result();
		$bank = $this->db->select("
								cara_pembayaran.jenis_cara_pembayaran_id,
								cara_pembayaran.id,
								cara_pembayaran.biaya_admin,
								CONCAT(bank.name,' (',cara_pembayaran.name,')') as name")
							->from("cara_pembayaran")
							->join("bank",
									"bank.id = cara_pembayaran.bank_id")
							->where("cara_pembayaran.delete", 0)
							->where("cara_pembayaran.project_id",$project->id)
							->where("isnull(bank.id,0) != 0")
							->distinct()
							->get()->result();					
		
		if($jenis_unit == 1){
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
		}elseif($jenis_unit == 2){
			$unit_virtual_id = isset($unit_id)?$unit_id:0;
			if ($unit_virtual_id != 0) {
				$unit_virtual = $this->db
									->select("unit_virtual.id, CONCAT(unit_virtual.unit,'-',customer.name) as text")
									->from('unit_virtual')
									->join('customer',
											'customer.id = unit_virtual.customer_id'
											)
									->where('unit_virtual.project_id', $project->id)
									->where("unit_virtual.id", $unit_virtual_id)
									->get()->row();
			} else {
				$unit_virtual->id = 0;
			}
			$this->load->view('core/header');
			$this->load->model('alert');
			$this->alert->css();

			$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
			$this->load->view('core/body_header', ['title' => 'Transaksi Service > Pembayaran Tagihan', 'subTitle' => 'List']);
			$this->load->view('Proyek/Transaksi/Pembayaran/add2', [
				"data" 				=> $data,
				"cara_pembayaran"	=> $cara_pembayaran,
				"unit_virtual"		=> $unit_virtual,
				"unit_virtual_id"	=> $unit_virtual_id,
				"bank"				=> $bank,
				"backdate"			=> $backdate
			]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
		}
	}
	public function add_modal($unit_id = 0)
	{
		$jenis_unit = $this->input->get('jenis_unit')?$this->input->get('jenis_unit'):1;

		$project = $this->m_core->project();
		$unit = (object) [];
		$unit_virtual = (object)[];
		$this->load->model('Setting/m_parameter_project');
		$max_backdate_pembayaran = $this->m_parameter_project->get($project->id,"max_backdate_pembayaran");
		$backdate = date('Y-m-d',strtotime(date("Y-m-d") . "-".($max_backdate_pembayaran)." days"));
		$data = $this->m_pembayaran->get_all_unit();
		$cara_pembayaran = $this->db
		->select("
					case 
						when isnull(bank_id,0) = 0 THEN
							cara_pembayaran.id
						else jenis_cara_pembayaran_id 
					end as id,
					jenis_cara_pembayaran_id,
					cara_pembayaran_jenis.code,
					cara_pembayaran_jenis.name,
					sum(biaya_admin) as biaya_admin")
		->from("cara_pembayaran")
		->join('cara_pembayaran_jenis',
				"cara_pembayaran_jenis.id = cara_pembayaran.jenis_cara_pembayaran_id")
		->where("delete", 0)
		->where("project_id",$project->id)
		->group_by("case 
					when isnull(bank_id,0) = 0 THEN
						cara_pembayaran.id
					else jenis_cara_pembayaran_id 
					end,
					jenis_cara_pembayaran_id,
					cara_pembayaran_jenis.code,
					cara_pembayaran_jenis.name")
		->distinct()

		->get()->result();
		$bank = $this->db->select("
								cara_pembayaran.jenis_cara_pembayaran_id,
								cara_pembayaran.id,
								cara_pembayaran.biaya_admin,
								CONCAT(bank.name,' (',cara_pembayaran.name,')') as name")
							->from("cara_pembayaran")
							->join("bank",
									"bank.id = cara_pembayaran.bank_id")
							->where("cara_pembayaran.delete", 0)
							->where("cara_pembayaran.project_id",$project->id)
							->where("isnull(bank.id,0) != 0")
							->distinct()
							->get()->result();
		
		if($jenis_unit == 1){
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
		
		
			$this->load->view('core/header');
			$this->load->model('alert');
			$this->alert->css();

			$this->load->view('core/top_bar_modal', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
			$this->load->view('core/body_header_modal', ['title' => 'Transaksi Service > Pembayaran Tagihan', 'subTitle' => 'List']);
			$this->load->view('Proyek/Transaksi/Pembayaran/add', [
				"data" 				=> $data,
				"cara_pembayaran"	=> $cara_pembayaran,
				"unit"				=> $unit,
				"unit_id" 			=> $unit_id,
				"bank"				=> $bank,
				"backdate"			=> $backdate
			]);
			$this->load->view('core/body_footer_modal');
			$this->load->view('core/footer_modal');
		}elseif($jenis_unit == 2){
			$unit_virtual_id = isset($unit_id)?$unit_id:0;
			if ($unit_virtual_id != 0) {
				$unit_virtual = $this->db
									->select("unit_virtual.id, CONCAT(unit_virtual.unit,'-',customer.name) as text")
									->from('unit_virtual')
									->join('customer',
											'customer.id = unit_virtual.customer_id'
											)
									->where('unit_virtual.project_id', $project->id)
									->where("unit_virtual.id", $unit_virtual_id)
									->get()->row();
			} else {
				$unit_virtual->id = 0;
			}
			$this->load->view('core/header');
			$this->load->model('alert');
			$this->alert->css();

			$this->load->view('core/top_bar_modal', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
			$this->load->view('core/body_header_modal', ['title' => 'Transaksi Service > Pembayaran Tagihan', 'subTitle' => 'List']);
			$this->load->view('Proyek/Transaksi/Pembayaran/add2', [
				"data" 				=> $data,
				"cara_pembayaran"	=> $cara_pembayaran,
				"unit_virtual"		=> $unit_virtual,
				"unit_virtual_id"	=> $unit_virtual_id,
				"bank"				=> $bank,
				"backdate"			=> $backdate
			]);
			$this->load->view('core/body_footer_modal');
			$this->load->view('core/footer_modal');
		}
	}
	public function add_modal_bu($unit_id = 0)
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
		$this->load->view('core/header_modal');
		$this->load->model('alert');
		$this->alert->css();

		// $this->load->view('core/side_bar_modal', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar_modal', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header_modal', ['title' => 'Transaksi Service > Pembayaran Tagihan', 'subTitle' => 'Add']);
		$this->load->view('Proyek/Transaksi/Pembayaran/add', [
			"data" 				=> $data,
			"cara_pembayaran"	=> $cara_pembayaran,
			"unit"				=> $unit,
			"unit_id" 			=> $unit_id,
			"bank"				=> $bank,
			"backdate"			=> $backdate
		]);
		$this->load->view('core/body_footer_modal');
		$this->load->view('core/footer_modal');
	}
	public function generate_kwitansi(){
		echo($this->m_pembayaran->generate_kwitansi(2));
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
	public function ajax_get_unit_virtual()
	{
		$data = $this->input->get("data");	 
		echo (json_encode($this->m_pembayaran->ajax_get_unit_virtual($data)));
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
	
	public function ajax_get_tagihan_unit_virtual()
	{
		$project = $this->m_core->project();
		$unit_virtual_id = $this->input->post("unit_virtual_id");
		$this->load->model("core/m_tagihan");
		$jumlah_tagihan_service = 0;
		$layanan_lain = $this->m_tagihan->layanan_lain($project->id,['unit_virtual_id'=>[$unit_virtual_id]]);

		if ($layanan_lain)
			$jumlah_tagihan_service++;

		$unit = (object) [];
		$unit->jumlah_tagihan_service = $jumlah_tagihan_service;
		$unit->tagihan_layanan_lain = $layanan_lain;
		// $unit->tagihan_tvi = $tagihan_tvi_registrasi;

		echo json_encode($unit);
	}
	
	public function ajax_get_tagihan()
	{
		$project = $this->m_core->project();
		$dateForm = $this->input->post("date");
		$unit_id = $this->input->post("unit_id");
		if($dateForm)
			$periode_now = substr($dateForm,6,4)."-".substr($dateForm,3,2)."-".substr($dateForm,0,2);
		else
			$periode_now = date("Y-m-d");
		$periode_pemakaian = date("Y-m-01", strtotime("-1 Months"));
		$this->load->model("core/m_tagihan");
		$tagihan_air = $this->m_tagihan->air($project->id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>$periode_now]);
		$tagihan_lingkungan = $this->m_tagihan->lingkungan($project->id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>$periode_now]);
		
		// $tagihan_loi_registrasi = $this->db->select("
		// 							t_tagihan_loi.id as tagihan_id,
		// 							t_tagihan_loi.periode,
		// 							t_loi_registrasi.total as nilai_tagihan,
		// 							'LOI <br> Registrasi' as service,
		// 							5 as service_jenis_id,
		// 							service.id as service_id								
		// 							")
		// 						->from("t_tagihan_loi")
		// 						->join("t_loi_registrasi",
		// 								"t_loi_registrasi.id = t_tagihan_loi.t_loi_registrasi_id
		// 								AND t_tagihan_loi.tipe = 1")
		// 						->join("service",
		// 								"service.service_jenis_id = 5
		// 								AND service.project_id = t_tagihan_loi.project_id")
		// 						->where("t_tagihan_loi.status_tagihan",0)
		// 						->where("t_tagihan_loi.unit_id",$unit_id)
		// 						->get()->result();


		//loi yang depositnya kurang akibat ada penambahan item di survey nya

		// $tagihan_loi_deposit = $this->db	->select("
		// 											t_tagihan_loi.id as tagihan_id,
		// 											t_tagihan_loi.periode,
		// 											CASE
		// 												WHEN tipe = 2 THEN deposit_pemakaian - deposit_masuk
		// 												WHEN tipe = 3 THEN 
		// 													CASE 
		// 														WHEN (deposit_pemakaian - deposit_masuk) > 0 THEN deposit_pemakaian2
		// 														ELSE deposit_pemakaian2 + (deposit_pemakaian - deposit_masuk)
		// 													END
		// 												ELSE 0
		// 											END as nilai_tagihan,
		// 											CASE
		// 												WHEN tipe = 2 THEN 'LOI <br> Kurang Deposit 1'
		// 												WHEN tipe = 3 THEN 'LOI <br> Kurang Deposit 2'
		// 												ELSE ''
		// 											END as service,
		// 											5 as service_jenis_id,
		// 											service.id as service_id
		// 										")
		// 									->from("t_tagihan_loi")
		// 									->join("service",
		// 											"service.service_jenis_id = 5
		// 											AND service.project_id = t_tagihan_loi.project_id")
		// 									->join("t_loi_deposit",
		// 											"t_loi_deposit.t_loi_registrasi_id = t_tagihan_loi.t_loi_registrasi_id")
		// 									->where("unit_id",$unit_id)
		// 									->where("status_tagihan",0)
		// 									->where("(t_tagihan_loi.tipe = 2 or t_tagihan_loi.tipe = 3)")
		// 									->get()->result();
		// $tagihan_tvi_registrasi = $this->db->select("
		// 										t_tagihan_tvi.id as tagihan_id,
		// 										t_tagihan_tvi.periode,
		// 										CASE
		// 											WHEN tipe = 0 THEN 'TVI <br> Registrasi'
		// 											WHEN tipe = 1 THEN 'TVI <br> Survei'
		// 										END as service,
		// 										t_tagihan_tvi.total as nilai_tagihan,
		// 										service.id as service_id,
		// 										service.service_jenis_id as service_jenis_id
		// 										")
		// 									->from("t_tagihan_tvi")
		// 									->join("t_tvi_registrasi",
		// 											"t_tvi_registrasi.id = t_tagihan_tvi.t_tvi_registrasi_id")
		// 									->join("service",
		// 											"service.service_jenis_id = 4
		// 											AND service.project_id = t_tvi_registrasi.project_id")
		// 									->where("t_tagihan_tvi.tipe < 2")
		// 									->where("t_tvi_registrasi.unit_id",$unit_id)
		// 									->where("t_tagihan_tvi.status_tagihan",0)
		// 									->get()->result();
											
		// $tagihan_layanan_lain = $this->db->select("");
		$jumlah_tagihan_service = 0;
		$layanan_lain = $this->m_tagihan->layanan_lain($project->id,['unit_id'=>$unit_id]);

		if ($tagihan_air)
			$jumlah_tagihan_service++;
		if ($tagihan_lingkungan)
			$jumlah_tagihan_service++;
		// if ($tagihan_loi_registrasi)
		// 	$jumlah_tagihan_service++;
		if ($layanan_lain)
			$jumlah_tagihan_service++;

		$unit = (object) [];
		$unit->jumlah_tagihan_service = $jumlah_tagihan_service;
		$unit->tagihan_air = $tagihan_air;
		$unit->tagihan_lingkungan = $tagihan_lingkungan;
		$unit->tagihan_layanan_lain = $layanan_lain;
		// $unit->tagihan_tvi = $tagihan_tvi_registrasi;


		// $unit->tagihan_loi = $tagihan_loi_registrasi;
		// $unit->tagihan_loi = $tagihan_loi_deposit;
		// $unit->tagihan_loi = (array_merge($tagihan_loi_registrasi,$tagihan_loi_deposit));
		echo json_encode($unit);

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
						->where("((GETDATE()  BETWEEN periode_berlaku_awal and periode_berlaku_akhir) or (periode_berlaku_awal is null and periode_berlaku_akhir is null))")
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
