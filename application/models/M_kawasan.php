<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_kawasan extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM kawasan where project_id = $project->id and [delete] = 0 order by id desc
        ");

        return $query->result_array();
    }

    public function getAll()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                kawasan.name as kawasan_name, 
                kawasan.code as kawasan_code, 
                kawasan.id 
            FROM  kawasan  
            WHERE kawasan.active =1 
            AND kawasan.project_id= $project->id 
            AND kawasan.[delete] = 0 
            ORDER BY kawasan.id desc
        
        ");

        return $query->result_array();
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $data =
        [
            'nama_kawasan' => $dataTmp['nama_kawasan'],
            'project_id' => $project->id,
            'keterangan' => $dataTmp['keterangan'],
            'delete' => 0,
        ];

        $this->db->where('code', $data['code']);
        $this->db->from('cluster');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('cluster', $data);

            return 'success';
        } else {
            return 'double';
        }
    }
}
