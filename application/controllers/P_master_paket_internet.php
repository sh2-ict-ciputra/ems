<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_paket_internet extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_paket_internet');
        $this->load->model('m_item_tvi');
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
        $data = $this->m_paket_internet->get_all();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/master/paket_internet/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_paket_internet');
        $dataGroupTvi = $this->m_paket_internet->get_group_tvi2();
        $dataItemPaket = $this->m_paket_internet->getItemPaket();
        $this->load->model('alert');
		
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Internet', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/paket_internet/add', ['dataGroupTvi' => $dataGroupTvi, 'dataItemPaket' => $dataItemPaket]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    // public function save()
    // {
    //     $status = $this->m_paket_internet->save([
    //         'group_tvi_id' => $this->input->post('group_tvi_id'),
    //         'kode' => $this->input->post('kode'),
    //         'nama_paket' => $this->input->post('nama_paket'),
    //         'bandwith' => $this->input->post('bandwith'),
    //         'hpp' => $this->input->post('hpp'),
    //         'harga_jual' => $this->input->post('harga_jual'),
    //         'biaya_pasang' => $this->input->post('biaya_pasang'),
    //         'biaya_registrasi' => $this->input->post('biaya_registrasi'),
    //         'keterangan' => $this->input->post('keterangan')
    //     ]);

    //     $this->load->model('alert');
    //     $this->load->model('m_paket_internet');
    //     $dataGroupTvi = $this->m_paket_internet->get_group_tvi2();
    //     $this->load->view('core/header');
    //     $this->alert->css();

    //     $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
    //     $this->load->view('core/top_bar');
    //     $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Internet ', 'subTitle' => 'Add']);
    //     $this->load->view('proyek/master/paket_internet/add', ['dataGroupTvi' => $dataGroupTvi]);
    //     $this->load->view('core/body_footer');
    //     $this->load->view('core/footer');
    //     if ($status == 'success') {
    //         $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
    //     } elseif ($status == 'double') {
    //         $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
    //     }
    // }


    public function edit()
    {
        if ($this->m_paket_internet->cek($this->input->get('id'))) {
            $dataPaketInternet = $this->m_paket_internet->get_all();
            $dataPaketInternetSelect = $this->m_paket_internet->getSelect_all($this->input->get('id'));
            $dataPaketItemDetail = $this->m_paket_internet->get_paket_tvi_item($this->input->get('id'));
            $dataItemPilih = $this->m_paket_internet->get_item_tvi2();
            $dataItemPaket = $this->m_paket_internet->getItemPaket();
            $dataGroupInternet = $this->m_paket_internet->get_group_tvi2();
            $this->load->model('alert');
            $this->load->model('m_log');
            $data = $this->m_log->get('paket_internet', $this->input->get('id'));
            $this->load->view('core/header');
            $this->alert->css();
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Internet', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/paket_internet/edit', ['data' => $data, 'dataPaketInternet' => $dataPaketInternet, 'data_select' => $dataPaketInternetSelect, 'dataGroupInternet' => $dataGroupInternet, 'dataItemPaket'=>$dataItemPaket, 'dataItemPilih'=>$dataItemPilih,'dataItemDetail'=>$dataPaketItemDetail]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url() . '/P_master_paket_internet');
        }
    }
    // public function edit()
    // {
    //     $status = 0;
    //     if ($this->input->post('group')) {
    //         $this->load->model('alert');

    //         $status = $this->m_paket_internet->edit([
    //             'id' => $this->input->get('id'),
    //             'group' => $this->input->post('group'),
    //             'kode' => $this->input->post('kode'),
    //             'nama_paket' => $this->input->post('nama_paket'),
    //             'bandwith' => $this->input->post('bandwith'),
    //             'hpp' => $this->input->post('hpp'),
    //             'harga_jual' => $this->input->post('harga_jual'),
    //             'biaya_pasang' => $this->input->post('biaya_pasang'),
    //             'biaya_registrasi' => $this->input->post('biaya_registrasi'),
    //             'keterangan' => $this->input->post('keterangan'),
    //             'active' => $this->input->post('active'),
    //         ]);
    //         $this->alert->css();
    //     }

    //     if ($this->m_paket_internet->cek($this->input->get('id'))) {
    //         $dataPaketInternet = $this->m_paket_internet->get_all();
    //         $dataPaketInternetSelect = $this->m_paket_internet->getSelect_all($this->input->get('id'));
    //         $dataPaketItemDetail = $this->m_paket_internet->get_paket_tvi_item($this->input->get('id'));
    //         $dataItemSelect = $this->m_paket_internet->get_item_tvi($this->input->get('id'));
    //         $dataItemPaket = $this->m_paket_internet->getItemPaket();
    //         $dataGroupInternet = $this->m_paket_internet->get_group_tvi2();
    //         $this->load->model('m_log');
    //         $data = $this->m_log->get('paket_internet', $this->input->get('id'));
    //         $this->load->view('core/header');
    //         $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
    //         $this->load->view('core/top_bar');
    //         $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Internet', 'subTitle' => 'Edit']);
    //         $this->load->view('proyek/master/paket_internet/edit', ['data' => $data, 'dataPaketInternet' => $dataPaketInternet, 'data_select' => $dataPaketInternetSelect, 'dataGroupInternet' => $dataGroupInternet, 'dataItemPaket'=>$dataItemPaket, 'dataItemSelect'=>$dataItemSelect,'dataItemDetail'=>$dataPaketItemDetail]);
    //         $this->load->view('core/body_footer');
    //         $this->load->view('core/footer');
    //     } else {
    //         redirect(site_url() . '/P_master_paket_internet');
    //     }

    //     if ($status == 'success') {
    //         $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
    //     } elseif ($status == 'double') {
    //         $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
    //     }
    // }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_paket_internet->delete([
            'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_paket_internet->get_all();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket TV Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/master/paket_internet/view', ['data' => $data]);
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

    public function ajax_save(){
        echo(json_encode($this->m_paket_internet->save($this->input->post())));
    }
    public function ajax_edit(){
        echo(json_encode($this->m_paket_internet->edit($this->input->get())));
    }

    public function get_info_item()
    {
        echo json_encode($this->m_paket_internet->getItemPaketInternet($this->input->post('id')));
    }
}
