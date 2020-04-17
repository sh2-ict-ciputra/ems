<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_user extends CI_Model
{
    public function get()
    {
        return $this->db
                        ->select("
                                id,
                                name,
                                username,
                                CASE
                                    WHEN active = 1 THEN 'Aktif'
                                    ELSE 'Tidak Aktif'
                                END as status,
                                ces_id,
                                email")
                        ->from("user")
                        ->get()->result();
    }
    public function get_edit($id)
    {
        return $this->db
                    ->select("
                            id,
                            name,
                            username,
                            CASE
                                WHEN active = 1 THEN 'Aktif'
                                ELSE 'Tidak Aktif'
                            END as status,
                            ces_id,
                            description,
                            email")
                    ->from("user")
                    ->where("id",$id)
                    ->get()->row();
    }
    
    public function save($data){
        $data = (object)$data;
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db->where('username', $data->username);
        $this->db->from('user');
        // validasi double username
        if ($this->db->count_all_results() > 0) {
            $return->message = "Username sudah di gunakan";
            return $return;
        }
        
        $this->db->where('name', $data->name);
        $this->db->from('user');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) {
            $return->message = "Nama sudah di gunakan";
            return $return;
        }
        
        $data->failed_login = 0;
        $data->active = 1;
        $data->password = md5($data->password);
        $this->db->insert('user',$data);
        $return->status = true;        
        $return->message = "Data user berhasil di tambah";        
        return $return;

    }
    public function edit($data){
        
        $data = (object)$data;
        
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "Data gagal di ubah";
        $return->status = false;
        
        $this->db->where('username', $data->username);
        $this->db->from('user');
        // validasi double username
        if ($this->db->count_all_results() > 0) {
            $this->db->set('name',$data->name);
            $this->db->set('username',$data->username);
            if($data->password)
                $this->db->set('password',md5($data->password));
            $this->db->set('ces_id',$data->ces_id);
            $this->db->set('description',$data->description);
            $this->db->set('email',$data->email);
            $this->db->where('id',$data->id);
            $this->db->update('user');
            $return->status = true;        
            $return->message = "Data user berhasil di ubah";        
            return $return;
        }
        return $return;

    }
   
}
