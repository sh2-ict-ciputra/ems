<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_range_lingkungan extends CI_Model {

    public function get()
		
    {
		
	    $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT * FROM range_lingkungan where project_id = $project->id and [delete] = 0 order by id desc
        ");
        return $query->result_array();
    }


    public function getSelect($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
		    SELECT * FROM range_lingkungan 
			WHERE id = $id
            AND project_id = $project->id
        ");
        $row = $query->row();
        return $row; 
    }


    public function get_range_detail_bangunan($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  *  FROM range_lingkungan_detail
            WHERE range_lingkungan_id = $id  and flag_jenis=0 and [delete]=0
            order by  id asc
        ");
		return $query->result_array();
		
    }


    public function get_range_detail_kavling($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT  *  FROM range_lingkungan_detail
            WHERE range_lingkungan_id = $id  and flag_jenis=1 and [delete]=0
            order by  id asc
        ");
		return $query->result_array();
		
    }
    

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            			
			SELECT  
                    range_lingkungan.code					 as code,
                    range_lingkungan.name					 as name,                    
                    range_lingkungan.description			 as description, 
                    case when range_lingkungan.active = 0	 then 'Tidak Aktif' else 'Aktif' end as aktif, 
                    case when range_lingkungan.[delete] = 0 then 'Tidak di Hapus' else 'Terhapus' end as [delete],
                    range_lingkungan_detail.id				as id_range_lingkungan_detail,
                    range_lingkungan_detail.range_awal		as range_awal,
					range_lingkungan_detail.range_akhir	as range_akhir,
					range_lingkungan_detail.harga_hpp		as harga_hpp,
                    range_lingkungan_detail.harga	as harga_range,
                    range_lingkungan_detail.flag_jenis	as flag_jenis
                    
                   
            FROM range_lingkungan
            LEFT JOIN range_lingkungan_detail		ON range_lingkungan.id = range_lingkungan_detail.range_lingkungan_id
            WHERE				range_lingkungan.id             = $id
            AND					range_lingkungan.project_id     = $project->id
            ORDER BY			range_lingkungan.id		  ASC
			
			
			
			
			
			
			
        ");
        $row = $query->result_array();
        $hasil = [];
        $i = 1;
        foreach ($row as $v) {
            if (!array_key_exists('code', $hasil)) {
                $hasil['code'] = $v['code'];
                $hasil['name'] = $v['name'];
                $hasil['description'] = $v['description'];
                $hasil['aktif'] = $v['aktif'];
                $hasil['delete'] = $v['delete'];
            }
            $hasil[$i.' id_range_lingkungan_detail'] = $v['id_range_lingkungan_detail'];
            $hasil[$i.' range_awal'] = $v['range_awal'];
            $hasil[$i.' range_akhir'] = $v['range_akhir'];
            $hasil[$i.' harga_hpp'] = $v['harga_hpp'];
            $hasil[$i.' harga'] = $v['harga_range'];
            $hasil[$i.' flag_jenis'] = $v['flag_jenis'];
           
            ++$i;
        }

        return $hasil;
    }
	
	


	
	public function get_log_detail($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        
        $query = $this->db->query("
             SELECT * FROM range_lingkungan_detail
        ");
        $row = $query->row();
        return $row; 
    }
	
	
	public function cek($id){
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * FROM range_lingkungan WHERE id = $id and project_id = $project->id
        ");
        $row = $query->row();
        return isset($row)?1:0;
    }
	
	
    
	public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = [
            'code'              => $dataTmp['kode'],
            'project_id'        => $project->id,
            'name'              => $dataTmp['nama'],
            'description'       => $dataTmp['keterangan'],
            'formula_bangunan'  => $dataTmp['formula_bangunan'],
            'formula_kavling'   => $dataTmp['formula_kavling'],
            'flag_bangunan'     => $dataTmp['flag_bangunan'] ? 1:0,
            'flag_kavling'      => $dataTmp['flag_kavling'] ? 1:0,
            'keamanan'          => $this->m_core->currency_to_number($dataTmp['keamanan']),
			'kebersihan'        => $this->m_core->currency_to_number($dataTmp['kebersihan']),
			'bangunan_fix'      => $this->m_core->currency_to_number($dataTmp['bangunan_fix']),
			'kavling_fix'       => $this->m_core->currency_to_number($dataTmp['kavling_fix']),
            'service_charge'    => $this->m_core->currency_to_number($dataTmp['service_charge']),
            'ppn_charge'        => $dataTmp['ppn_charge'],
            'active'            => 1,
            'delete'            => 0
        ];
		
		
		$data_range_bangunan = [
            'range_awal'       =>  $dataTmp['range_awal'], 
            'range_akhir'      =>  $dataTmp['range_akhir'],  
            'harga_hpp'        =>  $dataTmp['harga_hpp'],  
			'harga'            =>  $dataTmp['harga_range'],  
            'flag_jenis'       => 0,
            'active'           => 1,
            'delete'           => 0
        ];
		
		
		
		$data_range_kavling = [
            'range_awal'       => $dataTmp['range_awal2'],   
            'range_akhir'      => $dataTmp['range_akhir2'],  
            'harga_hpp'        => $dataTmp['harga_hpp2'],  
			'harga'            => $dataTmp['harga_range2'],  
            'flag_jenis'       => 1,
            'active'           => 1,
            'delete'           => 0
        ];
		
		
		
		//var_dump($data_range_detail);
	

        $this->db->where('code', $data['code']);
        $this->db->from('range_lingkungan');

        // validasi double
        if($this->db->count_all_results()==0){ 

                    $this->db->insert('range_lingkungan', $data);
                    $id = $this->db->insert_id();
                   	$dataLog = $this->get_log($id);
                   // $this->m_log->log_save('range_lingkungan',$id,'Tambah',$dataLog);
					
					//echo("<pre>");
					//print_r($dataTmp);
					//echo("</pre>");
					
					
					
					if (isset($dataTmp['range_awal']))
					{
					for($i= 0;$i<=count($dataTmp['range_awal'])-1;$i++) {
						
                        $range_awal[$i]  =  $this->m_core->currency_to_number($dataTmp['range_awal'][$i]);
                        $range_akhir[$i] = $this->m_core->currency_to_number($dataTmp['range_akhir'][$i]); 
                        $harga_hpp[$i]   =  $this->m_core->currency_to_number($dataTmp['harga_hpp'][$i]);  
						$harga_range[$i] =  $this->m_core->currency_to_number($dataTmp['harga_range'][$i]); 
                        
                        
                        $data_range_bangunan = [
                            'range_lingkungan_id'      => $id, 
                            'range_awal'               => $range_awal[$i],
                            'range_akhir'              => $range_akhir[$i],
                            'harga_hpp'                => $harga_hpp[$i],
							'harga'                    => $harga_range[$i],
                            'flag_jenis'               => 0,
                            'active'                   => 1,
                            'delete'                   => 0
                            
                        ];
                            
                        $this->db->insert('range_lingkungan_detail', $data_range_bangunan);
                        $dataLog = $this->get_log_detail($this->db->insert_id());
                         $this->m_log->log_save('range_lingkungan_detail',$this->db->insert_id(),'Tambah',$dataLog);

					}
					}
					
					if (isset($dataTmp['range_awal2']))
					{
					for($i= 0;$i<=count($dataTmp['range_awal2'])-1;$i++) {
						
                        $range_awal2[$i]  =  $this->m_core->currency_to_number($dataTmp['range_awal2'][$i]); 
                        $range_akhir2[$i] =  $this->m_core->currency_to_number($dataTmp['range_akhir2'][$i]);  
                        $harga_hpp2[$i]   =  $this->m_core->currency_to_number($dataTmp['harga_hpp2'][$i]);  
						$harga_range2[$i] =  $this->m_core->currency_to_number($dataTmp['harga_range2'][$i]); 
                        
                        
                        $data_range_kavling = [
                            'range_lingkungan_id'      => $id, 
                            'range_awal'               => $range_awal2[$i],
                            'range_akhir'              => $range_akhir2[$i],
                            'harga_hpp'                => $harga_hpp2[$i],
							'harga'                    => $harga_range2[$i],
                            'flag_jenis'               => 1,
                            'active'                   => 1,
                            'delete'                   => 0
                           
                        ];
                            
                        $this->db->insert('range_lingkungan_detail', $data_range_kavling);
                        $dataLog = $this->get_log_detail($this->db->insert_id());
                        $this->m_log->log_save('range_lingkungan_detail',$this->db->insert_id(),'Tambah',$dataLog);

					}
					}
					
					
					
				
					
            return 'success';
        }else return 'double';
        
        
    }
	
	
	  public function edit($dataTmp)
    {
        // echo '<pre>';
        // print_r($dataTmp);
        // echo '</pre>';

        $before = $this->get_log($dataTmp['id']);

        $this->db->where('range_lingkungan_id', $dataTmp['id']);
        $this->db->update('range_lingkungan_detail', ['delete' => 1]);

        //$this->save($dateTmp);

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->where('project_id', $project->id);
        $this->db->from('range_lingkungan');
        $data =
        [
            'code' => $dataTmp['kode_range'],
            'name' => $dataTmp['name'],
            'description' => $dataTmp['keterangan'],
            'formula_bangunan'  => $dataTmp['formula_bangunan'],
            'formula_kavling'   => $dataTmp['formula_kavling'],
            'flag_bangunan' => $dataTmp['flag_bangunan'] ? 1:0,
            'flag_kavling'  => $dataTmp['flag_kavling'] ? 1:0,
            'keamanan' =>$this->m_core->currency_to_number($dataTmp['keamanan']),
            'kebersihan' => $this->m_core->currency_to_number($dataTmp['kebersihan']),
            'bangunan_fix' => $this->m_core->currency_to_number($dataTmp['bangunan_fix']),
            'kavling_fix' => $this->m_core->currency_to_number($dataTmp['kavling_fix']),
            'service_charge' => $this->m_core->currency_to_number($dataTmp['service_charge']),
            'ppn_charge' => $dataTmp['ppn_charge'],
            'active' => $dataTmp['active'] ? 1 : 0,
            'delete' => 0
        ];


        

        
       // echo '<pre>';
       // print_r($data);
       // echo '</pre>';

       if ($this->db->count_all_results() != 0) {
        
        //proses edit
        $beforeRange = $this->get_log($dataTmp['id']);

        $this->db->where('id', $dataTmp['id']);
        $this->db->update('range_lingkungan', $data);
        $afterRange = $this->get_log($dataTmp['id']);
        $diff = (object) (array_diff_assoc((array) $afterRange, (array) $beforeRange));
        $tmpDiff = (array) $diff;
        if(true){
            $i = 0;
            $j = 0;
            $jumlahRangeBangunanBaru = 0;
            $jumlahRangeKavlingBaru = 0;

             //echo '<pre>';
             //   print_r($dataTmp);
             //   echo '</pre>';
            if (isset($dataTmp['id_range_bangunan'] )) {

            foreach ($dataTmp['id_range_bangunan'] as $v) {
                // echo '<pre>';
                // print_r($v);
                // echo '</pre>';
                // var_dump($dataTmp['id_rekening'][$i]);
                $dataRangeLingkunganDetailBangunanTmp = [];
                $dataRangeLingkunganDetailBangunanTmp =
                [
                    'range_lingkungan_id' => $dataTmp['id'],
                    'range_awal' =>  $this->m_core->currency_to_number($dataTmp['range_awal'][$i]),  
                    'range_akhir' =>  $this->m_core->currency_to_number($dataTmp['range_akhir'][$i]),  
                    'harga_hpp' =>  $this->m_core->currency_to_number($dataTmp['harga_hpp'][$i]), 
                    'harga' =>  $this->m_core->currency_to_number($dataTmp['harga_range'][$i]),     
                    'flag_jenis'=> 0,
                    'active'=> 1,                    
                    'delete' => 0

                ];
                if ($v != 0) {
                    $jumlahRangeBangunanBaru++;
                    // $dataRekeningTmp['id'] = $dataTmp['id_rekening'][$i];
                    // edit rekening
                    $this->db->where('id', $dataTmp['id_range_bangunan'][$i]);
                    $this->db->update('range_lingkungan_detail', $dataRangeLingkunganDetailBangunanTmp);
                }else{
                    // add rekening
                    $this->db->insert('range_lingkungan_detail', $dataRangeLingkunganDetailBangunanTmp);  

                }

                // echo '<pre>';
                // print_r($dataRekeningTmp);
                // echo '</pre>';
                $i++;
            }

        }

        }

        if (isset($dataTmp['id_range_kavling'] )) {


            foreach ($dataTmp['id_range_kavling'] as $m) {
                // echo '<pre>';
                // print_r($v);
                // echo '</pre>';
                
                $dataRangeLingkunganDetailKavlingTmp = [];
                $dataRangeLingkunganDetailKavlingTmp =
                [
                    'range_lingkungan_id' => $dataTmp['id'],
                    'range_awal' =>  $this->m_core->currency_to_number($dataTmp['range_awal2'][$j]), 
                    'range_akhir' =>  $this->m_core->currency_to_number($dataTmp['range_akhir2'][$j]),  
                    'harga_hpp' =>  $this->m_core->currency_to_number($dataTmp['harga_hpp2'][$j]),   
                    'harga' =>  $this->m_core->currency_to_number($dataTmp['harga_range2'][$j]),    
                    'flag_jenis'=> 1,
                    'active'=> 1,                    
                    'delete' => 0

                ];
                if ($m != 0) {
                    $jumlahRangeKavlingBaru++;
                   
                    // edit range
                    $this->db->where('id', $dataTmp['id_range_kavling'][$j]);
                    $this->db->update('range_lingkungan_detail', $dataRangeLingkunganDetailKavlingTmp);
                }else{
                    // add range
                    $this->db->insert('range_lingkungan_detail', $dataRangeLingkunganDetailKavlingTmp);  

                }

               
                $j++;
            }

           
            $after = $this->get_log($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;
            // echo '<pre>';
            //     print_r($before);
            // echo '</pre>';
            // echo '<pre>';
            //     print_r($after);
            // echo '</pre>';
            // echo '<pre>';
            //     print_r($tmpDiff);
            // echo '</pre>';
            if ($tmpDiff) {
                $this->m_log->log_save('range_lingkungan', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            }
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
        $this->db->from('range_lingkungan');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
          
                        $before = $this->get_log($dataTmp['id']);
                        $this->db->where('id', $dataTmp['id']);
                        $this->db->update('range_lingkungan', ['delete' => 1]);
                        $after = $this->get_log($dataTmp['id']);

                        $diff = (object) (array_diff((array) $after, (array) $before));
                        $tmpDiff = (array) $diff;

                        if ($tmpDiff) {
                            $this->m_log->log_save('range_lingkungan', $dataTmp['id'], 'Edit', $diff);

                            return 'success';
                        } else {
                            return 'Tidak Ada Perubahan';
                        }
                 
        }
    }
	
	

}