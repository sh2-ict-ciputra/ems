<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_history_pembayaran_harian extends CI_Model
{

    public function getAll()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
                SELECT 
                    unit.id as unit_id,
                    unit.no_unit,
                    blok.name as blok,
                    blok.code as code_blok,
                    kawasan.name as kawasan,
                    pemilik.name as pemilik,
                    unit.project_id as code_project,
                    kwitansi.no_referensi as no_kwitansi,
                    bayar.tgl_bayar as tgl_bayar
                FROM unit
                JOIN blok
                    ON blok.id = unit.blok_id
                JOIN kawasan
                    ON kawasan.id = blok.kawasan_id
                JOIN customer as pemilik
                    ON pemilik.id = unit.pemilik_customer_id
                JOIN project
                    ON project.id = pemilik.project_id
                JOIN kwitansi_referensi as kwitansi
                    ON kwitansi.project_id = project.id
                JOIN t_pembayaran as bayar
                    ON bayar.unit_id = unit.id
                WHERE unit.project_id = $project->id
        ");
        $result = $query->result_array();
        
        return $result;
    }
    
}
