<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('m_login');
    }
	public function index()
	{
		echo(md5("admin"));
		if(!$this->m_login->status_login()){
			$this->load->view('core/login');
		}else{
			//ketika user dan password salah
			redirect(site_url()."/dashboard");	
		}

	}
	public function proses(){
		$cek_user = $this->m_login->cek_user($this->input->post('username'),$this->input->post('password'));
		
		if($cek_user){
			$set_session = $this->m_login->set_session(
							$cek_user->name,
							$cek_user->username,
							$cek_user->password,
							$cek_user->group_user_id,
							$cek_user->unit_id
						);
			if($cek_user->unit_id !=null){
				redirect(site_url()."/Customer");
			}
			//ketika user dan password benar
			var_dump($set_session);
			redirect(site_url()."/Dashboard");
		}else{
			//ketika user dan password salah
			redirect($this->index());	
		}
	}

	public function proses2(){

		$user_login = $this->input->post('user_login');
		$ces_id = substr($user_login,0,strpos($user_login,"~#~"));
		$username = substr($user_login,strpos($user_login,"~#~")+3);
		
		$login= $this->db	
					->select("*")
					->from("user")
					->where("ces_id",$ces_id)
					->where("username",$username)
					->get()->row();
		if($login){
			$set_session = $this->m_login->set_session(
				$login->name,
				$login->username,
				$login->password,
				$login->group_user_id,
				$login->unit_id
			);
			if($login->unit_id !=null){
				redirect(site_url()."/Customer");
			}
			//ketika user dan password benar
			var_dump($set_session);
			redirect(site_url()."/Dashboard");
		}else{
			//ketika user dan password salah
			redirect($this->index());	
		}
	}
	public function status(){
		$cek_user = $this->m_login->status_login();
		var_dump($cek_user);
	}
	public function logout(){
		$this->m_login->unset_session();
		// redirect($this->index());
		redirect("https://ces.ciputragroup.com");

	}
	
	
}
