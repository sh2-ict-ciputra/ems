<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_unit_virtual extends CI_Model {

    public function get()
		
    {
		
	    $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM unit_virtual where [delete] = 0 order by id desc
        ");
        return $query->result_array();
    }
	
	
	public function getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT customer.name as name , unit_virtual.*  FROM unit_virtual
			join customer on customer.id = unit_virtual.customer_id
            where customer.project_id =  $project->id and unit_virtual.[delete] = 0 order by unit_virtual.id desc
        ");
        return $query->result_array();
    }
	
	
	 public function getCustomer()
		
    {
		
	    $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM customer where active =1 and project_id = $project->id
        ");
        return $query->result_array();
    }
	
	
	 public function get_log($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
             SELECT * FROM unit_virtual where id = $id
        ");
        $row = $query->row();
        return $row; 
    }
	
	
	public function save($dataTmp)
    {
        $this->load->model('m_core');
		$this->load->model('m_log');
        $project = $this->m_core->project();
        $data = 
        [
		
		
		    
            'unit'                    => $dataTmp['unit'],
            'customer_id'             => $dataTmp['customer_id'],
			'alamat'                  => $dataTmp['alamat'],
			'va'                      => $dataTmp['va'],
			'active'                  => 1,			
			'delete'                  => 0
			
	
							
			
			
        ];

        $this->db->where('unit', $data['unit']);
        $this->db->from('unit_virtual');

         // validasi double
        if($this->db->count_all_results()==0){ 
            
            $this->db->insert('unit_virtual', $data);
			
			$idTMP = $this->db->insert_id();
            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('unit_virtual', $idTMP, 'Tambah', $dataLog);

			
            return 'success';
        }else return 'double';
        
        
        
    }
	
	public function cek($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM unit_virtual WHERE id = $id and active = 1
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }
	
	
	 public function getSelect($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
           
		   SELECT customer.name as name , unit_virtual.*  FROM unit_virtual
			join customer on customer.id = unit_virtual.customer_id
            where customer.project_id =  $project->id and unit_virtual.id = $id
			
        ");
        $row = $query->row();
        return $row; 
    }
	
	
	
	
	
	
	
	
	public function edit($dataTmp){
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

       // $this->db->where('project_id', $project->id);
       // $this->db->from('cara_pembayaran');
        $data = 
        [
   
			
		
			'unit'                          => $dataTmp['unit'],
            'customer_id'                   => $dataTmp['customer_id'],
            'alamat'                        => $dataTmp['alamat'],
			'va'                            => $dataTmp['va'],
			'active'                        => $dataTmp['active']?1:0,
			
						
			
			
			
        ];
        // validasi apakah user dengan project $project boleh edit data ini
       // if($this->db->count_all_results()!=0){
            $this->db->where('va', $dataTmp['va'])->where('id !=',$dataTmp['id']);
            $this->db->from('unit_virtual');
            // validasi double
           if($this->db->count_all_results()==0){ 
                        
                        $before = $this->get_log($dataTmp['id']);            
                        $this->db->where('id', $dataTmp['id']);
                        $this->db->update('unit_virtual', $data);
                        $after = $this->get_log($dataTmp['id']);

                        $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                        $tmpDiff = (array) $diff;
                        if($tmpDiff){
                            $this->m_log->log_save('unit_virtual',$dataTmp['id'],'Edit',$diff);
                            return 'success';
                        }else return 'Tidak Ada Perubahan';
                   
           }else return 'double';


            
            
        //}
        
    }
	
	
	
	public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

       // $this->db->where('project_id', $project->id);
       // $this->db->from('unit_virtual');

        // validasi apakah user dengan project $project boleh edit data ini
       // if ($this->db->count_all_results() != 0) {
          
                        $before = $this->get_log($dataTmp['id']);
                        $this->db->where('id', $dataTmp['id']);
                        $this->db->update('unit_virtual', ['delete' => 1]);
                        $after = $this->get_log($dataTmp['id']);

                        $diff = (object) (array_diff((array) $after, (array) $before));
                        $tmpDiff = (array) $diff;

                        if ($tmpDiff) {
                            $this->m_log->log_save('unit_virtual', $dataTmp['id'], 'Edit', $diff);

                            return 'success';
                        } else {
                            return 'Tidak Ada Perubahan';
                        }
                  
        //}
    }
	
	
	

}