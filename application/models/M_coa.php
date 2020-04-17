<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_coa extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();

        $project_id = $this->db->select("source_id")->from("project")->where("id",$project->id)->get()->row()->source_id;
        
        return $this->db->select("
                                view_coa.coa_id as id,
                                pt.id as pt_id,
                                pt.name as ptName,
                                view_coa.name as coaName,
                                view_coa.coa as coaCode,
                                view_coa.active,
                                view_coa.deleted,
                                view_coa.is_journal
                            ")
                            ->from("gl_2018.dbo.view_coa")
                            ->join("ems.dbo.pt",
                                    "pt.source_id = view_coa.pt_id")
                            ->where("view_coa.project_id",$project_id)->get()->result();
    }
    public function get_isjournal(){
        $project = $this->m_core->project();

        $project_id = $this->db ->select("source_id")
                                ->from("project")
                                ->where("id",$project->id)
                                ->get()->row()->source_id;
                                
        return $this->db->select("
                                view_coa.coa_id as id,
                                m_pt.pt_id as pt_id,
                                m_pt.name as ptName,
                                view_coa.name as coaName,
                                view_coa.coa as coaCode,
                                view_coa.active,
                                view_coa.deleted,
                                view_coa.is_journal
                            ")
                            ->from("gl_2018.dbo.view_coa")
                            ->join("dbmaster.dbo.m_pt",
                                    "m_pt.pt_id = view_coa.pt_id")
                            ->join("ems.dbo.project",
                                    "project.source_id = m_pt.project_id")
                            // ->join("ems.dbo.pt",
                            //         "pt.source_id = view_coa.pt_id")
                            ->where("project.id",$project->id)
                            ->where("view_coa.is_journal",1)
                            ->where("view_coa.deleted",0)
                            ->get()->result();
    }
    public function mapping_get($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM coa_mapping
            WHERE id = $id
            AND project_id = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function mapping_get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT pt.name as pt, coa.description as coa, coa_mapping.description as description, case when coa_mapping.active = 0 then 'Tidak Aktif' else 'Aktif' end as aktif, coa_mapping.[delete] as [delete] FROM coa_mapping
            join pt on pt.id = coa_mapping.pt_id
            join coa on coa.id = coa_mapping.coa_id
            WHERE coa_mapping.id = $id
            AND coa_mapping.project_id = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function mapping_save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data =
        [
            'pt_id' => $dataTmp['pt_id'],
            'project_id' => $project->id,
            'coa_id' => $dataTmp['coa_id'],
            'description' => $dataTmp['description'],
            'active' => 1,
            'delete' => 0,
        ];

        $this->db->where('pt_id', $data['pt_id'])->where('coa_id', $data['coa_id'])->where('project_id',$project->id);
        $this->db->from('coa_mapping');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            // validasi PT
            $this->db->where('id', $data['pt_id']);
            $this->db->from('pt');
            if ($this->db->count_all_results() > 0) {
                // validasi coa
                $this->db->where('id', $data['coa_id']);
                $this->db->from('coa');
                if ($this->db->count_all_results() > 0) {
                    $this->db->insert('coa_mapping', $data);
                    $dataLog = $this->mapping_get_log($this->db->insert_id());
                    $this->m_log->log_save('coa_mapping', $this->db->insert_id(), 'Tambah', $dataLog);

                    return 'success';
                } else {
                    return 'coa';
                }
            } else {
                return 'pt';
            }
        } else {
            return 'double';
        }
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM coa_mapping WHERE id = $id and project_id = $project->id
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function mapping_edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('coa_mapping');
        $data =
        [
            'pt_id' => $dataTmp['pt_id'],
            'project_id' => $project->id,
            'coa_id' => $dataTmp['coa_id'],
            'description' => $dataTmp['description'],
            'active' => $dataTmp['active'] ? 1 : 0,
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('pt_id', $data['pt_id'])->where('coa_id', $data['coa_id'])->where('id !=', $dataTmp['id']);
            $this->db->from('coa_mapping');
            // validasi double
            if ($this->db->count_all_results() == 0) {
                // validasi PT
                $this->db->where('id', $data['pt_id']);
                $this->db->from('pt');
                if ($this->db->count_all_results() > 0) {
                    // validasi coa
                    $this->db->where('id', $data['coa_id']);
                    $this->db->from('coa');
                    if ($this->db->count_all_results() > 0) {
                        $before = $this->mapping_get_log($dataTmp['id']);
                        $this->db->where('id', $dataTmp['id']);
                        $this->db->update('coa_mapping', $data);
                        $after = $this->mapping_get_log($dataTmp['id']);

                        $diff = (object) (array_diff((array) $after, (array) $before));
                        $tmpDiff = (array) $diff;
                        if ($tmpDiff) {
                            $this->m_log->log_save('coa_mapping', $dataTmp['id'], 'Edit', $diff);

                            return 'success';
                        } else {
                            return 'Tidak Ada Perubahan';
                        }
                    } else {
                        return 'coa';
                    }
                } else {
                    return 'pt';
                }
            } else {
                return 'double';
            }
        }
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('coa_mapping');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            // validasi Cara Pembayaran
            $this->db->where('coa_mapping_id', $dataTmp['id']);
            $this->db->from('cara_pembayaran');
            if ($this->db->count_all_results() == 0) {
                // validasi Metode Penagihan
                $this->db->where('coa_mapping_id', $dataTmp['id']);
                $this->db->from('metode_penagihan');
                if ($this->db->count_all_results() == 0) {
                    $this->db->where('service_coa_mapping_id', $dataTmp['id'])->where('ppn_coa_mapping_id', $dataTmp['id']);
                    $this->db->from('service');
                    if ($this->db->count_all_results() == 0) {
                        $before = $this->mapping_get_log($dataTmp['id']);
                        $this->db->where('id', $dataTmp['id']);
                        $this->db->update('coa_mapping', ['delete' => 1]);
                        $after = $this->mapping_get_log($dataTmp['id']);

                        $diff = (object) (array_diff((array) $after, (array) $before));
                        $tmpDiff = (array) $diff;

                        if ($tmpDiff) {
                            $this->m_log->log_save('coa_mapping', $dataTmp['id'], 'Edit', $diff);

                            return 'success';
                        } else {
                            return 'Tidak Ada Perubahan';
                        }
                    } else {
                        return 'service';
                    }
                } else {
                    return 'metode_penagihan';
                }
            } else {
                return 'cara_pembayaran';
            }
        }
    }
}
