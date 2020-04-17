<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_log extends CI_Model {
    function __construct() {
		parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    public function log_detail_get($id){
        $query = $this->db->query("
            SELECT * FROM log_detail	
            WHERE log_id = '$id'
        ");
        $row = $query->result_array();
        return $row;
    }
    public function get_table($id){
        $query = $this->db->query("
            SELECT [table] FROM log	
            WHERE log.id = '$id'
        ");
        $row = $query->result_array();
        return $row[0]['table'];
    }public function get_table_id($id){
        $query = $this->db->query("
            SELECT table_id FROM log	
            WHERE log.id = '$id'
        ");
        $row = $query->result_array();
        return $row[0]['table_id'];
    }
    public function get($table,$id){
        $query = $this->db->query("
            SELECT log.*,[user].name FROM log	
            JOIN [user] ON [user].id = log.user_id
            WHERE log.[table] = '$table'
            AND log.table_id = '$id'
            ORDER BY log.date DESC
        ");
        $row = $query->result_array();
        return $row;
    }

    public function log_save($table,$table_id,$status,$dataTMP){
        $this->load->model('m_core');
        $user_id = $this->m_core->user_id();
        if($status == 'Tambah'){
            $status = 1;
        }elseif($status == 'Edit'){
            $status = 2;
        }elseif($status == 'Delete'){
            $status = 3;
        }else{
            $status = 0;
        }

        date_default_timezone_set("Asia/Jakarta");
        $data = 
        [
            'table'     => $table,
            'table_id'  => $table_id,
            'date'      => date('Y-m-d H:i:s'),
            'user_id'   => $user_id,
            'status'    => $status
        ];
        $this->db->insert('log', $data);
        $this->log_detail_save($user_id, $dataTMP,$this->db->insert_id(),$status);
    }
    public function log_detail_save($user_id,$dataTMP,$id,$status){
        // $data = 
        // [
        //     'log_id'    => $dataTMP['table'],
        //     'name'      => $dataTMP['table_id'],
        //     'value'     => $a
        // ];

        if($status == 1){
            for($i = 0; $i < count((array)$dataTMP);$i++){
                $data = [
                            'log_id'    => $id,
                            'name'      => array_keys((array)$dataTMP)[$i],
                            'value'     => array_values((array)$dataTMP)[$i]
                        ];
                $this->db->insert('log_detail', $data);
            }
        }elseif($status == 2){
            for($i = 0; $i < count((array)$dataTMP);$i++){
                $data = [
                            'log_id'    => $id,
                            'name'      => array_keys((array)$dataTMP)[$i],
                            'value'     => array_values((array)$dataTMP)[$i]
                        ];
                $this->db->insert('log_detail', $data);
            }
        }
        date_default_timezone_set("Asia/Jakarta");
        // $data = 
        // [
        //     'table'     => $table,
        //     'table_id'  => $table_id,
        //     'date'      => date('Y-m-d H:i:s'),
        //     'user_id'   => $user_id,
        //     'status'    => $status
        // ];
        // if($detail){

        // }
        // $this->db->insert('log', $data);
        
    }
}