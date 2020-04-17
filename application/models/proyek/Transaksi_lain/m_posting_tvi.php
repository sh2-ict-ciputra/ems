<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_posting_tvi extends CI_Model
{
    public function getTransfer()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



            SELECT  t_tvi_transfer.id as id,
                    t_tvi_transfer.nama_rekening as customer,
                    bank.name as bank,
                    t_tvi_transfer.nomor_rekening as nomor_rekening,
                    t_tvi_transfer.kode as kode

            FROM  t_tvi_transfer
            left join  bank on bank.id = t_tvi_transfer.bank_id
            where t_tvi_transfer.project_id = $project->id
            order by t_tvi_transfer.id asc






        ");

        return $query->result_array();
    }

    public function getTransfer2($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



            SELECT  t_tvi_transfer.id as id,
                    t_tvi_transfer.nama_rekening as customer,
                    bank.name as bank,
                    t_tvi_transfer.nomor_rekening as nomor_rekening,
                    t_tvi_transfer.kode as kode,
                    t_tvi_transfer.tanggal as tanggal,
                    t_tvi_transfer.nomor_rekening as nomor_rekening,
                    t_tvi_transfer.nama_rekening as nama_rekening,
                    t_tvi_transfer.total as total_transfer

            FROM  t_tvi_transfer
            left join  bank on bank.id = t_tvi_transfer.bank_id
            where  t_tvi_transfer.id = $id and  t_tvi_transfer.project_id = $project->id
            order by t_tvi_transfer.id asc




        ");

        return $query->row();
    }



     public function getCustomer()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



            SELECT  *


            FROM t_tvi_registrasi 
            Where project_id = $project->id and status_bayar='pending'
            order by id asc


        ");

        return $query->result_array();
    }


     public function getRegistrasi($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



            SELECT  

            t_tvi_registrasi.tanggal_document as tanggal,
            paket_tvi.name as paket_name,
            paket_tvi.bandwidth as bandwidth,
            t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
            t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
            paket_tvi.harga_jual as harga,
            t_tvi_registrasi.nomor_registrasi as nomor_registrasi
           


            FROM t_tvi_registrasi
            left join t_tvi_tagihan on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on t_tvi_registrasi.jenis_paket_id  = paket_tvi.id 
            Where t_tvi_registrasi.project_id = $project->id and t_tvi_tagihan.status_bayar='pending' and t_tvi_registrasi.id  = $id
            order by t_tvi_registrasi.id asc


        ");

        return $query->result_array();
    }




     public function getTagihan($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



            SELECT  *

           

            FROM t_tvi_tagihan
            Where project_id = $project->id  and t_tvi_tagihan.id  = $id
            order by t_tvi_tagihan.id asc


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





  


     public function save($dataTmp)
    {


        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();




                $id_registrasi = $dataTmp['customer'];


                $query = $this->db->query("
           


                 SELECT TOP 1 id
                   
                    
             
                FROM t_tvi_tagihan
                where t_tvi_tagihan.registrasi_id = $id_registrasi and t_tvi_tagihan.project_id = $project->id  and t_tvi_tagihan.status_bayar = 'pending' 
                order by id desc 
            


                ");


                $row = $query->row();


                if (isset($row) )
                {

                  
                    $tagihan_id  = $row->id;


                }

      

        
         $dataPembayaran =
        [
            'tagihan_id' => $tagihan_id,
            'transfer_id' => $dataTmp['pilih_transfer'],            
            'project_id'    => $project->id,
            'tanggal_pembayaran' => $dataTmp['tanggal'],
            'cara_pembayaran'    => 'transfer',
            'total_bayar' => $this->m_core->currency_to_number($dataTmp['total_bayar']),
            'nomor_pembayaran' => $dataTmp['kode_transfer'],
            'active' => 1,
            'delete' => 0,

        ];




          $dataTransfer =
        [
            'status' => 'posted',
        
        ];


          $dataRegistrasi =
        [
            'status_bayar' => 1,
        
        ];


         $dataTagihan =
        [
            'status_bayar' => 1,
        
        ];



               

      
        $this->db->where('kode', $dataTmp['kode_transfer']);
        $this->db->from('t_tvi_transfer');

        // validasi double
        if ($this->db->count_all_results() >= 0) {

            $this->db->insert('t_tvi_pembayaran', $dataPembayaran);
            $id = $this->db->insert_id();

            $dataLog = $this->get_log($this->db->insert_id());
           


            $before = $this->get_log_registrasi($dataTmp['customer']);
            $this->db->where('id', $dataTmp['customer']);
            $this->db->update('t_tvi_registrasi', $dataRegistrasi);
            $after = $this->get_log_registrasi($dataTmp['customer']);


            $beforetagihan = $this->get_log_tagihan($tagihan_id);
            $this->db->where('id', $tagihan_id);
            $this->db->update('t_tvi_tagihan', $dataTagihan);
            $aftertagihan = $this->get_log_tagihan($tagihan_id);


            $beforetransfer = $this->get_log_transfer($dataTmp['pilih_transfer']);
            $this->db->where('id', $dataTmp['pilih_transfer']);
            $this->db->update('t_tvi_transfer', $dataTransfer);
            $aftertransfer = $this->get_log_transfer($dataTmp['pilih_transfer']);


             $diff = (object) (array_diff_assoc((array) $after, (array) $before));
             $tmpDiff = (array) $diff;

              

            $diff2 = (object) (array_diff_assoc((array) $aftertagihan, (array) $beforetagihan));
            $tmpDiff2 = (array) $diff2;



            $diff3 = (object) (array_diff_assoc((array) $aftertransfer, (array) $beforetransfer));
            $tmpDiff3 = (array) $diff3;



            // echo '<PRE>';
            // print_r($id);
            // echo '</PRE>';


            // echo '<PRE>';
            // print_r( $dataTmp['customer']);
            // echo '</PRE>';


            //  echo '<PRE>';
            // print_r( $tagihan_id);
            // echo '</PRE>';


            //  echo '<PRE>';
            // print_r( $dataTmp['pilih_transfer']);
            // echo '</PRE>';

             



               if ($dataLog  OR  $tmpDiff  OR   $tmpDiff2  OR $tmpDiff3  )  

                 {

                    $this->m_log->log_save('t_tvi_pembayaran', $id, 'Tambah', $dataLog);

                    $this->m_log->log_save('t_tvi_registrasi',  $dataTmp['customer'] , 'Edit', $diff);

                    $this->m_log->log_save('t_tvi_tagihan',  $tagihan_id , 'Edit', $diff2);

                    $this->m_log->log_save('t_tvi_transfer',  $dataTmp['pilih_transfer'] , 'Edit', $diff3);
                    
                  
                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
                }





            return 'success';
        } else {
            return 'double';
        }


           
       
        

}








   

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
             SELECT t_tvi_transfer.id as id,
                    t_tvi_transfer.nama_rekening as customer,
                    bank.name as bank,
                    t_tvi_transfer.nomor_rekening as nomor_rekening,
                    t_tvi_transfer.kode as kode,
                    t_tvi_transfer.tanggal as tanggal,
                    t_tvi_transfer.nomor_rekening as nomor_rekening,
                    t_tvi_transfer.nama_rekening as nama_rekening,
                    t_tvi_transfer.total as total_transfer

            FROM  t_tvi_transfer
            left join  bank on bank.id = t_tvi_transfer.bank_id
            where  t_tvi_transfer.id = $id and  t_tvi_transfer.project_id = $project->id
            order by t_tvi_transfer.id asc




            ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
        
        SELECT * 
        FROM t_tvi_pembayaran
            left join unit on  t_tvi_registrasi.unit_id = unit.id
            left join customer on unit.pemilik_customer_id = customer.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            where 

            t_tvi_pembayaran.id = $id
            kawasan.project_id = $project->id  
            


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
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.keterangan as keterangan,
                   t_tvi_registrasi.harga_paket as harga_paket,
                   t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                   t_tvi_registrasi.diskon  as diskon,
                   t_tvi_registrasi.total as total,

                    paket_tvi.description as keterangan_paket,
                    paket_tvi.name as jenis_paket
                    
             
            FROM t_tvi_pembayaran
            left join t_tvi_tagihan on t_tvi_pembayaran.tagihan_id = t_tvi_tagihan.id
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
            left join unit on unit.id = t_tvi_registrasi.unit_id
            left join customer on unit.pemilik_customer_id = customer.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            where 
            t_tvi_pembayaran.id = $id and
            kawasan.project_id = $project->id  
            


        ");
        $row = $query->row();

        return $row;
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





    public function get_log_transfer($id)
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
        $this->db->from('t_tvi_posting');

       

       $data =
        [
            'pilih_transfer' => $dataTmp['pilih_transfer'],
            'customer' => $dataTmp['customer'],
            'project_id'    => $project->id,
            'kode_transfer' => $dataTmp['kode_transfer'],
            'tanggal' => $dataTmp['tanggal'],
            'bank' => $dataTmp['bank'],
            'nomor_rekening' => $dataTmp['nomor_rekening'],
            'nama_rekening' => $dataTmp['nama_rekening'],
            'total_bayar' => $this->m_core->currency_to_number($dataTmp['total_bayar']),
            'total_transfer' => $this->m_core->currency_to_number($dataTmp['total_transfer']),
            'keterangan' => $dataTmp['keterangan'],
            'active' => $dataTmp['active'] ? 1 : 0,

            
        ];






        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('kode_transfer', $data['kode_transfer'])->where('id !=', $dataTmp['id']);
            $this->db->from('t_tvi_posting');
            // validasi double
            if ($this->db->count_all_results() == 0) {
                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('t_tvi_posting', $data);
                $after = $this->get_log($dataTmp['id']);

                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                if ($tmpDiff) {
                    $this->m_log->log_save('t_tvi_posting', $dataTmp['id'], 'Edit', $diff);

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
            $this->db->update('t_tvi_posting', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('t_tvi_posting', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
