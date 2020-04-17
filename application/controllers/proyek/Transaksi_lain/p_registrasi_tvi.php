<?php

defined('BASEPATH') or exit('No direct script access allowed');

class p_registrasi_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_registrasi_tvi');
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
        $data = $this->m_registrasi_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Registrasi', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_tvi/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $dataUnit = $this->m_registrasi_tvi->getUnit();
        $dataCustomer = $this->m_registrasi_tvi->getCustomer();
        $dataPaket = $this->m_registrasi_tvi->getPaket();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Registrasi', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/registrasi_tvi/add', ['dataUnit' => $dataUnit,  'dataCustomer' => $dataCustomer, 'dataPaket' => 
            $dataPaket    ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = $this->m_registrasi_tvi->save([

            'pilih_unit' => $this->input->post('pilih_unit'),
            'unit' => $this->input->post('unit_name'),
            'customer_name' => $this->input->post('customer_name'),
            'jenis_pemasangan' => $this->input->post('jenis_pemasangan'),
            'nomor_telepon' => $this->input->post('nomor_telepon'),
            'nomor_handphone' => $this->input->post('nomor_handphone'),
            'email' => $this->input->post('email'),
            'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan'),
            'jenis_paket_id' => $this->input->post('pilih_paket'),
            'harga_paket' => $this->input->post('harga_paket'),
            'harga_lain_lain' => $this->input->post('harga_pasang'),
            'diskon' => $this->input->post('diskon'),
            'total' => $this->input->post('total'),
            'keterangan' => $this->input->post('keterangan_paket'),
            'nomor_registrasi' => $this->input->post('nomor_registrasi'),


          
            'customer_name' => $this->input->post('customer_name2'),
            'nomor_registrasi' => $this->input->post('nomor_registrasi2'),
            'nomor_telepon' => $this->input->post('nomor_telepon2'),
            'nomor_handphone' => $this->input->post('nomor_handphone2'),
            'email' => $this->input->post('email2'),
            'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan'),
            'jenis_paket_id' => $this->input->post('pilih_paket'),
            'harga_paket' => $this->input->post('harga_paket'),
            'harga_lain_lain' => $this->input->post('harga_pasang'),
            'diskon' => $this->input->post('diskon'),
            'total' => $this->input->post('total'),
            'keterangan' => $this->input->post('keterangan_paket'),
            



            

        ]);

        $this->load->model('alert');
        $dataUnit = $this->m_registrasi_tvi->getUnit();
        $dataCustomer = $this->m_registrasi_tvi->getCustomer();
        $dataPaket = $this->m_registrasi_tvi->getPaket();
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Registrasi ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/registrasi_tvi/add', ['dataUnit' => $dataUnit, 'dataCustomer' => $dataCustomer,
         'dataPaket' => $dataPaket ]);
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

            $status = $this->m_cara_pembayaran->edit([
                'id' => $this->input->get('id'),
                'code' => $this->input->post('code'),
                'jenis_pembayaran' => $this->input->post('jenis_pembayaran'),
                'biaya_admin' => $this->input->post('biaya_admin'),
                'coa' => $this->input->post('coa'),
                'keterangan' => $this->input->post('keterangan'),
                'active' => $this->input->post('status'),
            ]);
            $this->alert->css();
        }

        if ($this->m_cara_pembayaran->cek($this->input->get('id'))) {
            $dataCaraPembayaran = $this->m_cara_pembayaran->get();
            $dataCaraPembayaranSelect = $this->m_cara_pembayaran->getSelect($this->input->get('id'));
            $dataPTCOA = $this->m_cara_pembayaran->get_all_pt_coa();
            $this->load->model('m_log');
            $data = $this->m_log->get('cara_pembayaran', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Accounting > Cara Pembayaran', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/cara_pembayaran/edit', ['dataCaraPembayaran' => $dataCaraPembayaran, 'data_select' => $dataCaraPembayaranSelect, 'dataPTCOA' => $dataPTCOA, 'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_cara_pembayaran');
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


    public function lihat_unit()
    {
        $pilih_unit = $this->input->post('pilih_unit');

        echo json_encode($this->m_registrasi_tvi->getUnit2($pilih_unit));
    }


    public function lihat_customer()
    {
        $pilih_customer = $this->input->post('pilih_customer');

        echo json_encode($this->m_registrasi_tvi->getCustomer2($pilih_customer));
    }


     public function lihat_paket()
    {
        $pilih_paket = $this->input->post('pilih_paket');

        echo json_encode($this->m_registrasi_tvi->getPaket2($pilih_paket));
    }



}
