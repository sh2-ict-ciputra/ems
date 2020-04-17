<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_deposit extends CI_Controller
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
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > Pembayaran Tagihan','subTitle' => 'List']);
		$this->load->view('Proyek/Transaksi/Deposit/view');
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function add($unit_id = 0)
	{
		// $query = $this->db->query('ALTER TABLE t_pembayaran 
		// ADD no_kwitansi VARCHAR (50);');
		// $query->result();
		$customer = (object)[];
		if($unit_id != 0){
			$customer = $this->db->select("customer.id, customer.name")
						->from("unit")	
						->join("customer",
								"customer.id = unit.pemilik_customer_id")
						->where("unit.id",$unit_id)
						->get()->row();
		}else{
			$customer->id = 0;
		}
		$project = $this->m_core->project();
		$status = 0;

		$data = $this->m_pembayaran->get_all_unit();
		$cara_pembayaran = 
			$this->db
			->select("	cara_pembayaran.id, 
						CASE
							WHEN bank.id is not null THEN concat(cara_pembayaran.name, ' - ', bank.name) 
							ELSE cara_pembayaran.name
						END as name,
						cara_pembayaran.code")
			->from("cara_pembayaran")
			->join("bank",
					"bank.id = cara_pembayaran.bank_id",
					"LEFT")
			->where("cara_pembayaran.delete",0)
			->where("cara_pembayaran.project_id",$project->id)
			->get()->result();
		$this->load->view('core/header');
		$this->load->model('alert');
		$this->alert->css();

		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > Pembayaran Tagihan','subTitle' => 'List']);
		$this->load->view('Proyek/Transaksi/Deposit/add',[
			"data" 				=> $data,
			"cara_pembayaran"	=> $cara_pembayaran,
			"customer" 			=> $customer,
			"unit_id" 			=> $unit_id
		]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
		// if ($status == 'success') {
        //     $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Saldo Deposit Berhasil di Tambah', 'type' => 'success']);
        // }elseif($status == 'failed'){
        //     $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Saldo Deposit Gagal di Tambah', 'type' => 'danger']);
		// }
	}
	public function add_modal($unit_id = 0)
	{
		// $query = $this->db->query('ALTER TABLE t_pembayaran 
		// ADD no_kwitansi VARCHAR (50);');
		// $query->result();
		$customer = (object)[];
		if($unit_id != 0){
			$customer = $this->db->select("customer.id, customer.name")
						->from("unit")	
						->join("customer",
								"customer.id = unit.pemilik_customer_id")
						->where("unit.id",$unit_id)
						->get()->row();
		}else{
			$customer->id = 0;
		}
		$project = $this->m_core->project();
		$status = 0;

		$data = $this->m_pembayaran->get_all_unit();
		$cara_pembayaran = 
			$this->db
			->select("	cara_pembayaran.id, 
						CASE
							WHEN bank.id is not null THEN concat(cara_pembayaran.name, ' - ', bank.name) 
							ELSE cara_pembayaran.name
						END as name,
						cara_pembayaran.code")
			->from("cara_pembayaran")
			->join("bank",
					"bank.id = cara_pembayaran.bank_id",
					"LEFT")
			->where("cara_pembayaran.delete",0)
			->where("cara_pembayaran.project_id",$project->id)
			->get()->result();
		$this->load->view('core/header_modal');
		$this->load->model('alert');
		$this->alert->css();

		$this->load->view('core/top_bar_modal',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header_modal',['title' => 'Transaksi Service > Pembayaran Tagihan','subTitle' => 'List']);
		$this->load->view('Proyek/Transaksi/Deposit/add',[
			"data" 				=> $data,
			"cara_pembayaran"	=> $cara_pembayaran,
			"customer" 			=> $customer,
			"unit_id" 			=> $unit_id
		]);
		$this->load->view('core/body_footer_modal');
		$this->load->view('core/footer_modal');
		// if ($status == 'success') {
        //     $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Saldo Deposit Berhasil di Tambah', 'type' => 'success']);
        // }elseif($status == 'failed'){
        //     $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Saldo Deposit Gagal di Tambah', 'type' => 'danger']);
		// }
	}
	public function ajax_get_tagihan(){
		$unit_id = $this->input->post("unit_id");
		$periode_now = date("Y-m-01");
		$periode_pemakaian = date("Y-m-01",strtotime("-1 Months"));
		
		$unit = $this->db->select("
									pemilik.name as pemilik, 
									penghuni.name as penghuni,
									unit.luas_bangunan,
									isnull(unit.luas_taman,0) as luas_taman,
									unit.luas_tanah,
									concat(jenis_golongan.code,' - ',jenis_golongan.description) as golongan,
									'Rumah' as purpose_use,
									'-' as type_unit,
									convert(varchar,tgl_st,103) as tgl_st
									")
							->from('unit')
							->join('customer as pemilik','pemilik.id = unit.pemilik_customer_id')
							->join('customer as penghuni','penghuni.id = unit.penghuni_customer_id')
							->join('jenis_golongan','jenis_golongan.id = unit.gol_id')
							
                            ->where('unit.id',$unit_id)
							->get()->row();

		$tagihan_air = $this->db->select("
								v_tagihan_air.tagihan_id,
								'Air' as service,
								v_tagihan_air.periode,
								isnull(CASE 
								WHEN v_tagihan_air.periode = '$periode_pemakaian' THEN v_tagihan_air.total
								ELSE 0
								END,0) as nilai_tagihan,
								isnull(CASE 
									WHEN v_tagihan_air.periode < '$periode_pemakaian' THEN v_tagihan_air.total
									ELSE 0
								END,0) as nilai_tunggakan,
								isnull(CASE
									WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
									WHEN v_tagihan_air.periode > '2019-5-01' THEN 0
									ELSE
									CASE
										
										WHEN v_tagihan_air.denda_jenis_service = 1 THEN
										v_tagihan_air.denda_nilai_service 
										WHEN v_tagihan_air.denda_jenis_service = 2 THEN
										v_tagihan_air.denda_nilai_service* DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
										WHEN v_tagihan_air.denda_jenis_service = 3 THEN
										( v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) * DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
									END 
									END,0) AS nilai_denda,
								isnull(CASE 
								WHEN v_tagihan_air.periode = '$periode_now' THEN v_tagihan_air.total
								ELSE 0
								END,0) +
								isnull(CASE 
								WHEN v_tagihan_air.periode < '$periode_now' THEN v_tagihan_air.total
								ELSE 0
								END,0) +
								isnull(CASE
									WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
									WHEN v_tagihan_air.periode > '2019-5-01' THEN 0
									ELSE
									CASE
										WHEN v_tagihan_air.denda_jenis_service = 1 THEN
										v_tagihan_air.denda_nilai_service 
										WHEN v_tagihan_air.denda_jenis_service = 2 THEN
										v_tagihan_air.denda_nilai_service* DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
										WHEN v_tagihan_air.denda_jenis_service = 3 THEN
										( v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) * DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
									END 
								END,0) AS total")
							->from("v_tagihan_air")
							->where("v_tagihan_air.status_tagihan = 0")
							->where("v_tagihan_air.unit_id = $unit_id")
							->get()->result();
			$tagihan_lingkungan = $this->db->select("
								v_tagihan_lingkungan.tagihan_id,
								'lingkungan' as service,
								v_tagihan_lingkungan.periode,
								isnull(CASE 
								WHEN v_tagihan_lingkungan.periode = '$periode_pemakaian' THEN v_tagihan_lingkungan.total
								ELSE 0
								END,0) as nilai_tagihan,
								isnull(CASE 
								WHEN v_tagihan_lingkungan.periode < '$periode_pemakaian' THEN v_tagihan_lingkungan.total
								ELSE 0
								END,0) as nilai_tunggakan,
								isnull(CASE
									WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
									WHEN v_tagihan_lingkungan.periode > '2019-5-01' THEN 0
									ELSE
									CASE
										
										WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN
										v_tagihan_lingkungan.denda_nilai_service 
										WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN
										v_tagihan_lingkungan.denda_nilai_service* DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) 
										WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN
										( v_tagihan_lingkungan.denda_nilai_service* v_tagihan_lingkungan.total/ 100 ) * DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) 
									END 
									END,0) AS nilai_denda,
								isnull(CASE 
								WHEN v_tagihan_lingkungan.periode = '$periode_now' THEN v_tagihan_lingkungan.total
								ELSE 0
								END,0) +
								isnull(CASE 
								WHEN v_tagihan_lingkungan.periode < '$periode_now' THEN v_tagihan_lingkungan.total
								ELSE 0
								END,0) +
								isnull(CASE
									WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
									WHEN v_tagihan_lingkungan.periode > '2019-5-01' THEN 0
									ELSE
									CASE
										WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN
										v_tagihan_lingkungan.denda_nilai_service 
										WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN
										v_tagihan_lingkungan.denda_nilai_service* DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) 
										WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN
										( v_tagihan_lingkungan.denda_nilai_service* v_tagihan_lingkungan.total/ 100 ) * DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) 
									END 
								END,0) AS total
							")
							->from("v_tagihan_lingkungan")
							->where("v_tagihan_lingkungan.status_tagihan = 0")
							->where("v_tagihan_lingkungan.unit_id = $unit_id")
							->get()->result();
		$jumlah_tagihan_service = 0;
		$jumlah_tunggakan_bulan = 0;
		$jumlah_tunggakan = 0;
		$jumlah_denda = 0;
		$jumlah_penalti = 0;
		$jumlah_tagihan = 0;

		foreach ($tagihan_air as $v) {
			if($v->nilai_tagihan == "Telah Jadi Tunggakan"){
				$jumlah_tunggakan_bulan++;
				$jumlah_tunggakan+=$v->nilai_tunggakan;
				$jumlah_denda+=$v->nilai_denda;
			
			}else{
				$jumlah_tagihan+=$v->nilai_tagihan;
			}
		}

		foreach ($tagihan_lingkungan as $v) {
			if($v->nilai_tagihan == "Telah Jadi Tunggakan"){
				$jumlah_tunggakan_bulan++;
				$jumlah_tunggakan+=$v->nilai_tunggakan;
				$jumlah_denda+=$v->nilai_denda;
			
			}else{
				$jumlah_tagihan+=$v->nilai_tagihan;
				// $jumlah_penalti+=$v->nilai_penalti;
			}
		}
		if($tagihan_air)
			$jumlah_tagihan_service++;
		if($tagihan_lingkungan)
			$jumlah_tagihan_service++;

		$unit->jumlah_tagihan_service = $jumlah_tagihan_service;
		$unit->tagihan_air = $tagihan_air;
		$unit->tagihan_lingkungan = $tagihan_lingkungan;
		echo json_encode($unit);
		
		// echo json_encode($this->m_pembayaran->ajax_get_tagihan($this->input->get("unit_id")));
	}public function get_ajax_customer(){
		$data = $this->input->get("data");        
		echo json_encode($this->m_deposit->ajax_get_customer($data));
	}public function ajax_get_deposit($customer_id){
		echo json_encode($this->m_deposit->ajax_get_deposit($customer_id));
	}public function ajax_save(){
		$data = (object)[];
		$data->project_id =	$this->m_core->project()->id;
		$data->tambah_deposit = $this->input->post("tambah_deposit");
		$data->customer_id = $this->input->post("customer_id");
		$data->cara_pembayaran_id = $this->input->post("cara_pembayaran_id");
		$data->deskripsi = $this->input->post("deskripsi");
		$data->no_referensi = $this->m_deposit->get_no_referensi();
		
		$seconds = microtime(true) / 1000;
		$remainder = round($seconds - ($seconds >> 0), 3) * 1000;
		
		$data->tgl_document = date("Y-m-d H:i:s.000");
		$data->tgl_tambah = date("Y-m-d H:i:s.000");

		$data->user_id = $this->db->select("id")
		->from("user")
		->where("username",$this->session->userdata["username"])
		->get()->row()->id;
		if($this->m_deposit->save($data))
			echo json_encode(true);
		else
			echo json_encode(false);
	}

}