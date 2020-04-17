<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_unit_virtual extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_unit_virtual');
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
		$data = $this->m_unit_virtual->getAll();
       	$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Unit Virtual','subTitle' => 'List']);
		$this->load->view('proyek/master/unit_virtual/view' ,['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	public function add()
	{
		$this->load->model('m_unit_virtual');
		$dataUnit = $this->m_unit_virtual->get();
		$dataCustomer = $this->m_unit_virtual->getCustomer();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Unit Virtual', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/unit_virtual/add', ['dataUnit' => $dataUnit,'dataCustomer' => $dataCustomer]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	public function save()
	{	
		$status = $this->m_unit_virtual->save([
			
            'unit'                                          => $this->input->post('unit'),
			'customer_id'                                   => $this->input->post('customer_id'),
			'alamat'                                        => $this->input->post('alamat'),
			'va'                                            => $this->input->post('va'),
		]);

		$this->load->model('alert');
		$this->load->model('m_unit_virtual');
		$dataUnitVirtual = $this->m_unit_virtual->getAll();
	    $this->load->view('core/header');
		$this->alert->css();
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Unit Virtual', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/unit_virtual/add',['dataUnitVirtual' => $dataUnitVirtual]);
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
		if($this->input->post('va')){
		
			$this->load->model('alert');

			$status = $this->m_unit_virtual->edit([
			'id'         	                => $this->input->get('id'),
			'unit'                          => $this->input->post('unit'),
            'customer_id'                   => $this->input->post('customer_id'),
            'alamat'                        => $this->input->post('alamat'),
			'va'                            => $this->input->post('va'),
			'active'                        => $this->input->post('active'),
			]);
			$this->alert->css();
		}
		
		if($this->m_unit_virtual->cek($this->input->get('id'))){
			$dataUnitVirtual = $this->m_unit_virtual->getAll();
			$dataUnitVirtualSelect = $this->m_unit_virtual->getSelect($this->input->get('id'));
			$dataCustomer = $this->m_unit_virtual->getCustomer();
			
			$this->load->model('m_log');
			$data = $this->m_log->get('unit_virtual',$this->input->get('id'));
			$this->load->view('core/header');
			$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header',['title' => 'Master > Unit Virtual', 'subTitle' => 'Edit']);
			$this->load->view('proyek/master/unit_virtual/edit',['dataUnitVirtual' => $dataUnitVirtual,'data_select'=>$dataUnitVirtualSelect, 'dataCustomer'=>$dataCustomer, 'data'=>$data  ]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
		}else{
			redirect(site_url().'/P_master_unit_virtual');	
		}
		
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Update','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal | Double','text'=>'Data Inputan sudah Ada','type'=>'danger']);
			
	}
	
	
	public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_unit_virtual->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_unit_virtual->getAll();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Unit Virtual','subTitle' => 'List']);
		$this->load->view('proyek/master/unit_virtual/view', ['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } 
    }


}
