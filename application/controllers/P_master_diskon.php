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
        $data = $this->m_diskon->get_view();
        
        $this->load->view('core/header');
        $this->load->model('alert');
		$this->alert->css();

        $this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Diskon > Diskon','subTitle' => 'List']);
        $this->load->view('proyek/master/diskon/view',['data' => $data]);
        $this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }
    
    public function add()
	{   
        
        $this->load->model('m_service');
        $this->load->model('m_purpose_use');
        $this->load->model('m_gol_diskon');
        $dataGolDiskon = $this->m_gol_diskon->get();
        $dataPurposeUse = $this->m_purpose_use->get();
        
        $dataService = $this->m_service->get();


        $this->load->model('alert');
        $this->load->view('core/header');
        $this->alert->css();
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Diskon > Diskon', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/diskon/add',[
                    'dataService' => $dataService,
                    'dataGolDiskon'=>$dataGolDiskon,
                    'dataPurposeUse'=>$dataPurposeUse,
                    ]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }
    public function ajax_paket_service(){
        $project = $this->m_core->project();
        echo(json_encode($this->db->select("*")->from("paket_service")->where("service_id",$this->input->get("id"))->where("project_id",$project->id)->get()->result()));
    }
    public function ajax_save(){
        echo(json_encode($this->m_diskon->ajax_save($this->input->get())));
    }
    public function edit()
	{
        if($this->m_diskon->cek($this->input->get('id'))){
            $dataSelect = $this->m_diskon->get_by_id($this->input->get('id'));
            $this->load->model('m_service');
            $this->load->model('m_purpose_use');
            $this->load->model('m_gol_diskon');
            $this->load->model('m_log');
            $dataGolDiskon = $this->m_gol_diskon->get();
            $dataPurposeUse = $this->m_purpose_use->get();
            
            $dataService = $this->m_service->get();

            
            $data = $this->m_log->get('diskon', $this->input->get('id'));

            $this->load->model('alert');
            $this->load->view('core/header');
            $this->alert->css();
            $this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header',['title' => 'Master > Diskon > Diskon', 'subTitle' => 'Add']);
            $this->load->view('proyek/master/diskon/edit',[
                        'dataService' => $dataService,
                        'dataGolDiskon'=>$dataGolDiskon,
                        'dataPurposeUse'=>$dataPurposeUse,
                        'dataSelect' => $dataSelect,
                        'data'  => $data
                        ]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
		}else{
			redirect(site_url().'/P_master_diskon');	
        }
	}
	
	public function delete()
    {
        echo json_encode($this->m_diskon->delete($this->input->post("id")));
    }
    public function get_paket_service(){
		echo json_encode($this->m_diskon->get_paket_service($this->input->post('service_id')));	
    }
    public function ajax_edit(){
        echo(json_encode($this->m_diskon->ajax_edit($this->input->get())));
    }

}
?>