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
        $this->load->helper('file');
        write_file("./log/".date("y-m-d").'_log_auto_generate.txt',"\n".date("y-m-d h:i:s")." - Mulai", 'a+');

        $id = 'asfvev3g13f12foibfe3v3iufoh31roibvdjfu1hir';
        if($id == $this->input->get("id")){
            $tmp = $this->db    ->select("*")
                                ->from("parameter_project")
                                ->where("code","generate_ipl")
                                ->get()->result();
            // var_dump($tmp);

            foreach ($tmp as $k => $v) {
                // var_dump($v);
                if($v->value == date('d')){
                    $this->pl2($v->project_id,date("m/Y"));
                    // var_dump($v);
                }
            }
        }
        write_file("./log/".date("y-m-d").'_log_auto_generate.txt',"\n".date("y-m-d h:i:s")." - Selesai", 'a+');

    }
    public function pl(){
        $project = $GLOBALS['project']->id;
        $this->load->model('auto_generate/m_tagihan');

        $periode = $this->input->get('periode')?$this->input->get('periode'):0;
        $this->m_tagihan->pl($project,$periode);
        
    }
    public function pl2($project_id,$periode){
        // $project = $GLOBALS['project']->id;
        // var_dump('pl2');
        // var_dump($project_id);
        // var_dump($periode);

        // die;
        $this->load->model('auto_generate/m_tagihan');
        
        // $periode = $this->input->get('periode')?$this->input->get('periode'):0;
        $this->m_tagihan->pl2($project_id,$periode);
    }
    public function pl_unit(){
        $project = $GLOBALS['project']->id;
        $unit_id = $this->input->post("unit_id");
        $periode = (object)[];
        $periode->awal          = $this->input->post("periode_awal");
        $periode->akhir         = $this->input->post("periode_akhir");
        
        $periode1  = DateTime::createFromFormat('Y-m-d', substr($periode->awal,3,4).'-'.substr($periode->awal,0,2).'-01');
        
        $diff_periode = (((int)substr($periode->akhir,3,4)-(int)substr($periode->awal,3,4))*12)+((int)substr($periode->akhir,0,2)-(int)substr($periode->awal,0,2))+1;

        $this->load->model('auto_generate/m_tagihan');
        $j = 0;
        for ($i=0; $i < $diff_periode; $i++) {
            $tmp = (date("Y-m-01", strtotime("+$i month", strtotime(date(substr($periode->awal,3,4)."/".substr($periode->awal,0,2)."/01")))));
            $tmp2  = DateTime::createFromFormat('Y-m-d', $tmp);
            $tmp3 = $this->db->select('tgl_aktif')->from('unit_lingkungan')->where('unit_id',$unit_id)->get()->row();
            $tmp3 = $tmp3?$tmp3->tgl_aktif:'';
            $tmp3  = DateTime::createFromFormat('Y-m-d', $tmp3);
            
            if($tmp2->format('Y-m-d')>= date('Y-m-01') || $tmp2->format('Y-m-d') >= $tmp3->format('Y-m-d')){
                if($this->m_tagihan->pl_unit($unit_id,$tmp)){
                    $j++;
                }
            }
        }
        echo(json_encode($j));
    }
}
