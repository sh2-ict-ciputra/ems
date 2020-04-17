<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_registrasi_liaison_officer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_registrasi_liaison_officer');
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
        $data = $this->m_registrasi_liaison_officer->get();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Registrasi', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function add()
    {
        $kode_reg = "CG/REGISTRASILOI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_registrasi_liaison_officer->last_id()+1);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Registrasi', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/registrasi_liaison_officer/add');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function ajax_save()
    {
        echo('<pre>');
            print_r($this->input->post());
        echo('</pre>');
        // die;
        $validasi = $this->db   ->select("*")
                    ->from("t_loi_registrasi")
                    ->where("loi_paket_id",$this->input->post("paket_id"))
                    ->where("unit_id",$this->input->post("unit_id"))
                    ->where("tgl_exp >= '".date("Y-m-d")."'")
                    ->count_all_results();
        // echo('<pre>');
        //     print_r($validasi);
        // echo('</pre>');
        // die;
        if($validasi == 0){
            $status = $this->m_registrasi_liaison_officer->save($this->input->post()
                // [
                //     'pilih_unit' => $this->input->post('pilih_unit'),
                //     'unit' => $this->input->post('unit_name'),
                //     'customer_id' => $this->input->post('customer_id'),
                //     'customer_name' => $this->input->post('customer_name'),
                //     'customer_name2' => $this->input->post('customer_name2'),
                //     'nomor_telepon' => $this->input->post('nomor_telepon'),
                //     'nomor_handphone' => $this->input->post('nomor_handphone'),
                //     'email' => $this->input->post('email'),
                //     'nomor_telepon2' => $this->input->post('nomor_telepon2'),
                //     'nomor_handphone2' => $this->input->post('nomor_handphone2'),
                //     'email2' => $this->input->post('email2'),
                //     'tanggal_document' => $this->input->post('tanggal_document'),
                //     'tanggal_rencana_pemasangan' => $this->input->post('tanggal_rencana_pemasangan'),
                //     'tanggal_rencana_survei' => $this->input->post('tanggal_rencana_survei'),
                //     'tanggal_rencana_aktifasi' => $this->input->post('tanggal_rencana_aktifasi'),           
                //     'paket_loi_id' => $this->input->post('jenis_paket'),
                //     'luasbaru' => $this->input->post('luasbaru'),
                //     'luaslama' => $this->input->post('luaslama'),
                //     'harga_paket' => $this->input->post('harga_paket'),
                //     'diskon' => $this->input->post('diskon'),
                //     'total' => $this->input->post('total_bayar'),
                //     'keterangan' => $this->input->post('keterangan'),
                //     'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                //     'nomor_registrasi2' => $this->input->post('nomor_registrasi2'),
                //     'status_dokumen' => 0,
                //     'kategori_loi_id' => $this->input->post('kategori_loi_id'),
                //     'jenis_loi_id' => $this->input->post('jenis_loi_id'),
                //     'peruntukan_loi_id' => $this->input->post('peruntukan_loi_id'),
                //     'expired_date' => $this->input->post('expired_date'),
                //     'deposit_masuk' => $this->input->post('nilai_jaminan')
                //     // 'dokumen' => $data_image
                // ]
            );
        }
    }

    public function edit()
    {
        $dataUnit = $this->m_registrasi_liaison_officer->getUnit();
        $dataKategori = $this->m_registrasi_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_registrasi_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_registrasi_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_registrasi_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_registrasi_liaison_officer->get_paket();
        $this->load->model('m_log');
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Registrasi ', 'subTitle' => 'Edit']);
        $this->load->view('proyek/transaksi_lain/registrasi_liaison_officer/edit', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function get_ajax_unit()
	{
		echo json_encode($this->m_registrasi_liaison_officer->get_unit($this->input->get('data')));
    }
    public function get_ajax_unit_detail(){
        $project = $this->m_core->project();
        $this->load->model("core/m_tagihan");
        $unit_id = $this->input->get("id");
        $unit_detail = $this->m_registrasi_liaison_officer->get_unit_detail($unit_id);
        $tagihan_air = $this->m_tagihan->air(
                                        $project->id,
                                        [
                                            'status_tagihan'=>[0,2,3,4],
                                            'unit_id'=>[$unit_id],
                                            'periode'=>date("Y-m-d")
                                        ]);
		$tagihan_lingkungan = $this->m_tagihan->lingkungan(
                                        $project->id,
                                        [
                                            'status_tagihan'=>[0,2,3,4],
                                            'unit_id'=>[$unit_id],
                                            'periode'=>date("Y-m-d")
                                        ]);
        $total = 0;
        foreach ($tagihan_air as $k => $v) {
            $total += $v->nilai_tagihan + $v->nilai_denda + $v->belum_bayar + 0 - ($v->view_pemutihan_nilai_tagihan + $v->view_pemutihan_nilai_denda);
        }   
        foreach ($tagihan_lingkungan as $k => $v) {
            $total += $v->nilai_tagihan + $v->nilai_denda + $v->belum_bayar + 0 - ($v->view_pemutihan_nilai_tagihan + $v->view_pemutihan_nilai_denda);
        }   
        $unit_detail->outstading = $total;
        echo json_encode($unit_detail);

    }
    public function get_ajax_paket()
    {
        echo json_encode($this->m_registrasi_liaison_officer->get_paket($this->input->get('data')));
    }
    public function get_ajax_paket_detail(){
        echo json_encode($this->m_registrasi_liaison_officer->get_paket_detail($this->input->get('id')));

    }
    public function cetak()
    {
        $this->load->library('pdf');
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "dokumentvi.pdf";
        $dataRegistrasiLOISelect = $this->m_registrasi_liaison_officer->getSelect($this->input->get('id'));
        $tanggal = (date("d")." ".$this->bln_indo(date("m"))." ".date("Y"));
        $terbilang = $this->terbilang(str_replace(",","",$dataRegistrasiLOISelect->total));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liaison Officer > Edit', 'subTitle' => 'Edit']);
        $this->pdf->load_view('proyek/cetakan/dokumenLOI', ['data_select'=>$dataRegistrasiLOISelect,'tanggal'=>$tanggal,'terbilang'=>$terbilang]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function cetakform()
    {
        $this->load->library('pdf');
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "dokumentvi.pdf";
        // $dataRegistrasiLOISelect = $this->m_registrasi_liaison_officer->getSelect($this->input->get('id'));
        //$tanggal = (date("d")." ".$this->bln_indo(date("m"))." ".date("Y"));
        //$terbilang = $this->terbilang(str_replace(",","",$dataRegistrasiLOISelect->total));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liaison Officer > Edit', 'subTitle' => 'Edit']);
        $this->pdf->load_view('proyek/cetakan/emptyLOI');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
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

    public function upload()
    {
        $status = 0;
        $this->load->model('alert');
        $config['upload_path']          = './assets/dokumen/';
        $config['allowed_types']        = 'jpeg|gif|jpg|png|docx|pdf|xls';
        $config['remove_spaces']        =TRUE;
		$config['overwrite']            =TRUE;
        $config['max_size']             = 9999; // 1MB
        $config['max_width']            = 9999;
        $config['max_height']           = 9999;

        $this->load->library('upload');
        $this->upload->initialize($config);
        $this->upload->do_upload('dokumen');
        $data_image=$this->upload->data('file_name');
        
        $status = $this->m_registrasi_liaison_officer->upload([
            'id' => $this->input->get('id'),
            'dokumen' => $data_image
        ]);

        $this->alert->css();

        $data = $this->m_registrasi_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_liaison_officer/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Dokumen Berhasil di Upload', 'type' => 'success']);
        } 
    }

}
?>