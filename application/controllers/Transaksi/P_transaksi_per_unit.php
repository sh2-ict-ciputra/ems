<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_transaksi_per_unit extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('transaksi/m_meter_air');
		$this->load->model('transaksi/m_transaksi_per_unit');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
    }
	public function index()
	{
		$kode_cust = "CG/CUST/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_meter_air->last_id()+1);
		$dataPT = $this->m_meter_air->getPT();
		$kawasan = $this->m_meter_air->getKawasan();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > Pencatatan Meter Air > Meter Air','subTitle' => 'List']);
		$this->load->view('proyek/transaksi/transaksi_per_unit/view',['dataPT' => $dataPT,'kode_cust'=>$kode_cust,'kawasan'=>$kawasan]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function ajax_get_blok(){
		echo json_encode($this->m_meter_air->ajax_get_blok($this->input->get('id')));
	}
	public function ajax_get_unit(){
		echo json_encode($this->m_transaksi_per_unit->ajax_get_unit($this->input->get('kawasan'),$this->input->get('blok')));
	}
	public function ajax_save_meter(){
		echo json_encode($this->m_meter_air->ajax_save_meter($this->input->get('meter'),$this->input->get('periode'),$this->input->get('unit_id')));
	}
}
