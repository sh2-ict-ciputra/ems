<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_permission_dokumen extends CI_Model
{
    public function get_selected($project_id){
        $header = $this->db->from("permission_dokumen")
                            ->where("project_id",$project_id)
                            ->get()->row();
        $data = (object)[];
        $data->mengetahui = (object)[];
        $detail = $this->db->select("permission_dokumen_detail.*")
                            ->from("permission_dokumen_detail")
                            ->join("permission_dokumen",
                                    "permission_dokumen.id = permission_dokumen_detail.permission_dokumen_id")
                            ->where("permission_dokumen.project_id",$project_id)
                            ->where("permission_dokumen_detail.tipe",0)
                            ->get()->row();
        $detail_id = $detail?$detail->id:0;
        $data->mengetahui->detail = $detail;
        $group_user_tmp = $this->db->select("permission_dokumen_group_user.*")
                                    ->from("permission_dokumen_group_user")
                                    ->join("permission_dokumen_detail",
                                            "permission_dokumen_detail.id = permission_dokumen_group_user.permission_dokumen_detail_id")
                                    ->where("permission_dokumen_detail.id",$detail_id)
                                    ->get()->result();
        $group_user = [];              
        foreach ($group_user_tmp as $k2 => $v2) {
            array_push($group_user,$v2->group_user_id);
        }
        if($data->mengetahui->detail)
            $data->mengetahui->detail->group_user = $group_user;
        
        $data->wewenang = (object)[];
        $detail = $this->db->select("permission_dokumen_detail.*")
                            ->from("permission_dokumen_detail")
                            ->join("permission_dokumen",
                                    "permission_dokumen.id = permission_dokumen_detail.permission_dokumen_id")
                            ->where("permission_dokumen.project_id",$project_id)
                            ->where("permission_dokumen_detail.tipe",1)
                            ->get()->result();
        $data->wewenang->detail = $detail;
        
        foreach ($detail as $k=> $v) {
            $group_user_tmp = $this->db->select("permission_dokumen_group_user.*")
                                        ->from("permission_dokumen_group_user")
                                        ->join("permission_dokumen_detail",
                                                "permission_dokumen_detail.id = permission_dokumen_group_user.permission_dokumen_detail_id")
                                        ->where("permission_dokumen_detail.id",$v->id)
                                        ->get()->result();
            $group_user = [];              
            foreach ($group_user_tmp as $k2 => $v2) {
                array_push($group_user,$v2->group_user_id);
            }
            $data->wewenang->detail[$k]->group_user = $group_user;

        }
        // $data->detail = $detail;
        // echo("<pre>");
        //     print_r($data);
        // echo("</pre>");
        return $data;
        
    }
    public function get_wewenang($project_id,$nilai){
        return $this->db->select("pdd1.*,
                            STUFF(
                                (SELECT 
                                    concat(', ', pdgu2.group_user_id)
                                FROM permission_dokumen pd2
                                JOIN permission_dokumen_detail pdd2
                                    ON pdd2.permission_dokumen_id = pd2.id
                                JOIN permission_dokumen_group_user pdgu2
                                    ON pdgu2.permission_dokumen_detail_id = pdd2.id
                                WHERE pdd2.id = pdd1.id
                                AND pdd2.nilai_awal <= '$nilai'
                                AND pd1.project_id = $project_id
                                FOR XML PATH('')
                                ), 
                            1, 2, '') as group_user_id")
                    ->from("permission_dokumen pd1")
                    ->join("permission_dokumen_detail pdd1",
                            "pdd1.permission_dokumen_id = pd1.id")
                    ->join("permission_dokumen_group_user pdgu1",
                            "pdgu1.permission_dokumen_detail_id = pdd1.id")
                    ->where("pd1.project_id",$project_id)
                    ->where("pdd1.nilai_awal <=",$nilai)
                    ->where("pdd1.tipe",1)
                    ->distinct()
                    ->order_by('pdd1.nilai_awal')
                    ->get()->result();
    }
    public function get_mengetahui($project_id,$nilai){
        return $this->db->select("pdd1.*,
                            STUFF(
                                (SELECT 
                                    concat(', ', pdgu2.group_user_id)
                                FROM permission_dokumen pd2
                                JOIN permission_dokumen_detail pdd2
                                    ON pdd2.permission_dokumen_id = pd2.id
                                JOIN permission_dokumen_group_user pdgu2
                                    ON pdgu2.permission_dokumen_detail_id = pdd2.id
                                WHERE pdd2.id = pdd1.id
                                AND pd1.project_id = $project_id
                                FOR XML PATH('')
                                ), 
                            1, 2, '') as group_user_id")
                    ->from("permission_dokumen pd1")
                    ->join("permission_dokumen_detail pdd1",
                            "pdd1.permission_dokumen_id = pd1.id")
                    ->join("permission_dokumen_group_user pdgu1",
                            "pdgu1.permission_dokumen_detail_id = pdd1.id")
                    ->where("pd1.project_id",$project_id)
                    ->where("pdd1.tipe",0)
                    ->distinct()
                    ->get()->row();
    }
    
    public function get_wewenang_bu($project_id,$dokumen_jenis_kode,$nilai){
        $result = $this->db->select("jabatan_id,
                                    jarak_approve")
                            ->from("permission_dokumen")
                            ->join("dokumen_jenis",
                                    "dokumen_jenis.id = permission_dokumen.dokumen_jenis_id")
                            ->where("dokumen_jenis.code",$dokumen_jenis_kode)
                            ->where("tipe",1)
                            ->where("project_id",$project_id)
                            ->where("nilai_awal <= $nilai")
                            ->order_by("permission_dokumen.id,permission_dokumen.nilai_awal")
                            ->get()->result();
        if(!$result){
            $result = $this->db->select("jabatan_id,
                                        jarak_approve")
                            ->from("permission_dokumen")
                            ->join("dokumen_jenis",
                                    "dokumen_jenis.id = permission_dokumen.dokumen_jenis_id")
                            ->where("dokumen_jenis.code",$dokumen_jenis_kode)
                            ->where("tipe",1)
                            ->where("project_id is null")
                            ->where("nilai_awal <= $nilai")
                            ->order_by("permission_dokumen.id,permission_dokumen.nilai_awal")
                            ->get()->result();
        }
        $data1 = [];
        $data2 = [];
        
        foreach ($result as $k=>$v) {
            $data1[$k] = $v->jabatan_id;
            $data2[$k] = $v->jarak_approve;
        }
        return $result;
    }
    public function get_mengetahui_bu($project_id,$dokumen_jenis_kode){
        $result = $this->db->select("jabatan_id,
                                    jarak_approve")
                            ->from("permission_dokumen")
                            ->join("dokumen_jenis",
                                    "dokumen_jenis.id = permission_dokumen.dokumen_jenis_id")
                            ->where("dokumen_jenis.code",$dokumen_jenis_kode)
                            ->where("tipe",0)
                            ->where("project_id",$project_id)
                            ->order_by("permission_dokumen.id")
                            ->get()->result();
        if(!$result){
        $result = $this->db->select("jabatan_id,
                                    jarak_approve")
                            ->from("permission_dokumen")
                            ->join("dokumen_jenis",
                                    "dokumen_jenis.id = permission_dokumen.dokumen_jenis_id")
                            ->where("dokumen_jenis.code",$dokumen_jenis_kode)
                            ->where("tipe",0)
                            ->where("project_id is null")
                            ->order_by("permission_dokumen.id")
                            ->get()->result();
        }
        $data1 = [];
        $data2 = [];
        
        foreach ($result as $k=>$v) {
            $data1[$k] = $v->jabatan_id;
            $data2[$k] = $v->jarak_approve;
        }
        return (object)[
            "jabatan" => $data1,
            "jarak_approve" => $data2
            
        ];
    }
    public function get($dokumen_jenis_kode,$nilai){
        $project = $this->m_core->project();
        $mengetahui = $this->get_mengetahui($project->id,$dokumen_jenis_kode);
        $wewenang = $this->get_wewenang($project->id,$dokumen_jenis_kode,$nilai);

        
        return (object)[
            "mengetahui"    => $mengetahui->data1,
            "mengetahui_jarak_approve"    => $mengetahui->data2,
            "wewenang"      => $wewenang->data1,
            "wewenang_jarak_approve"      => $wewenang->data2
        ];
    }
    public function get_user(){
        $project = $this->m_core->project();
        return $this->db->select("
                            user.id,
                            concat([user].name,' - ',jabatan.name, ' - ', project.name) as name
                            ")
                        ->from('user')
                        ->join('group_user',
                                'group_user.id = user.id')
                        ->join('jabatan',
                                'jabatan.id = group_user.jabatan_id')
                        ->join('project',
                                'project.id = group_user.project_id')
                        ->where('project.id',$project->id)
                        ->get()->result();
    }
    public function get_jabatan()
    {
        $project = $this->m_core->project();

        return $this->db
                        ->select("
                                id,
                                name
                        ")
                        ->from("jabatan")
                        ->get()->result();
    }
    public function get_group_user()
    {
        $project = $this->m_core->project();
        return $this->db->select("
                                group_user.id,
                                concat([user].name,' - ',jabatan.name) as name
                            ")
                        ->from("group_user")
                        ->join("user",
                                "user.id = group_user.user_id")
                        ->join("jabatan",
                                "jabatan.id = group_user.jabatan_id")
                        ->where("group_user.project_id",$project->id)
                        ->get()->result();
    }
    public function get_view()
    {
        return $this->db
                        ->select("
                                id,
                                name,
                                code,
                                description
                                ")
                        ->from("dokumen_jenis")
                        ->get()->result();
    }
    public function get_by_id($id)
    {
        return $this->db
                        ->select("
                                id,
                                name,
                                code,
                                description
                                ")
                        ->from("dokumen_jenis")
                        ->where("id",$id)
                        ->get()->row();
    }
    public function get_by_id_tipe2($project_id){
        return $this->db->select("id,project_id")
                        ->from("permission_dokumen")
                        ->where("project_id",$project_id)
                        ->get()->row();
    }
    public function get_range($project_id,$tipe){
        $tmp =  $this->db
                        ->from("permission_dokumen")
                        ->join("permission_dokumen_detail",
                                "permission_dokumen_detail.permission_dokumen_id = permission_dokumen.id")
                        ->where("permission_dokumen.project_id",$project_id)
                        ->where("permission_dokumen_detail.tipe",$tipe)
                        ->get()->result();
        // echo("<pre>");
        //     print_r($tmp);
        // echo("</pre>");
        // var_dump(explode(",",$tmp[0]->jabatan_id));
        return $tmp;
    }
    public function to_int($data){
        return str_replace(",","",$data);
    }
    public function save($data,$project_id){
        
        $data = (object)$data;

        $return = (object)[];
        $return->message = "Data Gagal Di Tambah atau Update";
        $return->status = false;
        // save header
        $data_header = (object)[];
        $permission_dokumen = $this->db->select("id")->from("permission_dokumen")->where("project_id",$project_id)->get()->row();    
        if(!$permission_dokumen){
            $data_header->project_id = $project_id;
            $this->db->insert("permission_dokumen",$data_header);
            $data_header->id = $this->db->insert_id();
        }else{
            $data_header->id = $permission_dokumen->id;
        }
        // \save header
        // save detail
            //delete group_user
        $this->db->select('id');
        $this->db->from('permission_dokumen_detail');
        $this->db->where('permission_dokumen_id', $data_header->id);
        $where_clause = $this->db->get_compiled_select();
        
        $this->db->where("permission_dokumen_detail_id IN ($where_clause)");
        $this->db->delete('permission_dokumen_group_user'); 
            //\delete group_user
            //delete detail
        $data_detail = (object)[];
        $this->db->where("permission_dokumen_id",$data_header->id);
        $this->db->delete("permission_dokumen_detail");
            //\delete detail
        $data_detail->permission_dokumen_id = $data_header->id;
        $data_detail->tipe = 0;
        $data_detail->nilai_awal = null;
        $data_detail->nilai_akhir = null;
        $data_detail->jarak_approve = null;
        $this->db->insert("permission_dokumen_detail",$data_detail);
        $data_detail->id = $this->db->insert_id();
            //save group_user - mengetahui
        $data_group_user = (object)[];
        $data_group_user->permission_dokumen_detail_id = $data_detail->id;
        foreach ($data->mengetahui as $k => $v) {
            $data_group_user->group_user_id = $v;
            $this->db->insert("permission_dokumen_group_user",$data_group_user);
        }
            //\save group_user - mengetahui
        //save group_user - wewenang
        foreach ($data->wewenang as $k => $v) {
            $data_detail = (object)[];
            $data_detail->permission_dokumen_id = $data_header->id;
            $data_detail->tipe = 1;
            $data_detail->nilai_awal = $this->to_int($data->range_awal[$k]);
            if($data->range_akhir[$k] == 'Tak Hingga')
                $data_detail->nilai_akhir = -1;
            else
                $data_detail->nilai_akhir = $this->to_int($data->range_akhir[$k]);
            $data_detail->jarak_approve = $this->to_int($data->jarak_approve[$k]);
            $this->db->insert("permission_dokumen_detail",$data_detail);
            $data_detail->id = $this->db->insert_id();
            $data_group_user = (object)[];
            $data_group_user->permission_dokumen_detail_id = $data_detail->id;
            foreach ($v as $k2 => $v2) {
                $data_group_user->group_user_id = $v2;
                $this->db->insert("permission_dokumen_group_user",$data_group_user);
            }
        }
        //save group_user wewenang
        // \save detail
        


        // save user
        $return->status = true;        
        $return->message = "Data permission dokumen berhasil di tambah";        
        
        return $return;


        // $tmp->dokumen_jenis_id = $data->id_dokumen;
        // $tmp->project_id = $project_id;
        // $tmp->tipe = 1;
        
        
        
        // $this->db->where("dokumen_jenis_id",$data->id_dokumen);
        // $this->db->where("project_id",$project_id);
        // $this->db->delete("permission_dokumen");
        // foreach ($data->jabatan_user as $k=>$v) {
        //     $tmp->nilai_awal = str_replace(",","",$data->range_awal[$k]);
        //     $tmp->nilai_akhir = $k==(count($data->jabatan_user)-1)?'-1':str_replace(",","",$data->range_akhir[$k]);
        //     $tmp->jarak_approve = $data->jarak_approve[$k];
        //     $tmp->jabatan_id = $v;
        //     // var_dump($v);
        //     $this->db->insert("permission_dokumen",$tmp);

           


        //     // foreach ($tmp2 as $k2 => $v2) {
        //     //     $tmp->jabatan_id = str_replace(",","",$v2);                
        //     //     $this->db->insert("permission_dokumen",$tmp);
        //     // }
        // }
        // $tmp2 = (object)[];
        // $tmp2->dokumen_jenis_id = $data->id_dokumen;
        // $tmp2->project_id = $project_id;
        // $tmp2->tipe = 0;
        // $tmp2->nilai_awal = 0;
        // $tmp2->nilai_akhir = 0;

        // foreach ($data->jabatan_user_mengetahui as $k=>$v) {
        //     $tmp2->jarak_approve = $data->jarak_approve_mengetahui[$k];
        //     $tmp2->jabatan_id = $v;
        //     // var_dump($v);

        //     $this->db->insert("permission_dokumen",$tmp2);
        // }

        // $return->status = true;        
        // $return->message = "Data permission dokumen berhasil di tambah";        
        // return $return;
    }
    public function save_tipe2($data,$project_id){
        
        $data = (object)$data;
        $tmp = (object)[];
        $return = (object)[];

        if($data->user_id == FALSE){
            $return->message = "Data permission dokumen gagal di tambah";
            $return->status = false;
            return $return;

        }

        $return->status = true;        
        $return->message = "Data permission dokumen berhasil di tambah"; 
        
        $tmp->dokumen_jenis_id = $data->id_dokumen;
        $tmp->project_id = $project_id;
        $tmp->user_id = $data->user_id;
        $this->db->trans_start();

        $this->db->where("dokumen_jenis_id",$data->id_dokumen);
        $this->db->where("project_id",$project_id);
        $this->db->update("permission_dokumen",$tmp);

        if ($this->db->trans_status() === FALSE){
            $return->message = "Data permission dokumen gagal di tambah";
            $return->status = false;
        }else{
            $this->db->trans_commit();
        }
              
        return $return;
    }
   
}
