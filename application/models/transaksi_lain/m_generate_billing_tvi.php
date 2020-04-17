<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_generate_billing_tvi extends CI_Model
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
            Where t_tvi_registrasi.active = 1 and project_id =  $project->id





        ");

        return $query->result_array();
    }



 

     public function getKawasan()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("

            SELECT * FROM kawasan where project_id = $project->id

        ");

        return $query->result_array();
    }


    
    public function getCustomer()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("

            SELECT * FROM customer where project_id = $project->id

        ");

        return $query->result_array();
    }





     public function getBlok($id)
    {

        $project = $this->m_core->project();

        if($id=='all')
        {
                                       
           $query = $this->db->query('
            SELECT * FROM blok 

        ');

         }
        else
        {
                                        
          $query = $this->db->query("
            SELECT * FROM blok where kawasan_id = $id

        ");


        }


       return $query->result_array();
    }



    public function getUnit($kawasan, $blok)
    {

        $project = $this->m_core->project();

       if($kawasan=='all' and $blok=='all')
    {
        

        $query = $this->db->query("

            SELECT kawasan.name as kawasan,
                   blok.name as blok,
                   unit.no_unit as unit,
                   unit.id as unit_id,
                   customer.name as customer,
                   customer.email as email,
                   customer.mobilephone1 as no_hp


            FROM unit
            left join customer on unit.pemilik_customer_id = customer.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where kawasan.project_id = $project->id  

        ");


    }
    elseif($kawasan=='all' and $blok!='all')
    {
       


         $query = $this->db->query("


             SELECT kawasan.name as kawasan,
                   blok.name as blok,
                   unit.no_unit as unit,
                   unit.id as unit_id,
                   customer.name as customer,
                   customer.email as email,
                   customer.mobilephone1 as no_hp


            FROM unit
            left join customer on unit.pemilik_customer_id = customer.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where  blok.id = $blok and kawasan.project_id = $project->id  

        ");




    }
    elseif($kawasan!='all' and $blok=='all')
    {
       
        $query = $this->db->query("

         
          SELECT kawasan.name as kawasan,
                   blok.name as blok,
                   unit.no_unit as unit,
                   unit.id as unit_id,
                   customer.name as customer,
                   customer.email as email,
                   customer.mobilephone1 as no_hp


            FROM unit
            left join customer on unit.pemilik_customer_id = customer.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where  kawasan.id = $kawasan and  kawasan.project_id = $project->id  
      


        ");



    }
    elseif($kawasan!='all' and $blok!='all')
    {
           


           $query = $this->db->query("


           SELECT kawasan.name as kawasan,
                   blok.name as blok,
                   unit.no_unit as unit,
                   unit.id as unit_id,
                   customer.name as customer,
                   customer.email as email,
                   customer.mobilephone1 as no_hp


            FROM unit
            left join customer on unit.pemilik_customer_id = customer.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where kawasan.id = $kawasan and blok.id = $blok and kawasan.project_id = $project->id  

       


        ");





    }

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
                   t_tvi_pembayaran.total_bayar as total_bayar



            FROM  t_tvi_pembayaran
            left join t_tvi_tagihan on t_tvi_pembayaran.tagihan_id = t_tvi_tagihan.id
            left join t_tvi_registrasi on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            Where  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.id = $id 





        ");

        return $query->result_array();
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





   


  


     public function generate_billing($dataTmp)
    {


      // echo ('<pre>');

      // print_r($dataTmp);
      // echo('</pre>');

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

      
       
        $data =
        [
            'unit' => $dataTmp['unit'],
           
        ];


      
            

      
      //  $this->db->where('kode_bill', $dataTmp['nomor_billing']);
      //  $this->db->from('t_tvi_tagihan');

       //if ($this->db->count_all_results() == 0) {


        
          if (isset($dataTmp['unit']))
          {
          for($i= 0;$i<=count($dataTmp['unit'])-1;$i++) {
           
                        // $this->db->select('*');
                        // $this->db->from('t_tvi_registrasi');
                        // $this->db->where('unit', $dataTmp['unit'][$i] );
                        // $query = $this->db->get();

           $id = $dataTmp['unit'][$i];


                         echo('<PRE>');
              
                         print_r($dataTmp);

                         echo('</PRE>');



        $query = $this->db->query("



         SELECT  



                   
                   t_tvi_registrasi.customer_name as customer_name,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_document as tanggal,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as registrasi_id,
                   t_tvi_registrasi.unit_id as unit_id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.aktifasi as aktifasi
                            
                   




            FROM  t_tvi_registrasi
            Where  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.unit_id = $id





        ");

                        $row = $query->row();
                          
                         echo('<PRE>');
              
                         print_r($row);

                         echo('</PRE>');

                        if (isset($row) )
                        {
                       
                      

                          $registrasi_id      = $row->registrasi_id;
                          $unit_id            = $row->unit_id;    
                          $tanggal            = date('Y-m-d');
                          $jenis_pemasangan   = $row->jenis_pemasangan;
                          $jenis_paket        = $row->jenis_paket_id;
                          $total              = $row->total;
                          $kode_tagihan = "CG/TAGIHANTVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->
                          last_id()+1);   
                          $aktif = $row->aktifasi;
                          $status_bayar = $row->status_bayar;
                          $tanggal_berakhir  = $row->tanggal_berakhir;
                          $today = date('Y-m-d');

                        
                        $dataRegistrasi = [
                            'tanggal_document'         => $tanggal, 
                            'aktifasi'                 => 'tidak',
                            'status_bayar'             => 0,
                            'active'                   => 1,
                            'delete'                   => 0
                            
                        ];




                        $dataTagihan = [
                            'registrasi_id'            => $registrasi_id, 
                            'kode_bill'                => $kode_tagihan,
                            'jenis_paket_id'           => $jenis_paket,
                            'total'                    => $total,
                            'status_bayar'             => 0,
                            'project_id'               =>  $project->id,
                            'active'                   => 1,
                            'delete'                   => 0
                            
                        ];
                            

                        if (($today >=   $tanggal_berakhir ) and ($status_bayar=='finish') and ($aktif=='ya') )
                         {


                        $before = $this->get_log($registrasi_id);


                         $this->db->where('id', $registrasi_id);
                         $this->db->update('t_tvi_registrasi', $dataRegistrasi);
                        

                        $after = $this->get_log($registrasi_id);


                         $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                         $tmpDiff = (array) $diff;

               

                         $this->db->insert('t_tvi_tagihan', $dataTagihan);
                         $id = $this->db->insert_id();
                         $dataLog = $this->get_log_tagihan($this->db->insert_id());
                         $this->m_log->log_save('t_tvi_tagihan',$this->db->insert_id(),'Tambah',$dataLog);




                        if ($tmpDiff) {
                          $this->m_log->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);

                          //return 'success';
                        } else {
                         //return 'Tidak Ada Perubahan';
                         }



                      }


                    }


          }
          }


        

}



    public function last_id(){
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_tvi_tagihan
            ORDER by id desc
        ");
        return $query->row()?$query->row()->id:0;
    }


    public function getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                coa_mapping.*, 
                coa.code as coa_code, 
                coa.description as coa_name, 
                cara_pembayaran.*, 
                pt.name as ptName 
            FROM t_tvi_pembayaran
                join coa_mapping 
                    on cara_pembayaran.coa_mapping_id = coa_mapping.id
                join pt 
                    on pt.id = coa_mapping.pt_id
                join coa 
                    on coa.id = coa_mapping.coa_id
                WHERE cara_pembayaran.[delete] = 0 
                AND coa_mapping.project_id = $project->id
                ORDER BY cara_pembayaran.id desc
        ");

        return $query->result_array();
    }

   

   

    public function cek($id)
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
