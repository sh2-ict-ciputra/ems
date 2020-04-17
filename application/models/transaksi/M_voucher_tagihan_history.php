<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_voucher_tagihan_history extends CI_Model
{
    public function get_view_header($awal,$akhir){
        $awal   = substr($awal,6,4)."-".substr($awal,3,2)."-".substr($awal,0,2);
        $akhir  = substr($akhir,6,4)."-".substr($akhir,3,2)."-".substr($akhir,0,2);

        $project = $this->m_core->project();
        $data    = $this->db    ->select("
                                            voucher.cara_pembayaran,
                                            voucher.coa_cara_pembayaran,
                                            sum(voucher.nilai_item) as nilai_item,
                                            CONCAT(DAY(voucher.create_date),'/',MONTH(voucher.create_date),'/',YEAR(voucher.create_date)) as create_date,
                                            voucher.pt_id,
                                            voucher.cara_pembayaran_id,
                                            pt.name as pt_name,
                                            voucher.voucher_header_id")
                                ->from("voucher")
                                ->join("pt",
                                        "pt.id = voucher.pt_id")
                                ->where("voucher.project_id",$project->id)
                                ->where("voucher.create_date between '$awal 00:00:00.000' AND '$akhir 23:59:59.000'")
                                ->group_by("
                                    voucher.cara_pembayaran,
                                    voucher.coa_cara_pembayaran,
                                    CONCAT(DAY(voucher.create_date),'/',MONTH(voucher.create_date),'/',YEAR(voucher.create_date)),
                                    voucher.pt_id,
                                    voucher.cara_pembayaran_id,
                                    pt.name,
                                    voucher.voucher_header_id
                                ")
                                ->get()->result();
        return $data;
    }       
    public function get()
    {
        $project = $this->m_core->project();
        $data_tagihan   = $this->db ->select("*")
                                    ->from("view_belum_transfer_keuangan_tagihan")
                                    ->where("project_id",$project->id)
                                    ->get()->result();
        $data_ppn       = $this->db ->select("*")
                                    ->from("view_belum_transfer_keuangan_ppn")
                                    ->where("project_id",$project->id)
                                    ->get()->result();
        $data = array_merge($data_tagihan,$data_ppn);
        $dataCluster = [];
        $amountHeader = 0;
        if($data[0]){
            $dataCluster[0] = $data[0];

            foreach ($data as $k1 =>$v1) {
                $amountHeader +=$v1->nilai_item;
                if($k1 > 0){

                    $adaYangSama = 0;
                    foreach ($dataCluster as $k2 => $v2) {
                        if($v1->item == $v2->item && $v1->cara_pembayaran == $v2->cara_pembayaran){
                            $dataCluster[$k2]->nilai_item += $v1->nilai_item;
                            $dataCluster[$k2]->id = $dataCluster[$k2]->id.",".$v1->id;
                            $adaYangSama = 1;
                        }
                    }   
                    if($adaYangSama == 0){
                        array_push($dataCluster,$v1);
                    }
                }
            }
        }
        $project_id_erems = $this->db->select("source_id")
                                    ->from("project")
                                    ->where("id",$project->id)
                                    ->get()->row()->source_id;
        var_dump($project_id_erems);
        $dataValidasi = (object)[];
        $dataValidasi->project_id           = $project_id_erems;
        // $dataValidasi->pt_id                = 4223;
        $dataValidasi->pt_id                = 2099;

        $dataValidasi->uploaduniquenumber   = 57312;
        $dataValidasi->department           = "ESTATE";
        $dataValidasi->dataflow             = "I";
        $dataValidasi->is_customer          = 0;
        $dataValidasi->is_vendor            = 1;
        $dataValidasi->vendor_name          = "EMS";
        $dataValidasi->duedate              = "";
        $dataValidasi->status               = "";
        $dataValidasi->vid                  = "";
        $dataValidasi->is_posting           = "";
        $dataValidasi->spk                  = "";
        $dataValidasi->receipt_no           = "";
        $dataValidasi->amount_header        = $amountHeader;            

        $url = "https://api.ciputragroup.com/cashierapi/index.php/ems/uploadvoucher";

        foreach ($dataCluster as $k => $v) {
            // $dataValidasi->coa_header           = $v->coa_cara_pembayaran;
            $dataValidasi->coa_header           = "";

            $dataValidasi->note                 = "ESTATE $v->cara_pembayaran ".date("d/m/Y");
            $dataValidasi->pengajuandate        = "2019-09-18"; //tgl_bayar
            $dataValidasi->kwitansidate         = "2019-09-18"; //tgl_bayar
            $dataValidasi->coa_detail           = $v->item_coa;
            $dataValidasi->description          = "TESTAPI1";
            $dataValidasi->sub_unit             = "SUBAPI22";
            $dataValidasi->seq_detail           = $k;
            $dataValidasi->amount               = $v->nilai_item;
            $dataValidasi->kawasan              = "";
            $dataValidasi->paymentdate          = "2019-09-18"; //tgl_bayar
    
            $request_headers = $dataValidasi;

            // $request_headers[] = 'Authorization: Bearer ' . $secretKey;
            
            $ch = curl_init();


            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);

            $data = curl_exec($ch);
            if (curl_errno($ch)){
                print "Error: " . curl_error($ch);
            }else{
                // Show me the result

                $transaction = json_decode($data, TRUE);

                curl_close($ch);

                var_dump($transaction);

            }
            $dataCluster[$k]->status = (object)$transaction;
        }
        return $dataCluster;
    }
    public function get_detail($pt_id,$cara_pembayaran_id){
        $project = $this->m_core->project();

        $data       = $this->db ->select("voucher.*")
                                    ->from("voucher")
                                    // ->join("t_pembayaran_detail",
                                    //         "t_pembayaran_detail.id = voucher.t_pembayaran_detail_id")
                                    // ->join("t_pembayaran",
                                    //         "t_pembayaran.id = t_pembayaran_detail.t_pembayaran_id")
                                    // ->join("unit",
                                    //         "unit.id = t_pembayaran.unit_id")
                                    // ->join("blok",
                                    //         "blok.id = unit.blok_id")
                                    // ->join("kawasan",
                                    //         "kawasan.id = blok.kawasan_id")
                                    ->where("voucher.project_id",$project->id)
                                    ->where("voucher.cara_pembayaran_id",$cara_pembayaran_id)
                                    ->where("voucher.pt_id",$pt_id)
                                    
                                    ->get()->result();
        $pt_name       = $this->db->select("name")
                                ->from("pt")
                                ->where("id",$pt_id)
                                ->get()->row()->name;

        
        return ["pt_name"   => $pt_name,
                "data"      => $data];
        echo("<pre>");
            print_r($data);
        echo("</pre>");
    }
    public function validasi($id,$jenis,$total_nilai){
        $project = $this->m_core->project();

        $project_id_erems = $this->db   ->select("source_id")
                                        ->from("project")
                                        ->where("id",$project->id)
                                        ->get()->row()->source_id;
        // var_dump($id);
        // var_dump($jenis);
        // var_dump($total_nilai);
        
        $data   = $this->db ->select("*")
                                ->from("voucher")
                                ->where("project_id",$project->id)
                                ->where("id",$id)
                                ->get()->row();
        // var_dump($data);
        
        $pt_id_erems = $this->db->select("pt.source_id")
                                ->from("pt")
                                ->where("pt.id",$data->pt_id)
                                ->get()->row()->source_id;

        $url = "https://api.ciputragroup.com/cashierapi/index.php/ems/validasivoucher";

        $dataValidasi = (object)[];
        $dataValidasi->project_id           = $project_id_erems;
        // $dataValidasi->pt_id                = 4223;
        $dataValidasi->pt_id                = $pt_id_erems;

        $dataValidasi->uploaduniquenumber   = 57312;
        $dataValidasi->department           = "ESTATE";
        $dataValidasi->dataflow             = "I";
        $dataValidasi->is_customer          = 0;
        $dataValidasi->is_vendor            = 1;
        $dataValidasi->vendor_name          = "ESTATE";
        $dataValidasi->duedate              = "";
        $dataValidasi->status               = "";
        $dataValidasi->vid                  = "";
        $dataValidasi->is_posting           = "";
        $dataValidasi->spk                  = "";
        $dataValidasi->receipt_no           = "";
        $dataValidasi->amount_header        = $total_nilai;  

            // $dataValidasi->coa_header           = $v->coa_cara_pembayaran;
        $dataValidasi->coa_header           = $data->coa_cara_pembayaran;

        $dataValidasi->note                 = "ESTATE $data->cara_pembayaran ".date("d/m/Y");
        $dataValidasi->pengajuandate        = $data->tgl_bayar; //tgl_bayar
        $dataValidasi->kwitansidate         = $data->tgl_bayar; //tgl_bayar
        $dataValidasi->coa_detail           = $data->coa_item;
        $dataValidasi->description          = "ESTATE $data->kawasan $data->blok/$data->no_unit";
        $dataValidasi->sub_unit             = "$data->blok/$data->no_unit";
        // $dataValidasi->seq_detail           = $k;   
        $dataValidasi->amount               = $data->nilai_item;
        $dataValidasi->kawasan              = $data->kawasan;
        $dataValidasi->paymentdate          = $data->tgl_bayar; //tgl_bayar 
        
        $ch = curl_init();


            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);

            $data = curl_exec($ch);
            if (curl_errno($ch)){
                // print "Error: " . curl_error($ch);
                return (object)[
                    "result" => -1,
                    "message" => "ERROR di API atau Koneksi Internet"
                ];
            }else{
                // Show me the result

                $transaction = json_decode($data, TRUE);

                curl_close($ch);


            }
            return (object)$transaction;
    }
    public function kirim_voucher($pt_id,$cara_pembayaran_id,$total_nilai,$data_id){
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $project_id_erems = $this->db   ->select("source_id")
                                        ->from("project")
                                        ->where("id",$project->id)
                                        ->get()->row()->source_id;

        $data_tagihan   = $this->db ->select("*")
                                    ->from("view_belum_transfer_keuangan_tagihan")
                                    ->where("project_id",$project->id)
                                    ->where("pt_id",$pt_id)
                                    ->where("cara_pembayaran_id",$cara_pembayaran_id)
                                    ->get()->result();
            
        $data_ppn       = $this->db ->select("*")
                                    ->from("view_belum_transfer_keuangan_ppn")
                                    ->where("project_id",$project->id)
                                    ->where("pt_id",$pt_id)
                                    ->where("cara_pembayaran_id",$cara_pembayaran_id)
                                    ->get()->result();

        $data = array_merge($data_tagihan,$data_ppn);
        
        $pt_id_erems = $this->db->select("pt.source_id")
                                ->from("unit")
                                ->join("pt",
                                        "pt.id = unit.pt_id")
                                ->where("unit.id",$data[0]->unit_id)
                                ->get()->row()->source_id;
        $url = "https://api.ciputragroup.com/cashierapi/index.php/ems/requestkey";

        $ch = curl_init();
    
    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);

        $tmp = curl_exec($ch);
        if (curl_errno($ch)){
            return (object)[
                "result" => -1,
                "message" => "ERROR di API atau Koneksi Internet"
            ];
        }else{
            $key = json_decode($tmp, TRUE);
            $key = $key["key"];
            curl_close($ch);
            var_dump($key);
        }
        if(isset($key)){


            $url = "https://api.ciputragroup.com/cashierapi/index.php/ems/uploadvoucher";


            $this->db->insert("voucher_header_generate",["generate"=>date("ymdhis").$pt_id_erems]);
            $id_header = $this->db->insert_id();
            

            $dataValidasi = (object)[];
            $dataValidasi->project_id           = $project_id_erems;
            $dataValidasi->pt_id                = $pt_id_erems;
            $dataValidasi->uploaduniquenumber   = $id_header;
            $dataValidasi->department           = "ESTATE";
            $dataValidasi->dataflow             = "I";
            $dataValidasi->is_customer          = 0;
            $dataValidasi->is_vendor            = 1;
            $dataValidasi->vendor_name          = "ESTATE";
            $dataValidasi->duedate              = "";
            $dataValidasi->status               = "";
            $dataValidasi->vid                  = "";
            $dataValidasi->is_posting           = "";
            $dataValidasi->spk                  = "";
            $dataValidasi->receipt_no           = "";
            $dataValidasi->requestkey           = $key;
            $dataValidasi->amount_header        = $total_nilai;  
            $dataValidasi->coa_header           = $data[0]->coa_cara_pembayaran;
            $dataValidasi->note                 = "ESTATE ".$data[0]->cara_pembayaran." ".date("d/m/Y");
            $jumlahBerhasil = 0;
            $jumlahGagal = 0;
            $data_uploud = [];
            foreach ($data as $k => $v) {
                $data_uploud_detail = (object)[];
                // var_dump($v);
                $dataValidasi->pengajuandate        = $v->tgl_bayar2; //tgl_bayar
                $dataValidasi->kwitansidate         = $v->tgl_bayar2; //tgl_bayar
                $dataValidasi->coa_detail           = $v->item_coa;
                $dataValidasi->description          = "ESTATE $v->kawasan $v->blok/$v->no_unit";
                $dataValidasi->sub_unit             = "$v->blok/$v->no_unit";
                $dataValidasi->seq_detail           = $k;   
                $dataValidasi->amount               = $v->nilai_item;
                $dataValidasi->kawasan              = $v->kawasan;
                $dataValidasi->paymentdate          = $v->tgl_bayar2; //tgl_bayar 
                
                $ch = curl_init();
        
        
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);

                $data = curl_exec($ch);
                if (curl_errno($ch)){
                    $jumlahGagal++;
                    // print "Error: " . curl_error($ch);
                    return (object)[
                        "result" => -1,
                        "message" => "ERROR di API atau Koneksi Internet"
                    ];
                }else{
                    // Show me the result

                    $transaction = json_decode($data, TRUE);
                    if($transaction['result']==1){
                        $jumlahBerhasil++;
                    }else{
                        $jumlahGagal++;
                    }

                    curl_close($ch);

                }  
                $data_uploud_detail->voucher_header_id          = $id_header;
                // $data_uploud_detail->voucher_id                 = $v->voucher_id;
                $data_uploud_detail->project_id                 = $project->id;
                $data_uploud_detail->pt_id                      = $v->pt_id;
                $data_uploud_detail->t_pembayaran_detail_id     = $v->id;
                $data_uploud_detail->create_date                = date("Y-m-d h:i:s.000");;
                $data_uploud_detail->user_create_id             = $user_id;
                $data_uploud_detail->cara_pembayaran            = $v->cara_pembayaran;
                $data_uploud_detail->item                       = $v->item;
                $data_uploud_detail->nilai_item                 = $v->nilai_item;
                $data_uploud_detail->nilai_total_item           = $total_nilai;
                $data_uploud_detail->coa_item                   = $v->item_coa;
                $data_uploud_detail->coa_cara_pembayaran        = $v->coa_cara_pembayaran;
                $data_uploud_detail->voucher_detail_id          = $k;
                $data_uploud_detail->project_id_erems           = $project_id_erems;
                $data_uploud_detail->pt_id_erems                = $pt_id_erems;
                
                array_push($data_uploud,$data_uploud_detail);
            }

            if($jumlahGagal == 0){
                $this->db->trans_start();
                foreach ($data_uploud as $k => $v) {
                    $this->db->insert("voucher", $v);
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
            }
        }

        return (object)[
            "jumlah_berhasil" => $jumlahBerhasil,
            "jumlah_gagal" => $jumlahGagal,
            "data_uploud"  => $data_uploud  
        ];
    }
    public function kirim_voucher_all($pt_id,$cara_pembayaran_id,$total_nilai){
        $project = $this->m_core->project();

        $project_id_erems = $this->db   ->select("source_id")
                                        ->from("project")
                                        ->where("id",$project->id)
                                        ->get()->row()->source_id;

        $data_tagihan   = $this->db ->select("*")
                                    ->from("view_belum_transfer_keuangan_tagihan")
                                    ->where("project_id",$project->id)
                                    ->where("pt_id",$pt_id)
                                    ->where("cara_pembayaran_id",$cara_pembayaran_id)
                                    ->get()->result();
            
        $data_ppn       = $this->db ->select("*")
                                    ->from("view_belum_transfer_keuangan_ppn")
                                    ->where("project_id",$project->id)
                                    ->where("pt_id",$pt_id)
                                    ->where("cara_pembayaran_id",$cara_pembayaran_id)
                                    ->get()->result();

        $data = array_merge($data_tagihan,$data_ppn);
        
        $pt_id_erems = $this->db->select("pt.source_id")
                                ->from("unit")
                                ->join("pt",
                                        "pt.id = unit.pt_id")
                                ->where("unit.id",$data[0]->unit_id)
                                ->get()->row()->source_id;

        $url = "https://api.ciputragroup.com/cashierapi/index.php/ems/uploadvoucher";

        $dataValidasi = (object)[];
        $dataValidasi->project_id           = $project_id_erems;
        $dataValidasi->pt_id                = $pt_id_erems;
        $dataValidasi->uploaduniquenumber   = 57312;
        $dataValidasi->department           = "ESTATE";
        $dataValidasi->dataflow             = "I";
        $dataValidasi->is_customer          = 0;
        $dataValidasi->is_vendor            = 1;
        $dataValidasi->vendor_name          = "ESTATE";
        $dataValidasi->duedate              = "";
        $dataValidasi->status               = "";
        $dataValidasi->vid                  = "";
        $dataValidasi->is_posting           = "";
        $dataValidasi->spk                  = "";
        $dataValidasi->receipt_no           = "";
        $dataValidasi->amount_header        = $total_nilai;  
        $dataValidasi->coa_header           = $data[0]->coa_cara_pembayaran;
        $dataValidasi->note                 = "ESTATE ".$data[0]->cara_pembayaran." ".date("d/m/Y");
        $jumlahBerhasil = 0;
        $jumlahGagal = 0;
        
        foreach ($data as $k => $v) {
            var_dump($v);
            $dataValidasi->pengajuandate        = $v->tgl_bayar2; //tgl_bayar
            $dataValidasi->kwitansidate         = $v->tgl_bayar2; //tgl_bayar
            $dataValidasi->coa_detail           = $v->item_coa;
            $dataValidasi->description          = "ESTATE $v->kawasan $v->blok/$v->no_unit";
            $dataValidasi->sub_unit             = "$v->blok/$v->no_unit";
            // $dataValidasi->seq_detail           = $k;   
            $dataValidasi->amount               = $v->nilai_item;
            $dataValidasi->kawasan              = $v->kawasan;
            $dataValidasi->paymentdate          = $v->tgl_bayar2; //tgl_bayar 
            
            $ch = curl_init();
    
    
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);
    
                $data = curl_exec($ch);
                if (curl_errno($ch)){
                    $jumlahGagal++;
                    // print "Error: " . curl_error($ch);
                    return (object)[
                        "result" => -1,
                        "message" => "ERROR di API atau Koneksi Internet"
                    ];
                }else{
                    // Show me the result
                    $transaction = json_decode($data, TRUE);
                    if($transaction['result']==1){
                        $jumlahBerhasil++;
                    }else{
                        $jumlahGagal++;
                    }
                    var_dump($transaction);
                    curl_close($ch);
                    
                }        
            }
        
            return (object)[
                "jumlah_berhasil" => $jumlahBerhasil,
                "jumlah_gagal" => $jumlahGagal
            ];
    }

}
