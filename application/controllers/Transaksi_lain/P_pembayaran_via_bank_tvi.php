<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_pembayaran_via_bank_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_pembayaran_via_bank_tvi');
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
        $data = $this->m_pembayaran_via_bank_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran Via Bank', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/pembayaran_via_bank_tvi/view', [ 'data' => $data  ]);
    
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $dataBank = $this->m_pembayaran_via_bank_tvi->getBank();
        $kode_transfer = "CG/TRANSFERTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_pembayaran_via_bank_tvi->last_id()+1);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran Via Bank', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/pembayaran_via_bank_tvi/add', [
            'dataBank' => $dataBank , 'kode_transfer'=> $kode_transfer
    ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = $this->m_pembayaran_via_bank_tvi->save([

            'kode' => $this->input->post('kode'),
            'tanggal_pembayaran' => $this->input->post('tanggal_pembayaran'),
            'bank' => $this->input->post('bank'),
            'jenis_pembayaran' => $this->input->post('jenis_pembayaran'),
            'nomor_rekening' => $this->input->post('nomor_rekening'),
            'nama_rekening' => $this->input->post('nama_rekening'),
            'total_transfer' => $this->input->post('total_transfer'),
            'keterangan' => $this->input->post('keterangan'),
            'status' => $this->input->post('status'),
                                 

        ]);

        $this->load->model('alert');
        $dataBank = $this->m_pembayaran_via_bank_tvi->getBank();
        $kode_transfer = "CG/TRANSFERTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_pembayaran_via_bank_tvi->last_id());
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran Via Bank ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/pembayaran_via_bank_tvi/add', [ 'dataBank' => $dataBank , 'kode_transfer'=> $kode_transfer]
 );
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
        if ($this->input->post('kode')) {
            $this->load->model('alert');

            $status = $this->m_pembayaran_via_bank_tvi->edit([


            'id' => $this->input->get('id'),
            'kode' => $this->input->post('kode'),
            'tanggal_pembayaran' => $this->input->post('tanggal_pembayaran'),
            'bank' => $this->input->post('bank'),
            'jenis_pembayaran' => $this->input->post('jenis_pembayaran'),
            'nomor_rekening' => $this->input->post('nomor_rekening'),
            'nama_rekening' => $this->input->post('nama_rekening'),
            'total_transfer' => $this->input->post('total_transfer'),
            'keterangan' => $this->input->post('keterangan'),
            
            ]);
            $this->alert->css();
        }

        if ($this->m_pembayaran_via_bank_tvi->cek($this->input->get('id'))) {
            $dataPembayaranViaBank = $this->m_pembayaran_via_bank_tvi->getAll();
            $dataPembayaranViaBankSelect = $this->m_pembayaran_via_bank_tvi->getSelect($this->input->get('id'));
            $dataBank = $this->m_pembayaran_via_bank_tvi->getBank();
            $kode_transfer = "CG/TRANSFERTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_pembayaran_via_bank_tvi->last_id());
         
            $this->load->model('m_log');
            $data = $this->m_log->get('t_tvi_transfer', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Transaksi Lain > TV & Internet > Pembayaran Via Bank', 'subTitle' => 'Edit']);
            $this->load->view('proyek/transaksi_lain/pembayaran_via_bank_tvi/edit', ['dataPembayaranViaBank' => $dataPembayaranViaBank, 'data_select' => 
                $dataPembayaranViaBankSelect,  'dataBank' => $dataBank , 'kode_transfer'=> $kode_transfer,
     'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_pembayaran_via_bank_tvi');
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

        $data = $this->m_pembayaran_via_bank_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi_lain > Tv & Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/pembayaran_via_bank_tvi/view', ['data' => $data]);
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
