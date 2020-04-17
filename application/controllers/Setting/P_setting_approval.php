<?php
defined('BASEPATH') or exit('No direct script access allowed');

class P_setting_approval extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_login');
        if (!$this->m_login->status_login())
            redirect(site_url());
        $this->load->model('Setting/Akun/m_permission_dokumen',"m_dokumen");
        $this->load->model('m_core');
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();
    }
    public function index(){
        $data_group_user = $this->m_dokumen->get_group_user();
        // echo("<pre>");
        //     print_r($data_group_user);
        // echo("</pre>");
        $data_selected = $this->m_dokumen->get_selected($GLOBALS['project']->id);
        // $view = "proyek/setting/akun/Permission_Dokumen/edit_tipe2";
        $data_user = $this->m_dokumen->get_user();
        $view = "proyek/setting/akun/Permission_Dokumen/edit";
        $this->load->model('Setting/Akun/m_group');
        // $data = $this->m_dokumen->get_by_id($id);

        $data_range = $this->m_dokumen->get_range($GLOBALS['project']->id,1);
        $data_range_mengetahui = $this->m_dokumen->get_range($GLOBALS['project']->id,0);        
        $data_jabatan = $this->m_dokumen->get_jabatan();
        $this->load->view('core/header');
        $this->load->model('alert');
        $this->alert->css();
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Setting > Akun > Permission Dokumen', 'subTitle' => 'Add']);
        $this->load->view($view,
            [
                "data_range"=>$data_range,
                "data_jabatan"=>$data_jabatan,
                "data_range_mengetahui"=>$data_range_mengetahui,
                "data_user" => $data_user,
                "data_selected" => $data_selected,
                "data_group_user" => $data_group_user
            ]
        );
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function ajax_save(){
        echo(json_encode($this->m_dokumen->save($this->input->get(),$GLOBALS['project']->id)));
    }
    // public function index_test()
    // {
    //     $data = $this->m_dokumen->get_view();
    //     $this->load->view('core/header');
    //     $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
    //     $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
    //     $this->load->view('core/body_header', ['title' => 'Setting > Akun > Permission Dokumen', 'subTitle' => 'List']);
    //     $this->load->view('proyek/setting/akun/Permission_Dokumen/view', ['data' => $data]);
    //     $this->load->view('core/body_footer');
    //     $this->load->view('core/footer');
    // }
    // public function index($tipe=1)
    // {
    //     $id = 1;
    //     $data_user = '';
    //     $data_user_selected = '';
    //     if($tipe == 1){
    //         $view = "proyek/setting/akun/Permission_Dokumen/edit";
    //     }else{
    //         $view = "proyek/setting/akun/Permission_Dokumen/edit_tipe2";
    //         $data_user = $this->m_dokumen->get_user();
    //         $data_user_selected = $this->m_dokumen->get_by_id_tipe2($id,$GLOBALS['project']->id);
    //     }
    //         $this->load->model('Setting/Akun/m_group');
    //         $data = $this->m_dokumen->get_by_id($id);

    //         $data_range = $this->m_dokumen->get_range($id,null,1);
    //         $data_range_mengetahui = $this->m_dokumen->get_range($id,null,0);        
    //         $data_jabatan = $this->m_dokumen->get_jabatan();
    //         $this->load->view('core/header');
    //         $this->load->model('alert');
    //         $this->alert->css();
    //         $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
    //         $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
    //         $this->load->view('core/body_header', ['title' => 'Setting > Akun > Permission Dokumen', 'subTitle' => 'Add']);
    //         $this->load->view($view,
    //             [
    //                 "data"=>$data,
    //                 "data_range"=>$data_range,
    //                 "data_jabatan"=>$data_jabatan,
    //                 "data_range_mengetahui"=>$data_range_mengetahui,
    //                 "data_user" => $data_user,
    //                 "data_user_selected" => $data_user_selected
    //             ]
    //         );
    //         $this->load->view('core/body_footer');
    //         $this->load->view('core/footer');
        
    // }
    // public function add()
    // {
    //     $this->load->view('core/header');
    //     $this->load->model('alert');
	// 	$this->alert->css();
    //     $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
    //     $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
    //     $this->load->view('core/body_header', ['title' => 'Setting > Akun > Permission Dokumen', 'subTitle' => 'Add']);
    //     $this->load->view('proyek/setting/akun/Permission_Dokumen/add');
    //     $this->load->view('core/body_footer');
    //     $this->load->view('core/footer');
    // }
    // public function ajax_save(){
    //     echo(json_encode($this->m_dokumen->save($this->input->get(),$GLOBALS['project']->id)));
    // }
    // public function ajax_save_tipe2(){
    //     echo(json_encode($this->m_dokumen->save_tipe2($this->input->get(),$GLOBALS['project']->id)));
    // }
}
