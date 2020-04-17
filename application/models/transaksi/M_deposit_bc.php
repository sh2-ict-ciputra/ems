<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_deposit extends CI_Model
{
    public function get_all_unit()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
                SELECT 
                    unit.id as unit_id,
                    unit.no_unit,
                    blok.name as blok,
                    kawasan.name as kawasan,
                    pemilik.name as pemilik
                FROM unit
                JOIN blok
                    ON blok.id = unit.blok_id
                JOIN kawasan
                    ON kawasan.id = blok.kawasan_id
                JOIN customer as pemilik
                    ON pemilik.id = unit.pemilik_customer_id
                WHERE unit.project_id = $project->id
        ");
        $result = $query->result();
        
        return $result;
    }
    public function ajax_get_tagihan($unit_id){
        $dataAir = $this->db->select("periode,'Air' as Service, total_tagihan, 0 as denda, 0 as penalty, sudah_dibayar")
                            ->where("unit_id",$unit_id)
                            ->where("status_bayar_flag !=","1")
                            ->get("v_tagihan_air")
                            ->result();
        $dataPL = $this->db ->select("periode,'PL' as Service, total_tagihan, 0 as denda, 0 as penalty, sudah_dibayar")
                            ->where("unit_id",$unit_id)
                            ->where("status_bayar_flag !=","1")
                            ->get("v_tagihan_lingkungan")
                            ->result(); 
        $data = (object)array_merge((array)$dataAir,(array)$dataPL);
        return $data;
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
    public function get_no_referensi(){
        $project = $this->m_core->project();
        $no_referensi_tmp = $project->code."/".$this->numberToRomanRepresentation((int)date('m'))."/".date('Y')."/";
        $no = $this->db
                                ->select("")
                                ->from("kwitansi_referensi")
                                ->where("no_referensi LIKE '%$no_referensi_tmp%'")
                                ->where("project_id",$project->id)
                                ->order_by("no_referensi DESC")
                                ->limit(1)
                                ->get()->row();
        if($no){
            $no = $no->no_referensi;
            $no = ((int)substr($no,strripos($no,'/')+1,8));
            $no = $no + 1;
        }
        else    $no = 1;
        
        $no             = str_pad($no,8,'0',STR_PAD_LEFT);
        $no_referensi   = $no_referensi_tmp.$no;

        return $no_referensi;
    }
    public function ajax_get_customer($data){
        $project = $this->m_core->project();
        return $this->db->select("customer.id, customer.name as text")
                            ->from('customer')
                            ->join('unit',
									'unit.pemilik_customer_id = customer.id')
							->where("customer.project_id",$project->id)
							->where("customer.unit = 0 or (customer.unit = 1 and unit.id is not null)")
							->where("customer.name like '%$data%'")
                            ->get()->result();
    }
    public function ajax_get_deposit($customer_id){
        $project = $this->m_core->project();
        $saldo = (int)$this->db
                            ->select("
                                        sum(t_deposit_detail.nilai) as deposit
                                    ")
                            ->from("unit")
                            ->join("t_deposit",
                                    "t_deposit.customer_id = unit.pemilik_customer_id
                                    AND t_deposit.project_id = unit.project_id")
                            ->join("t_deposit_detail",
                                    "t_deposit_detail.t_deposit_id = t_deposit.id")                                    
                            ->where("unit.project_id",$project->id)
                            ->where("unit.pemilik_customer_id",$customer_id)
                            ->get()->row()->deposit;
        return (int)$saldo;
        // var_dump($saldo);
    }
    public function save($data){
        $data_deposit               = (object)[];
        $data_kwitansi_referensi    = (object)[];
        $data_deposit_detail        = (object)[];
        $this->db->trans_start();
        $insert_id = $this->db->insert_id();

        $data_deposit->project_id = $data->project_id;
        $data_deposit->customer_id = $data->customer_id;
        $this->db->insert("t_deposit",$data_deposit); 
        $data_deposit_detail->t_deposit_id = $this->db->insert_id();

        $data_kwitansi_referensi->project_id    = $data->project_id;
        $data_kwitansi_referensi->no_referensi  = $data->no_referensi;
        $this->db->insert("kwitansi_referensi",$data_kwitansi_referensi); 
        $data_deposit_detail->kwitansi_referensi_id = $this->db->insert_id();

        $data_deposit_detail->cara_pembayaran_id    = $data->cara_pembayaran_id;
        $data_deposit_detail->nilai                 = $data->tambah_deposit;
        $data_deposit_detail->tgl_document          = $data->tgl_document;
        $data_deposit_detail->tgl_tambah            = $data->tgl_tambah;
        $data_deposit_detail->user_id               = $data->user_id;
        $data_deposit_detail->description           = $data->deskripsi;
        $this->db->insert("t_deposit_detail",$data_deposit_detail); 

        $this->db->trans_complete();
        return true;

    }
}
