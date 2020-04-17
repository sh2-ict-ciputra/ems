<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_blok extends CI_Model
{

    public function get()
    {
        $erems = $this->load->database("erems",true)->database;

        $query = $this->db->query("SELECT * FROM PT");
        return $query->result_array();
    }
    public function getAll()
    {
        $erems = $this->load->database("erems",true)->database;

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
    public function save($data_id,$source){
        $erems = $this->load->database("erems",true)->database;

        if($source == 2){
            $data = $this->db
                    ->select("
                        '2' as source_table,
                        EREMS.block_id as source_id,
                        kawasanEMS.id as kawasan_id,
                        EREMS.code,
                        EREMS.block as name,
                        EREMS.description,
                        1 as active,
                        0 as delete
                    ")
                    ->from("$erems.dbo.m_block as EREMS")
                    ->join("blok as EMS",
                            "EMS.source_table = '2' 
                            AND EMS.source_id = EREMS.block_id", 
                            "left")
                    ->join("kawasan as kawasanEMS",
                        "kawasanEMS.source_table = '2' 
                        AND kawasanEMS.source_id = EREMS.cluster_id")
                    ->join("project as projectEMS",
                        "projectEMS.source_table = '2' 
                        AND projectEMS.source_id = EREMS.project_id")
                    ->where("EMS.source_id is null")
                    ->where_in("EREMS.block_id",$data_id)
                    ->get()->result_array();           
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
    public function get_ajax_blok_by_source($source)
    {   
        $erems = $this->load->database("erems",true)->database;

        if ($source == '2') {
            $data=$this->db
                    ->select("TOP 500 
                        '2' as source_table,
                        EREMS.block_id as source_id,
                        kawasanEMS.id as kawasan_id,
                        kawasanEMS.name as kawasan,
                        projectEMS.name as project,
                        EREMS.code,
                        EREMS.block as name,
                        EREMS.description,
                        1 as active,
                        0 as [delete]
                    ")
                    ->from("$erems.dbo.m_block as EREMS")
                    ->join("blok as EMS",
                            "EMS.source_table = '2' 
                            AND EMS.source_id = EREMS.block_id", 
                            "left")
                    ->join("kawasan as kawasanEMS",
                            "kawasanEMS.source_table = '2' 
                            AND kawasanEMS.source_id = EREMS.cluster_id")
                    ->join("project as projectEMS",
                        "projectEMS.source_table = '2' 
                        AND projectEMS.source_id = EREMS.project_id")
                    ->where("EMS.source_id is null")->get()->result();
            return $data;
        }
        return 0;
    }
}
