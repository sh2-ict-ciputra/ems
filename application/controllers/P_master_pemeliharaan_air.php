<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_pemeliharaan_air  extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_pemeliharaan_air');
        $this->load->model('m_bank');
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
        $data = $this->m_pemeliharaan_air->get();
        $this->load->model('alert');
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Tarif > Pemeliharaan Air', 'subTitle' => 'List']);
        $this->load->view('proyek/master/pemeliharaan_air/view', ['data' => $data]);
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
        $this->load->view('core/body_header', ['title' => 'Master > Tarif > Pemeliharaan Air', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/pemeliharaan_air/add');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function ajax_save(){
        echo(json_encode($this->m_pemeliharaan_air->save($this->input->post())));
    }

    public function ajax_edit(){
        echo(json_encode($this->m_pemeliharaan_air->edit($this->input->post())));
    }
    public function ajax_delete(){
        echo(json_encode($this->m_pemeliharaan_air->delete($this->input->post())));
    }
    public function edit()
    {
        $this->load->model('m_log');
        $data = $this->m_log->get('service', $this->input->get('id'));
        $data2 = $this->m_pemeliharaan_air->get($this->input->get('id'))[0];
        $this->load->model('alert');
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Tarif > Pemeliharaan Air', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/pemeliharaan_air/edit',['data'=>$data,'data2'=>$data2]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_pemeliharaan_air->delete([
            'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_pemeliharaan_air->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Pemeliharaan Air', 'subTitle' => 'List']);
        $this->load->view('proyek/master/pemeliharaan_air/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } elseif ($status == 'cara_pembayaran') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Pemeliharaan Air', 'type' => 'danger']);
        } elseif ($status == 'metode_penagihan') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Metode Penagihan', 'type' => 'danger']);
        } elseif ($status == 'service') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Service', 'type' => 'danger']);
        }
    }
}
