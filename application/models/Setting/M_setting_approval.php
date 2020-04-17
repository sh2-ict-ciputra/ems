<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_setting_approval extends CI_Model
{
    public function get($project_id,$code){
        $result = $this->db
                        ->select("value")
                        ->from("parameter_project")
                        ->where("project_id","$project_id")
                        ->where("code",$code)
                        ->get()->row();
        if(!$result){
            $result = $this->db
                        ->select("value")
                        ->from("parameter_project")
                        ->where("project_id",null)
                        ->where("code",$code)
                        ->get()->row();
        }
        if(!$result){
            $result = "";
        }
        return $result->value;
            
    }
    public function get_view($project_id)
    {
        $project_id = $project_id!=0?$project_id:null;   
        $result = $this->db
                        ->select("
                            parameter_project_jenis.id as jenis_id,
                            parameter_project_jenis.name,
                            parameter_project_jenis.code,
                            isnull(parameter_project.value,CONCAT('Default: ',pp2.value)) as value,
                            isnull(parameter_project.description,CONCAT('Default: ',pp2.description)) as description")
                        ->from("parameter_project_jenis")
                        ->join("parameter_project as pp2",
                                "pp2.code = parameter_project_jenis.code
                                and pp2.project_id is null",
                                "LEFT");
        if($project_id)
            $result = $result->join("parameter_project",
                                "parameter_project.code = parameter_project_jenis.code
                                and parameter_project.project_id = $project_id",
                                "LEFT");
        else
            $result = $result->join("parameter_project",
                                "parameter_project.code = parameter_project_jenis.code
                                and parameter_project.project_id is null",
                                "LEFT");
        return $result->get()->result();
    }
    public function get_by_jenis_id($jenis_id,$project_id)
    {
        $result = $this->db
                        ->select("
                            parameter_project_jenis.id as jenis_id,
                            parameter_project.project_id,
                            parameter_project_jenis.name,
                            parameter_project_jenis.code,
                            parameter_project.value,
                            parameter_project.description")
                        ->from("parameter_project_jenis")
                        ->where("parameter_project_jenis.id",$jenis_id);
        if($project_id)
        $result = $result->join("parameter_project",
                                "parameter_project.code = parameter_project_jenis.code
                                and parameter_project.project_id = $project_id",
                                "LEFT");
        else
        $result = $result->join("parameter_project",
                                "parameter_project.code = parameter_project_jenis.code
                                and parameter_project.project_id is null",
                                "LEFT");
        return $result->get()->row();
    }
    public function save($data,$value,$jenis_id,$project_id){
        
        $data = (object)$data;
        $return = (object)[];
        
        $parameter_jenis = $this->db->from("parameter_project_jenis")->where("id",$jenis_id)->get()->row();
        
        $cek = $this->db->select("count(*) as c")->from("parameter_project")->where("project_id",$project_id)->where("code",$parameter_jenis->code)->get()->row()->c;
        // var_dump($data);
        if($cek == 0){
            $tmp = $this->db->select("*")
                            ->from("parameter_project_jenis")
                            ->where("code",$parameter_jenis->code)
                            ->get()->row();
            $this->db->insert("parameter_project",[
                                "project_id"    => $project_id,
                                "name"          => $tmp->name,
                                "value"         => $value,
                                "description"   => $data->description,
                                "code"          => $tmp->code
                            ]);
        }else{  
            $this->db->where("project_id","$project_id");
            $this->db->where("code",$parameter_jenis->code);
            $this->db->set("value",$value==""?null:$value);
            $this->db->set("description",$data->description==""?null:$data->description);
            $this->db->update("parameter_project");
        }

        $return->status = true;        
        $return->message = "Data user berhasil di tambah";        
        return $return;
    }
   
}
