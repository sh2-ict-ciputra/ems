<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_unit_type extends CI_Model {

    public function get()
    {
        $query = $this->db->query("
            SELECT * FROM unit_type
        ");
        return $query->result_array();
    }

    public function get_all_unit_type()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * from unit_type
            where project_id =  $project->id
            ORDER BY id DESC
        ");
        return $query->result_array();
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
        $this->db->from('unit_type');

        // validasi double
        if($this->db->count_all_results()==0){ 
            
            $this->db->insert('unit_type', $data);
            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('unit_type',$this->db->insert_id(),'Tambah',$dataLog);
            return 'success';
        }else return 'double';
           
    }

    public function cek($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * 
            FROM unit_type
            WHERE id = $id 
            AND project_id = $project->id
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }

    public function getSelect($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
            SELECT * FROM unit_type
            WHERE id = $id 
            AND project_id = $project->id			
        ");
        $row = $query->row();
        return $row; 
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
            SELECT 
                name as Nama,
                description as Deskripsi,
                case when active    = 0 then 'Tidak Aktif' else 'Aktif' end as Aktif, 
                case when [delete]  = 0 then 'Tidak Aktif' else 'Aktif' end as [Delete]  
            FROM unit_type
            WHERE id        = $id
            AND project_id  = $project->id
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
        $this->db->from('unit_type');
        $data = 
        [
            'name'                          => $dataTmp['name'],
			'description'                   => $dataTmp['keterangan'],
			'active'                        => $dataTmp['active']?1:0,	
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if($this->db->count_all_results()!=0){
            $this->db->where('name', $data['name'])->where('id !=',$dataTmp['id']);
            $this->db->from('unit_type');
            // validasi double
            if($this->db->count_all_results()==0){
                $before = $this->get_log($dataTmp['id']);            
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('unit_type', $data);
                $after = $this->get_log($dataTmp['id']);
                $diff = (object)(array_diff_assoc((array)$after,(array)$before));
                $tmpDiff = (array)$diff;
                
                if($tmpDiff){
                    $this->m_log->log_save('unit_type',$dataTmp['id'],'Edit',$diff);
                    return 'success';
                }else return 'Tidak Ada Perubahan';
            }else return 'double';  
        }
        
    }

}

?>