<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_group_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_group_tvi');
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
        $data = $this->m_group_tvi->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Grup TV Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/master/group_tvi/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $dataGroupTvi = $this->m_group_tvi->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service > Grup TV Internet', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/group_tvi/add', ['dataGroupTvi' => $dataGroupTvi]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = $this->m_group_tvi->save([
            'kode_group' => $this->input->post('code'),
            'nama_group' => $this->input->post('nama_group'),
            'jumlah_hari' => $this->input->post('jumlah_hari'),
            'jenis_bayar' => $this->input->post('jenis_bayar'),
            'keterangan' => $this->input->post('keterangan'),
            'active' => $this->input->post('active'),
        ]);

        $this->load->model('alert');

        $dataGroupTvi = $this->m_group_tvi->get();
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service > Grup TV Internet', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/group_tvi/add', ['dataGroupTvi' => $dataGroupTvi]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        }
    }

    public function edit()
    {
        $status = 0;
        if ($this->input->post('code')) {
            $this->load->model('alert');

            $status = $this->m_group_tvi->edit([
            'id' => $this->input->get('id'),
            'kode_group' => $this->input->post('code'),
            'nama_group' => $this->input->post('nama_group'),
            'jumlah_hari' => $this->input->post('jumlah_hari'),
            'jenis_bayar' => $this->input->post('jenis_bayar'),
            'active' => $this->input->post('status'),
            'keterangan' => $this->input->post('keterangan'),
            'active' => $this->input->post('active'),
            ]);
            $this->alert->css();
        }

        if ($this->m_group_tvi->cek($this->input->get('id'))) {
            $dataGroupTvi = $this->m_group_tvi->get();
            $dataGroupTviSelect = $this->m_group_tvi->getSelect($this->input->get('id'));
            $this->load->model('m_log');
            $data = $this->m_log->get('group_tvi', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Accounting > Group Tvi', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/group_tvi/edit', ['data' => $data, 'dataGroupTvi' => $dataGroupTvi, 'data_select' => $dataGroupTviSelect]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_group_tvi');
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

        $status = $this->m_group_tvi->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_group_tvi->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Grup TV Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/master/group_tvi/view', ['data' => $data]);
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
