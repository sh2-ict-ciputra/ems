<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_unit extends CI_Model
{
    public function getService($unit_id)
    {
        $query = $this->db->query("
                            SELECT 
                                *
                            FROM v_unit_service
                            WHERE unit_id = $unit_id
                        ");
        return $query->result();
    }
    public function getTagihan($unit_id){
        $query = $this->db->query("
                    SELECT 
                        vus.*,
                        CASE 
                            WHEN vta.total_tagihan IS NOT NULL THEN vta.total_tagihan
                            WHEN vtl.total_tagihan IS NOT NULL THEN vtl.total_tagihan
                        END as total_tagihan
                    FROM v_unit_service as vus
                    LEFT JOIN v_tagihan_air as vta
                        on vta.unit_id = vus.unit_id
                        AND vus.service_jenis = 1
                    LEFT JOIN v_tagihan_lingkungan as vtl
                        on vtl.unit_id = vus.unit_id
                        AND vus.service_jenis = 2
                    WHERE vus.unit_id = $unit_id 
                    AND
                        CASE 
                            WHEN vta.total_tagihan IS NOT NULL THEN vta.total_tagihan
                            WHEN vtl.total_tagihan IS NOT NULL THEN vtl.total_tagihan
                        END IS NOT NULL
                ");
        $tagihan = $query->result();
        // echo("<pre>");
        //     print_r($tagihan);
        // echo("</pre>");
        
        // $query = $this->db->query("
        //                             SELECT
        //                                 service ='Air',
        //                                 t_tagihan_air.periode,
        //                                 t_tagihan_air.nilai +
        //                                 t_tagihan_air.denda +
        //                                 t_tagihan_air.penalti
                                        
        //                             FROM unit
        //                             JOIN t_tagihan_air
        //                                 on t_tagihan_air.unit_id = unit.id
        //                             WHERE unit.id = $unit_id    
        // ");
        // $tagihanAir = $query->result();
        return $tagihan;
    }
    public function getUnitBlokKawasan($unit_id){
        $query = $this->db->query("
                                    SELECT 
                                        unit.no_unit as unit,
                                        blok.name as blok,
                                        kawasan.name as kawasan
                                    FROM unit
                                    JOIN blok
                                        on blok.id = unit.blok_id
                                    JOIN kawasan
                                        on kawasan.id = blok.kawasan_id
                                    WHERE unit.id = $unit_id
        ");
        return $query->row();
    }
    public function test(){

        $awal_periode = $this->db->select("*")
                            ->from("v_tagihan_lingkungan")
                            ->where("periode <= '2019-06-01'")
                            ->where("unit_id",202)
                            ->where("status_bayar_flag",0)
                            ->order_by('periode')
                            ->get()->row();
        $awal_periode = $awal_periode?$awal_periode->periode:null;
        if($awal_periode){
            $query = $this->db->select("*")
                                ->from("v_tagihan_lingkungan")
                                ->where("periode <= '2019-06-01'")
                                ->where("periode >= '$awal_periode'")
                                ->where("unit_id",202)
                                ->get()->result();
            // echo("<pre>");
            //     print_r($awal_periode);
            // echo("</pre>");
            // echo("<pre>");
            //     print_r($query);
            // echo("</pre>");
        }                            
                
    }
    public function void_pembayaran($pembayaran_id,$description=null){
        $smtp_host = 'smtp.office365.com';
        $smtp_user = 'no.reply@ciputra.com';
        $smtp_pass = 'Som69936';
        $smtp_port = '587';

        $this->load->helper('url');

        $this->db->trans_start();
        $project = $this->m_core->project();
        $date = date("Y-m-d");
        $create_user_id = $this->db->select("id")
            ->from("user")
            ->where("username", $this->session->userdata["username"])
            ->get()->row()->id;
        $create_jabatan_id = $this->db->select("jabatan_id")
            ->from("group_user")
            ->where("id", $this->session->userdata["group"])
            ->get()->row()->jabatan_id;
        $nilai_dokumen = $this->db->select("sum(t_pembayaran_detail.bayar + t_pembayaran_detail.bayar_deposit) as total")
                                    ->from("t_pembayaran_detail")
                                    ->where("t_pembayaran_detail.t_pembayaran_id",$pembayaran_id)
                                    ->get()->row();
        $nilai_dokumen = $nilai_dokumen?$nilai_dokumen->total:0;
        if($nilai_dokumen == 0){
            return 0;
        }
        $this->db->where("t_pembayaran.id",$pembayaran_id);
        $this->db->set("t_pembayaran.is_void",3);
        $this->db->update("t_pembayaran");

        $this->load->model('Setting/Akun/m_permission_dokumen');
        $permission_wewenang = $this->m_permission_dokumen->get_wewenang($project->id, $nilai_dokumen);
        $permission_mengetahui = $this->m_permission_dokumen->get_mengetahui($project->id, $nilai_dokumen);
        if(isset($permission_wewenang) and isset($permission_mengetahui)){
            $approval                       = (object) [];

            $approval->approval_status_id   = 0;
            $approval->user_id              = $create_user_id;
            $approval->tgl_tambah           = date("Y-m-d H:i:s.000");
            $approval->dokumen_jenis_id     = $this->db->select("id")->from("dokumen_jenis")->where("code", "void_pembayaran")->get()->row()->id;
            $approval->dokumen_id           = $pembayaran_id;
            $approval->jabatan_id           = $create_jabatan_id;
            $approval->project_id           = $project->id;
            $approval->dokumen_code         = 'Void Pembayaran';
            $approval->dokumen_nilai        = $nilai_dokumen;
            $approval->jarak_approval_closed = 0;
            $approval->group_user_id        = isset($this->session->userdata['group'])?$this->session->userdata['group']:'0';
            $approval->description          = $description;
            foreach ($permission_wewenang as $k => $v) {
                $approval->jarak_approval_closed += $v->jarak_approve;
            }
            $approval->tgl_closed           = (new DateTime(date("Y-m-d"). " + $approval->jarak_approval_closed day"))->format("Y-m-d");
            $this->db->insert("approval",$approval);                    
            $approval->id = $this->db->insert_id();

            // echo ("approval<pre>");
            // print_r($approval);
            // echo ("</pre>");            
            $tujuan_email = (object)[];
            foreach ($permission_wewenang as $k => $v) {
                $list_group_user_id = explode(',',$v->group_user_id);  

                $approval_wewenang    = (object) [];
                $approval_wewenang->approval_status_id = 0;
                if($k == 0){
                    $tujuan_email->wewenang = $this->db->select('user.name, user.email')
                                            ->from('group_user')
                                            ->join("user",
                                                    "user.id = group_user.user_id")
                                            ->where_in("group_user.id",$list_group_user_id)
                                            ->distinct()
                                            ->get()->result();
                    $approval_wewenang->approval_status_id = 3;
                }

                $approval_wewenang->tgl_kirim_email = $approval->tgl_tambah;
                $approval_wewenang->approval_id     = $approval->id;
                $approval_wewenang->jarak_approve   = $v->jarak_approve;
                $this->db->insert("approval_wewenang",$approval_wewenang);                 
                $approval_wewenang->id = $this->db->insert_id();
    
                // echo("approval_wewenang<pre>");
                //     print_r($approval_wewenang);
                // echo("</pre>");

                $approval_wewenang_user    = (object) [];
                $approval_wewenang_user->approval_wewenang_id = $approval_wewenang->id;
                foreach ($list_group_user_id as $k2 => $v2) {
                    $approval_wewenang_user->group_user_id = $v2;
                    $group_user = $this->db->select("user_id,jabatan_id,project_id")
                                                ->from("group_user")
                                                ->where_in('id',$v2)
                                                ->get()->row();
                    $approval_wewenang_user->user_id = $group_user->user_id;
                    $approval_wewenang_user->jabatan_id = $group_user->jabatan_id;
                    $approval_wewenang_user->project_id = $group_user->project_id;
                    $this->db->insert("approval_wewenang_user",$approval_wewenang_user);                    

                    // echo("approval_wewenang_user<pre>");
                    //     print_r($approval_wewenang_user);
                    // echo("</pre>");
                                        

                }
            }
            $approval_mengetahui    = (object) [];
            $approval_mengetahui->approval_id     = $approval->id;
            $list_group_user_id = explode(',',$permission_mengetahui->group_user_id);
            $tujuan_email->mengetahui = $this->db->select('user.name, user.email')
                                            ->from('group_user')
                                            ->join("user",
                                                    "user.id = group_user.user_id")
                                            ->where_in("group_user.id",$list_group_user_id)
                                            ->distinct()
                                            ->get()->result();
            foreach ($list_group_user_id as $k => $v) {
                $group_user = $this->db->select("user_id,jabatan_id")
                                        ->from("group_user")
                                        ->where('id',$v)
                                        ->get()->row();
                $approval_mengetahui->user_id = $group_user->user_id;
                $approval_mengetahui->jabatan_id = $group_user->jabatan_id;
                $approval_mengetahui->group_user_id = $v;
                $approval_mengetahui->tgl_kirim_email = $approval->tgl_tambah;
                $this->db->insert("approval_mengetahui",$approval_mengetahui);                    

                // echo("approval_mengetahui<pre>");
                //     print_r($approval_mengetahui);
                // echo("</pre>");
                
            }
            // echo("tujuan_email<pre>");
            //     print_r($tujuan_email);
            // echo("</pre>");



            $this->load->model('Setting/m_parameter_project');

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
            // print_r($config);
            // $this->db->selec
            $this->email->from($this->m_parameter_project->get($project->id, "smtp_user"), 'Ciputra EMS - Approval');
            $name_user_create = $this->db->select('name') 
                                            ->from('user')
                                            ->where('user.id',$approval->user_id)
                                            ->get()->row();
            $name_dokumen = $this->db->select('name') 
                                        ->from('dokumen_jenis')
                                        ->where('dokumen_jenis.id',$approval->dokumen_jenis_id  )
                                        ->get()->row();
        
            $tmp = $this->m_parameter_project->get($project->id, "isi_email_approval");
            $tmp = str_replace("{{Dokumen}}", $name_dokumen->name, $tmp);
            $tmp = str_replace("{{Kode}}", $approval->dokumen_code, $tmp);
            $tmp = str_replace("{{User_create}}", $name_user_create->name, $tmp);
            $tmp = str_replace("{{Nilai}}", number_format($approval->dokumen_nilai), $tmp);
            $tmp = str_replace("{{Date_create}}", substr($approval->tgl_tambah, 0, 10), $tmp);

            // $tmp = str_replace("{{Button_V}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Login </a>", $tmp);
            $isi_email_wewenang = str_replace("{{Button_A}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#286090;border-radius:5px;color:white'> Approve </a>", $tmp);
            $isi_email_wewenang = str_replace("{{Button_R}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#d9534f;border-radius:5px;color:white'> Reject </a>", $isi_email_wewenang);
            $isi_email_wewenang = str_replace("{{Button_V}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Open EMS </a>", $isi_email_wewenang);

            $isi_email_mengetahui = str_replace("{{Button_V}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Open EMS </a>", $tmp);
            $isi_email_mengetahui = str_replace("{{Button_A}}", "", $isi_email_mengetahui);
            $isi_email_mengetahui = str_replace("{{Button_R}}", "", $isi_email_mengetahui);
            // $tmp = str_replace("{{Button_A}}",$unit->project,$tmp);
            // $parameter_delay = explode(";",$this->m_parameter_project->get($project->id,"delay_email"));
            // var_dump($parameter_delay);
            foreach ($tujuan_email->mengetahui as $k => $v) {
                $tmp = str_replace("{{User}}", ucwords($v->name), $isi_email_mengetahui);
                $this->email->clear(TRUE);
                $this->email->from($smtp_user, 'EMS Ciputra');
                $this->email->subject($this->m_parameter_project->get($project->id, "subjeck_email_approval"));
                $this->email->message($tmp);
                $this->email->to($v->email);
                // echo ("Email Mengetahui $v->email");

                if ($this->email->send()) {
                    // echo ("Success Kirim Email");
                    // $email_success++;
                } else {
                    // echo ("Gagal Kirim Email");
                }
            }
            foreach ($tujuan_email->wewenang as $k => $v) {
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

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return [
                    'status' => 1,
                    'message' => 'Sukses membuat request void'
                ];
            }
            return "Tidak Memiliki Izin";
        }

    }
}
