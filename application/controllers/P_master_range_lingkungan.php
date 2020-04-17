<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_range_lingkungan extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_range_lingkungan');
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
		$data = $this->m_range_lingkungan->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Lingkungan','subTitle' => 'List']);
		$this->load->view('proyek/master/range_lingkungan/view' ,['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function add()
	{
		$this->load->model('m_range_lingkungan');
		$dataRangelingkungan = $this->m_range_lingkungan->get();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Lingkungan', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/range_lingkungan/add',['dataRangelingkungan' => $dataRangelingkungan]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function save()
	{	
		
		$status = $this->m_range_lingkungan->save([
			
            'kode'                                   => $this->input->post('kode_range'),
			'nama'                                   => $this->input->post('nama'),
			'keterangan'                             => $this->input->post('keterangan'),
			'formula_bangunan'                       => $this->input->post('formula_bangunan'),
			'formula_kavling'                        => $this->input->post('formula_kavling'),
			'flag_bangunan'                          => $this->input->post('flag_bangunan'),
			'flag_kavling'                           => $this->input->post('flag_kavling'),
			'keamanan'                               => $this->input->post('keamanan'),
			'kebersihan'                             => $this->input->post('kebersihan'),
			'bangunan_fix'                           => $this->input->post('biaya_bangunan'),
			'kavling_fix'                            => $this->input->post('biaya_kavling'),
			'service_charge'                         => $this->input->post('service_charge'),
			'ppn_charge'                             => $this->input->post('ppn'),

			
			
			
			'range_awal'                             => $this->input->post('range_awal[]'),
            'range_akhir'                            => $this->input->post('range_akhir[]'),
            'harga_hpp'                              => $this->input->post('harga_hpp[]'),
			'harga_range'                            => $this->input->post('harga_range[]'),
          
			
			
			'range_awal2'                            => $this->input->post('range_awal2[]'),
            'range_akhir2'                           => $this->input->post('range_akhir2[]'),
            'harga_hpp2'                             => $this->input->post('harga_hpp2[]'),
			'harga_range2'                           => $this->input->post('harga_range2[]'),
           
			
		
			
			
			
				
		]);
		
		
		$this->load->model('alert');
		$this->load->model('m_range_lingkungan');
		$dataRangeLingkungan = $this->m_range_lingkungan->get();
		$this->load->view('core/header');
		$this->alert->css();
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Lingkungan', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/range_lingkungan/add',['dataRangelingkungan' => $dataRangeLingkungan]);
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

            $status = $this->m_range_lingkungan->edit([
                'id' => $this->input->get('id'),
                'kode_range' => $this->input->post('kode_range'),
                'name' => $this->input->post('nama'),
				'keterangan' => $this->input->post('keterangan'),
			    'formula_bangunan'  => $this->input->post('formula_bangunan'),
			    'formula_kavling'   => $this->input->post('formula_kavling'),
			    'flag_bangunan'  => $this->input->post('flag_bangunan'),
				'flag_kavling'   => $this->input->post('flag_kavling'),
				'active'   => $this->input->post('active'),
             	'keamanan' => $this->input->post('keamanan'),
				'kebersihan' => $this->input->post('kebersihan'),
				'bangunan_fix' => $this->input->post('biaya_bangunan'),
				'kavling_fix' => $this->input->post('biaya_kavling'),
				'service_charge' => $this->input->post('service_charge'),
				'ppn_charge' => $this->input->post('ppn'),
				
				
					
				
                'id_range_bangunan' => $this->input->post('id_range_bangunan[]'),
                'range_awal' => $this->input->post('range_awal[]'),
                'range_akhir' => $this->input->post('range_akhir[]'),
                'harga_hpp' => $this->input->post('harga_hpp[]'),
                'harga_range' => $this->input->post('harga_range[]'),
				
				
				'id_range_kavling' => $this->input->post('id_range_kavling[]'),
                'range_awal2' => $this->input->post('range_awal2[]'),
                'range_akhir2' => $this->input->post('range_akhir2[]'),
                'harga_hpp2' => $this->input->post('harga_hpp2[]'),
                'harga_range2' => $this->input->post('harga_range2[]'),
				
				
				
				
            ]);
            $this->alert->css();
        }

        if ($this->m_range_lingkungan->cek($this->input->get('id'))) {
            $dataRangeLingkungan = $this->m_range_lingkungan->get();
            $dataRangeLingkunganSelect = $this->m_range_lingkungan->getSelect($this->input->get('id'));
			$dataRangeDetailBangunan = $this->m_range_lingkungan->get_range_detail_bangunan($this->input->get('id'));
			$dataRangeDetailKavling = $this->m_range_lingkungan->get_range_detail_kavling($this->input->get('id'));
            $this->load->model('m_log');
            $data = $this->m_log->get('range_lingkungan', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Range > Range Lingkungan', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/range_lingkungan/edit', ['data' => $data, 'dataRangeLingkungan' => $dataRangeLingkungan, 'data_select' => $dataRangeLingkunganSelect, 'dataRangeDetailBangunan' => $dataRangeDetailBangunan,  'dataRangeDetailKavling' => $dataRangeDetailKavling    ]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_range_lingkungan');
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

        $status = $this->m_range_lingkungan->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_range_lingkungan->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Lingkungan','subTitle' => 'List']);
		$this->load->view('proyek/master/range_lingkungan/view' ,['data' => $data]);
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
