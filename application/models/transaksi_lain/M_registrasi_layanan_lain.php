<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_registrasi_layanan_lain extends CI_Model
{
    public function get($id)
    {
        return $this->db->select("t_layanan_lain_registrasi.*,
                                CASE 
                                    WHEN unit.id is null THEN unit_virtual.unit
                                    ELSE CONCAT(kawasan.name,' ',blok.name,'/',unit.no_unit,' ',customer.name)
                                END as unit_name,
                                customer.id as customer_id,
                                customer.name as customer_name
                                ")
                ->from("t_layanan_lain_registrasi")
                ->join("unit_virtual",
                        "unit_virtual.id = t_layanan_lain_registrasi.unit_virtual_id",
                        "LEFT")
                ->join('unit',
                        'unit.id = t_layanan_lain_registrasi.unit_id',
                        'LEFT')
                ->join("blok",
                        "blok.id = unit.blok_id",
                        "LEFT")
                ->join("kawasan",
                        "kawasan.id = blok.kawasan_id",
                        "LEFT")
                ->join("customer",
                        "customer.id = unit.pemilik_customer_id
                        or customer.id = unit_virtual.customer_id")
                ->where("t_layanan_lain_registrasi.id",$id)
                ->get()->row();
    }
    public function get_detail($id){
        return $this->db->select("  t_layanan_lain_registrasi_detail.*,
                                        case tipe_periode 
                                            WHEN 1 THEN FORMAT(periode_awal,'dd/MM/yyyy')
                                            WHEN 2 THEN FORMAT(periode_awal,'MM/yyyy')
                                            WHEN 3 THEN FORMAT(periode_awal,'yyyy')
                                        END as periode_awal,
                                        case tipe_periode 
                                            WHEN 1 THEN FORMAT(periode_akhir,'dd/MM/yyyy')
                                            WHEN 2 THEN FORMAT(periode_akhir,'MM/yyyy')
                                            WHEN 3 THEN FORMAT(periode_akhir,'yyyy')
                                        END as periode_akhir
                                ")
                        ->from('t_layanan_lain_registrasi_detail')
                        ->where('t_layanan_lain_registrasi_id',$id)
                        ->get()->row();
    }
    public function getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->select("
                                    t_layanan_lain_registrasi.id as registrasi_id,
                                    unit.id as unit_id,
                                    isnull(unit.no_unit,unit_virtual.unit) as no_unit,
                                    t_layanan_lain_registrasi.nomor_registrasi,
                                    pemilik.name,
                                    CASE 
                                        WHEN unit.id > 0 THEN 'Unit Project'
                                        ELSE 'Unit Virtual'
                                    END as jenis_unit,
                                    CASE t_tagihan_layanan_lain.status_tagihan
                                        WHEN 0 THEN 'Belum Terbayar'
                                        WHEN 1 THEN 'Lunas'
                                        WHEN 4 THEN 'Terbayar Sebagian'
                                    END as status_bayar
                                ")
                            ->from("t_layanan_lain_registrasi")
                            ->join("unit",
                                    "unit.id = t_layanan_lain_registrasi.unit_id",
                                    "LEFT")
                            ->join("unit_virtual",
                                    "unit_virtual.id = t_layanan_lain_registrasi.unit_virtual_id",
                                    "LEFT")
                            ->join("customer as pemilik",
                                    "pemilik.id = unit.pemilik_customer_id
                                    OR pemilik.id = unit_virtual.customer_id",
                                    "LEFT")
                            ->join("t_tagihan_layanan_lain",
                                    "t_tagihan_layanan_lain.t_layanan_lain_registrasi_id = t_layanan_lain_registrasi.id")
                            ->where("t_layanan_lain_registrasi.active",1)
                            ->where("t_layanan_lain_registrasi.delete",0)
                            ->where("(unit.project_id = '$project->id' or unit_virtual.project_id = '$project->id')")
                            ->get()->result();

        return $query;
    }

    public function getDetailServiceCetak($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $result = $this->db
            ->select("
                        CASE
                            WHEN biaya_pemasangan is null  or  biaya_pemasangan =0 THEN '0'
                            ELSE '1'
                        END as biaya_pemasangan_flag,
                        biaya_pemasangan,
                        t_layanan_lain_registrasi.unit_id,
                        t_layanan_lain_registrasi_detail.biaya_satuan as harga_satuan,
                        t_tagihan_layanan_lain_detail.biaya_registrasi,
                        service.name as service_name,
                        t_layanan_lain_registrasi_detail.*")
            ->from("t_layanan_lain_registrasi")
            ->join(
                " t_layanan_lain_registrasi_detail",
                "t_layanan_lain_registrasi_detail.layanan_lain_registrasi_id = t_layanan_lain_registrasi.id"
            )
            ->join(
                " service",
                "service.id = t_layanan_lain_registrasi_detail.service_id"
            )
            ->join(
                " t_tagihan_layanan_lain",
                "t_tagihan_layanan_lain.layanan_lain_registrasi_detail_id = t_layanan_lain_registrasi_detail.id"
            )
            ->join(
                " t_tagihan_layanan_lain_detail",
                "t_tagihan_layanan_lain_detail.layanan_lain_tagihan_id = t_tagihan_layanan_lain.id"
            )
            ->where("t_layanan_lain_registrasi.id", $id)
            ->order_by('t_layanan_lain_registrasi_detail.id', 'ASC')
            ->distinct()
            ->get()->row();
        // echo("<pre>");
        //     print_r($result);
        // echo("</pre>");

        return $result;
    }

    public function getDetailService($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            t_layanan_lain_registrasi_detail.id AS id,
            t_layanan_lain_registrasi.id AS id_registrasi,
            service.id AS service_id,
            paket_service.id AS paket_service_id,
            t_layanan_lain_registrasi_detail.periode_awal,
            t_layanan_lain_registrasi_detail.periode_akhir,
            t_layanan_lain_registrasi_detail.kuantitas,
            t_layanan_lain_registrasi_detail.status_berlangganan,
            paket_service.satuan AS satuan,
            paket_service.biaya_satuan_langganan as harga_satuan,
            paket_service.biaya_registrasi as biaya_registrasi,
            paket_service.biaya_satuan_tanpa_langganan as biaya_satuan_tanpa_langganan,
            paket_service.biaya_pemasangan as biaya_pemasangan,
            paket_service.minimal_langganan as minimal_langganan,
            t_layanan_lain_registrasi_detail.active,
            t_layanan_lain_registrasi.unit_id as unit_id
        FROM
        t_layanan_lain_registrasi_detail
        JOIN service 
            ON service.id = t_layanan_lain_registrasi_detail.service_id
        JOIN paket_service 
            ON paket_service.id = t_layanan_lain_registrasi_detail.paket_service_id
					JOIN t_layanan_lain_registrasi
					ON t_layanan_lain_registrasi.id = t_layanan_lain_registrasi_detail.layanan_lain_registrasi_id
        WHERE t_layanan_lain_registrasi_detail.active = 1          
        ");
        $result = $this->db
            ->select("
                        CASE
                            WHEN biaya_pemasangan is null  or  biaya_pemasangan =0 THEN '0'
                            ELSE '1'
                        END as biaya_pemasangan_flag,
                        biaya_pemasangan,
                        t_layanan_lain_registrasi.unit_id,
                        t_layanan_lain_registrasi_detail.biaya_satuan as harga_satuan,
                        t_tagihan_layanan_lain_detail.biaya_registrasi,
                        t_layanan_lain_registrasi_detail.*")
            ->from("t_layanan_lain_registrasi")
            ->join(
                " t_layanan_lain_registrasi_detail",
                "t_layanan_lain_registrasi_detail.layanan_lain_registrasi_id = t_layanan_lain_registrasi.id"
            )
            ->join(
                " t_tagihan_layanan_lain",
                "t_tagihan_layanan_lain.layanan_lain_registrasi_detail_id = t_layanan_lain_registrasi_detail.id"
            )
            ->join(
                " t_tagihan_layanan_lain_detail",
                "t_tagihan_layanan_lain_detail.layanan_lain_tagihan_id = t_tagihan_layanan_lain.id"
            )
            ->where("t_layanan_lain_registrasi.id", $id)
            ->order_by('t_layanan_lain_registrasi_detail.id', 'ASC')
            ->distinct()
            ->get()->result();
        // echo("<pre>");
        //     print_r($result);
        // echo("</pre>");

        return $result;
    }
    public function get_service_non_retribusi()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                id,
                name
            FROM service
            WHERE service_jenis_id = 6   
            and project_id = $project->id
        ");

        return $query->result();
    }

    public function get_paket_service_non_retribusi()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
             id,
             name
         FROM paket_service  
         where project_id = $project->id
        ");
        return $query->result();
    }

    public function getUnit()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
                case 
                    when t_layanan_lain_registrasi.id > 0 THEN '1'
                    else '0'
                END as telah_registrasi,
                project.name as project_name,
                kawasan.name as kawasan_name,
                blok.name as blok_name,
                unit.*
            FROM unit
            join blok 
                on unit.blok_id = blok.id
            join kawasan 
                on blok.kawasan_id = kawasan.id
            join project 
                on project.id = kawasan.project_id 
            join customer
                on customer.id = unit.pemilik_customer_id
            LEFT JOIN t_layanan_lain_registrasi
                on t_layanan_lain_registrasi.unit_id = unit.id
                AND t_layanan_lain_registrasi.[delete] = 0
	            AND t_layanan_lain_registrasi.[active] = 1
            where project.id = $project->id
                   
    
            ");

        return $query->result_array();
    }

    public function getUnitCetak()
    {

        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
                case 
                    when t_layanan_lain_registrasi.id > 0 THEN '1'
                    else '0'
                END as telah_registrasi,
                project.name as project_name,
                kawasan.name as kawasan_name,
                blok.name as blok_name,
                customer.name as customer_name,
                unit.*
            FROM unit
            join blok 
                on unit.blok_id = blok.id
            join kawasan 
                on blok.kawasan_id = kawasan.id
            join project 
                on project.id = kawasan.project_id 
            join customer
                on customer.id = unit.pemilik_customer_id
            LEFT JOIN t_layanan_lain_registrasi
                on t_layanan_lain_registrasi.unit_id = unit.id
                AND t_layanan_lain_registrasi.[delete] = 0
	            AND t_layanan_lain_registrasi.[active] = 1
            where project.id = $project->id
                   
    
            ");

        return $query->row();
    }
    public function get_paket($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                id,
                name
            FROM paket_service
            where service_id = $id
            and project_id = $project->id
        ");

        return $query->result();
    }
    public function get_harga_paket($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                harga
            FROM paket_service
            where service_id = $id
            and project_id = $project->id
        ");

        return $query->row();
    }
    public function get_pemilik_penghuni($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                pemilik.id as pemilik_kode,
                pemilik.name as pemilik_nama,
                pemilik.mobilephone1 as pemilik_mobile_phone,
                pemilik.homephone as pemilik_home_phone,
                pemilik.email as pemilik_email,
                pemilik.address as pemilik_alamat,
                penghuni.code as penghuni_kode,
                penghuni.name as penghuni_nama,
                penghuni.mobilephone1 as penghuni_mobile_phone,
                penghuni.homephone as penghuni_home_phone,
                penghuni.email as penghuni_email,
                penghuni.address as penghuni_alamat,
                unit.bangunan_type,
                unit.luas_tanah,
                unit.luas_bangunan,
                unit.luas_taman
            FROM unit
            JOIN customer as pemilik
                ON pemilik.id = unit.pemilik_customer_id
            LEFT JOIN customer as penghuni
                ON penghuni.id = unit.penghuni_customer_id
            JOIN blok
                ON blok.id = unit.blok_id
            JOIN kawasan
                ON kawasan.id = blok.kawasan_id
                AND kawasan.project_id = $project->id
            WHERE unit.id = $id
        ");
        return $query->row();
    }
    // public function getparameter(){
    //     $this->load->model('m_core');
    //     $project = $this->m_core->project();

    //     $statusBerlangganan = $this->db->select('value')
    //     -> where('project_id',$project->id)
    //     -> get('parameter_project')
    //     -> row();
    //     $statusBerlangganan = $statusBerlangganan?$statusBerlangganan->value:0;

    //     return $statusBerlangganan;
    // }
    public function get_parameter()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT id, 
               name,
               value,
               description,
               code
        FROM parameter_project  
        ");
        return $query->row();
    }
    public function hapus()
    {
        $query = $this->db->query("
        SELECT unit_id,
        nomor_registrasi, customer.name
        FROM t_layanan_lain_registrasi
        JOIN customer
        ON customer.id = t_layanan_lain_registrasi.id
        ");
    }
    public function last_id()
    {
        $query = $this->db->query("
            SELECT TOP 1
               id
            FROM t_layanan_lain_registrasi
            order by id desc
        ");
        return $query->row() ? $query->row()->id : 0;
    }

    public function last_id_tagihan()
    {
        $query = $this->db->query("
            SELECT TOP 1
               id
            FROM t_tagihan_layanan_lain
            order by id desc
        ");
        return $query->row() ? $query->row()->id : 0;
    }

    public function get_paket_service()
    {
        $project = $this->m_core->project();

        return $this->db->select('paket_service.id,paket_service.name')
            ->from('paket_service')
            ->join(
                'service',
                'service.id = paket_service.service_id'
            )
            ->where('service.project_id', $project->id)
            ->where('service.service_jenis_id', 6)
            ->get()->result();

        $query = $this->db->query("
            SELECT
               id,name
            FROM paket_service
            WHERE service_id = $id
            AND active = 1
            AND [delete] = 0
            order by name
        ");
        return $query->result();
    }

    public function get_paket_service_cetak($id)
    {
        $query = $this->db->query("
            SELECT
               id,name
            FROM paket_service
            WHERE service_id = $id
            AND active = 1
            AND [delete] = 0
            order by name
        ")->row();

        return $query;
    }
    public function get_info_paket_service($id)
    {
        $this->load->model('Setting/M_parameter_project');
        $project = $this->m_core->project();
        $ppn = $this->M_parameter_project->get($project->id, 'ppn');
        $ppn = $ppn?$ppn:0;
        return $this->db->select("
                                        case ppn_flag
                                            WHEN 1 THEN paket_service.biaya_satuan_langganan/$ppn
                                            ELSE 0
                                        END as ppn_biaya_satuan_langganan,
                                        case ppn_flag
                                            WHEN 1 THEN paket_service.biaya_satuan_tanpa_langganan/$ppn
                                            ELSE 0
                                        END as ppn_biaya_satuan_tanpa_langganan,
                                        case ppn_flag
                                            WHEN 1 THEN paket_service.biaya_registrasi/$ppn
                                            ELSE 0
                                        END as ppn_biaya_registrasi,
                                        case ppn_flag
                                            WHEN 1 THEN paket_service.biaya_pemasangan/$ppn
                                            ELSE 0
                                        END as ppn_biaya_pemasangan,
                                        paket_service.*,
                                        ppn_flag
                                    ", false)
            ->from("paket_service")
            ->join(
                'service',
                'service.id = paket_service.service_id'
            )
            ->where("paket_service.id", $id)
            ->get()->row();
        // $query = $this->db->query("
        //     SELECT
        //         satuan,
        //         biaya_satuan_langganan as harga,
        //         biaya_registrasi,
        //         biaya_satuan_tanpa_langganan,
        //         minimal_langganan
        //         FROM paket_service
        //     WHERE id = $id
        // ");
        // return $query->row();
    }

    public function get_info_paketservice($id)
    {
        $query = $this->db->query("
            SELECT
                satuan,
                biaya_satuan_langganan as harga,
                biaya_registrasi,
                biaya_satuan_tanpa_langganan,
                biaya_pemasangan
                FROM paket_service
            WHERE id = $id
        ");
        return $query->row();
    }

    // public function last_id_tagihan()
    // {
    //     $query = $this->db->query("
    //         SELECT TOP 1 id FROM t_tagihan_layanan_lain
    //         ORDER by id desc
    //     ");
    //     return $query->row() ? $query->row()->id : 0;
    // }


    public function save($data)
    {
        $this->db->trans_start();
		
        $data = (object) $data;
        $project = $this->m_core->project();
        $kode_tagihan = "CG/TLL/" . date("Y") . "/" . $this->m_core->numberToRomanRepresentation($project->id) . "/" . ($this->m_registrasi_layanan_lain->last_id_tagihan() + 1); //??

        $check_double = $this->db->select('count(*) as data_double')
            ->from('t_layanan_lain_registrasi')
            ->where("nomor_registrasi", $data->nomor_registrasi)
            ->get()->row();
        $cek = $this->db->select("count(*) as count")
            ->from("t_tagihan")
            ->where("proyek_id", $project->id)
            ->where("periode", date("Y-m-01"));
        if(isset($data->unit_id))
            $cek = $cek->where('unit_id',$data->unit_id);
        else
            $cek = $cek->where('unit_virtual_id',$data->unit_virtual_id);
        
        $cek = $cek->get()->row()->count;
        if ($cek > 0) {
            $t_tagihan = $this->db->select("id")
                ->from("t_tagihan")
                ->where("proyek_id", $project->id)
                ->where("periode", date("Y-m-01"));
            if(isset($data->unit_id))
                $t_tagihan = $t_tagihan->where("unit_id", $data->unit_id);
            else
                $t_tagihan = $t_tagihan->where("unit_virtual_id", $data->unit_virtual_id);
            $t_tagihan = $t_tagihan->get()->row();
        } else {
            $t_tagihan = [
                'proyek_id' => $project->id,
                'periode' => date("Y-m-01")
            ];
            if(isset($data->unit_id)){
                $t_tagihan['unit_id'] = $data->unit_id;
                $t_tagihan['unit_virtual_id'] = 0;
            }else{
                $t_tagihan['unit_id'] = 0;
                $t_tagihan['unit_virtual_id'] = $data->unit_virtual_id;
            }
            $this->db->insert('t_tagihan', $t_tagihan);
            $t_tagihan = (object) $t_tagihan;
            $t_tagihan->id = $this->db->insert_id();
        }
        $t_layanan_lain_registrasi =
            [
                'unit_id'           => isset($data->unit_id)?$data->unit_id:0,
                'unit_virtual_id'   => isset($data->unit_virtual_id)?$data->unit_virtual_id:0,
                'project_id'        => $project->id,
                'nomor_registrasi'  => $kode_tagihan,
                // 'status_bayar_registrasi' => 0,
                'active'            => 1,
                'delete'            => 0,
                'log'               => 0, //belum dipake juga, belum dibayar jadi nanti bisa di edit karena status nya 0
            ];
        $this->db->insert('t_layanan_lain_registrasi', $t_layanan_lain_registrasi);
        $t_layanan_lain_registrasi = (object) $t_layanan_lain_registrasi;
        $t_layanan_lain_registrasi->id = $this->db->insert_id();
        $service = $this->db->select('service.*')
            ->from('paket_service')
            ->join('service',
                    'service.id = paket_service.service_id')
            ->where('paket_service.id', $data->paket_service)
            ->get()->row();
        $paket_service = $this->db->from('paket_service')
            ->where('id', $data->paket_service)
            ->get()->row();
        $periode_awal = explode("/",$data->periode_awal);
        $periode_akhir = explode("/",$data->periode_akhir);
        if($paket_service->tipe_periode == 1){ 
            $periode_awal = $periode_awal[2].'-'.$periode_awal[1].'-'.$periode_awal[0];
            $periode_akhir = $periode_akhir[2].'-'.$periode_akhir[1].'-'.$periode_akhir[0];    
        }elseif($paket_service->tipe_periode == 2){
            $periode_awal = $periode_awal[1].'-'.$periode_awal[0].'-01';
            $periode_akhir = $periode_akhir[1].'-'.$periode_akhir[0].'-01';    
        }elseif($paket_service->tipe_periode == 3){
            $periode_awal = $periode_awal[0].'-01-01';
            $periode_akhir = $periode_akhir[0].'-01-01';    
        }
        $t_layanan_lain_registrasi_detail = (object)
            [
                't_layanan_lain_registrasi_id' => $t_layanan_lain_registrasi->id,
                'service_id'        => $service->id,
                'paket_service_id'  => $data->paket_service,
                'periode_awal'      => $periode_awal,
                'periode_akhir'     => $periode_akhir,
                'jumlah_periode'    => $data->jumlah_periode,
                'tipe_periode'      => $paket_service->tipe_periode,
                'kuantitas'         => $data->kuantitas,
                'satuan'            => $paket_service->satuan,
                'biaya_satuan'      => $data->harga_satuan,
                'status_berlangganan' => $data->status_berlangganan == 'Tidak' ? 0 : 1,
                'minimal_langganan' => $data->min_berlangganan,
                'active'            => 1,
                'delete'            => 0,
            ];
        $this->db->insert('t_layanan_lain_registrasi_detail', $t_layanan_lain_registrasi_detail);

        $t_layanan_lain_registrasi_detail->id = $this->db->insert_id();


        $t_tagihan_layanan_lain = (object)
            [
                'project_id'        => $project->id,
                'unit_id'           => isset($data->unit_id)?$data->unit_id:0,
                'unit_virtual_id'   => isset($data->unit_virtual_id)?$data->unit_virtual_id:0,
                'kode_tagihan'      => $kode_tagihan,
                't_layanan_lain_registrasi_id' => $t_layanan_lain_registrasi->id,
                'total_nilai'       => $data->total_tagihan ? $data->total_tagihan : 0,
                'status_tagihan' => 0,
                't_tagihan_id'      => $t_tagihan->id
            ];

        $this->db->insert('t_tagihan_layanan_lain', $t_tagihan_layanan_lain);
        $t_tagihan_layanan_lain->id = $this->db->insert_id();
        $this->load->model('Setting/m_parameter_project');
        $ppn_nilai= $this->m_parameter_project->get($project->id,"PPN");

        $t_tagihan_layanan_lain_detail =
            [
                't_layanan_lain_tagihan_id'     => $t_tagihan_layanan_lain->id,
                'service_id'                    => $service->id,
                'paket_service_id'              => $data->paket_service,
                'paket_service_name'            => $paket_service->name,
                'biaya_satuan'                  => $data->harga_satuan,
                'biaya_registrasi'              => $data->biaya_registrasi,
                'biaya_pemasangan'              => $data->biaya_pemasangan,
                'kuantitas'                     => $data->kuantitas,
                'periode_awal'                  => $periode_awal,
                'periode_akhir'                 => $periode_akhir,
                'ppn_flag'                      => $service->ppn_flag,
                'ppn_nilai'                     => $ppn_nilai
            ];

        $this->db->insert('t_tagihan_layanan_lain_detail', $t_tagihan_layanan_lain_detail);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return [
                'status' => 0,
                'message'=> 'Gagal Registrasi data double atau ada masalah di Data'
            ];
        } else {
            $this->db->trans_commit();
            return [
                'status' => 1,
                'message'=> 'Berhasil Registrasi silahkan melakukan pembayaran di Dasboard'
            ];
        }
        return [
            'status' => 0,
            'message'=> 'Gagal Registrasi data double atau ada masalah di Data'
        ];
    }


    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        // $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $data =
            [
                'project_id'                => $project->id,
                'coa_mapping_id_service'    => $dataTmp['coa_mapping_id_service'],
                'ppn'                       => $dataTmp['ppn'],
                'coa_mapping_id_ppn'        => $dataTmp['coa_mapping_id_ppn'],
                'denda_parameter'           => $dataTmp['denda_parameter'],
                'denda_jenis'               => $dataTmp['denda_jenis'],
                'denda_minimum'             => $this->m_core->currency_to_number($dataTmp['denda_minimum']),
                'denda_persen'              => $dataTmp['denda_persen'],
                // 'denda_tgl_nonactive'    => $dataTmp['denda_tgl_putus'],
                'description'               => $dataTmp['description'],
                'active'                    => $dataTmp['active'],
                'delete'                    => 0
            ];



        $paket = explode('|', $dataTmp['jenis_paket_id']);

        $this->db->get_where('t_layanan_lain_registrasi', $dataUnit);
        // $id = $this->db->insert_id();
        // $this->db->insert_id('t_layanan_lain_registrasi', $dataUnit);
        return $this;
    }

    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');

        $this->db->where('id', $dataTmp['id']);
        $this->db->update('t_layanan_lain_registrasi', ['delete' => 1]);

        return 'success';
    }
    public function updateStatus($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');

        $this->db->where('id', $dataTmp['id']);
        $this->db->update('t_layanan_lain_registrasi', ['active' => 0]);

        return 'success';
    }
}
