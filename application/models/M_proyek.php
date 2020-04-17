<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_proyek extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM 
                project 
            WHERE id = $project->id
            AND [delete]=0 
            order by id desc
        ");

        return $query->result_array();
    }

    public function mapping_save($dataTmp)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $data =
        [
            'subholding_id' => $dataTmp['subholding_id'],
            'project_id' => $project->id,
            'code' => $dataTmp['code'],
            'name' => $dataTmp['name'],
            'address' => $dataTmp['address'],
            'zipcode' => $dataTmp['zipcode'],
            'npwp' => $dataTmp['npwp'],
            'phone' => $dataTmp['phone'],
            'fax' => $dataTmp['fax'],
            'email' => $dataTmp['email'],
            'contactperson' => $dataTmp['contactperson'],
            'active' => $dataTmp['active'],
            'user_tambah' => $dataTmp['user_tambah'],
            'tgl_tambah' => $dataTmp['tgl_tambah'],
            'user ubah' => $dataTmp['user_ubah'],
            'is_transfer' => $dataTmp['is_transfer'],
            'businessgroup_id' => $dataTmp['businessgroup__id'],
            'periode_cutoff' => $dataTmp['periode_cutoff'],
            'api_aci' => $dataTmp['api_aci'],
            'alias' => $dataTmp['alias'],
            'delete' => 0,
        ];

        $this->db->where('code', $data['code']);
        $this->db->from('proyek');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('proyek', $data);

            return 'success';
        } else {
            return 'double';
        }
    }
    
    public function get_ajax_proyek_source($source)
    {
        $project = $this->m_core->project();
        $table = '';
        if ($source == 1)       $table = "global_project";
        elseif ($source == 2)   $table = "erems_project";
        elseif ($source == 3)   $table = "qs_project";
        
        if ($source == 1){

        }else{
            
        }
        
        if ($table != '') {
            $query = $this->db->query("
                SELECT
                    $table.pt_id,
                    $table.code,
                    $table.name,
                    $table.npwp,
                    $table.project_id,
                    pt.id
                FROM $table
                JOIN project
                    ON project.id = $project->id
                    AND project.source_table = $source
                    AND project.source_id = $table.project_id
                LEFT JOIN pt
                    ON pt.source_id = $table.pt_id
                    AND pt.source_table = project.source_table
                WHERE pt.id IS NULL
            ")->result();
            return $query;
        }
        return 0;
    }
}
