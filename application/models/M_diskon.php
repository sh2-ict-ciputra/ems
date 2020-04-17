<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_diskon extends CI_Model {

    public function get_by_id($id){
        $project = $this->m_core->project();
        return $this->db->select("diskon.*,
                                CONCAT(RIGHT('0' + RTRIM(DAY(diskon.periode_diskon_awal)), 2),'-',RIGHT('0' + RTRIM(MONTH(diskon.periode_diskon_awal)), 2),'-',YEAR(diskon.periode_diskon_awal)) as periode_diskon_awal,
                                CONCAT(RIGHT('0' + RTRIM(DAY(diskon.periode_diskon_akhir)), 2),'-',RIGHT('0' + RTRIM(MONTH(diskon.periode_diskon_akhir)), 2),'-',YEAR(diskon.periode_diskon_akhir)) as periode_diskon_akhir,
                                CONCAT(RIGHT('0' + RTRIM(DAY(diskon.periode_berlaku_awal)), 2),'-',RIGHT('0' + RTRIM(MONTH(diskon.periode_berlaku_awal)), 2),'-',YEAR(diskon.periode_berlaku_awal)) as periode_berlaku_awal,
                                CONCAT(RIGHT('0' + RTRIM(DAY(diskon.periode_berlaku_akhir)), 2),'-',RIGHT('0' + RTRIM(MONTH(diskon.periode_berlaku_akhir)), 2),'-',YEAR(diskon.periode_berlaku_akhir)) as periode_berlaku_akhir
                                ")
                            ->from("diskon")
                            ->where("diskon.id",$id)
                            ->where("diskon.delete",0)
                            ->where("diskon.project_id",$project->id)
                            ->get()->row();
    }
    public function get_view(){
        $project = $this->m_core->project();
        return $this->db->select("
                                    diskon.id,

                                    case
                                        WHEN diskon.gol_diskon_id = 0 THEN 'ALL'
                                        ELSE gol_diskon.name 
                                    END as gol_diskon,
                                    case
                                        WHEN diskon.purpose_use_id = 0 THEN 'ALL'
                                        ELSE purpose_use.name 
                                    END as purpose_use,
                                    case
                                        WHEN diskon.service_id = 0 THEN 'ALL'
                                        ELSE service.name 
                                    END as service,
                                    case
                                        WHEN diskon.paket_service_id = 0 THEN 'ALL'
                                        ELSE paket_service.name 
                                    END as paket_service,
                                    diskon.nilai,
                                    CASE
                                        WHEN diskon.flag_berlaku = 1 THEN 'Pendaftaran'
                                        WHEN diskon.flag_berlaku = 2 THEN 'Tagihan'
                                    END as flag_berlaku,
                                    CASE
                                        WHEN diskon.parameter = 1 THEN 'Bulan'
                                        WHEN diskon.parameter = 2 THEN 'Rupiah'
                                        WHEN diskon.parameter = 3 THEN 'Persen'
                                    END as parameter,
                                    diskon.description,
                                    diskon.minimal_bulan
                                ")
                        ->from("diskon")
                        ->join("gol_diskon",
                                "gol_diskon.id = diskon.gol_diskon_id",
                                "LEFT")
                        ->join("purpose_use",
                                "purpose_use.id = diskon.purpose_use_id",
                                "LEFT")
                        ->join("service",
                                "service.id = diskon.service_id",
                                "LEFT")
                        ->join("paket_service",
                                "paket_service.id = diskon.paket_service_id",
                                "LEFT")
                        ->where("diskon.project_id",$project->id)
                        ->where("diskon.delete",0)
                        ->get()->result();
    }
    public function diskon_getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                diskon.id as id, 
                diskon.name as name, 
                diskon.active as active, 
                product_category.id as product_category_id, 
                product_category.name as product_category_name, 
                diskon.description 
            FROM diskon
            join product_category on product_category.id = diskon.product_category_id
            where diskon.project_id =  $project->id and diskon.[delete] = 0 order by diskon.id desc
        ");
        return $query->result_array();
    }

    public function get_paket_service($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                id,
                code,
                name
            FROM paket_service
            WHERE project_id = $project->id
            AND active = 1
            AND [delete] = 0
            AND service_id = $id
            ORDER BY code
        ");
        return $query->result();
    }

    public function get_log($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        return $this->db->select("
                                diskon.id,

                                case
                                    WHEN diskon.gol_diskon_id = 0 THEN 'ALL'
                                    ELSE gol_diskon.name 
                                END as gol_diskon,
                                case
                                    WHEN diskon.purpose_use_id = 0 THEN 'ALL'
                                    ELSE purpose_use.name 
                                END as purpose_use,
                                case
                                    WHEN diskon.service_id = 0 THEN 'ALL'
                                    ELSE service.name 
                                END as service,
                                case
                                    WHEN diskon.paket_service_id = 0 THEN 'ALL'
                                    ELSE paket_service.name 
                                END as paket_service,
                                diskon.nilai,
                                CASE
                                    WHEN diskon.flag_berlaku = 1 THEN 'Pendaftaran'
                                    WHEN diskon.flag_berlaku = 2 THEN 'Tagihan'
                                END as flag_berlaku,
                                CASE
                                    WHEN diskon.parameter = 1 THEN 'Bulan'
                                    WHEN diskon.parameter = 2 THEN 'Rupiah'
                                    WHEN diskon.parameter = 3 THEN 'Persen'
                                END as parameter,
                                diskon.description,
                                diskon.minimal_bulan
                            ")
                        ->from("diskon")
                        ->join("gol_diskon",
                            "gol_diskon.id = diskon.gol_diskon_id",
                            "LEFT")
                        ->join("purpose_use",
                            "purpose_use.id = diskon.purpose_use_id",
                            "LEFT")
                        ->join("service",
                            "service.id = diskon.service_id",
                            "LEFT")
                        ->join("paket_service",
                            "paket_service.id = diskon.paket_service_id",
                            "LEFT")
                        ->where("diskon.project_id",$project->id)
                        ->where("diskon.id",$id)
                        ->get()->row();
    }


    
    public function ajax_save($dataTmp){
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $return = (object)[];
        $dataTmp = (object)$dataTmp;
        $return->status = 1;
        $return->message = "Data berhasil di tambah";

        $data                   = (object)[];
        $data->gol_diskon_id	= isset($dataTmp->gol_diskon)?$dataTmp->gol_diskon:0;
        $data->purpose_use_id	= isset($dataTmp->purpose_use)?$dataTmp->purpose_use:0;
        $data->service_id		= isset($dataTmp->service)?$dataTmp->service:0;
        

        $data->paket_service_id	= isset($dataTmp->paket_service)?$dataTmp->paket_service:0;
        $data->flag_berlaku	    = $dataTmp->flag_berlaku;
        // $data->periode			= $dataTmp->periode;
        $data->periode_diskon_awal      = isset($dataTmp->periode_awal)?substr($dataTmp->periode_awal,3,4)."-".substr($dataTmp->periode_awal,0,2)."-01":null;
        $data->periode_diskon_akhir	    = isset($dataTmp->periode_akhir)?substr($dataTmp->periode_akhir,3,4)."-".substr($dataTmp->periode_akhir,0,2)."-01":null;
        $data->periode_berlaku_awal	    = isset($dataTmp->masa_awal)?substr($dataTmp->masa_awal,6,4)."-".substr($dataTmp->masa_awal,3,2)."-".substr($dataTmp->masa_awal,0,2):null;
        $data->periode_berlaku_akhir    = isset($dataTmp->masa_akhir)?substr($dataTmp->masa_akhir,6,4)."-".substr($dataTmp->masa_akhir,3,2)."-".substr($dataTmp->masa_akhir,0,2):null;
        $data->minimal_bulan            = isset($dataTmp->minimal_bulan)?$dataTmp->minimal_bulan:0;
        
        $data->parameter		        = $dataTmp->parameter;
        $data->nilai			        = $dataTmp->nilai;

        $data->active                   = 1;
        $data->delete                   = 0;
        $data->project_id               = $project->id;
        $data->description              = isset($dataTmp->description)?$dataTmp->description:"";
        if(isset($data->service_id)){
            if($data->service_id==0){
                $data->service_jenis_id = 0;
            }else{   
                $data->service_jenis_id = $this->db->select("service_jenis_id")
                                                    ->from("service")
                                                    ->where("id",$data->service_id)
                                                    ->get()->row()->service_jenis_id;
            }
        }

        $this->db->where('gol_diskon_id', $data->gol_diskon_id);
        $this->db->where('purpose_use_id', $data->purpose_use_id);
        $this->db->where('service_id', $data->service_id);
        $this->db->where('paket_service_id', $data->paket_service_id);
        $this->db->where('flag_berlaku', $data->flag_berlaku);
        $this->db->where('periode_diskon_awal', $data->periode_diskon_awal);
        $this->db->where('periode_diskon_akhir', $data->periode_diskon_akhir);
        $this->db->where('periode_berlaku_awal', $data->periode_berlaku_awal);
        $this->db->where('periode_berlaku_akhir', $data->periode_berlaku_akhir);
        $this->db->where('minimal_bulan', $data->minimal_bulan);
        $this->db->where('project_id', $project->id);
        $this->db->from('diskon');
        // validasi double

        if($this->db->count_all_results()==0){         
            $this->db->insert('diskon', $data);
            $dataLog = $this->get_log($this->db->insert_id());
            $this->m_log->log_save('diskon',$this->db->insert_id(),'Tambah',$dataLog);
        }else{
            $return->status = 0;
            $return->message = "Data sudah ada";
        }
        return $return;
        
    }
    public function ajax_edit($dataTmp){
        $this->load->model('m_log');
        $before = $this->get_log($dataTmp['id']);
        $id = $dataTmp['id'];
        $project = $this->m_core->project();

        $return = (object)[];
        $dataTmp = (object)$dataTmp;
        $return->status = 1;
        $return->message = "Data berhasil di Ubah";

        $data                   = (object)[];
        $data->gol_diskon_id	= isset($dataTmp->gol_diskon)?$dataTmp->gol_diskon:0;
        $data->purpose_use_id	= isset($dataTmp->purpose_use)?$dataTmp->purpose_use:0;
        $data->service_id		= isset($dataTmp->service)?$dataTmp->service:0;
        

        $data->paket_service_id	= isset($dataTmp->paket_service)?$dataTmp->paket_service:0;
        $data->flag_berlaku	    = $dataTmp->flag_berlaku;
        // $data->periode			= $dataTmp->periode;
        $data->periode_diskon_awal      = isset($dataTmp->periode_awal)?substr($dataTmp->periode_awal,3,4)."-".substr($dataTmp->periode_awal,0,2)."-01":null;
        $data->periode_diskon_akhir	    = isset($dataTmp->periode_akhir)?substr($dataTmp->periode_akhir,3,4)."-".substr($dataTmp->periode_akhir,0,2)."-01":null;
        $data->periode_berlaku_awal	    = isset($dataTmp->masa_awal)?substr($dataTmp->masa_awal,6,4)."-".substr($dataTmp->masa_awal,3,2)."-".substr($dataTmp->masa_awal,0,2):null;
        $data->periode_berlaku_akhir    = isset($dataTmp->masa_akhir)?substr($dataTmp->masa_akhir,6,4)."-".substr($dataTmp->masa_akhir,3,2)."-".substr($dataTmp->masa_akhir,0,2):null;
        $data->minimal_bulan            = isset($dataTmp->minimal_bulan)?$dataTmp->minimal_bulan:0;
        
        $data->parameter		        = $dataTmp->parameter;
        $data->nilai			        = $dataTmp->nilai;

        $data->active                   = 1;
        $data->delete                   = 0;
        $data->project_id               = $project->id;
        $data->description              = isset($dataTmp->description)?$dataTmp->description:"";
        if(isset($data->service_id)){
            if($data->service_id==0){
                $data->service_jenis_id = 0;
            }else{   
                $data->service_jenis_id = $this->db->select("service_jenis_id")
                                                    ->from("service")
                                                    ->where("id",$data->service_id)
                                                    ->get()->row()->service_jenis_id;
            }
        }

        $this->db->where('gol_diskon_id', $data->gol_diskon_id);
        $this->db->where('purpose_use_id', $data->purpose_use_id);
        $this->db->where('service_id', $data->service_id);
        $this->db->where('paket_service_id', $data->paket_service_id);
        $this->db->where('flag_berlaku', $data->flag_berlaku);
        $this->db->where('periode_diskon_awal', $data->periode_diskon_awal);
        $this->db->where('periode_diskon_akhir', $data->periode_diskon_akhir);
        $this->db->where('periode_berlaku_awal', $data->periode_berlaku_awal);
        $this->db->where('periode_berlaku_akhir', $data->periode_berlaku_akhir);
        $this->db->where('minimal_bulan', $data->minimal_bulan);
        $this->db->where('project_id', $project->id);
        $result = $this->db->from('diskon')->get()->row();
        // validasi double
        // var_dump($result);
        if(isset($result)){
            if($result->id == $id){
                $this->db->where("id",$id);
                $this->db->update('diskon', $data);
                $dataLog = $this->get_log($this->db->insert_id());
                $this->m_log->log_save('diskon',$this->db->insert_id(),'Tambah',$dataLog);    
            }
        }else{
            $return->status = 0;
            $return->message = "Data sudah ada";
        }
        return $return;
        
    }
    public function save($dataTmp)
    {        
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = [
            'name'                  => $dataTmp['name'],
            'project_id'            => $project->id,
            'product_category_id'   => $dataTmp['product_category_id'],
			'description'           => $dataTmp['description'],
            'active'                => 1,
            'delete'                => 0,
        ];
		
		
		$data_diskon_detail = [
            'service_id'            => $dataTmp['service_id'],
            'coa_mapping_id_diskon' => $dataTmp['coa_mapping_id_diskon'],
            'is_umum'       => $dataTmp['is_umum'],
            'parameter_id'          => $dataTmp['parameter_id'],
            'nilai'                 => $dataTmp['nilai'],
            'min_bulan'             => $dataTmp['min_bulan'],
            'active'                => 1,
            'delete'                => 0,
        ];

        $this->db->where('name', $data['name']);
        $this->db->where('name', $data['name'])->where('product_category_id',$data['product_category_id']);
        $this->db->from('diskon');

        // validasi double
        if($this->db->count_all_results()==0){ 

            $this->db->insert('diskon', $data);
            $id = $this->db->insert_id();
			for($i= 0;$i<count($dataTmp['service_id']);$i++) {
                $service_id[$i]             = $dataTmp['service_id'][$i];
                $coa_mapping_id_diskon[$i]  = $dataTmp['coa_mapping_id_diskon'][$i];
                $parameter_id[$i]           = $dataTmp['parameter_id'][$i];
                $nilai[$i]                  = $dataTmp['nilai'][$i];
                $min_bulan[$i]              = $dataTmp['min_bulan'][$i];
                        
                $data_diskon_detail2        = [
                    'diskon_id'             => $id, 
                    'service_id'            => $service_id[$i],
                    'is_umum'       => 0,
                    'coa_mapping_id_diskon' => $coa_mapping_id_diskon[$i],
                    'parameter_id'          => $parameter_id[$i],
                    'nilai'                 => $nilai[$i],
                    'min_bulan'             => $parameter_id[$i]==1?$min_bulan[$i]:0,
                    'active'                => 1,
                    'delete'                => 0,
                ];
                            
                $this->db->insert('diskon_detail', $data_diskon_detail2);
                $dataLog = $this->get_log_diskon($this->db->insert_id());
                $this->m_log->log_save('diskon_detail',$this->db->insert_id(),'Tambah',$dataLog);

			}
            $dataLog = $this->get_log_diskon($id);
            $this->m_log->log_save('diskon',$id,'Tambah',$dataLog);
							
            return 'success';
        }else return 'double';

    }

    public function cek($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $this->db->where('id', $id);
        $this->db->where('project_id', $project->id);
        $this->db->where("delete",0);

        $this->db->from('diskon');
        // validasi double

        return $this->db->count_all_results(); 
    }

    public function get_diskon_detail($diskon_id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            diskon_detail.id AS diskon_detail_id,
            diskon_detail.diskon_id AS diskon_id,
            diskon.name as name,
            diskon_detail.[delete] as [delete],
            service.id AS service_id,
            service.name AS service_name,
            diskon_detail.is_umum,
            diskon_detail.coa_mapping_id_diskon,
            diskon_detail.parameter_id AS parameter_id,
            diskon_detail.nilai AS nilai,	
            diskon_detail.min_bulan AS min_bulan	
            

        
        FROM diskon_detail
        JOIN service ON service.id = diskon_detail.service_id
        JOIN diskon ON diskon.id = diskon_detail.diskon_id
        WHERE diskon_detail.diskon_id = $diskon_id order by diskon_detail.id
        ");
        return $query->result_array();
    }

    public function edit($dataTmp){

        $before = $this->get_log_diskon($dataTmp['id']);

        //echo '<pre>' ;
        //print_r($dataTmp);
        //echo '</pre>';

        
        //$this->db->where('project_id', $project->id);
        $this->db->where('diskon_id', $dataTmp['id']);
        $this->db->update('diskon_detail', ['delete' => 1]);

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $data = 
        [
            'name'                  => $dataTmp['name'],
            'project_id'            => $project->id,
            'product_category_id'   => $dataTmp['product_category_id'],
            'description'           => $dataTmp['description'],
            'active'                => $dataTmp['active'] ? 1 : 0,
            'delete'                => 0, 
        ];

        $beforeDiskon = $this->get_log_diskon($dataTmp['id']);

        $this->db->where('id', $dataTmp['id']);
        $this->db->update('diskon', $data);
        $afterDiskon = $this->get_log_diskon($dataTmp['id']);
        $diff = (object) (array_diff_assoc((array) $afterDiskon, (array) $beforeDiskon));
        $tmpDiff = (array) $diff;
        if(true){
            $i = 0;
            $jumlahDetailBaru = 0;
            foreach ($dataTmp['diskon_detail_id'] as $v) {
                $dataDiskonDetailTmp = [];
                $dataDiskonDetailTmp =
                [
                    'diskon_id'             => $dataTmp['id'],
                    'service_id'            => $dataTmp['service_id'][$i],
                    'is_umum'       => $dataTmp['is_umum'][$i],
                    'coa_mapping_id_diskon' => $dataTmp['coa_mapping_id_diskon'][$i],
                    'parameter_id'          => $dataTmp['parameter_id'][$i],
                    'nilai'                 => $dataTmp['nilai'][$i],
                    'min_bulan'             => $dataTmp['parameter_id'][$i]==1?$dataTmp['min_bulan'][$i]:0,
                    'active'                => 1,
                    'delete'                => 0,
                ];
                if($v != 0){
                    $jumlahDetailBaru++;
                    $this->db->where('id', $dataTmp['diskon_detail_id'][$i]);
                    $this->db->update('diskon_detail', $dataDiskonDetailTmp);
                }else{
                    $this->db->insert('diskon_detail', $dataDiskonDetailTmp);
                }

                $i++;
            }

            $after = $this->get_log_diskon($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('diskon', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }






        }
        
    }
    public function delete($id){
        $project = $this->m_core->project();
        $this->db->where("id",$id);
        $this->db->where("project_id",$project->id);
        $this->db->set("delete",1);
        $this->db->update("diskon");
        if($this->db->affected_rows())
            return true;
        else
            return false;
    }
	

}
?>