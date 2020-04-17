<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_group extends CI_Model
{
    public function get()
    {
        return $this->db
                        ->select("
                                group_user.id,
                                user.name as user,
                                user.username,
                                jabatan.name as jabatan,
                                project.name as project,
                                group_user.description,
                                CASE
                                    WHEN group_user.active = 1 THEN 'Aktif'
                                    ELSE 'Tidak Aktif'
                                END as status
                        ")
                        ->from("group_user")
                        ->join("user",
                                "user.id = group_user.user_id")
                        ->join("jabatan",
                                "jabatan.id = group_user.jabatan_id")
                        ->join("project",
                                "project.id = group_user.project_id")
                        ->get()->result();
    }
    public function get_jabatan(){
        return $this->db
                        ->select("
                                    id,
                                    name")
                        ->from("jabatan")
                        ->where("active",1)
                        ->get()->result();    
    }
    public function get_user(){
        return $this->db
                        ->select("
                                    id,
                                    name,
                                    username")
                        ->from("user")
                        ->where("active",1)
                        ->get()->result();    
    }
    public function get_project(){
        return $this->db
                        ->select("
                                    id,
                                    name")
                        ->from("project")
                        ->where("active",1)
                        ->get()->result();    
    }
    

    public function save($data){
        $data = (object)$data;

        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db
                ->where('user_id', $data->user_id)
                ->where('jabatan_id', $data->jabatan_id)
                ->where('project_id', $data->project_id)
                ;
        $this->db->from('group_user');

        // validasi double
        if ($this->db->count_all_results() > 0) {
            $return->message = "Kombinasi User,Jabatan dan Project sudah di gunakan";
            return $return;
        }
       
        $data->active = 1;
        $this->db->insert('group_user',$data);
        $id = $this->db->insert_id();

        $this->db->set("group_user_id",$id);
        $this->db->where("group_user_id is null");
        $this->db->where("id",$data->user_id);
        $this->db->update('user');
        
        $return->status = true;        
        $return->message = "Data Group berhasil di tambah";        
        return $return;

    }
   
}
