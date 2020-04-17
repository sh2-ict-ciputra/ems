<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_paket_loi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        $this->load->model('m_channel');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_paket_loi');
        $this->load->model('m_peruntukan_loi');
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
        $this->load->model('alert');
        $data = $this->m_paket_loi->get();
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Liaison Officer', 'subTitle' => 'List']);
        $this->load->view('proyek/master/paket_loi/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('alert');
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Liaison Officer', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/paket_loi/add');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function edit()
    {
        $this->load->model('m_log');
        $this->load->model('alert');
        $this->load->view('core/header');
		$this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Liaison Officer', 'subTitle' => 'Edit']);
        $this->load->view('proyek/master/paket_loi/edit',
            [   "data"       =>$this->m_log->get('loi_paket', $this->input->get('id')), 
                "dataSelect" =>$this->m_paket_loi->getSelect($this->input->get("id"))
            ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function ajax_save(){
        echo(json_encode($this->m_paket_loi->save($this->input->post())));
    }
    public function ajax_edit(){
        echo(json_encode($this->m_paket_loi->edit($this->input->post())));
    }
    public function ajax_delete(){
        echo(json_encode($this->m_paket_loi->delete($this->input->post())));
    }
    public function get_ajax_item(){
        echo(json_encode($this->m_paket_loi->get_item($this->input->get('data'))));

    }public function get_ajax_item_cek(){
        echo(json_encode($this->m_paket_loi->get_item_cek($this->input->get('data'))));

    }public function get_ajax_item_satuan(){
        echo(json_encode($this->m_paket_loi->get_item_satuan($this->input->get())));

    }public function get_ajax_item_satuan_cek(){
        echo(json_encode($this->m_paket_loi->get_item_satuan_cek($this->input->get())));

    }
}
