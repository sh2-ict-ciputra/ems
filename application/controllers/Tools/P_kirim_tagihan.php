<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

defined('BASEPATH') or exit('No direct script access allowed');

class P_kirim_tagihan  extends REST_Controller
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
    }

    public function test($unit_id=null){
        $this->load->library('curl');
        $result = $this->curl->simple_get(site_url()."/Cetakan/konfirmasi_tagihan_api/send/".$unit_id);
        var_dump($result);
    }
    public function unit_get($unit_id)
    {
        $project = $this->m_core->project();

        $this->load->library('curl');
        $result = $this->curl->simple_get(site_url()."/Cetakan/konfirmasi_tagihan_api/send/".$unit_id);
        if($result){
            $result = str_replace("\"","",$result);
            $config = [
                'mailtype'  => 'html',
                'charset'   => 'utf-8',
                'protocol'  => 'smtp',
                'smtp_host' => $this->db->select("value")->from("parameter_project")->where("project_id",$project->id)->where("code","smtp_host")->get()->row()->value,
                'smtp_user' => $this->db->select("value")->from("parameter_project")->where("project_id",$project->id)->where("code","smtp_user")->get()->row()->value,
                'smtp_pass' => $this->db->select("value")->from("parameter_project")->where("project_id",$project->id)->where("code","smtp_pass")->get()->row()->value,
                'smtp_port' => $this->db->select("value")->from("parameter_project")->where("project_id",$project->id)->where("code","smtp_port")->get()->row()->value,
                'crlf'      => "\r\n",
                'newline'   => "\r\n"
            ];
            $this->load->library('email',$config);
            // print_r($config);
            // $this->db->selec
            $this->email->from($this->db->select("value")->from("parameter_project")->where("project_id",$project->id)->where("code","smtp_user")->get()->row()->value, 'EMS Ciputra');
            
            $email = $this->db
                    ->select("
                    CASE
                        WHEN unit.kirim_tagihan = 1 THEN pemilik.email
                        WHEN unit.kirim_tagihan = 2 THEN penghuni.email
                        WHEN unit.kirim_tagihan = 3 THEN CONCAT(pemilik.email,';',penghuni.email)
                    END as email")
                    ->from("unit")
                    ->join("customer as pemilik",
                        "pemilik.id = unit.pemilik_customer_id",
                        "LEFT")
                    ->join("customer as penghuni",
                        "penghuni.id = unit.penghuni_customer_id 
                        AND penghuni.id != pemilik.id",
                        "LEFT")
                    
                    ->where("unit.id",$unit_id)->get()->row()->email;
            $email = explode(";",$email);
            foreach ($email as $v) {
                echo($v);
                $this->email->from($this->db->select("value")->from("parameter_project")->where("project_id",$project->id)->where("code","smtp_user")->get()->row()->value, 'EMS Ciputra');
                $this->email->to($v);
                $this->email->subject($this->db->select("value")->from("parameter_project")->where("project_id",$project->id)->where("code","subjek_konfirmasi_tagihan")->get()->row()->value);
                $this->email->message($this->db->select("value")->from("parameter_project")->where("project_id",$project->id)->where("code","isi_konfirmasi_tagihan")->get()->row()->value);
                $this->email->attach("pdf/$result");
                if($this->email->send()){
                    echo("Success ".$result);
                }else{
                    echo("Gagal  ".$result);
                }
            }    
                
        }
    }
    

}
