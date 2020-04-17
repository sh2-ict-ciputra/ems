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

        ini_set('memory_limit', '256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288');
    }

    public function index()
    {
        // die;
        $project = $this->m_core->project();
        $periode = date('Y-m');
        $data = $this->db->query(
            "SELECT
                                    unit.id as unit_id,
                                    kawasan.name as kawasan,
                                    blok.name as blok,
                                    unit.no_unit as no_unit,    
                                    CASE unit.kirim_tagihan 
                                        WHEN 1 THEN 'Pemilik'
                                        WHEN 2 THEN 'Penghuni'
                                        WHEN 3 THEN 'Keduanya'
                                        ELSE ''
                                    END as tujuan,
                                    sum(
                                        IIF(t_tagihan_lingkungan.status_tagihan is not null,1,0)
                                        +IIF(t_tagihan_air.status_tagihan is not null,1,0)
                                        ) as count_tagihan,
                                    count(send_sms.id) as send_sms,
                                    0 as surat
                                FROM unit
                                JOIN blok
                                    ON blok.id = unit.blok_id
                                JOIN kawasan
                                    ON kawasan.id = blok.kawasan_id
                                LEFT JOIN t_tagihan_lingkungan
                                    ON t_tagihan_lingkungan.unit_id = unit.id
                                    AND t_tagihan_lingkungan.status_tagihan != 1
                                LEFT JOIN t_tagihan_air
                                    ON t_tagihan_air.unit_id = unit.id
                                    AND t_tagihan_air.status_tagihan != 1
                                LEFT JOIN send_sms
                                    ON send_sms.unit_id = unit.id
                                    AND FORMAT(send_sms.create_date,'yyyy-MM') = '$periode'
                                WHERE unit.project_id = $project->id
                                GROUP BY 
                                    unit.id,
                                    kawasan.name,
                                    blok.name,
                                    unit.no_unit,
                                    CASE unit.kirim_tagihan 
                                        WHEN 1 THEN 'Pemilik'
                                        WHEN 2 THEN 'Penghuni'
                                        WHEN 3 THEN 'Keduanya'
                                        ELSE ''
                                    END
                                HAVING sum(
                                        IIF(t_tagihan_lingkungan.status_tagihan is not null,1,0)
                                        +IIF(t_tagihan_air.status_tagihan is not null,1,0)
                                        )  > 0
                                        "
        )->result();
        // var_dump($this->db->last_query());
        // die;
        $this->load->helper('directory');
        $map = directory_map('./application/pdf');
        foreach ($data as $k => $v) {
            $unit_id_periode = $v->unit_id . "_" . date("Y-m-");
            $result = preg_grep("/^$unit_id_periode/i", $map);
            $data[$k]->name_file = end($result);
            $data[$k]->email = end($result) ? 1 : 0;
        }

        $this->load->view('core/header');
        $this->load->view('core/side_bar', ['menu' => $GLOBALS['menu']]);
        $this->load->view('core/top_bar', ['jabatan' => $GLOBALS['jabatan'], 'project' => $GLOBALS['project']]);
        $this->load->view('core/body_header', ['title' => 'Transaksi Service > Kirim Konfirmasi Tagihan', 'subTitle' => 'List']);
        $this->load->view('proyek/transaksi/kirim_konfirmasi_tagihan/view', ['data' => $data]);
        $this->load->view('core/body_footer');
        $this->load->view('core/footer');
    }
    public function ajax_get_view()
    {
        $project = $this->m_core->project();
        $periode = date('Y-m');

        $table =    "v_kirim_konfirmasi_tagihan
                    WHERE project_id = $project->id
                    ";
        $primaryKey = 'unit_id';
        $columns = array(
            array( 'db' => 'unit_id as unit_id', 'dt' => 0 ),
            array( 'db' => 'kawasan as kawasan',  'dt' => 1 ),
            array( 'db' => 'blok as blok',   'dt' => 2 ),
            array( 'db' => 'no_unit as no_unit',     'dt' => 3 ),
            array( 'db' => "tujuan as tujuan",     'dt' => 4 ),
            array( 'db' => "pemilik as pemilik",     'dt' => 5 ),
            array( 'db' => "'Belum di kirim' as send_email",     'dt' => 6 ),
            array( 'db' => "send_sms as send_sms",     'dt' => 7 ),
            array( 'db' => "send_surat as send_surat",     'dt' => 8 )
        );
        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
        );
        $this->load->library("SSP");

    
        $table = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns );
        $this->load->helper('directory');

        $map = directory_map('./application/pdf');
        
        foreach ($table["data"] as $k => $v) {

            $table["data"][$k][9] = 
                "<button class='btn btn-primary' onClick=\"window.open('" . site_url() . "/Cetakan/konfirmasi_tagihan/unit/" . $table["data"][$k][0] . "')\">View Dokumen</button>";

            $unit_id_periode = $table["data"][$k][0] . "_" . date("Y-m-");
            $result = preg_grep("/^$unit_id_periode/i", $map);
            $table["data"][$k][10] = end($result) 
                ? "<button class='btn btn-primary' onClick=\"window.location.href='" . base_url() . "pdf/".end($result)."'\">View Dokumen</button>" 
                : "";

            $table["data"][$k][0] = 
                "<input name='unit_id[]' type='checkbox' class='flat table-check' val='$v[0]'>";
        }
        echo(json_encode($table));	
    }
    public function test()
    {



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
    public function kirim_sms()
    {
        $this->load->model('m_unit');
        $this->load->model('m_customer');

        $unit_id_array = $this->input->post("unit_id[]");
        $project = $this->m_core->project();
        $this->load->library('curl');
        $template_sms = $this->m_parameter_project->get($project->id, "template_sms_konfirmasi_tagihan");
        foreach ($unit_id_array as $k => $unit_id) {
            $data_unit = $this->m_unit->getSelect($unit_id);

            $message = $template_sms;
            $blok = $this->db->select("name")->from("blok")->where("id", $data_unit->blok)->get()->row()->name;
            $kawasan = $this->db->select("name")->from("kawasan")->where("id", $data_unit->kawasan)->get()->row()->name;

            $total_tagihan = $this->db->select("sum(tagihan_air + tagihan_lingkungan + total_denda) as total_tagihan")
                ->from("v_sales_force_bill")
                ->where("unit_id", $unit_id)
                ->get()->row()->total_tagihan;

            $message = str_replace("{{Blok}}", $blok . "/" . $data_unit->no_unit, $message);
            $message = str_replace("{{Kawasan}}", $kawasan, $message);
            $message = str_replace("{{Total_tagihan}}", number_format($total_tagihan, 0, ",", "."), $message);
            $uid =	$this->db->select("concat(project.source_id,kawasan.code,blok.code,'/',unit.no_unit) as uid")
                        ->from("unit")
                    ->join("project",
                            "project.id = unit.project_id")
                    ->join("blok",
                            "blok.id = unit.blok_id")
                    ->join("kawasan",
                            "kawasan.id = blok.kawasan_id")
                    ->where("unit.id",$unit_id)
                    ->get()->row();
            $uid = $uid?$uid->uid:0;	
            $message = str_replace("{{no_iplk}}",$uid,$message);
            $data_customer = $this->m_customer->getSelect($data_unit->pemilik);
            $data_customer->mobilephone1 = preg_replace("/[^0-9]/", "", $data_customer->mobilephone1);
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
            $url = $this->m_parameter_project->get($project->id, "sms_gateway_host");
            $rows = array(
                'username' => $this->m_parameter_project->get($project->id, "sms_gateway_user"),
                'password' => $this->m_parameter_project->get($project->id, "sms_gateway_pass"),
                'hp' => $data_customer->mobilephone1,
                'message' => $message
                // 'message' => 'testing SMS'
            );
            $source_id = $this->curl->simple_post($url, $rows);
            $i = 0;
            do {
                $result = $this->curl->simple_get("http://103.16.199.187/masking/report.php?rpt=$source_id");
                $i++;
            } while ($result == "Success Send" && $i < 100);
            $send_sms = [
                "unit_id"       => $unit_id,
                "no"            => $data_customer->mobilephone1,
                "source_id"     => $source_id,
                "status_full"   => $result,
                "create_date"   => date("Y-m-d"),
                "message"       => $message,
                "jenis_id"      => 1,
                "status_flag"   => (int) $result
            ];
            $this->db->insert("send_sms", $send_sms);
            echo ("<pre>");
            print_r($rows);
            echo ("</pre>");
            echo ("<pre>");
            print_r($result);
            echo ("</pre>");
            echo ("Success ");
        }
    }
    public function test2()
    {
        // $data = "{\"isi\":\"Kepata Yth.\r\nBapak\/Ibu JUHARIAH\r\nProject CitraLand Cibubur\r\nKawasan MONTEVERDE APHANDRA\r\nBlok A.02\/08\r\n\r\nDengan Hormat,\r\nTerlampir detail tagihan IPLK & AIR\r\nBulan JANUARI JAN  sampai AGUSTUS 2019 Tahun 2019\r\n\r\nTerimakasih atas kesetiaan dan\r\nkepercayaan Anda bersama \r\nCitraLand Cibubur\r\n\r\nSalam,\r\nCitraLand Cibubur\",\"name_file\":\"19_2019-08-19_15-46-55.pdf\"}";
        $data = '{"isi":"Kepata Yth.\r\nBapak\/Ibu JUHARIAH\r\nProject CitraLand Cibubur\r\nKawasan MONTEVERDE APHANDRA\r\nBlok A.02\/08\r\n\r\nDengan Hormat,\r\nTerlampir detail tagihan IPLK & AIR\r\nBulan JANUARI JAN  sampai AGUSTUS 2019 Tahun 2019\r\n\r\nTerimakasih atas kesetiaan dan\r\nkepercayaan Anda bersama \r\nCitraLand Cibubur\r\n\r\nSalam,\r\nCitraLand Cibubur","name_file":"19_2019-08-19_15-46-55.pdf"}';

        echo ("<pre>");
        print_r($data);
        echo ("</pre>");
        $data = json_decode($data);
        echo (json_encode($data->isi));
    }
    public function kirim_email()
    {
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
                "isi" => $this->m_parameter_project->get($project->id, "isi_konfirmasi_tagihan")
            ];

            echo ("test1<pre>");
            print_r($unit_id);
            echo ("</pre>");
            echo ("test2<pre>");
            print_r($isi_konfirmasi_tagihan);
            echo ("</pre>");
            var_dump(site_url() . "/Cetakan/konfirmasi_tagihan_api/send/" . $unit_id);
            $result = $this->curl->simple_post(site_url() . "/Cetakan/konfirmasi_tagihan_api/send/" . $unit_id, $isi_konfirmasi_tagihan);
            // var_dump(site_url()."/Cetakan/konfirmasi_tagihan_api/send/".$unit_id."?isi=$isi_konfirmasi_tagihan");

            echo ("result1<pre>");
            print_r($result);
            echo ("</pre>");
            $result = json_decode($result);
            echo ("result2<pre>");
            print_r($result);
            echo ("</pre>");

            if ($result) {
                echo ("test123");
                // var_dump($result);
                // var_dump($result->name_file);

                $result->name_file = str_replace("\"", "", $result->name_file);
                $config = [
                    'mailtype'  => 'html',
                    'charset'   => 'utf-8',
                    'protocol'  => 'smtp',
                    'smtp_host' => $this->m_parameter_project->get($project->id, "smtp_host"),
                    'smtp_user' => $this->m_parameter_project->get($project->id, "smtp_user"),
                    'smtp_pass' => $this->m_parameter_project->get($project->id, "smtp_pass"),
                    'smtp_port' => $this->m_parameter_project->get($project->id, "smtp_port"),
                    // 'smtp_crypto' => 'tls',
                    'crlf'      => "\r\n",
                    'newline'   => "\r\n",
                    'smtp_crypto'	=> "ssl"
                ];
                echo ("config<pre>");
                print_r($config);
                echo ("</pre>");
                $this->load->library('email', $config);
                // print_r($config);
                // $this->db->selec
                $this->email->from($this->m_parameter_project->get($project->id, "smtp_user"), 'EMS Ciputra');

                $email = $this->db
                    ->select("
                        CASE
                            WHEN unit.kirim_tagihan = 1 THEN pemilik.email
                            WHEN unit.kirim_tagihan = 2 THEN penghuni.email
                            WHEN unit.kirim_tagihan = 3 THEN CONCAT(pemilik.email,';',penghuni.email)
                        END as email")
                    ->from("unit")
                    ->join(
                        "customer as pemilik",
                        "pemilik.id = unit.pemilik_customer_id",
                        "LEFT"
                    )
                    ->join(
                        "customer as penghuni",
                        "penghuni.id = unit.penghuni_customer_id 
                            AND penghuni.id != pemilik.id",
                        "LEFT"
                    )

                    ->where("unit.id", $unit_id)->get()->row()->email;
                $email = explode(";", $email);
                $parameter_delay = explode(";", $this->m_parameter_project->get($project->id, "delay_email"));

                foreach ($email as $k => $v) {
                    if ($k != 0 && ($k + 1) % $parameter_delay[0] == 0) {
                        sleep($parameter_delay[1]);
                    }
                    $this->email->clear(TRUE);
                    $this->email->from($this->m_parameter_project->get($project->id, "smtp_user"), 'EMS Ciputra');
                    $this->email->subject($this->m_parameter_project->get($project->id, "subjek_konfirmasi_tagihan"));
                    $this->email->message(($result->isi));
                    $this->email->to($v);
                    $this->email->attach("application/pdf/$result->name_file");
                    var_dump($this->m_parameter_project->get($project->id, "smtp_user"));
                    var_dump($this->m_parameter_project->get($project->id, "subjek_konfirmasi_tagihan"));
                    var_dump($result->isi);

                    $status = $this->email->send();
                    if ($status) {
                        echo ("Success " . $result->name_file);
                        $email_success++;
                    } else {
                        echo ("Gagal  " . $result->name_file);
                    }
                    var_dump($v . "->" . $status);
                }
            }
        }
    }
}
