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
    public function lingkungan($project_id,$data = null){
        $data =(object)$data;
        $periode = isset($data->periode)?substr($data->periode,0,4).'-'.substr($data->periode,5,2).'-01':date('Y-m-01');
        $date = isset($data->periode)?substr($data->periode,0,4).'-'.substr($data->periode,5,2).'-'.substr($data->periode,8,2):date('Y-m-d');
        $day = isset($data->periode)?substr($data->periode,8,2):date('d');
		$tagihan_lingkungan = $this->db->select("
								'Lingkungan' as service,
								CASE
									WHEN v_tagihan_lingkungan.periode >= '$periode' THEN 1
									ELSE 0
								END as is_tagihan,
								0 as nilai_penalti,
								v_tagihan_lingkungan.total_tanpa_ppn,
								v_tagihan_lingkungan.tagihan_id,
								service.id as service_id,
								service.service_jenis_id as service_jenis_id,
								v_tagihan_lingkungan.periode,
								DATEADD(MONTH, -(service.jarak_periode_penggunaan), v_tagihan_lingkungan.periode) as periode_pemakaian,
								unit_lingkungan.tgl_mulai_denda,
								v_tagihan_lingkungan.status_tagihan,
								isnull(CASE 
									WHEN v_tagihan_lingkungan.status_tagihan = 0 or v_tagihan_lingkungan.status_tagihan = 2 or v_tagihan_lingkungan.status_tagihan = 3  THEN isnull(v_tagihan_lingkungan.ppn,0)
									ELSE 0
								END,0) as ppn,
								CASE RIGHT(isnull(v_tagihan_lingkungan.total,0),2)
									WHEN 99 THEN 1
									WHEN 01 THEN -1
									ELSE 0
								END
								+
								CASE 
									WHEN v_tagihan_lingkungan.status_tagihan = 4 THEN 
										isnull(v_tagihan_lingkungan.total,0) - iif(t_pembayaran.is_void=1,0,t_pembayaran_detail.nilai_tagihan)
									else isnull(v_tagihan_lingkungan.total,0)
								END as nilai_tagihan,
								CASE
									WHEN v_tagihan_lingkungan.status_tagihan = 0 or v_tagihan_lingkungan.status_tagihan = 2 or v_tagihan_lingkungan.status_tagihan = 3 or v_tagihan_lingkungan.status_tagihan = 4 THEN
										isnull(CASE
											WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN
												0
											WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
											WHEN DATEADD(MONTH,service.denda_selisih_bulan,v_tagihan_lingkungan.periode) > '$date' THEN 0
											ELSE
												CASE
													WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
														THEN v_tagihan_lingkungan.denda_nilai_service 
													WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
														THEN v_tagihan_lingkungan.denda_nilai_service * 
															(DateDiff
																( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$date' ) 
																+ IIF(" . $day. ">=service.denda_tanggal_jt,1,0) 
															)
													WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
														THEN 
															( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
															* (DateDiff( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$date' ) 
															+ IIF(" . $day. ">=service.denda_tanggal_jt,1,0) )
												END 
											END,0) 
										ELSE 0
								END 
								-
								CASE 
									WHEN v_tagihan_lingkungan.status_tagihan = 4 THEN isnull(iif(t_pembayaran.is_void=1,0,t_pembayaran_detail.nilai_denda),0)
									else 0
								END
								AS nilai_denda,
								isnull(v_tagihan_lingkungan.total,0)+
								isnull(CASE
								WHEN v_tagihan_lingkungan.status_tagihan = 0 or v_tagihan_lingkungan.status_tagihan = 2 or v_tagihan_lingkungan.status_tagihan = 3 THEN
									isnull(CASE
										WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN
											0
										WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
										WHEN DATEADD(MONTH,service.denda_selisih_bulan,v_tagihan_lingkungan.periode) > '$date' THEN 0
										ELSE
											CASE
												WHEN v_tagihan_lingkungan.denda_jenis_service = 1 
													THEN v_tagihan_lingkungan.denda_nilai_service 
												WHEN v_tagihan_lingkungan.denda_jenis_service = 2 
													THEN v_tagihan_lingkungan.denda_nilai_service * 
														(DateDiff
															( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$date' ) 
															+ IIF(" . $day. ">=service.denda_tanggal_jt,1,0) 
														)
												WHEN v_tagihan_lingkungan.denda_jenis_service = 3 
													THEN 
														( v_tagihan_lingkungan.denda_nilai_service * v_tagihan_lingkungan.total/ 100 ) 
														* (DateDiff( MONTH, DATEADD(MONTH, service.denda_selisih_bulan, v_tagihan_lingkungan.periode), '$date' ) 
														+ IIF(" . $day. ">=service.denda_tanggal_jt,1,0) )
											END 
										END,0) 
									ELSE 0
								END,0) 
									- isnull(v_pemutihan.pemutihan_nilai_tagihan,0)
									- isnull(v_pemutihan.pemutihan_nilai_denda,0)
									AS total,
								v_tagihan_lingkungan.ppn_flag,
								CONVERT(INT,
									CASE v_tagihan_lingkungan.ppn_flag
										WHEN 1 THEN 
											isnull(
												round(v_pemutihan.pemutihan_nilai_tagihan*(1.0+(v_tagihan_lingkungan.nilai_ppn/100.0)),0)
												,0)
										ELSE isnull(v_pemutihan.pemutihan_nilai_tagihan,0)
									END
								) as view_pemutihan_nilai_tagihan,
								CONVERT(INT,
									CASE v_tagihan_lingkungan.ppn_flag
										WHEN 1 THEN 
										isnull(
											round(v_pemutihan.pemutihan_nilai_denda*(1.0+(v_tagihan_lingkungan.nilai_ppn/100.0)),0)
											,0)
										ELSE isnull(v_pemutihan.pemutihan_nilai_denda,0)
									END
								) as view_pemutihan_nilai_denda,								
								isnull(iif(t_pembayaran.is_void=1,0,t_pembayaran_detail.sisa_tagihan),0) as belum_bayar")		
            ->from("v_tagihan_lingkungan")
            // ->distinct()
			// ->where("v_tagihan_lingkungan.periode <= '$periode_now'")
			->join(
				"service",
				"service.project_id = $project_id
				AND service.service_jenis_id = 1
				AND service.active = 1
				AND service.delete = 0"
			)
			->join("v_pemutihan",
					"v_pemutihan.masa_akhir >= '$date'
					AND v_pemutihan.masa_awal <= '$date'
					AND v_pemutihan.periode = v_tagihan_lingkungan.periode 
					AND v_pemutihan.service_jenis_id = 1
					AND v_pemutihan.unit_id  = v_tagihan_lingkungan.unit_id",
					"LEFT")
			->join("t_tagihan_lingkungan",
					"t_tagihan_lingkungan.t_tagihan_id = v_tagihan_lingkungan.t_tagihan_id
					AND t_tagihan_lingkungan.unit_id =  v_tagihan_lingkungan.unit_id
					AND t_tagihan_lingkungan.periode =  v_tagihan_lingkungan.periode",
					"LEFT")
					// AND t_tagihan_lingkungan.periode = dateadd(MONTH,-1,v_tagihan_lingkungan.periode)",
			->join("t_pembayaran_detail",
					"t_pembayaran_detail.tagihan_service_id = t_tagihan_lingkungan.id
					AND t_pembayaran_detail.service_id = service.id",
					"LEFT")
			->join("t_pembayaran",
					"t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id
					AND (
						(t_pembayaran.is_void = 0 and v_tagihan_lingkungan.status_tagihan in (0,4))
						or (t_pembayaran.is_void = 1 and v_tagihan_lingkungan.status_tagihan = 0)
						)",
					"LEFT")
			->join("unit_lingkungan",
				"unit_lingkungan.unit_id = v_tagihan_lingkungan.unit_id");
			// ->where("v_tagihan_lingkungan.unit_id = $unit_id")
        if(isset($data->unit_id))
                $tagihan_lingkungan = $tagihan_lingkungan->where_in("v_tagihan_lingkungan.unit_id",$data->unit_id);
        if(isset($data->status_tagihan))  
                $tagihan_lingkungan = $tagihan_lingkungan->where_in("v_tagihan_lingkungan.status_tagihan",$data->status_tagihan);
        else
                $tagihan_lingkungan = $tagihan_lingkungan->where("(v_tagihan_lingkungan.status_tagihan = 0 or v_tagihan_lingkungan.status_tagihan = 4  or v_tagihan_lingkungan.status_tagihan = 2 or v_tagihan_lingkungan.status_tagihan = 3)");

        $tagihan_lingkungan = $tagihan_lingkungan
                                    ->distinct()
                                    ->get();
		// var_dump($this->db->last_query());

        // var_dump($this->db->last_query());
        $tagihan_lingkungan = $this->db->from("(".$this->db->last_query().") as a")
                                        ->order_by("periode")
										->get()->result();
		// echo("<pre>");
		// 	print_r($tagihan_lingkungan);
		// echo("</pre>");
		// echo("<pre>");
		// 	print_r($this->db->last_query());
		// echo("</pre>");
															

                $tagihan_lingkungan_tmp = $tagihan_lingkungan;
		$tagihan_lingkungan = [];
		$view_pemutihan_nilai_tagihan = 0;
		$view_pemutihan_nilai_denda = 0;
		$sisa_nilai_tagihan = 0;
		$sisa_nilai_denda = 0;
		foreach ($tagihan_lingkungan_tmp as $k => $v) {
			// if($v->view_pemutihan_nilai_tagihan > 0 ){
			// 	$v->nilai_tagihan = ($v->total_tanpa_ppn - $v->view_pemutihan_nilai_tagihan) + (($v->total_tanpa_ppn - $v->view_pemutihan_nilai_tagihan) * 10 / 100);
			// }
			if (substr($v->view_pemutihan_nilai_tagihan,-2) == '99')
				$tagihan_lingkungan_tmp[$k]->view_pemutihan_nilai_tagihan++;
			elseif (substr($v->view_pemutihan_nilai_tagihan,-2) == '01')
				$tagihan_lingkungan_tmp[$k]->view_pemutihan_nilai_tagihan--;
			$v->sisa_nilai_tagihan = $v->nilai_tagihan - $v->view_pemutihan_nilai_tagihan;

			// if($v->view_pemutihan_nilai_denda > 0 ){
			// 	$v->nilai_denda = ($v->nilai_denda - $v->view_pemutihan_nilai_denda);
			// }
			$v->sisa_nilai_denda = $v->nilai_denda - $v->view_pemutihan_nilai_denda;

			array_push($tagihan_lingkungan,$v); 

        }
        return $tagihan_lingkungan;
	}
    public function layanan_lain($project_id,$data = null){
		$data = (object) $data;
		$tagihan_layanan_lain = $this->db->select("
													t_tagihan_layanan_lain.id as tagihan_id,
													CONCAT('Layanan Lain - <br>',paket_service.name) as service,
													case
														WHEN periode_awal = periode_akhir THEN convert(varchar,periode_awal) 
														ELSE CONCAT(periode_awal,'<br>',periode_akhir) 
													END as periode,
													case
														WHEN periode_awal = periode_akhir THEN convert(varchar,periode_awal) 
														ELSE CONCAT(periode_awal,'<br>',periode_akhir) 
													END as periode_pemakaian,
													1 as is_tagihan,
													0 as nilai_penalti,
													CASE 
														WHEN t_tagihan_layanan_lain.status_tagihan = 0 THEN t_tagihan_layanan_lain.total_nilai - isnull(t_pembayaran_detail.nilai_tagihan,0)
														WHEN t_tagihan_layanan_lain.status_tagihan = 4 THEN 
														0
													END as nilai_tagihan,
													t_tagihan_layanan_lain.total_nilai - isnull(t_pembayaran_detail.nilai_tagihan,0) as total,
													service.id as service_id,
													service.service_jenis_id as service_jenis_id,
													isnull(t_pembayaran_detail.sisa_tagihan,0) as belum_bayar

												",false)
												// t_tagihan_layanan_lain.total_nilai - isnull(t_pembayaran_detail.nilai_tagihan,0) as belum_bayar

												->from('t_tagihan_layanan_lain')
												->join('t_layanan_lain_registrasi',
														't_layanan_lain_registrasi.id = t_tagihan_layanan_lain.t_layanan_lain_registrasi_id')
												->join('t_layanan_lain_registrasi_detail',
														't_layanan_lain_registrasi_detail.t_layanan_lain_registrasi_id = t_layanan_lain_registrasi.id')
												->join('service',
														'service.id = t_layanan_lain_registrasi_detail.service_id')
												->join('paket_service',
														'paket_service.id = t_layanan_lain_registrasi_detail.paket_service_id')
												->join("t_pembayaran_detail",
														"t_pembayaran_detail.tagihan_service_id = t_tagihan_layanan_lain.id
														AND t_pembayaran_detail.service_id = service.id",
														"LEFT")
												->where_in('t_tagihan_layanan_lain.status_tagihan',[0,4]);
		if(isset($data->unit_id)){
			$tagihan_layanan_lain = $tagihan_layanan_lain
										->where_in('t_tagihan_layanan_lain.unit_id',$data->unit_id);
		}else{
			$tagihan_layanan_lain = $tagihan_layanan_lain
										->where_in('t_tagihan_layanan_lain.unit_virtual_id',$data->unit_virtual_id);
		}
		$tagihan_layanan_lain = $tagihan_layanan_lain->get()->result();
		// echo("<pre>");
		// print_r($this->db->last_query());
		// echo("</pre>");
		return $tagihan_layanan_lain;
	}
	public function air($project_id,$data = null){
		$data =(object)$data;
        $periode = isset($data->periode)?substr($data->periode,0,4).'-'.substr($data->periode,5,2).'-01':date('Y-m-01');
        $date = isset($data->periode)?substr($data->periode,0,4).'-'.substr($data->periode,5,2).'-'.substr($data->periode,8,2):date('Y-m-d');
        $day = isset($data->periode)?substr($data->periode,8,2):date('d');
		$tagihan_air = $this->db->select("
								'Air' as service,
								DATEADD(MONTH, -(service.jarak_periode_penggunaan), v_tagihan_air.periode) as periode_pemakaian,

								CASE
									WHEN v_tagihan_air.periode >= '$periode' THEN 1
									ELSE 0
								END as is_tagihan,
								0 as nilai_penalti,
								v_tagihan_air.total_tanpa_ppn,
								v_tagihan_air.tagihan_id,
								service.id as service_id,
								service.service_jenis_id as service_jenis_id,
								v_tagihan_air.periode,
								v_tagihan_air.status_tagihan,
								CASE 
									WHEN v_tagihan_air.status_tagihan = 4 THEN 
										isnull(v_tagihan_air.total,0) - iif(t_pembayaran.is_void=1,0,t_pembayaran_detail.nilai_tagihan)
									else isnull(v_tagihan_air.total,0)
								END as nilai_tagihan,
								CASE 
									WHEN v_tagihan_air.status_tagihan = 0 or v_tagihan_air.status_tagihan = 2 or v_tagihan_air.status_tagihan = 3 THEN isnull(v_tagihan_air.total,0) 
									ELSE 0 
								END - v_tagihan_air.total_tanpa_ppn
								as ppn,
								CASE 
									WHEN v_tagihan_air.status_tagihan = 0 or v_tagihan_air.status_tagihan = 2 or v_tagihan_air.status_tagihan = 3 THEN
										isnull(CASE
										WHEN service.denda_flag = 0 THEN 0
										WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
										WHEN DATEADD(MONTH,service.denda_selisih_bulan,v_tagihan_air.periode) > '$date' THEN 0
										
											ELSE
											CASE					
												WHEN v_tagihan_air.denda_jenis_service = 1 
													THEN v_tagihan_air.denda_nilai_service *
													CASE (DateDiff
																( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$date' ) 
																+ IIF(".$day.">=service.denda_tanggal_jt,1,0) 
															)
														WHEN 0 THEN 0
														ELSE 1
												END												WHEN v_tagihan_air.denda_jenis_service = 2 
													THEN v_tagihan_air.denda_nilai_service *
														(DateDiff
															( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$date' ) 
															+ IIF(".$day.">=service.denda_tanggal_jt,1,0) 
														)
												WHEN v_tagihan_air.denda_jenis_service = 3 
													THEN 
														(v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) 
														* (DateDiff( MONTH, v_tagihan_air.periode, '$date' ) 
														+ IIF(".$day.">=service.denda_tanggal_jt,1,0) )
											END 
										END,0) 
									ELSE 0
								END
								-
								CASE 
									WHEN v_tagihan_air.status_tagihan = 4 THEN isnull(iif(t_pembayaran.is_void=1,0,t_pembayaran_detail.nilai_denda),0)
									else 0
								END
								AS nilai_denda,
								isnull(v_tagihan_air.total,0)+
								isnull(CASE
									WHEN service.denda_flag = 0 THEN 0
									WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
									WHEN DATEADD(MONTH,service.denda_selisih_bulan,v_tagihan_air.periode) > '$date' THEN 0
									ELSE
									CASE
										WHEN v_tagihan_air.denda_jenis_service = 1 
											THEN v_tagihan_air.denda_nilai_service *
												CASE (DateDiff
															( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$date' ) 
															+ IIF(".$day.">=service.denda_tanggal_jt,1,0) 
														)
													WHEN 0 THEN 0
													ELSE 1
												END
										WHEN v_tagihan_air.denda_jenis_service = 2 
											THEN v_tagihan_air.denda_nilai_service *
												(DateDiff
													( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$date' ) 
													+ IIF(".$day.">=service.denda_tanggal_jt,1,0) 
												)
										WHEN v_tagihan_air.denda_jenis_service = 3 
											THEN (v_tagihan_air.denda_nilai_service * v_tagihan_air.total/ 100 ) 
											* (DateDiff( MONTH, v_tagihan_air.periode, '$date' ) 
											+ IIF(".$day.">=service.denda_tanggal_jt,1,0) )
									END 
								END,0) 
								- isnull(v_pemutihan.pemutihan_nilai_tagihan,0)
								- isnull(v_pemutihan.pemutihan_nilai_denda,0)
								AS total,
								isnull(v_pemutihan.pemutihan_nilai_tagihan,0) as view_pemutihan_nilai_tagihan,
								isnull(v_pemutihan.pemutihan_nilai_denda,0) as view_pemutihan_nilai_denda,
								isnull(iif(t_pembayaran.is_void=1,0,t_pembayaran_detail.sisa_tagihan),0) as belum_bayar,
								meter_awal,
								meter_akhir")		
			->from("v_tagihan_air")
			// ->where("v_tagihan_air.periode <= '$periode_now'")
			->join(
				"service",
				"service.project_id = $project_id
				AND service.service_jenis_id = 2
				AND service.active = 1
				AND service.delete = 0"
			)
			->join("v_pemutihan",
					"v_pemutihan.masa_akhir >= '$date'
					AND v_pemutihan.masa_awal <= '$date'
					AND v_pemutihan.periode = v_tagihan_air.periode 
					AND v_pemutihan.service_jenis_id = 2
					AND v_pemutihan.unit_id  = v_tagihan_air.unit_id",
					"LEFT")
			->join("t_tagihan_air",
					"t_tagihan_air.t_tagihan_id = v_tagihan_air.t_tagihan_id
					AND t_tagihan_air.unit_id =  v_tagihan_air.unit_id
					AND t_tagihan_air.periode =  v_tagihan_air.periode",
					"LEFT")
			->join("t_pencatatan_meter_air",
					"t_pencatatan_meter_air.unit_id = v_tagihan_air.unit_id
					AND t_pencatatan_meter_air.periode = v_tagihan_air.periode")
			->join("t_pembayaran_detail",
					"t_pembayaran_detail.tagihan_service_id = t_tagihan_air.id
					AND t_pembayaran_detail.service_id = service.id",
					"LEFT")
			->join("t_pembayaran",
					"t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id
					AND (
						(t_pembayaran.is_void = 0 and v_tagihan_air.status_tagihan in (0,4))
						or (t_pembayaran.is_void = 1 and v_tagihan_air.status_tagihan = 0)
						)",
					"LEFT");
        if(isset($data->unit_id))
                $tagihan_air = $tagihan_air->where_in("v_tagihan_air.unit_id",$data->unit_id);
        if(isset($data->status_tagihan))  
                $tagihan_air = $tagihan_air->where_in("v_tagihan_air.status_tagihan",$data->status_tagihan);
        else
                $tagihan_air = $tagihan_air->where("(v_tagihan_air.status_tagihan = 0 or v_tagihan_air.status_tagihan = 4  or v_tagihan_air.status_tagihan = 2 or v_tagihan_air.status_tagihan = 3)");

        $tagihan_air = $tagihan_air
                                    ->distinct()
                                    ->get();

        // var_dump($this->db->last_query());
        $tagihan_air = $this->db->from("(".$this->db->last_query().") as a")
                                        ->order_by("periode")
                                        ->get()->result();

        $tagihan_air_tmp = $tagihan_air;
        $tagihan_air = [];
        $view_pemutihan_nilai_tagihan = 0;
        $view_pemutihan_nilai_denda = 0;
        $sisa_nilai_tagihan = 0;
        $sisa_nilai_denda = 0;
        foreach ($tagihan_air_tmp as $k => $v) {
            // if($v->view_pemutihan_nilai_tagihan > 0 ){
			// 	$v->nilai_tagihan = ($v->total_tanpa_ppn - $v->view_pemutihan_nilai_tagihan) + (($v->total_tanpa_ppn - $v->view_pemutihan_nilai_tagihan) * 10 / 100);
			// }
			$v->sisa_nilai_tagihan = $v->nilai_tagihan - $v->view_pemutihan_nilai_tagihan;

			// if($v->view_pemutihan_nilai_denda > 0 ){
			// 	$v->nilai_denda = ($v->nilai_denda - $v->view_pemutihan_nilai_denda);
			// }
			$v->sisa_nilai_denda = $v->nilai_denda - $v->view_pemutihan_nilai_denda;

			array_push($tagihan_air,$v); 
        }
        return $tagihan_air;
    }
}
