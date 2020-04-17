<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_aging extends CI_Controller
{
    function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('transaksi/m_aging');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
		global $unit_id;
		$unit_id = $this->m_core->unit_id();
		ini_set('memory_limit','256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');

    }
    
    public function index()
    {
        $kawasan = $this->m_aging->getKawasan();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > Aging > Aging Service','subTitle' => 'List']);
		$this->load->view('proyek/transaksi/aging/view',['kawasan'=>$kawasan]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }

    public function ajax_get_blok(){
		echo json_encode($this->m_aging->ajax_get_blok($this->input->get('id')));
	}
	public function ajax_get_unit(){
		echo json_encode($this->m_aging->ajax_get_unit($this->input->get('kawasan'),$this->input->get('blok'),$this->input->get('periode')));
	}
	public function ajax_get_aging(){
		$kawasan_id = $this->input->get('kawasan');
		$blok_id = $this->input->get('blok');
		$tgl_aging = $this->input->get('tgl_aging');
		$tgl_aging = substr($tgl_aging,6,4)."-".substr($tgl_aging,3,2)."-".substr($tgl_aging,0,2);
		// var_dump($tgl_aging);
		$result = $this->m_aging->ajax_get_aging($kawasan_id,$blok_id,$tgl_aging);
		echo json_encode($result);
	}
	
}
?>