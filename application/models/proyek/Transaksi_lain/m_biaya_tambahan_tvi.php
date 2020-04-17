<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_biaya_tambahan_tvi extends CI_Model
{

    public function getAll()
    {


       $project = $this->m_core->project();

        $query = $this->db->query("
       


            SELECT      
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.customer_name as customer_name,
                   t_tvi_registrasi.nomor_telepon as nomor_telepon,
                   t_tvi_registrasi.nomor_handphone as nomor_handphone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_biaya_tambahan.nomor_tagihan as nomor_tagihan,
                   t_tvi_biaya_tambahan.tanggal_tagihan as tanggal_tagihan,
                   t_tvi_biaya_tambahan.total_tagihan as total_tagihan,
                   t_tvi_biaya_tambahan.id as id,
                   t_tvi_biaya_tambahan.status_bayar as status_bayar,
                   t_tvi_biaya_tambahan.active as active,
                   blok.name as blok_name,
                   unit.no_unit as unit_no,
                   kawasan.name as kawasan_name






        FROM t_tvi_biaya_tambahan 
        left join t_tvi_registrasi on t_tvi_registrasi.id = t_tvi_biaya_tambahan.registrasi_id
        left join unit on unit.id = t_tvi_registrasi.unit_id
        left join blok on unit.blok_id = blok.id
        left join kawasan on blok.kawasan_id = kawasan.id
        left join project on project.id = kawasan.project_id 

        where t_tvi_registrasi.project_id = $project->id



        ");

        return $query->result_array();
    }




     public function getSelect($id)
    {


       $project = $this->m_core->project();

        $query = $this->db->query("
       


            SELECT      
                   t_tvi_registrasi.id as registrasi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.customer_name as customer_name,
                   t_tvi_registrasi.nomor_telepon as nomor_telepon,
                   t_tvi_registrasi.nomor_handphone as nomor_handphone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_biaya_tambahan.nomor_tagihan as nomor_tagihan,
                   t_tvi_biaya_tambahan.tanggal_tagihan as tanggal_tagihan,
                   t_tvi_biaya_tambahan.total_tagihan as total_tagihan,
                   t_tvi_biaya_tambahan.id as id,
                   t_tvi_biaya_tambahan.active as active,
                   t_tvi_biaya_tambahan.keterangan as keterangan,
                   blok.name as blok_name,
                   unit.no_unit as unit_no,
                   kawasan.name as kawasan_name,
                   paket_tvi.name as paket_name







        FROM t_tvi_biaya_tambahan 
        left join t_tvi_registrasi on t_tvi_registrasi.id = t_tvi_biaya_tambahan.registrasi_id
        left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
        left join t_tvi_biaya_tambahan_detail on t_tvi_registrasi.id = t_tvi_biaya_tambahan_detail.registrasi_id
        left join unit on unit.id = t_tvi_registrasi.unit_id
        left join blok on unit.blok_id = blok.id
        left join kawasan on blok.kawasan_id = kawasan.id
        left join project on project.id = kawasan.project_id 

        where t_tvi_registrasi.project_id = $project->id and t_tvi_biaya_tambahan.id = $id



        ");

         return $query->row();
    }






    public function getRegistrasi()
    {


       $project = $this->m_core->project();

        $query = $this->db->query("
       


            SELECT      
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.total as total,
                   paket_tvi.name as paket_name,
                   t_tvi_registrasi.customer_name as customer_name,
                   t_tvi_registrasi.nomor_telepon as nomor_telepon,
                   t_tvi_registrasi.nomor_handphone as nomor_handphone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_registrasi.id_tagihan as id_tagihan

    


        FROM t_tvi_registrasi 
        left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
        where t_tvi_registrasi.project_id = $project->id and t_tvi_registrasi.active = 0



        ");

        return $query->result_array();
    }


    public function getRegistrasi2($id)
    {

        $project = $this->m_core->project();


        $query = $this->db->query("



         SELECT      
                   t_tvi_registrasi.id as registrasi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.total as total,
                   paket_tvi.name as paket_name,
                   t_tvi_registrasi.customer_name as customer_name,
                   t_tvi_registrasi.nomor_telepon as nomor_telepon,
                   t_tvi_registrasi.nomor_handphone as nomor_handphone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_registrasi.id_tagihan as id_tagihan

                 


        FROM t_tvi_registrasi 
        left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
        where t_tvi_registrasi.id = $id and t_tvi_registrasi.project_id = $project->id



        ");

        return $query->row();
    }



    public function get_biaya_tambahan_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                t_tvi_biaya_tambahan_detail.id as id_item,
                t_tvi_biaya_tambahan_detail.item as item,
                t_tvi_biaya_tambahan_detail.quantity as quantity,
                t_tvi_biaya_tambahan_detail.harga_satuan as harga_satuan,
                t_tvi_biaya_tambahan_detail.description as keterangan,
                t_tvi_biaya_tambahan_detail.[delete] as delete_item
                 
            FROM t_tvi_biaya_tambahan_detail
            WHERE t_tvi_biaya_tambahan_detail.project_id = $project->id   
            and t_tvi_biaya_tambahan_detail.biaya_tambahan_id = $id 
            order by t_tvi_biaya_tambahan_detail.id asc
        ");
        // echo("<pre>");
        //     print_r($query->result_array());
        // echo("</pre>");
        
        return $query->result_array();
    }


  





    public function last_id(){
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_tvi_tagihan
            ORDER by id desc
        ");
        return $query->row()?$query->row()->id:0;
    }

  


     public function save($dataTmp)
    {


        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

      


        $dataBiayaTambahan =
        [
           
            'registrasi_id' => $dataTmp['registrasi_id'],
            'project_id'    => $project->id,
            'nomor_tagihan' => $dataTmp['nomor_tagihan'],
            'tanggal_tagihan' => $dataTmp['tanggal_tagihan'],
            'total_tagihan' => $this->m_core->currency_to_number($dataTmp['total_tagihan']),
            'keterangan' => $dataTmp['keterangan'],
            'status_bayar' => 0,
            'active' => 1,
            'delete' => 0,
        ];


         $dataBiayaTambahanDetail = [
            'item' => $dataTmp['item'],
            'project_id'    => $project->id,
            'quantity' => $this->m_core->currency_to_number($dataTmp['quantity']),
            'harga_satuan' => $this->m_core->currency_to_number($dataTmp['harga_satuan']),
            'project_id'    => $project->id,
            'description' => $dataTmp['keterangan'],
            'active_detail' => 1,
            'delete_detail' => 0,
        ];



            $dataTagihan =
            [
                'kode_bill' =>  $dataTmp['nomor_tagihan'],
                'tanggal' => date('Y-m-d'),
                'project_id'    => $project->id,
                'jenis_paket_id' => $dataTmp['jenis_paket_id'],
                'total' => $this->m_core->currency_to_number($dataTmp['total']),
                'registrasi_id' =>$dataTmp['registrasi_id'],
                'status_bayar' => 0,
                'active' => 1,
                'delete' => 0,
            ];


             

      
        $this->db->where('registrasi_id', $dataTmp['registrasi_id']);
        $this->db->from('t_tvi_biaya_tambahan');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('t_tvi_biaya_tambahan', $dataBiayaTambahan);
            $id = $this->db->insert_id();


            $this->db->insert('t_tvi_tagihan', $dataTagihan);
            $id_tagihan = $this->db->insert_id();

           
            for ($i = 0; $i < count($dataTmp['item']); ++$i) {
                $item[$i] = $dataTmp['item'][$i];
                $quantity[$i] = $dataTmp['quantity'][$i];
                $harga_satuan[$i] = $dataTmp['harga_satuan'][$i];
                $keterangan[$i] = $dataTmp['keterangan'][$i];


                $dataBiayaTambahanDetail2 = [
                    'biaya_tambahan_id' => $id,
                    'registrasi_id' => $dataTmp['registrasi_id'],
                    'item' => $item[$i],
                    'quantity' => $this->m_core->currency_to_number($quantity[$i]),
                    'harga_satuan' => $this->m_core->currency_to_number($harga_satuan[$i]),
                    'description' => $keterangan[$i],
                    'active' => 1,
                    'delete' => 0,
                ];

                $this->db->insert('t_tvi_biaya_tambahan_detail', $dataBiayaTambahanDetail2);
                $dataLog = $this->get_log($this->db->insert_id());
                $this->m_log->log_save('t_tvi_biaya_tambahan_detail',$this->db->insert_id(),'Tambah',$dataLog);
            }
            $dataLog = $this->get_log($id);
            $this->m_log->log_save('t_tvi_biaya_tambahan', $id, 'Tambah', $dataLog);


            $dataLog = $this->get_log_tagihan($id_tagihan);
            $this->m_log->log_save('t_tvi_tagihan', $id_tagihan, 'Tambah', $dataLog);




            return 'success';
        } else {
            return 'double';
        }


           
       
        

}


     public function get_log_biaya_tambahan_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                t_tvi_biaya_tambahan_detail.id as id_item,
                t_tvi_biaya_tambahan_detail.item as item,
                t_tvi_biaya_tambahan_detail.quantity as quantity,
                t_tvi_biaya_tambahan_detail.harga_satuan as harga_satuan,
                t_tvi_biaya_tambahan_detail.description as keterangan,
                t_tvi_biaya_tambahan_detail.[delete] as delete_item
                 
            FROM t_tvi_biaya_tambahan_detail
            WHERE t_tvi_biaya_tambahan_detail.project_id = $project->id   
            and t_tvi_biaya_tambahan_detail.id = $id 
            order by t_tvi_biaya_tambahan_detail.id asc
        ");
        // echo("<pre>");
        //     print_r($query->result_array());
        // echo("</pre>");
        
        return $query->result_array();
    }





   

   

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
           SELECT      
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.customer_name as customer_name,
                   t_tvi_registrasi.nomor_telepon as nomor_telepon,
                   t_tvi_registrasi.nomor_handphone as nomor_handphone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_biaya_tambahan.nomor_tagihan as nomor_tagihan,
                   t_tvi_biaya_tambahan.tanggal_tagihan as tanggal_tagihan,
                   t_tvi_biaya_tambahan.total_tagihan as total_tagihan,
                   t_tvi_biaya_tambahan.id as id,
                   t_tvi_biaya_tambahan.active as active,
                   blok.name as blok_name,
                   unit.no_unit as unit_no,
                   kawasan.name as kawasan_name






        FROM t_tvi_biaya_tambahan 
        left join t_tvi_registrasi on t_tvi_registrasi.id = t_tvi_biaya_tambahan.registrasi_id
        left join t_tvi_biaya_tambahan_detail on t_tvi_registrasi.id = t_tvi_biaya_tambahan_detail.registrasi_id
        left join unit on unit.id = t_tvi_registrasi.unit_id
        left join blok on unit.blok_id = blok.id
        left join kawasan on blok.kawasan_id = kawasan.id
        left join project on project.id = kawasan.project_id 

        where t_tvi_registrasi.project_id = $project->id and t_tvi_biaya_tambahan.id = $id




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
                    t_tvi_registrasi.customer_name   as customer_name,
                    t_tvi_registrasi.nomor_registrasi  as nomor_registrasi,
                    t_tvi_registrasi.nomor_telepon  as nomor_telepon,
                    t_tvi_registrasi.nomor_handphone  as nomor_handphone,
                    t_tvi_registrasi.email  as email,
                    t_tvi_registrasi.jenis_pemasangan  as jenis_layanan,
                    t_tvi_registrasi.total  as total_tagihan,

                    t_tvi_biaya_tambahan.nomor_tagihan    as nomor_tagihan_biaya_tambahan,
                    t_tvi_biaya_tambahan.tanggal_tagihan  as tanggal_tagihan_biaya_tambahan,
                    t_tvi_biaya_tambahan.total_tagihan  as total_tagihan_biaya_tambahan,
                    t_tvi_biaya_tambahan.keterangan  as keterangan,

                    paket_tvi.name as paket_layanan,

                    case when t_tvi_biaya_tambahan.active = 0      then 'Tidak Aktif' else 'Aktif' end as aktif, 
                    case when t_tvi_biaya_tambahan.[delete] = 0     then 'Tidak di Hapus' else 'Terhapus' end as [delete],

                    t_tvi_biaya_tambahan_detail.id    as detail_id,
                    t_tvi_biaya_tambahan_detail.item    as item,
                    t_tvi_biaya_tambahan_detail.quantity  as quantity,
                    t_tvi_biaya_tambahan_detail.harga_satuan  as harga_satuan,
                    t_tvi_biaya_tambahan_detail.description  as keterangan,

                    case when t_tvi_biaya_tambahan_detail.active = 0  then 'Tidak Aktif' else 'Aktif' end as detail_aktif,
                    case when t_tvi_biaya_tambahan_detail.[delete] = 0 then 'Tidak di Hapus' else 'Terhapus' end as detail_delete

                    
            FROM t_tvi_biaya_tambahan
            LEFT JOIN t_tvi_registrasi                   on t_tvi_biaya_tambahan.registrasi_id = t_tvi_registrasi.id
            LEFT JOIN t_tvi_biaya_tambahan_detail            on t_tvi_biaya_tambahan.id = t_tvi_biaya_tambahan_detail.biaya_tambahan_id
            LEFT JOIN paket_tvi                          on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
            WHERE               t_tvi_biaya_tambahan.id             = $id
            AND                 t_tvi_biaya_tambahan.project_id     = $project->id
            ORDER BY            t_tvi_biaya_tambahan_detail.id          ASC
        ");
        $row = $query->result_array();
        $hasil = [];
        $i = 1;
        foreach ($row as $v) {
            if (!array_key_exists('nomor_tagihan_biaya_tambahan', $hasil)) {
                $hasil['nomor_tagihan_biaya_tambahan'] = $v['nomor_tagihan_biaya_tambahan'];
                $hasil['tanggal_tagihan_biaya_tambahan'] = $v['tanggal_tagihan_biaya_tambahan'];
                $hasil['total_tagihan_biaya_tambahan'] = $v['total_tagihan_biaya_tambahan'];
                $hasil['keterangan'] = $v['keterangan'];
                $hasil['customer_name'] = $v['customer_name'];
                $hasil['nomor_registrasi'] = $v['nomor_registrasi'];
                $hasil['nomor_telepon'] = $v['nomor_telepon'];
                $hasil['nomor_handphone'] = $v['nomor_handphone'];
                $hasil['email'] = $v['email'];
                $hasil['jenis_layanan'] = $v['jenis_layanan'];
                $hasil['total_tagihan'] = $v['total_tagihan'];
                $hasil['aktif'] = $v['aktif'];
                $hasil['delete'] = $v['delete'];
            }
              

            $hasil[$i.' detail_id'] = $v['detail_id'];
            $hasil[$i.' item'] = $v['item'];
            $hasil[$i.' quantity'] = $v['quantity'];
            $hasil[$i.' harga_satuan'] = $v['harga_satuan'];
            $hasil[$i.' keterangan'] = $v['keterangan'];
            $hasil[$i.' detail_aktif'] = $v['detail_aktif'];
            $hasil[$i.' detail_delete'] = $v['detail_delete'];

            ++$i;
        }

        return $hasil;
    }


     public function get_log_tagihan($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           


            SELECT *
                   
                    
             
            FROM t_tvi_tagihan
            where t_tvi_tagihan.id = $id and t_tvi_tagihan.project_id = $project->id  
            


        ");
        $row = $query->row();

        return $row;
    }




    public function edit($dataTmp)
    {


        $before = $this->get_log($dataTmp['id']);

        $this->db->where('biaya_tambahan_id', $dataTmp['id']);
        $this->db->update('t_tvi_biaya_tambahan_detail', ['delete' => 1]);


        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id)->where('id', $dataTmp['id']);
        $this->db->from('t_tvi_biaya_tambahan');

        //  echo '<PRE>';
        //   print_r($dataTmp);
        //  echo '</PRE>';



        $dataBiayaTambahan =
        [
           
            'registrasi_id' => $dataTmp['registrasi_id'],
            'project_id'    => $project->id,
            'nomor_tagihan' => $dataTmp['nomor_tagihan'],
            'tanggal_tagihan' => $dataTmp['tanggal_tagihan'],
            'total_tagihan' => $this->m_core->currency_to_number($dataTmp['total_tagihan']),
            'keterangan' => $dataTmp['keterangan'],
            'active' => 1,
            'delete' => 0,
        ];


         $dataBiayaTambahanDetail = [
            'item' => $dataTmp['item'],
            'quantity' => $this->m_core->currency_to_number($dataTmp['quantity']),
            'harga_satuan' => $this->m_core->currency_to_number($dataTmp['harga_satuan']),
            'project_id'    => $project->id,
            'description' => $dataTmp['description'],
            'active_detail' => 1,
            'delete_detail' => 0,
        ];



            $dataTagihan =
            [
                'kode_bill' =>  $dataTmp['nomor_tagihan'],
                'tanggal' => date('Y-m-d'),
                'project_id'    => $project->id,
                'jenis_paket_id' => $dataTmp['jenis_paket_id'],
                'total' => $this->m_core->currency_to_number($dataTmp['total_tagihan']),
                'registrasi_id' =>$dataTmp['registrasi_id'],
                'status_bayar' => 0,
                'active' => 1,
                'delete' => 0,
            ];


        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('nomor_tagihan', $dataTmp['nomor_tagihan']);
            $this->db->from('t_tvi_biaya_tambahan');
            // validasi double
            if ($this->db->count_all_results() > 0) {


                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('t_tvi_biaya_tambahan', $dataBiayaTambahan);
                $after = $this->get_log($dataTmp['id']);


                $registrasi_id = $dataTmp['registrasi_id'];


                $query = $this->db->query("
           


                 SELECT TOP 1 id
                   
                    
             
                FROM t_tvi_tagihan
                where t_tvi_tagihan.registrasi_id = $registrasi_id and t_tvi_tagihan.project_id = $project->id   
                order by id desc 
            


                ");


                $row = $query->row();


                if (isset($row) )
                {

                  
                    $tagihan_id  = $row->id;


                }

                //   echo '<PRE>';
                //             echo print_r($tagihan_id);
                //             echo '</PRE>';



                $beforetagihan = $this->get_log_tagihan($tagihan_id);
                $this->db->where('id', $tagihan_id);
                $this->db->update('t_tvi_tagihan', $dataTagihan);
                $aftertagihan = $this->get_log_tagihan($tagihan_id);


                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                $diff2 = (object) (array_diff_assoc((array) $aftertagihan, (array) $beforetagihan));
                $tmpDiff2 = (array) $diff2;



                 if (true){
                $i = 0;
                $jumlahItemBaru = 0;
               

                foreach ($dataTmp['id_item'] as $v) {

                    $dataItemTmp = [];
                    $dataItemTmp =
                    [
                     'biaya_tambahan_id'  => $dataTmp['id'],
                     'registrasi_id' => $dataTmp['registrasi_id'],      
                     'item' => $dataTmp['item'][$i],
                     'quantity' => $this->m_core->currency_to_number($dataTmp['quantity'][$i]),
                     'harga_satuan' => $this->m_core->currency_to_number($dataTmp['harga_satuan'][$i]),
                     'project_id'    => $project->id,
                     'description' => $dataTmp['description']?$dataTmp['description'][$i]:"",
                     'active' => 1,
                     'delete' => 0,
                    ];

                    if ($v != 0) {
                        $jumlahItemBaru++;
                        // edit rekening
                        $this->db->where('id', $v);
                        $this->db->update('t_tvi_biaya_tambahan_detail', $dataItemTmp);
                    }else{
                        // add rekening
                        $this->db->insert('t_tvi_biaya_tambahan_detail', $dataItemTmp);  

                    }

                    $i++;

                    }
            }

            $after = $this->get_log($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;


            $diff2 = (object) (array_diff_assoc((array) $aftertagihan, (array) $beforetagihan));
            $tmpDiff2 = (array) $diff2;





            if (($tmpDiff) OR  ($tmpDiff2) )        {
                $this->m_log->log_save('t_tvi_biaya_tambahan', $dataTmp['id'], 'Edit', $diff);
                $this->m_log->log_save('t_tvi_tagihan', $tagihan_id, 'Edit', $diff2);


                return 'success';
            }

                   
            else {
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
        $this->db->from('t_tvi_pembayaran');


        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('t_tvi_pembayaran', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('t_tvi_pembayaran', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
