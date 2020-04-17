<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_transaksi_lo extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM liason where active =1 and project_id = $project->id and [delete] = 0 order by id desc
        ");

        return $query->result_array();
    }


    public function getSelect($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
		    SELECT * FROM liason
			WHERE id = $id
            AND project_id = $project->id
        ");
        $row = $query->row();
        return $row; 
    }


     public function get_log_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query('
             SELECT * FROM liason_detail
        ');
        $row = $query->row();

        return $row;
    }

    public function get_log_outflow($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query('
             SELECT * FROM liason_outflow
        ');
        $row = $query->row();

        return $row;
    }
	
	
	 public function get_transaksi_lo_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  *  FROM liason_detail
            WHERE liaison_id = $id 
            order by  id asc
        ");
		return $query->result_array();
		
    }
    

    public function get_transaksi_lo_outflow($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  *  FROM liason_outflow
            WHERE liaison_id = $id 
            order by  id asc
        ");
		return $query->result_array();
		
	}

    public function cek($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM liason WHERE id = $id and project_id = $project->id
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }
	
	public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            			
			SELECT  
                    liason.code					 as code,
                    liason.name					 as name,                    
                    liason.description			 as description, 
                    case when liason.active = 0	 then 'Tidak Aktif' else 'Aktif' end as aktif, 
                    case when liason.[delete] = 0 then 'Tidak di Hapus' else 'Terhapus' end as [delete],
                    liason_detail.id				as id_transaksi_lo_detail,
                    liason_detail.code	            as code_detail,
                    liason_detail.description     	as description_detail,
                    liason_detail.range	         	as range_detail,
                    liason_detail.harga	         	as harga_detail,
                    liason_outflow.id				as id_transaksi_lo_outflow,
                    liason_outflow.code	            as code_outflow,
                    liason_outflow.name     	    as name_outflow,
                    liason_outflow.harga	       	as harga_outflow,
					liason_outflow.description      as description_outflow
					
                   
            FROM liason
            JOIN liason_detail		ON liason.id = liason_detail.liaison_id
            JOIN liason_outflow		ON liason.id = liason_outflow.liaison_id
            WHERE				liason.id             = $id
            AND					liason.project_id     = $project->id
            ORDER BY			liason.id		  ASC
			
			
			
			
			
			
			
        ");
        $row = $query->result_array();
        $hasil = [];
        $i = 1;
        foreach ($row as $v) {
            if (!array_key_exists('code', $hasil)) {
                $hasil['code'] = $v['code'];
                $hasil['name'] = $v['name'];
                $hasil['description'] = $v['description'];
                $hasil['aktif'] = $v['aktif'];
                $hasil['delete'] = $v['delete'];
            }
            $hasil[$i.' id_transaksi_lo_detail'] = $v['id_transaksi_lo_detail'];
            $hasil[$i.' code_detail'] = $v['code_detail'];
            $hasil[$i.' description_detail'] = $v['description_detail'];
            $hasil[$i.' range_detail'] = $v['range_detail'];
            $hasil[$i.' harga_detail'] = $v['harga_detail'];

            $hasil[$i.' id_transaksi_lo_outflow'] = $v['id_transaksi_lo_outflow'];
            $hasil[$i.' code_outflow'] = $v['code_outflow'];
            $hasil[$i.' name_outflow'] = $v['name_outflow'];
            $hasil[$i.' harga_outflow'] = $v['harga_outflow'];
            $hasil[$i.' description_outflow'] = $v['description_outflow'];


           
            ++$i;
        }

        return $hasil;
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

        $data_liason_detail = [
            'code' => $dataTmp['kode'],
            'project_id' => $project->id,
            'description' => $dataTmp['keterangan_detail'],
            'range' => $dataTmp['range'],
            'harga' => $dataTmp['harga'],
            'active' => 1,
            'delete' => 0,
        ];

        $data_liason_outflow = [
            'code' => $dataTmp['kode2'],
            'project_id' => $project->id,
            'name' => $dataTmp['nama2'],
            'harga' => $dataTmp['harga2'],
            'description' => $dataTmp['keterangan2'],
            'active' => 1,
            'delete' => 0,
        ];

        //var_dump($data_range_detail);

        $this->db->where('code', $data['code']);
        $this->db->from('liason');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('liason', $data);
            $id = $this->db->insert_id();
            $dataLog = $this->get_log($id);
            $this->m_log->log_save('liason', $id, 'Tambah', $dataLog);

            //echo("<pre>");
            //print_r($dataTmp);
            //echo("</pre>");

            if (isset($dataTmp['kode'])) {
                for ($i = 0; $i <= count($dataTmp['kode']) - 1; ++$i) {
                    $kode[$i] = $dataTmp['kode'][$i];
                    $keterangan_detail[$i] = $dataTmp['keterangan_detail'][$i];
                    $range[$i] = $dataTmp['range'][$i];
                    $harga[$i] = $dataTmp['harga'][$i];

                    $data_liason_detail = [
                            'liaison_id' => $id,
                            'project_id' => $project->id,
                            'code' => $kode[$i],
                            'description' => $keterangan_detail[$i],
                            'range' => $range[$i],
                            'harga' => $harga[$i],
                            'active' => 1,
                            'delete' => 0,
                        ];

                    $this->db->insert('liason_detail', $data_liason_detail);
                    $dataLog = $this->get_log_detail($this->db->insert_id());
                    $this->m_log->log_save('liason_detail', $this->db->insert_id(), 'Tambah', $dataLog);
                }
            }

            if (isset($dataTmp['kode2'])) {
                for ($i = 0; $i <= count($dataTmp['kode2']) - 1; ++$i) {
                    $kode2[$i] = $dataTmp['kode2'][$i];
                    $nama2[$i] = $dataTmp['nama2'][$i];
                    $harga2[$i] = $dataTmp['harga2'][$i];
                    $keterangan2[$i] = $dataTmp['keterangan2'][$i];

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

                    $this->db->insert('liason_outflow', $data_liason_outflow);
                    $dataLog = $this->get_log_outflow($this->db->insert_id());
                    $this->m_log->log_save('liason_outflow', $this->db->insert_id(), 'Tambah', $dataLog);
                }
            }

            return 'success';
        } else {
            return 'double';
        }
    }

    public function edit($dataTmp)
    {
        // echo '<pre>';
        // print_r($dataTmp);
        // echo '</pre>';

        $before = $this->get_log($dataTmp['id']);

        $this->db->where('liaison_id', $dataTmp['id']);
        $this->db->update('liason_detail', ['delete' => 1]);

        $this->db->where('liaison_id', $dataTmp['id']);
        $this->db->update('liason_outflow', ['delete' => 1]);

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

        
        // echo '<pre>';
        // print_r($before);
        // echo '</pre>';
        
        //proses edit
        $beforeLiason = $this->get_log($dataTmp['id']);

        $this->db->where('id', $dataTmp['id']);
        $this->db->update('liason', $data);
        $afterLiason = $this->get_log($dataTmp['id']);
        $diff = (object) (array_diff_assoc((array) $afterLiason, (array) $beforeLiason));
        $tmpDiff = (array) $diff;
        if(true){
            $i = 0;
            $j = 0;
            $jumlahLODetailBaru = 0;
            $jumlahLOOutflowBaru = 0;



            foreach ($dataTmp['id_transaksi_lo_detail'] as $v) {
                // echo '<pre>';
                // print_r($v);
                // echo '</pre>';
                // var_dump($dataTmp['id_rekening'][$i]);
                $dataLODetailTmp = [];
                $dataLODetailTmp =
                [
                    'liaison_id' => $dataTmp['id'],
                    'code' => $dataTmp['kode'][$i],
                    'description' => $dataTmp['keterangan_detail'][$i],
                    'range' => $dataTmp['range'][$i],
                    'harga' => $dataTmp['harga'][$i], 
                    'active' => 1,                
                    'delete' => 0,
                ];
                if ($v != 0) {
                    $jumlahLODetailBaru++;
                    // $dataRekeningTmp['id'] = $dataTmp['id_rekening'][$i];
                    // edit rekening
                    $this->db->where('id', $dataTmp['id_transaksi_lo_detail'][$i]);
                    $this->db->update('liason_detail', $dataLODetailTmp);
                }else{
                    // add rekening
                    $this->db->insert('liason_detail', $dataLODetailTmp);  

                }

                // echo '<pre>';
                // print_r($dataRekeningTmp);
                // echo '</pre>';
                $i++;
            }
            // echo '<pre>';
            //     print_r($jumlahRekeningBaru);
            // echo '</pre>';
            foreach ($dataTmp['id_transaksi_lo_outflow'] as $m) {
                // echo '<pre>';
                // print_r($v);
                // echo '</pre>';
                // var_dump($dataTmp['id_rekening'][$i]);
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
                    $this->db->where('id', $dataTmp['id_transaksi_lo_outflow'][$j]);
                    $this->db->update('liason_outflow', $dataLOOutflowTmp);
                }else{
                    // add rekening
                    $this->db->insert('liason_outflow', $dataLOOutflowTmp);  

                }

                // echo '<pre>';
                // print_r($dataRekeningTmp);
                // echo '</pre>';
                $j++;
            }
            // echo '<pre>';
            //     print_r($jumlahRekeningBaru);
            // echo '</pre>';
            






            $after = $this->get_log($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;
            // echo '<pre>';
            //     print_r($before);
            // echo '</pre>';
            // echo '<pre>';
            //     print_r($after);
            // echo '</pre>';
            // echo '<pre>';
            //     print_r($tmpDiff);
            // echo '</pre>';
            if ($tmpDiff) {
                $this->m_log->log_save('liason', $dataTmp['id'], 'Edit', $diff);

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
        $this->db->from('liason');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('liason', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('liason', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
