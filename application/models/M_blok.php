<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_blok extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM blok where project_id = $project->id order by id desc
        ");

        return $query->result_array();
    }

    public function getAll()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            select 
                kawasan.name as kawasan_name,
                blok.name as blok_name, 
                blok.description as blok_desc, 
                blok.id 
            from kawasan
            JOIN blok ON blok.kawasan_id = kawasan.id 
            WHERE blok.active = 1 
            and kawasan.project_id = $project->id 
            and blok.[delete] = 0 
            order by blok.id desc
        ");

        return $query->result_array();
    }

    public function mapping_save($dataTmp)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $data =
        [
            'pt_id' => $dataTmp['pt_id'],
            'project_id' => $project->id,
            'cluster_id' => $dataTmp['cluster_id'],
            'code' => $dataTmp['code'],
            'block' => $dataTmp['block'],
            'description' => $dataTmp['description'],
            'iconl' => $dataTmp['iconl'],
            'addon' => $dataTmp['addon'],
            'addby' => $dataTmp['addby'],
            'modion' => $dataTmp['modion'],
            'modiby' => $dataTmp['modiby'],
            'inactiveon' => $dataTmp['inactiveon'],
            'inactiveby' => $dataTmp['inactiveby'],
            'deleteon' => $dataTmp['deleteon'],
            'deleteby' => $dataTmp['deleteby'],
            'description' => $dataTmp['active'],
            'active' => $dataTmp['active'],
            'delete' => 0,
        ];

        $this->db->where('code', $data['code']);
        $this->db->from('blok');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('blok', $data);

            return 'success';
        } else {
            return 'double';
        }
    }
}
