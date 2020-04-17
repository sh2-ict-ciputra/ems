<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Desktop_Tagihan_Air extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Sync/m_desktop_transaksi_air');
        $this->load->model('m_core');
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();
    }
    
    public function index2()
    {
        $data = $this->db->get('v_list_desktop_unit')->result();
        if(!$data){
            $dataTmp = $this->db
                        ->select("COLUMN_NAME")
                        ->from("INFORMATION_SCHEMA.COLUMNS")
                        ->WHERE("TABLE_NAME = 'v_list_desktop_unit' AND TABLE_SCHEMA='dbo'")
                        ->get()->result();
            foreach ($dataTmp as $v)               array_push($data,$v->COLUMN_NAME);    
            $dataTmp = array_flip($data);
            $data = [];
            $data[0] = $dataTmp;
            foreach ($data[0] as $key => $value)    $data[0][$key] = '';
        }
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Sync > Desktop Tagihan Air ', 'subTitle' => 'List']);
        $this->load->view('core/list', [
            'table' => $data
            ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function add()
    {
        $project = $this->m_core->project();


        ini_set('memory_limit', '256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288'); // Setting to 512M - for pdo_sqlsrv
        ini_set('max_execution_time','-1'); // Setting to 512M - for pdo_sqlsrv

        $this->load->view('core/header');
        $this->load->model('alert');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Sync > Desktop Tagihan Air', 'subTitle' => 'Add']);
        $this->load->view('sync/desktop_tagihan_air',
            [
                'project'       => $this->db->from("project")->get()->result(),
                'project_id'    => $project->id
            ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function save(){
        $data = $this->input->POST('data_id'); 
        if($data){
            echo json_encode($this->m_desktop_transaksi_air->save($this->input->POST("project"),$data,$this->input->POST("source")));
        }
        // print_r($this->input->get('data_id'));        
    }
    public function erems(){
        $db_erems = $this->load->database('EREMS');
    }
    public function get_ajax_desktop_transaksi_by_source(){
        echo json_encode($this->m_desktop_transaksi_air->get_ajax_desktop_transaksi_by_source($this->input->POST('source')));
    }
    public function save2(){
        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv
        ini_set('max_execution_time','-1'); // Setting to 512M - for pdo_sqlsrv
        
        $this->m_desktop_transaksi_air->save2($this->input->get("project_id"),
                                                    $this->input->get("source"),
                                                    $this->input->get("denda_jenis_service"),
                                                    $this->input->get("denda_nilai_service"),
                                                    $this->input->get("jarak_periode"));
        // $this->m_desktop_transaksi_air->save2($this->input->get("project_id"),$this->input->get("source"),1);
        // print_r($this->input->get('data_id'));        
    }
}