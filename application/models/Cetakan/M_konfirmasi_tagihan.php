<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_konfirmasi_tagihan extends CI_Model
{

    public function get_unit($unit_id)
    {

        $query = $this->db
                    ->select("
                        pemilik.name as pemilik,
                        penghuni.name as penghuni,
                        CASE 
                            WHEN pemilik.id = penghuni.id or penghuni.id = 0 or penghuni.id is null 
                                THEN CONCAT(pemilik.name,' ( ',pemilik.mobilephone1,' )')
                            ELSE 
                                CONCAT(pemilik.name,' ( ',pemilik.mobilephone1,' )','<br>',penghuni.name,' ( ',penghuni.mobilephone1,' )')
                        END as customer_name,
                        unit.no_unit,
                        project.name as project,
                        blok.name as blok,
                        kawasan.name as kawasan,
                        pt.name as pt,
                        unit.kirim_tagihan,
                        project.contactperson,
                        project.phone,
                        parameter_pj_jabatan.value as pp_name,
                        parameter_pj.value as pp_value,
                        parameter_catatan.value as catatan,
                        project_address.value as project_address,
                        unit.virtual_account,
                        CONCAT(bank.va_bank,bank.va_merchant) as mandiri_va,
                        CASE 
                            WHEN unit.kirim_tagihan = 1 and isnull(penghuni.id,0) = 0 THEN pemilik.address
                            ELSE CONCAT('Cluster ', kawasan.name,' - Blok ', blok.name ,'/',unit.no_unit)
                        END as alamat
                    ")
                    ->from("unit")
                    ->join("customer as pemilik",
                            "pemilik.id = unit.pemilik_customer_id",
                            "LEFT")
                    ->join("customer as penghuni",
                            "penghuni.id = unit.penghuni_customer_id",
                            "LEFT")
                    ->join("project",
                            "project.id = unit.project_id")
                    ->join("blok", 
                            "blok.id = unit.blok_id")
                    ->join("kawasan",
                            "kawasan.id = blok.kawasan_id")
                    ->join("pt",
                            "pt.id = unit.pt_id",
                            "LEFT")
                    ->join("parameter_project as parameter_pj",
                            "parameter_pj.project_id = unit.project_id
                            AND parameter_pj.code = 'pj_konfirmasi_tagihan'")
                    ->join("parameter_project as parameter_pj_jabatan",
                            "parameter_pj_jabatan.project_id = unit.project_id
                            AND parameter_pj_jabatan.code = 'jabatan_pj_konfirmasi_tagihan'")
                    ->join("parameter_project as project_address",
                            "project_address.project_id = unit.project_id
                            AND project_address.code = 'address_konfirmasi_tagihan'")
                    ->join("parameter_project as parameter_catatan",
                            "parameter_catatan.project_id = unit.project_id
                            AND parameter_catatan.code = 'catatan_konfirmasi_tagihan'")
                    ->join("bank",
                            "bank.project_id = unit.project_id
                            AND bank.code = 'MANDIRIVA'",
                            "LEFT")
                    ->where("unit.id",$unit_id)
                    ->get()->row();
        return $query;
    }
    public function get_tagihan($unit_id){
        $query = $this->db->select("
                                periode,
                                meter_awal,
                                meter_akhir,
                                meter_pakai,
                                REPLACE(CONVERT(varchar, CAST(tagihan_lain AS money), 1), '.00', '') as tagihan_lain,
                                REPLACE(CONVERT(varchar, CAST(tagihan_air AS money), 1), '.00', '') as tagihan_air,
                                REPLACE(CONVERT(varchar, CAST(tagihan_lingkungan AS money), 1), '.00', '') as tagihan_lingkungan,
                                REPLACE(CONVERT(varchar, CAST(ppn_lingkungan AS money), 1), '.00', '') as ppn_lingkungan,
                                REPLACE(CONVERT(varchar, CAST(total_denda AS money), 1), '.00', '') as total_denda,
                                REPLACE(CONVERT(varchar, CAST(tagihan_lain+tagihan_air+tagihan_lingkungan+ppn_lingkungan+total_denda AS money), 1), '.00', '') as total    
                            ")
                            ->from("v_konfirmasi_tagihan")
                            ->where("unit_id",$unit_id)
                            ->order_by("periode")
                            ->get()->result();
        return $query;
    }
    public function get_saldo_deposit($unit_id){

        $unit = $this->db->select("project_id,pemilik_customer_id as customer_id")->from("unit")->where("id",$unit_id)->get()->row();
        $result = $this->db->select("
                            REPLACE(CONVERT(varchar, CAST(saldo AS money), 1), '.00', '') as saldo,
                        ")->from("v_saldo_deposit")->where("project_id",$unit->project_id)->where("customer_id",$unit->customer_id)->get()->row();
        return $result?$result->saldo:0;
    }
    public function get_status_saldo_deposit($unit_id){
        $unit = $this->db->select("project_id")->from("unit")->where("id",$unit_id)->get()->row();
        return $this->db->select("value")->from("parameter_project")->where("project_id",$unit->project_id)->where("code",'saldo_deposit_konfirmasi_tagihan')->get()->row()->value;
    }
}
