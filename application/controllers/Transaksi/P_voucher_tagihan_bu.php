<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_voucher_tagihan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('transaksi/m_voucher_tagihan');
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
        $this->load->model('Setting/m_parameter_project');
        $project = $this->m_core->project();

        $data = $this->m_voucher_tagihan->get_view();
        // echo("<pre>");
        //     print_r($data);
        // echo("</pre>");
        
        $this->load->view('core/header');
        $this->load->model('alert');
		$this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi > Voucher Tagihan', 'subTitle' => 'List']);
        
        $bentuk_voucher    = $this->m_parameter_project->get($project->id,"bentuk_voucher");
        if($bentuk_voucher == 0){
        $this->load->view('proyek/transaksi/voucher_tagihan/view_unit',
            [  
                "data"=>$data,
                "project_name" =>$GLOBALS["project"]->name
            ]);
        }else if($bentuk_voucher == 1){
            $this->load->view('proyek/transaksi/voucher_tagihan/view_gabungan',
            [  
                "data"=>$data,
                "project_name" =>$GLOBALS["project"]->name
            ]);    
        }

        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        
    }
    public function ajax_get_detail(){
        echo json_encode($this->m_voucher_tagihan->get_detail($this->input->GET('pt_id'),$this->input->GET('cara_pembayaran_id')));
    }
    public function ajax_get_detail_gabungan(){
        echo json_encode($this->m_voucher_tagihan->get_detail_gabungan($this->input->GET('pt_id'),$this->input->GET('cara_pembayaran_id')));
    }
    public function ajax_validasi(){
        $data = explode(".",$this->input->GET('id'));
        echo json_encode($this->m_voucher_tagihan->validasi($data[0],$data[1],$this->input->GET('total_nilai')));
    }
    public function ajax_kirim(){
        echo json_encode($this->m_voucher_tagihan->kirim_voucher($this->input->GET('pt_id'),$this->input->GET('cara_pembayaran_id'),$this->input->GET('total_nilai')));
    }
}
