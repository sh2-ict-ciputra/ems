<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_kirim_tagihan  extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('m_core');
        $this->load->model('m_login');
        if (!$this->m_login->status_login()) {
            redirect(site_url());
        }
        global $jabatan;
        $jabatan = $this->m_core->jabatan();
        global $project;
        $project = $this->m_core->project();
        global $menu;
        $menu = $this->m_core->menu();
    }

    public function index()
    {
        echo("a");

        $this->load->library('email');
        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.gmail.com';
        $config['smtp_user'] = 'emsciputa@gmail.com';
        $config['smtp_pass'] = 'antihack22';
        $config['smtp_port'] = 25;
        $this->email->initialize($config);
        $this->email->from('emsciputra@gmail.com', 'EMS Ciputra');
        $this->email->to('rfajrika22@gmail.com');
        $this->email->subject('Send Email Codeigniter');
        $this->email->message('The email send using codeigniter library');
        if($this->email->send())
            $this->session->set_flashdata("email_sent","Congragulation Email Send Successfully.");
        else
            $this->session->set_flashdata("email_sent","You have encountered an error");
        $this->load->view('contact_email_form');
    }

}
