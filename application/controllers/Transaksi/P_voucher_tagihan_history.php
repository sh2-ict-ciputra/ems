<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_voucher_tagihan_history extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('transaksi/m_voucher_tagihan_history');
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
        
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi > History Voucher Tagihan ', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi/voucher_tagihan_history/view',
            [  
                "project_name" =>$GLOBALS["project"]->name
            ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        
    }
    public function ajax_get_header(){
        echo json_encode($this->m_voucher_tagihan_history->get_view_header($this->input->GET('awal'),$this->input->GET('akhir')));
    }
    public function ajax_get_detail(){
        echo json_encode($this->m_voucher_tagihan_history->get_detail($this->input->GET('pt_id'),$this->input->GET('cara_pembayaran_id')));
    }
    public function ajax_validasi(){
        $data = explode(".",$this->input->GET('id'));
        echo json_encode($this->m_voucher_tagihan_history->validasi($data[0],$data[1],$this->input->GET('total_nilai')));
    }
    public function ajax_kirim(){
        echo json_encode($this->m_voucher_tagihan_history->kirim_voucher($this->input->GET('pt_id'),$this->input->GET('cara_pembayaran_id'),$this->input->GET('total_nilai'),$this->input->GET('data_id')));
    }
}
