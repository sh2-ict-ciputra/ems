<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_tagihan extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM kawasan where project_id = $project->id and [delete] = 0 order by id desc
        ");

        return $query->result_array();
    }
    public function get_unit_by_id($id)
    {
        $project = $this->m_core->project();
        $result = $this->db
                    ->select("
                        kawasan.name as kawasan,
                        blok.name as blok,
                        unit.no_unit,
                        customer.name as pemilik,
                        unit.tgl_st
                    ")
                    ->join("blok","blok.id = unit.blok_id")
                    ->join("kawasan","kawasan.id = blok.kawasan_id")
                    ->join("customer","customer.id = unit.pemilik_customer_id")
                    ->where("unit.id",$id)
                    ->where("unit.project_id",$project->id)
                    ->get("unit")
                    ->row();
        return $result;
    }

    public function get_kawasan()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                kawasan.name as kawasan_name, 
                kawasan.code as kawasan_code, 
                kawasan.id 
            FROM  kawasan  
            WHERE kawasan.active = 1 
            AND kawasan.project_id= $project->id 
            AND kawasan.[delete] = 0 
            ORDER BY kawasan.name asc
        
        ");
        $result = $query->result();
        for ($i=0; $i < count($result); $i++) { 
            $result[$i]->total_tagihan = 0 ;
        }
        return $result;
    }
    public function get_blok()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                kawasan.id as kawasan_id,
                kawasan.name as kawasan_name,
                blok.name as blok_name, 
                blok.code as blok_code, 
                blok.id
            FROM blok  
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
                AND kawasan.active = 1
                AND kawasan.[delete] = 0
                AND kawasan.project_id= $project->id 
            WHERE blok.active =1 
            AND blok.[delete] = 0 
            ORDER BY blok.name asc
        ");

        $result = $query->result();
        for ($i=0; $i < count($result); $i++) { 
            $result[$i]->total_tagihan = 0 ;
        }
        return $result;
    }
    public function ajax_get_unit($blok_id,$periode)
    {
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
                ISNULL(v_tagihan_air.total_tagihan,0)+ISNULL(v_tagihan_lingkungan.total_tagihan ,0) as now_tagihan,
                SUM(isnull(v_tagihan_air_lama.total_tagihan,0) + isnull(v_tagihan_air_lama.denda,0) + 
                isnull(v_tagihan_lingkungan_lama.total_tagihan,0) + isnull(v_tagihan_lingkungan_lama.denda,0)) as tunggakan,		
                count(v_tagihan_air_lama.periode) as tunggakan_bulan,		
                unit.id as unit_id,
                unit.no_unit as unit_no,
                pemilik.name as pemilik,
                isnull(v_tagihan_air.denda,0) + isnull(v_tagihan_lingkungan.denda,0) as old_denda,
                0 as now_penalti,
                CASE 
                        WHEN v_tagihan_air.periode IS NOT NULL THEN v_tagihan_air.periode
                        WHEN v_tagihan_lingkungan.periode IS NOT NULL THEN v_tagihan_lingkungan.periode
                END as periode,
                ISNULL(v_tagihan_air.total_tagihan,0)+ISNULL(v_tagihan_lingkungan.total_tagihan ,0)+
                SUM(isnull(v_tagihan_air_lama.total_tagihan,0) + isnull(v_tagihan_air_lama.denda,0) + 
                    isnull(v_tagihan_lingkungan_lama.total_tagihan,0) + isnull(v_tagihan_lingkungan_lama.denda,0))+
                isnull(v_tagihan_air.denda,0) + isnull(v_tagihan_lingkungan.denda,0) as total
        FROM v_tagihan_air
        LEFT JOIN (
                    SELECT * FROM v_tagihan_air
                    WHERE status_bayar_flag = 0
                    AND periode < '$periode'
                ) as v_tagihan_air_lama
                ON v_tagihan_air_lama.unit_id = v_tagihan_air.unit_id
        FULL JOIN v_tagihan_lingkungan
                ON v_tagihan_lingkungan.unit_id = v_tagihan_air.unit_id
                AND v_tagihan_lingkungan.periode = v_tagihan_air.periode
        LEFT JOIN (
                    SELECT * FROM v_tagihan_lingkungan
                    WHERE status_bayar_flag = 0
                    AND periode < '$periode'
                ) as v_tagihan_lingkungan_lama
                ON v_tagihan_lingkungan_lama.unit_id = v_tagihan_lingkungan.unit_id
        JOIN unit
                ON unit.id = v_tagihan_lingkungan.unit_id 
                OR unit.id = v_tagihan_air.unit_id 
        JOIN customer as pemilik
                ON pemilik.id = unit.pemilik_customer_id
        WHERE unit.blok_id = $blok_id
        AND CASE 
                        WHEN v_tagihan_air.periode IS NOT NULL THEN v_tagihan_air.periode
                        WHEN v_tagihan_lingkungan.periode IS NOT NULL THEN v_tagihan_lingkungan.periode
                        END  = '$periode'
        GROUP BY 	v_tagihan_air.total_tagihan,
                    v_tagihan_lingkungan.total_tagihan,
                    unit.id,
                    unit.no_unit,
                    pemilik.name,
                    v_tagihan_air.periode,
                    v_tagihan_air.denda,
                    v_tagihan_lingkungan.periode,
                    v_tagihan_lingkungan.denda
        ORDER By unit.no_unit
                ");
        // FULL JOIN v_tagihan_lingkungan
        // ON v_tagihan_lingkungan.unit_id = v_tagihan_air.unit_id
        // AND v_tagihan_lingkungan.periode = v_tagihan_air.periode
        $result = $query->result();
        // for ($i=0; $i < count($result); $i++) { 
        //     if ($result[$i]->total_tagihan ==null){
        //         $result[$i]->total_tagihan = 0;
        //     }
        // }
        return $result;
    }
    public function ajax_get_unit2($blok_id,$periode)
    {
        $periode = substr($periode, 3, 4)."-".substr($periode, 0, 2) . "-01";
        $project = $this->m_core->project();
        
        $tagihan_tmp = $this->db
                            ->select("
                                t_tagihan.unit_id,
                                unit.no_unit,
                                customer.name as pemilik,
                                t_tagihan.periode,
                                t_tagihan.proyek_id,
                                
                                CASE 
                                    WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda
                                    ELSE
                                        CASE 
                                        WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service 
										WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service *(DateDiff( MONTH, v_tagihan_air.periode, '$periode' ) + IIF(".date("d").">=service_air.denda_tanggal_jt,1,0) - service_air.denda_selisih_bulan)
										WHEN v_tagihan_air.denda_jenis_service = 3 THEN (v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) *(DateDiff( MONTH, v_tagihan_air.periode, '$periode' ) + IIF(".date("d").">=service_air.denda_tanggal_jt,1,0) - service_air.denda_selisih_bulan)
                                    END 
                                END as air_nilai_denda,
                                CASE
                                    WHEN (t_tagihan.periode < '$periode' AND v_tagihan_air.status_tagihan = 0 )THEN 1
                                    ELSE 0 
                                END as air_status_tunggakan,
                                v_tagihan_air.total as air_tagihan,
                                v_tagihan_air.status_tagihan as air_status_tagihan,
                                
                                
                                v_tagihan_lingkungan.nilai_denda as lingkungan_nilai_denda,
                                CASE 
                                    WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda
                                    WHEN t_tagihan.periode <= unit_lingkungan.tgl_mulai_denda THEN 0 
                                    ELSE
                                        CASE 
                                            WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service 
                                            WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service* (DateDiff( MONTH, v_tagihan_air.periode, '$periode' ) + IIF(".date("d").">=service_lingkungan.denda_tanggal_jt,1,0) - service_lingkungan.denda_selisih_bulan)
                                            WHEN v_tagihan_air.denda_jenis_service = 3 THEN ( v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) * (DateDiff( MONTH, v_tagihan_air.periode, '$periode' ) + IIF(".date("d").">=service_lingkungan.denda_tanggal_jt,1,0) - service_lingkungan.denda_selisih_bulan)
                                    END 
                                END as lingkungan_nilai_denda,
                                CASE
                                    WHEN (t_tagihan.periode < '$periode' AND v_tagihan_lingkungan.status_tagihan = 0 )THEN 1
                                    ELSE 0 
                                END as lingkungan_status_tunggakan,
                                v_tagihan_lingkungan.total as lingkungan_tagihan,
                                v_tagihan_lingkungan.status_tagihan as lingkungan_status_tagihan
                            ")
                            ->from("t_tagihan")
                            ->join("unit",
                                "unit.id = t_tagihan.unit_id")
                            ->join("customer",
                                "customer.id = unit.pemilik_customer_id")
                            ->join("v_tagihan_air",
                                "v_tagihan_air.t_tagihan_id = t_tagihan.id
                                AND (v_tagihan_air.periode = '$periode' or (v_tagihan_air.periode <'$periode' AND v_tagihan_air.status_tagihan = 0))
                                ",
                                "LEFT")
                            ->join("v_tagihan_lingkungan",
                                "v_tagihan_lingkungan.t_tagihan_id = t_tagihan.id
                                AND (v_tagihan_lingkungan.periode = '$periode' or (v_tagihan_lingkungan.periode <'$periode' AND v_tagihan_lingkungan.status_tagihan = 0))
                                ",
                                "LEFT")
                            ->join("unit_lingkungan",
                                "unit_lingkungan.unit_id = unit.id")
                            ->join("service as service_air",
                                "service_air.service_jenis_id = 2
                                AND service_air.project_id = $project->id")
                            ->join("service as service_lingkungan",
                                "service_lingkungan.service_jenis_id = 1
                                AND service_lingkungan.project_id = $project->id")
                                           
                            ->where("t_tagihan.proyek_id",$project->id)
                            ->where("unit.blok_id",$blok_id)
                            ->where("(t_tagihan.periode = '$periode' OR (t_tagihan.periode < '$periode' AND (v_tagihan_air.status_tagihan = 0 OR v_tagihan_lingkungan.status_tagihan = 0)))")
                            ->order_by("unit.no_unit,t_tagihan.periode")
                            ->get()->result();
        $tagihan = [];
        $tagihan[0] = (object)[];
        $tagihan[0]->no_unit            = "";
        $tagihan[0]->type_unit          = "-";
        $tagihan[0]->pemilik            = "";
        $tagihan[0]->tunggakan          = 0;
        $tagihan[0]->tunggakan_bulan    = 0;
        $tagihan[0]->denda              = 0;
        $tagihan[0]->tagihan            = 0;
        if($tagihan_tmp){
            $tagihan[0]->unit_id            = $tagihan_tmp[0]->unit_id;
            $tagihan[0]->no_unit            = $tagihan_tmp[0]->no_unit;
            $tagihan[0]->type_unit          = "-";
            $tagihan[0]->pemilik            = $tagihan_tmp[0]->pemilik;
            
            $tagihan[0]->tunggakan          = $tagihan_tmp[0]->air_status_tunggakan == 1 ? $tagihan_tmp[0]->air_tagihan : 0;
            $tagihan[0]->tunggakan          += $tagihan_tmp[0]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[0]->lingkungan_tagihan : 0;
            
            
            $tagihan[0]->tunggakan_bulan    = $tagihan_tmp[0]->air_status_tunggakan==1 || $tagihan_tmp[0]->lingkungan_status_tunggakan == 1 ? 1 : 0;
            
            $tagihan[0]->denda              = $tagihan_tmp[0]->air_nilai_denda;
            $tagihan[0]->denda             += $tagihan_tmp[0]->lingkungan_nilai_denda;
            
            $tagihan[0]->tagihan            = $tagihan_tmp[0]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->air_tagihan;
            $tagihan[0]->tagihan           += $tagihan_tmp[0]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->lingkungan_tagihan;

            $j = 0;
            for ($i=1; $i < count($tagihan_tmp); $i++) { 
                if($tagihan_tmp[$i]->unit_id == $tagihan_tmp[$i-1]->unit_id){
                    $tagihan[$j]->denda             += $tagihan_tmp[$i]->air_nilai_denda;
                    $tagihan[$j]->denda             += $tagihan_tmp[$i]->lingkungan_nilai_denda;

                    $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->air_status_tunggakan == 1 ? $tagihan_tmp[$i]->air_tagihan : 0;
                    $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[$i]->lingkungan_tagihan : 0;

                    $tagihan[$j]->tagihan           += $tagihan_tmp[$i]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->air_tagihan;
                    $tagihan[$j]->tagihan           += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->lingkungan_tagihan;
                    $tagihan[$j]->tunggakan_bulan   += $tagihan_tmp[$i]->air_status_tunggakan==1 || $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? 1 : 0;

                }else{

                    $tagihan[$j]->total =   $tagihan[$j]->tunggakan 
                                            + $tagihan[$j]->denda 
                                            + $tagihan[$j]->tagihan;
                    $j++;
                    
                    $tagihan[$j] = (object)[];
                    $tagihan[$j]->unit_id            = $tagihan_tmp[$i]->unit_id;

                    $tagihan[$j]->no_unit            = $tagihan_tmp[$i]->no_unit;
                    $tagihan[$j]->type_unit          = "-";
                    $tagihan[$j]->pemilik            = $tagihan_tmp[$i]->pemilik;
                    
                    $tagihan[$j]->tunggakan          = $tagihan_tmp[$i]->air_status_tunggakan == 1 ? $tagihan_tmp[$i]->air_tagihan : 0;
                    $tagihan[$j]->tunggakan          += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[$i]->lingkungan_tagihan : 0;
            
                    $tagihan[$j]->tunggakan_bulan    = $tagihan_tmp[$i]->air_status_tunggakan==1 || $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? 1 : 0;

                    $tagihan[$j]->denda              = $tagihan_tmp[$i]->air_nilai_denda;
                    $tagihan[$j]->denda             += $tagihan_tmp[$i]->lingkungan_nilai_denda;
                    
                    $tagihan[$j]->tagihan            = $tagihan_tmp[$i]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->air_tagihan;
                    $tagihan[$j]->tagihan           += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->lingkungan_tagihan;
                }
            }
            $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
        }
        return $tagihan;
        
    }
    public function ajax_get_unit_lingkungan($blok_id,$periode)
    {
        
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
        $tagihan_tmp = $this->db
                            ->select("
                                proyek_id,
                                no_unit,
                                periode,
                                kawasan_id,
                                kawasan,
                                blok_id,
                                blok,
                                pemilik,
                                nilai_bangunan,
                                nilai_kavling,
                                CASE 
                                    WHEN nilai_denda_flag = 1 THEN nilai_denda
                                    ELSE
                                        CASE 
                                            WHEN denda_jenis_service = 1 THEN denda_nilai_service
                                            WHEN denda_jenis_service = 2 THEN denda_nilai_service*DateDiff(MONTH,periode,'$periode')
                                            WHEN denda_jenis_service = 3 THEN (denda_nilai_service*total/100)*DateDiff(MONTH,periode,'$periode')
                                    END 
                                END as nilai_denda,
                                nilai_keamanan,
                                nilai_kebersihan,
                                ppn_flag,
                                sub_total_1,
                                nilai_administrasi,
                                ppn_sc_flag,
                                sub_total_2,
                                nilai_denda_flag,
                                status_tagihan,
                                tagihan_id,
                                unit_id,
                                total,
                                CASE
                                    WHEN (periode < '$periode' AND status_tagihan = 0 )THEN 1
                                    ELSE 0 
                                END as status_tunggakan")
                            ->from("v_tagihan_lingkungan")
                            ->where("blok_id",$blok_id)
                            ->where("proyek_id",$project->id)
                            ->where("(periode = '$periode'")
                            ->or_where("(periode < '$periode' AND status_tagihan = 0))")
                            ->order_by("no_unit,periode")
                            ->get()->result();
        $tagihan = [];
        $tagihan[0] = (object)[];
        $tagihan[0]->no_unit            = "";
        $tagihan[0]->type_unit          = "-";
        $tagihan[0]->pemilik            = "";
        $tagihan[0]->tunggakan          = 0;
        $tagihan[0]->tunggakan_bulan    = 0;
        $tagihan[0]->denda              = 0;
        $tagihan[0]->tagihan            = 0;
        if($tagihan_tmp){
            $tagihan[0]->no_unit            = $tagihan_tmp[0]->no_unit;
            $tagihan[0]->type_unit          = "-";
            $tagihan[0]->pemilik            = $tagihan_tmp[0]->pemilik;
            $tagihan[0]->tunggakan          = $tagihan_tmp[0]->status_tunggakan == 1 ? $tagihan_tmp[0]->total : 0;
            $tagihan[0]->tunggakan_bulan    = $tagihan_tmp[0]->status_tunggakan == 1 ? 1 : 0;
            $tagihan[0]->denda              = $tagihan_tmp[0]->nilai_denda;
            $tagihan[0]->tagihan            = $tagihan_tmp[0]->status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->total;
            $j = 0;
            for ($i=1; $i < count($tagihan_tmp); $i++) { 
                if($tagihan_tmp[$i]->unit_id == $tagihan_tmp[$i-1]->unit_id){
                    $tagihan[$j]->denda             +=$tagihan_tmp[$i]->nilai_denda;
                    if($tagihan_tmp[$i]->status_tunggakan==1){
                        $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->total;
                        $tagihan[$j]->tunggakan_bulan   ++;
                    }else{
                        $tagihan[$j]->tagihan = $tagihan_tmp[$i]->total;
                    }
                }else{
                    $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
                    $j++;
                    $tagihan[$j] = (object)[];
                    $tagihan[$j]->no_unit            = $tagihan_tmp[$i]->no_unit;
                    $tagihan[$j]->type_unit          = "-";
                    $tagihan[$j]->pemilik            = $tagihan_tmp[$i]->pemilik;
                    $tagihan[$j]->tunggakan          = $tagihan_tmp[$i]->status_tunggakan == 1 ? $tagihan_tmp[$i]->total : 0;
                    $tagihan[$j]->tunggakan_bulan    = $tagihan_tmp[$i]->status_tunggakan == 1 ? 1 : 0;
                    $tagihan[$j]->denda              = $tagihan_tmp[$i]->nilai_denda;
                    $tagihan[$j]->tagihan            = $tagihan_tmp[$i]->status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->total;
                }
            }
            $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
        }
        return $tagihan;
        
    }
    public function ajax_get_unit_air($blok_id,$periode)
    {
        
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
        $tagihan_tmp = $this->db
                            ->select("
                                proyek_id,
                                no_unit,
                                periode,
                                kawasan_id,
                                kawasan,
                                blok_id,
                                blok,
                                pemilik,
                                nilai,
                                CASE 
                                    WHEN nilai_denda_flag = 1 THEN nilai_denda
                                    ELSE
                                        CASE 
                                            WHEN denda_jenis_service = 1 THEN denda_nilai_service
                                            WHEN denda_jenis_service = 2 THEN denda_nilai_service*DateDiff(MONTH,periode,'$periode')
                                            WHEN denda_jenis_service = 3 THEN (denda_nilai_service*total/100)*DateDiff(MONTH,periode,'$periode')
                                    END 
                                END as nilai_denda,
                                ppn_flag,
                                sub_total_1,
                                nilai_administrasi,
                                sub_total_2,
                                nilai_denda_flag,
                                status_tagihan,
                                tagihan_id,
                                unit_id,
                                total,
                                CASE
                                    WHEN (periode < '$periode' AND status_tagihan = 0 )THEN 1
                                    ELSE 0 
                                END as status_tunggakan")
                            ->from("v_tagihan_air")
                            ->where("blok_id",$blok_id)
                            ->where("proyek_id",$project->id)
                            ->where("(periode = '$periode'")
                            ->or_where("(periode < '$periode' AND status_tagihan = 0))")
                            ->order_by("no_unit,periode")
                            ->get()->result();
        
        $tagihan = [];
        $tagihan[0] = (object)[];
        $tagihan[0]->no_unit            = "";
        $tagihan[0]->type_unit          = "-";
        $tagihan[0]->pemilik            = "";
        $tagihan[0]->tunggakan          = 0;
        $tagihan[0]->tunggakan_bulan    = 0;
        $tagihan[0]->denda              = 0;
        $tagihan[0]->tagihan            = 0;
        if($tagihan_tmp){
            $tagihan[0]->no_unit            = $tagihan_tmp[0]->no_unit;
            $tagihan[0]->type_unit          = "-";
            $tagihan[0]->pemilik            = $tagihan_tmp[0]->pemilik;
            $tagihan[0]->tunggakan          = $tagihan_tmp[0]->status_tunggakan == 1 ? $tagihan_tmp[0]->total : 0;
            $tagihan[0]->tunggakan_bulan    = $tagihan_tmp[0]->status_tunggakan == 1 ? 1 : 0;
            $tagihan[0]->denda              = $tagihan_tmp[0]->nilai_denda;
            $tagihan[0]->tagihan            = $tagihan_tmp[0]->status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->total;
            $j = 0;
            for ($i=1; $i < count($tagihan_tmp); $i++) { 
                if($tagihan_tmp[$i]->unit_id == $tagihan_tmp[$i-1]->unit_id){
                    $tagihan[$j]->denda             +=$tagihan_tmp[$i]->nilai_denda;
                    if($tagihan_tmp[$i]->status_tunggakan==1){
                        $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->total;
                        $tagihan[$j]->tunggakan_bulan   ++;
                    }else{
                        $tagihan[$j]->tagihan = $tagihan_tmp[$i]->total;
                    }
                }else{
                    $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
                    $j++;
                    $tagihan[$j] = (object)[];
                    $tagihan[$j]->no_unit            = $tagihan_tmp[$i]->no_unit;
                    $tagihan[$j]->type_unit          = "-";
                    $tagihan[$j]->pemilik            = $tagihan_tmp[$i]->pemilik;
                    $tagihan[$j]->tunggakan          = $tagihan_tmp[$i]->status_tunggakan == 1 ? $tagihan_tmp[$i]->total : 0;
                    $tagihan[$j]->tunggakan_bulan    = $tagihan_tmp[$i]->status_tunggakan == 1 ? 1 : 0;
                    $tagihan[$j]->denda              = $tagihan_tmp[$i]->nilai_denda;
                    $tagihan[$j]->tagihan            = $tagihan_tmp[$i]->status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->total;
                }
            }
            $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
            echo("<pre>");
                print_r($tagihan);
            echo("</pre>");
        }
        return $tagihan;
        
    }       
    public function ajax_get_blok($id_kawasan,$periode)
    {
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                SUM(ISNULL(v_tagihan_air.total_tagihan,0) + ISNULL(v_tagihan_lingkungan.total_tagihan ,0)) as total_tagihan,
                blok.id as blok_id,
                blok.code as blok_code,
                blok.name as blok_name,
                Case
                    WHEN v_tagihan_lingkungan.periode IS NOT NULL THEN v_tagihan_lingkungan.periode
                    WHEN v_tagihan_air.periode IS NOT NULL THEN v_tagihan_air.periode
                END as periode
            FROM v_tagihan_air
            FULL JOIN   
                ON v_tagihan_lingkungan.unit_id = v_tagihan_air.unit_id
                AND v_tagihan_lingkungan.periode = v_tagihan_air.periode
            JOIN kawasan
                ON kawasan.id = v_tagihan_air.kawasan_id
                OR kawasan.id = v_tagihan_lingkungan.kawasan_id
            JOIN blok
                ON blok.id = v_tagihan_air.blok_id
                OR blok.id = v_tagihan_lingkungan.blok_id
            WHERE kawasan.project_id = $project->id
            and 
                Case
                    WHEN v_tagihan_lingkungan.periode IS NOT NULL THEN v_tagihan_lingkungan.periode
                    WHEN v_tagihan_air.periode IS NOT NULL THEN v_tagihan_air.periode
                END = '$periode'
            and kawasan.id = $id_kawasan
            GROUP BY 	blok.code,
                        blok.name,
                        blok.id,
                        v_tagihan_lingkungan.periode,
                        v_tagihan_air.periode
            ORDER BY blok.name asc

        ");

        $result = $query->result();
        return $result;
        for ($i=0; $i < count($result); $i++) { 
            $result[$i]->total_tagihan = 0 ;
        }
        
    }
    public function ajax_get_blok4($kawasan_id,$periode)
    {
        
        $periode = substr($periode, 3, 4)."-".substr($periode, 0, 2) . "-01";
        $project = $this->m_core->project();
        
        $tagihan_tmp = $this->db
                            ->select("
                                t_tagihan.unit_id,
                                unit.no_unit,
                                customer.name as pemilik,
                                t_tagihan.periode,
                                t_tagihan.proyek_id,
                                blok.id as blok_id,
                                blok.name as blok,
                                CASE 
                                    WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda
                                    ELSE
                                        CASE 
                                            WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service
                                            WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service*DateDiff(MONTH,t_tagihan.periode,'$periode')
                                            WHEN v_tagihan_air.denda_jenis_service = 3 THEN (v_tagihan_air.denda_nilai_service*v_tagihan_air.total/100)*DateDiff(MONTH,t_tagihan.periode,'$periode')
                                    END 
                                END as air_nilai_denda,
                                CASE
                                    WHEN (t_tagihan.periode < '$periode' AND v_tagihan_air.status_tagihan = 0 )THEN 1
                                    ELSE 0 
                                END as air_status_tunggakan,
                                v_tagihan_air.total as air_tagihan,
                                v_tagihan_air.status_tagihan as air_status_tagihan,
                                
                                
                                v_tagihan_lingkungan.nilai_denda as lingkungan_nilai_denda,
                                CASE 
                                    WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda
                                    WHEN t_tagihan.periode <= unit_lingkungan.tgl_mulai_denda THEN 0 
                                    ELSE
                                        CASE 
                                            WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN v_tagihan_lingkungan.denda_nilai_service
                                            WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN v_tagihan_lingkungan.denda_nilai_service*DateDiff(MONTH,t_tagihan.periode,'$periode')
                                            WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN (v_tagihan_lingkungan.denda_nilai_service*v_tagihan_lingkungan.total/100)*DateDiff(MONTH,t_tagihan.periode,'$periode')
                                    END 
                                END as lingkungan_nilai_denda,
                                CASE
                                    WHEN (t_tagihan.periode < '$periode' AND v_tagihan_lingkungan.status_tagihan = 0 )THEN 1
                                    ELSE 0 
                                END as lingkungan_status_tunggakan,
                                v_tagihan_lingkungan.total as lingkungan_tagihan,
                                v_tagihan_lingkungan.status_tagihan as lingkungan_status_tagihan
                            ")
                            ->from("t_tagihan")
                            ->join("unit",
                                "unit.id = t_tagihan.unit_id")
                            ->join("blok",
                                    "blok.id = unit.blok_id")
                            ->join("customer",
                                "customer.id = unit.pemilik_customer_id")
                            ->join("v_tagihan_air",
                                "v_tagihan_air.t_tagihan_id = t_tagihan.id",
                                "LEFT")
                            ->join("v_tagihan_lingkungan",
                                "v_tagihan_lingkungan.t_tagihan_id = t_tagihan.id",
                                "LEFT")
                            ->join("unit_lingkungan",
                                "unit_lingkungan.unit_id = unit.id")
                            ->where("t_tagihan.proyek_id",$project->id)
                            ->where("blok.kawasan_id",$kawasan_id)
                            ->where("(t_tagihan.periode = '$periode' OR (t_tagihan.periode < '$periode' AND (v_tagihan_air.status_tagihan = 0 OR v_tagihan_lingkungan.status_tagihan = 0)))")
                            ->order_by("unit.blok_id,unit.no_unit,t_tagihan.periode")
                            ->get()->result();
        // echo("<pre>");
        // print_r($tagihan_tmp);
        // echo("</pre>");
        
        $tagihan = [];
        $tagihan[0] = (object)[];
        $tagihan[0]->tunggakan  = 0;
        $tagihan[0]->denda      = 0;
        $tagihan[0]->tagihan    = 0;
        $tagihan[0]->blok       = 0;
        $tagihan[0]->blok_id    = 0;
        $tagihan[0]->unit       = 1;

        if($tagihan_tmp){

            $tagihan[0]->blok       = $tagihan_tmp[0]->blok;
            $tagihan[0]->blok_id    = $tagihan_tmp[0]->blok_id;

            $tagihan[0]->tunggakan  = $tagihan_tmp[0]->air_status_tunggakan == 1 ? $tagihan_tmp[0]->air_tagihan : 0;
            $tagihan[0]->tunggakan  += $tagihan_tmp[0]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[0]->lingkungan_tagihan : 0;
            
            
            $tagihan[0]->denda      =  $tagihan_tmp[0]->air_nilai_denda;
            $tagihan[0]->denda      += $tagihan_tmp[0]->lingkungan_nilai_denda;
            
            $tagihan[0]->tagihan    =  $tagihan_tmp[0]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->air_tagihan;
            $tagihan[0]->tagihan    += $tagihan_tmp[0]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->lingkungan_tagihan;

            $j = 0;
            for ($i=1; $i < count($tagihan_tmp); $i++) { 

                if($tagihan_tmp[$i]->blok_id == $tagihan_tmp[$i-1]->blok_id){
                    // echo("<pre>");
                    //     print_r($tagihan[$j]);
                    // echo("</pre>");
                
                    if($tagihan_tmp[$i]->unit_id != $tagihan_tmp[$i-1]->unit_id){
                        $tagihan[$j]->unit++;

                        // echo("total unit  <pre>");
                        //     print_r($tagihan[$j]->unit);
                        // echo("</pre>");
                        
                    }
            
                    $tagihan[$j]->denda             += $tagihan_tmp[$i]->air_nilai_denda;
                    $tagihan[$j]->denda             += $tagihan_tmp[$i]->lingkungan_nilai_denda;

                    $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->air_status_tunggakan == 1 ? $tagihan_tmp[$i]->air_tagihan : 0;
                    $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[$i]->lingkungan_tagihan : 0;

                    $tagihan[$j]->tagihan           += $tagihan_tmp[$i]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->air_tagihan;
                    $tagihan[$j]->tagihan           += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->lingkungan_tagihan;

                }else{

                    $tagihan[$j]->total =   $tagihan[$j]->tunggakan 
                                            + $tagihan[$j]->denda 
                                            + $tagihan[$j]->tagihan;
                    $j++;
                    
                    $tagihan[$j] = (object)[];
                    $tagihan[$j]->unit       = 1;

                    $tagihan[$j]->blok      = $tagihan_tmp[$i]->blok;
                    $tagihan[$j]->blok_id   = $tagihan_tmp[$i]->blok_id;

                    $tagihan[$j]->tunggakan =  $tagihan_tmp[$i]->air_status_tunggakan == 1 ? $tagihan_tmp[$i]->air_tagihan : 0;
                    $tagihan[$j]->tunggakan += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[$i]->lingkungan_tagihan : 0;
            
                    $tagihan[$j]->denda     =  $tagihan_tmp[$i]->air_nilai_denda;
                    $tagihan[$j]->denda     += $tagihan_tmp[$i]->lingkungan_nilai_denda;
                    
                    $tagihan[$j]->tagihan   =  $tagihan_tmp[$i]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->air_tagihan;
                    $tagihan[$j]->tagihan   += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->lingkungan_tagihan;
                }
            }
            $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
        }
        return $tagihan;
        
    }
    public function ajax_get_blok2($kawasan_id,$periode)
    {
        $periode_tmp = $periode;
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
        $tagihan_tmp = $this->db
                            ->select("*, 
                                CASE
                                    WHEN periode < '$periode' THEN 1
                                    ELSE 0 
                                END as status_tunggakan")
                            ->from("v_tagihan")
                            ->where("kawasan_id",$kawasan_id)
                            ->where("proyek_id",$project->id)
                            ->where("(periode = '$periode'")
                            ->or_where("(periode < '$periode' AND status_tagihan = 0))")
                            ->order_by("blok_id,unit_id,periode")
                            ->get()->result();
        $tagihan = [];
        $tagihan[0] = (object)[];
        if($tagihan_tmp){
            $tagihan[0]->no_unit            = $tagihan_tmp[0]->no_unit;
            $tagihan[0]->blok               = $tagihan_tmp[0]->blok;
            $tagihan[0]->blok_id            = $tagihan_tmp[0]->blok_id;
            $tagihan[0]->total_unit         = 1;
            $tagihan[0]->tunggakan          = $tagihan_tmp[0]->status_tunggakan == 1 ? $tagihan_tmp[0]->total : 0;
            $tagihan[0]->tunggakan_bulan    = $tagihan_tmp[0]->status_tunggakan == 1 ? 1 : 0;
            $tagihan[0]->denda              = $tagihan_tmp[0]->nilai_denda;
            $tagihan[0]->tagihan            = $tagihan_tmp[0]->status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->total;
            $j = 0;
            for ($i=1; $i < count($tagihan_tmp); $i++) { 
                if($tagihan_tmp[$i]->blok_id == $tagihan_tmp[$i-1]->blok_id){
                    // echo("<br>");
                    $tagihan[$j]->denda             +=$tagihan_tmp[$i]->nilai_denda;

                    if($tagihan_tmp[$i]->status_tunggakan==1){
                        $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->total;
                        $tagihan[$j]->tunggakan_bulan   ++;
                    }else{
                        $tagihan[$j]->tagihan += $tagihan_tmp[$i]->total;
                    }
                    if($tagihan_tmp[$i]->unit_id != $tagihan_tmp[$i-1]->unit_id)
                        $tagihan[$j]->total_unit++;

                }else{
                    $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
                    $j++;
                    $tagihan[$j] = (object)[];
                    $tagihan[$j]->no_unit            = $tagihan_tmp[$i]->no_unit;
                    $tagihan[$j]->blok               = $tagihan_tmp[$i]->blok;
                    $tagihan[$j]->blok_id            = $tagihan_tmp[$i]->blok_id;
                    $tagihan[$j]->total_unit         = 1;
                    $tagihan[$j]->tunggakan          = $tagihan_tmp[$i]->status_tunggakan == 1 ? $tagihan_tmp[$i]->total : 0;
                    $tagihan[$j]->tunggakan_bulan    = $tagihan_tmp[$i]->status_tunggakan == 1 ? 1 : 0;
                    $tagihan[$j]->denda              = $tagihan_tmp[$i]->nilai_denda;
                    $tagihan[$j]->tagihan            = $tagihan_tmp[$i]->status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->total;
                }
            }
            $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
            return $tagihan;
        }
    }
    public function ajax_get_blok3($kawasan_id,$periode)
    {
        $periode_tmp = $periode;
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
        $tagihan_tmp = $this->db
                            ->select("*, 
                                CASE
                                    WHEN periode < '$periode' THEN 1
                                    ELSE 0 
                                END as status_tunggakan")
                            ->from("v_tagihan_lingkungan")
                            ->where("kawasan_id",$kawasan_id)
                            ->where("proyek_id",$project->id)
                            ->where("(periode = '$periode'")
                            ->or_where("(periode < '$periode' AND status_tagihan = 0))")
                            ->order_by("blok_id,unit_id,periode")
                            ->get()->result();
        $tagihan = [];
        $tagihan[0] = (object)[];
        if($tagihan_tmp){
            $tagihan[0]->no_unit            = $tagihan_tmp[0]->no_unit;
            $tagihan[0]->blok               = $tagihan_tmp[0]->blok;
            $tagihan[0]->blok_id            = $tagihan_tmp[0]->blok_id;
            $tagihan[0]->total_unit         = 1;
            $tagihan[0]->tunggakan          = $tagihan_tmp[0]->status_tunggakan == 1 ? $tagihan_tmp[0]->total : 0;
            $tagihan[0]->tunggakan_bulan    = $tagihan_tmp[0]->status_tunggakan == 1 ? 1 : 0;
            $tagihan[0]->denda              = $tagihan_tmp[0]->nilai_denda;
            $tagihan[0]->tagihan            = $tagihan_tmp[0]->status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->total;
            $j = 0;
            for ($i=1; $i < count($tagihan_tmp); $i++) { 
                if($tagihan_tmp[$i]->blok_id == $tagihan_tmp[$i-1]->blok_id){
                    // echo("<br>");
                    $tagihan[$j]->denda             +=$tagihan_tmp[$i]->nilai_denda;

                    if($tagihan_tmp[$i]->status_tunggakan==1){
                        $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->total;
                        $tagihan[$j]->tunggakan_bulan   ++;
                    }else{
                        $tagihan[$j]->tagihan += $tagihan_tmp[$i]->total;
                    }
                    if($tagihan_tmp[$i]->unit_id != $tagihan_tmp[$i-1]->unit_id)
                        $tagihan[$j]->total_unit++;

                }else{
                    $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
                    $j++;
                    $tagihan[$j] = (object)[];
                    $tagihan[$j]->no_unit            = $tagihan_tmp[$i]->no_unit;
                    $tagihan[$j]->blok               = $tagihan_tmp[$i]->blok;
                    $tagihan[$j]->blok_id            = $tagihan_tmp[$i]->blok_id;
                    $tagihan[$j]->total_unit         = 1;
                    $tagihan[$j]->tunggakan          = $tagihan_tmp[$i]->status_tunggakan == 1 ? $tagihan_tmp[$i]->total : 0;
                    $tagihan[$j]->tunggakan_bulan    = $tagihan_tmp[$i]->status_tunggakan == 1 ? 1 : 0;
                    $tagihan[$j]->denda              = $tagihan_tmp[$i]->nilai_denda;
                    $tagihan[$j]->tagihan            = $tagihan_tmp[$i]->status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->total;
                }
            }
            $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
            return $tagihan;
        }
    }
    public function ajax_get_kawasan($periode){
        $oldPeriode = $periode;
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
        $result = $this->db->query("
                SELECT 
                    SUM(ISNULL(v_tagihan_air.total_tagihan,0) + ISNULL(v_tagihan_lingkungan.total_tagihan ,0)) as total_tagihan,
                    kawasan.id as kawasan_id,
                    kawasan.code as kawasan_code,
                    kawasan.name as kawasan_name,
                    Case
                        WHEN v_tagihan_lingkungan.periode IS NOT NULL THEN v_tagihan_lingkungan.periode
                        WHEN v_tagihan_air.periode IS NOT NULL THEN v_tagihan_air.periode
                    END as periode
                FROM v_tagihan_air
                FULL JOIN v_tagihan_lingkungan
                    ON v_tagihan_lingkungan.unit_id = v_tagihan_air.unit_id
                    AND v_tagihan_lingkungan.periode = v_tagihan_air.periode
                JOIN kawasan
                    ON kawasan.id = v_tagihan_air.kawasan_id
                    OR kawasan.id = v_tagihan_lingkungan.kawasan_id
                JOIN blok
                    ON blok.id = v_tagihan_air.blok_id
                    OR blok.id = v_tagihan_lingkungan.blok_id
                WHERE kawasan.project_id = $project->id
                and 
                    Case
                        WHEN v_tagihan_lingkungan.periode IS NOT NULL THEN v_tagihan_lingkungan.periode
                        WHEN v_tagihan_air.periode IS NOT NULL THEN v_tagihan_air.periode
                    END = '$periode'
                GROUP BY 	kawasan.code,
                            kawasan.name,
                            kawasan.id,
                            v_tagihan_lingkungan.periode,
                            v_tagihan_air.periode
    
        ")->result();

        return $result;
    }
    public function ajax_get_kawasan2($periode)
    {
        $periode = substr($periode, 3, 4)."-".substr($periode, 0, 2) . "-01";
        $project = $this->m_core->project();
        
        $tagihan_tmp = $this->db
                            ->select("
                                kawasan.name as kawasan,
                                kawasan.id as kawasan_id,
                                t_tagihan.unit_id,
                                unit.no_unit,
                                customer.name as pemilik,
                                t_tagihan.periode,
                                t_tagihan.proyek_id,
                                blok.id as blok_id,
                                blok.name as blok,
                                CASE 
                                    WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda
                                    ELSE
                                        CASE 
                                            WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service
                                            WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service*DateDiff(MONTH,t_tagihan.periode,'$periode')
                                            WHEN v_tagihan_air.denda_jenis_service = 3 THEN (v_tagihan_air.denda_nilai_service*v_tagihan_air.total/100)*DateDiff(MONTH,t_tagihan.periode,'$periode')
                                    END
                                END as air_nilai_denda,
                                CASE
                                    WHEN (t_tagihan.periode < '$periode' AND v_tagihan_air.status_tagihan = 0 )THEN 1
                                    ELSE 0 
                                END as air_status_tunggakan,
                                v_tagihan_air.total as air_tagihan,
                                v_tagihan_air.status_tagihan as air_status_tagihan,
                                
                                
                                v_tagihan_lingkungan.nilai_denda as lingkungan_nilai_denda,
                                CASE 
                                    WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda
                                    WHEN t_tagihan.periode <= unit_lingkungan.tgl_mulai_denda THEN 0 
                                    ELSE
                                        CASE 
                                            WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN v_tagihan_lingkungan.denda_nilai_service
                                            WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN v_tagihan_lingkungan.denda_nilai_service*DateDiff(MONTH,t_tagihan.periode,'$periode')
                                            WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN (v_tagihan_lingkungan.denda_nilai_service*v_tagihan_lingkungan.total/100)*DateDiff(MONTH,t_tagihan.periode,'$periode')
                                        END 
                                    END as lingkungan_nilai_denda,
                                CASE
                                    WHEN (t_tagihan.periode < '$periode' AND v_tagihan_lingkungan.status_tagihan = 0 )THEN 1
                                    ELSE 0 
                                END as lingkungan_status_tunggakan,
                                v_tagihan_lingkungan.total as lingkungan_tagihan,
                                v_tagihan_lingkungan.status_tagihan as lingkungan_status_tagihan
                            ")
                            ->from("t_tagihan")
                            ->join("unit",
                                "unit.id = t_tagihan.unit_id")
                            ->join("blok",
                                "blok.id = unit.blok_id")
                            ->join("kawasan",
                                "kawasan.id = blok.kawasan_id")
                            ->join("customer",
                                "customer.id = unit.pemilik_customer_id")
                            ->join("v_tagihan_air",
                                "v_tagihan_air.t_tagihan_id = t_tagihan.id",
                                "LEFT")
                            ->join("v_tagihan_lingkungan",
                                "v_tagihan_lingkungan.t_tagihan_id = t_tagihan.id",
                                "LEFT")
                            ->join("unit_lingkungan",
                                    "unit_lingkungan.unit_id = unit.id")
                            ->where("t_tagihan.proyek_id",$project->id)
                            ->where("(t_tagihan.periode = '$periode' OR (t_tagihan.periode < '$periode' AND (v_tagihan_air.status_tagihan = 0 OR v_tagihan_lingkungan.status_tagihan = 0)))")

                            // ->where("(t_tagihan.periode < '$periode' AND (v_tagihan_air.status_tagihan = 0 OR v_tagihan_lingkungan.status_tagihan = 0)))")
                            ->order_by("blok.kawasan_id,unit.blok_id,unit.no_unit,t_tagihan.periode")
                            ->get()->result();
        // echo("<pre>");
        // print_r($tagihan_tmp);
        // echo("</pre>");
        
        $tagihan = [];
        $tagihan[0] = (object)[];
        $tagihan[0]->kawasan    = 0;
        $tagihan[0]->kawasan_id    = 0;
        $tagihan[0]->tunggakan  = 0;
        $tagihan[0]->denda      = 0;
        $tagihan[0]->tagihan    = 0;
        $tagihan[0]->blok       = 0;
        $tagihan[0]->blok_id    = 0;
        $tagihan[0]->unit       = 1;

        if($tagihan_tmp){

            $tagihan[0]->kawasan       = $tagihan_tmp[0]->kawasan;
            $tagihan[0]->kawasan_id    = $tagihan_tmp[0]->kawasan_id;

            $tagihan[0]->blok       = $tagihan_tmp[0]->blok;
            $tagihan[0]->blok_id    = $tagihan_tmp[0]->blok_id;

            $tagihan[0]->tunggakan  = $tagihan_tmp[0]->air_status_tunggakan == 1 ? $tagihan_tmp[0]->air_tagihan : 0;
            $tagihan[0]->tunggakan  += $tagihan_tmp[0]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[0]->lingkungan_tagihan : 0;
            
            
            $tagihan[0]->denda      =  $tagihan_tmp[0]->air_nilai_denda;
            $tagihan[0]->denda      += $tagihan_tmp[0]->lingkungan_nilai_denda;
            
            $tagihan[0]->tagihan    =  $tagihan_tmp[0]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->air_tagihan;
            $tagihan[0]->tagihan    += $tagihan_tmp[0]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[0]->lingkungan_tagihan;

            $j = 0;
            for ($i=1; $i < count($tagihan_tmp); $i++) { 
                // echo("<pre>");
                //     print_r($tagihan_tmp[$i]);
                // echo("</pre>");

                if($tagihan_tmp[$i]->kawasan_id == $tagihan_tmp[$i-1]->kawasan_id){
                
                    if($tagihan_tmp[$i]->unit_id != $tagihan_tmp[$i-1]->unit_id){
                        $tagihan[$j]->unit++;

                        // echo("total unit  <pre>");
                        //     print_r($tagihan[$j]->unit);
                        // echo("</pre>");
                        
                    }
            
                    $tagihan[$j]->denda             += $tagihan_tmp[$i]->air_nilai_denda;
                    $tagihan[$j]->denda             += $tagihan_tmp[$i]->lingkungan_nilai_denda;

                    $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->air_status_tunggakan == 1 ? $tagihan_tmp[$i]->air_tagihan : 0;
                    $tagihan[$j]->tunggakan         += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[$i]->lingkungan_tagihan : 0;

                    $tagihan[$j]->tagihan           += $tagihan_tmp[$i]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->air_tagihan;
                    $tagihan[$j]->tagihan           += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->lingkungan_tagihan;

                }else{

                    $tagihan[$j]->total =   $tagihan[$j]->tunggakan 
                                            + $tagihan[$j]->denda 
                                            + $tagihan[$j]->tagihan;
                    $j++;
                    
                    $tagihan[$j] = (object)[];
                    $tagihan[$j]->unit       = 1;

                    $tagihan[$j]->kawasan      = $tagihan_tmp[$i]->kawasan;
                    $tagihan[$j]->kawasan_id   = $tagihan_tmp[$i]->kawasan_id;

                    $tagihan[$j]->blok      = $tagihan_tmp[$i]->blok;
                    $tagihan[$j]->blok_id   = $tagihan_tmp[$i]->blok_id;

                    $tagihan[$j]->tunggakan =  $tagihan_tmp[$i]->air_status_tunggakan == 1 ? $tagihan_tmp[$i]->air_tagihan : 0;
                    $tagihan[$j]->tunggakan += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? $tagihan_tmp[$i]->lingkungan_tagihan : 0;
            
                    $tagihan[$j]->denda     =  $tagihan_tmp[$i]->air_nilai_denda;
                    $tagihan[$j]->denda     += $tagihan_tmp[$i]->lingkungan_nilai_denda;
                    
                    $tagihan[$j]->tagihan   =  $tagihan_tmp[$i]->air_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->air_tagihan;
                    $tagihan[$j]->tagihan   += $tagihan_tmp[$i]->lingkungan_status_tunggakan == 1 ? 0 : $tagihan_tmp[$i]->lingkungan_tagihan;
                }
            }
            $tagihan[$j]->total = $tagihan[$j]->tunggakan + $tagihan[$j]->denda + $tagihan[$j]->tagihan;
        }
        return $tagihan;
    }
    public function get_unit_detail_lingkungan($unit_id,$periode){
        $periode = substr($periode, 3, 4) ."-".substr($periode, 0, 2). "-01";
        $project = $this->m_core->project();
        $periode_now = date("Y-m-01");
        $time = strtotime("-12-2010");
        $periode_now2 = date("Y-m-d", strtotime("+1 month", strtotime(date("Y-m-01"))));
        // $date->modify('+1 month');
        // echo($periode_now2); 
        // $periode2 = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        // $periode_now2 = date("Y-m-01");
        $tagihan_lingkungan =   $this->db
                                    ->select(
                                        "'Lingkungan' as service,
                                        sum(
                                            CASE
                                                    WHEN periode < '$periode_now' THEN 1
                                                    ELSE 0
                                            END 
                                        ) as tunggakan_bulan,
                                        sum(
                                            CASE
                                                    WHEN periode < '$periode_now' THEN total
                                                    ELSE 0
                                            END 
                                        ) as tunggakan,
                                        sum(
                                            CASE
                                                WHEN periode = '$periode_now2' THEN total 
                                                ELSE 0
                                            END
                                        ) as tagihan,
                                        sum(
                                            isnull(CASE
                                                WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN 0 
                                                WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
                                                WHEN v_tagihan_lingkungan.periode > '$periode_now' THEN 0
                                                ELSE
                                                CASE
                                                    
                                                    WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN v_tagihan_lingkungan.denda_nilai_service 
                                                    WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN v_tagihan_lingkungan.denda_nilai_service * (DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0))
                                                    WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN ( v_tagihan_lingkungan.denda_nilai_service* v_tagihan_lingkungan.total/ 100 ) * (DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0))
                                                END 
                                                END,0)
                                        )	as denda")
                                    ->from("v_tagihan_lingkungan")
                                    ->join("unit_lingkungan",
                                        "unit_lingkungan.unit_id = v_tagihan_lingkungan.unit_id")
                                    ->join("service",
                                        "service.service_jenis_id = 2
                                        AND service.project_id = $project->id")
                                    ->where("v_tagihan_lingkungan.unit_id",$unit_id)
                                    ->where("v_tagihan_lingkungan.proyek_id",$project->id)
                                    ->where("(periode = '$periode' OR (periode < '$periode' AND status_tagihan = 0))")
                                    ->get()->row();

            return $tagihan_lingkungan;
    }
    public function get_unit_detail_air($unit_id,$periode){
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
		$periode_now = date("Y-m-01");

        $tagihan_air =   $this->db
                                    ->select(
                                        "'Air' as service,
                                        sum(
                                            CASE
                                                    WHEN periode < '$periode' THEN 1
                                                    ELSE 0
                                            END 
                                        ) as tunggakan_bulan,
                                        sum(
                                            CASE
                                                    WHEN periode < '$periode' THEN total
                                                    ELSE 0
                                            END 
                                        ) as tunggakan,
                                        sum(
                                            CASE
                                                WHEN periode = '$periode' THEN total 
                                                ELSE 0
                                            END
                                        ) as tagihan,
                                        sum(
                                            CASE
                                                WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
                                                WHEN v_tagihan_air.periode >= '$periode_now' THEN 0
                                                ELSE
                                                CASE
                                                    WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service 
                                                    WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service *(DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) - service.denda_selisih_bulan)
                                                    WHEN v_tagihan_air.denda_jenis_service = 3 THEN (v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) *(DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) - service.denda_selisih_bulan)
                                                END 
                                                END
                                        )	as denda")
                                    ->from("v_tagihan_air")
                                    ->join("service",
									"service.service_jenis_id = 2
									AND service.project_id = $project->id")
                                    ->where("unit_id",$unit_id)
                                    ->where("proyek_id",$project->id)

                                    ->where("(periode = '$periode' OR (periode < '$periode' AND status_tagihan = 0))")
                                    ->get()->row();
                                
            return $tagihan_air;
    }
}
