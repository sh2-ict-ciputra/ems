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
class Nbs extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->load->database();
        $this->db->database = "ems_temp";

    }
    public function index_post($api_key=null,$type=null){
        $from = "ems_temp.dbo";
        if(!$api_key){
            $this->response(null,401);
        }else{
            if($api_key != 'sales_force_permission')
                $this->response(null,401);
        }
        $external_id    = $this->post("external_id");
        $external_id    = explode(";", $external_id);
        $tagihan = $this->db
                ->select("
                    isnull(t_tagihan_lingkungan.id,0) as lingkungan_id,
                    isnull(t_tagihan_air.id,0) as air_id")
                ->from("$from.v_sales_force_bill")
                ->join("t_tagihan_lingkungan",
                        "t_tagihan_lingkungan.t_tagihan_id = v_sales_force_bill.bill_id
                        AND t_tagihan_lingkungan.status_tagihan != 1",
                        "LEFT")
                ->join("t_tagihan_air",
                        "t_tagihan_air.t_tagihan_id = v_sales_force_bill.bill_id
                        AND t_tagihan_air.status_tagihan != 1",
                        "LEFT")
                ->where_in("bill_id",$external_id)
                ->get()->result();

        foreach ($tagihan as $v) {
            if($v->lingkungan_id != 0){
                $this->db->set('status_tagihan', 3);
                $this->db->where('id', $v->lingkungan_id);
                $this->db->update("$from.t_tagihan_lingkungan");
            }
            if($v->air_id != 0){
                $this->db->set('status_tagihan', 3);
                $this->db->where('id', $v->air_id);
                $this->db->update("$from.t_tagihan_air");
            }
        }

        $message = [
            'test' => "sukses", // Automatically generated by the model
        ];
        $this->set_response($message, REST_Controller::HTTP_CREATED);
    }
    public function index_get($api_key=null)
    {
        $from = "ems_temp.dbo";

        $result = (object)[];

        $uid = $this->input->get("uid");
        if(!$api_key){
            $this->response(null,401);
        }else{
            if($api_key != 'sales_force_permission')
                $this->response(null,401);
        }
        if(!$uid || !$type){
            $this->response(null,400); // OK (200) being the HTTP response code
        }

        $resultUnit = $this->db->select("*")
                                ->from("unit")
                                ->join("project",
                                        "project.id = unit.project_id")
                                ->join("blok",
                                        "blok.id = unit.blok_id")
                                ->join("kawasan",
                                        "kawasan.id = blok.kawasan_id")
                                ->where("CONCAT(project.source_id,kawasan.code,blok.code,'/',unit.no_unit)","$uid")
                                ->get()->row();
        
        $total = (object)[];

        if($type == "bill"){

            $total->tagihan_air = 0;
            $total->tagihan_lingkungan = 0;
            $total->tagihan_lain = 0;
            $total->total_denda = 0;
            $total->total = 0;
            $resultTMP = $this->db->select("
                                                uid,
                                                bill_id as tagihan_id,
                                                periode,
                                                tagihan_air,
                                                tagihan_lingkungan,
                                                tagihan_lain,
                                                total_denda, 
                                                isnull(tagihan_air,0)+
                                                isnull(tagihan_lingkungan,0)+
                                                isnull(tagihan_lain,0)+
                                                isnull(total_denda,0)
                                                as total
                        ")
                        ->from("$from.v_sales_force_bill")
                        ->where("uid","$uid")
                        ->order_by("periode")
                        ->get()->result();
            $resultVA = $this->db->select("
                                            MANDIRI,
                                            BNI,
                                            BRI,
                                            BCA,
                                            PERMATA,
                                            CIMB
                        ")
                        ->from("$from.v_sales_force_bill")
                        ->where("uid","$uid")
                        ->order_by("periode")
                        ->get()->row();
            $result->va    = $resultVA;
            foreach ($resultTMP as $key => $v) {
                $total->tagihan_air          += $v->tagihan_air;
                $total->tagihan_lingkungan   += $v->tagihan_lingkungan;
                $total->tagihan_lain         += $v->tagihan_lain;
                $total->total_denda          += $v->total_denda;
                $total->total                += $v->total;
            }
        }
        else if($type == "history"){
            $total->tagihan_air = 0;
            $total->tagihan_lingkungan = 0;
            $total->tagihan_lain = 0;
            $total->total_denda = 0;
            $total->total = 0;
            $resultTMP = $this->db
                        ->select("
                            uid,
                            bill_id as tagihan_id,
                            periode,
                            tagihan_air,
                            tagihan_lingkungan,
                            tagihan_lain,
                            total_denda, 
                            isnull(tagihan_air,0)+
                            isnull(tagihan_lingkungan,0)+
                            isnull(tagihan_lain,0)+
                            isnull(total_denda,0)
                            as total,
                            status_tagihan
                        ")
                        ->from("v_sales_force_history")
                        ->where("uid","$uid")
                        ->limit(12)
                        ->order_by("periode")
                        ->get()->result();
            foreach ($resultTMP as $key => $v) {
                $total->tagihan_air          += $v->tagihan_air;
                $total->tagihan_lingkungan   += $v->tagihan_lingkungan;
                $total->tagihan_lain         += $v->tagihan_lain;
                $total->total_denda          += $v->total_denda;
                $total->total                += $v->total;
            }
            // $result = $this->db->select("*")->from("v_xendit_history")->where("uid = $uid")->get()->result();
        }else
            $this->response(null,400);

        // echo("<pre>");   
        //     print_r($project);
        // echo("</pre>");
        $result->info = (object)[];
        $result->info->project = $resultUnit->project;
        $result->info->kawasan = $resultUnit->kawasan;
        $result->info->blok = $resultUnit->blok;
        $result->info->no_unit = $resultUnit->no_unit;
        
        $result->total = $total;
        $result->tagihan = $resultTMP;
        // $result->info = $resultTMP;
        $this->response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

    }
    public function users_get($id=null)
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];

        $id = $id;

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retrieval.
        // Usually a model is to be used for this.

        $user = NULL;

        if (!empty($users))
        {
            foreach ($users as $key => $value)
            {
                if (isset($value['id']) && $value['id'] === $id)
                {
                    $user = $value;
                }
            }
        }

        if (!empty($user))
        {
            $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function users_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
