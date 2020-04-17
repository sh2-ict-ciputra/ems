<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_metode_penagihan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_metode_penagihan');
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
        $data = $this->m_metode_penagihan->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Metode Penagihan', 'subTitle' => 'List']);
        $this->load->view('proyek/master/metode_penagihan/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_metode_penagihan');
        // $dataMetodePenagihan = $this->m_metode_penagihan->get_all_pt_coa();
        $this->load->model('m_coa');
        $dataMetodePenagihan = $this->m_coa->get_isjournal();

        $dataMetodePenagihanJenis = $this->m_metode_penagihan->get_jenis();

        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Metode Penagihan', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/metode_penagihan/add', ['dataMetodePenagihan' => $dataMetodePenagihan, 'dataMetodePenagihanJenis' => $dataMetodePenagihanJenis]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $project = $this->m_core->project();

        $status = $this->m_metode_penagihan->save([
            'project_id' => $project->id,
            'metode_penagihan_jenis_id' => $this->input->post("jenis_metode_penagihan"),
            'code' => $this->input->post('code'),
            'metode_penagihan' => $this->input->post('metode_penagihan'),
            'biaya_admin' => $this->input->post('biaya_admin'),
            'coa' => $this->input->post('coa'),
            'keterangan' => $this->input->post('keterangan'),
            'status' => $this->input->post('status'),
        ]);

        $this->load->model('alert');
        $this->load->model('m_metode_penagihan');
        // $dataMetodePenagihan = $this->m_metode_penagihan->get_all_pt_coa();
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->model('m_coa');
        $dataMetodePenagihan = $this->m_coa->get_isjournal();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Metode Penagihan ', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/metode_penagihan/add', ['dataMetodePenagihan' => $dataMetodePenagihan]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        }
    }

    public function edit()
    {
        $project = $this->m_core->project();
        $dataMetodePenagihanJenis = $this->m_metode_penagihan->get_jenis();

        $status = 0;
        if ($this->input->post('code')) {
            $this->load->model('alert');
            $status = $this->m_metode_penagihan->edit([
                'id' => $this->input->get('id'),
                'project_id' => $project->id,
                'metode_penagihan_jenis_id' => $this->input->post("jenis_metode_penagihan"),
                'code' => $this->input->post('code'),
                'metode_penagihan' => $this->input->post('metode_penagihan'),
                'biaya_admin' => $this->input->post('biaya_admin'),
                'coa' => $this->input->post('coa'),
                'keterangan' => $this->input->post('keterangan'),
                'active' => $this->input->post('status'),
            ]);
            $this->alert->css();
        }

        if ($this->m_metode_penagihan->cek($this->input->get('id'))) {
            $dataMetodePenagihan = $this->m_metode_penagihan->get();
            $dataMetodePenagihanSelect = $this->m_metode_penagihan->getSelect($this->input->get('id'));
            // $dataPTCOA = $this->m_metode_penagihan->get_all_pt_coa();
            $this->load->model('m_coa');
            $dataPTCOA = $this->m_coa->get_isjournal();

            $this->load->model('m_log');
            $data = $this->m_log->get('metode_penagihan', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Accounting > Metode Penagihan', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/metode_penagihan/edit', ['dataMetodePenagihan' => $dataMetodePenagihan, 'data_select' => $dataMetodePenagihanSelect, 'dataPTCOA' => $dataPTCOA, 'data' => $data,'dataMetodePenagihanJenis'=>$dataMetodePenagihanJenis]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url() . '/P_master_metode_penagihan');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_metode_penagihan->delete([
            'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_metode_penagihan->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Metode Penagihan', 'subTitle' => 'List']);
        $this->load->view('proyek/master/metode_penagihan/view', ['data' => $data]);
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
