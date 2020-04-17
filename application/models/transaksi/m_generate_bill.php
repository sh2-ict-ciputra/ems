<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_generate_bill extends CI_Model {

    public function get()	
    {
	    $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                t_one_bill.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit,
                customer.name as customer,
                t_one_bill.periode
            FROM t_one_bill
            JOIN unit
                ON unit.id = t_one_bill.unit_id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
                AND kawasan.project_id = 1
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
                SELECT
                t_one_bill.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit,
                customer.name as customer,
                t_one_bill.periode
            FROM t_one_bill
            JOIN unit
                ON unit.id = t_one_bill.unit_id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
                AND kawasan.project_id = 1
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            SELECT
                t_one_bill.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit,
                customer.name as customer,
                t_one_bill.periode
            FROM t_one_bill
            JOIN unit
                ON unit.id = t_one_bill.unit_id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
                AND kawasan.project_id = $project->id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            WHERE t_one_bill.[delete] = 0
            ORDER BY t_one_bill.id DESC
        ");
        return $query->result();
    }
	public function getKawasan(){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                id,
                code,
                name
            FROM kawasan  
            WHERE project_id = $project->id
            ORDER BY id ASC
        ");
        return $query->result();
    }
    public function ajax_get_blok($id){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                id,
                code,
                name 
            FROM blok".
            ($id!='all'?" WHERE kawasan_id = $id":"").
            " ORDER BY id ASC"
        )->result();
        return $query;
    }
    public function ajax_get_unit($blok_id,$kawasan_id){
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                unit.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit as unit,
                customer.name as customer,
                customer.email as email,
                customer.mobilephone1 as phone
            from unit
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            WHERE kawasan.project_id = $project->id
             ". ($blok_id!='all'?" AND blok.id = $blok_id":"")."
             ". ($kawasan_id!='all'?" AND kawasan.id = $kawasan_id":"")."
            AND unit.status_tagihan = 1
            ORDER BY customer.id, kawasan.name ASC
        ")->result();
        return $query;
    }
    public function getSelect($id){
        $project = $this->m_core->project();
        $meter_akhirTmp = $this->db->query("
            SELECT 
                meter as meter_akhir
            FROM t_meter_air
            WHERE id <= $id
            AND unit_id in (SELECT unit_id FROM t_meter_air WHERE id = $id)
            ORDER BY id DESC
        ")->result_array();
        $meter_akhir = $meter_akhirTmp[0];
        echo("<pre>");
            print_r($meter_akhir);
        echo("</pre>");
        if(count($meter_akhirTmp) == 1){
            $meter_awal = $this->db->query("
                SELECT 
                    angka_meter_sekarang as meter_awal
                FROM unit_air
                JOIN t_meter_air
                    ON t_meter_air.unit_id = unit_air.unit_id
                WHERE t_meter_air.id = $id
            ")->row();
        }else{
            $meter_awal = $meter_akhirTmp[1];
        }
        echo("<pre>");
            print_r($meter_awal);
        echo("</pre>");
        // $this->db->query("");
        // $query = $this->db->query("
        //     SELECT
        //         customer.name as customer,
        //         unit_air.barcode_meter as barcode,
        //         case
        //             WHEN t_meter_air.unit_id = unit.id THEN t_meter_air.meter
        //             ELSE unit_air.angka_meter_sekarang
        //         END as meter_akhir
        //     FROM unit
        //     JOIN unit_air
        //         ON unit_air.unit_id = unit.id
        //     JOIN customer
        //         ON customer.id = unit.pemilik_customer_id
        //     LEFT JOIN t_meter_air
        //         ON t_meter_air.unit_id = unit.id
            
        //     WHERE t_meter_air.id = $id
        // ");
        // return $query->row();
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
                CONCAT(DATENAME(MM,t_meter_air.periode),' - ',DATEPART(yy,t_meter_air.periode)) as [Periode],
                REPLACE(CONVERT(varchar, CAST(t_meter_air.meter AS money), 1),'.00','') as [Luas Tanah]	
            FROM t_meter_air
            JOIN unit
                ON unit.id = t_meter_air.unit_id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            WHERE t_meter_air.id = $id
            AND kawasan.project_id = $project->id
        ");
        $row = $query->row();
        return $row;
    }
    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                id
            FROM customer 
            WHERE id = $id 
            and project_id = $project->id
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }
	public function last_id(){
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_one_bill 
            ORDER by id desc
        ");
        return $query->row()?$query->row()->id:0;
    }
    public function code(){
        $project = $this->m_core->project();
        return "$project->code/ONEBILL/".date("Y")."/".$this->m_core->numberToRomanRepresentation($project->id)."/".($this->last_id()+1);
    }
	public function save($dataTmp)
    {
        echo('<pre>');
            print_r($dataTmp);
        echo('</pre>');
        $project = $this->m_core->project();
        $this->load->model('m_sub_golongan');
		$this->load->model('transaksi/m_meter_air');
		$this->load->model('transaksi/m_meter_listrik');

        $diskon = $this->db->query("
            SELECT
                value
            FROM parameter_project
            WHERE name = 'diskon'
            AND project_id = $project->id
        ")->row()->value;
        $diskonKedudukan = $this->db->query("
            SELECT
                value
            FROM parameter_project
            WHERE name like 'diskon %'
            AND project_id = $project->id
        ")->row()->value;
        echo('<pre>');
            print_r($diskon);
        echo('</pre>');
        echo('<pre>');
            print_r($diskonKedudukan);
        echo('</pre>');
        
        $periode_awal = strtotime($dataTmp['periode']); 
        $periode_akhir = strtotime($dataTmp['sampai_periode']); 
        $selisih_tahun = abs(date('Y',$periode_akhir) - date('Y',$periode_awal)); 
        $selisih_bulan = abs(((int)date('m',$periode_akhir)+$selisih_tahun*12)-(int)date('m',$periode_awal));
        $periode = date("Y-m-d",strtotime($dataTmp['periode']));
        echo('<pre>');
            print_r($selisih_bulan);
        echo('</pre>');
        for ($i=0; $i <= $selisih_bulan; $i++) {
            if($dataTmp['unit_id']){
                foreach ($dataTmp['unit_id'] as $v) {
                    $adaTransaksi = 0;
                    $dataAir = 0;
                    $dataListrik = 0;
                    $dataOneBill =[
                        'unit_id' => $v,
                        'no_tagihan' => $this->code(),
                        'periode' => $periode
                    ];
                    
                    if(in_array("air",$dataTmp['service'])){
                        $querySTR = "
                            SELECT 
                                sub_golongan.id as sub_gol_id,
                                pemeliharaan_meter_air.id as meter_id,
                                t_meter_air.id as t_meter_id,
                                sub_golongan.range_id as biaya, -- berisi range untuk biaya
                                sub_golongan.administrasi,
                                ISNULL(service.ppn * ppn.value,0) as ppn_biaya, -- berisi nilai ppn bukan hasil ppn
                                pemeliharaan_meter_air.harga as pemeliharaan_meter,
                                ISNULL(pemeliharaan_meter_air.ppn * pemeliharaan_meter_air.harga * ppn.value /100,0) as ppn_pemeliharaan_meter,
                                sub_golongan.administrasi + pemeliharaan_meter_air.harga + ISNULL(pemeliharaan_meter_air.ppn * pemeliharaan_meter_air.harga * ppn.value /100,0)		as sub_total,
                                0 as t_deposit_id,
                                0 as deposit_rp,
                                ISNULL(pemeliharaan_meter_air.ppn * pemeliharaan_meter_air.harga * ppn.value /100,0) as ppn_pemeliharaan_meter,
                                0 as total_tagihan
                            ";
                        if($diskonKedudukan == 1 OR $diskon == 2)
                            $querySTR .= ",
                                        diskon_umum.id as diskon_umum_id,
                                        CASE 
                                        WHEN diskon_umum.id IS NOT NULL THEN CONCAT(diskon_umum.parameter_id,',',diskon_umum.nilai) 
                                        END as diskon_umum
                                        ";
                        if($diskonKedudukan == 2 OR $diskon == 2)
                            $querySTR .= ",
                                        diskon_event.id as diskon_event_id,
                                        CASE 
                                        WHEN diskon_event.id IS NOT NULL THEN CONCAT(diskon_event.parameter_id,',',diskon_event.nilai) 
                                        END as diskon_event
                                        ";
                        $querySTR .= "
                                    FROM unit
                                    JOIN  unit_air
                                        ON  unit_air.unit_id = unit.id
                                        AND unit_air.aktif = 1
                                    JOIN  t_meter_air
                                        ON  t_meter_air.unit_id = unit.id
                                        AND t_meter_air.periode = '$periode'
                                    JOIN  sub_golongan
                                        ON  sub_golongan.id = unit_air.sub_gol_id
                                    JOIN  pemeliharaan_meter_air
                                        ON  pemeliharaan_meter_air.id = unit_air.meter_id
                                    JOIN  parameter_project as ppn
                                        ON  ppn.name = 'ppn'
                                        AND ppn.project_id = $project->id
                                    JOIN  service
                                        ON  service.retribusi = 1
                                        AND service.jenis = 1
                                        AND service.project_id = $project->id
                                    JOIN customer
                                        ON customer.id = unit.pemilik_customer_id
                                    ";
                        if($diskonKedudukan == 1 OR $diskon == 2)
                            $querySTR .= "
                                        Left JOIN  diskon_detail as diskon_umum
                                            ON  diskon_umum.diskon_id = customer.diskon_id
                                            AND diskon_umum.flag_umum_event = 1
                                            AND diskon_umum.service_id = service.id
                                    ";
                        if($diskonKedudukan == 2 OR $diskon == 2)
                            $querySTR .= "
                                        Left JOIN  diskon_detail as diskon_event
                                            ON  diskon_event.diskon_id = customer.diskon_id
                                            AND diskon_event.flag_umum_event = 2
                                            AND diskon_event.service_id = service.id            
                                        ";
                        $querySTR .= "
                                    WHERE unit.id = $v
                                    ";
                        echo('<pre>');
                            print_r($querySTR);
                        echo('</pre>');        

                        $dataAir = $this->db->query($querySTR)->row();
                        echo('<pre>');
                            print_r($periode);
                        echo('</pre>');
                        echo('<pre>');
                            print_r($dataAir);
                        echo('</pre>');
                        if($dataAir){
                            $adaTransaksi = 1;
                            $range_id = $dataAir->biaya;
                            var_dump("yahooo");
                            var_dump($range_id);
                            $pemakaian = $this->m_meter_air->getSelect($dataAir->t_meter_id)->pemakaian;
                            $biaya = $this->m_sub_golongan->get_minimum(1,$range_id,$pemakaian);
                            $dataAir->biaya = $biaya;
                            $dataAir->ppn_biaya = $dataAir->ppn_biaya*$biaya/100;
                            $dataAir->sub_total = $dataAir->sub_total+$dataAir->biaya+$dataAir->ppn_biaya;
                            $dataAir->total_tagihan = $dataAir->sub_total;//kurang deposit
                        
                            if(isset($dataAir->diskon_umum)){
                                if(explode(',',$dataAir->diskon_umum)[0] == 1){
                                    
                                }
                                elseif(explode(',',$dataAir->diskon_umum)[0] == 2){
                                    $dataAir->diskon_umum = explode(',',$dataAir->diskon_umum)[1];
                                    $dataAir->total_tagihan = $dataAir->total_tagihan - $dataAir->diskon_umum;
                                }
                                elseif(explode(',',$dataAir->diskon_umum)[0] == 3){
                                    
                                }
                            }
                            if(isset($dataAir->diskon_event)){
                                if(explode(',',$dataAir->diskon_event)[0] == 1){
                                    
                                }
                                elseif(explode(',',$dataAir->diskon_event)[0] == 2){
                                    $dataAir->diskon_event = explode(',',$dataAir->diskon_event)[1];
                                    $dataAir->total_tagihan = $dataAir->total_tagihan - $dataAir->diskon_event;
                                }
                                elseif  (explode(',',$dataAir->diskon_event)[0] == 3){
                                    
                                }
                            }
                                
                        }
                        echo('<pre>');
                            print_r($dataAir);
                        echo('</pre>');
                    }
                    if(in_array("listrik",$dataTmp['service'])){
                        $dataListrik = $this->db->query("
                            SELECT 
                                sub_golongan.id as sub_gol_id,
                                pemeliharaan_meter_listrik.id as meter_id,
                                t_meter_listrik.id as t_meter_id,
                                sub_golongan.range_id as biaya, -- berisi range untuk biaya
                                sub_golongan.administrasi,
                                ISNULL(service.ppn * ppn.value,0) as ppn_biaya, -- berisi nilai ppn bukan hasil ppn
                                pemeliharaan_meter_listrik.harga as pemeliharaan_meter,
                                ISNULL(pemeliharaan_meter_listrik.ppn * pemeliharaan_meter_listrik.harga * ppn.value /100,0) as ppn_pemeliharaan_meter,
                                sub_golongan.administrasi + pemeliharaan_meter_listrik.harga + ISNULL(pemeliharaan_meter_listrik.ppn * pemeliharaan_meter_listrik.harga * ppn.value /100,0)		as sub_total,
                                0 as t_deposit_id,
                                0 as deposit_rp,
                                ISNULL(pemeliharaan_meter_listrik.ppn * pemeliharaan_meter_listrik.harga * ppn.value /100,0) as ppn_pemeliharaan_meter,
                                0 as total_tagihan,
                                diskon_umum.id as diskon_umum_id,
                                CONCAT(diskon_umum.parameter_id,',',diskon_umum.nilai) as diskon_umum,
                                diskon_event.id as diskon_event_id,
                                CONCAT(diskon_event.parameter_id,',',diskon_event.nilai) as diskon_event
                            FROM unit
                            JOIN  unit_listrik
                                ON  unit_listrik.unit_id = unit.id
                                AND unit_listrik.aktif = 1
                            JOIN  t_meter_listrik
                                ON  t_meter_listrik.unit_id = unit.id
                                AND t_meter_listrik.periode = '$periode'
                            JOIN  sub_golongan
                                ON  sub_golongan.id = unit_listrik.sub_gol_id
                            JOIN  pemeliharaan_meter_listrik
                                ON  pemeliharaan_meter_listrik.id = unit_listrik.meter_id
                            JOIN  parameter_project as ppn
                                ON  ppn.name = 'ppn'
                                AND ppn.project_id = $project->id
                            JOIN  service
                                ON  service.retribusi = 1
                                AND service.jenis = 3
                            JOIN customer
                                ON customer.id = unit.pemilik_customer_id
                            Left JOIN  diskon_detail as diskon_umum
                                ON  diskon_umum.diskon_id = customer.diskon_id
                                AND diskon_umum.flag_umum_event = 1
                                AND diskon_umum.service_id = service.id
                            Left JOIN  diskon_detail as diskon_event
                                ON  diskon_event.diskon_id = customer.diskon_id
                                AND diskon_event.flag_umum_event = 2
                                AND diskon_event.service_id = service.id
                            WHERE unit.id = $v
                        ")->row();
                        echo('<pre>');
                            print_r($periode);
                        echo('</pre>');
                        echo('<pre>');
                            print_r($dataListrik);
                        echo('</pre>');
                        if($dataListrik){
                            $adaTransaksi = 1;
                            $range_id = $dataListrik->biaya;
                            var_dump($range_id);
                            $pemakaian = $this->m_meter_listrik->getSelect($dataListrik->t_meter_id)->pemakaian;
                            $biaya = $this->m_sub_golongan->get_minimum(1,$range_id,$pemakaian);
                            $dataListrik->biaya = $biaya;
                            $dataListrik->ppn_biaya = $dataListrik->ppn_biaya*$biaya/100;
                            $dataListrik->sub_total = $dataListrik->sub_total+$dataListrik->biaya+$dataListrik->ppn_biaya;
                            $dataListrik->total_tagihan = $dataListrik->sub_total;//kurang deposit

                            if($dataListrik->diskon_umum){
                                if(explode(',',$dataListrik->diskon_umum)[0] == 1){
                                    
                                }
                                elseif(explode(',',$dataListrik->diskon_umum)[0] == 2){
                                    $dataListrik->diskon_umum = explode(',',$dataListrik->diskon_umum)[1];
                                    $dataListrik->total_tagihan = $dataListrik->total_tagihan - $dataListrik->diskon_umum;
                                }
                                elseif(explode(',',$dataListrik->diskon_umum)[0] == 3){
                                    
                                }
                            }
                            if($dataListrik->diskon_event){
                                if(explode(',',$dataListrik->diskon_event)[0] == 1){
                                    
                                }
                                elseif(explode(',',$dataListrik->diskon_event)[0] == 2){
                                    $dataListrik->diskon_event = explode(',',$dataListrik->diskon_event)[1];
                                    $dataListrik->total_tagihan = $dataListrik->total_tagihan - $dataListrik->diskon_event;
                                }
                                elseif(explode(',',$dataListrik->diskon_event)[0] == 3){
                                    
                                }
                            }
                            echo('<pre>');
                                print_r($dataListrik);
                            echo('</pre>');
                        }

                    }
                    if(in_array("pl",$dataTmp['service'])){
                        
                    }
                    var_dump($adaTransaksi);
                    if($adaTransaksi == 1){
                        echo('<pre>');
                            print_r($dataOneBill);
                        echo('</pre>');
                        
                        $this->db->insert('t_one_bill', (array)$dataOneBill);
                        $idOneBill = $this->db->insert_id();
                        echo('<pre>');
                            print_r($dataAir);
                        echo('</pre>');
                        if($dataAir){
                            $dataAir->t_one_bill_id = $idOneBill;
                            $dataDiskonAirUmum = [
                                'diskon_type'   => 1,
                                // 'diskon_type_id'=> ,
                                // 'service_id'    => ,
                                // 'diskon_id'     => ,
                                // 'diskon_rp'     => ,
                                // 'active'        => 1 
                            ];
                            $this->db->insert('t_air', (array)$dataAir);
                        }
                        if($dataListrik){
                            $dataListrik->t_one_bill_id = $idOneBill;
                            $this->db->insert('t_listrik', (array)$dataListrik);
                        }
                    }   
                }
            }
            
            $periode = date("Y-m-d", strtotime("+1 month", strtotime($periode)));
            
        }
        // echo('<pre>');
        //     print_r($selisih_bulan);
        // echo('</pre>');
        
        
        // $this->load->model('m_core');
        // $this->load->model('m_log');
        // $project = $this->m_core->project();

        // $data = [
        //     'unit_id' 		=> $dataTmp['unit_id'],
		// 	'periode' 		=> $dataTmp['periode'],
		// 	'keterangan' 	=> $dataTmp['keterangan'],
		// 	'meter' 		=> $this->m_core->currency_to_number($dataTmp['meter']),
		// 	'active' 		=> 1,
		// 	'delete' 		=> 0
        // ];
        
        // $this->db->where('periode', $data['periode']);
        // $this->db->from('t_meter_air');

        // // validasi double
        // if($this->db->count_all_results()==0){             
        //     $this->db->insert('t_meter_air', $data);
        //     $idTMP = $this->db->insert_id();

        //     $dataLog = $this->get_log($idTMP);
        //     $this->m_log->log_save('t_meter_air', $idTMP, 'Tambah', $dataLog);

        //     return 'success';
        // }else return 'double';
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