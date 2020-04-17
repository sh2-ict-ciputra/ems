<?php

defined('BASEPATH') or exit('No direct script access allowed');

class P_kirim_konfirmasi_tagihan  extends CI_Controller
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
        $this->load->model('Setting/m_parameter_project');

    }

    public function index()
    {
        $project = $this->m_core->project();
        $data = $this->db->select("*")
                        ->from("v_list_kirim_tagihan")
                        ->join("v_sales_force_bill",
                                "v_sales_force_bill.unit_id = v_list_kirim_tagihan.unit_id
                                AND v_sales_force_bill.periode = v_list_kirim_tagihan.periode")
                        ->join("unit",
                                "unit.id = v_list_kirim_tagihan.unit_id")
                        ->where("v_list_kirim_tagihan.periode = '".date("Y-m-01")."'")
                        ->where("unit.project_id",$project->id)
                        ->get()->result();
        $this->load->helper('directory');
        $map = directory_map('./application/pdf');
        $i = 0;
        foreach($data as $k => $v){
            $data[$k]->send_sms = $this->db->select("*")
                                    ->from("send_sms")
                                    ->where("unit_id",$v->unit_id)
                                    ->order_by("create_date DESC,id DESC")
                                    ->get()->row();
            $unit_id_periode = $v->unit_id."_".date("Y-m-");
            $result = preg_grep("/^$unit_id_periode/i", $map);
            $data[$k]->name_file = end($result);
            $data[$k]->email = end($result)?1:0;
        }
        // echo("<pre>");
        //     print_r($data);
        // echo("</pre>");
        
        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service > Kirim Konfirmasi Tagihan', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi/kirim_konfirmasi_tagihan/view',['data'=>$data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function test(){


        
        // response post
        // 90026993                      = Harus Cek Report
        // Invalid MSISDN                = Failed / Nomor Salah
        // Sorry anda tidak punya akses  = Salah Username/Password

        // report
        // 22,                          = Sukses Terikirim
        // 50,Failed                    = Gagal
        // 51                           = Periode Habis
        // 52                           = Kesalahan Format Nomor Tujuan
        // 20                           = Pesan Pending Terkirim, nomor tujuan tidak aktif dalam waktu tertentu
    }
    public function kirim_sms(){
        $this->load->model('m_unit');
        $this->load->model('m_customer');

        $unit_id_array = $this->input->post("unit_id[]");
        $project = $this->m_core->project();
        $this->load->library('curl');
        $template_sms = $this->m_parameter_project->get($project->id,"template_sms_konfirmasi_tagihan");
        foreach ($unit_id_array as $k => $unit_id) {
            $data_unit = $this->m_unit->getSelect($unit_id);

            $message = $template_sms;
            $blok = $this->db->select("name")->from("blok")->where("id",$data_unit->blok)->get()->row()->name;
            $kawasan = $this->db->select("name")->from("kawasan")->where("id",$data_unit->kawasan)->get()->row()->name;
            
            $total_tagihan = $this->db->select("sum(tagihan_air + tagihan_lingkungan + total_denda) as total_tagihan")
                                        ->from("v_sales_force_bill")
                                        ->where("unit_id",$unit_id)
                                        ->get()->row()->total_tagihan;
            
            $message = str_replace("{{Blok}}",$blok."/".$data_unit->no_unit,$message);
            $message = str_replace("{{Kawasan}}",$kawasan,$message);
            $message = str_replace("{{Total_tagihan}}",number_format($total_tagihan,0,",","."),$message);
            
            $data_customer = $this->m_customer->getSelect($data_unit->pemilik);
            $data_customer->mobilephone1 = preg_replace("/[^0-9]/","",$data_customer->mobilephone1);
            // echo("<pre>");
            //     print_r($data_unit);
            // echo("</pre>");
            // echo("<pre>");
            //     print_r($data_customer);
            // echo("</pre>");
            // echo("<pre>");
            //     print_r($message);
            // echo("</pre>");
            // die;
            // $url = "http://103.16.199.187/masking/send_post.php";
            $url = $this->m_parameter_project->get($project->id,"sms_gateway_host");
            $rows = array (
                'username' => $this->m_parameter_project->get($project->id,"sms_gateway_user"),
                'password' => $this->m_parameter_project->get($project->id,"sms_gateway_pass"),
                'hp' => $data_customer->mobilephone1,
                'message' => $message
                // 'message' => 'testing SMS'
            );
            $source_id = $this->curl->simple_post($url,$rows);
            $i = 0;
            do{
                $result = $this->curl->simple_get("http://103.16.199.187/masking/report.php?rpt=$source_id");    
                $i++;
            }while($result == "Success Send" && $i < 100);
            $send_sms = [
                "unit_id"       => $unit_id,
                "no"            => $data_customer->mobilephone1,
                "source_id"     => $source_id,
                "status_full"   => $result,
                "create_date"   => date("Y-m-d"),
                "message"       => $message,
                "jenis_id"      => 1,
                "status_flag"   => (int)$result
            ];
            $this->db->insert("send_sms",$send_sms);
            echo("<pre>");
                print_r($rows);
            echo("</pre>");
            echo("<pre>");
                print_r($result);
            echo("</pre>");
            echo("Success ");

        
        }
    }
    public function test2(){
        // $data = "{\"isi\":\"Kepata Yth.\r\nBapak\/Ibu JUHARIAH\r\nProject CitraLand Cibubur\r\nKawasan MONTEVERDE APHANDRA\r\nBlok A.02\/08\r\n\r\nDengan Hormat,\r\nTerlampir detail tagihan IPLK & AIR\r\nBulan JANUARI JAN  sampai AGUSTUS 2019 Tahun 2019\r\n\r\nTerimakasih atas kesetiaan dan\r\nkepercayaan Anda bersama \r\nCitraLand Cibubur\r\n\r\nSalam,\r\nCitraLand Cibubur\",\"name_file\":\"19_2019-08-19_15-46-55.pdf\"}";
        $data ='{"isi":"Kepata Yth.\r\nBapak\/Ibu JUHARIAH\r\nProject CitraLand Cibubur\r\nKawasan MONTEVERDE APHANDRA\r\nBlok A.02\/08\r\n\r\nDengan Hormat,\r\nTerlampir detail tagihan IPLK & AIR\r\nBulan JANUARI JAN  sampai AGUSTUS 2019 Tahun 2019\r\n\r\nTerimakasih atas kesetiaan dan\r\nkepercayaan Anda bersama \r\nCitraLand Cibubur\r\n\r\nSalam,\r\nCitraLand Cibubur","name_file":"19_2019-08-19_15-46-55.pdf"}';

        echo("<pre>");
            print_r($data);
        echo("</pre>");
        $data = json_decode($data);
        echo(json_encode($data->isi));
        
    }
    public function kirim_email(){
        $unit_id_array = $this->input->post("unit_id[]");
        // echo("<pre>");
        //     print_r($unit_id_array);
        // echo("</pre>");
        $project = $this->m_core->project();
        $email_success = 0;
        foreach ($unit_id_array as $k => $unit_id) {
            
            $this->load->library('curl');
            $isi_konfirmasi_tagihan = [
                "project_id"  => $project->id,
                "isi"=>$this->m_parameter_project->get($project->id,"isi_konfirmasi_tagihan")
            ];

            
            $result = $this->curl->simple_post(site_url()."/Cetakan/konfirmasi_tagihan_api/send/".$unit_id,$isi_konfirmasi_tagihan);
            // var_dump(site_url()."/Cetakan/konfirmasi_tagihan_api/send/".$unit_id."?isi=$isi_konfirmasi_tagihan");

            echo("result<pre>");
                print_r($result);
            echo("</pre>");
            $result = json_decode($result);
            if($result){
                var_dump($result);
                var_dump($result->name_file);
                
                $result->name_file = str_replace("\"","",$result->name_file);
                $config = [
                    'mailtype'  => 'html',
                    'charset'   => 'utf-8',
                    'protocol'  => 'smtp',
                    'smtp_host' => $this->m_parameter_project->get($project->id,"smtp_host"),
                    'smtp_user' => $this->m_parameter_project->get($project->id,"smtp_user"),
                    'smtp_pass' => $this->m_parameter_project->get($project->id,"smtp_pass"),
                    'smtp_port' => $this->m_parameter_project->get($project->id,"smtp_port"),
                    'crlf'      => "\r\n",
                    'newline'   => "\r\n",
                    'smtp_crypto'	=> "ssl"
                ];
                echo("<pre>");
                    print_r($config);
                echo("</pre>");
                $this->load->library('email',$config);
                // print_r($config);
                // $this->db->selec
                $this->email->from($this->m_parameter_project->get($project->id,"smtp_user"), 'EMS Ciputra');
                
                $email = $this->db
                        ->select("
                        CASE
                            WHEN unit.kirim_tagihan = 1 THEN pemilik.email
                            WHEN unit.kirim_tagihan = 2 THEN penghuni.email
                            WHEN unit.kirim_tagihan = 3 THEN CONCAT(pemilik.email,';',penghuni.email)
                        END as email")
                        ->from("unit")
                        ->join("customer as pemilik",
                            "pemilik.id = unit.pemilik_customer_id",
                            "LEFT")
                        ->join("customer as penghuni",
                            "penghuni.id = unit.penghuni_customer_id 
                            AND penghuni.id != pemilik.id",
                            "LEFT")
                        
                        ->where("unit.id",$unit_id)->get()->row()->email;
                $email = explode(";",$email);
                $parameter_delay = explode(";",$this->m_parameter_project->get($project->id,"delay_email"));
                
                foreach ($email as $k=>$v) {
                    if($k!=0 && ($k+1)%$parameter_delay[0]==0){
                        sleep($parameter_delay[1]);
                    }
                    $this->email->clear(TRUE);
                    $this->email->from($this->m_parameter_project->get($project->id,"smtp_user"), 'EMS Ciputra');
                    $this->email->subject($this->m_parameter_project->get($project->id,"subjek_konfirmasi_tagihan"));
                    $this->email->message(($result->isi));
                    $this->email->to($v);
                    $this->email->attach("application/pdf/$result->name_file");
                    $status = $this->email->send();
                    if($status){
                        echo("Success ".$result->name_file);
                        $email_success++;
                    }else{
                        echo("Gagal  ".$result->name_file);
                    }
                    var_dump($v."->".$status);
                }    
                    
            }
        }
    }
}
