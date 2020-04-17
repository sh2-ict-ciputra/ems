<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_monitoring_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_monitoring_tvi');
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
       
        $data = $this->m_monitoring_tvi->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Monitoring', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/monitoring_tvi/view' , ['data' => $data  ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


   

      public function detail()
    {

        $id =  $this->input->get('id');

        $dataCekTanggal = $this->m_monitoring_tvi->getCekTanggal($id);
        $dataListTagihan = $this->m_monitoring_tvi->getListTagihan($id);
        $dataListPembayaran = $this->m_monitoring_tvi->getListPembayaran($id);
        $dataRegistrasi = $this->m_monitoring_tvi->getRegistrasi($id);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Detail Monitoring', 'subTitle' => 'Detail']);
        $this->load->view('proyek/transaksi_lain/monitoring_tvi/view_detail', [
            'dataListTagihan' => $dataListTagihan ,   'dataListPembayaran' => $dataListPembayaran, 'dataRegistrasi' => 
            $dataRegistrasi, 'dataCekTanggal' => $dataCekTanggal
        ]);
        

        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


      public function add_tagihan()
    {



        $id =  $this->input->get('id'); 


        $dataRegistrasi = $this->m_monitoring_tvi->getRegistrasi($id);
        $dataPaket = $this->m_monitoring_tvi->getPaket();
        $kode_tagihan = "CG/TAGIHANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_monitoring_tvi->last_id()+1);  
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Aktifasi', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/monitoring_tvi/add', [
            'dataRegistrasi' => $dataRegistrasi, 'dataPaket' => $dataPaket, 'kode_tagihan'=> $kode_tagihan

        ]);
      
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }



     public function save_tagihan()
    {
        $status = $this->m_monitoring_tvi->save_tagihan([

            'id' => $this->input->get('id'),
            'jenis_pemasangan' => $this->input->post('jenis_pemasangan'),
            'nomor_billing' => $this->input->post('nomor_billing'),
            'tanggal' => $this->input->post('tanggal'),
            'pilih_paket' => $this->input->post('pilih_paket'),
            'total' => $this->input->post('total'),
            
           
         

        ]);

        $this->load->model('alert');
        $dataRegistrasi = $this->m_monitoring_tvi->getRegistrasi($this->input->get('id')); 
        $dataPaket = $this->m_monitoring_tvi->getPaket();
        $kode_tagihan = "CG/TAGIHANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_monitoring_tvi->last_id()+1);     
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Monitoring ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/monitoring_tvi/add', ['dataRegistrasi' => $dataRegistrasi, 'kode_tagihan'=> $kode_tagihan

        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }


    public function edit_tagihan()
    {
        $status = 0;
        if ($this->input->post('nomor_billing')) {
            $this->load->model('alert');

            $status = $this->m_monitoring_tvi->edit_tagihan([


                'id' => $this->input->get('id'),
                'registrasi_id' => $this->input->post('registrasi_id'),                     
                'jenis_pemasangan' => $this->input->post('jenis_pemasangan'),
                'nomor_billing' => $this->input->post('nomor_billing'), 
                'tanggal' => $this->input->post('tanggal'),
                'pilih_paket' => $this->input->post('pilih_paket'),
                'total' => $this->input->post('total'),
            
             
            ]);
            $this->alert->css();
        }

        if ($this->m_monitoring_tvi->cek($this->input->get('id'))) {


           
           
            $dataRegistrasiSelect = $this->m_monitoring_tvi->getSelect($this->input->get('id'));
          //    echo '<PRE>';

          // print_r( $dataRegistrasiSelect );

          // echo '</PRE>';
            $dataPaket = $this->m_monitoring_tvi->getPaket();
            $kode_tagihan = "CG/TAGIHANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_monitoring_tvi->last_id());  
            $this->load->model('m_log');
            $data = $this->m_log->get('t_tvi_tagihan', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Edit Tagihan', 'subTitle' => 'Edit']);
            $this->load->view('proyek/transaksi_lain/monitoring_tvi/edit', [ 'data_select' => 
                $dataRegistrasiSelect,'dataPaket' => $dataPaket, 'kode_tagihan' => $kode_tagihan, 'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/transaksi_lain/P_monitoring_tvi');
        }
        var_dump($status);
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
     
        } elseif ($status == 'Tidak Ada Perubahan') {
            $this->load->view('core/alert', ['title' => 'Tidak Ada Perubahan', 'text' => 'Data Tidak Ada Perubahan', 'type' => 'danger']);
        }
    }



     public function lihat_paket()
    {
        $pilih_paket = $this->input->post('pilih_paket');

        echo json_encode($this->m_monitoring_tvi->getPaket2($pilih_paket));
    }



    
  
}
