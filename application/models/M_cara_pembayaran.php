<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_cara_pembayaran extends CI_Model
{
    public function get_jenis_cara_pembayaran()
    {
        $query = $this->db
            ->select("*")
            ->from("cara_pembayaran_jenis")
            ->get()->result();
        return $query;
    }
    public function get()
    {
        $query = $this->db->query('
            SELECT * FROM cara_pembayaran
        ');

        return $query->result_array();
    }

    public function getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                coa_mapping.*, 
                coa.code as coa_code, 
                coa.description as coa_name, 
                cara_pembayaran.*, 
                pt.name as ptName,
                bank.name as bank 
            FROM cara_pembayaran
            join coa_mapping 
                on cara_pembayaran.coa_mapping_id = coa_mapping.id
            join pt 
                on pt.id = coa_mapping.pt_id
            join coa 
                on coa.id = coa_mapping.coa_id
            LEFT JOIN bank
                ON bank.id = cara_pembayaran.bank_id
            WHERE cara_pembayaran.[delete] = 0 
            AND cara_pembayaran.project_id = $project->id 
            ORDER BY cara_pembayaran.id desc
        ");
        $query = $this->db->select("
                            view_coa.coa as coa_code, 
                            view_coa.name as coa_name, 
                            cara_pembayaran.*, 
                            pt.name as ptName,
                            bank.name as bank")
                        ->from("cara_pembayaran")
                        ->join("gl_2018.dbo.view_coa",
                                "view_coa.coa_id = cara_pembayaran.coa_mapping_id",
                                "LEFT")
                        ->join("ems.dbo.pt",
                                "pt.source_id = view_coa.pt_id",
                                "LEFT")
                        ->join("bank",
                                "bank.id = cara_pembayaran.bank_id",
                                "LEFT")
                        ->where("cara_pembayaran.delete",0) 
                        ->where("cara_pembayaran.project_id",$project->id) 
                        ->order_by("cara_pembayaran.id desc")->get();
        return $query->result_array();
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

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data =
            [
                'code'                      => $dataTmp['code'],
                'project_id'                => $project->id,
                'name'                      => $dataTmp['jenis_pembayaran'],
                'nilai_flag'                => $dataTmp['nilai_flag'],
                'biaya_admin'               => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
                'coa_mapping_id'            => $dataTmp['coa'],
                'description'               => $dataTmp['keterangan'],
                'jenis_cara_pembayaran_id'  => $dataTmp['jenis_cara_pembayaran'],
                'active'                    => 1,
                'delete'                    => 0,
                'bank_id'                   => $dataTmp['bank'],
                
                'va_bank'                   => $dataTmp['va_bank'],
                'va_merchant'               => $dataTmp['va_merchant'],
                'max_digit'                 => $dataTmp['max_digit'],
                
                'pt_id'                     => $dataTmp['pt'],
                

            ];

        $this->db   ->where('code', $data['code'])
                    ->where('delete', 0)
                    ->where('pt_id', $data['pt_id'])
                    ->where('bank_id', $data["bank_id"])
                    ->where('project_id', $project->id);
        $this->db->from('cara_pembayaran');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('cara_pembayaran', $data);
            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('cara_pembayaran', $this->db->insert_id(), 'Tambah', $dataLog);

            return 'success';
        } else {
            return 'double';
        }
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * 
            FROM cara_pembayaran 
            WHERE cara_pembayaran.id = $id 
            AND cara_pembayaran.project_id = $project->id        
            ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                cara_pembayaran.*,
                CONCAT(bank.name,' - ',bank.code) as kode_bank,
                cara_pembayaran_jenis.name as jenis_name
            FROM cara_pembayaran 
            LEFT JOIN bank
                ON bank.id = cara_pembayaran.bank_id
            JOIN cara_pembayaran_jenis
                ON cara_pembayaran_jenis.id = cara_pembayaran.jenis_cara_pembayaran_id
            WHERE cara_pembayaran.id = $id 
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
                cara_pembayaran.code as Kode,
                cara_pembayaran.name as Nama,
                cara_pembayaran.biaya_admin as [Biaya Admin],
                cara_pembayaran.description as Deskripsi,
                case when cara_pembayaran.active    = 0 then 'Tidak Aktif' else 'Aktif' end as Aktif, 
                case when cara_pembayaran.[delete]  = 0 then 'Tidak Aktif' else 'Aktif' end as [Delete], 
                pt.name as [Nama PT], 
                view_coa.coa as [Kode COA], 
                view_coa.name as [Nama COA], 
                view_coa.coa_id as [Id Mapping COA] 
            FROM cara_pembayaran
            LEFT JOIN gl_2018.dbo.view_coa
                ON view_coa.coa_id = cara_pembayaran.coa_mapping_id
            LEFT JOIN pt 
                on pt.source_id = view_coa.pt_id
            WHERE cara_pembayaran.id        = $id
            AND cara_pembayaran.project_id  = $project->id
        ");
        $row = $query->row();

        return $row;
    }  
    public function ajax_edit($id,$dataTmp){
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();        

        $data =
            [
                // 'code'                      => $dataTmp['code'],
                // 'project_id'                => $project->id,
                // 'name'                      => $dataTmp['jenis_pembayaran'],
                'nilai_flag'                => $dataTmp['nilai_flag'],
                'biaya_admin'               => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
                'coa_mapping_id'            => $dataTmp['coa'],
                'description'               => $dataTmp['keterangan'],
                // 'jenis_cara_pembayaran_id'  => $dataTmp['jenis_cara_pembayaran'],
                // 'active'                    => 1,
                // 'delete'                    => 0,
                // 'bank_id'                   => $dataTmp['bank'],
                
                'va_bank'                   => $dataTmp['va_bank'],
                'va_merchant'               => $dataTmp['va_merchant'],
                'max_digit'                 => $dataTmp['max_digit'],
                
                // 'pt_id'                     => $dataTmp['pt'],
            ];

        $this->db->where('cara_pembayaran.project_id', $project->id);
        $this->db->from('cara_pembayaran');
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($id);
            $this->db->where('id', $id);
            $this->db->update('cara_pembayaran', $data);
            $after = $this->get_log($id);
                
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;
            if ($tmpDiff) {
                $this->m_log->log_save('cara_pembayaran', $id, 'Edit', $diff);

                return 'success';
            } else {
                return 'failed';
            }
            // } else {
            //     return 'double';
            // }
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
                'code' => $dataTmp['code'],
                'name' => $dataTmp['jenis_pembayaran'],
                'biaya_admin' => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
                'coa_mapping_id' => $dataTmp['coa'],
                'description' => $dataTmp['keterangan'],
                'active' => $dataTmp['active'] ? 1 : 0,
            ];

        $this->db->where('cara_pembayaran.project_id', $project->id);
        $this->db->from('cara_pembayaran');
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            // $this->db->where('code', $data['code'])
            //     ->where('id !=', $dataTmp['id'])
            //     ->where('project_id', $project->id);
            // $this->db->from('cara_pembayaran');
            // validasi double
            // if ($this->db->count_all_results() == 0) {
                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('cara_pembayaran', $data);
                $after = $this->get_log($dataTmp['id']);
                
                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;
                if ($tmpDiff) {
                    $this->m_log->log_save('cara_pembayaran', $dataTmp['id'], 'Edit', $diff);

                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
                }
            // } else {
            //     return 'double';
            // }
        }
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->join('coa_mapping', 'coa_mapping.id = cara_pembayaran.coa_mapping_id');
        $this->db->where('coa_mapping.project_id', $project->id);
        $this->db->from('cara_pembayaran');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('cara_pembayaran', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('cara_pembayaran', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
