<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_purpose_unit extends CI_Model
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
    public function save($data_id,$source){
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";

        if($source == 2){
            $data=$this->db
                        ->select("
                        project.id as project_id,
                        purpose_id as code,
                        '' as name,
                        '' as description,
                        '2' as source_table,
                        purpose_id as source_id,
                        1 as active,
                        0 as delete
                    ")
                    ->from("$erems.dbo.view_unit")
                    ->join("project",
                            "project.source_table = '2'
                            AND project.source_id = view_unit.project_id")
                    ->join("purpose_unit",
                            "purpose_unit.source_table = '2'
                            AND purpose_unit.source_id = purpose_id",
                            "LEFT")
                    ->where("purpose_unit.id is null")
                    ->distinct()
                    ->where_in("purpose_id",$data_id)
                    ->get()->result();
            if($data){
                $this->db->trans_start();
                $this->db->insert_batch("purpose_unit",$data); 
                $this->db->trans_complete();
            }
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah data di input:".count($data)
                    ];
        }
    }
    public function get_ajax_purpose_unit_by_source($source)
    {   
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";

        if ($source == "2") {
            $data=$this->db
                    ->select("
                        purpose_id as source_id,
                        project.name as project,
                        purpose_id as purpose_code,
                        '' as purpose_name,
                        '' as description,
                        1 as active,
                        0 as [delete]
                    ")
                    ->from("$erems.dbo.view_unit")
                    ->join("project",
                            "project.source_table = '2'
                            AND project.source_id = view_unit.project_id")
                    ->join("purpose_unit",
                            "purpose_unit.source_table = '2'
                            AND purpose_unit.source_id = purpose_id",
                            "LEFT")
                    ->where("purpose_unit.id is null")
                    ->where("view_unit.purpose_id is not null")
                    ->distinct()
                    ->get()->result();
            return $data;
        }
        return 0;
    }
}
