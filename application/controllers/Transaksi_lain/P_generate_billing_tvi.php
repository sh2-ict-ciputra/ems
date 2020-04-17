<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_generate_billing_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_generate_billing_tvi');
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
        $dataKawasan  = $this->m_generate_billing_tvi->getKawasan();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Generate Billing', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/generate_billing_tvi/view' , ['dataKawasan' => $dataKawasan]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


   

      public function generate_billing()
    {
        $status = $this->m_generate_billing_tvi->generate_billing([

            
            'unit' => $this->input->post('unit[]'),
           
           
         

        ]);

        $this->load->model('alert');
        $dataKawasan  = $this->m_generate_billing_tvi->getKawasan();
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Generate Bill ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/generate_billing_tvi/view', ['dataKawasan' => $dataKawasan ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }


   

       public function lihat_blok()
    {
         $this->load->model('transaksi_lain/m_generate_billing_tvi');

        $kawasan_id = $this->input->post('kawasan');

        echo json_encode($this->m_generate_billing_tvi->getBlok($kawasan_id));
    }


    public function lihat_unit()
    {
        $this->load->model('transaksi_lain/m_generate_billing_tvi');

        $kawasan_id = $this->input->post('kawasan');


        $blok_id = $this->input->post('blok');


        echo json_encode($this->m_generate_billing_tvi->getUnit($kawasan_id, $blok_id));
    }


    
  
}
