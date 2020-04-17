<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_testing extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		
		//var_dump($this->session->userdata('name'));
    }
	public function index()
	{
        $menu = $this->m_core->menu();
		$this->load->view('core/header');
		$this->load->view('core/side_bar2',['menu' => $menu]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Dashboard']);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
}
