<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Desktop_Unit_SP1 extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Sync/m_desktop_unit');
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
        $data = $this->db->select("
                th_trans.subgol_id,
                max(
                    case 
                        WHEN td_air.periode >= td_lingkungan.periode or td_lingkungan.periode is null THEN td_air.periode
                        ELSE td_lingkungan.periode
                    END
                ) as periode,
                case 
                    WHEN td_air.periode >= td_lingkungan.periode or td_lingkungan.periode is null THEN td_air.nilai_keamanan
                    ELSE td_lingkungan.nilai_keamanan
                END as nilai_keamanan,
                case 
                    WHEN td_air.periode >= td_lingkungan.periode or td_lingkungan.periode is null THEN td_air.nilai_sampah
                    ELSE td_lingkungan.nilai_sampah
                END as nilai_sampah,
                case 
                    WHEN td_air.periode >= td_lingkungan.periode or td_lingkungan.periode is null THEN 0
                    ELSE th_trans.rangebankav_id
                END as rangebankav_id
                ",false)
            ->from("ems_temp.citragran_cibubur.th_trans")
            ->join("
                (
                    SELECT 
                        td_air.th_trans_id,
                        td_air.periode,
                        td_air.nilai_keamanan,
                        td_air.nilai_sampah
                    FROM ems_temp.citragran_cibubur.td_air
                    JOIN    (
                            SELECT 
                                max(periode) as periode,
                                th_trans_id
                            FROM ems_temp.citragran_cibubur.td_air
                            GROUP BY th_trans_id
                            ) as td_air2
                    ON td_air2.periode = td_air.periode
                    AND td_air2.th_trans_id = td_air.th_trans_id
                ) as td_air",
            "td_air.th_trans_id = th_trans.th_trans_id
            AND td_air.periode is not null",
            "LEFT") 
            ->join("
                (
                    SELECT 
                        td_lingkungan.th_trans_id,
                        td_lingkungan.periode,
                        td_lingkungan.nilai_keamanan,
                        td_lingkungan.nilai_sampah,
                        td_lingkungan.nilai_tanah,
                        td_lingkungan.nilai_bangunan
                    FROM ems_temp.citragran_cibubur.td_lingkungan
                    JOIN    (
                            SELECT 
                                max(periode) as periode,
                                th_trans_id
                            FROM ems_temp.citragran_cibubur.td_lingkungan
                            GROUP BY th_trans_id
                            ) as td_lingkungan2
                    ON td_lingkungan2.periode = td_lingkungan.periode
                    AND td_lingkungan2.th_trans_id = td_lingkungan.th_trans_id
                ) as td_lingkungan",
                "td_lingkungan.th_trans_id = th_trans.th_trans_id
                AND td_lingkungan.periode is not null",
                "LEFT")
            ->where("
                    case 
                        WHEN td_air.periode >= td_lingkungan.periode or td_lingkungan.periode is null THEN td_air.periode
                        ELSE td_lingkungan.periode
                    END is not null")
            ->group_by("
                        th_trans.subgol_id,
                        case 
                            WHEN td_air.periode >= td_lingkungan.periode or td_lingkungan.periode is null THEN td_air.nilai_keamanan
                            ELSE td_lingkungan.nilai_keamanan
                        END,
                        case 
                            WHEN td_air.periode >= td_lingkungan.periode or td_lingkungan.periode is null THEN td_air.nilai_sampah
                            ELSE td_lingkungan.nilai_sampah
                        END,
                        case 
                            WHEN td_air.periode >= td_lingkungan.periode or td_lingkungan.periode is null THEN 0
                            ELSE th_trans.rangebankav_id
                        END",false)->get()->result();
            
            echo("<pre>");
            print_r($data);
            echo("</pre>");


		
    }
}
