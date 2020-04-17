<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_parameter_project extends CI_Model
{
    public function get()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                *
            FROM parameter_project
            where project_id =  $project->id
            ORDER BY id DESC
        ");

        return $query->result_array();
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                name as [Name],
                value as [Value],
                description as [Keterangan]
            FROM parameter_project
            WHERE id   = $id
            AND project_id  = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                *
            FROM parameter_project 
            WHERE id = $id
            AND project_id = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                *
            FROM parameter_project 
            WHERE id = $id 
            AND project_id = $project->id
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $data =
        [
            'project_id' => $project->id,
            'name' => $dataTmp['name'],
            'value' => $dataTmp['value'],
            'description' => $dataTmp['description'],
        ];

        $this->db->where('name', $data['name']);
        $this->db->from('parameter_project');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->load->model('m_log');
            $this->db->insert('parameter_project', $data);
            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('parameter_project', $this->db->insert_id(), 'Tambah', $dataLog);

            return 'success';
        } else {
            return 'double';
        }
    }

    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $data =
        [
            'name' => $dataTmp['name'],
            'value' => $dataTmp['value'],
            'description' => $dataTmp['description'],
        ];
        // validasi apakah user dengan project $project boleh edit data ini

        $this->db->where('project_id', $project->id);
        $this->db->from('parameter_project');

        if ($this->db->count_all_results() != 0) {
            $this->db->where('name', $data['name'])->where('id !=', $dataTmp['id']);
            $this->db->from('parameter_project');
            // validasi double
            if ($this->db->count_all_results() == 0) {
                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('parameter_project', $data);
                $after = $this->get_log($dataTmp['id']);
                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                if ($tmpDiff) {
                    $this->m_log->log_save('parameter_project', $dataTmp['id'], 'Edit', $diff);

                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
                }
            } else {
                return 'double';
            }
        }
    }
}
