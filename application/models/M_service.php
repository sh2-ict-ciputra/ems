<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_service extends CI_Model {

    public function get()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        

        $query = $this->db->query("
            SELECT 
                * 
            FROM service
            WHERE project_id = $project->id
            AND [delete] = 0
        ");
        return $query->result();
    }
    public function get_by_id($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            FROM service
            WHERE id = $id
            AND project_id = $project->id
        ");
        return $query->row();
    }
	public function cek($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM service 
            WHERE id = $id 
            AND project_id = $project->id
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }
    public function get_view()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->select("
                                    service.*,
                                    pt_service.name as pt_service,
                                    coa_service.name as coa_service,
                                    coa_service.coa as coa_service_code,
                                    coa_ppn.name as coa_ppn,
                                    coa_ppn.coa as coa_ppn_code
                                    ")
                            ->from("service")
                            ->join("gl_2018.dbo.view_coa as coa_service",
                                "coa_service.coa_id = service.service_coa_mapping_id",
                                "LEFT")
                            ->join("gl_2018.dbo.view_coa as coa_ppn",
                                "coa_ppn.coa_id = service.ppn_coa_mapping_id",
                                "LEFT")
            
                            ->join("dbmaster.dbo.m_pt as pt_service",
                                "pt_service.pt_id = coa_service.pt_id",
                                "LEFT")
                            ->join("dbmaster.dbo.m_pt as pt_ppn",
                                "pt_ppn.pt_id = coa_ppn.pt_id",
                                "LEFT")                                 
                            ->where("service.project_id",$project->id)
                            ->where("service.delete",0)
                    
                            ->get()->result();
        return $query;
    }
	
	
	public function mapping_getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT coa_mapping.*, cara_pembayaran.*, pt.name as ptName FROM coa_mapping
            join pt on pt.id = coa_mapping.pt_id
            join coa on coa.id = coa_mapping.coa_id
			join cara_pembayaran on cara_pembayaran.coa_mapping_id = coa_mapping.id
            where coa_mapping.project_id =  $project->id
        ");

        return $query->result_array();
    }
    public function mapping_get_all_pt(){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT DISTINCT pt.id, pt.name FROM coa_mapping
            join pt on pt.id = coa_mapping.pt_id
            join coa on coa.id = coa_mapping.coa_id
			where coa_mapping.project_id =  $project->id
        ");
        return $query->result_array();
    }
    public function get_log($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $row = $this->db
                    ->select("
                    service_jenis.name_default as [Jenis],
                    service.code as [Kode],
                    service.name as [Nama],
                    service.jarak_periode_penggunaan as [Jarak],
                    coa_service.name as [Coa Service],
                    pt_service.name as [PT Service],
                    CASE 
                        WHEN service.ppn_flag = 1 THEN 'Aktif'
                        ELSE 'Tidak Aktif'
                    END as [PPN Service],
                    coa_ppn.name as [Coa PPN],
                    pt_ppn.name as [PT PPN],
                    service.tgl_jatuh_tempo as [Tanggal Jatuh Tempo],
                    CASE
                        WHEN service.denda_flag = 1 THEN 'Aktif'
                        ELSE 'Tidak Aktif'
                    END as [Denda],
                    service.denda_selisih_bulan as [Denda Selisih Bulan],
                    service.denda_tanggal_jt as [Denda Tanggal Jatuh Tempo],
                    case 
                            when service.denda_jenis = 1 then 'Fixed'
                            when service.denda_jenis = 2 then 'Progresif'
                            when service.denda_jenis = 3 then 'Persen Nilai Piutang'
                    end as [Jenis Denda],
                    service.denda_minimum as [Minimum Denda],
                    service.denda_nilai as [Nilai Denda],
                    CASE
                        WHEN service.denda_tgl_putus_flag = 1 THEN 'Aktif'
                        ELSE 'Tidak Aktif'
                    END as [Denda Tanggal Putus],
                    
                    CASE
                        WHEN service.penalti_flag = 1 THEN 'Aktif'
                        ELSE 'Tidak Aktif'
                    END as [Penalti],
                    service.penalti_selisih_bulan as [Penalti Selisih Bulan],
                    service.penalti_tanggal_jt as [Penalti Tanggal Jatuh Tempo],
                    case 
                            when service.penalti_jenis = 1 then 'Fixed'
                            when service.penalti_jenis = 3 then 'Persen Nilai Piutang'
                    end as [Jenis Penalti],
                    service.penalti_minimum as [Minimum Penalti],
                    service.penalti_nilai as [Nilai Penalti],
                    CASE
                        WHEN service.penalti_tgl_putus_flag = 1 THEN 'Aktif'
                        ELSE 'Tidak Aktif'
                    END as [Penalti Tanggal Putus],
                    case 
                            when service.denda_tgl_putus_flag = 1 then 'Flag'
                            else 'No Flag'
                    end as denda_tanggal_putus_flag,
                    service.description as [Deskripsi],
                    case
                            when service.active = 1 then 'Aktif'
                            else 'Non Aktif'
                    end as [Aktif],
                    service.delete as [Delete]
                    ")
                ->from("service")
                ->join("service_jenis",
                    "service_jenis.id = service.service_jenis_id")
                ->join("gl_2018.dbo.view_coa as coa_service",
                    "coa_service.coa_id = service.service_coa_mapping_id",
                    "LEFT")
                ->join("gl_2018.dbo.view_coa as coa_ppn",
                    "coa_ppn.coa_id = service.ppn_coa_mapping_id",
                    "LEFT")

                ->join("pt as pt_service",
                    "pt_service.source_id = coa_service.pt_id",
                    "LEFT")
                ->join("pt as pt_ppn",
                    "pt_ppn.source_id = coa_ppn.pt_id",
                    "LEFT")                            
                ->where("service.project_id", $project->id)
                ->where("service.id",$id)
                ->get()->row();
        return $row;
    }
	public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = 
        [
            'project_id'                => $project->id,
            // 'retribusi'                 => $dataTmp['jenis_service']=="Retribusi"?1:0,
            'service_jenis_id'          => $dataTmp['jenis_retribusi'],
            'code'                      => $dataTmp['code'],
			'name'                      => $dataTmp['nama_service'],
            'service_coa_mapping_id'    => $dataTmp['coa_mapping_id_service'],
            'ppn_flag'                  => $dataTmp['ppn_flag'],
            'ppn_coa_mapping_id'        => $dataTmp['coa_mapping_id_ppn'],
            'tgl_jatuh_tempo'           => $dataTmp['parameter_tanggal_jatuh_tempo'],
            'denda_penalti_coa_mapping_id'           => $dataTmp['coa_mapping_id_service_denda'],
            
            'denda_flag'                => $dataTmp['denda_jenis'],
            'denda_selisih_bulan'       => $dataTmp['denda_selisih_bulan'],
            'denda_tanggal_jt'          => $dataTmp['denda_tanggal_jt'],
            'denda_jenis'               => $dataTmp['denda_jenis'],
			'denda_minimum'             => $this->m_core->currency_to_number($dataTmp['denda_minimum']),
			'denda_nilai'              => $this->m_core->currency_to_number($dataTmp['denda_nilai']),
            'denda_tgl_putus_flag'       => $dataTmp['denda_tgl_putus'],
            'penalti_flag'                => $dataTmp['penalti_jenis'],
            'penalti_selisih_bulan'       => $dataTmp['penalti_selisih_bulan'],
            'penalti_tanggal_jt'          => $dataTmp['penalti_tanggal_jt'],
            'penalti_jenis'               => $dataTmp['penalti_jenis'],
			'penalti_minimum'             => $this->m_core->currency_to_number($dataTmp['penalti_minimum']),
			'penalti_nilai'              => $this->m_core->currency_to_number($dataTmp['penalti_nilai']),
            'penalti_tgl_putus_flag'       => $dataTmp['penalti_tgl_putus'],
            'jarak_periode_penggunaan'  => $dataTmp['jarak_periode_penggunaan'],
            
            'description'               => $dataTmp['description'],
            'active'                    => 1,
            'delete'                    => 0
        ];
        // echo("<pre>");
		// 	print_r($data);
        // echo("</pre>");
        // echo("<pre>");
		// 	print_r($data);
        // echo("</pre>");
        
        $this->db->where('code', $dataTmp['code'])->where('project_id',$project->id)->where('delete',0);
        $this->db->from('service');

        // // validasi double
        if($this->db->count_all_results()==0){
            $this->db->insert('service', $data);
            $idTMP = $this->db->insert_id(); 
            // echo("<pre>");
            //     print_r($idTMP);
            // echo("</pre>");
            // $dataLog = $this->get_log($idTMP);
            // echo("<pre>");
            //     print_r($dataLog);
            // echo("</pre>");
            // $this->m_log->log_save('service',$idTMP,'Tambah',$dataLog);
            return 'success';
        }else 
            return 'double';
        
        
    }
	public function get_coa_by_pt($pt){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT DISTINCT coa.id, coa.description as name FROM coa_mapping
            join coa on coa.id = coa_mapping.coa_id
            where coa_mapping.project_id =  $project->id
            AND coa_mapping.pt_id = $pt
        ");
        return $query->result_array();
    }
	
	public function edit($dataTmp){
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();
    

        $data = 
        [
            'project_id'                => $project->id,
            //'retribusi'                 => $dataTmp['jenis_service']=="Retribusi"?1:0,
            // 'service_jenis_id'          => $dataTmp['jenis_retribusi'],
            // 'code'                      => $dataTmp['code'],
			// 'name'                      => $dataTmp['nama_service'],
            'service_coa_mapping_id'    => $dataTmp['coa_mapping_id_service'],
            'ppn_flag'                  => $dataTmp['ppn_flag'],
            'ppn_coa_mapping_id'        => $dataTmp['coa_mapping_id_ppn'],
            'tgl_jatuh_tempo'           => $dataTmp['parameter_tanggal_jatuh_tempo'],
            'denda_penalti_coa_mapping_id'  => $dataTmp['coa_mapping_id_service_denda'],

            'denda_flag'                => $dataTmp['denda_flag'],
            'denda_selisih_bulan'       => $dataTmp['denda_selisih_bulan'],
            'denda_tanggal_jt'          => $dataTmp['denda_tanggal_jt'],
            'denda_jenis'               => $dataTmp['denda_jenis'],
			'denda_minimum'             => $this->m_core->currency_to_number($dataTmp['denda_minimum']),
			'denda_nilai'               => $this->m_core->currency_to_number($dataTmp['denda_nilai']),
            'denda_tgl_putus_flag'      => $dataTmp['denda_tgl_putus'],

            'penalti_flag'              => $dataTmp['penalti_flag'],
            'penalti_selisih_bulan'     => $dataTmp['penalti_selisih_bulan'],
            'penalti_tanggal_jt'        => $dataTmp['penalti_tanggal_jt'],
            'penalti_jenis'             => $dataTmp['penalti_jenis'],
			'penalti_minimum'           => $this->m_core->currency_to_number($dataTmp['penalti_minimum']),
			'penalti_nilai'             => $this->m_core->currency_to_number($dataTmp['penalti_nilai']),
            'penalti_tgl_putus_flag'    => $dataTmp['penalti_tgl_putus'],
            'jarak_periode_penggunaan'  => $dataTmp['jarak_periode_penggunaan'],
            
            'description'               => $dataTmp['description'],
            'active'                    => 1,
            'delete'                    => 0
        ];

        $this->db->where('project_id', $project->id)->where('id',$dataTmp['id']);
        $this->db->from('service');
        // validasi apakah user dengan project $project boleh edit data ini
        if($this->db->count_all_results()!=0){
            $before = $this->get_log($dataTmp['id']);   
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('service', $data);
            $after = $this->get_log($dataTmp['id']);  
            $diff = (object)(array_diff_assoc((array)$after,(array)$before));
            $tmpDiff = (array)$diff;

            if($tmpDiff){
                $this->m_log->log_save('service',$dataTmp['id'],'Edit',$diff);
            }
        }
        return 'success';
    }


    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('service');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('service', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('service', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
                   
        }
    }

}