<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_customer extends CI_Model {

    public function get()	
    {
	    $project = $this->m_core->project();
        $query = $this->db->select("*")
                        ->from("customer")
                        ->where("project_id",$project->id)
                        ->where("delete",0)
                        ->order_by("id DESC")->get();
        return $query->result_array();
    }
	public function getPT(){
        $project = $this->m_core->project();
        // $query = $this->db->query("
        //     SELECT * FROM PT 
        //     WHERE project_id = $project->id 
        //     and active = 1 
        //     and [delete] = 0
        //     order by id asc
        // ");
        $project_id = $this->db ->select("source_id")
        ->from("project")
        ->where("id",$project->id)
        ->get()->row()->source_id;

    $result = $this->db ->select('m_pt.pt_id as id,
                m_pt.name')
    ->from("dbmaster.dbo.m_pt")
    ->where("m_pt.project_id",$project_id)
    ->distinct()->get()->result_array();
        return $result;
    }
    public function getDiskon(){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                id,
                name
            FROM diskon
            where active = 1
            and [delete] = 0
            and project_id = $project->id
        ");
        return $query->result_array();
    }
    public function get_log($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                customer.code as [Kode],
                pt.name as [nama PT],
                case
                    when customer.unit = 1 then 'UNIT'
                    else 'Non Unit'
                end as [Unit] ,
                customer.name as [Nama],
                customer.address as [Alamat Domisili],
                customer.email as [Email],
                customer.ktp as [No KTP],
                customer.ktp_address as [Alamat KTP],
                customer.mobilephone1 as [Mobile Phone 1],
                customer.mobilephone2 as [Mobile Phone 2],
                customer.homephone as [Home Phone],
                customer.officephone as [Office Phone],
                customer.npwp_no as [No NPWP],
                customer.npwp_name as [Nama NPWP],
                customer.npwp_address as [Alamat NPWP],
                customer.description as [Deskripsi],
                case
                    when customer.active = 1 then 'Aktif'
                    else 'Tidak Aktif'
                end as Aktif,
                case
                    when customer.[delete] = 1 then 'Terhapus'
                    else 'Tidak Terhapus'
                end as [Delete]
            FROM customer
            JOIN PT 
                ON PT.id = customer.pt_id
            LEFT JOIN diskon
                ON diskon.id = customer.diskon_id
            WHERE customer.id = $id
            AND customer.project_id = $project->id
        ");
        $row = $query->row();
        return $row;
    }
	public function last_id(){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT TOP 1 
                SUBSTRING(code, CHARINDEX('".date("Y")."/',code)+5, 6) as code
            FROM customer
            where code like '%".date("Y")."%' 
            AND project_id = '$project->id'
            ORDER by id desc
        ");
        return $query->row()?$query->row()->code:0;
    }
	public function last_id_by_project($project_id){
        $query = $this->db->query("
            SELECT TOP 1 
                SUBSTRING(code, CHARINDEX('".date("Y")."/',code)+5, 6) as code
            FROM customer
            where code like '%".date("Y")."%' 
            AND project_id = '$project_id'
            ORDER by id desc
        ");
        return $query->row()?$query->row()->code:0;
    }
    public function getSelect($id){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            FROM customer 
            where project_id = $project->id
            and id = $id
        ");
        return $query->row();
    }
    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                id
            FROM customer 
            WHERE id = $id 
            and project_id = $project->id
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }
	
	public function save($dataTmp)
    {
        
        
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $data = 
        [   
            'code' 		             => "CUST/$project->code/".date("Y")."/".str_pad(($this->last_id()+1), 4, "0", STR_PAD_LEFT),
			'project_id'             => $project->id,
            'pt_id' 		         => $dataTmp['pt_id'],
            'diskon_id' 	         => $dataTmp['gol_diskon_id'],
			'unit' 			         => $dataTmp['unit'],
			'name'                   => $dataTmp['name'],
			'address' 		         => $dataTmp['address'],
			'email' 		         => $dataTmp['email'],
			'ktp' 			         => $dataTmp['ktp'],
			'ktp_address' 	         => $dataTmp['ktp_address'],
			'mobilephone1'           => str_replace("_","",$dataTmp['mobilephone1']),
			'mobilephone2' 	         => $dataTmp['mobilephone2'],
			'homephone' 	         => $dataTmp['homephone'],
			'officephone' 	         => $dataTmp['officephone'],
			'npwp_no' 		         => $dataTmp['npwp_no'],
			'npwp_name' 	         => $dataTmp['npwp_name'],
			'npwp_address' 	         => $dataTmp['npwp_address'],
            'description'            => $dataTmp['description'],
            // 'expired_diskon' 	     => $dataTmp['expired_diskon']?str_replace('/','-',$dataTmp['expired_diskon']):'',
			'active' 		         => 1,
			'delete'                 => 0
        ];

        // echo("<pre>");
        //     print_r($data);
        // echo("</pre>");
        $this->db->where('code', $data['code'])
                ->or_where('ktp',$data['ktp']);
        $this->db->from('customer');

        // validasi double
        if($this->db->count_all_results()==0){             
            $this->db->insert('customer', $data);
            $idTMP = $this->db->insert_id();
            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('customer', $idTMP, 'Tambah', $dataLog);

            return 'success';
        }else return 'double';
        
        
        
    }
    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = 


        [   
            'code' 		    => $dataTmp['code'],
            'pt_id' 		=> $dataTmp['pt_id'],
            'diskon_id' 	=> $dataTmp['gol_diskon_id'],
			'unit' 			=> $dataTmp['unit'],
			'name' 			=> $dataTmp['name'],
			'address' 		=> $dataTmp['address'],
			'email' 		=> $dataTmp['email'],
			'ktp' 			=> $dataTmp['ktp'],
			'ktp_address' 	=> $dataTmp['ktp_address'],
			'mobilephone1'  => str_replace("_","",$dataTmp['mobilephone1']),
			'mobilephone2' 	=> $dataTmp['mobilephone2'],
			'homephone' 	=> $dataTmp['homephone'],
			'officephone' 	=> $dataTmp['officephone'],
			'npwp_no' 		=> $dataTmp['npwp_no'],
			'npwp_name' 	=> $dataTmp['npwp_name'],
			'npwp_address' 	=> $dataTmp['npwp_address'],
            'description' 	=> $dataTmp['description'],
            // 'expired_diskon'=> $dataTmp['expired_diskon']?$dataTmp['expired_diskon']:null,
			'active' 		=> $dataTmp['active'],
			'delete' 		=> 0
        ];

        // validasi data
        if($this->cek($dataTmp['id'])){
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('customer', $data);
            $after = $this->get_log($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('customer', $dataTmp['id'], 'Edit', $diff);
                return 'success';
            }            
        }else{
            return 'error';
        }
    }
	public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('customer');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            // validasi Unit
            $this->db->where('pemilik_customer_id', $dataTmp['id']);
            $this->db->or_where('penghuni_customer_id', $dataTmp['id']);
            $this->db->from('unit');
            if ($this->db->count_all_results() == 0) {

                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->set("delete",1);
                $this->db->update('customer');
                $after = $this->get_log($dataTmp['id']);

                $diff = (object) (array_diff((array) $after, (array) $before));
                $tmpDiff = (array) $diff;
                if ($tmpDiff) {
                    $this->m_log->log_save('customer', $dataTmp['id'], 'Delete', $diff);

                    return 'success';
                } else
                    return 'Tidak Ada Perubahan';
            } else
                return 'unit';
        }
    }
}