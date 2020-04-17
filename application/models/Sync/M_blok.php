<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_blok extends CI_Model
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
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";

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
            $data=$this->db
                    ->select("
                        kawasan.id as kawasan_id,
                        m_block.code,
                        m_block.block as name,
                        m_block.description,
                        1 as active,
                        0 as delete,
                        '2' as source_table,
                        m_block.block_id as source_id
                    ")
                    ->from("$erems.dbo.m_block")
                    ->join("kawasan",
                            "kawasan.source_table = '2' 
                            AND kawasan.source_id = m_block.cluster_id")
                    ->join("project",
                            "project.source_table = '2' 
                            AND project.source_id = m_block.project_id
                            AND project.id = $project->id")
                    ->join("blok",
                            "blok.source_table = '2'
                            AND blok.source_id = m_block.block_id",
                            "LEFT")
                    ->where("blok.source_id is null")
                    ->where_in("m_block.block_id",$data_id)
                    ->get()->result_array();  
            // $data = $this->db
            //         ->select("
            //             2 as source_table,
            //             EREMS.block_id as source_id,
            //             kawasanEMS.id as kawasan_id,
            //             EREMS.code,
            //             EREMS.block as name,
            //             EREMS.description,
            //             1 as active,
            //             0 as delete
            //         ")
            //         ->from("$erems.dbo.view_block as EREMS")
            //         ->join("blok as EMS",
            //                 "EMS.source_table = '2' 
            //                 AND EMS.source_id = EREMS.block_id", 
            //                 "left")
            //         ->join("kawasan as kawasanEMS",
            //             "kawasanEMS.source_table = '2' 
            //             AND kawasanEMS.source_id = EREMS.cluster_id")
            //         ->join("project as projectEMS",
            //             "projectEMS.source_table = '2'
            //             AND projectEMS.source_id = EREMS.project_id
            //             AND projectEMS.id = $project->id")
            //         ->where("EMS.source_id is null")
            //         ->where_in("EREMS.block_id",$data_id)
            //         ->get()->result_array();           
            if($data){
            
                $this->db->trans_start();
                $this->db->insert_batch("blok",$data); 
                $this->db->trans_complete();
            }
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah data di input:".count($data)
                    ];
        }
    }
    public function get_ajax_blok_by_source($source,$project)
    {   
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";
        
        if ($source == "2") {
            $data=$this->db
                    ->select("
                        project.name as project,
                        kawasan.id as kawasan_id,
                        kawasan.name as kawasan,
                        m_block.code,
                        m_block.block as name,
                        m_block.description,
                        1 as active,
                        0 as [delete],
                        '2' as source_table,
                        m_block.block_id as source_id
                    ")
                    ->from("$erems.dbo.m_block")
                    ->join("kawasan",
                            "kawasan.source_table = '2' 
                            AND kawasan.source_id = m_block.cluster_id")
                    ->join("project",
                            "project.source_table = '2' 
                            AND project.source_id = m_block.project_id
                            AND project.id = $project->id")
                    ->join("blok",
                            "blok.source_table = '2'
                            AND blok.source_id = m_block.block_id",
                            "LEFT")
                    ->where("blok.source_id is null")->get()->result();
            // $data=$this->db
            //         ->select("TOP 500 
            //             '2' as source_table,
            //             EREMS.block_id as source_id,
            //             kawasanEMS.id as kawasan_id,
            //             kawasanEMS.name as kawasan,
            //             projectEMS.name as project,
            //             EREMS.code,
            //             EREMS.block as name,
            //             EREMS.description,
            //             1 as active,
            //             0 as [delete]
            //         ")
            //         ->from("$erems.dbo.view_block AS EREMS")
            //         ->join("blok as EMS",
            //                 "EMS.source_table = '2' 
            //                 AND EMS.source_id = EREMS.block_id", 
            //                 "left")
            //         ->join("kawasan as kawasanEMS",
            //             "kawasanEMS.source_table = '2' 
            //             AND kawasanEMS.source_id = EREMS.cluster_id")
            //         ->join("project as projectEMS",
            //             "projectEMS.source_table = '2' 
            //             AND projectEMS.source_id = EREMS.project_id
            //             AND projectEMS.id = $project->id")
            //         ->where("EMS.source_id is null")->get()->result();
        return $data;
        }
        return 0;
    }
}
