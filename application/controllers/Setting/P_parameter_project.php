<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_parameter_project extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Setting/m_parameter_project');
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
        $project = $this->m_core->project();

        $data = $this->m_parameter_project->get_view($project->id);
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Parameter Project', 'subTitle' => 'List']);
        $this->load->view('proyek/setting/Parameter_Project/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function edit()
    {
        $project = $this->m_core->project();

        $this->load->model('Setting/Akun/m_group');
        $data = $this->m_parameter_project->get_by_jenis_id($this->input->get("id"),$project->id);
        $this->load->view('core/header');
        $this->load->model('alert');
		$this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Parameter Project', 'subTitle' => 'Edit']);

        $this->load->view('proyek/setting/Parameter_Project/edit',
            [
                "data"=>$data
            ]
        );
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function ajax_save(){
        $project = $this->m_core->project();
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D','%7B','%7D','+','%0D%0A');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]","{","}"," ",'\n');
        $value= urlencode($this->input->post('value'));
        $value =  str_replace($entities, $replacements, $value);
        
        echo(json_encode($this->m_parameter_project->save($this->input->post(),$this->input->post('value'),$this->input->get("id"),$project->id)));
    }
    public function ajax_save_img(){
        $project = $this->m_core->project();
        
        $config['upload_path'] = "./files/ttd/konfirmasi_tagihan"; //path folder file upload
		$config['allowed_types'] = 'gif|jpg|png|jpeg'; //type file yang boleh di upload
		$config['encrypt_name'] = TRUE; //enkripsi file name upload
		$this->load->library('upload', $config); //call library upload 
		if ($this->upload->do_upload("file")) { //upload file
			$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
            $nama_file = $data['upload_data']['file_name']; //set file name ke variable image
            // $this->input->post("value") = $nama_file;
            echo(json_encode($this->m_parameter_project->save($this->input->get(),$nama_file,$this->input->get("id"),$project->id)));
			// echo(json_encode($this->m_pemutihan->save($this->input->get(),$nama_file)));
		}else{
			echo(json_encode(false));
		}
    }
}
