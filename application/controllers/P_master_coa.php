<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_master_coa extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('m_coa');
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
        $data = $this->m_coa->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > COA', 'subTitle' => 'List']);
        $this->load->view('proyek/master/coa/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function add()
    {
        $status = "";
        if ($this->input->post('source')) {
            $this->load->model('alert');
            $this->alert->css();
            $status = $this->m_PT->save([
                'source'=> $this->input->post('id'),
                'pt_id' => $this->input->post('pt_id')
            ]);
        }
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > PT', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/pt/add');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        } elseif ($status == 'pt') {
            $this->load->view('core/alert', ['title' => 'Gagal | PT', 'text' => 'Data PT Tidak Ada', 'type' => 'danger']);
        } elseif ($status == 'coa') {
            $this->load->view('core/alert', ['title' => 'Gagal | COA', 'text' => 'Data COA Tidak Ada', 'type' => 'danger']);
        }
    }
    public function get_ajax_pt_source(){
        echo json_encode($this->m_PT->get_ajax_pt_source($this->input->POST('source')));
    }
}
