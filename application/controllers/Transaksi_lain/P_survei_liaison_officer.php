<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_survei_liaison_officer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('transaksi_lain/m_survei_liaison_officer');
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
        $data = $this->m_survei_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Survei', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/survei_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function add()
    {
        $dataUnit = $this->m_survei_liaison_officer->getUnit();
        $dataKategori = $this->m_survei_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_survei_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_survei_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_survei_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_survei_liaison_officer->get_paket();
        $this->load->model('m_log');
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Survei ', 'subTitle' => 'Survei 1']);
        $this->load->view('proyek/transaksi_lain/survei_liaison_officer/add_survei', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function save()
    {
        $config['upload_path']          = './assets/dokumen/';
        $config['allowed_types']        = 'jpeg|gif|jpg|png|docx|pdf|xls';
        $config['remove_spaces']        =TRUE;
		$config['overwrite']            =TRUE;
        $config['max_size']             = 9999; // 1MB
        $config['max_width']            = 9999;
        $config['max_height']           = 9999;
        $datafile = $_FILES['bukti_survei'];
        $this->load->library('upload');
        $this->upload->initialize($config);
        if($datafile != null){
            $countfile =sizeof($datafile["size"]);
            $data_image = array();
            for($i = 0;$i<$countfile;$i++){
                if(!empty($datafile["size"][$i]))
                {
                    $file = array();
                    $file['name'] = $datafile['name'][$i];
                    $file['type'] = $datafile['type'][$i];
                    $file['tmp_name'] = $datafile['tmp_name'][$i];
                    $file['error'] = $datafile['error'][$i];
                    $file['size'] = $datafile['size'][$i];
                    $this->upload->do_upload(null,$file);
                    // var_dump($file);
                    $data_image[$i]=$this->upload->data('file_name');
                }
            }
        }else{
            $data_image[$i] = '';
        }

        $status = 0;
        if ($this->input->post('nomor_registrasi')) {
            $this->load->model('alert');

            $status = $this->m_survei_liaison_officer->save([
                'id' => $this->input->get('id'),
                'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                'tanggal_survei' => $this->input->post('tanggal_survei'),
                'bukti_survei' => $data_image,               
                'keterangan_survei' => $this->input->post('keterangan_survei'),
                'hasil_survei' => $this->input->post('hasil_survei'),
                'nama_item' => $this->input->post('nama_item[]'),
                'volume' => $this->input->post('volume[]'),
                'satuan' => $this->input->post('satuan[]')
            ]);
            $this->alert->css();
        }
        $data = $this->m_survei_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Survei', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/survei_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        // if ($this->m_registrasi_liaison_officer->cek($this->input->get('id'))) {
            
        // } else {
        //     redirect(site_url().'/P_master_cara_pembayaran');
        // }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Simpan', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function add2()
    {
        $dataUnit = $this->m_survei_liaison_officer->getUnit();
        $dataKategori = $this->m_survei_liaison_officer->get_kategori();
        $dataRegistrasiLoiSelect = $this->m_survei_liaison_officer->getSelect($this->input->get('id'));
        $dataJenis = $this->m_survei_liaison_officer->get_jenis();
        $dataPeruntukan = $this->m_survei_liaison_officer->get_peruntukan();
        $dataPaket = $this->m_survei_liaison_officer->get_paket();
        $dataItemCharge = $this->m_survei_liaison_officer->get_item_charge($this->input->get('id'));
        $this->load->model('m_log');
        $data = $this->m_log->get('t_loi_registrasi', $this->input->get('id'));
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Survei ', 'subTitle' => 'Survei 2']);
        $this->load->view('proyek/transaksi_lain/survei_liaison_officer/add_survei2', ['data'=>$data,'dataUnit' => $dataUnit,'dataKategori'=>$dataKategori,'dataJenis'=>$dataJenis,'dataSelect'=>$dataRegistrasiLoiSelect,'dataPeruntukan'=>$dataPeruntukan, 'dataPaket'=>$dataPaket, 'dataItemCharge'=>$dataItemCharge]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function save2()
    {
        $config['upload_path']          = './assets/dokumen/';
        $config['allowed_types']        = 'jpeg|gif|jpg|png|docx|pdf|xls';
        $config['remove_spaces']        =TRUE;
		$config['overwrite']            =TRUE;
        $config['max_size']             = 9999; // 1MB
        $config['max_width']            = 9999;
        $config['max_height']           = 9999;
        $datafile = $_FILES['bukti_survei'];
        $this->load->library('upload');
        $this->upload->initialize($config);
        if($datafile['name'] != 0){
            $countfile =sizeof($datafile["size"]);
            $data_image = array();
            for($i = 0;$i<$countfile;$i++){
                if(!empty($datafile["size"][$i]))
                {
                    $file = array();
                    $file['name'] = $datafile['name'][$i];
                    $file['type'] = $datafile['type'][$i];
                    $file['tmp_name'] = $datafile['tmp_name'][$i];
                    $file['error'] = $datafile['error'][$i];
                    $file['size'] = $datafile['size'][$i];
                    $this->upload->do_upload(null,$file);
                    // var_dump($file);
                    $data_image[$i]=$this->upload->data('file_name');
                }
            }
        }else{
            $data_image = '';
        }
        $status = 0;
        if ($this->input->post('nomor_registrasi')) {
            $this->load->model('alert');

            $status = $this->m_survei_liaison_officer->save2([
                'id' => $this->input->get('id'),
                'nomor_registrasi' => $this->input->post('nomor_registrasi'),
                'tanggal_survei2' => $this->input->post('tanggal_survei2'),
                'bukti_survei' => $data_image,               
                'keterangan_survei2' => $this->input->post('keterangan_survei2'),
                'hasil_survei2' => $this->input->post('hasil_survei2'),
                'nama_item' => $this->input->post('nama_item[]'),
                'volume' => $this->input->post('volume[]'),
                'satuan' => $this->input->post('satuan[]')
            ]);
            $this->alert->css();
        }
        $data = $this->m_survei_liaison_officer->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service Lain > Liason Officer > Survei', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi_lain/survei_liaison_officer/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        // if ($this->m_registrasi_liaison_officer->cek($this->input->get('id'))) {
            
        // } else {
        //     redirect(site_url().'/P_master_cara_pembayaran');
        // }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Simpan', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }

    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_cara_pembayaran->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_cara_pembayaran->getAll();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Cara Pembayaran', 'subTitle' => 'List']);
        $this->load->view('proyek/master/cara_pembayaran/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } elseif ($status == 'cara_pembayaran') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Cara Pembayaran', 'type' => 'danger']);
        } elseif ($status == 'metode_penagihan') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Metode Penagihan', 'type' => 'danger']);
        } elseif ($status == 'service') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Service', 'type' => 'danger']);
        }
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
}
?>