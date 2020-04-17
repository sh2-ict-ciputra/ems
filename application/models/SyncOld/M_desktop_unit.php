<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_desktop_unit extends CI_Model
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
    public function save($project_id,$data_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        // var_dump($formula_air);
        // var_dump($formula_bangunan);
        // var_dump($formula_kavling);
        
        

        $dataRangeAirTMP = $this->db
                            ->select("
                                    '$project_id' as project_id,
                                    '$source' as source_table,
                                    '1' as active,
                                    '0' as [delete],
                                    m_range.kode as code,
                                    m_range.nama as name,
                                    m_range.range_id as source_id,
                                    m_range.note as description,
                                    $formula_air as formula,
                                    range_l1,
                                    range_r1,
                                    range_rp1,
                                    range_l2,
                                    range_r2,
                                    range_rp2,
                                    range_l3,
                                    range_r3,
                                    range_rp3,
                                    range_l4,
                                    range_r4,
                                    range_rp4,
                                    range_l5,
                                    range_r5,
                                    range_rp5,
                                    range_l6,
                                    range_r6,
                                    range_rp6	
                                ")
                            ->from("$source.dbo.m_range")
                            ->join("$source.dbo.m_custwtp",
                                    "m_custwtp.rangeair_id = m_range.range_id")
                            // ->where_in('m_custwtp.cust_id',$data_id)
                            ->join('range_air',
                                    "range_air.source_table = '$source'
                                    AND range_air.source_id = m_range.range_id
                                    AND range_air.project_id = '$project_id'",
                                    "LEFT")
                            ->where("range_air.id is null")
                            ->distinct()
                            ->get()->result();

        $dataRangeAir = [];
        foreach ($dataRangeAirTMP  as $k=> $v) {
            $this->db->trans_start();
            $dataRangeAir = [];
            $dataRangeAir = [
                'project_id' => $v->project_id,
                'code' => $v->code,
                'name' => $v->name,
                'source_table' => $v->source_table,
                'source_id' => $v->source_id,
                'description' => $v->description,
                'formula' => $v->formula,
                'active' => $v->active,
                'delete' => $v->delete
            ];
            
            $this->db->insert("range_air",$dataRangeAir); 
            $insert_id = $this->db->insert_id();

            $dataRangeAirDetail = [];
            for ($i=1; $i <= 6 ; $i++) {
                
                
                if($v->{"range_l$i"}+$v->{"range_r$i"}+$v->{"range_rp$i"} == 0){
                    break;
                }
                $dataRangeAirDetail=[

                    'range_air_id'  => $insert_id,
                    'range_awal'    => $v->{"range_l$i"},
                    'range_akhir'   => $v->{"range_r$i"},
                    'harga'         => $v->{"range_rp$i"},
                    'delete'        => 0
                ];

                $this->db->insert("range_air_detail",$dataRangeAirDetail); 

            }
            $this->db->trans_complete();

        }
        
        $dataRangeLingkunganTMP = $this->db
                            ->select("
                                    '$project_id' as project_id,
                                    '$source' as source_table,
                                    '1' as active,
                                    '0' as [delete],
                                    m_rangebankav.kode as code,
                                    m_rangebankav.nama as name,
                                    m_rangebankav.range_id as source_id,
                                    m_rangebankav.note as description,
                                    $formula_bangunan as formula_bangunan,
                                    $formula_kavling as formula_kavling,
                                    range_bl1,
                                    range_br1,
                                    range_brp1,
                                    range_bl2,
                                    range_br2,
                                    range_brp2,
                                    range_bl3,
                                    range_br3,
                                    range_brp3,
                                    range_bl4,
                                    range_br4,
                                    range_brp4,
                                    range_bl5,
                                    range_br5,
                                    range_brp5,
                                    range_bl6,
                                    range_br6,
                                    range_brp6,
                                    range_kl1,
                                    range_kr1,
                                    range_krp1,
                                    range_kl2,
                                    range_kr2,
                                    range_krp2,
                                    range_kl3,
                                    range_kr3,
                                    range_krp3,
                                    range_kl4,
                                    range_kr4,
                                    range_krp4,
                                    range_kl5,
                                    range_kr5,
                                    range_krp5,
                                    range_kl6,
                                    range_kr6,
                                    range_krp6		
                                ")
                            ->from("$source.dbo.m_rangebankav")
                            ->join("$source.dbo.m_custwtp",
                                    "m_custwtp.rangebankav_id = m_rangebankav.range_id")
                            // ->where_in('m_custwtp.cust_id',$data_id)
                            ->join('range_lingkungan',
                                    "range_lingkungan.source_table = '$source'
                                    AND range_lingkungan.source_id = m_rangebankav.range_id
                                    AND range_lingkungan.project_id = '$project_id'",
                                    "LEFT")
                            ->where("range_lingkungan.id is null")
                            ->distinct()
                            ->get()->result();
        

        $dataRangeLingkungan = [];
        foreach ($dataRangeLingkunganTMP  as $k=> $v) {
            $this->db->trans_start();
            $dataRangeLingkungan = [];
            $dataRangeLingkungan = [
                'project_id' => $v->project_id,
                'code' => $v->code,
                'name' => $v->name,
                'source_table' => $v->source_table,
                'source_id' => $v->source_id,
                'description' => $v->description,
                'formula_bangunan' => $v->formula_bangunan,
                'formula_kavling' => $v->formula_kavling,
                'active' => $v->active,
                'delete' => $v->delete
            ];
            
            $this->db->insert("range_lingkungan",$dataRangeLingkungan); 
            $insert_id = $this->db->insert_id();

            $dataRangeLingkunganDetailB = [];
            $dataRangeLingkunganDetailK = [];
            for ($i=1; $i <= 6 ; $i++) {
                
                
                if($v->{"range_bl$i"}+$v->{"range_br$i"}+$v->{"range_brp$i"} == 0){
                    break;
                }
                else{   
                    $dataRangeLingkunganDetailB=[

                        'range_lingkungan_id'  => $insert_id,
                        'range_awal'    => $v->{"range_bl$i"},
                        'range_akhir'   => $v->{"range_br$i"},
                        'harga'         => $v->{"range_brp$i"},
                        'flag_jenis'    => 0,
                        'delete'        => 0
                    ];
                }
                if($v->{"range_kl$i"}+$v->{"range_kr$i"}+$v->{"range_krp$i"} == 0){
                    break;
                }
                else{
                    $dataRangeLingkunganDetailK=[

                        'range_lingkungan_id'  => $insert_id,
                        'range_awal'    => $v->{"range_kl$i"},
                        'range_akhir'   => $v->{"range_kr$i"},
                        'harga'         => $v->{"range_krp$i"},
                        'flag_jenis'    => 1,
                        'delete'        => 0
                    ];
                    }
                $this->db->insert("range_lingkungan_detail",$dataRangeLingkunganDetailB); 
                $this->db->insert("range_lingkungan_detail",$dataRangeLingkunganDetailK); 

            }
            $this->db->trans_complete();

        }
        
        
        $dataGolongan = $this->db
                            ->select("
                                    '$project_id' as project_id,
                                    '$source' as source_table,
                                    '1' as active,
                                    '0' as [delete],
                                    m_custwtp.gol_id as source_id,
                                    m_gol.kode as code ,
                                    m_gol.note as description
                                ")
                            ->from("$source.dbo.m_custwtp")
                            ->join("$source.dbo.m_gol",
                                    'm_gol.gol_id = m_custwtp.gol_id')
                            ->join('jenis_golongan',
                                    "jenis_golongan.source_table = '$source'
                                    AND jenis_golongan.project_id = $project_id
                                    AND jenis_golongan.source_id = m_gol.gol_id",
                                    "LEFT")
                            // ->where_in('m_custwtp.cust_id',$data_id)    
                            ->where("jenis_golongan.id is null")                                
                            ->distinct()
                            ->get()->result();
        
        $this->db->trans_start();
        if($dataGolongan)
            $this->db->insert_batch("jenis_golongan",$dataGolongan); 
        $this->db->trans_complete();
        $dataSubGolonganAirTMP = $this->db
                            ->select("
                                    '$source' as source_table,
                                    m_golsub.subgol_id as source_id,
                                    '1' as active,  
                                    '0' as [delete],
                                    CONCAT(m_golsub.kode,' Air') as code,
                                    m_golsub.nama as name,
                                    jenis_golongan.id as jenis_golongan_id,			
                                    m_golsub.nilai_min as minimum_pemakaian,
                                    m_golsub.nilai_min_rp as minimum_rp,
                                    m_golsub.nilai_admin as administrasi,
                                    service.id as service_id,
                                    '1' as range_flag,
                                    range_air.id as range_id
                                ")
                            ->from("$source.dbo.m_custwtp")
                            ->join("$source.dbo.m_golsub",
                                    'm_golsub.subgol_id = m_custwtp.subgol_id')
                            ->join('sub_golongan',
                                    "sub_golongan.source_table = '$source'
                                    AND sub_golongan.source_id = m_golsub.subgol_id
                                    AND sub_golongan.code = CONCAT ( m_golsub.kode, ' Air' )",
                                    "LEFT")
                            ->join('jenis_golongan',
                                    " jenis_golongan.source_id  = m_custwtp.gol_id		
                                    AND jenis_golongan.source_table = '$source'
                                    AND jenis_golongan.project_id = $project_id")
                            ->join('service',
                                    "service.service_jenis_id  = 1		
                                    AND service.project_id = $project_id")
                            ->join("range_air",
                                    "range_air.source_table = '$source'
                                    AND range_air.source_id = m_custwtp.rangeair_id")
                            // ->where_in('m_custwtp.cust_id',$data_id)    
                            ->where("sub_golongan.id is null")                                
                            ->order_by("CONCAT(m_golsub.kode,' Air')")
                            ->distinct()
                            ->get()->result();
        $tmp = $dataSubGolonganAirTMP?$dataSubGolonganAirTMP[0]->code:'';

        $tmpIndex = 1;
        foreach ($dataSubGolonganAirTMP as $k => $v) {
            if($k > 0)
            {
                if($v->code == $tmp){
                    $tmpIndex++;
                    $v->code = $v->code." - v".$tmpIndex;
                }else{
                    $tmpIndex = 1;
                    $tmp = $v->code;

                }
            }
            $this->db->trans_start();
                $this->db->insert("sub_golongan",$v); 
            $this->db->trans_complete();
        }
        $dataSubGolonganLingkunganTMP = $this->db
                            ->select("
                                    '$source' as source_table,
                                    m_golsub.subgol_id as source_id,
                                    '1' as active,
                                    '0' as [delete],
                                    CONCAT(m_golsub.kode,' Lingkungan') as code,
                                    m_golsub.nama as name,
                                    jenis_golongan.id as jenis_golongan_id,			
                                    m_golsub.nilai_min as minimum_pemakaian,
                                    m_golsub.nilai_min_rp as minimum_rp,
                                    m_golsub.nilai_admin as administrasi,
                                    service.id as service_id,
                                    '2' as range_flag,
                                    range_lingkungan.id as range_id
                                ")
                            ->from("$source.dbo.m_custwtp")
                            ->join("$source.dbo.m_golsub",
                                    'm_golsub.subgol_id = m_custwtp.subgol_id')
                            ->join('sub_golongan',
                                    "sub_golongan.source_table = '$source'
                                    AND sub_golongan.source_id = m_golsub.subgol_id
                                    AND sub_golongan.code = CONCAT ( m_golsub.kode, ' Lingkungan' )
                                    ",
                                    "LEFT")
                            ->join('jenis_golongan',
                                    " jenis_golongan.source_id  = m_custwtp.gol_id		
                                    AND jenis_golongan.source_table = '$source'
                                    AND jenis_golongan.project_id = $project_id")
                            ->join('service',
                                    "service.service_jenis_id  = 1		
                                    AND service.project_id = $project_id")
                            ->join("range_lingkungan",
                                    "range_lingkungan.source_table = '$source'
                                    AND range_lingkungan.source_id = m_custwtp.rangebankav_id")
                            // ->where_in('m_custwtp.cust_id',$data_id)    
                            ->where("sub_golongan.id is null")     
                            ->order_by("CONCAT(m_golsub.kode,' Lingkungan')")                           
                            ->distinct()
                            ->get()->result();
        $tmp = $dataSubGolonganLingkunganTMP?$dataSubGolonganLingkunganTMP[0]->code:'';
        $tmpIndex = 1;
        foreach ($dataSubGolonganLingkunganTMP as $k => $v) {
            if($k > 0)
            {
                if($v->code == $tmp){
                    $tmpIndex++;
                    $v->code = $v->code." - v".$tmpIndex;
                }else{
                    $tmpIndex = 1;
                    $tmp = $v->code;

                }
            }
            // echo("<pre>");
            //     print_r($v);
            // echo("</pre>");
            $this->db->trans_start();
                $this->db->insert("sub_golongan",$v); 
            $this->db->trans_complete();
        }
        
        
        if($source){
            $this->db->trans_start();
            


            $dataKawasan = $this->db
                                ->select("
                                m_custwtp.kawasan_id as source_id,
                                '$source' as source_table,
                                m_kawasan.kode as code,
                                    m_kawasan.note as description,
                                    m_kawasan.nama as name, 
                                    '1' as active,
                                    '0' as [delete],
                                    '$project_id'as project_id")
                                ->from("$source.dbo.m_custwtp")
                                ->join("$source.dbo.m_kawasan",
                                        'm_kawasan.kawasan_id = m_custwtp.kawasan_id')
                                ->join('kawasan',
                                    "kawasan.source_table = '$source' 
                                    AND kawasan.source_id = m_custwtp.kawasan_id
                                    AND kawasan.project_id = $project_id",
                                    "left")    
                                ->where_in('m_custwtp.cust_id',$data_id)
                                ->where("kawasan.id is null")                                
                                ->distinct()
                                ->get()->result();

            if($dataKawasan)
                $this->db->insert_batch("kawasan",$dataKawasan); 
            $this->db->trans_complete();

            $this->db->trans_start();
            $dataBlok = $this->db
                                ->select("
                                    kawasan.id as kawasan_id,
                                    '$source' as source_table,
                                    m_custwtp2.code as name,
                                    m_custwtp2.code,
                                    '1' as active,
                                    '0' as [delete]
                                    ")
                                ->from("$source.dbo.m_custwtp")
                                ->join("(SELECT cust_id,
                                            CASE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok))
                                                WHEN '' THEN '-'
                                                ELSE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok)) 
                                                END as code
                                        FROM $source.dbo.m_custwtp
                                        ) as m_custwtp2","m_custwtp2.cust_id = m_custwtp.cust_id")
                                ->join('project',
                                        "project.id = $project_id")
                                ->join('kawasan',
                                        "kawasan.source_table = '$source'
                                        AND kawasan.source_id = m_custwtp.kawasan_id")
                                ->join('blok',
                                        "blok.source_table = '$source'
                                        AND blok.name =	CASE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',m_custwtp.kode_blok)) WHEN '' THEN '-' ELSE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',m_custwtp.kode_blok)) END",
                                        "LEFT")
                                ->where_in('m_custwtp.cust_id',$data_id)    
                                ->where('project.id IS NOT NULL')
                                ->where('kawasan.id IS NOT NULL')
                                ->where('blok.id IS NULL')
                                
                                ->distinct()
                                ->get()->result();
            
            if($dataBlok)
                $this->db->insert_batch("blok",$dataBlok); 
            $this->db->trans_complete();


            


            $dataCustomer = $this->db
                                ->select("
                                        '$source' as source_table,
                                        m_custwtp.cust_id as source_id,
                                        '1' as active,
                                        '0' as [delete],
                                
                                        CONCAT('CUST/',project.code,UPPER('/$source/'),m_custwtp.cust_id) as code,
                                        $project_id as project_id,
                                        1 as unit,
                                        m_custwtp.nama as name,
                                        m_custwtp.alamat as address,
                                        m_custwtp.email,
                                        m_custwtp.handphone as mobilephone1,
                                        m_custwtp.telp1 as homephone,
                                        m_custwtp.telp2 as officephone,
                                        m_custwtp.nomor_npwp as npwp_no,
                                        m_custwtp.npwp_nama as npwp_name,
                                        m_custwtp.npwp_alamat as npwp_address,
                                        m_custwtp.catatan as description
                                    ")
                                ->from("$source.dbo.m_custwtp")
                                ->join("customer",
                                        "customer.source_id = m_custwtp.cust_id
                                        AND customer.source_table = '$source'",
                                        "LEFT")
                                ->join('project',
                                        "project.id = $project_id")
	                            ->where_in('m_custwtp.cust_id',$data_id)    
                                ->where("customer.id is null")                                
                                ->distinct()
                                ->get()->result();
            $this->db->trans_start();
            if($dataCustomer)
                $this->db->insert_batch("customer",$dataCustomer); 
            $this->db->trans_complete();

            $dataUnit = $this->db
                                ->select("
                                        $project_id as project_id,
                                        blok.id as blok_id,
                                        1 as status_tagihan,
                                        m_custwtp2.no_unit,
                                        customer.id as pemilik_customer_id,
                                        customer.id as penghuni_customer_id,
                                        m_custwtp.luas_tanah,
                                        m_custwtp.luas_bangunan,
                                        m_custwtp.Tanggal_STerima as tgl_st,
                                        m_custwtp.tanggal_bangun as tgl_bangun,
                                        jenis_golongan.id as gol_id,
                                        '$source.m_custwtp' as source_table,
                                        m_custwtp.cust_id as source_id,
                                        '1' as active,
                                        '0' as [delete]
                                    ")
                                ->from("$source.dbo.m_custwtp")
                                ->join("(SELECT 
                                            cust_id,
                                            CASE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok))
                                                WHEN '' THEN '-'
                                                ELSE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok)) 
                                            END as code,
                                            CASE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok))
                                                WHEN '' THEN '-'
                                                ELSE SUBSTRING(m_custwtp.kode_blok,CHARINDEX('/',  m_custwtp.kode_blok)+1,len(kode_blok)) 
                                            END as no_unit
                                        FROM $source.dbo.m_custwtp
                                        ) as m_custwtp2",
                                        "m_custwtp2.cust_id = m_custwtp.cust_id")
                                ->join('unit',
                                        "unit.source_id = m_custwtp.cust_id
                                        AND unit.source_table = '$source.m_custwtp'",
                                        "LEFT")
                                ->join('project',
                                        "project.id = $project_id")
                                ->join('blok',
                                        "blok.code = m_custwtp2.code")
                                ->join('customer',
                                        "customer.source_table = '$source'
                                        AND customer.source_id = m_custwtp.cust_id")
                                ->join("jenis_golongan",
                                        "jenis_golongan.source_table = '$source'
                                        AND jenis_golongan.source_id = m_custwtp.gol_id")
                                ->where_in('m_custwtp.cust_id',$data_id)    
                                ->where("unit.id is null")                                
                                ->distinct()
                                ->get()->result();
            
            $this->db->trans_start();
            if($dataUnit)
                $this->db->insert_batch("unit",$dataUnit); 
            $this->db->trans_complete();
            $dataUnitAir = $this->db
                                ->select("
                                        unit.id as unit_id,
                                        m_custwtp.flag_air as aktif,
                                        m_custwtp.nomor_meter as no_seri_meter,
                                        sub_golongan.id as sub_gol_id
                                    ")
                                ->from("$source.dbo.m_custwtp")
                                ->join("customer",
                                        "customer.source_id = m_custwtp.cust_id
                                        AND customer.source_table = '$source'")
                                ->join("unit",
                                        "unit.source_id = m_custwtp.cust_id
                                        AND unit.source_table = '$source.m_custwtp'")
                                ->join("unit_air",
                                        "unit_air.unit_id = unit.id",
                                        "LEFT")
                                ->join("range_air",
                                        "range_air.source_table = 'iplk_clbcm'
                                        AND range_air.source_id = m_custwtp.rangeair_id")
                                ->join('sub_golongan',
                                        "sub_golongan.source_table = '$source'
                                        AND sub_golongan.source_id = m_custwtp.subgol_id
                                        AND sub_golongan.range_id = range_air.id")
                                ->where_in('m_custwtp.cust_id',$data_id)    
                                ->where("customer.id is not null")                                
                                ->where("unit_air.id is null")                                
                                ->distinct()
                                ->get()->result();
            
            $this->db->trans_start();
            if($dataUnitAir)
                $this->db->insert_batch("unit_air",$dataUnitAir); 
            $this->db->trans_complete();

            $dataUnitLingkungan = $this->db
                                ->select("
                                        unit.id as unit_id,
                                        1 as aktif,
                                        sub_golongan.id as sub_gol_id
                                    ")
                                ->from("$source.dbo.m_custwtp")
                                ->join("unit",
                                        "unit.source_table = '$source.m_custwtp'
                                        AND unit.source_id = m_custwtp.cust_id")
                                ->join("range_lingkungan",
                                        "ON range_lingkungan.source_table = '$source'
                                        AND range_lingkungan.source_id = m_custwtp.rangebankav_id
                                        and range_lingkungan.project_id = unit.project_id")
                                ->join("sub_golongan",
                                        "sub_golongan.source_table = '$source'
                                        AND sub_golongan.source_id = m_custwtp.subgol_id
                                        AND sub_golongan.range_id = range_lingkungan.id
                                        AND sub_golongan.range_flag = 2
                                        AND sub_golongan.jenis_golongan_id = m_custwtp.gol_id",
                                        "LEFT")
                                ->where_in('m_custwtp.cust_id',$data_id)    
                                ->distinct()
                                ->get()->result();
            $this->db->trans_start();
            if($dataUnitLingkungan)
                $this->db->insert_batch("unit_lingkungan",$dataUnitLingkungan); 
            $this->db->trans_complete();
            $data = [];
            return  [
                        "success" => true,
                        "message" => "Berhasil, Jumlah data di input:".count($data)
                    ];
        }
    }
    public function get_ajax_desktop_unit_by_source($source,$project_id)
    {   
        if ($source) {
            $data=$this->db
                    ->select("
                        m_custwtp.cust_id as id,
                        m_kawasan.nama as kawasan,
                        m_custwtp2.blok,
                        m_custwtp2.no_unit,
                        m_custwtp.nama as pemilik,
                        customer.id as customer_id
                    ")
                    ->from("$source.dbo.m_custwtp")
                    ->join("(SELECT 
                                cust_id,    
                                CASE CHARINDEX('/',kode_blok)
                                    WHEN 0 THEN kode_blok 
                                    ELSE SUBSTRING (kode_blok,0,CHARINDEX( '/', kode_blok ))
                                END as blok, 
                                CASE CHARINDEX('/',kode_blok)
                                    WHEN 0 THEN '-'
                                    ELSE SUBSTRING (kode_blok,CHARINDEX( '/', kode_blok ) + 1,LEN( kode_blok ))
                                END as no_unit
                                FROM $source.dbo.m_custwtp) as m_custwtp2",
                            "m_custwtp.cust_id = m_custwtp2.cust_id")
                    ->join("$source.dbo.m_kawasan",
                            'm_kawasan.kawasan_id = m_custwtp.kawasan_id')
                    ->join('customer',
                            "customer.source_table = '$source' 
                            AND customer.source_id = m_custwtp.cust_id",                            
                            "LEFT")
                            // AND customer.project_id = $project_id", //kalau ada, project lain bisa mengambil 1 source yg sama

                    ->join("$source.dbo.m_range",
                            "m_range.range_id = m_custwtp.rangeair_id","LEFT")
                    // ->join("$source.dbo.m_rangebankav",
                    //         "m_rangebankav.range_id = m_custwtp.rangebankav_id")
                    ->where("customer.id is null")
                    ->where("(m_custwtp.flag_id != 9
                            AND m_custwtp.flag_id != 0)")
                    ->order_by("m_custwtp2.no_unit","ASC")
                    ->order_by("m_custwtp2.blok","ASC")
                    ->order_by("m_kawasan.nama","ASC")
                    ->limit(950)
                    ->get()->result();
            return $data;
        }
        return 0;
    }
    public function get_range_lingkungan($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('range_lingkungan')->count_all_results();
        $range_lingkungan_tmp = $this->db
                    ->select("
                        m_custwtp.rangebankav_id as range_id,
                        m_custwtp.nilai_aman,
                        m_custwtp.nilai_sampah,
                        m_rangebankav.kode,
                        m_rangebankav.nama,
                        m_rangebankav.note,
                        m_rangebankav.range_bl1,
                        m_rangebankav.range_br1,
                        m_rangebankav.range_brp1,
                        m_rangebankav.range_bl2,
                        m_rangebankav.range_br2,
                        m_rangebankav.range_brp2,
                        m_rangebankav.range_bl3,
                        m_rangebankav.range_br3,
                        m_rangebankav.range_brp3,
                        m_rangebankav.range_bl4,
                        m_rangebankav.range_br4,
                        m_rangebankav.range_brp4,
                        m_rangebankav.range_bl5,
                        m_rangebankav.range_br5,
                        m_rangebankav.range_brp5,
                        m_rangebankav.range_bl6,
                        m_rangebankav.range_br6,
                        m_rangebankav.range_brp6,
                        m_rangebankav.range_kl1,
                        m_rangebankav.range_kr1,
                        m_rangebankav.range_krp1,
                        m_rangebankav.range_kl2,
                        m_rangebankav.range_kr2,
                        m_rangebankav.range_krp2,
                        m_rangebankav.range_kl3,
                        m_rangebankav.range_kr3,
                        m_rangebankav.range_krp3,
                        m_rangebankav.range_kl4,
                        m_rangebankav.range_kr4,
                        m_rangebankav.range_krp4,
                        m_rangebankav.range_kl5,
                        m_rangebankav.range_kr5,
                        m_rangebankav.range_krp5,
                        m_rangebankav.range_kl6,
                        m_rangebankav.range_kr6,
                        m_rangebankav.range_krp6
                        ")
                    ->from("$source.dbo.m_custwtp")
                    ->join("$source.dbo.m_rangebankav",
                            "m_rangebankav.range_id = m_custwtp.rangebankav_id",
                            "LEFT")
                    ->where_not_in("m_custwtp.flag_id",[0,9])
                    ->where("(rangeair_id !=0 OR rangebankav_id != 0)")
                    ->order_by("m_custwtp.rangebankav_id,m_custwtp.nilai_aman,m_custwtp.nilai_sampah")
                    ->distinct()
                    ->get()->result();
        $versi = 0;

        $range_lingkungan = (object)[];
        $range_lingkungan->project_id       = $project_id;
        $range_lingkungan->active           = 1;
        $range_lingkungan->delete           = 0;
        $range_lingkungan->formula_bangunan = $formula_bangunan;
        $range_lingkungan->formula_kavling  = $formula_kavling;
        $range_lingkungan->source_table     = $source;
        
        for ($i=0; $i < count($range_lingkungan_tmp); $i++) { 
            if($range_lingkungan_tmp[$i]->range_id == 0){
                $versi++;
                $range_lingkungan->name         = "Lingkungan Create V - $versi";
                $range_lingkungan->code         = "LCV-$versi";
                $range_lingkungan->description  = "Lingkungan Create Version - $versi";
            }else{
                $range_lingkungan->name         = $range_lingkungan_tmp[$i]->nama;
                $range_lingkungan->code         = $range_lingkungan_tmp[$i]->kode;
                $range_lingkungan->description  = $range_lingkungan_tmp[$i]->note;
            }
            $range_lingkungan->source_id    = $range_lingkungan_tmp[$i]->range_id;
            $range_lingkungan->keamanan     = $range_lingkungan_tmp[$i]->nilai_aman;
            $range_lingkungan->kebersihan   = $range_lingkungan_tmp[$i]->nilai_sampah;
                
            
            $double = $this->db->select("id")
                                ->from("range_lingkungan")
                                ->where("project_id",$range_lingkungan->project_id)
                                ->where("keamanan",$range_lingkungan->keamanan)
                                ->where("kebersihan",$range_lingkungan->kebersihan)
                                ->where("source_table",$source)
                                ->where("source_id",$range_lingkungan_tmp[$i]->range_id)
                                ->get()->num_rows();
            if ($double == 0){
                $this->db->insert("range_lingkungan",$range_lingkungan);
                $insert_id = $this->db->insert_id();

                $range_lingkungan_detail_B = (object)[]; 
                $range_lingkungan_detail_K = (object)[]; 
                for ($j=1; $j <= 6; $j++) { 
                    $cek_bangunan   = $range_lingkungan_tmp[$i]->{"range_bl$j"}+$range_lingkungan_tmp[$i]->{"range_br$j"}+$range_lingkungan_tmp[$i]->{"range_brp$j"};
                    $cek_kavling    = $range_lingkungan_tmp[$i]->{"range_kl$j"}+$range_lingkungan_tmp[$i]->{"range_kr$j"}+$range_lingkungan_tmp[$i]->{"range_krp$j"};
                    if($cek_bangunan+$cek_kavling == 0){
                        break;
                    }
                    if($cek_bangunan != 0){
                        $range_lingkungan_detail_B = [
                            'range_lingkungan_id'   => $insert_id,
                            'range_awal'            => $range_lingkungan_tmp[$i]->{"range_bl$j"},
                            'range_akhir'           => $range_lingkungan_tmp[$i]->{"range_br$j"},
                            'harga'                 => $range_lingkungan_tmp[$i]->{"range_brp$j"},
                            'flag_jenis'            => 0,
                            'delete'                => 0
                        ];
                        $this->db->insert("range_lingkungan_detail",$range_lingkungan_detail_B);
                    }
                    if($cek_kavling != 0){
                        $range_lingkungan_detail_K = [
                            'range_lingkungan_id'   => $insert_id,
                            'range_awal'            => $range_lingkungan_tmp[$i]->{"range_kl$j"},
                            'range_akhir'           => $range_lingkungan_tmp[$i]->{"range_kr$j"},
                            'harga'                 => $range_lingkungan_tmp[$i]->{"range_krp$j"},
                            'flag_jenis'            => 1,
                            'delete'                => 0
                        ];
                        $this->db->insert("range_lingkungan_detail",$range_lingkungan_detail_K);
                    }
                }
            }
                
        }
        return $this->db->from('range_lingkungan')->count_all_results()-$jumlah_awal;
    }
    public function get_range_air($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('range_air')->count_all_results();

        $range_air_tmp = $this->db
                    ->select("
                        m_custwtp.rangeair_id as range_id,
                        m_range.kode,
                        m_range.nama,
                        m_range.note,
                        m_range.range_l1,
                        m_range.range_r1,
                        m_range.range_rp1,
                        m_range.range_l2,
                        m_range.range_r2,
                        m_range.range_rp2,
                        m_range.range_l3,
                        m_range.range_r3,
                        m_range.range_rp3,
                        m_range.range_l4,
                        m_range.range_r4,
                        m_range.range_rp4,
                        m_range.range_l5,
                        m_range.range_r5,
                        m_range.range_rp5,
                        m_range.range_l6,
                        m_range.range_r6,
                        m_range.range_rp6
                        ")
                    ->from("$source.dbo.m_custwtp")
                    ->join("$source.dbo.m_range",
                            "m_range.range_id = m_custwtp.rangeair_id",
                            "LEFT")
                    ->where_not_in("m_custwtp.flag_id",[0,9])
                    ->where("rangeair_id!=0")
                    ->order_by("m_custwtp.rangeair_id")
                    ->distinct()
                    ->get()->result();
        
        $range_air = (object)[];
        $range_air->project_id      = $project_id;
        $range_air->active          = 1;
        $range_air->delete          = 0;
        $range_air->formula         = $formula_air;
        $range_air->source_table    = $source;
        
        for ($i=0; $i < count($range_air_tmp); $i++) { 
            $range_air->name        = $range_air_tmp[$i]->nama;
            $range_air->code        = $range_air_tmp[$i]->kode;
            $range_air->description = $range_air_tmp[$i]->note;
            $range_air->source_id   = $range_air_tmp[$i]->range_id;
                
            $double = $this->db->select("id")
                                ->from("range_air")
                                ->where("project_id",$range_air->project_id)
                                ->where("source_table",$source)
                                ->where("source_id",$range_air_tmp[$i]->range_id)
                                ->get()->num_rows();
        
            if ($double == 0){
                $this->db->insert("range_air",$range_air);
                $insert_id = $this->db->insert_id();

                $range_air_detail = (object)[]; 
                for ($j=1; $j <= 6; $j++) { 
                    $cek = $range_air_tmp[$i]->{"range_l$j"}+$range_air_tmp[$i]->{"range_r$j"}+$range_air_tmp[$i]->{"range_rp$j"};
                    if($cek == 0)   break;
                    if($cek != 0){
                        $range_air_detail=[
                            'range_air_id'  => $insert_id,
                            'range_awal'    => $range_air_tmp[$i]->{"range_l$j"},
                            'range_akhir'   => $range_air_tmp[$i]->{"range_r$j"},
                            'harga'         => $range_air_tmp[$i]->{"range_rp$j"},
                            'delete'        => 0
                        ];
                        $this->db->insert("range_air_detail",$range_air_detail);
                    }
                }
            }
                
        }
        return $this->db->from('range_air')->count_all_results()-$jumlah_awal;

    }
    public function get_golongan($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('jenis_golongan')->count_all_results();

        $golongan_tmp = $this->db
                    ->select("
                            m_custwtp.gol_id,
                            m_gol.kode,
                            m_gol.note            
                        ")
                    ->from("$source.dbo.m_custwtp")
                    ->join("$source.dbo.m_gol",
                            "m_gol.gol_id = m_custwtp.gol_id",
                            "LEFT")
                    ->where_not_in("m_custwtp.flag_id",[0,9])
                    ->where("(rangeair_id!=0 or rangebankav_id!=0)")
                    ->order_by("m_custwtp.gol_id")
                    ->distinct()
                    ->get()->result();
        
        $golongan = (object)[];
        $golongan->project_id   = $project_id;
        $golongan->active       = 1;
        $golongan->delete       = 0;
        $golongan->source_table = $source;
        foreach ($golongan_tmp as $v) {
            
            $double = $this->db->select("id")
                                ->from("jenis_golongan")
                                ->where("project_id",$golongan->project_id)
                                ->where("source_table",$golongan->source_table)
                                ->where("source_id",$v->gol_id)
                                ->get()->num_rows();
            if($double == 0){
                $golongan->code         = $v->kode;
                $golongan->description  = $v->note;
                $golongan->source_id    = $v->gol_id;   
                $this->db->insert("jenis_golongan",$golongan);
            }
        }
        return $this->db->from('jenis_golongan')->count_all_results()-$jumlah_awal;

    }
    public function get_sub_golongan_lingkungan($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('sub_golongan')->count_all_results();

        $sub_golongan_lingkungan_tmp = $this->db
                                        ->select("
                                                m_custwtp.gol_id,
                                                m_custwtp.subgol_id,
                                                jenis_golongan.id as golongan_id,
                                                service.id as service_id,
                                                m_golsub.kode,
                                                m_golsub.nama,
                                                m_golsub.nilai_min,
                                                m_golsub.nilai_min_rp,
                                                m_golsub.nilai_admin,
                                                m_custwtp.nilai_aman,
                                                m_custwtp.nilai_sampah,
                                                isnull(range_lingkungan.id,0) as range_lingkungan_id
                                            ")
                                        ->from("$source.dbo.m_custwtp")
                                        ->join("$source.dbo.m_golsub",
                                                "m_golsub.subgol_id = m_custwtp.subgol_id",
                                                "LEFT")
                                        ->join("range_lingkungan",
                                                "range_lingkungan.source_table = '$source'
                                                AND range_lingkungan.source_id = m_custwtp.rangebankav_id
                                                AND range_lingkungan.keamanan = m_custwtp.nilai_aman
                                                AND range_lingkungan.kebersihan = m_custwtp.nilai_sampah",
                                                "LEFT")
                                        ->join("jenis_golongan",
                                                "jenis_golongan.source_table = '$source'
                                                AND jenis_golongan.source_id = m_custwtp.gol_id
                                                ")
                                        ->join("service", 
                                                "service.project_id = $project_id
                                                AND service.service_jenis_id = 1
                                                ")
                                        ->where_not_in("m_custwtp.flag_id",[0,9])
                                        ->where("(rangeair_id!=0 or rangebankav_id!=0)")
                                        ->order_by("subgol_id")
                                        ->distinct()
                                        ->get()->result();

        $sub_golongan_lingkungan                = (object)[];
        $sub_golongan_lingkungan->active        = 1;
        $sub_golongan_lingkungan->delete        = 0;
        $sub_golongan_lingkungan->source_table  = $source;
        foreach ($sub_golongan_lingkungan_tmp as $v) {
            $double = $this->db->select("sub_golongan.id")
                                ->from("sub_golongan")
                                ->join("jenis_golongan",
                                        "jenis_golongan.id = sub_golongan.jenis_golongan_id")
                                ->where("jenis_golongan.project_id",$project_id)
                                ->where("sub_golongan.source_table",$sub_golongan_lingkungan->source_table)
                                ->where("sub_golongan.source_id",$v->subgol_id)
                                ->where("sub_golongan.range_flag",1)
                                ->where("sub_golongan.range_id",$v->range_lingkungan_id)
                                ->get()->num_rows();
            if($double == 0){
                $sub_golongan_lingkungan->code              = $v->kode;
                $sub_golongan_lingkungan->jenis_golongan_id = $v->golongan_id;
                $sub_golongan_lingkungan->name              = "IPL - ".$v->nama;
                $sub_golongan_lingkungan->minimum_pemakaian = $v->nilai_min;
                $sub_golongan_lingkungan->minimum_rp        = $v->nilai_min_rp;
                $sub_golongan_lingkungan->administrasi      = $v->nilai_admin;
                $sub_golongan_lingkungan->service_id        = $v->service_id;
                $sub_golongan_lingkungan->range_flag        = 1;
                $sub_golongan_lingkungan->range_id          = $v->range_lingkungan_id;
                $sub_golongan_lingkungan->source_id         = $v->subgol_id;
                $this->db->insert("sub_golongan",$sub_golongan_lingkungan);
            }
        }
        return $this->db->from('sub_golongan')->count_all_results()-$jumlah_awal;

    }
    public function get_sub_golongan_air($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('sub_golongan')->count_all_results();

        $sub_golongan_air_tmp = $this->db
                                        ->select("
                                                m_custwtp.gol_id,
                                                m_custwtp.subgol_id,
                                                jenis_golongan.id as golongan_id,
                                                service.id as service_id,
                                                m_golsub.kode,
                                                m_golsub.nama,
                                                m_golsub.nilai_min,
                                                m_golsub.nilai_min_rp,
                                                m_golsub.nilai_admin,
                                                m_custwtp.nilai_aman,
                                                m_custwtp.nilai_sampah,
                                                isnull(range_air.id,0) as range_air_id
                                            ")
                                        ->from("$source.dbo.m_custwtp")
                                        ->join("$source.dbo.m_golsub",
                                                "m_golsub.subgol_id = m_custwtp.subgol_id",
                                                "LEFT")
                                        ->join("range_air",
                                                "range_air.source_table = '$source'
                                                AND range_air.source_id = m_custwtp.rangeair_id",
                                                "LEFT")
                                        ->join("jenis_golongan",
                                                "jenis_golongan.source_table = '$source'
                                                AND jenis_golongan.source_id = m_custwtp.gol_id
                                                ")
                                        ->join("service", 
                                                "service.project_id = $project_id
                                                AND service.service_jenis_id = 2
                                                ")
                                        ->where_not_in("m_custwtp.flag_id",[0,9])
                                        ->where("(rangeair_id!=0 or rangebankav_id!=0)")
                                        ->order_by("subgol_id")
                                        ->distinct()
                                        ->get()->result();

        $sub_golongan_air               = (object)[];
        $sub_golongan_air->active       = 1;
        $sub_golongan_air->delete       = 0;
        $sub_golongan_air->source_table = $source;
        foreach ($sub_golongan_air_tmp as $v) {
            $double = $this->db->select("sub_golongan.id")
                                ->from("sub_golongan")
                                ->join("jenis_golongan",
                                        "jenis_golongan.id = sub_golongan.jenis_golongan_id")
                                ->where("jenis_golongan.project_id",$project_id)
                                ->where("sub_golongan.source_table",$sub_golongan_air->source_table)
                                ->where("sub_golongan.source_id",$v->subgol_id)
                                ->where("sub_golongan.range_flag",2)
                                ->where("sub_golongan.range_id",$v->range_air_id)
                                ->get()->num_rows();
            if($double == 0){
                $sub_golongan_air->code              = $v->kode;
                $sub_golongan_air->jenis_golongan_id = $v->golongan_id;
                $sub_golongan_air->name              = "Air - ".$v->nama;
                $sub_golongan_air->minimum_pemakaian = $v->nilai_min;
                $sub_golongan_air->minimum_rp        = $v->nilai_min_rp;
                $sub_golongan_air->administrasi      = $v->nilai_admin;
                $sub_golongan_air->service_id        = $v->service_id;
                $sub_golongan_air->range_flag        = 2;
                $sub_golongan_air->range_id          = $v->range_air_id;
                $sub_golongan_air->source_id         = $v->subgol_id;
                    
                $this->db->insert("sub_golongan",$sub_golongan_air);
            }
        }
        return $this->db->from('sub_golongan')->count_all_results()-$jumlah_awal;

    }
    public function get_kawasan($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('kawasan')->count_all_results();

        $kawasan_tmp = $this->db
                    ->select("
                            m_kawasan.kawasan_id,
                            m_kawasan.kode,
                            m_kawasan.note,
                            m_kawasan.nama             
                        ")
                    ->from("$source.dbo.m_kawasan")
                    ->order_by("m_kawasan.kawasan_id")
                    ->distinct()
                    ->get()->result();
                    
        $kawasan = (object)[];
        $kawasan->project_id   = $project_id;
        $kawasan->active       = 1;
        $kawasan->delete       = 0;
        $kawasan->source_table = $source;
        $kawasan->name         = "Ciputra";
        $kawasan->code         = "Ciputra";
        $kawasan->description  = "unit tanpa kawasan";
        $kawasan->source_id    = 0;
        $double = $this->db->select("id")
                                ->from("kawasan")
                                ->where("project_id",$kawasan->project_id)
                                ->where("source_table",$kawasan->source_table)
                                ->where("source_id",0)
                                ->get()->num_rows();
        if($double == 0)    $this->db->insert("kawasan",$kawasan);
        foreach ($kawasan_tmp as $v) {
    
            $double = $this->db->select("id")
                                ->from("kawasan")
                                ->where("project_id",$kawasan->project_id)
                                ->where("source_table",$kawasan->source_table)
                                ->where("source_id",$v->kawasan_id)
                                ->get()->num_rows();
            if($double == 0){
                $kawasan->name         = $v->nama;
                $kawasan->code         = $v->kode;
                $kawasan->description  = $v->note;
                $kawasan->source_id    = $v->kawasan_id;
            
                $this->db->insert("kawasan",$kawasan);
            }
        }
        return $this->db->from('kawasan')->count_all_results()-$jumlah_awal;


    }
    public function get_blok($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('blok')->count_all_results();

        $blok_tmp = $this->db
                    ->select("
                            CASE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok))
                                WHEN '' THEN 'Ciputra'
                                ELSE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok)) 
                            END as nama,
                            m_custwtp.kawasan_id,
                            kawasan.id
                        ")
                    ->from("$source.dbo.m_custwtp")
                    ->join("kawasan",
                            "kawasan.source_table = '$source'
                            AND kawasan.source_id = m_custwtp.kawasan_id")
                    ->where_not_in("m_custwtp.flag_id",[0,9])
                    ->order_by("nama")
                    ->distinct()
                    ->get()->result();
        
        $blok = (object)[];
        $blok->active       = 1;
        $blok->delete       = 0;
        $blok->source_table = $source;

        $blok->source_id    = 0;
        foreach ($blok_tmp as $v) {
    
            $double = $this->db->select("blok.id")
                                ->from("blok")
                                ->join("kawasan",
                                        "kawasan.id = blok.kawasan_id")
                                ->where("kawasan.project_id",$project_id)
                                ->where("blok.source_table",$blok->source_table)
                                ->where("blok.source_id",$v->kawasan_id)
                                ->where("blok.name",$v->nama)
                                ->get()->num_rows();
            if($double == 0){
                $blok->name         = $v->nama;
                $blok->kawasan_id   = $v->id;
                $blok->code         = $v->nama;
                $blok->source_id    = $v->kawasan_id;
                $this->db->insert("blok",$blok);
            }
        }
        return $this->db->from('blok')->count_all_results()-$jumlah_awal;

    }
    public function get_customer($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('customer')->count_all_results();

        $customer_tmp = $this->db
                    ->select("
                            m_custwtp.cust_id,
                            m_custwtp.nama,
                            m_custwtp.alamat,
                            m_custwtp.email,
                            m_custwtp.handphone,
                            m_custwtp.telp1,
                            m_custwtp.telp2,
                            m_custwtp.nomor_npwp,
                            m_custwtp.npwp_nama,
                            m_custwtp.npwp_alamat,
                            m_custwtp.catatan
                        ")
                    ->from("$source.dbo.m_custwtp")
                    ->where_not_in("m_custwtp.flag_id",[0,9])

                    ->order_by("cust_id")
                    ->distinct()
                    ->get()->result();

        $customer = (object)[];
        $customer->active       = 1;
        $customer->delete       = 0;
        $customer->source_table = $source;
        $customer->project_id   = $project_id;
        $customer->unit         = 1;
        $project_code          = $this->db->select("code")
                                    ->from("project")
                                    ->where("id",$project_id)
                                    ->get()->row()->code;
        foreach ($customer_tmp as $v) {

            $double = $this->db->select("id")
            ->from("customer")
            ->where("project_id",$project_id)
            ->where("source_table",$customer->source_table)
            ->where("source_id",$v->cust_id)                                
            ->get()->num_rows();
            if($double == 0){
                $customer->code         = "CUST/$project_code/".strtoupper($source)."/$v->cust_id";
                $customer->name         = $v->nama;
                $customer->address      = $v->alamat;
                $customer->email        = $v->email;
                $customer->mobilephone1 = $v->handphone;
                $customer->mobilephone2 = $v->telp2;
                $customer->homephone    = $v->telp1;
                $customer->npwp_no      = $v->nomor_npwp;
                $customer->npwp_name    = $v->npwp_nama;
                $customer->npwp_address = $v->npwp_alamat;
                $customer->description  = $v->catatan;
                $customer->source_id    = $v->cust_id;
                $this->db->insert("customer",$customer);

            }
        }
        return $this->db->from('customer')->count_all_results()-$jumlah_awal;

    }
    public function get_unit($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('unit')->count_all_results();

        $unit_tmp = $this->db
                    ->select("
                            m_custwtp.cust_id,
                            blok.id as blok_id,
                            CASE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok))
                                    WHEN '' THEN '-'
                                    ELSE SUBSTRING(m_custwtp.kode_blok,CHARINDEX('/',  m_custwtp.kode_blok)+1,len(kode_blok)) 
                            END as no_unit,
                            customer.id as customer_id,
                            m_custwtp.luas_tanah,
                            m_custwtp.luas_bangunan,
                            m_custwtp.tanggal_sterima,
                            m_custwtp.tanggal_bangun,
                            jenis_golongan.id as golongan_id
                        ")
                    ->from("$source.dbo.m_custwtp")
                    ->join("blok",
                            "blok.code = CASE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok)) WHEN '' THEN 'Ciputra' ELSE SUBSTRING(m_custwtp.kode_blok,0,CHARINDEX('/',  m_custwtp.kode_blok)) END
                            AND blok.source_table = '$source'
                            AND blok.source_id = m_custwtp.kawasan_id")
                    ->join("customer",
                            "customer.source_table = '$source'
                            AND customer.source_id = m_custwtp.cust_id")
                    ->join("jenis_golongan",
                            "jenis_golongan.source_table = '$source'
                            AND jenis_golongan.source_id = m_custwtp.gol_id")
                    ->where_not_in("m_custwtp.flag_id",[0,9])
                    ->order_by("cust_id")
                    ->distinct()
                    ->get()->result();
        
        
        $unit = (object)[];
        $unit->active           = 1;
        $unit->delete           = 0;
        $unit->source_table     = $source;
        $unit->project_id       = $project_id;
        $unit->status_tagihan   = 1;
        $unit->kirim_tagihan    = 1;

        foreach ($unit_tmp as $v) {
            $double = $this->db->select("id")
            ->from("unit")
            ->where("project_id",$project_id)
            ->where("source_table",$unit->source_table)
            ->where("source_id",$v->cust_id)                                
            ->get()->num_rows();
            if($double == 0) {
                $unit->blok_id              = $v->blok_id;
                $unit->no_unit              = $v->no_unit;
                $unit->pemilik_customer_id  = $v->customer_id;
                $unit->penghuni_customer_id = $v->customer_id;
                $unit->luas_tanah           = $v->luas_tanah;
                $unit->luas_bangunan        = $v->luas_bangunan;
                $unit->tgl_st               = $v->tanggal_sterima;
                $unit->tgl_bangun           = $v->tanggal_bangun;
                $unit->gol_id               = $v->golongan_id;
                $unit->source_id            = $v->cust_id;
                $this->db->insert("unit",$unit);

            }
        }
        return $this->db->from('unit')->count_all_results()-$jumlah_awal;

    }
    public function get_unit_lingkungan($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('unit_lingkungan')->count_all_results();


        $unit_lingkungan_tmp = $this->db
                    ->select("
                            cust_id,
                            tanggal_link,
                            tgl_mandiri,
                            sub_golongan.id as sub_gol_id,
                            unit.id as unit_id
                        ")
                    ->from("$source.dbo.m_custwtp")
                    ->join("unit",
                            "unit.source_table = '$source'
                            AND unit.source_id = m_custwtp.cust_id")
                    ->join("range_lingkungan",
                            "range_lingkungan.source_table = '$source'
                            AND range_lingkungan.source_id = m_custwtp.rangebankav_id
                            AND range_lingkungan.keamanan = m_custwtp.nilai_aman
                            AND range_lingkungan.kebersihan = m_custwtp.nilai_sampah")
                    ->join("sub_golongan",
                            "sub_golongan.source_table = '$source'
                            AND sub_golongan.source_id = m_custwtp.subgol_id
                            AND sub_golongan.range_flag = 1
                            AND sub_golongan.range_id = range_lingkungan.id")
                    ->where_not_in("m_custwtp.flag_id",[0,9])
                    ->order_by("cust_id")
                    ->distinct()
                    ->get()->result();
        
        
        $unit_lingkungan = (object)[];
        $unit_lingkungan->aktif            = 1;

        foreach ($unit_lingkungan_tmp as $v) {
            $double = $this->db->select("id")
            ->from("unit_lingkungan")
            ->where("unit_id",$v->unit_id)
            ->where("sub_gol_id",$v->sub_gol_id)
            ->get()->num_rows();

            if($double == 0) {
                $unit_lingkungan->unit_id               = $v->unit_id;
                $unit_lingkungan->tgl_aktif             = $v->tanggal_link;
                $unit_lingkungan->sub_gol_id            = $v->sub_gol_id;
                $unit_lingkungan->tgl_mandiri           = $v->tgl_mandiri;
                
                $this->db->insert("unit_lingkungan",$unit_lingkungan);

            }
        }
        return $this->db->from('unit_lingkungan')->count_all_results()-$jumlah_awal;

    }
    public function get_unit_air($project_id,$source,$formula_air,$formula_bangunan,$formula_kavling){
        $jumlah_awal = $this->db->from('unit_air')->count_all_results();


        $unit_air_tmp = $this->db
                    ->select("
                            cust_id,
                            tanggal_pasang,
                            sub_golongan.id as sub_gol_id,
                            unit.id as unit_id,
                            m_custwtp.nomor_meter
                        ")
                    ->from("$source.dbo.m_custwtp")
                    ->join("unit",
                            "unit.source_table = '$source'
                            AND unit.source_id = m_custwtp.cust_id")
                    ->join("range_air",
                            "range_air.source_table = '$source'
                            AND range_air.source_id = m_custwtp.rangeair_id")
                    ->join("sub_golongan",
                            "sub_golongan.source_table = '$source'
                            AND sub_golongan.source_id = m_custwtp.subgol_id
                            AND sub_golongan.range_flag = 2
                            AND sub_golongan.range_id = range_air.id")
                    ->where_not_in("m_custwtp.flag_id",[0,9])
                    ->order_by("cust_id")
                    ->distinct()
                    ->get()->result();
        
        
        $unit_air = (object)[];
        $unit_air->aktif            = 1;

        foreach ($unit_air_tmp as $v) {
            $double = $this->db->select("id")
            ->from("unit_air")
            ->where("unit_id",$v->unit_id)
            ->where("sub_gol_id",$v->sub_gol_id)
            ->get()->num_rows();

            if($double == 0) {
                $unit_air->unit_id               = $v->unit_id;
                $unit_air->tgl_aktif             = $v->tanggal_pasang;
                $unit_air->sub_gol_id            = $v->sub_gol_id;
                $unit_air->no_seri_meter           = $v->nomor_meter;
                
                $this->db->insert("unit_air",$unit_air);

            }
            
        }
        return $this->db->from('unit_air')->count_all_results()-$jumlah_awal;

    }
}
