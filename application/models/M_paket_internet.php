<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_paket_internet extends CI_Model
{

    public function get()
    {
        $query = $this->db->query("
            SELECT * FROM paket_internet
        ");
        return $query->result_array();
    }

    public function get_all()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            paket_internet.*,
            group_tvi.name as group_name
        FROM paket_internet
            JOIN group_tvi ON group_tvi.id = paket_internet.group_tvi_id         
        WHERE group_tvi.project_id = $project->id and paket_internet.[delete] = 0 order by id desc
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
            paket_internet.*,
			group_tvi.id as group_id,
            group_tvi.name as group_name,
            item_tvi.nama as item_name,
            paket_tvi_item.paket_id as paket_id,
            paket_tvi_item.item_tvi_id as item_id
        FROM paket_internet
            JOIN group_tvi ON group_tvi.id = paket_internet.group_tvi_id
            JOIN paket_tvi_item ON paket_tvi_item.paket_id = paket_internet.id
            JOIN item_tvi ON item_tvi.id = paket_tvi_item.item_tvi_id         
        WHERE group_tvi.project_id = $project->id and paket_tvi_item.paket_id = $id	
        ");
        $row = $query->row();
        return $row;
    }

    public function getItemPaket()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db
                            ->select("*")
                            ->from("item_tvi")
                            ->where("delete",0)
                            ->where("is_channel",0)
                            ->where("project_id",$project->id)
                            ->get()->result();
        // return $query;

        // $query = $this->db
        //                     ->select("paket_tvi_item.paket_id as id,
        //                             paket_tvi_item.volume as paket_volume,
        //                             item_tvi.nama as item_nama,
        //                             item_tvi.satuan as satuan,
        //                             item_tvi.harga_satuan as harga_satuan
        //                             ")
        //                     ->from("paket_tvi_item")
        //                     ->join("item_tvi",
        //                             "item_tvi.id = paket_tvi_item.item_tvi_id")
        //                     ->get()->result_array();
		return $query;
    }

    public function get_item_tvi($id)
    {
        $query = $this->db
                            ->select("item_tvi.id as id,
                                    paket_tvi_item.paket_id as paket_id,
                                    paket_tvi_item.volume as paket_volume,
                                    item_tvi.nama as item_nama,
                                    item_tvi.satuan as satuan,
                                    item_tvi.harga_satuan as harga_satuan
                                    ")
                            ->from("paket_tvi_item")
                            ->join("item_tvi",
                                    "item_tvi.id = paket_tvi_item.item_tvi_id")
                            ->where("paket_tvi_item.paket_id",$id)
                            ->get()->result_array();
		return $query;
    }

    public function get_item_tvi2()
    {
        $query = $this->db
                            ->select("paket_internet.id as internet_id,
                                    item_tvi.nama as nama,
                                    item_tvi.id as item_id,
                                    paket_tvi_item.item_tvi_id as itemID,
                                    paket_tvi_item.paket_id as paket_id")
                            ->from("paket_tvi_item")
                            ->join("paket_internet",
                                    "paket_internet.id = paket_tvi_item.paket_id")
                            ->join("item_tvi",
                                    "item_tvi.id = paket_tvi_item.item_tvi_id")
                            ->get()->result_array();
        return $query;
    }

    public function getInfoItem($id)
    {
        $query = $this->db
                        ->select("nama,
                                satuan,
                                harga_satuan")
                        ->from("item_tvi")
                        ->where("delete",0)
                        ->get()->row();
        return $query;
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM paket_internet WHERE id = $id 
        ");
        $row = $query->row();
        return isset($row) ? 1 : 0;
    }

    public function getSelectItem($id)
    {
        $query = $this->db
                        ->select("
                                paket_tvi_item.*,
                                item_tvi.id,
                                item_tvi.nama,
                                item_tvi.satuan,
                                item_tvi.harga_satuan")
                        ->from("paket_tvi_item")
                        ->join("item_tvi",
                                "item_tvi.id = paket_tvi_item.item_tvi_id")
                        ->where("paket_tvi_item.paket_id",$id)
                        ->where("item_tvi.delete",0)
                        ->get()->result();
        return $query;
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            select 
                group_tvi.name as [Nama Group TVI],
                paket_internet.code as [Kode],
                paket_internet.name as [Nama],
                paket_internet.bandwidth as [Bandwidth],
                paket_internet.harga_hpp as [Harga HPP],
                paket_internet.harga_jual as [Harga Jual],
                paket_internet.harga_setelah_ppn as [Harga Setelah PPN],
                paket_internet.biaya_pasang_baru as [Biaya Pasang Baru],
                paket_internet.description as [Deskripsi],
                case 
                    when paket_internet.active = 1 then 'Aktif' 
                    else 'Tidak Aktif' 
                end as Aktif, 
                case 
                    when paket_internet.[delete] = 1 then 'Ya' 
                    else 'Tidak' 
                end as [Delete] 
            from paket_internet
            join group_tvi
                on group_tvi.id = paket_internet.group_tvi_id
            WHERE paket_internet.id = $id
            AND group_tvi.project_id = $project->id
        ");
        $row = $query->row();
        return $row;
    }

    public function get_paket_tvi_item($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db
                            ->select("
                                    paket_tvi_item.id as id,
                                    paket_tvi_item.volume,
                                    item_tvi.nama as item_nama,
                                    item_tvi.satuan as satuan,
                                    item_tvi.harga_satuan as harga_satuan
                                    ")
                            ->from("paket_tvi_item")
                            ->join("item_tvi",
                                    "item_tvi.id = paket_tvi_item.item_tvi_id")
                            ->where("paket_id",$id)
                            ->order_by("paket_tvi_item.id","ASC")->get()->result_array();
        // $query = $this->db->query("
        //     SELECT  *  FROM paket_tvi_item
        //     WHERE paket_id = $id 
        //     order by  id asc
        // ");
		return $query;
		
    }

    public function save($data){
        $data = (object)$data;
        $this->load->model('m_log');
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "";
        $return->status = false;
        
        $this->db->where('code', $data->code);
        $this->db->from('paket_internet');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) {
            $return->message = "Kode sudah di gunakan";
            return $return;
        }
        $data->bandwidth = $this->m_core->currency_to_number($data->bandwidth);
        $data->harga_hpp = $this->m_core->currency_to_number($data->harga_hpp);
        $data->harga_jual = $this->m_core->currency_to_number($data->harga_jual);
        $data->biaya_pasang_baru = $this->m_core->currency_to_number($data->biaya_pasang_baru);
        $data->biaya_registrasi = $this->m_core->currency_to_number($data->biaya_registrasi);
        $data->active = 1;
        $data->delete = 0;
        $this->db->insert('paket_internet',$data);
        $id = $this->db->insert_id();
        
        
        if (isset($data->nama_item))
        {
            for($i= 0;$i<count($data->nama_item);$i++) {
                
                $nama_item[$i]  =  $data->nama_item[$i];  
                $volume[$i] =  $this->m_core->currency_to_number($data->volume[$i]);
                
                
                $dataItem = [
                    'item_tvi_id'      => $nama_item[$i],
                    'volume'       => $volume[$i],
                    'tipe'         => 'Internet',
                    'paket_id'  => $id
                ];
                    
                $this->db->insert('paket_tvi_item', $dataItem);
                $dataLog = $this->get_log($this->db->insert_id());
                $this->m_log->log_save('paket_tvi_item',$this->db->insert_id(),'Tambah',$dataLog);

            }
        }
        $dataLog = $this->get_log($id);
        $this->m_log->log_save('paket_internet',$id,'Tambah',$dataLog);
        $return->status = true;        
        $return->message = "Data user berhasil di tambah";
        return $return;
    }

    public function edit($data){
        $data = (object)$data;
        $this->load->model('m_log');
        //cek data double dari segi username dan nama
        $return = (object)[];
        $return->message = "";
        $return->status = false;
        $before = $this->get_log($data->id);
        $this->db->where('code', $data->code);
        $this->db->from('paket_internet');
        // validasi double code
        if ($this->db->count_all_results() > 0) {
            $return->message = "Kode sudah di gunakan";
            return $return;
        }
        $beforePaket = $this->get_log($data->id);
        $this->db->set('group_tvi_id',$data->group_tvi_id);
        $this->db->set('code',$data->code);
        $this->db->set('harga_hpp',$data->harga_hpp);
        $this->db->set('harga_jual',$data->harga_jual);
        $this->db->set('biaya_pasang_baru',$data->biaya_pasang_baru);
        $this->db->set('description',$data->description);
        $this->db->set('biaya_registrasi',$data->biaya_registrasi);
        $this->db->where('id',$data->id);
        $this->db->update('paket_internet');
        $afterPaket = $this->get_log($data->id);

        $diff = (object) (array_diff_assoc((array) $afterPaket, (array) $beforePaket));
        $tmpDiff = (array) $diff;

        if(true){
            $i = 0;
            $itemBaru = 0;
            if (isset($data->paket_tvi_item_id )) {
                foreach ($data->paket_tvi_item_id as $v) {
                    // echo '<pre>';
                    // print_r($v);
                    // echo '</pre>';
                    // var_dump($dataTmp['id_rekening'][$i]);
                    $nama_item[$i]  =  $data->nama_item[$i];  
                    $volume[$i] =  $this->m_core->currency_to_number($data->volume[$i]);
                    
                    $dataItem =[];
                    $dataItem = [
                        'item_tvi_id'      => $nama_item[$i],
                        'volume'       => $volume[$i],
                        'tipe'         => 'Internet',
                        'paket_id'  => $data->id
                    ];
                    if ($v != 0) {
                        $itemBaru++;
                        // $dataRekeningTmp['id'] = $dataTmp['id_rekening'][$i];
                        // edit rekening
                        $this->db->where('id', $data->paket_tvi_item_id[$i]);
                        $this->db->update('paket_tvi_item', $dataItem);
                    }else{
                        // add rekening
                        $this->db->insert('paket_tvi_item', $dataItem);  

                    }

                    // echo '<pre>';
                    // print_r($dataRekeningTmp);
                    // echo '</pre>';
                    $i++;

                }
            }
            $after = $this->get_log($data->id);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;
            if ($tmpDiff) {
                $this->m_log->log_save('paket_internet', $data->id, 'Edit', $diff);

                return 'success';
            }
        }
        $return->status = true;        
        $return->message = "Data user berhasil di ubah";
        return $return;
    }


    // public function edit($dataTmp)
    // {
    //     $this->load->model('m_core');
    //     $this->load->model('m_log');
    //     $project = $this->m_core->project();
    //     $user_id = $this->m_core->user_id();

    //     $data =
    //         [
    //             'group_tvi_id'                   => $dataTmp['group'],
    //             'code'                           => $dataTmp['kode'],
    //             'name'                           => $dataTmp['nama_paket'],
    //             'bandwidth'                      => $this->m_core->currency_to_number($dataTmp['bandwith']),
    //             'harga_hpp'                      => $this->m_core->currency_to_number($dataTmp['hpp']),
    //             'harga_jual'                     => $this->m_core->currency_to_number($dataTmp['harga_jual']),
    //             'biaya_pasang_baru'              => $this->m_core->currency_to_number($dataTmp['biaya_pasang']),
    //             'biaya_registrasi'               => $this->m_core->currency_to_number($dataTmp['biaya_registrasi']),
    //             'description'                    => $dataTmp['keterangan'],
    //             'active'                         => $dataTmp['active'] ? 1 : 0,
    //         ];

    //     // validasi apakah user dengan project $project boleh edit data ini
    //     $this->db->join('group_tvi', 'group_tvi.id = paket_internet.group_tvi_id');
    //     $this->db->where('group_tvi.project_id', $project->id);
    //     $this->db->from('paket_internet');
    //     if ($this->db->count_all_results() != 0) {
    //         $this->db->where('code', $data['code'])->where('id !=', $dataTmp['id']);
    //         $this->db->from('paket_internet');
    //         // validasi double
    //         if ($this->db->count_all_results() == 0) {

    //             $before = $this->get_log($dataTmp['id']);
    //             $this->db->where('id', $dataTmp['id']);
    //             $this->db->update('paket_internet', $data);
    //             $after = $this->get_log($dataTmp['id']);

    //             $diff = (object)(array_diff_assoc((array)$after, (array)$before));
    //             $tmpDiff = (array)$diff;
    //             if ($tmpDiff) {
    //                 $this->m_log->log_save('paket_internet', $dataTmp['id'], 'Edit', $diff);
    //                 return 'success';
    //             } else return 'Tidak Ada Perubahan';
    //         } else return 'double';
    //     }
    // }

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
        $this->db->update('paket_internet', ['delete' => 1]);
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

