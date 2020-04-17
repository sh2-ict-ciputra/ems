<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_tagihan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if (!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('transaksi/m_tagihan');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
	}
	public function kawasan()
	{
		$data = $this->m_tagihan->get_kawasan();

		$this->load->view('core/header');
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Transaksi > Tagihan > Total Rencana > Per Kawasan', 'subTitle' => 'List']);
		$this->load->view('proyek/transaksi/tagihan/view_kawasan', ['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function blok()
	{
		//kawasan di butuh kan untuk inputan dalam view		
		$kawasan = $this->m_tagihan->get_kawasan();
		$kawasan_id = $this->input->get('id')?$this->input->get('id'):0;
		$periode = $this->input->get('periode')?$this->input->get('periode'):0;
		$this->load->view('core/header');
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Transaksi > Tagihan > Per Blok', 'subTitle' => 'List']);
		$this->load->view('proyek/transaksi/tagihan/view_blok', ['kawasan' => $kawasan,'get_kawasan_id'=>$kawasan_id,'get_periode' => $periode]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}

	public function unit()
	{
		//blok di butuh kan untuk inputan dalam view
		$kawasan = $this->m_tagihan->get_kawasan();		
		$blok = $this->m_tagihan->get_blok();
		$blok_id = $this->input->get('id')?$this->input->get('id'):0;
		$periode = $this->input->get('periode')?$this->input->get('periode'):0;
		$this->load->view('core/header');
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Transaksi > Tagihan > Per Unit', 'subTitle' => 'List']);
		$this->load->view('proyek/transaksi/tagihan/view_unit', ['blok' => $blok,'get_blok_id'=>$blok_id,'get_periode'=>$periode,'kawasan'=>$kawasan]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function unit_detail()
	{	
		$periode_old = $this->input->get('periode');
		$periode = $this->input->get('periode');
		$periode = substr('0'.(((int)substr($this->input->get('periode'),0,2))),-2)."/".substr($this->input->get('periode'),3,4);

		$periode = $periode?$periode:0;
		$unit_id = $this->input->get("id");
		$unit = $this->m_tagihan->get_unit_by_id($unit_id);
		$tagihan_lingkungan = $this->m_tagihan->get_unit_detail_lingkungan($unit_id,$periode);
		$tagihan_air = $this->m_tagihan->get_unit_detail_air($unit_id,$periode);
		
		$periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
		
		$this->load->view('core/header');
		
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Transaksi > Tagihan > Per Unit Detail', 'subTitle' => 'List']);
		$this->load->view('proyek/transaksi/tagihan/view_unit_detail', [
			'unit' => $unit,
			'periode'=>$periode_old,
			'tagihan_air'=>$tagihan_air,
			'tagihan_lingkungan'=>$tagihan_lingkungan
			]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function ajax_get_kawasan()
	{
		$periode = $this->input->get('periode');
		$periode = substr('0'.(((int)substr($this->input->get('periode'),0,2))),-2)."/".substr($this->input->get('periode'),3,4);

		echo json_encode($this->m_tagihan->ajax_get_kawasan($periode));
	}
	public function ajax_get_kawasan2()
	{
		$periode = $this->input->get('periode');
		$periode = substr('0'.(((int)substr($this->input->get('periode'),0,2))),-2)."/".substr($this->input->get('periode'),3,4);

		echo json_encode($this->m_tagihan->ajax_get_kawasan2($periode));
	}
	public function ajax_get_blok()
	{
		$periode = $this->input->get('periode');
		$periode = substr('0'.(((int)substr($this->input->get('periode'),0,2))),-2)."/".substr($this->input->get('periode'),3,4);

		echo json_encode($this->m_tagihan->ajax_get_blok($this->input->get('id'),$periode));
	}
	
	public function ajax_get_blok2()
	{
		$periode = $this->input->get('periode');
		$periode = substr('0'.(((int)substr($this->input->get('periode'),0,2))),-2)."/".substr($this->input->get('periode'),3,4);

		echo json_encode($this->m_tagihan->ajax_get_blok4($this->input->get('id'),$periode));
	}
	public function ajax_get_unit()
	{
		$periode = $this->input->get('periode');
		$periode = substr('0'.(((int)substr($this->input->get('periode'),0,2))),-2)."/".substr($this->input->get('periode'),3,4);

		echo json_encode($this->m_tagihan->ajax_get_unit($this->input->get('id'),$periode));
	}
	public function ajax_get_unit2()
	{
		$periode = $this->input->get('periode');
		$periode = substr('0'.(((int)substr($this->input->get('periode'),0,2))),-2)."/".substr($this->input->get('periode'),3,4);

		echo json_encode($this->m_tagihan->ajax_get_unit2($this->input->get('id'),$periode));
	}
	public function ajax(){
		$this->load->view('proyek/transaksi/tagihan/view_ajax', ['phrase' => $this->input->get("phrase")]);
	}
}
