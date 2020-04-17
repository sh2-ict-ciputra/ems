<?php

defined('BASEPATH') or exit('No direct script access allowed');

class liaison_item extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('proyek/master/m_liaison_item');
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
        $data = $this->m_liaison_item->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Item Liaison Officer', 'subTitle' => 'List']);
        $this->load->view('proyek/master/liaison_item/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $dataLiaison = $this->m_liaison_item->get_lo();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service > Item Liaison Officer', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/liaison_item/add', ['dataLiaison' => $dataLiaison]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = $this->m_liaison_item->save([
            'liaison_officer_id'    => $this->input->post('liaison_officer_id'),
            'item_kode'             => $this->input->post('item_kode[]'),
            'item_keterangan'       => $this->input->post('item_keterangan[]'),
            'item_range'       => $this->input->post('item_range[]'),
            'item_harga'       => $this->input->post('item_harga[]'),
            'outflow'       => $this->input->post('outflow[][]'),
            
        ]);
        
        // $this->load->model('alert');
        // $dataTransaksiLo = $this->m_liaison_item->get();
        // $this->load->view('core/header');
        // $this->alert->css();

        // $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        // $this->load->view('core/top_bar');
        // $this->load->view('core/body_header', ['title' => 'Master > Service > Item Liaison Officer ', 'subTitle' => 'Add']);
        // $this->load->view('proyek/master/liaison_item/add', ['dataTransaksiLo' => $dataTransaksiLo]);
        // $this->load->view('core/body_footer');
        // $this->load->view('core/footer');
        // if ($status == 'success') {
        //     $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        // } elseif ($status == 'double') {
        //     $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        // }
    }

    public function edit()
    {
        $status = 0;
        if ($this->input->post('code')) {
            $this->load->model('alert');

            $status = $this->m_liaison_item->edit([
                'id' => $this->input->get('id'),
                'code' => $this->input->post('code'),
                'nama_transaksi' => $this->input->post('nama_transaksi'),
                'keterangan' => $this->input->post('keterangan'),
                'active' => $this->input->post('active'),
				
				
				
                'id_transaksi_lo_detail' => $this->input->post('id_transaksi_lo_detail[]'),
                'kode' => $this->input->post('kode[]'),
                'keterangan_detail' => $this->input->post('keterangan_detail[]'),
                'range' => $this->input->post('range[]'),
                'harga' => $this->input->post('harga[]'),
                'active_detail' => 1,
                'delete_detail' => 0,



                'id_transaksi_lo_outflow' => $this->input->post('id_transaksi_lo_outflow[]'),
                'kode2' => $this->input->post('kode2[]'),
                'nama2' => $this->input->post('nama2[]'),
                'harga2' => $this->input->post('harga2[]'),
                'keterangan2' => $this->input->post('keterangan2[]'),
                'active_outflowl' => 1,
                'delete_outflow' => 0,




            ]);
            $this->alert->css();
        }

        if ($this->m_liaison_item->cek($this->input->get('id'))) {
            // $dataTransaksiLO = $this->m_liaison_item->get();
            
            $dataLOSelect = $this->m_liaison_item->get_select($this->input->get('id'));
            $dataLOSelectItem = $this->m_liaison_item->get_select_item($this->input->get('id'));
            $dataLOSelectOutflow = $this->m_liaison_item->get_select_outflow($this->input->get('id'));
            $this->load->model('m_log');
            $data = $this->m_log->get('liaison', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Service > Item Liason Officer', 'subTitle' => 'Edit']);
            
            $this->load->view('proyek/master/liaison_item/edit', [
                'data' => $data, 
                'dataLOSelect' => $dataLOSelect, 
                'dataLOSelectItem' => $dataLOSelectItem, 
                'dataLOSelectOutflow' => $dataLOSelectOutflow, 
            ]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_transaksi_lo');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        }
    }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_liaison_item->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_liaison_item->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Item Liaison Officer', 'subTitle' => 'List']);
        $this->load->view('proyek/master/liaison_item/view', ['data' => $data]);
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
    public function get_outflow(){
        echo json_encode($this->m_liaison_item->get_outflow($this->input->get('id')));
    }
}
