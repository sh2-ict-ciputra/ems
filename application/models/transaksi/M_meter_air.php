<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_meter_air extends CI_Model
{

    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                t_pencatatan_meter_air.id,
                t_pencatatan_meter_air.periode,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit as unit,
                t_pencatatan_meter_air.meter_awal,
                t_pencatatan_meter_air.meter_akhir as meter_akhir,
                t_pencatatan_meter_air.meter_akhir - t_pencatatan_meter_air.meter_awal as pemakaian,
                customer.name as customer	
            FROM	t_pencatatan_meter_air
            JOIN unit
                ON unit.id = t_pencatatan_meter_air.unit_id
            JOIN unit_air
                ON unit_air.unit_id = unit.id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = unit.blok_id
                AND kawasan.project_id = $project->id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
        ");
        return $query->result_array();
    }
    public function getPT()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                * 
            FROM pt 
            where active = 1
        ");
        return $query->result_array();
    }
    public function getInfoAdd()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                unit.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit as unit,
                customer.name as pemilik
            FROM unit
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            JOIN unit_air
                ON unit_air.unit_id = unit.id
            WHERE kawasan.project_id = $project->id
            AND unit.status_tagihan = 1
            AND unit_air.aktif = 1
        ");
        return $query->result();
    }
    public function getInfoUnit()
    {
        $id = $this->input->get('id');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                customer.name as customer,
                unit_air.barcode_meter as barcode,
                case
                    WHEN t_meter_air.unit_id = unit.id THEN t_meter_air.meter
                    ELSE unit_air.angka_meter_sekarang
                END as meter
            FROM unit
            JOIN unit_air
                ON unit_air.unit_id = unit.id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            LEFT JOIN t_meter_air
                ON t_meter_air.unit_id = unit.id
            WHERE unit.id = $id
        ");
        return $query->row();
    }
    public function getSelect($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                unit.id as unit,
                DATENAME(month,t_meter_air.periode) as bulan,
                DATENAME(year,t_meter_air.periode) as tahun,
                t_meter_air.keterangan,
                customer.name as customer,
                unit_air.barcode_meter,
                ISNULL(meter_awal.meter, unit_air.angka_meter_sekarang) as meter_awal,
                t_meter_air.meter as meter_akhir,
                t_meter_air.meter-ISNULL(meter_awal.meter, unit_air.angka_meter_sekarang) as pemakaian
            FROM	t_meter_air
            JOIN unit
                ON unit.id = t_meter_air.unit_id
            JOIN unit_air
                ON unit_air.unit_id = unit.id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = unit.blok_id
                AND kawasan.project_id = 1
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            LEFT JOIN t_meter_air as meter_awal
                ON MONTH(meter_awal.periode)+1 = MONTH(t_meter_air.periode)
                AND meter_awal.id = t_meter_air.id
            WHERE t_meter_air.id = $id
        ");
        return $query->result();
    }
    public function get_log($id)
    {
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
    public function getKawasan()
    {
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
    public function ajax_get_blok($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query(
            "
            SELECT 
                id,
                code,
                name 
            FROM blok" . ($id != 'all' ?" WHERE kawasan_id = $id" : "") .
                " ORDER BY id ASC"
        )->result();
        return $query;
    }
    public function ajax_get_unit($kawasan, $blok, $periode)
    {
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $project = $this->m_core->project();
        $query = $this->db
                        ->distinct()
                        ->select("
                        unit.id,
                        case 
                            WHEN count(cek_setelah.id)>0 THEN 'disabled'
                            else ''
                        END as data_setelah,
                        kawasan.name as kawasan,
                        blok.name as blok,
                        unit.no_unit,
                        pemilik.name as pemilik,
                        REPLACE( CONVERT ( VARCHAR, CAST ( ISNULL( t_pencatatan_meter_air.meter_awal , ISNULL(cek_sebelum.meter_akhir,0) ) AS money ), 1 ), '.00', '' ) as meter_awal,
                        REPLACE( CONVERT ( VARCHAR, CAST ( ISNULL( t_pencatatan_meter_air.meter_akhir , 0 ) AS money ), 1 ), '.00', '' ) as meter_akhir,
                        CASE
                            WHEN ISNULL( t_pencatatan_meter_air.meter_akhir , 0 ) <= ISNULL( t_pencatatan_meter_air.meter_awal , ISNULL(cek_sebelum.meter_akhir,0) ) THEN '0'
                            ELSE  REPLACE( CONVERT ( VARCHAR, CAST ( (ISNULL( t_pencatatan_meter_air.meter_akhir , 0 )-ISNULL( t_pencatatan_meter_air.meter_awal , ISNULL(cek_sebelum.meter_akhir,0))) AS money ), 1 ), '.00', '' )
                        END as meter_pakai
                        ")
                        ->from("unit")
                        ->join("unit_air",
                            "unit_air.unit_id = unit.id
                            AND unit_air.aktif = 1")
                        ->join("t_pencatatan_meter_air",
                            "t_pencatatan_meter_air.unit_id = unit.id
                            AND t_pencatatan_meter_air.periode = '$periode'"
                            ,"LEFT")
                        ->join("t_pencatatan_meter_air as cek_sebelum",
                            "cek_sebelum.unit_id = unit.id
                            AND cek_sebelum.periode = DATEADD(month,-1,'$periode')"
                            ,"LEFT")
                        ->join("t_pencatatan_meter_air as cek_setelah",
                            "cek_setelah.unit_id = unit.id
                            AND cek_setelah.periode > '$periode'"
                            ,"LEFT")
                        ->join("blok",
                            "blok.id = unit.blok_id")
                        ->join("kawasan",
                            "kawasan.id = blok.kawasan_id")
                        ->join("customer as pemilik",
                            "pemilik.id = unit.pemilik_customer_id")
                        ->where("kawasan.project_id",$project->id)     
                        ->group_by("
                            unit.id,
                            kawasan.name,
                            blok.name,
                            unit.no_unit,
                            pemilik.name,
                            t_pencatatan_meter_air.periode,
                                REPLACE( CONVERT ( VARCHAR, CAST ( ISNULL( t_pencatatan_meter_air.meter_awal , ISNULL(cek_sebelum.meter_akhir,0) ) AS money ), 1 ), '.00', '' ),
                            REPLACE( CONVERT ( VARCHAR, CAST ( ISNULL( t_pencatatan_meter_air.meter_akhir , 0 ) AS money ), 1 ), '.00', '' ),
                            case
                                WHEN ISNULL( t_pencatatan_meter_air.meter_akhir , 0 ) <= ISNULL( t_pencatatan_meter_air.meter_awal , ISNULL(cek_sebelum.meter_akhir,0) ) THEN '0'
                                ELSE  REPLACE( CONVERT ( VARCHAR, CAST ( (ISNULL( t_pencatatan_meter_air.meter_akhir , 0 )-ISNULL( t_pencatatan_meter_air.meter_awal , ISNULL(cek_sebelum.meter_akhir,0))) AS money ), 1 ), '.00', '' )
                            END");
        if ($kawasan != "all") {
            $query = $query->where("kawasan.id",$kawasan);
        }
        if ($blok != "all") {
            $query = $query->where("blok.id",$blok);
        }

        $result = $query->get()->result();

        return $result;
    }

    public function harga_air($data1,$data2,$data3){
        
            $this->load->model('m_core');
            $hasil = 0;
            $tableName = '';
            if ($data1 == '1') {
                $tableName = 'range_air_detail';
                $tableNameid = 'range_air_id';
                $tableRange =  'range_air';
            } elseif ($data1 == '2') {
                $tableName = 'range_lingkungan_detail';
                $tableNameid = 'range_lingkungan_id';
                $tableRange =  'range_lingkungan';

            } elseif ($data1 == '3') {
                $tableName = 'range_listrik_detail';
                $tableNameid = 'range_listrik_id';
            }
            
            //get minimum pemakaian
            
            
            // $rumus = $this->db->get_where('parameter_project', array('name' => 'Rumus Hitung Air', 'project_id' => 1))->row()->value;
            $rumus = $this->db->select('formula')->from($tableRange)->where('id',$data2)->get()->row()->formula;
            if ($rumus == 1) {
                $query = $this->db->query("
                    select top 1
                        (CAST(harga as BIGINT) * $data3) as harga
                    from $tableName
                    where $tableNameid = $data2
                    and range_awal <= $data3
                    order by range_awal desc
                ");
                $hasil = $query->result_array()[0]['harga'];

                return $hasil;
            } elseif ($rumus == 2) {
                $query = $this->db->query("
                    select
                        range_awal,
                        range_akhir,
                        harga
                    from $tableName
                    where $tableNameid = $data2
                    and range_awal <= $data3
                ");
                $result = $query->result_array();
                $i = 0;
                do {
                    if ($data3 > $result[$i]['range_akhir']) {
                        if($i == 0)
                            $hasil += (($result[$i]['range_akhir']) * $result[$i]['harga']);
                        else
                            $hasil += (($result[$i]['range_akhir']-$result[$i-1]['range_akhir']) * $result[$i]['harga']);   
                    } else {
                        $hasil += ($data3 * $result[$i]['harga']);

                        return $hasil;
                    }
                    if($i == 0)
                        $data3 -=  $result[$i]['range_akhir'];
                    else
                        $data3 -= ($result[$i]['range_akhir']-$result[$i-1]['range_akhir']);
                    ++$i;
                    // var_dump($data3);
                } while ($data3 > 0);
            } elseif ($rumus == 3) {
                $query = $this->db->query("
                    select
                        range_akhir,
                        harga
                    from $tableName
                    where $tableNameid = $data2
                    and range_awal <= $data3
                ");
                $result = $query->result_array();
                $data3 -= $result[0]['range_akhir'];
                $hasil = $result[0]['harga'];
                $i = 1;
                if (isset($result[$i])) {
                    do {
                        if ($data3 > $result[$i]['range_akhir']) {
                            $hasil += (($result[$i]['range_akhir']-$result[$i-1]['range_akhir']) * $result[$i]['harga']);
                        } else {
                            $hasil += ($data3 * $result[$i]['harga']);

                            return $hasil;
                        }
                        
                        $data3 -= ($result[$i]['range_akhir']-$result[$i-1]['range_akhir']);
                        ++$i;
                        // var_dump($data3);
                    } while ($data3 > 0);
                    
                }

                return $hasil;
            } elseif ($rumus == 4) {
                $query = $this->db->query("
                        select
                            harga
                        from $tableName
                        where $tableNameid = $data2
                        and range_awal <= $data3
                        and range_akhir >= $data3
                    ");
                $hasil = $query->row();
                if ($hasil == null) {
                    $query = $this->db->query("
                            select top 1
                                harga
                            from $tableName
                            where $tableNameid = $data2
                            order by harga DESC
                        ");
                    $hasil = $query->row();
                }

                return $hasil->harga;
            }
    }

    public function ajax_save_meter($meter, $periode, $unit_id)
    {

        $this->load->model('m_sub_golongan');

        $project = $this->m_core->project();
        // $periode_1 = str_pad((((int)substr($periode, 0, 2))-1), 2, '0',STR_PAD_LEFT). "-01-" . substr($periode, 3, 4);
        $periode = substr($periode, 3, 4)."-".substr($periode, 0, 2). "-01";

        $tmp = $periode;
        $tmp = strtotime(date("Y-m-d", strtotime($tmp)) . " -1 month");

        $tmp = date("Y-m-d",$tmp);

        $tmp = substr($tmp,5,2).'-'.substr($tmp,8,2).'-'.substr($tmp,0,4);

        $periode_1 = $tmp;

        $meter = str_replace(',', '', $meter);
        
        // var_dump($periode_1);
        // var_dump($periode);
        // var_dump($tmp);
        
        $meter_awal= $this->db->select("meter_akhir")
        ->from("t_pencatatan_meter_air")
        ->where("unit_id",$unit_id)
        ->where("periode",$periode_1)
        ->get()->row();

        $meter_awal = $meter_awal?$meter_awal->meter_akhir:0;
        $dataMeterAir = [
            'unit_id'       => $unit_id,
            'periode'       => $periode,
            'keterangan'    => 'Data Inputan',
            'meter_akhir'         => $meter,
            'meter_awal'         => $meter_awal,
        ];
        $resultMeterAir =   $this->db->query("
                                SELECT
                                    id
                                FROM t_pencatatan_meter_air
                                Where periode = '$dataMeterAir[periode]'
                                AND unit_id = $dataMeterAir[unit_id]
                            ")->row();
        $this->db->trans_start();
        
        if($resultMeterAir){ //jika t_meter_air sudah ada, maka di update
            $this->db->where('id', $resultMeterAir->id);
            $this->db->update('t_pencatatan_meter_air', $dataMeterAir);
        }else{
            $this->db->insert('t_pencatatan_meter_air', $dataMeterAir);
        }
        $this->db->trans_complete();

        $this->db->trans_start();
        $resultTagihan = $this->db->query("
                            SELECT 
                                sub_golongan.administrasi,
                                service.ppn_flag,
                                range_air.formula,
                                range_air.id as range_id,
                                range_air.code as range_code,
                                sub_golongan.id as sub_golongan_id,
                                sub_golongan.code as sub_golongan_code
                            FROM unit_air
                            JOIN sub_golongan
                                ON sub_golongan.id = unit_air.sub_gol_id
                            JOIN range_air
                                ON range_air.id = sub_golongan.range_id
                            JOIN service
                                ON service.id = sub_golongan.service_id
                            where unit_air.unit_id = $unit_id
                        ")->row();
        $resultPemakaian =  $this->db->query("
                                        SELECT 
                                            meter
                                        FROM t_meter_air
                                        WHERE unit_id = $dataMeterAir[unit_id]
                                        AND periode = '$dataMeterAir[periode]'
                                        OR unit_id = $dataMeterAir[unit_id]
                                        AND periode = DATEADD(month,-1,'$dataMeterAir[periode]')
                                        ORDER BY periode
                                    ")->result();

        

        // $pemakaian = $meter_awal!=0?$meter-$meter_awal:0;
        $pemakaian = $meter-$meter_awal;


        // $pemakaian = $this->getSelect($resultMeterAir->id)?$this->getSelect($resultMeterAir->id)[0]->pemakaian:0;
        $minimum_pemakaian_rp = $this->db
                                ->select('minimum_pemakaian,minimum_rp')
                                ->from('unit')
                                ->join('unit_air','unit_air.unit_id = unit.id')
                                ->join('sub_golongan','sub_golongan.id = unit_air.sub_gol_id')
                                ->where('unit.id',$unit_id)->get()->row();
        if($minimum_pemakaian_rp->minimum_pemakaian > $pemakaian)
            $pemakaian = $minimum_pemakaian_rp->minimum_pemakaian;

        
        // $pemakaian = $resultPemakaian[1]->meter - $resultPemakaian[0]->meter;
        // 1 ialah flag untuk air di m_sub_golongan
        $biaya = $this->harga_air(1,$resultTagihan->range_id,$pemakaian,$unit_id);
        if($minimum_pemakaian_rp->minimum_rp > $biaya)
            $biaya = $minimum_pemakaian_rp->minimum_rp;

        $tagihan_air         = (object)[];
        $tagihan_air_detail  = (object)[];
        $tagihan_air_info    = (object)[];
        $meter_air           = (object)[];
        $data_tagihan        = (object)[];

        $tagihan_air->proyek_id             = $project->id;


        $tagihan_air_detail->source_table   = "";
        $tagihan_air_detail->active         = 1;
        // $tagihan_air_detail->user_id        = $user_id;

        $ppn_flag = $this->db   ->select("ppn_flag")
                                ->from("service")
                                ->where("service.project_id",$project->id)
                                ->where("service_jenis_id",2)
                                ->get()->row()->ppn_flag;
        if($ppn_flag == 1)
            $tagihan_air_detail->nilai_ppn      = $this->db->select("isnull(value,'0') as value")
                                                        ->from("parameter_project")
                                                        ->where("project_id",$project->id)
                                                        ->where("code","PPN")->get()->row()->value;
        else
            $tagihan_air_detail->nilai_ppn = 0;
        $tagihan_air_detail->nilai_flag     = 0;
        $tagihan_air_detail->nilai_denda_flag = 0;

        $data_tagihan->proyek_id    = $project->id;

        $this->db->where('proyek_id',$project->id);                
        $this->db->where('unit_id',$dataMeterAir['unit_id']);            
        $this->db->where('periode',$dataMeterAir['periode']);
        
        $tagihan_sudah_ada = $this->db->get('t_tagihan');

        if (!$tagihan_sudah_ada->num_rows()) {
            $data_tagihan->unit_id      = $dataMeterAir['unit_id'];
            $data_tagihan->periode      = $dataMeterAir['periode'];

            $this->db->insert('t_tagihan',$data_tagihan);

            $tagihan_air->t_tagihan_id = $this->db->insert_id();
        }else{
            $tagihan_air->t_tagihan_id = $tagihan_sudah_ada->row()->id;
        }

        $tagihan_air->unit_id       = $dataMeterAir['unit_id'];
        $tagihan_air->kode_tagihan  = "EX-Test";
        $tagihan_air->periode       = $dataMeterAir['periode'];
        $tagihan_air->status_tagihan= 0;            
        // $this->db->insert("t_tagihan_air",$tagihan_air);

        $tagihan_air_detail->nilai              = $biaya;
        $tagihan_air_detail->nilai_administrasi = $resultTagihan->administrasi;
        $tagihan_air_detail->nilai_denda        = 0;
        $tagihan_air_detail->ppn_flag           = $resultTagihan->ppn_flag?1:0;

        $service = $this->db->select("*")
                    ->from("service")
                    ->where("service_jenis_id",2)
                    ->where("project_id",$project->id)
                    ->get()->row();
        $tagihan_air_info->range_id             = $resultTagihan->range_id;
        $tagihan_air_info->range_code           = $resultTagihan->range_code;
        $tagihan_air_info->sub_golongan_id      = $resultTagihan->sub_golongan_id;
        $tagihan_air_info->sub_golongan_code    = $resultTagihan->sub_golongan_code;
        $tagihan_air_info->pemakaian            = $pemakaian;
        $tagihan_air_info->denda_jenis_service  = $service->denda_jenis;
        $tagihan_air_info->denda_nilai_service  = $service->denda_nilai;
        
        // $meter_air->unit_id     = $tagihan_air->unit_id;
        // $meter_air->periode     = $tagihan_air->periode;
        // $meter_air->meter_awal  = $v->Meter_awal;
        // $meter_air->meter_akhir = $v->Meter_akhir;
        // $meter_air->keterangan  = "Data Migrasi dari $source";
        // $this->db->insert("t_pencatatan_meter_air",$dataMeterAir);

        
        $dataTagihanAir = [
            "proyek_id"                     => $project->id,
            "unit_id"                       => $dataMeterAir['unit_id'],
            "kode_tagihan"                  => 'kode123',
            "periode"                       => $dataMeterAir['periode'],
            "nilai"                         => $biaya,
            "administrasi"                  => $resultTagihan->administrasi,
            "ppn_flag"                      => $resultTagihan->ppn_flag,
            "denda"                         => 0,
            "penalti"                       => 0,
            "diskon_master_id"              => 0,
            "diskon_master"                 => 0,
            "diskon_request_tagihan_id"     => 0,
            "diskon_request_denda_id"       => 0,
            "diskon_request_pinalti_id"     => 0,
            "status_bayar_flag"             => 0,
            "formula"                       => $resultTagihan->formula,
            "range_id"                      => $resultTagihan->range_id,
            "range_code"                    => $resultTagihan->range_code,
            "sub_golongan_id"               => $resultTagihan->sub_golongan_id,
            "sub_golongan_code"             => $resultTagihan->sub_golongan_code,
            "pemakaian"                     => $pemakaian
        ];

        $resultTagihanAir =   $this->db->query("
                                                SELECT
                                                    id
                                                FROM t_tagihan_air
                                                Where periode = '$dataTagihanAir[periode]'
                                                AND unit_id = $dataTagihanAir[unit_id]
                                            ")->row();
        

        if($resultTagihanAir){ //jika t_meter_air sudah ada, maka di update
            $this->db->where('id', $resultTagihanAir->id);
            $this->db->update('t_tagihan_air', $tagihan_air);

            $this->db->where('t_tagihan_air_id', $resultTagihanAir->id);
            $this->db->update("t_tagihan_air_detail",$tagihan_air_detail);
            
            $this->db->where('t_tagihan_air_id', $resultTagihanAir->id);
            $this->db->update("t_tagihan_air_info",$tagihan_air_info);
    

        }else{
            $this->db->insert('t_tagihan_air', $tagihan_air);
            
            $tagihan_air_detail->t_tagihan_air_id   = $this->db->insert_id();
            $tagihan_air_info->t_tagihan_air_id     = $tagihan_air_detail->t_tagihan_air_id;

            $this->db->insert("t_tagihan_air_detail",$tagihan_air_detail);
            $this->db->insert("t_tagihan_air_info",$tagihan_air_info);
        }
        
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return false;
        }else{
            $this->db->trans_commit();
            return true;
        }        
    }
    public function last_id()
    {
        $query = $this->db->query("
            SELECT TOP 1 id FROM customer 
            ORDER by id desc
        ");
        return $query->row() ?$query->row()->id : 0;
    }
    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                t_meter_air.id
            FROM t_meter_air
            JOIN unit
                ON unit.id = t_meter_air.unit_id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = unit.blok_id
                AND kawasan.project_id = $project->id 
            WHERE t_meter_air.id = $id 
        ");
        $row = $query->row();

        return isset($row) ?1 : 0;
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $data = [
            'unit_id'         => $dataTmp['unit_id'],
            'periode'         => $dataTmp['periode'],
            'keterangan'     => $dataTmp['keterangan'],
            'meter_akhir'         => $this->m_core->currency_to_number($dataTmp['meter']),
            'active'         => 1,
            'delete'         => 0
        ];

        $this->db->where('periode', $data['periode']);
        $this->db->from('t_pencatatan_meter_air');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('t_pencatatan_meter_air', $data);
            $idTMP = $this->db->insert_id();

            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('t_pencatatan_meter_air', $idTMP, 'Tambah', $dataLog);

            return 'success';
        } else return 'double';
    }
    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data =
            [
                'pt_id'         => $dataTmp['pt_id'],
                'unit'             => $dataTmp['unit'],
                'name'             => $dataTmp['name'],
                'address'         => $dataTmp['address'],
                'email'         => $dataTmp['email'],
                'ktp'             => $dataTmp['ktp'],
                'ktp_address'     => $dataTmp['ktp_address'],
                'mobilephone1'     => $dataTmp['mobilephone1'],
                'mobilephone2'     => $dataTmp['mobilephone2'],
                'homephone'     => $dataTmp['homephone'],
                'officephone'     => $dataTmp['officephone'],
                'npwp_no'         => $dataTmp['npwp_no'],
                'npwp_name'     => $dataTmp['npwp_name'],
                'npwp_address'     => $dataTmp['npwp_address'],
                'description'     => $dataTmp['description'],
                'active'         => $dataTmp['active'],
                'delete'         => 0
            ];

        // validasi data
        if ($this->cek($dataTmp['id'])) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('customer', $data);
            $after = $this->get_log($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array)$after, (array)$before));
            $tmpDiff = (array)$diff;

            if ($tmpDiff) {
                $this->m_log->log_save('customer', $dataTmp['id'], 'Edit', $diff);
                return 'success';
            }
        } else {
            return 'error';
        }
    }
}
