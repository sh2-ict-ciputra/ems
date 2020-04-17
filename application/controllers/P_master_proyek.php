<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_proyek extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_proyek');
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
		$data = $this->m_proyek->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Town Plaining > Proyek','subTitle' => 'List']);
		$this->load->view('proyek/master/proyek/view' , ['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function add()
	{
		$this->load->model('m_proyek');
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Town Plaiining > Proyek', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/proyek/add');
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function save()
	{	
		$status = $this->m_customer->mapping_save([
			
            'subholding_id'                                 => $this->input->post('subholding_id'),
			'code'                                          => $this->input->post('code'),
			'name'                                          => $this->input->post('name'),
			'address'                                       => $this->input->post('address'),
			'zipcode'                                       => $this->input->post('zipcode'),
			'npwp'                                          => $this->input->post('npwp'),
			'phone'                                         => $this->input->post('phone'),
			'fax'                                           => $this->input->post('fax'),
			'email'                                         => $this->input->post('email'),
			'contactperson'                                 => $this->input->post('contactperson'),
			'active'                                        => $this->input->post('active'),
			'user_tambah'                                   => $this->input->post('user_tambah'),
			'tgl_tambah'                                    => $this->input->post('tgl_tambah'),
			'user_ubah'                                     => $this->input->post('user_ubah'),
			'is_transfer'                                   => $this->input->post('is_transfer'),
			'businessgroup_id'                              => $this->input->post('businessgroup_id'),
			'periode_cutoff'                                => $this->input->post('periode_cutoff'),
			'api_aci'                                       => $this->input->post('api_aci'),
			'alias'                                         => $this->input->post('alias'),
			
			
			
	
				
		]);

		$this->load->model('alert');
		$this->load->model('m_proyek');
		$dataRangeair = $this->m_proyek->get();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Town Plaining > Proyek', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/proyek/add',['dataProyek' => $dataProyek]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Tambah','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal','text'=>'Data Inputan suda Ada','type'=>'danger']);
					
	}
	public function get_ajax_proyek_source(){
		echo json_encode($this->m_proyek->get_ajax_proyek_source($this->input->post('source')));
	}
}
