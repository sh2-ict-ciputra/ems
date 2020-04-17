<?php

defined('BASEPATH') or exit('No direct script access allowed');

class p_registrasi_tvi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->model('transaksi_lain/m_registrasi_tvi');
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
        $data = $this->m_registrasi_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Registrasi', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_tvi/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        // $dataUnit = $this->m_registrasi_tvi->getUnit();
        // $dataCustomer = $this->m_registrasi_tvi->getCustomer();
        $dataPaket = $this->m_registrasi_tvi->getPaket();
        $kode_reg = "CG/REGISTRASITVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_registrasi_tvi->last_id()+1);
        $this->load->view('core/header');
        $this->load->model('alert');
        $this->alert->css();
        
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Registrasi', 'subTitle' => 'Add']);
        $this->load->view('proyek/transaksi_lain/registrasi_tvi/add', 
            // [   'dataUnit'      => $dataUnit,
            //     'dataCustomer'  => $dataCustomer,
            [ 
                'dataPaket'     => $dataPaket, 
                'kode_reg'      => $kode_reg    
            ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function get_select2_customer()
	{
		$data = $this->db   ->select("id, name as text")
                            ->from('customer')
                            ->where('project_id', $GLOBALS['project']->id)
                            ->where("name like '%" . $this->input->get('data') . "%'")
                            ->get()->result();
		echo json_encode($data);
    }
    public function get_select2_unit_virtual()
	{
		$data = $this->db   ->select("id, unit as text")
                            ->from('unit_virtual')
                            // ->where('project_id', $GLOBALS['project']->id)
                            ->where('customer_id',$this->input->get('customer_id'))
                            ->where("unit like '%" . $this->input->get('data') . "%'")
                            ->get()->result();
        
		echo json_encode($data);
    }
    public function get_ajax_jenis_pemasangan(){
        $data = $this->db   ->select('*')
                            ->from('t_tvi_aktifasi')
                            ->join('t_tvi_registrasi',
                                    't_tvi_registrasi.id = t_tvi_aktifasi.t_tvi_registrasi_id');
        if($this->input->get('is_unit') == 1)
            $data = $data->where('unit_id',$this->input->get('unit_id'));
        if($this->input->get('is_unit') == 0)
            $data = $data->where('unit_virtual_id',$this->input->get('unit_virtual_id'));
        $data = $data->get()->row();
        echo json_encode($data?0:1);
    }
    public function get_ajax_customer(){
        $data = $this->db   ->select('*')
                            ->from('customer')
                            ->where('id',$this->input->post('customer_id'))
                            ->get()->row();
        echo json_encode($data);
    }
    public function get_ajax_pilih_unit_virtual(){
        $data = $this->db   ->select('*')
                            ->from('unit_virtual')
                            ->where('id',$this->input->post('unit_virtual_id'))
                            ->get()->row();
        echo json_encode($data);
    }
    public function save()
    {
        $status = $this->m_registrasi_tvi->save([
            'is_unit' => $this->input->post('is_unit'),
            'unit_id' => $this->input->post('unit_id'),
            'unit_virtual_id' => $this->input->post('unit_virtual_id'),
            'jenis_pemasangan' => $this->input->post('jenis_pemasangan'),
            'tanggal_document' => $this->input->post('tanggal_document'),
            'pilih_paket' => $this->input->post('pilih_paket'),
            'tanggal_rencana_pemasangan' => $this->input->post('tanggal_rencana_pemasangan'),
            'tanggal_rencana_aktifasi' => $this->input->post('tanggal_rencana_aktifasi'),
            'tanggal_rencana_survei' => $this->input->post('tanggal_rencana_survei'), 
            'jenis_bayar' => $this->input->post('jenis_bayar'),                 
            'jenis_paket_id' => $this->input->post('jenis_paket'),
            'harga_paket' => $this->input->post('harga_paket'),
            'harga_lain_lain' => $this->input->post('harga_pasang'),
            'harga_registrasi' => $this->input->post('harga_registrasi'),
            'diskon' => $this->input->post('diskon'),
            'total' => $this->input->post('total'),
            'keterangan' => $this->input->post('keterangan'),
            'nomor_registrasi' => $this->input->post('nomor_registrasi'),
            'status_dokumen' => 0
        ]);
            
        $data = $this->m_registrasi_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Registrasi', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_tvi/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function ajax_save()
    {
        echo(
            json_encode(
                $this->m_registrasi_tvi->save([
                    'is_unit' => $this->input->post('is_unit'),
                    'unit_id' => $this->input->post('unit_id'),
                    'unit_virtual_id' => $this->input->post('unit_virtual_id'),
                    'jenis_pemasangan' => $this->input->post('jenis_pemasangan'),
                    'tanggal_document' => $this->input->post('tanggal_document'),
                    'pilih_paket' => $this->input->post('pilih_paket'),
                    'tanggal_rencana_pemasangan' => $this->input->post('tanggal_rencana_pemasangan'),
                    'tanggal_rencana_aktifasi' => $this->input->post('tanggal_rencana_aktifasi'),
                    'tanggal_rencana_survei' => $this->input->post('tanggal_rencana_survei'), 
                    'jenis_bayar' => $this->input->post('jenis_bayar'),                 
                    'jenis_paket_id' => $this->input->post('jenis_paket'),
                    'harga_paket' => $this->input->post('harga_paket'),
                    'harga_lain_lain' => $this->input->post('harga_pasang'),
                    'harga_registrasi' => $this->input->post('harga_registrasi'),
                    'diskon' => $this->input->post('diskon'),
                    'total' => $this->input->post('total'),
                    'keterangan' => $this->input->post('keterangan'),
                    'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                    'status_dokumen' => 0
                ])
            )
        );
    }
    
    public function edit()
    {        
        if ($this->m_registrasi_tvi->cek($this->input->get('id'))) {
            $dataRegistrasiTviSelect = $this->m_registrasi_tvi->getSelect($this->input->get('id'));

            $dataUnit = $this->m_registrasi_tvi->getUnit();
            $dataCustomer = $this->m_registrasi_tvi->getCustomer();
            $dataPaket = $this->m_registrasi_tvi->getPaket();
            $kode_reg = "CG/REGISTRASITVI/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_registrasi_tvi->last_id()+1);
            $dataSelect = $this->m_registrasi_tvi->getSelect($this->input->get('id'));
            
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Registrasi', 'subTitle' => 'Edit']);
            $this->load->view('proyek/transaksi_lain/registrasi_tvi/edit', 
            [   'dataUnit'      => $dataUnit,
                'dataCustomer'  => $dataCustomer, 
                'dataPaket'     => $dataPaket, 
                'kode_reg'     => $kode_reg, 
                'dataSelect'      => $dataSelect        
            ]);

            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/transaksi_lain/P_registrasi_tvi');
        }
    }

    public function cetak()
    {
        $project = $this->m_core->project();
        $this->load->library('pdf');
        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "dokumentvi.pdf";
        $dataRegistrasiTviSelect = $this->m_registrasi_tvi->getSelect($this->input->get('id'));
        $tanggal = (date("d")." ".$this->bln_indo(date("m"))." ".date("Y"));
        $terbilang = $this->terbilang(str_replace(",","",$dataRegistrasiTviSelect->total));
        $customer = $this->m_registrasi_tvi->getCustomer2($dataRegistrasiTviSelect->customer_id);
        $paket = $this->m_registrasi_tvi->getDetailPaket($dataRegistrasiTviSelect->jenis_paket ,$dataRegistrasiTviSelect->jenis_paket_id);
        
        $this->pdf->load_view('proyek/cetakan/dokumenTVI', [
            'data_select'   =>$dataRegistrasiTviSelect,
            'tanggal'       =>$tanggal,
            'terbilang'     =>$terbilang, 
            'project'       =>$project,
            'customer'      =>$customer,
            'paket'         =>$paket
        ]);
        
    }

    public function cetakFormKosong()
    {
        $this->load->library('pdf');

        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "formtvi.pdf";
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Edit', 'subTitle' => 'Edit']);
        $this->pdf->load_view('proyek/cetakan/formTVI');
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

     public function edit_non_unit()
    {
        $status = 0;
        echo $this->input->post('nomor_registrasi2');
        if ($this->input->post('nomor_registrasi2')) {
            $this->load->model('alert');

            $status = $this->m_registrasi_tvi->edit_non_unit([
                'id' => $this->input->get('id'),
                
               
                'id_tagihan' => $this->input->post('id_tagihan'), 
                'nomor_registrasi2' => $this->input->post('nomor_registrasi2'),
                'jenis_pemasangan' => $this->input->post('jenis_pemasangan'),
                'tanggal_document' => $this->input->post('tanggal_document'),
                'tanggal_pemasangan_mulai' => $this->input->post('tanggal_pemasangan_mulai'), 
                'tanggal_aktifasi' => $this->input->post('tanggal_aktifasi'), 
                'jenis_bayar' => $this->input->post('jenis_bayar'),           
                'jenis_paket_id' => $this->input->post('pilih_paket'),
                'harga_paket' => $this->input->post('harga_paket'),
                'harga_pasang' => $this->input->post('harga_pasang'),
                'biaya_registrasi' => $this->input->post('harga_registrasi'),
                'diskon' => $this->input->post('diskon'),
                'total' => $this->input->post('total'),
                'keterangan' => $this->input->post('keterangan'),
             
            ]);
            $this->alert->css();
        }

        if ($this->m_registrasi_tvi->cek($this->input->get('id'))) {          
            $dataRegistrasiTviNonUnitSelect = $this->m_registrasi_tvi->getSelectNonUnit($this->input->get('id'));
            $dataPaket = $this->m_registrasi_tvi->getPaket();
            $this->load->model('m_log');
            $data = $this->m_log->get('t_tvi_registrasi', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet > Registrasi', 'subTitle' => 'Edit']);
            $this->load->view('proyek/transaksi_lain/registrasi_tvi/edit_non_unit', ['data_select' => 
                $dataRegistrasiTviNonUnitSelect, 'dataPaket' => $dataPaket, 'data' => $data]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/transaksi_lain/P_registrasi_tvi');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        } elseif ($status == 'Tidak Ada Perubahan') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan Tidak Ada Perubahan', 'type' => 'danger']);
        }
    }


    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_registrasi_tvi->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_registrasi_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_tvi/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } 
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
        
        $status = $this->m_registrasi_tvi->upload([
            'id' => $this->input->get('id'),
            'dokumen' => $data_image
        ]);

        $this->alert->css();

        $data = $this->m_registrasi_tvi->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > TV & Internet', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/registrasi_tvi/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Dokumen Berhasil di Upload', 'type' => 'success']);
        } 
    }


    public function lihat_unit()
    {
        $pilih_unit = $this->input->post('pilih_unit');

        echo json_encode($this->m_registrasi_tvi->getUnit2($pilih_unit));
    }


    public function lihat_customer()
    {
        $pilih_customer = $this->input->post('pilih_customer');

        echo json_encode($this->m_registrasi_tvi->getCustomer2($pilih_customer));
    }


    public function lihat_paket()
    {
        $pilih_paket = $this->input->post('pilih_paket');

        echo json_encode($this->m_registrasi_tvi->getPaket2($pilih_paket));
    }

    public function ajax_jenis_paket_internet()
    {
        $jenis_bayar = $this->input->post('jenis_bayar');

        echo json_encode($this->m_registrasi_tvi->getJenisPaketInternet($jenis_bayar));
    }

    public function ajax_jenis_paket_tv()
    {
        $jenis_bayar = $this->input->post('jenis_bayar');

        echo json_encode($this->m_registrasi_tvi->getJenisPaketTV($jenis_bayar));
    }

    public function ajax_jenis_paket_tvi()
    {
        $jenis_bayar = $this->input->post('jenis_bayar');

        echo json_encode($this->m_registrasi_tvi->getJenisPaketTVI($jenis_bayar));
    }

    public function ajax_detail_jenis_paket(){
        $jenis_paket = $this->input->post('jenis_paket');
        // $jenis_bayar = $this->input->post('jenis_bayar');
        $jenis_paket_id = $this->input->post('jenis_paket_id');
        echo json_encode($this->m_registrasi_tvi->getDetailPaket($jenis_paket,$jenis_paket_id));

    }

    public function lihat_nomorreg_non_unit()
    {
        $customer_id = $this->input->post('customer_id');

        echo json_encode($this->m_registrasi_tvi->getNomorRegistrasiNonUnit($customer_id));
    }

    public function lihat_nomorreg_unit()
    {
        $unit_id = $this->input->post('unit_id');
        echo json_encode($this->m_registrasi_tvi->getNomorRegistrasiUnit( $unit_id));
    }


    public function lihat_aktifasi_unit()
    {
       
        $unit_id = $this->input->post('unit_id');


        echo json_encode($this->m_registrasi_tvi->getAktifasiUnit( $unit_id));
    }


    public function lihat_aktifasi_non_unit()
    {
       
        $customer_id = $this->input->post('customer_id');


        echo json_encode($this->m_registrasi_tvi->getAktifasiNonUnit( $customer_id));
    }





}
