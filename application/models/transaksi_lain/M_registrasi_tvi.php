<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_registrasi_tvi extends CI_Model
{
    public function get()
    {
        $query = $this
            ->db
            ->query('
            SELECT * FROM cara_pembayaran
        ');

        return $query->result_array();
    }

    public function getUnit()
    {

        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT project.name as project_name,
                   kawasan.name as kawasan_name,
                   blok.name as blok_name,
                   unit.*

             FROM unit 
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id where kawasan.project_id = $project->id


             ");

        return $query->result_array();
    }

    public function getUnit2($id)
    {

        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
                SELECT project.name as project_name,
                    kawasan.name as kawasan_name,
                    blok.name as blok_name,
                    unit.id as unit_id,
                    unit.*,
                    customer.code as customer_code,
                    customer.homephone as customer_homephone,
                    customer.mobilephone1 as customer_mobilephone,
                    customer.email as customer_email,
                    customer.id as customer_id,
                    customer.name as customer_name

                FROM unit 
                left join blok on unit.blok_id = blok.id
                left join kawasan on blok.kawasan_id = kawasan.id
                left join project on project.id = kawasan.project_id 
                left join customer on customer.id = unit.pemilik_customer_id
                where kawasan.project_id = $project->id and unit.id = $id


             ");

        return $query->row();
    }

    public function getCustomer()
    {

        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT *
            FROM customer 
             ");

        return $query->result_array();
    }

    public function getCustomer2($id)
    {

        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT 
                   customer.name as customer_name,
                   customer.code as customer_code,
                   customer.homephone as customer_homephone,
                   customer.mobilephone1 as customer_mobilephone,
                   customer.email as customer_email,
                   customer.id as customer_id

            FROM customer  
            where  project_id = $project->id and id = $id          


             ");

        return $query->row();
    }

    public function getNomorRegistrasiNonUnit($customer_id)
    {

        $project = $this
            ->m_core
            ->project();

        $query = $this
            ->db
            ->query("
                SELECT TOP 1 id, nomor_registrasi
                
                FROM t_tvi_registrasi
                where t_tvi_registrasi.customer_id = $customer_id and 
                t_tvi_registrasi.project_id = $project->id   and 
                t_tvi_registrasi.unit = 'non_unit'  
                order by id desc 
            


                ");

        $row = $query->row();

        if (isset($row)) {

            $nomor_registrasi = $row->nomor_registrasi;
        } else {

            $nomor_registrasi = "Auto Generate";
        }

        return $nomor_registrasi;
    }

    public function getAktifasiNonUnit($customer_id)
    {

        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
        SELECT TOP 1 tanggal
        FROM t_tagihan_tvi
        LEFT JOIN t_tvi_registrasi on t_tvi_registrasi.id = t_tagihan_tvi.registrasi_id
        where t_tvi_registrasi.customer_id = $customer_id and t_tagihan_tvi.project_id = $project->id and t_tagihan_tvi.flag_type = 3 and t_tvi_registrasi.unit = 'non_unit'
        order by t_tagihan_tvi.id desc
        ");

        $row = $query->row();

        if (isset($row)) {
            $tanggal = $row->tanggal;
        }
        $last_day = date('Y-m-t', strtotime($tanggal));
        $tanggal_aktifasi = date('d-m-Y', strtotime('+1 day', strtotime($last_day)));

        return $tanggal_aktifasi;
    }

    public function getNomorRegistrasiUnit($unit_id)
    {

        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT TOP 1 id, nomor_registrasi
            FROM t_tvi_registrasi
            where t_tvi_registrasi.unit_id = $unit_id and t_tvi_registrasi.project_id = $project->id   
            order by id desc 
        ");

        $row = $query->row();

        if (isset($row)) {
            $nomor_registrasi = $row->nomor_registrasi;
        } else {
            $nomor_registrasi = "Auto Generate";
        }
        return $nomor_registrasi;
    }

    public function getAktifasiUnit($unit_id)
    {
        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
        SELECT TOP 1  tanggal
        FROM t_tagihan_tvi
        LEFT JOIN t_tvi_registrasi on t_tvi_registrasi.id = t_tagihan_tvi.registrasi_id
        where t_tvi_registrasi.unit_id = $unit_id and t_tagihan_tvi.project_id = $project->id and t_tagihan_tvi.flag_type = 3  
        order by t_tagihan_tvi.id desc 
        ");

        $row = $query->row();

        if (isset($row)) {
            $tanggal = $row->tanggal;
        }

        $last_day = date('Y-m-t', strtotime($tanggal));

        $tanggal_aktifasi = date('d-m-Y', strtotime('+1 day', strtotime($last_day)));

        return $tanggal_aktifasi;
    }

    public function getPaket()
    {
        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT *
            FROM paket_internet
             ");

        return $query->result_array();
    }

    public function getTV()
    {
        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT
            paket_tv.id,
            paket_tv.name as nama,
            group_tvi.*
            FROM paket_tv
            LEFT JOIN group_tvi
            ON paket_tv.group_tvi_id = group_tvi.id
            where paket_tv.active =1 and group_tvi.project_id = $project->id  order by paket_tv.id asc
        ");

        return $query->result_array();
    }

    public function getInternet()
    {
        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT
            paket_internet.id,
            paket_internet.name as nama,
            group_tvi.*
            FROM paket_internet
            LEFT JOIN group_tvi
            ON paket_internet.group_tvi_id = group_tvi.id
            where paket_internet.active =1 and group_tvi.project_id = $project->id  order by paket_internet.id asc
        ");
        return $query->result_array();
    }

    public function getTVI()
    {
        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT
            paket_tvi.id,
            paket_tvi.name as nama,
            group_tvi.*
            FROM paket_tvi
            LEFT JOIN group_tvi
            ON paket_tvi.group_tvi_id = group_tvi.id
            where paket_tvi.active =1 and group_tvi.project_id = $project->id  order by paket_tvi.id asc
        ");

        return $query->result_array();
    }
    public function getJenisPaketInternet($jenis_bayar)
    {

        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
                SELECT 
                    paket_internet.id,
                    paket_internet.name as name

                FROM paket_internet 
                LEFT JOIN group_tvi on group_tvi.id = paket_internet.group_tvi_id  
                where group_tvi.project_id = $project->id and group_tvi.jenis_bayar = '$jenis_bayar'
        ");

        return $query->result_array();
    }

    public function getJenisPaketTV($jenis_bayar)
    {

        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT 
                   paket_tv.id,
                   paket_tv.name as name
            FROM paket_tv 
            LEFT JOIN group_tvi on group_tvi.id = paket_tv.group_tvi_id  
            where group_tvi.project_id = $project->id and group_tvi.jenis_bayar = '$jenis_bayar'
        ");

        return $query->result_array();
    }

    public function getJenisPaketTVI($jenis_bayar)
    {

        $project = $this->m_core->project();
        $query = $this
            ->db
            ->query("
            SELECT 
                   paket_tvi.id,
                   paket_tvi.name
            FROM paket_tvi 
            LEFT JOIN group_tvi on group_tvi.id = paket_tvi.group_tvi_id
            where group_tvi.project_id = $project->id and group_tvi.jenis_bayar = '$jenis_bayar'
        ");

        return $query->result_array();
    }
    
    public function getDetailPaket($jenis_paket,$jenis_paket_id){
        $nama_channel = null;
        $jenis_paket_flag = $jenis_paket==1?'paket_tv':$jenis_paket==2?'paket_internet':'paket_tvi';
        $result = $this->db ->select("*") 
                            ->from($jenis_paket_flag)
                            ->where("id",$jenis_paket_id)
                            ->get()->row();
        // var_dump($jenis_paket_id);
        // var_dump($this->db->last_query());
        if($jenis_paket == 1 || $jenis_paket == 3){
            $nama_channel = $this->db   ->select("name")
                                        ->from($jenis_paket_flag."_channel")
                                        ->join("channel",
                                                "channel.id = ".$jenis_paket_flag."_channel.channel_id")
                                        ->where($jenis_paket_flag."_channel.".$jenis_paket_flag."_id",$jenis_paket_id)
                                        ->get()->result();
                                                // var_dump($this->db->last_query());
            $str = array_map(function($a) { return $a->name; },$nama_channel);
            $str = implode(';',$str);
            $result->channel = $str;
        }
        return $result;
    }
    public function getPaket2($id)
    {
        $project = $this
            ->m_core
            ->project();
        $query = $this
            ->db
            ->query("
            SELECT 
                   paket_internet.name as paket_name,
                   paket_internet.harga_setelah_ppn as paket_harga_ppn,
                   paket_internet.biaya_pasang_baru as paket_biaya_pasang_baru,
                   paket_internet.biaya_registrasi as biaya_registrasi,
                   paket_internet.bandwidth as bandwidth,
                   paket_internet.description as paket_description,
                   paket_internet.id as paket_id,
                   group_tvi.jumlah_hari as jumlah_hari

            FROM paket_internet      
            join group_tvi  on paket_internet.group_tvi_id = group_tvi.id where  group_tvi.project_id = $project->id and paket_internet.id = $id 
            ");

        return $query->row();
    }

    public function save($dataTmp)
    {        
        $return = (object)[];
        $return->message = "Data Registrasi TVI Gagal di Tambah";
        $return->status = false;

        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $kode_tagihan = $project->code . "/TAGIHANTVI/" . date("Y") . "/" . 
                            $this->m_core->numberToRomanRepresentation(
                                $this->m_core->project()->id) . "/" . (
                                    $this->m_registrasi_tvi->last_id_tagihan() + 1);
        $nomor_registrasi = "CG/REGISTRASITVI/".date("Y")."/".
                            $this->m_core->numberToRomanRepresentation(
                                $this->m_core->project()->id)."/".(
                                    $this->m_registrasi_tvi->last_id());


        // if ($dataTmp['jenis_paket_id']==1) {
        //     $jenis_paket = explode("|", $dataTmp['jenis_paket_tvi_id']);
        // }elseif ($dataTmp['jenis_paket_id']==2) {
        //     $jenis_paket = explode("|", $dataTmp['jenis_paket_internet_id']);
        // }else{
        //     $jenis_paket = explode("|", $dataTmp['jenis_paket_tv_id']);
        // }

        $this->db->trans_start();

        $registrasiData = (object)[
            "jenis_pemasangan"      => $dataTmp['jenis_pemasangan'],
            "tanggal_document"      => date('Y-m-d',strtotime($dataTmp['tanggal_document'])),
            "harga_paket"           => $this->m_core->currency_to_number($dataTmp['harga_paket']) ,
            "diskon"                => $this->m_core->currency_to_number($dataTmp['diskon']),
            "total"                 => $this->m_core->currency_to_number($dataTmp['total']),
            "keterangan"            => $this->m_core->currency_to_number($dataTmp['keterangan']),
            "biaya_registrasi"      => $this->m_core->currency_to_number($dataTmp['harga_registrasi']),
            "nomor_registrasi"      => $nomor_registrasi,
            "status_tagihan"          => 0,
            "project_id"            => $project->id,
            "delete"                => 0,
            "active"                => 1,
            "tanggal_rencana_pemasangan"    => date('Y-m-d',strtotime($dataTmp['tanggal_rencana_pemasangan'])),
            "tanggal_rencana_aktifasi"      => date('Y-m-d',strtotime($dataTmp['tanggal_rencana_aktifasi'])),
            "tanggal_rencana_survei"      => date('Y-m-d',strtotime($dataTmp['tanggal_rencana_survei'])),
            "jenis_bayar"           => $dataTmp['jenis_bayar'],
            "jenis_paket_id"           => $dataTmp['jenis_paket_id'],
            "jenis_paket"           => $dataTmp['pilih_paket'],
            "status_dokumen"        => 0,
        ];
        if($dataTmp['is_unit']==1){
            $registrasiData->unit_id = $dataTmp['unit_id'];
        }else{
            $registrasiData->unit_virtual_id = $dataTmp['unit_virtual_id'];
        }
        $this->db->insert('t_tvi_registrasi', $registrasiData);
        $t_tvi_registrasi_id = $this->db->insert_id();

        $data_tagihan           = (object)[];
        if($dataTmp['is_unit']==1){
            $this->db->where('unit_id',$registrasiData->unit_id);   
        }else{
            $this->db->where('unit_virtual_id',$registrasiData->unit_virtual_id);   
        }

        $this->db->where('periode',date("Y-m-01"));
        $this->db->where('proyek_id',$project->id);                
        $tagihan_sudah_ada = $this->db->get('t_tagihan');
        
        if (!$tagihan_sudah_ada->num_rows()) {
            $data_tagihan->proyek_id                    = $project->id;
            if($dataTmp['is_unit']==1){
                $data_tagihan->unit_id                      = $registrasiData->unit_id;
            }else{
                $data_tagihan->unit_virtual_id                      = $registrasiData->unit_virtual_id;
            }
            $data_tagihan->periode                      = date("Y-m-01");
            $this->db->insert('t_tagihan',$data_tagihan);
            $t_tagihan_id = $this->db->insert_id();
        }else{
            $t_tagihan_id = $tagihan_sudah_ada->row()->id;
        }

        $tagihanTviData = [
            "kode_bill"             => $kode_tagihan,
            "periode"               => date('Y-m-d'),
            "total"                 => $this->m_core->currency_to_number($dataTmp['total']),
            "t_tvi_registrasi_id"   => $t_tvi_registrasi_id,
            "status_tagihan"          => 0,
            "tanggal_mulai"         => "",
            "tanggal_berakhir"      => "",
            "project_id"            => $project->id,
            "active"                => 1,
            "delete"                => 0,
            "flag_type"             => 1,
            "jenis_paket"           => $dataTmp['jenis_paket_id'],
            "flag_jenis"            => 1,
            "flag_biaya"            => 50000,
            "t_tagihan_id"          => $t_tagihan_id,
            "tipe"                  => 0
        ];

        $this->db->insert('t_tagihan_tvi',$tagihanTviData);
        $this->db->trans_complete();
        if($this->db->trans_status()!==false){
            $return->message = "Data Registrasi TVI Berhasil di Tambah";
            $return->status = $this->db->trans_status()===false ? false : true;
        }
        return $return;
        
    }

    public function last_id()
    {
        $query = $this->db->query(" SELECT TOP 1 id FROM t_tvi_registrasi ORDER by id desc ");
        return $query->row() ? $query->row()->id : 0;
    }

    public function last_id_tagihan()
    {
        $query = $this->db->query("SELECT TOP 1 id FROM t_tagihan_tvi ORDER by id desc");
        return $query->row() ? $query->row()->id : 0;
    }

    public function getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        return $this->db    ->select("
                                t_tvi_registrasi.id as id,
                                CASE 
                                    WHEN unit.id is null THEN unit_virtual.unit
                                    ELSE CONCAT(kawasan.name,' ',blok.name,'/',unit.no_unit)
                                END as unit,
                                customer.name as customer,
                                case when t_tvi_registrasi.jenis_pemasangan ='1' then 'Pemasangan Baru' else 'Perpanjangan Paket' end as jenis_layanan, 
                                t_tvi_registrasi.tanggal_document as tanggal_document,
                                t_tvi_registrasi.tanggal_rencana_pemasangan as tanggal_rencana_pemasangan,
                                t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                                t_tvi_registrasi.keterangan as keterangan,
                                t_tvi_registrasi.harga_paket as harga_paket,
                                t_tvi_registrasi.status_dokumen as status_dokumen,
                                t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                                t_tvi_registrasi.diskon  as diskon,
                                t_tvi_registrasi.total as total,
                                t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                                CASE
                                    WHEN t_tagihan_tvi.status_tagihan = 1 THEN
                                    'Lunas' 
                                    WHEN t_tagihan_tvi.status_tagihan = 2 THEN
                                    'Pemutihan' 
                                    WHEN t_tagihan_tvi.status_tagihan = 0 THEN
                                    'Tagihan'                   
                                END AS status_tagihan,

                                t_tvi_registrasi.active as active,
                                CASE
                                WHEN t_tvi_biaya_tambahan.status_tagihan = 1 THEN
                                'Lunas' 
                                WHEN t_tvi_biaya_tambahan.status_tagihan = 2 THEN
                                'Pemutihan' 
                                WHEN t_tvi_biaya_tambahan.status_tagihan = 0 THEN
                                'Tagihan'                   
                                END AS status_tagihan_biaya")
                            ->from("t_tvi_registrasi")
                            ->join("t_tagihan_tvi",
                                    "t_tagihan_tvi.t_tvi_registrasi_id = t_tvi_registrasi.id
                                    and t_tagihan_tvi.tipe = 0")
                            ->join("unit",
                                    "unit.id = t_tvi_registrasi.unit_id",
                                    "LEFT")
                            ->join("blok",
                                    "blok.id = unit.blok_id",
                                    "LEFT")
                            ->join("kawasan",
                                    "kawasan.id = blok.kawasan_id",
                                    "LEFT")
                            ->join("unit_virtual",
                                    "unit_virtual.id = t_tvi_registrasi.unit_virtual_id",
                                    "LEFT")
                            ->join("customer",
                                    "customer.id = unit.pemilik_customer_id
                                    or customer.id = unit_virtual.customer_id")
                            ->join("t_tvi_biaya_tambahan", 
                                    "t_tvi_biaya_tambahan.registrasi_id = t_tvi_registrasi.id",
                                    "Left")
                            ->where("t_tvi_registrasi.project_id",$project->id)
                            ->where("t_tvi_registrasi.delete",0)
                            ->where("t_tvi_registrasi.status_dokumen",0)
                            ->or_where("t_tvi_registrasi.status_dokumen >= 1")
                            ->order_by("t_tvi_registrasi.tanggal_document desc")
                            ->get()->result_array(); 
        // $query = $this->db->query("
        //    SELECT 
        //            t_tvi_registrasi.id as id,
        //            case when t_tvi_registrasi.jenis_pemasangan ='1' then 'Pemasangan Baru' else 'Perpanjangan Paket' end as jenis_layanan, 
        //            t_tvi_registrasi.tanggal_document as tanggal_document,
        //            t_tvi_registrasi.tanggal_rencana_pemasangan as tanggal_rencana_pemasangan,
        //            t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
        //            t_tvi_registrasi.keterangan as keterangan,
        //            t_tvi_registrasi.harga_paket as harga_paket,
        //            t_tvi_registrasi.status_dokumen as status_dokumen,
        //            t_tvi_registrasi.harga_lain_lain  as harga_pasang,
        //            t_tvi_registrasi.diskon  as diskon,
        //            t_tvi_registrasi.total as total,
        //            t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
        //            CASE
        //            WHEN t_tvi_registrasi.status_tagihan = 1 THEN
        //            'Lunas' 
        //            WHEN t_tvi_registrasi.status_tagihan = 2 THEN
        //            'Pemutihan' 
        //            WHEN t_tvi_registrasi.status_tagihan = 0 THEN
        //            'Tagihan'                   
        //            END AS status_tagihan,

        //            t_tvi_registrasi.active as active,
        //            CASE
        //            WHEN t_tvi_biaya_tambahan.status_tagihan = 1 THEN
        //            'Lunas' 
        //            WHEN t_tvi_biaya_tambahan.status_tagihan = 2 THEN
        //            'Pemutihan' 
        //            WHEN t_tvi_biaya_tambahan.status_tagihan = 0 THEN
        //            'Tagihan'                   
        //            END AS status_tagihan_biaya
        //     FROM t_tvi_registrasi
        //     left join t_tvi_biaya_tambahan on t_tvi_biaya_tambahan.registrasi_id = t_tvi_registrasi.id
        //     where  t_tvi_registrasi.[delete] = 0 
        //     and t_tvi_registrasi.project_id = $project->id 
        //     AND t_tvi_registrasi.[status_dokumen] = 0 
        //     OR t_tvi_registrasi.[status_dokumen] >= 1
        //     order by t_tvi_registrasi.tanggal_document desc 
            
        // ");
        // $result = $query->result_array();

        // return $result;
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
           
             SELECT 
                   t_tvi_registrasi.id as id,
                   t_tvi_registrasi.jenis_pemasangan as jenis_layanan,
                   t_tvi_registrasi.tanggal_document as tanggal_document,
                   t_tvi_registrasi.tanggal_rencana_pemasangan as tanggal_rencana_pemasangan,
                   t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                   t_tvi_registrasi.keterangan as keterangan,
                   t_tvi_registrasi.harga_paket as harga_paket,
                   t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                   t_tvi_registrasi.diskon  as diskon,
                   t_tvi_registrasi.total as total,
                   t_tvi_registrasi.status_tagihan as status_tagihan,
                   t_tvi_registrasi.active as active
                    
             
            FROM t_tvi_registrasi
          
            where 
            t_tvi_registrasi.project_id = $project->id and t_tvi_registrasi.id = $id
            order by t_tvi_registrasi.id desc 
            


            ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this
            ->m_core
            ->project();
        return $this->db ->select("
                                    t_tvi_registrasi.*,
                                    customer.id as customer_id,
                                    customer.name as customer_name,
                                    unit_virtual.unit as unit_virtual_name,
                                    concat(kawasan.name,'-',blok.name,'/',unit.no_unit) as unit_name
                                    ")
                            ->select("CASE 
                                        WHEN t_tvi_registrasi.jenis_paket = 1 THEN paket_tv.name
                                        WHEN t_tvi_registrasi.jenis_paket = 2 THEN paket_internet.name
                                        ELSE paket_tvi.name
                                    END as jenis_paket_name    
                                    ",false)
                            ->select("t_tvi_registrasi.jenis_paket as jenis_paket")
                            ->from("t_tvi_registrasi")
                            ->join("paket_tv",
                                    "paket_tv.id = t_tvi_registrasi.jenis_paket_id 
                                    and t_tvi_registrasi.jenis_paket = 1",
                                    "LEFT")
                            ->join("paket_internet",
                                    "paket_internet.id = t_tvi_registrasi.jenis_paket_id 
                                    and t_tvi_registrasi.jenis_paket = 2",
                                    "LEFT")
                            ->join("paket_tvi",
                                    "paket_tvi.id = t_tvi_registrasi.jenis_paket_id 
                                    and t_tvi_registrasi.jenis_paket = 3",
                                    "LEFT")
                            ->join("unit",
                                    "unit.id = t_tvi_registrasi.unit_id",
                                    "LEFT")
                            ->join("blok",
                                    "blok.id = unit.blok_id",
                                    "LEFT")
                            ->join("kawasan",
                                    "kawasan.id = blok.kawasan_id",
                                    "LEFT")
                            ->join("unit_virtual",
                                    "unit_virtual.id = t_tvi_registrasi.unit_virtual_id",
                                    "LEFT")
                            ->join("customer",
                                    "customer.id = unit.pemilik_customer_id
                                    or customer.id = unit_virtual.customer_id")
                            ->where("t_tvi_registrasi.id",$id)
                            ->get()->row();
        // $query = $this
        //     ->db
        //     ->query("
        
        // SELECT     project.name as project,
        //            kawasan.name as kawasan,
        //            blok.name as blok,
        //            unit.no_unit as unit,
        //            unit.id as unit_id,
        //            t_tvi_registrasi.customer_name as customer,
        //            t_tvi_registrasi.email as email,
        //            t_tvi_registrasi.nomor_handphone as no_hp,
        //            t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
        //            t_tvi_registrasi.nomor_telepon as telepon,
        //            t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
        //            CONVERT(varchar, t_tvi_registrasi.tanggal_document, 105) as tanggal_document,
        //            CONVERT(varchar, t_tvi_registrasi.tanggal_rencana_pemasangan, 105) as tanggal_rencana_pemasangan,
        //            CONVERT(varchar, t_tvi_registrasi.tanggal_aktifasi, 105) as tanggal_aktifasi,
        //            CONVERT(varchar, t_tvi_registrasi.tanggal_rencana_aktifasi, 105) as tanggal_rencana_aktifasi,
        //            CONVERT(varchar, t_tvi_registrasi.tanggal_pemasangan_berakhir, 105) as tanggal_pemasangan_berakhir,
        //            CONVERT(varchar, t_tvi_registrasi.tanggal_rencana_survei, 105) as tanggal_rencana_survei,
        //            t_tvi_registrasi.keterangan as keterangan,
        //            t_tvi_registrasi.harga_paket as harga_paket,
        //            t_tvi_registrasi.harga_lain_lain  as harga_pasang,
        //            t_tvi_registrasi.jenis_bayar as jenis_bayar,
        //            t_tvi_registrasi.biaya_registrasi  as biaya_registrasi,
        //            t_tvi_registrasi.diskon  as diskon,
        //            t_tvi_registrasi.total as total,
        //            paket_internet.id as internet,
        //             paket_internet.description as keterangan_paket,
        //             paket_internet.name as jenis_paket_internet,
        //             paket_internet.harga_jual as harga_paket_internet,
        //             paket_internet.biaya_pasang_baru as pemasangan_internet,
        //             paket_internet.biaya_registrasi as registrasi_internet,
        //             customer.id as customer_id,
        //             paket_internet.bandwidth as bandwidth_internet,
        //             paket_tv.id as tv,
        //             paket_tv.name as jenis_paket_tv,
        //             paket_tv.jml_channel as jml_channel,
        //             paket_tv.harga_jual as harga_paket_tv,
        //             paket_tv.biaya_pasang_baru as pemasangan_tv,
        //             paket_tv.biaya_registrasi as registrasi_tv,
        //             paket_tvi.id as tvi,
        //             paket_tvi.name as jenis_paket_tvi,
        //             paket_tvi.jml_channel as jml_channel_tvi,
        //             paket_tvi.bandwidth as bandwidth_tvi,
        //             paket_tvi.harga_jual as harga_paket_tvi,
        //             paket_tvi.biaya_pasang_baru as pemasangan_tvi,
        //             paket_tvi.biaya_registrasi as registrasi_tvi
        // FROM t_tvi_registrasi
        //     left join unit on unit.id = t_tvi_registrasi.unit_id
        //     left join blok on unit.blok_id = blok.id
        //     left join kawasan on blok.kawasan_id = kawasan.id
        //     left join project on project.id = kawasan.project_id
        //     left join customer on unit.pemilik_customer_id = customer.id 
        //     where 

        //     t_tvi_registrasi.id = $id and
        //     kawasan.project_id = $project->id  
            


        // ");
        $row = $query->row();
        return $row;
    }

    public function getSelectNonUnit($id)
    {
        $this
            ->load
            ->model('m_core');
        $project = $this
            ->m_core
            ->project();

        $query = $this
            ->db
            ->query("
        
                    SELECT 
                                
                                

                            t_tvi_registrasi.email as email,
                            t_tvi_registrasi.nomor_handphone as no_hp,
                            t_tvi_registrasi.nomor_registrasi as nomor_registrasi,
                            t_tvi_registrasi.nomor_telepon as telepon,


                            t_tvi_registrasi.id as id,
                            t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                            t_tvi_registrasi.tanggal_document as tanggal_document,
                            t_tvi_registrasi.tanggal_rencana_pemasangan as tanggal_rencana_pemasangan,
                            t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                            t_tvi_registrasi.keterangan as keterangan,
                            t_tvi_registrasi.harga_paket as harga_paket,
                            t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                            t_tvi_registrasi.biaya_registrasi  as biaya_registrasi,
                            t_tvi_registrasi.diskon  as diskon,
                            t_tvi_registrasi.total as total,
                            t_tvi_registrasi.status_tagihan as status_tagihan,
                            t_tvi_registrasi.active as active,
                            t_tvi_registrasi.unit as unit,


                                paket_internet.description as keterangan_paket,
                                paket_internet.name as jenis_paket,
                                paket_internet.bandwidth as bandwidth
                                
                        
                        FROM t_tvi_registrasi
                    
                        where 
                        t_tvi_registrasi.project_id = $project->id  and t_tvi_registrasi.id = $id
                        order by t_tvi_registrasi.id asc


        ");
        $row = $query->row();

        return $row;
    }

    public function get_log($id)
    {
        $this
            ->load
            ->model('m_core');
        $project = $this
            ->m_core
            ->project();

        $query = $this
            ->db
            ->query("
            SELECT 
                   project.name as project,
                   kawasan.name as kawasan,
                   blok.name as blok,
                   unit.no_unit as unit,
                   unit.id as unit_id,
                   customer.name as customer,
                   customer.email as email,
                   customer.mobilephone1 as no_hp,
                   customer.code as nomor_registrasi,
                   customer.homephone as telepon,



                   t_tvi_registrasi.jenis_pemasangan as jenis_pemasangan,
                   t_tvi_registrasi.tanggal_document as tanggal_document,
                   t_tvi_registrasi.tanggal_rencana_pemasangan as tanggal_rencana_pemasangan,
                   t_tvi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
                   t_tvi_registrasi.dokumen as dokumen,
                   t_tvi_registrasi.status_dokumen as status_dokumen,
                   t_tvi_registrasi.keterangan as keterangan,
                   t_tvi_registrasi.harga_paket as harga_paket,
                   t_tvi_registrasi.harga_lain_lain  as harga_pasang,
                   t_tvi_registrasi.diskon  as diskon,
                   t_tvi_registrasi.total as total,

                    paket_internet.description as keterangan_paket,
                    paket_internet.name as jenis_paket
                    
             
            FROM t_tvi_registrasi
            left join t_tagihan_tvi on t_tagihan_tvi.registrasi_id = t_tvi_registrasi.id
            left join unit on unit.no_unit = t_tvi_registrasi.unit
            left join customer on unit.pemilik_customer_id = customer.id
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            where 
            t_tvi_registrasi.id = $id and
            kawasan.project_id = $project->id  
            


        ");
        $row = $query->row();

        return $row;
    }

    public function get_log_tagihan($id)
    {
        $this
            ->load
            ->model('m_core');
        $project = $this
            ->m_core
            ->project();

        $query = $this
            ->db
            ->query("
           


            SELECT *
                   
                    
             
            FROM t_tagihan_tvi
            where t_tagihan_tvi.id = $id and t_tagihan_tvi.project_id = $project->id  
            


        ");
        $row = $query->row();

        return $row;
    }

    public function edit($dataTmp)
    {
        $this
            ->load
            ->model('m_core');
        $this
            ->load
            ->model('m_log');
        $project = $this
            ->m_core
            ->project();
        $user_id = $this
            ->m_core
            ->user_id();

        $paketTV = explode('|', $dataTmp['jenis_paket_tv_id']);
        $paketTVI = explode('|', $dataTmp['jenis_paket_tvi_id']);
        $paketInet = explode('|', $dataTmp['jenis_paket_internet_id']);

        $this
            ->db
            ->where('project_id', $project->id);
        $this
            ->db
            ->from('t_tvi_registrasi');
        // echo("<pre>");
        //     print_r($dataTmp);
        // echo("</pre>");
        $dataUnit = [

            'jenis_pemasangan' => $dataTmp['jenis_pemasangan'], 'nomor_registrasi' => $dataTmp['nomor_registrasi'], 'tanggal_document' => $dataTmp['tanggal_document'], 'tanggal_rencana_pemasangan' => $dataTmp['tanggal_rencana_pemasangan'], 'jenis_paket_tv_id' => $paketTV[0], 'jenis_paket_tvi_id' => $paketTVI[0], 'jenis_paket_internet_id' => $paketInet[0], 'harga_paket' => $this
                ->m_core
                ->currency_to_number($dataTmp['harga_paket']), 'harga_lain_lain' => $this
                ->m_core
                ->currency_to_number($dataTmp['harga_pasang']), 'biaya_registrasi' => $this
                ->m_core
                ->currency_to_number($dataTmp['biaya_registrasi']), 'diskon' => $dataTmp['diskon'], 'total' => $this
                ->m_core
                ->currency_to_number($dataTmp['total']), 'keterangan' => $dataTmp['keterangan'], 'status_tagihan' => 0, 'active' => 0, 'delete' => 0,

        ];

        // validasi apakah user dengan project $project boleh edit data ini
        if (
            $this
            ->db
            ->count_all_results() != 0
        ) {

            $this
                ->db
                ->where('nomor_registrasi', $dataTmp['nomor_registrasi'])->where('id !=', $dataTmp['id']);
            $this
                ->db
                ->from('t_tvi_registrasi');
            // validasi double
            if (
                $this
                ->db
                ->count_all_results() == 0
            ) {

                $before = $this->get_log($dataTmp['id']);
                $this
                    ->db
                    ->where('id', $dataTmp['id']);
                $this
                    ->db
                    ->update('t_tvi_registrasi', $dataUnit);
                $after = $this->get_log($dataTmp['id']);

                $id = $dataTmp['id'];

                // echo '<PRE>';
                // print_r($id);
                // echo '<PRE>';


                $query = $this
                    ->db
                    ->query("
           


                SELECT TOP 1 id
                  
                   
            
               FROM t_tagihan_tvi
               where t_tagihan_tvi.registrasi_id = $id and t_tagihan_tvi.project_id = $project->id   
               order by id desc 
           


               ");

                $row = $query->row();

                if (isset($row)) {

                    $tagihan_id = $row->id;
                }

                $dataTagihan = [

                    'project_id' => $project->id, 'tanggal' => $dataTmp['tanggal_document'], 'jenis_paket_tv_id' => $paketTV[0], 'jenis_paket_tvi_id' => $paketTVI[0], 'jenis_paket_internet_id' => $paketInet[0], 'total' => $this
                        ->m_core
                        ->currency_to_number($dataTmp['total']), 'registrasi_id' => $dataTmp['id'], 'status_tagihan' => 0, 'active' => 1, 'delete' => 0,
                ];

                $beforeTagihan = $this->get_log_tagihan($tagihan_id);
                $this
                    ->db
                    ->where('id', $tagihan_id);
                $this
                    ->db
                    ->update('t_tagihan_tvi', $dataTagihan);
                $afterTagihan = $this->get_log_tagihan($tagihan_id);

                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                $diff2 = (object) (array_diff_assoc((array) $afterTagihan, (array) $beforeTagihan));
                $tmpDiff2 = (array) $diff2;

                if ($tmpDiff) {
                    $this
                        ->m_log
                        ->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);

                    return 'success';
                } else if ($tmpDiff2) {

                    $this
                        ->m_log
                        ->log_save('t_tagihan_tvi', $tagihan_id, 'Edit', $diff2);

                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
                }
            } else {
            }
        }
    }

    public function edit_non_unit($dataTmp)
    {

        $this
            ->load
            ->model('m_core');
        $this
            ->load
            ->model('m_log');
        $project = $this
            ->m_core
            ->project();
        $user_id = $this
            ->m_core
            ->user_id();
        $paketTV = explode('|', $dataTmp['jenis_paket_tv_id']);
        $paketTVI = explode('|', $dataTmp['jenis_paket_tvi_id']);
        $paketInet = explode('|', $dataTmp['jenis_paket_internet_id']);

        $dataNonUnit = ['jenis_pemasangan' => $dataTmp['jenis_pemasangan'], 'nomor_registrasi' => $dataTmp['nomor_registrasi2'], 'project_id' => $project->id, 'tanggal_document' => $dataTmp['tanggal_document'], 'jenis_paket_tv_id' => $paketTV[0], 'jenis_paket_tvi_id' => $paketTVI[0], 'jenis_paket_internet_id' => $paketInet[0], 'harga_paket' => $this
            ->m_core
            ->currency_to_number($dataTmp['harga_paket']), 'harga_lain_lain' => $this
            ->m_core
            ->currency_to_number($dataTmp['harga_pasang']), 'biaya_registrasi' => $this
            ->m_core
            ->currency_to_number($dataTmp['biaya_registrasi']), 'diskon' => $this
            ->m_core
            ->currency_to_number($dataTmp['diskon']), 'total' => $this
            ->m_core
            ->currency_to_number($dataTmp['total']), 'keterangan' => $dataTmp['keterangan'], 'status_tagihan' => 0, 'active' => 0, 'delete' => 0,];

        $this
            ->db
            ->where('nomor_registrasi', $dataTmp['nomor_registrasi2'])->where('id', $dataTmp['id']);
        $this
            ->db
            ->from('t_tvi_registrasi');

        // echo '<PRE>';
        // print_r($this->db->count_all_results());
        // echo '</PRE>';
        // validasi double
        if (
            $this
            ->db
            ->count_all_results() > 0
        ) {

            $before = $this->get_log($dataTmp['id']);
            $this
                ->db
                ->where('id', $dataTmp['id']);
            $this
                ->db
                ->update('t_tvi_registrasi', $dataNonUnit);
            $after = $this->get_log($dataTmp['id']);

            $id = $dataTmp['id'];

            $query = $this
                ->db
                ->query("
           


            SELECT TOP 1 id
              
               
        
           FROM t_tagihan_tvi
           where t_tagihan_tvi.registrasi_id = $id and t_tagihan_tvi.project_id = $project->id   
           order by id desc 
       


           ");

            $row = $query->row();

            if (isset($row)) {

                $tagihan_id = $row->id;
            }

            $dataTagihan = [

                'project_id' => $project->id, 'tanggal' => $dataTmp['tanggal_document'], 'jenis_paket_tv_id' => $paketTV[0], 'jenis_paket_tvi_id' => $paketTVI[0], 'jenis_paket_internet_id' => $paketInet[0], 'total' => $this
                    ->m_core
                    ->currency_to_number($dataTmp['total']), 'registrasi_id' => $id, 'status_tagihan' => 0, 'active' => 1, 'delete' => 0,
            ];

            $beforeTagihan = $this->get_log_tagihan($tagihan_id);
            $this
                ->db
                ->where('id', $tagihan_id);
            $this
                ->db
                ->update('t_tagihan_tvi', $dataTagihan);
            $afterTagihan = $this->get_log_tagihan($tagihan_id);

            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            $diff2 = (object) (array_diff_assoc((array) $afterTagihan, (array) $beforeTagihan));
            $tmpDiff2 = (array) $diff2;

            if ($tmpDiff) {
                $this
                    ->m_log
                    ->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else if ($tmpDiff2) {

                $this
                    ->m_log
                    ->log_save('t_tagihan_tvi', $tagihan_id, 'Edit', $diff2);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }

            echo '<PRE>';
            print_r($tmpDiff);
            echo '</PRE>';

            echo '<PRE>';
            print_r($tmpDiff2);
            echo '</PRE>';
        } else {
            return 'double';
        }
    }

    public function delete($dataTmp)
    {
        $this
            ->load
            ->model('m_core');
        $this
            ->load
            ->model('m_log');
        $project = $this
            ->m_core
            ->project();
        $user_id = $this
            ->m_core
            ->user_id();

        $this
            ->db
            ->where('project_id', $project->id);
        $this
            ->db
            ->from('t_tvi_registrasi');

        // validasi apakah user dengan project $project boleh edit data ini
        if (
            $this
            ->db
            ->count_all_results() != 0
        ) {
            $before = $this->get_log($dataTmp['id']);
            $this
                ->db
                ->where('id', $dataTmp['id']);
            $this
                ->db
                ->update('t_tvi_registrasi', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this
                    ->m_log
                    ->log_save('t_tvi_registrasi', $dataTmp['id'], 'Edit', $diff);
                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }

    public function upload($dataTmp)
    {
        $this
            ->load
            ->model('m_core');
        $this
            ->load
            ->model('m_log');
        $project = $this
            ->m_core
            ->project();

        $this
            ->db
            ->where('project_id', $project->id);
        $this
            ->db
            ->from('t_tvi_registrasi');
        $data = ['status_dokumen' => 1, 'dokumen' => $dataTmp['dokumen']];
        $this
            ->db
            ->where('id', $dataTmp['id']);
        $this
            ->db
            ->update('t_tvi_registrasi', $data);
        return 'success';
    }

    public function saveTagihan($unit_id,$project_id){
        $t_tagihan_id = '';
        $data_tagihan           = (object)[];
        $this->db->where('unit_id',$unit_id);            
        $this->db->where('periode',date("Y-m-01"));
        $this->db->where('proyek_id',$project_id);                
        $tagihan_sudah_ada = $this->db->get('t_tagihan');
        
        if (!$tagihan_sudah_ada->num_rows()) {
            $data_tagihan->proyek_id                    = $project_id;
            $data_tagihan->unit_id                      = $unit_id;
            $data_tagihan->periode                      = date("Y-m-01");
            $this->db->insert('t_tagihan',$data_tagihan);
            $t_tagihan_id = $this->db->insert_id();
        }else{
            $t_tagihan_id = $tagihan_sudah_ada->row()->id;
        }
        return $t_tagihan_id;

    }
}
