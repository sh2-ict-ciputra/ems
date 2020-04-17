<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_monitoring_liaison_officer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_monitoring_liaison_officer');
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
        $data = $this->m_monitoring_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liaison Officer > Monitoring', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function tambahBiaya()
    {
        $dataUnit = $this->m_monitoring_liaison_officer->getUnit();
        $dataKategori = $this->m_monitoring_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_monitoring_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_monitoring_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_monitoring_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_monitoring_liaison_officer->get_paket();
        $dataItemCharge = $this->m_monitoring_liaison_officer->get_item_charge($this->input->get('id'));
        $this->load->model('m_log');
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Monitoring ', 'subTitle' => 'Tambah Biaya']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/add', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket, 'dataItemCharge'=>$dataItemCharge]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = 0;
        if ($this->input->post('nomor_registrasi')) {
            $this->load->model('alert');
            $status = $this->m_monitoring_liaison_officer->save([
                'id' => $this->input->get('id'),
                'id_item' => $this->input->post('id_item[]'),
                'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                'deposit_masuk' => $this->input->post('deposit_masuk'),
                'deposit_pemakaian' => $this->input->post('deposit_pemakaian'),
                'sisa_deposit' => $this->input->post('sisa_deposit'),
                'harga_satuan' => $this->input->post('harga_satuan[]'),
                'unit_id' => $this->input->post('unit_name')
            ]);
            $this->alert->css();
        }
        $data = $this->m_monitoring_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liaison Officer > Monitoring', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        // if ($this->m_registrasi_liaison_officer->cek($this->input->get('id'))) {
            
        // } else {
        //     redirect(site_url().'/P_master_cara_pembayaran');
        // }
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Simpan', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function tambahBiaya2()
    {
        $dataUnit = $this->m_monitoring_liaison_officer->getUnit();
        $dataKategori = $this->m_monitoring_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_monitoring_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_monitoring_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_monitoring_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_monitoring_liaison_officer->get_paket();
        $dataItemCharge = $this->m_monitoring_liaison_officer->get_item_charge_tambah($this->input->get('id'));
        $this->load->model('m_log');
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Monitoring ', 'subTitle' => 'Tambah Biaya']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/add2', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket, 'dataItemCharge'=>$dataItemCharge]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save2()
    {
        $status = 0;
        if ($this->input->post('nomor_registrasi')) {
            $this->load->model('alert');
            $status = $this->m_monitoring_liaison_officer->save2([
                'id' => $this->input->get('id'),
                'id_item' => $this->input->post('id_item[]'),
                'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                // 'deposit_masuk' => $this->input->post('deposit_masuk'),
                'deposit_pemakaian2' => $this->input->post('deposit_pemakaian2'),
                'sisa_deposit' => $this->input->post('sisa_deposit'),
                'harga_satuan' => $this->input->post('harga_satuan[]')
            ]);
            $this->alert->css();
        }
        $data = $this->m_monitoring_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liaison Officer > Monitoring', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        // if ($this->m_registrasi_liaison_officer->cek($this->input->get('id'))) {
            
        // } else {
        //     redirect(site_url().'/P_master_cara_pembayaran');
        // }
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Simpan', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function pembayaran1()
    {
        $dataUnit = $this->m_monitoring_liaison_officer->getUnit();
        $dataKategori = $this->m_monitoring_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_monitoring_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_monitoring_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_monitoring_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_monitoring_liaison_officer->get_paket();
        $dataItemCharge = $this->m_monitoring_liaison_officer->get_item_charge($this->input->get('id'));
        $this->load->model('m_log');
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Monitoring', 'subTitle' => 'Pembayaran Survei Awal']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/payment1', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket, 'dataItemCharge'=>$dataItemCharge]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function aksi_pembayaran1()
    {
        $status = 0;
        if ($this->input->post('nomor_registrasi')) {
            $this->load->model('alert');

            $status = $this->m_monitoring_liaison_officer->pembayaran1([
                'id' => $this->input->get('id'),
                'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                'total_survei1' => $this->input->post('total_survei1'),
                'status_bayar_survei1' => $this->input->post('status_bayar_survei1'),
            ]);
            $this->alert->css();
        }
        $data = $this->m_monitoring_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Monitoring', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        // if ($this->m_registrasi_liaison_officer->cek($this->input->get('id'))) {
            
        // } else {
        //     redirect(site_url().'/P_master_cara_pembayaran');
        // }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Simpan', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function pembayaran2()
    {
        $dataUnit = $this->m_monitoring_liaison_officer->getUnit();
        $dataKategori = $this->m_monitoring_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_monitoring_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_monitoring_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_monitoring_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_monitoring_liaison_officer->get_paket();
        $dataItemCharge = $this->m_monitoring_liaison_officer->get_item_charge_tambah($this->input->get('id'));
        $this->load->model('m_log');
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Monitoring', 'subTitle' => 'Pembayaran Survei Akhir']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/payment2', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket, 'dataItemCharge'=>$dataItemCharge]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function aksi_pembayaran2()
    {
        $status = 0;
        if ($this->input->post('nomor_registrasi')) {
            $this->load->model('alert');

            $status = $this->m_monitoring_liaison_officer->pembayaran2([
                'id' => $this->input->get('id'),
                'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                'total_survei2' => $this->input->post('total_survei2'),
                'status_bayar_survei2' => $this->input->post('status_bayar_survei2'),
            ]);
            $this->alert->css();
        }
        $data = $this->m_monitoring_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Monitoring', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/monitoring_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        // if ($this->m_registrasi_liaison_officer->cek($this->input->get('id'))) {
            
        // } else {
        //     redirect(site_url().'/P_master_cara_pembayaran');
        // }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Simpan', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }
}
?>