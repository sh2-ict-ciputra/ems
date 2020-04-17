<?php
defined("BASEPATH") or exit("No direct script access allowed");

class m_pemutihan extends CI_Model
{


    public function get_kawasan()
    {
        $project = $this->m_core->project();

        return $this->db
            ->select("
                        id,
                        code,
                        name")
            ->from("kawasan")
            ->where("project_id", $project->id)
            ->get()->result();
    }
    public function get_blok($kawasan_id)
    {
        if ($kawasan != "all") {
            return $this->db
                ->select("
                                id,
                                code,
                                name")
                ->from("blok")
                ->where("id", $kawasan_id)
                ->get()->result();
        }
        return $this->db
            ->select("
                            id,
                            code,
                            name")
            ->from("blok")
            ->where("id", $kawasan_id)
            ->get()->result();
    }
    public function get_service()
    {
        return $this->db
            ->select("id,jenis_service as name")
            ->from("service_jenis")
            ->where_in("id",[1,2])
            ->get()->result();
    }
    public function get_unit($blok_id, $kawasan_id, $periode_awal, $periode_akhir, $metode_tagihan)
    {
        
        $periode_awal = substr($periode_awal, 3, 4) . "-" . substr($periode_awal, 0, 2) . "-01";
        $periode_akhir = substr($periode_akhir, 3, 4) . "-" . substr($periode_akhir, 0, 2) . "-01";
        $project = $this->m_core->project();
        $query = $this->db
            ->select("
                        unit_id,
                        kawasan.name as kawasan,
                        blok.name as blok,
                        unit.no_unit,
                        pemilik.name as pemilik")
            ->from("v_sales_force_bill")
            ->join(
                "unit",
                "unit.id = v_sales_force_bill.unit_id"
            )
            ->join(
                "blok",
                "blok.id = v_sales_force_bill.blok_id"
            )
            ->join(
                "kawasan",
                "kawasan.id = v_sales_force_bill.kawasan_id"
            )
            ->join(
                "customer as pemilik",
                "pemilik.id = unit.pemilik_customer_id"
            )
            ->where("v_sales_force_bill.periode >= '$periode_awal'")
            ->where("v_sales_force_bill.periode <= '$periode_akhir'")
            ->where("unit.project_id", $project->id)
            ->group_by("
                        unit_id,
                        kawasan.name,
                        blok.name,
                        unit.no_unit,
                        pemilik.name");
        $service_air        = false;
        $service_lingkungan = false;
        foreach ($metode_tagihan as $v) {
            if ($v == 2) {
                $service_air = true;
            } elseif ($v == 1) {
                $service_lingkungan = true;
            }
        }
        if ($service_air && $service_lingkungan) {
            $query = $query
                ->select("
                        sum(tagihan_air_tanpa_ppn
                        +tagihan_lingkungan_tanpa_ppn) as nilai_pokok,
                        sum(denda_lingkungan+denda_air) as denda,
                        sum(tagihan_air_tanpa_ppn
                        +tagihan_lingkungan_tanpa_ppn
                        +denda_lingkungan
                        +denda_air) as total, 
                    ");
        } elseif ($service_air) {
            $query = $query
                ->select("
                        sum(tagihan_air_tanpa_ppn) as nilai_pokok,
                        sum(denda_air) as denda,
                        sum(tagihan_air_tanpa_ppn
                        +denda_air) as total
                    ");
        } elseif ($service_lingkungan) {
            $query = $query
                ->select("
                        sum(tagihan_lingkungan_tanpa_ppn) as nilai_pokok,
                        sum(denda_lingkungan) as denda,
                        sum(tagihan_lingkungan_tanpa_ppn
                        +denda_lingkungan) as total
                    ");
        } else {
            $query = $query
                ->select("
                        0 as nilai_pokok,
                        0 as denda,
                        0 as total
                    ");
        }
        if ($blok_id != 'all') {
            $query = $query->where("v_sales_force_bill.blok_id", $blok_id);
        }
        if ($kawasan_id != 'all') {
            $query = $query->where("v_sales_force_bill.kawasan_id", $kawasan_id);
        }
        return $query->get()->result();
    }
    public function save($data, $nama_file)
    {
        $smtp_host = 'smtp.office365.com';
        $smtp_user = 'no.reply@ciputra.com';
        $smtp_pass = 'Som69936';
        $smtp_port = '587';

        $this->load->helper('url');

        $this->db->trans_start();
        $project = $this->m_core->project();
        $data = (object) $data;
        $data->nama_file = $nama_file;
        $date = date("Y-m-d");
        $create_user_id = $this->db->select("id")
            ->from("user")
            ->where("username", $this->session->userdata["username"])
            ->get()->row()->id;
        $create_jabatan_id = $this->db->select("jabatan_id")
            ->from("group_user")
            ->where("id", $this->session->userdata["group"])
            ->get()->row()->jabatan_id;

        $pemutihan = (object) [];
        $pemutihan->project_id      = $project->id;
        $pemutihan->periode_awal    = substr($data->periode_awal, 3, 4) . "-" . substr($data->periode_awal, 0, 2) . "-" . "01";
        $pemutihan->periode_akhir   = substr($data->periode_akhir, 3, 4) . "-" . substr($data->periode_akhir, 0, 2) . "-" . "01";
        $pemutihan->masa_awal       = substr($data->masa_awal, 6, 4) . "-" . substr($data->masa_awal, 3, 2) . "-" . substr($data->masa_awal, 0, 2);
        $pemutihan->masa_akhir      = substr($data->masa_akhir, 6, 4) . "-" . substr($data->masa_akhir, 3, 2) . "-" . substr($data->masa_akhir, 0, 2);
        $pemutihan->description     = $data->description;
        $pemutihan->tgl_tambah      = $date;
        $pemutihan->tambah_user_id  = $create_user_id;
        $pemutihan->approval_id     = 0;
        $pemutihan->status          = 0;
        $pemutihan->file            = $data->nama_file;
        $pemutihan->code            = $data->kode;
        $this->db->insert("pemutihan",$pemutihan);
        $pemutihan->id = $this->db->insert_id();
        
        $pemutihan_nilai                        = (object)[];
        $pemutihan_nilai->pemutihan_id          = $pemutihan->id;
        $pemutihan_nilai->nilai_tagihan_type    = $data->nilai_tagihan_type;
        $pemutihan_nilai->nilai_tagihan         = str_replace(",","",$data->nilai_tagihan);
        $pemutihan_nilai->nilai_denda_type      = $data->nilai_denda_type;
        $pemutihan_nilai->nilai_denda           = str_replace(",","",$data->nilai_denda);
        $pemutihan_nilai->perkiraan_pemutihan_nilai_tagihan     = str_replace(",","",$data->perkiraan_pemutihan_nilai_tagihan);
        $pemutihan_nilai->perkiraan_pemutihan_nilai_denda       = str_replace(",","",$data->perkiraan_pemutihan_nilai_denda);
        $pemutihan_nilai->perkiraan_pemutihan_total             = str_replace(",","",$data->perkiraan_pemutihan_total);
        $this->db->insert("pemutihan_nilai",$pemutihan_nilai);

        $periode_awal = new DateTime($pemutihan->periode_awal);
        $periode_akhir = (new DateTime($pemutihan->periode_akhir))->modify("+1 month");

        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($periode_awal, $interval, $periode_akhir);
        $periode_akhir->modify("+1 month");
        $pemutihan_service = (object)[];
        $pemutihan_service->pemutihan_id        = $pemutihan->id;
        $pemutihan_unit = [];
        $pemutihan_unit = (object)[];
        $pemutihan_unit->pemutihan_id              = $pemutihan->id;
        $total_tagihan = 0;
        $total_denda = 0;

        
        foreach ($data->service_jenis as $v) {
            $pemutihan_service->service_id          = $this->db->select("id")
                ->from("service")
                ->where("project_id", $project->id)
                ->where("service_jenis_id", $v)
                ->where("service.active",1)
                ->where("service.delete",0)
                ->get()->row();
            if ($pemutihan_service->service_id) {
                $pemutihan_service->service_id          = $pemutihan_service->service_id->id;
                $pemutihan_service->service_jenis_id    = $v;
                $this->db->insert("pemutihan_service", $pemutihan_service);
            }
        }

        if(!$data->unit_id)
            return "Tidak ada unit yang terpilih";

        $tagihan = $this->db->select("
                                        unit_id,
                                        tagihan_lingkungan_tanpa_ppn as lingkungan_nilai_pokok,
                                        tagihan_lingkungan as lingkungan_nilai_pokok_ppn,
                                        denda_lingkungan as lingkungan_nilai_denda,
                                        tagihan_air_tanpa_ppn as air_nilai_pokok,
                                        denda_air as air_nilai_denda,
                                        periode
                                    ")
                        ->from("v_sales_force_bill")
                        ->where_in("unit_id", $data->unit_id)
                        ->where("periode >=", $pemutihan->periode_awal)
                        ->where("periode <=", $pemutihan->periode_akhir)
                        ->order_by("unit_id,periode")
                        ->get()->result();
        $pemutihan_tagihan_type = $pemutihan_nilai->nilai_tagihan_type;
        $pemutihan_tagihan      = $pemutihan_nilai->nilai_tagihan;
        $pemutihan_denda_type   = $pemutihan_nilai->nilai_denda_type; 
        $pemutihan_denda        = $pemutihan_nilai->nilai_denda;

        if($pemutihan_tagihan_type == 0){
            $jatah_pemutihan_tagihan_unit = array_flip($data->unit_id);
            foreach($jatah_pemutihan_tagihan_unit as $k => $v){
                $jatah_pemutihan_tagihan_unit[$k] = $pemutihan_tagihan;
            }
        }
        if($pemutihan_denda_type == 0){
            $jatah_pemutihan_denda_unit = array_flip($data->unit_id);
            foreach($jatah_pemutihan_denda_unit as $k => $v){
                $jatah_pemutihan_denda_unit[$k] = $pemutihan_denda;
            }
        }
        
        $jatah_pemutihan_unit = array_flip($data->unit_id);           

        foreach ($tagihan as $k => $v) {
            $total_tagihan += $v->lingkungan_nilai_pokok;
            $total_tagihan += $v->air_nilai_pokok;
            $total_denda += $v->lingkungan_nilai_denda;
            $total_denda += $v->air_nilai_denda;
 
            $pemutihan_unit->unit_id = $v->unit_id;
            $pemutihan_unit->periode = $v->periode;
            if(array_search(2,$data->service_jenis) !== false){
                $pemutihan_unit->service_jenis_id   = 2;
                if($pemutihan_tagihan_type == 0){
                    if($jatah_pemutihan_tagihan_unit[$v->unit_id] > 0){
                        if($jatah_pemutihan_tagihan_unit[$v->unit_id] <= $v->air_nilai_pokok){
                            $pemutihan_unit->pemutihan_nilai_tagihan = $jatah_pemutihan_tagihan_unit[$v->unit_id];
                            $jatah_pemutihan_tagihan_unit[$v->unit_id] = 0;
                        }else{
                            $pemutihan_unit->pemutihan_nilai_tagihan = $v->air_nilai_pokok;
                            $jatah_pemutihan_tagihan_unit[$v->unit_id] -= $v->air_nilai_pokok;
                        }
                    }else{
                        $pemutihan_unit->pemutihan_nilai_tagihan = 0;
                    }
                }else{
                    $pemutihan_unit->pemutihan_nilai_tagihan = $v->air_nilai_pokok * $pemutihan_nilai->nilai_tagihan /100;
                }
                if($pemutihan_denda_type == 0){
                    if($jatah_pemutihan_denda_unit[$v->unit_id] > 0){
                        if($jatah_pemutihan_denda_unit[$v->unit_id] <= $v->air_nilai_denda){
                            $pemutihan_unit->pemutihan_nilai_denda = $jatah_pemutihan_denda_unit[$v->unit_id];
                            $jatah_pemutihan_denda_unit[$v->unit_id] = 0;
                        }else{
                            $pemutihan_unit->pemutihan_nilai_denda = $v->air_nilai_denda;
                            $jatah_pemutihan_denda_unit[$v->unit_id] -= $v->air_nilai_denda;
                        }
                    }else{
                        $pemutihan_unit->pemutihan_nilai_denda = 0;
                    }
                }else{
                    $pemutihan_unit->pemutihan_nilai_denda = $v->air_nilai_denda * $pemutihan_nilai->nilai_denda /100;
                }
                if($pemutihan_unit->pemutihan_nilai_denda + $pemutihan_unit->pemutihan_nilai_tagihan > 0 ){
                    // echo("<pre>");
                    //     print_r($pemutihan_unit);
                    // echo("</pre>");
                    $this->db->insert("pemutihan_unit",$pemutihan_unit);                    
                }
                
            }
            if(array_search(1,$data->service_jenis) !== false){
                $pemutihan_unit->service_jenis_id   = 1;
                if($pemutihan_tagihan_type == 0){
                    if($jatah_pemutihan_tagihan_unit[$v->unit_id] > 0){
                        if($jatah_pemutihan_tagihan_unit[$v->unit_id] <= $v->lingkungan_nilai_pokok){
                            $pemutihan_unit->pemutihan_nilai_tagihan = $jatah_pemutihan_tagihan_unit[$v->unit_id];
                            $jatah_pemutihan_tagihan_unit[$v->unit_id] = 0;
                        }else{
                            $pemutihan_unit->pemutihan_nilai_tagihan = $v->lingkungan_nilai_pokok;
                            $jatah_pemutihan_tagihan_unit[$v->unit_id] -= $v->lingkungan_nilai_pokok;
                        }
                    }else{
                        $pemutihan_unit->pemutihan_nilai_tagihan = 0;
                    }
                }else{
                    if($v->lingkungan_nilai_pokok_ppn == $v->lingkungan_nilai_pokok){
                        $pemutihan_unit->pemutihan_nilai_tagihan = $v->lingkungan_nilai_pokok * $pemutihan_nilai->nilai_tagihan /100;
                    }else{
                        $pemutihan_unit->pemutihan_nilai_tagihan = $v->lingkungan_nilai_pokok_ppn * $pemutihan_nilai->nilai_tagihan /100 / 1.1;
                    }
                }
                if($pemutihan_denda_type == 0){
                    if($jatah_pemutihan_denda_unit[$v->unit_id] > 0){
                        if($jatah_pemutihan_denda_unit[$v->unit_id] <= $v->lingkungan_nilai_denda){
                            $pemutihan_unit->pemutihan_nilai_denda = $jatah_pemutihan_denda_unit[$v->unit_id];
                            $jatah_pemutihan_denda_unit[$v->unit_id] = 0;
                        }else{
                            $pemutihan_unit->pemutihan_nilai_denda = $v->lingkungan_nilai_denda;
                            $jatah_pemutihan_denda_unit[$v->unit_id] -= $v->lingkungan_nilai_denda;
                        }
                    }else{
                        $pemutihan_unit->pemutihan_nilai_denda = 0;
                    }
                }else{
                    $pemutihan_unit->pemutihan_nilai_denda = $v->lingkungan_nilai_denda * $pemutihan_nilai->nilai_denda /100;
                }
                if($pemutihan_unit->pemutihan_nilai_denda + $pemutihan_unit->pemutihan_nilai_tagihan > 0 ){
                    // echo("<pre>");
                    //     print_r($pemutihan_unit);
                    // echo("</pre>");
                    $this->db->insert("pemutihan_unit",$pemutihan_unit);
                }
            }
            // var_dump($jatah_pemutihan_tagihan_unit[$v->unit_id]);
            // var_dump($jatah_pemutihan_denda_unit[$v->unit_id]);
            
            
        }
        // var_dump($total_tagihan);
        // var_dump($total_denda);

        
        
        
        $this->load->model('Setting/Akun/m_permission_dokumen');
        $permission_wewenang = $this->m_permission_dokumen->get_wewenang($project->id, $pemutihan_nilai->perkiraan_pemutihan_total);
        $permission_mengetahui = $this->m_permission_dokumen->get_mengetahui($project->id, $pemutihan_nilai->perkiraan_pemutihan_total);
        if(isset($permission_wewenang) and isset($permission_mengetahui)){
            // echo ("<pre>");
            // print_r($permission_wewenang);
            // echo ("</pre>");
            
            // echo ("<pre>");
            // print_r($permission_mengetahui);
            // echo ("</pre>");
            $approval           = (object) [];

            $approval->approval_status_id   = 0;
            $approval->user_id              = $create_user_id;
            $approval->tgl_tambah           = date("Y-m-d H:i:s.000");
            $approval->dokumen_jenis_id     = $this->db->select("id")->from("dokumen_jenis")->where("code", "pemutihan")->get()->row()->id;
            $approval->dokumen_id            = $pemutihan->id;
            $approval->jabatan_id           = $create_jabatan_id;
            $approval->project_id           = $project->id;
            $approval->dokumen_code         = $data->kode;
            $approval->dokumen_nilai        = $pemutihan_nilai->perkiraan_pemutihan_total;
            $approval->jarak_approval_closed = 0;
            $approval->group_user_id        = isset($this->session->userdata['group'])?$this->session->userdata['group']:'0';
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

        
            // if ($this->db->trans_status() === FALSE) {
            //     $this->db->trans_rollback();
            //     return false;
            // } else {
            //     $this->db->trans_commit();
            //     return true;
            // }
            
            // return "Tidak Memiliki Izin";
            // die;


            if (true) {
                // $approval->jarak_request_closed    =  (int) $tmp->jarak_approve;
                // $approval->tgl_closed           =  date('Y-m-d', strtotime(date("Y/m/d") . "+" . ($approval->jarak_request_closed + 1) . " days"));
                // $approval->status_approval = 0;
                // $this->db->insert("approval", $approval);



                // $approval_detail->approval_id   = $this->db->insert_id();
                // $approval_detail->status_approve  = 0;


                // $jabatan_approval = [];

                // foreach ($permission as $k => $v) {
                //     $approval_detail->jarak_approve  = $v->jarak_approve;

                //     $tmp = explode(",", $v->jabatan_id);
                //     $tmp2 = [];
                //     foreach ($tmp as $v2) {
                //         if ($v2 != $create_jabatan_id)
                //             array_push($tmp2, $v2);
                //     }
                //     $approval_detail->approval_jabatan_id  = implode(",", $tmp2);
                //     $this->db->insert("approval_detail", $approval_detail);
                // }

                // echo ("jabatan_approval<pre>");
                // print_r($jabatan_approval);
                // echo ("</pre>");

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
                    return true;
                }
            }
            return "Tidak Memiliki Izin";
        }
    }
    public function approve($id){
        $this->db->where("pemutihan.id",$id);
        $this->db->update("pemutihan",["status"=>1]);
    }
    public function reject($id){
        $this->db->where("pemutihan.id",$id);
        $this->db->update("pemutihan",["status"=>2]);
    }
    
    public function save2($data, $nama_file)
    {

        $this->db->trans_start();
        $project = $this->m_core->project();
        $data = (object) $data;
        $data->nama_file = $nama_file;
        $date = date("Y-m-d");
        $create_user_id = $this->db->select("id")
            ->from("user")
            ->where("username", $this->session->userdata["username"])
            ->get()->row()->id;
        $create_jabatan_id = $this->db->select("jabatan_id")
            ->from("group_user")
            ->where("id", $this->session->userdata["group"])
            ->get()->row()->jabatan_id;

        // $approval = (object)[];
        // status 
        // $approval->status_approval = 0;
        // $approval->approval_jenis_id = 1;
        // $approval->create_user_id = $create_user_id;
        // // $approval->approval_user_id = ;
        // $approval->tgl_tambah = $date;


        // $approval->tgl_approve = ;
        // $approval->create_jabatan_id = ;
        // $approval->approval_jabatan_id = ;

        $pemutihan = (object) [];
        $pemutihan->project_id      = $project->id;
        $pemutihan->periode_awal    = substr($data->periode_awal, 3, 4) . "-" . substr($data->periode_awal, 0, 2) . "-" . "01";
        $pemutihan->periode_akhir   = substr($data->periode_akhir, 3, 4) . "-" . substr($data->periode_akhir, 0, 2) . "-" . "01";
        $pemutihan->masa_awal       = substr($data->masa_awal, 6, 4) . "-" . substr($data->masa_awal, 3, 2) . "-" . substr($data->masa_awal, 0, 2);
        $pemutihan->masa_akhir      = substr($data->masa_akhir, 6, 4) . "-" . substr($data->masa_akhir, 3, 2) . "-" . substr($data->masa_akhir, 0, 2);
        $pemutihan->description     = $data->description;
        $pemutihan->tgl_tambah      = $date;
        $pemutihan->tambah_user_id  = $create_user_id;
        $pemutihan->approval_id     = 0;
        $pemutihan->status          = 0;
        $pemutihan->file            = $data->nama_file;
        $pemutihan->code            = $data->kode;
        $this->db->insert("pemutihan", $pemutihan);
        $id_pemutihan = $this->db->insert_id();

        $pemutihan_nilai                        = (object) [];
        $pemutihan_nilai->pemutihan_id          = $id_pemutihan;
        $pemutihan_nilai->nilai_tagihan_type    = $data->nilai_tagihan_type;
        $pemutihan_nilai->nilai_tagihan         = str_replace(",", "", $data->nilai_tagihan);
        $pemutihan_nilai->nilai_denda_type      = $data->nilai_denda_type;
        $pemutihan_nilai->nilai_denda           = str_replace(",", "", $data->nilai_denda);
        $pemutihan_nilai->perkiraan_pemutihan_nilai_tagihan     = str_replace(",", "", $data->perkiraan_pemutihan_nilai_tagihan);
        $pemutihan_nilai->perkiraan_pemutihan_nilai_denda       = str_replace(",", "", $data->perkiraan_pemutihan_nilai_denda);
        $pemutihan_nilai->perkiraan_pemutihan_total             = str_replace(",", "", $data->perkiraan_pemutihan_total);
        $this->db->insert("pemutihan_nilai", $pemutihan_nilai);

        $this->load->model('Setting/Akun/m_permission_dokumen');
        $permission = $this->m_permission_dokumen->get_wewenang($project->id, "pemutihan", $pemutihan_nilai->perkiraan_pemutihan_total);

        // echo ("<pre>");
        // print_r($permission);
        // echo ("</pre>");

        $approval           = (object) [];
        $approval_detail    = (object) [];

        $approval->status_dokumen       = 0;
        $approval->status_request       = 0;

        $approval->create_user_id       = $create_user_id;
        $approval->tgl_tambah           = date("Y-m-d H:i:s.000");
        $approval->dokumen_jenis_id     = $this->db->select("id")->from("dokumen_jenis")->where("code", "pemutihan")->get()->row()->id;
        $approval->dokumen_id            = $id_pemutihan;
        $approval->create_jabatan_id    = $create_jabatan_id;
        $approval->project_id           = $project->id;
        $approval->dokumen_code         = $data->kode;
        $approval->dokumen_nilai        = str_replace(",", "", $data->perkiraan_pemutihan_total);
        $tmp = $this->db->select("jarak_approve")
            ->from("permission_dokumen")->where("jabatan_id", "$create_jabatan_id")->where("tipe", 0)->where("project_id", $project->id)->get()->row();
        if ($tmp) {
            $approval->jarak_request_closed    =  (int) $tmp->jarak_approve;
            $approval->tgl_closed           =  date('Y-m-d', strtotime(date("Y/m/d") . "+" . ($approval->jarak_request_closed + 1) . " days"));
            $approval->status_approval = 0;
            $this->db->insert("approval", $approval);



            $approval_detail->approval_id   = $this->db->insert_id();
            // $approval_detail->mengajukan_wewenang  = 0;
            $approval_detail->status_approve  = 0;


            $jabatan_approval = [];
            // foreach ($permission->mengetahui as $k => $v) {
            //     $approval_detail->approval_jabatan_id  = $v;
            //     $approval_detail->lama_approve  = $permission->mengetahui_jarak_approve[$k];

            //     $tmp = explode(",",$v);
            //     foreach ($tmp as $k2=> $v2) {
            //         array_push($jabatan_approval,$v2);
            //     }
            //     $this->db->insert("approval_detail",$approval_detail);
            // }
            // $approval_detail->mengajukan_wewenang  = 1;

            foreach ($permission as $k => $v) {
                $approval_detail->jarak_approve  = $v->jarak_approve;

                $tmp = explode(",", $v->jabatan_id);
                $tmp2 = [];
                foreach ($tmp as $v2) {
                    if ($v2 != $create_jabatan_id)
                        array_push($tmp2, $v2);
                }
                $approval_detail->approval_jabatan_id  = implode(",", $tmp2);
                $this->db->insert("approval_detail", $approval_detail);
            }

            // echo ("jabatan_approval<pre>");
            // print_r($jabatan_approval);
            // echo ("</pre>");

            $email_approval = $this->db->select("email, user.name")
                ->from("group_user")
                ->join(
                    "user",
                    "user.id = group_user.user_id"
                )
                ->where_in("jabatan_id", $create_jabatan_id)
                ->where("group_user.project_id", $project->id)
                ->where("email is not null")
                ->distinct()->get()->result();
            // echo ("<pre>");
            // print_r($email_approval);
            // echo ("</pre>");
            $pemutihan_service                      = (object) [];
            $pemutihan_service->pemutihan_id        = $id_pemutihan;
            foreach ($data->service_jenis as $v) {
                $pemutihan_service->service_id          = $this->db->select("id")
                    ->from("service")
                    ->where("project_id", $project->id)
                    ->where("service_jenis_id", $v)
                    ->get()->row();
                if ($pemutihan_service->service_id) {
                    $pemutihan_service->service_id          = $pemutihan_service->service_id->id;
                    $pemutihan_service->service_jenis_id    = $v;
                    $this->db->insert("pemutihan_service", $pemutihan_service);
                }
            }
            $pemutihan_unit                      = (object) [];
            $pemutihan_unit->pemutihan_id        = $id_pemutihan;
            foreach ($data->unit_id as $v) {
                $pemutihan_unit->unit_id          = $v;
                $this->db->insert("pemutihan_unit", $pemutihan_unit);
            }

            $this->load->model('Setting/m_parameter_project');

            $config = [
                'mailtype'  => 'html',
                'charset'   => 'utf-8',
                'protocol'  => 'smtp',
                'smtp_host' => $this->m_parameter_project->get($project->id, "smtp_host"),
                'smtp_user' => $this->m_parameter_project->get($project->id, "smtp_user"),
                'smtp_pass' => $this->m_parameter_project->get($project->id, "smtp_pass"),
                'smtp_port' => $this->m_parameter_project->get($project->id, "smtp_port"),
                'crlf'      => "\r\n",
                'newline'   => "\r\n"
            ];
            $this->load->library('email', $config);
            // print_r($config);
            // $this->db->selec
            $this->email->from($this->m_parameter_project->get($project->id, "smtp_user"), 'EMS Ciputra');
            $komponen = $this->db->select("
                                            dokumen_jenis.name as dokumen,
                                            approval.dokumen_code,
                                            user_create.name as user_create,
                                            approval.dokumen_nilai,
                                            approval.tgl_tambah")
                ->from("approval")
                ->join(
                    "dokumen_jenis",
                    "dokumen_jenis.id = approval.dokumen_jenis_id"
                )
                ->join(
                    "user as user_create",
                    "user_create.id = approval.create_user_id"
                )
                ->where("approval.id", $approval_detail->approval_id)
                ->get()->row();
            $tmp = $this->m_parameter_project->get($project->id, "isi_email_approval");
            $tmp = str_replace("{{Dokumen}}", $komponen->dokumen, $tmp);
            $tmp = str_replace("{{Kode}}", $komponen->dokumen_code, $tmp);
            $tmp = str_replace("{{User_create}}", $komponen->user_create, $tmp);
            $tmp = str_replace("{{Nilai}}", number_format($komponen->dokumen_nilai), $tmp);
            $tmp = str_replace("{{Date_create}}", substr($komponen->tgl_tambah, 0, 10), $tmp);

            $tmp = str_replace("{{Button_V}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Login </a>", $tmp);
            $tmp = str_replace("{{Button_A}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#286090;border-radius:5px;color:white'> Approve </a>", $tmp);
            $tmp = str_replace("{{Button_R}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#286090;border-radius:5px;color:white'> Approve </a>", $tmp);
            $tmp = str_replace("{{Button_L}}", "<a href='http://ces.ciputragroup.com' style='text-decoration: none;line-height:6;padding:6px 12px;margin:5px;width:100px;background-color:#169F85;border-radius:5px;color:white'> Login </a>", $tmp);

            // $tmp = str_replace("{{Button_A}}",$unit->project,$tmp);
            // $parameter_delay = explode(";",$this->m_parameter_project->get($project->id,"delay_email"));
            // var_dump($parameter_delay);

            foreach ($email_approval as $k => $v) {
                // if($k!=0 && ($k+1)%$parameter_delay[0]==0){
                //     sleep($parameter_delay[1]);
                // }
                // echo ("Email $v->email");
                $tmp2 = str_replace("{{User}}", ucwords($v->name), $tmp);
                $this->email->clear(TRUE);
                $this->email->from($this->m_parameter_project->get($project->id, "smtp_user"), 'EMS Ciputra');
                $this->email->subject($this->m_parameter_project->get($project->id, "subjeck_email_approval"));
                $this->email->message($tmp2);
                // echo($v);
                $this->email->to($v->email);
                // echo("email<pre>");
                //     print_r($this->email);
                // echo("</pre>");
                // $this->email->attach("application/pdf/$result->name_file");

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
                return true;
            }
        }
        return "Tidak Memiliki Izin";
    }
}
