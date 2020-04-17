<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core extends CI_Controller {
	function __construct() {
		parent::__construct();
        $this->load->model('m_core');
        //var_dump($this->m_theme->jabatan_all());
        
    }
	public function get_jabatan()
	{
        echo json_encode($this->m_core->jabatan_all());
        
    }
    public function get_project()
    {
        echo json_encode($this->m_core->project_all($this->input->post('jabatan')));
    }
    public function changeJP()
    {
        $jabatan = $this->input->post('jabatan');
        $project = $this->input->post('project');
        
        $this->m_core->changeJP($jabatan,$project);
		redirect(site_url().'/Dashboard');
    }
    public function get_log_detail(){
        if($this->input->post('type') == 2){
            $id = $this->input->post('id');
            $this->load->model('m_log');
            $dataAfter = $this->m_log->log_detail_get($id);

            $tableName = $this->m_log->get_table($id);
            $tableId  = $this->m_log->get_table_id($id);

            $query = $this->db->query("
                SELECT id, log_id, name, value
                FROM (
                    select	id,
                            log_id, 
                            name,
                            value,
                            ROW_NUMBER() OVER(PARTITION BY name ORDER BY ID DESC) count
                    from log_detail
                    where log_id IN 
                        (select id from log	
                        where [table]='$tableName' 
                        and table_id='$tableId' 
                        and id<$id)
                    AND name IN 
                        (select name from log_detail
                        where log_id = $id)
                    ) a
                WHERE count = 1
            ");
            $dataBefore = $query->result_array();

            $dataAfter = array($dataAfter);

            array_push($dataAfter,$dataBefore);
            array_push($dataAfter,$this->input->post('type'));

            echo json_encode($dataAfter);
            
        }else{
            $this->load->model('m_log');
            $data = $this->m_log->log_detail_get($this->input->post('id'));
            array_push($data,$this->input->post('type'));
            echo json_encode($data);
        }

    }
    public function test(){
        $this->load->model('m_log');
        $dataAfter = $this->m_log->log_detail_get(17);
        
        $tableName = $this->m_log->get_table(17);
        $tableId  = $this->m_log->get_table_id(17);
        
        var_dump($tableName);
        var_dump($tableId);

        $query = $this->db->query("
                    select id from log	
                    where [table]='$tableName' 
                    and table_id='$tableId' 
                    and id<17
                    ORDER BY id DESC
                ");
        $query = $this->db->query("
                SELECT id, log_id, name, value
                FROM (
                    select	id,
                            log_id, 
                            name,
                            value,
                            ROW_NUMBER() OVER(PARTITION BY name ORDER BY ID DESC) count
                    from log_detail
                    where log_id IN 
                        (select id from log	
                        where [table]='$tableName' 
                        and table_id='$tableId' 
                        and id<17)
                    AND name IN 
                        (select name from log_detail
                        where log_id = 17)
                    ) a
                WHERE count = 1
        ");
        
        $row = $query->result_array();
        echo('<pre>');
        print_r($row);
        echo('</pre>');
        
        $dataBefore = $this->m_log->log_detail_get($row[0]['id']);
        
        $dataAfter = array($dataAfter);
        //var_dump(dataAfter);
        echo('<pre>');
        echo json_encode($dataAfter);
        echo('</pre>');
        
        array_push($dataAfter,$dataBefore);
        array_push($dataAfter,$this->input->post('type'));
        echo('<pre>');
        echo json_encode($dataAfter);
        echo('</pre>');
        
    }
    
	
	
}
