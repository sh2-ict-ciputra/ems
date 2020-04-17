<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_purpose_unit extends CI_Model
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
                        projectEMS.id as project_id,
                        ptEMS.id as pt_id,
                        EREMS.code,
                        EREMS.purpose as name,
                        EREMS.description,
                        2 as source_table,
                        EREMS.purpose_id as source_id,
                        1 as active,
                        0 as delete
                    ")
                    ->from("$erems.dbo.purpose as EREMS")
                    ->join('ciputraEms.dbo.purpose_unit as EMS',
                        'EMS.source_table = 2 
                        AND EMS.source_id = EREMS.purpose_id', 
                        'left')
                    ->join('ciputraEms.dbo.project as projectEMS',
                        'projectEMS.source_table = 2 
                        AND projectEMS.source_id = EREMS.project_id')
                    ->join('ciputraEms.dbo.pt as ptEMS',
                        'ptEMS.source_table = 2 
                        AND ptEMS.source_id = EREMS.pt_id')
                    ->where("EMS.source_id is null")
                    ->where_in("EREMS.purpose_id",$data_id)
                    ->get()->result_array();           
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
        if ($source == '2') {
            $data=$this->db
                    ->select("
                        ptEMS.name as ptName,
                        projectEMS.name as projectName,
                        projectEMS.id as project_id,
                        ptEMS.id as pt_id,
                        EREMS.code,
                        EREMS.purpose as name,
                        EREMS.description,
                        2 as source_table,
                        EREMS.purpose_id as source_id,
                        1 as active,
                        0 as [delete]
                    ")
                    ->from("$erems.dbo.purpose as EREMS")
                    ->join('ciputraEms.dbo.purpose_unit as EMS',
                            'EMS.source_table = 2 
                            AND EMS.source_id = EREMS.purpose_id', 
                            'left')
                    ->join('ciputraEms.dbo.project as projectEMS',
                        'projectEMS.source_table = 2 
                        AND projectEMS.source_id = EREMS.project_id')
                    ->join('ciputraEms.dbo.pt as ptEMS',
                        'ptEMS.source_table = 2 
                        AND ptEMS.source_id = EREMS.pt_id')
                    ->where("EMS.source_id is null")->get()->result();
            return $data;
        }
        return 0;
    }
}
