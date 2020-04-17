<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_paket_loi extends CI_Model
{
    public function get()
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        return $this->db->select("
                                loi_paket.id,
                                loi_paket.code,
                                loi_paket.name,
                                loi_paket.nilai,
                                loi_paket.nilai_admin,
                                loi_paket.uang_jaminan,
                                case loi_paket.follow_up
                                    WHEN 1 THEN 'EMS'
                                    WHEN 2 THEN 'CPMS'
                                    WHEN 3 THEN 'Customer'
                                    ELSE 'Nothing'
                                END as follow_up,
                                count(loi_paket_outflow.id) as jumlah_outflow")
                            ->from("loi_paket")
                            ->join('loi_paket_outflow',
                                    'loi_paket_outflow.loi_paket_id = loi_paket.id')
                            ->group_by("
                                loi_paket.id,
                                loi_paket.code,
                                loi_paket.name,
                                loi_paket.nilai,
                                loi_paket.nilai_admin,
                                loi_paket.uang_jaminan,
                                case loi_paket.follow_up
                                    WHEN 1 THEN 'EMS'
                                    WHEN 2 THEN 'CPMS'
                                    WHEN 3 THEN 'Customer'
                                    ELSE 'Nothing'
                                END")
                            ->where('project_id',$project->id)
                            ->where('delete',0)
                            ->order_by("id desc")
                            ->get()->result();
    }

    public function getSelect($id)
    {
        
        $loi_paket = $this->db->from("loi_paket")
                            ->where('id',$id)
                            ->get()->row();
        $loi_paket_outflow = $this->db->from("loi_paket_outflow")
                                        ->where("loi_paket_id",$id)
                                        ->get()->result();
        return (object)[
            'loi_paket' => $loi_paket,
            'loi_paket_outflow' => $loi_paket_outflow
        ];
    }

    public function get_log($id)
    {
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $loi_paket = $this->db->select("
                                loi_paket.code as kode,
                                loi_paket.name as nama,
                                loi_paket.nilai as nilai_loi,
                                loi_paket.nilai_admin,
                                loi_paket.uang_jaminan,
                                case loi_paket.follow_up
                                    WHEN 1 THEN 'EMS'
                                    WHEN 2 THEN 'CPMS'
                                    WHEN 3 THEN 'Customer'
                                    ELSE 'Nothing'
                                END as follow_up,
                                case loi_paket.active
                                    when 1 then 'Aktif' 
                                    else 'Tidak Aktif' 
                                end as Aktif, 
                                case loi_paket.[delete]
                                    when 1 then 'Ya' 
                                    else 'Tidak' 
                                end as [Delete]")
                            ->from("loi_paket")
                            ->where('project_id',$project->id)
                            ->where('id',$id)
                            ->get()->row();
        $loi_paket_outflow = $this->db->select("
                                                    name as nama,
                                                    nilai as harga_total,
                                                    satuan,
                                                    kwantitas
                                                ")
                                        ->from("loi_paket_outflow")
                                        ->where("loi_paket_id",$id)
                                        ->get()->result();
                        
        foreach ($loi_paket_outflow as $k => $v) {
            foreach ($v as $k2 => $v2) {
                $loi_paket->{($k+1).".".$k2} = $v2;
            }
        }
        return $loi_paket;
    }
    
    public function save($data){
        // echo("<pre>");
        //     print_r($data);
        // echo("</pre>");
        
        // die;

        $data = (object)$data;
        $this->load->model('m_log');
        $this->load->model('m_core');
        $this->load->model('m_item_loi');
        $project = $this->m_core->project();

        // init return
        $return = (object)[];
        $return->message = "Kode sudah di gunakan";
        $return->status = false;
        
        $this->db->where('code', $data->code);
        $this->db->where('project_id', $project->id);
        $this->db->from('loi_paket');
        
        // validasi double name
        if ($this->db->count_all_results() > 0) return $return; 
        
        $loi_paket = (object)[];
        $loi_paket->project_id      = $project->id;
        $loi_paket->code            = $data->code;
        $loi_paket->name            = $data->name;
        $loi_paket->nilai           = $data->nilai;
        $loi_paket->nilai_admin     = $data->nilai_admin;
        $loi_paket->uang_jaminan    = $data->uang_jaminan;
        $loi_paket->follow_up       = $data->follow_up;
        // echo("<pre>");
        //     print_r($loi_paket);
        // echo("</pre>");
        
        $this->db->insert('loi_paket',$loi_paket);
        $loi_paket->id = $this->db->insert_id();


        if (isset($data->item_name))
        {
            foreach ($data->item_name as $k => $v) {
                $loi_paket_outflow = (object)[];
                $loi_paket_outflow->loi_paket_id    = $loi_paket->id;
                $loi_paket_outflow->kwantitas       = $data->item_kwantitas[$k];
                $loi_paket_outflow->nilai           = $data->item_nilai[$k];

                if(preg_match('/[A-Za-z]/', $data->item_name[$k])){ // data baru
                    $this->m_item_loi->save([
                        'name' => $data->item_name[$k],
                        'satuan_name' => [$data->item_satuan[$k]]
                    ]);
                    $loi_paket_outflow->name            = $data->item_name[$k];
                    $loi_paket_outflow->satuan          = $data->item_satuan[$k];
                }elseif(preg_match('/[A-Za-z]/', $data->item_satuan[$k])){ // data baru
                    $this->m_item_loi->save_satuan([
                        'id' => $data->item_name[$k],
                        'satuan_name' => $data->item_satuan[$k]
                    ]);
                    
                    $loi_paket_outflow->name            = $this->db->select('name')->from('loi_item_outflow')->where('id',$data->item_name[$k])->get()->row()->name;
                    $loi_paket_outflow->satuan          = $data->item_satuan[$k];
                }else{
                    $loi_paket_outflow->name            = $this->db->select('name')->from('loi_item_outflow')->where('id',$data->item_name[$k])->get()->row()->name;
                    $loi_paket_outflow->satuan          = $this->db->select('name')->from('loi_item_outflow_satuan')->where('loi_item_outflow_id',$data->item_name[$k])->get()->row()->name;
                }
                // // die;
                // echo("<pre>");
                //     print_r($loi_paket_outflow);
                // echo("</pre>");
                $this->db->insert('loi_paket_outflow', $loi_paket_outflow);
            }
            // die;

        }
        $return->status = true;        
        $return->message = "Data berhasil di tambah";
        $dataLog = $this->get_log($loi_paket->id);
        
        $this->m_log->log_save('loi_paket',$loi_paket->id,'Tambah',$dataLog);
        return $return;
    }

    public function edit($data)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');

        $data = (object)$data;
        $return = (object)[];
        $return->message = "Akses Tidak Ada";
        $return->status = false;

        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        // validasi apakah user dengan project $project boleh edit data ini
        $this->db->where('code', $data->code);
        $this->db->where('project_id', $project->id);
        $this->db->from('loi_paket');
        if ($this->db->count_all_results() != 0) {
            $loi_paket = (object)[];
            $loi_paket->code        = $data->code;
            $loi_paket->name        = $data->name;
            $loi_paket->nilai       = $data->nilai;
            $loi_paket->nilai_admin = $data->nilai_admin;
            $loi_paket->uang_jaminan = $data->uang_jaminan;
            $loi_paket->follow_up   = $data->follow_up;
            
            $before = $this->get_log($data->id);
            $this->db->where('id', $data->id);
            $this->db->update('loi_paket', $loi_paket);
            $loi_paket->id = $data->id;
            

            $this->db->where('loi_paket_id', $loi_paket->id);
            $this->db->delete('loi_paket_outflow');            
            foreach ($data->item_name as $k => $v) {
                $loi_paket_outflow = (object)[];
                $loi_paket_outflow->loi_paket_id    = $loi_paket->id;
                $loi_paket_outflow->name            = $data->item_name[$k];
                $loi_paket_outflow->kwantitas       = $data->item_kwantitas[$k];
                $loi_paket_outflow->satuan          = $data->item_satuan[$k];
                $loi_paket_outflow->nilai           = $data->item_nilai[$k];
                $this->db->insert('loi_paket_outflow', $loi_paket_outflow);
            }

            $after = $this->get_log($data->id);
            $diff = (object)(array_diff_assoc((array)$after, (array)$before));
            $tmpDiff = (array)$diff;
            if ($tmpDiff) {
                $this->m_log->log_save('loi_paket', $data->id, 'Edit', $diff);
                $return->status = true;        
                $return->message = "Data berhasil di Update";
            } else{
                $return->status = false;        
                $return->message = "Tidak Ada Perubahan";
            }
            return $return;            
        }else return $return;
    }

    public function delete($data)
    {
        $this->load->model('m_core');
        $this->load->model('m_log');
        $project = $this->m_core->project();
        $data = (object)$data;
        $return = (object)[];
        $return->message = "Tidak Ada Perubahan";
        $return->status = false;
        // validasi apakah user dengan project $project boleh edit data ini

        // validasi Cara Pembayaran

        $before = $this->get_log($data->id);
        $this->db->where('id', $data->id);
        $this->db->update('loi_paket', ['delete' => 1]);
        $after = $this->get_log($data->id);

        $diff = (object)(array_diff((array)$after, (array)$before));
        $tmpDiff = (array)$diff;

        if ($tmpDiff) {
            $this->m_log->log_save('loi_paket', $data->id, 'Edit', $diff);

            $return->message = "Data Sukses di Delete";
            $return->status = true;
        }
        return $return;
    }
    public function get_item($data){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $data = $data == ' '?'':$data;
        return $this->db->select("
                            loi_item_outflow.id as id, 
                            upper(loi_item_outflow.name) as text")
                        ->from('loi_item_outflow')
                        ->where('loi_item_outflow.project_id', $project->id)
                        ->where("loi_item_outflow.name like '%" . $data . "%'")
                        ->limit(10)
                        ->get()->result();
    }
    public function get_item_cek($data){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        return $this->db->select("
                            loi_item_outflow.id as id, 
                            upper(loi_item_outflow.name) as text")
                        ->from('loi_item_outflow')
                        ->where('loi_item_outflow.project_id', $project->id)
                        ->where("upper(loi_item_outflow.name)",$data)
                        ->get()->row();
    }
    public function get_item_satuan($data){
        $this->load->model('m_core');
        $project = $this->m_core->project();
        $data = (object)$data;
        $id = (int)$data->loi_item_outflow_id;
        $data = $data->data == ' '?'':$data->data;
        return $this->db->select("
                            loi_item_outflow_satuan.id as id, 
                            upper(loi_item_outflow_satuan.name) as text")
                        ->from('loi_item_outflow_satuan')
                        ->join('loi_item_outflow',
                                "loi_item_outflow.id = loi_item_outflow_satuan.loi_item_outflow_id")
                        ->where('loi_item_outflow.project_id', $project->id)
                        ->where('loi_item_outflow_satuan.loi_item_outflow_id', $id)
                        ->where("loi_item_outflow_satuan.name like '%" . $data . "%'")
                        ->limit(10)
                        ->get()->result();
    }
    public function get_item_satuan_cek($data){
        $data = (object)$data;
        $this->load->model('m_core');
        $project = $this->m_core->project();
        return $this->db->select("
                            loi_item_outflow_satuan.id as id, 
                            upper(loi_item_outflow_satuan.name) as text")
                        ->from('loi_item_outflow_satuan')
                        ->join('loi_item_outflow',
                                "loi_item_outflow.id = loi_item_outflow_satuan.loi_item_outflow_id")
                        ->where('loi_item_outflow.project_id', $project->id)
                        ->where("upper(loi_item_outflow_satuan.name)",$data->data)
                        ->where("loi_item_outflow.id",$data->id)

                        ->get()->row();
    }
}

