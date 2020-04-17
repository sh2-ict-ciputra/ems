<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

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
class Water_meter extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->helper(['jwt', 'authorization']); 

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->load->database();
        $this->db->database = "ems";
    }
    public function index_get($type = null)
    {
        ini_set('memory_limit','256M');
        ini_set('sqlsrv.ClientBufferMaxKBSize','524288');   
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');
        $this->load->helper('file');
        write_file("./log/" . date("y-m-d") . '_log_water_meter.txt', "\n" . date("y-m-d h:i:s") . " = GET !", 'a+');
        $parameter = (object)json_decode($this->input->raw_input_stream, true);
        $head = (object)$this->head();
        $this->load->database();       
        
        
        if($type == "request_key"){
            $result = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ2ZW5kb3IiOiJuYnMiLCJ1c2VyIjoiSm9obiBEb2UiLCJwcm9qZWN0X2lkIjoxfQ.htZ0poLfOX7gvMysfOL6hrpvfeioPofg5-p5ijNGS8o";
            if(isset($head->api_token)){
                if($head->api_token == $result)
                    $this->response(['api_key' => $result], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                else{
                    echo 'Api Token Salah';
                    $this->response(null, 401);
                }   
            }else{
                echo 'Api Token Tidak Ada';
                $this->response(null, 401);
            }
        }


        if(isset($head->api_key)){
            try {
                $validateToken = AUTHORIZATION::validateToken($head->api_key);
                $generateToken = AUTHORIZATION::generateToken(['a'=>'b']);
                // var_dump($validateToken);
                // var_dump($generateToken);
            } catch (Exception $e) {
                echo 'Api Key Salah';
                $this->response(null, 401);

            }
        }else{
            echo 'Api Key Tidak Ada';
            $this->response(null, 401);
        }

        $result = (object) [];
        
        
        // var_dump($head);
        // var_dump($jwt_key);

        
        if ($type == "Pegawai") {
            $result = $this->db->select("   user.id,
                                            user.name,
                                            user.email,
                                            group_user_level.level_id as role_id,
                                            level.name as role,
                                            project.id as project_id
                                        ")
                ->from("user")
                ->join(
                    "group_user",
                    "group_user.user_id = user.id"
                )
                ->join(
                    "group_user_level",
                    "group_user_level.group_user_id = group_user.id"
                )
                ->join(
                    "level",
                    "level.id = group_user_level.id"
                )
                ->join('project',
                        'project.id = group_user.project_id');
        } else if ($type == "Project") {
            $result = $this->db->select("   project.id,
                                            project.address,
                                            project.name
                                        ")
                ->from("project");
        } else if ($type == "Kawasan") {
            // var_dump("3");
            $result = $this->db->select("   kawasan.id,
                                            kawasan.name,
                                            project.id as project_id
                                        ")
                ->from("kawasan")
                ->join('project',
                        'project.id = kawasan.project_id');
        } else if ($type == "Blok") {
            $result = $this->db->select("   blok.id,
                                            blok.code,
                                            kawasan.project_id,
                                            blok.kawasan_id
                                        ")
                ->from("blok")
                ->join("kawasan",
                        "kawasan.id = blok.kawasan_id")
                ->join("project",
                        "project.id = kawasan.project_id");
        } else if ($type == "Unit") {
            $result = $this->db->select("   unit.id,
                                            unit.project_id,
                                            blok.kawasan_id as cluster_id,
                                            unit.blok_id as block_id,
                                            unit.no_unit as number,
                                            unit.pemilik_customer_id
                                        ")
                ->from("unit")
                ->join("blok",
                        "blok.id = unit.blok_id")
                ->join("kawasan",
                        "kawasan.id = blok.kawasan_id")
                ->join('project',
                        'project.id = unit.project_id');
            
        } else if ($type == "Customer") {
            $result = $this->db->select("   customer.id,
                                            customer.name as fullname,
                                            customer.email,
                                            customer.mobilephone1 as msisdn,
                                            project.id as project_id
                                        ")
                ->from("customer")
                ->join('project',
                        'project.id = customer.project_id');
        } else if ($type == "Pencatatan_meter_air") {
            $result = $this->db->select("   t_pencatatan_meter_air.id,
                                            t_pencatatan_meter_air.meter_awal,
                                            t_pencatatan_meter_air.meter_akhir,
                                            unit.project_id,
                                            unit.blok_id as block_id,
                                            blok.kawasan_id as residence_id,
                                            t_pencatatan_meter_air.periode as periode
                                        ")
                ->from("t_pencatatan_meter_air")
                ->join("unit",
                        "unit.id = t_pencatatan_meter_air.unit_id")
                ->join("blok",
                        "blok.id = unit.blok_id")
                ->join("kawasan",
                        "kawasan.id = blok.kawasan_id")
                ->join('project',
                        'project.id = unit.project_id');
            
        } else $this->response(null, 400);

        if(isset($parameter->project_id))
            $result = $result->where("project.id", $parameter->project_id);
        if(isset($parameter->pegawai_id))
            $result = $result->where("user.id", $parameter->pegawai_id);
        if(isset($parameter->role_id))
            $result = $result->where("group_user_level.level_id", $parameter->role_id);
        if(isset($parameter->role))
            $result = $result->where("level.name", $parameter->role);
        if(isset($parameter->blok_id))
            $result = $result->where("blok.id", $parameter->blok_id);   
        if(isset($parameter->kawasan_id))
            $result = $result->where("kawasan.id", $parameter->kawasan_id);
        if(isset($parameter->unit_id))
            $result = $result->where("unit.id", $parameter->unit_id);
        if(isset($parameter->customer_id))
            $result = $result->where("customer.id", $parameter->customer_id);
        if(isset($parameter->pencatatan_id))
            $result = $result->where("t_pencatatan_meter_air.id", $parameter->pencatatan_id);
        if(isset($parameter->periode))
            $result = $result->where("t_pencatatan_meter_air.periode",$parameter->periode);

        
        $result = $result->get()->result();
        // var_dump($parameter);
        // var_dump($this->db->last_query());
        $this->response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function index_post($tipe=null)
    {
        ini_set('memory_limit','256M');
        ini_set('sqlsrv.ClientBufferMaxKBSize','524288');   
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');
        $this->load->helper('file');
        write_file("./log/" . date("y-m-d") . '_log_water_meter.txt', "\n" . date("y-m-d h:i:s") . " = GET !", 'a+');
        $parameter = (object)json_decode($this->input->raw_input_stream, true);
        $head = (object)$this->head();
        $this->load->database();       
        
        if(isset($head->api_key)){
            try {
                $validateToken = AUTHORIZATION::validateToken($head->api_key);
                $generateToken = AUTHORIZATION::generateToken(['a'=>'b']);
                // var_dump($validateToken);
                // var_dump($generateToken);
            } catch (Exception $e) {
                echo 'Api Key Salah';
                $this->response(null, 401);

            }
        }else{
            echo 'Api Key Tidak Ada';
            $this->response(null, 401);
        }
        if($tipe == "pencatatan_meter_air"){
            if(isset($parameter->unit_id) && isset($parameter->periode) && isset($parameter->meter_akhir)){

            }else{
                $this->response('Parameter Tidak Lengkap', 400);
            }
        }
        $this->set_response([
            "success"
        ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
}
