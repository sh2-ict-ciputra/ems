<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_sub_golongan extends CI_Model
{
    public function get()
    {
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT 
                sub_golongan.*,
                jenis_golongan.code as gol_code,
                case
                    when sub_golongan.range_flag = 2 then 'AIR'
                    when sub_golongan.range_flag = 1 then 'Lingkungan'
                    else 'Listrik'
                end as jenis_service,
                case
                    when sub_golongan.range_flag = 2 then range_air.code
                    when sub_golongan.range_flag = 1 then range_lingkungan.code
                    else range_listrik.code
                end as range_code
            FROM sub_golongan
            JOIN jenis_golongan
                on jenis_golongan.id = sub_golongan.jenis_golongan_id
            LEFT JOIN range_air 
                on range_air.id = sub_golongan.range_id
            LEFT JOIN range_lingkungan
                on range_lingkungan.id = sub_golongan.range_id
            LEFT JOIN range_listrik
                on range_listrik.id = sub_golongan.range_id
            WHERE jenis_golongan.project_id = $project->id and sub_golongan.[delete] = 0
            order by sub_golongan.id DESC
        ");

        return $query->result_array();
    }

    public function cek($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                * 
            FROM sub_golongan
            JOIN jenis_golongan
                ON jenis_golongan.id = sub_golongan.jenis_golongan_id
            WHERE sub_golongan.id = $id
            AND jenis_golongan.project_id = $project->id
        ");
        $row = $query->row();

        return isset($row) ? 1 : 0;
    }
    // public function cek($id)
    // {
    //     $this->load->model('m_core');
    //     $project = $this->m_core->project();

    //     $query = $this->db->query("
    //         SELECT 
    //             * 
    //         FROM sub_golongan
    //         JOIN jenis_golongan
    //             ON jenis_golongan.id = sub_golongan.jenis_golongan_id
    //         WHERE sub_golongan.id = $id
    //         AND jenis_golongan.project_id = $project->id
    //     ");
    //     $row = $query->row();

    //     return isset($row) ? 1 : 0;
    // }
    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                sub_golongan.code as [Code],
                sub_golongan.name as [Name],
                sub_golongan.minimum_pemakaian as [Minimum Pemakaian],
                sub_golongan.minimum_rp as [Minimum Rp],
                sub_golongan.administrasi as [Administrasi],
                sub_golongan.description as [Description],
                jenis_golongan.code as gol_code,
                case
                    when sub_golongan.range_flag = 1 then 'AIR'
                    when sub_golongan.range_flag = 2 then 'Lingkungan'
                    else 'Listrik'
                end as [Jenis Service] ,
                case
                    when sub_golongan.range_flag = 1 then range_air.code
                    when sub_golongan.range_flag = 2 then range_lingkungan.code
                    else range_listrik.code
                end as [Range Code],
                case
                    when sub_golongan.active = 1 then 'Aktif'
                    else 'Tidak Aktif'
                end as Aktif,
                isnull(pemeliharaan_air.name,'') as [Pemeliharaan Air]
            FROM sub_golongan
            JOIN jenis_golongan
                on jenis_golongan.id = sub_golongan.jenis_golongan_id
            LEFT JOIN pemeliharaan_air
                ON pemeliharaan_air.id = sub_golongan.pemeliharaan_air_id
            LEFT JOIN range_air 
                on range_air.id = sub_golongan.range_id
            LEFT JOIN range_lingkungan
                on range_lingkungan.id = sub_golongan.range_id
            LEFT JOIN range_listrik
                on range_listrik.id = sub_golongan.range_id
            WHERE jenis_golongan.project_id = $project->id
            AND sub_golongan.id = $id
        ");
        $row = $query->row();

        return $row;
    }

    public function get_select($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();

        $query = $this->db->query("
            SELECT 
                sub_golongan.*
            FROM sub_golongan
            JOIN jenis_golongan
                ON jenis_golongan.id = sub_golongan.jenis_golongan_id
            WHERE sub_golongan.id = $id
            AND jenis_golongan.project_id = $project->id
        ");

        return $query->row();
    }

    public function get_golongan()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            id,
            description
        FROM jenis_golongan
            WHERE project_id = $project->id and active = 1
        ");

        return $query->result_array();
    }

    public function get_service()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db
                            ->select("service_jenis_id as id,
                                    code")
                            ->from('service')
                            ->where('project_id',$project->id)
                            ->where('active',1)
                            ->where_in('service_jenis_id',[1,2])
                            ->get();
        return $query->result_array();
    }

    public function get_range_air()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
            SELECT
                id,
                name,
                code
            FROM range_air
            WHERE project_id = $project->id and active = 1
        ");

        return $query->result_array();
    }

    public function get_range_lingkungan()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            id,
            name,
            code
        FROM range_lingkungan
            WHERE project_id = $project->id and active = 1
        ");

        return $query->result_array();
    }

    public function get_range_listrik()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            id,
            name,
            code
        FROM range_listrik
            WHERE project_id = $project->id and active = 1
        ");

        return $query->result_array();
    }

    public function get_range_air_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            id,
            range_air_id,
            range_awal,
            range_akhir,
			harga_hpp,
			harga

        FROM range_air_detail where range_air_id = $id

        ");

        return $query->result_array();
    }

    public function get_range_lingkungan_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            id,
            range_lingkungan_id,
            range_awal,
            range_akhir,
			harga_hpp,
			harga

        FROM range_lingkungan_detail where range_lingkungan_id = $id

        ");

        return $query->result_array();
    }

    public function get_range_listrik_detail($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $query = $this->db->query("
        SELECT
            id,
            range_listrik_id,
            range_awal,
            range_akhir,
			harga_hpp,
			harga

        FROM range_listrik_detail where range_listrik_id = $id

        ");

        return $query->result_array();
    }

    public function save($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();

        $query = $this->db->query('
            SELECT 
            id
            FROM service
            WHERE service_jenis_id = '.$dataTmp['range_flag']."
            AND project_id = $project->id
        ");
        $dataTmp['service_id'] = $query->row()->id;
        $data =
            [
            'jenis_golongan_id' => $dataTmp['golongan'],
            'code' => $dataTmp['code'],
            'name' => $dataTmp['nama_sub'],
            'minimum_pemakaian' => $this->m_core->currency_to_number($dataTmp['minimum_pemakaian']),
            'minimum_rp' => $this->m_core->currency_to_number($dataTmp['nilai_minimum']),
            'administrasi' => $this->m_core->currency_to_number($dataTmp['administrasi']),
            'service_id' => $dataTmp['service_id'],
            'range_flag' => $dataTmp['range_flag'],
            'range_id' => $dataTmp['range_id'],
            'description' => $dataTmp['keterangan'],
            'active' => 1,
            'delete' => 0,
            'pemeliharaan_air_id' => $dataTmp['pemeliharaan_air_id']
        ];

        $this->db->where('code', $data['code'])
                 ->where('service_id', $data['service_id'])
                 ->where('range_id', $data['range_id'])
                 ->where('jenis_golongan_id', $data['jenis_golongan_id']);
        $this->db->from('sub_golongan');

        // validasi double
        if ($this->db->count_all_results() == 0) {
            $this->db->insert('sub_golongan', $data);

            $idTMP = $this->db->insert_id();
            $dataLog = $this->get_log($idTMP);
            $this->m_log->log_save('sub_golongan', $idTMP, 'Tambah', $dataLog);

            return 'success';
        } else {
            return 'double';
        }
    }

    public function edit($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();
        
        $dataTmp['service_id'] = $this->db->select("id")->from("service")->where("project_id",$project->id)->where("service_jenis_id",2)->get()->row()->id;
        $data =
        [
            'jenis_golongan_id' => $dataTmp['golongan'],
            'code' => $dataTmp['code'],
            'name' => $dataTmp['nama_sub'],
            'minimum_pemakaian' => $this->m_core->currency_to_number($dataTmp['minimum_pemakaian']),
            'minimum_rp' => $this->m_core->currency_to_number($dataTmp['nilai_minimum']),
            'administrasi' => $this->m_core->currency_to_number($dataTmp['administrasi']),
            'service_id' => $dataTmp['service_id'],
            'range_flag' => $dataTmp['range_flag'],
            'range_id' => $dataTmp['range_id'],
            'description' => $dataTmp['keterangan'],
            'active' => $dataTmp['active'],
            'delete' => 0,
            'pemeliharaan_air_id' => $dataTmp['pemeliharaan_air_id']

        ];
        
        // $this->db->join('jenis_golongan', 'jenis_golongan.id = sub_golongan.jenis_golongan_id');
        // $this->db->where('jenis_golongan.project_id', $project->id);
        // $this->db->from('sub_golongan');

        $this->db->join('jenis_golongan', 'jenis_golongan.id = sub_golongan.jenis_golongan_id');
        $this->db->from('sub_golongan')->where('sub_golongan.id',$dataTmp['id'])->where('jenis_golongan.project_id',$project->id);
        
        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
            $before = $this->get_log($dataTmp['id']);
            $this->db->where('id', $dataTmp['id']);
            $this->db->update('sub_golongan', $data);
            $after = $this->get_log($dataTmp['id']);
            $diff = (object) (array_diff_assoc((array) $after, (array) $before));
            $tmpDiff = (array) $diff;

            if ($tmpDiff) {
                $this->m_log->log_save('sub_golongan', $dataTmp['id'], 'Edit', $diff);

                return 'success';
            }
        }

        return 'gagal';
    }

    public function get_minimum($data1, $data2, $data3)
    {
        $data3 = 2;
        $data3 = $data3 ? $this->m_core->currency_to_number($data3) : 0;
            $this->load->model('m_core');
            $hasil = 0;
            $tableName = '';
            $rumus = 0;
            if ($data1 == '2') {
                $tableName = 'range_air_detail';
                $tableNameid = 'range_air_id';
                $tableRange =  'range_air';
                $rumus = $this->db->select('formula')->from("range_air")->where('id',$data2)->get()->row()->formula;
            } elseif ($data1 == '1') {
                $tableName = 'range_lingkungan_detail';
                $tableNameid = 'range_lingkungan_id';
                $tableRange =  'range_lingkungan';
                // $rumus = 0;
            } elseif ($data1 == '3') {
                $tableName = 'range_listrik_detail';
                $tableNameid = 'range_listrik_id';
            }
            // $rumus = $this->db->get_where('parameter_project', array('name' => 'Rumus Hitung Air', 'project_id' => 1))->row()->value;
            // $rumus = $this->db->select('formula')->from($tableRange)->where('id',$data2)->get()->row()->formula;

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
                echo("a");
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
            }else{
                return 0;
            }

        return 0;
    }


    public function delete($dataTmp)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

    
        $this->db->join('jenis_golongan', 'jenis_golongan.id = sub_golongan.jenis_golongan_id');
        $this->db->where('jenis_golongan.project_id', $project->id);
        $this->db->from('sub_golongan');

        // validasi apakah user dengan project $project boleh edit data ini
        if ($this->db->count_all_results() != 0) {
          
                        $before = $this->get_log($dataTmp['id']);
                        $this->db->where('id', $dataTmp['id']);
                        $this->db->update('sub_golongan', ['delete' => 1]);
                        $after = $this->get_log($dataTmp['id']);

                        $diff = (object) (array_diff_assoc((array) $after, (array) $before));
                        $tmpDiff = (array) $diff;

                        if ($tmpDiff) {
                            $this->m_log->log_save('sub_golongan', $dataTmp['id'], 'Edit', $diff);

                            return 'success';
                        } else {
                            return 'Tidak Ada Perubahan';
                        }
                   
        }
    }


}
