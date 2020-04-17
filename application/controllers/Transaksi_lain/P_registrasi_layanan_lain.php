<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_registrasi_layanan_lain extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_registrasi_layanan_lain');
        $this->load->model('m_core');
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
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

    public function index()
    {
        $data = $this->m_registrasi_layanan_lain->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Layanan Lain', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_layanan_lain/view', [
            'data' => $data,
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $no_regis = $this->m_core->project()->code . "/RLL/" . date("Y") . "/" . $this->m_core->numberToRomanRepresentation($this->m_core->project()->id) . "/" . ($this->m_registrasi_layanan_lain->last_id() + 1);
        $dataUnit = $this->m_registrasi_layanan_lain->getUnit();
        // $dataService = $this->m_registrasi_layanan_lain->get_service_non_retribusi();
        $dataServiceLain = $this->m_registrasi_layanan_lain->get_paket_service();
        
        $databerlangganan = $this->m_registrasi_layanan_lain->get_parameter();
        $code = $this->m_registrasi_layanan_lain->get_parameter();
        // $    gihan = $this->m_core->project()->code . "/TLL/" . date("Y") . "/" . $this->m_core->numberToRomanRepresentation($this->m_core->project()->id) . "/" . ($this->m_registrasi_layanan_lain->last_id() + 1);
        
        $this->load->model('alert');
        $this->load->view('core/header');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Layanan Lain > Registrasi', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/registrasi_layanan_lain/add', [
            'dataUnit' => $dataUnit,
            'dataServiceLain' => $dataServiceLain,
            'no_regis' => $no_regis,
            'data_berlangganan' => $databerlangganan,
            'code' => $this->input->get('code'),
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function edit()
    {
        $data = $this->m_registrasi_layanan_lain->get($this->input->get('id'));
        $data_detail = $this->m_registrasi_layanan_lain->get_detail($this->input->get('id'));
        $dataServiceLain = $this->m_registrasi_layanan_lain->get_paket_service();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Layanan Lain > Edit', 'subTitle' => 'Edit']);
        $this->load->view('proyek/transaksi_lain/registrasi_layanan_lain/edit', [
            "data" => $data,
            "data_detail" => $data_detail,
            'dataServiceLain' => $dataServiceLain,
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function ajax_save(){
        echo json_encode($this->m_registrasi_layanan_lain->save($this->input->post()));
    }
    public function save()
    {
        $data = [
            'no_unit' => $this->input->post('unit'),
            'name' => $this->input->post('pemilik_nama'),
            'nomor_registrasi' => $this->input->post('nomor_registrasi'),
            // 'status_bayar_registrasi' => 0,
            'service_id' => $this->input->post('service_id[]'),
            'paket_service_id' => $this->input->post('paket_service_id[]'),
            'periode_awal' => $this->input->post('periode_awal[]'),
            'periode_akhir' => $this->input->post('periode_akhir[]'),
            'jumlah_periode' => $this->input->post('jumlah_periode[]'),
            'kuantitas' => $this->input->post('kuantitas[]'),
            'satuan' => $this->input->post('satuan[]'),
            'harga_satuan' => $this->input->post('harga_satuan[]'),
            'harga_bulanan' => $this->input->post('harga_bulanan'),
            'biaya_registrasi' => $this->input->post('biaya_registrasi[]'),
            'harga_bulan_pertama' => $this->input->post('harga_bulan_pertama[]'),
            'status_berlangganan' => $this->input->post('status_berlangganan[]'),
            'minimal_langganan' => $this->input->post('minimal_langganan[]'),
            'total' => $this->input->post('total[]'),
            'biaya_pemasangan_aktif' => $this->input->post('biaya_pemasangan[]')
        ];        
        $status = $this->m_registrasi_layanan_lain->save($data);
        $this->load->model('alert');
        $data_paket = $this->m_registrasi_layanan_lain->getDetailService($this->input->get("id"));
        $data_detail_service = $this->m_registrasi_layanan_lain->getDetailService($this->input->get("id"));
        $dataUnit = $this->m_registrasi_layanan_lain->getUnit();
        $dataService = $this->m_registrasi_layanan_lain->get_service_non_retribusi();
        $dataAll  = $this->m_registrasi_layanan_lain->getAll();

        // $data_tagihan =  $this->m_registrasi_layanan_lain->getDataTagihan();
        // $this ->data['hasil'] = $this->m_registrasi_layanan_lain->getAll('t_layanan_lain_registrasi');

        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Layanan Lain > Registrasi', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_layanan_lain/view', [
            'data' => $dataAll,
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        }
    }
    
    public function cetak()
    {
        $dataUnit = $this->m_registrasi_layanan_lain->getUnitCetak();
        $dataService = $this->m_registrasi_layanan_lain->get_service_non_retribusi();
        $data_detail_service = $this->m_registrasi_layanan_lain->getDetailServiceCetak($this->input->get('id'));
        $data_paket_service = $this->m_registrasi_layanan_lain->get_paket_service_cetak($this->input->get('id'));
        // echo("<pre>");
        // print_r($data_detail_service);
        // echo("</pre>");
        $diff = abs(strtotime($data_detail_service->periode_awal) - strtotime($data_detail_service->periode_akhir)); 
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $total_tagihan = $months + ($years * 12) + 1 < ($data_detail_service->minimal_langganan) ? ((($data_detail_service->biaya_satuan) * ($data_detail_service->kuantitas)) + ($data_detail_service->biaya_registrasi)) * ($months + ($years * 12) + 1)  : (((($data_detail_service->harga_satuan) * ($data_detail_service->kuantitas))) * ($months + ($years * 12) + 1)) + ($data_detail_service->biaya_registrasi);
        $terbilang = $this->terbilang(str_replace(",","",$total_tagihan));
        
        
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Layanan Lain > Edit', 'subTitle' => 'Edit']);
        $this->pdf->load_view('proyek/cetakan/kwitansi_service', [
            'dataUnit' => $dataUnit,
            'dataService' => $dataService,
            'no_regis' => $no_regis,
            'unit_id' => $this->input->get('unit_id'),
            'data_detail_service' => $data_detail_service,
            'data_paket_service' => $data_paket_service,
            'tanggal' => date("Y-m-d"),
            'total_tagihan'=>$total_tagihan,
            'terbilang' => strtoupper($terbilang)
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function get_ajax_unit()
	{
		$unit = 
			$this->db->select("unit.id, CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) as text")
			->from('unit')
			->join(
				'blok',
				'blok.id = unit.blok_id'
			)
			->join(
				'kawasan',
				'kawasan.id = blok.kawasan_id'
			)
			->join(
				'customer',
				'customer.id = unit.pemilik_customer_id'
			)
			->where('unit.project_id', $GLOBALS['project']->id)
			->where("CONCAT(kawasan.name,'-',blok.name,'/',unit.no_unit,'-',customer.name) like '%" . $this->input->get('data') . "%'")
			->limit(10)
			->get()->result();
		echo json_encode($unit);
	}
    // public function edit()
    // {
    //     $status = 0;
    //     if ($this->input->post('code')) {
    //         $this->load->model('alert');

    //         $status = $this->m_registrasi_layanan_lain->edit([
    //             'id' => $this->input->get('id'),
    //             // 'code' => $this->input->post('code'),
    //             // 'jenis_pembayaran' => $this->input->post('jenis_pembayaran'),
    //             // 'biaya_admin' => $this->input->post('biaya_admin'),
    //             // 'coa' => $this->input->post('coa'),
    //             // 'keterangan' => $this->input->post('keterangan'),
    //             'active' => $this->input->post('status'),
    //         ]);
    //         $this->alert->css();
    //     }

    //     if ($this->m_registrasi_layanan_lain->cek($this->input->get('id'))) {
    //         // $dataCaraPembayaran = $this->m_cara_pembayaran->get();
    //         // $dataCaraPembayaranSelect = $this->m_cara_pembayaran->getSelect($this->input->get('id'));
    //         // $dataPTCOA = $this->m_cara_pembayaran->get_all_pt_coa();
    //         $this->load->model('m_log');
    //         // $data = $this->m_log->get('cara_pembayaran', $this->input->get('id'));
    //         $this->load->view('core/header');
    //         $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
    //         $this->load->view('core/top_bar');
    //         $this->load->view('core/body_header', ['title' => 'Master > Accounting > Cara Pembayaran', 'subTitle' => 'Edit']);
    //         $this->load->view('proyek/transaksi_lain/P_registrasi_layanan_lain/edit');
    //         $this->load->view('core/body_footer');
    //         $this->load->view('core/footer');
    //     } else {
    //         redirect(site_url() . '/P_registrasi_layanan_lain');
    //     }

    //     if ($status == 'success') {
    //         $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
    //     } elseif ($status == 'double') {
    //         $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
    //     }
    // }
    public function delete()
    {
        $this->load->model('alert');
        
        $status = $this->m_registrasi_layanan_lain->delete([
                'id' => $this->input->get('id')
        ]);
            
        $this->alert->css();
        $data = $this->m_registrasi_layanan_lain->get();
        $data = $this->m_registrasi_layanan_lain->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Layanan Lain > Registrasi', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_layanan_lain/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } 
    }

    public function updateStatus(){
        
        $this->load->model('alert');
        
        $status = $this->m_registrasi_layanan_lain->updateStatus([
                'id' => $this->input->get('id')
        ]);
            
        $this->alert->css();
        $data = $this->m_registrasi_layanan_lain->get();
        $data = $this->m_registrasi_layanan_lain->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Layanan Lain > Registrasi', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_layanan_lain/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        }
    }

    public function get_paket()
    {
        echo json_encode($this->m_registrasi_layanan_lain->get_paket($this->input->post('id')));
    }
    public function get_harga_paket()
    {
        echo json_encode($this->m_registrasi_layanan_lain->get_harga_paket($this->input->post('id')));
    }
    public function get_pemilik_penghuni()
    {
        echo json_encode($this->m_registrasi_layanan_lain->get_pemilik_penghuni($this->input->post('id')));
    }
    public function get_paket_service()
    {
        echo json_encode($this->m_registrasi_layanan_lain->get_paket_service($this->input->post('id')));
    }
    public function get_info_paket_service()
    {
        echo json_encode($this->m_registrasi_layanan_lain->get_info_paket_service($this->input->post('id')));
    }
    public function get_info_paketservice()
    {
        echo json_encode($this->m_registrasi_layanan_lain->get_info_paketservice($this->input->post('id')));
    }
    public function getDetailService()
    {
        echo json_encode($this->m_registrasi_layanan_lain->getDetailService($this->input->post('id')));
    }
    public function get_parameter()
    {
        echo json_encode($this->m_registrasi_layanan_lain->get_parameter($this->input->post('id')));
    }
    public function getparameter()
    {
        echo json_encode($this->m_registrasi_layanan_lain->getparameter($this->input->post('id')));
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
}
