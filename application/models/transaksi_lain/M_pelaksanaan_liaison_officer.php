<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_pelaksanaan_liaison_officer extends CI_Model
{
    public function get()
    {
        $query = $this->db->query('
            SELECT * FROM cara_pembayaran
        ');

        return $query->result_array();
    }

    public function get_kategori()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            id,
            kode,
            nama
        FROM kategori_loi
            WHERE project_id = $project->id and active = 1
        ");
        return $query->result_array();
    }

    public function get_jenis()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db
                            ->select("jenis_loi.*")
                            ->from("jenis_loi")
                            ->join("kategori_loi",
                                    "kategori_loi.id = jenis_loi.kategori_loi_id","LEFT")
                            ->where("kategori_loi.project_id",$project->id);
        return $query->get()->result_array();
    }

    public function get_peruntukan()
    {
        $query = $this->db
                            ->select("peruntukan_loi.*")
                            ->from("peruntukan_loi")
                            ->join("jenis_loi",
                                    "jenis_loi.id = peruntukan_loi.jenis_loi_id","LEFT")
                            ->where("peruntukan_loi.delete",0);
        return $query->get()->result_array();
    }

    public function get_paket()
    {
        $query = $this->db
                            ->select("paket_loi.*")
                            ->from("paket_loi")
                            ->join("jenis_loi",
                                    "jenis_loi.id = paket_loi.jenis_loi_id", "LEFT")
                            ->where("paket_loi.delete",0);
        return $query->get()->result_array();
    }

    public function getPeruntukan($jenis)
    {
        $query = $this->db
                            ->select("peruntukan_loi.*")
                            ->from("peruntukan_loi")
                            ->join("jenis_loi",
                                    "jenis_loi.id = peruntukan_loi.jenis_loi_id","LEFT")
                            ->where("peruntukan_loi.jenis_loi_id",$jenis);
        return $query->get()->result();
    }

    public function getPaket($jenis)
    {
        $query = $this->db
                            ->select("paket_loi.*")
                            ->from("paket_loi")
                            ->join("jenis_loi",
                                    "jenis_loi.id = paket_loi.jenis_loi_id","LEFT")
                            ->where("paket_loi.jenis_loi_id",$jenis);
        return $query->get()->result();
    }

    public function getUnit()
    {
        $project = $this->m_core->project();
        $query = $this->db
                            ->select("project.name as project_name,
                                    kawasan.name as kawasan_name,
                                    blok.name as blok_name,
                                    unit.*")
                            ->from("unit")
                            ->join("blok",
                                    "unit.blok_id = blok.id","LEFT")
                            ->join("kawasan",
                                    "blok.kawasan_id = kawasan.id","LEFT")
                            ->join("project",
                                    "project.id = kawasan.project_id","LEFT")
                            ->where("kawasan.project_id",$project->id);

        return $query->get()->result_array();
    }


    public function getUnit2($id)
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT project.name as project_name,
                   kawasan.name as kawasan_name,
                   blok.name as blok_name,
                   unit.luas_bangunan as luas_bangunan,
                   unit.luas_tanah as luas_tanah,
                   unit.unit_type as unit_type,
                   unit.tgl_st as tanggal_st,
                   unit.no_unit as no_unit,
                   customer.name as customer_name,
                   customer.address as customer_address,
                   customer.code as customer_code,
                   customer.homephone as customer_homephone,
                   customer.mobilephone1 as customer_mobilephone,
                   customer.email as customer_email

            FROM unit 
            left join blok on unit.blok_id = blok.id
            left join kawasan on blok.kawasan_id = kawasan.id
            left join project on project.id = kawasan.project_id 
            left join customer on customer.id = unit.pemilik_customer_id
            where kawasan.project_id = $project->id and unit.id = $id
            ");
        return $query->row();
    }

    public function getJenisService()
    {
        $query = $this->db->query('
            SELECT * FROM liaison
        ');
        return $query->result_array();
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        // echo("<pre>");
        //     print_r($dataTmp);
        // echo("</pre>");
        $tanggal_instalasi =  $dataTmp['tanggal_instalasi'];
        $oldDate1 = $tanggal_instalasi;
        $arr = explode('-', $oldDate1);
        //  $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];
        $tanggal_instalasi2 = $arr[2].'-'.$arr[1].'-'.$arr[0];

        $dataRegister =
        [
            'nomor_registrasi' => $dataTmp['nomor_registrasi'],
            'status_dokumen' => 4
        ];
        $this->db->where('id', $dataTmp['id']);
        $this->db->update('t_loi_registrasi', $dataRegister);

        $dataInstalasi =
        [  
            't_loi_registrasi_id' => $dataTmp['id'],
            'bukti_instalasi' => $dataTmp['bukti_instalasi'],
            'tanggal_instalasi' => $tanggal_instalasi2,
            'keterangan_instalasi' => $dataTmp['keterangan_instalasi'],
            'hasil_instalasi' => $dataTmp['hasil_instalasi'],
            'delete' => 0,
        ];
        
        $this->db->insert('t_loi_instalasi', $dataInstalasi);
        $id = $this->db->insert_id();

        $dataLog = $this->get_log($id);
        $this->m_log->log_save('t_loi_instalasi', $id, 'Tambah', $dataLog);
        return 'success';
    }

    public function getAll()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db
                            ->select("t_loi_registrasi.*,
                                    paket_loi.nama as nama_paket")
                            ->from("t_loi_registrasi")
                            ->join("paket_loi",
                                    "paket_loi.id = t_loi_registrasi.paket_loi_id", "LEFT")
                            ->where("t_loi_registrasi.project_id",$project->id)
                            ->where("t_loi_registrasi.delete",0)
                            ->where("t_loi_registrasi.status_dokumen",3)
                            ->or_where("t_loi_registrasi.status_dokumen",7)
                            ->or_where("t_loi_registrasi.status_dokumen",6)
                            ->or_where("t_loi_registrasi.status_dokumen",4)
                            ->or_where("t_loi_registrasi.status_dokumen",5)
                            ->get()->result_array();
        return $query;
    }

    public function get_all_pt_coa()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT 
            coa_mapping.id,
            pt.name as pt_name,
            coa.description as coa_name,
            coa.code as coa_code
        FROM coa_mapping
            JOIN coa ON coa.id = coa_mapping.coa_id
            JOIN pt ON pt.id = coa_mapping.pt_id
        WHERE coa_mapping.project_id = $project->id
        ");

        return $query->result_array();
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT * 
            FROM cara_pembayaran 
            JOIN coa_mapping ON coa_mapping.id = cara_pembayaran.coa_mapping_id
            WHERE cara_pembayaran.id = $id 
            AND coa_mapping.project_id = $project->id        
            ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }

    public function getSelect($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
        SELECT     
            project.name as project,
            kawasan.name as kawasan,
            blok.name as blok,
            unit.no_unit as unit,
            unit.id as unit_id,
            t_loi_registrasi.customer_name as customer,
            t_loi_registrasi.email as email,
            t_loi_registrasi.nomor_handphone as no_hp,
            t_loi_registrasi.nomor_registrasi as nomor_registrasi,
            t_loi_registrasi.nomor_telepon as telepon,
            CONVERT(varchar, t_loi_registrasi.tgl_document, 105) as tanggal_document,
            CONVERT(varchar, t_loi_registrasi.tanggal_rencana_pemasangan, 105) as tanggal_rencana_pemasangan,
            CONVERT(varchar, t_loi_registrasi.tanggal_rencana_aktifasi, 105) as tanggal_rencana_aktifasi,
            CONVERT(varchar, t_loi_registrasi.tanggal_rencana_survei,105) as tanggal_rencana_survei,
            CONVERT(varchar, t_loi_survei.tanggal_survei,105) as tanggal_survei,
            t_loi_registrasi.tanggal_pemasangan_berakhir as tanggal_pemasangan_berakhir,
            t_loi_registrasi.keterangan as keterangan,
            t_loi_registrasi.harga_paket as harga_paket,
            t_loi_registrasi.diskon  as diskon,
            t_loi_registrasi.luaslama as luaslama,
            t_loi_registrasi.luasbaru as luasbaru,
            t_loi_registrasi.total as total,
            kategori_loi.id as kategori,
            jenis_loi.id as jenis,
            peruntukan_loi.id as peruntukan,
            paket_loi.id as paket,
            customer.id as customer_id,
            paket_loi.kode as kode_paket,
            paket_loi.biaya_registrasi as biaya_registrasi,
            paket_loi.harga_jasa as harga_jasa,
            paket_loi.biaya_prakiraan as biaya_prakiraan,
            paket_loi.keterangan as keterangan,
            SUM(paket_loi.biaya_registrasi+paket_loi.harga_jasa+paket_loi.biaya_prakiraan) as total_paket
        FROM t_loi_registrasi
        left join kategori_loi on kategori_loi.id = t_loi_registrasi.kategori_loi_id
        left join jenis_loi on jenis_loi.id = t_loi_registrasi.jenis_loi_id
        left join peruntukan_loi on peruntukan_loi.id = t_loi_registrasi.peruntukan_loi_id
        left join paket_loi on paket_loi.id = t_loi_registrasi.paket_loi_id
        left join t_loi_survei on t_loi_survei.t_loi_registrasi_id = t_loi_registrasi.id
        left join unit on unit.id = t_loi_registrasi.unit_id
        left join blok on unit.blok_id = blok.id
        left join kawasan on blok.kawasan_id = kawasan.id
        left join project on project.id = kawasan.project_id
        left join customer on unit.pemilik_customer_id = customer.id
        where 
        t_loi_registrasi.id = $id and
        kawasan.project_id = $project->id
        GROUP BY 
        project.name,
            kawasan.name,
            blok.name,
            unit.no_unit,
            unit.id,
            t_loi_registrasi.customer_name,
            t_loi_registrasi.email,
            t_loi_registrasi.nomor_handphone ,
            t_loi_registrasi.nomor_registrasi,
            t_loi_registrasi.nomor_telepon,
            CONVERT(varchar, t_loi_registrasi.tgl_document, 105),
            CONVERT(varchar, t_loi_registrasi.tanggal_rencana_pemasangan, 105),
            CONVERT(varchar, t_loi_registrasi.tanggal_rencana_aktifasi, 105),
            CONVERT(varchar, t_loi_registrasi.tanggal_rencana_survei,105),
            CONVERT(varchar, t_loi_survei.tanggal_survei,105),
            t_loi_registrasi.tanggal_pemasangan_berakhir,
            t_loi_registrasi.keterangan,
            t_loi_registrasi.harga_paket,
            t_loi_registrasi.diskon,
            t_loi_registrasi.luaslama,
            t_loi_registrasi.luasbaru,
            t_loi_registrasi.total,
            kategori_loi.id,
            jenis_loi.id,
            peruntukan_loi.id,
            paket_loi.id,
            customer.id,
            paket_loi.kode,
            paket_loi.keterangan,
            paket_loi.biaya_registrasi,
            paket_loi.harga_jasa,
            paket_loi.biaya_prakiraan
        ");
        $row = $query->row();
        return $row;
    }

    public function get_item_charge($id)
    {
        $query = $this->db->query("
            SELECT 
            *
            FROM item_charge
            WHERE t_loi_registrasi_id = $id
            order by id asc
        ");
		return $query->result_array();
		
	}

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                cara_pembayaran.code as Kode,
                cara_pembayaran.name as Nama,
                cara_pembayaran.biaya_admin as [Biaya Admin],
                cara_pembayaran.description as Deskripsi,
                case when cara_pembayaran.active    = 0 then 'Tidak Aktif' else 'Aktif' end as Aktif, 
                case when cara_pembayaran.[delete]  = 0 then 'Tidak Aktif' else 'Aktif' end as [Delete], 
                pt.name as [Nama PT], 
                coa.code as [Kode COA], 
                coa.description as [Nama COA], 
                coa_mapping.id as [Id Mapping COA] 
            FROM cara_pembayaran
            join coa_mapping ON coa_mapping.id = cara_pembayaran.coa_mapping_id
            join pt on pt.id            = coa_mapping.pt_id
            join coa on coa.id          = coa_mapping.coa_id
            WHERE cara_pembayaran.id        = $id
            AND coa_mapping.project_id  = $project->id
        ");
        $row = $query->row();

        return $row;
    }

    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $this->db->join('coa_mapping', 'coa_mapping.id = cara_pembayaran.coa_mapping_id');
        $this->db->where('coa_mapping.project_id', $project->id);
        $this->db->from('cara_pembayaran');

        $data =
        [
            'code' => $dataTmp['code'],
            'name' => $dataTmp['jenis_pembayaran'],
            'biaya_admin' => $this->m_core->currency_to_number($dataTmp['biaya_admin']),
            'coa_mapping_id' => $dataTmp['coa'],
            'description' => $dataTmp['keterangan'],
            'active' => $dataTmp['active'] ? 1 : 0,
        ];
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $this->db->where('code', $data['code'])->where('id !=', $dataTmp['id']);
            $this->db->from('cara_pembayaran');
            // validasi double
            if ($this->db->count_all_results() == 0) {
                $before = $this->get_log($dataTmp['id']);
                $this->db->where('id', $dataTmp['id']);
                $this->db->update('cara_pembayaran', $data);
                $after = $this->get_log($dataTmp['id']);

                $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                $tmpDiff = (array) $diff;

                if ($tmpDiff) {
                    $this->m_log->log_save('cara_pembayaran', $dataTmp['id'], 'Edit', $diff);

                    return 'success';
                } else {
                    return 'Tidak Ada Perubahan';
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

        $this->db->join('coa_mapping', 'coa_mapping.id = cara_pembayaran.coa_mapping_id');
        $this->db->where('coa_mapping.project_id', $project->id);
        $this->db->from('cara_pembayaran');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('cara_pembayaran', ['delete' => 1]);
            $after = $this->get_log($dataTmp['id']);

            $diff = (object) (array_diff((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('cara_pembayaran', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            } else {
                return 'Tidak Ada Perubahan';
            }
        }
    }

    public function last_id()
    {
        $query = $this->db->query("
            SELECT TOP 1 id FROM t_loi_registrasi
            ORDER by id desc
        ");
        return $query->row() ? $query->row()->id : 0;
    }
}
