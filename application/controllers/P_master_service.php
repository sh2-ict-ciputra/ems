<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_master_service extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_service');
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
        // $this->load->helper('file');
        // $data = ['test','yuhuu'];
        // write_file("./log/".date("y-m-d").'_log.txt',"\n".date("y-m-d h:i:s = ! ").json_encode($data)." !", 'a+');

        $data = $this->m_service->get_view();
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Service', 'subTitle' => 'List']);
        $this->load->view('proyek/master/service/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function add()
    {
        $this->load->model('m_coa');
        $dataService = $this->m_coa->get_isjournal();

        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service > Service', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/service/add', [
            'dataService'       => $dataService,
            'dataJenisService'  => $this->db->where('active','1')->from('service_jenis')->get()->result()
            ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    
    public function save()
    {
        $data = [
            // 'jenis_service' => $this->input->post('jenis_service'),
            'jenis_retribusi'               => $this->input->post('jenis_retribusi'),
            'code'                          => $this->input->post('code'),
            'nama_service'                  => $this->input->post('nama_service'),
            'coa_mapping_id_service'        => $this->input->post('coa_mapping_id_service'),
            'parameter_tanggal_jatuh_tempo' => $this->input->post('parameter_tanggal_jatuh_tempo'),
            'ppn_flag'                      => $this->input->post('ppn_flag'),
            'jarak_periode_penggunaan'      => $this->input->post('jarak_periode_penggunaan'),
            'coa_mapping_id_ppn'            => $this->input->post('coa_mapping_id_ppn'),
            'coa_mapping_id_service_denda'  => $this->input->post('coa_mapping_id_service_denda'),

            'denda_flag'                    => $this->input->post('denda_flag'),
            'denda_selisih_bulan'           => $this->input->post('selisih_bulan_denda'),
            'denda_tanggal_jt'              => $this->input->post('tanggal_denda'),
            'denda_nilai'                   => $this->input->post('denda_nilai'),
            'denda_minimum'                 => $this->input->post('denda_minimum'),
            'denda_tgl_putus'               => $this->input->post('denda_tgl_putus'),
            'denda_jenis'                   => $this->input->post('denda_jenis'),
            
            'penalti_flag'                  => $this->input->post('penalti_flag'),
            'penalti_selisih_bulan'         => $this->input->post('selisih_bulan_penalti'),
            'penalti_tanggal_jt'            => $this->input->post('tanggal_penalti'),
            'penalti_nilai'                 => $this->input->post('penalti_nilai'),
            'penalti_minimum'               => $this->input->post('penalti_minimum'),
            'penalti_tgl_putus'             => $this->input->post('penalti_tgl_putus'),
            'penalti_jenis'                 => $this->input->post('penalti_jenis'),

            'description'                   => $this->input->post('description'),
        ];
        $status = $this->m_service->save($data);
        $this->load->model('alert');
        $data = $this->m_service->get_view();
        $this->load->view('core/header');
        $this->alert->css();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar');
        $this->load->view('core/body_header', ['title' => 'Master > Service', 'subTitle' => 'Add']);
        $this->load->view('proyek/master/service/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Tambah', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        } elseif ($status == 'pt') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data PT Tidak Ada', 'type' => 'danger']);
        } elseif ($status == 'coa') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA Tidak Ada', 'type' => 'danger']);
        }

    }

    public function edit()
    {
        $status = 0;
        if ($this->input->post('coa_mapping_id_service')) {
            $this->load->model('alert');
            $status = $this->m_service->edit([
                'id' => $this->input->get('id'),
                'coa_mapping_id_service' => $this->input->post('coa_mapping_id_service'),
                'parameter_tanggal_jatuh_tempo' => $this->input->post('parameter_tanggal_jatuh_tempo'),
                'ppn_flag' => $this->input->post('ppn_flag'),
                'jarak_periode_penggunaan' => $this->input->post('jarak_periode_penggunaan'),
                'coa_mapping_id_ppn' => $this->input->post('coa_mapping_id_ppn'),
                'coa_mapping_id_service_denda'  => $this->input->post('coa_mapping_id_service_denda'),

                'denda_flag' => $this->input->post('denda_flag'),
                'denda_selisih_bulan' => $this->input->post('selisih_bulan_denda'),
                'denda_tanggal_jt' => $this->input->post('tanggal_denda'),
                'denda_nilai' => $this->input->post('denda_nilai'),
                'denda_minimum' => $this->input->post('denda_minimum'),
                'denda_tgl_putus' => $this->input->post('denda_tgl_putus'),
                'denda_jenis' => $this->input->post('denda_jenis'),
                
                'penalti_flag' => $this->input->post('penalti_flag'),
                'penalti_selisih_bulan' => $this->input->post('selisih_bulan_penalti'),
                'penalti_tanggal_jt' => $this->input->post('tanggal_penalti'),
                'penalti_nilai' => $this->input->post('penalti_nilai'),
                'penalti_minimum' => $this->input->post('penalti_minimum'),
                'penalti_tgl_putus' => $this->input->post('penalti_tgl_putus'),
                'penalti_jenis' => $this->input->post('penalti_jenis'),

                'description' => $this->input->post('description'),
                'active' => $this->input->post('active'),
            ]);
            $this->alert->css();
        }

        $data = 1;

        if ($this->m_service->cek($this->input->get('id'))) {
            $this->load->model('m_log');

            $data = $this->m_log->get('service', $this->input->get('id'));
            $dataSelect = $this->m_service->get_by_id($this->input->get('id'));
            $this->load->model('m_coa');
            $dataService = $this->m_coa->get_isjournal();
            $this->load->view('core/header');
            $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
            $this->load->view('core/top_bar');
            $this->load->view('core/body_header', ['title' => 'Master > Service > Service', 'subTitle' => 'Edit']);
            $this->load->view('proyek/master/service/edit', [
                                                'dataService' => $dataService, 
                                                'data' => $data, 
                                                'dataJenisService'  => $this->db->where('active','1')->get('service_jenis')->result(),
                                                'dataSelect' => $dataSelect]);
            $this->load->view('core/body_footer');
            $this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_service');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'gagal') {
            $this->load->view('core/alert', ['title' => 'Gagal | Akses', 'text' => 'Tidak ada Hak akses', 'type' => 'danger']);
        }
    }

    public function add_get_coa()
    {
        echo json_encode($this->m_service->get_coa_by_pt($this->input->post('id')));
    }


    public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_service->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_service->get_view();
        $this->load->view('core/header');
        $this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header',['title' => 'Master > Service > Service','subTitle' => 'List']);
        $this->load->view('proyek/master/service/view',['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } 
    }

}
