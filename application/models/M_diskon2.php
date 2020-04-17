<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class m_diskon extends CI_Model {

    public function get()
    {

        $project = $this->m_core->project();
	    $query = $this->db->query("
        SELECT * FROM diskon where project_id = $project->id and [delete] = 0 order by id desc
        ");
        return $query->result_array();
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

    public function get_service(){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            id,
            code,
            name
        FROM service
            WHERE  project_id = $project->id
        ");
        return $query->result_array();
    }

    public function get_gol_diskon(){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            gol_diskon.id AS gol_diskon_id,
            gol_diskon.name AS gol_diskon_name
        FROM gol_diskon
        WHERE gol_diskon.project_id = $project->id
        ");
        return $query->result_array();
    }

    public function get_product_category(){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            product_category.id AS product_category_id,
            product_category.name AS product_category_name
        FROM product_category
        WHERE product_category.project_id = $project->id
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

    public function get_coa(){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                coa_mapping.id,
                pt.name as ptName, 
                coa.description as coaName, 
                coa.code as coaCode 
            FROM coa_mapping
            join pt on pt.id = coa_mapping.pt_id
            join coa on coa.id = coa_mapping.coa_id
            where coa_mapping.project_id =  $project->id 
            and coa_mapping.[delete] = 0
			order by coaCode
        ");

        return $query->result_array();
    }
    public function get_log_diskon($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  
                    diskon.id,
                    product_category.name                       as product_category_name, 
                    diskon.description				            as description, 
                    diskon_detail.coa_mapping_id_diskon			as coa_mapping_id_diskon, 
                    case when diskon.active = 0		            then 'Tidak Aktif' else 'Aktif' end as aktif, 
                    case when diskon.[delete] = 0	            then 'Tidak di Hapus' else 'Terhapus' end as [delete],
                    diskon_detail.id				            as diskon_detail_id,
                    service.id						            as id_service,
                    case when diskon_detail.active = 0	        then 'Tidak Aktif' else 'Aktif' end as diskon_detail_aktif,
                    case when diskon_detail.[delete] = 0        then 'Tidak di Hapus' else 'Terhapus' end as diskon_detail_delete,
                    service.name					            as name_service,
                    diskon_detail.parameter_id,
                    diskon_detail.nilai,
                    case when diskon_detail.is_umum = 0 then 'Event' else 'Umum' end as is_umum
            FROM diskon
            JOIN diskon_detail	    ON diskon_detail.diskon_id  = diskon.id
            JOIN product_category   ON product_category.id      = diskon.product_category_id
            JOIN service		    ON service.id               = diskon_detail.service_id
            WHERE diskon.id                                     = $id
            AND	diskon.project_id                               = $project->id
        ");
        $row = $query->result_array();
        $hasil = [];
        $i = 1;
        foreach ($row as $v){
            if(!array_key_exists("name",$hasil)){
               
                $hasil['product_category_name']     = $v['product_category_name'];
                $hasil['description']               = $v['description'];
                $hasil['aktif']                     = $v['aktif'];
                $hasil['delete']                    = $v['delete'];   
            }
            $hasil[$i.' diskon_detail_id']          = $v['diskon_detail_id'];
            $hasil[$i.' parameter_id']              = $v['parameter_id'];
            $hasil[$i.' id_service']                = $v['id_service'];
            $hasil[$i.' name_service']              = $v['name_service'];
            $hasil[$i.' nilai']                     = $v['nilai'];
            $hasil[$i.' is_umum']           = $v['is_umum'];
            $hasil[$i.' coa_mapping_id_diskon']     = $v['coa_mapping_id_diskon'];

            $i++;
        }
        return $hasil;
    }


    

    public function save($dataTmp)
    {
        echo("<pre>");
            print_r($dataTmp);
        echo("</pre>");
        

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

        $query = $this->db->query("
            SELECT * FROM diskon
            WHERE diskon.id = $id and diskon.project_id = $project->id
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }

    public function mapping_get($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
            SELECT
                diskon.id,
                diskon.name,
                product_category.id AS product_category_id,
                product_category.name AS product_category_name,
                diskon.description,
                diskon.active
            FROM diskon
            JOIN product_category ON product_category.id = diskon.product_category_id
            WHERE diskon.id = $id
            AND diskon.project_id = $project->id
        ");
        $row = $query->row();
        return $row; 
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
    

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('diskon');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
          
                        $before = $this->get_log_diskon($dataTmp['id']);
                        $this->db->where('id', $dataTmp['id']);
                        $this->db->update('diskon', ['delete' => 1]);
                        $after = $this->get_log_diskon($dataTmp['id']);

                        $diff = (object) (array_diff((array) $after, (array) $before));
                        $tmpDiff = (array) $diff;

                        if ($tmpDiff) {
                            $this->m_log->log_save('diskon', $dataTmp['id'], 'Edit', $diff);

                            return 'success';
                        } else {
                            return 'Tidak Ada Perubahan';
                        }
                  
        }
    }
	





}
?>