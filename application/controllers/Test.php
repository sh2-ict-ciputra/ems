<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
     
    }

    public function index()
    {
        var_dump(date("Y-m-d")."T".date("H:i:s.000")."Z");
        $date = new DateTime(date("Y-m-d H:i:s"),new DateTimeZone("Asia/Jakarta"));
        $date->setTimezone(new DateTimeZone('UTC'));

        var_dump($date->format("Y-m-d")."T".$date->format("H:i:s")."Z");
        
    }
}
