<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_biaya_tambahan_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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
        $data = $this->m_biaya_tambahan_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Biaya Tambahan', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/biaya_tambahan_tvi/view', ['data' => $data ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

     public function add()
    {


     

        $dataRegistrasi = $this->m_biaya_tambahan_tvi->getRegistrasi();
        $kode_tagihan = "CG/TAGIHANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_biaya_tambahan_tvi->last_id()+1);
       // var_dump($dataCaraPembayaran);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Biaya Tambahan', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/biaya_tambahan_tvi/add', [
            'dataRegistrasi' => $dataRegistrasi,  'kode_tagihan'=> $kode_tagihan
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


    public function add_reg()
    {


       $id =  $this->input->get('id');


        $dataRegistrasi = $this->m_biaya_tambahan_tvi->getRegistrasi2($id);
        $kode_tagihan = "CG/TAGIHANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_biaya_tambahan_tvi->last_id()+1);
       // var_dump($dataCaraPembayaran);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Biaya Tambahan', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/biaya_tambahan_tvi/add_reg', [
            'dataRegistrasi' => $dataRegistrasi,  'kode_tagihan'=> $kode_tagihan
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }


    

    public function save()
    {


     


        $status = $this->m_biaya_tambahan_tvi->save([

            'registrasi_id' => $this->input->post('registrasi_id'),
            'nomor_tagihan' => $this->input->post('nomor_tagihan_biaya'),
            'tanggal_tagihan' => $this->input->post('tanggal_tagihan_biaya'),
            'jenis_paket_id' => $this->input->post('jenis_paket_id'),
            'total' => $this->input->post('total'),
            'nomor_registrasi' => $this->input->post('nomor_registrasi'),
            'total_tagihan_biaya' => $this->input->post('total_tagihan_biaya'),
            'tanggal_tagihan' => $this->input->post('tanggal_tagihan_biaya'),
            'keterangan' => $this->input->post('keterangan'),


            'item' => $this->input->post('item[]'),
            'quantity' => $this->input->post('volume[]'),
            'satuan' => $this->input->post('satuan[]'),
            'harga_satuan' => $this->input->post('harga_satuan[]'),            
            'total' => $this->input->post('total[]'),
            'description' => $this->input->post('description[]'),



           
        ]);

        $this->load->model('alert');
        $dataRegistrasi = $this->m_biaya_tambahan_tvi->getRegistrasi2($this->input->post('registrasi_id'));
        $kode_tagihan = "CG/TAGIHANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_biaya_tambahan_tvi->last_id());
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Biaya Tambahan', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/biaya_tambahan_tvi/add', ['dataRegistrasi' => $dataRegistrasi, 'kode_tagihan'=> $kode_tagihan ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
        elseif ($status == 'Tidak Ada Perubahan') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Tidak Ada Perubahan', 'type' => 'danger']);
        }
    }

    public function edit()
    {
        $status = 0;
        if ($this->input->post('nomor_tagihan_biaya_tambahan')) {
            $this->load->model('alert');

            $status = $this->m_biaya_tambahan_tvi->edit([
            'id' => $this->input->get('id'),

            'registrasi_id' => $this->input->post('registrasi_id'),
            'nomor_tagihan' => $this->input->post('nomor_tagihan_biaya_tambahan'),
            'tanggal_tagihan' => $this->input->post('tanggal_tagihan_biaya_tambahan'),
            'jenis_paket_id' => $this->input->post('jenis_paket_id'),
            'total' => $this->input->post('total'),
            'nomor_registrasi' => $this->input->post('nomor_registrasi'),
            'total_tagihan' => $this->input->post('total_tagihan_biaya_tambahan'),
            'keterangan' => $this->input->post('keterangan'),


            'id_item' => $this->input->post('id_item[]'),
            'item' => $this->input->post('item[]'),
            'quantity' => $this->input->post('quantity[]'),
            'harga_satuan' => $this->input->post('harga_satuan[]'),
            'description' => $this->input->post('description[]'),
            'active_item' => 1,
            'delete_item' => 0,

          


            ]);
            $this->alert->css();
        }

        if ($this->m_biaya_tambahan_tvi->cek($this->input->get('id'))) {

              // echo '<PRE>';
              //  print_r($this->input->get('id'));
              // echo '</PRE>';
            
            $dataBiayaTambahanSelect = $this->m_biaya_tambahan_tvi->getSelect($this->input->get('id'));
            $dataBiayaTambahanDetail = $this->m_biaya_tambahan_tvi->get_biaya_tambahan_detail($this->input->get('id'));

             // echo '<PRE>';
             // print_r( $dataBiayaTambahanDetail);
             // echo '</PRE>';
            $this->load->model('m_log');
            $data = $this->m_log->get('t_tvi_biaya_tambahan', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Transaksi Lain > TV & Internet > Biaya Tambahan', 'subTitle' => 'Edit']);
            $this->load->view('proyek/transaksi_lain/biaya_tambahan_tvi/edit', ['data_select' => $dataBiayaTambahanSelect,'dataBiayaTambahanDetail' => 
                $dataBiayaTambahanDetail, 'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_biaya_tambahan_tvi');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }  elseif ($status == 'Tidak Ada Perubahan') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Tidak Ada Perubahan', 'type' => 'danger']);
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

        echo json_encode($this->m_biaya_tambahan_tvi->getRegistrasi2($pilih_reg));
    }





}
