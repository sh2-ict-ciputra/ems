<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_group_level extends CI_Model
{
    public function get()
    {
        return $this->db
                        ->select("
                            group_user_level.id,
                            project.name as project,
                            user.name as user,
                            jabatan.name as jabatan,
                            level.name as level,
                            group_user_level.description    
                        ")
                        ->from("group_user_level")
                        ->join("group_user",
                                "group_user.id = group_user_level.group_user_id")
                        ->join("project",
                                "project.id = group_user.project_id")
                        ->join("user",
                                "user.id = group_user.user_id")
                        ->join("jabatan",
                                "jabatan.id = group_user.jabatan_id")
                        ->join("level",
                                "level.id = group_user_level.level_id")
                        ->where("group_user_level.delete",0)
                        ->get()->result();
    }
    public function get_group_user(){
        return $this->db
                        ->select("
                            group_user.id,
                            project.name as project,
                            jabatan.name as jabatan,
                            user.name as user,
                            group_user.description    
                        ")
                        ->from("group_user")
                        ->join("project",
                                "project.id = group_user.project_id")
                        ->join("user",
                                "user.id = group_user.user_id")
                        ->join("jabatan",
                                "jabatan.id = group_user.jabatan_id")
                        ->where("group_user.delete",0)
                        ->get()->result();
    }
    
    public function get_level(){
        return $this->db
                        ->from("level")
                        ->where("delete",0)
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
        
        $this->db->where('group_user_id', $data->group_user_id);
        $this->db->where('level_id', $data->level_id);
        $this->db->from('group_user_level');

        // validasi double
        if ($this->db->count_all_results() > 0) {
            $return->message = "Data Group Level sudah di ada";
            return $return;
        }

        $this->db->insert('group_user_level',$data);
        
        $return->status = true;        
        $return->message = "Data Group Level berhasil di tambah";        
        return $return;

    }
    public function delete($data){
    
        $return = (object)[];
        $return->message = "Data Group Level Gagal di Hapus";
        $return->status = false;

        $this->db->trans_start();
        $this->db->where("id",$data["id"]);
        $this->db->update("group_user_level",['delete'=> 1]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE){
            $return->message = "Data Group Level Berhasil di Hapus";
            $return->status = true;
        }

        return $return;
    }
   
}
