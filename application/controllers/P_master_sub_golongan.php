<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_sub_golongan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }

        $this->load->model('m_sub_golongan');
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
        $data = $this->m_sub_golongan->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Golongan > Sub Golongan', 'subTitle' => 'List']);
        $this->load->view('proyek/master/sub_golongan/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_sub_golongan');
        $this->load->model('m_pemeliharaan_air');
        
        $dataGolongan = $this->m_sub_golongan->get_golongan();
        $dataService = $this->m_sub_golongan->get_service();
        $dataPemeliharaanAir =$this->m_pemeliharaan_air->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Golongan > Sub Golongan', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/sub_golongan/add', ['dataGolongan' => $dataGolongan, 'dataService' => $dataService, 'dataPemeliharaanAir'=>$dataPemeliharaanAir]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        
        $status = $this->m_sub_golongan->save([
            'golongan' => $this->input->post('golongan'),
            'code' => $this->input->post('code'),
            'nama_sub' => $this->input->post('nama_sub'),
            'minimum_pemakaian' => $this->input->post('minimum_pemakaian'),
            'nilai_minimum' => $this->input->post('nilai_minimum'),
            'administrasi' => $this->input->post('administrasi'),
            'range_flag' => $this->input->post('range_flag'),
            'range_id' => $this->input->post('range_id'),
            'keterangan' => $this->input->post('keterangan'),
            'pemeliharaan_air_id' => $this->input->post('pemeliharaan_air_id')
        ]);
        $this->load->model('m_pemeliharaan_air');
        $dataPemeliharaanAir =$this->m_pemeliharaan_air->get();

        $this->load->model('alert');
        $this->load->model('m_sub_golongan');
        $dataGolongan = $this->m_sub_golongan->get_golongan();
        $dataService = $this->m_sub_golongan->get_service();
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Golongan > Sub Golongan', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/sub_golongan/add', ['dataGolongan' => $dataGolongan, 'dataService' => $dataService,'dataPemeliharaanAir'=>$dataPemeliharaanAir]);
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
        $this->load->model('m_golongan');
        $this->load->model('m_pemeliharaan_air');
        $dataPemeliharaanAir =$this->m_pemeliharaan_air->get();

        $status = 0;
        if ($this->input->post('code')) {
            $this->load->model('alert');
            $status = $this->m_sub_golongan->edit([
                'id' => $this->input->get('id'),
                'golongan' => $this->input->post('golongan'),
                'code' => $this->input->post('code'),
                'nama_sub' => $this->input->post('nama_sub'),
                'minimum_pemakaian' => $this->input->post('minimum_pemakaian'),
                'nilai_minimum' => $this->input->post('nilai_minimum'),
                'administrasi' => $this->input->post('administrasi'),
                'range_flag' => $this->input->post('range_flag'),
                'range_id' => $this->input->post('range_id'),
                'keterangan' => $this->input->post('keterangan'),
                'active' => $this->input->post('status'),
                'pemeliharaan_air_id' => $this->input->post('pemeliharaan_air_id')
            ]);
            $this->alert->css();
        }

        if ($this->m_sub_golongan->cek($this->input->get('id'))) {
            $dataGolongan = $this->m_sub_golongan->get_golongan();
            $dataService = $this->m_sub_golongan->get_service();
            $dataSelect = $this->m_sub_golongan->get_select($this->input->get('id'));
            // echo("<pre>");
            //     print_r($dataSelect);
            // echo("</pre>");
            
            if ($dataSelect->range_flag == 2) {
                $dataRange = $this->m_sub_golongan->get_range_air();
            } elseif ($dataSelect->range_flag == 1) {
                $dataRange = $this->m_sub_golongan->get_range_lingkungan();
            } else {
                $dataRange = $this->m_sub_golongan->get_range_listrik();
            }

            $this->load->model('m_log');
            $data = $this->m_log->get('sub_golongan', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Golongan > Sub Golongan', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/sub_golongan/edit', ['data' => $data, 'dataGolongan' => $dataGolongan, 'dataService' => $dataService, 'dataSelect' => $dataSelect, 'dataRange' => $dataRange,'dataPemeliharaanAir'=>$dataPemeliharaanAir]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_golongan');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function lihat_range()
    {
        $this->load->model('m_sub_golongan');

        $range_flag = $this->input->post('range_flag');

        if ($range_flag == 2) {
            echo json_encode($this->m_sub_golongan->get_range_air());
        } elseif ($range_flag == 1) {
            echo json_encode($this->m_sub_golongan->get_range_lingkungan());
        }
    }

    public function lihat_tabel()
    {
        $this->load->model('m_sub_golongan');

        $range = $this->input->post('select-range');

        $action = $this->input->post('action');

        $jenis = $this->input->post('jenis');

        $min_use = $this->input->post('min_use');

        $range_flag = $this->input->post('range_flag');

        $id = $this->input->post('id');

        if ($range_flag == 1) {
            echo json_encode($this->m_sub_golongan->get_range_air_detail($id));
        } elseif ($range_flag == 2) {
            echo json_encode($this->m_sub_golongan->get_range_lingkungan_detail($id));
        } elseif ($range_flag == 3) {
            echo json_encode($this->m_sub_golongan->get_range_listrik_detail($id));
        }
    }

    public function ajax_get_minimum()
    {
        echo json_encode($this->m_sub_golongan->get_minimum($this->input->get('data1'), $this->input->get('data2'), $this->input->get('data3')));
    }



    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_sub_golongan->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_sub_golongan->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header',['title' => 'Master > Golongan > Sub Golongan','subTitle' => 'List']);
        $this->load->view('proyek/master/sub_golongan/view',['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } 
    }


}
