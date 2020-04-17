<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_monitoring_tvi extends CI_Model
{
    public function get()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



            SELECT  

                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.status_bayar as status_bayar


            FROM  t_tvi_registrasi
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            Where t_tvi_registrasi.[delete] = 0 and t_tvi_registrasi.project_id =  $project->id and  t_tvi_registrasi.active = 1





        ");

        return $query->result_array();
    }





     public function getRegistrasi($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



         SELECT    project.name as project_name,
                   kawasan.name as kawasan_name,
                   blok.name as blok_name,
                   unit.no_unit as unit,



                   
                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   paket_tvi.name as group_tvi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_document as tanggal,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan,
                   t_tvi_tagihan.kode_bill as kode_bill,




                    customer.name as customer_name,
                    customer.homephone as homephone,
                    customer.mobilephone1 as mobilephone,
                    customer.email as email

                   
                   



            FROM  t_tvi_registrasi
            left join t_tvi_tagihan on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            left join unit on t_tvi_registrasi.unit = unit.no_unit
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            left join customer on customer.id = unit.pemilik_customer_id
            Where  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.id = $id





        ");

        return $query->row();
    }




     public function getSelect($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



         SELECT    project.name as project_name,
                   kawasan.name as kawasan_name,
                   blok.name as blok_name,
                   unit.no_unit as unit,



                   
                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   paket_tvi.name as group_tvi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_document as tanggal,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan,
                   t_tvi_tagihan.kode_bill as kode_bill,
                   t_tvi_tagihan.tanggal as tanggal,
                   t_tvi_registrasi.nomor_telepon as homephone,
                   t_tvi_registrasi.nomor_handphone as mobilephone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_tagihan.total as total

            FROM  t_tvi_tagihan
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            left join unit on t_tvi_registrasi.unit_id = unit.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            left join customer on customer.id = unit.pemilik_customer_id
            Where  t_tvi_tagihan.project_id =  $project->id and t_tvi_tagihan.id = $id





        ");

        return $query->row();
    }










     public function getCekTagihan($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



         SELECT  
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   paket_tvi.group_tvi_id as group_tvi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan,
                   t_tvi_tagihan.kode_bill as kode_bill






            FROM  t_tvi_tagihan
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            Where  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.id = $id and t_tvi_tagihan.status_bayar = 'finish'





        ");

        return $query->row();
    }



     public function getListTagihan($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



         SELECT  
                  
                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   paket_tvi.group_tvi_id as group_tvi_id,
                   paket_tvi.harga_jual as harga,
                   paket_tvi.bandwidth as bandwidth,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan,
                   t_tvi_tagihan.kode_bill as kode_bill,
                   t_tvi_tagihan.tanggal as tanggal,
                   t_tvi_tagihan.total as total,
                   t_tvi_pembayaran.no_fisik_kwitansi as no_fisik_kwitansi,
                   t_tvi_pembayaran.no_fisik_kwitansi as no_ref_pembayaran









            FROM  t_tvi_tagihan
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            left join t_tvi_pembayaran on t_tvi_pembayaran.tagihan_id = t_tvi_tagihan.id
            WHERE  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.id = $id 
            ORDER BY t_tvi_tagihan.id desc





        ");

        return $query->result_array();
    }



     public function getListPembayaran($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



         SELECT  
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   paket_tvi.group_tvi_id as group_tvi_id,
                   paket_tvi.harga_jual as harga,
                   paket_tvi.bandwidth as bandwidth,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan,
                   t_tvi_tagihan.kode_bill as kode_bill,
                   t_tvi_tagihan.tanggal as tanggal,
                   t_tvi_tagihan.total as total,
                   t_tvi_pembayaran.no_fisik_kwitansi as no_fisik_kwitansi,
                   t_tvi_pembayaran.no_ref_pembayaran as no_ref_pembayaran,
                   t_tvi_pembayaran.tanggal_pembayaran as tanggal_pembayaran,
                   t_tvi_pembayaran.total_bayar as total_bayar,
                   t_tvi_pembayaran.tanggal_pembayaran as tanggal_pembayaran




            FROM  t_tvi_pembayaran
            left join t_tvi_tagihan on t_tvi_pembayaran.tagihan_id = t_tvi_tagihan.id
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            Where  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.id = $id 
            ORDER BY t_tvi_pembayaran.id desc





        ");

        return $query->result_array();
    }



    public function getCekTanggal($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



         SELECT  
                  
                   t_tvi_registrasi.customer_name as customer_name,                
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi
                  
            FROM   t_tvi_registrasi           
            WHERE  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.id = $id 
            ORDER BY t_tvi_registrasi.id asc





        ");

        return $query->row();
    }





     public function getPaket()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT *
            FROM paket_tvi         


             ");

        return $query->result_array();
    }



    public function getPaket2($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                   paket_tvi.name as paket_name,
                   paket_tvi.harga_setelah_ppn as paket_harga_ppn,
                   paket_tvi.biaya_pasang_baru as paket_biaya_pasang_baru,
                   paket_tvi.description as paket_description,
                   paket_tvi.id as paket_id

            FROM paket_tvi      
            join group_tvi  on paket_tvi.group_tvi_id = group_tvi.id where  group_tvi.project_id = $project->id and paket_tvi.id = $id      

             ");

        return $query->row();
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
                   unit.*,
                   customer.name as customer_name,
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





  


     public function save_tagihan($dataTmp)
    {


      // echo ('<pre>');

      // print_r($dataTmp);
      // echo('</pre>');

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

      
        $paket = explode('|', $dataTmp['pilih_paket']);


        $dataRegistrasi =
        [
            'jenis_pemasangan' => $dataTmp['jenis_pemasangan'],
            'tanggal_document' => $dataTmp['tanggal'],
            'jenis_paket_id' => $paket[0],
            'total' => $this->m_core->currency_to_number($dataTmp['total']),
            'status_bayar' => 'pending',
            'active' => 0,
            'delete' => 0,
        ];


         $dataTagihan =
        [
            
            'kode_bill' => $dataTmp['nomor_billing'],
            'project_id'    => $project->id,
            'tanggal' => $dataTmp['tanggal'], 
            'jenis_paket_id' => $paket[0], 
            'total' => $this->m_core->currency_to_number($dataTmp['total']), 
            'registrasi_id' => $dataTmp['id'], 
            'status_bayar' => 'pending',                        
            'active' => 0,
            'delete' => 0,
        ];

            

      
        $this->db->where('kode_bill', $dataTmp['nomor_billing']);
        $this->db->from('t_tvi_tagihan');

       if ($this->db->count_all_results() == 0) {
          
                $before = $this->get_log($dataTmp['id']);

                $this->db->where('id', $dataTmp['id']);
                $this->db->update('t_tvi_registrasi', $dataRegistrasi);

                 $after = $this->get_log($dataTmp['id']);
                 
                $this->db->insert('t_tvi_tagihan', $dataTagihan);

                $dataLog = $this->get_log_tagihan($this->db->insert_id());
                $this->m_log->log_save('t_tvi_tagihan', $this->db->insert_id(), 'Tambah', $dataLog);

                
               

                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                echo '<PRE>';

                print_r($diff);

                echo '</PRE>';



                if ($tmpDiff)     {
                    $this->m_log->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);

                
                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
                }
            
        }


           
       
        

}







    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
            SELECT    project.name as project_name,
                   kawasan.name as kawasan_name,
                   blok.name as blok_name,
                   unit.no_unit as unit,



                   
                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   paket_tvi.name as group_tvi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_document as tanggal,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan,
                   t_tvi_tagihan.kode_bill as kode_bill,
                   t_tvi_tagihan.tanggal as tanggal,
                   t_tvi_registrasi.nomor_telepon as homephone,
                   t_tvi_registrasi.nomor_handphone as mobilephone,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_tagihan.total as total

            FROM  t_tvi_tagihan
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            left join unit on t_tvi_registrasi.unit_id = unit.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            left join customer on customer.id = unit.pemilik_customer_id
            Where  t_tvi_tagihan.project_id =  $project->id and t_tvi_tagihan.id = $id





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
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   paket_tvi.group_tvi_id as group_tvi_id,
                   paket_tvi.harga_jual as harga,
                   paket_tvi.bandwidth as bandwidth,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan,
                   t_tvi_tagihan.kode_bill as kode_bill,
                   t_tvi_tagihan.tanggal as tanggal,
                   t_tvi_tagihan.total as total,
                   t_tvi_pembayaran.no_fisik_kwitansi as no_fisik_kwitansi,
                   t_tvi_pembayaran.no_fisik_kwitansi as no_ref_pembayaran








            FROM  t_tvi_tagihan
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            left join t_tvi_pembayaran on t_tvi_pembayaran.tagihan_id = t_tvi_tagihan.id
            Where  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.id = $id 





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



    public function last_id(){
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_tvi_tagihan
            ORDER by id desc
        ");
        return $query->row()?$query->row()->id:0;
    }



    
  



    public function edit_tagihan($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        
        $this->db->where('project_id', $project->id)->where('id', $dataTmp['id']);
        $this->db->from('t_tvi_tagihan');
         // echo '<PRE>';

         //  print_r($dataTmp);

         //  echo '</PRE>';
         //   echo '<PRE>';

         //  print_r($this->db->count_all_results());

         //  echo '</PRE>';
       
          $paket = explode('|', $dataTmp['pilih_paket']);



          $dataRegistrasi =
        [
            'jenis_pemasangan' => $dataTmp['jenis_pemasangan'],
            'tanggal_document' => $dataTmp['tanggal'],
            'jenis_paket_id' => $paket[0],
            'total' => $this->m_core->currency_to_number($dataTmp['total']),
            'status_bayar' => 'pending',
            'active' => 0,
            'delete' => 0,
        ];


         $dataTagihan =
        [
            
            'project_id'    => $project->id,
            'tanggal' => $dataTmp['tanggal'], 
            'jenis_paket_id' => $paket[0], 
            'total' => $this->m_core->currency_to_number($dataTmp['total']), 
            'registrasi_id' => $dataTmp['registrasi_id'], 
            'status_bayar' => 'pending',                        
            'active' => 0,
            'delete' => 0,
        ];







        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('kode_bill', $dataTmp['nomor_billing'])->where('id !=', $dataTmp['id']);
            $this->db->from('t_tvi_tagihan');
            // validasi double
            if ($this->db->count_all_results() == 0) {


                 $before = $this->get_log($dataTmp['registrasi_id']);

                $this->db->where('id', $dataTmp['registrasi_id']);
                $this->db->update('t_tvi_registrasi', $dataRegistrasi);

                 $after = $this->get_log($dataTmp['registrasi_id']);
                 
                $beforeTagihan = $this->get_log($dataTmp['id']);

                $this->db->where('id', $dataTmp['id']);
                $this->db->update('t_tvi_tagihan', $dataTagihan);

                 $afterTagihan = $this->get_log($dataTmp['id']);
               
                
               

                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                // echo '<PRE>';

                // print_r($diff);

                // echo '</PRE>';



                 $diff2 = (object) (array_diff_assoc((array) $afterTagihan, (array) $beforeTagihan));
                 $tmpDiff2 = (array) $diff2;


                //   echo '<PRE>';

                // print_r($diff2);

                // echo '</PRE>';




                if ($tmpDiff)    {
                    $this->m_log->log_save('t_tvi_registrasi', $dataTmp['registrasi_id'], 'Edit', $diff);

                  

                    return 'success';
                } else if ($tmpDiff2)    {
                  

                    $this->m_log->log_save('t_tvi_tagihan', $dataTmp['id'], 'Edit', $diff2);


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
        $this->db->from('t_tvi_posting');


        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('t_tvi_tagihan', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('t_tvi_tagihan', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
