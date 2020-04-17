<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_purpose_use extends CI_Model {
    public function get()
    {

        $query = $this->db->query("
            SELECT * FROM purpose_use 
            where [delete] = 0
            order by id desc
        ");
        return $query->result();
        
    }

    public function get_all_purpose_use()
    {
        $this->load->model('m_core');
        $query = $this->db->query("
            SELECT * from purpose_use
            ORDER BY id DESC
        ");
        return $query->result();
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $data = 
        [
            'name'              => $dataTmp['name'],
            'description'       => $dataTmp['keterangan'],
            'active'            => 1,
            'delete'            => 0
        ];

        $this->db->where('name', $data['name']);
        $this->db->from('purpose_use');

        // validasi double
        if($this->db->count_all_results()==0){ 
            
            $this->db->insert('purpose_use', $data);
            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('purpose_use',$this->db->insert_id(),'Tambah',$dataLog);
            return 'success';
        }else return 'double';
           
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        
        $query = $this->db->query("
            SELECT 
                name as Nama,
                description as Deskripsi,
                case when active    = 0 then 'Tidak Aktif' else 'Aktif' end as Aktif, 
                case when [delete]  = 0 then 'Tidak Aktif' else 'Aktif' end as [Delete]  
            FROM purpose_use
            WHERE id        = $id
        ");
        $row = $query->row();
        return $row; 
    }

    public function cek($id){
        $this->load->model('m_core');

        $query = $this->db->query("
            SELECT * 
            FROM purpose_use
            WHERE id = $id 
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }

    public function getSelect($id){
        $this->load->model('m_core');
        
        $query = $this->db->query("
            SELECT * FROM purpose_use
            WHERE id = $id 
        ");
        $row = $query->row();
        return $row; 
    }

    public function edit($dataTmp){
        $this->load->model('m_core');
        $this->load->model('m_log');
        $user_id = $this->m_core->user_id();
        
        $this->db->from('purpose_use');
        $data = 
        [
            'name'                          => $dataTmp['name'],
			'description'                   => $dataTmp['keterangan'],
			'active'                        => $dataTmp['active']?1:0,	
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if($this->db->count_all_results()!=0){
            $this->db->where('name', $data['name'])->where('id !=',$dataTmp['id']);
            $this->db->from('purpose_use');
            // validasi double
            if($this->db->count_all_results()==0){
                $before = $this->get_log($dataTmp['id']);            
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('purpose_use', $data);
                $after = $this->get_log($dataTmp['id']);
                $diff = (object)(array_diff_assoc((array)$after,(array)$before));
                $tmpDiff = (array)$diff;
                
                if($tmpDiff){
                    $this->m_log->log_save('purpose_use',$dataTmp['id'],'Edit',$diff);
                    return 'success';
                }else return 'Tidak Ada Perubahan';
            }else return 'double';  
        }
        
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $user_id = $this->m_core->user_id();

        $this->db->from('purpose_use');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
          
                        $before = $this->get_log($dataTmp['id']);
                        $this->db->where('id', $dataTmp['id']);
                        $this->db->update('purpose_use', ['delete' => 1]);
                        $after = $this->get_log($dataTmp['id']);

                        $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                        $tmpDiff = (array) $diff;

                        if ($tmpDiff) {
                            $this->m_log->log_save('purpose_use', $dataTmp['id'], 'Edit', $diff);

                            return 'success';
                        } else {
                            return 'Tidak Ada Perubahan';
                        }
                   
        }
    }

}

?>