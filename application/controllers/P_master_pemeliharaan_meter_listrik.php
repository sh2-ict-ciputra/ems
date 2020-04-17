<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_pemeliharaan_meter_listrik extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_pemeliharaan_meter_listrik');
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
		$data = $this->m_pemeliharaan_meter_listrik->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Pemeliharaan Meter > Pemeliharaan Meter Listrik','subTitle' => 'List']);
		$this->load->view('proyek/master/pemeliharaan_meter_listrik/view' ,['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function add()
	{
		$this->load->model('m_pemeliharaan_meter_listrik');
		$dataPemeliharaanmeterlistrik = $this->m_pemeliharaan_meter_listrik->get();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Pemeliharaan Meter > Pemeliharaan Meter Listrik', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/pemeliharaan_meter_listrik/add',['dataPemeliharaanmeterlistrik' => $dataPemeliharaanmeterlistrik]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function save()
	{	
		$status = $this->m_pemeliharaan_meter_listrik->save([
            'code'                                   => $this->input->post('code'),
			'daya'                                   => $this->input->post('daya'),
			'ppn'                                    => $this->input->post('ppn'),
			'harga_sewa'                             => $this->input->post('harga_sewa'),
			'biaya_pasang_baru'                      => $this->input->post('biaya_pasang_baru'),
			'keterangan'                             => $this->input->post('keterangan'),
		]);

		$this->load->model('alert');
		$this->load->model('m_pemeliharaan_meter_listrik');
		$dataPemeliharaanmeterlistrik = $this->m_pemeliharaan_meter_listrik->get();
		$this->load->view('core/header');
		$this->alert->css();

		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Pemeliharaan Meter > Pemeliharaan Meter Air Listrik', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/pemeliharaan_meter_listrik/add',['dataPemeliharaanmeterlistrik' => $dataPemeliharaanmeterlistrik]);
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
		if($this->input->post('code')){

			$this->load->model('alert');

			$status = $this->m_pemeliharaan_meter_listrik->edit([
			'id'         	                         => $this->input->get('id'),
			'code'                                   => $this->input->post('code'),
			'daya'                                   => $this->input->post('daya'),
			'ppn'                                    => $this->input->post('ppn'),
			'harga_sewa'                             => $this->input->post('harga_sewa'),
			'biaya_pasang_baru'                      => $this->input->post('biaya_pasang_baru'),
			'keterangan'                             => $this->input->post('keterangan'),			
			'status'                                 => $this->input->post('status')
		
			]);
			$this->alert->css();
			
		}
		
		
		
		
		

		if($this->m_pemeliharaan_meter_listrik->cek($this->input->get('id'))){
			$dataMeterListrik = $this->m_pemeliharaan_meter_listrik->get();
			$dataMeterListrikSelect = $this->m_pemeliharaan_meter_listrik->getSelect($this->input->get('id'));
			$this->load->model('m_log');
			$data = $this->m_log->get('pemeliharaan_meter_listrik',$this->input->get('id'));
			$this->load->view('core/header');
			$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header',['title' => 'Master > Pemeliharaan Meter > Pemeliharaan Meter Listrik', 'subTitle' => 'Edit']);
			$this->load->view('proyek/master/pemeliharaan_meter_listrik/edit',['data'=>$data,'dataMeterListrik' => $dataMeterListrik,'data_select'=>$dataMeterListrikSelect ]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
		}else{
			redirect(site_url().'/P_master_pemeliharaan_listrik');	
		}
		
		if($status == 'success')
			$this->load->view('core/alert',['title' => 'Berhasil','text'=>'Data Berhasil di Update','type'=>'success']);
		elseif($status == 'double')
			$this->load->view('core/alert',['title' => 'Gagal | Double','text'=>'Data Inputan sudah Ada','type'=>'danger']);
			
	}
	
	
	public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_pemeliharaan_meter_listrik->delete([
                'id' => $this->input->get('id'),
        ]);

        $this->alert->css();

        $data = $this->m_pemeliharaan_meter_listrik->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Pemeliharaan Meter > Pemeliharaan Meter Listrik','subTitle' => 'List']);
		$this->load->view('proyek/master/pemeliharaan_meter_listrik/view' ,['data' => $data]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } elseif ($status == 'cara_pembayaran') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Cara Pembayaran', 'type' => 'danger']);
        } elseif ($status == 'metode_penagihan') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Metode Penagihan', 'type' => 'danger']);
        } elseif ($status == 'service') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data COA digunakan di Service', 'type' => 'danger']);
        }
    }



}
