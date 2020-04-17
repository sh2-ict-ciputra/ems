<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_kirim_sms extends CI_Model
{
    public function get()
    {
        $query = $this->db->query('
            SELECT * FROM t_
        ');

        return $query->result_array();
    }


     public function getUnit()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT project.name as project_name,
                   kawasan.name as kawasan_name,
                   blok.name as blok_name,
                   unit.*

             FROM unit 
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where kawasan.project_id = $project->id


             ");

        return $query->result_array();
    }


     public function getUnit2($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT project.name as project_name,
                   kawasan.name as kawasan_name,
                   blok.name as blok_name,
                   unit.luas_bangunan as luas_bangunan,
                   unit.luas_tanah as luas_tanah,
                   unit.unit_type as unit_type,
                   unit.tgl_st as tanggal_st,
                   customer.name as customer_name,
                   customer.address as customer_address,
                   customer.code as customer_code,
                   customer.homephone as customer_homephone,
                   customer.mobilephone1 as customer_mobilephone,
                   customer.email as customer_email

            FROM unit 
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            left join customer on customer.id = unit.pemilik_customer_id
            where kawasan.project_id = $project->id and unit.id = $id


             ");

        return $query->row();
    }



      public function getJenisService()
    {
        $query = $this->db->query('
            SELECT * FROM liason
        ');

        return $query->result_array();
    }



    

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = [
            'code' => $dataTmp['code'],
            'project_id' => $project->id,
            'name' => $dataTmp['name'],
            'virtual_account' => $dataTmp['virtual_account'],
            'biaya_admin' => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
            'description' => $dataTmp['description'],
            'active' => 1,
            'delete' => 0,
        ];

        $data_rekening = [
            'no_rekening' => $dataTmp['no_rekening'],
            'service_id' => $dataTmp['service_id'],
            'coa_mapping_id' => $dataTmp['coa_mapping_id'],
            'active' => 1,
            'delete' => 0,
        ];
        //var_dump($data_rekening);

        $this->db->where('code', $data['code']);
        $this->db->from('bank');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('bank', $data);
            $id = $this->db->insert_id();
            // $this->m_log->log_save('bank',$id,'Tambah',$dataLog);

            for ($i = 0; $i < count($dataTmp['no_rekening']); ++$i) {
                $nomorRekening[$i] = $dataTmp['no_rekening'][$i];
                $service_id[$i] = $dataTmp['service_id'][$i];
                $coa_mapping_id[$i] = $dataTmp['coa_mapping_id'][$i];

                $data_rekening2 = [
                    'bank_id' => $id,
                    'no_rekening' => $nomorRekening[$i],
                    'service_id' => $service_id[$i],
                    'coa_mapping_id' => $coa_mapping_id[$i],
                    'active' => 1,
                    'delete' => 0,
                ];

                $this->db->insert('rekening', $data_rekening2);
                $dataLog = $this->mapping_get_log($this->db->insert_id());
                // $this->m_log->log_save('rekening',$this->db->insert_id(),'Tambah',$dataLog);
            }
            $dataLog = $this->get_log($id);
            $this->m_log->log_save('bank', $id, 'Tambah', $dataLog);

            return 'success';
        } else {
            return 'double';
        }
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
                pt.name as ptName 
            FROM cara_pembayaran
                join coa_mapping 
                    on cara_pembayaran.coa_mapping_id = coa_mapping.id
                join pt 
                    on pt.id = coa_mapping.pt_id
                join coa 
                    on coa.id = coa_mapping.coa_id
                WHERE cara_pembayaran.[delete] = 0 
                AND coa_mapping.project_id = $project->id
                ORDER BY cara_pembayaran.id desc
        ");

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

   

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * 
            FROM cara_pembayaran 
            JOIN coa_mapping ON coa_mapping.id = cara_pembayaran.coa_mapping_id
            WHERE cara_pembayaran.id = $id 
            AND coa_mapping.project_id = $project->id        
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
            cara_pembayaran.*
        FROM cara_pembayaran 
        JOIN coa_mapping ON coa_mapping.id = cara_pembayaran.coa_mapping_id
        WHERE cara_pembayaran.id = $id 
        AND coa_mapping.project_id = $project->id	
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
                coa.code as [Kode COA], 
                coa.description as [Nama COA], 
                coa_mapping.id as [Id Mapping COA] 
            FROM cara_pembayaran
            join coa_mapping ON coa_mapping.id = cara_pembayaran.coa_mapping_id
            join pt on pt.id            = coa_mapping.pt_id
            join coa on coa.id          = coa_mapping.coa_id
            WHERE cara_pembayaran.id        = $id
            AND coa_mapping.project_id  = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->join('coa_mapping', 'coa_mapping.id = cara_pembayaran.coa_mapping_id');
        $this->db->where('coa_mapping.project_id', $project->id);
        $this->db->from('cara_pembayaran');

        $data =
        [
            'code' => $dataTmp['code'],
            'name' => $dataTmp['jenis_pembayaran'],
            'biaya_admin' => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
            'coa_mapping_id' => $dataTmp['coa'],
            'description' => $dataTmp['keterangan'],
            'active' => $dataTmp['active'] ? 1 : 0,
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('code', $data['code'])->where('id !=', $dataTmp['id']);
            $this->db->from('cara_pembayaran');
            // validasi double
            if ($this->db->count_all_results() == 0) {
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
