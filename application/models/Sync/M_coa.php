<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_coa extends CI_Model
{

    public function get()
    {
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
    public function save($data_id,$source){
        if($source == 2){
            $data = $this->db
                    ->select("
                            ptEREMS.city_id as city_id,
                            projectEMS.id as project_id,
                            ptEREMS.code,
                            ptEREMS.name,
                            ptEREMS.address,
                            ptEREMS.npwp,
                            ptEREMS.phone,
                            ptEREMS.rekening,
                            ptEREMS.description,
                            2 as source_table,
                            ptEREMS.pt_id as source_id,
                            1 as active,
                            0 as delete
                    ")
                    ->from("$erems.dbo.m_projectpt as EREMS")
                    ->join("$erems.dbo.project as projectEREMS",
                            "projectEREMS.project_id = EREMS.project_id",
                            )
                    ->join("$erems.dbo.pt as ptEREMS",
                            "ptEREMS.pt_id = EREMS.pt_id"
                            )
                    ->join("ciputraEms.dbo.pt as ptEMS",
                            "ptEMS.source_table = 2
                            AND ptEMS.source_id = EREMS.pt_id",
                            "left"
                            )
                    ->join("ciputraEms.dbo.project as projectEMS",
                            "projectEMS.source_table = 2
                            AND projectEMS.source_id = EREMS.project_id"
                            )
                            
                    ->where("ptEMS.source_id is null")
                    ->where_in("EREMS.pt_id",$data_id)
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
        if ($source == "2") {
            $data=$this->db
                    ->select("
                            EREMS.city_id,
                            EREMS.code,
                            EREMS.name,
                            EREMS.address,
                            EREMS.npwp,
                            EREMS.phone,
                            EREMS.rekening,
                            EREMS.description,
                            2 as source_table,
                            EREMS.pt_id as source_id,
                            1 as active,
                            0 as [delete]
                    ")
                    ->from("$erems.dbo.pt as EREMS")
                    ->join("ciputraEms.dbo.pt as EMS",
                            "EMS.source_table = 2 AND EMS.source_id = EREMS.pt_id", 
                            "left")
                    ->join("$erems.dbo.m_projectpt as projectptEREMS",
                            "projectptEREMS.project_id = EREMS.project_id AND
                            projectptEREMS.pt_id = EREMS.pt_id
                            "
                            )
                    ->where("EMS.source_id is null")->get()->result();
            $data=$this->db
                    ->select("
                            ptEREMS.city_id as city_id,
                            ptEREMS.name as PT,
                            projectEMS.name as ProjectName,
                            projectEMS.id as project_id,
                            ptEREMS.code,
                            ptEREMS.name,
                            ptEREMS.address,
                            ptEREMS.npwp,
                            ptEREMS.phone,
                            ptEREMS.rekening,
                            ptEREMS.description,
                            2 as source_table,
                            ptEREMS.pt_id as source_id,
                            1 as active,
                            0 as [delete]
                    ")
                    ->from("$erems.dbo.m_projectpt as EREMS")
                    ->join("$erems.dbo.project as projectEREMS",
                            "projectEREMS.project_id = EREMS.project_id",
                            )
                    ->join("$erems.dbo.pt as ptEREMS",
                            "ptEREMS.pt_id = EREMS.pt_id"
                            )
                    ->join("ciputraEms.dbo.pt as ptEMS",
                            "ptEMS.source_table = 2
                            AND ptEMS.source_id = EREMS.pt_id",
                            "left"
                            )
                    ->join("ciputraEms.dbo.project as projectEMS",
                            "projectEMS.source_table = 2
                            AND projectEMS.source_id = EREMS.project_id"
                            )
                            
                    ->where("ptEMS.source_id is null")->
                    get()->result();
            return $data;
        }
        return 0;
    }
}
