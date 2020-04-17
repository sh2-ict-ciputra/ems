<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sql  extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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
        $this->load->model('Setting/m_parameter_project');

    }
    public function test(){
        
    }
    public function index(){
        // $this->db->set('periode','DATEADD(month, 1, t_tagihan_lingkungan.periode)');
        // $this->db->update("t_tagihan_lingkungan");
        // $this->db->query("BACKUP DATABASE ems.dbo 
        //                     TO DISK = 'ems.BAK'
        //                     ");
        //                     var_dump($this->db->affected_rows());
        // $this->db->query("  UPDATE t_tagihan_lingkungan
        //                     SET periode = DATEADD(month, +1, t_tagihan_lingkungan.periode)");
        // var_dump($this->db->affected_rows());

        $a = $this->db->query("SELECT t_tagihan_lingkungan.* 
                        FROM t_tagihan_lingkungan
                        LEFT JOIN t_tagihan
                            ON t_tagihan.unit_id = t_tagihan_lingkungan.unit_id
                            and t_tagihan.periode = t_tagihan_lingkungan.periode
                        WHERE t_tagihan.id is null
                        ")->result();
        echo("<pre>");
            print_r($a);
        echo("</pre>");
        foreach($a as $k=>$v){
            $data = (object)[];
            $data->proyek_id    = $v->proyek_id;
            $data->unit_id      = $v->unit_id;
            $data->periode      = $v->periode;
            
            $this->db->insert("t_tagihan",$data);
            $tmp = $this->db->insert_id();
            $this->db->set("t_tagihan_id",$tmp);
            $this->db->where("id",$v->id);
            $this->db->update("t_tagihan_lingkungan");
            
            echo("$k<pre>");
            var_dump($this->db->affected_rows());
                print_r($data);
            echo("</pre>");
            
        }
        
        

    }
}
