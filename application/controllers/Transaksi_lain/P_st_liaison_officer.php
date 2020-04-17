<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_st_liaison_officer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_st_liaison_officer');
		$this->load->model('transaksi/m_deposit');
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
    }

    public function index()
    {
        $data = $this->m_st_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Serah Terima', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/st_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $dataUnit = $this->m_st_liaison_officer->getUnit();
        $dataKategori = $this->m_st_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_st_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_st_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_st_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_st_liaison_officer->get_paket();
        $dataItemCharge = $this->m_st_liaison_officer->get_item_charge($this->input->get('id'));
        $dataItemChargeTambah = $this->m_st_liaison_officer->get_item_charge_tambah($this->input->get('id'));
        $this->load->model('m_log');
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Serah Terima ', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/st_liaison_officer/add', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket, 'dataItemCharge'=>$dataItemCharge, 'dataItemChargeTambah'=>$dataItemChargeTambah]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function buktiSt($id=0)
    {
        $this->load->model('Cetakan/m_konfirmasi_tagihan');
        $this->load->model('m_parameter_project');
        $this->load->model('m_log');
        $this->load->model('m_log');
        $this->load->library('pdf');
        $dataUnit = $this->m_st_liaison_officer->getUnit();
        $dataKategori = $this->m_st_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_st_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_st_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_st_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_st_liaison_officer->get_paket();
        $dataItemCharge = $this->m_st_liaison_officer->get_item_charge($this->input->get('id'));
        $dataItemChargeTambah = $this->m_st_liaison_officer->get_item_charge_tambah($this->input->get('id'));
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));        
        $dataRegistrasiLOISelect = $this->m_st_liaison_officer->getSelect($this->input->get('id'));
        $tanggal = (date("d")." ".$this->bln_indo(date("m"))." ".date("Y"));
        $terbilang = $this->terbilang(str_replace(",","",$dataRegistrasiLOISelect->total));
        $unit= $this->m_konfirmasi_tagihan->get_unit($dataRegistrasiLoiSelect->unit_id);
        $ttd = $this->m_parameter_project->get($this->m_core->project()->id,"ttd_konfirmasi_tagihan");
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "dokumentvi.pdf";
        $this->pdf->load_view('proyek/cetakan/dokumenSt', [
            'data'=>$data,'dataUnit' => $dataUnit,
            'dataKategori'=>$dataKategori,
            'dataJenis'=>$dataJenis,
            'dataSelect'=>$dataRegistrasiLoiSelect,
            'dataPeruntukan'=>$dataPeruntukan, 
            'dataPaket'=>$dataPaket, 
            'dataItemCharge'=>$dataItemCharge, 
            'dataItemChargeTambah'=>$dataItemChargeTambah,
            'tanggal'=>$tanggal,
            'unit'=>$unit,
            'ttd'=>$ttd]);

        // $this->load->view('proyek/cetakan/dokumenSt', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket, 'dataItemCharge'=>$dataItemCharge, 'dataItemChargeTambah'=>$dataItemChargeTambah]);
        
    }

    public function save()
    {
        $data = (object)[];
        $data->customer_id = $this->db->select("unit_id")
		->from("t_loi_registrasi")
		->where("id",$this->input->get('id'))
        ->get()->row()->unit_id;
		$data->project_id =	$this->m_core->project()->id;
		$data->tambah_deposit = $this->m_core->currency_to_number($this->input->post("sisa_deposit"));
		$data->cara_pembayaran_id = '';
		$data->deskripsi = 'Mutasi Deposit Liaison Officer';
        $data->no_referensi = $this->m_deposit->get_no_referensi();
        $data->tgl_document = date("Y-m-d H:i:s.000");
		$data->tgl_tambah = date("Y-m-d H:i:s.000");
        $data->user_id = $this->db->select("id")
		->from("user")
		->where("username",$this->session->userdata["username"])
        ->get()->row()->id;
        $status = 0;
        if ($this->input->post('nomor_registrasi')) {
            $this->load->model('alert');
            $action_st = $this->input->post('action_st');
            $status = $this->m_st_liaison_officer->save([
                'id' => $this->input->get('id'),
                'id_item' => $this->input->post('id_item[]'),
                'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                'tanggal_aktifasi' => $this->input->post('tanggal_aktifasi'),
                'hasil_aktifasi' => 1,
                'deposit_pemakaian' => $this->input->post('deposit_pemakaian'),
                'sisa_deposit' => $this->input->post('sisa_deposit'),
                'action_st' => $this->input->post('action_st'),
                'harga_satuan' => $this->input->post('harga_satuan[]')
            ]);
            if ($this->input->post('sisa_deposit')>0&&($this->input->post('action_st')==3)){
                $this->m_deposit->save($data);
            }
            
            $this->alert->css();
        }
        $data = $this->m_st_liaison_officer->getAll();    
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Serah Terima', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/st_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Simpan', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function ajax_save(){
		$data = (object)[];
		$data->project_id =	$this->m_core->project()->id;
		$data->tambah_deposit = $this->input->post("tambah_deposit");
		$data->customer_id = $this->input->post("customer_id");
		$data->cara_pembayaran_id = $this->input->post("cara_pembayaran_id");
		$data->deskripsi = $this->input->post("deskripsi");
		$data->no_referensi = $this->m_deposit->get_no_referensi();
		
		$seconds = microtime(true) / 1000;
		$remainder = round($seconds - ($seconds >> 0), 3) * 1000;
		
		$data->tgl_document = date("Y-m-d H:i:s.000");
		$data->tgl_tambah = date("Y-m-d H:i:s.000");

		$data->user_id = $this->db->select("id")
		->from("user")
		->where("username",$this->session->userdata["username"])
		->get()->row()->id;
		if($this->m_deposit->save($data))
			echo json_encode(true);
		else
			echo json_encode(false);
	}
    
    public function lihat_unit()
    {
        $this->load->model('transaksi_lain/m_registrasi_liaison_officer');
        $pilih_unit = $this->input->post('pilih_unit');
        echo json_encode($this->m_registrasi_liaison_officer->getUnit2($pilih_unit));
    }

    public function ajax_get_peruntukan(){
        $jenis = $this->input->post('jenis');
        echo json_encode($this->m_registrasi_liaison_officer->getPeruntukan($jenis));
    }

    public function ajax_get_paket()
    {
        $jenis = $this->input->post('jenis');
        echo json_encode($this->m_registrasi_liaison_officer->getPaket($jenis));
    }

    function terbilang($nilai) {
		if($nilai<0)    $hasil = "minus ". trim($this->penyebut($nilai));
        else            $hasil = trim($this->penyebut($nilai));
		return $hasil." Rupiah";
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
}
?>