<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_aktifasi_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_aktifasi_tvi');
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
        $data = $this->m_aktifasi_tvi->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Aktifasi', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/aktifasi_tvi/view' , ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


     public function add()
    {

        $id =  $this->input->get('id');

        $dataRegistrasi = $this->m_aktifasi_tvi->getRegistrasi($id);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Aktifasi', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/aktifasi_tvi/add', [
            'dataRegistrasi' => $dataRegistrasi
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


     public function save()
    {
        $status = $this->m_aktifasi_tvi->save([


            'id' => $this->input->post('id'),
            'id_tagihan' => $this->input->post('id_tagihan'),
            'nomor_registrasi' => $this->input->post('nomor_registrasi'),
            'customer' => $this->input->post('customer'),
            'jenis_paket' => $this->input->post('jenis_paket'),
            'tanggal_pemasangan_berakhir' => $this->input->post('tanggal_pemasangan_berakhir'),
            'tanggal_awal' => $this->input->post('tanggal_awal'),
            'tanggal_akhir' => $this->input->post('tanggal_akhir'),
           
         

        ]);

        $this->load->model('alert');
        $dataRegistrasi = $this->m_aktifasi_tvi->getRegistrasi($this->input->post('id'));       
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Aktifasi ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/aktifasi_tvi/add', ['dataRegistrasi' => $dataRegistrasi]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        
        } elseif ($status == 'Tidak Ada Perubahan') {
            $this->load->view('core/alert', ['title' => 'Tidak Ada Perubahan', 'text' => 'Data Tidak Ada Perubahan', 'type' => 'danger']);
        }
    }


    
  
}
