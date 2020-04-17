<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_pemutihan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if (!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('Transaksi/m_meter_air');
		$this->load->model('Transaksi/m_pemutihan');
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
		$data = $this->db->select("
								id,
								periode_awal,
								periode_akhir,
								masa_awal,
								masa_akhir,
								[file]
							")
						->from("v_pemutihan")
						->distinct()
						->get()->result();
		$this->load->view('core/header');
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Transaksi Service > Pemutihan', 'subTitle' => 'List']);
		$this->load->view('proyek/transaksi/pemutihan/view', ['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function add()
	{
		$project = $this->m_core->project();

		$no = $this->db->select("count(*) as c")->from("pemutihan")->where("project_id",$project->id)->get()->row()->c+1;
		$kode = "DOK/PMTHN/$project->code/$no";

		// $kode_cust = $this->m_core->project()->code."/CUST/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_meter_air->last_id()+1);
		// $dataPT = $this->m_meter_air->getPT();
		$kawasan = $this->m_pemutihan->get_kawasan();
		$service = $this->m_pemutihan->get_service();
		$this->load->view('core/header');
		$this->load->model('alert');
		$this->alert->css();
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header', ['title' => 'Transaksi Service > Pemutihan', 'subTitle' => 'Add']);
		$this->load->view(
			'proyek/transaksi/pemutihan/add',
			[
				'kawasan'	=> $kawasan,
				'service'	=> $service,
				'kode'		=> $kode
			]
		);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}

	public function edit()
	{
		$status = 0;
		if ($this->input->post('pt_id')) {
			$this->load->model('alert');

			$status = $this->m_meter_air->edit([
				'id' 			=> $this->input->get('id'),
				'pt_id' 		=> $this->input->post('pt_id'),
				'unit' 			=> $this->input->post('unit'),
				'name' 			=> $this->input->post('name'),
				'address' 		=> $this->input->post('alamat_domisili'),
				'email' 		=> $this->input->post('email'),
				'ktp' 			=> $this->input->post('nik'),
				'ktp_address' 	=> $this->input->post('alamat_ktp'),
				'mobilephone1' 	=> $this->input->post('mobile_phone_1'),
				'mobilephone2' 	=> $this->input->post('mobile_phone_2'),
				'homephone' 	=> $this->input->post('home_phone'),
				'officephone' 	=> $this->input->post('office_phone'),
				'npwp_no' 		=> $this->input->post('nomor_npwp'),
				'npwp_name' 	=> $this->input->post('nama_npwp'),
				'npwp_address' 	=> $this->input->post('alamat_npwp'),
				'description' 	=> $this->input->post('keterangan'),
				'active' 		=> $this->input->post('status'),
				'delete' 		=> 0
			]);
			$this->alert->css();
		}

		if ($this->m_meter_air->cek($this->input->get('id'))) {
			$this->load->model('m_log');
			$data = $this->m_log->get('customer', $this->input->get('id'));
			$this->load->view('core/header');

			$kode_cust = "CG/CUST/" . date("Y") . "/" . $this->m_core->numberToRomanRepresentation($this->m_core->project()->id) . "/" . ($this->m_meter_air->last_id() + 1);
			$dataPT = $this->m_meter_air->getPT();
			$dataSelect = $this->m_meter_air->getSelect($this->input->get('id'));
			$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header', ['title' => 'Transaksi Service > Pemutihan', 'subTitle' => 'Edit']);
			$this->load->view('proyek/transaksi/meter_air//edit', ['data' => $data, 'dataPT' => $dataPT, 'dataSelect' => $dataSelect]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
		} else {
			redirect(site_url() . '/P_master_mappingCoa');
		}

		if ($status == 'success') {
			$this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
		} elseif ($status == 'double') {
			$this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
		}
	}
	public function ajax_get_blok()
	{
		echo json_encode($this->m_pemutihan->get_blok($this->input->get('id')));
	}
	public function ajax_get_unit()
	{
		echo json_encode($this->m_pemutihan->get_unit($this->input->get('blok'), $this->input->get('kawasan'), $this->input->get('periode_awal'), $this->input->get('periode_akhir'), $this->input->get("metode_tagihan[]")));
	}
	public function ajax_save_meter()
	{
		echo json_encode($this->m_meter_air->ajax_save_meter($this->input->get('meter'), $this->input->get('periode'), $this->input->get('unit_id')));
	}
	function do_upload()
	{
		$config['upload_path'] = "./assets/images"; //path folder file upload
		$config['allowed_types'] = 'gif|jpg|png'; //type file yang boleh di upload
		$config['encrypt_name'] = TRUE; //enkripsi file name upload

		$this->load->library('upload', $config); //call library upload 
		if ($this->upload->do_upload("file",null)) { //upload file
			$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload

			$judul = $this->input->post('judul'); //get judul image
			$image = $data['upload_data']['file_name']; //set file name ke variable image

			$result = $this->m_upload->simpan_upload($judul, $image); //kirim value ke model m_upload
			echo json_decode($result);
		}
	}
	public function save()
	{	
		echo(json_encode($this->m_pemutihan->save($this->input->get(),'a.jpg')));
		die;

		$config['upload_path'] = "./files/pemutihan/"; //path folder file upload
		$config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|xls|xlsx'; //type file yang boleh di upload
		$config['encrypt_name'] = TRUE; //enkripsi file name upload
		$this->load->library('upload', $config); //call library upload 
		if ($this->upload->do_upload("file")) { //upload file
			$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
			$nama_file = $data['upload_data']['file_name']; //set file name ke variable image
			echo(json_encode($this->m_pemutihan->save($this->input->get(),$nama_file)));

			// $result = $this->m_upload->simpan_upload($judul, $image); //kirim value ke model m_upload
		}else{
			echo(json_encode(false));
			// echo("File Tidak Bisa di Uploud");
		}
	}
}
