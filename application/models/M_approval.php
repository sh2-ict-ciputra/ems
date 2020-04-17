<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_approval extends CI_Model
{
    public function get_view(){
        $group_user_id = isset($this->session->userdata['group'])?$this->session->userdata['group']:'0';

        return $this->db->select("
                                    approval.id,
                                    dokumen_code as dokumen_code,
                                    dokumen_jenis.name as dokumen_jenis,
                                    FORMAT(tgl_tambah,'dd-MM-yyyy') as tgl_tambah,
                                    user.name as user_request,
                                    approval_status.status as status_dokumen
                                    ")
                            ->from('approval')
                            ->join('dokumen_jenis',
                                    'dokumen_jenis.id = approval.dokumen_jenis_id')
                            ->join('user',
                                    'user.id = approval.user_id')
                            ->join('approval_status',
                                    'approval_status.id = approval.approval_status_id')
                            ->join("approval_mengetahui",
                                    "approval_mengetahui.approval_id = approval.id")
                            ->join("approval_wewenang",
                                    "approval_wewenang.approval_id = approval.id")
                            ->join("approval_wewenang_user",
                                    "approval_wewenang_user.approval_wewenang_id = approval_wewenang.id")
                            ->where("(
                                    approval.group_user_id = $group_user_id
                                    or approval_mengetahui.group_user_id = $group_user_id
                                    or approval_wewenang.group_user_id = $group_user_id
                                    or (
                                        approval_wewenang_user.group_user_id = $group_user_id 
                                        and  approval_wewenang.approval_status_id = 3
                                        )
                                    )")
                            ->distinct()
                            ->get()->result();
    }
    public function get_view_bu()
    {
        $approval_id = []; 
        
        $jabatan_id = $this->db->select("jabatan_id")
                                    ->from("group_user")
                                    ->where("id", $this->session->userdata["group"])
                                    ->get()->row()->jabatan_id;
        $project = $this->m_core->project();
        $dataCreate = $this->db->select("id")
                                ->from("approval")
                                ->where("approval.create_jabatan_id",$jabatan_id)
                                ->get()->result();
        foreach ($dataCreate as $v) {
            array_push($approval_id,$v->id);
        }
        $dataWewenang = $this->db->select("id")
                                ->from("approval")
                                ->where("approval.create_jabatan_id",$jabatan_id)
                                ->get()->result();

        $dataWewenang = $this->db->select("
                                approval.id,
                                approval_detail.approval_jabatan_id as jabatan_id
                                ")
                        ->from("approval")
                        ->join("dokumen_jenis",
                                "dokumen_jenis.id = approval.dokumen_jenis_id")
                        ->join("user",
                                "user.id = approval.create_user_id")
                        ->join("approval_status",
                                "approval_status.id = approval.status_dokumen")
                        ->join("approval_detail",
                                "approval_detail.approval_id = approval.id")
                        ->where("approval.project_id",$project->id)
                        ->where("approval.status_request",1)
                        ->get()->result();
        $data = [];
        $tmp = 0; // untuk pemisahan jabatan
        $tmp2 = 0; // untuk tidak double, berisi id
        foreach ($dataWewenang as $k => $v) {
            $tmp = explode(",",$v->jabatan_id);
            foreach ($tmp as $k2 => $v2) {
                if($v2 == $jabatan_id && $tmp2 != $v->id ){
                    array_push($approval_id,$v->id);
                    $tmp2                   = $v->id;
                }
            }
        }
        if($approval_id){
        $data = $this->db->select("
                            approval.id,
                            approval.dokumen_code,
                            dokumen_jenis.name as dokumen_jenis,
                            approval.tgl_tambah,
                            approval_status.status,
                            user.name as user_request
                            ")
                    ->from("approval")
                    ->join("dokumen_jenis",
                            "dokumen_jenis.id = approval.dokumen_jenis_id")
                    ->join("user",
                            "user.id = approval.create_user_id")
                    ->join("approval_status",
                            "approval_status.id = approval.status_dokumen")
                    ->where("approval.project_id",$project->id)
                    ->where_in("approval.id",$approval_id)
                    // ->where("approval.status_request",1)
                    ->get()->result();
        }

        return $data;
    }
    public function get_void_pembayaran($status_approval){
        $data = $this->db->select("
                                    approval.id,
                                    approval.dokumen_code,
                                    dokumen_jenis.name as dokumen_jenis,
                                    approval.tgl_tambah as tgl_tambah,
                                    user.name as user_request,
                                    approval_status.status as status_dokumen
                                ")
                        ->from("approval")
                        ->join("approval_status",
                                "approval_status.id = approval.status_approval")
                        ->join("dokumen_jenis",
                                "dokumen_jenis.id = approval.dokumen_jenis_id")
                        ->join("user",
                                "user.id = approval.create_user_id")
                        ->where("status_approval",$status_approval)
                        ->get()->result();
        return $data;
    }
    public function cek_permission_edit($id){
        $data = $this->get_view();
        foreach ($data as $v) {
            if($v->id == $id){
                return true;
            }
        }
        return false;
    }
    public function reject_void_pembayaran($approval_id){
        $return = (object)[];

        $return->status = true;        
        $return->message = "Data Void berhasil di Reject"; 
        
        $this->db->trans_start();
        $data = (object)[];
        $data->approval_id          = $approval_id;
        $data->approval_user_id     = $this->m_core->user_id();
        $data->tgl_approve          = date("Y-m-d");
        $data->approval_jabatan_id  = $this->m_core->jabatan()->id;
        // $data->mengetahui_wewenang  = ;
        $data->approve_user_id      = $this->m_core->user_id();
        $data->description          = '';
        $data->status_approve       = 2;
        // $data->lama_approve         = ;
        $this->db->insert("approval_detail",$data);
        
        $this->db->where("id", $approval_id);
        $this->db->update("approval",["status_approval"=> 2]);
        if ($this->db->trans_status() === FALSE){
            $return->message = "Data void gagal di Reject";
            $return->status = false;
        }else{
            $this->db->trans_commit();
        }
		return $return;


    }
    public function approve_void_pembayaran($approval_id){
        $source_id = $this->db->select("source_id")
                                ->from("approval")
                                ->where("id",$approval_id)
                                ->get()->row()->source_id;
        $this->db->where("id",$source_id);
        $this->db->update("t_pembayaran",["is_void"=>1]);


        $data = $this->db->from("t_pembayaran_detail")
                        ->where("t_pembayaran_id",$source_id)
                        ->get()->result();
        var_dump($data);
        foreach ($data as $k => $v) {
            $service_jenis_id = $this->db->from("service")
                                    ->where("id",$v->service_id)
                                    ->get()->row()->service_jenis_id;
            if($service_jenis_id == 1){
                $this->db->where("id",$v->tagihan_service_id);
                $this->db->update("t_tagihan_lingkungan",['status_tagihan'=>0]);
            }elseif($service_jenis_id == 2){
                $this->db->where("id",$v->tagihan_service_id);
                $this->db->update("t_tagihan_air",['status_tagihan'=>0]);
            }
        }

        
        $return = (object)[];

        $return->status = true;        
        $return->message = "Data Void berhasil di Approve"; 
        
        $this->db->trans_start();
        $data = (object)[];
        $data->approval_id          = $approval_id;
        $data->approval_user_id     = $this->m_core->user_id();
        $data->tgl_approve          = date("Y-m-d");
        $data->approval_jabatan_id  = $this->m_core->jabatan()->id;
        // $data->mengetahui_wewenang  = ;
        $data->approve_user_id      = $this->m_core->user_id();
        $data->description          = '';
        $data->status_approve       = 1;
        // $data->lama_approve         = ;
        $this->db->insert("approval_detail",$data);
        
        $this->db->where("id", $approval_id);
        $this->db->update("approval",["status_approval"=> 1]);

        if ($this->db->trans_status() === FALSE){
            $return->message = "Data void gagal di Approve";
            $return->status = false;
        }else{
            $this->db->trans_commit();
        }
		return $return;
    }
    
    public function get_edit($id){
        $group_user_id = isset($this->session->userdata['group'])?$this->session->userdata['group']:'0';
        // var_dump($group_user_id);
        $project = $this->m_core->project();
        //izin, 0: tidak, 1: mengetahui, 2 approve/reject
        $izin   = $this->db->select("
                                        CASE 
                                            WHEN (approval_wewenang_user.group_user_id = $group_user_id
                                                    and approval_wewenang.approval_status_id = 3) THEN
                                                2
                                            WHEN approval_wewenang.group_user_id = $group_user_id THEN
                                                1
                                            WHEN approval_mengetahui.group_user_id = $group_user_id THEN
                                                1
                                            WHEN approval.group_user_id = $group_user_id THEN
                                                1
                                            WHEN (approval_wewenang_user.group_user_id = $group_user_id
                                                and (approval_wewenang.approval_status_id = 1 or approval_wewenang.approval_status_id = 2)) THEN
                                                1
                                            else
                                                0
                                        END as izin
                                        ")
                                        
                                ->from('approval')
                                ->join("approval_mengetahui",
                                        "approval_mengetahui.approval_id = approval.id")
                                ->join("approval_wewenang",
                                        "approval_wewenang.approval_id = approval.id")
                                ->join("approval_wewenang_user",
                                        "approval_wewenang_user.approval_wewenang_id = approval_wewenang.id")
                                ->where('approval.id',$id)
                                ->order_by('izin desc')
                                // ->where("(
                                //         approval.group_user_id = $group_user_id
                                //         or approval_mengetahui.group_user_id = $group_user_id
                                //         or approval_wewenang.group_user_id = $group_user_id
                                //         or (
                                //             approval_wewenang_user.group_user_id = $group_user_id 
                                //             and  approval_wewenang.approval_status_id = 3
                                //             )
                                //         )")
                                ->distinct()
                                ->get()->row();
        // echo("<pre>");
        // print_r($izin);
        // echo("</pre>");

        $izin = $izin?$izin->izin:0;

        $dokumen = $this->db->select("
                                dokumen_jenis.name as dokumen,
                                approval.dokumen_code,
                                concat([user].name,' (',jabatan.name,')') as request,
                                approval.dokumen_nilai,
                                approval.tgl_tambah,
                                approval.jarak_approval_closed as jarak_request_closed,
                                approval.tgl_closed,
                                approval.tgl_approve,
                                approval.approval_status_id as status_dokumen_id,
                                approval_status.status as status_dokumen,
                                ''  as status_request,
                                '' as status_request_id,
                                approval.user_id,
                                approval.jabatan_id")
                            ->from("approval")
                            ->join("dokumen_jenis",
                                    "dokumen_jenis.id = approval.dokumen_jenis_id")
                            ->join("user",
                                    "user.id = approval.user_id")
                            ->join("jabatan",
                                    "jabatan.id = approval.jabatan_id")
                            ->join("approval_status",
                                    "approval_status.id = approval.approval_status_id")
                            ->where("approval.id",$id)
                            ->get()->row();
        $jabatan_id = $this->db->select("jabatan_id")
            ->from("group_user")
            ->where("id", $this->session->userdata["group"])
            ->get()->row()->jabatan_id;   
        // $create = (object)[
        //     // "hak_approve"       => $dokumen->status_request_id==0?2:0,
        //     "jabatan_name"      => $this->db->select("name")->from("jabatan")->where("id",$dokumen->jabatan_id)->get()->row()->name,
        //     "jabatan_name"      => $this->db->select("name")->from("jabatan")->where("id",$dokumen->jabatan_id)->get()->row()->name,
            
        //     // "status"            => $dokumen->status_request,
        //     // "status_id"         => $dokumen->status_request_id,
        //     // "batas_waktu"       => $dokumen->jarak_request_closed,
        //     "deskripsi"         => 0,
        // ];

        $daftar_wewenang = $this->db->select("
                                                approval_wewenang.id,
                                                approval_wewenang.approval_status_id,
                                                approval_status.status_approval as status,
                                                STUFF((SELECT DISTINCT ' / ' + 	concat(u.name,' (', j.name,')')
                                                        FROM approval_wewenang aw 
                                                        JOIN approval_wewenang_user awu 
                                                            ON awu.approval_wewenang_id = aw.id
                                                        JOIN [user] as u
                                                            ON u.id = awu.user_id
                                                        JOIN jabatan as j
                                                            ON j.id = awu.jabatan_id
                                                    WHERE aw.id = approval_wewenang.id 
                                                    FOR XML PATH('')), 1, 2, '') as [daftar_user],
                                                CASE 
                                                    WHEN user1.name is not null THEN
                                                        concat(user1.name,' - ', jabatan1.name)
                                                    ELSE 
                                                        null
                                                END as [user_approve],
                                                approval_wewenang.tgl_approve,
                                                approval_wewenang.tgl_kirim_email,
                                                approval_wewenang.jarak_approve,
                                                approval_wewenang.description
                                            ")
                                            ->from("approval_wewenang")
                                            ->join("approval_wewenang_user",
                                                    "approval_wewenang_user.approval_wewenang_id = approval_wewenang.id")
                                            ->join("approval_status",
                                                    "approval_status.id = approval_wewenang.approval_status_id")
                                            ->join("user as user1",
                                                    "user1.id = approval_wewenang.user_id",
                                                    "LEFT")
                                            ->join("jabatan as jabatan1",
                                                    "jabatan1.id = approval_wewenang.jabatan_id",
                                                    "LEFT")
                                            ->join("user as user2",
                                                    "user2.id = approval_wewenang_user.user_id")
                                            ->join("jabatan as jabatan2",
                                                    "jabatan2.id = approval_wewenang_user.jabatan_id")
                                            ->where("approval_wewenang.approval_id", $id)       
                                            ->distinct()
                                            ->get()->result(); 
        $daftar_mengetahui = $this->db->select(" 
                                                approval_mengetahui.id,
                                                STUFF((SELECT DISTINCT ' / ' + 	concat(u.name,' (', j.name,')')
                                                        FROM approval_mengetahui am
                                                        JOIN [user] as u
                                                            ON u.id = am.user_id
                                                        JOIN jabatan as j
                                                            ON j.id = am.jabatan_id
                                                    WHERE am.id = approval_mengetahui.id 
                                                    FOR XML PATH('')), 1, 2, '') as [daftar_user],
                                                approval_mengetahui.tgl_kirim_email
                                                ")
                                        ->from("approval_mengetahui")
                                        ->join("user",
                                                "user.id = approval_mengetahui.user_id")
                                        ->join('jabatan',
                                                'jabatan.id = approval_mengetahui.jabatan_id')
                                        ->where('approval_mengetahui.approval_id',$id)
                                        ->distinct()
                                        ->get()->result();
        $jumlah_wewenang =(object)[
            "total"    => 0,
            "request"  => 0,
            "approve"  => 0,
            "reject"   => 0 
        ];
        foreach ($daftar_wewenang as $k => $v) {
            $jumlah_wewenang->total++;
            if($v->approval_status_id == 0)
                $jumlah_wewenang->request++;
            if($v->approval_status_id == 1)
                $jumlah_wewenang->approve++;
            if($v->approval_status_id == 2)
                $jumlah_wewenang->reject++;
        }
        $data = (object)[];
        // $data->mengajukan       = $create;
        $data->wewenang         = $daftar_wewenang;
        $data->mengetahui       = $daftar_mengetahui;
        $data->jumlah           = $jumlah_wewenang;
        $data->dokumen          = $dokumen;
        $data->jabatan_id       = $jabatan_id;
        $data->izin             = $izin;
        // echo("<pre>");
        // print_r($data);
        // echo("</pre>");
        return $data;
    }
    public function get_edit_bu($id){
        $project = $this->m_core->project();

        $dokumen = $this->db->select("
                                dokumen_jenis.name as dokumen,
                                approval.dokumen_code,
                                user.name as request,
                                approval.dokumen_nilai,
                                approval.tgl_tambah,
                                approval.jarak_request_closed,
                                approval.tgl_closed,
                                approval.tgl_approve,
                                approval.status_dokumen,
                                status_dokumen.status_dokumen,
                                status_request.status_request,
                                status_request.id as status_request_id,
                                approval.create_user_id,
                                approval.create_jabatan_id")
                            ->from("approval")
                            ->join("dokumen_jenis",
                                    "dokumen_jenis.id = approval.dokumen_jenis_id")
                            ->join("user",
                                    "user.id = approval.create_user_id")
                            ->join("approval_status as status_dokumen",
                                    "status_dokumen.id = approval.status_dokumen")
                            ->join("approval_status as status_request",
                                    "status_request.id = approval.status_request")
                            ->where("approval.id",$id)
                            ->get()->row();
        // if($dokumen)
        $jabatan_id = $this->db->select("jabatan_id")
                                ->from("group_user")
                                ->where("id", $this->session->userdata["group"])
                                ->get()->row()->jabatan_id;    
        // var_dump($jabatan_id);
        $daftar_jabatan_tmp = $this->db->select("
                                    approval_detail.approval_jabatan_id as jabatan_id,
                                    approval_detail.status_approve,
                                    description,
                                    approval_detail.jarak_approve
                                ")
                                // approval_detail.mengetahui_wewenang,
                                ->from("approval_detail")
                                ->where("approval_detail.approval_id",$id)
                                ->order_by("approval_detail.id")
                                ->get()->result();
        $daftar_jabatan    = [];
        $jumlah_wewenang =(object)[
            "wewenang" => 0,
            "wewenang_approve" => 0
        ];
        // if($dokumen->status_approval==0)
        //     $tmp = "Create";
        // else if($dokumen->status_approval==1)
        //     $tmp = "Open";
        // else
        //     $tmp = "Cancel";
        $create = (object)[
            "hak_approve"       => $dokumen->status_request_id==0?2:0,
            "jabatan_name"      => $this->db->select("name")->from("jabatan")->where("id",$dokumen->create_jabatan_id)->get()->row()->name,
            "jabatan_id"      => $this->db->select("id")->from("jabatan")->where("id",$dokumen->create_jabatan_id)->get()->row()->id,
            "status"            => $dokumen->status_request,
            "status_id"         => $dokumen->status_request_id,
            "batas_waktu"       => $dokumen->jarak_request_closed,
            "deskripsi"         => 0,
        ];
        foreach ($daftar_jabatan_tmp as $k => $v) {            
            $daftar_jabatan[$k] = (object)[];
            $tmp = explode(",",$v->jabatan_id);
            $nama_jabatan_tmp = "";
            $id_jabatan_tmp = "";
            $daftar_jabatan[$k]->hak_approve = 0;
            foreach($tmp as $k2 => $v2){
                $cek_jabatan = $this->db->select("name")->from("jabatan")->where("id",$v2)->get()->row();
                if($cek_jabatan){
                    $nama_jabatan_tmp = $nama_jabatan_tmp.$cek_jabatan->name." / ";
                    $id_jabatan_tmp = $id_jabatan_tmp.$v2.",";
                    if($v2 == $jabatan_id && $v->status_approve == 0){
                        $daftar_jabatan[$k]->hak_approve = 1;                
                    }
                }

            }
            $daftar_jabatan[$k]->jabatan_id     = substr($id_jabatan_tmp,0,-1);
            $daftar_jabatan[$k]->jabatan_name   = substr($nama_jabatan_tmp,0,-3);
            if($v->status_approve == 0)
                $daftar_jabatan[$k]->status = "Belum di Approve";
            if($v->status_approve == 1)
                $daftar_jabatan[$k]->status = "Sudah di Approve";
            if($v->status_approve == 2)
                $daftar_jabatan[$k]->status = "Di Tolak";
            if($v->status_approve == 3)
                $daftar_jabatan[$k]->status = "Telat";
            $daftar_jabatan[$k]->status_id              = $v->status_approve;
            // $daftar_jabatan[$k]->mengetahui_wewenang    = $v->mengetahui_wewenang;
            $daftar_jabatan[$k]->batas_waktu            = $v->jarak_approve;
            $daftar_jabatan[$k]->deskripsi              = $v->description;
            
            // if($daftar_jabatan[$k]->mengetahui_wewenang == 0){
                // $jumlah_wewenang->mengetahui++;
                // if($v->status_approve == 1){
                //     $jumlah_wewenang->mengetahui_approve++;
                // }
            // }
            // if($daftar_jabatan[$k]->mengetahui_wewenang == 1){
            $jumlah_wewenang->wewenang++;
            if($v->status_approve == 1){
                $jumlah_wewenang->wewenang_approve++;
            }
            // }
        }
        $data = (object)[];
        $data->mengajukan       = $create;
        $data->wewenang         = $daftar_jabatan;
        $data->jumlah_wewenang  = $jumlah_wewenang;
        $data->dokumen          = $dokumen;
        $data->jabatan_id       = $jabatan_id;
        return $data;
        
    }
    public function mengajukan($id,$tipe){
        $jabatan_id = $this->db->select("user_id,jabatan_id")
                                    ->from("group_user")
                                    ->where("id", $this->session->userdata["group"])
                                    ->get()->row(); 
        $user_id    = $jabatan_id->user_id;
        $jabatan_id = $jabatan_id->jabatan_id;
        $project = $this->m_core->project();
        $date = date('Y-m-d H:i:s.000');

        if($tipe == 1){

            $query1 = $this->db->select("count(*) as c")
                        ->from("approval")
                        ->where("status_request",0)
                        ->where("status_dokumen",0)
                        ->where("id",$id)
                        ->where("tgl_closed <= ","$date")
                        ->get()->row()->c;
            $query2 = $this->db->select("count(*) as c")
                        ->from("approval")
                        ->where("status_request",0)
                        ->where("status_dokumen",0)
                        ->where("id",$id)->where("create_jabatan_id",$jabatan_id)->get()->row()->c;
            if($query1 > 0){
                return 0;
            }else if($query2 > 0){
                $this->db->set('status_request', 1);
                $this->db->set('status_dokumen', 1);
                $this->db->set('approve_user_id', $user_id);
                $this->db->set('approve_jabatan_id', $jabatan_id);
                $this->db->set('tgl_approve', $date);
                $this->db->where('id', $id);
                $this->db->update('approval');

                $jabatan_id = $this->db->select("approval_jabatan_id")
                                    ->from("approval_detail")
                                    ->where("approval_id",$id)
                                    ->order_by("id")
                                    ->get()->row()->approval_jabatan_id;
                $jabatan_id = explode(",",$jabatan_id);
                $email = $this->db->select("email, user.name")
                    ->from("group_user")
                    ->join("user",
                            "user.id = group_user.user_id")
                    ->where_in("jabatan_id",$jabatan_id)
                    ->where("group_user.project_id",$project->id)
                    ->where("email is not null")
                    ->distinct()->get()->result(); 
                 $this->load->model('Setting/m_parameter_project');
                $config = [
                    'mailtype'  => 'html',
                    'charset'   => 'utf-8',
                    'protocol'  => 'smtp',
                    'smtp_host' => $this->m_parameter_project->get($project->id,"smtp_host"),
                    'smtp_user' => $this->m_parameter_project->get($project->id,"smtp_user"),
                    'smtp_pass' => $this->m_parameter_project->get($project->id,"smtp_pass"),
                    'smtp_port' => $this->m_parameter_project->get($project->id,"smtp_port"),
                    'crlf'      => "\r\n",
                    'newline'   => "\r\n"
                ];
                $this->load->library('email',$config);
                $this->email->from($this->m_parameter_project->get($project->id,"smtp_user"), 'EMS Ciputra');
                $komponen = $this->db->select("
                                                dokumen_jenis.name as dokumen,
                                                approval.dokumen_code,
                                                user_create.name as user_create,
                                                approval.dokumen_nilai,
                                                approval.tgl_tambah")
                                        ->from("approval")
                                        ->join("dokumen_jenis",
                                                "dokumen_jenis.id = approval.dokumen_jenis_id")
                                        ->join("user as user_create",
                                                "user_create.id = approval.create_user_id")
                                        ->where("approval.id",$id)
                                        ->get()->row();
                $tmp = $this->m_parameter_project->get($project->id,"isi_email_approval");
                $tmp = str_replace("{{Dokumen}}",$komponen->dokumen,$tmp);
                $tmp = str_replace("{{Kode}}",$komponen->dokumen_code,$tmp);
                $tmp = str_replace("{{User_create}}",$komponen->user_create,$tmp);
                $tmp = str_replace("{{Nilai}}",number_format($komponen->dokumen_nilai),$tmp);
                $tmp = str_replace("{{Date_create}}",substr($komponen->tgl_tambah,0,10),$tmp);
                
                $tmp = str_replace("{{Button_V}}","<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Login </a>",$tmp);
                $tmp = str_replace("{{Button_A}}","<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#286090;border-radius:5px;color:white'> Approve </a>",$tmp);
                $tmp = str_replace("{{Button_R}}","<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#286090;border-radius:5px;color:white'> Approve </a>",$tmp);
                $tmp = str_replace("{{Button_L}}","<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Login </a>",$tmp);
                foreach ($email as $v) {
                    $tmp2 = str_replace("{{User}}",ucwords($v->name),$tmp);
                    $this->email->clear(TRUE);
                    $this->email->from($this->m_parameter_project->get($project->id,"smtp_user"), 'EMS Ciputra');
                    $this->email->subject($this->m_parameter_project->get($project->id,"subjeck_email_approval"));
                    $this->email->message($tmp2);
                    $this->email->to($v->email);
                    if($this->email->send()){
                    }else{
                    }                   
                }
                $approval_detail = $this->db->select("*")
                                            ->from("approval_detail")
                                            ->where("approval_id",$id)
                                            ->get()->result();
                if($approval_detail){
                    $newDate = "";
                    $jarak_sebelumnya = 0;
                    foreach ($approval_detail as $k => $v) {
                        $data = (object)[];
                        if($k==0){
                            $newDate = date('Y-09-01');
                            $data->status_kirim_email = 1;
                        }else{
                            $newDate = date('Y-m-d',strtotime($newDate . "+".(1+$jarak_sebelumnya)." days"));
                            $data->status_kirim_email = 0;
                        }
                        $jarak_sebelumnya = $v->jarak_approve;

                        $data->tgl_kirim_email = $newDate;
                        $this->db->set('tgl_kirim_email', $data->tgl_kirim_email);
                        $this->db->set('status_kirim_email', $data->status_kirim_email);
                        $this->db->where('id', $v->id);
                        $this->db->update('approval_detail');

                    }
                    $newDate = date('Y-m-d',strtotime($newDate . "+".(1+$jarak_sebelumnya)." days"));
                    $this->db->set('tgl_closed', $newDate);
                    $this->db->where('id', $v->id);
                    $this->db->update('approval');
                }
                return 1;
            }
        }else{

        }
    }
    public function approve($approval_id,$description){
        $return = (object)[];
        $return->status = 0;
        $return->message = "Gagal melakukan Approve";
        
        $project = $this->m_core->project();
        $smtp_host = 'smtp.office365.com';
        $smtp_user = 'no.reply@ciputra.com';
        $smtp_pass = 'Som69936';
        $smtp_port = '587';
        
        $group_user_id = $this->session->userdata["group"];
        $jabatan_user_id = $this->db->select("user_id,jabatan_id")
                                    ->from("group_user")
                                    ->where("id", $group_user_id)
                                    ->get()->row();
        $jabatan_id = $jabatan_user_id?$jabatan_user_id->jabatan_id:0;
        $user_id = $jabatan_user_id?$jabatan_user_id->user_id:0;
        if($jabatan_id == 0 || $user_id == 0){
            $return->status = 0;
            $return->message = "Gagal mendapatkan data jabatan dan/atau user";
            return $return;
        }
        $date = date('Y-m-d H:i:s.000');
        $approval = $this->db->select("*")
                                ->from('approval')
                                ->where('approval.id',$approval_id)
                                ->get()->row();
        $approval_wewenang = (object)[];
        $approval_wewenang_id = $this->db->select("approval_wewenang.id")
                                            ->from('approval')
                                            ->join('dokumen_jenis',
                                                    'dokumen_jenis.id = approval.dokumen_jenis_id')
                                            ->join('user',
                                                    'user.id = approval.user_id')
                                            ->join('approval_status',
                                                    'approval_status.id = approval.approval_status_id')
                                            ->join("approval_mengetahui",
                                                    "approval_mengetahui.approval_id = approval.id")
                                            ->join("approval_wewenang",
                                                    "approval_wewenang.approval_id = approval.id")
                                            ->join("approval_wewenang_user",
                                                    "approval_wewenang_user.approval_wewenang_id = approval_wewenang.id")
                                            ->where("approval_wewenang_user.group_user_id",$group_user_id) 
                                            ->where("approval_wewenang.approval_status_id",3)
                                            ->where("approval.id",$approval_id)
                                            ->distinct()
                                            ->get()->row();
        $approval_wewenang_id = $approval_wewenang_id?$approval_wewenang_id->id:0;
        if($approval_wewenang_id == 0){
            // return "approval_wewenang_id = 0";
            $return->status = 0;
            $return->message = "Gagal mendapatkan data Wewenang anda (izin)";
            return $return;
        }
        // $approval_wewenang->approval_id         = $approval_id;
        $approval_wewenang->tgl_approve         = $date;
        $approval_wewenang->jabatan_id          = $jabatan_id;
        $approval_wewenang->user_id             = $user_id;
        $approval_wewenang->group_user_id       = $group_user_id;
        $approval_wewenang->approval_status_id  = 1;
        $approval_wewenang->description         = $description;
        $this->db->where("approval_wewenang.id",$approval_wewenang_id);
        $this->db->where("approval_wewenang.approval_id",$approval_id);        
        $this->db->update("approval_wewenang",$approval_wewenang);
        $approval_wewenang->id                  = $approval_wewenang_id;
        
        $next_approval_wewenang_id = $this->db->select("approval_wewenang.id")
                                            ->from('approval')
                                            ->join('user',
                                                    'user.id = approval.user_id')
                                            ->join("approval_wewenang",
                                                    "approval_wewenang.approval_id = approval.id")
                                            ->where("approval_wewenang.id >",$approval_wewenang->id) 
                                            ->where("approval_wewenang.approval_status_id",0)
                                            ->where("approval.id",$approval_id)
                                            ->distinct()
                                            ->get()->row();
        $next_approval_wewenang_id = $next_approval_wewenang_id?$next_approval_wewenang_id->id:0;
        if($next_approval_wewenang_id == 0){
            if($approval->dokumen_jenis_id == 1){ // pemutihan
                $this->load->model('transaksi/m_pemutihan');
                $this->m_pemutihan->approve($approval->dokumen_id);
            }
            if($approval->dokumen_jenis_id == 2){ // pemutihan
                $this->db->where("t_pembayaran.id",$approval->dokumen_id);
                $this->db->set("t_pembayaran.is_void",1);
                $this->db->update("t_pembayaran");            

                $t_pembayaran_detail = $this->db->select("*") 
                                                ->from("t_pembayaran_detail")
                                                ->where("t_pembayaran_detail.t_pembayaran_id",$approval->dokumen_id)
                                                ->get()->result();
                foreach ($t_pembayaran_detail as $k => $v) {
                    $service_jenis_id = $this->db->select("*")
                                                    ->from("service")
                                                    ->where("service.id",$v->service_id)
                                                    ->get()->row();
                    $service_jenis_id = $service_jenis_id?$service_jenis_id->service_jenis_id:0;
                    if($service_jenis_id == 1){//ipl
                        $this->db->where("t_tagihan_lingkungan.id",$v->tagihan_service_id);
                        $this->db->set("t_tagihan_lingkungan.status_tagihan",0);
                        $this->db->update("t_tagihan_lingkungan");
                    }elseif($service_jenis_id == 2){//air
                        $this->db->where("t_tagihan_air.id",$v->tagihan_service_id);
                        $this->db->set("t_tagihan_air.status_tagihan",0);
                        $this->db->update("t_tagihan_air");
                    }
                }

            }
            $approval = (object)[];
            $approval->approval_status_id   = 1;
            $approval->tgl_closed           = date("Y-m-d");
            $approval->tgl_approve          = date("Y-m-d"); // nanti di hapus aja gak dibutuhin lagi

            $this->db->where("approval.id",$approval_id);
            $this->db->update("approval",$approval);

            $return->status = 1;
            $return->message = "Sukses - Approval Closed dengan status Approve";
            return $return;

            //nanti untuk last approve (close)
        }
        $tujuan_email = $this->db->select('user.name, user.email')
                                            ->from('approval_wewenang_user')
                                            ->join("user",
                                                    "user.id = approval_wewenang_user.user_id")
                                            ->where_in("approval_wewenang_user.approval_wewenang_id",$next_approval_wewenang_id)
                                            ->distinct()
                                            ->get()->result();

        $next_approval_wewenang = (object)[];
        $next_approval_wewenang->approval_status_id = 3;                                
        $next_approval_wewenang->tgl_kirim_email    = $date;
        $this->db->where("approval_wewenang.id",$next_approval_wewenang_id);
        $this->db->update("approval_wewenang",$next_approval_wewenang);                 
        $next_approval_wewenang->id = $next_approval_wewenang_id;

        $name_user_create = $this->db->select('name') 
                                    ->from('user')
                                    ->where('user.id',$approval->user_id)
                                    ->get()->row();
        $name_dokumen = $this->db->select('name') 
                                    ->from('dokumen_jenis')
                                    ->where('dokumen_jenis.id',$approval->dokumen_jenis_id)
                                    ->get()->row();

        $config = [
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'protocol'  => 'smtp',
            'smtp_host' => $smtp_host,
            'smtp_user' => $smtp_user,
            'smtp_pass' => $smtp_pass,
            'smtp_port' => $smtp_port,
            'crlf'      => "\r\n",
            'newline'   => "\r\n",
            'smtp_crypto'=> 'tls',

        ];

        $this->load->library('email', $config);
        $this->load->model('Setting/m_parameter_project');

        $tmp = $this->m_parameter_project->get($project->id, "isi_email_approval");
        $tmp = str_replace("{{Dokumen}}", $name_dokumen->name, $tmp);
        $tmp = str_replace("{{Kode}}", $approval->dokumen_code, $tmp);
        $tmp = str_replace("{{User_create}}", $name_user_create->name, $tmp);
        $tmp = str_replace("{{Nilai}}", number_format($approval->dokumen_nilai), $tmp);
        $tmp = str_replace("{{Date_create}}", substr($approval->tgl_tambah, 0, 10), $tmp);
        $isi_email_wewenang = str_replace("{{Button_A}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#286090;border-radius:5px;color:white'> Approve </a>", $tmp);
        $isi_email_wewenang = str_replace("{{Button_R}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#d9534f;border-radius:5px;color:white'> Reject </a>", $isi_email_wewenang);
        $isi_email_wewenang = str_replace("{{Button_V}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Open EMS </a>", $isi_email_wewenang);

        foreach ($tujuan_email as $k => $v) {
            $tmp = str_replace("{{User}}", ucwords($v->name), $isi_email_wewenang);
            $this->email->clear(TRUE);
            $this->email->from($smtp_user, 'EMS Ciputra');
            $this->email->subject($this->m_parameter_project->get($project->id, "subjeck_email_approval"));
            $this->email->message($tmp);
            $this->email->to($v->email);

            // echo ("Email Wewenang $v->email");

            if ($this->email->send()) {
                // echo ("Success Kirim Email");
                // $email_success++;
            } else {
                // echo ("Gagal Kirim Email");
                
            }
        }
        $approval = (object)[];
        $approval->approval_status_id   = 3;
        $this->db->where("approval.id",$approval_id);
        $this->db->update("approval",$approval);

        $return->status = 1;
        $return->message = "Sukses - Approval akan di lanjutkan";
        return $return;

        // return "Sukses - Lanjut Approval";

    }
    public function reject($approval_id,$description){
        $return = (object)[];
        $return->status = 0;
        $return->message = "Gagal melakukan Reject";
        
        $project = $this->m_core->project();
        // $smtp_host = 'smtp.office365.com';
        // $smtp_user = 'no.reply@ciputra.com';
        // $smtp_pass = 'Som69936';
        // $smtp_port = '587';
        
        $group_user_id = $this->session->userdata["group"];
        $jabatan_user_id = $this->db->select("user_id,jabatan_id")
                                    ->from("group_user")
                                    ->where("id", $group_user_id)
                                    ->get()->row();
        $jabatan_id = $jabatan_user_id?$jabatan_user_id->jabatan_id:0;
        $user_id = $jabatan_user_id?$jabatan_user_id->user_id:0;
        if($jabatan_id == 0 || $user_id == 0){
            $return->status = 0;
            $return->message = "Gagal mendapatkan data jabatan dan/atau user";
            return $return;
        }
        $date = date('Y-m-d H:i:s.000');
        $approval = $this->db->select("*")
                                ->from('approval')
                                ->where('approval.id',$approval_id)
                                ->get()->row();
        $approval_wewenang = (object)[];
        $approval_wewenang_id = $this->db->select("approval_wewenang.id")
                                            ->from('approval')
                                            ->join('dokumen_jenis',
                                                    'dokumen_jenis.id = approval.dokumen_jenis_id')
                                            ->join('user',
                                                    'user.id = approval.user_id')
                                            ->join('approval_status',
                                                    'approval_status.id = approval.approval_status_id')
                                            ->join("approval_mengetahui",
                                                    "approval_mengetahui.approval_id = approval.id")
                                            ->join("approval_wewenang",
                                                    "approval_wewenang.approval_id = approval.id")
                                            ->join("approval_wewenang_user",
                                                    "approval_wewenang_user.approval_wewenang_id = approval_wewenang.id")
                                            ->where("approval_wewenang_user.group_user_id",$group_user_id) 
                                            ->where("approval_wewenang.approval_status_id",3)
                                            ->where("approval.id",$approval_id)
                                            ->distinct()
                                            ->get()->row();
        $approval_wewenang_id = $approval_wewenang_id?$approval_wewenang_id->id:0;
        if($approval_wewenang_id == 0){
            // return "approval_wewenang_id = 0";
            $return->status = 0;
            $return->message = "Gagal mendapatkan data Wewenang anda (izin)";
            return $return;
        }
        // $approval_wewenang->approval_id         = $approval_id;
        $approval_wewenang->tgl_approve         = $date;
        $approval_wewenang->jabatan_id          = $jabatan_id;
        $approval_wewenang->user_id             = $user_id;
        $approval_wewenang->group_user_id       = $group_user_id;
        $approval_wewenang->approval_status_id  = 2;
        $approval_wewenang->description         = $description;
        $this->db->where("approval_wewenang.id",$approval_wewenang_id);
        $this->db->where("approval_wewenang.approval_id",$approval_id);        
        $this->db->update("approval_wewenang",$approval_wewenang);
        $approval_wewenang->id                  = $approval_wewenang_id;
        

        if($approval->dokumen_jenis_id == 1){ // pemutihan
            $this->load->model('transaksi/m_pemutihan');
            $this->m_pemutihan->approve($approval->dokumen_id);
        }
        if($approval->dokumen_jenis_id == 2){ // void_pembayaran
            $this->db->where("t_pembayaran.id",$approval->dokumen_id);
            $this->db->set("t_pembayaran.is_void",0);
            $this->db->update("t_pembayaran");
            
        }
        $approval = (object)[];
        $approval->approval_status_id   = 2;
        $approval->tgl_closed           = date("Y-m-d");
        $approval->tgl_approve          = date("Y-m-d"); // nanti di hapus aja gak dibutuhin lagi

        $this->db->where("approval.id",$approval_id);
        $this->db->update("approval",$approval);


        $return->status = 1;
        $return->message = "Sukses - Approval di Closed dengan status Reject";
        return $return;

        // return "Sukses - Lanjut Approval";

    }
    public function approve_bu($id,$description,$tipe){
        $jabatan_id = $this->db->select("user_id,jabatan_id")
                                    ->from("group_user")
                                    ->where("id", $this->session->userdata["group"])
                                    ->get()->row(); 

        $user_id    = $jabatan_id->user_id;
        $jabatan_id = $jabatan_id->jabatan_id;


        $project = $this->m_core->project();
        $date = date('Y-m-d H:i:s.000');

        $id_approval_detail_tmp = $this->db->select("
                                                id,
                                                approval_jabatan_id,
                                                jarak_approve
                                                ")
                                            ->from("approval_detail")
                                            ->where("approval_id",$id)
                                            ->where("status_approve",0)
                                            ->get()->result();
        $id_approval_detail = 0;
        $jarak_tgl_closed = 0;
        $jarak_tgl_closed2 = 0;

        $email_jabatan_id = [0];
        $get_email_jabatan_id = 0;
        foreach ($id_approval_detail_tmp as $v) {
            if($get_email_jabatan_id == 1){
                $tmp = explode(",",$v->approval_jabatan_id);
                foreach ($tmp as $v2) {
                    array_push($email_jabatan_id,$v2);
                }   
                $get_email_jabatan_id = 0;
            }
            if($id_approval_detail == 0){
                $tmp = explode(",",$v->approval_jabatan_id);
                foreach ($tmp as $v2) {
                    if($v2 == $jabatan_id){
                        $id_approval_detail = $v->id;
                        $get_email_jabatan_id = 1;
                    }
                }
            }else{
                $jarak_tgl_closed2 = $jarak_tgl_closed2 + ((int)$v->jarak_approve)+1;
            }
            $jarak_tgl_closed = $jarak_tgl_closed + ((int)$v->jarak_approve)+1;

        }
        if($tipe == 1){

            

            $this->db->set('approve_user_id', $user_id);
            $this->db->set('approve_jabatan_id', $jabatan_id);
            $this->db->set('tgl_approve', $date);
            $this->db->set('status_approve', 1);
            $this->db->set('status_kirim_email', 1);
            $this->db->set('description', $description);
            $this->db->where('id', $id_approval_detail);
            // var_dump("approve_user_id ".$user_id."<br>");
            // var_dump("approve_jabatan_id ".$jabatan_id."<br>");
            // var_dump("tgl_approve ".$date."<br>");
            // var_dump("status_approve 1<br>");
            // var_dump("status_kirim_email 1<br>");
            $this->db->update('approval_detail');


            
            $approve_semua = $this->db->select("
                                                        case
                                                            WHEN count(status_approve)=sum(convert(int,status_approve)) THEN 1
                                                            ELSE 0
                                                        END as c")
                                        ->from("approval_detail")
                                        ->where("approval_detail.approval_id ",$id)
                                        ->get()->row()->c;
            if($approve_semua == 1){
                $tabel_dokumen = $this->db->select("
                                                approval.source_id,
                                                dokumen_jenis.source_table")
                                        ->from("approval")
                                        ->join("dokumen_jenis",
                                                "dokumen_jenis.id = approval.dokumen_jenis_id")
                                        ->where("approval.id",$id)
                                        ->get()->row();
                $source_id = $tabel_dokumen->source_id;
                $tabel_dokumen = $tabel_dokumen->source_table;
                $this->db->set("status",1);
                $this->db->where("id",$source_id);
                $this->db->update($tabel_dokumen);
                
                $this->db->set("status_dokumen",3);
                $this->db->set('tgl_closed', date("Y-m-d"));
                $this->db->where("id",$id);
                $this->db->update("approval");
                
                return 1;
            }else{
                // untuk kirim email
                $email = $this->db->select("email, user.name")
                    ->from("group_user")
                    ->join("user",
                            "user.id = group_user.user_id")
                    ->where_in("jabatan_id",$email_jabatan_id)
                    ->where("group_user.project_id",$project->id)
                    ->where("email is not null")
                    ->distinct()->get()->result(); 
                $this->load->model('Setting/m_parameter_project');
                
                $config = [
                    'mailtype'  => 'html',
                    'charset'   => 'utf-8',
                    'protocol'  => 'smtp',
                    'smtp_host' => $this->m_parameter_project->get($project->id,"smtp_host"),
                    'smtp_user' => $this->m_parameter_project->get($project->id,"smtp_user"),
                    'smtp_pass' => $this->m_parameter_project->get($project->id,"smtp_pass"),
                    'smtp_port' => $this->m_parameter_project->get($project->id,"smtp_port"),
                    'crlf'      => "\r\n",
                    'newline'   => "\r\n"
                ];
                $this->load->library('email',$config);
                $this->email->from($this->m_parameter_project->get($project->id,"smtp_user"), 'EMS Ciputra');
                $komponen = $this->db->select("
                                                dokumen_jenis.name as dokumen,
                                                approval.dokumen_code,
                                                user_create.name as user_create,
                                                approval.dokumen_nilai,
                                                approval.tgl_tambah")
                                        ->from("approval")
                                        ->join("dokumen_jenis",
                                                "dokumen_jenis.id = approval.dokumen_jenis_id")
                                        ->join("user as user_create",
                                                "user_create.id = approval.create_user_id")
                                        ->where("approval.id",$id)
                                        ->get()->row();
                $tmp = $this->m_parameter_project->get($project->id,"isi_email_approval");
                $tmp = str_replace("{{Dokumen}}",$komponen->dokumen,$tmp);
                $tmp = str_replace("{{Kode}}",$komponen->dokumen_code,$tmp);
                $tmp = str_replace("{{User_create}}",$komponen->user_create,$tmp);
                $tmp = str_replace("{{Nilai}}",number_format($komponen->dokumen_nilai),$tmp);
                $tmp = str_replace("{{Date_create}}",substr($komponen->tgl_tambah,0,10),$tmp);
                
                $tmp = str_replace("{{Button_V}}","<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Login </a>",$tmp);
                $tmp = str_replace("{{Button_A}}","<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#286090;border-radius:5px;color:white'> Approve </a>",$tmp);
                $tmp = str_replace("{{Button_R}}","<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#286090;border-radius:5px;color:white'> Approve </a>",$tmp);
                $tmp = str_replace("{{Button_L}}","<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Login </a>",$tmp);
                foreach ($email as $v) {
                    $tmp2 = str_replace("{{User}}",ucwords($v->name),$tmp);
                    $this->email->clear(TRUE);
                    $this->email->from($this->m_parameter_project->get($project->id,"smtp_user"), 'EMS Ciputra');
                    $this->email->subject($this->m_parameter_project->get($project->id,"subjeck_email_approval"));
                    $this->email->message($tmp2);
                    $this->email->to($v->email);
                    if($this->email->send()){
                    }else{
                    }                   
                }
                // end kirim email

                // update tgl closed
                
                $jarak_tgl_closed = $jarak_tgl_closed + ((int)$this->db->select("jarak_request_closed")
                                                            ->from("approval")
                                                            ->where("id",$id)
                                                            ->get()->row()->jarak_request_closed
                                                        )+1;
                $create_date = substr($this->db->select("tgl_tambah")
                                ->from("approval")
                                ->where("id",$id)
                                ->get()->row()->tgl_tambah,0,10);
                
                $crete_closed = date('Y-m-d',strtotime($create_date . "+".(1+$jarak_tgl_closed)." days"));

                $last_approve_date = (int)$this->db->select("tgl_closed")
                                        ->from("approval")
                                        ->where("id",$id)
                                        ->get()->row()->tgl_closed;
                $last_approve_closed = date('Y-m-d',strtotime($last_approve_date . "+".(1+$jarak_tgl_closed2)." days"));

                if(strtotime($crete_closed) < strtotime($last_approve_closed)){
                    $this->db->set('tgl_closed', $crete_closed);
                    // var_dump("crete_closed ".$crete_closed."<br>");

                }else{
                    $this->db->set('tgl_closed', $last_approve_closed);
                    // var_dump("last_approve_closed ".$last_approve_closed."<br>");

                }            
                $this->db->where('id', $id);

                $this->db->update('approval');
                return 1;
            }
        }else{
            // echo("ini");
            $this->db->set('approve_user_id', $user_id);
            $this->db->set('approve_jabatan_id', $jabatan_id);
            $this->db->set('tgl_approve', $date);
            $this->db->set('status_approve', 2);
            $this->db->set('description', $description);
            $this->db->where('id', $id_approval_detail);
            $this->db->update('approval_detail');
        }

    }
}
