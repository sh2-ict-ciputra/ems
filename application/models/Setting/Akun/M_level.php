<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_level extends CI_Model
{
    public function get()
    {
        return $this->db->from("level")
                        ->where("delete",0)
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
        
        $this->db->where('name', $data->name);
        $this->db->from('level');

        // validasi double
        if ($this->db->count_all_results() > 0) {
            $return->message = "Nama Level sudah di ada";
            return $return;
        }

        $this->db->insert('level',$data);
        // $id = $this->db->insert_id();
        
        $return->status = true;        
        $return->message = "Data Level berhasil di tambah";        
        return $return;

    }
    public function delete($data){
    
        $return = (object)[];
        $return->message = "Gagal Hapus";
        $return->status = false;

        $this->db->trans_start();
        $this->db->where("id",$data["id"]);
        $this->db->update("level",['delete'=> 1]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE){
            $return->message = "Level berhasil di hapus";
            $return->status = true;
        }

        return $return;
    }
   
}
