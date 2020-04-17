<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permission_menu extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Setting/Akun/m_Permission_menu',"m1");
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
        $data = $this->m1->get();
        $this->load->view('core/header');
        $this->load->model('alert');
		$this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Akun > Permission Menu', 'subTitle' => 'List']);
        $this->load->view('proyek/setting/akun/Permission_menu/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function add()
    {
        $this->load->view('core/header');
        $this->load->model('alert');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Akun > Permission Menu', 'subTitle' => 'Add']);
        $this->load->view('proyek/setting/akun/Permission_menu/add',[
            "data1" => $this->m1->get_group_user(),
            "data2" => $this->m1->get_level()
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function edit()
    {
        $this->load->view('core/header');
        $this->load->model('alert');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Akun > Permission Menu', 'subTitle' => 'Add']);
        $this->load->view('proyek/setting/akun/Permission_menu/edit',[
            "data1" => $this->m1->get_group_user(),
            "data2" => $this->m1->get_level()
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function ajax_save(){
        echo(json_encode($this->m1->save($this->input->post())));
    }
    public function ajax_delete(){
        echo(json_encode($this->m1->delete($this->input->post())));
    }
    public function ajax_permission_menu(){
        echo(json_encode($this->m1->get_permission_menu_by_id($this->input->post("id"))));
    }
}
