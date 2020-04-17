<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Desktop_Unit extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Sync/m_desktop_unit');
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
        $this->load->view('core/body_header', ['title' => 'Sync > Desktop_Unit ', 'subTitle' => 'List']);
        $this->load->view('core/list', [
            'table' => $data
            ]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function index()
    {
        $this->load->model('alert');
        $this->alert->css();

        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Sync > Purpose Desktop_Unit', 'subTitle' => 'Add']);
        $this->load->view('sync/desktop_unit',['project'=>$this->db->from("project")->get()->result()]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');

    }
    public function save(){
        $data = $this->input->post('data_id'); 
        if($data){
            echo json_encode($this->m_desktop_unit->save($this->input->post("project"),$data,$this->input->post("source"),$this->input->post("formula_air"),$this->input->post("formula_bangunan"),$this->input->post("formula_kavling")));
        }
        // print_r($this->input->get('data_id'));        
    }
    public function erems(){
        $db_erems = $this->load->database('EREMS');
    }
    public function get_ajax_desktop_unit_by_source(){
        echo json_encode($this->m_desktop_unit->get_ajax_desktop_unit_by_source($this->input->POST('source'),$this->input->POST('project_id')));
    }
    public function save2(){
        ini_set('memory_limit','256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');
        $range_lingkungan = $this->m_desktop_unit->get_range_lingkungan($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));        
        $range_air = $this->m_desktop_unit->get_range_air($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));        
        $golongan = $this->m_desktop_unit->get_golongan($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));        
        $sub_golongan = $this->m_desktop_unit->get_sub_golongan_lingkungan($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));        
        $sub_golongan += $this->m_desktop_unit->get_sub_golongan_air($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));        
        $kawasan = $this->m_desktop_unit->get_kawasan($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));  
        $blok = $this->m_desktop_unit->get_blok($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));
        $customer = $this->m_desktop_unit->get_customer($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));
        $unit = $this->m_desktop_unit->get_unit($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));
        $unit_lingkungan = $this->m_desktop_unit->get_unit_lingkungan($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));
        $unit_air = $this->m_desktop_unit->get_unit_air($this->input->post('project_id'),
                                    $this->input->post('source'),
                                    $this->input->post('formula_air'),
                                    $this->input->post('formula_bangunan'),
                                    $this->input->post('formula_kavling'));
        

        echo json_encode([
            "range_lingkungan" => $range_lingkungan ,
            "range_air" => $range_air ,
            "golongan" => $golongan ,
            "sub_golongan" => $sub_golongan ,
            "kawasan" => $kawasan ,
            "blok" => $blok ,
            "customer" => $customer ,
            "unit" => $unit ,
            "unit_lingkungan" => $unit_lingkungan ,
            "unit_air" => $unit_air
        ]);
    }
}