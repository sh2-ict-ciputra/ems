<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_desktop_transaksi_air extends CI_Model
{

    public function get()
    {
        $query = $this->db->query("SELECT * FROM PT");
        return $query->result_array();
    }
    public function getAll()
    {
        $query = $this->db->query("
            Select 
                pt.*,city.name as cityName, 
                city.zipcode, 
                province.name as provinceName, 
                country.name as countryName 
            FROM pt
            LEFT JOIN city
                on city.id = pt.city_id
            LEFT JOIN province
                on province.id = city.province_id
            LEFT JOIN country 
                on country.id = province.country_id
        ");
        return $query->result_array();
    }
    public function save($project_id, $data_id, $source)
    {
        // var_dump($project_id);
        // echo ini_get('memory_limit');
        // echo ini_get('sqlsrv.ClientBufferMaxKBSize');
        // echo ini_get('pdo_sqlsrv.client_buffer_max_kb_size');
        ini_set('memory_limit', '256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize', '224288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '224288'); // Setting to 512M - for pdo_sqlsrv
        // echo ini_get('memory_limit');
        // echo ini_get('sqlsrv.ClientBufferMaxKBSize');
        // echo ini_get('pdo_sqlsrv.client_buffer_max_kb_size');
        if ($source) {
            $this->db->trans_start();

            $dataTagihanAir = $this->db
                ->select("
                                    '$project_id' as proyek_id,
                                    unit.id as unit_id,
                                    td_air.nomor as kode_tagihan,
                                    td_air.periode as periode,
                                    td_air.nilai_denda as denda,
                                    td_air.nilai_pakai as tagihan,
                                    td_air.nilai_disc as diskon_master,
                                    td_air.nilai_admin as administrasi,
                                    (td_air.meter_akhir) - (td_air.meter_awal) as pemakaian,
                                    td_air.td_air_id as source_id,
                                    '$source' as source_table,
                                    CASE
                                        WHEN td_air.tanggal_bayar is null THEN '0'
                                        ELSE '1'
                                    END as status_bayar_flag
                            ")
                ->from("$source.dbo.td_air")
                ->join(
                    "$source.dbo.th_trans",
                    "th_trans.th_trans_id = td_air.th_trans_id"
                )
                ->join(
                    "$source.dbo.m_custwtp",
                    "th_trans.cust_id = m_custwtp.cust_id"
                )
                ->join(
                    'unit',
                    "unit.source_table = '$source.m_custwtp'
                                    AND unit.source_id = m_custwtp.cust_id"
                )
                ->join(
                    "blok",
                    "blok.id = unit.blok_id"
                )
                ->join(
                    "kawasan",
                    "kawasan.id = blok.kawasan_id"
                )
                ->join(
                    "customer",
                    "customer.id = unit.pemilik_customer_id"
                )
                ->join(
                    "t_tagihan_air",
                    "t_tagihan_air.source_table = '$source'
                                    AND t_tagihan_air.source_id = td_air.td_air_id",
                    "LEFT"
                )
                // ->join("$source.dbo.m_rangebankav",
                //         "m_rangebankav.range_id = m_custwtp.rangebankav_id")
                ->where("t_tagihan_air.id is null")
                // ->where_in('td_air.td_air_id',$data_id)
                //     ->limit(1000)
                ->get()->result();

            if ($dataTagihanAir)
                $this->db->insert_batch("t_tagihan_air", $dataTagihanAir);
            $this->db->trans_complete();

            $data = [];
            return  [
                "success" => true,
                "message" => "Berhasil, Jumlah data di input:" . count($data)
            ];
        }
    }
    public function get_ajax_desktop_transaksi_by_source($source)
    {

        if ($source) {
            $data = $this->db
                ->select("
                        td_air.td_air_id as id,
                        kawasan.name as kawasan,
                        blok.name as blok,
                        unit.no_unit,
                        customer.name as pemilik,
                        CONCAT(RIGHT('0' + RTRIM(MONTH(td_air.periode)), 2) ,'-',YEAR(td_air.periode)) as periode,
                        td_air.nilai_denda as denda,
                        td_air.nilai_pakai as tagihan,
                        CASE td_air.status_id 
                            WHEN 1 THEN 'Tagihan'
                            WHEN 2 THEN 'Terbayar'
                            WHEN 3 THEN 'Pemutihan'
                            WHEN 4 THEN 'Dibatalkan'
                            ELSE ''
                        END as status
                    ")
                ->from("$source.dbo.td_air")
                ->join(
                    "$source.dbo.th_trans",
                    "th_trans.th_trans_id = td_air.th_trans_id"
                )
                ->join(
                    "$source.dbo.m_custwtp",
                    "th_trans.cust_id = m_custwtp.cust_id"
                )
                ->join(
                    'unit',
                    "unit.source_table = '$source.m_custwtp'
                            AND unit.source_id = m_custwtp.cust_id"
                )
                ->join(
                    "blok",
                    "blok.id = unit.blok_id"
                )
                ->join(
                    "kawasan",
                    "kawasan.id = blok.kawasan_id"
                )
                ->join(
                    "customer",
                    "customer.id = unit.pemilik_customer_id"
                )
                ->join(
                    "t_tagihan_air",
                    "t_tagihan_air.source_table = '$source'
                            AND t_tagihan_air.source_id = td_air.td_air_id",
                    "LEFT"
                )
                // ->join("$source.dbo.m_rangebankav",
                //         "m_rangebankav.range_id = m_custwtp.rangebankav_id")
                ->where("t_tagihan_air.id is null")
                ->limit(950)
                ->get()->result();
            return $data;
        }
        return 0;
    }
    
    public function save2($project_id,$source,$user_id){
        $tagihan_air_tmp = $this->db->select("
                                                td_air.td_air_id,
                                                td_air.th_trans_id,
                                                td_air.periode,
                                                td_air.range_id,
                                                td_air.bayar_id,
                                                td_air.tanggal_bayar,
                                                td_air.Meter_awal,
                                                td_air.Meter_akhir,
                                                td_air.nilai_admin,
                                                td_air.nilai_denda,
                                                td_air.nilai_pakai,
                                                td_air.nilai_ppnair,
                                                td_air.nomor,
                                                td_air.nilai_ppnair,
                                                unit.id as unit_id,
                                                sub_golongan.id as sub_gol_id,
                                                sub_golongan.code as sub_gol_code,
                                                range_air.id as range_id,
                                                range_air.code as range_code,
                                                t_tagihan_air.id as tagihan_id 
                                                ")
                                            ->from("$source.dbo.td_air")
                                            ->join("$source.dbo.th_trans",
                                                    "th_trans.th_trans_id = td_air.th_trans_id")
                                            ->join("unit",
                                                    "unit.source_table = '$source'
                                                    AND unit.source_id = th_trans.cust_id")
                                            ->join("unit_air",
                                                    "unit_air.unit_id = unit.id")
                                            ->join("sub_golongan",
                                                    "sub_golongan.id = unit_air.sub_gol_id")
                                            ->join("range_air",
                                                    "range_air.id = sub_golongan.range_id")
                                            ->join("t_tagihan_air",
                                                    "t_tagihan_air.unit_id = unit.id
                                                    AND t_tagihan_air.periode = td_air.periode",
                                                    "LEFT")
                                            ->where("td_air.nilai_pakai != 0
                                                    OR (td_air.Meter_akhir - td_air.Meter_awal) > 0")
                                            ->order_by("unit_id")
                                            ->get()->result();
        var_dump(count($tagihan_air_tmp));
        $tagihan_air         = (object)[];
        $tagihan_air_detail  = (object)[];
        $tagihan_air_info    = (object)[];
        $meter_air           = (object)[];

        $tagihan_air->proyek_id             = $project_id;
        $tagihan_air_detail->source_table   = $source;
        $tagihan_air_detail->active         = 1;
        $tagihan_air_detail->user_id        = $user_id;
        $tagihan_air_detail->nilai_ppn      = 10;
        $tagihan_air_detail->nilai_flag     = 0;
        $tagihan_air_detail->nilai_denda_flag = 0;
        $meter_air->active                  = 1;
        $meter_air->delete                  = 0;
        

        foreach ($tagihan_air_tmp as $k=>$v) {
            $double = $v->tagihan_id?1:0;
            if($double == 0){

                $tagihan_air->unit_id = $v->unit_id;
                $tagihan_air->kode_tagihan = $v->nomor;
                $tagihan_air->periode = $v->periode;
                $tagihan_air->status_tagihan = $v->tanggal_bayar?1:0;            
                $this->db->insert("t_tagihan_air",$tagihan_air);
                $tagihan_air_detail->t_tagihan_air_id   = $this->db->insert_id();;
                $tagihan_air_detail->nilai              = $v->nilai_pakai;
                $tagihan_air_detail->nilai_administrasi = $v->nilai_admin;
                $tagihan_air_detail->nilai_denda        = $v->nilai_denda;
                $tagihan_air_detail->ppn_flag           = $v->nilai_ppnair?1:0;
                
                $tagihan_air_info->t_tagihan_air_id     = $tagihan_air_detail->t_tagihan_air_id;
                $tagihan_air_info->range_id             = $v->range_id;
                $tagihan_air_info->range_code           = $v->range_code;
                $tagihan_air_info->sub_golongan_id      = $v->sub_gol_id;
                $tagihan_air_info->sub_golongan_code    = $v->sub_gol_code;
                $tagihan_air_info->pemakaian            = ($v->Meter_akhir-$v->Meter_awal);
                
                $meter_air->unit_id = $tagihan_air->unit_id;
                $meter_air->periode = $tagihan_air->periode;
                $meter_air->meter   = $v->Meter_akhir;

                $this->db->insert("t_meter_air",$meter_air);
                $this->db->insert("t_tagihan_air_detail",$tagihan_air_detail);
                $this->db->insert("t_tagihan_air_info",$tagihan_air_info);
            }                
        }                                    
    }
}
