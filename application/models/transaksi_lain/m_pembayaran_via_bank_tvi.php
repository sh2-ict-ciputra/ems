<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_pembayaran_via_bank_tvi extends CI_Model
{

    public function getAll()
    {


        $project = $this->m_core->project();


        $query = $this->db->query("



            SELECT *,


                    t_tvi_transfer.kode as kode,
                    t_tvi_transfer.tanggal as tanggal,
                    bank.name as bank_name,
                    t_tvi_transfer.jenis_pembayaran as jenis_pembayaran,
                    t_tvi_transfer.nama_rekening as nama_rekening,
                    t_tvi_transfer.nomor_rekening as nomor_rekening,
                    t_tvi_transfer.total as total,
                    t_tvi_transfer.keterangan as keterangan,
                    t_tvi_transfer.id as id




            FROM t_tvi_transfer
            left join bank on bank.id = t_tvi_transfer.bank_id
            WHERE t_tvi_transfer.project_id = $project->id  and t_tvi_transfer.[delete] = 0
            ORDER by t_tvi_transfer.id desc  





        ");

        return $query->result_array();
    }











    public function getBank()
    {
        $query = $this->db->query('



            SELECT * FROM bank





        ');

        return $query->result_array();
    }



   

    


        public function save($dataTmp)
    {


        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

      


        $dataViaBank =
        [
            'kode' => $dataTmp['kode'],
            'tanggal' => $dataTmp['tanggal_pembayaran'],
            'project_id'    => $project->id,
            'bank_id' => $dataTmp['bank'],
            'jenis_pembayaran' => $dataTmp['jenis_pembayaran'],
            'nomor_rekening' => $dataTmp['nomor_rekening'],
            'nama_rekening' => $dataTmp['nama_rekening'],
            'total' => $this->m_core->currency_to_number($dataTmp['total_transfer']),
            'keterangan' => $dataTmp['keterangan'],
            'status' => $dataTmp['status'],
            'active' => 1,
            'delete' => 0,
        ];


      

      
        $this->db->where('kode', $dataTmp['kode']);
        $this->db->from('t_tvi_transfer');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('t_tvi_transfer', $dataViaBank);
            $id = $this->db->insert_id();

            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('t_tvi_transfer', $this->db->insert_id(), 'Tambah', $dataLog);

            return 'success';
        } else {
            return 'double';
        }


           
       
        

}



public function last_id(){
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_tvi_transfer
            ORDER by id desc
        ");
        return $query->row()?$query->row()->id:0;
    }

  






   

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
              SELECT


                    t_tvi_transfer.kode as kode,
                    t_tvi_transfer.tanggal as tanggal,
                    bank.name as bank_name,
                    t_tvi_transfer.jenis_pembayaran as jenis_pembayaran,
                    t_tvi_transfer.nama_rekening as nama_rekening,
                    t_tvi_transfer.nomor_rekening as nomor_rekening,
                    t_tvi_transfer.total as total,
                    t_tvi_transfer.keterangan as keterangan,
                    t_tvi_transfer.id as id




            FROM t_tvi_transfer
            left join bank on bank.id = t_tvi_transfer.bank_id
            WHERE t_tvi_transfer.project_id = $project->id and t_tvi_transfer.id = $id
            ORDER by t_tvi_transfer.id asc  





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


                    t_tvi_transfer.kode as kode,
                    t_tvi_transfer.tanggal as tanggal,
                    bank.name as bank_name,
                    t_tvi_transfer.jenis_pembayaran as jenis_pembayaran,
                    t_tvi_transfer.nama_rekening as nama_rekening,
                    t_tvi_transfer.nomor_rekening as nomor_rekening,
                    t_tvi_transfer.total as total_transfer,
                    t_tvi_transfer.keterangan as keterangan,
                    t_tvi_transfer.id as id,
                    t_tvi_transfer.tanggal as tanggal_pembayaran,
                    t_tvi_transfer.bank_id as bank_id






            FROM t_tvi_transfer
            left join bank on bank.id = t_tvi_transfer.bank_id
            WHERE t_tvi_transfer.project_id = $project->id and t_tvi_transfer.id = $id 
            ORDER by t_tvi_transfer.id asc  




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
                  

                   t_tvi_transfer.kode as kode,
                   t_tvi_transfer.tanggal as tanggal_pembayaran,
                   bank.name as bank,
                   t_tvi_transfer.jenis_pembayaran as jenis_pembayaran,
                   t_tvi_transfer.nomor_rekening as nomor_rekening,
                   t_tvi_transfer.nama_rekening  as nama_rekening,
                   t_tvi_transfer.total  as total_transfer,
                   t_tvi_transfer.keterangan as keterangan,
                   t_tvi_transfer.status as status

             
            FROM t_tvi_transfer
            left join bank on t_tvi_transfer.bank_id = bank.id
            where 
            t_tvi_transfer.id = $id and
            t_tvi_transfer.project_id = $project->id  
            


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

        $this->db->where('project_id', $project->id);
        $this->db->from('t_tvi_transfer');

        $data =
        [
            'kode' => $dataTmp['kode'],
            'tanggal' => $dataTmp['tanggal_pembayaran'],
            'project_id'    => $project->id,
            'bank_id' => $dataTmp['bank'],
            'jenis_pembayaran' => $dataTmp['jenis_pembayaran'],
            'nomor_rekening' => $dataTmp['nomor_rekening'],
            'nama_rekening' => $dataTmp['nama_rekening'],
            'total' => $this->m_core->currency_to_number($dataTmp['total_transfer']),
            'keterangan' => $dataTmp['keterangan'],
           
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('kode', $data['kode'])->where('id !=', $dataTmp['id']);
            $this->db->from('t_tvi_transfer');
            // validasi double
            if ($this->db->count_all_results() == 0) {
                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('t_tvi_transfer', $data);
                $after = $this->get_log($dataTmp['id']);

                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                if ($tmpDiff) {
                    $this->m_log->log_save('t_tvi_transfer', $dataTmp['id'], 'Edit', $diff);

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
        $this->db->from('t_tvi_transfer');


        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('t_tvi_transfer', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('t_tvi_transfer', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
