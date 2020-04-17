<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_tagihan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        // $this->load->model('m_login');
        // if (!$this->m_login->status_login()) {
        //     redirect(site_url());
        // }
        // $this->load->model('m_unit');
        $this->load->model('m_core');
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();

    }
    public function index(){
        
    }
    public function pl(){
        $project = $GLOBALS['project']->id;
        $this->load->model('auto_generate/m_tagihan');

        $periode = $this->input->get('periode')?$this->input->get('periode'):0;
        $this->m_tagihan->pl($project,$periode);
        
    }
    public function pl2(){
        $project = $GLOBALS['project']->id;
        $this->load->model('auto_generate/m_tagihan');

        $periode = $this->input->get('periode')?$this->input->get('periode'):0;
        $this->m_tagihan->pl2($this->input->get("project_id"),$periode);
    }
    public function pl_unit(){
        $project = $GLOBALS['project']->id;
        $unit_id = $this->input->post("unit_id");
        $periode = (object)[];
        $periode->awal          = $this->input->post("periode_awal");
        $periode->akhir         = $this->input->post("periode_akhir");
        $diff_periode = (((int)substr($periode->akhir,3,4)-(int)substr($periode->awal,3,4))*12)+((int)substr($periode->akhir,0,2)-(int)substr($periode->awal,0,2))+1;

        $this->load->model('auto_generate/m_tagihan');
        $j = 0;
        for ($i=0; $i < $diff_periode; $i++) { 
            if($this->m_tagihan->pl_unit($unit_id,(date("Y-m-01", strtotime("+$i month", strtotime(date(substr($periode->awal,3,4)."/".substr($periode->awal,0,2)."/01"))))))){
                $j++;
            }
        }
        echo(json_encode($j));
    }
}
