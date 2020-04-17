<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_paket_service extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_paket_service');
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
        $data = $this->m_paket_service->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Service', 'subTitle' => 'List']);
        $this->load->view('proyek/master/paket_service/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_paket_service');
        $dataPaketService = $this->m_paket_service->get_jenis_services();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Service', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/paket_service/add', ['dataPaketService' => $dataPaketService]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = $this->m_paket_service->save([
            'jenis_service' => $this->input->post('jenis_service'),
            'kode_paket' => $this->input->post('code'),
            'nama_pekerjaan' => $this->input->post('nama_pekerjaan'),
            'satuan' => $this->input->post('satuan'),
            'biaya_satuan_langganan' => $this->input->post('biaya_satuan_langganan'),
            'biaya_satuan_tanpa_langganan' => $this->input->post('biaya_satuan_tanpa_langganan'),
            'biaya_registrasi_aktif' => $this->input->post('biaya_registrasi_aktif'),
            'biaya_registrasi' => $this->input->post('biaya_registrasi'),
            'biaya_pemasangan_aktif' => $this->input->post('biaya_pemasangan_aktif'),
            'biaya_pemasangan' => $this->input->post('biaya_pemasangan'),
            'minimal_langganan' => $this->input->post('minimal_langganan'),
            'tipe_periode'      => $this->input->post('tipe_periode')

        ]);


        $this->load->model('alert');
        $this->load->model('m_paket_service');
        $dataPaketService = $this->m_paket_service->get_jenis_services();
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service > Paket Service ', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/paket_service/add', ['dataPaketService' => $dataPaketService]);
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

            $status = $this->m_paket_service->edit([
                'id' => $this->input->get('id'),
                'jenis_service' => $this->input->post('jenis_service'),
                'kode_paket' => $this->input->post('code'),
                'nama_pekerjaan' => $this->input->post('nama_pekerjaan'),
                'satuan' => $this->input->post('satuan'),
                'biaya_satuan_langganan' => $this->input->post('biaya_satuan_langganan'),
                'biaya_satuan_tanpa_langganan' => $this->input->post('biaya_satuan_tanpa_langganan'),
                'biaya_registrasi_aktif' => $this->input->post('biaya_registrasi_aktif'),
                'biaya_registrasi' => $this->input->post('biaya_registrasi'),
                'biaya_pemasangan_aktif' => $this->input->post('biaya_pemasangan_aktif'),
                'biaya_pemasangan' => $this->input->post('biaya_pemasangan'),
                'minimal_langganan' => $this->input->post('minimal_langganan'),
                'tipe_periode'      => $this->input->post('tipe_periode'),
                'active' => $this->input->post('status'),
            ]);
            $this->alert->css();
        }
        if ($this->m_paket_service->cek($this->input->get('id'))) {
            $dataPaketService = $this->m_paket_service->get();
            $dataPaketServiceSelect = $this->m_paket_service->getSelect($this->input->get('id'));
            $dataService = $this->m_paket_service->get_jenis_services();
            $this->load->model('m_log');
            $data = $this->m_log->get('paket_service', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Accounting > Paket Service', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/paket_service/edit', ['data' => $data, 'dataPaketService' => $dataPaketService, 'data_select' => $dataPaketServiceSelect, 'dataService' => $dataService, 'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_paket_service');
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

        $status = $this->m_paket_service->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_paket_service->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header',['title' => 'Master > Service > Paket Service Non Retribusi','subTitle' => 'List']);
        $this->load->view('proyek/master/paket_service/view',['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } 
    }




}
