<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_jabatan extends CI_Model
{
    public function get()
    {
        return $this->db
                        ->select("
                                id,
                                name,
                                description,
                                CASE
                                    WHEN active = 1 THEN 'Aktif'
                                    ELSE 'Tidak Aktif'
                                END as status
                                ")
                        ->from("jabatan")
                        ->get()->result();
    }
    public function get_select($id){
        return $this->db->select("*")
                        ->from("jabatan")
                        ->where("id",$id)
                        ->get()->row();
    }
    public function save($data){
        $data = (object)$data;

        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db->where('name', $data->name);
        $this->db->from('jabatan');
        
        // validasi double jabatan
        if ($this->db->count_all_results() > 0) {
            $return->message = "Nama jabatan sudah di gunakan";
            return $return;
        }
        
        $data->active = 1;
        $this->db->insert('jabatan',$data);
        $return->status = true;        
        $return->message = "Data Jabatan berhasil di tambah";        
        return $return;

    }
    public function edit($id,$data){
        $data = (object)$data;

        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db->where('name', $data->name);
        $this->db->from('jabatan');
        
        // validasi double jabatan
        if ($this->db->count_all_results() > 0) {
            $return->message = "Nama jabatan sudah digunakan";
            return $return;
        }
        $data->active = 1;
        $this->db->where("id",$id);
        $this->db->update('jabatan',$data);

        $return->status = true;        
        $return->message = "Data Jabatan berhasil di tambah";        
        return $return;

    }
   
}
