<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_pembayaran_tvi extends CI_Model
{
    public function getRegistrasi()
    {


       $project = $this->m_core->project();

        $query = $this->db->query("
       


            SELECT * 
            FROM t_tvi_registrasi 
            where  project_id = $project->id and status_bayar = 0


        ");

        return $query->result_array();
    }

    public function getAll()
    {

        $project = $this->m_core->project();


        $query = $this->db->query("



         SELECT    t_tvi_pembayaran.tanggal_pembayaran as tanggal_pembayaran,
                   t_tvi_pembayaran.no_fisik_kwitansi as no_fisik_kwitansi,
                   t_tvi_pembayaran.total_bayar as total_bayar,
                   t_tvi_pembayaran.id as pay_id,
                   t_tvi_pembayaran.active as active,
                   t_tvi_registrasi.id as registrasi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   paket_tvi.name as paket_name,
                   t_tvi_registrasi.customer_name as customer_name,
                   t_tvi_registrasi.nomor_telepon as nomor_telepon,
                   t_tvi_registrasi.nomor_handphone as nomor_handphone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi
                 


                 


        FROM t_tvi_pembayaran 
        left join t_tvi_tagihan on t_tvi_pembayaran.tagihan_id = t_tvi_tagihan.id
        left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
        left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
        where  t_tvi_registrasi.project_id = $project->id



        ");

        return $query->result_array();
    }


    public function getRegistrasi2($id)
    {

        $project = $this->m_core->project();


        $query = $this->db->query("



         SELECT     
                   t_tvi_registrasi.id_tagihan as id_tagihan, 
                   t_tvi_registrasi.id as registrasi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   paket_tvi.name as paket_name,
                   t_tvi_registrasi.customer_name as customer_name,
                   t_tvi_registrasi.nomor_telepon as nomor_telepon,
                   t_tvi_registrasi.nomor_handphone as nomor_handphone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_tagihan.kode_bill as nomor_tagihan,
                   t_tvi_tagihan.total as sub_total



                 


        FROM t_tvi_registrasi 
        left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
        left join t_tvi_tagihan on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
        where t_tvi_registrasi.id = $id and t_tvi_registrasi.project_id = $project->id and t_tvi_tagihan.status_bayar = 0
        order by t_tvi_tagihan.id desc



        ");

        return $query->row();
    }

  



     public function getCaraPembayaran()
    {


       $project = $this->m_core->project();

        $query = $this->db->query("
       


            SELECT * FROM cara_pembayaran


        ");

        return $query->result_array();
    }






    public function last_id(){
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_tvi_pembayaran
            ORDER by id desc
        ");
        return $query->row()?$query->row()->id:0;
    }





     public function save($dataTmp)
    {


        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

      


      

         $dataRegistrasi =
        [
           
            'status_bayar' => 1,
            'active' => 0,
            'delete' => 0,
        ];


         $dataTagihan =
        [
           
            'status_bayar' => 1,
            'active' => 0,
            'delete' => 0,
        ];


          $id = $dataTmp['registrasi_id'];
         

          $query = $this->db->query("
           


          SELECT TOP 1 id
            
             
      
         FROM t_tvi_tagihan
         where t_tvi_tagihan.registrasi_id = $id and t_tvi_tagihan.project_id = $project->id   
         order by id desc 
     


         ");


         $row = $query->row();


         if (isset($row) )
         {

           
             $tagihan_id  = $row->id;


         }




        $dataPembayaran =
        [
           
            'nomor_pembayaran' => $dataTmp['nomor_pembayaran'],
            'project_id'    => $project->id,
            'tanggal_pembayaran' => $dataTmp['tanggal_pembayaran'],
            'cara_pembayaran' => $dataTmp['cara_pembayaran'],
            'keterangan' => $dataTmp['keterangan'],
            'no_ref_pembayaran' => $dataTmp['nomor_ref_pembayaran'],
            'no_fisik_kwitansi' => $dataTmp['nomor_fisik_kwitansi'],
            'nomor_tagihan' => $dataTmp['nomor_tagihan'],
            'total_bayar' => $this->m_core->currency_to_number($dataTmp['total_bayar']),
            'total_tagihan' => $this->m_core->currency_to_number($dataTmp['total_tagihan']),
            'discount' => $dataTmp['discount'],                  
            'tagihan_id' => $tagihan_id,
            'active' => 1,
            'delete' => 0,
        ];


      
            

      

      
        $this->db->where('nomor_pembayaran', $dataTmp['nomor_pembayaran']);
        $this->db->from('t_tvi_pembayaran');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('t_tvi_pembayaran', $dataPembayaran);
            $id = $this->db->insert_id();

            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('t_tvi_pembayaran', $this->db->insert_id(), 'Tambah', $dataLog);

          


             $before = $this->get_log_registrasi($dataTmp['registrasi_id']);

             $this->db->where('id', $dataTmp['registrasi_id']);
             $this->db->update('t_tvi_registrasi', $dataRegistrasi);


             $after = $this->get_log_registrasi($dataTmp['registrasi_id']);


             $beforetagihan = $this->get_log_tagihan($tagihan_id);                 
              
             $this->db->where('id', $tagihan_id);
             $this->db->update('t_tvi_tagihan', $dataTagihan);

             $aftertagihan = $this->get_log_tagihan($tagihan_id);

 
             
             $diff = (object) (array_diff_assoc((array) $after, (array) $before));
             $tmpDiff = (array) $diff;

              

             $diff2 = (object) (array_diff_assoc((array) $aftertagihan, (array) $beforetagihan));
             $tmpDiff2 = (array) $diff2;


               if  ($dataLog or $tmpDiff or $tmpDiff2)    {
                    $tmpDiff?$this->m_log->log_save('t_tvi_registrasi', $dataTmp['registrasi_id'], 'Edit', $diff):'';
                    $tmpDiff2?$this->m_log->log_save('t_tvi_tagihan', $tagihan_id, 'Edit', $diff2):'';

                  

                    return 'success';
             
                } else {
                    return 'Tidak Ada Perubahan';
                }







        } else {
            return 'double';
        }


           
       
        

}


public function save_biaya_tambahan($dataTmp)
{


    $this->load->model('m_core');
    $this->load->model('m_log');
    $project = $this->m_core->project();

  

    $dataBiayaTambahan =
    [
       
        'status_bayar' => 1,
        'active' => 0,
        'delete' => 0,
    ];

  

    
     $dataTagihan =
    [
       
        'status_bayar' => 1,
        'active' => 0,
        'delete' => 0,
    ];


    $nomor_tagihan =  $dataTmp['nomor_tagihan'];
     

            $query = $this->db->query("
       


             SELECT TOP 1 id
               
                
         
            FROM t_tvi_tagihan
            where t_tvi_tagihan.kode_bill = '$nomor_tagihan'  and t_tvi_tagihan.project_id = $project->id  
            order by id desc 
        


            ");


            $row = $query->row();


            if (isset($row) )
            {

              
                $tagihan_id  = $row->id;


            }


    $dataPembayaran =
    [
       
        'nomor_pembayaran' => $dataTmp['nomor_pembayaran'],
        'project_id'    => $project->id,
        'tanggal_pembayaran' => $dataTmp['tanggal_pembayaran'],
        'cara_pembayaran' => $dataTmp['cara_pembayaran'],
        'keterangan' => $dataTmp['keterangan'],
        'no_ref_pembayaran' => $dataTmp['nomor_ref_pembayaran'],
        'no_fisik_kwitansi' => $dataTmp['nomor_fisik_kwitansi'],
        'nomor_tagihan' => $dataTmp['nomor_tagihan'],
        'total_bayar' => $this->m_core->currency_to_number($dataTmp['total_bayar']),
        'total_tagihan' => $this->m_core->currency_to_number($dataTmp['total_tagihan']),
        'discount' => $this->m_core->currency_to_number($dataTmp['discount']),                 
        'tagihan_id' => $tagihan_id,
        'active' => 1,
        'delete' => 0,
    ];


  
        

  

  
    $this->db->where('nomor_pembayaran', $dataTmp['nomor_pembayaran']);
    $this->db->from('t_tvi_pembayaran');

    // validasi double
    if ($this->db->count_all_results() == 0) {
        $this->db->insert('t_tvi_pembayaran', $dataPembayaran);
        $id = $this->db->insert_id();

        $dataLog = $this->get_log($this->db->insert_id());
        $this->m_log->log_save('t_tvi_pembayaran', $this->db->insert_id(), 'Tambah', $dataLog);

      

        $before = $this->get_log_biaya_tambahan($dataTmp['biaya_tambahan_id']);

        $this->db->where('id', $dataTmp['biaya_tambahan_id']);
        $this->db->update('t_tvi_biaya_tambahan', $dataBiayaTambahan);


        $after = $this->get_log_biaya_tambahan($dataTmp['biaya_tambahan_id']);




         $beforetagihan = $this->get_log_tagihan($tagihan_id);

             
          
         $this->db->where('id', $tagihan_id);
         $this->db->update('t_tvi_tagihan', $dataTagihan);

         $aftertagihan = $this->get_log_tagihan($tagihan_id);



         $diff = (object) (array_diff_assoc((array) $after, (array) $before));
         $tmpDiff = (array) $diff;


         $diff2 = (object) (array_diff_assoc((array) $aftertagihan, (array) $beforetagihan));
         $tmpDiff2 = (array) $diff2;


           if  ($dataLog or $tmpDiff  or $tmpDiff2 )    {
            $tmpDiff?$this->m_log->log_save('t_tvi_biaya_tambahan', $dataTmp['biaya_tambahan_id'], 'Edit', $diff):'';
            $tmpDiff2?$this->m_log->log_save('t_tvi_tagihan', $tagihan_id, 'Edit', $diff2):'';


              

                return 'success';
         
            } else {
                return 'Tidak Ada Perubahan';
            }







    } else {
        return 'double';
    }


       
   
    

}

 public function get_log_registrasi($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                   project.name as project,
                   kawasan.name as kawasan,
                   blok.name as blok,
                   unit.no_unit as unit,
                   unit.id as unit_id,
                   customer.name as customer,
                   customer.email as email,
                   customer.mobilephone1 as no_hp,
                   customer.code as nomor_registrasi,
                   customer.homephone as telepon,



                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_document as tanggal_document,
                   t_tvi_registrasi.tanggal_pemasangan_mulai as tanggal_pemasangan_mulai,
                   t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.keterangan as keterangan,
                   t_tvi_registrasi.harga_paket as harga_paket,
                   t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                   t_tvi_registrasi.diskon  as diskon,
                   t_tvi_registrasi.total as total,

                    paket_tvi.description as keterangan_paket,
                    paket_tvi.name as jenis_paket
                    
             
            FROM t_tvi_registrasi
            left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
            left join unit on unit.no_unit = t_tvi_registrasi.unit
            left join customer on unit.pemilik_customer_id = customer.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            where 
            t_tvi_registrasi.id = $id and
            kawasan.project_id = $project->id  
            


        ");
        $row = $query->row();

        return $row;
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



    public function get_log_biaya_tambahan($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           


            SELECT *
                   
                    
             
            FROM t_tvi_biaya_tambahan
            where t_tvi_biaya_tambahan.id = $id and t_tvi_biaya_tambahan.project_id = $project->id  
            


        ");
        $row = $query->row();
    
        return $row;
    }





    
   

   

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
            SELECT * 


            FROM t_tvi_pembayaran
            left join t_tvi_tagihan on t_tvi_pembayaran.tagihan_id = t_tvi_tagihan.id
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
           
            where 

            t_tvi_pembayaran.id = $id and
            t_tvi_pembayaran.project_id = $project->id  




            ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
        
        SELECT t_tvi_pembayaran.nomor_pembayaran as nomor_pembayaran,
               t_tvi_pembayaran.cara_pembayaran as cara_pembayaran,
               t_tvi_pembayaran.tanggal_pembayaran as tanggal_pembayaran,
               t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
               t_tvi_pembayaran.keterangan as keterangan,
               t_tvi_pembayaran.no_ref_pembayaran as nomor_ref_pembayaran,
               t_tvi_tagihan.total as total_tagihan,
               t_tvi_pembayaran.total_tagihan as total_akhir,
               t_tvi_pembayaran.total_bayar as total_bayar,
               t_tvi_pembayaran.no_fisik_kwitansi as no_fisik_kwitansi,
               t_tvi_pembayaran.active as active,
               paket_tvi.name as paket_layanan,
               t_tvi_registrasi.id as registrasi_id,
               t_tvi_registrasi.id_tagihan as id_tagihan,
               t_tvi_tagihan.kode_bill as nomor_tagihan,
               t_tvi_pembayaran.discount as diskon




        FROM t_tvi_pembayaran
            left join t_tvi_tagihan on t_tvi_pembayaran.tagihan_id = t_tvi_tagihan.id
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
          
            where 

            t_tvi_pembayaran.id = $id and
            t_tvi_pembayaran.project_id = $project->id  
            


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
                  
                 t_tvi_pembayaran.nomor_pembayaran as nomor_pembayaran,
                 t_tvi_pembayaran.tanggal_pembayaran as tanggal_pembayaran,
                 t_tvi_pembayaran.cara_pembayaran as cara_pembayaran,
                 t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                 t_tvi_pembayaran.keterangan as keterangan,
                 paket_tvi.name as paket_name,
                 t_tvi_pembayaran.no_ref_pembayaran,
                 t_tvi_pembayaran.no_fisik_kwitansi,
                 t_tvi_tagihan.total as total_tagihan,
                 t_tvi_pembayaran.total_bayar as total_bayar

             
            FROM t_tvi_pembayaran
            left join t_tvi_tagihan on t_tvi_tagihan.id = t_tvi_pembayaran.tagihan_id
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            where  t_tvi_pembayaran.id = $id and
            t_tvi_pembayaran.project_id = $project->id  
            


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
        $this->db->from('t_tvi_pembayaran');

        $data =
        [
            
          
            'nomor_pembayaran' => $dataTmp['nomor_pembayaran'],
            'project_id'    => $project->id,
            'tanggal_pembayaran' => $dataTmp['tanggal_pembayaran'],
            'cara_pembayaran' => $dataTmp['cara_pembayaran'],
            'keterangan' => $dataTmp['keterangan'],
            'no_ref_pembayaran' => $dataTmp['nomor_ref_pembayaran'],
            'no_fisik_kwitansi' => $dataTmp['nomor_fisik_kwitansi'],
            'nomor_tagihan' => $dataTmp['nomor_tagihan'],
            'total_bayar' => $this->m_core->currency_to_number($dataTmp['total_bayar']),
            'total_tagihan' => $this->m_core->currency_to_number($dataTmp['total_tagihan']),
            'discount' => $dataTmp['discount'],     
           
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('nomor_pembayaran', $data['nomor_pembayaran'])->where('id', $dataTmp['id']);
            $this->db->from('t_tvi_pembayaran');
            // validasi double
            if ($this->db->count_all_results() > 0) {


                
             $before = $this->get_log($dataTmp['id']);

             $this->db->where('id', $dataTmp['id']);
             $this->db->update('t_tvi_pembayaran', $data);


             $after = $this->get_log($dataTmp['id']);


            
             
             $diff = (object) (array_diff_assoc((array) $after, (array) $before));
             $tmpDiff = (array) $diff;

              


               if  ( $tmpDiff )    {
                    $tmpDiff?$this->m_log->log_save('t_tvi_pembayaran', $dataTmp['id'], 'Edit', $diff):'';
                  
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
