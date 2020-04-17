<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_pembayaran_xendit extends CI_Model
{
    function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    public function get_no_referensi(){
        $project = $this->m_core->project();
        $no_referensi_tmp = $project->code."/".$this->numberToRomanRepresentation((int)date('m'))."/".date('Y')."/";
        $no = $this->db
                                ->select("")
                                ->from("kwitansi_referensi")
                                ->where("no_referensi LIKE '%$no_referensi_tmp%'")
                                ->where("project_id",$project->id)
                                ->order_by("no_referensi DESC")
                                ->limit(1)
                                ->get()->row();
        if($no){
            $no = $no->no_referensi;
            $no = ((int)substr($no,strripos($no,'/')+1,8));
            $no = $no + 1;
        }
        else    $no = 1;
        
        $no             = str_pad($no,8,'0',STR_PAD_LEFT);
        $no_referensi   = $no_referensi_tmp.$no;

        return $no_referensi;
    }

    public function ajax_get_unit($data){
        $project = $this->m_core->project();
        $unit = $this->db   ->select("unit.id, CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) as text")
                            ->from('unit')
                            ->join('blok',
                                    'blok.id = unit.blok_id')
							->join('kawasan',
									'kawasan.id = blok.kawasan_id')
							->join('customer',
									'customer.id = unit.pemilik_customer_id')
							->where('unit.project_id',$project->id)
							->where("CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) like '%".$data."%'")
							->get()->result();
        return $unit;
    }
    public function get_all_unit()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
                SELECT 
                    unit.id as unit_id,
                    unit.no_unit,
                    blok.name as blok,
                    kawasan.name as kawasan,
                    pemilik.name as pemilik
                FROM unit
                JOIN blok
                    ON blok.id = unit.blok_id
                JOIN kawasan
                    ON kawasan.id = blok.kawasan_id
                JOIN customer as pemilik
                    ON pemilik.id = unit.pemilik_customer_id
                WHERE unit.project_id = $project->id
        ");
        $result = $query->result();
        
        return $result;
    }
    public function ajax_get_tagihan($unit_id){
        $dataAir = $this->db->select("periode,'Air' as Service, total_tagihan, 0 as denda, 0 as penalty, sudah_dibayar")
                            ->where("unit_id",$unit_id)
                            ->where("status_bayar_flag !=","1")
                            ->order_by("periode")
                            ->get("v_tagihan_air")
                            ->result();
        $dataPL = $this->db ->select("periode,'PL' as Service, total_tagihan, 0 as denda, 0 as penalty, sudah_dibayar")
                            ->where("unit_id",$unit_id)
                            ->where("status_bayar_flag !=","1")
                            ->get("v_tagihan_lingkungan")
                            ->order_by("periode")
                            ->result(); 
        $data = (object)array_merge((array)$dataAir,(array)$dataPL);
        return $data;
    }
    public function save($bayarTMP,$bayar_depositTMP,$unit_id,$cara_pembayaran_id,$project_id,$user_id,$biaya_admin,$dateForm=null){
        $from = "ems.dbo.";

		if($dateForm){
			$periode_now = substr($dateForm,6,4)."-".substr($dateForm,3,2)."-01";	
			$datetime = substr($dateForm,6,4)."-".substr($dateForm,3,2)."-".substr($dateForm,0,2).date(" H:i:s.000");
		}else{
			$periode_now = date("Y-m-01");
			$datetime = date("Y-m-d H:i:s.000");
		}
		$dateTimeNow = date("Y-m-d H:i:s.000");
		// $periode_now = substr($dateForm,6,4)."-".substr($dateForm,3,2)."-01";

		// $periode_now = date("Y-m-01");
		
		$periode_pemakaian = date("Y-m-01", strtotime("-1 Months"));
		
		$this->db->trans_start();
		// $datetime = date("Y-m-d H:i:s.000");
		$pembayaran = (object) [];
		$pembayaran->unit_id = $unit_id;
		$pembayaran->cara_pembayaran_id = $cara_pembayaran_id;
		$pembayaran->jenis_cara_pembayaran_id = $this->db->select("jenis_cara_pembayaran_id")
															->from($from."cara_pembayaran")
															->where("id",$cara_pembayaran_id)->get()->row()->jenis_cara_pembayaran_id;
		// var_dump($this->db->last_query());
		$pembayaran->code_pembayaran = "Test";
		$pembayaran->tgl_document = $dateTimeNow;
		$pembayaran->tgl_tambah = $dateTimeNow;
		$pembayaran->user_id = $user_id;
		$pembayaran->delete = 0;
		$pembayaran->tgl_bayar = $datetime;
		$this->db->insert($from."t_pembayaran", $pembayaran);
		$id_pembayaran = $this->db->insert_id();
		$nilai_ppn= $this->db->select("value")->from($from."parameter_project")
								->where("project_id",$project_id)
								->where("code","PPN")->get()->row()->value;
		$pembayaran_detail = [];

		$bayar = [];
		$kwitansi_referensi = (object) [];
		$kwitansi_referensi->project_id = $project_id;
		$service_id_tmp = 0;
		// echo("test");
		if ($bayarTMP) {
			foreach ($bayarTMP as $k=>$v) {
                // var_dump($v);
                $tmp = explode("|", $v);
                // var_dump($tmp);

				if ($tmp[1] == 1) {
					$cek = $this->db->select("
							isnull(CASE
								WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda +total
								WHEN v_tagihan_lingkungan.periode >= '$periode_now' THEN 0+total
								ELSE
								CASE
									WHEN service.denda_flag = 0 THEN 0+total
									WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN
										v_tagihan_lingkungan.denda_nilai_service +total
									WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN
										v_tagihan_lingkungan.denda_nilai_service * (DateDiff( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_lingkungan.periode), '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )+total
									WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN
										( v_tagihan_lingkungan.denda_nilai_service* v_tagihan_lingkungan.total/ 100 ) * DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) +total
								END 
								END,0) as total
							")
						->from($from."v_tagihan_lingkungan")
						->join(
							$from."service",
							"service.project_id = $project_id
							AND service.service_jenis_id = 1
							AND service.active = 1
							AND service.delete = 0"
						)
						->where("tagihan_id", $tmp[2])->get()->row()->total;
				} else if ($tmp[1] == 2) {
					$cek = $this->db->select("
							isnull(CASE
								WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda +total
								WHEN v_tagihan_air.periode >= '$periode_now' THEN 0+total
								ELSE
								CASE
									WHEN service.denda_flag = 0 THEN 0+total
									WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service 
									WHEN v_tagihan_air.denda_jenis_service = 2 THEN 
										v_tagihan_air.denda_nilai_service *(DateDiff( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )+total
									WHEN v_tagihan_air.denda_jenis_service = 3 THEN (v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) *(DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) ) + v_tagihan_air.total
								END 
								END,0) as total
							")
						->from($from."v_tagihan_air")
						->join(
							$from."service",
							"service.project_id = $project_id
							AND service.service_jenis_id = 2
							AND service.active = 1
							AND service.delete = 0"
						)
						->where("tagihan_id", $tmp[2])->get()->row()->total;
				}
                // var_dump($cek);
                // var_dump($tmp[3] + $tmp[4] + $tmp[5]);
                // var_dump($tmp[3]."|".$tmp[4]."|".$tmp[5]);
                
                
				// if ($cek == $tmp[3] + $tmp[4] + $tmp[5] ) {

					if(($k == 0 || $service_id_tmp != $tmp[0])){
						// $kwitansi_referensi->no_referensi = $this->m_pembayaran->get_no_referensi();
						
						$this->db->insert($from."kwitansi_referensi", $kwitansi_referensi);
						$kwitansi_referensi_id = $this->db->insert_id();
					}
						array_push($pembayaran_detail, [
							"t_pembayaran_id" => $id_pembayaran,
							"nilai_tagihan" => $tmp[3],
							"nilai_penalti" => 0,
							"bayar" => $tmp[3] + $tmp[4] + $tmp[5] - ($tmp[6]),//+$tmp[7]),
							"bayar_deposit" => 0,
							"service_id" => $tmp[0],
							"tagihan_service_id" => $tmp[2],
							"nilai_denda" => $tmp[4],
							"kwitansi_referensi_id" => $kwitansi_referensi_id,
							"diskon_id" => 0, //nanti di isi
							"nilai_diskon" => 0, // nanti di isi
							"nilai_tagihan_pemutihan" => $tmp[6],
							// "nilai_denda_pemutihan" => $tmp[7],
							"nilai_biaya_admin_cara_pembayaran" => $biaya_admin,
							"nilai_ppn" => $this->db->select("isnull(ppn_flag,0) as ppn_flag")->from($from."service")->where("id",$tmp[0])->get()->row()->ppn_flag?$nilai_ppn:0
							]);
					
					$this->db->insert($from."t_pembayaran_detail", end($pembayaran_detail));

					if ($tmp[1] == 1) {
						$this->db->set('status_tagihan', 1);
						$this->db->where('id', $tmp[2]);
						$this->db->update($from."t_tagihan_lingkungan");
					} elseif ($tmp[1] == 2) {
						$this->db->set('status_tagihan', 1);
						$this->db->where('id', $tmp[2]);
						$this->db->update($from."t_tagihan_air");
					}
				// }
				$service_id_tmp = $tmp[0];
			}
		}
		if ($bayar_depositTMP) {
			foreach ($bayar_depositTMP as $v) {
				$tmp = explode("|", $v);
				if ($tmp[1] == 1) {
					$cek = $this->db->select("isnull(CASE
								WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda +total
								WHEN v_tagihan_lingkungan.periode >= '$periode_now' THEN 0+total
								ELSE
								CASE
									WHEN service.denda_flag = 0 THEN 0+total
									WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN
									v_tagihan_lingkungan.denda_nilai_service +total
									WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN v_tagihan_lingkungan.denda_nilai_service *(DateDiff( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_lingkungan.periode), '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )+total
									WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN
									( v_tagihan_lingkungan.denda_nilai_service* v_tagihan_lingkungan.total/ 100 ) * DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) +total
								END 
								END,0) as total
							")
						->from($from."v_tagihan_lingkungan")
						->join($from."service",
							"service.project_id = $project_id
							AND service.service_jenis_id = 2
							AND service.active = 1
							AND service.delete = 0"
						)
                        ->where("tagihan_id", $tmp[2])
                        ->where("status_tagihan",0)->get()->row()->total;
				} else if ($tmp[1] == 2) {
					$cek = $this->db->select("isnull(CASE
								WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda +total
								WHEN v_tagihan_air.periode >= '$periode_now' THEN 0+total
								ELSE
								CASE
									WHEN service.denda_flag = 0 THEN 0+total
									WHEN v_tagihan_air.denda_jenis_service = 1 THEN
									v_tagihan_air.denda_nilai_service +total
									WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service *(DateDiff( MONTH, DATEADD(month,service.denda_selisih_bulan,v_tagihan_air.periode), '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )+total
									WHEN v_tagihan_air.denda_jenis_service = 3 THEN
									( v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) * DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) +total
								END 
								END,0) as total
							")
						->from($from."v_tagihan_air")
                        ->where("tagihan_id", $tmp[2])
                        ->where("status_tagihan",0)->get()->row()->total;
				}
				if ($cek == $tmp[3] + $tmp[4] + $tmp[5]) {
					// $kwitansi_referensi->no_referensi = $this->m_pembayaran->get_no_referensi();
					$this->db->insert($from."kwitansi_referensi", $kwitansi_referensi);
					$kwitansi_referensi_id = $this->db->insert_id();
					array_push($pembayaran_detail, [
						"t_pembayaran_id" => $id_pembayaran,
						"nilai_tagihan" => $tmp[3],
						"nilai_penalti" => $tmp[4],
						"bayar" => 0,
						"bayar_deposit" => $tmp[3] + $tmp[4] + $tmp[5] - ($tmp[6]+$tmp[7]),
						"service_id" => $tmp[0],
						"tagihan_service_id" => $tmp[2],
						"nilai_denda" => $tmp[4],
						"kwitansi_referensi_id" => $kwitansi_referensi_id,
						"diskon_id" => 0, //nanti di isi
						"nilai_diskon" => 0, // nanti di isi
						"nilai_tagihan_pemutihan" => $tmp[6],
						"nilai_denda_pemutihan" => $tmp[7],
						"nilai_ppn" => $this->db->select("isnull(ppn_flag,0)")->from($from."service")->where("id",$tmp[0])->get()->row()->ppn_flag?$nilai_ppn:0
						]);
					$this->db->insert($from."t_pembayaran_detail", end($pembayaran_detail));
					// \\01-10-2019
					if(!$tmp[2]){
						return false;
					}
					// \\01-10-2019
					if ($tmp[1] == 1) {
						$this->db->set('status_tagihan', 1);
						$this->db->where('id', $tmp[2]);
						$this->db->update($from."t_tagihan_lingkungan");
					} elseif ($tmp[1] == 2) {
						$this->db->set('status_tagihan', 1);
						$this->db->where('id', $tmp[2]);
						$this->db->update($from."t_tagihan_air");
					}
					$customer_id = $this->db->select("pemilik_customer_id as id")->from($from."unit")->where("id", $unit_id)->get()->row()->id;
					$this->db->insert($from."t_deposit", [
						"project_id" => $project_id,
						"customer_id" => $customer_id
					]);
					$deposit_id = $this->db->insert_id();

					// $kwitansi_referensi->no_referensi = $this->m_pembayaran->get_no_referensi();
					$this->db->insert($from."kwitansi_referensi", $kwitansi_referensi);
					$kwitansi_referensi_id = $this->db->insert_id();

					$this->db->insert($from."t_deposit_detail", [
						"t_deposit_id" => $deposit_id,
						"cara_pembayaran_id" => 0,
						"kwitansi_referensi_id" => $kwitansi_referensi_id,
						"nilai" => -($tmp[3] + $tmp[4] + $tmp[5]),
						"tgl_document" => $dateTimeNow,
						"tgl_tambah" => $dateTimeNow,
						"user_id" => $user_id,
						"description" => "Pembayaran Tagihan",
						"delete " => 0,
					]);
				}
			}
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
            return false;
		} else {
			$this->db->trans_commit();
            return true;
		}

    }
}
