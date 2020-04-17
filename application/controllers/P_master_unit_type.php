<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_unit_type extends CI_Controller {
    function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_unit_type');
		$this->load->library('session');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
    }

    public function index(){
		$data = $this->m_unit_type->get_all_unit_type();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Town Planning > Unit Type','subTitle' => 'List']);
		$this->load->view('proyek/master/unit_type/view',['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }
    
    public function add()
	{
		$this->load->model('m_unit_type');
		$dataGolDiskon = $this->m_unit_type->get_all_unit_type();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Town Planning > Unit Type', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/unit_type/add',['dataGolDiskon' => $dataGolDiskon]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }
    
    public function save()
	{	
		$status = $this->m_unit_type->save([
			'name'                          => $this->input->post('name'),
			'keterangan'                    => $this->input->post('keterangan'),
			'active'                        => $this->input->post('status')	
		]);

		
		$this->load->model('alert');
		$this->alert->css();
		$this->load->model('m_unit_type');
		$dataUnitType = $this->m_unit_type->get_all_unit_type();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Town Planning > Unit Type ', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/unit_type/add',['dataUnitType' => $dataUnitType]);
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
		if($this->input->post('name')){
			$this->load->model('alert');
			$status = $this->m_unit_type->edit([
			'id'				=> $this->input->get('id'),
			'name'				=> $this->input->post('name'),
			'keterangan'      	=> $this->input->post('keterangan'),
			'active'         	=> $this->input->post('status')
			]);
			$this->alert->css();
		}

		if($this->m_unit_type->cek($this->input->get('id'))){
			$dataUnitType = $this->m_unit_type->get();
			$dataUnitTypeSelect = $this->m_unit_type->getSelect($this->input->get('id'));
			$this->load->model('m_log');
			$data = $this->m_log->get('unit_type',$this->input->get('id'));
			$this->load->view('core/header');
			$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header',['title' => 'Master > Town Planning > Unit Type', 'subTitle' => 'Edit']);
			$this->load->view('proyek/master/unit_type/edit',['dataUnitType' => $dataUnitType,'data_select'=>$dataUnitTypeSelect,'data' => $data ]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
		}else{
			redirect(site_url().'/P_master_unit_type');	
		}
		
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Tambah','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal | Double','text'=>'Data Inputan sudah Ada','type'=>'danger']);
			
	}

}

?>