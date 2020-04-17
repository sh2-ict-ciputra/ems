<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_PT extends CI_Model
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
