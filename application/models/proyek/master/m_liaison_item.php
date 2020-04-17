<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_liaison_item extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            FROM Liaison 
            where active =1 
            and project_id = $project->id 
            and [delete] = 0 
            order by id desc 
        ");

        return $query->result();
    }	
	public function get_lo()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            FROM Liaison 
            where active =1 
            and project_id = $project->id 
            and [delete] = 0 
            order by code ASC
        ");

        return $query->result();
    }	
    public function get_select($id){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                id,
                code,
                name
            FROM Liaison 
            where active =1 
            and project_id = $project->id 
            and [delete] = 0 
            and id = $id
        ");
        return $query->row();
    }
    public function get_select_item($id){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                *
            FROM liaison_item
            WHERE active = 1
            AND [delete] = 0
            AND liaison_id = $id
        ");
        return $query->result();
    }
    public function get_select_outflow($id){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                *
            FROM liaison_outflow
            WHERE active = 1
            AND [delete] = 0
            AND liaison_id = $id
        ");
        return $query->result();
    }
	public function get_transaksi_lo_item($id)
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
    

    public function get_transaksi_lo_outflow($id)
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
	public function get_outflow($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                id,
                code,
                name,
                harga,
                description
            FROM liaison_outflow
            WHERE project_id = $project->id
            AND liaison_id = $id
            AND active = 1
            AND [delete] = 0
        ");
		return $query->result();
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
                        when lo.[delete] = 0 then 'Tidak di Hapus' 
                        else 'Terhapus' end 
                    as [Delete]
                FROM Liaison
                WHERE lo.id = $id
                and lo.project_id = $project->id                
            ");
        $queryLiasonDetail = $this->db->query("
            SELECT 
                lo_item.code as Kode,
                lo_item.range as Range,
                lo_item.harga as Harga,
                lo_item.description as Deskripsi,
                case
                    when lo_item.active = 1 then 'Aktif'
                    else 'Non Aktif'
                end as Aktif,
                case 
                    when lo_item.[delete] = 0 then 'Tidak di Hapus' 
                    else 'Terhapus' end 
                as [Delete]
            FROM Liaison_item
            JOIN lo
                ON lo.id = lo_item.liaison_Id
            WHERE lo.id = $id
            and lo.project_id = $project->id                
        ");
        $queryLiasonOutflow = $this->db->query("
            SELECT 
                lo_outflow.code as Kode,
                lo_outflow.name as Nama,
                lo_outflow.harga as Harga,
                lo_outflow.description as Deskripsi,
                case
                    when lo_outflow.active = 1 then 'Aktif'
                    else 'Non Aktif'
                end as Aktif,
                case 
                    when lo_outflow.[delete] = 0 then 'Tidak di Hapus' 
                    else 'Terhapus' end 
                as [Delete]
            FROM Liaison_outflow
            JOIN lo
                ON lo.id = lo_outflow.liaison_id
            WHERE lo.id = $id
            and lo.project_id = $project->id                
        ");
        
        $rowLiason          = $queryLiason->result_array();
        $rowLiasonDetail    = $queryLiasonDetail->result_array();
        $rowLiasonOutflow   = $queryLiasonOutflow->result_array();

        echo("<pre>");
            print_r($rowLiason);
        echo("</pre>");
        echo("<pre>");
            print_r($rowLiasonDetail);
        echo("</pre>");
        echo("<pre>");
            print_r($rowLiasonOutflow);
        echo("</pre>");
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

            //log untuk detil nya
            $i=1;
            foreach ($rowLiasonDetail as $v) {
                $hasil['1'.$i.' Kode'] = $v['Kode'];
                $hasil['1'.$i.' Range'] = $v['Range'];
                $hasil['1'.$i.' Harga'] = $v['Harga'];
                $hasil['1'.$i.' Deskripsi'] = $v['Deskripsi'];
                $hasil['1'.$i.' Aktif'] = $v['Aktif'];
                $hasil['1'.$i.' Delete'] = $v['Delete'];
                ++$i;
            }
            
            //log untuk Outflow nya
            $i=1;
            foreach ($rowLiasonOutflow as $v) {
                $hasil['2'.$i.' Kode'] = $v['Kode'];
                $hasil['2'.$i.' Range'] = $v['Nama'];
                $hasil['2'.$i.' Harga'] = $v['Harga'];
                $hasil['2'.$i.' Deskripsi'] = $v['Deskripsi'];
                $hasil['2'.$i.' Aktif'] = $v['Aktif'];
                $hasil['2'.$i.' Delete'] = $v['Delete'];
                ++$i;
            }
        }
        echo("<pre>");
            print_r($hasil);
        echo("</pre>");
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
        echo("<pre>");
            print_r($dataTmp);
        echo("</pre>");
        $project = $this->m_core->project();
        foreach ($dataTmp['item_kode'] as $k => $v) {
            $dataItem = [
                'liaison_id'    => $dataTmp['liaison_officer_id'],
                'project_id'    => $project->id,
                'code'          => $v,
                'description'    => $dataTmp['item_keterangan'][$k],
                'range'         => $dataTmp['item_range'][$k],
                'harga'         => $dataTmp['item_harga'][$k],
                'liaison_outflow_id' => isset($dataTmp['outflow'][$k])?implode(',',$dataTmp['outflow'][$k]):"",
                'active'        => 1,
                'delete'        => 0
            ];    
            
            echo("<pre>");
            print_r($dataItem);
            echo("</pre>");
            $this->db->insert('liaison_item', $dataItem);

            
        }
    
        // $this->load->model('m_core');
        // $this->load->model('m_log');
        // $project = $this->m_core->project();
        // $data = [
        //     'code' => $dataTmp['kode_paket'],
        //     'project_id' => $project->id,
        //     'name' => $dataTmp['nama_transaksi'],
        //     'description' => $dataTmp['keterangan'],
        //     'active' => 1,
        //     'delete' => 0,
        // ];

        // $data_liason_detail = [
        //     'code' => $dataTmp['kode'],
        //     'project_id' => $project->id,
        //     'description' => $dataTmp['keterangan_detail'],
        //     'range' => $dataTmp['range'],
        //     'harga' => $dataTmp['harga'],
        //     'active' => 1,
        //     'delete' => 0,
        // ];

        // $data_liason_outflow = [
        //     'code' => $dataTmp['kode2'],
        //     'project_id' => $project->id,
        //     'name' => $dataTmp['nama2'],
        //     'harga' => $dataTmp['harga2'],
        //     'description' => $dataTmp['keterangan2'],
        //     'active' => 1,
        //     'delete' => 0,
        // ];
        

        // //var_dump($data_range_detail);

        // $this->db->where('code', $data['code']);
        // $this->db->from('lo');

        // // validasi double
        // if ($this->db->count_all_results() == 0) {
        //     $this->db->insert('lo', $data);
        //     $id = $this->db->insert_id();


        //     if (isset($dataTmp['kode'])) {
        //         for ($i = 0; $i <= count($dataTmp['kode']) - 1; ++$i) {
        //             $kode[$i] = $dataTmp['kode'][$i];
        //             $keterangan_detail[$i] = $dataTmp['keterangan_detail'][$i];
        //             $range[$i] = $dataTmp['range'][$i];
        //             $harga[$i] = $dataTmp['harga'][$i];

        //             $data_liason_detail = [
        //                     'liaison_id' => $id,
        //                     'project_id' => $project->id,
        //                     'code' => $kode[$i],
        //                     'description' => $keterangan_detail[$i],
        //                     'range' => $range[$i],
        //                     'harga' => $harga[$i],
        //                     'active' => 1,
        //                     'delete' => 0,
        //                 ];

        //             $this->db->insert('lo_item', $data_liason_detail);
        //         }
        //     }

        //     if (isset($dataTmp['kode2'])) {
        //         for ($i = 0; $i <= count($dataTmp['kode2']) - 1; ++$i) {
        //             $kode2[$i] = $dataTmp['kode2'][$i];
        //             $nama2[$i] = $dataTmp['nama2'][$i];
        //             $harga2[$i] = $dataTmp['harga2'][$i];
        //             $keterangan2[$i] = $dataTmp['keterangan2'][$i];

        //             $data_liason_outflow = [
        //                     'liaison_id' => $id,
        //                     'project_id' => $project->id,
        //                     'code' => $kode2[$i],
        //                     'name' => $nama2[$i],
        //                     'harga' => $harga2[$i],
        //                     'description' => $keterangan2[$i],
        //                     'active' => 1,
        //                     'delete' => 0,
        //                 ];

        //             $this->db->insert('lo_outflow', $data_liason_outflow);
        //         }
        //     }
        //     $dataLog = $this->get_log($id);

        //     $this->m_log->log_save('lo', $id, 'Tambah', $dataLog);

        //     return 'success';
        // } else {
        //     return 'double';
        // }
    }

    public function edit($dataTmp)
    {
        echo '<pre>';
        print_r($dataTmp);
        echo '</pre>';

        $before = $this->get_log($dataTmp['id']);

        $this->db->where('liaison_id', $dataTmp['id']);
        $this->db->update('lo_item', ['delete' => 1]);

        $this->db->where('liaison_id', $dataTmp['id']);
        $this->db->update('lo_outflow', ['delete' => 1]);

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
        $this->db->update('lo', $data);
        $afterLiason = $this->get_log($dataTmp['id']);
        $diff = (object) (array_diff_assoc((array) $afterLiason, (array) $beforeLiason));
        $tmpDiff = (array) $diff;
        if(true){
            $i = 0;
            $j = 0;
            $jumlahLODetailBaru = 0;
            $jumlahLOOutflowBaru = 0;



            foreach ($dataTmp['id_transaksi_lo_item'] as $v) {
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
                    $this->db->where('id', $dataTmp['id_transaksi_lo_item'][$i]);
                    $this->db->update('lo_item', $dataLODetailTmp);
                }else{
                    // add rekening
                    $this->db->insert('lo_item', $dataLODetailTmp);  

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
                    $this->db->update('lo_outflow', $dataLOOutflowTmp);
                }else{
                    // add rekening
                    $this->db->insert('lo_outflow', $dataLOOutflowTmp);  

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
                $this->m_log->log_save('lo', $dataTmp['id'], 'Edit', $diff);

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
