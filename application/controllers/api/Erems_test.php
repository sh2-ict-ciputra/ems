<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Erems_test extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->load->database();
        $this->db->database = "ems_temp";

    }
    function dec_enc($action, $string) {
        $output = false;
        
        $encrypt_method = "AES-256-CBC";
        $secret_key = '2151ae91210a9ae3eaa9bec9fd82ce95b0ecfbc5';
        $secret_iv = 'faa4762cade124d130ba867298f8e22a6e2ce4e4';
    
        // hash
        $key = hash('sha256', $secret_key);
    
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
    
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    
        return $output;
    }
    public function kirim_post(){
        // echo("<pre>");
        //     print_r($this->post());
        // echo("</pre>");
        // die;
        $from = "ems_temp.dbo.";
        
        $this->db->trans_start();

        $data = $this->post("send_data");
        $data = json_decode($this->dec_enc("decrypt",$data),true);
        $this->load->model("m_customer");
        // echo("<pre>");
        //     print_r($data);
        // echo("</pre>");
        
        $project_id_erems = $data["project_id"];
        $project = $this->db ->select("*")
                                ->from($from."project")
                                ->where("source_table","2")
                                ->where("source_id",$project_id_erems)
                                ->get()->row();
        
        $result = (object)[
            "result_code"    => 0,
            "result_text"    => "Data Gagal di Migrasi dari EREMS ke EMS",
            "data_code"      => 
                (object)[
                    "customer"      => 0,
                    "kawasan"       => 0,
                    "blok"          => 0,
                    "purpose_use"   => 0,
                    "unit"          => 0
                ],
            "data_text" =>
                (object)[
                    "customer"      => "Data Gagal di Tambah",
                    "kawasan"       => "Data Gagal di Tambah",
                    "blok"          => "Data Gagal di Tambah",
                    "purpose_use"   => "Data Gagal di Tambah",
                    "unit"          => "Data Gagal di Tambah"
                ]
        ];
        if($project){
            $customer = (object)[
                "code"          => "CUST/".$project->code."/".date("Y")."/".str_pad(($this->m_customer->last_id_by_project($project->id)+1), 4, "0", STR_PAD_LEFT),
                "project_id"    => $project->id,
                "pt_id"         => null,
                "unit"          => 1,
                "name"          => $data["customer_name"],
                "address"       => $data["customer_address"],
                "email"         => $data["customer_email"],
                "ktp"           => $data["customer_ktp"],
                "ktp_address"   => $data["customer_ktp_address"],
                "mobilephone1"  => $data["customer_mobilephone1"],
                "mobilephone2"  => null,
                "homephone"     => $data["customer_homephone"],
                "officephone"   => $data["customer_officephone"],
                "npwp_no"       => $data["customer_npwp_no"],
                "npwp_name"     => $data["customer_npwp_name"],
                "npwp_address"  => $data["customer_npwp_address"],
                "description"   => "",
                "active"        => 1,
                "delete"        => 0,
                "source_table"  => "EREMS",
                "source_id"     => $data["customer_source_id"]
            ];
            
            // validasi customer 1 
            $validasi_customer = $this  ->db->select("id")
                                        ->from($from."customer")
                                        ->where("source_table","$customer->source_table")
                                        ->where("source_id","$customer->source_id")                                    
                                        ->get()->row();
            if($validasi_customer){
                $customer_id = $validasi_customer->id;
                $result->data_code->customer = 3;
                $result->data_text->customer = "Data Sudah Ada";
            }else{
                // validasi customer 2
                $validasi_customer = $this  ->db->select("id")
                                            ->from($from."customer")
                                            ->where("((isnull(email,0)!= 0 and email!='') and email='$customer->email')")
                                            ->where("((isnull(ktp,0)!= 0 and ktp!='') and ktp='$customer->ktp')")
                                            ->where("((isnull(mobilephone1,0)!= 0 and mobilephone1!='') and mobilephone1='$customer->mobilephone1')")
                                            ->where("((isnull(homephone,0)!= 0 and homephone!='') and homephone='$customer->homephone')")
                                            ->where("((isnull(npwp_no,0)!= 0 and npwp_no!='') and npwp_no='$customer->npwp_no')")
                                            // ->where("email",$customer->email)
                                            // ->where("ktp",$customer->ktp)
                                            // ->or_where("mobilephone1",$customer->mobilephone1)
                                            // ->or_where("homephone",$customer->homephone)
                                            // ->or_where("npwp_no",$customer->npwp_no)
                                            ->get()->row();
                if($validasi_customer){
                    $customer_id = $validasi_customer->id;
                    $this->db->where("id",$customer_id);
                    $this->db->update($from."customer",[
                        "source_table"  => "$customer->source_table",
                        "source_id"     => "$customer->source_id"
                    ]);
                    $result->data_code->customer = 2;
                    $result->data_text->customer = "Data Source_table dan Source_id Berhasil di Update";
                }else{
                    $this->db->insert($from."customer",$customer);
                    $customer_id = $this->db->insert_id();
                    $result->data_code->customer = 1;
                    $result->data_text->customer = "Data Berhasil di Tambah";
                }
            }

            $kawasan = (object)[
                "project_id" => $project->id,
                "source_table" =>"EREMS",
                "source_id" => $data["kawasan_source_id"],
                "code" => $data["kawasan_code"],
                "name" => $data["kawasan_name"],
                "description" => $data["kawasan_description"],
                "active" => 1,
                "delete" => 0
            ];
            // validasi kawasan 1 
            $validasi_kawasan = $this  ->db->select("id")
                                        ->from($from."kawasan")
                                        ->where("source_table","$kawasan->source_table")
                                        ->where("source_id","$kawasan->source_id")                                    
                                        ->get()->row();
            if($validasi_kawasan){
                $kawasan_id = $validasi_kawasan->id;
                $result->data_code->kawasan = 3;
                $result->data_text->kawasan = "Data Sudah Ada";
            }else{
                // validasi kawasan 2
                $validasi_kawasan = $this  ->db->select("id")
                                            ->from($from."kawasan")
                                            ->where("name",$kawasan->code)
                                            ->where("code",$kawasan->name)
                                            ->get()->row();
                if($validasi_kawasan){
                    $kawasan_id = $validasi_kawasan->id;
                    $this->db->where("id",$kawasan_id);
                    $this->db->update($from."kawasan",[
                        "source_table"  => "$kawasan->source_table",
                        "source_id"     => "$kawasan->source_id"
                    ]);
                    $result->data_code->kawasan = 2;
                    $result->data_text->kawasan = "Data Source_table dan Source_id Berhasil di Update";
                }else{
                    $this->db->insert($from."kawasan",$kawasan);
                    $kawasan_id = $this->db->insert_id();
                    $result->data_code->kawasan = 1;
                    $result->data_text->kawasan = "Data Berhasil di Tambah";
                }
            }

            $blok = (object)[
                "source_table"  => "EREMS",
                "source_id"     => $data["blok_source_id"],
                "kawasan_id"    => $kawasan_id,
                "code"          => substr($data["unit_blok_unit"],0,strlen($data["unit_blok_unit"])-strpos($data["unit_blok_unit"],"/")),
                "name"          => substr($data["unit_blok_unit"],0,strlen($data["unit_blok_unit"])-strpos($data["unit_blok_unit"],"/")),
                "active"        => 1,
                "delete"        => 0
            ];

            // validasi kawasan 1 
            $validasi_blok = $this  ->db->select("id")
                                        ->from($from."blok")
                                        ->where("source_table","$blok->source_table")
                                        ->where("source_id","$blok->source_id")                                    
                                        ->get()->row();
            if($validasi_blok){
                $blok_id = $validasi_blok->id;
                $result->data_code->blok = 3;
                $result->data_text->blok = "Data Sudah Ada";
            }else{
                // validasi blok 2
                $validasi_blok = $this  ->db->select("id")
                                            ->from($from."blok")
                                            ->where("name",$blok->code)
                                            ->where("code",$blok->name)
                                            ->get()->row();
                if($validasi_blok){
                    $blok_id = $validasi_blok->id;
                    $this->db->where("id",$blok_id);
                    $this->db->update($from."blok",[
                        "source_table"  => "$blok->source_table",
                        "source_id"     => "$blok->source_id"
                    ]);
                    $result->data_code->blok = 2;
                    $result->data_text->blok = "Data Source_table dan Source_id Berhasil di Update";
                }else{
                    $this->db->insert($from."blok",$blok);
                    $blok_id = $this->db->insert_id();
                    $result->data_code->blok = 1;
                    $result->data_text->blok = "Data Berhasil di Tambah";
                }
            }

            // $validasi_blok = $this   ->db->select("id")
            //                             ->from($from."kawasan")
            //                             ->where("source_table","$kawasan->source_table")
            //                             ->where("source_id","$kawasan->source_id")                                    
            //                             ->get()->row();

            
            // // validasi blok 1 
            // $validasi_blok = $this->db->select("id")
            //                         ->from($from."blok")
            //                         ->where("name","$blok->name")
            //                         ->where("code","$blok->code")                                    
            //                         ->get()->row();
            // if($validasi_blok){
            //     $blok_id = $validasi_blok->id;
            //     $result->data_code->blok = 3;
            //     $result->data_text->blok = "Data Sudah Ada";
            // }else{
            //     $this->db->insert($from."blok",$blok);
            //     $blok_id = $this->db->insert_id();
            //     $result->data_code->blok = 1;
            //     $result->data_text->blok = "Data Berhasil di Tambah";
            // }


            $purpose_use = (object)[
                "name" => $data["purpose_use_name"],
                "description" => $data["purpose_use_description"],
                "active" => 1,
                "delete" => 0,
                "source_table" =>"EREMS",
                "source_id" => $data["purpose_use_source_id"]
            ];
            // validasi purpose_use 1 
            $validasi_purpose_use = $this->db->select("id")
                                        ->from($from."purpose_use")
                                        ->where("source_table","$purpose_use->source_table")
                                        ->where("source_id","$purpose_use->source_id")                                    
                                        ->get()->row();
            if($validasi_purpose_use){
                $purpose_use_id = $validasi_purpose_use->id;
                $result->data_code->purpose_use = 3;
                $result->data_text->purpose_use = "Data Sudah Ada";
            }else{
                // validasi purpose_use 2
                $validasi_purpose_use = $this   ->db->select("id")
                                                ->from($from."purpose_use")
                                                ->where("name",$purpose_use->name)
                                                ->get()->row();
                if($validasi_purpose_use){
                    $purpose_use_id = $validasi_purpose_use->id;
                    $this->db->where("id",$purpose_use_id);
                    $this->db->update($from."purpose_use",[
                        "source_table"  => "$purpose_use->source_table",
                        "source_id"     => "$purpose_use->source_id"
                    ]);
                    $result->data_code->purpose_use = 2;
                    $result->data_text->purpose_use = "Data Source_table dan Source_id Berhasil di Update";
                }else{
                    $this->db->insert($from."purpose_use",$purpose_use);
                    $purpose_use_id = $this->db->insert_id();
                    $result->data_code->purpose_use = 1;
                    $result->data_text->purpose_use = "Data Berhasil di Tambah";
                }
            }

            $unit = (object)[
                "project_id"            => $project->id,
                "blok_id"               => $blok_id,
                "no_unit"               => substr($data["unit_blok_unit"],strpos($data["unit_blok_unit"],"/")+1),
                "pemilik_customer_id"   => $customer_id,
                "penghuni_customer_id"  => $customer_id,
                "luas_tanah"            => $data["unit_luas_tanah"],
                "luas_bangunan"         => $data["unit_luas_bangunan"],
                "luas_taman"            => 0,
                "tgl_st"                => $data["unit_tgl_st"],
                "pt_id"                 => $data["unit_pt_id_estate"],
                "unit_type"             => isset($data["unit_unit_type"])?$data["unit_unit_type"]:null,       //1. ,2. ,3.
                "status_tagihan"        => isset($data["unit_status_tagihan"])?$data["unit_status_tagihan"]:null,  //1. Aktif, 0 Non Aktif
                "virtual_account"       => isset($data["unit_virtual_account"])?$data["unit_virtual_account"]:null,
                "diskon_flag"           => isset($data["unit_diskon_flag"])?$data["unit_diskon_flag"]:null,     //1. Aktif, 0 Non Aktif
                "kirim_tagihan"         => isset($data["unit_kirim_tagihan"])?$data["unit_kirim_tagihan"]:null,   //1. Pemilik, 2. Penghuni, 3. All
                "source_table"          => "EREMS",
                "source_id"             => $data["unit_source_id"],
                "active"                => 1,
                "delete"                => 0,
                "purpose_use_id"        => $purpose_use_id,
                "bangunan_type"         => isset($data["unit_bangunan_type"])?$data["unit_bangunan_type"]:null,
                "status_jual"           => 1
            ];

            // validasi unit 1 
            $validasi_unit = $this  ->db->select("id")
                                    ->from($from."unit")
                                    ->where("source_table","$unit->source_table")
                                    ->where("source_id","$unit->source_id")                                    
                                    ->get()->row();
            // var_dump($unit);
            if($validasi_unit){
                $unit_id = $validasi_unit->id;
                $result->data_code->unit = 3;
                $result->data_text->unit = "Data Sudah Ada";
            }else{
                // validasi unit 2
                $validasi_unit = $this  ->db->select("id")
                                        ->from($from."unit")
                                        ->where("blok_id",$unit->blok_id)
                                        ->where("no_unit",$unit->no_unit)
                                        ->get()->row();
                if($validasi_unit){
                    $unit_id = $validasi_unit->id;
                    $this->db->where("id",$unit_id);
                    $this->db->update($from."unit",[
                        "source_table"  => "$unit->source_table",
                        "source_id"     => "$unit->source_id"
                    ]);
                    $result->data_code->unit = 2;
                    $result->data_text->unit = "Data Source_table dan Source_id Berhasil di Update";
                }else{
                    $this->db->insert($from."unit",$unit);
                    $unit_id = $this->db->insert_id();
                    $result->data_code->unit = 1;
                    $result->data_text->unit = "Data Berhasil di Tambah";
                }
            }
            if ($this->db->trans_status() === FALSE)
                    $this->db->trans_rollback();
            else
            {
                $this->db->trans_commit();
                $result->result_code = 1;
                $result->result_text = "Data Berhasil di Migrasi dari EREMS ke EMS";
            }
            $this->load->helper('file');
            $data = json_encode($result);
            
            write_file('./log.txt', $data,'w+');
            $myfile = fopen("log.txt", "w+") or die("Unable to open file!");
            fwrite($myfile, $result);
            fclose($myfile);
            $response = $this->dec_enc("encrypt",json_encode($result));
            // var_dump($this->dec_enc("decrypt",$response));

            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }else{
            $this->load->helper('file');
            $data = json_encode($result);
            write_file('./log.txt', $data,'w+');
            $response = $this->dec_enc("encrypt",json_encode($result));
            // var_dump($this->dec_enc("decrypt",$response));

            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }
        // echo $this->dec_enc("decrypt",$response);
    }
}
