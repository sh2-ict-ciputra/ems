<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_liaison_outflow extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM Liaison 
            where active =1 
            and project_id = $project->id 
            and [delete] = 0 
            order by id desc
        ");

        return $query->result_array();
    }


    public function getSelect($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
		    SELECT * FROM Liaison
			WHERE id = $id
            AND project_id = $project->id
        ");
        $row = $query->row();
        return $row; 
    }
	
	
	 public function get_transaksi_liaison_item($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  *  FROM Liaison_item
            WHERE liaison_id = $id 
            order by  id asc
        ");
		return $query->result_array();
		
    }
    

    public function get_transaksi_liaison_outflow($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  *  FROM Liaison_outflow
            WHERE liaison_id = $id 
            order by  id asc
        ");
		return $query->result_array();
		
	}

    public function cek($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM Liaison WHERE id = $id and project_id = $project->id
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }
	
	public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $queryLiason = $this->db->query("
            SELECT
                liaison.code as Kode,
                liaison.name as Nama,
                liaison.description as Deskripsi,
                case
                    when liaison.active = 1 then 'Aktif'
                    else 'Non Aktif'
                end as Aktif,
                case 
                    when liaison.[delete] = 0 then 'Tidak di Hapus' 
                    else 'Terhapus' end 
                as [Delete]
            FROM Liaison
            WHERE liaison.id = $id
            and liaison.project_id = $project->id                
        ");
        $queryLiasonOutflow = $this->db->query("
            SELECT 
                liaison_outflow.code as Kode,
                liaison_outflow.name as Nama,
                liaison_outflow.harga as Harga,
                liaison_outflow.description as Deskripsi,
                case
                    when liaison_outflow.active = 1 then 'Aktif'
                    else 'Non Aktif'
                end as Aktif,
                case 
                    when liaison_outflow.[delete] = 0 then 'Tidak di Hapus' 
                    else 'Terhapus' end 
                as [Delete]
            FROM Liaison_outflow
            JOIN liaison
                ON liaison.id = liaison_outflow.liaison_id
            WHERE liaison.id = $id
            and liaison.project_id = $project->id                
        ");
        
        $rowLiason          = $queryLiason->result_array();
        $rowLiasonOutflow   = $queryLiasonOutflow->result_array();

        if($rowLiason){
            //log untuk liason
            $hasil = [];
            foreach ($rowLiason as $v) {
                $hasil['Kode'] = $v['Kode'];
                $hasil['Nama'] = $v['Nama'];
                $hasil['Deskripsi'] = $v['Deskripsi'];
                $hasil['Aktif'] = $v['Aktif'];
                $hasil['Delete'] = $v['Delete'];

            }

            //log untuk Outflow nya
            $i=1;
            foreach ($rowLiasonOutflow as $v) {
                $hasil['Outflow Transaksi.'.$i.'.Kode'] = $v['Kode'];
                $hasil['Outflow Transaksi.'.$i.'.Range'] = $v['Nama'];
                $hasil['Outflow Transaksi.'.$i.'.Harga'] = $v['Harga'];
                $hasil['Outflow Transaksi.'.$i.'.Deskripsi'] = $v['Deskripsi'];
                $hasil['Outflow Transaksi.'.$i.'.Aktif'] = $v['Aktif'];
                $hasil['Outflow Transaksi.'.$i.'.Delete'] = $v['Delete'];
                ++$i;
            }
        }
        return $hasil;
    }
	public function get_log_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query('
             SELECT * FROM Liaison_item
        ');
        $row = $query->row();

        return $row;
    }

    public function get_log_outflow($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query('
             SELECT * FROM Liaison_outflow
        ');
        $row = $query->row();

        return $row;
    }
	
    public function save($dataTmp)
    {
        
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = [
            'code' => $dataTmp['kode_paket'],
            'project_id' => $project->id,
            'name' => $dataTmp['nama_transaksi'],
            'description' => $dataTmp['keterangan'],
            'active' => 1,
            'delete' => 0,
        ];

        // $data_liason_detail = [
        //     'code' => $dataTmp['kode'],
        //     'project_id' => $project->id,
        //     'description' => $dataTmp['keterangan_detail'],
        //     'range' => $dataTmp['range'],
        //     'harga' => $dataTmp['harga'],
        //     'active' => 1,
        //     'delete' => 0,
        // ];

        $data_liason_outflow = [
            'code' => $dataTmp['kode'],
            'project_id' => $project->id,
            'name' => $dataTmp['nama'],
            'harga' => $dataTmp['harga'],
            'description' => $dataTmp['keterangan_outflow'],
            'active' => 1,
            'delete' => 0,
        ];
        

        //var_dump($data_range_detail);

        $this->db->where('code', $data['code']);
        $this->db->from('liaison');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('liaison', $data);
            $id = $this->db->insert_id();


            // if (isset($dataTmp['kode'])) {
            //     for ($i = 0; $i <= count($dataTmp['kode']) - 1; ++$i) {
            //         $kode[$i] = $dataTmp['kode'][$i];
            //         $keterangan_detail[$i] = $dataTmp['keterangan_detail'][$i];
            //         $range[$i] = $dataTmp['range'][$i];
            //         $harga[$i] = $dataTmp['harga'][$i];

            //         $data_liason_detail = [
            //                 'liaison_id' => $id,
            //                 'project_id' => $project->id,
            //                 'code' => $kode[$i],
            //                 'description' => $keterangan_detail[$i],
            //                 'range' => $range[$i],
            //                 'harga' => $harga[$i],
            //                 'active' => 1,
            //                 'delete' => 0,
            //             ];

            //         $this->db->insert('liaison_item', $data_liason_detail);
            //     }
            // }

            if (isset($dataTmp['kode'])) {
                for ($i = 0; $i <= count($dataTmp['kode']) - 1; ++$i) {
                    $kode2[$i] = $dataTmp['kode'][$i];
                    $nama2[$i] = $dataTmp['nama'][$i];
                    $harga2[$i] = $dataTmp['harga'][$i];
                    $keterangan2[$i] = $dataTmp['keterangan_outflow'][$i];

                    $data_liason_outflow = [
                            'liaison_id' => $id,
                            'project_id' => $project->id,
                            'code' => $kode2[$i],
                            'name' => $nama2[$i],
                            'harga' => $harga2[$i],
                            'description' => $keterangan2[$i],
                            'active' => 1,
                            'delete' => 0,
                        ];

                    $this->db->insert('liaison_outflow', $data_liason_outflow);
                }
            }
            $dataLog = $this->get_log($id);

            $this->m_log->log_save('liaison', $id, 'Tambah', $dataLog);

            return 'success';
        } else {
            return 'double';
        }
    }

    public function edit($dataTmp)
    {
        $before = $this->get_log($dataTmp['id']);

      
        $this->db->where('liaison_id', $dataTmp['id']);
        $this->db->update('liaison_outflow', ['delete' => 1]);

        //$this->save($dateTmp);

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        // $this->db->where('project_id', $project->id);
        // $this->db->from('bank');
        $data =
        [
            'code' => $dataTmp['code'],
            'name' => $dataTmp['nama_transaksi'],
            'description' => $dataTmp['keterangan'],
            'active' => $dataTmp['active'] ? 1 : 0,
            'delete' => 0,
        ];

        
        //proses edit
        $beforeLiason = $this->get_log($dataTmp['id']);

        $this->db->where('id', $dataTmp['id']);
        $this->db->update('liaison', $data);
        $afterLiason = $this->get_log($dataTmp['id']);
        $diff = (object) (array_diff_assoc((array) $afterLiason, (array) $beforeLiason));
        $tmpDiff = (array) $diff;
        if(true){
            $i = 0;
            $j = 0;
          
            $jumlahLOOutflowBaru = 0;

           
            foreach ($dataTmp['id_transaksi_liaison_outflow'] as $m) {
                $dataLOOutflowTmp = [];
                $dataLOOutflowTmp =
                [
                    'liaison_id' => $dataTmp['id'],
                    'code' => $dataTmp['kode2'][$j],
                    'name' => $dataTmp['nama2'][$j],
                    'harga' => $dataTmp['harga2'][$j],
                    'description' => $dataTmp['keterangan2'][$j], 
                    'active' => 1,                
                    'delete' => 0,
                ];
                if ($m != 0) {
                    $jumlahLOOutflowBaru++;
                    // $dataRekeningTmp['id'] = $dataTmp['id_rekening'][$i];
                    // edit rekening
                    $this->db->where('id', $dataTmp['id_transaksi_liaison_outflow'][$j]);
                    $this->db->update('liaison_outflow', $dataLOOutflowTmp);
                }else{
                    // add rekening
                    $this->db->insert('liaison_outflow', $dataLOOutflowTmp);  

                }

                $j++;
            }



            $after = $this->get_log($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;
            if ($tmpDiff) {
                $this->m_log->log_save('liaison', $dataTmp['id'], 'Edit', $diff);

                return 'success';
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
        $this->db->from('lo');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('lo', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('lo', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
