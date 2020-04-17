<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_range_listrik extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_range_listrik');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();global $menu;
		$menu = $this->m_core->menu();
    }
	public function index()
	{
		$data = $this->m_range_listrik->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Listrik','subTitle' => 'List']);
		$this->load->view('proyek/master/range_listrik/view' ,['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function add()
	{
		$this->load->model('m_range_listrik');
		$dataRangelistrik = $this->m_range_listrik->get();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Listrik', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/range_listrik/add',['dataRangelistrik' => $dataRangelistrik]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	
	
	public function save()
	{	
		$status = $this->m_range_listrik->save([
			
            
			'kode'                                   => $this->input->post('kode_range'),
			'nama'                                   => $this->input->post('nama'),
			'keterangan'                             => $this->input->post('keterangan'),
			'range_fix'                              => $this->input->post('range_fix'),
			'formula'                                => $this->input->post('formula'),
			
					
			'range_awal'                             => $this->input->post('range_awal[]'),
            'range_akhir'                            => $this->input->post('range_akhir[]'),
            'harga_hpp'                              => $this->input->post('harga_hpp[]'),
			'harga_range'                            => $this->input->post('harga_range[]'),
            'delete_range_detail'                     => 0
			
			
			
		
				
		]);

		$this->load->model('alert');
		$this->load->model('m_range_listrik');
		$dataRangeListrik = $this->m_range_listrik->get();
	    $this->load->view('core/header');
	    $this->alert->css();
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Listrik', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/range_listrik/add',['dataRangelistrik' => $dataRangeListrik]);
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

            $status = $this->m_range_listrik->edit([
                'id' => $this->input->get('id'),
                'kode_range' => $this->input->post('kode_range'),
                'name' => $this->input->post('name'),
                'keterangan' => $this->input->post('keterangan'),
                'formula'    => $this->input->post('formula'),
				'range_fix' => $this->input->post('range_fix'),
                'active' => $this->input->post('active'),
				
				
				
                'id_range_listrik_detail' => $this->input->post('id_range_listrik_detail[]'),
                'range_awal' => $this->input->post('range_awal[]'),
                'range_akhir' => $this->input->post('range_akhir[]'),
                'harga_hpp' => $this->input->post('harga_hpp[]'),
                'harga_range' => $this->input->post('harga_range[]'),
                'delete_detail' => 0,
            ]);
            $this->alert->css();
        }

        if ($this->m_range_listrik->cek($this->input->get('id'))) {
            $dataRangeListrik = $this->m_range_listrik->get();
            $dataRangeListrikSelect = $this->m_range_listrik->getSelect($this->input->get('id'));
			$dataRangeListrikDetail = $this->m_range_listrik->get_range_listrik_detail($this->input->get('id'));
            $this->load->model('m_log');
            $data = $this->m_log->get('range_listrik', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Range > Range Listrik', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/range_listrik/edit', ['data' => $data, 'dataRangeListrik' => $dataRangeListrik, 'data_select' => $dataRangeListrikSelect, 'dataRangeListrikDetail' => $dataRangeListrikDetail]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_range_listrik');
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

        $status = $this->m_range_listrik->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_range_listrik->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Listrik','subTitle' => 'List']);
		$this->load->view('proyek/master/range_listrik/view' ,['data' => $data]);
		$this->load->view('core/body_footer');
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
