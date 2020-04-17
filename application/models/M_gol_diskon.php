<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_gol_diskon extends CI_Model {
    public function get()
    {
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM gol_diskon
            where project_id = $project->id
            AND [delete] = 0
            order by id desc
        ");
        return $query->result();
    }

    public function get_all_gol_diskon()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            from gol_diskon
            where gol_diskon.project_id =  $project->id
            AND [delete] = 0
            ORDER BY gol_diskon.id DESC

        ");
        return $query->result();
    }

    public function delete($id){
        $project = $this->m_core->project();
        $this->db->where("id",$id);
        $this->db->where("project_id",$project->id);
        $this->db->update("gol_diskon",
        [
            "delete" => 1
        ]);
        if($this->db->affected_rows())
            return true;
        else
            return false;
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = 
        [
            'project_id'        => $project->id,
            'name'              => $dataTmp['name'],
            'description'       => $dataTmp['keterangan'],
            'active'            => 1,
            'delete'            => 0
        ];

        $this->db->where('name', $data['name']);
        $this->db->from('gol_diskon');

        // validasi double
        if($this->db->count_all_results()==0){ 
            
            $this->db->insert('gol_diskon', $data);
            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('gol_diskon',$this->db->insert_id(),'Tambah',$dataLog);
            return 'success';
        }else return 'double';
           
    }

    public function cek($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * 
            FROM gol_diskon
            WHERE gol_diskon.id = $id 
            AND gol_diskon.project_id = $project->id
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }

    public function getSelect($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
            SELECT * FROM gol_diskon
            WHERE id = $id 
            AND project_id = $project->id			
        ");
        $row = $query->row();
        return $row; 
    }

    public function get_log($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
            SELECT 
                gol_diskon.name as Nama,
                gol_diskon.description as Deskripsi,
                case when gol_diskon.active    = 0 then 'Tidak Aktif' else 'Aktif' end as Aktif, 
                case when gol_diskon.[delete]  = 0 then 'Tidak Aktif' else 'Aktif' end as [Delete]  
            FROM gol_diskon
            WHERE gol_diskon.id        = $id
            AND gol_diskon.project_id  = $project->id
        ");
        $row = $query->row();
        return $row; 
    }

    public function edit($dataTmp){
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();
        
        $this->db->where('project_id', $project->id);
        $this->db->from('gol_diskon');
        $data = 
        [
            'name'                          => $dataTmp['name'],
			'description'                   => $dataTmp['keterangan'],
			'active'                        => $dataTmp['active']?1:0,	
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if($this->db->count_all_results()!=0){
            $this->db->where('name', $data['name'])->where('id !=',$dataTmp['id']);
            $this->db->from('gol_diskon');
            // validasi double
            if($this->db->count_all_results()==0){
                $before = $this->get_log($dataTmp['id']);            
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('gol_diskon', $data);
                $after = $this->get_log($dataTmp['id']);
                $diff = (object)(array_diff_assoc((array)$after,(array)$before));
                $tmpDiff = (array)$diff;
                
                if($tmpDiff){
                    $this->m_log->log_save('gol_diskon',$dataTmp['id'],'Edit',$diff);
                    return 'success';
                }else return 'Tidak Ada Perubahan';
            }else return 'double';


            
            
        }
        
    }

}