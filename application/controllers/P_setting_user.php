<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_setting_user extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
		//var_dump($this->session->userdata('name'));
    }
	public function index()
	{
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header',['title' => 'Dashboard']);
        $this->load->view('proyek/master/bank/view',['data' => $data]);

		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
}
