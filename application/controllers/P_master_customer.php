<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class P_master_customer extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('m_customer');
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
		ini_set('memory_limit', '256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288'); // Setting to 512M - for pdo_sqlsrv
        ini_set('max_execution_time','-1'); // Setting to 512M - for pdo_sqlsrv

		// $data = $this->m_customer->get();
		$this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header',['title' => 'Master > Customer','subTitle' => 'List']);
		$this->load->view('proyek/master/customer/view');
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	public function add()
	{	
		// $kode_cust = "CG/CUST/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_customer->last_id()+1);
		$project = $this->m_core->project();

		$kode_cust = "CUST/".$project->code."/".date("Y")."/".str_pad(($this->m_customer->last_id()+1), 4, "0", STR_PAD_LEFT);
		
		$dataPT = $this->m_customer->getPT();
		$this->load->model('m_code_country_telp');
		$dataCodeTelp = $this->m_code_country_telp->get();
	    $this->load->view('core/header');
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Customer', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/customer/add',['dataCodeTelp'=>$dataCodeTelp,'dataPT' => $dataPT,'kode_cust'=>$kode_cust]);
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
	}
	
	
	public function save()
	{	
		$status = $this->m_customer->save([
			'code' 			=> $this->input->post('code'),
			'pt_id' 		=> $this->input->post('pt_id'),
			'gol_diskon_id' => $this->input->post('gol_diskon_id'),
			'unit' 			=> $this->input->post('unit'),
			'name' 			=> $this->input->post('name'),
			'address' 		=> $this->input->post('alamat_domisili'),
			'email' 		=> $this->input->post('email'),
			'ktp' 			=> $this->input->post('nik'),
			'ktp_address' 	=> $this->input->post('alamat_ktp'),
			'mobilephone1' 	=> $this->input->post('mobile_phone_1'),
			'mobilephone2' 	=> $this->input->post('mobile_phone_2'),
			'homephone' 	=> str_replace('_','',$this->input->post('home_phone')),
			'officephone' 	=> $this->input->post('office_phone'),
			'npwp_no' 		=> $this->input->post('nomor_npwp'),
			'npwp_name' 	=> $this->input->post('nama_npwp'),
			'npwp_address' 	=> $this->input->post('alamat_npwp'),
			'description' 	=> $this->input->post('keterangan'),
			// 'expired_diskon'=> $this->input->post('expired_diskon')?explode('/',$this->input->post('expired_diskon'))[0].'/01/'.explode('/',$this->input->post('expired_diskon'))[1]:null,
			'active' 		=> 1,
			'delete' 		=> 0
		]);
		$this->load->model('alert');
		$this->load->model('m_golongan');

		$this->load->view('core/header');
		$this->alert->css();
		$project = $this->m_core->project();

		// $kode_cust = "CG/CUST/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_customer->last_id()+1);
		// $kode_cust = "CG/CUST/".date("Y")."/".str_pad(($this->m_customer->last_id()+1), 4, "0", STR_PAD_LEFT);
		$kode_cust = "CUST/".$project->code."/".date("Y")."/".str_pad(($this->m_customer->last_id()+1), 4, "0", STR_PAD_LEFT);

		$dataPT = $this->m_customer->getPT();
		$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar');
		$this->load->view('core/body_header',['title' => 'Master > Customer', 'subTitle' => 'Add']);
		$this->load->view('proyek/master/customer/add',['dataPT' => $dataPT,'kode_cust'=>$kode_cust]);
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
        if ($this->input->post('code')) {
			$this->load->model('alert');
			

			

            $status = $this->m_customer->edit([
				'id' 			=> $this->input->get('id'),
				'code' 			=> $this->input->post('code'),
				'pt_id' 		=> $this->input->post('pt_id'),
				'gol_diskon_id' => $this->input->post('gol_diskon_id'),
				'unit' 			=> $this->input->post('unit'),
				'name' 			=> $this->input->post('name'),
				'address' 		=> $this->input->post('alamat_domisili'),
				'email' 		=> $this->input->post('email'),
				'ktp' 			=> $this->input->post('nik'),
				'ktp_address' 	=> $this->input->post('alamat_ktp'),
				'mobilephone1' 	=> $this->input->post('mobile_phone_1'),
				'mobilephone2' 	=> $this->input->post('mobile_phone_2'),
				'homephone' 	=> $this->input->post('home_phone'),
				'officephone' 	=> $this->input->post('office_phone'),
				'npwp_no' 		=> $this->input->post('nomor_npwp'),
				'npwp_name' 	=> $this->input->post('nama_npwp'),
				'npwp_address' 	=> $this->input->post('alamat_npwp'),
				// 'expired_diskon'=> explode('/',$this->input->post('expired_diskon'))[0].'/01/'.explode('/',$this->input->post('expired_diskon'))[1],
				'description' 	=> $this->input->post('keterangan'),
				'active' 		=> $this->input->post('active'),
				'delete' 		=> 0
            ]);
            $this->alert->css();
        }

        if ($this->m_customer->cek($this->input->get('id'))) {
            $this->load->model('m_log');
			$data = $this->m_log->get('customer', $this->input->get('id'));
			$this->load->view('core/header');			
			// $kode_cust = "CG/CUST/".date("Y")."/".$this->m_core->numberToRomanRepresentation($this->m_core->project()->id)."/".($this->m_customer->last_id()+1);
			$project = $this->m_core->project();

			// $kode_cust = "CUST/".$project->code."/".date("Y")."/".str_pad(($this->m_customer->last_id()+1), 4, "0", STR_PAD_LEFT);

			// var_dump($this->m_customer->last_id());
			// $kode_cust = "CG/CUST/".date("Y")."/".str_pad(($this->m_customer->last_id()+1), 4, "0", STR_PAD_LEFT);

			$dataPT = $this->m_customer->getPT();
			// $dataGolonganDiskon = $this->m_customer->getDiskon();
			$dataSelect = $this->m_customer->getSelect($this->input->get('id'));

			$this->load->model('m_code_country_telp');
			$dataCodeTelp = $this->m_code_country_telp->get();
			$this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
			$this->load->view('core/top_bar');
			$this->load->view('core/body_header',['title' => 'Master > Customer', 'subTitle' => 'Edit']);
			$this->load->view('proyek/master/customer/edit',[
					'data'=>$data,
					'dataPT' => $dataPT,
					'dataSelect'=> $dataSelect, 
					// 'kode_cust' => $kode_cust, 
					// 'dataGolonganDiskon'=> $dataGolonganDiskon, 
					'dataCodeTelp'=> $dataCodeTelp   ]);
			$this->load->view('core/body_footer');
			$this->load->view('core/footer');
        } else {
            redirect(site_url().'/P_master_customer');
        }

        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Update', 'type' => 'success']);
        } elseif ($status == 'double') {
            $this->load->view('core/alert', ['title' => 'Gagal | Double', 'text' => 'Data Inputan suda Ada', 'type' => 'danger']);
        } 
	}
	public function delete()
    {
        $this->load->model('alert');

        $status = $this->m_customer->delete([
			'id' => $this->input->get('id'),
		]);
		$this->load->view('core/header');
        $this->alert->css();

		ini_set('memory_limit', '256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288'); // Setting to 512M - for pdo_sqlsrv
        ini_set('max_execution_time','-1'); // Setting to 512M - for pdo_sqlsrv

		$data = $this->m_customer->get();

        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Master > Accounting > Mapping COA', 'subTitle' => 'List']);
        $this->load->view('proyek/master/customer/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
        if ($status == 'success') {
            $this->load->view('core/alert', ['title' => 'Berhasil', 'text' => 'Data Berhasil di Delete', 'type' => 'success']);
        } elseif ($status == 'unit') {
            $this->load->view('core/alert', ['title' => 'Gagal', 'text' => 'Data Customer digunakan di Unit', 'type' => 'danger']);
        }
	}
	
    public function ajax_get_view(){
        // echo json_encode($this->m_unit->serverSideUnit());
        $project = $this->m_core->project();

        $table =    "customer
                    WHERE project_id = $project->id";
 
        $primaryKey = 'customer.id';
        
        $columns = array(
            array( 'db' => 'customer.code as code', 'dt' => 0 ),
            array( 'db' => 'customer.name as name',  'dt' => 1 ),
            array( 'db' => 'customer.email as email',   'dt' => 2 ),
            array( 'db' => 'customer.mobilephone1 as mobilephone1',     'dt' => 3 ),
            array( 'db' => 'customer.id as id',     'dt' => 4 )
        );

        // SQL server connection information

        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
        );
		$this->load->library("SSP");
		$table = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns );
		// var_dump($table["data"][0]);
		// echo("<pre>");
		// print_r($table);
		// echo("</pre>");
		foreach ($table["data"] as $k => $v) {
			// var_dump($table["data"][$k][count($v)-1]);
			// var_dump(end($v));
			$table["data"][$k][count($v)-1] = 
				"<a href='" . site_url() . "/P_master_customer/edit?id=".end($v)."' class='btn btn-primary col-md-10'>
					<i class='fa fa-pencil'></i>
				</a>";
			$table["data"][$k][count($v)] = 
				"<a href='#'  class='btn btn-md btn-danger col-md-12' data-toggle='modal' onclick='confirm_modal(".end($v).")' data-target='#myModal'> 
					<i class='fa fa-trash'></i>
				</a>";
		}
		echo(json_encode($table));		
    }
}
