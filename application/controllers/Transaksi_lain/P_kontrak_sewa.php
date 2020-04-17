<?php
defined('BASEPATH') or exit('No direct script access allowed');
class P_kontrak_sewa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_unit');
        $this->load->model('m_core');
        $this->load->model('m_login');
        $this->load->model('transaksi_lain/m_kontrak_sewa');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();
    }

    public function index()
    {
        $data = $this->m_kontrak_sewa->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Sewa Properti > Kontrak Sewa', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/kontrak_sewa/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
	}
    
    public function add()
    {
        $dataUnit = $this->m_kontrak_sewa->getUnit();
        $dataCustomer = $this->m_kontrak_sewa->getCustomer();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Sewa Properti > Kontrak Sewa', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/kontrak_sewa/add', ['dataUnit' => $dataUnit, 'dataCustomer'=>$dataCustomer]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function lihat_unit()
    {
        $pilih_unit = $this->input->post('pilih_unit');

        echo json_encode($this->m_kontrak_sewa->getUnit2($pilih_unit));
	}
	
	public function save()
	{	
		
		$status = $this->m_kontrak_sewa->save([
			
			'unit_id'                                => $this->input->post('pilih_unit'),
			'tanggal_document'						 => $this->input->post('tanggal_document'),
			'customer_id'							 => $this->input->post('penyewa'),
			'jenis_bayar'							 => $this->input->post('jenis_bayar'),
			'jenis_sewa'							 => $this->input->post('jenis_sewa'),
			'alamat'								 => $this->input->post('alamat'),
			'nohp'									 => $this->input->post('nohp'),
			'biaya_sewa'							 => $this->input->post('biaya_sewa'),
			'deposit_sewa'							 => $this->input->post('deposit_sewa'),
			'tanggal_sewa'							 => $this->input->post('tanggal_sewa'),
			'batas_sewa'							 => $this->input->post('batas_sewa'),
			
			
			'penghuni'                             	 => $this->input->post('penghuni[]'),
            'alamatpenghuni'                         => $this->input->post('alamatpenghuni[]'),
            'no_hp'                    	     	     => $this->input->post('no_hp[]'),
			'pekerjaan'                            	 => $this->input->post('pekerjaan[]'),
			
			
			
				
		]);
		
		
		$this->load->model('alert');
		$this->load->model('m_range_lingkungan');
		$dataRangeLingkungan = $this->m_range_lingkungan->get();
		$this->load->view('core/header');
		$this->alert->css();
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Range > Range Lingkungan', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/range_lingkungan/add',['dataRangelingkungan' => $dataRangeLingkungan]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Tambah','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal','text'=>'Data Inputan suda Ada','type'=>'danger']);
					
	}

    public function get_ajax_unit_detail(){
		$project = $this->m_core->project();

		$periode_now = date("Y-m-01");
		$periode_pemakaian = date("Y-m-01",strtotime("-1 Months"));
		$unit_id = $this->input->get('unit_id');
		$unit = $this->db->select("
									pemilik.id as pemilik_id,
									pemilik.name as pemilik, 
									penghuni.name as penghuni,
									unit.luas_bangunan,
									isnull(unit.luas_taman,0) as luas_taman,
									unit.luas_tanah,
									concat(jenis_golongan.code,' - ',jenis_golongan.description) as golongan,
									'Rumah' as purpose_use,
									'-' as type_unit,
									convert(varchar,tgl_st,103) as tgl_st
									")
							->from('unit')
							->join('customer as pemilik','pemilik.id = unit.pemilik_customer_id')
							->join('customer as penghuni','penghuni.id = unit.penghuni_customer_id')
							->join('jenis_golongan','jenis_golongan.id = unit.gol_id')
							
                            ->where('unit.id',$unit_id)
							->get()->row();

		// WHEN v_tagihan_air.periode > '$periode_now' THEN 0 -> kalau periode nya lebih dari periode saat ini, denda = 0

		$tagihan_air = $this->db->select("
								'Air' as service,
								v_tagihan_air.periode,
								0 as nilai_penalti,

								(v_tagihan_air.total - v_tagihan_air.total_tanpa_ppn) as ppn,
								DATEADD(MONTH, ((-1)*(CONVERT(INT,service.denda_selisih_bulan))), v_tagihan_air.periode) as periode_pemakaian,
								v_tagihan_air.total_tanpa_ppn,
								isnull(CASE 
								WHEN DATEADD(MONTH, 1,v_tagihan_air.periode) >= '$periode_now' THEN v_tagihan_air.total
								ELSE 0
								END,0) as nilai_tagihan,
								isnull(CASE 
									WHEN DATEADD(MONTH, 1,v_tagihan_air.periode) < '$periode_now' THEN v_tagihan_air.total
									ELSE 0
								END,0) as nilai_tunggakan,
								isnull(CASE
									WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
									WHEN v_tagihan_air.periode >= '$periode_now' THEN 0
									ELSE
									CASE
										WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service 
										WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service *(DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0))
										WHEN v_tagihan_air.denda_jenis_service = 3 THEN (v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) *(DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
									END 
									END,0) AS nilai_denda,
								isnull(CASE 
									WHEN v_tagihan_air.periode = '$periode_now' THEN v_tagihan_air.total
									ELSE 0
								END,0) +
								isnull(CASE 
									WHEN v_tagihan_air.periode < '$periode_now' THEN v_tagihan_air.total
									ELSE 0
								END,0) +
								isnull(CASE
									WHEN v_tagihan_air.nilai_denda_flag = 1 THEN v_tagihan_air.nilai_denda 
									WHEN v_tagihan_air.periode > '$periode_now' THEN 0
									ELSE
									CASE
										WHEN v_tagihan_air.denda_jenis_service = 1 THEN v_tagihan_air.denda_nilai_service 
										WHEN v_tagihan_air.denda_jenis_service = 2 THEN v_tagihan_air.denda_nilai_service* (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
										WHEN v_tagihan_air.denda_jenis_service = 3 THEN ( v_tagihan_air.denda_nilai_service* v_tagihan_air.total/ 100 ) * (DateDiff( MONTH, v_tagihan_air.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
									END 
								END,0) AS total")
							->from("v_tagihan_air")
							->join("service",
									"service.service_jenis_id = 2
									AND service.project_id = $project->id")
							// ->where("v_tagihan_air.periode <= '$periode_now'")
							->where("v_tagihan_air.status_tagihan = 0")
							->where("v_tagihan_air.unit_id = $unit_id")
							->get()->result();
							// echo($periode_now);
			$tagihan_lingkungan = $this->db->select("
								'lingkungan' as service,
								v_tagihan_lingkungan.total_tanpa_ppn,
								v_tagihan_lingkungan.ppn,
								v_tagihan_lingkungan.periode,
								v_tagihan_lingkungan.tagihan_id,
								1 as service_jenis_id,
								0 as nilai_penalti,
								isnull(CASE 
									WHEN v_tagihan_lingkungan.periode >= '$periode_now' THEN v_tagihan_lingkungan.total
									ELSE 0
								END,0) as nilai_tagihan,
								isnull(CASE 
									WHEN v_tagihan_lingkungan.periode < '$periode_now' THEN v_tagihan_lingkungan.total
									ELSE 0
								END,0) as nilai_tunggakan,
								isnull(CASE
									WHEN v_tagihan_lingkungan.periode <= unit_lingkungan.tgl_mulai_denda THEN 0 
									WHEN v_tagihan_lingkungan.nilai_denda_flag = 1 THEN v_tagihan_lingkungan.nilai_denda 
									WHEN v_tagihan_lingkungan.periode > '$periode_now' THEN 0
									ELSE
									CASE
										WHEN v_tagihan_lingkungan.denda_jenis_service = 1 THEN v_tagihan_lingkungan.denda_nilai_service 
										WHEN v_tagihan_lingkungan.denda_jenis_service = 2 THEN v_tagihan_lingkungan.denda_nilai_service * (DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
										WHEN v_tagihan_lingkungan.denda_jenis_service = 3 THEN ( v_tagihan_lingkungan.denda_nilai_service* v_tagihan_lingkungan.total/ 100 ) * (DateDiff( MONTH, v_tagihan_lingkungan.periode, '$periode_now' ) + IIF(".date("d").">=service.denda_tanggal_jt,1,0) )
									END 
									END,0) AS nilai_denda
							")
							->from("v_tagihan_lingkungan")
							->join("service",
									"service.service_jenis_id = 1
									AND service.project_id = $project->id")
							->join("unit_lingkungan",
									"unit_lingkungan.unit_id = $unit_id")
							// ->where("v_tagihan_lingkungan.periode <= '$periode_now'")
							->where("v_tagihan_lingkungan.status_tagihan = 0")
							->where("v_tagihan_lingkungan.unit_id = $unit_id")
							->get()->result();
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
									->join("t_pembayaran_detail",
											"t_pembayaran_detail.t_pembayaran_id = t_pembayaran.id")
									->join("service",
											"service.id = t_pembayaran_detail.service_id")
									->join("service_jenis",
											"service_jenis.id = service.service_jenis_id")
									// ->where("no_kwitansi is null")
									->join("kwitansi_referensi",
											"kwitansi_referensi.id = t_pembayaran_detail.kwitansi_referensi_id")
									->where("unit_id",$unit_id)
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
									->join("t_deposit_detail",
											"t_deposit_detail.t_deposit_id = t_deposit.id
											AND t_deposit_detail.nilai > 0")
									->join("kwitansi_referensi",
											"kwitansi_referensi.id = t_deposit_detail.kwitansi_referensi_id")
									->where("t_deposit.customer_id",$unit->pemilik_id)
									->get()->result();

		$jumlah_tagihan_service = 0;
		$jumlah_tunggakan_bulan = 0;
		$jumlah_tunggakan = 0;
		$jumlah_ppn = 0;
		$jumlah_denda = 0;
		$jumlah_penalti = 0;
		$jumlah_tagihan = 0;

		foreach ($tagihan_air as $v) {
			$jumlah_denda+=$v->nilai_denda;
			$jumlah_ppn+=$v->ppn;
			$jumlah_tagihan+=$v->total_tanpa_ppn;

			if($v->nilai_denda>0){
				$jumlah_tunggakan_bulan++;
				$jumlah_tunggakan+=$v->nilai_tunggakan;
				}		
		}

		foreach ($tagihan_lingkungan as $k=> $v) {
			$jumlah_denda+=$v->nilai_denda;
			$jumlah_ppn+=$v->ppn;
			$jumlah_tagihan+=$v->total_tanpa_ppn;
			if($v->nilai_denda>0){
				$jumlah_tunggakan_bulan++;
				$jumlah_tunggakan+=$v->nilai_tunggakan;
				

			}
		}
		if($tagihan_air)
			$jumlah_tagihan_service++;
		if($tagihan_lingkungan)
			$jumlah_tagihan_service++;

		$unit->jumlah_tagihan_service = $jumlah_tagihan_service;
		$unit->tagihan_air = $tagihan_air;
		$unit->tagihan_lingkungan = $tagihan_lingkungan;


		$unit->jumlah_tagihan_service 	= $jumlah_tagihan_service;
		$unit->jumlah_tunggakan_bulan 	= $jumlah_tunggakan_bulan;
		$unit->jumlah_tunggakan 		= $jumlah_tunggakan;
		
		$unit->jumlah_ppn 				= $jumlah_ppn;
		$unit->jumlah_denda 			= $jumlah_denda?$jumlah_denda:0;
		$unit->jumlah_penalti 			= $jumlah_penalti;
		$unit->jumlah_tagihan 			= $jumlah_tagihan;
		$unit->jumlah_semua 			= $jumlah_denda+$jumlah_penalti+$jumlah_tagihan+$jumlah_ppn;
		$unit->kwitansi 				= $kwitansi_per_service;
		$unit->kwitansi_deposit 		= $kwitansi_deposit;
		echo json_encode($unit);
		// echo json_encode($tagihan_air);
		// echo("<pre>");
		// print_r($unit);
		// echo("</pre>");
		
	}
}
?>