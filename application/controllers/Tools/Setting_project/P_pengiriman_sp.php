<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_pengiriman_sp  extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Tools/m_pengiriman_sp');
        $this->load->model('m_core');
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();
    }

    public function index()
    {
        $table = $this->db->get("v_list_pengiriman_sp")->result();

        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Tools > Setting Project > Pengiriman SP', 'subTitle' => 'List']);
        $this->load->view('core/list',[
            "btn_add"   => "style='display:none'",
            "table"     => $table
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }public function add()
    {
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Tools > Setting Project > Pengiriman SP', 'subTitle' => 'List']);
        $this->load->view('Proyek/Tools/pengiriman_sp/add');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function edit()
    {
        $project    = $this->m_core->project();
        $service    = $this->db->select('id,name')->where("project_id",$project->id)->where("id",$this->input->get('id'))->get("service")->row();
        $data       = $this->db->where("service_id",$service->id)->where("project_id",$project->id)->get("parameter_project")->result();
        echo("<pre>");
            print_r($data);
        echo("</pre>");
        
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Tools > Setting Project > Pengiriman SP', 'subTitle' => 'List']);
        $this->load->view('Proyek/Tools/pengiriman_sp/edit',[
            "service"   => $service,
            "data"      => $data
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function save()
    {
        $this->m_pengiriman_sp->save([
            "jarak-sp"  => $this->input->post("jarak-sp[]"),
            "service"   => $this->input->post("service")
        ]);
        // redirect(site_url()."/Tools/Setting_project/P_pengiriman_sp/");
        
    }
}
