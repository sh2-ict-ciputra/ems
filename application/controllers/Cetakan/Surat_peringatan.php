<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class surat_peringatan extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('Setting/m_parameter_project');
        $this->load->model('Cetakan/m_konfirmasi_tagihan');
        $this->load->model('m_core');
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();
        ini_set('memory_limit','256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');

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
    public function test(){
        return 1;
    }

    public function unit($unit_id=null,$service_jenis_id=null){
        $project = $this->m_core->project();
        
        $this->load->library('pdf');
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "konfirmasi_tagihan.pdf";
        
        $unit = $this->db   ->select("
                                kawasan.name as kawasan,
                                blok.name as blok,
                                unit.no_unit,
                                customer.name as pemilik,
                                customer.address
                            ")
                            ->from("unit")
                            ->join("blok",
                                    "blok.id = unit.blok_id")
                            ->join("kawasan",
                                    "kawasan.id = blok.kawasan_id")
                            ->join("customer",
                                    "customer.id = unit.pemilik_customer_id")
                            ->where("unit.id",$unit_id)
                            ->get()->row();
        
        $periode_now = date("Y-m-01");
        if($service_jenis_id == 1){
            $service = "Lingkungan";
            $tagihan_service = $this->db->select("
								SUM(isnull(v_tagihan_lingkungan.total,0)) as tagihan,
								sum(isnull(CASE
									WHEN service.denda_flag = 0 THEN 0
									WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
									WHEN v_tagihan_lingkungan.periode > '$periode_now' THEN 0
									ELSE
										CASE
										WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN v_tagihan_lingkungan.denda_nilai_service 
										WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN v_tagihan_lingkungan.denda_nilai_service * (DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
										WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN ( v_tagihan_lingkungan.denda_nilai_service* v_tagihan_lingkungan.total/ 100 ) * (DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
									END 
								END,0)) AS denda")	
			->from("v_tagihan_lingkungan")
			// ->where("v_tagihan_lingkungan.periode <= '$periode_now'")
			->join(
				"service",
				"service.project_id = $project->id
				AND service.service_jenis_id = 1
				AND service.active = 1
				AND service.delete = 0"
			)
			->join("v_pemutihan",
					"v_pemutihan.masa_akhir >= GETDATE()
					AND v_pemutihan.masa_awal <= GETDATE()
					AND v_pemutihan.periode_akhir >= v_tagihan_lingkungan.periode 
					AND v_pemutihan.periode_awal <= v_tagihan_lingkungan.periode 
					AND v_pemutihan.service_jenis_id = 1
					AND v_pemutihan.unit_id  = v_tagihan_lingkungan.unit_id",
					"LEFT")
			->where("v_tagihan_lingkungan.status_tagihan = 0")
			->where("v_tagihan_lingkungan.unit_id = $unit_id")
            ->get()->row();
            $denda_service = $tagihan_service?$tagihan_service->denda:"-";
            $tagihan_service = $tagihan_service?$tagihan_service->tagihan:"-";

            $tagihan_periode = $this->db->select("min(periode) as periode")
                                        ->from("v_tagihan_lingkungan")
                                        ->where("unit_id",$unit_id)
                                        ->get()->row();
            $tagihan_periode = $tagihan_periode?$tagihan_periode->periode:"-";

        }
        if($service_jenis_id == 2){
            $service = "Air";

            $tagihan_service = $this->db->select("
                                sum(isnull(v_tagihan_air.total,0)) as tagihan,
                                sum(
                                    isnull(CASE
                                    WHEN service.denda_flag = 0 THEN 0
                                    WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
                                    WHEN v_tagihan_air.periode > '$periode_now' THEN 0
                                    ELSE
                                    CASE
                                        WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service 
                                        WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service *(DateDiff( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
                                        WHEN v_tagihan_air.denda_jenis_service = 3 THEN (v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) *(DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
                                    END 
                                END,0)) AS denda")
                        ->from("v_tagihan_air")
                        // ->where("v_tagihan_air.periode <= '$periode_now'")
                        ->join(
                            "service",
                            "service.project_id = $project->id
                            AND service.service_jenis_id = 2
                            AND service.active = 1
                            AND service.delete = 0"
                        )
                        ->join("v_pemutihan",
                        "v_pemutihan.masa_akhir >= GETDATE()
                        AND v_pemutihan.masa_awal <= GETDATE()
                        AND v_pemutihan.periode_akhir >= v_tagihan_air.periode 
                        AND v_pemutihan.periode_awal <= v_tagihan_air.periode 
                        AND v_pemutihan.service_jenis_id = 1
                        AND v_pemutihan.unit_id  = v_tagihan_air.unit_id",
                        "LEFT")
                        ->where("v_tagihan_air.status_tagihan = 0")
                        ->where("v_tagihan_air.unit_id = $unit_id")
                        ->get()->row()->total;
            $denda_service = $tagihan_service?$tagihan_service->denda:"-";
            $tagihan_service = $tagihan_service?$tagihan_service->tagihan:"-";

            $tagihan_periode = $this->db->select("min(periode) as periode")
                                        ->from("v_tagihan_air")
                                        ->where("unit_id",$unit_id)
                                        ->get()->row();
            $tagihan_periode = $tagihan_periode?$tagihan_periode->periode:"-";
            
        }

        
        $this->pdf->load_view("proyek/cetakan/surat_peringatan",[
                                        "unit"              => $unit,
                                        "tagihan_periode"   => $tagihan_periode,
                                        "tagihan_service"   => $tagihan_service,
                                        "denda_service"     => $denda_service
                                        ]);
    }
    public function send($unit_id=null){
        $this->load->helper('file');

        $unit_id = str_replace(".pdf","",$unit_id);
        $this->load->library('pdf');
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "konfirmasi_tagihan.pdf";
        
        $unit= $this->m_konfirmasi_tagihan->get_unit($unit_id);
        $tagihan= $this->m_konfirmasi_tagihan->get_tagihan($unit_id);
        $total_air          = 0;
        $total_lingkungan   = 0;
        $total_lain         = 0;
        $total_pakai        = 0;
        $total_ppn          = 0;
        $total_denda        = 0;
        $total              = 0;
        $periode_last  = $this->bln_indo(substr($tagihan[0]->periode,5,2))." ".substr($tagihan[0]->periode,0,4);
        $periode_first = $this->bln_indo(date('m'))." ".date("Y");
        


        foreach ($tagihan as $k=>$v) {

            $tagihan[$k]->periode = substr($this->bln_indo(substr($tagihan[$k]->periode,5,2)),0,3)." ".substr($tagihan[$k]->periode,0,4);
            $total_air          += str_replace(',','',$v->tagihan_air);
            $total_lingkungan   += str_replace(',','',$v->tagihan_lingkungan);
            $total_lain         += str_replace(',','',$v->tagihan_lain);
            $total_pakai        += str_replace(',','',$v->meter_pakai?$v->meter_pakai:0);
            $total_ppn          += str_replace(',','',$v->ppn_lingkungan);
            $total_denda        += str_replace(',','',$v->total_denda);
            $total              += str_replace(',','',$v->total);
            
        }
        
        // $this->load->view('konfirmasi_tagihan', $data);

        $nama_file = $unit_id.date("_Y-m-d_H-i-s").".pdf";

        $a = $this->pdf->send("proyek/cetakan/konfirmasi_tagihan",[
                                        "unit"              => $unit,
                                        "tagihan"           => $tagihan,
                                        "total_air"         => number_format($total_air),
                                        "total_lingkungan"  => number_format($total_lingkungan),
                                        "total_lain"        => number_format($total_lain),
                                        "total_pakai"       => number_format($total_pakai),
                                        "total_ppn"         => number_format($total_ppn),
                                        "total_denda"       => number_format($total_denda),
                                        "total"             => number_format($total),
                                        "periode_first"     => $periode_first,
                                        "periode_last"      => $periode_last,
                                        "line"              => $k
                                        ]);
        if(write_file("pdf/$nama_file", $a)){
            echo json_encode($nama_file);
        }else{
            echo("Gagal ".$nama_file);
            echo json_encode(false);
        }
    }
}
