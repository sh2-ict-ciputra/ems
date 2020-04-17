<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_golongan extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM jenis_golongan where project_id = $project->id and [delete] = 0 order by id desc
        ");

        return $query->result_array();
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
		SELECT * FROM jenis_golongan where  project_id = $project->id and id = $id
			
        ");
        $row = $query->row();

        return $row;
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM jenis_golongan WHERE id = $id 
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
             SELECT code, description,
             case
                    when active = 1 then 'Aktif'
                    else 'Tidak Aktif'
                end as Aktif	
				FROM jenis_golongan where id = $id
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
            'code' => $dataTmp['code'],
            'project_id' => $project->id,
            'description' => $dataTmp['keterangan'],
            'active' => 1,
            'delete' => 0,
        ];

        $this->db->where('code', $data['code']);
        $this->db->from('jenis_golongan');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('jenis_golongan', $data);
			
			$idTMP = $this->db->insert_id();
            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('jenis_golongan', $idTMP, 'Tambah', $dataLog);

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
        $this->db->from('jenis_golongan');
        $data =
        [
            'code' => $dataTmp['code'],
            'description' => $dataTmp['keterangan'],
            'active' => $dataTmp['active'] ? 1 : 0,
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('code', $data['code'])->where('id !=', $dataTmp['id']);
            $this->db->from('jenis_golongan');
            // validasi double
            if ($this->db->count_all_results() == 0) {
                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('jenis_golongan', $data);
                $after = $this->get_log($dataTmp['id']);

                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;
                if ($tmpDiff) {
                    $this->m_log->log_save('jenis_golongan', $dataTmp['id'], 'Edit', $diff);

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
        $this->db->from('jenis_golongan');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('jenis_golongan', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('jenis_golongan', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
