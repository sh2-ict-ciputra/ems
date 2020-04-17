<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_meter_listrik extends CI_Model {

    public function get()	
    {
	    $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                t_meter_listrik.id,
                t_meter_listrik.periode,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit as unit,
                ISNULL(meter_awal.meter, unit_listrik.angka_meter_sekarang) as meter_awal,
                t_meter_listrik.meter as meter_akhir,
                t_meter_listrik.meter-ISNULL(meter_awal.meter, unit_listrik.angka_meter_sekarang) as pemakaian,
                customer.name as customer	
            FROM	t_meter_listrik
            JOIN unit
                ON unit.id = t_meter_listrik.unit_id
            JOIN unit_listrik
                ON unit_listrik.unit_id = unit.id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = unit.blok_id
                AND kawasan.project_id = $project->id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            LEFT JOIN t_meter_listrik as meter_awal
                ON MONTH(meter_awal.periode)+1 = MONTH(t_meter_listrik.periode)
        ");
        return $query->result_array();
    }
	public function getPT(){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            FROM pt 
            where active = 1
        ");
        return $query->result_array();
    }
    public function getInfoAdd(){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                unit.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit as unit,
                customer.name as pemilik
            FROM unit
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            JOIN unit_listrik
                ON unit_listrik.unit_id = unit.id
            WHERE kawasan.project_id = $project->id
            AND unit.status_tagihan = 1
            AND unit_listrik.aktif = 1
        ");
        return $query->result();
    }
    public function getInfoUnit(){
        $id = $this->input->get('id');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                customer.name as customer,
                unit_listrik.no_seri_meter as barcode,
                case
                    WHEN t_meter_listrik.unit_id = unit.id THEN t_meter_listrik.meter
                    ELSE unit_listrik.angka_meter_sekarang
                END as meter
            FROM unit
            JOIN unit_listrik
                ON unit_listrik.unit_id = unit.id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            LEFT JOIN t_meter_listrik
                ON t_meter_listrik.unit_id = unit.id
            WHERE unit.id = $id
        ");
        return $query->row();
    }
    public function getSelect($id){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                unit.id as unit,
                DATENAME(month,t_meter_listrik.periode) as bulan,
                DATENAME(year,t_meter_listrik.periode) as tahun,
                t_meter_listrik.keterangan,
                customer.name as customer,
                unit_listrik.no_seri_meter,
                ISNULL(meter_awal.meter, unit_listrik.angka_meter_sekarang) as meter_awal,
                t_meter_listrik.meter as meter_akhir,
                t_meter_listrik.meter-ISNULL(meter_awal.meter, unit_listrik.angka_meter_sekarang) as pemakaian
            FROM	t_meter_listrik
            JOIN unit
                ON unit.id = t_meter_listrik.unit_id
            JOIN unit_listrik
                ON unit_listrik.unit_id = unit.id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = unit.blok_id
                AND kawasan.project_id = 1
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            LEFT JOIN t_meter_listrik as meter_awal
                ON MONTH(meter_awal.periode)+1 = MONTH(t_meter_listrik.periode)
            WHERE t_meter_listrik.id = $id
        ");
        return $query->row();
    }
    public function get_log($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                kawasan.name as [Kawasan],
                blok.name as [Blok],
                unit.no_unit as [Unit],
                customer.name as [Pemilik],
                CONCAT(DATENAME(MM,t_meter_listrik.periode),' - ',DATEPART(yy,t_meter_listrik.periode)) as [Periode],
                REPLACE(CONVERT(varchar, CAST(t_meter_listrik.meter AS money), 1),'.00','') as [Luas Tanah]	
            FROM t_meter_listrik
            JOIN unit
                ON unit.id = t_meter_listrik.unit_id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            WHERE t_meter_listrik.id = $id
            AND kawasan.project_id = $project->id
        ");
        $row = $query->row();
        return $row;
    }
	public function last_id(){
        $query = $this->db->query("
            SELECT TOP 1 id FROM customer 
            ORDER by id desc
        ");
        return $query->row()?$query->row()->id:0;
    }
    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                t_meter_listrik.id
            FROM t_meter_listrik
            JOIN unit
                ON unit.id = t_meter_listrik.unit_id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = unit.blok_id
                AND kawasan.project_id = $project->id 
            WHERE t_meter_listrik.id = $id 
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }
	
	public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $data = [
            'unit_id' 		=> $dataTmp['unit_id'],
			'periode' 		=> $dataTmp['periode'],
			'keterangan' 	=> $dataTmp['keterangan'],
			'meter' 		=> $this->m_core->currency_to_number($dataTmp['meter']),
			'active' 		=> 1,
			'delete' 		=> 0
        ];
        
        $this->db->where('periode', $data['periode']);
        $this->db->from('t_meter_listrik');

        // validasi double
        if($this->db->count_all_results()==0){             
            $this->db->insert('t_meter_listrik', $data);
            $idTMP = $this->db->insert_id();

            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('t_meter_listrik', $idTMP, 'Tambah', $dataLog);

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
			'pt_id' 		=> $dataTmp['pt_id'],
			'unit' 			=> $dataTmp['unit'],
			'name' 			=> $dataTmp['name'],
			'address' 		=> $dataTmp['address'],
			'email' 		=> $dataTmp['email'],
			'ktp' 			=> $dataTmp['ktp'],
			'ktp_address' 	=> $dataTmp['ktp_address'],
			'mobilephone1' 	=> $dataTmp['mobilephone1'],
			'mobilephone2' 	=> $dataTmp['mobilephone2'],
			'homephone' 	=> $dataTmp['homephone'],
			'officephone' 	=> $dataTmp['officephone'],
			'npwp_no' 		=> $dataTmp['npwp_no'],
			'npwp_name' 	=> $dataTmp['npwp_name'],
			'npwp_address' 	=> $dataTmp['npwp_address'],
			'description' 	=> $dataTmp['description'],
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
	
	
	

}