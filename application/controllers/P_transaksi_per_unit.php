<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_transaksi_per_unit extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('transaksi/m_meter_air');
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
		$data = $this->m_meter_air->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > Pencatatan Meter Air > Meter Air','subTitle' => 'List']);
		$this->load->view('proyek/transaksi/transaksi_per_unit/view',['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function add()
	{
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Transaksi Service > Pencatatan Meter Air > Meter Air', 'subTitle' => 'Add']);
		$this->load->view('proyek/transaksi/meter_air/add',
			[
				'data' => $this->m_meter_air->getInfoAdd()
			]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function getInfoUnit(){
		$id = $this->input->get('id');
		echo json_encode($this->m_meter_air->getInfoUnit($id));
	}
	public function save()
	{	
		$status = $this->m_meter_air->save([
			'unit_id' 			=> $this->input->post('unit'),
			'periode' 		=> date('Y/m/d', strtotime($this->input->post('bulan'). ' 1 '. $this->input->post('tahun'))),
			'keterangan' 	=> $this->input->post('keterangan'),
			'meter' 		=> $this->input->post('meter'),
			'active' 		=> 1,
			'delete' 		=> 0
		]);
	
		$this->load->model('alert');

		$this->load->view('core/header');
		$this->alert->css();
		
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Transaksi Service > Pencatatan Meter Air > Meter Air', 'subTitle' => 'Add']);
		$this->load->view('proyek/transaksi/meter_air/add',
			[
				'dataPT' => $this->m_meter_air->getPT(),
				'kode_cust'=> "CG/CUST/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_meter_air->last_id()+1),
				'data' => $this->m_meter_air->getInfoAdd()
			]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
		if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        } 
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
			$dataSelect = $this->m_meter_air->getSelect($this->input->get('id'));
			
			$this->load->view('core/header');
			
			$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header',['title' => 'Transaksi Service > Pencatatan Meter Air > Meter Air', 'subTitle' => 'Edit']);
			$this->load->view('proyek/transaksi/meter_air/edit',
				[
					'data' => $this->m_meter_air->getInfoAdd(),
					'dataSelect' => $this->m_meter_air->getSelect($this->input->get('id'))
				]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_transaksi_meter_air');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        } 
    }

}
