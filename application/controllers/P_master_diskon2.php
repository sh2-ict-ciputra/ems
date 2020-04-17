<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_diskon extends CI_Controller{
    function __construct() 
    {
		parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if(!$this->m_login->status_login()) redirect(site_url());
        $this->load->model('m_diskon');
        $this->load->library('session');
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
        $data = $this->m_diskon->diskon_getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Diskon > Diskon Customer','subTitle' => 'List']);
        $this->load->view('proyek/master/diskon/view',['data' => $data]);
        $this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }
    
    public function add()
	{   
		$this->load->model('m_diskon');
        $dataDiskon = $this->m_diskon->get();
        $dataProductCategory = $this->m_diskon->get_product_category();
		$dataService = $this->m_diskon->get_service();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Diskon > Diskon Customer', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/diskon/add',['dataDiskon' => $dataDiskon, 'dataService' => $dataService, 'dataProductCategory' => $dataProductCategory]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }
    
    public function save()
	{	
		$status = 0;
		$status = $this->m_diskon->save([
            'name'         => $this->input->post('nama'),
		 	'product_category_id'   => $this->input->post('product_category_id'),
			'description'           => $this->input->post('description'),
			'active_diskon'         => 1,
            'delete_diskon'         => 0,
		
			
			'service_id'            => $this->input->post('service[]'),				
			'coa_mapping_id_diskon' => $this->input->post('coa[]'),				
			'flag_umum_event'       => $this->input->post('jenisDiskon[]'),				
			'parameter_id'          => $this->input->post('parameter[]'),
			'nilai'                 => $this->input->post('nilai[]'),     
			'min_bulan'             => $this->input->post('minimum_bulan[]'),    
            'active_diskon_detail'  => 1,
            'delete_diskon_detail'  => 0,
			
		 ]);

		$this->load->model('alert');
		$this->load->model('m_diskon');
		$dataDiskon = $this->m_diskon->get();
		$this->load->view('core/header');
		$this->alert->css();

		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Diskon > Diskon Customer', 'subTitle' => 'List']);
		$this->load->view('proyek/master/diskon/add',['dataDiskon' => $dataDiskon]);
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
		if($this->input->post('nama')){
            $this->load->model('alert');

			$status = $this->m_diskon->edit([
			'id'         	        => $this->input->get('id'),
			'name'                  => $this->input->post('nama'),
            'product_category_id'   => $this->input->post('product_category_id'),
			'description'           => $this->input->post('description'),
            'active'                => $this->input->post('active'),
            
            'diskon_detail_id'		=> $this->input->post('diskon_detail_id[]'),
			'service_id'            => $this->input->post('service[]'),
			'coa_mapping_id_diskon' => $this->input->post('coa[]'),
			'flag_umum_event'		=> $this->input->post('jenisDiskon[]'), 
            'parameter_id'          => $this->input->post('parameter_id[]'),
			'nilai'                 => $this->input->post('nilai[]'),
			'min_bulan'             => $this->input->post('minimum_bulan[]'),
            'active_diskon_detail'  => 1,
            'delete_diskon_detail'  => 0,

            ]);
			$this->alert->css();
		}

        if($this->m_diskon->cek($this->input->get('id'))){
			$dataDiskon = $this->m_diskon->get();
			$dataDiskonSelect = $this->m_diskon->mapping_get($this->input->get('id'));
			$dataDiskonDetail = $this->m_diskon->get_diskon_detail($this->input->get('id'));
			$dataProductCategory = $this->m_diskon->get_product_category();
			$dataService = $this->m_diskon->get_service();
			$dataCOA = $this->m_diskon->get_coa();
			$this->load->model('m_log');
			$data = $this->m_log->get('diskon',$this->input->get('id'));
			$this->load->view('core/header');
			$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header',['title' => 'Master > Diskon > Diskon Customer', 'subTitle' => 'Edit']);
			$this->load->view('proyek/master/diskon/edit',['data'=>$data,'dataDiskon' => $dataDiskon,'dataDiskonSelect'=>$dataDiskonSelect,'dataDiskonDetail'=>$dataDiskonDetail, 'dataProductCategory'=>$dataProductCategory, 'dataService'=>$dataService, 'dataCOA'=>$dataCOA]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
		}else{
			redirect(site_url().'/P_master_diskon');	
        }
        
        if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Update','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal | Double','text'=>'Data Inputan suda Ada','type'=>'danger']);

	}
	
	public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_diskon->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_diskon->diskon_getAll();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Diskon > Diskon','subTitle' => 'List']);
		$this->load->view('proyek/master/diskon/view', ['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } 
    }
    public function get_paket_service(){
		echo json_encode($this->m_diskon->get_paket_service($this->input->post('service_id')));	
	}
}
?>