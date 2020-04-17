<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Parameter_project extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Setting/m_parameter_project');
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
        $data = $this->m_parameter_project->get_view(0);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Parameter Project', 'subTitle' => 'List']);
        $this->load->view('global/setting/Parameter_Project/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function edit()
    {
        $this->load->model('Setting/Akun/m_group');
        $data = $this->m_parameter_project->get_by_jenis_id($this->input->get("id"),null);
        $this->load->view('core/header');
        $this->load->model('alert');
		$this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Parameter Project', 'subTitle' => 'Edit']);
        $this->load->view('global/setting/Parameter_Project/edit',
            [
                "data"=>$data
            ]
        );
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function ajax_save(){
        echo(json_encode($this->m_parameter_project->save($this->input->post(),$this->input->get("id"),null)));
    }
}
