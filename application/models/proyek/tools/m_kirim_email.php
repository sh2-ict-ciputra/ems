<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_kirim_email extends CI_Model
{
    
    public function get()
    {
        $query = $this->db->query('
            SELECT * FROM t_parameter
        ');

        return $query->result_array();
    }


     public function getJenisEmail()
    {

        $project = $this->m_core->project();
        $query = $this->db->query('
            SELECT distinct(type) FROM t_template_email 

        ');

        return $query->result_array();
    }


     public function getTemplateEmail($type)
    {

        $project = $this->m_core->project();
        $query = $this->db->query('
            SELECT distinct(type) FROM t_template_email where type = $type

        ');

        return $query->result_array();
    }





     public function getKawasan()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("

            SELECT * FROM kawasan where project_id = $project->id

        ");

        return $query->result_array();
    }


     public function getBlok($id)
    {

        $project = $this->m_core->project();

        if($id=='all')
        {
                                       
           $query = $this->db->query('
            SELECT * FROM blok 

        ');

         }
        else
        {
                                        
          $query = $this->db->query("
            SELECT * FROM blok where kawasan_id = $id

        ");


        }


       return $query->result_array();
    }



    public function getUnit($kawasan, $blok)
    {

        $project = $this->m_core->project();

       if($kawasan=='all' and $blok_id=='all')
    {
        

        $query = $this->db->query('
            SELECT * FROM unit
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where kawasan.project_id = $project->id  

        ');


    }
    elseif($kawasan=='all' and $blok_id!='all')
    {
       


         $query = $this->db->query('
            SELECT * FROM unit
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where unit.blok_id = $blok  and project.id = $project->id  

        ');




    }
    elseif($kawasan!='all' and $blok_id=='all')
    {
        $sql    = "select * from m_unit where cluster_id='$kawasan'";

        $query = $this->db->query('
            SELECT * FROM unit
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where kawasan.kawasan_id = $kawasan  and project.id = $project->id  

        ');



    }
    elseif($kawasan!='all' and $blok_id!='all')
    {
            $sql    = "select * from m_unit where  block_id='$blok_id' and cluster_id='$kawasan'";   


           $query = $this->db->query('
            SELECT * FROM unit
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where unit.blok_id = $blok and kawasan.kawasan_id = $ kawasan 
            and project.id = $project->id  

        ');





    }

       return $query->result_array();
    }



   


     public function getUnit2($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT project.name as project_name,
                   kawasan.name as kawasan_name,
                   blok.name as blok_name,
                   unit.luas_bangunan as luas_bangunan,
                   unit.luas_tanah as luas_tanah,
                   unit.unit_type as unit_type,
                   unit.tgl_st as tanggal_st,
                   customer.name as customer_name,
                   customer.address as customer_address,
                   customer.code as customer_code,
                   customer.homephone as customer_homephone,
                   customer.mobilephone1 as customer_mobilephone,
                   customer.email as customer_email

            FROM unit 
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            left join customer on customer.id = unit.pemilik_customer_id
            where kawasan.project_id = $project->id and unit.id = $id


             ");

        return $query->row();
    }



      public function getJenisService()
    {
        $query = $this->db->query('
            SELECT * FROM liason
        ');

        return $query->result_array();
    }



    
}
