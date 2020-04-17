<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_peruntukan_loi extends CI_Model
{

    public function get_all()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db 
                            ->select("peruntukan_loi.*,
                                    jenis_loi.nama as nama_jenis,
                                    kategori_loi.nama as nama_kategori")
                            ->from("peruntukan_loi")
                            ->join("jenis_loi",
                                    "jenis_loi.id = peruntukan_loi.jenis_loi_id")
                            ->join('kategori_loi',
                                    "kategori_loi.id = jenis_loi.kategori_loi_id")
                            ->where("peruntukan_loi.delete",0)
                            ->where_in("peruntukan_loi.project_id",[0,$project->id])
                            ->order_by("id","DESC");
        return $query->get()->result();
    }

    public function get_jenis()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db
                            ->select("jenis_loi.id,
                                    concat(kategori_loi.nama,' - ',jenis_loi.nama) as nama")
                            ->from("jenis_loi")
                            ->join('kategori_loi',
                                    "kategori_loi.id = jenis_loi.kategori_loi_id")
                            ->where("jenis_loi.delete",0)
                            ->where("kategori_loi.delete",0)
                            ->where_in("jenis_loi.project_id",[0,$project->id])
                            ->order_by("jenis_loi.id","ASC");
        return $query->get()->result();
    }

    public function getSelect($id)
    {
        return $this->db
                    ->select("
                    jenis_loi.id as jenis,
                    peruntukan_loi.*")
                    ->from("peruntukan_loi")
                    ->join("jenis_loi",
                            "jenis_loi.id = peruntukan_loi.jenis_loi_id","LEFT")
                    ->where("peruntukan_loi.id",$id)
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

        $query = $this->db->query("
            select 
                jenis_loi.nama as [Jenis LOI],
                peruntukan_loi.nama as [Nama],
                case 
                    when peruntukan_loi.[delete] = 1 then 'Ya' 
                    else 'Tidak' 
                end as [Delete] 
            from peruntukan_loi
            LEFT JOIN jenis_loi
            ON jenis_loi.id = peruntukan_loi.jenis_loi_id
            WHERE peruntukan_loi.id = $id
        ");
        $row = $query->row();
        return $row;
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
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $data = (object)$data;
        $data->project_id = $project->id;
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db->where('kode', $data->kode);
        $this->db->where('project_id', $data->project_id);
        $this->db->from('peruntukan_loi');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) {
            $return->message = "Kode sudah di gunakan";
            return $return;
        }

        $data->delete = 0;
        $this->db->insert('peruntukan_loi',$data);
        $id = $this->db->insert_id();
        
        $dataLog = $this->get_log($id);
        $this->m_log->log_save('peruntukan_loi',$id,'Tambah',$dataLog);
        $return->status = true;        
        $return->message = "Data berhasil di tambah";
        return $return;
    }

    public function edit($data){
        

        $this->load->model('m_core');
        $project = $this->m_core->project();
        $this->load->model('m_log');

        $data = (object)$data;
        $data->project_id = $project->id;
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "Data gagal di ubah";
        $return->status = false;
        $this->db->where('id', $data->id);
        $this->db->where('project_id', $data->project_id);
        $this->db->from('peruntukan_loi');
        // validasi double kode
        if ($this->db->count_all_results() > 0) {            
            $before = $this->get_log($data->id);
            $this->db->set('jenis_loi_id',$data->jenis_loi_id);
            $this->db->set('nama',$data->nama);
            $this->db->set('kode',$data->kode);
            $this->db->where('id',$data->id);
            $this->db->update('peruntukan_loi');
            $after = $this->get_log($data->id);
            $return->message = "ID sudah di gunakan";        
        }
        $diff = (object) (array_diff_assoc((array) $after, (array) $before));
        $tmpDiff = (array) $diff;
        if ($tmpDiff) {
            $this->m_log->log_save('peruntukan_loi', $data->id, 'Edit', $diff);
        }
        $return->status = true;  
        $return->message = "Data berhasil di ubah";   
        return $return;
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');

        $before = $this->get_log($dataTmp['id']);
        $this->db->where('id', $dataTmp['id']);
        $this->db->update('peruntukan_loi', ['delete' => 1]);
        $after = $this->get_log($dataTmp['id']);

        $diff = (object)(array_diff((array)$after, (array)$before));
        $tmpDiff = (array)$diff;

        if ($tmpDiff) {
            $this->m_log->log_save('peruntukan_loi', $dataTmp['id'], 'Edit', $diff);

        }
        return 'success';
    }
}

