<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_desktop_transaksi_lingkungan extends CI_Model
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
    public function save($project_id,$data_id,$source){
        // var_dump($project_id);
    //     echo("<pre>");
    //     print_r($source);
    // echo("</pre>");
        if($source){
            $this->db->trans_start();

            $dataTagihanLingkungan= $this->db
                                ->select("
                                    '$project_id' as proyek_id,
                                    unit.id as unit_id,
                                    td_lingkungan.nomor as kode_tagihan,
                                    CONCAT(YEAR(td_lingkungan.periode),'-',RIGHT('0'+RTRIM(MONTH(td_lingkungan.periode)),2),'-01') as periode,
                                    td_lingkungan.nilai_tanah as nilai_kavling,
                                    td_lingkungan.nilai_bangunan as nilai_bangunan,
                                    CASE td_lingkungan.nilai_ppn
                                        WHEN 0 THEN '0'
                                        ELSE '1'
                                    END as ppn_flag,
                                    CASE  
                                        WHEN td_lingkungan.tanggal_bayar IS NULL THEN '0'
                                        ELSE '1'
                                    END as status_bayar_flag,
                                    td_lingkungan.nilai_denda as denda,
                                    CASE ISNULL(td_lingkungan.nilai_keamanan,0)
                                        WHEN 0 THEN ISNULL(td_air.nilai_keamanan,0)
                                        ELSE td_lingkungan.nilai_keamanan
                                    END as keamanan,
                                    CASE 
                                        WHEN ISNULL(td_lingkungan.nilai_sampah,0) = 0 THEN ISNULL(td_air.nilai_sampah,0)
                                        ELSE td_lingkungan.nilai_sampah
                                    END as kebersihan,
                                    td_lingkungan.td_lingkungan_id as source_id,
                                    '$source' as source_table,
                                    td_lingkungan.nilai_tanah + 
                                    td_lingkungan.nilai_bangunan + 
                                    CASE ISNULL(td_lingkungan.nilai_keamanan,0)
                                        WHEN 0 THEN ISNULL(td_air.nilai_keamanan,0)
                                        ELSE td_lingkungan.nilai_keamanan
                                    END +
                                    CASE ISNULL(td_lingkungan.nilai_sampah,0)
                                        WHEN 0 THEN ISNULL(td_air.nilai_sampah,0)
                                        ELSE td_lingkungan.nilai_sampah
                                    END as tagihan
                                ")
                            ->from("$source.dbo.td_lingkungan")
                            ->join("$source.dbo.td_air",
                                "td_air.th_trans_id = td_lingkungan.th_trans_id
                                AND td_air.periode = td_lingkungan.periode",
                                "LEFT")
                            ->join("$source.dbo.th_trans",
                                    "th_trans.th_trans_id = td_lingkungan.th_trans_id")
                            ->join("$source.dbo.m_custwtp",
                                    "th_trans.cust_id = m_custwtp.cust_id")
                            ->join('unit',
                                    "unit.source_table = '$source'
                                    AND unit.source_id = m_custwtp.cust_id")
                            ->join("blok",
                                    "blok.id = unit.blok_id")
                            ->join("kawasan",
                                    "kawasan.id = blok.kawasan_id")
                            ->join("customer",
                                    "customer.id = unit.pemilik_customer_id")
                            ->join("t_tagihan_lingkungan",
                                    "t_tagihan_lingkungan.source_table = '$source'
                                    AND t_tagihan_lingkungan.source_id = td_lingkungan.td_lingkungan_id",
                                    "LEFT")
                            // ->join("$source.dbo.m_rangebankav",
                            //         "m_rangebankav.range_id = m_custwtp.rangebankav_id")
                            ->where("t_tagihan_lingkungan.id is null")
                            ->where_in('td_lingkungan.td_lingkungan_id',$data_id)

                            ->get()->result();
            // echo("<pre>");
            //     print_r($dataTagihanLingkungan);
            // echo("</pre>");

            if($dataTagihanLingkungan)
                $this->db->insert_batch("t_tagihan_lingkungan",$dataTagihanLingkungan); 
            $this->db->trans_complete();

            $data = [];
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah data di input:".count($data)
                    ];
        }
    }
    public function get_ajax_desktop_transaksi_by_source($source)
    {   
        if ($source) {
            $data=$this->db
                    ->select("
                        td_lingkungan.td_lingkungan_id as id,
                        kawasan.name as kawasan,
                        blok.name as blok,
                        unit.no_unit,
                        customer.name as pemilik,
                        CONCAT(RIGHT('0' + RTRIM(MONTH(td_lingkungan.periode)), 2) ,'-',YEAR(td_lingkungan.periode)) as periode,
                        CASE td_lingkungan.status_id 
                                WHEN 1 THEN 'Tagihan'
                                WHEN 2 THEN 'Terbayar'
                                WHEN 3 THEN 'Pemutihan'
                                WHEN 4 THEN 'Dibatalkan'
                                ELSE ''
                        END as status
                    ")
                    ->from("$source.dbo.td_lingkungan")
                    ->join("$source.dbo.th_trans",
                            "th_trans.th_trans_id = td_lingkungan.th_trans_id")
                    ->join("$source.dbo.m_custwtp",
                            "th_trans.cust_id = m_custwtp.cust_id")
                    ->join('unit',
                            "unit.source_table = '$source'
                            AND unit.source_id = m_custwtp.cust_id")
                    ->join("blok",
                            "blok.id = unit.blok_id")
                    ->join("kawasan",
                            "kawasan.id = blok.kawasan_id")
                    ->join("customer",
                            "customer.id = unit.pemilik_customer_id")
                    ->join("t_tagihan_lingkungan",
                            "t_tagihan_lingkungan.source_table = '$source'
                            AND t_tagihan_lingkungan.source_id = td_lingkungan.td_lingkungan_id",
                            "LEFT")
                    // ->join("$source.dbo.m_rangebankav",
                    //         "m_rangebankav.range_id = m_custwtp.rangebankav_id")
                    ->where("t_tagihan_lingkungan.id is null")
                    // ->limit(900)
                    ->get()->result();
            return $data;
        }
        return 0;
    }
    public function save2($project_id,$source,$user_id){
        $tagihan_lingkungan_tmp = $this->db->select("
                                                    t_tagihan_lingkungan.id as tagihan_id,
                                                    td_air.periode as air_periode,
                                                    isnull(td_air.nilai_keamanan,0) as air_keamanan, 
                                                    isnull(td_air.nilai_sampah,0) as air_kebersihan,
                                                    isnull(td_air.nilai_ppnkeamanan,0) as air_keamanan_ppn,
                                                    isnull(td_air.nilai_ppnkebersihan,0) as air_kebersihan_ppn,
                                                    isnull(td_air.nomor,0) as air_kode_tagihan,
                                                    isnull(td_air.nilai_denda,0) as air_denda,
                                                    isnull(td_air.nilai_ppnkebersihan + td_air.nilai_ppnkeamanan,0) as air_ppn,
                                                    td_air.tanggal_bayar as air_bayar,
                                                    

                                                    td_lingkungan.periode as lingkungan_periode,
                                                    isnull(td_lingkungan.nilai_keamanan,0) as lingkungan_keamanan,
                                                    isnull(td_lingkungan.nilai_sampah,0) as lingkungan_kebersihan,
                                                    isnull(td_lingkungan.nilai_ppn,0) as lingkungan_ppn,
                                                    isnull(td_lingkungan.nilai_tanah,0) as nilai_tanah,
                                                    isnull(td_lingkungan.nilai_bangunan,0) as nilai_bangunan,
                                                    isnull(td_lingkungan.nomor,0) as lingkungan_kode_tagihan,
                                                    isnull(td_lingkungan.nilai_denda,0) as lingkungan_denda,
                                                    isnull(td_lingkungan.nilai_ppn,0) as lingkungan_ppn,

                                                    td_lingkungan.td_lingkungan_id,
                                                    td_air.td_air_id,
                                                    td_lingkungan.tanggal_bayar as lingkungan_bayar,
                                                    unit.id as unit_id,
                                                    sub_golongan.id as sub_gol_id,
                                                    sub_golongan.code as sub_gol_code,
                                                    range_lingkungan.id as range_id,
                                                    range_lingkungan.code as range_code,
                                                    unit.luas_bangunan,
                                                    unit.luas_tanah,

                                                    td_air.nilai_admin")
                                            ->from("$source.dbo.td_lingkungan
                                                    FULL JOIN $source.dbo.td_air
                                                    ON td_air.th_trans_id = td_lingkungan.th_trans_id
                                                    AND MONTH(td_air.periode) = MONTH(td_lingkungan.periode)
                                                    AND YEAR(td_air.periode) = YEAR(td_lingkungan.periode)",
                                                    )
                                            ->join("$source.dbo.th_trans",
                                                    "th_trans.th_trans_id = td_lingkungan.th_trans_id
                                                    OR th_trans.th_trans_id = td_air.th_trans_id",
                                                    "LEFT")
                                            ->join("unit",
                                                    "unit.source_table = '$source'
                                                    AND unit.source_id = th_trans.cust_id")
                                            ->join("unit_lingkungan",
                                                    "on unit_lingkungan.unit_id = unit.id")
                                            ->join("sub_golongan",
                                                    "sub_golongan.id = unit_lingkungan.sub_gol_id")
                                            ->join("range_lingkungan",
                                                    "range_lingkungan.id = sub_golongan.range_id")
                                            ->join("t_tagihan_lingkungan",
                                                    "t_tagihan_lingkungan.proyek_id = '$project_id'
                                                    AND t_tagihan_lingkungan.unit_id = unit.id
                                                    AND ((MONTH(t_tagihan_lingkungan.periode) = MONTH(td_air.periode) AND YEAR(t_tagihan_lingkungan.periode) = YEAR(td_air.periode))
                                                    OR(MONTH(t_tagihan_lingkungan.periode) = MONTH(td_lingkungan.periode) AND YEAR(t_tagihan_lingkungan.periode) = YEAR(td_lingkungan.periode)))
                                                    "
                                                    ,"LEFT")
                                            ->where("(td_lingkungan.periode is not null
                                                    OR td_air.periode is not null)")
                                            ->get()->result();
        var_dump(count($tagihan_lingkungan_tmp));
        $tagihan_lingkungan         = (object)[];
        $tagihan_lingkungan_detail  = (object)[];
        $tagihan_lingkungan_info    = (object)[];
        // echo("<pre>");
        //     print_r($tagihan_lingkungan_tmp);
        // echo("</pre>");
        
        $tagihan_lingkungan->proyek_id                  = $project_id;
        $tagihan_lingkungan_detail->source_table        = $source;
        $tagihan_lingkungan_detail->active              = 1;
        $tagihan_lingkungan_detail->user_id             = $user_id;
        $tagihan_lingkungan_detail->nilai_ppn           = 10;
        $tagihan_lingkungan_detail->nilai_bangunan_flag = 0;
        $tagihan_lingkungan_detail->nilai_kavling_flag  = 0;
        $tagihan_lingkungan_detail->nilai_denda_flag    = 0;
        $tagihan_lingkungan_detail->ppn_sc_flag         = 0;
        $tagihan_lingkungan_info->formula_bangunan      = 9;
        $tagihan_lingkungan_info->formula_kavling       = 9;
        
        
        foreach ($tagihan_lingkungan_tmp as $k=>$v) {
            $tagihan_lingkungan->unit_id = $v->unit_id;
            if(($v->nilai_bangunan+$v->nilai_tanah+$v->lingkungan_keamanan+$v->lingkungan_kebersihan+$v->lingkungan_denda) > 0){
                $tagihan_lingkungan->periode        = $v->lingkungan_periode?substr($v->lingkungan_periode,0,8)."01":null;
                $tagihan_lingkungan->kode_tagihan   = $v->lingkungan_kode_tagihan;
                $tagihan_lingkungan->status_tagihan = $v->lingkungan_bayar?1:0;

            }else{
                $tagihan_lingkungan->periode        = $v->air_periode?substr($v->air_periode,0,8)."01":null;
                $tagihan_lingkungan->kode_tagihan   = $v->air_kode_tagihan;
                $tagihan_lingkungan->status_tagihan = $v->air_bayar?1:0;
            }
            $double = $v->tagihan_id?1:0;

            if($double == 0){
                $this->db->insert("t_tagihan_lingkungan",$tagihan_lingkungan);
                $tagihan_lingkungan_detail->t_tagihan_lingkungan_id = $this->db->insert_id();
                if(($v->nilai_bangunan+$v->nilai_tanah+$v->lingkungan_keamanan+$v->lingkungan_kebersihan+$v->lingkungan_denda) > 0){
                    $tagihan_lingkungan_detail->source_id           = "IPL - ".$v->td_lingkungan_id;
                    $tagihan_lingkungan_detail->nilai_bangunan      = $v->nilai_bangunan;
                    $tagihan_lingkungan_detail->nilai_kavling       = $v->nilai_tanah;
                    $tagihan_lingkungan_detail->nilai_administrasi  = 0;
                    $tagihan_lingkungan_detail->nilai_keamanan      = $v->lingkungan_keamanan;
                    $tagihan_lingkungan_detail->nilai_kebersihan    = $v->lingkungan_kebersihan;
                    $tagihan_lingkungan_detail->nilai_denda         = $v->lingkungan_denda;
                    $tagihan_lingkungan_detail->ppn_flag            = $v->lingkungan_ppn?1:0;                    
                }else{
                    $tagihan_lingkungan_detail->source_id           = "AIR - ".$v->td_air_id;
                    $tagihan_lingkungan_detail->nilai_bangunan      = 0;
                    $tagihan_lingkungan_detail->nilai_kavling       = 0;
                    $tagihan_lingkungan_detail->nilai_administrasi  = $v->nilai_admin;
                    $tagihan_lingkungan_detail->nilai_keamanan      = $v->air_keamanan;
                    $tagihan_lingkungan_detail->nilai_kebersihan    = $v->air_kebersihan;
                    $tagihan_lingkungan_detail->nilai_denda         = $v->air_denda;
                    $tagihan_lingkungan_detail->ppn_flag            = $v->air_ppn?1:0;
                }
                $tagihan_lingkungan_info->t_tagihan_lingkungan_id   = $tagihan_lingkungan_detail->t_tagihan_lingkungan_id;
                $tagihan_lingkungan_info->range_id                  = $v->range_id;
                $tagihan_lingkungan_info->range_code                = $v->range_code;
                $tagihan_lingkungan_info->sub_golongan_id           = $v->sub_gol_id;
                $tagihan_lingkungan_info->sub_golongan_code         = $v->sub_gol_code;
                $tagihan_lingkungan_info->luas_bangunan             = $v->luas_bangunan;
                $tagihan_lingkungan_info->luas_kavling              = $v->luas_tanah;

                $this->db->insert("t_tagihan_lingkungan_detail",$tagihan_lingkungan_detail);
                $this->db->insert("t_tagihan_lingkungan_info",$tagihan_lingkungan_info);
            }
        }
    }
}
