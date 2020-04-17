<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_transaksi_per_unit extends CI_Model
{

    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                t_meter_air.id,
                t_meter_air.periode,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit as unit,
                ISNULL(meter_awal.meter, unit_air.angka_meter_sekarang) as meter_awal,
                t_meter_air.meter as meter_akhir,
                t_meter_air.meter-ISNULL(meter_awal.meter, unit_air.angka_meter_sekarang) as pemakaian,
                customer.name as customer	
            FROM	t_meter_air
            JOIN unit
                ON unit.id = t_meter_air.unit_id
            JOIN unit_air
                ON unit_air.unit_id = unit.id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = unit.blok_id
                AND kawasan.project_id = $project->id
            JOIN customer
                ON customer.id = unit.pemilik_customer_id
            LEFT JOIN t_meter_air as meter_awal
                ON MONTH(meter_awal.periode)+1 = MONTH(t_meter_air.periode)
                AND meter_awal.id = t_meter_air.id
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
        return $query->row();
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
    public function ajax_get_unit($kawasan, $blok)
    {
        $project = $this->m_core->project();
        $query = "
            SELECT
                unit.id,
                kawasan.name as kawasan,
                blok.name as blok,
                unit.no_unit,
                pemilik.name as pemilik
            FROM unit
            JOIN blok
                on blok.id = unit.blok_id
            JOIN kawasan
                on kawasan.id = blok.kawasan_id
            JOIN customer as pemilik
                on pemilik.id = unit.pemilik_customer_id
            WHERE kawasan.project_id = $project->id";
        if ($kawasan != "all") {
            $query = $query . " AND kawasan.id = $kawasan ";
        }
        if ($blok != "all") {
            $query = $query . " AND blok.id = $blok ";
        }

        $result = $this->db->query($query)->result();

        return $result;
    }
    public function ajax_save_meter($meter, $periode, $unit_id)
    {
        $this->load->model('m_sub_golongan');

        $project = $this->m_core->project();
        $periode = substr($periode, 0, 2) . "-01-" . substr($periode, 3, 4);
        $meter = str_replace(',', '', $meter);
        // var_dump($meter);
        // var_dump($periode);
        // var_dump($unit_id);


        $dataMeterAir = [
            'unit_id'       => $unit_id,
            'periode'       => $periode,
            'keterangan'    => '',
            'meter'         => $meter,
            'active'        => 1,
            'delete'        => 0
        ];
        $resultMeterAir =   $this->db->query("
                        SELECT
                            id
                        FROM t_meter_air
                        Where periode = '$dataMeterAir[periode]'
                        AND unit_id = $dataMeterAir[unit_id]
                    ")->row();
        $this->db->trans_start();
        if($resultMeterAir){ //jika t_meter_air sudah ada, maka di update
            $this->db->where('id', $resultMeterAir->id);
            $this->db->update('t_meter_air', $dataMeterAir);
        }else{
            $this->db->insert('t_meter_air', $dataMeterAir);
        }

        
        $resultTagihan = $this->db->query("
                            SELECT 
                                sub_golongan.administrasi,
                                service.ppn as ppn_flag,
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
                            where unit_air.unit_id = 24
                        ")->row();
        var_dump($resultTagihan);
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
        echo('aaa');

        var_dump($resultPemakaian);
        
        $pemakaian = $resultPemakaian[1]->meter - $resultPemakaian[0]->meter;
        var_dump($pemakaian);
        // 1 ialah flag untuk air di m_sub_golongan
        $biaya = $this->m_sub_golongan->get_minimum(1,$resultTagihan->range_id,$pemakaian);
        var_dump($biaya);
            
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
        echo("<pre>");
        print_r($dataTagihanAir);
        echo("</pre>");

        $resultTagihanAir =   $this->db->query("
                                                SELECT
                                                    id
                                                FROM t_tagihan_air
                                                Where periode = '$dataTagihanAir[periode]'
                                                AND unit_id = $dataTagihanAir[unit_id]
                                            ")->row();
        echo("<pre>");
        print_r($resultTagihanAir);
        echo("</pre>");

        if($resultTagihanAir){ //jika t_meter_air sudah ada, maka di update
            $this->db->where('id', $resultTagihanAir->id);
            $this->db->update('t_tagihan_air', $dataTagihanAir);
        }else{
            $this->db->insert('t_tagihan_air', $dataTagihanAir);
        }
        $this->db->trans_complete();
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
            'meter'         => $this->m_core->currency_to_number($dataTmp['meter']),
            'active'         => 1,
            'delete'         => 0
        ];

        $this->db->where('periode', $data['periode']);
        $this->db->from('t_meter_air');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('t_meter_air', $data);
            $idTMP = $this->db->insert_id();

            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('t_meter_air', $idTMP, 'Tambah', $dataLog);

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
