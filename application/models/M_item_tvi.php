<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_item_tvi extends CI_Model
{
    public function get()
    {
        return $this->db
                        ->select("*")
                        ->from("item_tvi")
                        ->where("delete = 0")
                        ->get()->result();
    }
    public function get_edit($id)
    {
        return $this->db
                    ->select("
                    id,
                    nama,
                    satuan,
                    harga_satuan,
                    keterangan,
                    is_channel")
                    ->from("item_tvi")
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
        
        $this->db->where('nama', $data->nama);
        $this->db->from('item_tvi');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) {
            $return->message = "Nama sudah di gunakan";
        }
        $data->harga_satuan = $this->m_core->currency_to_number($data->harga_satuan);
        $data->project_id = $project->id;
        // $data->is_channel = implode(",",$data->is_channel);
        $data->delete = 0;
        $this->db->insert('item_tvi',$data);
        $dataLog = $this->get_log($this->db->insert_id());
        $this->m_log->log_save('item_tvi', $this->db->insert_id(), 'Tambah', $dataLog);
        $return->status = true;        
        $return->message = "Data item berhasil di tambah";        
        return $return;

    }
    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query= $this->db
                        ->select("*")
                        ->from("item_tvi")
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
        
        $this->db->where('nama', $data->nama);
        $this->db->from('item_tvi');
        // validasi double nama
        if ($this->db->count_all_results() > 0) {            
            $before = $this->get_log($data->id);
            $this->db->set('nama',$data->nama);
            $this->db->set('satuan',$data->satuan);
            $this->db->set('harga_satuan',$data->harga_satuan);
            $this->db->set('keterangan',$data->keterangan);
            $this->db->set('is_channel',$data->is_channel);
            $this->db->where('id',$data->id);
            $this->db->update('item_tvi');
            $after = $this->get_log($data->id);
            $return->message = "Nama sudah di gunakan";        
        }
        $diff = (object) (array_diff_assoc((array) $after, (array) $before));
        $tmpDiff = (array) $diff;
        if ($tmpDiff) {
            $this->m_log->log_save('item_tvi', $data->id, 'Edit', $diff);
        }
        $return->status = true;  
        $return->message = "Data item berhasil di ubah";   
        return $return;

    }
    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('item_tvi');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('item_tvi', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('item_tvi', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
                   
        }
    }
   
}
