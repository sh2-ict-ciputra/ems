<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_tagihan extends CI_Model
{
    public function pl($project_id,$periode)
    {
        $this->load->model('m_sub_golongan');

        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $ppn_persen = $this->db->select('value')
                            ->where('name','PPN')
                            ->where('project_id',$project_id)
                            ->get('parameter_project')
                            ->row();
        $ppn_persen = $ppn_persen?$ppn_persen->value:0;
        
        echo("<pre>");
            print_r($ppn_persen);
        echo("</pre>");
        $ppn_service = $this->db->select('ppn_flag')
                            ->where('denda_jenis',2)
                            ->where('project_id',$project_id)
                            ->get('service')
                            ->row();
        $ppn_service = $ppn_service?$ppn_service->ppn_flag:0;
        var_dump($periode);
        $unit = $this->db->query("
            SELECT
                unit_lingkungan.unit_id,
                unit_lingkungan.sub_gol_id,
                sub_golongan.range_id,
                sub_golongan.administrasi,
                unit.project_id,
                unit.luas_bangunan,
                unit.luas_tanah as luas_kavling
            FROM unit_lingkungan
            JOIN unit
                ON unit.id = unit_lingkungan.unit_id
            JOIN sub_golongan
                ON sub_golongan.id = unit_lingkungan.sub_gol_id
            WHERE 
                (unit_lingkungan.tgl_mandiri < '$periode'
                OR unit_lingkungan.tgl_mandiri is null)
                AND unit_lingkungan.aktif = 1
                AND unit.project_id = $project_id
            ORDER By sub_golongan.range_id
        ")->result();
        //get range
        echo("<pre>");
            print_r($unit);
        echo("</pre>");
        
        $rangesid = [];
        foreach ($unit as $v) {
            if(!in_array($v->range_id,$rangesid))
                array_push($rangesid,$v->range_id);
        }
        var_dump($unit);
        
        $rangesidString = implode(',',$rangesid);
        var_dump($rangesidString);
        
        $resultRanges = $this->db->query("
            SELECT 
                range_lingkungan.id as range_id,
                range_lingkungan.formula_bangunan,
                range_lingkungan.formula_kavling,
                range_lingkungan.keamanan,
                range_lingkungan.kebersihan,
                range_lingkungan.bangunan_fix,
                range_lingkungan.kavling_fix,
                range_lingkungan.ppn_charge as ppn_sc_flag,
                range_lingkungan.flag_bangunan,
                range_lingkungan.flag_kavling,
                range_lingkungan_detail.range_awal,
                range_lingkungan_detail.range_akhir,
                range_lingkungan_detail.harga,	
                range_lingkungan.service_charge	
            from range_lingkungan	
            LEFT JOIN range_lingkungan_detail
                ON range_lingkungan_detail.range_lingkungan_id = range_lingkungan.id
                AND flag_jenis = 0
            WHERE range_lingkungan.project_id = $project_id
            AND range_lingkungan.id in ($rangesidString)
            ORDER BY range_lingkungan.id ASC, range_lingkungan_detail.range_awal ASC
        ")->result();
        
        $ranges = [];
        $i = 0;
        foreach($rangesid as $v) {
            $tmp = (object)[];
            $j=0;
            foreach ($resultRanges as $v2) {
                $rangeDetailBangunan = (object)[]; 

                if($v2->range_id == $v ){
                    $rangeDetailBangunan->awal = $v2->range_awal;
                    $rangeDetailBangunan->akhir = $v2->range_akhir;
                    $rangeDetailBangunan->harga = $v2->harga;
                    if($j == 0){      
                        $tmp->range_id = $v2->range_id;
                        $tmp->keamanan = $v2->keamanan;
                        $tmp->kebersihan = $v2->kebersihan;
                        $tmp->formula_bangunan = $v2->formula_bangunan?$v2->formula_bangunan:0;
                        $tmp->flag_bangunan = $v2->flag_bangunan;
                        $tmp->bangunan_fix = $v2->bangunan_fix;
                        $tmp->formula_kavling = $v2->formula_kavling?$v2->formula_kavling:0;
                        $tmp->flag_kavling = $v2->flag_kavling;
                        $tmp->kavling_fix = $v2->kavling_fix;
                        $tmp->ppn_sc_flag = $v2->ppn_sc_flag;
                        $tmp->service_charge = $v2->service_charge;
                    }
                    $tmp->range_detail_bangunan[$j] = $rangeDetailBangunan;
                    $ranges[$i] = $tmp;
                    $j++;              

                }
            }
            $i++;
        }
        echo("<rangeDetail123213 a>");
            print_r($ranges);
        echo("</pre>");
        $resultRanges = $this->db->query("
            SELECT 
                range_lingkungan.id as range_id,
                range_lingkungan.formula_bangunan,
                range_lingkungan.formula_kavling,
                range_lingkungan.keamanan,
                range_lingkungan.kebersihan,
                range_lingkungan.bangunan_fix,
                range_lingkungan.kavling_fix,
                range_lingkungan.ppn_charge as ppn_sc_flag,
                range_lingkungan.flag_bangunan,
                range_lingkungan.flag_kavling,
                range_lingkungan_detail.range_awal,
                range_lingkungan_detail.range_akhir,
                range_lingkungan_detail.harga,	
                range_lingkungan.service_charge	
            from range_lingkungan	
            LEFT JOIN range_lingkungan_detail
                ON range_lingkungan_detail.range_lingkungan_id = range_lingkungan.id
                AND flag_jenis = 1
            WHERE range_lingkungan.project_id = $project_id
            AND range_lingkungan.id in ($rangesidString)
            ORDER BY range_lingkungan.id ASC, range_lingkungan_detail.range_awal ASC
        ")->result();
        
        $i = 0;
        foreach($rangesid as $v) {
            $tmp = (object)[];
            $j=0;
            foreach ($resultRanges as $v2) {
                $rangeDetailKavling = (object)[]; 

                if($v2->range_id == $v ){
                    $rangeDetailKavling->awal = $v2->range_awal;
                    $rangeDetailKavling->akhir = $v2->range_akhir;
                    $rangeDetailKavling->harga = $v2->harga;
                    if($j == 0){      
                        $tmp->range_id = $v2->range_id;
                        $tmp->keamanan = $v2->keamanan;
                        $tmp->kebersihan = $v2->kebersihan;
                        $tmp->formula_bangunan = $v2->formula_bangunan?$v2->formula_bangunan:0;
                        $tmp->flag_bangunan = $v2->flag_bangunan;
                        $tmp->bangunan_fix = $v2->bangunan_fix;
                        $tmp->formula_kavling = $v2->formula_kavling?$v2->formula_kavling:0;
                        $tmp->flag_kavling = $v2->flag_kavling;
                        $tmp->kavling_fix = $v2->kavling_fix;
                        $tmp->ppn_sc_flag = $v2->ppn_sc_flag;
                        $tmp->service_charge = $v2->service_charge;
                    }
                    $tmp->range_detail_kavling[$j] = $rangeDetailKavling;
                    $ranges[$i]->range_detail_kavling = $tmp->range_detail_kavling;
                    $j++;              

                }
            }
            $i++;
        }
        echo("rangeDetail123213 <pre>");
            print_r($ranges);
        echo("</pre>"); 

        //variable data ialah variabel yang menjadi base untuk perhitungan nanti nya
        $data = [];
        foreach ($unit as $u) {
            $tmp = (object)[];
            foreach ($ranges as $r) {
                $tmp = $r;
                if($u->range_id == $tmp->range_id)  foreach ($tmp as $ktmp => $vtmp) $u->$ktmp = $vtmp;    
            }
            array_push($data,$u);
        }
        echo("data123 <pre>");
            print_r($data);
        echo("</pre>"); 
        foreach ($ranges as $dr) {
            $this->db->trans_begin();

            $this->db->set('lock',1);
            $this->db->where('id',$dr->range_id);
            $this->db->update('range_lingkungan');

            if ($this->db->trans_status() === FALSE)
                $this->db->trans_rollback();
            else
                $this->db->trans_commit();
        }
        foreach ($data as $dv) {

            //flag_bangunan/flag_kavling == 1 maka dia fix
            
            $this->db->where('unit_id',$dv->unit_id);            
            $this->db->where('periode',$periode);
            $tagihan_sudah_ada = $this->db->get('t_tagihan_lingkungan');

            if (!$tagihan_sudah_ada->num_rows()) 
            {
                $this->db->trans_begin();
                $biaya_bangunan = $dv->flag_bangunan?$dv->bangunan_fix:$this->perhitungan_formula($dv->formula_bangunan,$dv->range_detail_bangunan,$dv->luas_bangunan);
                $biaya_kavling = $dv->flag_kavling?$dv->kavling_fix:$this->perhitungan_formula($dv->formula_kavling,$dv->range_detail_kavling,$dv->luas_kavling);
            
                $dataInput = [
                    "proyek_id"             => $project_id,
                    "unit_id"               => $dv->unit_id,
                    "kode_tagihan"          => "Example",
                    "periode"               => $periode,
                    "nilai_bangunan"        => $biaya_bangunan,
                    "nilai_kavling"         => $biaya_kavling,
                    "administrasi"          => $dv->administrasi,
                    "ppn_flag"              => $dv->ppn_sc_flag,
                    "ppn_sc_flag"           => $dv->ppn_sc_flag,
                    "ppn_persen"            => $ppn_persen,
                    "keamanan"              => $dv->keamanan,
                    "kebersihan"            => $dv->kebersihan,
                    "service_charge"        => $dv->service_charge,
                    "status_bayar_flag"     => 0 
                ];
                
                $dataInputDetail = [
                    "formula_bangunan"  => $dv->formula_bangunan,
                    "formula_kavling"   => $dv->formula_kavling,
                    "range_id"          => $dv->range_id,
                    // "range_code"        => $db->,
                    "sub_golongan_id"   => $dv->sub_gol_id,
                    // "sub_golongan_code" => ,
                    // "pemakaian"         => $db->,
                    "luas_bangunan"     => $dv->luas_bangunan,
                    "luas_kavling"      => $dv->luas_kavling
                ];
                

                
            
                // $this->db->insert('t_tagihan_lingkungan',$dataInput);
                // $dataInputDetail["tagihan_lingkungan_id"] = $this->db->insert_id();
                // $this->db->insert('t_tagihan_lingkungan_detail',$dataInputDetail);

                if ($this->db->trans_status() === FALSE)
                    $this->db->trans_rollback();
                else
                    $this->db->trans_commit();
            } else  continue;

            echo("dataInput <pre>");
                print_r($dataInput);
            echo("</pre>");
            echo("dataInputDetail <pre>");
                print_r($dataInputDetail);
            echo("</pre>");
        }
        // return $query->result_array();
    }

    //$nilai adalah data yang akan di combine dengan range,
    //exp: jika lingkungan, maka luas bangunan/kavling
    public function perhitungan_formula($formula,$range,$nilai){
        $harga = 0;
        if($formula == 1){
            foreach ($range as $r)                 
                if($r->akhir >= $nilai)
                    return $r->harga * $nilai;
        }elseif($formula == 2){
            foreach ($range as $r){
                if($nilai > 0){
                    if($nilai >= $r->akhir){
                        $harga +=  ($r->akhir * $r->harga);
                    }
                    elseif($nilai < $r->akhir){
                        $harga +=  ($nilai * $r->harga);
                    }
                    $nilai -= $r->akhir;
                }else
                    return $harga;
            }
            return $harga;
        }elseif($formula == 3){
            $harga = $range[0]->harga;
            $nilai = $nilai - $range[0]->akhir;
            for ($i=1; $i < count($range); $i++) { 
                if($nilai > 0){
                    if($nilai >= $range[$i]->akhir)         $harga +=  ($range[$i]->akhir * $range[$i]->harga);
                    elseif($nilai < $range[$i]->akhir)      $harga +=  ($nilai * $range[$i]->harga);
                    $nilai -= $range[$i]->akhir;
                }else{
                    return $harga;
                }
            }
            return $harga;
        }elseif($formula == 4){
            foreach ($range as $r)                 
                if($r->akhir >= $nilai){
                    return $r->harga;
            }        
        }
        return 0;
    }
    public function pl2($project_id,$periode){
        $this->load->helper('file');

        $username = $this->session->userdata('username');
        $password = $this->session->userdata('password');
        $user_id = $this->db->SELECT("id")
                            ->from("user")
                            ->where("username",$username)
                            ->where("password",$password)
                            ->get()->row();
        $user_id = $user_id?$user_id->id:0; 
        var_dump($periode);
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $nilai_ppn = $this->db  ->select("value")
                                ->from("parameter_project")
                                ->where("project_id",$project_id)
                                ->where("code","PPN")
                                ->get()->row();
        $nilai_ppn = $nilai_ppn?$nilai_ppn->value:0;
        $ppn_flag   = $this->db->select('ppn_flag,denda_jenis,denda_nilai')
                            ->from('service')                            
                            ->where('project_id',$project_id)
                            ->get()->row();
        $denda_nilai_service = $ppn_flag?$ppn_flag->denda_nilai:0;
        $denda_jenis_service = $ppn_flag?$ppn_flag->denda_jenis:0;
        //denda_jenis 1:fixed, 2:progresif, 3:progresif persen
        $ppn_flag = $ppn_flag?$ppn_flag->ppn_flag:0;
        $unit = $this->db
                        ->select("unit_lingkungan.unit_id,
                                unit.luas_tanah,
                                unit.luas_bangunan,
                                unit_lingkungan.sub_gol_id,
                                sub_golongan.range_id,
                                sub_golongan.administrasi,
                                sub_golongan.code as sub_gol_code,
                                range_lingkungan.code as range_code,
                                range_lingkungan.keamanan,
                                range_lingkungan.kebersihan")
                        ->from("unit")
                        ->join("unit_lingkungan",
                                "unit_lingkungan.unit_id = unit.id
                                AND unit_lingkungan.tgl_aktif <= '".date("Y-m-d")."'")
                        ->join("sub_golongan",
                                "sub_golongan.id = unit_lingkungan.sub_gol_id")
                        ->join("range_lingkungan",
                                "range_lingkungan.id = sub_golongan.range_id")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.unit_id = unit.id
                                AND t_tagihan_lingkungan.periode = '$periode'",
                                "LEFT")
                        ->where("unit.project_id",$project_id)
                        ->where("unit.status_tagihan",1)
                        ->where("isnull(t_tagihan_lingkungan.id,0) = 0")
                        ->order_by("unit_id")
                        ->get()->result();
        
        $range_id = $this->db
                        ->select("sub_golongan.range_id")
                        ->from("unit")
                        ->join("unit_lingkungan",
                                "unit_lingkungan.unit_id = unit.id
                                AND unit_lingkungan.tgl_aktif <= '".date("Y-m-d")."'")
                        ->join("sub_golongan",
                                "sub_golongan.id = unit_lingkungan.sub_gol_id")
                        ->join("range_lingkungan",
                                "range_lingkungan.id = sub_golongan.range_id")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.unit_id = unit.id
                                AND t_tagihan_lingkungan.periode = '$periode'",
                                "LEFT")
                        ->where("unit.project_id",$project_id)
                        ->where("unit.status_tagihan",1)
                        ->where("isnull(t_tagihan_lingkungan.id,0) = 0")
                        ->distinct()
                        ->get()->result();
        $range_id_string = str_replace(['{"range_id":',"}","[","]"],[''],json_encode($range_id));
        $resultRanges = $this->db
                                ->select("
                                    range_lingkungan.id as range_id,
                                    range_lingkungan.formula_bangunan,
                                    range_lingkungan.formula_kavling,
                                    range_lingkungan.keamanan,
                                    range_lingkungan.kebersihan,
                                    isnull(range_lingkungan.bangunan_fix,0) as bangunan_fix,
                                    isnull(range_lingkungan.kavling_fix,0) as kavling_fix,
                                    isnull(range_lingkungan.ppn_charge,0) as ppn_sc_flag,
                                    isnull(range_lingkungan.flag_bangunan,0) as flag_bangunan,
                                    isnull(range_lingkungan.flag_kavling,0) as flag_kavling,
                                    isnull(range_lingkungan_detail.range_awal,0) as range_awal,
                                    isnull(range_lingkungan_detail.range_akhir,0) as range_akhir,
                                    isnull(range_lingkungan_detail.harga,0) as harga,
                                    isnull(range_lingkungan.service_charge,0) as service_charge
                                    ")
                                ->from("range_lingkungan")	
                                ->join("range_lingkungan_detail",
                                        "range_lingkungan_detail.range_lingkungan_id = range_lingkungan.id
                                        AND flag_jenis = 0",
                                        "LEFT")
                                ->where("range_lingkungan.project_id",$project_id)
                                ->where_in("range_lingkungan.id",explode(",",$range_id_string))
                                ->order_by("range_lingkungan.id ASC, range_lingkungan_detail.range_awal ASC")
                                ->get()->result();
        $ranges = [];
        $i = 0;
        foreach($range_id as $v) {
            $tmp = (object)[];
            $j=0;
            foreach ($resultRanges as $v2) {
                $rangeDetailBangunan = (object)[]; 

                if($v2->range_id == $v->range_id){
                    $rangeDetailBangunan->awal = $v2->range_awal;
                    $rangeDetailBangunan->akhir = $v2->range_akhir;
                    $rangeDetailBangunan->harga = $v2->harga;
                    if($j == 0){      
                        $tmp->range_id = $v2->range_id;
                        $tmp->keamanan = $v2->keamanan;
                        $tmp->kebersihan = $v2->kebersihan;
                        $tmp->formula_bangunan = $v2->formula_bangunan?$v2->formula_bangunan:0;
                        $tmp->flag_bangunan = $v2->flag_bangunan;
                        $tmp->bangunan_fix = $v2->bangunan_fix;
                        $tmp->formula_kavling = $v2->formula_kavling?$v2->formula_kavling:0;
                        $tmp->flag_kavling = $v2->flag_kavling;
                        $tmp->kavling_fix = $v2->kavling_fix;
                        $tmp->ppn_sc_flag = $v2->ppn_sc_flag;
                        $tmp->service_charge = $v2->service_charge;
                    }
                    $tmp->range_detail_bangunan[$j] = $rangeDetailBangunan;
                    $ranges[$i] = $tmp;
                    $j++;              

                }
            }
            $i++;
        }
        echo("rangeDetail123213 <pre>");
            print_r($ranges);
        echo("</pre>"); 
        $resultRanges = $this->db
                                ->select("
                                    range_lingkungan.id as range_id,
                                    range_lingkungan.formula_bangunan,
                                    range_lingkungan.formula_kavling,
                                    range_lingkungan.keamanan,
                                    range_lingkungan.kebersihan,
                                    isnull(range_lingkungan.bangunan_fix,0) as bangunan_fix,
                                    isnull(range_lingkungan.kavling_fix,0) as kavling_fix,
                                    isnull(range_lingkungan.ppn_charge,0) as ppn_sc_flag,
                                    isnull(range_lingkungan.flag_bangunan,0) as flag_bangunan,
                                    isnull(range_lingkungan.flag_kavling,0) as flag_kavling,
                                    isnull(range_lingkungan_detail.range_awal,0) as range_awal,
                                    isnull(range_lingkungan_detail.range_akhir,0) as range_akhir,
                                    isnull(range_lingkungan_detail.harga,0) as harga,
                                    isnull(range_lingkungan.service_charge,0) as service_charge
                                    ")
                                ->from("range_lingkungan")	
                                ->join("range_lingkungan_detail",
                                        "range_lingkungan_detail.range_lingkungan_id = range_lingkungan.id
                                        AND flag_jenis = 1",
                                        "LEFT")
                                ->where("range_lingkungan.project_id",$project_id)
                                ->where_in("range_lingkungan.id",explode(",",$range_id_string))
                                ->order_by("range_lingkungan.id ASC, range_lingkungan_detail.range_awal ASC")
                                ->get()->result();
        
        $i = 0;
        foreach($range_id as $v) {
            $tmp = (object)[];
            $j=0;
            foreach ($resultRanges as $v2) {
                $rangeDetailKavling = (object)[]; 

                if($v2->range_id == $v->range_id){
                    $rangeDetailKavling->awal = $v2->range_awal;
                    $rangeDetailKavling->akhir = $v2->range_akhir;
                    $rangeDetailKavling->harga = $v2->harga;
                    if($j == 0){      
                        $tmp->range_id = $v2->range_id;
                        $tmp->keamanan = $v2->keamanan;
                        $tmp->kebersihan = $v2->kebersihan;
                        $tmp->formula_bangunan = $v2->formula_bangunan?$v2->formula_bangunan:0;
                        $tmp->flag_bangunan = $v2->flag_bangunan;
                        $tmp->bangunan_fix = $v2->bangunan_fix;
                        $tmp->formula_kavling = $v2->formula_kavling?$v2->formula_kavling:0;
                        $tmp->flag_kavling = $v2->flag_kavling;
                        $tmp->kavling_fix = $v2->kavling_fix;
                        $tmp->ppn_sc_flag = $v2->ppn_sc_flag;
                        $tmp->service_charge = $v2->service_charge;
                    }
                    $tmp->range_detail_kavling[$j] = $rangeDetailKavling;
                    $ranges[$i]->range_detail_kavling = $tmp->range_detail_kavling;
                    $j++;              

                }
            }
            $i++;
        }
        

        $data = [];
        foreach ($unit as $u) {
            $tmp = (object)[];
            foreach ($ranges as $r) {
                $tmp = $r;
                if($u->range_id == $tmp->range_id)  foreach ($tmp as $ktmp => $vtmp) $u->$ktmp = $vtmp;    
            }
            array_push($data,$u);
        }
        echo("data123 <pre>");
            print_r($unit);
        echo("</pre>"); 
        
        foreach ($ranges as $dr) {
            $this->db->trans_begin();

            $this->db->set('lock',1);
            $this->db->where('id',$dr->range_id);
            $this->db->update('range_lingkungan');

            if ($this->db->trans_status() === FALSE)
                $this->db->trans_rollback();
            else
                $this->db->trans_commit();
        }
        foreach ($data as $dv) {

            //flag_bangunan/flag_kavling == 1 maka dia fix
            
            $this->db->where('unit_id',$dv->unit_id);            
            $this->db->where('periode',$periode);
            $tagihan_sudah_ada = $this->db->get('t_tagihan_lingkungan');

            if (!$tagihan_sudah_ada->num_rows()) 
            {
                $data_tagihan           = (object)[];
                $data_lingkungan        = (object)[];
                $data_lingkungan_detail = (object)[];
                $data_lingkungan_info   = (object)[];

                $this->db->trans_begin();
                $biaya_bangunan = $dv->flag_bangunan?$dv->bangunan_fix:$this->perhitungan_formula($dv->formula_bangunan,$dv->range_detail_bangunan,$dv->luas_bangunan);
                $biaya_kavling = $dv->flag_kavling?$dv->kavling_fix:$this->perhitungan_formula($dv->formula_kavling,$dv->range_detail_kavling,$dv->luas_tanah);
                
                $this->db->where('unit_id',$dv->unit_id);            
                $this->db->where('periode',$periode);
                $this->db->where('proyek_id',$project_id);                
                $tagihan_sudah_ada = $this->db->get('t_tagihan');
                if (!$tagihan_sudah_ada->num_rows()) {
                    $data_tagihan->proyek_id                    = $project_id;
                    $data_tagihan->unit_id                      = $dv->unit_id;
                    $data_tagihan->periode                      = $periode;

                    $this->db->insert('t_tagihan',$data_tagihan);

                    $data_lingkungan->t_tagihan_id = $this->db->insert_id();
                }else{
                    $data_lingkungan->t_tagihan_id = $tagihan_sudah_ada->row()->id;
                }
                $data_lingkungan->proyek_id         = $project_id;
                $data_lingkungan->unit_id           = $dv->unit_id;
                $data_lingkungan->kode_tagihan      = "Example";
                $data_lingkungan->periode           = $periode;
                $data_lingkungan->status_tagihan    = 0;
                
                $data_lingkungan_detail->nilai_bangunan        = $biaya_bangunan;
                $data_lingkungan_detail->nilai_kavling         = $biaya_kavling;
                $data_lingkungan_detail->nilai_administrasi    = $dv->administrasi;
                $data_lingkungan_detail->nilai_keamanan        = $dv->keamanan;
                $data_lingkungan_detail->nilai_kebersihan      = $dv->kebersihan;
                $data_lingkungan_detail->nilai_denda           = 0; //ada jika nilai_denda_flag = 1
                $data_lingkungan_detail->nilai_ppn             = $nilai_ppn;
                $data_lingkungan_detail->nilai_bangunan_flag   = 0;
                $data_lingkungan_detail->nilai_kavling_flag    = 0;
                $data_lingkungan_detail->nilai_denda_flag      = 0; //1:nilai_denda_fix; 0:nilai_denda_auto
                $data_lingkungan_detail->ppn_flag              = $ppn_flag;
                $data_lingkungan_detail->ppn_sc_flag           = $dv->ppn_sc_flag;
                $data_lingkungan_detail->user_id               = 0;
                $data_lingkungan_detail->active                = 1;

                $data_lingkungan_info->formula_bangunan     = $dv->formula_bangunan;
                $data_lingkungan_info->formula_kavling      = $dv->formula_kavling;
                $data_lingkungan_info->range_id             = $dv->range_id;
                $data_lingkungan_info->range_code           = $dv->range_code;
                $data_lingkungan_info->sub_golongan_id      = $dv->sub_gol_id;
                $data_lingkungan_info->sub_golongan_code    = $dv->sub_gol_code;
                $data_lingkungan_info->luas_bangunan        = $dv->luas_bangunan;
                $data_lingkungan_info->luas_kavling         = $dv->luas_tanah;
                $data_lingkungan_info->denda_nilai_service  = $denda_nilai_service;
                $data_lingkungan_info->denda_jenis_service  = $denda_jenis_service;

                // $data_lingkungan = [
                //     "proyek_id"             => $project_id,
                //     "unit_id"               => $dv->unit_id,
                //     "kode_tagihan"          => "Example",
                //     "periode"               => $periode,
                //     "nilai_bangunan"        => $biaya_bangunan,
                //     "nilai_kavling"         => $biaya_kavling,
                //     "administrasi"          => $dv->administrasi,
                //     "ppn_flag"              => $dv->ppn_sc_flag,
                //     "ppn_sc_flag"           => $dv->ppn_sc_flag,
                //     "ppn_persen"            => $nilai_ppn,
                //     "keamanan"              => $dv->keamanan,
                //     "kebersihan"            => $dv->kebersihan,
                //     "service_charge"        => $dv->service_charge,
                //     "status_bayar_flag"     => 0 
                // ];
                
                // $data_lingkungan_detail = [
                    // "formula_bangunan"  => $dv->formula_bangunan,
                    // "formula_kavling"   => $dv->formula_kavling,
                    // "range_id"          => $dv->range_id,
                    // // "range_code"        => $db->,
                    // "sub_golongan_id"   => $dv->sub_gol_id,
                    // // "sub_golongan_code" => ,
                    // // "pemakaian"         => $db->,
                    // "luas_bangunan"     => $dv->luas_bangunan,
                    // "luas_kavling"      => $dv->luas_tanah
                // ];
                
                
            
                $this->db->insert('t_tagihan_lingkungan',$data_lingkungan);
                $data_lingkungan_detail->t_tagihan_lingkungan_id = $this->db->insert_id();
                $data_lingkungan_info->t_tagihan_lingkungan_id = $data_lingkungan_detail->t_tagihan_lingkungan_id;
                $this->db->insert('t_tagihan_lingkungan_detail',$data_lingkungan_detail);
                $this->db->insert('t_tagihan_lingkungan_info',$data_lingkungan_info);

                if ($this->db->trans_status() === FALSE)
                    $this->db->trans_rollback();
                else
                    $this->db->trans_commit();
            } else  continue;

            echo("data_lingkungan <pre>");
                print_r($data_lingkungan);
            echo("</pre>");
            echo("data_lingkungan_detail <pre>");
                print_r($data_lingkungan_detail);
            echo("</pre>");
            echo("data_lingkungan_info <pre>");
                print_r($data_lingkungan_info);
            echo("</pre>");
            write_file("./log/".date("y-m-d").'_log_auto_generate.txt',"\n".date("y-m-d h:i:s")." - $dv->unit_id", 'a+');
        }
        // return $query->result_array();
    }
    // public function pl2($project_id,$periode){
    public function pl_unit($unit_id,$periode){
        $project = (object)[];
        $project->id = $this->m_core->project()->id;

        $username = $this->session->userdata('username');
        $password = $this->session->userdata('password');
        $user_id = $this->db->SELECT("id")
                            ->from("user")
                            ->where("username",$username)
                            ->where("password",$password)
                            ->get()->row();
        $user_id = $user_id?$user_id->id:0; 

        // $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $nilai_ppn = $this->db  ->select("value")
                                ->from("parameter_project")
                                ->where("project_id",$project->id)
                                ->where("code","PPN")
                                ->get()->row();
        $nilai_ppn = $nilai_ppn?$nilai_ppn->value:0;
        $ppn_flag   = $this->db->select('ppn_flag,denda_jenis,denda_nilai')
                            ->from('service')                            
                            ->where('project_id',$project->id)
                            ->where('service_jenis_id',1)
                            ->get()->row();
        $denda_nilai_service = $ppn_flag?$ppn_flag->denda_nilai:0;
        $denda_jenis_service = $ppn_flag?$ppn_flag->denda_jenis:0;
        //denda_jenis 1:fixed, 2:progresif, 3:progresif persen
        $ppn_flag = $ppn_flag?$ppn_flag->ppn_flag:0;
        $unit = $this->db
                        ->select("unit_lingkungan.unit_id,
                                unit.luas_tanah,
                                unit.luas_bangunan,
                                unit_lingkungan.sub_gol_id,
                                sub_golongan.range_id,
                                sub_golongan.administrasi,
                                sub_golongan.code as sub_gol_code,
                                range_lingkungan.code as range_code,
                                range_lingkungan.keamanan,
                                range_lingkungan.kebersihan")
                        ->from("unit")
                        ->join("unit_lingkungan",
                                "unit_lingkungan.unit_id = unit.id")
                        ->join("sub_golongan",
                                "sub_golongan.id = unit_lingkungan.sub_gol_id")
                        ->join("range_lingkungan",
                                "range_lingkungan.id = sub_golongan.range_id")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.unit_id = unit.id
                                AND t_tagihan_lingkungan.periode = '$periode'",
                                "LEFT")
                        ->where("unit.project_id",$project->id)
                        ->where("unit.status_tagihan",1)
                        ->where("isnull(t_tagihan_lingkungan.id,0) = 0")
                        ->where("unit.id",$unit_id)
                        ->order_by("unit_id")
                        ->get()->result();
        
        // echo("<pre>");
        // print_r($this->db->last_query());
        // echo("</pre>");
        

        $range_id = $this->db
                        ->select("sub_golongan.range_id")
                        ->from("unit")
                        ->join("unit_lingkungan",
                                "unit_lingkungan.unit_id = unit.id")
                        ->join("sub_golongan",
                                "sub_golongan.id = unit_lingkungan.sub_gol_id")
                        ->join("range_lingkungan",
                                "range_lingkungan.id = sub_golongan.range_id")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.unit_id = unit.id
                                AND t_tagihan_lingkungan.periode = '$periode'",
                                "LEFT")
                        ->where("unit.project_id",$project->id)
                        ->where("unit.status_tagihan",1)
                        ->where("isnull(t_tagihan_lingkungan.id,0) = 0")
                        ->distinct()
                        ->get()->result();
        // echo("range_id<pre>");
        // print_r($range_id);
        // echo("</pre>");
                   
        $range_id_string = str_replace(['{"range_id":',"}","[","]"],[''],json_encode($range_id));
        $resultRanges = $this->db
                                ->select("
                                    range_lingkungan.id as range_id,
                                    range_lingkungan.formula_bangunan,
                                    range_lingkungan.formula_kavling,
                                    range_lingkungan.keamanan,
                                    range_lingkungan.kebersihan,
                                    isnull(range_lingkungan.bangunan_fix,0) as bangunan_fix,
                                    isnull(range_lingkungan.kavling_fix,0) as kavling_fix,
                                    isnull(range_lingkungan.ppn_charge,0) as ppn_sc_flag,
                                    isnull(range_lingkungan.flag_bangunan,0) as flag_bangunan,
                                    isnull(range_lingkungan.flag_kavling,0) as flag_kavling,
                                    isnull(range_lingkungan_detail.range_awal,0) as range_awal,
                                    isnull(range_lingkungan_detail.range_akhir,0) as range_akhir,
                                    isnull(range_lingkungan_detail.harga,0) as harga,
                                    isnull(range_lingkungan.service_charge,0) as service_charge
                                    ")
                                ->from("range_lingkungan")	
                                ->join("range_lingkungan_detail",
                                        "range_lingkungan_detail.range_lingkungan_id = range_lingkungan.id
                                        AND flag_jenis = 0",
                                        "LEFT")
                                ->where("range_lingkungan.project_id",$project->id)
                                ->where_in("range_lingkungan.id",explode(",",$range_id_string))
                                ->order_by("range_lingkungan.id ASC, range_lingkungan_detail.range_awal ASC")
                                ->get()->result();
        // echo("resultRanges<pre>");
        // print_r($resultRanges);
        // echo("</pre>");
        $ranges = [];
        $i = 0;
        foreach($range_id as $v) {
            $tmp = (object)[];
            $j=0;
            foreach ($resultRanges as $v2) {
                $rangeDetailBangunan = (object)[]; 

                if($v2->range_id == $v->range_id){
                    $rangeDetailBangunan->awal = $v2->range_awal;
                    $rangeDetailBangunan->akhir = $v2->range_akhir;
                    $rangeDetailBangunan->harga = $v2->harga;
                    if($j == 0){      
                        $tmp->range_id = $v2->range_id;
                        $tmp->keamanan = $v2->keamanan;
                        $tmp->kebersihan = $v2->kebersihan;
                        $tmp->formula_bangunan = $v2->formula_bangunan?$v2->formula_bangunan:0;
                        $tmp->flag_bangunan = $v2->flag_bangunan;
                        $tmp->bangunan_fix = $v2->bangunan_fix;
                        $tmp->formula_kavling = $v2->formula_kavling?$v2->formula_kavling:0;
                        $tmp->flag_kavling = $v2->flag_kavling;
                        $tmp->kavling_fix = $v2->kavling_fix;
                        $tmp->ppn_sc_flag = $v2->ppn_sc_flag;
                        $tmp->service_charge = $v2->service_charge;
                    }
                    $tmp->range_detail_bangunan[$j] = $rangeDetailBangunan;
                    $ranges[$i] = $tmp;
                    $j++;              

                }
            }
            $i++;
        }
        // echo("ranges<pre>");
        // print_r($ranges);
        // echo("</pre>");
        $resultRanges = $this->db
                                ->select("
                                    range_lingkungan.id as range_id,
                                    range_lingkungan.formula_bangunan,
                                    range_lingkungan.formula_kavling,
                                    range_lingkungan.keamanan,
                                    range_lingkungan.kebersihan,
                                    isnull(range_lingkungan.bangunan_fix,0) as bangunan_fix,
                                    isnull(range_lingkungan.kavling_fix,0) as kavling_fix,
                                    isnull(range_lingkungan.ppn_charge,0) as ppn_sc_flag,
                                    isnull(range_lingkungan.flag_bangunan,0) as flag_bangunan,
                                    isnull(range_lingkungan.flag_kavling,0) as flag_kavling,
                                    isnull(range_lingkungan_detail.range_awal,0) as range_awal,
                                    isnull(range_lingkungan_detail.range_akhir,0) as range_akhir,
                                    isnull(range_lingkungan_detail.harga,0) as harga,
                                    isnull(range_lingkungan.service_charge,0) as service_charge
                                    ")
                                ->from("range_lingkungan")	
                                ->join("range_lingkungan_detail",
                                        "range_lingkungan_detail.range_lingkungan_id = range_lingkungan.id
                                        AND flag_jenis = 1",
                                        "LEFT")
                                ->where("range_lingkungan.project_id",$project->id)
                                ->where_in("range_lingkungan.id",explode(",",$range_id_string))
                                ->order_by("range_lingkungan.id ASC, range_lingkungan_detail.range_awal ASC")
                                ->get()->result();
        // echo("resultRanges<pre>");
        // print_r($resultRanges);
        // echo("</pre>");
        
        $i = 0;
        foreach($range_id as $v) {
            $tmp = (object)[];
            $j=0;
            foreach ($resultRanges as $v2) {
                $rangeDetailKavling = (object)[]; 

                if($v2->range_id == $v->range_id){
                    $rangeDetailKavling->awal = $v2->range_awal;
                    $rangeDetailKavling->akhir = $v2->range_akhir;
                    $rangeDetailKavling->harga = $v2->harga;
                    if($j == 0){      
                        $tmp->range_id = $v2->range_id;
                        $tmp->keamanan = $v2->keamanan;
                        $tmp->kebersihan = $v2->kebersihan;
                        $tmp->formula_bangunan = $v2->formula_bangunan?$v2->formula_bangunan:0;
                        $tmp->flag_bangunan = $v2->flag_bangunan;
                        $tmp->bangunan_fix = $v2->bangunan_fix;
                        $tmp->formula_kavling = $v2->formula_kavling?$v2->formula_kavling:0;
                        $tmp->flag_kavling = $v2->flag_kavling;
                        $tmp->kavling_fix = $v2->kavling_fix;
                        $tmp->ppn_sc_flag = $v2->ppn_sc_flag;
                        $tmp->service_charge = $v2->service_charge;
                    }
                    $tmp->range_detail_kavling[$j] = $rangeDetailKavling;
                    $ranges[$i]->range_detail_kavling = $tmp->range_detail_kavling;
                    $j++;              

                }
            }
            $i++;
        }
        // echo("ranges<pre>");
        // print_r($ranges);
        // echo("</pre>");
        

        $data = [];
        foreach ($unit as $u) {
            $tmp = (object)[];
            foreach ($ranges as $r) {
                $tmp = $r;
                if($u->range_id == $tmp->range_id)  foreach ($tmp as $ktmp => $vtmp) $u->$ktmp = $vtmp;    
            }
            array_push($data,$u);
        }
        // echo("data<pre>");
        // print_r($data);
        // echo("</pre>");
        foreach ($ranges as $dr) {
            $this->db->trans_begin();

            $this->db->set('lock',1);
            $this->db->where('id',$dr->range_id);
            $this->db->update('range_lingkungan');

            if ($this->db->trans_status() === FALSE)
                $this->db->trans_rollback();
            else
                $this->db->trans_commit();
        }
        foreach ($data as $dv) {

            //flag_bangunan/flag_kavling == 1 maka dia fix
            
            $this->db->where('unit_id',$dv->unit_id);            
            $this->db->where('periode',$periode);
            $tagihan_sudah_ada = $this->db->get('t_tagihan_lingkungan');

            if (!$tagihan_sudah_ada->num_rows()) 
            {
                $data_tagihan           = (object)[];
                $data_lingkungan        = (object)[];
                $data_lingkungan_detail = (object)[];
                $data_lingkungan_info   = (object)[];

                $this->db->trans_begin();
                $biaya_bangunan = $dv->flag_bangunan?$dv->bangunan_fix:$this->perhitungan_formula($dv->formula_bangunan,$dv->range_detail_bangunan,$dv->luas_bangunan);
                $biaya_kavling = $dv->flag_kavling?$dv->kavling_fix:$this->perhitungan_formula($dv->formula_kavling,$dv->range_detail_kavling,$dv->luas_tanah);
                // echo("dv<pre>");
                // print_r($dv);
                // echo("</pre>");
                
                // echo("biaya_bangunan<pre>");
                // print_r($biaya_bangunan);
                // echo("</pre>");
                // echo("biaya_kavling<pre>");
                // print_r($biaya_kavling);
                // echo("</pre>");
                $minimum_rp = $this->db
                                ->select('minimum_rp')
                                ->from('unit')
                                ->join('unit_lingkungan','unit_lingkungan.unit_id = unit.id')
                                ->join('sub_golongan','sub_golongan.id = unit_lingkungan.sub_gol_id')
                                ->where('unit.id',$dv->unit_id)->get()->row();
                                echo("minimum_rp<pre>");
                                print_r($minimum_rp);
                                echo("</pre>");
                if($minimum_rp){
                    if($biaya_kavling < $minimum_rp->minimum_rp){
                        $biaya_kavling = $minimum_rp->minimum_rp;
                    }
                }
                // echo("minimum_rp<pre>");
                // print_r($minimum_rp);
                // echo("</pre>");
                

                $this->db->where('unit_id',$dv->unit_id);            
                $this->db->where('periode',$periode);
                $this->db->where('proyek_id',$project->id);                
                $tagihan_sudah_ada = $this->db->get('t_tagihan');
                if (!$tagihan_sudah_ada->num_rows()) {
                    $data_tagihan->proyek_id                    = $project->id;
                    $data_tagihan->unit_id                      = $dv->unit_id;
                    $data_tagihan->periode                      = $periode;
                    // echo("data_tagihan<pre>");
                    // print_r($data_tagihan);
                    // echo("</pre>");
                    $this->db->insert('t_tagihan',$data_tagihan);

                    $data_lingkungan->t_tagihan_id = $this->db->insert_id();
                }else{
                    $data_lingkungan->t_tagihan_id = $tagihan_sudah_ada->row()->id;
                }
                $data_lingkungan->proyek_id         = $project->id;
                $data_lingkungan->unit_id           = $dv->unit_id;
                $data_lingkungan->kode_tagihan      = "Example";
                $data_lingkungan->periode           = $periode;
                $data_lingkungan->status_tagihan    = 0;
                
                $data_lingkungan_detail->nilai_bangunan        = $biaya_bangunan;
                $data_lingkungan_detail->nilai_kavling         = $biaya_kavling;
                $data_lingkungan_detail->nilai_administrasi    = $dv->administrasi;
                $data_lingkungan_detail->nilai_keamanan        = $dv->keamanan;
                $data_lingkungan_detail->nilai_kebersihan      = $dv->kebersihan;
                $data_lingkungan_detail->nilai_denda           = 0; //ada jika nilai_denda_flag = 1
                $data_lingkungan_detail->nilai_ppn             = $nilai_ppn;
                $data_lingkungan_detail->nilai_bangunan_flag   = 0;
                $data_lingkungan_detail->nilai_kavling_flag    = 0;
                $data_lingkungan_detail->nilai_denda_flag      = 0; //1:nilai_denda_fix; 0:nilai_denda_auto
                $data_lingkungan_detail->ppn_flag              = $ppn_flag;
                $data_lingkungan_detail->ppn_sc_flag           = $dv->ppn_sc_flag;
                $data_lingkungan_detail->user_id               = 0;
                $data_lingkungan_detail->active                = 1;

                $data_lingkungan_info->formula_bangunan     = $dv->formula_bangunan;
                $data_lingkungan_info->formula_kavling      = $dv->formula_kavling;
                $data_lingkungan_info->range_id             = $dv->range_id;
                $data_lingkungan_info->range_code           = $dv->range_code;
                $data_lingkungan_info->sub_golongan_id      = $dv->sub_gol_id;
                $data_lingkungan_info->sub_golongan_code    = $dv->sub_gol_code;
                $data_lingkungan_info->luas_bangunan        = $dv->luas_bangunan;
                $data_lingkungan_info->luas_kavling         = $dv->luas_tanah;
                $data_lingkungan_info->denda_nilai_service  = $denda_nilai_service;
                $data_lingkungan_info->denda_jenis_service  = $denda_jenis_service;

                // $data_lingkungan = [
                //     "proyek_id"             => $project->id,
                //     "unit_id"               => $dv->unit_id,
                //     "kode_tagihan"          => "Example",
                //     "periode"               => $periode,
                //     "nilai_bangunan"        => $biaya_bangunan,
                //     "nilai_kavling"         => $biaya_kavling,
                //     "administrasi"          => $dv->administrasi,
                //     "ppn_flag"              => $dv->ppn_sc_flag,
                //     "ppn_sc_flag"           => $dv->ppn_sc_flag,
                //     "ppn_persen"            => $nilai_ppn,
                //     "keamanan"              => $dv->keamanan,
                //     "kebersihan"            => $dv->kebersihan,
                //     "service_charge"        => $dv->service_charge,
                //     "status_bayar_flag"     => 0 
                // ];
                
                // $data_lingkungan_detail = [
                    // "formula_bangunan"  => $dv->formula_bangunan,
                    // "formula_kavling"   => $dv->formula_kavling,
                    // "range_id"          => $dv->range_id,
                    // // "range_code"        => $db->,
                    // "sub_golongan_id"   => $dv->sub_gol_id,
                    // // "sub_golongan_code" => ,
                    // // "pemakaian"         => $db->,
                    // "luas_bangunan"     => $dv->luas_bangunan,
                    // "luas_kavling"      => $dv->luas_tanah
                // ];
                
                
            
                $this->db->insert('t_tagihan_lingkungan',$data_lingkungan);
                $data_lingkungan_detail->t_tagihan_lingkungan_id = $this->db->insert_id();
                $data_lingkungan_info->t_tagihan_lingkungan_id = $data_lingkungan_detail->t_tagihan_lingkungan_id;
                $this->db->insert('t_tagihan_lingkungan_detail',$data_lingkungan_detail);
                $this->db->insert('t_tagihan_lingkungan_info',$data_lingkungan_info);

                if ($this->db->trans_status() === FALSE)
                    $this->db->trans_rollback();
                else
                    $this->db->trans_commit();
            } else  continue;
        }
        if(count($unit)>0){
            return true;
        }else{
            return false;
        }
    }
}
