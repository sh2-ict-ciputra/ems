<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class laporan_pdf extends CI_Controller {
    
    public function index(){

        $data = array(
            "dataku" => array(
                "nama" => "Petani Kode",
                "url" => "http://petanikode.com"
            )
        );
    

        
        $this->load->library('pdf');


        $this->pdf->set_option('defaultMediaType', 'all');
        $this->pdf->set_option('isFontSubsettingEnabled', true);
        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "laporan-petanikode.pdf";
        $this->pdf->load_view('konfirmasi_tagihan', $data);
    
    
    }
    public function a(){
    
        $this->load->view("konfirmasi_tagihan");
    }
}
