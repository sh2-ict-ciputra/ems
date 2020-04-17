<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_aktifasi_tvi extends CI_Model
{
    public function get()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



        SELECT
        t_tvi_registrasi.customer_name AS customer_name,
        paket_tvi.name AS paket_name,
        t_tvi_registrasi.jenis_pemasangan AS jenis_pemasangan,
        t_tvi_registrasi.tanggal_mulai AS tanggal_mulai,
        t_tvi_registrasi.tanggal_berakhir AS tanggal_berakhir,
        t_tvi_registrasi.id AS id,
        t_tvi_registrasi.active AS active,
    
    CASE
            
            WHEN t_tvi_registrasi.status_bayar = 0 THEN
            'Tagihan' ELSE 'Lunas' 
        END AS status_bayar,
    CASE
            WHEN t_tvi_biaya_tambahan.status_bayar = 1 THEN
            'Lunas' 
            WHEN t_tvi_biaya_tambahan.status_bayar is null THEN
            'Biaya Tambahan Belum Dibuat' 
            WHEN t_tvi_biaya_tambahan.status_bayar = 0 THEN
            'Tagihan' 
            
        END AS status_bayar_biaya 
    FROM
        t_tvi_registrasi
        LEFT JOIN paket_tvi ON paket_tvi.id = t_tvi_registrasi.jenis_paket_id
        LEFT JOIN t_tvi_biaya_tambahan ON t_tvi_biaya_tambahan.registrasi_id = t_tvi_registrasi.id 
    WHERE
        t_tvi_registrasi.[delete] = 0 
        AND t_tvi_registrasi.project_id =  1
    ORDER BY
        t_tvi_registrasi.id DESC




        ");

        return $query->result_array();
    }



     public function getRegistrasi($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("



         SELECT  
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.customer_name as customer_name,
                   paket_tvi.name as paket_name,
                   paket_tvi.name as group_tvi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan





            FROM  t_tvi_registrasi
            left join t_tvi_tagihan on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            Where  t_tvi_registrasi.project_id =  $project->id and t_tvi_registrasi.id = $id





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

      


        $dataRegistrasi =
        [
            
            'tanggal_pemasangan_berakhir' => $dataTmp['tanggal_pemasangan_berakhir'],
            'tanggal_mulai' => $dataTmp['tanggal_awal'],
            'tanggal_berakhir' => $dataTmp['tanggal_akhir'],            
            'active' => 1,
            'delete' => 0,
        ];


         $dataTagihan =
        [
            
            'tanggal_mulai' => $dataTmp['tanggal_awal'],
            'tanggal_berakhir' => $dataTmp['tanggal_akhir'],            
            'active' => 1,
            'delete' => 0,
        ];

               

      
        $this->db->where('nomor_registrasi', $dataTmp['nomor_registrasi']);
        $this->db->from('t_tvi_registrasi');

       if ($this->db->count_all_results() != 0) {
          
                $before = $this->get_log($dataTmp['id']);

                $this->db->where('id', $dataTmp['id']);
                $this->db->update('t_tvi_registrasi', $dataRegistrasi);


                 $after = $this->get_log($dataTmp['id']);


                 $beforetagihan = $this->get_log_tagihan($dataTmp['id_tagihan']);

                 
              
                $this->db->where('id', $dataTmp['id_tagihan']);
                $this->db->update('t_tvi_tagihan', $dataTagihan);

                $aftertagihan = $this->get_log_tagihan($dataTmp['id_tagihan']);

 
               


                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

              

                 $diff2 = (object) (array_diff_assoc((array) $aftertagihan, (array) $beforetagihan));
                 $tmpDiff2 = (array) $diff2;


               if ($tmpDiff)    {
                    $this->m_log->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);

                  

                    return 'success';
                } else if ($tmpDiff2)    {
                  

                    $this->m_log->log_save('t_tvi_tagihan', $dataTmp['id_tagihan'], 'Edit', $diff2);


                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
                }

            
        }


           
       
        

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
                   paket_tvi.name as group_tvi_id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_mulai as tanggal_mulai,
                   t_tvi_registrasi.tanggal_berakhir as tanggal_berakhir,
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.active as active,
                   t_tvi_registrasi.status_bayar as status_bayar,
                   t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                   group_tvi.jumlah_hari as jumlah_hari,
                   t_tvi_tagihan.id as id_tagihan





            FROM  t_tvi_registrasi
            left join t_tvi_tagihan on t_tvi_tagihan.registrasi_id = t_tvi_registrasi.id
            left join paket_tvi on paket_tvi.id = t_tvi_registrasi.jenis_paket_id
            left join group_tvi on group_tvi.id = paket_tvi.group_tvi_id
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
