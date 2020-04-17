<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_kawasan extends CI_Model
{

    public function get()
    {
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";

        $query = $this->db->query("SELECT * FROM PT");
        return $query->result_array();
    }
    public function getAll()
    {
        $query = $this->db->query("
            Select 
                pt.*,city.name as cityName, 
                city.zipcode, 
                province.name as provinceName, 
                country.name as countryName 
            FROM pt
            LEFT JOIN city 
                on city.id = pt.city_id
            LEFT JOIN province 
                on province.id = city.province_id
            LEFT JOIN country 
                on country.id = province.country_id
        ");
        return $query->result_array();
    }
    public function save($data_id,$source,$project){
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";

        if($source == 2){
            $data = $this->db
                    ->select("
                        projectEMS.id as project_id,
                        EREMS.code,
                        EREMS.cluster as name,
                        EREMS.description,
                        2 as source_table,
                        EREMS.cluster_id as source_id,
                        1 as active,
                        0 as delete
                    ")
                    ->from("$erems.dbo.mh_cluster as EREMS")
                    ->join("kawasan as EMS",
                            "EMS.source_table = '2' 
                            AND EMS.source_id = EREMS.cluster_id", 
                            "left")
                    ->join("project as projectEMS",
                            "projectEMS.source_table = 2 
                            AND projectEMS.source_id = EREMS.project_id
                            AND projectEMS.id = $project->id")
                    ->where("EMS.source_id is null")
                    ->where_in("EREMS.cluster_id",$data_id)
                    ->get()->result_array();            
            if($data){
                $this->db->trans_start();
                $this->db->insert_batch("kawasan",$data); 
                $this->db->trans_complete();
            }
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah data di input:".count($data)
                    ];
        }
    }
    public function get_ajax_kawasan_by_source($source,$project)
    {   
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";

        if ($source == "2") {
            // $data=$this->db
            //         ->select("
            //             projectEms.name as projectName,
            //             projectEMS.id as project_id,
            //             EREMS.code,
            //             EREMS.cluster as name,
            //             EREMS.description,
            //             2 as source_table,
            //             EREMS.cluster_id as source_id,
            //             1 as active,
            //             0 as delete
            //         ")
            //         ->from("$erems.dbo.view_cluster as EREMS")
            //         ->join("kawasan as EMS",
            //                 "EMS.source_table = '2' 
            //                 AND EMS.source_id = EREMS.cluster_id", 
            //                 "left")
            //         ->join("project as projectEMS",
            //                 "projectEMS.source_table = '2' 
            //                 AND projectEMS.source_id = EREMS.project_id")
            //         ->where("EMS.source_id is null")
            //         ->get()->result();
            $data=$this->db
                    ->select("
                        projectEms.name as projectName,
                        projectEMS.id as project_id,
                        EREMS.code,
                        EREMS.cluster as name,
                        EREMS.description,
                        2 as source_table,
                        EREMS.cluster_id as source_id,
                        1 as active,
                        0 as delete
                    ")
                    ->from("$erems.dbo.mh_cluster as EREMS")
                    ->join("kawasan as EMS",
                            "EMS.source_table = '2' 
                            AND EMS.source_id = EREMS.cluster_id", 
                            "left")
                    ->join("project as projectEMS",
                            "projectEMS.source_table = '2' 
                            AND projectEMS.source_id = EREMS.project_id
                            AND projectEMS.id = $project->id")
                    ->where("EMS.source_id is null")
                    ->get()->result();
            return $data;
        }
        return 0;
    }
}
