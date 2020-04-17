<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_ubah_tagihan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if (!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('transaksi/m_pembayaran');
		$this->load->model('transaksi/m_deposit');
		
		$this->load->model('m_core');
		global $jabatan;
		$jabatan = $this->m_core->jabatan();
		global $project;
		$project = $this->m_core->project();
		global $menu;
		$menu = $this->m_core->menu();

		ini_set('memory_limit','256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');

	}
	public function index()
	{
		$this->load->view('core/header');
		$this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
		$this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
		$this->load->view('core/body_header', ['title' => 'Transaksi Service > Pembayaran Tagihan', 'subTitle' => 'List']);
		$this->load->view('Proyek/Transaksi/Pembayaran/view');
		$this->load->view('core/body_footer');
		$this->load->view('core/footer');
    }
    public function edit($unit_id = 0)
	{
        $this->load->model("core/m_tagihan");
		// $tagihan_air = $this->m_tagihan->air($GLOBALS['project']->id,['status_tagihan'=>[0],'unit_id'=>[$unit_id],'periode'=> date("Y-m-d")]);
		$tagihan_lingkungan = $this->m_tagihan->lingkungan($GLOBALS['project']->id,['status_tagihan'=>[0],'unit_id'=>[$unit_id],'periode'=> date("Y-m-d")]);

        $this->load->view('core/header');
        $this->load->model('alert');
        $this->alert->css();
        $this->load->view('core/top_bar_modal', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header_modal', ['title' => 'Transaksi Service > Pembayaran Tagihan', 'subTitle' => 'List']);
        $this->load->view('Proyek/Transaksi/ubah_tagihan/view',[
            'unit_id' => $unit_id,
            'tagihan' => $tagihan_lingkungan
        ]);
        $this->load->view('core/body_footer_modal');
        $this->load->view('core/footer_modal');
    
    }
    public function update($unit_id=null){
        if($this->input->post('method')=='put'){
            $user_id = $this->db->select("id")
                                ->from("user")
                                ->where("username", $this->session->userdata["username"])
                                ->get()->row()->id;
            $this->db->insert('t_delete_tagihan',array(
                'user_id' => $user_id,
                'periode' => $this->input->post('periode'),
                'service_id' => $this->input->post('service_id'),
                'description' => 'ubah nilai tagihan'
            ));
            // $this->db->delete('t_tagihan_lingkungan_info',array('t_tagihan_lingkungan_id' => $this->input->post('tagihan_id')));
            $this->db->where('t_tagihan_lingkungan_id',$this->input->post('tagihan_id'));
            $this->db->update('t_tagihan_lingkungan_detail',[
                'nilai_kavling' => $this->input->post('nilai')/1.1,
                'nilai_bangunan' => 0,
                'nilai_keamanan' => 0,
                'nilai_kebersihan' => 0,
                'nilai_ppn' => 10,
                'ppn_flag' => 1

            ]);
            // $this->db->delete('t_tagihan_lingkungan',array('id' => $this->input->post('tagihan_id')));
            $this->session->set_flashdata('success', "Data Berhasil di Edit"); 
            redirect(site_url("Transaksi/P_ubah_tagihan/edit/$unit_id"));
        }else{
            echo('Maaf anda tidak di izinkan ke halaman ini');
        }
    }
}
