<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_service extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_service');
		$this->load->model('m_core');
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
    }
	public function index()
	{
		$data = $this->m_service->get_view();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Service','subTitle' => 'List']);
		$this->load->view('proyek/master/service/view',['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function add()
	{
		$dataService = $this->m_service->mapping_get_all_pt_coa();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Service > Service', 'subTitle' => 'Add']);		
		$this->load->view('proyek/master/service/add',['dataService' => $dataService]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function save()
	{	
		$data = [
			'jenis_service'                          => $this->input->post('jenis_service'),
            'jenis_retribusi'                        => $this->input->post('jenis_retribusi'),
            'code'                                   => $this->input->post('code'),
			'nama_service'                           => $this->input->post('nama_service'),
			'coa_mapping_id_service'           		 => $this->input->post('coa_mapping_id_service'),
			'ppn'                                    => $this->input->post('ppn'),
			'coa_mapping_id_ppn'                     => $this->input->post('coa_mapping_id_ppn'),
			'denda_parameter'                        => $this->input->post('denda_parameter'),
			'denda_jenis'                            => $this->input->post('denda_jenis'),
			'denda_minimum'                          => $this->input->post('denda_minimum'),
			'denda_persen'                           => $this->input->post('denda_persen'),
			'denda_tgl_putus'                        => $this->input->post('denda_tgl_putus'),
			'description'                            => $this->input->post('description'),
		];
		
		// echo("<pre>");
		// 	print_r($data);
		// echo("</pre>");

		$status = $this->m_service->save($data);
		$this->load->model('alert');

		$dataService = $this->m_service->mapping_get_all_pt();
		$this->load->view('core/header');
				$this->alert->css();

		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Service > Service', 'subTitle' => 'Add']);		
		$this->load->view('proyek/master/service/add',['dataService' => $dataService]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');	


		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Tambah','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal','text'=>'Data Inputan suda Ada','type'=>'danger']);
		elseif($status == 'pt')
			$this->load->view('core/alert',['title' => 'Gagal','text'=>'Data PT Tidak Ada','type'=>'danger']);
		elseif($status == 'coa')
			$this->load->view('core/alert',['title' => 'Gagal','text'=>'Data COA Tidak Ada','type'=>'danger']);
			
		// aa

		// $this->load->model('alert');
		// $this->load->model('m_service');
		// $dataService = $this->m_service->mapping_getAll();
	    // $this->load->view('core/header');
		// $this->load->view('core/side_bar');
		// $this->load->view('core/top_bar');
		// $this->load->view('core/body_header',['title' => 'Master > Service >  Service ', 'subTitle' => 'Add']);
		// $this->load->view('proyek/master/service/add',['dataService' => $dataService]);
		// $this->load->view('core/body_footer');
		// $this->load->view('core/footer');
		// if($status == 'success')
		// 	$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Tambah','type'=>'success']);
		// elseif($status == 'double')
		// 	$this->load->view('core/alert',['title' => 'Gagal','text'=>'Data Inputan suda Ada','type'=>'danger']);
					
	}

	public function edit()
	{
		$status = 0;
		// if($this->input->post('pt_id') AND $this->input->post('coa_id')){

		// 	$this->load->model('alert');

		// 	$status = $this->m_coa->mapping_edit([
		// 		'id'         	=> $this->input->get('id'),
		// 		'pt_id'         => $this->input->post('pt_id'),
		// 		'coa_id'        => $this->input->post('coa_id'),
		// 		'description'   => $this->input->post('description'),
		// 		'active'   		=> $this->input->post('status')
		// 	]);
		// 	$this->alert->css();
		// }
		// $this->load->model('PT');
		// $dataPT = $this->PT->get();
		// $dataCOA = $this->m_coa->get();
		// $data = 1;
		$data = $this->m_service->mapping_get_all_pt_coa();

		if($this->m_service->cek($this->input->get('id'))){
			// $dataCOA = $this->m_coa->get();
			// $dataCOASelect = $this->m_coa->mapping_get($this->input->get('id'));
			$this->load->model('m_log');
			// $data = $this->m_log->get('coa_mapping',$this->input->get('id'));

			$dataService = $this->m_service->mapping_get_all_pt_coa();
			$data = $this->m_log->get('service',$this->input->get('id'));

			$this->load->view('core/header');
			$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header',['title' => 'Master > Service > Service', 'subTitle' => 'Edit']);		
			$this->load->view('proyek/master/service/edit',['data' => $data]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
		}else{
			redirect(site_url().'/P_master_service');	
		}
		
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Tambah','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal | Double','text'=>'Data Inputan suda Ada','type'=>'danger']);
		elseif($status == 'pt')
			$this->load->view('core/alert',['title' => 'Gagal | PT','text'=>'Data PT Tidak Ada','type'=>'danger']);
		elseif($status == 'coa')
			$this->load->view('core/alert',['title' => 'Gagal | COA','text'=>'Data COA Tidak Ada','type'=>'danger']);	
	}

	public function add_get_coa(){
		echo(json_encode($this->m_service->get_coa_by_pt($this->input->post('id'))));
	}
	
	
	public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_service->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_service->get_view();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Service','subTitle' => 'List']);
		$this->load->view('proyek/master/service/view',['data' => $data]);
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
