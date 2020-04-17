<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_core extends CI_Model {
    function __construct() {
		parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    function menu(){
        $query = $this->db->query(" 
                    Select 
                        m1.id as id1,
                        m1.name as name1
                    from menu as m1
                    LEFT JOIN menu as m2
                        ON m1.id_parent = m2.id
                    LEFT JOIN menu as m3
                        ON m2.id_parent = m3.id
                    LEFT JOIN menu as m4
                        ON m3.id_parent = m4.id
                    WHERE m2.id is NULL
                    AND m1.active = 1
                    ORDER BY m1.id ASC
                ");
        $row = $query->result_array(); 
        $menu['level1'] = $row;
        
        $query = $this->db->query(" 
                    Select 
                        m1.id as id1,
                        m1.name as name1,
                        m2.id as id2,
                        m2.name as name2
                    from menu as m1
                    LEFT JOIN menu as m2
                        ON m1.id_parent = m2.id
                    LEFT JOIN menu as m3
                        ON m2.id_parent = m3.id
                    LEFT JOIN menu as m4
                        ON m3.id_parent = m4.id
                    WHERE m3.id is NULL
                    AND m2.id is Not Null
                    AND m1.active = 1
                    ORDER BY m1.id ASC
                ");
        $row = $query->result_array(); 
        $menu['level2'] = $row;
        
        $query = $this->db->query(" 
                    Select 
                        m1.id as id1,
                        m1.name as name1,
                        m1.url,
                        m2.id as id2,
                        m2.name as name2
                    from menu as m1
                    LEFT JOIN menu as m2
                        ON m1.id_parent = m2.id
                    LEFT JOIN menu as m3
                        ON m2.id_parent = m3.id
                    LEFT JOIN menu as m4
                        ON m3.id_parent = m4.id
                    WHERE m4.id is NULL
                    AND m3.id is Not NULL
                    AND m1.active = 1
                    ORDER BY m1.id ASC
                ");
        $row = $query->result_array(); 
        $menu['level3'] = $row;
        
        $query = $this->db->query(" 
                    Select 
                        m1.id as id1,
                        m1.name as name1,
                        m1.url,
                        m2.id as id2,
                        m2.name as name2
                    from menu as m1
                    LEFT JOIN menu as m2
                        ON m1.id_parent = m2.id
                    LEFT JOIN menu as m3
                        ON m2.id_parent = m3.id
                    LEFT JOIN menu as m4
                        ON m3.id_parent = m4.id
                    WHERE m4.id is NOT NULL
                    AND m1.active = 1
                    ORDER BY m1.id ASC    
                ");
        $level_id = $this->level_id();
        $query = $this->db->select("
                            m1.id as id1,
                            m1.name as name1,
                            m1.url,
                            m2.id as id2,
                            m2.name as name2,
                            CASE 
                                WHEN m1.url is not null and (permission_menu.[read] is null or permission_menu.[read] = 0) THEN '0'
                                WHEN m1.url is not null and (permission_menu.[read] is not null or permission_menu.[read] != 0) THEN '1'
                                WHEN m1.url is null THEN '1'
                            END as akses")
                        ->from("menu as m1")
                        ->join("menu as m2",
                                "m1.id_parent = m2.id",
                                "LEFT")
                        ->join("menu as m3",
                                "m2.id_parent = m3.id",
                                "LEFT")
                        ->join("menu as m4",
                                "m3.id_parent = m4.id",
                                "LEFT")
                        ->join('permission_menu',
                                "permission_menu.menu_id = m1.id
                                AND permission_menu.level_id = '$level_id'",
                                'LEFT')
                        ->where("m4.id is not null")
                        ->where("m1.active = 1")
                        ->order_by('m1.id ASC')
                        ->get(); 
        $row = $query->result_array(); 
        $menu['level4'] = $row;
        // echo("<pre>");
        //     print_r($menu);
        // echo("</pre>");
        
        return $menu;
    }
    function level_id(){
        $group_user_id = isset($this->session->userdata['group'])?$this->session->userdata['group']:'0';
        $query = $this->db  ->from('group_user_level')
                            ->where('group_user_id',$group_user_id)
                            ->get()->row();
        return isset($query)?$query->level_id:'';
    }
    function jabatan(){
        $group_user_id = isset($this->session->userdata['group'])?$this->session->userdata['group']:'0';
        $query = $this->db->query(" SELECT jabatan.name,jabatan.id FROM group_user
                                    JOIN jabatan ON jabatan.id = group_user.jabatan_id
                                    where group_user.id = $group_user_id");
        $row = $query->row(); 
        return isset($row)?$row:'';
    }
    function project(){
        $group_user_id = isset($this->session->userdata['group'])?$this->session->userdata['group']:'0';
        $query = $this->db->query(" SELECT project.name,project.id,project.code FROM group_user
                                    JOIN project ON project.id = group_user.project_id
                                    where group_user.id = $group_user_id");
        $row = $query->row(); 
        return isset($row)?$row:'';
    }
    function unit_id(){
        return isset($this->session->userdata['unit_id'])?$this->session->userdata['unit_id']:false;
    }
    function user_id(){
        $group_user_id = isset($this->session->userdata['group'])?$this->session->userdata['group']:'0';
        $query = $this->db->query("SELECT user_id FROM group_user
                                    where id = $group_user_id");
        $row = $query->row();
        return isset($row)?$row->user_id:0;
    }
    function jabatan_all(){
        $user_id = $this->user_id();
        $query = $this->db->query(" SELECT distinct jabatan.id, jabatan.name FROM group_user
                                    JOIN jabatan ON jabatan.id = group_user.jabatan_id
                                    where group_user.user_id = $user_id");
        $row = $query->result_array(); 
        return isset($row)?$row:'';
    }
    function project_all($jabatan){
        $user_id = $this->user_id();

        $query = $this->db->query(" SELECT project.id, project.name FROM group_user
                                    JOIN project ON project.id = group_user.project_id
                                    where group_user.user_id = $user_id
                                    AND group_user.jabatan_id = $jabatan");
        $row = $query->result_array(); 
        return isset($row)?$row:'';
    }
    function changeJP($jabatan,$project){
        $user_id = $this->user_id();
        $query = $this->db->query(" SELECT * FROM group_user
                                    where user_id = $user_id
                                    AND jabatan_id = $jabatan
                                    AND project_id = $project");
        $row = $query->row(); 
        if($row){
            $this->session->set_userdata([
                'group'     => $row->id
            ]);
        }
    }
    function currency_to_number($v){
        return preg_replace('/\D/', '', $v);
    }
    function numberToRomanRepresentation($number) {
		$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
		$returnValue = '';
		while ($number > 0) {
			foreach ($map as $roman => $int) {
				if($number >= $int) {
					$number -= $int;
					$returnValue .= $roman;
					break;
				}
			}
		}
		return $returnValue;
    }
    
    function send_email($to,$subject,$message){
        $this->load->library('email');
        $config = [
            'protocol'           => 'smtp',
            'smtp_host'         => 'ssl://smtp.googlemail.com',
            'smtp_timeout'      => 30,
            'smtp_port'         => '465',
            'smtp_user'         => "ciputraems@gmail.com",
            'smtp_from_name'    => 'ciputra EMS',
            'smtp_pass'         => 'antihack22',
            'wordwrap'          => TRUE,
            'mailtype'          => 'html'
        ];  

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");  

        $this->email->from($config['smtp_user'], $config['smtp_from_name']);
        $list = array('rfajrika22@gmail.com');
        $this->email->to($to);
        $this->email->subject("$subject");
        $message ="
            <html>
                <body>
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Fajrika</td>
                            </tr>
                        </tbody>
                    </table>    
                </body>
            </html>
        ";
        $this->email->message("$message");
        if ($this->email->send()) {
            echo 'INI BAP 2.';
        } else {
            show_error($this->email->print_debugger());
        }
    }
}