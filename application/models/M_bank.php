<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_bank extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();

        $query = $this->db->select("*")
                            ->from("bank")
                            ->where("delete",0)
                            ->where("project_id",$project->id)
                            ->order_by("id DESC")->get();
        return $query->result_array();
    }
    public function get_jenis(){
        return $this->db->from("bank_jenis")
                            ->get()->result();
    }
    public function get_selected($id){
        $project = $this->m_core->project();
        return $this->db->from("bank")
                        ->where("id",$id)
                        ->where("project_id",$project->id)
                        ->get()->row();
    }
    public function get_order_name(){
        $project = $this->m_core->project();

        $query = $this->db->select("id,name,code")
                    ->from("bank")
                    ->where("delete",0)
                    ->where("project_id",$project->id)
                    ->order_by("name")
                    ->get();
        return $query->result();
    }
    public function get_all_pt_coa()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            coa_mapping.id,
            coa_mapping.coa_id as coa_id,
            pt.name as pt_name,
			coa.code as coa_code,
            coa.description as coa_name
        FROM coa_mapping
            JOIN coa ON coa.id = coa_mapping.coa_id
            JOIN pt ON pt.id = coa_mapping.pt_id
        WHERE coa_mapping.project_id = $project->id
        ");

        return $query->result_array();
    }

    public function get_jenis_service()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            id,
            name          
        FROM service
            WHERE  project_id = $project->id order by id
        ");

        return $query->result_array();
    }

    public function get_rekening($bank_id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                rekening.id as id_rekening,
                rekening.no_rekening as no_rekening,
                rekening.[delete] as delete_rekening,
                service.id as service_id,
                service.name as service_name,
                coa.description as coa_name,
                coa_mapping.id as coa_id		
            FROM rekening
                JOIN coa_mapping ON rekening.coa_mapping_id = coa_mapping.id
                JOIN coa ON coa.id = coa_mapping.coa_id
                JOIN service ON rekening.service_id = service.id
            WHERE coa_mapping.project_id = $project->id	  
            and rekening.bank_id = $bank_id 
            order by rekening.id
        ");
        // echo("<pre>");
        //     print_r($query->result_array());
        // echo("</pre>");
        
        return $query->result_array();
    }

    public function mapping_get($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
		    SELECT * FROM bank 
			WHERE id = $id
            AND project_id = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM bank WHERE id = $id and project_id = $project->id
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  
                    bank.code						as code,
                    bank.name						as name,
                    bank.biaya_admin				as biaya_admin, 
                    bank.description				as description, 
                    case when bank.active = 0		then 'Tidak Aktif' else 'Aktif' end as aktif, 
                    case when bank.[delete] = 0		then 'Tidak di Hapus' else 'Terhapus' end as [delete]
            FROM bank
            WHERE				bank.id             = $id
            AND					bank.project_id     = $project->id
        ");
        $row = $query->row();
        $hasil = [];
        $i = 1;
        if (!array_key_exists('code', $hasil)) {
            $hasil['code']                          = $row->code;
            $hasil['name']                          = $row->name;
            $hasil['biaya_admin']                   = $row->biaya_admin;
            $hasil['description']                   = $row->description;
            $hasil['aktif']                         = $row->aktif;
            $hasil['delete']                        = $row->delete;
        }
        return $hasil;
    }

    public function mapping_get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query('
             SELECT * FROM bank
        ');
        $row = $query->row();

        return $row;
    }
    public function ajax_edit($id,$dataTmp){
        $beforeBank = $this->get_log($id);

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $data = [
            // 'code'          => $dataTmp['code'],
            // 'project_id'    => $project->id,
            // 'name'          => $dataTmp['name'],
            // 'bank_jenis_id' => $dataTmp['bank_jenis_id'],
            'biaya_admin'   => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
            'description'   => $dataTmp['description'],
            // 'active'        => 1,
            // 'delete'        => 0
        ];
        $this->db->where('id', $id);
        $this->db->update('bank', $data);
        $afterBank = $this->get_log($id);
        $diff = (object) (array_diff_assoc((array) $afterBank, (array) $beforeBank));
        $count = count(get_object_vars($diff));

        if ($count) {
            $this->m_log->log_save('bank', $id, 'Edit', $diff);
            return 'success';
        }else {
            return 'failed';
        }
    }
    public function edit($dataTmp)
    {

        $before = $this->get_log($dataTmp['id']);

        $this->db->where('bank_id', $dataTmp['id']);
        $this->db->update('rekening', ['delete' => 1]);


        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $data =
        [
            'code' => $dataTmp['code'],
            'project_id' => $project->id,
            'name' => $dataTmp['name'],
            'va_bank'       => $dataTmp['va_bank'],
            'va_merchant'   => $dataTmp['va_merchant'],
            'max_digit'     => $dataTmp['max_digit'],                
            'biaya_admin' => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
            'description' => $dataTmp['description'],
            'active' => $dataTmp['active'] ? 1 : 0,
            'delete' => 0,
        ];

        
     
        //proses edit
        $beforeBank = $this->get_log($dataTmp['id']);

        $this->db->where('id', $dataTmp['id']);
        $this->db->update('bank', $data);
        $afterBank = $this->get_log($dataTmp['id']);
        $diff = (object) (array_diff_assoc((array) $afterBank, (array) $beforeBank));
        $tmpDiff = (array) $diff;
        if(true){
            $i = 0;
            $jumlahRekeningBaru = 0;
            if($dataTmp['id_rekening'] ){
                foreach ($dataTmp['id_rekening'] as $v) {

                    $dataRekeningTmp = [];
                    $dataRekeningTmp =
                    [
                        'bank_id' => $dataTmp['id'],
                        'no_rekening' => $dataTmp['no_rekening'][$i],
                        'coa_mapping_id' => $dataTmp['coa_mapping_id'][$i],
                        'service_id' => $dataTmp['service_id'][$i],
                        'active' => 1,
                        'delete' => 0,
                    ];
                    if ($v != 0) {
                        $jumlahRekeningBaru++;
                        // edit rekening
                        $this->db->where('id', $dataTmp['id_rekening'][$i]);
                        $this->db->update('rekening', $dataRekeningTmp);
                    }else{
                        // add rekening
                        $this->db->insert('rekening', $dataRekeningTmp);  

                    }

                    $i++;
                }
            }
            $after = $this->get_log($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;
            if ($tmpDiff) {
                $this->m_log->log_save('bank', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            }
        }
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $data = [
            'code'          => $dataTmp['code'],
            'project_id'    => $project->id,
            'name'          => $dataTmp['name'],
            'bank_jenis_id' => $dataTmp['bank_jenis_id'],
            'biaya_admin'   => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
            'description'   => $dataTmp['description'],
            'active'        => 1,
            'delete'        => 0
        ];
        // // print_r($dataTmp);
        // // print_r($dataTmp['code']);
        // print_r($data);
        // $data_rekening = [
        //     'no_rekening' => $dataTmp['no_rekening'],
        //     'service_id' => $dataTmp['service_id'],
        //     'coa_mapping_id' => $dataTmp['coa_mapping_id'],
        //     'active' => 1,
        //     'delete' => 0,
        // ];
        // //var_dump($data_rekening);

        $this->db->where('bank_jenis_id', $data['bank_jenis_id'])
                 ->where("project_id",$project->id);
        $this->db->from('bank');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('bank', $data);
            $id = $this->db->insert_id();
            $dataLog = $this->get_log($id);
            $this->m_log->log_save('bank', $id, 'Tambah', $dataLog);
            return 'success';
        } else {
            return 'double';
        }
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('bank');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            // validasi Cara Pembayaran

            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('bank', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('bank', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }
}
