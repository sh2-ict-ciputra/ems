<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_group_sms_email  extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('proyek/tools/m_group_sms_email');
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
        $data = $this->m_group_sms_email->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Tools > Group SMS Email', 'subTitle' => 'List']);
        $this->load->view('proyek/tools/group_sms_email/view' , ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
       
      
        $dataCustomer  = $this->m_group_sms_email->getCustomer();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Tools > Group SMS Email', 'subTitle' => 'Add']);
        $this->load->view('proyek/tools/group_sms_email/add', ['dataCustomer' => $dataCustomer ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        
    }

    public function save()
    {
        $status = $this->m_group_sms_email->save([

            
            'nama_grup' => $this->input->post('nama_grup'),
            'type' => $this->input->post('type'),
            'keterangan' => $this->input->post('keterangan'),
            'unit' => $this->input->post('unit'),

         

        ]);

        $this->load->model('alert');
        $this->load->model('proyek/tools/m_group_sms_email');
        $this->load->view('core/header');
        $this->alert->css();
        $dataCustomer  = $this->m_group_sms_email->getCustomer();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Tools > Group SMS Email ', 'subTitle' => 'Add']);
        $this->load->view('proyek/tools/group_sms_email/add', ['dataKawasan' => $dataKawasan ]);
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



    public function lihat_blok()
    {
         $this->load->model('tools/m_group_sms_email');

        $kawasan_id = $this->input->post('kawasan');

        echo json_encode($this->m_group_sms_email->getBlok($kawasan_id));
    }



   

}
