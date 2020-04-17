<?php
defined('BASEPATH') or exit('No direct script access allowed');

class p_unit extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('core/m_unit');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
		// echo("<pre>");
        //     print_r($menu);
        // echo("</pre>");
		global $unit_id;
		$unit_id = $this->m_core->unit_id();
		//var_dump($this->session->userdata('name'));
	}
	public function test(){
		$this->load->view('core/header');
		$this->load->model('alert');
		$this->alert->css();

		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);

		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view("test");

		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function index($unit_id = 0)
	{
		$unit = (object) [];
		if ($unit_id != 0) {
			$unit = $this->db
				->select("unit.id, 
							CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,		'-',customer.name) 
							as text")
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
		$this->load->view('unit/dashboard', [
			"unit" => $unit,
			"project" => $GLOBALS['project']
		]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function ajax_save_kwitansi()
	{
		$pembayaran_id	= $this->input->post("pembayaran_id");
		$no_kwitansi	= $this->input->post("no_kwitansi");

		$kwitansi_referensi_id = $this->db->select("t_pembayaran_detail.kwitansi_referensi_id")
			->from("t_pembayaran")
			->join(
				"t_pembayaran_detail",
				"t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id"
			)
			->where("t_pembayaran.id", $pembayaran_id)
			->get()->row();
		$kwitansi_referensi_id = $kwitansi_referensi_id ? $kwitansi_referensi_id->kwitansi_referensi_id : 0;
		$this->db->set("no_kwitansi", $no_kwitansi);
		$this->db->where("id", $kwitansi_referensi_id);
		$this->db->update("kwitansi_referensi");
		echo json_encode(true);
	}
	public function ajax_save_void(){
		// echo json_encode($this->input->post('pembayaran_id'));
		echo(json_encode($this->m_unit->void_pembayaran($this->input->get('pembayaran_id'),$this->input->get('description'))));

		die;
		$return = (object)[];

        $return->status = true;        
		$return->message = "Data Void berhasil di request"; 
		
		$this->db->trans_start();

		$dataTmp = (object)[];
		// $dataTmp->id					= ;
		$dokumen_jenis =  $this->db->from('dokumen_jenis')->where('code','void_pembayaran')->get()->row();
		$dataTmp->status_approval		= 0;
		$dataTmp->create_user_id		= $this->m_core->user_id();
		$dataTmp->tgl_tambah			= date("Y-m-d h:i:s.000");
		$dataTmp->dokumen_jenis_id		= $dokumen_jenis->id;
		$dataTmp->source_id				= $this->input->post('pembayaran_id');
		$dataTmp->create_jabatan_id		= $this->m_core->jabatan()->id;
		$dataTmp->project_id			= $this->m_core->project()->id;
		$dataTmp->dokumen_code			= $dokumen_jenis->code;
		$dataTmp->dokumen_nilai			= $this->input->post('total');
		// $dataTmp->jarak_approval_closed	= 7;
		// $dataTmp->status_dokumen		= ;
		// $dataTmp->status_request		= ;
		// $dataTmp->jarak_request_closed	= ;
		// $dataTmp->tgl_closed			= ;
		// $dataTmp->tgl_approve			= ;
		// $dataTmp->approve_user_id		= ;
		// $dataTmp->approve_jabatan_id	= ;
		$dataTmp->tgl_kirim_email		= date("Y-m-d");
		$dataTmp->source_table			= 't_pembayaran';
		$this->db->insert("approval",$dataTmp);
		if ($this->db->trans_status() === FALSE){
            $return->message = "Data void gagal di request";
            $return->status = false;
        }else{
            $this->db->trans_commit();
        }
		echo json_encode($return);
	}
	public function get_ajax_unit()
	{
		$unit = 
			$this->db->select("concat('1.',unit.id) as id, CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) as text")
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
			->where("CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) like '%" . $this->input->get('data') . "%'")
			->limit(10)
			->get()->result();
		$data = (object)[];
		$data->result = [];
		$data->result[0] = (object)[];
		$data->result[0]->text = 'Project';
		$data->result[0]->children = $unit;
		$unit_virtual = 
			$this->db->select("concat('2.',unit_virtual.id) as id, CONCAT(unit_virtual.unit,'-',customer.name) as text")
			->from('unit_virtual')
			->join(
				'customer',
				'customer.id = unit_virtual.customer_id'
			)
			->where('unit_virtual.project_id', $GLOBALS['project']->id)
			->where("CONCAT(unit_virtual.unit,'-',customer.name) like '%" . $this->input->get('data') . "%'")
			->limit(10)
			->get()->result();
		$data->result[1] = (object)[];
		$data->result[1]->text = 'Non Project';
		$data->result[1]->children = $unit_virtual;
		
		
		echo json_encode($data->result);
	}
	public function get_ajax_unit2()
	{
		$unit = 
			$this->db->select("unit.id as id, CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) as text")
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
			->where("CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) like '%" . $this->input->get('data') . "%'")
			->limit(10)
			->get()->result();
		
		echo json_encode($unit);
	}
	public function get_ajax_unit_virtual_detail(){
		$project = $this->m_core->project();
		$unit_virtual_id = $this->input->get('unit_id');
		$unit_virtual = $this->db->select("
									unit_virtual.id,
									pemilik.name as pemilik, 
									pemilik.id as pemilik_id
									")
			->from('unit_virtual')
			->join('customer as pemilik', 
				'pemilik.id = unit_virtual.customer_id')
			->where('unit_virtual.id', $unit_virtual_id)
			->get()->row();
		$pemilik = $this->db->select("	
								isnull(name,' ') as name,
								isnull(email,' ') as email,
								isnull(mobilephone1,' ') as mobilephone1,
								isnull(mobilephone2,' ') as mobilephone2,
								isnull(address,' ') as address
							")
							->from("customer")
							->where("id",$unit_virtual->pemilik_id)
							->get()->row();
		$unit_virtual->pemilik = $pemilik;
		$unit_virtual->penghuni = $pemilik;
		$this->load->model("core/m_tagihan");
		$tagihan_layanan_lain = $this->m_tagihan->layanan_lain(
									$project->id,
									[
										'status_tagihan'=>[0,2,3,4],
										'unit_virtual_id'=>[$unit_virtual_id],
										'periode'=>date("Y-m-d")
									]);

		echo(json_encode($unit_virtual));		
	}
	public function get_ajax_unit_detail()
	{
		$project = $this->m_core->project();

		$periode_now = date("Y-m-01");
		$periode_pemakaian = date("Y-m-01", strtotime("-1 Months"));
		$unit_id = $this->input->get('unit_id');


		$unit = $this->db->select("
									CONCAT(project.source_id,kawasan.code,blok.code,'/',unit.no_unit) as uid,
									pemilik.name as pemilik, 
									penghuni.name as penghuni,
									pemilik.id as pemilik_id,
									penghuni.id as penghuni_id,
									unit.luas_bangunan,
									isnull(unit.luas_taman,0) as luas_taman,
									unit.luas_tanah,
									concat(jenis_golongan.code,' - ',jenis_golongan.description) as golongan,
									'Rumah' as purpose_use,
									'-' as type_unit,
									convert(varchar,tgl_st,103) as tgl_st
									")
			->from('unit')
			->join('customer as pemilik', 'pemilik.id = unit.pemilik_customer_id')
			->join('customer as penghuni', 'penghuni.id = unit.penghuni_customer_id','LEFT')
			->join('jenis_golongan', 'jenis_golongan.id = unit.gol_id','LEFT')
			->join('project','project.id = unit.project_id')
			->join('blok','blok.id = unit.blok_id')
			->join('kawasan','kawasan.id = blok.kawasan_id')
			->where('unit.id', $unit_id)
			->get()->row();

		// WHEN v_tagihan_air.periode > '$periode_now' THEN 0 -> kalau periode nya lebih dari periode saat ini, denda = 0
		$pemilik = $this->db->select("	isnull(name,' ') as name,
										isnull(email,' ') as email,
										isnull(mobilephone1,' ') as mobilephone1,
										isnull(mobilephone2,' ') as mobilephone2,
										isnull(address,' ') as address")
							->from("customer")
							->where("id",$unit->pemilik_id)
							->get()->row();
		$penghuni = $this->db->select("	isnull(name,' ') as name,
										isnull(email,' ') as email,
										isnull(mobilephone1,' ') as mobilephone1,
										isnull(mobilephone2,' ') as mobilephone2,
										isnull(address,' ') as address")
							->from("customer")
							->where("id",$unit->penghuni_id)
							->get()->row();
		$this->load->model("core/m_tagihan");
		$tagihan_air = $this->m_tagihan->air($project->id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>date("Y-m-d")]);
		$tagihan_lingkungan = $this->m_tagihan->lingkungan($project->id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>date("Y-m-d")]);
		
		// $view_pemutihan_nilai_denda = $view_pemutihan_nilai_denda<0?0:$view_pemutihan_nilai_denda;

		$kwitansi_per_service = $this->db
			->select("
					t_pembayaran.id as pembayaran_id,
					t_pembayaran.tgl_bayar,
					service_jenis.id as  service_jenis_id,
					service_jenis.code_default as code_service,
					service_jenis.name_default as name_service,
					sum(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)) as bayar,
					isnull(kwitansi_referensi.no_kwitansi,0) as no_kwitansi")
			->from("t_pembayaran")
			->join(
				"t_pembayaran_detail",
				"t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id"
			)
			->join(
				"service",
				"service.id = t_pembayaran_detail.service_id"
			)
			->join(
				"service_jenis",
				"service_jenis.id = service.service_jenis_id"
			)
			// ->where("no_kwitansi is null")
			->join(
				"kwitansi_referensi",
				"kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id"
			)
			->where("unit_id", $unit_id)
			->group_by("t_pembayaran.id,
												service_jenis.id,
												t_pembayaran.tgl_bayar ,
												service_jenis.code_default, 
												service_jenis.name_default,
												isnull(kwitansi_referensi.no_kwitansi,0),
												isnull(kwitansi_referensi.no_referensi,0)
												")
			->where("isnull(t_pembayaran.is_void,0)",0)
			->get()->result();
		$kwitansi_deposit = $this->db
			->select("
										t_deposit.id as deposit_id,
										t_deposit_detail.tgl_document as tgl_bayar,
										'Deposit' as name_service,
										t_deposit_detail.nilai as bayar,
										isnull(kwitansi_referensi.no_kwitansi,0) as no_kwitansi")
			->from("t_deposit")
			->join(
				"t_deposit_detail",
				"t_deposit_detail.t_deposit_id = t_deposit.id
											AND t_deposit_detail.nilai > 0"
			)
			->join(
				"kwitansi_referensi",
				"kwitansi_referensi.id = t_deposit_detail.kwitansi_referensi_id"
			)
			->where("t_deposit.customer_id", $unit->pemilik_id)
			->get()->result();
		$void_pembayaran =	$this->db	->select("
											t_pembayaran.id,
											CASE 
												WHEN bank.id is null THEN cara_pembayaran.name
												ELSE concat(cara_pembayaran.name,' - ', bank.name)
											END as cara_pembayaran,
											FORMAT (tgl_bayar, 'dd-MM-yyyy hh:mm:ss') as tgl_bayar,
											FORMAT(t_pembayaran_detail.bayar,'N0') as bayar,
											t_pembayaran.no_kwitansi,
											t_pembayaran.is_void")
										->from("t_pembayaran")
										->join("
												(
													SELECT 
														t_pembayaran_id, sum(bayar+bayar_deposit) as bayar
													FROM t_pembayaran_detail 
														group by t_pembayaran_id 
												) t_pembayaran_detail",
												"t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
										->join("cara_pembayaran",
												"cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
										->join("bank",
												"bank.id = cara_pembayaran.bank_id",
												"LEFT")
										->join("approval",
												"approval.dokumen_id = t_pembayaran.id
												AND approval.dokumen_jenis_id = 2
												AND approval.dokumen_code = 'void_pembayaran'",
												"LEFT")
										->where("t_pembayaran.unit_id",$unit_id)
										->where("(approval.approval_status_id != 1 or approval.approval_status_id is null)")
										->distinct()
										->get()->result();
		

		
		
		$jumlah_nilai_pokok 			= 0;
		$jumlah_nilai_ppn 				= 0;
		$jumlah_nilai_denda 			= 0;
		$jumlah_nilai_penalti 			= 0;
		$jumlah_nilai_pemutihan_pokok 	= 0;
		$jumlah_nilai_pemutihan_denda 	= 0;
		$jumlah_total 					= 0;
		foreach ($tagihan_lingkungan as $v) {
			$jumlah_nilai_pokok 			+= $v->total_tanpa_ppn;
			$jumlah_nilai_ppn 				+= $v->ppn;
			$jumlah_nilai_denda 			+= $v->nilai_denda;
			$jumlah_nilai_penalti 			+= $v->nilai_penalti;
			$jumlah_nilai_pemutihan_pokok 	+= $v->view_pemutihan_nilai_tagihan;
			$jumlah_nilai_pemutihan_denda 	+= $v->view_pemutihan_nilai_denda;
			$jumlah_total 					+= $v->total;
		}
		foreach ($tagihan_air as $v) {
			$jumlah_nilai_pokok 			+= $v->total_tanpa_ppn;
			$jumlah_nilai_ppn 				+= $v->ppn;
			$jumlah_nilai_denda 			+= $v->nilai_denda;
			$jumlah_nilai_penalti 			+= $v->nilai_penalti;
			$jumlah_nilai_pemutihan_pokok 	+= $v->view_pemutihan_nilai_tagihan;
			$jumlah_nilai_pemutihan_denda 	+= $v->view_pemutihan_nilai_denda;
			$jumlah_total 					+= $v->total;
		}

		
		$unit->jumlah_nilai_pokok 			= $jumlah_nilai_pokok;
		$unit->jumlah_nilai_ppn 			= $jumlah_nilai_ppn;
		$unit->jumlah_nilai_denda 			= $jumlah_nilai_denda;
		$unit->jumlah_nilai_penalti 		= $jumlah_nilai_penalti;
		$unit->jumlah_nilai_pemutihan_pokok	= $jumlah_nilai_pemutihan_pokok;
		$unit->jumlah_nilai_pemutihan_denda	= $jumlah_nilai_pemutihan_denda;
		$unit->jumlah_total 				= $jumlah_total;

		$unit->tagihan_air = $tagihan_air;
		$unit->tagihan_lingkungan = $tagihan_lingkungan;
		$unit->kwitansi 				= $kwitansi_per_service;
		$unit->kwitansi_deposit 		= $kwitansi_deposit;
		
		$unit->pemilik					= $pemilik;
		$unit->penghuni					= $penghuni;
		$unit->void_pembayaran 			= $void_pembayaran;
		echo json_encode($unit);
		// echo json_encode($tagihan_air);
		// echo("<pre>");
		// print_r($unit);
		// echo("</pre>");

	}
	// public function test()
	// {
	// 	$this->m_unit->test();
	// }
}
