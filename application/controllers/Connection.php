<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Connection extends CI_Controller {
	// function __construct() {
	// 	parent::__construct();
	// 	$this->load->model('m_login');
    // }
	public function index()
	{
        $serverName = ".";
        $connectionInfo = array( "Database"=>"ems", "UID"=>"ciputraestate", "PWD"=>"ciputraestate123");
        $conn = sqlsrv_connect( $serverName, $connectionInfo);

        if( $conn ) {
            echo "Connection established.<br />";
        }else{
            echo "Connection could not be established.<br />";
            die( print_r( sqlsrv_errors(), true));
        }
    }
	
}
