<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_mappingCoa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_coa');
        $this->load->library('session');
        $this->load->model('m_core');
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();
        $this->load->model('m_PT');

    }

    public function index()
    {
        $data = $this->m_coa->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Mapping COA', 'subTitle' => 'List']);
        $this->load->view('proyek/master/coa/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $dataPT = $this->m_PT->get();
        $dataCOA = $this->m_coa->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Mapping COA', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/coa/add', ['dataPT' => $dataPT, 'dataCOA' => $dataCOA]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function edit()
    {
        $status = 0;
        if ($this->input->post('pt_id') and $this->input->post('coa_id')) {
            $this->load->model('alert');

            $status = $this->m_coa->mapping_edit([
                'id' => $this->input->get('id'),
                'pt_id' => $this->input->post('pt_id'),
                'coa_id' => $this->input->post('coa_id'),
                'description' => $this->input->post('description'),
                'active' => $this->input->post('status'),
            ]);
            $this->alert->css();
        }
        $dataPT = $this->m_PT->get();
        $dataCOA = $this->m_coa->get();
        $data = 1;

        if ($this->m_coa->cek($this->input->get('id'))) {
            $dataCOA = $this->m_coa->get();
            $dataCOASelect = $this->m_coa->mapping_get($this->input->get('id'));
            $this->load->model('m_log');
            $data = $this->m_log->get('coa_mapping', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Accounting > Mapping COA', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/coa/edit', ['dataPT' => $dataPT, 'dataCOA' => $dataCOA, 'data' => $data, 'data_select' => $dataCOASelect, 'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_mappingCoa');
        }

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

    public function save()
    {
        $status = $this->m_coa->mapping_save([
            'pt_id' => $this->input->post('pt_id'),
            'coa_id' => $this->input->post('coa_id'),
            'description' => $this->input->post('description'),
        ]);

        $this->load->model('alert');
        $this->load->model('m_PT');
        $dataPT = $this->m_PT->get();
        $dataCOA = $this->m_coa->get();

        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Mapping COA', 'subTitle' => 'List']);
        $this->load->view('proyek/master/coa/add', ['dataPT' => $dataPT, 'dataCOA' => $dataCOA]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        } elseif ($status == 'pt') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data PT Tidak Ada', 'type' => 'danger']);
        } elseif ($status == 'coa') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA Tidak Ada', 'type' => 'danger']);
        }
    }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_coa->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_coa->mapping_getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Mapping COA', 'subTitle' => 'List']);
        $this->load->view('proyek/master/coa/view', ['data' => $data]);
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
