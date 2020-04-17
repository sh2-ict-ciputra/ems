<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_kontrak_sewa extends CI_Model
{
    public function get()
    {
        $query = $this->db->query('
            SELECT * FROM cara_pembayaran
        ');

        return $query->result_array();
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
                   unit.id as unit_id,
                   unit.*,
                   customer.name as customer_name,
                   customer.code as customer_code,
                   customer.homephone as customer_homephone,
                   customer.mobilephone1 as customer_mobilephone,
                   customer.email as customer_email,
                   customer.id as customer_id

            FROM unit 
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            left join customer on customer.id = unit.pemilik_customer_id
            where kawasan.project_id = $project->id and unit.id = $id


             ");

        return $query->row();
    }



    public function getCustomer()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT *
            FROM customer          
            WHERE project_id = $project->id

             ");

        return $query->result_array();
    }



    public function getCustomer2($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                   customer.name as customer_name,
                   customer.code as customer_code,
                   customer.homephone as customer_homephone,
                   customer.mobilephone1 as customer_mobilephone,
                   customer.email as customer_email,
                   customer.id as customer_id

            FROM customer  
            where  project_id = $project->id and id = $id          


             ");

        return $query->row();
    }


       public function getNomorRegistrasiNonUnit( $customer_id)
    {

        $project = $this->m_core->project();

    


         
                $query = $this->db->query("
           


                 SELECT TOP 1 id, nomor_registrasi
                   
                    
             
                FROM t_tvi_registrasi
                where t_tvi_registrasi.customer_id = $customer_id and t_tvi_registrasi.project_id = $project->id   and t_tvi_registrasi.unit = 'non_unit'  order by id desc 
            


                ");


                $row = $query->row();


                if (isset($row)) {


                 
                    $nomor_registrasi  = $row->nomor_registrasi;
                }

                else


                {


                    $nomor_registrasi = "Auto Generate";

                }




               return $nomor_registrasi;
    


   }


   public function getAktifasiNonUnit($customer_id)
   {

       $project = $this->m_core->project();

    


        
               $query = $this->db->query("
          


                SELECT TOP 1 tanggal
                  
                   
            
               FROM t_tvi_tagihan
               LEFT JOIN t_tvi_registrasi on t_tvi_registrasi.id = t_tvi_tagihan.registrasi_id
               where t_tvi_registrasi.customer_id = $customer_id and t_tvi_tagihan.project_id = $project->id and t_tvi_tagihan.flag_type = 3 and t_tvi_registrasi.unit = 'non_unit'
               order by t_tvi_tagihan.id desc 
           


               ");


               $row = $query->row();


               if (isset($row)) {


                  
                   $tanggal  = $row->tanggal;
               }



               $last_day = date('Y-m-t', strtotime($tanggal));



               $tanggal_aktifasi =  date('d-m-Y', strtotime('+1 day', strtotime($last_day)));


              


               return $tanggal_aktifasi;

    



  }





         public function getNomorRegistrasiUnit($unit_id)
    {

        $project = $this->m_core->project();

     


         
                $query = $this->db->query("
           


                 SELECT TOP 1 id, nomor_registrasi
                   
                    
             
                FROM t_tvi_registrasi
                where t_tvi_registrasi.unit_id = $unit_id and t_tvi_registrasi.project_id = $project->id   
                order by id desc 
            


                ");


                $row = $query->row();


                if (isset($row)) {


                   
                    $nomor_registrasi  = $row->nomor_registrasi;
                }


                else


                {


                  $nomor_registrasi = "Auto Generate";


                }



                return $nomor_registrasi;

     



   }


   
   public function getAktifasiUnit($unit_id)
   {

       $project = $this->m_core->project();

    


        
               $query = $this->db->query("
          


                SELECT TOP 1  tanggal
                  
                   
            
               FROM t_tvi_tagihan
               LEFT JOIN t_tvi_registrasi on t_tvi_registrasi.id = t_tvi_tagihan.registrasi_id
               where t_tvi_registrasi.unit_id = $unit_id and t_tvi_tagihan.project_id = $project->id and t_tvi_tagihan.flag_type = 3  
               order by t_tvi_tagihan.id desc 
           


               ");


               $row = $query->row();


               if (isset($row)) {


                  
                   $tanggal  = $row->tanggal;
               }


               $last_day = date('Y-m-t', strtotime($tanggal));



               $tanggal_aktifasi =  date('d-m-Y', strtotime('+1 day', strtotime($last_day)));


              


               return $tanggal_aktifasi;

    



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


     public function getJenisPaket($jenis_bayar)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 

                   paket_tvi.id as id,
                   paket_tvi.harga_jual as harga_jual,
                   paket_tvi.description as description,
                   paket_tvi.biaya_pasang_baru as biaya_pasang_baru,
                   paket_tvi.biaya_registrasi as biaya_registrasi,
                   paket_tvi.bandwidth as bandwidth,
                   paket_tvi.name as name


            FROM paket_tvi 
            LEFT JOIN group_tvi on group_tvi.id = paket_tvi.group_tvi_id  
            where group_tvi.project_id = $project->id and group_tvi.jenis_bayar = '$jenis_bayar'      


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
                   paket_tvi.biaya_registrasi as biaya_registrasi,
                   paket_tvi.bandwidth as bandwidth,
                   paket_tvi.description as paket_description,
                   paket_tvi.id as paket_id,
                   group_tvi.jumlah_hari as jumlah_hari

            FROM paket_tvi      
            join group_tvi  on paket_tvi.group_tvi_id = group_tvi.id where  group_tvi.project_id = $project->id and paket_tvi.id = $id      

             ");

        return $query->row();
    }


    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = [
            'unit_id'           => $dataTmp['unit_id'],
            'project_id'        => $project->id,
            'tanggal_document'  => $dataTmp['tanggal_document'],
            'customer_id'       => $dataTmp['customer_id'],
            'jenis_bayar'       => $dataTmp['jenis_bayar'],
            'jenis_sewa'        => $dataTmp['jenis_sewa'],
            'alamat'            => $dataTmp['alamat'],
            'nohp'              => $dataTmp['nohp'],
            'biaya_sewa'        => $dataTmp['biaya_sewa'],
            'deposit_sewa'      => $dataTmp['deposit_sewa'],
            'tanggal_sewa'      => $dataTmp['tanggal_sewa'],
            'batas_sewa'        => $dataTmp['batas_sewa']
        ];
		
		
		$data_range_bangunan = [
            'range_awal'       =>  $dataTmp['range_awal'], 
            'range_akhir'      =>  $dataTmp['range_akhir'],  
            'harga_hpp'        =>  $dataTmp['harga_hpp'],  
			'harga'            =>  $dataTmp['harga_range'],  
            'flag_jenis'       => 0,
            'active'           => 1,
            'delete'           => 0
        ];
		
		
		
		$data_range_kavling = [
            'range_awal'       => $dataTmp['range_awal2'],   
            'range_akhir'      => $dataTmp['range_akhir2'],  
            'harga_hpp'        => $dataTmp['harga_hpp2'],  
			'harga'            => $dataTmp['harga_range2'],  
            'flag_jenis'       => 1,
            'active'           => 1,
            'delete'           => 0
        ];
		
		
		
		//var_dump($data_range_detail);
	

        $this->db->where('code', $data['code']);
        $this->db->from('range_lingkungan');

        // validasi double
        if($this->db->count_all_results()==0){ 

                    $this->db->insert('range_lingkungan', $data);
                    $id = $this->db->insert_id();
                   	$dataLog = $this->get_log($id);
                   // $this->m_log->log_save('range_lingkungan',$id,'Tambah',$dataLog);
					
					//echo("<pre>");
					//print_r($dataTmp);
					//echo("</pre>");
					
					
					
					if (isset($dataTmp['range_awal']))
					{
					for($i= 0;$i<=count($dataTmp['range_awal'])-1;$i++) {
						
                        $range_awal[$i]  =  $this->m_core->currency_to_number($dataTmp['range_awal'][$i]);
                        $range_akhir[$i] = $this->m_core->currency_to_number($dataTmp['range_akhir'][$i]); 
                        $harga_hpp[$i]   =  $this->m_core->currency_to_number($dataTmp['harga_hpp'][$i]);  
						$harga_range[$i] =  $this->m_core->currency_to_number($dataTmp['harga_range'][$i]); 
                        
                        
                        $data_range_bangunan = [
                            'range_lingkungan_id'      => $id, 
                            'range_awal'               => $range_awal[$i],
                            'range_akhir'              => $range_akhir[$i],
                            'harga_hpp'                => $harga_hpp[$i],
							'harga'                    => $harga_range[$i],
                            'flag_jenis'               => 0,
                            'active'                   => 1,
                            'delete'                   => 0
                            
                        ];
                            
                        $this->db->insert('range_lingkungan_detail', $data_range_bangunan);
                        $dataLog = $this->get_log_detail($this->db->insert_id());
                         $this->m_log->log_save('range_lingkungan_detail',$this->db->insert_id(),'Tambah',$dataLog);

					}
					}
					
					if (isset($dataTmp['range_awal2']))
					{
					for($i= 0;$i<=count($dataTmp['range_awal2'])-1;$i++) {
						
                        $range_awal2[$i]  =  $this->m_core->currency_to_number($dataTmp['range_awal2'][$i]); 
                        $range_akhir2[$i] =  $this->m_core->currency_to_number($dataTmp['range_akhir2'][$i]);  
                        $harga_hpp2[$i]   =  $this->m_core->currency_to_number($dataTmp['harga_hpp2'][$i]);  
						$harga_range2[$i] =  $this->m_core->currency_to_number($dataTmp['harga_range2'][$i]); 
                        
                        
                        $data_range_kavling = [
                            'range_lingkungan_id'      => $id, 
                            'range_awal'               => $range_awal2[$i],
                            'range_akhir'              => $range_akhir2[$i],
                            'harga_hpp'                => $harga_hpp2[$i],
							'harga'                    => $harga_range2[$i],
                            'flag_jenis'               => 1,
                            'active'                   => 1,
                            'delete'                   => 0
                           
                        ];
                            
                        $this->db->insert('range_lingkungan_detail', $data_range_kavling);
                        $dataLog = $this->get_log_detail($this->db->insert_id());
                        $this->m_log->log_save('range_lingkungan_detail',$this->db->insert_id(),'Tambah',$dataLog);

					}
					}
					
            return 'success';
        }else return 'double';
        
        
    }


    public function last_id()
    {
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_tvi_registrasi
            ORDER by id desc
        ");
        return $query->row() ? $query->row()->id : 0;
    }


    public function last_id_tagihan()
    {
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_tvi_tagihan
            ORDER by id desc
        ");
        return $query->row() ? $query->row()->id : 0;
    }

    public function getSPK()
    {
        $query = $this->db->query("
            SELECT * FROM spk
        ");
        return $query->result();
    }



    public function getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
           SELECT 
                  
                   

                   t_tvi_registrasi.customer_name as customer,
                   t_tvi_registrasi.id as id,
                   case when t_tvi_registrasi.jenis_pemasangan ='1' then 'Pemasangan Baru' else 'Perpanjangan Paket' end as jenis_layanan, 
                   t_tvi_registrasi.tanggal_document as tanggal_document,
                   t_tvi_registrasi.tanggal_pemasangan_mulai as tanggal_pemasangan_mulai,
                   t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.keterangan as keterangan,
                   t_tvi_registrasi.harga_paket as harga_paket,
                   t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                   t_tvi_registrasi.diskon  as diskon,
                   t_tvi_registrasi.total as total,
                   CASE
                   WHEN t_tvi_registrasi.status_bayar = 1 THEN
                   'Lunas' 
                   WHEN t_tvi_registrasi.status_bayar = 2 THEN
                   'Pemutihan' 
                   WHEN t_tvi_registrasi.status_bayar = 0 THEN
                   'Tagihan'                   
                   END AS status_bayar,

                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.unit as unit,
                   CASE
                   WHEN t_tvi_biaya_tambahan.status_bayar = 1 THEN
                   'Lunas' 
                   WHEN t_tvi_biaya_tambahan.status_bayar = 2 THEN
                   'Pemutihan' 
                   WHEN t_tvi_biaya_tambahan.status_bayar = 0 THEN
                   'Tagihan'                   
                   END AS status_bayar_biaya,


                    paket_tvi.description as keterangan_paket,
                    paket_tvi.name as jenis_paket
                    
             
            FROM t_tvi_registrasi
            left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
            left join t_tvi_biaya_tambahan on t_tvi_biaya_tambahan.registrasi_id = t_tvi_registrasi.id
          
            where  t_tvi_registrasi.[delete] = 0 and
            t_tvi_registrasi.project_id = $project->id 
            order by t_tvi_registrasi.id desc 
            
        ");

        return $query->result_array();
    }





    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
             SELECT 
                  
                   

                   t_tvi_registrasi.customer_name as customer,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.tanggal_document as tanggal_document,
                   t_tvi_registrasi.tanggal_pemasangan_mulai as tanggal_pemasangan_mulai,
                   t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.keterangan as keterangan,
                   t_tvi_registrasi.harga_paket as harga_paket,
                   t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                   t_tvi_registrasi.diskon  as diskon,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.unit as unit,


                    paket_tvi.description as keterangan_paket,
                    paket_tvi.name as jenis_paket
                    
             
            FROM t_tvi_registrasi
            left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
          
            where 
            t_tvi_registrasi.project_id = $project->id and t_tvi_registrasi.id = $id
            order by t_tvi_registrasi.id desc 
            


            ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
        
        SELECT     project.name as project,
                   kawasan.name as kawasan,
                   blok.name as blok,
                   unit.no_unit as unit,
                   unit.id as unit_id,
                   t_tvi_registrasi.customer_name as customer,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_handphone as no_hp,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_registrasi.nomor_telepon as telepon,
                   t_tvi_registrasi.id_tagihan as id_tagihan,


                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_document as tanggal_document,
                   t_tvi_registrasi.tanggal_pemasangan_mulai as tanggal_pemasangan_mulai,
                   t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.keterangan as keterangan,
                   t_tvi_registrasi.harga_paket as harga_paket,
                   t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                   t_tvi_registrasi.biaya_registrasi  as biaya_registrasi,
                   t_tvi_registrasi.diskon  as diskon,
                   t_tvi_registrasi.total as total,

                    paket_tvi.description as keterangan_paket,
                    paket_tvi.name as jenis_paket,

                    customer.id as customer_id,
                    paket_tvi.bandwidth as bandwidth





        FROM t_tvi_registrasi
            left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
            left join unit on unit.id = t_tvi_registrasi.unit_id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id
            left join customer on unit.pemilik_customer_id = customer.id 
            where 

            t_tvi_registrasi.id = $id and
            kawasan.project_id = $project->id  
            


        ");
        $row = $query->row();

        return $row;
    }


    public function getSelectNonUnit($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
        
       SELECT 
                  
                   

                   t_tvi_registrasi.customer_name as customer,
                   t_tvi_registrasi.email as email,
                   t_tvi_registrasi.nomor_handphone as no_hp,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   t_tvi_registrasi.nomor_telepon as telepon,
                   t_tvi_registrasi.id_tagihan as id_tagihan,


                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_document as tanggal_document,
                   t_tvi_registrasi.tanggal_pemasangan_mulai as tanggal_pemasangan_mulai,
                   t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                   t_tvi_registrasi.jenis_paket_id as jenis_paket_id,
                   t_tvi_registrasi.keterangan as keterangan,
                   t_tvi_registrasi.harga_paket as harga_paket,
                   t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                   t_tvi_registrasi.biaya_registrasi  as biaya_registrasi,
                   t_tvi_registrasi.diskon  as diskon,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.unit as unit,


                    paket_tvi.description as keterangan_paket,
                    paket_tvi.name as jenis_paket,
                    paket_tvi.bandwidth as bandwidth
                    
             
            FROM t_tvi_registrasi
            left join paket_tvi on t_tvi_registrasi.jenis_paket_id = paket_tvi.id
          
            where 
            t_tvi_registrasi.project_id = $project->id  and t_tvi_registrasi.id = $id
            order by t_tvi_registrasi.id asc


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
            left join t_tvi_tagihan on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
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





    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();





        $paket = explode('|', $dataTmp['jenis_paket_id']);





        $this->db->where('project_id', $project->id);
        $this->db->from('t_tvi_registrasi');
        // echo("<pre>");
        //     print_r($dataTmp);
        // echo("</pre>");
        
        $dataUnit =
            [


                'jenis_pemasangan' => $dataTmp['jenis_pemasangan'],
                'nomor_registrasi' => $dataTmp['nomor_registrasi'],
                'tanggal_document' => $dataTmp['tanggal_document'],
                'tanggal_pemasangan_mulai' => $dataTmp['tanggal_pemasangan_mulai'],
                'jenis_paket_id' => $paket[0],
                'harga_paket' =>  $this->m_core->currency_to_number($dataTmp['harga_paket']),
                'harga_lain_lain' =>  $this->m_core->currency_to_number($dataTmp['harga_pasang']),
                'biaya_registrasi' => $this->m_core->currency_to_number($dataTmp['biaya_registrasi']),
                'diskon' => $dataTmp['diskon'],
                'total' =>  $this->m_core->currency_to_number($dataTmp['total']),
                'keterangan' => $dataTmp['keterangan'],
                'status_bayar' => 0,
                'active' => 0,
                'delete' => 0,


            ];



        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {


            $this->db->where('nomor_registrasi', $dataTmp['nomor_registrasi'])->where('id !=', $dataTmp['id']);
            $this->db->from('t_tvi_registrasi');
            // validasi double
            if ($this->db->count_all_results() == 0) {




                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('t_tvi_registrasi', $dataUnit);
                $after = $this->get_log($dataTmp['id']);




                $id = $dataTmp['id'];

                // echo '<PRE>';
                // print_r($id);
                // echo '<PRE>';


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



                $dataTagihan =
                    [


                        'project_id'    => $project->id,
                        'tanggal' =>  $dataTmp['tanggal_document'],
                        'jenis_paket_id' => $paket[0],
                        'total' => $this->m_core->currency_to_number($dataTmp['total']),
                        'registrasi_id' => $dataTmp['id'],
                        'status_bayar' => 0,
                        'active' => 1,
                        'delete' => 0,
                    ];



                $beforeTagihan = $this->get_log_tagihan($tagihan_id);
                $this->db->where('id', $tagihan_id);
                $this->db->update('t_tvi_tagihan', $dataTagihan);
                $afterTagihan = $this->get_log_tagihan($tagihan_id);

                $diff = (object)(array_diff_assoc((array)$after, (array)$before));
                $tmpDiff = (array)$diff;



                $diff2 = (object)(array_diff_assoc((array)$afterTagihan, (array)$beforeTagihan));
                $tmpDiff2 = (array)$diff2;


                if ($tmpDiff) {
                    $this->m_log->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);



                    return 'success';
                } else if ($tmpDiff2) {


                    $this->m_log->log_save('t_tvi_tagihan', $tagihan_id, 'Edit', $diff2);


                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
                }
            } else { }
        }
    }



    public function edit_non_unit($dataTmp)
    {

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();



        $paket = explode('|', $dataTmp['jenis_paket_id']);






        $dataNonUnit =
            [



                'jenis_pemasangan' => $dataTmp['jenis_pemasangan'],
                'nomor_registrasi' => $dataTmp['nomor_registrasi2'],
                'project_id'      => $project->id,
                'tanggal_document' => $dataTmp['tanggal_document'],
                'jenis_paket_id' => $paket[0],
                'harga_paket' => $this->m_core->currency_to_number($dataTmp['harga_paket']),
                'harga_lain_lain' => $this->m_core->currency_to_number($dataTmp['harga_pasang']),
                'biaya_registrasi' => $this->m_core->currency_to_number($dataTmp['biaya_registrasi']),
                'diskon' => $this->m_core->currency_to_number($dataTmp['diskon']),
                'total' => $this->m_core->currency_to_number($dataTmp['total']),
                'keterangan' => $dataTmp['keterangan'],
                'status_bayar' => 0,
                'active' => 0,
                'delete' => 0,


            ];


        $this->db->where('nomor_registrasi', $dataTmp['nomor_registrasi2'])->where('id', $dataTmp['id']);
        $this->db->from('t_tvi_registrasi');


        // echo '<PRE>';
        // print_r($this->db->count_all_results());
        // echo '</PRE>';

        // validasi double
        if ($this->db->count_all_results() > 0) {




            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('t_tvi_registrasi', $dataNonUnit);
            $after = $this->get_log($dataTmp['id']);




            $id = $dataTmp['id'];


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


           



            $dataTagihan =
                [


                    'project_id'    => $project->id,
                    'tanggal' =>  $dataTmp['tanggal_document'],
                    'jenis_paket_id' => $paket[0],
                    'total' => $this->m_core->currency_to_number($dataTmp['total']),
                    'registrasi_id' => $id,
                    'status_bayar' => 0,
                    'active' => 1,
                    'delete' => 0,
                ];





            $beforeTagihan = $this->get_log_tagihan($tagihan_id);
            $this->db->where('id', $tagihan_id);
            $this->db->update('t_tvi_tagihan', $dataTagihan);
            $afterTagihan = $this->get_log_tagihan($tagihan_id);

            $diff = (object)(array_diff_assoc((array)$after, (array)$before));
            $tmpDiff = (array)$diff;



            $diff2 = (object)(array_diff_assoc((array)$afterTagihan, (array)$beforeTagihan));
            $tmpDiff2 = (array)$diff2;


            if ($tmpDiff) {
                $this->m_log->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);



                return 'success';
            } else if ($tmpDiff2) {


                $this->m_log->log_save('t_tvi_tagihan', $tagihan_id, 'Edit', $diff2);


                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }


            echo '<PRE>';
            print_r($tmpDiff);
            echo '</PRE>';


            echo '<PRE>';
            print_r($tmpDiff2);
            echo '</PRE>';
        } else {
            return 'double';
        }
    }




    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();


        $this->db->where('project_id', $project->id);
        $this->db->from('t_tvi_registrasi');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('t_tvi_registrasi', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object)(array_diff((array)$after, (array)$before));
            $tmpDiff = (array)$diff;

            if ($tmpDiff) {
                $this->m_log->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
