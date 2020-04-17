<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_paket_tvi extends CI_Model
{

    public function get()
    {
        $query = $this->db->query("
            SELECT * FROM paket_tvi
        ");
        return $query->result_array();
    }

    public function get_all()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            paket_tvi.*,
            group_tvi.name as group_name
        FROM paket_tvi
            JOIN group_tvi ON group_tvi.id = paket_tvi.group_tvi_id         
        WHERE group_tvi.project_id = $project->id and paket_tvi.[delete] = 0 order by id desc
        ");
        return $query->result_array();
    }



    public function get_group_tvi($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            id,
            name			
        FROM group_tvi
            WHERE project_id = $project->id and id = $id
        ");
        $row = $query->row();
        return $row;
    }


    public function get_group_tvi2()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            id,
            name			
        FROM group_tvi
            WHERE project_id = $project->id
        ");
        return $query->result_array();
    }

    public function getSelect_all($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
		SELECT 
            paket_tvi.*,
			group_tvi.id as group_id,
            group_tvi.name as group_name
        FROM paket_tvi
            JOIN group_tvi ON group_tvi.id = paket_tvi.group_tvi_id         
        WHERE group_tvi.project_id = $project->id and paket_tvi.id = $id
			
        ");
        $row = $query->row();
        return $row;
    }


    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM paket_tvi WHERE id = $id 
        ");
        $row = $query->row();
        return isset($row) ? 1 : 0;
    }


    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            select 
                group_tvi.name as [Nama Group TVI],
                paket_tvi.code as [Kode],
                paket_tvi.name as [Nama],
                paket_tvi.bandwidth as [Bandwidth],
                paket_tvi.harga_hpp as [Harga HPP],
                paket_tvi.harga_jual as [Harga Jual],
                paket_tvi.harga_setelah_ppn as [Harga Setelah PPN],
                paket_tvi.biaya_pasang_baru as [Biaya Pasang Baru],
                paket_tvi.description as [Deskripsi],
                case 
                    when paket_tvi.active = 1 then 'Aktif' 
                    else 'Tidak Aktif' 
                end as Aktif, 
                case 
                    when paket_tvi.[delete] = 1 then 'Ya' 
                    else 'Tidak' 
                end as [Delete] 
            from paket_tvi
            join group_tvi
                on group_tvi.id = paket_tvi.group_tvi_id
            WHERE paket_tvi.id = $id
            AND group_tvi.project_id = $project->id
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
                'group_tvi_id'                   => $dataTmp['group'],
                'code'                           => $dataTmp['kode'],
                'name'                           => $dataTmp['nama_paket'],
                'bandwidth'                      => $this->m_core->currency_to_number($dataTmp['bandwith']),
                'harga_hpp'                      => $this->m_core->currency_to_number($dataTmp['hpp']),
                'harga_jual'                     => $this->m_core->currency_to_number($dataTmp['harga_jual']),
                'biaya_pasang_baru'              => $this->m_core->currency_to_number($dataTmp['biaya_pasang']),
                'biaya_registrasi'               => $this->m_core->currency_to_number($dataTmp['biaya_registrasi']),
                'description'                    => $dataTmp['keterangan'],
                'active'                         => 1,
                'delete'                         => 0
            ];

        $this->db->where('code', $data['code']);
        $this->db->from('paket_tvi');

        // validasi double
        if ($this->db->count_all_results() == 0) {

            $this->db->insert('paket_tvi', $data);
            $idTMP = $this->db->insert_id();
            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('paket_tvi', $idTMP, 'Tambah', $dataLog);
            return 'success';
        } else return 'double';
    }


    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $data =
            [
                'group_tvi_id'                   => $dataTmp['group'],
                'code'                           => $dataTmp['kode'],
                'name'                           => $dataTmp['nama_paket'],
                'bandwidth'                      => $this->m_core->currency_to_number($dataTmp['bandwith']),
                'harga_hpp'                      => $this->m_core->currency_to_number($dataTmp['hpp']),
                'harga_jual'                     => $this->m_core->currency_to_number($dataTmp['harga_jual']),
                'biaya_pasang_baru'              => $this->m_core->currency_to_number($dataTmp['biaya_pasang']),
                'biaya_registrasi'               => $this->m_core->currency_to_number($dataTmp['biaya_registrasi']),
                'description'                    => $dataTmp['keterangan'],
                'active'                         => $dataTmp['active'] ? 1 : 0,
            ];

        // validasi apakah user dengan project $project boleh edit data ini
        $this->db->join('group_tvi', 'group_tvi.id = paket_tvi.group_tvi_id');
        $this->db->where('group_tvi.project_id', $project->id);
        $this->db->from('paket_tvi');
        if ($this->db->count_all_results() != 0) {
            $this->db->where('code', $data['code'])->where('id !=', $dataTmp['id']);
            $this->db->from('paket_tvi');
            // validasi double
            if ($this->db->count_all_results() == 0) {

                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('paket_tvi', $data);
                $after = $this->get_log($dataTmp['id']);

                $diff = (object)(array_diff_assoc((array)$after, (array)$before));
                $tmpDiff = (array)$diff;
                if ($tmpDiff) {
                    $this->m_log->log_save('paket_tvi', $dataTmp['id'], 'Edit', $diff);
                    return 'success';
                } else return 'Tidak Ada Perubahan';
            } else return 'double';
        }
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        // validasi apakah user dengan project $project boleh edit data ini

        // validasi Cara Pembayaran

        $before = $this->get_log($dataTmp['id']);
        $this->db->where('id', $dataTmp['id']);
        $this->db->update('paket_tvi', ['delete' => 1]);
        $after = $this->get_log($dataTmp['id']);

        $diff = (object)(array_diff((array)$after, (array)$before));
        $tmpDiff = (array)$diff;

        if ($tmpDiff) {
            $this->m_log->log_save('coa_mapping', $dataTmp['id'], 'Edit', $diff);

            return 'success';
        } else {
            return 'Tidak Ada Perubahan';
        }
    }
}

