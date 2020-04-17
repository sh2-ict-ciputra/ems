<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_cara_pembayaran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_cara_pembayaran');
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
        $data = $this->m_cara_pembayaran->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Cara Pembayaran', 'subTitle' => 'List']);
        $this->load->view('proyek/master/cara_pembayaran/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_pt');
        $pt = $this->m_pt->get();
        $bank = $this->m_bank->get_order_name();
        $dataJenisCaraPembayaran = $this->m_cara_pembayaran->get_jenis_cara_pembayaran();
        
        $this->load->model('m_coa');
        $dataCaraPembayaran = $this->m_coa->get_isjournal();
        $this->load->model('alert');
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Cara Pembayaran', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/cara_pembayaran/add', [
            'dataCaraPembayaran'        => $dataCaraPembayaran,
            'dataJenisCaraPembayaran'   => $dataJenisCaraPembayaran,
            'bank'                      => $bank,
            'pt'                        => $pt
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save(){
        echo(json_encode($this->m_cara_pembayaran->save($this->input->post())));
    }

    public function ajax_edit(){
        echo(json_encode($this->m_cara_pembayaran->ajax_edit($this->input->get("id"),$this->input->post())));
    }
    public function edit()
    {
        $this->load->model('m_pt');
        $pt = $this->m_pt->get();
        $dataJenisCaraPembayaran = $this->m_cara_pembayaran->get_jenis_cara_pembayaran();
        $bank = $this->m_bank->get_order_name();
        $project = $this->m_core->project();

        $status = 0;
        $dataSelected = $this->m_cara_pembayaran->getSelect($this->input->get('id'));
        if ($dataSelected->project_id ==$project->id) {
            $dataCaraPembayaran = $this->m_cara_pembayaran->get();
            $dataPTCOA = $this->m_cara_pembayaran->get_all_pt_coa();
            $this->load->model('m_coa');
            $dataCaraPembayaran = $this->m_coa->get_isjournal();
            
            $this->load->model('m_log');
            $data = $this->m_log->get('cara_pembayaran', $this->input->get('id'));
            $this->load->model('alert');
            $this->load->view('core/header');
            $this->alert->css();
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Accounting > Cara Pembayaran', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/cara_pembayaran/edit', 
                [
                    'dataCaraPembayaran' => $dataCaraPembayaran, 
                    'dataSelected' => $dataSelected, 
                    'dataPTCOA' => $dataPTCOA, 
                    'data' => $data,
                    'bank' => $bank,
                    'dataJenisCaraPembayaran'   => $dataJenisCaraPembayaran,

                    'pt'                        => $pt

                    ]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url() . '/P_master_cara_pembayaran');
        }

    }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_cara_pembayaran->delete([
            'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_cara_pembayaran->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Cara Pembayaran', 'subTitle' => 'List']);
        $this->load->view('proyek/master/cara_pembayaran/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } elseif ($status == 'cara_pembayaran') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Cara Pembayaran', 'type' => 'danger']);
        } elseif ($status == 'metode_penagihan') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Metode Penagihan', 'type' => 'danger']);
        } elseif ($status == 'service') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Service', 'type' => 'danger']);
        }
    }
}
