<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_generate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_unit');
        $this->load->model('m_core');
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();

    }
    
    public function lingkungan(){
       
        $this->load->view('core/header');
        $this->load->model('alert');
		$this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi> Generate IPL', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi/generate/add');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');

        // $project = $GLOBALS['project']->id;
        // $this->load->model('auto_generate/m_tagihan');

        // $periode = $this->input->get('periode')?$this->input->get('periode'):0;
        // $this->m_tagihan->pl2($project,$periode);
        
    }
}
