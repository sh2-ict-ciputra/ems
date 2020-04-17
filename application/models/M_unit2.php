<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_unit extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query('
            SELECT * FROM unit where [delete] = 0 order by id desc
        ');

        return $query->result_array();
    }

    public function getAll()
    {
        $project = $this->m_core->project();
        $query = $this->db->query('
        SELECT 
            project.name as project_name,
			kawasan.name as kawasan_name,
            blok.name as blok_name,
			customer.name as customer_name,
            unit.*
        FROM  unit
            JOIN blok ON blok.id = unit.blok_id
            JOIN kawasan ON kawasan.id = blok.kawasan_id
			JOIN project ON project.id = kawasan.project_id
			JOIN customer ON customer.id = unit.pemilik_customer_id
        WHERE unit.[delete] = 0 order by unit.id desc
        ');

        return $query->result_array();
    }

    public function getStatusJual()
    {
        $project = $this->m_core->project();
        $query = $this->db->query('
        SELECT 
            status_jual
        FROM  unit
        WHERE [delete] = 0 order by unit.id desc
        ');

        return $query->result_array();
    }
    public function getSelectMetodePenagihan($id)
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            metode_penagihan_id as metode_penagihan
        FROM unit_metode_penagihan
        WHERE unit_id = $id  and [delete] = 0");

        return $query->result_array();
    }
    public function getSelect($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                kawasan.id as kawasan,
                blok.id as blok,
                unit.no_unit,
                unit.pemilik_customer_id as pemilik,
                unit.penghuni_customer_id as penghuni,
                unit.unit_type as jenis_unit,
                unit.purpose_use_id as produk_kategori,
                unit.gol_id as golongan,
                unit.status_tagihan,
                unit.luas_tanah,
                unit.luas_bangunan,
                unit.luas_taman,
                unit.virtual_account,
                unit.pt_id as pt,
                convert(varchar(20),unit.tgl_st,105) as tanggal_st,
                unit.diskon_flag,
                unit.kirim_tagihan as kirim_tagihan,
                unit.active as active,
                unit.status_jual as status_jual,
                
                air.aktif as air_meter_aktif,
                concat(DAY(air.tgl_aktif),'-',MONTH(air.tgl_aktif),'-',YEAR(air.tgl_aktif)) as air_tanggal_aktif,
                concat(DAY(air.tgl_putus),'-',MONTH(air.tgl_putus),'-',YEAR(air.tgl_putus)) as air_tanggal_putus,
                air.sub_gol_id as air_sub_golongan,
                air.meter_id as air_pemeliharaan_meter_air,
                air.nilai_penyambungan as air_nilai_penyambungan,
                air.angka_meter_sekarang as air_angka_meter_sekarang,
                air.barcode_meter as air_id_barcode_meter,
                air.no_seri_meter as air_no_meter_air,
                
                convert(varchar(20),pl.tgl_aktif,105) as pl_tangal_aktif,
                convert(varchar(20),pl.tgl_nonaktif,105) as pl_tangal_putus,
                pl.aktif as pl_aktif,
                pl.sub_gol_id as pl_sub_golongan,
                pl.tgl_mandiri as pl_tangal_mandiri,
                

                listrik.aktif as listrik_aktif,
                listrik.tgl_aktif as listrik_tanggal_aktif,
                listrik.tgl_putus as listrik_tanggal_putus,
                listrik.sub_gol_id as listrik_sub_golongan,
                listrik.meter_id as listrik_sewa_meter_listrik,
                listrik.no_seri_meter as listrik_nomor_seri_meter,
                listrik.angka_meter_sekarang as listrik_angka_meter_sekarang
            FROM unit
            join blok
                on blok.id = unit.blok_id
            join kawasan
                on kawasan.id = blok.kawasan_id
            LEFT join unit_air as air
                on air.unit_id = unit.id
            LEFT join unit_listrik as listrik
                on listrik.unit_id = unit.id
            LEFT join unit_lingkungan as pl
                on pl.unit_id = unit.id
            where unit.id = $id
            and kawasan.project_id = $project->id
        ");

        return $query->row();
    }

    public function getKawasan()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM kawasan where active =1 and project_id = $project->id   order by id asc
        ");

        return $query->result_array();
    }

    public function getUnit()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM unit where active =1 and project_id = $project->id   order by id asc
        ");

        return $query->result_array();
    }

    public function getBlok($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            FROM blok 
            where active = 1 and kawasan_id = $id 
            order by id asc
        ");

        return $query->result_array();
    }

    public function getCustomer()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM customer where active =1 and project_id = $project->id   order by id asc
        ");

        return $query->result_array();
    }

    public function getGolongan()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM jenis_golongan where active =1 and project_id = $project->id   order by id asc
        ");

        return $query->result_array();
    }

    public function get_sub_golongan($jenis_golongan_id)
    {
        $jenis_golongan_id = $jenis_golongan_id?$jenis_golongan_id:'0';
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                sub_golongan.*
            FROM sub_golongan 
            join jenis_golongan
                on jenis_golongan.id = sub_golongan.jenis_golongan_id
            where sub_golongan.active =1 
            and jenis_golongan.project_id = $project->id 
            and sub_golongan.jenis_golongan_id = $jenis_golongan_id   
            order by sub_golongan.id asc
        ");

        return $query->result_array();
    }

    public function getPT()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM PT 
            WHERE project_id = $project->id 
            and active = 1 
            and [delete] = 0
            order by id asc
        ");

        return $query->result_array();
    }

    public function getMetodePenagihan()
    {
        $project = $this->m_core->project();

        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM metode_penagihan
            WHERE project_id = $project->id 
            and active = 1 
            and [delete] = 0
            order by id asc
        ");

        return $query->result_array();
    }

    public function getProductCategory()
    {
        $query = $this->db->query("
            SELECT * FROM purpose_use where active =1 order by id asc
        ");

        return $query->result_array();
    }

    public function getPemeliharaanMeterAir()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM pemeliharaan_meter_air where active =1 and project_id = $project->id   order by id asc
        ");

        return $query->result_array();
    }

    public function getPemeliharaanMeterListrik()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM pemeliharaan_meter_listrik where active =1 and project_id = $project->id   order by id asc
        ");

        return $query->result_array();
    }

    public function getSubGolongan()
    {
        $project = $this->m_core->project();
        $query = $this->db->query('
            SELECT * FROM sub_golongan 
            where active = 1    
            order by id asc
        ');

        return $query->result_array();
    }
    public function getSubGolonganAir($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                sub_golongan.*
            FROM sub_golongan 
            join jenis_golongan
                on jenis_golongan.id = sub_golongan.jenis_golongan_id
            where sub_golongan.active =1 
            and sub_golongan.service_id = 1 
            and sub_golongan.jenis_golongan_id = $id   
            order by id asc
        ");

        return $query->result_array();
    }

    public function getSubGolonganLingkungan($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM sub_golongan where active =1 and service_id = 2 and gol_id = $id   order by id asc
        ");

        return $query->result_array();
    }

    public function getSubGolonganListrik($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM sub_golongan where active =1  and service_id = 3 and gol_id = $id   order by id asc
        ");

        return $query->result_array();
    }
    // public function getDiskon()
    // {
    //     $project = $this->m_core->project();
    //     $query = $this->db->query('
    //         SELECT 
    //             id,
    //             name
    //         FROM diskon
    //         where active = 1
    //         order by id asc
       
    //     ');

    //     return $query->result_array();
    // }
    public function getView()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                unit.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit as unit,
                unit.luas_tanah,
                unit.luas_bangunan,
                pemilik.name as pemilik,
                unit.active as active,
                unit.status_jual as status_jual,
                CASE kawasan.source_table 
                    WHEN '1' THEN 'EMS'
                    WHEN '2' THEN 'EREMS'
                    WHEN '3' THEN 'QS'
                    ELSE kawasan.source_table
                END as source
            FROM unit
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
            LEFT JOIN customer as pemilik
                ON pemilik.id = unit.pemilik_customer_id
                
            WHERE unit.[delete] = 0
            AND kawasan.project_id = $project->id
        ");

        return $query->result_array();
    }
    public function get_unit()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                unit.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit as unit,
                unit.luas_tanah,
                unit.luas_bangunan,
                pemilik.name as pemilik,
                unit.active as active,
                unit.status_jual as status_jual,
                CASE kawasan.source_table 
                    WHEN '1' THEN 'EMS'
                    WHEN '2' THEN 'EREMS'
                    WHEN '3' THEN 'QS'
                    ELSE kawasan.source_table
                END as source
            FROM unit
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
            LEFT JOIN customer as pemilik
                ON pemilik.id = unit.pemilik_customer_id
                
            WHERE unit.[delete] = 0
            AND kawasan.project_id = $project->id
        ");

        return $query->result();
    }
    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT
                kawasan.name as [Kawasan],
                blok.name as [Blok],
                unit.no_unit as [No Unit],
                pemilik.name as [Pemilik],
                penghuni.name as [Penghuni],
                CASE unit.unit_type
                    WHEN 1 THEN 'Rental'
                    WHEN 2 THEN 'Umum'
                    WHEN 3 THEN 'Unit Proyek'
                END as [Jenis Unit],
                CONCAT(jenis_golongan.code,' - ',jenis_golongan.description) as golongan,
                CASE unit.status_tagihan
                    WHEN 1 THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END as [Status Tagihan],
                REPLACE(CONVERT(varchar, CAST(unit.luas_tanah AS money), 1),'.00','') as [Luas Tanah],
                REPLACE(CONVERT(varchar, CAST(unit.luas_bangunan AS money), 1),'.00','') as [Luas Bangunan],
                REPLACE(CONVERT(varchar, CAST(unit.luas_taman AS money), 1),'.00','') as [Luas Taman],
                unit.virtual_account as [Virtual Account],
                pt.name as [PT],
                unit.tgl_st [Tanggal ST],
                CASE unit.kirim_tagihan
                    WHEN 1 THEN 'Pemilik'
                    WHEN 2 THEN 'Penghuni'
                    ELSE 'Pemilik dan Penghuni'
                END as [Kirim Tagihan],
                CASE unit.active
                    when 1 then 'Aktif'
                    else 'Tidak Aktif'
                end as [Status Unit],
                CASE unit.[delete]
                    when 1 then 'Terhapus'
                    else 'Tidak Terhapus'
                end as [Status Delete Unit],
                
                
                CASE unit_air.aktif
                    WHEN 1 THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END as [Informasi Air -> Aktif],
                unit_air.tgl_aktif as [Informasi Air -> Tanggal Aktif],
                unit_air.tgl_putus as [Informasi Air -> Tanggal Putus],
                sub_gol_air.name as [Informasi Air -> Sub Golongan],
                pemeliharaan_meter_air.code as [Informasi Air -> Kode Sewa Meter],
                unit_air.nilai_penyambungan as [Informasi Air -> Nilai Penyambungan],
                unit_air.angka_meter_sekarang as [Informasi Air -> Angka Meter Sekarang],
                unit_air.barcode_meter as [Informasi Air -> Barcode Meter],
                unit_air.no_seri_meter	as [Informasi Air -> No Meter Air],
                
                CASE unit_lingkungan.aktif
                    WHEN 1 THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END as [Informasi PL -> Aktif],
                unit_lingkungan.tgl_aktif as [Informasi PL -> Tanggal Aktif],
                unit_lingkungan.tgl_nonaktif as [Informasi PL -> Tanggal Putus],
                sub_gol_lingkungan.name as [Informasi PL -> Sub Golongan],
                unit_lingkungan.tgl_mandiri as [Informasi PL -> Tanggal Mandiri],
                
                CASE unit_listrik.aktif
                    WHEN 1 THEN 'Aktif'
                    ELSE 'Tidak Aktif'
                END as [Informasi Listrik -> Aktif],
                unit_listrik.tgl_aktif as [Informasi Listrik -> Tanggal Aktif],
                unit_listrik.tgl_putus as [Informasi Listrik -> Tanggal Putus],
                sub_gol_listrik.name as [Informasi Listrik -> Sub Golongan],
                pemeliharaan_meter_listrik.code as [Informasi Listrik -> Kode Sewa Meter],
                unit_listrik.no_seri_meter as [Informasi Listrik -> Nomor Seri Meter],
                unit_listrik.angka_meter_sekarang as [Informasi Listrik -> Angka Meter Sekarang]
                
                
            FROM unit
            
            LEFT JOIN blok
                ON blok.id = unit.blok_id
            LEFT JOIN kawasan
                ON kawasan.id = blok.kawasan_id
            LEFT JOIN customer as pemilik
                ON pemilik.id = unit.pemilik_customer_id
            LEFT JOIN customer as penghuni
                ON penghuni.id = unit.pemilik_customer_id	
            LEFT JOIN pt
                ON pt.id = unit.pt_id
            LEFT JOIN purpose_use
                ON purpose_use.id = unit.purpose_use_id
            LEFT JOIN jenis_golongan
                ON jenis_golongan.id = unit.gol_id

                
            LEFT JOIN unit_air
                ON unit_air.unit_id = unit.id
            LEFT JOIN pemeliharaan_meter_air
                ON pemeliharaan_meter_air.id = unit_air.meter_id
            LEFT JOIN sub_golongan as sub_gol_air
                ON sub_gol_air.id = unit_air.sub_gol_id
                
            LEFT JOIN unit_listrik
                ON unit_listrik.unit_id  = unit.id
            LEFT JOIN pemeliharaan_meter_listrik
                ON pemeliharaan_meter_listrik.id = unit_listrik.meter_id
            LEFT JOIN sub_golongan as sub_gol_listrik
                ON sub_gol_listrik.id = unit_listrik.sub_gol_id
                
            LEFT JOIN unit_lingkungan
                ON unit_lingkungan.unit_id  = unit.id
            LEFT JOIN sub_golongan as sub_gol_lingkungan
                ON sub_gol_lingkungan.id = unit_lingkungan.sub_gol_id
                
            WHERE unit.id = $id
            AND kawasan.project_id = $project->id
        ");
        $hasil = $query->row();
        // echo("hahaha");
        // echo($id);
        // echo($project->id);

        // echo('<pre>');
        //     print_r($hasil);
        // echo('</pre>');

        $query = $this->db->query("
            SELECT name FROM unit_metode_penagihan
            JOIN metode_penagihan 
                ON metode_penagihan.id = unit_metode_penagihan.metode_penagihan_id
            WHERE unit_metode_penagihan.unit_id = $id
        ");
        $row = $query->result_array();


        // echo('<pre>');
        //     print_r($row);
        // echo('</pre>');
        $metode_penagihan = '';
        $tmp = [];
        foreach ($row as $v) {
            array_push($tmp, $v["name"]);
        }
        // var_dump($tmp);
        $tmp = implode(', ', $tmp);
        $hasil = (array)$hasil;
        $hasil['Metode Penagihan -> Nama Metode yang di gunakan'] = $tmp;
        // echo('<pre>');
        //     print_r($hasil);
        // echo('</pre>');
        return $hasil;
    }
    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                unit.id
            FROM unit
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id	
            WHERE unit.id = $id
            AND unit.[delete] = 0
            AND kawasan.project_id = $project->id
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }





    public function save($dataTmp)
    {
        // echo('<pre>');
        //     print_r($dataTmp);
        // echo('</pre>');

        $this->load->model('m_core');
        $this->load->model('m_log');

        $project = $this->m_core->project();


        $dataUnit =
            [
                'project_id' => $project->id,
                'blok_id' => $dataTmp['blok'],
                'no_unit' => $dataTmp['no_unit'],
                'pemilik_customer_id' => $dataTmp['pemilik_customer_id'],
                'penghuni_customer_id' => $dataTmp['penghuni_customer_id'],
                'luas_tanah' => $this->m_core->currency_to_number($dataTmp['luas_tanah']),
                'luas_bangunan' =>  $this->m_core->currency_to_number($dataTmp['luas_bangunan']),
                'luas_taman' => $this->m_core->currency_to_number($dataTmp['luas_taman']),
                'unit_type' => $dataTmp['unit_type'],
                'purpose_use_id' => $dataTmp['product_category_id'],
                'status_tagihan' => $dataTmp['status_tagihan'],
                'virtual_account' => $dataTmp['virtual_account'],
                'gol_id' => $dataTmp['gol_id'],
                'pt_id' => $dataTmp['pt'],
                'diskon_flag' => $dataTmp['flag_diskon'],
                'kirim_tagihan' => $dataTmp['kirim_tagihan']?array_sum($dataTmp['kirim_tagihan']):0,
                'tgl_st' => $dataTmp['tgl_st'],
                'status_jual' => $dataTmp['status_jual'],
                'source_table' => 1,
                'active' => 1,
                'delete' => 0
            ];
        $dataUnitAir =
            [
                'aktif' => $dataTmp['air_aktif'],
                'tgl_aktif' => $dataTmp['tgl_aktif_air'],
                'tgl_putus' => $dataTmp['tgl_putus_air'],
                'meter_id' => $dataTmp['meter_air_id'],
                'nilai_penyambungan' => $dataTmp['nilai_penyambungan_air'],
                'sub_gol_id' => $dataTmp['sub_gol_air_id'],
                'angka_meter_sekarang' => $dataTmp['angka_meter_sekarang_air'],
                // 'barcode_meter' => $dataTmp['barcode_meter_air_id'],
                // 'no_seri_meter' => $dataTmp['no_seri_meter_air'],
            ];
        $dataUnitLingkungan =
            [
                'aktif' => $dataTmp['lingkungan_aktif'],
                'tgl_aktif' => $dataTmp['tgl_aktif_lingkungan'],
                'tgl_nonaktif' => $dataTmp['tgl_nonaktif_lingkungan'],
                'sub_gol_id' => $dataTmp['sub_gol_lingkungan_id'],
                'tgl_mandiri' => $dataTmp['tgl_mandiri_lingkungan'] ? $dataTmp['tgl_mandiri_lingkungan'] : null,
            ];
        $dataUnitListrik =
            [
                'aktif' => $dataTmp['listrik_aktif'],
                'tgl_aktif' => $dataTmp['tgl_aktif_listrik'],
                'tgl_putus' => $dataTmp['tgl_putus_listrik'],
                'angka_meter_sekarang' => $dataTmp['angka_meter_sekarang_listrik'],
                'meter_id' => $dataTmp['meter_listrik_id'],
                'sub_gol_id' => $dataTmp['sub_gol_listrik_id'],
                'no_seri_meter' => $dataTmp['no_seri_meter_listrik'],
            ];
        $dataUnitMetodePenagihan = $dataTmp['metode_tagihan'];


        $this->db->join('blok', 'unit.blok_id = blok.id');
        $this->db->join('kawasan', 'blok.kawasan_id = kawasan.id');
        $this->db->where('kawasan.project_id', $project->id);
        $this->db->where('unit.no_unit', $dataUnit['no_unit']);
        $this->db->where('unit.blok_id', $dataUnit['blok_id']);
        $this->db->from('unit');

        // validasi double
        // if ($this->db->count_all_results() == 0) {
        if (true) {
            $this->db->insert('unit', $dataUnit);
            $idTMP = $this->db->insert_id();

            $dataUnitAir['unit_id'] = $idTMP;
            $dataUnitLingkungan['unit_id'] = $idTMP;
            $dataUnitListrik['unit_id'] = $idTMP;
            //$dataUnitMetodePenagihan['unit_id'] = $idTMP;

            $this->db->insert('unit_air', $dataUnitAir);
            $this->db->insert('unit_lingkungan', $dataUnitLingkungan);
            $this->db->insert('unit_listrik', $dataUnitListrik);
            if($dataUnitMetodePenagihan){
                foreach ($dataUnitMetodePenagihan as $v) {
                    $this->db->insert('unit_metode_penagihan', [
                        'unit_id' => $idTMP,
                        'metode_penagihan_id' => $v
                    ]);
                }
            }
            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('unit', $idTMP, 'Tambah', $dataLog);
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

        $this->db->where('unit_id', $dataTmp['id']);
        $this->db->update('unit_metode_penagihan', ['delete' => 1]);

        //$this->save($dateTmp);

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        // $this->db->join('blok', 'unit.blok_id = blok.id');
        // $this->db->join('kawasan', 'blok.kawasan_id = kawasan.id');
        // $this->db->where('kawasan.project_id', $project->id);
        // $this->db->where('unit.no_unit', $dataTmp['no_unit']);
        // $this->db->where('unit.blok_id', $dataTmp['blok_id']);
        // $this->db->from('unit');


        $dataUnit =
            [
                'blok_id' => $dataTmp['blok_id'],
                'no_unit' => $dataTmp['no_unit'],
                'pemilik_customer_id' => $dataTmp['pemilik_customer_id'],
                'penghuni_customer_id'     => $dataTmp['penghuni_customer_id'],
                'unit_type' => $dataTmp['unit_type'],
                'purpose_use_id' => $dataTmp['product_category_id'],
                'gol_id' => $dataTmp['gol_id'],
                'status_tagihan' => $dataTmp['status_tagihan'] ? 1 : 0,
                'luas_tanah'     => $this->m_core->currency_to_number($dataTmp['luas_tanah']),
                'luas_bangunan'     =>  $this->m_core->currency_to_number($dataTmp['luas_bangunan']),
                'luas_taman'     =>  $this->m_core->currency_to_number($dataTmp['luas_taman']),
                'virtual_account' => $dataTmp['virtual_account'],
                'pt_id'     => $dataTmp['pt'],
                // 'tgl_st'     => $dataTmp['tgl_st'],
                'diskon_flag' => $dataTmp['diskon_flag'] ? 1 : 0,
                'kirim_tagihan' => $dataTmp['kirim_tagihan']?array_sum($dataTmp['kirim_tagihan']):0,
                'active' => $dataTmp['active'] ? 1 : 0,
                'delete' => 0,
                'status_jual'     => $dataTmp['status_jual'],
            ];


        $dataUnitAir =
            [
                'aktif' => $dataTmp['air_aktif'],
                'tgl_aktif' => $dataTmp['tgl_aktif_air'] ? $dataTmp['tgl_aktif_air'] : null,
                'tgl_putus' => $dataTmp['tgl_putus_air'] ? $dataTmp['tgl_putus_air'] : null,
                'meter_id' => $dataTmp['meter_air_id'],
                'nilai_penyambungan' => $dataTmp['nilai_penyambungan_air'],
                'sub_gol_id' => $dataTmp['sub_gol_air_id'],
                'angka_meter_sekarang' => $dataTmp['angka_meter_sekarang_air'],
                'barcode_meter' => $dataTmp['barcode_meter_air_id'],
                'no_seri_meter' => $dataTmp['no_seri_meter_air'],
                'unit_id' => $dataTmp['id']
            ];
  
        $dataUnitLingkungan =
            [
                'aktif' => $dataTmp['lingkungan_aktif'],
                'tgl_aktif' => $dataTmp['tgl_aktif_lingkungan'] ? substr($dataTmp['tgl_aktif_lingkungan'],6,4)."-".substr($dataTmp['tgl_aktif_lingkungan'],3,2)."-".substr($dataTmp['tgl_aktif_lingkungan'],0,2) : null,
                'tgl_nonaktif' => $dataTmp['tgl_nonaktif_lingkungan'] ? $dataTmp['tgl_nonaktif_lingkungan'] : null,
                'sub_gol_id' => $dataTmp['sub_gol_lingkungan_id'],
                'tgl_mandiri' => $dataTmp['tgl_mandiri_lingkungan'] ? $dataTmp['tgl_mandiri_lingkungan'] : null,
                'unit_id' => $dataTmp['id'],
            ];
        // $dataUnitListrik =
        //     [
        //         'aktif' => $dataTmp['listrik_aktif'],
        //         'tgl_aktif' => $dataTmp['tgl_aktif_listrik'],
        //         'tgl_putus' => $dataTmp['tgl_putus_listrik'],
        //         'angka_meter_sekarang' => $dataTmp['angka_meter_sekarang_listrik'],
        //         'meter_id' => $dataTmp['meter_listrik_id'],
        //         'sub_gol_id' => $dataTmp['sub_gol_listrik_id'],
        //         'no_seri_meter' => $dataTmp['no_seri_meter_listrik'],
        //     ];
        $dataSelectMetodePenagihan = $dataTmp['metode_tagihan'];








        //    echo '<pre>';
        //    print_r($dataUnitAir);
        //    echo '</pre>';
        //    var_dump($this->db->where('unit_id', $dataTmp['id'])->from("unit_air")->count_all_results());
        // die;
        // if ($this->db->count_all_results() != 0) {



            $this->db->where('id', $dataTmp['id']);
            $this->db->update('unit', $dataUnit);

            
            if($this->db->where('unit_id', $dataTmp['id'])->from("unit_air")->count_all_results() > 0){
                $this->db->where('unit_id', $dataTmp['id']);
                $this->db->update('unit_air', $dataUnitAir);    
            }else{
                $this->db->insert('unit_air', $dataUnitAir);    
            }
            if($this->db->where('unit_id', $dataTmp['id'])->from("unit_lingkungan")->count_all_results() > 0){
                $this->db->where('unit_id', $dataTmp['id']);
                $this->db->update('unit_lingkungan', $dataUnitLingkungan);
            }else{
                $this->db->insert('unit_lingkungan', $dataUnitLingkungan);
            }
            // $count = $this->db->select("id")
            //             ->from("unit_lingkungan")
            //             ->where("unit_lingkungan.unit_id",$dataTmp['id'])
            //             ->get()->row();
            // // var_dump($count);
            // if($count){
                
            // }else{
            // }
            

           
            // $this->db->where('unit_id', $dataTmp['id']);
            // $this->db->update('unit_listrik', $dataUnitListrik);

            // var_dump($dataUnitLingkungan);
            if($dataSelectMetodePenagihan){
                foreach ($dataSelectMetodePenagihan as $v) {
                    $this->db->insert('unit_metode_penagihan', [
                        'unit_id' =>  $dataTmp['id'],
                        'metode_penagihan_id' => $v,
                        'delete' => 0
                    ]);
                }
            }




            $after = $this->get_log($dataTmp['id']);
            $diff = (object)(array_diff_assoc((array)$after, (array)$before));
            $tmpDiff = (array)$diff;
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
                $this->m_log->log_save('unit', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            }
        // }
    }
}
