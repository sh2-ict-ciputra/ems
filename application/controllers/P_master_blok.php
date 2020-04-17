<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_blok extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_blok');
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
        $data = $this->m_blok->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Town Planning > Blok', 'subTitle' => 'List']);
        $this->load->view('proyek/master/blok/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_blok');
        $dataBlok = $this->m_blok->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Town Planning > Blok', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/blok/add', ['dataBlok' => $dataBlok]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = $this->m_blok->mapping_save([
            'pt_id' => $this->input->post('pt_id'),
            'cluster_id' => $this->input->post('cluster_id'),
            'code' => $this->input->post('code'),
            'block' => $this->input->post('block'),
            'description' => $this->input->post('description'),
            'icon' => $this->input->post('icon'),
            'addon' => $this->input->post('addon'),
            'addby' => $this->input->post('addby'),
            'modion' => $this->input->post('modion'),
            'modiby' => $this->input->post('modiby'),
            'inactiveon' => $this->input->post('inactiveon'),
            'inactiveby' => $this->input->post('inactiveby'),
            'deleteon' => $this->input->post('deleteon'),
            'deleteby' => $this->input->post('deleteby'),
            'active' => $this->input->post('activeby'),
        ]);

        $this->load->model('alert');
        $this->load->model('m_blok');
        $dataBlok = $this->m_blok->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Town Planning > Blok', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/blok/add', ['dataGolongan' => $dataBlok]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        }
    }
}
