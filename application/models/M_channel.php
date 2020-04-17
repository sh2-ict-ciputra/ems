<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_channel extends CI_Model
{
    public function get()
    {
        return $this->db
                        ->select("*")
                        ->from("channel")
                        ->where("delete = 0")
                        ->get()->result();
    }
    
    public function get_edit($id)
    {
        return $this->db
                    ->select("
                    id,
                    name,
                    category")
                    ->from("channel")
                    ->where("id",$id)
                    ->get()->row();
    }
    
    public function save($data){
        $project = $this->m_core->project();
        $this->load->model('m_core');
        $this->load->model('m_log');
        $data = (object)$data;
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db->where('name', $data->nama);
        $this->db->from('channel');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) {
            $return->message = "Nama sudah di gunakan";
        }
        $data->project_id = $project->id;
        $data->delete = 0;
        $this->db->insert('channel',$data);
        $dataLog = $this->get_log($this->db->insert_id());
        $this->m_log->log_save('channel', $this->db->insert_id(), 'Tambah', $dataLog);
        $return->status = true;        
        $return->message = "Data channel berhasil di tambah";        
        return $return;
    }
    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query= $this->db
                        ->select("*")
                        ->from("channel")
                        ->where("id",$id)
                        ->where("project_id",$project->id)
                        ->get()->row();
        // $query = $this->db->query("
        //      SELECT *
		// 		FROM item_tvi where id = $id
        // ");
        // $row = $query->row();

        return $query;
    }
    public function edit($data){
        $data = (object)$data;
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $this->load->model('m_log');
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "Data gagal di ubah";
        $return->status = false;
        
        $this->db->where('name', $data->name);
        $this->db->from('channel');
        // validasi double nama
        if ($this->db->count_all_results() > 0) {            
            $before = $this->get_log($data->id);
            $this->db->set('name',$data->name);
            $this->db->set('category',$data->category);
            $this->db->where('id',$data->id);
            $this->db->update('channel');
            $after = $this->get_log($data->id);
            $return->message = "Nama sudah di gunakan";        
        }
        $diff = (object) (array_diff_assoc((array) $after, (array) $before));
        $tmpDiff = (array) $diff;
        if ($tmpDiff) {
            $this->m_log->log_save('channel', $data->id, 'Edit', $diff);
        }
        $return->status = true;  
        $return->message = "Data channel berhasil di ubah";   
        return $return;
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('channel');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('channel', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('channel', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
                   
        }
    }
   
}
