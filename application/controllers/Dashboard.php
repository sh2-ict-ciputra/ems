<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Setting/Akun/m_group');
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
		$this->load->view('core/header');
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Dashboard']);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
}
