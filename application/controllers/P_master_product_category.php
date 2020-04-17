<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_product_category extends CI_Controller {
    function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_product_category');
		$this->load->library('session');
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();
    }

    public function index(){
		$data = $this->m_product_category->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Town Planning > Purpose Use','subTitle' => 'List']);
		$this->load->view('proyek/master/product_category/view',['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }

    public function add()
	{
		$this->load->model('m_product_category');
		$dataProductCategory = $this->m_product_category->get_all_product_category();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Town Planning > Purpose Use', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/product_category/add',['dataProductCategory' => $dataProductCategory]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }

    public function save()
	{	
		$status = $this->m_product_category->save([
			'name'                          => $this->input->post('name'),
			'keterangan'                    => $this->input->post('keterangan'),
			'active'                        => $this->input->post('status')	
		]);

		
		$this->load->model('alert');
		$this->alert->css();
		$this->load->model('m_product_category');
		$dataProductCategory = $this->m_product_category->get_all_product_category();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Town Planning > Purpose Use ', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/product_category/add',['dataProductCategory' => $dataProductCategory]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Tambah','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal','text'=>'Data Inputan suda Ada','type'=>'danger']);
					
    }

    public function edit()
	{
		$status = 0;
		if($this->input->post('name')){
			$this->load->model('alert');
			$status = $this->m_product_category->edit([
			'id'				=> $this->input->get('id'),
			'name'				=> $this->input->post('name'),
			'keterangan'      	=> $this->input->post('keterangan'),
			'active'         	=> $this->input->post('status')
			]);
			$this->alert->css();
		}

		if($this->m_product_category->cek($this->input->get('id'))){
			$dataProductCategory = $this->m_product_category->get();
			$dataProductCategorySelect = $this->m_product_category->getSelect($this->input->get('id'));
			$this->load->model('m_log');
			$data = $this->m_log->get('product_category',$this->input->get('id'));
			$this->load->view('core/header');
			$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header',['title' => 'Master > Town Planning > Purpose Use', 'subTitle' => 'Edit']);
			$this->load->view('proyek/master/product_category/edit',['dataProductCategory' => $dataProductCategory,'data_select'=>$dataProductCategorySelect,'data' => $data ]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
		}else{
			redirect(site_url().'/product_category');	
		}
		
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Update','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal | Double','text'=>'Data Inputan sudah Ada','type'=>'danger']);
			
	}

	public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_product_category->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_product_category->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Town Planning > Purpose Use','subTitle' => 'List']);
		$this->load->view('proyek/master/product_category/view',['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } 
    }

}
?>