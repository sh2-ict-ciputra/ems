<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_history_pembayaran_harian extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('transaksi/m_history_pembayaran_harian','m_history');
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
		$data = $this->m_history->getAll();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Transaksi Service > History Pembayaran Harian','subTitle' => 'List Per User']);
		$this->load->view('proyek/transaksi/history_pembayaran_harian/view',['data'=>$data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}

	
}
