<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_pembayaran_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_pembayaran_tvi');
        $this->load->model('transaksi_lain/m_biaya_tambahan_tvi');
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
        $data = $this->m_pembayaran_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/pembayaran_tvi/view', [
            'data' => $data
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $dataRegistrasi = $this->m_pembayaran_tvi->getRegistrasi();
        $dataCaraPembayaran = $this->m_pembayaran_tvi->getCaraPembayaran();
        $kode_pay = "CG/PEMBAYARANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_pembayaran_tvi->last_id()+1);
       // var_dump($dataCaraPembayaran);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/pembayaran_tvi/add', [
            'dataRegistrasi' => $dataRegistrasi, 'dataCaraPembayaran' => $dataCaraPembayaran, 'kode_pay'=> $kode_pay
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


    public function add_reg()
    {


        
       $id =  $this->input->get('id');


        $dataRegistrasi = $this->m_pembayaran_tvi->getRegistrasi2($id);
        $dataCaraPembayaran = $this->m_pembayaran_tvi->getCaraPembayaran();
        $kode_pay = "CG/PEMBAYARANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_pembayaran_tvi->last_id()+1);
       // var_dump($dataCaraPembayaran);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/pembayaran_tvi/add_reg', [
            'dataRegistrasi' => $dataRegistrasi, 'dataCaraPembayaran' => $dataCaraPembayaran, 'kode_pay'=> $kode_pay
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function pembayaran_biaya_tambahan()
    {


        
       $id =  $this->input->get('id');

        $dataBiayaTambahanSelect = $this->m_biaya_tambahan_tvi->getSelect($this->input->get('id'));
        $dataBiayaTambahanDetail = $this->m_biaya_tambahan_tvi->get_biaya_tambahan_detail($this->input->get('id'));
        $dataRegistrasi = $this->m_pembayaran_tvi->getRegistrasi2($id);
        $dataCaraPembayaran = $this->m_pembayaran_tvi->getCaraPembayaran();
        $kode_pay = "CG/PEMBAYARANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_pembayaran_tvi->last_id()+1);
       // var_dump($dataCaraPembayaran);

       $this->load->model('m_log');
        $data = $this->m_log->get('t_tvi_pembayaran', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/pembayaran_tvi/add_biaya', [
            'dataRegistrasi' => $dataRegistrasi, 'dataCaraPembayaran' => $dataCaraPembayaran, 'kode_pay'=> $kode_pay,  'data_select' => $dataBiayaTambahanSelect,
            'dataBiayaTambahanDetail' => $dataBiayaTambahanDetail, 'data' => $data
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


    public function save()
    {
        $status = $this->m_pembayaran_tvi->save([

            
            'pilih_reg' => $this->input->post('pilih_reg'),
            'registrasi_id' => $this->input->post('registrasi_id'),
            'nomor_pembayaran' => $this->input->post('nomor_pembayaran'),
            'tanggal_pembayaran' => $this->input->post('tanggal_pembayaran'),
            'cara_pembayaran' => $this->input->post('cara_pembayaran'),
            'jenis_layanan' => $this->input->post('jenis_layanan'),
            'keterangan' => $this->input->post('keterangan'),
            'paket_layanan' => $this->input->post('paket_layanan'),
            'nomor_ref_pembayaran' => $this->input->post('nomor_ref_pembayaran'),
            'nomor_fisik_kwitansi' => $this->input->post('nomor_fisik_kwitansi'),
            'total_tagihan' => $this->input->post('total_tagihan'),
            'total_bayar' => $this->input->post('total_bayar'),
            'discount' => $this->input->post('discount'),
            'nomor_tagihan' => $this->input->post('nomor_tagihan'),        
                      

        ]);

        $this->load->model('alert');
       $dataRegistrasi = $this->m_pembayaran_tvi->getRegistrasi();
        $dataCaraPembayaran = $this->m_pembayaran_tvi->getCaraPembayaran();
        $kode_pay = "CG/PEMBAYARANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_pembayaran_tvi->last_id()+1);
        $this->load->view('core/header');
        $this->alert->css();


        
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/pembayaran_tvi/add', ['dataRegistrasi' => $dataRegistrasi, 'dataCaraPembayaran' => 
            $dataCaraPembayaran , 'kode_pay'=> $kode_pay ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }


    public function save_biaya_tambahan()
    {
        $status = $this->m_pembayaran_tvi->save_biaya_tambahan([

          
            'biaya_tambahan_id' => $this->input->post('biaya_tambahan_id'),
            'registrasi_id' => $this->input->post('registrasi_id'),
            'nomor_pembayaran' => $this->input->post('nomor_pembayaran'),
            'tanggal_pembayaran' => $this->input->post('tanggal_pembayaran'),
            'cara_pembayaran' => $this->input->post('cara_pembayaran'),
            'keterangan' => $this->input->post('keterangan'),
            'nomor_ref_pembayaran' => $this->input->post('nomor_ref_pembayaran'),
            'nomor_fisik_kwitansi' => $this->input->post('nomor_fisik_kwitansi'),
            'nomor_tagihan' => $this->input->post('nomor_tagihan'),
            'total_tagihan' => $this->input->post('total_akhir'),
            'total_bayar' => $this->input->post('total_bayar'),
            'discount' => $this->input->post('discount'),
                
                      

        ]);

        $this->load->model('alert');
       
        $dataBiayaTambahanSelect = $this->m_biaya_tambahan_tvi->getSelect($this->input->get('id'));
        $dataBiayaTambahanDetail = $this->m_biaya_tambahan_tvi->get_biaya_tambahan_detail($this->input->get('id'));
        $dataCaraPembayaran = $this->m_pembayaran_tvi->getCaraPembayaran();
        $kode_pay = "CG/PEMBAYARANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_pembayaran_tvi->last_id());
       // var_dump($dataCaraPembayaran);

        $this->load->view('core/header');
        $this->alert->css();


        
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Pembayaran ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/pembayaran_tvi/add_biaya', ['dataCaraPembayaran' => $dataCaraPembayaran, 'kode_pay'=> $kode_pay,  'data_select' => $dataBiayaTambahanSelect,
        'dataBiayaTambahanDetail' => $dataBiayaTambahanDetail,   'data_select' => $dataBiayaTambahanSelect  ]);
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
        if ($this->input->post('nomor_pembayaran')) {
            $this->load->model('alert');

            $status = $this->m_pembayaran_tvi->edit([
            'id' => $this->input->get('id'),
            'nomor_pembayaran' => $this->input->post('nomor_pembayaran'),
            'tanggal_pembayaran' => $this->input->post('tanggal_pembayaran'),
            'cara_pembayaran' => $this->input->post('cara_pembayaran'),
            'jenis_layanan' => $this->input->post('jenis_layanan'),
            'keterangan' => $this->input->post('keterangan'),
            'paket_layanan' => $this->input->post('paket_layanan'),
            'nomor_ref_pembayaran' => $this->input->post('nomor_ref_pembayaran'),
            'nomor_fisik_kwitansi' => $this->input->post('nomor_fisik_kwitansi'),
            'registrasi_id' => $this->input->post('registrasi_id'),
            'total_tagihan' => $this->input->post('total_tagihan'),
            'total_bayar' => $this->input->post('total_bayar'),
            'discount' => $this->input->post('discount'),
            'nomor_tagihan' => $this->input->post('nomor_tagihan'),        
          
            
           
            ]);
            $this->alert->css();
        }

        if ($this->m_pembayaran_tvi->cek($this->input->get('id'))) {
           
            $dataPembayaranSelect = $this->m_pembayaran_tvi->getSelect($this->input->get('id'));

            // echo '<PRE>';
            // print_r($dataPembayaranSelect);
            // echo '<PRE>';
         
            $this->load->model('m_log');
            $data = $this->m_log->get('t_tvi_pembayaran', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Transaksi Lain > TV & Internet > Pembayaran', 'subTitle' => 'Edit']);
            $this->load->view('proyek/transaksi_lain/pembayaran_tvi/edit', [ 'data_select' => $dataPembayaranSelect, 'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_pembayaran_tvi');
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

    public function lihat_reg()
    {
        $pilih_reg = $this->input->get('pilih_reg');

        echo json_encode($this->m_pembayaran_tvi->getRegistrasi2($pilih_reg));
    }


}
