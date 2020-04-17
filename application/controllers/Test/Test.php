<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{
	public function index()
	{
		$this->load->database();
		$this->db->trans_begin();
		$this->db->insert('testing',[
			'nomor' => 1
		]);
		$a = $this->db->insert_id();
		$this->db->insert('testing',[
			'nomor' =>$a 
		]);
		$this->db->insert('testing',[
			'nomor' =>$a 
		]);
		$this->db->trans_commit();

	}
}
