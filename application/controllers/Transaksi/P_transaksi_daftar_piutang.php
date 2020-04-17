<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_transaksi_daftar_piutang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_bank');
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
        //$data = $this->m_bank->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service > Daftar Piutang', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi/daftar_piutang/view');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_bank');
        $dataBank = $this->m_bank->get();
        $dataPTCOA = $this->m_bank->get_all_pt_coa();
        $dataJenisService = $this->m_bank->get_jenis_service();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Bank', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/bank/add', ['dataBank' => $dataBank,  'dataPTCOA' => $dataPTCOA, 'dataJenisService' => $dataJenisService]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = 0;
        $status = $this->m_bank->save([
            'code' => $this->input->post('code'),
            'name' => $this->input->post('name'),
            'virtual_account' => $this->input->post('virtual_account'),
            'biaya_admin' => $this->input->post('biaya_admin'),
            'description' => $this->input->post('description'),
            'active' => 1,

            'no_rekening' => $this->input->post('nomor_rekening[]'),
            'service_id' => $this->input->post('service[]'),
            'coa_mapping_id' => $this->input->post('coa[]'),
            'active_rekening' => 1,
            'delet_rekening' => 0,
        ]);

        $this->load->model('alert');
        $this->load->model('m_bank');
        $dataBank = $this->m_bank->get();
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Bank', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/bank/add', ['dataBnak' => $dataBank]);
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
        $status = 0;
        if ($this->input->post('code')) {
            $this->load->model('alert');

            $status = $this->m_bank->edit([
                'id' => $this->input->get('id'),
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'virtual_account' => $this->input->post('virtual_account'),
                'biaya_admin' => $this->input->post('biaya_admin'),
                'description' => $this->input->post('description'),
                'active' => $this->input->post('active'),

                'id_rekening' => $this->input->post('id_rekening[]'),
                'no_rekening' => $this->input->post('nomor_rekening[]'),
                'service_id' => $this->input->post('service[]'),
                'coa_mapping_id' => $this->input->post('coa[]'),
                'active_rekening' => 1,
                'delet_rekening' => 0,
            ]);
            $this->alert->css();
        }

        if ($this->m_bank->cek($this->input->get('id'))) {
            $dataBank = $this->m_bank->get();
            $dataBankSelect = $this->m_bank->mapping_get($this->input->get('id'));
            $dataRekening = $this->m_bank->get_rekening($this->input->get('id'));
            $dataPTCOA = $this->m_bank->get_all_pt_coa();
            $dataJenisService = $this->m_bank->get_jenis_service();
            $this->load->model('m_log');
            $data = $this->m_log->get('bank', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Accounting > Bank', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/bank/edit', ['data' => $data, 'dataBank' => $dataBank, 'data_select' => $dataBankSelect, 'dataRekening' => $dataRekening, 'dataPTCOA' => $dataPTCOA, 'dataJenisService' => $dataJenisService]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_bank');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        }
    }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_bank->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_bank->get();
        // echo '<pre>';
        // print_r($this->m_bank->get_log_bank(24));
        // echo '</pre>';
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Bank', 'subTitle' => 'List']);
        $this->load->view('proyek/master/bank/view', ['data' => $data]);
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
