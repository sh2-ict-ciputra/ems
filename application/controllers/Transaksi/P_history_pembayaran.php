<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_history_pembayaran extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('transaksi/m_history_pembayaran','m_history');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
	}
	
	public function air()
	{
		$this->load->model('transaksi/m_meter_air');
		$kawasan = $this->m_meter_air->getKawasan();
		$cara_bayar = $this->m_history->getCaraPembayaran();
		// $data = $this->m_history->getAir()
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > History Pembayaran Bulanan > Service Air','subTitle' => 'List Per User']);
		$this->load->view('proyek/transaksi/history_pembayaran_harian/viewAir',['kawasan'=>$kawasan,'cara_bayar'=>$cara_bayar]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}

	public function index()
	{
        $this->load->model('transaksi/m_meter_air');
		$kawasan = $this->m_meter_air->getKawasan();
		$cara_bayar = $this->m_history->getCaraPembayaran();
		$service_jenis = $this->m_history->getService();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > Report Pembayaran Service ','subTitle' => 'Report Harian, Bulanan']);
		$this->load->view('proyek/transaksi/history_pembayaran_harian/viewLingkungan',['kawasan'=>$kawasan,'cara_bayar'=>$cara_bayar,'service_jenis'=>$service_jenis]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}

	public function lainnya()
	{
		$data = $this->m_history->getAir();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > History Pembayaran Bulanan > Service Lainnya','subTitle' => 'List Per User']);
		$this->load->view('proyek/transaksi/history_pembayaran_harian/viewLainnya',['data'=>$data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}

	public function ajax_get_all(){
		ini_set('memory_limit', '-1'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288'); // Setting to 512M - for pdo_sqlsrv
        ini_set('max_execution_time','-1'); // Setting to 512M - for pdo_sqlsrv
        $kawasan        = $this->input->get("kawasan");
        $blok           = $this->input->get("blok");
        $periode_awal   = $this->input->get("periode_awal");
		$periode_akhir  = $this->input->get("periode_akhir");
		$cara_bayar  	= $this->input->get("cara_bayar[]");
		$jns_service  = $this->input->get("jns_service[]");
		
		if($jns_service == '1'){
			echo(json_encode($this->m_history->getLingkungan($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar,$jns_service)));
			
		}elseif($jns_service == '2'){
			echo(json_encode($this->m_history->getAir($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar,$jns_service)));
		}

	}

    public function ajax_get_lingkungan(){
        $kawasan        = $this->input->get("kawasan");
        $blok           = $this->input->get("blok");
        $periode_awal   = $this->input->get("periode_awal");
		$periode_akhir  = $this->input->get("periode_akhir");
		$cara_bayar  	= $this->input->get("cara_bayar[]");
    
        echo(json_encode($this->m_history->getLingkungan($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar)));

	}
	
	public function ajax_get_air(){
        $kawasan        = $this->input->get("kawasan");
        $blok           = $this->input->get("blok");
        $periode_awal   = $this->input->get("periode_awal");
		$periode_akhir  = $this->input->get("periode_akhir");
		$cara_bayar  	= $this->input->get("cara_bayar");
        
        echo(json_encode($this->m_history->getAir($kawasan,$blok,$periode_awal,$periode_akhir,$cara_bayar)));

    }
	
}