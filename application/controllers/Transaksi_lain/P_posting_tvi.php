<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_posting_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_posting_tvi');
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
        //$data = $this->m_posting_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Posting', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/posting_tvi/view');
    
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $dataTransfer = $this->m_posting_tvi->getTransfer();
        $dataCustomer = $this->m_posting_tvi->getCustomer();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Posting', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/posting_tvi/add', [
            'dataTransfer' => $dataTransfer,  'dataCustomer' => $dataCustomer
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = $this->m_posting_tvi->save([

            'pilih_transfer' => $this->input->post('pilih_transfer'),
            'customer' => $this->input->post('customer'),
            'kode_transfer' => $this->input->post('kode_transfer'),
            'tanggal' => $this->input->post('tanggal'),
            'bank' => $this->input->post('bank'),
            'nomor_rekening' => $this->input->post('nomor_rekening'),
            'nama_rekening' => $this->input->post('nama_rekening'),
            'total_bayar' => $this->input->post('total_bayar'),
            'total_tagihan' => $this->input->post('total_tagihan'),
           
         

        ]);

        $this->load->model('alert');
        $dataTransfer = $this->m_posting_tvi->getTransfer();
        $dataCustomer = $this->m_posting_tvi->getCustomer();
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Posting ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/posting_tvi/add', ['dataTransfer' => $dataTransfer, 'dataCustomer' => $dataCustomer]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function edit()
    {
        $status = 0;
        if ($this->input->post('code')) {
            $this->load->model('alert');

            $status = $this->m_posting_tvi->edit([
            'id' => $this->input->get('id'),
            'pilih_transfer' => $this->input->post('pilih_transfer'),
            'customer' => $this->input->post('customer'),
            'kode_transfer' => $this->input->post('kode_transfer'),
            'tanggal' => $this->input->post('tanggal'),
            'bank' => $this->input->post('bank'),
            'nomor_rekening' => $this->input->post('nomor_rekening'),
            'nama_rekening' => $this->input->post('nama_rekening'),
            'total_bayar' => $this->input->post('total_bayar'),
            'total_transfer' => $this->input->post('total_transfer'),
            ]);
            $this->alert->css();
        }

        if ($this->m_posting_tvi->cek($this->input->get('id'))) {
            $dataPosting = $this->m_posting_tvi->get();
            $dataPostingSelect = $this->m_posting_tvi->getSelect($this->input->get('id'));
         
            $this->load->model('m_log');
            $data = $this->m_log->get('t_tvi_transfers', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Transaksi Lain > TV & Internet > Posting', 'subTitle' => 'Edit']);
            $this->load->view('proyek/transaksi_lain/posting_tvi/edit', ['dataPosting' => $dataPosting, 'data_select' => $dataPostingSelect, 'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_posting_tvi');
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

        $status = $this->m_pembayaran_via_bank_tvi->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_posting_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi_lain > Tv & Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/posting_tvi/view', ['data' => $data]);
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


    public function lihat_transfer()
    {
        $pilih_transfer = $this->input->get('pilih_transfer');

        echo json_encode($this->m_posting_tvi->getTransfer2($pilih_transfer));
    }

      public function lihat_registrasi()
    {
       

        $customer = $this->input->get('customer');

        echo json_encode($this->m_posting_tvi->getRegistrasi($customer));
    }


      public function lihat_tagihan()
    {
       

        $id_bill = $this->input->get('id');

        echo json_encode($this->m_posting_tvi->getTagihan($id_bill));
    }


  
}
