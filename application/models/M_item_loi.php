<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_item_loi extends CI_Model
{
    public function get()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        return $this->db->select("
                                    loi_item_outflow.id,
                                    loi_item_outflow.name,
                                    count(loi_item_outflow_satuan.id) as jumlah_satuan
                                ")
                            ->from("loi_item_outflow")
                            ->join('loi_item_outflow_satuan',
                                    'loi_item_outflow_satuan.loi_item_outflow_id = loi_item_outflow.id')
                            ->group_by("
                                loi_item_outflow.id,
                                loi_item_outflow.name")
                            ->where('loi_item_outflow.project_id',$project->id)
                            ->where('loi_item_outflow.delete',0)
                            ->order_by("id desc")
                            ->get()->result();
    }

    public function getSelect($id)
    {
        
        $loi_item_outflow = $this->db->from("loi_item_outflow")
                            ->where('id',$id)
                            ->get()->row();
        $loi_item_outflow_satuan = $this->db->from("loi_item_outflow_satuan")
                                        ->where("loi_item_outflow_id",$id)
                                        ->get()->result();
        return (object)[
            'loi_item_outflow' => $loi_item_outflow,
            'loi_item_outflow_satuan' => $loi_item_outflow_satuan
        ];
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $loi_item_outflow = $this->db->select("
                                loi_item_outflow.name as Nama,
                                case loi_item_outflow.active
                                    when 1 then 'Aktif' 
                                    else 'Tidak Aktif' 
                                end as Aktif, 
                                case loi_item_outflow.[delete]
                                    when 1 then 'Ya' 
                                    else 'Tidak' 
                                end as [Delete]")
                            ->from("loi_item_outflow")
                            ->where('project_id',$project->id)
                            ->where('id',$id)
                            ->get()->row();
        $loi_item_outflow_satuan = $this->db->select("
                                                    loi_item_outflow_satuan.name as Satuan
                                                ")
                                        ->from("loi_item_outflow_satuan")
                                        ->where("loi_item_outflow_id",$id)
                                        ->get()->result();
                        
        foreach ($loi_item_outflow_satuan as $k => $v) {
            foreach ($v as $k2 => $v2) {
                $loi_item_outflow->{($k+1).".".$k2} = $v2;
            }
        }
        return $loi_item_outflow;
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
        $this->db->from('loi_item_outflow');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) return $return; 
        
        $loi_item_outflow = (object)[];
        $loi_item_outflow->project_id      = $project->id;
        $loi_item_outflow->name            = $data->name;
    
        $this->db->insert('loi_item_outflow',$loi_item_outflow);
        $loi_item_outflow->id = $this->db->insert_id();

        if (isset($data->satuan_name))
        {
            foreach ($data->satuan_name as $k => $v) {
                $loi_item_outflow_satuan = (object)[];
                $loi_item_outflow_satuan->loi_item_outflow_id   = $loi_item_outflow->id;
                $loi_item_outflow_satuan->name                  = $v;
                $this->db->insert('loi_item_outflow_satuan', $loi_item_outflow_satuan);
            }
        }
        $return->status = true;        
        $return->message = "Data berhasil di tambah";
        $dataLog = $this->get_log($loi_item_outflow->id);
        
        $this->m_log->log_save('loi_item_outflow',$loi_item_outflow->id,'Tambah',$dataLog);
        return $return;
    }

    public function save_satuan($data){
        $data = (object)$data;

        $before = $this->get_log($data->id);

        $loi_item_outflow_satuan = (object)[];
        $loi_item_outflow_satuan->loi_item_outflow_id   = $data->id;
        $loi_item_outflow_satuan->name                  = $data->satuan_name;
        $this->db->insert('loi_item_outflow_satuan', $loi_item_outflow_satuan);

        $after = $this->get_log($data->id);            
        $diff = (array_diff_assoc((array)$after, (array)$before));
        if(!$diff)
                $diff = (object)(array_diff_assoc((array)$before, (array)$after));
            
        if ($diff)
            $this->m_log->log_save('loi_item_outflow', $data->id, 'Edit', (object)$diff);
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
        $user_id = $this->m_core->user_id();

        // validasi apakah user dengan project $project boleh edit data ini
        $this->db->where('name', $data->name);
        $this->db->where('project_id', $project->id);
        $this->db->from('loi_item_outflow');
        if ($this->db->count_all_results() != 0) {
            $loi_item_outflow = (object)[];
            $loi_item_outflow->name        = $data->name;

            $before = $this->get_log($data->id);
            $this->db->where('id', $data->id);
            $this->db->update('loi_item_outflow', $loi_item_outflow);
            $loi_item_outflow->id = $data->id;
            

            $this->db->where('loi_item_outflow_id', $loi_item_outflow->id);
            $this->db->delete('loi_item_outflow_satuan');            
            foreach ($data->satuan_name as $k => $v) {
                $loi_item_outflow_satuan = (object)[];
                $loi_item_outflow_satuan->loi_item_outflow_id   = $loi_item_outflow->id;
                $loi_item_outflow_satuan->name                  = $v;
                $this->db->insert('loi_item_outflow_satuan', $loi_item_outflow_satuan);
            }

            $after = $this->get_log($data->id);            
            $diff = (array_diff_assoc((array)$after, (array)$before));
            if(!$diff)
                $diff = (object)(array_diff_assoc((array)$before, (array)$after));
            
            if ($diff) {
                $this->m_log->log_save('loi_item_outflow', $data->id, 'Edit', (object)$diff);
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
        $this->db->update('loi_item_outflow', ['delete' => 1]);
        $after = $this->get_log($data->id);

        $diff = (object)(array_diff((array)$after, (array)$before));
        $tmpDiff = (array)$diff;

        if ($tmpDiff) {
            $this->m_log->log_save('loi_item_outflow', $data->id, 'Edit', $diff);

            $return->message = "Data Sukses di Delete";
            $return->status = true;
        }
        return $return;
    }
}

