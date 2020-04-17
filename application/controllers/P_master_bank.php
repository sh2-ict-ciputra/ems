<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_bank extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_bank');
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
        $data = $this->m_bank->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Bank', 'subTitle' => 'List']);
        $this->load->view('proyek/master/bank/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_bank');
        $dataBank = $this->m_bank->get_jenis();
        $dataPTCOA = $this->m_bank->get_all_pt_coa();
        $dataJenisService = $this->m_bank->get_jenis_service();
        $this->load->model('alert');
        $this->load->view('core/header');

        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Bank', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/bank/add', ['dataBank' => $dataBank, 'dataPTCOA' => $dataPTCOA, 'dataJenisService' => $dataJenisService]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    // public function ajax_save(){
    //     $this->m_bank->save($this->input->post());
    // }
    public function ajax_save()
    {
        echo(json_encode($this->m_bank->save($this->input->post())));
    }

    public function ajax_edit()
    {
        echo(json_encode($this->m_bank->ajax_edit($this->input->get("id"),$this->input->post())));
    }

    public function edit()
    {
        $status = 0;
        $dataSelected = $this->m_bank->get_selected($this->input->get('id'));
        if($dataSelected){
            $dataBank = $this->m_bank->get_jenis();
            $this->load->model('m_log');
            $data = $this->m_log->get('bank', $this->input->get('id'));
            $this->load->model('alert');
            $this->load->view('core/header');

            $this->alert->css();
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Accounting > Bank', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/bank/edit', 
                [
                    'dataBank' => $dataBank, 
                    'dataSelected' => $dataSelected, 
                    'data'  => $data
                ]
            );
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        }
        else{
            redirect(site_url().'/P_master_bank');
        }
    }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_bank->delete([
            'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_bank->get();
        // echo '<pre>';
        // print_r($this->m_bank->get_log_bank(24));
        // echo '</pre>';
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Bank', 'subTitle' => 'List']);
        $this->load->view('proyek/master/bank/view', ['data' => $data]);
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
