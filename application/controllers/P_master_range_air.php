<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_range_air extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_range_air');
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
		$data = $this->m_range_air->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Air','subTitle' => 'List']);
		$this->load->view('proyek/master/range_air/view' ,['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function add()
	{
		$this->load->model('m_range_air');
		$dataRangeAir = $this->m_range_air->get();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Air', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/range_air/add',['dataRangeAir' => $dataRangeAir]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function save()
	{	
		$status = $this->m_range_air->save([
			
            'kode'                                   => $this->input->post('kode_range'),
			'nama'                                   => $this->input->post('nama'),
			'keterangan'                             => $this->input->post('keterangan'),
			'formula'                                => $this->input->post('formula'),
			
					
			'range_awal'                             => $this->input->post('range_awal[]'),
            'range_akhir'                            => $this->input->post('range_akhir[]'),
            'harga_hpp'                              => $this->input->post('harga_hpp[]'),
			'harga_range'                            => $this->input->post('harga_range[]'),
            'delete_range_detail'                     => 0
			
			
			
				
		]);

		$this->load->model('alert');
		$this->load->model('m_range_air');
		$dataRangeAir = $this->m_range_air->get();
	    $this->load->view('core/header');
		$this->alert->css();
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Air', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/range_air/add',['dataRangeAir' => $dataRangeAir]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Tambah','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal','text'=>'Data Inputan suda Ada','type'=>'danger']);
					
	}
	
	
	  public function edit()
    {
        $status = 0;
        if ($this->input->post('kode_range')) {
            $this->load->model('alert');

            $status = $this->m_range_air->edit([
                'id' => $this->input->get('id'),
                'kode_range' => $this->input->post('kode_range'),
                'name' => $this->input->post('name'),
                'keterangan' => $this->input->post('keterangan'),
                'formula'    => $this->input->post('formula'),
                'active' => $this->input->post('active'),
				
				
				
                'id_range_air_detail' => $this->input->post('id_range_air_detail[]'),
                'range_awal' => $this->input->post('range_awal[]'),
                'range_akhir' => $this->input->post('range_akhir[]'),
                'harga_hpp' => $this->input->post('harga_hpp[]'),
                'harga_range' => $this->input->post('harga_range[]'),
                'delete_detail' => 0,
            ]);
            $this->alert->css();
        }

        if ($this->m_range_air->cek($this->input->get('id'))) {
            $dataRangeAir = $this->m_range_air->get();
            $dataRangeAirSelect = $this->m_range_air->getSelect($this->input->get('id'));
			$dataRangeAirDetail = $this->m_range_air->get_range_air_detail($this->input->get('id'));
            $this->load->model('m_log');
            $data = $this->m_log->get('range_air', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Range > Range Air', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/range_air/edit', ['data' => $data, 'dataRangeAir' => $dataRangeAir, 'data_select' => $dataRangeAirSelect, 'dataRangeAirDetail' => $dataRangeAirDetail]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_range_air');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        }
    }
	
	
	public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_range_air->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_range_air->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Air','subTitle' => 'List']);
		$this->load->view('proyek/master/range_air/view' ,['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } elseif ($status == 'cara_pembayaran') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Cara Pembayaran', 'type' => 'danger']);
        } elseif ($status == 'metode_penagihan') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Metode Penagihan', 'type' => 'danger']);
        } elseif ($status == 'service') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Service', 'type' => 'danger']);
        }
    }

}
