<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_approval extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if (!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_approval');
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
		$data = $this->m_approval->get_view();
		
		$this->load->view('core/header');
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Approval Dokumen', 'subTitle' => 'List']);
		$this->load->view('proyek/approval/view', ['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}

	public function edit()
	{
		if(!$this->m_approval->cek_permission_edit($this->input->get("id"))){
			redirect(site_url()."/P_approval");
		}
        $data = $this->m_approval->get_edit($this->input->get("id"));

		// echo("<pre>");
        //     print_r($data);
        // echo("</pre>");
		$tomorrow = date('Y-m-d H:i:s.000',strtotime(date("Y/m/d H:i:s.000") . "+".(1+1)." days"));
		// var_dump($tomorrow);

		$this->load->view('core/header');
		$this->load->model('alert');
		$this->alert->css();

		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Approval Dokumen', 'subTitle' => 'Edit']);
		$this->load->view('proyek/approval/edit',["data"=>$data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function edit_void_pembayaran(){
		// if(!$this->m_approval->cek_permission_edit($this->input->get("id"))){
		// 	redirect(site_url()."/P_approval");
		// }
        $data = $this->m_approval->get_edit($this->input->get("id"));

		// echo("<pre>");
        //     print_r($data);
        // echo("</pre>");
		$tomorrow = date('Y-m-d H:i:s.000',strtotime(date("Y/m/d H:i:s.000") . "+".(1+1)." days"));
		// var_dump($tomorrow);

		$this->load->view('core/header');
		$this->load->model('alert');
		$this->alert->css();

		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Approval', 'subTitle' => 'List']);
		$this->load->view('proyek/approval/edit',["data"=>$data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function ajax_reject_void_pembayaran(){
		$approval_id = $this->input->post("id");
		echo json_encode($this->m_approval->reject_void_pembayaran($approval_id));
	}
	public function ajax_approve_void_pembayaran(){
		$approval_id = $this->input->post("id");
		echo json_encode($this->m_approval->approve_void_pembayaran($approval_id));
	}
	public function ajax_mengajukan(){
		echo json_encode($this->m_approval->mengajukan($this->input->post("id"),1)); // 1 mengajukan, 0 cancel 
	}
	public function ajax_cencel(){
		echo json_encode($this->m_approval->mengajukan($this->input->post("id"),0)); // 1 mengajukan, 0 cancel 
	}


	public function ajax_approve(){
		echo json_encode($this->m_approval->approve($this->input->post("id"),$this->input->post("deskripsi")));// 1 approve, 0 reject
	}
	public function ajax_reject(){
		echo json_encode($this->m_approval->reject($this->input->post("id"),$this->input->post("deskripsi"),0));// 1 approve, 0 reject
	}


	
}
