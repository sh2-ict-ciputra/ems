<?php

defined("BASEPATH") or exit("No direct script access allowed");

class m_history_pembayaran extends CI_Model
{

//     public function getCaraPembayaran()
//     {
//         $project = $this->m_core->project();
//         $query = $this->db
//                         ->select(
//                             "cara_pembayaran_jenis.*")
//                         ->from("cara_pembayaran_jenis")
//                         ->join("cara_pembayaran",
//                                 "cara_pembayaran.jenis_cara_pembayaran_id = cara_pembayaran_jenis.id
//                                 AND cara_pembayaran.project_id = $project->id",
//                                 "LEFT")
//                         ->where("cara_pembayaran.id is not null")
//                         ->distinct();
//         return $query->get()->result();
//     }
    public function getCaraPembayaran()
    {
        $project = $this->m_core->project();
        $query = $this->db
                        ->select(
                            "cara_pembayaran.id as id,
                            cara_pembayaran.code as code,
                            cara_pembayaran.name as cara,
                            bank.name as bank_name")
                        ->from("cara_pembayaran")
                        ->join("bank",
                                "bank.id = cara_pembayaran.bank_id","LEFT")
                        ->join("project",
                                "project.id = cara_pembayaran.project_id","LEFT")
                        ->where("cara_pembayaran.project_id",$project->id)
                        ->where("cara_pembayaran.id is not null")
                        ->distinct();
        return $query->get()->result();
    }

    public function getService()
    {
        $query = $this->db
                        ->select(
                            "service_jenis.*")
                        ->from("service_jenis")
                        ->where("service_jenis.id is not null")
                        ->distinct();
        return $query->get()->result();
    }

    public function getAll($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar,$jns_service)
    {
        //Air
        $project = $this->m_core->project();
        $periode_awal = substr($periode_awal,6,4)."-".substr($periode_awal,3,2)."-".substr($periode_awal,0,2);
        $periode_akhir = substr($periode_akhir,6,4)."-".substr($periode_akhir,3,2)."-".substr($periode_akhir,0,2);
        
        $query = $this->db
                            ->select("
                            kawasan.code,
                            v_tagihan_air.blok,
                            v_tagihan_air.no_unit,
                            ISNULL(kwitansi_referensi.no_kwitansi,'-') as no_kwitansi,
                            v_tagihan_air.periode,
                            t_tagihan_air_info.pemakaian,
                            t_pembayaran.tgl_bayar,
                            v_tagihan_air.nilai_administrasi,
                            v_tagihan_air.nilai_denda,
                            v_tagihan_air.nilai,
                            t_pembayaran_detail.nilai_diskon,
                            v_tagihan_air.total_tanpa_ppn+t_pembayaran_detail.nilai_denda-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan) as bayar")
                            ->from("v_tagihan_air")
                            ->join("project",
                                    "project.id = v_tagihan_air.proyek_id")
                            ->join("kawasan",
                                    "kawasan.id = v_tagihan_air.kawasan_id")      
                            ->join("t_tagihan_air",
                                    "t_tagihan_air.proyek_id = project.id")
                            ->join("t_tagihan_air_info",
                                    "t_tagihan_air_info.t_tagihan_air_id = t_tagihan_air.id")
                            ->join("t_pembayaran_detail",
                                    "t_pembayaran_detail.tagihan_service_id = v_tagihan_air.tagihan_id")
                            ->join("t_pembayaran",
                                    "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                            ->join("cara_pembayaran",
                                    "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
                            ->join("kwitansi_referensi",
                                    "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
                            ->join("service",
                                    "service.id = t_pembayaran_detail.service_id")
                            ->where("v_tagihan_air.proyek_id",$project->id)
                            ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                            ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'");

        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");


        }else{

                $query = $query->where("cara_pembayaran_id", $cara_bayar);
                $query = $query->where("t_pembayaran_detail.bayar > 0");


        }
        if($kawasan != 'all'){
            $query = $query->where("kawasan_id",$kawasan);
        }if($blok != 'all'){
            $query = $query->where("blok_id",$blok);
        }

        $data = (object)[];
        $data->header=["Kode Kawasan","Kode Blok","No Unit", "No. Kwitansi", "Periode", "Pakai (m3)", "Tanggal Bayar", "Nilai Admin", "Denda", 
                        "Pemakaian", "Disc", "Total Bayar"];
        $result = $query->get()->result();
        $data->isi = $result;
        

        if($cara_bayar==0){
                $cara_pembayaran_name = "Service Air - Deposit";
        }else{
            $cara = $this->db
                            ->select("CONCAT(cara_pembayaran.name,' ',bank.name) as gabungan")
                            ->from("cara_pembayaran")
                            ->join("bank",
                                    "bank.id = cara_pembayaran.bank_id", "LEFT")
                            ->where("cara_pembayaran.id",$cara_bayar)->get()->row()->gabungan;
        //     $cara_pembayaran_name = "Service Air - ".$cara;
            $cara_pembayaran_name = "Service Air  ";
        }
        $data->cara_bayar = $cara;
        $data->judul = $cara_pembayaran_name;
        $pembayaran_name = "Rekapitulasi Transaksi Service Air";
        $data->judul_rekap = $pembayaran_name;
        if($jns_service==2){
            $jns_service = $this->db->select("id")->from("service_jenis")->where("id",$jns_service)->get()->row()->id;
        }
        $data->serviceAir = $jns_service;

        // $Air = $this->db
        //                 ->select("
        //                 SUM(t_tagihan_air_info.pemakaian) as pakai,
        //                 SUM(v_tagihan_air.nilai_administrasi) as admin,
        //                 SUM(v_tagihan_air.nilai_denda) as denda,
        //                 SUM(v_tagihan_air.nilai) as pemakaian,
        //                 SUM(t_pembayaran_detail.nilai_diskon) as diskon,
        //                 SUM(v_tagihan_air.total_tanpa_ppn+t_pembayaran_detail.nilai_denda-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan)) as bayar")
        //                 ->from("v_tagihan_air")
        //                 ->join("project",
        //                         "project.id = v_tagihan_air.proyek_id")      
        //                 ->join("t_tagihan_air",
        //                         "t_tagihan_air.proyek_id = project.id")
        //                 ->join("t_tagihan_air_info",
        //                         "t_tagihan_air_info.t_tagihan_air_id = t_tagihan_air.id")
        //                 ->join("t_pembayaran_detail",
        //                         "t_pembayaran_detail.tagihan_service_id = v_tagihan_air.tagihan_id")
        //                 ->join("t_pembayaran",
        //                         "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
        //                 ->join("cara_pembayaran",
        //                         "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
        //                 ->join("kwitansi_referensi",
        //                         "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
        //                 ->join("service",
        //                         "service.id = t_pembayaran_detail.service_id")
        //                 ->where("v_tagihan_air.proyek_id",$project->id)
        //                 ->where("t_pembayaran.cara_pembayaran_id",$cara_bayar)
        //                 ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
        //                 ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")->get()->row();
        // $admin = $Air->admin;
        // $pakai = $Air->pakai;
        // $denda = $Air->denda;
        // $pemakaian = $Air->pemakaian;
        // $diskon = $Air->diskon;
        // $bayar = $Air->bayar;
        // $data->nilai_admin = $admin;
        // $data->denda = $denda;
        // $data->pakai = $pakai;
        // $data->pemakaian = $pemakaian;
        // $data->diskon = $diskon;
        // $data->bayar = $bayar;

        // $GrandAir = $this->db
        //                 ->select("
        //                 SUM(t_tagihan_air_info.pemakaian) as pakai,
        //                 SUM(v_tagihan_air.nilai_administrasi) as admin,
        //                 SUM(v_tagihan_air.nilai_denda) as denda,
        //                 SUM(v_tagihan_air.nilai) as pemakaian,
        //                 SUM(t_pembayaran_detail.nilai_diskon) as diskon,
        //                 SUM(v_tagihan_air.total_tanpa_ppn+t_pembayaran_detail.nilai_denda-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan)) as bayar,
        //                 SUM(t_pembayaran_detail.nilai_denda_pemutihan) as denda_pemutihan,
        //                 SUM(t_pembayaran_detail.nilai_tagihan_pemutihan) as tagihan_pemutihan")
        //                 ->from("v_tagihan_air")
        //                 ->join("project",
        //                         "project.id = v_tagihan_air.proyek_id")      
        //                 ->join("t_tagihan_air",
        //                         "t_tagihan_air.proyek_id = project.id")
        //                 ->join("t_tagihan_air_info",
        //                         "t_tagihan_air_info.t_tagihan_air_id = t_tagihan_air.id")
        //                 ->join("t_pembayaran_detail",
        //                         "t_pembayaran_detail.tagihan_service_id = v_tagihan_air.tagihan_id")
        //                 ->join("t_pembayaran",
        //                         "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
        //                 ->join("cara_pembayaran",
        //                         "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
        //                 ->join("kwitansi_referensi",
        //                         "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
        //                 ->join("service",
        //                         "service.id = t_pembayaran_detail.service_id")
        //                 ->where("v_tagihan_air.proyek_id",$project->id)
        //                 ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
        //                 ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")->get()->row();
        // $grandAdmin = $GrandAir->admin;
        // $grandPakai = $GrandAir->pakai;
        // $grandDenda = $GrandAir->denda;
        // $grandPemakaian = $GrandAir->pemakaian;
        // $grandDiskon = $GrandAir->diskon;
        // $grandBayar = $GrandAir->bayar;
        // $dendaPemutihan = $GrandAir->denda_pemutihan;
        // $tagihanPemutihan = $GrandAir->tagihan_pemutihan;
        // $data->grandAdmin = $grandAdmin;
        // $data->grandDenda = $grandDenda;
        // $data->grandPakai = $grandPakai;
        // $data->grandPemakaian = $grandPemakaian;
        // $data->grandDiskon = $grandDiskon;
        // $data->grandBayar = $grandBayar;
        // $data->dendaPemutihan = $dendaPemutihan;
        // $data->tagihanPemutihan = $tagihanPemutihan;

        //Lingkungan
        $project = $this->m_core->project();
        $periode_awal = substr($periode_awal,6,4)."-".substr($periode_awal,3,2)."-".substr($periode_awal,0,2);
        $periode_akhir = substr($periode_akhir,6,4)."-".substr($periode_akhir,3,2)."-".substr($periode_akhir,0,2);

        $query = $this->db
                        ->select("
                        kawasan.code,
                        v_tagihan_lingkungan.blok,
                        v_tagihan_lingkungan.no_unit,
                        ISNULL(kwitansi_referensi.no_kwitansi,' - ') as no_kwitansi,
                        v_tagihan_lingkungan.periode,
                        t_pembayaran.tgl_bayar,
                        t_pembayaran_detail.nilai_denda,
                        v_tagihan_lingkungan.nilai_bangunan,
                        v_tagihan_lingkungan.nilai_kavling,
                        v_tagihan_lingkungan.nilai_keamanan,
                        v_tagihan_lingkungan.nilai_kebersihan,
                        v_tagihan_lingkungan.total_tanpa_ppn,
                        v_tagihan_lingkungan.ppn,
                        t_pembayaran_detail.nilai_diskon,
                        v_tagihan_lingkungan.total_tanpa_ppn+t_pembayaran_detail.nilai_denda+v_tagihan_lingkungan.ppn-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan) as bayar")
                        ->from("v_tagihan_lingkungan")
                        ->distinct()
                        ->join("project",
                                "project.id = v_tagihan_lingkungan.proyek_id")
                        ->join("kawasan",
                                "kawasan.id = v_tagihan_lingkungan.kawasan_id")
                        ->join("t_pembayaran_detail",
                                "t_pembayaran_detail.tagihan_service_id = v_tagihan_lingkungan.tagihan_id")
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("cara_pembayaran",
                                "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id")
                        ->where("v_tagihan_lingkungan.proyek_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'");
                        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");


        }else{

            $query = $query->where("cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");


        }
        if($kawasan != 'all'){
            $query = $query->where("kawasan_id",$kawasan);
        }if($blok != 'all'){
            $query = $query->where("blok_id",$blok);
        }
        $data = (object)[];
        $data->header=["Kode Kawasan","Kode Blok","No Unit", "No. Kwitansi", "Periode", "Tanggal Bayar", "Denda", 
                        "Nilai Bangunan", "Nilai Tanah", "Nilai Keamanan", "Nilai Kebersihan", "Tagihan", "PPN", "Disc",
                        "Total Bayar"];
        // $data->footer=["Sub Total Lingkungan",$data->];
        // $data->colspan=["7"];
        $result = $query->get()->result();
        $data->isi = $result;

        if($cara_bayar==0){
                $cara_pembayaran_name = "Service Lingkungan - Deposit";
        }else{
        $cara = $this->db
                        ->select("CONCAT(cara_pembayaran.name,' ',bank.name) as gabungan")
                        ->from("cara_pembayaran")
                        ->join("bank",
                                "bank.id = cara_pembayaran.bank_id", "LEFT")
                        ->where("cara_pembayaran.id",$cara_bayar)->get()->row()->gabungan;
        // $cara_pembayaran_name = "Service Lingkungan - ".$cara;
        $cara_pembayaran_name = "Service Lingkungan ";
        }
        $data->cara_bayar = $cara;
        $data->judul = $cara_pembayaran_name;
        $pembayaran_name = "Sub Total Transaksi Lingkungan - ".$cara;
        $data->judul_rekap = $pembayaran_name;
        if($jns_service==1){
            $jns_service = $this->db->select("id")->from("service_jenis")->where("id",$jns_service)->get()->row()->id;
        }
        $data->serviceLingkungan = $jns_service;

        // $Lingkungan = $this->db
        //                 ->select("
        //                 ISNULL(SUM(t_pembayaran_detail.nilai_denda),' - ') as denda,
        //                 ISNULL(SUM(v_tagihan_lingkungan.nilai_bangunan),' - ') as bangunan,
        //                 ISNULL(SUM(v_tagihan_lingkungan.nilai_kavling),' - ') as kavling,
        //                 ISNULL(SUM(v_tagihan_lingkungan.nilai_keamanan),' - ') as keamanan,
        //                 ISNULL(SUM(v_tagihan_lingkungan.nilai_kebersihan),' - ') as kebersihan,
        //                 ISNULL(SUM(v_tagihan_lingkungan.total_tanpa_ppn),' - ') as tagihan,
        //                 ISNULL(SUM(v_tagihan_lingkungan.ppn),' - ') as ppn,
        //                 ISNULL(SUM(t_pembayaran_detail.nilai_diskon),' - ') as diskon,
        //                 ISNULL(SUM(v_tagihan_lingkungan.total_tanpa_ppn+t_pembayaran_detail.nilai_denda+v_tagihan_lingkungan.ppn-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan)),' - ') as bayar")
        //                 ->from("v_tagihan_lingkungan")
        //                 ->join("project",
        //                         "project.id = v_tagihan_lingkungan.proyek_id")
        //                 ->join("t_pembayaran_detail",
        //                         "t_pembayaran_detail.tagihan_service_id = v_tagihan_lingkungan.tagihan_id")
        //                 ->join("t_pembayaran",
        //                         "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
        //                 ->join("cara_pembayaran",
        //                         "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
        //                 ->join("kwitansi_referensi",
        //                         "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
        //                 ->join("service",
        //                         "service.id = t_pembayaran_detail.service_id")
        //                 ->where("v_tagihan_lingkungan.proyek_id",$project->id)
        //                 ->where("t_pembayaran.cara_pembayaran_id",$cara_bayar)
        //                 ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
        //                 ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")->get()->row();
        // $denda = $Lingkungan->denda;
        // $bangunan = $Lingkungan->bangunan;
        // $kavling = $Lingkungan->kavling;
        // $keamanan = $Lingkungan->keamanan;
        // $kebersihan = $Lingkungan->kebersihan;
        // $tagihan = $Lingkungan->tagihan;
        // $ppn = $Lingkungan->ppn;
        // $diskon = $Lingkungan->diskon;
        // $bayar = $Lingkungan->bayar;
        
        // $data->denda = $denda;
        // $data->bangunan = $bangunan;
        // $data->kavling = $kavling;
        // $data->keamanan = $keamanan;
        // $data->kebersihan = $kebersihan;
        // $data->tagihan = $tagihan;
        // $data->ppn = $ppn;
        // $data->diskon = $diskon;
        // $data->bayar = $bayar;

        // $GrandLingkungan = $this->db
        //                 ->select("
        //                 SUM(t_pembayaran_detail.nilai_denda) as denda,
        //                 SUM(v_tagihan_lingkungan.nilai_bangunan) as bangunan,
        //                 SUM(v_tagihan_lingkungan.nilai_kavling) as kavling,
        //                 SUM(v_tagihan_lingkungan.nilai_keamanan) as keamanan,
        //                 SUM(v_tagihan_lingkungan.nilai_kebersihan) as kebersihan,
        //                 SUM(v_tagihan_lingkungan.total_tanpa_ppn) as tagihan,
        //                 SUM(v_tagihan_lingkungan.ppn) as ppn,
        //                 SUM(t_pembayaran_detail.nilai_diskon) as diskon,
        //                 SUM(v_tagihan_lingkungan.total_tanpa_ppn+t_pembayaran_detail.nilai_denda+v_tagihan_lingkungan.ppn-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan)) as bayar,
        //                 SUM(t_pembayaran_detail.nilai_denda_pemutihan) as denda_pemutihan,
        //                 SUM(t_pembayaran_detail.nilai_tagihan_pemutihan) as tagihan_pemutihan")
        //                 ->from("v_tagihan_lingkungan")
        //                 ->join("project",
        //                         "project.id = v_tagihan_lingkungan.proyek_id")
        //                 ->join("t_pembayaran_detail",
        //                         "t_pembayaran_detail.tagihan_service_id = v_tagihan_lingkungan.tagihan_id")
        //                 ->join("t_pembayaran",
        //                         "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
        //                 ->join("cara_pembayaran",
        //                         "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
        //                 ->join("kwitansi_referensi",
        //                         "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
        //                 ->join("service",
        //                         "service.id = t_pembayaran_detail.service_id")
        //                 ->where("v_tagihan_lingkungan.proyek_id",$project->id)
        //                 ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
        //                 ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")->get()->row();
        // $grandDenda = $GrandLingkungan->denda;
        // $grandBangunan = $GrandLingkungan->bangunan;
        // $grandKavling = $GrandLingkungan->kavling;
        // $grandKeamanan = $GrandLingkungan->keamanan;
        // $grandKebersihan = $GrandLingkungan->kebersihan;
        // $grandTagihan = $GrandLingkungan->tagihan;
        // $grandPpn = $GrandLingkungan->ppn;
        // $grandDiskon = $GrandLingkungan->diskon;
        // $grandBayar = $GrandLingkungan->bayar;
        // $dendaPemutihan =  $GrandLingkungan->denda_pemutihan;
        // $tagihanPemutihan = $GrandLingkungan->tagihan_pemutihan; 
        
        // $data->grandDenda = $grandDenda;
        // $data->grandBangunan = $grandBangunan;
        // $data->grandKavling = $grandKavling;
        // $data->grandKeamanan = $grandKeamanan;
        // $data->grandKebersihan = $grandKebersihan;
        // $data->grandTagihan = $grandTagihan;
        // $data->grandPpn = $grandPpn;
        // $data->grandDiskon = $grandDiskon;
        // $data->grandBayar = $grandBayar;
        // $data->dendaPemutihan = $dendaPemutihan;
        // $data->tagihanPemutihan = $tagihanPemutihan;
        return $data;
    }

    public function getAir2($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar,$jns_service)
    {
        $project = $this->m_core->project();
        $periode_awal = substr($periode_awal,6,4)."-".substr($periode_awal,3,2)."-".substr($periode_awal,0,2);
        $periode_akhir = substr($periode_akhir,6,4)."-".substr($periode_akhir,3,2)."-".substr($periode_akhir,0,2);
        
        $query = $this->db
                        ->select("
                        kawasan.code,
                        v_tag ihan_air.blok,
                        v_tagihan_air.no_unit,
                        ISNULL(kwitansi_referensi.no_kwitansi,'-') as no_kwitansi,
                        v_tagihan_air.periode,
                        t_tagihan_air_info.pemakaian,
                        t_pembayaran.tgl_bayar,
                        v_tagihan_air.nilai_administrasi,
                        v_tagihan_air.nilai_denda,
                        v_tagihan_air.nilai,
                        t_pembayaran_detail.nilai_diskon,
                        v_tagihan_air.total_tanpa_ppn+t_pembayaran_detail.nilai_denda-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan) as bayar")
                        ->from("v_tagihan_air")
                        ->join("project",
                                "project.id = v_tagihan_air.proyek_id")
                        ->join("kawasan",
                                "kawasan.id = v_tagihan_air.kawasan_id")      
                        ->join("t_tagihan_air",
                                "t_tagihan_air.proyek_id = project.id")
                        ->join("t_tagihan_air_info",
                                "t_tagihan_air_info.t_tagihan_air_id = t_tagihan_air.id")
                        ->join("t_pembayaran_detail",
                                "t_pembayaran_detail.tagihan_service_id = v_tagihan_air.tagihan_id")
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("cara_pembayaran",
                                "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id")
                        ->where("v_tagihan_air.proyek_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'");

        if($cara_bayar==0){
                $query = $query->where("t_pembayaran_detail.bayar = 0");


        }else{

                $query = $query->where("cara_pembayaran_id", $cara_bayar);
                $query = $query->where("t_pembayaran_detail.bayar > 0");


        }
        if($kawasan != 'all'){
            $query = $query->where("kawasan_id",$kawasan);
        }if($blok != 'all'){
            $query = $query->where("blok_id",$blok);
        }

        $data = (object)[];
        $data->header=["Kode Kawasan","Kode Blok","No Unit", "No. Kwitansi", "Periode", "Pakai (m3)", "Tanggal Bayar", "Nilai Admin", "Denda", 
                        "Pemakaian", "Disc", "Total Bayar"];
        $result = $query->get()->result();
        $data->isi = $result;
        

        if($cara_bayar==0){
                $cara_pembayaran_name = "Service Air - Deposit";
        }else{
            $cara = $this->db
                            ->select("CONCAT(cara_pembayaran.name,' ',bank.name) as gabungan")
                            ->from("cara_pembayaran")
                            ->join("bank",
                                    "bank.id = cara_pembayaran.bank_id", "LEFT")
                            ->where("cara_pembayaran.id",$cara_bayar)->get()->row()->gabungan;
        //     $cara_pembayaran_name = "Service Air - ".$cara;
            $cara_pembayaran_name = "Service Air  ";
        }
        $data->cara_bayar = $cara;
        $data->judul = $cara_pembayaran_name;
        $pembayaran_name = "Rekapitulasi Transaksi Service Air";
        $data->judul_rekap = $pembayaran_name;
        if($jns_service==2){
            $jns_service = $this->db->select("id")->from("service_jenis")->where("id",$jns_service)->get()->row()->id;
        }
        $data->serviceAir = $jns_service;

        // $Air = $this->db
        //                 ->select("
        //                 ISNULL(SUM(t_tagihan_air_info.pemakaian),' - ') as pakai,
        //                 ISNULL(SUM(v_tagihan_air.nilai_administrasi),' - ') as admin,
        //                 ISNULL(SUM(v_tagihan_air.nilai_denda),' - ') as denda,
        //                 ISNULL(SUM(v_tagihan_air.nilai),' - ') as pemakaian,
        //                 ISNULL(SUM(t_pembayaran_detail.nilai_diskon),' - ') as diskon,
        //                 ISNULL(SUM(v_tagihan_air.total_tanpa_ppn+t_pembayaran_detail.nilai_denda-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan)),' - ') as bayar")
        //                 ->from("v_tagihan_air")
        //                 ->join("project",
        //                         "project.id = v_tagihan_air.proyek_id")      
        //                 ->join("t_tagihan_air",
        //                         "t_tagihan_air.proyek_id = project.id")
        //                 ->join("t_tagihan_air_info",
        //                         "t_tagihan_air_info.t_tagihan_air_id = t_tagihan_air.id")
        //                 ->join("t_pembayaran_detail",
        //                         "t_pembayaran_detail.tagihan_service_id = v_tagihan_air.tagihan_id")
        //                 ->join("t_pembayaran",
        //                         "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
        //                 ->join("cara_pembayaran",
        //                         "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
        //                 ->join("kwitansi_referensi",
        //                         "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
        //                 ->join("service",
        //                         "service.id = t_pembayaran_detail.service_id")
        //                 ->where("v_tagihan_air.proyek_id",$project->id)
        //                 ->where("t_pembayaran.cara_pembayaran_id",$cara_bayar)
        //                 ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
        //                 ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")->get()->row();
        // $admin = $Air->admin;
        // $pakai = $Air->pakai;
        // $denda = $Air->denda;
        // $pemakaian = $Air->pemakaian;
        // $diskon = $Air->diskon;
        // $bayar = $Air->bayar;
        // $data->nilai_admin = $admin;
        // $data->denda = $denda;
        // $data->pakai = $pakai;
        // $data->pemakaian = $pemakaian;
        // $data->diskon = $diskon;
        // $data->bayar = $bayar;

        // $GrandAir = $this->db
        //                 ->select("
        //                 SUM(t_tagihan_air_info.pemakaian) as pakai,
        //                 SUM(v_tagihan_air.nilai_administrasi) as admin,
        //                 SUM(v_tagihan_air.nilai_denda) as denda,
        //                 SUM(v_tagihan_air.nilai) as pemakaian,
        //                 SUM(t_pembayaran_detail.nilai_diskon) as diskon,
        //                 SUM(v_tagihan_air.total_tanpa_ppn+t_pembayaran_detail.nilai_denda-(t_pembayaran_detail.nilai_diskon+t_pembayaran_detail.nilai_denda_pemutihan+t_pembayaran_detail.nilai_tagihan_pemutihan)) as bayar,
        //                 SUM(t_pembayaran_detail.nilai_denda_pemutihan) as denda_pemutihan,
        //                 SUM(t_pembayaran_detail.nilai_tagihan_pemutihan) as tagihan_pemutihan")
        //                 ->from("v_tagihan_air")
        //                 ->join("project",
        //                         "project.id = v_tagihan_air.proyek_id")      
        //                 ->join("t_tagihan_air",
        //                         "t_tagihan_air.proyek_id = project.id")
        //                 ->join("t_tagihan_air_info",
        //                         "t_tagihan_air_info.t_tagihan_air_id = t_tagihan_air.id")
        //                 ->join("t_pembayaran_detail",
        //                         "t_pembayaran_detail.tagihan_service_id = v_tagihan_air.tagihan_id")
        //                 ->join("t_pembayaran",
        //                         "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
        //                 ->join("cara_pembayaran",
        //                         "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
        //                 ->join("kwitansi_referensi",
        //                         "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
        //                 ->join("service",
        //                         "service.id = t_pembayaran_detail.service_id")
        //                 ->where("v_tagihan_air.proyek_id",$project->id)
        //                 ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
        //                 ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")->get()->row();
        // $grandAdmin = $GrandAir->admin;
        // $grandPakai = $GrandAir->pakai;
        // $grandDenda = $GrandAir->denda;
        // $grandPemakaian = $GrandAir->pemakaian;
        // $grandDiskon = $GrandAir->diskon;
        // $grandBayar = $GrandAir->bayar;
        // $dendaPemutihan = $GrandAir->denda_pemutihan;
        // $tagihanPemutihan = $GrandAir->tagihan_pemutihan;
        // $data->grandAdmin = $grandAdmin;
        // $data->grandDenda = $grandDenda;
        // $data->grandPakai = $grandPakai;
        // $data->grandPemakaian = $grandPemakaian;
        // $data->grandDiskon = $grandDiskon;
        // $data->grandBayar = $grandBayar;
        // $data->dendaPemutihan = $dendaPemutihan;
        // $data->tagihanPemutihan = $tagihanPemutihan;
        return $data;
    }
    public function getRetribusi($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar){
        $project = $this->m_core->project();
        $periode_awal = substr($periode_awal,6,4)."-".substr($periode_awal,3,2)."-".substr($periode_awal,0,2);
        $periode_akhir = substr($periode_akhir,6,4)."-".substr($periode_akhir,3,2)."-".substr($periode_akhir,0,2);
        $history_ipl = $this->db->select("
                                    CASE isnull(bank.id,0)
                                        WHEN 0 THEN cara_pembayaran.name
                                        ELSE CONCAT(cara_pembayaran.name,'-',bank.name)
                                    END as cara_pembayaran,
                                    t_pembayaran_detail.id as pembayaran_detail_ipl_id,
                                    kawasan.name as kawasan,
                                    blok.name as blok,
                                    unit.no_unit,
                                    customer.name as customer,
                                    t_pembayaran.unit_id,
                                    t_pembayaran.no_kwitansi,
                                    FORMAT(t_tagihan_lingkungan.periode,'MM/yyyy') as periode_tagihan,
                                    FORMAT(t_pembayaran.tgl_bayar,'dd/MM/yyyy') as tgl_bayar_ipl,
                                    t_tagihan_lingkungan_detail.nilai_kavling,
                                    t_tagihan_lingkungan_detail.nilai_bangunan,
                                    t_tagihan_lingkungan_detail.nilai_keamanan,
                                    t_tagihan_lingkungan_detail.nilai_kebersihan,
                                    CONVERT(INT,ROUND((t_tagihan_lingkungan_detail.nilai_kavling
                                    + t_tagihan_lingkungan_detail.nilai_bangunan
                                    + t_tagihan_lingkungan_detail.nilai_keamanan
                                    + t_tagihan_lingkungan_detail.nilai_kebersihan)
                                    *(t_tagihan_lingkungan_detail.nilai_ppn/100.0),0)) as ppn,
                                    CONVERT(INT,ROUND((t_tagihan_lingkungan_detail.nilai_kavling
                                    + t_tagihan_lingkungan_detail.nilai_bangunan
                                    + t_tagihan_lingkungan_detail.nilai_keamanan
                                    + t_tagihan_lingkungan_detail.nilai_kebersihan)
                                    *((100.0+t_tagihan_lingkungan_detail.nilai_ppn)/100.0),0)) as tagihan_ipl,
                                    t_pembayaran_detail.nilai_denda as denda_ipl,
                                    isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit) as bayar_ipl")
                                ->from("t_pembayaran_detail")
                                ->join("t_pembayaran",
                                        "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                                ->join("cara_pembayaran",
                                        "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
                                ->join("bank",
                                        "bank.id = cara_pembayaran.bank_id",
                                        "LEFT")
                                ->join("unit",
                                        "unit.id = t_pembayaran.unit_id")
                                ->join("customer",
                                        "customer.id = unit.pemilik_customer_id")
                                ->join("blok",
                                        "blok.id = unit.blok_id")
                                ->join("kawasan",
                                        "kawasan.id = blok.kawasan_id")
                                ->join("service",
                                        "service.id = t_pembayaran_detail.service_id")
                                ->join("t_tagihan_lingkungan",
                                        "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id
                                        AND service.service_jenis_id = 1")
                                ->join("t_tagihan_lingkungan_detail",
                                        "t_tagihan_lingkungan_detail.t_tagihan_lingkungan_id = t_tagihan_lingkungan.id")
                                ->where("isnull(t_pembayaran.is_void,0)",0)
                                ->where("unit.project_id",$project->id)
                                ->order_by("unit_id, periode_tagihan");
        if(!$kawasan || $kawasan != 'all')
            $history_ipl = $history_ipl->where("kawasan.id",$kawasan);
        if(!$blok || $blok != 'all')
            $history_ipl = $history_ipl->where("blok.id",$blok);
        if($periode_awal)
            $history_ipl = $history_ipl->where("CONVERT(date,tgl_bayar) >=",$periode_awal);
        if($periode_akhir)
            $history_ipl = $history_ipl->where("CONVERT(date,tgl_bayar) <=",$periode_akhir);
        $history_ipl = $history_ipl->get()->result();
        $history_air = $this->db->select("
                                    CASE isnull(bank.id,0)
                                        WHEN 0 THEN cara_pembayaran.name
                                        ELSE CONCAT(cara_pembayaran.name,'-',bank.name)
                                    END as cara_pembayaran,
                                    t_pembayaran_detail.id as pembayaran_detail_air_id,
                                    kawasan.name as kawasan,
                                    blok.name as blok,
                                    unit.no_unit,
                                    customer.name as customer,
                                    t_pembayaran.unit_id,
                                    t_pembayaran.no_kwitansi,
                                    FORMAT(t_tagihan_air.periode,'MM/yyyy') as periode_tagihan,
                                    FORMAT(t_pembayaran.tgl_bayar,'dd/MM/yyyy') as tgl_bayar_air,
                                    t_pencatatan_meter_air.meter_awal,
                                    t_pencatatan_meter_air.meter_akhir,
                                    t_pencatatan_meter_air.meter_akhir - t_pencatatan_meter_air.meter_awal as meter_pakai,
                                    t_tagihan_air_detail.nilai as tagihan_air,
                                    t_pembayaran_detail.nilai_denda as denda_air,
                                    isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit) as bayar_air",false)
                                ->from("t_pembayaran_detail")
                                ->join("t_pembayaran",
                                        "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                                ->join("cara_pembayaran",
                                        "cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
                                ->join("bank",
                                        "bank.id = cara_pembayaran.bank_id",
                                        "LEFT")
                                ->join("unit",
                                        "unit.id = t_pembayaran.unit_id")
                                ->join("customer",
                                        "customer.id = unit.pemilik_customer_id")
                                ->join("blok",
                                        "blok.id = unit.blok_id")
                                ->join("kawasan",
                                        "kawasan.id = blok.kawasan_id")
                                ->join("service",
                                        "service.id = t_pembayaran_detail.service_id")
                                ->join("t_tagihan_air",
                                        "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id
                                        AND service.service_jenis_id = 2")
                                ->join("t_tagihan_air_detail",
                                        "t_tagihan_air_detail.t_tagihan_air_id = t_tagihan_air.id")
                                ->join("t_pencatatan_meter_air",
                                        "t_pencatatan_meter_air.unit_id = t_pembayaran.unit_id	
                                        AND t_pencatatan_meter_air.periode = t_tagihan_air.periode")
                                ->where("isnull(t_pembayaran.is_void,0)",0)
                                ->where("unit.project_id",$project->id)                                
                                ->order_by("unit_id, periode_tagihan");
        if(!$kawasan || $kawasan != 'all')
            $history_air = $history_air->where("kawasan.id",$kawasan);
        if(!$blok || $blok != 'all')
            $history_air = $history_air->where("blok.id",$blok);
        if($periode_awal)
            $history_air = $history_air->where("CONVERT(date,tgl_bayar) >=",$periode_awal);
        if($periode_akhir)
            $history_air = $history_air->where("CONVERT(date,tgl_bayar) <=",$periode_akhir);        
        $history_air = $history_air->get()->result();
        // $history_gabung = $history_ipl;
        $history_gabung = [];
        // echo("history_ipl<pre>");
        //     print_r($history_ipl);
        // echo("</pre>");        
        // unset($history_ipl[1]);
        // echo("history_ipl<pre>");
        //     print_r($history_ipl);
        // echo("</pre>");        
        
        // foreach ($history_ipl as $kl1=> $vl1) {
        //     echo("history_ipl<pre>");
        //         print_r(isset($history_ipl[$kl1]));
        //     echo("</pre>");        
        //     unset($history_ipl[1]);
        // }
        // die;
        
        foreach ($history_ipl as $kl1=> $vl1) {
            if(isset($history_ipl[$kl1])){
                foreach ($history_ipl as $kl2=> $vl2) {
                    if(($vl2->unit_id == $vl1->unit_id)&&($vl2->periode_tagihan == $vl1->periode_tagihan) && ($vl2->pembayaran_detail_ipl_id != $vl1->pembayaran_detail_ipl_id)){
                        $history_ipl[$kl1]->no_kwitansi = $vl1->no_kwitansi.','.$vl2->no_kwitansi;
                        $history_ipl[$kl1]->tgl_bayar_ipl = $vl1->tgl_bayar_ipl.','.$vl2->tgl_bayar_ipl;
                        $history_ipl[$kl1]->denda_ipl += $vl2->denda_ipl;
                        $history_ipl[$kl1]->bayar_ipl += $vl2->bayar_ipl;
                        
                        unset($history_ipl[$kl2]);
                    }
                }
                $history_ipl[$kl1]->total_ipl     = $history_ipl[$kl1]->denda_ipl + $history_ipl[$kl1]->tagihan_ipl;
                $history_ipl[$kl1]->tgl_bayar_air = '';
                $history_ipl[$kl1]->meter_awal    = '-';
                $history_ipl[$kl1]->meter_akhir   = '-';
                $history_ipl[$kl1]->meter_pakai   = '-';
                $history_ipl[$kl1]->tagihan_air   = '-';
                $history_ipl[$kl1]->denda_air     = '-';
                $history_ipl[$kl1]->bayar_air     = '-';
                $history_ipl[$kl1]->tagihan_ipl_air = $history_ipl[$kl1]->total_ipl;
                $history_ipl[$kl1]->bayar_ipl_air = $history_ipl[$kl1]->bayar_ipl; 
            }
        }
        foreach ($history_air as $ka1=> $va1) {
            if(isset($history_air[$ka1])){
                foreach ($history_air as $ka2=> $va2) {
                    if(($va2->unit_id == $va1->unit_id)&&($va2->periode_tagihan == $va1->periode_tagihan) && ($va2->pembayaran_detail_air_id != $va1->pembayaran_detail_air_id)){
                        $history_air[$ka1]->no_kwitansi = $history_air[$ka1]->no_kwitansi.','.$va2->no_kwitansi;
                        $history_air[$ka1]->tgl_bayar_air = $history_air[$ka1]->tgl_bayar_air.','.$va2->tgl_bayar_air;
                        $history_air[$ka1]->denda_air += $va2->denda_air;
                        $history_air[$ka1]->bayar_air += $va2->bayar_air;
                        unset($history_air[$ka2]);
                    }
                    $history_air[$ka1]->total_air     = $history_air[$ka1]->denda_air + $history_air[$ka1]->tagihan_air;

                }
            }
        }
        $history_gabung = $history_ipl;
        foreach ($history_air as $ka => $va) {
            $gabung = false;
            foreach ($history_gabung as $kg=> $vg) {
                if($va->unit_id == $vg->unit_id && $va->periode_tagihan == $vg->periode_tagihan){
                    $history_gabung[$kg]->cara_pembayaran = $va->cara_pembayaran;
                    $history_gabung[$kg]->tgl_bayar_air = $va->tgl_bayar_air;
                    $history_gabung[$kg]->tgl_bayar_air = $va->tgl_bayar_air;
                    $history_gabung[$kg]->meter_awal    = $va->meter_awal;
                    $history_gabung[$kg]->meter_akhir   = $va->meter_akhir;
                    $history_gabung[$kg]->meter_pakai   = $va->meter_pakai;
                    $history_gabung[$kg]->tagihan_air   = $va->tagihan_air;
                    $history_gabung[$kg]->denda_air     = $va->denda_air;
                    $history_gabung[$kg]->bayar_air     = $va->bayar_air;
                    $history_gabung[$kg]->tagihan_ipl_air += $va->total_air;
                    $history_gabung[$kg]->bayar_ipl_air += $va->bayar_air;                    
                    $gabung = true;
                    break;
                }
            }    
            if(!$gabung && isset($va)){
                $tmp = $va;
                $tmp->tgl_bayar_ipl = '';
                $tmp->nilai_kavling = 0;
                $tmp->nilai_bangunan = 0;
                $tmp->nilai_keamanan = 0;
                $tmp->nilai_kebersihan = 0;
                $tmp->ppn = 0;
                $tmp->tagihan_ipl = 0;
                $tmp->denda_ipl = 0;
                $tmp->bayar_ipl = 0;
                array_push($history_gabung,$tmp);
            }

        }
        $history = (object)[];
        $history->air = $history_air;
        $history->ipl = $history_ipl;
        $history->gabung = $history_gabung;
        echo(json_encode($history_gabung));
        // echo("history_air<pre>");
        //     print_r($history_air);
        // echo("</pre>");
        // echo("history_ipl<pre>");
        //     print_r($history_ipl);
        // echo("</pre>");
        // echo("history_gabung<pre>");
        //     print_r($history_gabung);
        // echo("</pre>");
    
    }

    public function getLingkungan($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar,$jns_service)
    {
        $project = $this->m_core->project();
        $periode_awal = substr($periode_awal,6,4)."-".substr($periode_awal,3,2)."-".substr($periode_awal,0,2);
        $periode_akhir = substr($periode_akhir,6,4)."-".substr($periode_akhir,3,2)."-".substr($periode_akhir,0,2);
        
        $query = $this->db
        // format(t_tagihan_lingkungan_detail.nilai_bangunan, 'N0') as nilai_bangunan,

                        ->select("
                                kawasan.code as kawasan,
                                blok.code as blok,
                                unit.no_unit,
                                isnull(kwitansi_referensi.no_kwitansi,'-') as no_kwitansi,
                                t_tagihan_lingkungan.periode as periode,
                                CONVERT(date,t_pembayaran.tgl_bayar) as tgl_bayar,
                                
                                format(t_tagihan_lingkungan_detail.nilai_kavling, 'N0') as nilai_kavling,
                                format(t_tagihan_lingkungan_detail.nilai_keamanan, 'N0') as nilai_keamanan,
                                format(t_tagihan_lingkungan_detail.nilai_kebersihan, 'N0') as nilai_kebersihan,
                                
                                format(t_pembayaran_detail.nilai_tagihan - ((t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn) / (100+t_pembayaran_detail.nilai_ppn)), 'N0') as nilai_tagihan,

                                format(((t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn) / (100+t_pembayaran_detail.nilai_ppn)), 'N0') as nilai_ppn,	

                                format(t_pembayaran_detail.nilai_denda, 'N0') as nilai_denda,


                                format(t_pembayaran_detail.nilai_diskon, 'N0') as nilai_diskon,
                                CASE
                                    WHEN t_pembayaran_detail.is_tunggakan = 1 THEN format(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit), 'N0')
                                    ELSE format ( 0 , 'N0' ) 
                                END as tunggakan,
                                format(isnull(t_pembayaran_detail.nilai_tagihan,0)
                                + isnull(t_pembayaran_detail.nilai_denda,0) 
                                + isnull(t_pembayaran_detail.nilai_penalti,0), 'N0')  as total_tagihan,

                                format(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)
                                        -t_pembayaran_detail.nilai_diskon-t_pembayaran_detail.nilai_tagihan_pemutihan
                                        -t_pembayaran_detail.nilai_denda_pemutihan, 'N0') as nilai_bayar")
                        ->from("t_pembayaran_detail")
                        ->distinct()
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id",
                                "LEFT")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id",
                                "LEFT")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id",
                                "LEFT")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->join("t_tagihan_lingkungan_detail",
                                "t_tagihan_lingkungan_detail.t_tagihan_lingkungan_id = t_tagihan_lingkungan.id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id ")
                        ->where("service.service_jenis_id", $jns_service)
                        ->where("unit.project_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'");
        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");
        }else{
            $query = $query->where("t_pembayaran.cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");
        }
        if($kawasan != 'all'){
            $query = $query->where("kawasan.id",$kawasan);
        }if($blok != 'all'){
            $query = $query->where("blok.id",$blok);
        }
        $data = (object)[];
        $data->header=["Kode Kawasan","Kode Blok","No Unit" , "No. Kwitansi", "Periode", "Tanggal Bayar", "Nilai Tanah", "Nilai Keamanan", "Nilai Kebersihan", "Tagihan", "PPN", "Denda", "Disc", "Tunggakan", "Total Tagihan", "Total Bayar"];
        $result = $query->get()->result();
        $data->isi = $result;  
        // print_r($this->db->last_query())
        if($cara_bayar==0){
            $cara_pembayaran_name = "Deposit";
        }else{
            $cara = $this->db
                            ->select("CONCAT(cara_pembayaran.name,' ',bank.name) as gabungan")
                            ->from("cara_pembayaran")
                            ->join("bank",
                                    "bank.id = cara_pembayaran.bank_id", "LEFT")
                            ->where("cara_pembayaran.id",$cara_bayar)->get()->row()->gabungan;
            $cara_pembayaran_name = $cara;
        }
        $pembayaran_name = "Sub Total Transaksi Lingkungan - ".$cara_pembayaran_name;
        $data->judul_rekap = $pembayaran_name;
        if($jns_service==1){
            $jns_service = $this->db->select("id")->from("service_jenis")->where("id",$jns_service)->get()->row()->id;
        }

        $query = $this->db
        // sum(t_tagihan_lingkungan_detail.nilai_bangunan) as nilai_bangunan,

                        ->select("
            
                                sum(t_tagihan_lingkungan_detail.nilai_kavling) as nilai_kavling,
                                sum(t_tagihan_lingkungan_detail.nilai_keamanan) as nilai_keamanan,
                                sum(t_tagihan_lingkungan_detail.nilai_kebersihan) as nilai_kebersihan,
                                
                                sum(t_pembayaran_detail.nilai_tagihan - ( ( t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn ) / ( 100+t_pembayaran_detail.nilai_ppn ) )) AS nilai_tagihan,
                                sum(( ( t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn ) / ( 100+t_pembayaran_detail.nilai_ppn ) )) AS nilai_ppn,
                                sum(t_pembayaran_detail.nilai_denda) as nilai_denda,

                                sum(t_pembayaran_detail.nilai_diskon) as nilai_diskon,
                                0 as tunggakan,
                                sum(isnull(t_pembayaran_detail.nilai_tagihan,0) 
                                + isnull(t_pembayaran_detail.nilai_denda,0) 
                                + isnull(t_pembayaran_detail.nilai_penalti,0)) as total_tagihan,

                                sum(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)
                                        -t_pembayaran_detail.nilai_diskon-t_pembayaran_detail.nilai_tagihan_pemutihan
                                        -t_pembayaran_detail.nilai_denda_pemutihan) AS nilai_bayar")
                        ->from("t_pembayaran_detail")
                        ->distinct()
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id",
                                "LEFT")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id",
                                "LEFT")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id",
                                "LEFT")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->join("t_tagihan_lingkungan_detail",
                                "t_tagihan_lingkungan_detail.t_tagihan_lingkungan_id = t_tagihan_lingkungan.id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id ")
                        ->where("service.service_jenis_id", $jns_service)
                        ->where("unit.project_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'");
        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");
        }else{
            $query = $query->where("t_pembayaran.cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");
        }
        if($kawasan != 'all'){
            $query = $query->where("kawasan.id",$kawasan);
        }if($blok != 'all'){
            $query = $query->where("blok.id",$blok);
        }
        $result = $query->get()->row();
        $data->footer[0] = 6;
        $data->footer[1] = $result;
        $data->jns_service = $jns_service;
        $data->cara_bayar = $cara_bayar;
        return $data;
    }
    public function getLingkunganUnit($unit_id,$periode_awal,$periode_akhir,$cara_bayar,$jns_service)
    {
        $project = $this->m_core->project();
        $periode_awal = substr($periode_awal,6,4)."-".substr($periode_awal,3,2)."-".substr($periode_awal,0,2);
        $periode_akhir = substr($periode_akhir,6,4)."-".substr($periode_akhir,3,2)."-".substr($periode_akhir,0,2);
        
        $query = $this->db
                        ->select("
                                isnull(kwitansi_referensi.no_kwitansi,'-') as no_kwitansi,
                                t_tagihan_lingkungan.periode as periode,
                                CONVERT(date,t_pembayaran.tgl_bayar) as tgl_bayar,
                                
                                format(t_tagihan_lingkungan_detail.nilai_bangunan, 'N0') as nilai_bangunan,
                                format(t_tagihan_lingkungan_detail.nilai_kavling, 'N0') as nilai_kavling,
                                format(t_tagihan_lingkungan_detail.nilai_keamanan, 'N0') as nilai_keamanan,
                                format(t_tagihan_lingkungan_detail.nilai_kebersihan, 'N0') as nilai_kebersihan,
                                
                                format(t_pembayaran_detail.nilai_tagihan - ((t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn) / (100+t_pembayaran_detail.nilai_ppn)), 'N0') as nilai_tagihan,

                                format(((t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn) / (100+t_pembayaran_detail.nilai_ppn)), 'N0') as nilai_ppn,	

                                format(t_pembayaran_detail.nilai_denda, 'N0') as nilai_denda,


                                format(t_pembayaran_detail.nilai_diskon, 'N0') as nilai_diskon,
                                CASE
                                    WHEN t_pembayaran_detail.is_tunggakan = 1 THEN format(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit), 'N0')
                                    ELSE format(0,'N0')
                                END as tunggakan,
                                format(isnull(t_pembayaran_detail.nilai_tagihan,0)
                                + isnull(t_pembayaran_detail.nilai_denda,0) 
                                + isnull(t_pembayaran_detail.nilai_penalti,0), 'N0')  as total_tagihan,
                                format(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)
                                -t_pembayaran_detail.nilai_diskon-t_pembayaran_detail.nilai_tagihan_pemutihan
                                -t_pembayaran_detail.nilai_denda_pemutihan, 'N0') as nilai_bayar")
                        ->from("t_pembayaran_detail")
                        ->distinct()
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id",
                                "LEFT")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id",
                                "LEFT")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id",
                                "LEFT")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->join("t_tagihan_lingkungan_detail",
                                "t_tagihan_lingkungan_detail.t_tagihan_lingkungan_id = t_tagihan_lingkungan.id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id ")
                        ->where("service.service_jenis_id", $jns_service)
                        ->where("unit.project_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")
                        ->where("unit.id",$unit_id);
        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");
        }else{
            $query = $query->where("t_pembayaran.cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");
        }
        $data = (object)[];
        $data->header=["No. Kwitansi", "Periode", "Tanggal Bayar", "Nilai Bangunan", "Nilai Tanah", "Nilai Keamanan", "Nilai Kebersihan", "Tagihan", "PPN", "Denda", "Disc", "Tunggakan", "Total Tagihan", "Total Bayar"];
        $result = $query->get()->result();
        $data->isi = $result;  
        // print_r($this->db->last_query())
        if($cara_bayar==0){
            $cara_pembayaran_name = "Deposit";
        }else{
            $cara = $this->db
                            ->select("CONCAT(cara_pembayaran.name,' ',bank.name) as gabungan")
                            ->from("cara_pembayaran")
                            ->join("bank",
                                    "bank.id = cara_pembayaran.bank_id", "LEFT")
                            ->where("cara_pembayaran.id",$cara_bayar)->get()->row()->gabungan;
            $cara_pembayaran_name = $cara;
        }
        $pembayaran_name = "Sub Total Transaksi Lingkungan - ".$cara_pembayaran_name;
        $data->judul_rekap = $pembayaran_name;
        if($jns_service==1){
            $jns_service = $this->db->select("id")->from("service_jenis")->where("id",$jns_service)->get()->row()->id;
        }

        $query = $this->db
                        ->select("
            
                                sum(t_tagihan_lingkungan_detail.nilai_bangunan) as nilai_bangunan,
                                sum(t_tagihan_lingkungan_detail.nilai_kavling) as nilai_kavling,
                                sum(t_tagihan_lingkungan_detail.nilai_keamanan) as nilai_keamanan,
                                sum(t_tagihan_lingkungan_detail.nilai_kebersihan) as nilai_kebersihan,
                                
                                sum(t_pembayaran_detail.nilai_tagihan - ( ( t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn ) / ( 100+t_pembayaran_detail.nilai_ppn ) )) AS nilai_tagihan,
                                sum(( ( t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn ) / ( 100+t_pembayaran_detail.nilai_ppn ) )) AS nilai_ppn,
                                sum(t_pembayaran_detail.nilai_denda) as nilai_denda,

                                sum(t_pembayaran_detail.nilai_diskon) as nilai_diskon,
                                0 as tunggakan,
                                sum(isnull(t_pembayaran_detail.nilai_tagihan,0) 
                                + isnull(t_pembayaran_detail.nilai_denda,0) 
                                + isnull(t_pembayaran_detail.nilai_penalti,0)) as total_tagihan,

                                sum(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)
                                -t_pembayaran_detail.nilai_diskon-t_pembayaran_detail.nilai_tagihan_pemutihan
                                -t_pembayaran_detail.nilai_denda_pemutihan) AS nilai_bayar")
                        ->from("t_pembayaran_detail")
                        ->distinct()
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id",
                                "LEFT")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id",
                                "LEFT")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id",
                                "LEFT")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->join("t_tagihan_lingkungan_detail",
                                "t_tagihan_lingkungan_detail.t_tagihan_lingkungan_id = t_tagihan_lingkungan.id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id ")
                        ->where("service.service_jenis_id", $jns_service)
                        ->where("unit.project_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")
                        ->where("unit.id",$unit_id);
        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");
        }else{
            $query = $query->where("t_pembayaran.cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");
        }
        $result = $query->get()->row();
        $data->footer[0] = 3;
        $data->footer[1] = $result;
        $data->jns_service = $jns_service;
        $data->cara_bayar = $cara_bayar;
        return $data;
    }
    public function getAir($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar,$jns_service)
    {
        $project = $this->m_core->project();
        $periode_awal = substr($periode_awal,6,4)."-".substr($periode_awal,3,2)."-".substr($periode_awal,0,2);
        $periode_akhir = substr($periode_akhir,6,4)."-".substr($periode_akhir,3,2)."-".substr($periode_akhir,0,2);
       
        $query = $this->db
                        ->select("
                                kawasan.code as kawasan,
                                blok.code as blok,
                                unit.no_unit,
                                isnull(kwitansi_referensi.no_kwitansi,'-') as no_kwitansi,
                                t_tagihan_air.periode as periode,
                                CONVERT(date,t_pembayaran.tgl_bayar) as tgl_bayar,
                                
                                format((t_pencatatan_meter_air.meter_akhir - t_pencatatan_meter_air.meter_awal), 'N0') as pemakaian,
                                

                                format(t_pembayaran_detail.nilai_tagihan - ((t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn) / (100+t_pembayaran_detail.nilai_ppn)), 'N0') as nilai_tagihan,
                                format(((t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn) / (100+t_pembayaran_detail.nilai_ppn)), 'N0') as nilai_ppn,	

                                format(t_pembayaran_detail.nilai_denda, 'N0') as nilai_denda,
                                
                                
                                format(t_pembayaran_detail.nilai_diskon, 'N0') as nilai_diskon,
                                CASE
                                    WHEN t_pembayaran_detail.is_tunggakan = 1 THEN format(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit), 'N0')
                                    ELSE format(0, 'N0')
                                END as tunggakan,

                                isnull(t_pembayaran_detail.nilai_tagihan,0) 
                                + isnull(t_pembayaran_detail.nilai_denda,0) 
                                + isnull(t_pembayaran_detail.nilai_penalti,0) as total_tagihan,

                                format(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)
                                        -t_pembayaran_detail.nilai_diskon-t_pembayaran_detail.nilai_tagihan_pemutihan
                                        -t_pembayaran_detail.nilai_denda_pemutihan, 'N0') as nilai_bayar")
                        ->from("t_pembayaran_detail")
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id",
                                "LEFT")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id",
                                "LEFT")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id",
                                "LEFT")
                        ->join("t_tagihan_air",
                                "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id")
                        ->join("t_tagihan_air_detail",
                                "t_tagihan_air_detail.t_tagihan_air_id = t_tagihan_air.id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id ")
                        ->join("t_pencatatan_meter_air",
                                "t_pencatatan_meter_air.unit_id = unit.id
                                AND t_pencatatan_meter_air.periode = t_tagihan_air.periode")
                        ->distinct()
                        ->where("service.service_jenis_id", $jns_service)
                        ->where("unit.project_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'");
        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");
        }else{
            $query = $query->where("t_pembayaran.cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");
        }
        if($kawasan != 'all'){
            $query = $query->where("kawasan.id",$kawasan);
        }if($blok != 'all'){
            $query = $query->where("blok.id",$blok);
        }
        $data = (object)[];
        $data->header = [
                            "Kode Kawasan",
                            "Kode Blok",
                            "No Unit", 
                            "No. Kwitansi", 
                            "Periode", 
                            "Tanggal Bayar", 
                            "Pemakaian", 
                            "Tagihan",
                            "PPN",  
                            "Denda",
                            "Disc",
                            "Tunggakan", 
                            "Total Tagihan",
                            "Total Bayar"
                        ];
                        
        $result = $query->get()->result();
        
        $data->isi = $result;

        if($cara_bayar==0){
            $cara_pembayaran_name = "Deposit";
        }else{
            $cara = $this->db
                            ->select("CONCAT(cara_pembayaran.name,' ',bank.name) as gabungan")
                            ->from("cara_pembayaran")
                            ->join("bank",
                                    "bank.id = cara_pembayaran.bank_id", "LEFT")
                            ->where("cara_pembayaran.id",$cara_bayar)->get()->row()->gabungan;
            $cara_pembayaran_name = $cara;
        }
        $pembayaran_name = "Sub Total Transaksi Air - ".$cara_pembayaran_name;
        $data->judul_rekap = $pembayaran_name;
        if($jns_service==1){
            $jns_service = $this->db->select("id")->from("service_jenis")->where("id",$jns_service)->get()->row()->id;
        }

        $query = $this->db
                        ->select("
                                sum(t_pencatatan_meter_air.meter_akhir - t_pencatatan_meter_air.meter_awal) as pemakaian,
                                sum(t_pembayaran_detail.nilai_tagihan - ( ( t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn ) / ( 100+t_pembayaran_detail.nilai_ppn ) )) AS nilai_tagihan,
                                sum(( ( t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn ) / ( 100+t_pembayaran_detail.nilai_ppn ) )) AS nilai_ppn,

                                sum(t_pembayaran_detail.nilai_denda) as nilai_denda,

                                
                                sum(t_pembayaran_detail.nilai_diskon) as nilai_diskon,
                                0 as tunggakan,

                                sum(isnull(t_pembayaran_detail.nilai_tagihan,0) 
                                + isnull(t_pembayaran_detail.nilai_denda,0) 
                                + isnull(t_pembayaran_detail.nilai_penalti,0)) as total_tagihan,
                                
                                sum(isnull( t_pembayaran_detail.bayar, t_pembayaran_detail.bayar_deposit )
                                -t_pembayaran_detail.nilai_diskon
                                -t_pembayaran_detail.nilai_tagihan_pemutihan
                                -t_pembayaran_detail.nilai_denda_pemutihan
                                ) AS nilai_bayar ")
                        ->from("t_pembayaran_detail")
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id",
                                "LEFT")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id",
                                "LEFT")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id",
                                "LEFT")
                        ->join("t_tagihan_air",
                                "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id")
                        ->join("t_tagihan_air_detail",
                                "t_tagihan_air_detail.t_tagihan_air_id = t_tagihan_air.id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id ")
                        ->join("t_pencatatan_meter_air",
                                "t_pencatatan_meter_air.unit_id = unit.id
                                AND t_pencatatan_meter_air.periode = t_tagihan_air.periode")
                        ->distinct()
                        ->where("service.service_jenis_id", $jns_service)
                        ->where("unit.project_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'");
        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");
        }else{
            $query = $query->where("t_pembayaran.cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");
        }
        if($kawasan != 'all'){
            $query = $query->where("kawasan.id",$kawasan);
        }if($blok != 'all'){
            $query = $query->where("blok.id",$blok);
        }
        $result = $query->get()->row();
        $data->footer[0] = 6;
        $data->footer[1] = $result;
        $data->jns_service = $jns_service;
        $data->cara_bayar = $cara_bayar;
        
        return $data;
    }
    public function getAirUnit($unit_id,$periode_awal,$periode_akhir,$cara_bayar,$jns_service) 
    {
        $project = $this->m_core->project();
        $periode_awal = substr($periode_awal,6,4)."-".substr($periode_awal,3,2)."-".substr($periode_awal,0,2);
        $periode_akhir = substr($periode_akhir,6,4)."-".substr($periode_akhir,3,2)."-".substr($periode_akhir,0,2);
       
        $query = $this->db
                        ->select("
                                isnull(kwitansi_referensi.no_kwitansi,'-') as no_kwitansi,
                                t_tagihan_air.periode as periode,
                                CONVERT(date,t_pembayaran.tgl_bayar) as tgl_bayar,
                                
                                format((t_pencatatan_meter_air.meter_akhir - t_pencatatan_meter_air.meter_awal), 'N0') as pemakaian,
                                

                                format(t_pembayaran_detail.nilai_tagihan - ((t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn) / (100+t_pembayaran_detail.nilai_ppn)), 'N0') as nilai_tagihan,
                                format(((t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn) / (100+t_pembayaran_detail.nilai_ppn)), 'N0') as nilai_ppn,	

                                format(t_pembayaran_detail.nilai_denda, 'N0') as nilai_denda,
                                
                                
                                format(t_pembayaran_detail.nilai_diskon, 'N0') as nilai_diskon,
                                CASE
                                    WHEN t_pembayaran_detail.is_tunggakan = 1 THEN format(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit), 'N0')
                                    ELSE format(0, 'N0')
                                END as tunggakan,
                                format(isnull(t_pembayaran_detail.nilai_tagihan,0) 
                                + isnull(t_pembayaran_detail.nilai_denda,0) 
                                + isnull(t_pembayaran_detail.nilai_penalti,0), 'N0')  as total_tagihan,
                                format(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit), 'N0') as nilai_bayar")
                        ->from("t_pembayaran_detail")
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id",
                                "LEFT")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id",
                                "LEFT")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id",
                                "LEFT")
                        ->join("t_tagihan_air",
                                "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id")
                        ->join("t_tagihan_air_detail",
                                "t_tagihan_air_detail.t_tagihan_air_id = t_tagihan_air.id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id ")
                        ->join("t_pencatatan_meter_air",
                                "t_pencatatan_meter_air.unit_id = unit.id
                                AND t_pencatatan_meter_air.periode = t_tagihan_air.periode")
                        ->distinct()
                        ->where("service.service_jenis_id", $jns_service)
                        ->where("unit.project_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")
                        ->where("unit.id",$unit_id);
        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");
        }else{
            $query = $query->where("t_pembayaran.cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");
        }
        $data = (object)[];
        $data->header = [
                            "No. Kwitansi", 
                            "Periode", 
                            "Tanggal Bayar", 
                            "Pemakaian", 
                            "Tagihan",
                            "PPN",  
                            "Denda",
                            "Disc",
                            "Tunggakan", 
                            "Total Tagihan",
                            "Total Bayar"
                        ];
                        
        $result = $query->get()->result();
        
        $data->isi = $result;

        if($cara_bayar==0){
            $cara_pembayaran_name = "Deposit";
        }else{
            $cara = $this->db
                            ->select("CONCAT(cara_pembayaran.name,' ',bank.name) as gabungan")
                            ->from("cara_pembayaran")
                            ->join("bank",
                                    "bank.id = cara_pembayaran.bank_id", "LEFT")
                            ->where("cara_pembayaran.id",$cara_bayar)->get()->row()->gabungan;
            $cara_pembayaran_name = $cara;
        }
        $pembayaran_name = "Sub Total Transaksi Air - ".$cara_pembayaran_name;
        $data->judul_rekap = $pembayaran_name;
        if($jns_service==1){
            $jns_service = $this->db->select("id")->from("service_jenis")->where("id",$jns_service)->get()->row()->id;
        }

        $query = $this->db
                        ->select("
                                sum(t_pencatatan_meter_air.meter_akhir - t_pencatatan_meter_air.meter_awal) as pemakaian,
                                sum(t_pembayaran_detail.nilai_tagihan - ( ( t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn ) / ( 100+t_pembayaran_detail.nilai_ppn ) )) AS nilai_tagihan,
                                sum(( ( t_pembayaran_detail.nilai_tagihan * t_pembayaran_detail.nilai_ppn ) / ( 100+t_pembayaran_detail.nilai_ppn ) )) AS nilai_ppn,

                                sum(t_pembayaran_detail.nilai_denda) as nilai_denda,

                                
                                sum(t_pembayaran_detail.nilai_diskon) as nilai_diskon,
                                0 as tunggakan,

                                sum(isnull(t_pembayaran_detail.nilai_tagihan,0) 
                                + isnull(t_pembayaran_detail.nilai_denda,0) 
                                + isnull(t_pembayaran_detail.nilai_penalti,0)) as total_tagihan,
                                sum(isnull( t_pembayaran_detail.bayar, t_pembayaran_detail.bayar_deposit )) AS nilai_bayar ")
                        ->from("t_pembayaran_detail")
                        ->join("t_pembayaran",
                                "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id",
                                "LEFT")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id",
                                "LEFT")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id",
                                "LEFT")
                        ->join("t_tagihan_air",
                                "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id")
                        ->join("t_tagihan_air_detail",
                                "t_tagihan_air_detail.t_tagihan_air_id = t_tagihan_air.id")
                        ->join("service",
                                "service.id = t_pembayaran_detail.service_id ")
                        ->join("t_pencatatan_meter_air",
                                "t_pencatatan_meter_air.unit_id = unit.id
                                AND t_pencatatan_meter_air.periode = t_tagihan_air.periode")
                        ->distinct()
                        ->where("service.service_jenis_id", $jns_service)
                        ->where("unit.project_id",$project->id)
                        ->where("t_pembayaran.tgl_bayar >= '$periode_awal 00:00:00.000'")
                        ->where("t_pembayaran.tgl_bayar <= '$periode_akhir 23:59:59.000'")
                        ->where("unit.id",$unit_id);
        
        if($cara_bayar==0){
            $query = $query->where("t_pembayaran_detail.bayar = 0");
        }else{
            $query = $query->where("t_pembayaran.cara_pembayaran_id", $cara_bayar);
            $query = $query->where("t_pembayaran_detail.bayar > 0");
        }
        $result = $query->get()->row();
        $data->footer[0] = 3;
        $data->footer[1] = $result;
        $data->jns_service = $jns_service;
        $data->cara_bayar = $cara_bayar;
        
        return $data;
    }
    
}