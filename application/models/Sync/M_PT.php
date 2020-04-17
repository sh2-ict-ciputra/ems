<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_pt extends CI_Model
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
        $erems = "erems";
        // $erems = $this->load->database("erems",true)->database;

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
            $data = $this->db
                    ->select("
                            pt_id as source_id,
                            pt_name as name,
                            2 as source_table,
                            1 as active,
                            0 as delete,
                            project.id as project_id,
                            '' as address,
                            '' as phone,
                            '' as npwp 
                    ")
                    ->from("$erems.dbo.view_serahterima")
                    ->join("pt",
                            "pt.source_table = '2'
                            AND pt.source_id = view_serahterima.pt_id",
                            "LEFT")
                    ->join("project",
                            "project.source_table = '2'
                            AND project.source_id = view_serahterima.project_id")
                    ->where("pt.id is null")
                    ->distinct()
                    ->where("project.id is not null")
                    ->where_in("view_serahterima.pt_id",$data_id)
                    ->get()->result_array();
            if($data){
                $this->db->trans_start();
                $this->db->insert_batch("pt",$data); 
                $this->db->trans_complete();
            }
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah uang di input:".count($data)
                    ];
        }
    }
    public function get_ajax_pt_by_source($source)
    {   
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";

        if ($source == "2") {
            $data=$this->db
                    ->select("
                            pt_id as source_id,
                            pt_name as name,
                            2 as source_table,
                            1 as active,
                            0 as [delete],
                            project.name as ProjectName,
                            project.id as project_id,
                            '' as address,
                            '' as phone,
                            '' as npwp 
                    ")
                    ->from("$erems.dbo.view_serahterima")
                    ->join("pt",
                            "pt.source_table = '2'
                            AND pt.source_id = view_serahterima.pt_id",
                            "LEFT")
                    ->join("project",
                            "project.source_table = '2'
                            AND project.source_id = view_serahterima.project_id")
                    ->where("pt.id is null")
                    ->distinct()
                    ->where("project.id is not null")
                    ->get()->result();
            // $data=$this->db
            //         ->select("
            //                 ptEREMS.city_id as city_id,
            //                 ptEREMS.name as PT,
            //                 projectEMS.name as ProjectName,
            //                 projectEMS.id as project_id,
            //                 ptEREMS.code,
            //                 ptEREMS.name,
            //                 ptEREMS.address,
            //                 ptEREMS.npwp,
            //                 ptEREMS.phone,
            //                 ptEREMS.rekening,
            //                 ptEREMS.description,
            //                 2 as source_table,
            //                 ptEREMS.pt_id as source_id,
            //                 1 as active,
            //                 0 as [delete]
            //         ")
                    // ->from("$erems.dbo.view_serahterima")
                    // ->join("$erems.dbo.pt",
                    //         "pt.source_table = '2'
                    //         AND pt.source_id = view_serahterima.pt_id",
                    //         "LEFT")
                    // ->join("project",
                    //         "project.source_table = 2
                    //         AND project.source_id = EREMS.project_id")
                    // ->where("pt.id is null")
                    // ->where("project.id is not null")
                    // ->get()->result();
            return $data;
        }
        return 0;
    }
}
