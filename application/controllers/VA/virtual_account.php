<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class virtual_account extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('m_login');
		if(!$this->m_login->status_login()) redirect(site_url());
		$this->load->model('transaksi/m_meter_air');
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
        $va = $this->db
                    ->select("unit.id as unit_id,
                    SUBSTRING(CONVERT(varchar(6), YEAR(unit.tgl_st)),3,2) as tahun")
                    ->from("unit")                  
                    ->order_by("unit.id,tahun")
                    ->get()->result();
        $j = 1;
        $va[0]->no = '000001';
        for ($i=1; $i < count($va); $i++) { 
            if($va[$i]->tahun == $va[$i-1]->tahun)    {
                $j++;
                $va[$i]->no = str_pad($j,6,"0",STR_PAD_LEFT);
            }else{
                $j = 1;
                $va[$i]->no = str_pad($j,6,"0",STR_PAD_LEFT);
            }
        }
        $tmp = [];
        foreach ($va as $k => $v) {
            $tmp[$k]=(object)[];
            $tmp[$k]->unit_id = $v->unit_id;
            $tmp[$k]->va = $v->tahun.$v->no;
            
        }
        echo("<pre>");
            print_r($tmp);
        echo("</pre>");
        
		// $data = $this->m_meter_air->get();
		// $this->load->view('core/header');
		// $this->load->view('core/side_bar',['menu' => $GLOBALS['menu']]);
		// $this->load->view('core/top_bar',['jabatan' => $GLOBALS['jabatan'],'project' => $GLOBALS['project']]);
		// $this->load->view('core/body_header',['title' => 'Transaksi Service > Pencatatan Meter Air > Meter Air','subTitle' => 'List']);
		// $this->load->view('proyek/transaksi/meter_air/view',['data' => $data]);
		// $this->load->view('core/body_footer');
		// $this->load->view('core/footer');
	}

}
