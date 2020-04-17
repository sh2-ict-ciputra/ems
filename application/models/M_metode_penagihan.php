<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_metode_penagihan extends CI_Model
{
    public function get_jenis(){
        return $this->db->select("*")->from("metode_penagihan_jenis")->get()->result();
    }
    public function getAll()
    {
        $query = $this->db->query('
            SELECT 
                * 
            FROM metode_penagihan
            
            ORDER BY id DESC

        ');

        return $query->result_array();
    }

    public function get()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                metode_penagihan.*, 
                view_coa.name as coa_name, 
                view_coa.coa as coa_code, 
                pt.name as pt_name 
            FROM metode_penagihan
            LEFT JOIN gl_2018.dbo.view_coa
                ON view_coa.coa_id = metode_penagihan.coa_mapping_id
            join pt 
                on pt.source_id = view_coa.pt_id
            where metode_penagihan.project_id =  $project->id
            AND metode_penagihan.[delete] = 0
            ORDER BY metode_penagihan.id DESC
        ");

        return $query->result_array();
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                metode_penagihan.*
            FROM metode_penagihan 
            JOIN coa_mapping on metode_penagihan.coa_mapping_id = coa_mapping.id
            WHERE metode_penagihan.id = $id
            AND coa_mapping.project_id = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function get_all_pt_coa()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            coa_mapping.id,
            pt.name as pt_name,
            coa.description as coa_name,
            coa.code as coa_code
        FROM coa_mapping
            JOIN coa ON coa.id = coa_mapping.coa_id
            JOIN pt ON pt.id = coa_mapping.pt_id
        WHERE coa_mapping.project_id = $project->id
        ");

        return $query->result_array();
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                *
            FROM metode_penagihan 
            JOIN coa_mapping on metode_penagihan.coa_mapping_id = coa_mapping.id
            WHERE metode_penagihan.id = $id 
            AND coa_mapping.project_id = $project->id
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
            metode_penagihan.code as Kode,
            metode_penagihan.name as Nama,
            metode_penagihan.description as Deskripsi,
            REPLACE(CONVERT(varchar, CAST(metode_penagihan.biaya_admin AS money), 1),'.00','') as [Biaya Admin],
                case when metode_penagihan.active    = 0 then 'Tidak Aktif' else 'Aktif' end as Aktif, 
                case when metode_penagihan.[delete]  = 0 then 'Tidak Aktif' else 'Aktif' end as [Delete], 
                pt.name as [Nama PT], 
                coa.description as [Nama COA], 
                coa_mapping.id as [Id Mapping COA] 
            FROM metode_penagihan
            join coa_mapping ON coa_mapping.id = metode_penagihan.coa_mapping_id
            join pt on pt.id            = coa_mapping.pt_id
            join coa on coa.id          = coa_mapping.coa_id
            WHERE metode_penagihan.id        = $id
            AND coa_mapping.project_id  = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $data =
        [
            'code' => $dataTmp['code'],
            'project_id' => $dataTmp['project_id'],
            'metode_penagihan_jenis_id' => $dataTmp['metode_penagihan_jenis_id'],
            'name' => $dataTmp['metode_penagihan'],
            'biaya_admin' => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
            'coa_mapping_id' => $dataTmp['coa'],
            'description' => $dataTmp['keterangan'],
            'active' => 1,
            'delete' => 0,
        ];

        $this->db->where('code', $data['code']);
        $this->db->from('metode_penagihan');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->load->model('m_log');
            $this->db->insert('metode_penagihan', $data);
            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('metode_penagihan', $this->db->insert_id(), 'Tambah', $dataLog);

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

        $this->db->join('coa_mapping', 'coa_mapping.id = metode_penagihan.coa_mapping_id');
        $this->db->where('coa_mapping.project_id', $project->id);
        $this->db->from('metode_penagihan');
        $data =
        [
            'code' => $dataTmp['code'],
            'project_id' => $dataTmp['project_id'],
            'metode_penagihan_jenis_id' => $dataTmp['metode_penagihan_jenis_id'],
            'name' => $dataTmp['metode_penagihan'],
            'biaya_admin' => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
            'coa_mapping_id' => $dataTmp['coa'],
            'description' => $dataTmp['keterangan'],
            'active' => $dataTmp['active'] ? 1 : 0,
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('code', $data['code'])->where('id !=', $dataTmp['id']);
            $this->db->from('metode_penagihan');
            // validasi double
            if ($this->db->count_all_results() == 0) {
                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('metode_penagihan', $data);
                $after = $this->get_log($dataTmp['id']);
                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                if ($tmpDiff) {
                    $this->m_log->log_save('metode_penagihan', $dataTmp['id'], 'Edit', $diff);

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

        $this->db->join('coa_mapping', 'coa_mapping.id = metode_penagihan.coa_mapping_id');
        $this->db->where('coa_mapping.project_id', $project->id);
        $this->db->from('metode_penagihan');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            // validasi Cara Pembayaran

            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('metode_penagihan', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('metode_penagihan', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
