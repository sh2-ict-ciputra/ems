<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_customer extends CI_Model
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
            $data = $this->db
                    ->select("
                        view_serahterima.customer_id as source_id,
                        '2' as source_table,
                        view_serahterima.customer_name as name,
                        view_serahterima.customer_address as address,
                        view_serahterima.customer_email as email,
                        view_serahterima.customer_ktp as ktp,
                        view_serahterima.KTP_address as ktp_address,
                        view_serahterima.customer_mobilephone as mobilephone1,
                        view_serahterima.customer_homephone as homephone,
                        view_serahterima.customer_npwp as npwp_no,
                        view_serahterima.npwp_name as npwp_name,
                        view_serahterima.npwp_address as npwp_address,
                        view_serahterima.pt_id,
                        '$project->id' as project_id,
                        1 as active,
                        0 as delete
                    ")
                    ->from("$erems.dbo.view_serahterima")
                    ->join("project",
                            "project.source_table = '2'
                            AND project.source_id = view_serahterima.project_id
                            ANd project.id = $project->id")
                    ->join("customer",
                            "customer.source_table = '2'
                            AND customer.source_id = view_serahterima.customer_id",
                            "LEFT")
                    ->where("customer.id is null")
                    ->where("project.id is not null")
                    ->where_in("view_serahterima.customer_id",$data_id)
                    ->get()->result_array();            
            if($data){
                $this->db->trans_start();
                $this->db->insert_batch("customer",$data); 
                $this->db->trans_complete();
            }
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah data di input:".count($data)
                    ];
        }
    }
    public function get_ajax_customer_by_source($source,$project)
    {   
        ini_set('memory_limit', '-1');
        // ini_set('sqlsrv.ClientBufferMaxKBSize','200000'); // Setting to 512M 524288
        // ini_set('pdo_sqlsrv.client_buffer_max_kb_size','200000'); // Setting to 512M 524288 - for pdo_sqlsrv
                
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";
        if ($source == "2") {
            $data = $this->db
                    ->select("
                        view_serahterima.customer_id as source_id,
                        '2' as source_table,
                        view_serahterima.customer_name as name,
                        view_serahterima.customer_address as adress,
                        view_serahterima.customer_email as email,
                        view_serahterima.customer_ktp as ktp,
                        view_serahterima.KTP_address as ktp_adress,
                        view_serahterima.customer_mobilephone as mobilephone1,
                        view_serahterima.customer_homephone as homephone,
                        view_serahterima.customer_npwp as npwp_no,
                        view_serahterima.npwp_name as npwp_name,
                        view_serahterima.npwp_address as npwp_address,
                        view_serahterima.project_id,
                        view_serahterima.project_name,
                        view_serahterima.project_id,
                        view_serahterima.project_name,
                        view_serahterima.pt_name as pt_name
                    ")
                    ->from("$erems.dbo.view_serahterima")
                    ->join("project",
                            "project.source_table = '2'
                            AND project.source_id = view_serahterima.project_id
                            ANd project.id = $project->id")
                    ->join("customer",
                            "customer.source_table = '2'
                            AND customer.source_id = view_serahterima.customer_id",
                            "LEFT")
                    ->where("customer.id is null")
                    ->where("project.id is not null")
                    ->limit(900)
                    ->distinct()
                    ->get()->result();
            return $data;
        }
        return 0;
    }
}
