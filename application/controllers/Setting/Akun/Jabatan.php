<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jabatan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Setting/Akun/m_jabatan');
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
        $data = $this->m_jabatan->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Akun > Jabatan', 'subTitle' => 'List']);
        $this->load->view('proyek/setting/akun/jabatan/view', ['data' => $data]);
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
        $this->load->view('core/body_header', ['title' => 'Setting > User > Jabatan', 'subTitle' => 'Add']);
        $this->load->view('proyek/setting/akun/jabatan/add');
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
        $this->load->view('core/body_header', ['title' => 'Setting > User > Jabatan', 'subTitle' => 'Add']);
        $this->load->view('proyek/setting/akun/jabatan/edit',
            [
                "data" =>$this->m_jabatan->get_select($this->input->get('id'))
            ]
        );
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function ajax_save(){
        echo(json_encode($this->m_jabatan->save($this->input->get())));
    }
    public function ajax_edit(){
        echo(json_encode($this->m_jabatan->edit($this->input->get('id'),$this->input->post())));
    }
    
}
