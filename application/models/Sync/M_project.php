<?php

defined("BASEPATH") or exit("No direct script access allowed");

class m_project extends CI_Model
{
    public function get_ajax_project_by_source($source)
    {
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";
        
        if ($source == "2") {
//             SELECT 
// 	DISTINCT 
// 	project_id,
// 	project_name
// FROM $erems.dbo.view_serahterima
// LEFT JOIN ems.dbo.project
// 	ON project.source_table = '2'
// 	AND project.source_id = view_serahterima.project_id
// WHERE project.id is null
// ORDER BY project_id
            $data=$this->db
                    ->select("
                        project_id as source_id,
                        project_name as name,
                        2 as source_table,
                        subholding_id as subholding
                    ")
                    ->from("$erems.dbo.view_serahterima")
                    ->join("project",
                            "project.source_table = 2 
                            AND project.source_id = view_serahterima.project_id", 
                            "left")
                    ->where("project.id is null")
                    ->distinct()
                    ->get()->result();
            // $data=$this->db
            //         ->select("
            //             projectErems.subholding_id as subholding,
            //             projectErems.contactperson,
            //             projectErems.code,
            //             projectErems.name,
            //             projectErems.address,
            //             projectErems.zipcode,
            //             projectErems.phone,
            //             projectErems.fax,
            //             projectErems.email,
            //             2 as source_table,
            //             projectErems.project_id as source_id
            //         ")
            //         ->from("$erems.dbo.project as projectErems")
            //         ->join("ciputraEms.dbo.project as projectEms",
            //                 "projectEms.source_table = 2 AND projectEms.source_id = projectErems.project_id", 
            //                 "left")
            //         ->where("projectEms.id is null")->get()->result();
            return $data;
        }
        return 0;
    }
    public function save($data_id,$source){
        // $erems = $this->load->database("erems",true)->database;
        $erems = "erems";

        if($source == 2){
            $data=$this->db
            ->select("
                project_id as source_id,
                project_name as name,
                2 as source_table,
                subholding_id as subholding
            ")
            ->from("$erems.dbo.view_serahterima")
            ->join("project",
                    "project.source_table = 2 
                    AND project.source_id = view_serahterima.project_id", 
                    "left")
            ->where("project.id is null")
            ->where_in("view_serahterima.project_id",$data_id)

            ->distinct()
            ->get()->result();
            // $data = $this->db
            
            // ->select("
            //     projectErems.subholding_id as subholding,
            //     projectErems.contactperson,
            //     projectErems.code,
            //     projectErems.name,
            //     projectErems.address,
            //     projectErems.zipcode,
            //     projectErems.phone,
            //     projectErems.fax,
            //     projectErems.email,
            //     2 as source_table,
            //     1 as active,
            //     0 as delete,
            //     projectErems.project_id as source_id
            // ")
            // ->from("$erems.dbo.project as projectErems")
            // ->join("ciputraEms.dbo.project as projectEms",
            //         "projectEms.source_table = 2 AND projectEms.source_id = projectErems.project_id", 
            //         "left")
            // ->where("projectEms.id is null")
            // ->where_in("projectErems.project_id",$data_id)
            // ->get()->result_array();
            if($data){
                $this->db->trans_start();
                $this->db->insert_batch("project",$data); 
                $this->db->trans_complete();
            }
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah uang di input:".count($data)
                    ];
        }
    }
}
