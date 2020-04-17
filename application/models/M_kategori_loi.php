<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_kategori_loi extends CI_Model
{

    public function get_all()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db
                            ->select("kategori_loi.*")
                            ->from("kategori_loi")
                            ->where_in("kategori_loi.project_id",[0,$project->id])
                            ->where("kategori_loi.delete",0)
                            ->order_by("id","ASC");
        return $query->get()->result();
    }

    public function getSelect($id)
    {
        return $this->db
                    ->select("
                    id,
                    kode,
                    nama")
                    ->from("kategori_loi")
                    ->where("id",$id)
                    ->get()->row();
    }

    public function getItemPaket()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db
                            ->select("*")
                            ->from("item_tvi")
                            ->where("delete",0)
                            ->where("project_id",$project->id)
                            ->get()->result();
        return $query;
    }

    public function getInfoItem($id)
    {
        $query = $this->db
                        ->select("nama,
                                satuan,
                                harga_satuan")
                        ->from("item_tvi")
                        ->where("delete",0)
                        ->get()->row();
        return $query;
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM paket_tvi WHERE id = $id 
        ");
        $row = $query->row();
        return isset($row) ? 1 : 0;
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query= $this->db
                        ->select("*")
                        ->from("kategori_loi")
                        ->where("id",$id)
                        ->where("project_id",$project->id)
                        ->get()->row();

        return $query;
    }
    
    public function get_paket_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  *  FROM paket_tvi_item
            WHERE item_tvi_id = $id 
            order by  id asc
        ");
		return $query->result_array();
    }
    
    public function save($data){
        $data = (object)$data;
        $this->load->model('m_log');
        $project = $this->m_core->project();
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db->where('kode', $data->kode);
        $this->db->from('kategori_loi');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) {
            $return->message = "Kode sudah di gunakan";
            return $return;
        }
        $data->active = 1;
        $data->delete = 0;
        $data->project_id = $project->id;
        $this->db->insert('kategori_loi',$data);
        $id = $this->db->insert_id();
        
        $dataLog = $this->get_log($id);
        $this->m_log->log_save('kategori_loi',$id,'Tambah',$dataLog);
        $return->status = true;        
        $return->message = "Data berhasil di tambah";
        return $return;
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
        
        $this->db->where('kode', $data->kode);
        $this->db->from('kategori_loi');
        // validasi double kode
        if ($this->db->count_all_results() > 0) {            
            $before = $this->get_log($data->id);
            $this->db->set('kode',$data->kode);
            $this->db->set('nama',$data->nama);
            $this->db->where('id',$data->id);
            $this->db->where("project_id",$project->id);
            $this->db->update('kategori_loi');
            $after = $this->get_log($data->id);
            $return->message = "Kode sudah di gunakan";        
        }
        $diff = (object) (array_diff_assoc((array) $after, (array) $before));
        $tmpDiff = (array) $diff;
        if ($tmpDiff) {
            $this->m_log->log_save('kategori_loi', $data->id, 'Edit', $diff);
        }
        $return->status = true;  
        $return->message = "Data berhasil di ubah";   
        return $return;

    }
    
    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('kategori_loi');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('kategori_loi', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('kategori_loi', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
                   
        }
    }
}

