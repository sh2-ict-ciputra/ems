<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_unit extends CI_Model
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
            // $data = $this->db
            //         ->select("
            //             projectEMS.id as project_id,
            //             ptEMS.id as pt_id,
            //             EREMS.code,
            //             EREMS.purpose as name,
            //             EREMS.description,
            //             2 as source_table,
            //             EREMS.purpose_id as source_id,
            //             1 as active,
            //             0 as delete
            //         ")
            //         ->from("$erems.dbo.purpose as EREMS")
            //         ->join("ciputraEms.dbo.purpose_unit as EMS",
            //             "EMS.source_table = 2 
            //             AND EMS.source_id = EREMS.purpose_id", 
            //             "left")
            //         ->join("ciputraEms.dbo.project as projectEMS",
            //             "projectEMS.source_table = 2 
            //             AND projectEMS.source_id = EREMS.project_id")
            //         ->join("ciputraEms.dbo.pt as ptEMS",
            //             "ptEMS.source_table = 2 
            //             AND ptEMS.source_id = EREMS.pt_id")
            //         ->where("EMS.source_id is null")
            //         ->where_in("EREMS.purpose_id",$data_id)
            //         ->get()->result_array(); 
            $data=$this->db->select("
                project.id as project_id,
                blok.id as blok_id,
                view_serahterima.unit_number as no_unit,
                customer.id as pemilik_customer_id,
                view_serahterima.land_size as luas_tanah,
                view_serahterima.building_size as luas_bangunan,
                0 as luas_taman,
                view_serahterima.tgl_serahterima as tgl_st,
                0 as unit_type,
                1 as status_tagihan,
                '0' as virtual_account,
                1 as kirim_tagihan,
                '2' as source_table,
                1 as active,
                0 as delete,
                view_serahterima.unit_id as source_id
            ")
            ->from("$erems.dbo.view_serahterima")
            ->join("project",
                    "project.source_table = '2'
                    AND project.source_id = view_serahterima.project_id
                    AND project.id = $project->id")
            ->join("blok",
                    "blok.source_table = '2'
                    AND blok.source_id = view_serahterima.block_id")
            ->join("kawasan",
                    "kawasan.id = blok.kawasan_id")
            ->join("customer",
                    "customer.source_table = '2'
                    AND customer.source_id = view_serahterima.customer_id")
            ->join("pt",
                    "pt.project_id = project.id")
            ->join("unit",
                    "unit.source_table = '2'
                    AND unit.source_id = view_serahterima.unit_id",
                    "LEFT")
            ->where("unit.id is null")
            ->where_in("view_serahterima.unit_id",$data_id)
            ->distinct()
            ->get()->result();
            if($data){
            
                $this->db->trans_start();
                $this->db->insert_batch("unit",$data); 
                $this->db->trans_complete();
            }
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah data di input:".count($data)
                    ];
        }
    }
    public function get_ajax_unit_by_source($source,$project)
    {   
        // $erems = $this->load->database("erems",true)->database;
        // var_dump($this->load->database("erems",true));
        // var_dump($erems);
        $erems = "erems";
        
        if ($source == "2") {
            $data=$this->db->select("
                        project.id as project_id,
                        blok.id as blok_id,
                        blok.name as blok_name,
                        kawasan.name as kawasan_name,
                        view_serahterima.unit_number,
                        customer.id as pemilik_customer_id,
                        customer.name as pemilik_name,
                        view_serahterima.land_size as luas_tanah,
                        view_serahterima.building_size as luas_bangunan,
                        0 as luas_taman,
                        view_serahterima.tgl_serahterima as tgl_st,
                        0 as unit_type,
                        1 as status_tagihan,
                        '0' as virtual_account,
                        1 as kirim_tagihan,
                        '2' as source_table,
                        view_serahterima.unit_id as source_table,
                        1 as active,
                        0 as delete,
                        view_serahterima.unit_id as source_id
                    ") // purpose_unit.id as purpose_unit_id,

                    ->from("$erems.dbo.view_serahterima")
                    ->join("project",
                            "project.source_table = '2'
                            AND project.source_id = view_serahterima.project_id
                            AND project.id = $project->id")
                    ->join("blok",
                            "blok.source_table = '2'
                            AND blok.source_id = view_serahterima.block_id")
                    ->join("kawasan",
                            "kawasan.id = blok.kawasan_id")
                    ->join("customer",
                            "customer.source_table = '2'
                            AND customer.source_id = view_serahterima.customer_id")
                    ->join("pt",
                           "pt.project_id = project.id")
                //     ->join("purpose_unit",
                //             "purpose_unit.source_table = '2'
                //             AND purpose_unit.source_id = view_unit.purpose_id")
                    ->join("unit",
                            "unit.source_table = '2'
                            AND unit.source_id = view_serahterima.unit_id",
                            "LEFT")
                    ->where("unit.id is null")
                    ->distinct()
                    ->limit(900)
                    ->get()->result();
            return $data;
        
        }
    }
}
