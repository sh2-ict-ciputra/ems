<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_pemeliharaan_air extends CI_Model
{
    public function get($id = null)
    {
        $project = $this->m_core->project();

        $return = $this->db->from('pemeliharaan_air')
            ->where('project_id', $project->id)
            ->where('delete', 0);
        if ($id)
            $return = $return->where('id', $id);
        return $return->get()->result();
    }
    public function save($data)
    {
        $project = $this->m_core->project();

        $data = (object) $data;
        $data->project_id = $project->id;

        $this->load->model('m_core');
        $this->load->model('m_log');

        $this->db->where('code', $data->code)
            ->where('name', $data->name)
            ->where('delete', 0)
            ->where('project_id', $project->id);
        $this->db->from('pemeliharaan_air');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('pemeliharaan_air', $data);
            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('pemeliharaan_air', $this->db->insert_id(), 'Tambah', $dataLog);
            return [
                'status' => 1,
                'message' => 'Data Berhasil di Tambah'
            ];
        }
        return [
            'status' => 0,
            'message' => 'Data Gagal di Tambah (Double)'
        ];
    }
    public function get_log($id)
    {
        return $this->db->select("
                    code as Kode,
                    name as Nama,
                    ukuran_pipa as [Ukuran Pipa],
                    nilai as nilai,
                    description as keterangan,
                    case active
                        when 0 then 'Tidak Aktif' 
                        else 'Aktif' 
                    end as Aktif, 
                    case [delete]
                        when 0 then 'Tidak Terdelete' 
                        else 'Terdelete' 
                    end as [Delete]
                    ")
            ->from("pemeliharaan_air")
            ->where('id', $id)
            ->get()->row();
    }
    public function edit($data)
    {
        $this->load->model('m_log');

        $data       = (object) $data;
        $id         = $data->id;
        unset($data->id);
        $project    = $this->m_core->project();

        $this->db->where('project_id', $project->id);
        $this->db->where('id', $id);
        $this->db->from('pemeliharaan_air');

        // var_dump($this->db->get()->result());
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($id);

            $this->db->where('id', $id);
            $this->db->update('pemeliharaan_air', $data);
            $after = $this->get_log($id);

            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;
            if ($tmpDiff) {
                $this->m_log->log_save('pemeliharaan_air', $id, 'Edit', $diff);
                return [
                    'status' => 1,
                    'message' => 'Data Berhasil di Update'
                ];
            }
        }
        return [
            'status' => 0,
            'message' => 'Data Gagal di Update'
        ];
    }
    public function delete($data)
    {
        $data = (object) $data;
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $this->db->where('project_id', $project->id);
        $this->db->from('pemeliharaan_air');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($data->id);
            $this->db->where('id', $data->id);
            $this->db->update('pemeliharaan_air', ['delete' => 1]);
            $after = $this->get_log($data->id);
            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;
            if ($tmpDiff) {
                $this->m_log->log_save('pemeliharaan_air', $data->id, 'Edit', $diff);
                return [
                    'status' => 1,
                    'message' => 'Data Berhasil di Delete'
                ];
            }
            return [
                'status' => 0,
                'message' => 'Data Gagal di Delete'
            ];
        }
    }
}
