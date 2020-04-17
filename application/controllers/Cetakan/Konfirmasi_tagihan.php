<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class konfirmasi_tagihan extends CI_Controller {
    
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
    public function index(){
        $this->load->library('pdf');
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "laporan-petanikode.pdf";
        
        $this->pdf->load_view('konfirmasi_tagihan', $data);
    
    
    }
    public function unit($unit_id=null){
        $project = $this->m_core->project();
        
        $this->load->library('pdf');
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "konfirmasi_tagihan.pdf";
        
        $unit= $this->m_konfirmasi_tagihan->get_unit($unit_id);

        
        // $tagihan= $this->m_konfirmasi_tagihan->get_tagihan($unit_id);

        // $periode_last           = $this->bln_indo(substr($tagihan[0]->periode,5,2))." ".substr($tagihan[0]->periode,0,4);
        // $periode_first          = $this->bln_indo(date('m'))." ".date("Y");
        $status_saldo_deposit   = $this->m_konfirmasi_tagihan->get_status_saldo_deposit($unit_id);
        $saldo_deposit          = $this->m_konfirmasi_tagihan->get_saldo_deposit($unit_id);
        $ttd                    = $this->m_parameter_project->get($project->id,"ttd_konfirmasi_tagihan");

        // foreach ($tagihan as $k=>$v) {

        //     $tagihan[$k]->periode = substr($this->bln_indo(substr($tagihan[$k]->periode,5,2)),0,3)." ".substr($tagihan[$k]->periode,0,4);
        //     $total_air          += str_replace(',','',$v->tagihan_air);
        //     $total_lingkungan   += str_replace(',','',$v->tagihan_lingkungan);
        //     $total_lain         += str_replace(',','',$v->tagihan_lain);
        //     $total_pakai        += str_replace(',','',$v->meter_pakai?$v->meter_pakai:0);
        //     $total_ppn          += str_replace(',','',$v->ppn_lingkungan);
        //     $total_denda        += str_replace(',','',$v->total_denda);
        //     $total              += str_replace(',','',$v->total);
            
        // }
        
		$catatan = $unit->catatan;
		
        $catatan = str_replace("{{va_unit}}",$unit->virtual_account,$catatan);
		// $uid = $this->db->select("uid")
		// 				->from("v_sales_force_bill")
		// 				->where("unit_id",$unit_id)
		// 				->get()->row();
		$uid =	$this->db->select("concat(project.source_id,kawasan.code,blok.code,'/',unit.no_unit) as uid")
						->from("unit")
					->join("project",
							"project.id = unit.project_id")
					->join("blok",
							"blok.id = unit.blok_id")
					->join("kawasan",
							"kawasan.id = blok.kawasan_id")
					->where("unit.id",$unit_id)
					->get()->row();
		$uid = $uid?$uid->uid:0;	
		$catatan = str_replace("{{no_iplk}}",$uid,$catatan);

        //Data Tagihan Without Sorting
        $dataTagihanWoS = $this->ajax_get_tagihan($unit_id);

        //After sort
        $dataTagihanWS = [];
        
        $min_tagihan_air = isset($dataTagihanWoS->tagihan_air[0])?$dataTagihanWoS->tagihan_air[0]->periode:null;
        $max_tagihan_air = isset($dataTagihanWoS->tagihan_air[0])?end($dataTagihanWoS->tagihan_air)->periode:null;
        $min_tagihan_lingkungan = isset($dataTagihanWoS->tagihan_lingkungan[0])?$dataTagihanWoS->tagihan_lingkungan[0]->periode:null;
        $max_tagihan_lingkungan = isset($dataTagihanWoS->tagihan_lingkungan[0])?end($dataTagihanWoS->tagihan_lingkungan)->periode:null;
        
        // $min_tagihan_air = $dataTagihanWoS->tagihan_air[0]->periode;
        // var_dump($min_tagihan_air);
        // var_dump($max_tagihan_air);
        // var_dump($min_tagihan_lingkungan);
        // var_dump($max_tagihan_lingkungan);
        if($min_tagihan_air == null){
            $min_tagihan_air = $min_tagihan_lingkungan;
        }if($min_tagihan_lingkungan == null){
            $min_tagihan_lingkungan = $min_tagihan_air;
        }if($max_tagihan_air == null){
            $max_tagihan_air = $max_tagihan_lingkungan;
        }if($max_tagihan_lingkungan == null){
            $max_tagihan_lingkungan = $max_tagihan_air;
        }
        $min_tagihan = new DateTime($min_tagihan_air > $min_tagihan_lingkungan?$min_tagihan_lingkungan:$min_tagihan_air);
        $max_tagihan = new DateTime($max_tagihan_air > $max_tagihan_lingkungan?$max_tagihan_air:$max_tagihan_lingkungan);
        // var_dump($min_tagihan);
        // var_dump($max_tagihan);
        $iterasi = 0;
        $total_tagihan = (object)[];
        $total_tagihan->pakai   = null;
        $total_tagihan->air     = null;
        $total_tagihan->ipl     = null;
        $total_tagihan->ppn     = null;
        $total_tagihan->denda   = null;
        $total_tagihan->tunggakan   = null;
        $total_tagihan->total   = null;
        $total_tagihan->lain   = null;
        $periode_first = $this->bln_indo(substr($min_tagihan->format("Y-m-01"),5,2))." ".substr($min_tagihan->format("Y-m-01"),0,4);
        $periode_last = $this->bln_indo(substr($max_tagihan->format("Y-m-01"),5,2))." ".substr($max_tagihan->format("Y-m-01"),0,4);
        
		// echo("<pre>");
		// 	print_r($dataTagihanWS);
		// echo("</pre>");
		$service_air = $this->db->select("jarak_periode_penggunaan")
								->from("service")
								->where("project_id",$project->id)
								->where("service_jenis_id",2)
								->get()->row();
		$service_air = $service_air?$service_air->jarak_periode_penggunaan:0;
		$service_lingkungan = $this->db->select("jarak_periode_penggunaan")
								->from("service")
								->where("project_id",$project->id)
								->where("service_jenis_id",1)
								->get()->row();
		$service_lingkungan = $service_lingkungan?$service_lingkungan->jarak_periode_penggunaan:0;
		if($service_air == $service_lingkungan)
			$jarak_periode_penggunaan = $service_air;
		else
			$jarak_periode_penggunaan = -1;
        for($i = $min_tagihan; $i <= $max_tagihan; $i->modify('+1 month')){
			
			$periode = $i->format("Y-m-01");
			$periode_1 = $periode;
			if($jarak_periode_penggunaan != -1){

				$tmp = $periode;
				
				$tmp = strtotime(date("Y-m-d", strtotime($tmp)) . " -$jarak_periode_penggunaan month");
				$tmp = date("Y-m-d",$tmp);

				// $tmp = substr($tmp,5,2).'-'.substr($tmp,8,2).'-'.substr($tmp,0,4);
		
				$periode_1 = $tmp;

			}
			$dataTagihanWS[$iterasi] =(object)[];
			
            $dataTagihanWS[$iterasi]->periode = substr($this->bln_indo(substr($periode,5,2)),0,3)."<br>".substr($periode,0,4);
            $dataTagihanWS[$iterasi]->periode_penggunaan = substr($this->bln_indo(substr($periode_1,5,2)),0,3)."<br>".substr($periode_1,0,4);
            $dataTagihanWS[$iterasi]->meter_awal    = null;
            $dataTagihanWS[$iterasi]->meter_akhir   = null;
            $dataTagihanWS[$iterasi]->pakai         = null;
            $dataTagihanWS[$iterasi]->air           = 0;
            $dataTagihanWS[$iterasi]->ipl           = null;
            $dataTagihanWS[$iterasi]->ppn           = null;
            $dataTagihanWS[$iterasi]->denda         = 0;
            $dataTagihanWS[$iterasi]->tunggakan     = 0;
            $dataTagihanWS[$iterasi]->total         = null;
            
            foreach ($dataTagihanWoS->tagihan_air as $k => $v) {
                if($v->periode == $periode){
                    $tmp_tagihan_air = $v;
                    $dataTagihanWS[$iterasi]->meter_awal    = $v->meter_awal;
                    $dataTagihanWS[$iterasi]->meter_akhir   = $v->meter_akhir;
                    $dataTagihanWS[$iterasi]->pakai         = $v->meter_akhir-$v->meter_awal;
                    if($v->belum_bayar > 0){
                        $dataTagihanWS[$iterasi]->tunggakan     = $v->belum_bayar;
                        $dataTagihanWS[$iterasi]->total         += $v->belum_bayar;
                            
                    }else{
                        $dataTagihanWS[$iterasi]->air           = $v->nilai_tagihan;
                        $dataTagihanWS[$iterasi]->denda         += $v->nilai_denda;
                        $dataTagihanWS[$iterasi]->total         += $v->total;
                    }
                    break;
                }
            }
            // var_dump($tmp_tagihan_air);
            foreach ($dataTagihanWoS->tagihan_lingkungan as $k => $v) {
                if($v->periode == $periode){
                    if($v->belum_bayar > 0){
                        $dataTagihanWS[$iterasi]->tunggakan     += $v->belum_bayar;
                        $dataTagihanWS[$iterasi]->total         += $v->belum_bayar;
                            
                    }else{
                        $dataTagihanWS[$iterasi]->ipl           = $v->total_tanpa_ppn;
                        $dataTagihanWS[$iterasi]->ppn           = $v->nilai_tagihan-$v->total_tanpa_ppn;
                        $dataTagihanWS[$iterasi]->denda         += $v->nilai_denda;
                        $dataTagihanWS[$iterasi]->total         += $v->total;
                    }
                    // $dataTagihanWS[$iterasi]->ipl           = $v->total_tanpa_ppn;
                    // $dataTagihanWS[$iterasi]->ppn           = $v->nilai_tagihan-$v->total_tanpa_ppn;
                    // $dataTagihanWS[$iterasi]->denda         = $v->nilai_denda;
                    // $dataTagihanWS[$iterasi]->total         += $v->total;
                    break;                
                }
            }
            $total_tagihan->pakai   += $dataTagihanWS[$iterasi]->pakai;
            $total_tagihan->air     += $dataTagihanWS[$iterasi]->air;
            $total_tagihan->ipl     += $dataTagihanWS[$iterasi]->ipl;
            $total_tagihan->ppn     += $dataTagihanWS[$iterasi]->ppn;
            $total_tagihan->denda   += $dataTagihanWS[$iterasi]->denda;
            $total_tagihan->total   += $dataTagihanWS[$iterasi]->total;
            $total_tagihan->tunggakan += $dataTagihanWS[$iterasi]->tunggakan;
            $iterasi++;
        }        
        // var_dump($total_tagihan);
        // var_dump($dataTagihanWS);

        // echo(json_encode($dataTagihanWS));


        // echo "1<pre>$data</pre>";
        
        // echo("<pre>");
        //     print_r($dataTagihanWS);
        // echo("</pre>");
        // var_dump($min_tagihan);
        // var_dump($max_tagihan);
		
		if($jarak_periode_penggunaan != -1)
			$this->pdf->load_view("proyek/cetakan/konfirmasi_tagihan2",[
											"unit"              => $unit,
											"catatan"           => $catatan,
											"tagihan"           => $dataTagihanWS,
											"total_tagihan"     => $total_tagihan,
											// "tagihan"           => $tagihan,
											// "total_air"         => number_format($total_air),
											// "total_lingkungan"  => number_format($total_lingkungan),
											// "total_lain"        => number_format($total_lain),
											// "total_pakai"       => number_format($total_pakai),
											// "total_ppn"         => number_format($total_ppn),
											// "total_denda"       => number_format($total_denda),
											// "total"             => number_format($total),
											"periode_first"     => $periode_first,
											"periode_last"      => $periode_last,
											"saldo_deposit"     => $saldo_deposit,
											"status_saldo_deposit"     => $status_saldo_deposit,
											"ttd"               => $ttd,
											// "data"              => $data                                     
											]);
		else
			$this->pdf->load_view("proyek/cetakan/konfirmasi_tagihan",[
											"unit"              => $unit,
											"catatan"           => $catatan,
											"tagihan"           => $dataTagihanWS,
											"total_tagihan"     => $total_tagihan,
											// "tagihan"           => $tagihan,
											// "total_air"         => number_format($total_air),
											// "total_lingkungan"  => number_format($total_lingkungan),
											// "total_lain"        => number_format($total_lain),
											// "total_pakai"       => number_format($total_pakai),
											// "total_ppn"         => number_format($total_ppn),
											// "total_denda"       => number_format($total_denda),
											// "total"             => number_format($total),
											"periode_first"     => $periode_first,
											"periode_last"      => $periode_last,
											"saldo_deposit"     => $saldo_deposit,
											"status_saldo_deposit"     => $status_saldo_deposit,
											"ttd"               => $ttd,
											// "data"              => $data                                     
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
    public function get_ajax_unit_detail($unit_id)
	{
		$project = $this->m_core->project();

		$periode_now = date("Y-m-01");
		$periode_pemakaian = date("Y-m-01", strtotime("-1 Months"));
		// $unit_id = $this->input->get('unit_id');
		$unit = $this->db->select("
									pemilik.id as pemilik_id,
									penghuni.id as penghuni_id
									")
			->from('unit')
			->join('customer as pemilik', 'pemilik.id = unit.pemilik_customer_id')
			->join('customer as penghuni', 'penghuni.id = unit.penghuni_customer_id','LEFT')
			->join('jenis_golongan', 'jenis_golongan.id = unit.gol_id','LEFT')

			->where('unit.id', $unit_id)
			->get()->row();

		// WHEN v_tagihan_air.periode > '$periode_now' THEN 0 -> kalau periode nya lebih dari periode saat ini, denda = 0
		$pemilik = $this->db->select("	isnull(name,' ') as name,
										isnull(email,' ') as email,
										isnull(mobilephone1,' ') as mobilephone1,
										isnull(mobilephone2,' ') as mobilephone2,
										isnull(address,' ') as address")
							->from("customer")
							->where("id",$unit->pemilik_id)
							->get()->row();
		$penghuni = $this->db->select("	isnull(name,' ') as name,
										isnull(email,' ') as email,
										isnull(mobilephone1,' ') as mobilephone1,
										isnull(mobilephone2,' ') as mobilephone2,
										isnull(address,' ') as address")
							->from("customer")
							->where("id",$unit->penghuni_id)
							->get()->row();
		// $tagihan_air = $this->db->select("
		// 						'Air' as service,
		// 						v_tagihan_air.periode,
		// 						0 as nilai_penalti,

		// 						(v_tagihan_air.total - v_tagihan_air.total_tanpa_ppn) as ppn,
		// 						DATEADD(
		// 							MONTH, 
		// 							(
		// 								(-1)*(CONVERT(INT,service.jarak_periode_penggunaan))
		// 							),
		// 							v_tagihan_air.periode
		// 						) as periode_pemakaian,
		// 						v_tagihan_air.total_tanpa_ppn,
		// 						isnull(CASE 
		// 						WHEN DATEADD(MONTH, 1,v_tagihan_air.periode) >= '$periode_now' THEN v_tagihan_air.total
		// 						ELSE 0
		// 						END,0) as nilai_tagihan,
		// 						isnull(CASE 
		// 							WHEN DATEADD(MONTH, 1,v_tagihan_air.periode) < '$periode_now' THEN v_tagihan_air.total
		// 							ELSE 0
		// 						END,0) as nilai_tunggakan,
		// 						isnull(CASE
		// 							WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
		// 							WHEN v_tagihan_air.periode > '$periode_now' THEN 0
		// 							ELSE
		// 								CASE
		// 									WHEN v_tagihan_air.denda_jenis_service = 1 
		// 										THEN v_tagihan_air.denda_nilai_service 
		// 									WHEN v_tagihan_air.denda_jenis_service = 2 
		// 										THEN 
		// 											v_tagihan_air.denda_nilai_service * 
		// 											( DateDiff
		// 												( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$periode_now' ) 
		// 												+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) 
		// 											)
		// 									WHEN v_tagihan_air.denda_jenis_service = 3 
		// 										THEN 
		// 											( v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) 
		// 											* (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
		// 											+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
		// 								END 
		// 							END,0) AS nilai_denda,
		// 						isnull(CASE 
		// 							WHEN v_tagihan_air.periode = '$periode_now' THEN v_tagihan_air.total
		// 							ELSE 0
		// 						END,0) +
		// 						isnull(CASE 
		// 							WHEN v_tagihan_air.periode < '$periode_now' THEN v_tagihan_air.total
		// 							ELSE 0
		// 						END,0) +
		// 						isnull(CASE
		// 							WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
		// 							WHEN v_tagihan_air.periode > '$periode_now' THEN 0
		// 							ELSE
		// 							CASE
		// 								WHEN v_tagihan_air.denda_jenis_service = 1 
		// 								THEN 
		// 									v_tagihan_air.denda_nilai_service 
		// 								WHEN v_tagihan_air.denda_jenis_service = 2 
		// 								THEN 
		// 									v_tagihan_air.denda_nilai_service
		// 										* (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
		// 										+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
		// 								WHEN v_tagihan_air.denda_jenis_service = 3 
		// 								THEN 
		// 									( v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) 
		// 									* (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
		// 									+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
		// 							END 
		// 						END,0) AS total,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_tagihan_type),-1) as pemutihan_nilai_tagihan_type,
		// 						case 
		// 							WHEN isnull(v_pemutihan.nilai_tagihan,-1)>0 THEN isnull(v_pemutihan.nilai_tagihan,-1)
		// 							else 0 
		// 						END as pemutihan_nilai_tagihan,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_denda_type),-1) as pemutihan_nilai_denda_type,
		// 						case 
		// 							WHEN isnull(v_pemutihan.nilai_denda,-1)>0 THEN isnull(v_pemutihan.nilai_denda,-1)
		// 							ELSE 0 
		// 						END  as pemutihan_nilai_denda")
		// 	->from("v_tagihan_air")
		// 	->join(
		// 		"service",
		// 		"service.service_jenis_id = 2
		// 							AND service.project_id = $project->id"
		// 	)
		// 	->join(
		// 		"v_pemutihan",
		// 		"v_pemutihan.masa_akhir >= GETDATE()
		// 							AND v_pemutihan.masa_awal <= GETDATE()
		// 							AND v_pemutihan.periode_akhir >= v_tagihan_air.periode 
		// 							AND v_pemutihan.periode_awal <= v_tagihan_air.periode 
		// 							AND v_pemutihan.service_jenis_id = 1
		// 							AND v_pemutihan.unit_id  = v_tagihan_air.unit_id
		// 							AND v_pemutihan.project_id = service.project_id",
		// 		"LEFT"
		// 	)
		// 	// ->where("v_tagihan_air.periode <= '$periode_now'")
		// 	->where("v_tagihan_air.status_tagihan = 0")
		// 	->where("v_tagihan_air.unit_id = $unit_id")
		// 	->order_by("periode")

		// 	->get()->result();
		// // echo($periode_now);
		// $tagihan_air_tmp = $tagihan_air;
		// $tagihan_air = [];
		// $view_pemutihan_nilai_tagihan = 0;
		// $view_pemutihan_nilai_denda = 0;
		// $sisa_nilai_tagihan = 0;
		// $sisa_nilai_denda = 0;
		// foreach ($tagihan_air_tmp as $k => $v) {
		// 	if ($v->periode != $tagihan_air_tmp[(count($tagihan_air_tmp) - 1) < ($k + 1) ? (count($tagihan_air_tmp) - 1) : ($k + 1)]->periode || $k == count($tagihan_air_tmp) - 1) {
		// 		// if($v->pemutihan_nilai_tagihan_type = 0){
		// 		// 	$v->view_pemutihan_nilai_tagihan += $v->pemutihan_nilai_tagihan;
		// 		// 	$v->sisa_nilai_tagihan = $v->pemutihan_nilai_tagihan - $v->view_pemutihan_nilai_tagihan;
		// 		// }else{

		// 		// }
		// 		// if($v->pemutihan_nilai_denda_type = 0){

		// 		// }		
		// 		if ($v->pemutihan_nilai_tagihan_type == 0) {
		// 			$view_pemutihan_nilai_tagihan += ((int) $v->pemutihan_nilai_tagihan);
		// 			$sisa_nilai_tagihan += ((int) $v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
		// 		}elseif ($v->pemutihan_nilai_tagihan_type == 1) {
		// 			$view_pemutihan_nilai_tagihan += (((int) $v->pemutihan_nilai_tagihan) * ((int) $sisa_nilai_tagihan) / 100);
		// 			$sisa_nilai_tagihan += ((int) $v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
		// 		}else{
		// 			$view_pemutihan_nilai_tagihan += 0;;
		// 			$sisa_nilai_tagihan +=0;
		// 		}
		// 		if ($v->pemutihan_nilai_denda_type == 0) {
		// 			$view_pemutihan_nilai_denda += ((int) $v->pemutihan_nilai_denda);
		// 			$sisa_nilai_denda += ((int) $v->nilai_denda) - $view_pemutihan_nilai_denda;
		// 		}elseif ($v->pemutihan_nilai_denda_type == 1) {
		// 			$view_pemutihan_nilai_denda += (((int) $v->pemutihan_nilai_denda) * ((int) $sisa_nilai_denda) / 100);
		// 			$sisa_nilai_denda += ((int) $v->nilai_denda) - $view_pemutihan_nilai_denda;
		// 		}else{
		// 			$view_pemutihan_nilai_denda += 0;
		// 			$sisa_nilai_denda += 0;
		// 		}
		// 		$sisa_nilai_tagihan = $sisa_nilai_tagihan > 0 ? $sisa_nilai_tagihan : 0;
		// 		$sisa_nilai_denda = $sisa_nilai_denda > 0 ? $sisa_nilai_denda : 0;

		// 		$v->view_pemutihan_nilai_tagihan	= $view_pemutihan_nilai_tagihan > $v->total_tanpa_ppn ? $v->total_tanpa_ppn : $view_pemutihan_nilai_tagihan;
		// 		$v->view_pemutihan_nilai_denda  	= $view_pemutihan_nilai_denda > $v->nilai_denda ? $v->nilai_denda : $view_pemutihan_nilai_denda;
		// 		$v->sisa_nilai_tagihan 				= $sisa_nilai_tagihan;
		// 		$v->sisa_nilai_denda 				= $sisa_nilai_denda;

		// 		array_push($tagihan_air, $v);
		// 	} else {
		// 		$view_pemutihan_nilai_tagihan = 0;
		// 		$view_pemutihan_nilai_denda = 0;
		// 		$sisa_nilai_tagihan = 0;
		// 		$sisa_nilai_denda = 0;
		// 		if ($v->pemutihan_nilai_tagihan_type == 0) {
		// 			$view_pemutihan_nilai_tagihan = ((int) $v->pemutihan_nilai_tagihan);
		// 			$sisa_nilai_tagihan = ((int) $v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
		// 		} else {
		// 			$view_pemutihan_nilai_tagihan = (((int) $v->pemutihan_nilai_tagihan) * ((int) $v->total_tanpa_ppn) / 100);
		// 			$sisa_nilai_tagihan = ((int) $v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
		// 		}
		// 		if ($v->pemutihan_nilai_denda_type == 0) {
		// 			$view_pemutihan_nilai_denda = ((int) $v->pemutihan_nilai_denda);
		// 			$sisa_nilai_denda = ((int) $v->nilai_denda) - $view_pemutihan_nilai_denda;
		// 		} else {
		// 			$view_pemutihan_nilai_denda = (((int) $v->pemutihan_nilai_denda) * ((int) $v->nilai_denda) / 100);
		// 			$sisa_nilai_denda = ((int) $v->nilai_denda) - $view_pemutihan_nilai_denda;
		// 		}
		// 		$sisa_nilai_tagihan = $sisa_nilai_tagihan > 0 ? $sisa_nilai_tagihan : 0;
		// 		$sisa_nilai_denda = $sisa_nilai_denda > 0 ? $sisa_nilai_denda : 0;

		// 		// echo($view_pemutihan_nilai_tagihan."<br>");
		// 	}
		// }
		// $tagihan_lingkungan = $this->db->select("
		// 						'lingkungan' as service,
		// 						v_tagihan_lingkungan.total_tanpa_ppn,
		// 						v_tagihan_lingkungan.ppn,
		// 						v_tagihan_lingkungan.periode,
		// 						DATEADD(
		// 							MONTH, 
		// 							(
		// 								(-1)*(CONVERT(INT,service.jarak_periode_penggunaan))
		// 							),
		// 							v_tagihan_lingkungan.periode
		// 						) as periode_pemakaian,
		// 						v_tagihan_lingkungan.tagihan_id,
		// 						1 as service_jenis_id,
		// 						0 as nilai_penalti,
		// 						isnull(CASE 
		// 							WHEN v_tagihan_lingkungan.periode >= '$periode_now' THEN v_tagihan_lingkungan.total
		// 							ELSE 0
		// 						END,0) as nilai_tagihan,
		// 						isnull(CASE 
		// 							WHEN v_tagihan_lingkungan.periode < '$periode_now' THEN v_tagihan_lingkungan.total
		// 							ELSE 0
		// 						END,0) as nilai_tunggakan,
		// 						isnull(CASE
		// 							WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN
		// 								CASE
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
		// 										THEN
		// 											v_tagihan_lingkungan.denda_nilai_service 
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
		// 										THEN
		// 											v_tagihan_lingkungan.denda_nilai_service * 
		// 											( 
		// 												DateDiff( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
		// 												+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
		// 											)
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
		// 										THEN
		// 											( v_tagihan_lingkungan.denda_nilai_service* v_tagihan_lingkungan.total/ 100 ) 
		// 											* (DateDiff( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
		// 											+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
		// 								END 	
		// 							WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
		// 							WHEN v_tagihan_lingkungan.periode > '$periode_now' THEN 0
		// 							ELSE
		// 								CASE
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
		// 									THEN 
		// 										v_tagihan_lingkungan.denda_nilai_service 
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
		// 									THEN 
		// 										v_tagihan_lingkungan.denda_nilai_service * 
		// 										( 
		// 											DateDiff( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
		// 											+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
		// 										)
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
		// 									THEN 
		// 										( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
		// 										* (DateDiff( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
		// 										+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
		// 								END 
		// 							END,0) AS nilai_denda,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_tagihan_type),-1) as pemutihan_nilai_tagihan_type,
		// 						isnull(v_pemutihan.nilai_tagihan,-1) as pemutihan_nilai_tagihan,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_denda_type),-1) as pemutihan_nilai_denda_type,
		// 						isnull(v_pemutihan.nilai_denda,-1) as pemutihan_nilai_denda
		// 					")
		// 	->from("v_tagihan_lingkungan")
		// 	->join(
		// 		"service",
		// 		"service.service_jenis_id = 1
		// 							AND service.project_id = $project->id"
		// 	)
		// 	->join(
		// 		"unit_lingkungan",
		// 		"unit_lingkungan.unit_id = $unit_id"
		// 	)
		// 	->join(
		// 		"v_pemutihan",
		// 		"v_pemutihan.masa_akhir >= GETDATE()
		// 							AND v_pemutihan.masa_awal <= GETDATE()
		// 							AND v_pemutihan.periode_akhir >= v_tagihan_lingkungan.periode 
		// 							AND v_pemutihan.periode_awal <= v_tagihan_lingkungan.periode 
		// 							AND v_pemutihan.service_jenis_id = 1
		// 							AND v_pemutihan.unit_id  = v_tagihan_lingkungan.unit_id",
		// 		"LEFT"
		// 	)
		// 	// ->where("v_tagihan_lingkungan.periode <= '$periode_now'")
		// 	->where("v_tagihan_lingkungan.status_tagihan = 0")
		// 	->where("v_tagihan_lingkungan.unit_id = $unit_id")
		// 	->order_by("periode")
		// 	->get()->result();
		// $tagihan_lingkungan_tmp = $tagihan_lingkungan;
		// $tagihan_lingkungan = [];
		// $view_pemutihan_nilai_tagihan = 0;
		// $view_pemutihan_nilai_denda = 0;
		// $sisa_nilai_tagihan = 0;
		// $sisa_nilai_denda = 0;
		// foreach ($tagihan_lingkungan_tmp as $k => $v) {
		// 	if ($v->periode != $tagihan_lingkungan_tmp[(count($tagihan_lingkungan_tmp) - 1) < ($k + 1) ? (count($tagihan_lingkungan_tmp) - 1) : ($k + 1)]->periode || $k == count($tagihan_lingkungan_tmp) - 1) {
		// 		if ($v->pemutihan_nilai_tagihan_type == 0) {
		// 			$view_pemutihan_nilai_tagihan += ((int) $v->pemutihan_nilai_tagihan);
		// 			$sisa_nilai_tagihan += ((int) $v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
		// 		} else if ($v->pemutihan_nilai_tagihan_type == 1) {
		// 			$view_pemutihan_nilai_tagihan += (((int) $v->pemutihan_nilai_tagihan) * ((int) $sisa_nilai_tagihan) / 100);
		// 			$sisa_nilai_tagihan += ((int) $v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
		// 		}
		// 		if ($v->pemutihan_nilai_denda_type == 0) {
		// 			$view_pemutihan_nilai_denda += ((int) $v->pemutihan_nilai_denda);
		// 			$sisa_nilai_denda += ((int) $v->nilai_denda) - $view_pemutihan_nilai_denda;
		// 		} elseif ($v->pemutihan_nilai_denda_type == 1) {
		// 			$view_pemutihan_nilai_denda += (((int) $v->pemutihan_nilai_denda) * ((int) $sisa_nilai_denda) / 100);
		// 			$sisa_nilai_denda += ((int) $v->nilai_denda) - $view_pemutihan_nilai_denda;
		// 		}
		// 		$sisa_nilai_tagihan = $sisa_nilai_tagihan > 0 ? $sisa_nilai_tagihan : 0;
		// 		$sisa_nilai_denda = $sisa_nilai_denda > 0 ? $sisa_nilai_denda : 0;

		// 		$v->view_pemutihan_nilai_tagihan	= $view_pemutihan_nilai_tagihan > $v->total_tanpa_ppn ? $v->total_tanpa_ppn : $view_pemutihan_nilai_tagihan;
		// 		$v->view_pemutihan_nilai_denda  	= $view_pemutihan_nilai_denda > $v->nilai_denda ? $v->nilai_denda : $view_pemutihan_nilai_denda;
		// 		$v->sisa_nilai_tagihan 				= $sisa_nilai_tagihan;
		// 		$v->sisa_nilai_denda 				= $sisa_nilai_denda;

		// 		array_push($tagihan_lingkungan, $v);
		// 	} else {
		// 		$view_pemutihan_nilai_tagihan = 0;
		// 		$view_pemutihan_nilai_denda = 0;
		// 		$sisa_nilai_tagihan = 0;
		// 		$sisa_nilai_denda = 0;
		// 		if ($v->pemutihan_nilai_tagihan_type == 0) {
		// 			$view_pemutihan_nilai_tagihan = ((int) $v->pemutihan_nilai_tagihan);
		// 			$sisa_nilai_tagihan = ((int) $v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
		// 		} else if ($v->pemutihan_nilai_tagihan_type == 1) {
		// 			$view_pemutihan_nilai_tagihan = (((int) $v->pemutihan_nilai_tagihan) * ((int) $v->total_tanpa_ppn) / 100);
		// 			$sisa_nilai_tagihan = ((int) $v->total_tanpa_ppn) - $view_pemutihan_nilai_tagihan;
		// 		}
		// 		if ($v->pemutihan_nilai_denda_type == 0) {
		// 			$view_pemutihan_nilai_denda = ((int) $v->pemutihan_nilai_denda);
		// 			$sisa_nilai_denda = ((int) $v->nilai_denda) - $view_pemutihan_nilai_denda;
		// 		} elseif ($v->pemutihan_nilai_denda_type == 1) {
		// 			$view_pemutihan_nilai_denda = (((int) $v->pemutihan_nilai_denda) * ((int) $v->nilai_denda) / 100);
		// 			$sisa_nilai_denda = ((int) $v->nilai_denda) - $view_pemutihan_nilai_denda;
		// 		}

		// 		$sisa_nilai_tagihan = $sisa_nilai_tagihan > 0 ? $sisa_nilai_tagihan : 0;
		// 		$sisa_nilai_denda = $sisa_nilai_denda > 0 ? $sisa_nilai_denda : 0;

		// 		// echo($view_pemutihan_nilai_tagihan."<br>");
		// 	}
		// }
		// $view_pemutihan_nilai_denda = $view_pemutihan_nilai_denda<0?0:$view_pemutihan_nilai_denda;
		$this->load->model("core/m_tagihan");

		$tagihan_air = $this->m_tagihan->air($project->id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>$periode_now]);
		$tagihan_lingkungan = $this->m_tagihan->lingkungan($project->id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>$periode_now]);
		
		$kwitansi_per_service = $this->db
			->select("
										t_pembayaran.id as pembayaran_id,
										t_pembayaran.tgl_bayar,
										service_jenis.id as  service_jenis_id,
										service_jenis.code_default as code_service,
										service_jenis.name_default as name_service,
										sum(isnull(t_pembayaran_detail.bayar,t_pembayaran_detail.bayar_deposit)) as bayar,
										isnull(kwitansi_referensi.no_kwitansi,0) as no_kwitansi")
			->from("t_pembayaran")
			->join(
				"t_pembayaran_detail",
				"t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id"
			)
			->join(
				"service",
				"service.id = t_pembayaran_detail.service_id"
			)
			->join(
				"service_jenis",
				"service_jenis.id = service.service_jenis_id"
			)
			// ->where("no_kwitansi is null")
			->join(
				"kwitansi_referensi",
				"kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id"
			)
			->where("unit_id", $unit_id)
			->group_by("t_pembayaran.id,
												service_jenis.id,
												t_pembayaran.tgl_bayar ,
												service_jenis.code_default, 
												service_jenis.name_default,
												isnull(kwitansi_referensi.no_kwitansi,0),
												isnull(kwitansi_referensi.no_referensi,0)
												")
			->get()->result();
		$kwitansi_deposit = $this->db
			->select("
										t_deposit.id as deposit_id,
										t_deposit_detail.tgl_document as tgl_bayar,
										'Deposit' as name_service,
										t_deposit_detail.nilai as bayar,
										isnull(kwitansi_referensi.no_kwitansi,0) as no_kwitansi")
			->from("t_deposit")
			->join(
				"t_deposit_detail",
				"t_deposit_detail.t_deposit_id = t_deposit.id
											AND t_deposit_detail.nilai > 0"
			)
			->join(
				"kwitansi_referensi",
				"kwitansi_referensi.id = t_deposit_detail.kwitansi_referensi_id"
			)
			->where("t_deposit.customer_id", $unit->pemilik_id)
			->get()->result();
		$void_pembayaran =	$this->db	->select("
								t.id,
								CASE 
									WHEN bank.id is null THEN cara_pembayaran.name
									ELSE concat(cara_pembayaran.name,' - ', bank.name)
								END as cara_pembayaran,
								FORMAT (tgl_bayar, 'dd-MM-yyyy hh:mm:ss') as tgl_bayar,
								FORMAT(t_pembayaran_detail2.bayar,'N0') as bayar,
								STUFF(
									(
										SELECT 
											DISTINCT
											'<br> '+
											kr2.no_referensi
										FROM t_pembayaran tp2
										JOIN t_pembayaran_detail tpd2
											ON tpd2.t_pembayaran_id = tp2.id
										JOIN kwitansi_referensi as kr2
											ON kr2.id = tpd2.kwitansi_referensi_id
										WHERE t.id = tp2.id
										FOR XML PATH('')
									), 1, 2, ''
								) as gabungan_no_referensi")
						->from("
								(SELECT 
									tp1.id
									FROM t_pembayaran tp1
								) t")
						->join("t_pembayaran",
								"t_pembayaran.id = t.id")
						->join("cara_pembayaran",
								"cara_pembayaran.id = t_pembayaran.cara_pembayaran_id")
						->join("t_pembayaran_detail",
								"t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
						->join("
								(
									SELECT 
										t_pembayaran_id, sum(bayar+bayar_deposit) as bayar
									FROM t_pembayaran_detail 
										group by t_pembayaran_id 
								) t_pembayaran_detail2",
								"t_pembayaran_detail2.t_pembayaran_id = t_pembayaran.id")
						->join("bank",
								"bank.id = cara_pembayaran.bank_id",
								"LEFT")
						->where("t_pembayaran.unit_id",$unit_id)
						->distinct()
						->get()->result();
		

		$jumlah_tunggakan = 0;
		$jumlah_ppn = 0;
		$jumlah_denda = 0;
		$jumlah_penalti = 0;
		$jumlah_tagihan = 0;
		$jumlah_pemutihan_tagihan = 0;
		$jumlah_pemutihan_denda = 0;

		$jumlah_nilai_pokok 			= 0;
		$jumlah_nilai_ppn 				= 0;
		$jumlah_nilai_denda 			= 0;
		$jumlah_nilai_penalti 			= 0;
		$jumlah_nilai_pemutihan_pokok 	= 0;
		$jumlah_nilai_pemutihan_denda 	= 0;
		$jumlah_total 					= 0;
		foreach ($tagihan_lingkungan as $v) {
			$jumlah_nilai_pokok 			+= $v->total_tanpa_ppn;
			$jumlah_nilai_ppn 				+= $v->ppn;
			$jumlah_nilai_denda 			+= $v->nilai_denda;
			$jumlah_nilai_penalti 			+= $v->nilai_penalti;
			$jumlah_nilai_pemutihan_pokok 	+= $v->view_pemutihan_nilai_tagihan;
			$jumlah_nilai_pemutihan_denda 	+= $v->view_pemutihan_nilai_denda;
			$jumlah_total 					+= $v->total;
		}
		foreach ($tagihan_air as $v) {
			$jumlah_nilai_pokok 			+= $v->total_tanpa_ppn;
			$jumlah_nilai_ppn 				+= $v->ppn;
			$jumlah_nilai_denda 			+= $v->nilai_denda;
			$jumlah_nilai_penalti 			+= $v->nilai_penalti;
			$jumlah_nilai_pemutihan_pokok 	+= $v->view_pemutihan_nilai_tagihan;
			$jumlah_nilai_pemutihan_denda 	+= $v->view_pemutihan_nilai_denda;
			$jumlah_total 					+= $v->total;
		}
		
        $unit->pemilik					= $pemilik;
		$unit->penghuni					= $penghuni;
		$unit->tagihan_air = $tagihan_air;
		$unit->tagihan_lingkungan = $tagihan_lingkungan;


		$unit->jumlah_tunggakan 		= $jumlah_tunggakan;

		$unit->jumlah_ppn 				= $jumlah_ppn;
		$unit->jumlah_denda 			= $jumlah_denda ? $jumlah_denda : 0;
		$unit->jumlah_penalti 			= $jumlah_penalti;
		$unit->jumlah_tagihan 			= $jumlah_tagihan;
		$unit->jumlah_semua 			= $jumlah_denda + $jumlah_penalti + $jumlah_tagihan + $jumlah_ppn - ($jumlah_pemutihan_tagihan+$jumlah_pemutihan_denda);
		$unit->jumlah_pemutihan_tagihan	= $jumlah_pemutihan_tagihan<0?0:$jumlah_pemutihan_tagihan;
		
		$unit->jumlah_pemutihan_denda	= $jumlah_pemutihan_denda<0?0:$jumlah_pemutihan_denda;
		echo json_encode($unit);

    }
    public function ajax_get_tagihan($unit_id)
	{
		$project = $this->m_core->project();
		$dateForm = $this->input->post("date");

		// $unit_id = $this->input->post("unit_id");
		if($dateForm)
			$periode_now = substr($dateForm,6,4)."-".substr($dateForm,3,2)."-01";
		else
			$periode_now = date("Y-m-01");

		$periode_pemakaian = date("Y-m-01", strtotime("-1 Months"));

		// $tagihan_air = $this->db->select("
		// 						CASE
		// 							WHEN v_tagihan_air.periode >= '$periode_now' THEN 1
		// 							ELSE 0
		// 						END as is_tagihan,
		// 						v_tagihan_air.total_tanpa_ppn,
		// 						v_tagihan_air.tagihan_id,
		// 						service.id as service_id,
		// 						service.service_jenis_id as service_jenis_id,
		// 						v_tagihan_air.periode,
		// 						CASE 
		// 							WHEN v_tagihan_air.status_tagihan = 0 THEN isnull(v_tagihan_air.total,0) 
		// 							ELSE 0 
		// 						END as nilai_tagihan,
		// 						CASE 
		// 							WHEN v_tagihan_air.status_tagihan = 0 THEN
		// 								isnull(CASE
		// 								WHEN service.denda_flag = 0 THEN 0
		// 								WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
		// 								WHEN v_tagihan_air.periode > '$periode_now' THEN 0
		// 									ELSE
		// 									CASE					
		// 										WHEN v_tagihan_air.denda_jenis_service = 1 
		// 											THEN v_tagihan_air.denda_nilai_service 
		// 										WHEN v_tagihan_air.denda_jenis_service = 2 
		// 											THEN v_tagihan_air.denda_nilai_service *
		// 												(DateDiff
		// 													( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$periode_now' ) 
		// 													+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) 
		// 												)
		// 										WHEN v_tagihan_air.denda_jenis_service = 3 
		// 											THEN 
		// 												(v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) 
		// 												* (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
		// 												+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
		// 									END 
		// 								END,0) 
		// 							ELSE 0
		// 						END AS nilai_denda,
		// 						isnull(v_tagihan_air.total,0)+
		// 						isnull(CASE
		// 							WHEN service.denda_flag = 0 THEN 0
		// 							WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
		// 							WHEN v_tagihan_air.periode > '$periode_now' THEN 0
		// 							ELSE
		// 							CASE
		// 								WHEN v_tagihan_air.denda_jenis_service = 1 
		// 									THEN v_tagihan_air.denda_nilai_service 
		// 								WHEN v_tagihan_air.denda_jenis_service = 2 
		// 									THEN v_tagihan_air.denda_nilai_service *
		// 										(DateDiff
		// 											( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$periode_now' ) 
		// 											+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) 
		// 										)
		// 								WHEN v_tagihan_air.denda_jenis_service = 3 
		// 									THEN (v_tagihan_air.denda_nilai_service * v_tagihan_air.total/ 100 ) 
		// 									* (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) 
		// 									+ IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
		// 							END 
		// 						END,0) AS total,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_tagihan_type),-1) as pemutihan_nilai_tagihan_type,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_tagihan),-1) as pemutihan_nilai_tagihan,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_denda_type),-1) as pemutihan_nilai_denda_type,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_denda),-1) as pemutihan_nilai_denda,
        //                         isnull(t_pembayaran_detail.sisa_tagihan,0) as belum_bayar,
        //                         t_pencatatan_meter_air.meter_awal,
        //                         t_pencatatan_meter_air.meter_akhir")
		// 	->from("v_tagihan_air")
        //     // ->where("v_tagihan_air.periode <= '$periode_now'")
        //     ->join("t_pencatatan_meter_air",
        //             "t_pencatatan_meter_air.unit_id = v_tagihan_air.unit_id
        //             AND t_pencatatan_meter_air.periode = v_tagihan_air.periode")
		// 	->join(
		// 		"service",
		// 		"service.project_id = $project->id
		// 		AND service.service_jenis_id = 2
		// 		AND service.active = 1
		// 		AND service.delete = 0"
		// 	)
		// 	->join("v_pemutihan",
		// 			"v_pemutihan.masa_akhir >= GETDATE()
		// 			AND v_pemutihan.masa_awal <= GETDATE()
		// 			AND v_pemutihan.periode_akhir >= v_tagihan_air.periode 
		// 			AND v_pemutihan.periode_awal <= v_tagihan_air.periode 
		// 			AND v_pemutihan.service_jenis_id = 2
		// 			AND v_pemutihan.unit_id  = v_tagihan_air.unit_id",
		// 			"LEFT")
		// 	->join("t_tagihan_air",
		// 			"t_tagihan_air.t_tagihan_id = v_tagihan_air.t_tagihan_id",
		// 			"LEFT")
		// 			// AND t_tagihan_air.periode = dateadd(MONTH,-1,v_tagihan_lingkungan.periode)",

		// 	->join("t_pembayaran_detail",
		// 			"t_pembayaran_detail.tagihan_service_id = t_tagihan_air.id
		// 			AND t_pembayaran_detail.service_id = service.id",
		// 			"LEFT")
		// 	->where("(v_tagihan_air.status_tagihan = 0 or v_tagihan_air.status_tagihan = 4)")
		// 	->where("v_tagihan_air.unit_id = $unit_id")
		// 	->order_by("v_pemutihan.tgl_tambah,periode")
		// 	->get()->result();
		
		// $tagihan_lingkungan = $this->db->select("
		// 						CASE
		// 							WHEN v_tagihan_lingkungan.periode >= '$periode_now' THEN 1
		// 							ELSE 0
		// 						END as is_tagihan,
		// 						v_tagihan_lingkungan.total_tanpa_ppn,
		// 						v_tagihan_lingkungan.tagihan_id,
		// 						service.id as service_id,
		// 						service.service_jenis_id as service_jenis_id,
		// 						v_tagihan_lingkungan.periode,
		// 						CASE 
		// 							WHEN v_tagihan_lingkungan.status_tagihan = 0 THEN isnull(v_tagihan_lingkungan.total,0)
		// 							ELSE 0
		// 						END as nilai_tagihan,
		// 						CASE
		// 							WHEN v_tagihan_lingkungan.status_tagihan = 0 THEN
		// 								isnull(CASE
		// 									WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN
		// 										CASE
		// 											WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
		// 												THEN v_tagihan_lingkungan.denda_nilai_service 
		// 											WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
		// 												THEN 
		// 													v_tagihan_lingkungan.denda_nilai_service * 
		// 														(DateDiff
		// 															( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
		// 															+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
		// 														)
		// 											WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
		// 												THEN 
		// 													( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
		// 													* (DateDiff( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
		// 													+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
		// 										END 	
		// 									WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
		// 									WHEN v_tagihan_lingkungan.periode > '$periode_now' THEN 0
		// 									ELSE
		// 										CASE
		// 											WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
		// 												THEN v_tagihan_lingkungan.denda_nilai_service 
		// 											WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
		// 												THEN v_tagihan_lingkungan.denda_nilai_service * 
		// 													(DateDiff
		// 														( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
		// 														+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
		// 													)
		// 											WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
		// 												THEN 
		// 													( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
		// 													* (DateDiff( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
		// 													+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
		// 										END 
		// 									END,0) 
		// 								ELSE 0
		// 							END AS nilai_denda,
		// 						isnull(v_tagihan_lingkungan.total,0)+
		// 						isnull(CASE
		// 							WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN
		// 								CASE
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
		// 										THEN v_tagihan_lingkungan.denda_nilai_service 
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
		// 										THEN v_tagihan_lingkungan.denda_nilai_service * 
		// 											(DateDiff
		// 												( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
		// 												+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
		// 											)
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
		// 										THEN 
		// 										( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
		// 										* (DateDiff( MONTH, unit_lingkungan.tgl_mulai_denda, '$periode_now' ) 
		// 										+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) )
		// 								END 	
		// 							WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
		// 							WHEN v_tagihan_lingkungan.periode > '$periode_now' THEN 0
		// 							ELSE
		// 								CASE
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
		// 										THEN v_tagihan_lingkungan.denda_nilai_service 
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
		// 										THEN v_tagihan_lingkungan.denda_nilai_service * 
		// 										(DateDiff
		// 											( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
		// 											+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
		// 										)
		// 									WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
		// 										THEN 
		// 											( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
		// 											* ( 
		// 												DateDiff ( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$periode_now' ) 
		// 												+ IIF(" . date("d") . ">=service.denda_tanggal_jt,1,0) 
		// 											)
		// 								END 
		// 							END,0) AS total,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_tagihan_type),-1) as pemutihan_nilai_tagihan_type,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_tagihan),-1) as pemutihan_nilai_tagihan,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_denda_type),-1) as pemutihan_nilai_denda_type,
		// 						isnull(CONVERT(int,v_pemutihan.nilai_denda),-1) as pemutihan_nilai_denda,
		// 						isnull(t_pembayaran_detail.sisa_tagihan,0) as belum_bayar")	
		// 	->from("v_tagihan_lingkungan")
		// 	// ->where("v_tagihan_lingkungan.periode <= '$periode_now'")
		// 	->join(
		// 		"service",
		// 		"service.project_id = $project->id
		// 		AND service.service_jenis_id = 1
		// 		AND service.active = 1
		// 		AND service.delete = 0"
		// 	)
		// 	->join("v_pemutihan",
		// 			"v_pemutihan.masa_akhir >= GETDATE()
		// 			AND v_pemutihan.masa_awal <= GETDATE()
		// 			AND v_pemutihan.periode_akhir >= v_tagihan_lingkungan.periode 
		// 			AND v_pemutihan.periode_awal <= v_tagihan_lingkungan.periode 
		// 			AND v_pemutihan.service_jenis_id = 1
		// 			AND v_pemutihan.unit_id  = v_tagihan_lingkungan.unit_id",
		// 			"LEFT")
		// 	->join("t_tagihan_lingkungan",
		// 			"t_tagihan_lingkungan.t_tagihan_id = v_tagihan_lingkungan.t_tagihan_id",
		// 			"LEFT")
		// 			// AND t_tagihan_lingkungan.periode = dateadd(MONTH,-1,v_tagihan_lingkungan.periode)",

		// 	->join("t_pembayaran_detail",
		// 			"t_pembayaran_detail.tagihan_service_id = t_tagihan_lingkungan.id
		// 			AND t_pembayaran_detail.service_id = service.id",
		// 			"LEFT")
		// 	->join("unit_lingkungan",
		// 		"unit_lingkungan.unit_id = v_tagihan_lingkungan.unit_id")
		// 	->where("(v_tagihan_lingkungan.status_tagihan = 0 or v_tagihan_lingkungan.status_tagihan = 4)")
		// 	->where("v_tagihan_lingkungan.unit_id = $unit_id")
		// 	->order_by("v_pemutihan.tgl_tambah,periode")
		// 	->get()->result();
		$this->load->model("core/m_tagihan");

		$tagihan_air = $this->m_tagihan->air($project->id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>$periode_now]);
		$tagihan_lingkungan = $this->m_tagihan->lingkungan($project->id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>$periode_now]);
		
		// $tagihan_loi_registrasi = $this->db->select("
		// 							t_tagihan_loi.id as tagihan_id,
		// 							t_tagihan_loi.periode,
		// 							t_loi_registrasi.total as nilai_tagihan,
		// 							'LOI <br> Registrasi' as service,
		// 							5 as service_jenis_id,
		// 							service.id as service_id								
		// 							")
		// 						->from("t_tagihan_loi")
		// 						->join("t_loi_registrasi",
		// 								"t_loi_registrasi.id = t_tagihan_loi.t_loi_registrasi_id
		// 								AND t_tagihan_loi.tipe = 1")
		// 						->join("service",
		// 								"service.service_jenis_id = 5
		// 								AND service.project_id = t_tagihan_loi.project_id")
		// 						->where("t_tagihan_loi.status_tagihan",0)
		// 						->where("t_tagihan_loi.unit_id",$unit_id)
		// 						->get()->result();

		//loi yang depositnya kurang akibat ada penambahan item di survey nya

		// $tagihan_loi_deposit = $this->db	->select("
		// 											t_tagihan_loi.id as tagihan_id,
		// 											t_tagihan_loi.periode,
		// 											CASE
		// 												WHEN tipe = 2 THEN deposit_pemakaian - deposit_masuk
		// 												WHEN tipe = 3 THEN 
		// 													CASE 
		// 														WHEN (deposit_pemakaian - deposit_masuk) > 0 THEN deposit_pemakaian2
		// 														ELSE deposit_pemakaian2 + (deposit_pemakaian - deposit_masuk)
		// 													END
		// 												ELSE 0
		// 											END as nilai_tagihan,
		// 											CASE
		// 												WHEN tipe = 2 THEN 'LOI <br> Kurang Deposit 1'
		// 												WHEN tipe = 3 THEN 'LOI <br> Kurang Deposit 2'
		// 												ELSE ''
		// 											END as service,
		// 											5 as service_jenis_id,
		// 											service.id as service_id
		// 										")
		// 									->from("t_tagihan_loi")
		// 									->join("service",
		// 											"service.service_jenis_id = 5
		// 											AND service.project_id = t_tagihan_loi.project_id")
		// 									->join("t_loi_deposit",
		// 											"t_loi_deposit.t_loi_registrasi_id = t_tagihan_loi.t_loi_registrasi_id")
		// 									->where("unit_id",$unit_id)
		// 									->where("status_tagihan",0)
		// 									->where("(t_tagihan_loi.tipe = 2 or t_tagihan_loi.tipe = 3)")
		// 									->get()->result();

		$jumlah_tunggakan_bulan = 0;
		$jumlah_tunggakan = 0;
		$jumlah_denda = 0;
		$jumlah_penalti = 0;
		$jumlah_tagihan = 0;

		
		$jumlah_nilai_pokok 			= 0;
		$jumlah_nilai_ppn 				= 0;
		$jumlah_nilai_denda 			= 0;
		$jumlah_nilai_penalti 			= 0;
		$jumlah_nilai_pemutihan_pokok 	= 0;
		$jumlah_nilai_pemutihan_denda 	= 0;
		$jumlah_total 					= 0;
		foreach ($tagihan_lingkungan as $v) {
			$jumlah_nilai_pokok 			+= $v->total_tanpa_ppn;
			$jumlah_nilai_ppn 				+= $v->ppn;
			$jumlah_nilai_denda 			+= $v->nilai_denda;
			$jumlah_nilai_penalti 			+= $v->nilai_penalti;
			$jumlah_nilai_pemutihan_pokok 	+= $v->view_pemutihan_nilai_tagihan;
			$jumlah_nilai_pemutihan_denda 	+= $v->view_pemutihan_nilai_denda;
			$jumlah_total 					+= $v->total;
		}
		foreach ($tagihan_air as $v) {
			$jumlah_nilai_pokok 			+= $v->total_tanpa_ppn;
			$jumlah_nilai_ppn 				+= $v->ppn;
			$jumlah_nilai_denda 			+= $v->nilai_denda;
			$jumlah_nilai_penalti 			+= $v->nilai_penalti;
			$jumlah_nilai_pemutihan_pokok 	+= $v->view_pemutihan_nilai_tagihan;
			$jumlah_nilai_pemutihan_denda 	+= $v->view_pemutihan_nilai_denda;
			$jumlah_total 					+= $v->total;
		}
		$unit = (object) [];
		$unit->tagihan_air = $tagihan_air;
		$unit->tagihan_lingkungan = $tagihan_lingkungan;
		// $unit->tagihan_loi = $tagihan_loi_registrasi;
		// $unit->tagihan_loi = $tagihan_loi_deposit;
		// $unit->tagihan_loi = (array_merge($tagihan_loi_registrasi,$tagihan_loi_deposit));
		return ($unit);

		// echo json_encode($this->m_pembayaran->ajax_get_tagihan($this->input->get("unit_id")));
	}
}
