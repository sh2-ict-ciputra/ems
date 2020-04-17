<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class konfirmasi_tagihan_api extends REST_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Setting/m_parameter_project');
        $this->load->model('Cetakan/m_konfirmasi_tagihan');
        $this->load->model('m_core');

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
    
    public function test_get(){
        echo json_encode(1);
    }
    public function send_post($unit_id=null){
        $isi_konfirmasi_tagihan = $this->post("isi");
        $project_id = $this->post("project_id");
        // var_dump($isi_konfirmasi_tagihan);
        // $project = $this->m_core->project();
        // var_dump($project);

        $this->load->helper('file');

        $unit_id = str_replace(".pdf","",$unit_id);


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
        $ttd                    = $this->m_parameter_project->get($project_id,"ttd_konfirmasi_tagihan");

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
        
		//Data Tagihan Without Sorting

        $dataTagihanWoS = $this->ajax_get_tagihan($unit_id,$project_id);
		// echo("dataTagihanWoS<pre>");
		// 	print_r($dataTagihanWoS);
		// echo("</pre>");
		
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
        
        
        $nama_file = $unit_id.date("_Y-m-d_H-i-s").".pdf";
        // var_dump($project);

        $ttd                    = $this->m_parameter_project->get($project_id,"ttd_konfirmasi_tagihan");
        $catatan = $unit->catatan;
		$catatan = str_replace("{{va_unit}}",$unit->virtual_account,$catatan);
		
		$service_air = $this->db->select("jarak_periode_penggunaan")
				->from("service")
				->where("project_id",$project_id)
				->where("service_jenis_id",2)
				->get()->row();
		$service_air = $service_air?$service_air->jarak_periode_penggunaan:0;
		$service_lingkungan = $this->db->select("jarak_periode_penggunaan")
				->from("service")
				->where("project_id",$project_id)
				->where("service_jenis_id",1)
				->get()->row();
		$service_lingkungan = $service_lingkungan?$service_lingkungan->jarak_periode_penggunaan:0;
		if($service_air == $service_lingkungan)
		$jarak_periode_penggunaan = $service_air;
		else
		$jarak_periode_penggunaan = -1;
        
        for($i = $min_tagihan; $i <= $max_tagihan; $i->modify('+1 month')){
			$periode = $i->format("Y-m-01");
			
			if($jarak_periode_penggunaan != -1){

				$tmp = $periode;
				
				$tmp = strtotime(date("Y-m-d", strtotime($tmp)) . " -$jarak_periode_penggunaan month");
				$tmp = date("Y-m-d",$tmp);

				// $tmp = substr($tmp,5,2).'-'.substr($tmp,8,2).'-'.substr($tmp,0,4);
		
				$periode_1 = $tmp;

			}
            $dataTagihanWS[$iterasi] =(object)[];
			$dataTagihanWS[$iterasi]->periode = substr($this->bln_indo(substr($periode,5,2)),0,3)." ".substr($periode,0,4);
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
		// echo("total_tagihan<pre>");
		// 	print_r($total_tagihan);
		// echo("</pre>");
		if($jarak_periode_penggunaan != -1)

			$a = $this->pdf->send("proyek/cetakan/konfirmasi_tagihan2",[
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
			$a = $this->pdf->send("proyek/cetakan/konfirmasi_tagihan",[
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
        $isi_konfirmasi_tagihan = str_replace("{{Project}}",$unit->project,$isi_konfirmasi_tagihan);
        $isi_konfirmasi_tagihan = str_replace("{{Kawasan}}",$unit->kawasan,$isi_konfirmasi_tagihan);
        $isi_konfirmasi_tagihan = str_replace("{{Blok}}",$unit->blok,$isi_konfirmasi_tagihan);
        $isi_konfirmasi_tagihan = str_replace("{{No_unit}}",$unit->no_unit,$isi_konfirmasi_tagihan);
        $isi_konfirmasi_tagihan = str_replace("{{Pemilik}}",$unit->pemilik,$isi_konfirmasi_tagihan);

        if ($periode_first == $periode_last) {
            $isi_konfirmasi_tagihan = str_replace("{{Bulan}}",$periode_first,$isi_konfirmasi_tagihan);
        } else {
            $isi_konfirmasi_tagihan = str_replace("{{Bulan}}",$periode_first . " sampai " . $periode_last,$isi_konfirmasi_tagihan);
        }




        $isi_konfirmasi_tagihan = str_replace("{{Tahun}}",date("Y"),$isi_konfirmasi_tagihan);
        // var_dump($isi_konfirmasi_tagihan);


        if(write_file("application/pdf/$nama_file", $a)){
            // echo json_encode([
            //     "isi"=>$isi_konfirmasi_tagihan,
            //     "name_file"=>$nama_file
			// ]);
			$this->set_response([
				"isi"=>$isi_konfirmasi_tagihan,
				"name_file"=>$nama_file
			], REST_Controller::HTTP_CREATED);
        }else{
			$this->set_response("Gagal". $nama_file, REST_Controller::HTTP_CREATED);
            // echo("Gagal ".$nama_file);
            // echo json_encode(false);
		}
		// $this->set_response([
		// 						"isi"=>$isi_konfirmasi_tagihan,
		// 						"name_file"=>$nama_file
		// 					], REST_Controller::HTTP_CREATED);

    }
    public function ajax_get_tagihan($unit_id,$project_id)
	{
		// $project = $this->m_core->project();
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
		// echo($unit_id);
		// die;
		$tagihan_air = $this->m_tagihan->air($project_id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>$periode_now]);
		$tagihan_lingkungan = $this->m_tagihan->lingkungan($project_id,['status_tagihan'=>[0,2,3,4],'unit_id'=>[$unit_id],'periode'=>$periode_now]);
		
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
