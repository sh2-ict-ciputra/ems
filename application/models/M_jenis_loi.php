<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_jenis_loi extends CI_Model
{
    public function get_all()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db
                            ->select("jenis_loi.*,
                                    kategori_loi.nama as nama_kategori")
                            ->from("jenis_loi")
                            ->join("kategori_loi",
                                    "jenis_loi.kategori_loi_id = kategori_loi.id")
                            ->where_in("kategori_loi.project_id",[0,$project->id])
                            ->where("jenis_loi.delete",0)
                            ->order_by("id","DESC");
        return $query->get()->result();
    }

    public function getSelect($id)
    {
        return $this->db
                    ->select("
                    kategori_loi.id as kategori,
                    jenis_loi.id,
                    jenis_loi.kode,
                    jenis_loi.nama")
                    ->from("jenis_loi")
                    ->join("kategori_loi",
                            "kategori_loi.id = jenis_loi.kategori_loi_id","LEFT")
                    ->where("jenis_loi.id",$id)
                    ->get()->row();
    }

    public function get_kategori()
    {
        $this->load->model('m_core');
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            id,
            kode,
            nama
        FROM kategori_loi
            WHERE project_id = $project->id and active = 1
        ");
        return $query->result_array();
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
                        ->select("kategori_loi.nama as nama_kategori,
                                jenis_loi.kode,
                                jenis_loi.nama")
                        ->from("jenis_loi")
                        ->join("kategori_loi",
                                "kategori_loi.id = jenis_loi.kategori_loi_id")
                        ->where("jenis_loi.id",$id)
                        ->get()->row();

        return $query;
    }
    
    public function save($data){
        $project = $this->m_core->project();
        $this->load->model('m_log');

        $data = (object)$data;
        $data->project_id = $project->id;

        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db->where('kode', $data->kode);
        $this->db->where('project_id', $data->project_id);
        $this->db->from('jenis_loi');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) {
            $return->message = "Kode sudah di gunakan";
            return $return;
        }
        $data->delete = 0;
        $this->db->insert('jenis_loi',$data);
        $id = $this->db->insert_id();
        
        $dataLog = $this->get_log($id);
        $this->m_log->log_save('jenis_loi',$id,'Tambah',$dataLog);
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
        $this->db->where('project_id', $project->id);
        $this->db->from('jenis_loi');
        // validasi double kode
        if ($this->db->count_all_results() > 0) {            
            $before = $this->get_log($data->id);
            $this->db->set('kategori_loi_id',$data->kategori_loi_id);
            $this->db->set('kode',$data->kode);
            $this->db->set('nama',$data->nama);
            $this->db->where('id',$data->id);
            $this->db->update('jenis_loi');
            $after = $this->get_log($data->id);
            $return->message = "Kode sudah di gunakan";        
        }
        $diff = (object) (array_diff_assoc((array) $after, (array) $before));
        $tmpDiff = (array) $diff;
        if ($tmpDiff) {
            $this->m_log->log_save('jenis_loi', $data->id, 'Edit', $diff);
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

        // validasi apakah user dengan project $project boleh edit data ini
        // validasi Cara Pembayaran

        $before = $this->get_log($dataTmp['id']);
        $this->db->where('id', $dataTmp['id']);
        $this->db->update('jenis_loi', ['delete' => 1]);
        $after = $this->get_log($dataTmp['id']);

        $diff = (object)(array_diff((array)$after, (array)$before));
        $tmpDiff = (array)$diff;

        if ($tmpDiff) {
            $this->m_log->log_save('jenis_loi', $dataTmp['id'], 'Edit', $diff);
        } 
        return 'success';
    }
}

