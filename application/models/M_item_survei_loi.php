<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_item_survei_loi extends CI_Model
{
    public function get()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        return $this->db->select("*")
                            ->from("loi_item_survei")
                            ->where('project_id',$project->id)
                            ->where('delete',0)
                            ->order_by("id desc")
                            ->get()->result();
    }

    public function getSelect($id)
    {
        
        return $this->db->from("loi_item_survei")
                            ->where('id',$id)
                            ->get()->row();
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        return $this->db->select("
                                loi_item_survei.name as nama,
                                loi_item_survei.nilai,
                                loi_item_survei.satuan,
                                loi_item_survei.description as deskripsi,
                                case loi_item_survei.active
                                    when 1 then 'Aktif' 
                                    else 'Tidak Aktif' 
                                end as Aktif, 
                                case loi_item_survei.[delete]
                                    when 1 then 'Ya' 
                                    else 'Tidak' 
                                end as [Delete]")
                            ->from("loi_item_survei")
                            ->where('project_id',$project->id)
                            ->where('id',$id)
                            ->get()->row();
    }
    
    public function save($data){
        $data = (object)$data;
        $this->load->model('m_log');
        $this->load->model('m_core');
        $project = $this->m_core->project();

        // init return
        $return = (object)[];
        $return->message = "Nama sudah di gunakan";
        $return->status = false;
        
        $this->db->where('name', $data->name);
        $this->db->where('project_id', $project->id);
        $this->db->from('loi_item_survei');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) return $return; 
        
        $loi_item_survei = (object)[];
        $loi_item_survei->project_id    = $project->id;
        $loi_item_survei->name          = $data->name;
        $loi_item_survei->nilai         = $data->nilai;
        $loi_item_survei->satuan        = $data->satuan;
        $loi_item_survei->description   = $data->description;
    
        $this->db->insert('loi_item_survei',$loi_item_survei);
        $loi_item_survei->id = $this->db->insert_id();
        
        $dataLog = $this->get_log($loi_item_survei->id);
        $return->status = true;        
        $return->message = "Data berhasil di tambah";
        $this->m_log->log_save('loi_item_survei',$loi_item_survei->id,'Tambah',$dataLog);
        return $return;
    }

    public function edit($data)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');

        $data = (object)$data;
        $return = (object)[];
        $return->message = "Akses Tidak Ada";
        $return->status = false;

        $project = $this->m_core->project();

        // validasi apakah user dengan project $project boleh edit data ini
        $this->db->where('name', $data->name);
        $this->db->where('project_id', $project->id);
        $this->db->from('loi_item_survei');
        if ($this->db->count_all_results() != 0) {
            $loi_item_survei = (object)[];
            $loi_item_survei->name        = $data->name;
            $loi_item_survei->nilai       = $data->nilai;
            $loi_item_survei->satuan      = $data->satuan;
            $loi_item_survei->description = $data->description;
            
            $before = $this->get_log($data->id);
            $this->db->where('id', $data->id);
            $this->db->update('loi_item_survei', $loi_item_survei);
            $loi_item_survei->id = $data->id;
            $after = $this->get_log($loi_item_survei->id);
            $diff = (object)(array_diff_assoc((array)$after, (array)$before));
            $tmpDiff = (array)$diff;
            if ($tmpDiff) {
                $this->m_log->log_save('loi_item_survei', $data->id, 'Edit', $diff);
                $return->status = true;        
                $return->message = "Data berhasil di Update";
            } else{
                $return->status = false;        
                $return->message = "Tidak Ada Perubahan";
            }
            return $return;            
        }else return $return;
    }

    public function delete($data)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = (object)$data;
        $return = (object)[];
        $return->message = "Tidak Ada Perubahan";
        $return->status = false;
        // validasi apakah user dengan project $project boleh edit data ini

        // validasi Cara Pembayaran

        $before = $this->get_log($data->id);
        $this->db->where('id', $data->id);
        $this->db->update('loi_item_survei', ['delete' => 1]);
        $after = $this->get_log($data->id);

        $diff = (object)(array_diff((array)$after, (array)$before));
        $tmpDiff = (array)$diff;

        if ($tmpDiff) {
            $this->m_log->log_save('loi_item_survei', $data->id, 'Edit', $diff);

            $return->message = "Data Sukses di Delete";
            $return->status = true;
        }
        return $return;
    }
}

