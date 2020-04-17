<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_unit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        $this->load->model('m_unit');
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

    public function index()
    {
        // $data = $this->m_unit->getView();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Town Planning > Unit', 'subTitle' => 'List']);
        $this->load->view('proyek/master/unit/view');
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }

    public function add()
    {
        $this->load->model('m_unit');
        $dataUnit = $this->m_unit->getAll();
        $dataKawasan = $this->m_unit->getKawasan();
        $dataCustomer = $this->m_unit->getCustomer();
        $dataGolongan = $this->m_unit->getGolongan();
        $dataPT = $this->m_unit->getPT();
        $dataMetodePenagihan = $this->m_unit->getMetodePenagihan();
        $dataPemeliharaanMeterAir = $this->m_unit->getPemeliharaanMeterAir();
        $dataPemeliharaanMeterListrik = $this->m_unit->getPemeliharaanMeterListrik();
        $dataSubGolongan = $this->m_unit->getSubGolongan();
        $dataProductCategory = $this->m_unit->getProductCategory();
        $this->load->view('core/header');

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Town Planning > Unit', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/unit/add', [
            'dataPemeliharaanMeterListrik'=> $dataPemeliharaanMeterListrik,
            'dataUnit' => $dataUnit,  
            'dataKawasan' => $dataKawasan, 
            'dataCustomer' => $dataCustomer, 
            'dataGolongan' => $dataGolongan,  
            'dataPT' => $dataPT,
            'dataMetodePenagihan' => $dataMetodePenagihan, 
            'dataPemeliharaanMeterAir' => $dataPemeliharaanMeterAir, 
            'dataSubGolongan' => $dataSubGolongan,
            'dataProductCategory'=>$dataProductCategory
        ]);
        $this->load->view('core/footer');
    }

    public function save()
    {
        $status = $this->m_unit->save([
            // 'kawasan_flag' => $this->input->post('kawasan_flag'),
            'blok' => $this->input->post('blok'),
            'no_unit' => $this->input->post('nomor_unit'),
            'pemilik_customer_id' => $this->input->post('pemilik'),
            'penghuni_customer_id' => $this->input->post('penghuni'),
            'luas_tanah' => $this->input->post('luas_tanah'),
            'luas_bangunan' => $this->input->post('luas_bangunan'),
            'luas_taman' => $this->input->post('luas_taman'),
            'unit_type' => $this->input->post('jenis_unit'),
            'status_tagihan' => $this->input->post('status_tagihan'),
            'virtual_account' => $this->input->post('virtual_account'),
            'product_category_id' => $this->input->post('produk_kategori'),
            'gol_id' => $this->input->post('golongan'),
            'pt' => $this->input->post('pt'),
            'flag_diskon' => $this->input->post('flag_diskon'),
            'kirim_tagihan' => $this->input->post('kirim_tagihan'),
            'tgl_st' => $this->input->post('tgl_st'),
            

            'air_aktif' => $this->input->post('meter_air_aktif'),
            'tgl_aktif_air' => $this->input->post('tanggal_aktif_air'),
            'tgl_putus_air' => $this->input->post('tanggal_putus_air'),
            'meter_air_id' => $this->input->post('pemeliharaan_meter_air'),
            'nilai_penyambungan_air' => $this->input->post('nilai_penyambungan'),
            'sub_gol_air_id' => $this->input->post('sub_golongan_air'),
            'angka_meter_sekarang_air' => $this->input->post('angka_meter_air'),
            'barcode_meter_air_id' => $this->input->post('barcode_id'),
            'no_seri_meter_air' => $this->input->post('nomor_meter_air'),
            

            'lingkungan_aktif' => $this->input->post('meter_pl_aktif'),
            'tgl_aktif_lingkungan' => $this->input->post('pl_tanggal_aktif'),
            'tgl_nonaktif_lingkungan' => $this->input->post('tanggal_non_aktif_pl'),
            'sub_gol_lingkungan_id' => $this->input->post('sub_golongan_lingkungan'),
            'tgl_mandiri_lingkungan' => $this->input->post('tanggal_mandiri_pl'),


            'listrik_aktif' => $this->input->post('meter_listrik_aktif'),
            'tgl_aktif_listrik' => $this->input->post('tanggal_aktif_listrik'),
            'tgl_putus_listrik' => $this->input->post('tanggal_putus_listrik'),
			'angka_meter_sekarang_listrik' => $this->input->post('angka_meter_listrik'),
			'meter_listrik_id' => $this->input->post('sewa_meter_listrik'),
			'sub_gol_listrik_id' => $this->input->post('sub_golongan_listrik'),
            'no_seri_meter_listrik' => $this->input->post('nomor_seri_listrik'),
            'status_jual' => $this->input->post('status_jual'),

            'metode_tagihan' => $this->input->post('metode_tagihan[]'),

        ]);
        
        $this->load->model('alert');
        $this->load->model('m_unit');
        $this->load->view('core/header');
        $this->alert->css();

        $dataUnit = $this->m_unit->getAll();
        $dataKawasan = $this->m_unit->getKawasan();
        $dataCustomer = $this->m_unit->getCustomer();
        $dataGolongan = $this->m_unit->getGolongan();
        $dataPT = $this->m_unit->getPT();
        $dataMetodePenagihan = $this->m_unit->getMetodePenagihan();
        $dataPemeliharaanMeterAir = $this->m_unit->getPemeliharaanMeterAir();
        $dataPemeliharaanMeterListrik = $this->m_unit->getPemeliharaanMeterListrik();
        $dataSubGolongan = $this->m_unit->getSubGolongan();
        $dataProductCategory = $this->m_unit->getProductCategory();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Town Planning > Unit', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/unit/add', [
            'dataPemeliharaanMeterListrik'  => $dataPemeliharaanMeterListrik,
            'dataUnit'                      => $dataUnit,
            'dataKawasan'                   => $dataKawasan,
            'dataCustomer'                  => $dataCustomer,
            'dataGolongan'                  => $dataGolongan,
            'dataPT'                        => $dataPT,
            'dataMetodePenagihan'           => $dataMetodePenagihan,
            'dataPemeliharaanMeterAir'      => $dataPemeliharaanMeterAir,
            'dataSubGolongan'               => $dataSubGolongan,
            'dataProductCategory'           => $dataProductCategory
        ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        }
    }


    public function edit()
    {

        $status = 0;
        if ($this->input->post('nomor_unit')) {
            $this->load->model('alert');
            $a = [
                'id' => $this->input->get('id'),

                'blok_id' => $this->input->post('blok'),
                'no_unit' => $this->input->post('nomor_unit'),
                'pemilik_customer_id' => $this->input->post('pemilik'),
                'penghuni_customer_id' => $this->input->post('penghuni'),
                'luas_tanah' => $this->input->post('luas_tanah'),
                'luas_bangunan' => $this->input->post('luas_bangunan'),
                'luas_taman' => $this->input->post('luas_taman'),
                'unit_type' => $this->input->post('jenis_unit'),
                'status_tagihan' => $this->input->post('status_tagihan'),
                'virtual_account' => $this->input->post('virtual_account'),
                'product_category_id' => $this->input->post('produk_kategori'),
                'gol_id' => $this->input->post('golongan'),
                'pt' => $this->input->post('pt'),
                'diskon_flag' => $this->input->post('flag_diskon'),
                'kirim_tagihan' => $this->input->post('kirim_tagihan'),
                'tgl_st' => $this->input->post('tgl_st'),
                'active' => $this->input->post('active'),
                
    
                'air_aktif' => $this->input->post('meter_air_aktif'),
                'tgl_aktif_air' => $this->input->post('tanggal_aktif_air'),
                'tgl_putus_air' => $this->input->post('tanggal_putus_air'),
                'meter_air_id' => $this->input->post('pemeliharaan_meter_air'),
                'nilai_penyambungan_air' => $this->input->post('nilai_penyambungan'),
                'sub_gol_air_id' => $this->input->post('sub_golongan_air'),
                'angka_meter_sekarang_air' => $this->input->post('angka_meter_air'),
                'barcode_meter_air_id' => $this->input->post('barcode_id'),
                'no_seri_meter_air' => $this->input->post('nomor_meter_air'),
                
    
                'lingkungan_aktif' => $this->input->post('meter_pl_aktif'),
                'tgl_aktif_lingkungan' => $this->input->post('pl_tanggal_aktif'),
                'tgl_nonaktif_lingkungan' => $this->input->post('tanggal_non_aktif_pl'),
                'sub_gol_lingkungan_id' => $this->input->post('sub_golongan_lingkungan'),
                'tgl_mandiri_lingkungan' => $this->input->post('tanggal_mandiri_pl'),
    
    
                // 'listrik_aktif' => $this->input->post('meter_listrik_aktif'),
                // 'tgl_aktif_listrik' => $this->input->post('tanggal_aktif_listrik'),
                // 'tgl_putus_listrik' => $this->input->post('tanggal_putus_listrik'),
                // 'angka_meter_sekarang_listrik' => $this->input->post('angka_meter_listrik'),
                // 'meter_listrik_id' => $this->input->post('sewa_meter_listrik'),
                // 'sub_gol_listrik_id' => $this->input->post('sub_golongan_listrik'),
                // 'no_seri_meter_listrik' => $this->input->post('nomor_seri_listrik'),
    
                'status_jual' => $this->input->post('status_jual'),
                'metode_tagihan' => $this->input->post('metode_tagihan[]'),
    


            ];
            // echo("<pre>");
            //     print_r($a);
            // echo("</pre>");
            


            $status = $this->m_unit->edit([
                'id' => $this->input->get('id'),

                'blok_id' => $this->input->post('blok'),
                'no_unit' => $this->input->post('nomor_unit'),
                'pemilik_customer_id' => $this->input->post('pemilik'),
                'penghuni_customer_id' => $this->input->post('penghuni'),
                'luas_tanah' => $this->input->post('luas_tanah'),
                'luas_bangunan' => $this->input->post('luas_bangunan'),
                'luas_taman' => $this->input->post('luas_taman'),
                'unit_type' => $this->input->post('jenis_unit'),
                'status_tagihan' => $this->input->post('status_tagihan'),
                'virtual_account' => $this->input->post('virtual_account'),
                'product_category_id' => $this->input->post('produk_kategori'),
                'gol_id' => $this->input->post('golongan'),
                'pt' => $this->input->post('pt'),
                'diskon_flag' => $this->input->post('flag_diskon'),
                'kirim_tagihan' => $this->input->post('kirim_tagihan'),
                'tgl_st' => $this->input->post('tgl_st'),
                'active' => $this->input->post('active'),
                
    
                'air_aktif' => $this->input->post('meter_air_aktif'),
                'tgl_aktif_air' => $this->input->post('tanggal_aktif_air'),
                'tgl_putus_air' => $this->input->post('tanggal_putus_air'),
                'meter_air_id' => $this->input->post('pemeliharaan_meter_air'),
                'nilai_penyambungan_air' => $this->input->post('nilai_penyambungan'),
                'sub_gol_air_id' => $this->input->post('sub_golongan_air'),
                'angka_meter_sekarang_air' => $this->input->post('angka_meter_air'),
                'barcode_meter_air_id' => $this->input->post('barcode_id'),
                'no_seri_meter_air' => $this->input->post('nomor_meter_air'),
                
    
                'lingkungan_aktif' => $this->input->post('meter_pl_aktif'),
                'tgl_aktif_lingkungan' => $this->input->post('pl_tanggal_aktif'),
                'tgl_nonaktif_lingkungan' => $this->input->post('tanggal_non_aktif_pl'),
                'sub_gol_lingkungan_id' => $this->input->post('sub_golongan_lingkungan'),
                'tgl_mandiri_lingkungan' => $this->input->post('tanggal_mandiri_pl'),
    
    
                // 'listrik_aktif' => $this->input->post('meter_listrik_aktif'),
                // 'tgl_aktif_listrik' => $this->input->post('tanggal_aktif_listrik'),
                // 'tgl_putus_listrik' => $this->input->post('tanggal_putus_listrik'),
                // 'angka_meter_sekarang_listrik' => $this->input->post('angka_meter_listrik'),
                // 'meter_listrik_id' => $this->input->post('sewa_meter_listrik'),
                // 'sub_gol_listrik_id' => $this->input->post('sub_golongan_listrik'),
                // 'no_seri_meter_listrik' => $this->input->post('nomor_seri_listrik'),
    
                'status_jual' => $this->input->post('status_jual'),
                'metode_tagihan' => $this->input->post('metode_tagihan[]'),
    


            ]);
            $this->alert->css();
        }

        if ($this->m_unit->cek($this->input->get('id'))) {
            $dataUnit = $this->m_unit->getAll();
            $dataKawasan = $this->m_unit->getKawasan();
            $dataCustomer = $this->m_unit->getCustomer();
            $dataGolongan = $this->m_unit->getGolongan();
            $dataPT = $this->m_unit->getPT();
            $dataMetodePenagihan = $this->m_unit->getMetodePenagihan();
          
            $dataPemeliharaanMeterAir = $this->m_unit->getPemeliharaanMeterAir();
            $dataPemeliharaanMeterListrik = $this->m_unit->getPemeliharaanMeterListrik();
            $dataProductCategory = $this->m_unit->getProductCategory();
            
            $dataSelect = $this->m_unit->getSelect($this->input->get('id'));
            $dataBlok = $this->m_unit->getBlok($dataSelect->kawasan);
            // echo($dataSelect->golongan);
            $dataSubGol =  $this->m_unit->get_sub_golongan($dataSelect->golongan);
            // echo("<pre>");
            //     print_r($dataSubGol);
            // echo("</pre>");
            
            $dataSelectMetodePenagihan = $this->m_unit->getSelectMetodePenagihan($this->input->get('id'));

            $this->load->model('m_log');
            $data = $this->m_log->get('unit', $this->input->get('id'));
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Town Planning > Unit', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/unit/edit', [
                'dataSelect'=> $dataSelect,
                'dataBlok' => $dataBlok,
                'dataGolongan' => $dataGolongan,
                'dataSubGol' => $dataSubGol,
                'dataSelectMetodePenagihan' => $dataSelectMetodePenagihan,
                'data' => $data,
                'dataPemeliharaanMeterListrik'=> $dataPemeliharaanMeterListrik,
                'dataUnit' => $dataUnit,  
                'dataKawasan' => $dataKawasan, 
                'dataCustomer' => $dataCustomer, 
                'dataGolongan' => $dataGolongan,  
                'dataPT' => $dataPT,   
                'dataMetodePenagihan' => $dataMetodePenagihan, 
                'dataPemeliharaanMeterAir' => $dataPemeliharaanMeterAir, 
                'dataProductCategory'=>$dataProductCategory]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_unit');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan sudah Ada', 'type' => 'danger']);
        }
    }
    public function get_sub_golongan()
    {
        $jenis_golongan_id = $this->input->post('jenis_golongan_id');
        echo json_encode($this->m_unit->get_sub_golongan($jenis_golongan_id));
    }

    public function lihat_blok()
    {
        $this->load->model('m_unit');

        $kawasan_flag = $this->input->post('kawasan_flag');

        echo json_encode($this->m_unit->getBlok($kawasan_flag));
    }

    public function lihat_subgolair()
    {
        $this->load->model('m_unit');

        $golongan_flag = $this->input->get('golongan_flag');

        echo json_encode($this->m_unit->getSubGolonganAir($golongan_flag));
    }

    public function lihat_subgollingkungan()
    {
        $this->load->model('m_unit');

        $golongan_flag = $this->input->post('golongan_flag');

        echo json_encode($this->m_unit->getSubGolonganLingkungan($golongan_flag));
    }

    public function lihat_subgollistrik()
    {
        $this->load->model('m_unit');
        $golongan_flag = $this->input->post('golongan_flag');
        echo json_encode($this->m_unit->getSubGolonganListrik($golongan_flag));
    }
    public function ajax_get_view(){
        // echo json_encode($this->m_unit->serverSideUnit());
        $project = $this->m_core->project();

        $table =    "unit
                    join blok
                        ON blok.id = unit.blok_id
                    JOIN kawasan
                        ON kawasan.id = blok.kawasan_id
                    JOIN customer as pemilik
                        ON pemilik.id = unit.pemilik_customer_id
                    WHERE unit.project_id = $project->id";
 
        $primaryKey = 'unit.id';
        
        $columns = array(
            array( 'db' => 'kawasan.name as kawasan_name', 'dt' => 0 ),
            array( 'db' => 'blok.name as blok_name',  'dt' => 1 ),
            array( 'db' => 'unit.no_unit as no_unit',   'dt' => 2 ),
            array( 'db' => 'pemilik.name as pemilik_name',     'dt' => 3 ),
            array( 'db' => 'unit.source_table as source_table',     'dt' => 4 ),
            array( 'db' => 'unit.id as id',     'dt' => 5 )
        );

        // SQL server connection information

        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
        );
        $this->load->library("SSP");

        

        $table = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns );
        foreach ($table["data"] as $k => $v) {
			// var_dump($table["data"][$k][count($v)-1]);
			// var_dump(end($v));
			$table["data"][$k][count($v)-1] = 
				"<a href='".site_url()."/P_master_unit/edit?id=".end($v)."' class='btn btn-primary col-md-10'>
                    <i class='fa fa-pencil'></i>
                </a>";
        }
        echo(json_encode($table));		

    }
}
