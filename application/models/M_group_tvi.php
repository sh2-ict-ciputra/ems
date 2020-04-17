<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_group_tvi extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            FROM group_tvi 
            where active =1 
            and project_id = $project->id 
            and [delete] = 0 
            order by id DESC
        ");

        return $query->result_array();
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
		    SELECT * FROM group_tvi 
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
            SELECT * FROM group_tvi WHERE id = $id and project_id = $project->id
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                code as [Code],
                name as [Name],
                jumlah_hari as [Jumlah Hari],
                description as [Description],
                case
                    when active = 1 then 'Aktif'
                    else 'Tidak Aktif'
                end as Aktif
            FROM group_tvi
            WHERE project_id = $project->id
            AND id = $id
        ");
        $row = $query->row();

        return $row;
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data =
        [
            'code' => $dataTmp['kode_group'],
            'project_id' => $project->id,
            'name' => $dataTmp['nama_group'],
            'jumlah_hari' => $this->m_core->currency_to_number($dataTmp['jumlah_hari']),
            'jenis_bayar' => $dataTmp['jenis_bayar'],
            'description' => $dataTmp['keterangan'],
            'active' => 1,
            'delete' => 0,
        ];

        $this->db->where('code', $data['code']);
        $this->db->from('group_tvi');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('group_tvi', $data);

            $idTMP = $this->db->insert_id();
            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('group_tvi', $idTMP, 'Tambah', $dataLog);

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

        $this->db->where('project_id', $project->id);
        $this->db->from('group_tvi');
        $data =
        [
            'code' => $dataTmp['kode_group'],
            'name' => $dataTmp['nama_group'],
            'jumlah_hari' => $this->m_core->currency_to_number($dataTmp['jumlah_hari']),
            'jenis_bayar' => $dataTmp['jenis_bayar'],
            'description' => $dataTmp['keterangan'],
            'active' => $dataTmp['active'] ? 1 : 0,
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('code', $data['code'])->where('id !=', $dataTmp['id']);
            $this->db->from('group_tvi');
            // validasi double
            if ($this->db->count_all_results() == 0) {
                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('group_tvi', $data);
                $after = $this->get_log($dataTmp['id']);
                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                if ($tmpDiff) {
                    $this->m_log->log_save('group_tvi', $dataTmp['id'], 'Edit', $diff);

                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
                }
            } else {
                return 'double';
            }
        }
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('group_tvi');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('group_tvi', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('group_tvi', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
