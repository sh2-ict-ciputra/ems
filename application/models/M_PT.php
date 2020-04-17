<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_PT extends CI_Model
{

    // public function get()
    // {
    //     $query = $this->db->query("SELECT * FROM PT");
    //     return $query->result_array();
    // }
    public function get(){
        $project = $this->m_core->project();
        $project = $this->db->from("project")
                        ->where("id",$project->id)
                        ->get()->row();
        return $this->db->select("*")
                 ->from("dbmaster.dbo.view_pt")
                 ->where("project_id",$project->source_id)
                 ->get()->result();
        
    }
    public function ajax_save($data){
        $project                = $this->m_core->project();
        $data                   = (object)$data;
        $pt_apikey              = (object)[];
        $pt_apikey->pt_id       = $data->pt_id;
        $pt_apikey->project_id  = $project->id;
        $pt_apikey->apikey      = $data->apikey;
        $this->db->where('pt_id',$pt_apikey->pt_id);
        $this->db->where('project_id',$pt_apikey->project_id);
        $this->db->where('apikey',$pt_apikey->apikey);
        $this->db->delete('pt_apikey');
        $this->db->insert('pt_apikey',$pt_apikey);
        return "success";
    }
    public function get_dbmaster($id = null){
        $project = $this->m_core->project();

        $query = $this->db->select("
                                    m_pt.pt_id,
                                    m_pt.code,
                                    m_pt.name,
                                    isnull(pt_apikey.apikey,'') as apikey,
                                    m_pt.address,
                                    m_pt.phone,
                                    m_pt.npwp,
                                    '' as zipcode,
                                    m_pt.rekening
                                ")
                        ->from("dbmaster.dbo.m_pt")
                        ->join("ems.dbo.project",
                                "project.source_id = m_pt.project_id")
                        ->join("ems.dbo.pt_apikey",
                                "pt_apikey.pt_id = m_pt.pt_id
                                and pt_apikey.project_id = project.id",
                                "LEFT")
                        ->where("project.id",$project->id);
        if($id){
            $query = $query->where("m_pt.pt_id",$id);
        }
        return $query->get()->result();
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
    public function save($dataTmp){
        echo("<pre>");
            print_r($dataTmp);
        echo("</pre>");
        
    }
    public function get_ajax_pt_source($source)
    {
        $project = $this->m_core->project();
        $table = '';
        if ($source == 1)       $table = "global_pt";
        elseif ($source == 2)   $table = "erems_pt";
        elseif ($source == 3)   $table = "qs_pt";

        if ($table != '') {
            $query = $this->db->query("
                SELECT
                    global_pt.pt_id,
                    global_pt.code,
                    global_pt.name,
                    global_pt.npwp,
                    global_pt.project_id,
                    pt.id
                FROM $table
                JOIN project
                    ON project.id = $project->id
                    AND project.source_table = $source
                    AND project.source_id = global_pt.project_id
                LEFT JOIN pt
                    ON pt.source_id = global_pt.pt_id
                    AND pt.source_table = project.source_table
                WHERE pt.id IS NULL
            ")->result();
            return $query;
        }
        return 0;
    }
}
