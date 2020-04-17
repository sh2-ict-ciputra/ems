<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kwitansi extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('Cetakan/m_konfirmasi_tagihan');
        $this->load->model('Setting/m_parameter_project');
        $this->load->model('m_core');
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();
    }
//     public function index(){
//         $this->load->library('pdf');
//         $this->pdf->set_option('defaultMediaType', 'all');
//         $this->pdf->set_option('isFontSubsettingEnabled', true);
//         $this->pdf->set_option('isHtml5ParserEnabled', true);
//         $this->pdf->setPaper('A5', 'landscape');
//         $this->pdf->filename = "laporan-petanikode.pdf";
        
//         $this->pdf->load_view('konfirmasi_tagihan', $data);
    
    
//     }
    public function gabungan(){
        $project = $this->m_core->project();

        $this->load->library('pdf');

        $pembayaran_id_tmp = $this->input->get("pembayaran_id");
        $pembayaran_id_tmp = explode(",",$pembayaran_id_tmp);
        $pembayaran_id = (object)[];
        $pembayaran_id->{1} = [];
        $pembayaran_id->{2} = [];
        $pembayaran_id->{3} = [];
        $pembayaran_id->{4} = [];
        $pembayaran_id->{5} = [];
        $pembayaran_id_gabungan = [];
        foreach ($pembayaran_id_tmp as $v) {
            $tmp = explode(".",$v);    
            $tmp_no_kwitansi = $tmp[1];
        
            array_push($pembayaran_id->{"$tmp[0]"},$tmp[1]);
            array_push($pembayaran_id_gabungan,$tmp[1]);
        }
        $unit = $this->db
                        ->select("
                                unit.project_id,
                                kawasan.code as kawasan_code,
                                blok.code as blok_code,
                                pemilik.id as pemilik_id,
                                kawasan.name as kawasan,
                                blok.name as blok,
                                unit.no_unit,
                                pemilik.name as pemilik,
                                unit_air.no_seri_meter as no_meter,
                                unit.virtual_account,
                                FORMAT (t_pembayaran.tgl_bayar, 'ddMMyyyy') as tgl_bayar")
                        ->from("t_pembayaran")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id")
                        ->join("unit_air",
                                "unit_air.unit_id = unit.id",
                                "LEFT")
                        ->join("blok",
                                "blok.id = unit.blok_id")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id")
                        ->join("customer as pemilik",
                                "pemilik.id = unit.pemilik_customer_id")
                        ->where_in("t_pembayaran.id",$pembayaran_id_gabungan)
                        ->get()->row();
        
        $meter = $this->db
                            ->select("
                                    isnull(min(t_pencatatan_meter_air.meter_awal),0) as meter_awal,
                                    isnull(max(t_pencatatan_meter_air.meter_akhir),0) as meter_akhir,
                                    isnull(max(t_pencatatan_meter_air.meter_akhir) - min(t_pencatatan_meter_air.meter_awal),0) as meter_pakai
                                    ")
                            ->from("t_pembayaran")
                            ->join("t_pembayaran_detail",
                                    "t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
                            ->join("service",
                                "service.id = t_pembayaran_detail.service_id")
                            ->join("v_tagihan_air",
                                    "v_tagihan_air.tagihan_id = t_pembayaran_detail.tagihan_service_id
                                    AND t_pembayaran_detail.service_id = service.id
                                    AND service.service_jenis_id = 2")
                            ->join("t_pencatatan_meter_air",
                                    "t_pencatatan_meter_air.periode = v_tagihan_air.periode
                                    AND t_pencatatan_meter_air.unit_id = t_pembayaran.unit_id");
        
        if($pembayaran_id->{2}){
            
            $meter = $meter->where_in("t_pembayaran.id",$pembayaran_id->{2});
        }else{
            $meter = $meter->where("t_pembayaran.id",null);
        }
        $meter = $meter->get()->row();

        $periode = $this->db
                            ->select("
                                    min(isnull(v_tagihan_lingkungan.periode,v_tagihan_air.periode)) as periode_awal,
                                    max(isnull(v_tagihan_lingkungan.periode,v_tagihan_air.periode)) as periode_akhir")
                            ->from("t_pembayaran")
                            ->join("t_pembayaran_detail",
                                    "t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
                            ->join("service",
                                    "service.id = t_pembayaran_detail.service_id")
                            ->join("v_tagihan_lingkungan",
                                    "v_tagihan_lingkungan.tagihan_id = t_pembayaran_detail.tagihan_service_id
                                    AND t_pembayaran_detail.service_id = service.id
                                    AND service.service_jenis_id = 1",
                                    "LEFT")
                            ->join("v_tagihan_air",
                                    "v_tagihan_air.tagihan_id = t_pembayaran_detail.tagihan_service_id
                                    AND t_pembayaran_detail.service_id = service.id
                                    AND service.service_jenis_id = 2",
                                    "LEFT")
                            ->where_in("t_pembayaran.id",$pembayaran_id_gabungan)
                            ->order_by("periode_awal")
                            ->get()->result();
        $periode_first_v2  = substr($periode[0]->periode_awal,5,2)."/".substr($periode[0]->periode_awal,0,4);
        $periode_last_v2  = substr(end($periode)->periode_akhir,5,2)."/".substr(end($periode)->periode_akhir,0,4);
        
        $periode_awal  = substr($periode[0]->periode_awal,0,4)."/".substr($periode[0]->periode_awal,5,2)."/01";
        $periode_akhir  = substr(end($periode)->periode_akhir,0,4)."/".substr(end($periode)->periode_akhir,5,2)."/01";

        $periode_first  = $this->bln_indo(substr($periode[0]->periode_awal,5,2))." ".substr($periode[0]->periode_awal,0,4);
        $periode_last  = $this->bln_indo(substr(end($periode)->periode_akhir,5,2))." ".substr(end($periode)->periode_akhir,0,4);
        $pembayaran_lingkungan  = $this->db
                                            ->select("
                                                    sum(t_pembayaran_detail.nilai_tagihan) as tagihan,
                                                    sum(t_pembayaran_detail.nilai_ppn)/(DATEDIFF(MONTH,'$periode_awal','$periode_akhir')+1) as ppn,
                                                    sum(t_pembayaran_detail.nilai_denda) as denda,
                                                    sum(t_pembayaran_detail.nilai_diskon) as diskon,
                                                    sum(t_pembayaran_detail.bayar) as total")
                                            ->from("t_pembayaran")
                                            ->join("t_pembayaran_detail",
                                                    "t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
                                            ->join("service",
                                                    "service.id = t_pembayaran_detail.service_id
                                                    AND service.service_jenis_id = 1");
        
        if($pembayaran_id->{1}){
            $pembayaran_lingkungan = $pembayaran_lingkungan->where_in("t_pembayaran.id",$pembayaran_id->{1});
        }else{
            $pembayaran_lingkungan = $pembayaran_lingkungan->where("t_pembayaran.id",null);
        }
            $pembayaran_lingkungan = $pembayaran_lingkungan->get()->row();

        if($pembayaran_lingkungan->tagihan){
            $pembayaran_lingkungan_periode = $this->db
                                                ->select("
                                                    FORMAT (t_tagihan_lingkungan.periode, 'MM/yyyy ') as periode")
                                                ->from("t_pembayaran")
                                                ->join("t_pembayaran_detail",
                                                        "t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
                                                ->join("t_tagihan_lingkungan",
                                                        "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                                                ->join("service",
                                                        "service.id = t_pembayaran_detail.service_id
                                                        AND service.service_jenis_id = 1");
            if($pembayaran_id->{1}){
                $pembayaran_lingkungan_periode = $pembayaran_lingkungan_periode->where_in("t_pembayaran.id",$pembayaran_id->{1});
            }else{
                $pembayaran_lingkungan_periode = $pembayaran_lingkungan_periode->where("t_pembayaran.id",null);
            }
                $pembayaran_lingkungan_periode = $pembayaran_lingkungan_periode->order_by('t_tagihan_lingkungan.periode')->get()->result();
            
            $pembayaran_lingkungan_periode_awal = $pembayaran_lingkungan_periode[0]->periode;
            $pembayaran_lingkungan_periode_akhir = end($pembayaran_lingkungan_periode)->periode;

        }else{
            $pembayaran_lingkungan_periode_awal = 0;
            $pembayaran_lingkungan_periode_akhir = 0;
        }
        $pembayaran_air  = $this->db
                                            ->select("
                                                    sum(t_pembayaran_detail.nilai_tagihan) as tagihan,
                                                    sum(t_pembayaran_detail.nilai_ppn) as ppn,
                                                    sum(t_pembayaran_detail.nilai_denda) as denda,
                                                    sum(t_pembayaran_detail.nilai_diskon) as diskon,
                                                    sum(t_pembayaran_detail.bayar) as total")
                                            ->from("t_pembayaran")
                                            ->join("t_pembayaran_detail",
                                                    "t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
                                            ->join("service",
                                                    "service.id = t_pembayaran_detail.service_id
                                                    AND service.service_jenis_id = 2");
        if($pembayaran_id->{2}){
            $pembayaran_air = $pembayaran_air->where_in("t_pembayaran.id",$pembayaran_id->{2});
        }else{
            $pembayaran_air = $pembayaran_air->where("t_pembayaran.id",null);
        }
        $pembayaran_air = $pembayaran_air->get()->row();
        if($pembayaran_air->tagihan){

            $pembayaran_air_periode  = $this->db
                                                ->select("
                                                        FORMAT (t_tagihan_air.periode, 'MM/yyyy ') as periode")
                                                ->from("t_pembayaran")
                                                ->join("t_pembayaran_detail",
                                                        "t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
                                                ->join("t_tagihan_air",
                                                        "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id")
                                                ->join("service",
                                                        "service.id = t_pembayaran_detail.service_id
                                                        AND service.service_jenis_id = 2");
            if($pembayaran_id->{2}){
                $pembayaran_air_periode = $pembayaran_air_periode->where_in("t_pembayaran.id",$pembayaran_id->{2});
            }else{
                $pembayaran_air_periode = $pembayaran_air_periode->where("t_pembayaran.id",null);
            }
            $pembayaran_air_periode = $pembayaran_air_periode->order_by('t_tagihan_air.periode')->get()->result();

            $pembayaran_air_periode_awal = $pembayaran_air_periode[0]->periode;
            $pembayaran_air_periode_akhir = end($pembayaran_air_periode)->periode;
        }else{
            $pembayaran_air_periode_awal = 0;
            $pembayaran_air_periode_akhir = 0;
        }
        $saldo_deposit_tmp = $this->db
                                ->select("min(t_deposit_detail.tgl_tambah) as tgl_tambah")
                                ->from("t_pembayaran")
                                ->join("unit",
                                        "unit.id = t_pembayaran.unit_id")
                                ->join("t_deposit",
                                        "t_deposit.customer_id = unit.pemilik_customer_id")
                                ->join("t_deposit_detail",
                                        "t_deposit_detail.t_deposit_id = t_deposit.id
                                        AND t_deposit_detail.tgl_tambah = t_pembayaran.tgl_tambah")
                                ->where_in("t_pembayaran.id", $pembayaran_id_gabungan)
                                ->get()->row(); 
        $pemakaian_deposit = $this->db
                                ->select("sum(t_pembayaran_detail.bayar_deposit) as deposit")
                                ->from("t_pembayaran")
                                ->join("t_pembayaran_detail",
                                        "t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
                                ->where_in("t_pembayaran.id",$pembayaran_id_gabungan)
                                ->get()->row();

        $saldo_deposit_tmp = $saldo_deposit_tmp?$saldo_deposit_tmp->tgl_tambah:null;
        $pemakaian_deposit = $pemakaian_deposit?$pemakaian_deposit->deposit:null;

        $saldo_deposit = $this->db
                                ->select("sum(t_deposit_detail.nilai) as nilai")
                                ->from("t_deposit")
                                ->join("t_deposit_detail",
                                        "t_deposit_detail.t_deposit_id = t_deposit.id
                                        AND t_deposit_detail.tgl_tambah < '$saldo_deposit_tmp'")
                                ->where("t_deposit.customer_id",$unit->pemilik_id)
                                ->where("t_deposit_detail.nilai > 0")
                                ->get()->row();
        $saldo_deposit = $saldo_deposit?$saldo_deposit->nilai:null;
        $sisa_deposit = $saldo_deposit-$pemakaian_deposit;
        
        $saldo_deposit = number_format($saldo_deposit,0,",",".");
        $pemakaian_deposit = number_format($pemakaian_deposit,0,",",".");
        $sisa_deposit = number_format($sisa_deposit,0,",",".");
        $total_ppn=($pembayaran_lingkungan->ppn/100)*((100+$pembayaran_lingkungan->ppn)/100);

        $grand_total = ($pembayaran_lingkungan->total-$pembayaran_lingkungan->diskon)+($pembayaran_air->total-$pembayaran_air->diskon);
        $terbilang = strtoupper($this->terbilang($grand_total));
        $grand_total = number_format($grand_total,0,",",".");
        $pembayaran_lingkungan->tagihan_tanpa_ppn = ($pembayaran_lingkungan->tagihan)/((100+$pembayaran_lingkungan->ppn)/100);
        $pembayaran_lingkungan->ppn_rupiah = $pembayaran_lingkungan->tagihan - ($pembayaran_lingkungan->tagihan)/((100+$pembayaran_lingkungan->ppn)/100);

        $pembayaran_air->tagihan = number_format($pembayaran_air->tagihan,0,",",".");
        $pembayaran_lingkungan->tagihan_tanpa_ppn = number_format($pembayaran_lingkungan->tagihan_tanpa_ppn,0,",",".");
        $pembayaran_lingkungan->ppn_rupiah = number_format($pembayaran_lingkungan->ppn_rupiah,0,",",".");
        $pembayaran_lingkungan->denda = number_format($pembayaran_lingkungan->denda,0,",",".");
        $pembayaran_lingkungan->total = number_format($pembayaran_lingkungan->total-$pembayaran_lingkungan->diskon,0,",",".");
        $pembayaran_lingkungan->diskon = number_format($pembayaran_lingkungan->diskon,0,",",".");
        $pembayaran_air->denda = number_format($pembayaran_air->denda,0,",",".");
        $pembayaran_air->total = number_format($pembayaran_air->total-$pembayaran_air->diskon,0,",",".");
        $pembayaran_air->diskon = number_format($pembayaran_air->diskon,0,",",".");
        // echo("<pre>");
        //     print_r($pembayaran_air);
        // echo("</pre>");
        // echo("<pre>");
        //     print_r($pembayaran_lingkungan);
        // echo("</pre>");
        // echo("<pre>");
        //     print_r($meter);
        // echo("</pre>");
        // $this->load->view("proyek/cetakan/kwitansi_gabungan",[
        //         "unit"                  => $unit,
        //         "meter"                 => $meter,
        //         "periode_first"         => $periode_first,
        //         "periode_last"          => $periode_last,
        //         "pembayaran_lingkungan" => $pembayaran_lingkungan,
        //         "pembayaran_air"        => $pembayaran_air,
        //         "grand_total"           => $grand_total,
        //         "terbilang"             => $terbilang,
        //         "saldo_deposit"         => $saldo_deposit,
        //         "pemakaian_deposit"     => $pemakaian_deposit,
        //         "sisa_deposit"          => $sisa_deposit,
        //         "total_ppn"             => $total_ppn,
        //         "date"                  => date("Y-m-d"),
        //         "user"                  => $this->session->userdata('name')
        //         ]);
        $no_referensi = "";
        // echo("<pre>");
        // print_r($project);
        // echo("</pre>");
        // echo("<pre>");
        // print_r($unit);
        // echo("</pre>");
        $unit_id = $unit->project_id.$unit->kawasan_code.$unit->blok_code.'/'.$unit->no_unit;
        
        if($project->id == 17){
                $this->pdf->load_view("proyek/cetakan/kwitansi_global_tanpa_logo",[
                        "pembayaran_lingkungan_periode_awal" => $pembayaran_lingkungan_periode_awal,
                        "pembayaran_lingkungan_periode_akhir" => $pembayaran_lingkungan_periode_akhir,
                        "pembayaran_air_periode_awal" => $pembayaran_air_periode_awal,
                        "pembayaran_air_periode_akhir" => $pembayaran_air_periode_akhir,
                        "no_kwitansi"           => $tmp_no_kwitansi,
                        "project"               => $project,
                        "unit_id"               => $unit_id,
                        "unit"                  => $unit,
                        "meter"                 => $meter,
                        "periode_first"         => $periode_first_v2,
                        "periode_last"          => $periode_last_v2,
                        "pembayaran_lingkungan" => $pembayaran_lingkungan,
                        "pembayaran_air"        => $pembayaran_air,
                        "grand_total"           => $grand_total,
                        "terbilang"             => $terbilang,
                        "saldo_deposit"         => $saldo_deposit,
                        "pemakaian_deposit"     => $pemakaian_deposit,
                        "sisa_deposit"          => $sisa_deposit,
                        "total_ppn"             => $total_ppn,
                        "date"                  => date("Y-m-d"),
                        "user"                  => $this->session->userdata('name'),
                        "no_referensi"          => $no_referensi
                        ]);
        }if($project->id == 13){
                $this->pdf->load_view("proyek/cetakan/kwitansi_global",[
                        "pembayaran_lingkungan_periode_awal" => $pembayaran_lingkungan_periode_awal,
                        "pembayaran_lingkungan_periode_akhir" => $pembayaran_lingkungan_periode_akhir,
                        "pembayaran_air_periode_awal" => $pembayaran_air_periode_awal,
                        "pembayaran_air_periode_akhir" => $pembayaran_air_periode_akhir,
                        "no_kwitansi"           => $tmp_no_kwitansi,
                        "project"               => $project,
                        "unit_id"               => $unit_id,
                        "unit"                  => $unit,
                        "meter"                 => $meter,
                        "periode_first"         => $periode_first_v2,
                        "periode_last"          => $periode_last_v2,
                        "pembayaran_lingkungan" => $pembayaran_lingkungan,
                        "pembayaran_air"        => $pembayaran_air,
                        "grand_total"           => $grand_total,
                        "terbilang"             => $terbilang,
                        "saldo_deposit"         => $saldo_deposit,
                        "pemakaian_deposit"     => $pemakaian_deposit,
                        "sisa_deposit"          => $sisa_deposit,
                        "total_ppn"             => $total_ppn,
                        "date"                  => date("Y-m-d"),
                        "user"                  => $this->session->userdata('name'),
                        "no_referensi"          => $no_referensi
                        ]);
        }
        else
        if($project->id == 12){
                $this->pdf->load_view("proyek/cetakan/kwitansi_gabungan_citragran",[
                        "unit"                  => $unit,
                        "meter"                 => $meter,
                        "periode_first"         => $periode_first_v2,
                        "periode_last"          => $periode_last_v2,
                        "pembayaran_lingkungan" => $pembayaran_lingkungan,
                        "pembayaran_air"        => $pembayaran_air,
                        "grand_total"           => $grand_total,
                        "terbilang"             => $terbilang,
                        "saldo_deposit"         => $saldo_deposit,
                        "pemakaian_deposit"     => $pemakaian_deposit,
                        "sisa_deposit"          => $sisa_deposit,
                        "total_ppn"             => $total_ppn,
                        "date"                  => date("Y-m-d"),
                        "user"                  => $this->session->userdata('name'),
                        "no_referensi"          => $no_referensi
                        ]);
        }elseif($project->id == 2){
                $this->pdf->load_view("proyek/cetakan/kwitansi_gabungan_citraland_full",[
                        "unit"                  => $unit,
                        "meter"                 => $meter,
                        "periode_first"         => $periode_first_v2,
                        "periode_last"          => $periode_last_v2,
                        "pembayaran_lingkungan" => $pembayaran_lingkungan,
                        "pembayaran_air"        => $pembayaran_air,
                        "grand_total"           => $grand_total,
                        "terbilang"             => $terbilang,
                        "saldo_deposit"         => $saldo_deposit,
                        "pemakaian_deposit"     => $pemakaian_deposit,
                        "sisa_deposit"          => $sisa_deposit,
                        "total_ppn"             => $total_ppn,
                        "date"                  => date("Y-m-d"),
                        "user"                  => $this->session->userdata('name'),
                        "no_referensi"          => $no_referensi
                        ]);
        }else{
                $this->pdf->load_view("proyek/cetakan/kwitansi_gabungan",[
                "unit"                  => $unit,
                "meter"                 => $meter,
                "periode_first"         => $periode_first,
                "periode_last"          => $periode_last,
                "pembayaran_lingkungan" => $pembayaran_lingkungan,
                "pembayaran_air"        => $pembayaran_air,
                "grand_total"           => $grand_total,
                "terbilang"             => $terbilang,
                "saldo_deposit"         => $saldo_deposit,
                "pemakaian_deposit"     => $pemakaian_deposit,
                "sisa_deposit"          => $sisa_deposit,
                "total_ppn"             => $total_ppn,
                "date"                  => date("Y-m-d"),
                "user"                  => $this->session->userdata('name'),
                "no_referensi"          => $no_referensi
                ]);
        }
    }
    public function lingkungan($pembayaran_id=null){
        $this->load->library('pdf');
        $project = $this->m_core->project();

        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "konfirmasi_tagihan.pdf";
        
        $no_referensi = $this->db
                        ->select("no_referensi")
                        ->from("t_pembayaran_detail")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
                        ->where("t_pembayaran_detail.t_pembayaran_id",$pembayaran_id)
                        ->get()->row()->no_referensi;
        $no_referensi2 = $this->db->select("no_kwitansi")
                                        ->from("t_pembayaran")
                                        ->where("id",$pembayaran_id)
                                        ->get()->row()->no_kwitansi;
        $periode = $this->db
                        ->select("periode")
                        ->from("t_pembayaran_detail")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->where("t_pembayaran_detail.t_pembayaran_id",$pembayaran_id)
                        ->order_by("periode")
                        ->get()->result();
        
        $unit = $this->db
                        ->select("
                            unit.no_unit,
                            blok.name as blok,
                            kawasan.name as kawasan,
                            customer.name as pemilik    
                        ")
                        ->from("t_pembayaran")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id")
                        ->join("blok",
                                "blok.id = unit.blok_id")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id")
                        ->join("customer",
                                "customer.id = unit.pemilik_customer_id")
                        ->where("t_pembayaran.id",$pembayaran_id)
                        ->get()->row();

        $tagihan = $this->db
                        ->select(" 
                                REPLACE(CONVERT(varchar, CAST(sum(isnull
                                        (t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)-(nilai_denda+nilai_penalti)
                                )AS money), 1), '.00', '') as tagihan,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_denda,0)+isnull(t_pembayaran_detail.nilai_penalti,0))AS money), 1), '.00', '') as denda,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar_deposit,0))AS money), 1), '.00', '') as deposit,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_diskon,0))AS money), 1), '.00', '') as diskon,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar-t_pembayaran_detail.nilai_diskon,t_pembayaran_detail.bayar_deposit-t_pembayaran_detail.nilai_diskon))AS money), 1), '.00', '') as total")
                        ->from("t_pembayaran_detail")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->where("t_pembayaran_id",$pembayaran_id)
                        ->get()->row();
        $periode_first  = $this->bln_indo(substr($periode[0]->periode,5,2))." ".substr($periode[0]->periode,0,4);
        $periode_last  = $this->bln_indo(substr(end($periode)->periode,5,2))." ".substr(end($periode)->periode,0,4);
        $terbilang = $this->terbilang(str_replace(",","",$tagihan->total));
        
        // $this->pdf->load_view("proyek/cetakan/kwitansi_lingkungan");
        $tanggal = (date("d")." ".$this->bln_indo(date("m"))." ".date("Y"));
        if($project->id == 13){
                $periode_first  = substr($this->bln_indo(substr($periode[0]->periode,5,2)),0,3)." ".substr($periode[0]->periode,0,4);
                $periode_last   = substr($this->bln_indo(substr(end($periode)->periode,5,2)),0,3)." ".substr(end($periode)->periode,0,4);
                $tanggal = (date("d")." ".substr($this->bln_indo(date("m")),0,3)." ".date("Y"));
        
                $this->pdf->load_view("proyek/cetakan/kwitansi_lingkungan_citraIndahCity",[
                                "periode_last"  => $periode_last,
                                "periode_first" => $periode_first,
                                "tagihan"       => $tagihan,
                                "terbilang"     => strtoupper($terbilang),
                                "tanggal"       => $tanggal,
                                "unit"          => $unit,
                                "no_referensi"  => $no_referensi
                                ]);
        }elseif($project->id == 2){
                $this->pdf->load_view("proyek/cetakan/kwitansi_lingkungan_bizpark",[
                            "periode_last"  => $periode_last,
                            "periode_first" => $periode_first,
                            "tagihan"       => $tagihan,
                            "terbilang"     => strtoupper($terbilang),
                            "tanggal"       => $tanggal,
                            "unit"          => $unit,
                            "no_referensi"  => $no_referensi,
                            "no_referensi2"  => $no_referensi2
                            ]);
                            
        }elseif($project->id == 4){
        $this->pdf->load_view("proyek/cetakan/kwitansi_lingkungan_bizpark",[
                        "periode_last"  => $periode_last,
                        "periode_first" => $periode_first,
                        "tagihan"       => $tagihan,
                        "terbilang"     => strtoupper($terbilang),
                        "tanggal"       => $tanggal,
                        "unit"          => $unit,
                        "no_referensi"  => $no_referensi
                        ]);
            }else{
                $this->pdf->load_view("proyek/cetakan/kwitansi_lingkungan_full",[
                            "periode_last"  => $periode_last,
                            "periode_first" => $periode_first,
                            "tagihan"       => $tagihan,
                            "terbilang"     => strtoupper($terbilang),
                            "tanggal"       => $tanggal,
                            "unit"          => $unit,
                            "no_referensi"  => $no_referensi
                            ]);
        }
    }
    public function air($pembayaran_id=null){
        $this->load->library('pdf');

        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "konfirmasi_tagihan.pdf";
        $meter_air_TMP = $this->db
                                ->select("
                                    t_pencatatan_meter_air.meter_awal,
                                    t_pencatatan_meter_air.meter_akhir")
                                ->from("t_pembayaran")
                                ->join("t_pembayaran_detail",
                                        "t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
                                ->join("t_tagihan_air",
                                        "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id")
                                ->join("t_pencatatan_meter_air",
                                        "t_pencatatan_meter_air.unit_id = t_tagihan_air.unit_id
                                        AND t_pencatatan_meter_air.periode = t_tagihan_air.periode")
                                ->where("t_pembayaran.id",$pembayaran_id)
                                ->order_by("t_pencatatan_meter_air.periode")
                                ->get()->result();
        $meter = (object)[];
        $meter->awal = $meter_air_TMP[0]->meter_awal;
        $meter->akhir = end($meter_air_TMP)->meter_akhir;
        $meter->pakai = $meter->akhir-$meter->awal;
        
        
    
        $no_referensi = $this->db
                        ->select("no_referensi")
                        ->from("t_pembayaran_detail")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
                        ->where("t_pembayaran_detail.t_pembayaran_id",$pembayaran_id)
                        ->get()->row()->no_referensi;

        $periode = $this->db
                        ->select("periode")
                        ->from("t_pembayaran_detail")
                        ->join("t_tagihan_air",
                                "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id")
                        ->where("t_pembayaran_detail.t_pembayaran_id",$pembayaran_id)
                        ->order_by("periode")
                        ->get()->result();
        
        $unit = $this->db
                        ->select("
                            unit.no_unit,
                            blok.name as blok,
                            kawasan.name as kawasan,
                            customer.name as pemilik    
                        ")
                        ->from("t_pembayaran")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id")
                        ->join("blok",
                                "blok.id = unit.blok_id")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id")
                        ->join("customer",
                                "customer.id = unit.pemilik_customer_id")
                        ->where("t_pembayaran.id",$pembayaran_id)
                        ->get()->row();

        $tagihan = $this->db
                        ->select(" 
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar-(nilai_denda+nilai_penalti),t_pembayaran_detail.bayar_deposit-(nilai_denda+nilai_penalti)))AS money), 1), '.00', '') as tagihan,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_denda,0)+isnull(t_pembayaran_detail.nilai_penalti,0))AS money), 1), '.00', '') as denda,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar_deposit,0))AS money), 1), '.00', '') as deposit,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_diskon,0))AS money), 1), '.00', '') as diskon,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit))AS money), 1), '.00', '') as total")
                        ->from("t_pembayaran_detail")
                        ->join("t_tagihan_air",
                                "t_tagihan_air.id = t_pembayaran_detail.tagihan_service_id")
                        ->where("t_pembayaran_id",$pembayaran_id)
                        ->get()->row();
        $periode_first  = $this->bln_indo(substr($periode[0]->periode,5,2))." ".substr($periode[0]->periode,0,4);
        $periode_last  = $this->bln_indo(substr(end($periode)->periode,5,2))." ".substr(end($periode)->periode,0,4);
        $terbilang = $this->terbilang(str_replace(",","",$tagihan->total));
        
        // $this->pdf->load_view("proyek/cetakan/kwitansi_air");
        $tanggal = (date("d")." ".$this->bln_indo(date("m"))." ".date("Y"));

        $this->pdf->load_view("proyek/cetakan/kwitansi_air",[
                            "periode_last"  => $periode_last,
                            "periode_first" => $periode_first,
                            "tagihan"       => $tagihan,
                            "terbilang"     => strtoupper($terbilang),
                            "tanggal"       => $tanggal,
                            "unit"          => $unit,
                            "no_referensi"  => $no_referensi,
                            "meter"  => $meter
                            ]);
    }

    public function servicelain()
    {
        $this->load->library('pdf');

        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "kwitansi_servicelain.pdf";
            
    }
    public function deposit($deposit_id=null){
        $this->load->library('pdf');

        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "konfirmasi_tagihan.pdf";
        
    
        $no_referensi = $this->db
                        ->select("no_referensi")
                        ->from("t_deposit_detail")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_deposit_detail.kwitansi_referensi_id")
                        ->where("t_deposit_detail.t_deposit_id",$deposit_id)
                        ->get()->row()->no_referensi;

        $periode = $this->db
                        ->select("convert(date,t_deposit_detail.tgl_document) as periode")
                        ->from("t_deposit_detail")
                        ->where("t_deposit_detail.t_deposit_id",$deposit_id)
                        ->order_by("periode")
                        ->get()->row()->periode;
        $periode = substr($periode,8,2)."-".substr($periode,5,2)."-".substr($periode,0,4);
        $customer = $this->db
                        ->select("
                            customer.name as pemilik,
                            t_deposit_detail.description,    
                            t_deposit_detail.nilai
                        ")
                        ->from("t_deposit")
                        ->join("t_deposit_detail",
                                "t_deposit_detail.t_deposit_id = t_deposit.id")
                        ->join("customer",
                                "customer.id = t_deposit.customer_id")
                        ->where("t_deposit.id",$deposit_id)
                        ->get()->row();

        $terbilang = $this->terbilang(str_replace(",","",$customer->nilai));
        
        $this->pdf->load_view("proyek/cetakan/kwitansi_deposit",[
                            "no_referensi"  => $no_referensi,
                            "periode"       => $periode,
                            "customer"      => $customer,
                            "terbilang"     => strtoupper($terbilang)
                            ]);
    }
    public function lingkunganA($unit_id=null,$pembayaran_id=null){
        $periode = $this->db
                        ->select("periode")
                        ->from("t_pembayaran_detail")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->where("t_pembayaran_detail.t_pembayaran_id",$pembayaran_id)
                        ->order_by("periode")
                        ->get()->result();
        $tagihan = $this->db
                        ->select(" 
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit))AS money), 1), '.00', '') as tagihan,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_denda,0)+isnull(t_pembayaran_detail.nilai_penalti,0))AS money), 1), '.00', '') as denda,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar_deposit,0))AS money), 1), '.00', '') as deposit,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_diskon,0))AS money), 1), '.00', '') as diskon,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)
                                +isnull(t_pembayaran_detail.nilai_denda,0)
                                +isnull(t_pembayaran_detail.nilai_penalti,0)
                                -isnull(t_pembayaran_detail.nilai_diskon,0))AS money), 1), '.00', '') as total")
                        ->from("t_pembayaran_detail")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->where("t_pembayaran_id",$pembayaran_id)
                        ->get()->row();
        $periode_first  = $this->bln_indo(substr($periode[0]->periode,5,2))." ".substr($periode[0]->periode,0,4);
        $periode_last  = $this->bln_indo(substr(end($periode)->periode,5,2))." ".substr(end($periode)->periode,0,4);
        $terbilang = $this->terbilang(str_replace(",","",$tagihan->total));
        
        
        $this->load->view("proyek/cetakan/kwitansi_lingkungan",[
                            "periode_last"  => $periode_last,
                            "periode_first" => $periode_first,
                            "tagihan"       => $tagihan,
                            "terbilang"     => strtoupper($terbilang)
                            ]);
    }
    function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . $this->penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
    }
    function terbilang($nilai) {
		if($nilai<0)    $hasil = "minus ". trim($this->penyebut($nilai));
        else            $hasil = trim($this->penyebut($nilai));
		return $hasil." Rupiah";
	}
    function bln_indo($tmp){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        return $bulan[(int)$tmp];
    }
    
    public function lo($pembayaran_id=null){
        
        $this->load->library('pdf');
        $project = $this->m_core->project();
        
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "kwitansi_pembayaran.pdf";
        
        $no_referensi = $this->db
                        ->select("no_referensi")
                        ->from("t_pembayaran_detail")
                        ->join("kwitansi_referensi",
                                "kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
                        ->where("t_pembayaran_detail.t_pembayaran_id",$pembayaran_id)
                        ->get()->row()->no_referensi;

        $periode = $this->db
                        ->select("periode")
                        ->from("t_pembayaran_detail")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->where("t_pembayaran_detail.t_pembayaran_id",$pembayaran_id)
                        ->order_by("periode")
                        ->get()->result();
        
        $unit = $this->db
                        ->select("
                            unit.no_unit,
                            blok.name as blok,
                            kawasan.name as kawasan,
                            customer.name as pemilik    
                        ")
                        ->from("t_pembayaran")
                        ->join("unit",
                                "unit.id = t_pembayaran.unit_id")
                        ->join("blok",
                                "blok.id = unit.blok_id")
                        ->join("kawasan",
                                "kawasan.id = blok.kawasan_id")
                        ->join("customer",
                                "customer.id = unit.pemilik_customer_id")
                        ->where("t_pembayaran.id",$pembayaran_id)
                        ->get()->row();
                        // SELECT
                        // kategori_loi.nama,
                        //         jenis_loi.nama,
                        //         peruntukan_loi.nama,
                        //         paket_loi.nama
                // FROM
                //         t_loi_registrasi
                //         LEFT JOIN kategori_loi ON t_loi_registrasi.kategori_loi_id = kategori_loi.id
                //         LEFT JOIN jenis_loi ON t_loi_registrasi.jenis_loi_id = jenis_loi.id
                //         LEFT JOIN peruntukan_loi ON t_loi_registrasi.peruntukan_loi_id = peruntukan_loi.id
                //         LEFT JOIN paket_loi ON t_loi_registrasi. = paket_loi.id
                
        $dataRegistrasi = $this->db
                                ->select("
                                kategori_loi.nama as kategori_nama,
                                        jenis_loi.nama as jenis_nama,
                                        paket_loi.nama as paket_nama  
                                ")
                                ->from("t_loi_registrasi")
                                ->join("kategori_loi",
                                        "t_loi_registrasi.kategori_loi_id = kategori_loi.id")
                                ->join("jenis_loi",
                                        "t_loi_registrasi.jenis_loi_id = jenis_loi.id")
                                ->join("peruntukan_loi",
                                        "t_loi_registrasi.peruntukan_loi_id = peruntukan_loi.id")
                                ->join("paket_loi",
                                        "t_loi_registrasi.paket_loi_id = paket_loi.id")
                                ->join("t_pembayaran",
                                        "t_loi_registrasi.unit_id = t_pembayaran.unit_id")
                                ->where("t_pembayaran.id",$pembayaran_id)
                                ->get()->row();

        $tagihan = $this->db
                        ->select(" 
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_tagihan,0))AS money), 1), '.00', '') as tagihan,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_denda,0)+isnull(t_pembayaran_detail.nilai_penalti,0))AS money), 1), '.00', '') as denda,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar_deposit,0))AS money), 1), '.00', '') as deposit,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.nilai_diskon,0))AS money), 1), '.00', '') as diskon,
                                REPLACE(CONVERT(varchar, CAST(sum(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit))AS money), 1), '.00', '') as total")
                        ->from("t_pembayaran_detail")
                        ->join("t_tagihan_lingkungan",
                                "t_tagihan_lingkungan.id = t_pembayaran_detail.tagihan_service_id")
                        ->where("t_pembayaran_id",$pembayaran_id)
                        ->get()->row();
        $periode_first  = $this->bln_indo(substr($periode[0]->periode,5,2))." ".substr($periode[0]->periode,0,4);
        $periode_last  = $this->bln_indo(substr(end($periode)->periode,5,2))." ".substr(end($periode)->periode,0,4);
        $terbilang = $this->terbilang(str_replace(",","",$tagihan->total));
        // bentuk_kwitansi_nr
        $ttd = $this->m_parameter_project->get($project->id,"bentuk_kwitansi_nr");
        
        // $this->pdf->load_view("proyek/cetakan/kwitansi_lingkungan");
        $tanggal = (date("d")." ".$this->bln_indo(date("m"))." ".date("Y"));
        $this->pdf->load_view("proyek/cetakan/lembarKwitansi",[
                        "periode_last"  => $periode_last,
                        "project"  => $project,
                        "periode_first" => $periode_first,
                        "tagihan"       => $tagihan,
                        "terbilang"     => strtoupper($terbilang),
                        "tanggal"       => $tanggal,
                        "unit"          => $unit,
                        "ttd"          => $ttd,
                        "no_referensi"  => $no_referensi,
                        "dataRegistrasi"  => $dataRegistrasi
                        ]);
    }
	
}