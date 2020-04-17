<?php

defined('BASEPATH') or exit('No direct script access allowed');

class m_voucher_tagihan extends CI_Model
{
    public function get_voucher($t1, $t2)
    {
        $project = $this->m_core->project();
        $t1 = substr($t1, -4) . "-" . substr($t1, 3, 2) . "-" . substr($t1, 0, 2);
        $t2 = substr($t2, -4) . "-" . substr($t2, 3, 2) . "-" . substr($t2, 0, 2);
        // var_dump($t1);
        // var_dump($t2);

        $voucher = $this->db->select("
                                pt_id,
                                pt,
                                cara_pembayaran_id,
                                cara_pembayaran,
                                coa_cara_pembayaran,
                                sum(isnull(bayar,bayar_deposit)-nilai_diskon) as tagihan 
                            ")
            ->from("v_voucher")
            ->where('tgl_bayar >=', "$t1")
            ->where('tgl_bayar <=', "$t2")
            ->where('project_id', $project->id)
            ->group_by("
                                pt_id,
                                pt,
                                cara_pembayaran_id,
                                cara_pembayaran,
                                coa_cara_pembayaran
                            ")
            // ->distinct()
            ->get()->result();
        // var_dump($this->db->last_query());
        return $voucher;
    }
    public function get_view_bu()
    {
        $project = $this->m_core->project();
        $data_tagihan   = $this->db->select("  
                                                pt,
                                                pt_id,
                                                erems_pt_id,
                                                cara_pembayaran_id,
                                                cara_pembayaran,
                                                coa_cara_pembayaran,
                                                sum(nilai_item) as nilai_item")
            ->from("view_belum_transfer_keuangan_tagihan")
            ->where("project_id", $project->id)
            ->group_by("
                                                pt,
                                                pt_id,
                                                erems_pt_id,
                                                cara_pembayaran,
                                                coa_cara_pembayaran,
                                                cara_pembayaran_id")
            ->get()->result();
        $data_ppn       = $this->db->select("
                                                pt,
                                                pt_id,
                                                erems_pt_id,
                                                cara_pembayaran_id,
                                                cara_pembayaran,
                                                coa_cara_pembayaran,
                                                sum(nilai_item) as nilai_item")
            ->from("view_belum_transfer_keuangan_ppn")
            ->where("project_id", $project->id)
            ->group_by("
                                                pt,
                                                pt_id,
                                                erems_pt_id,
                                                cara_pembayaran,
                                                coa_cara_pembayaran,
                                                cara_pembayaran_id")
            ->get()->result();
        $data_cluster = $data_tagihan;
        foreach ($data_ppn as $k1 => $v1) {
            $flag_sama = 0;
            foreach ($data_cluster as $k2 => $v2) {
                if ($v2->pt_id == $v1->pt_id && $v2->cara_pembayaran_id == $v1->cara_pembayaran_id) {
                    $data_cluster[$k2]->nilai_item += $v1->nilai_item;
                    $flag_sama = 1;
                    break;
                }
            }
            if ($flag_sama == 0) {
                array_push($data_cluster, $v1);
            }
        }
        return $data_cluster;
    }
    public function get()
    {
        $project = $this->m_core->project();
        $data_tagihan   = $this->db->select("*")
            ->from("view_belum_transfer_keuangan_tagihan")
            ->where("project_id", $project->id)
            ->get()->result();
        $data_ppn       = $this->db->select("*")
            ->from("view_belum_transfer_keuangan_ppn")
            ->where("project_id", $project->id)
            ->get()->result();
        $data = array_merge($data_tagihan, $data_ppn);
        $dataCluster = [];
        $amountHeader = 0;
        if ($data[0]) {
            $dataCluster[0] = $data[0];

            foreach ($data as $k1 => $v1) {
                $amountHeader += $v1->nilai_item;
                if ($k1 > 0) {

                    $adaYangSama = 0;
                    foreach ($dataCluster as $k2 => $v2) {
                        if ($v1->item == $v2->item && $v1->cara_pembayaran == $v2->cara_pembayaran) {
                            $dataCluster[$k2]->nilai_item += $v1->nilai_item;
                            $dataCluster[$k2]->id = $dataCluster[$k2]->id . "," . $v1->id;
                            $adaYangSama = 1;
                        }
                    }
                    if ($adaYangSama == 0) {
                        array_push($dataCluster, $v1);
                    }
                }
            }
        }
        $project_id_erems = $this->db->select("source_id")
            ->from("project")
            ->where("id", $project->id)
            ->get()->row()->source_id;
        var_dump($project_id_erems);
        $dataValidasi = (object) [];
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

        $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/uploadvoucher";

        foreach ($dataCluster as $k => $v) {
            // $dataValidasi->coa_header           = $v->coa_cara_pembayaran;
            $dataValidasi->coa_header           = "";

            $dataValidasi->note                 = "ESTATE $v->cara_pembayaran " . date("d/m/Y");
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
            if (curl_errno($ch)) {
                print "Error: " . curl_error($ch);
            } else {
                // Show me the result

                $transaction = json_decode($data, TRUE);

                curl_close($ch);

                var_dump($transaction);
            }
            $dataCluster[$k]->status = (object) $transaction;
        }
        return $dataCluster;
    }
    public function get_detail($pt_id, $cara_pembayaran_id, $t1, $t2)
    {
        $t1 = substr($t1, -4) . "-" . substr($t1, 3, 2) . "-" . substr($t1, 0, 2);
        $t2 = substr($t2, -4) . "-" . substr($t2, 3, 2) . "-" . substr($t2, 0, 2);

        $project = $this->m_core->project();

        $data = $this->db->select("
                            v_voucher.unit_id,
                            v_voucher.kawasan,
                            v_voucher.blok,
                            v_voucher.no_unit,
                            v_voucher.pemilik,
                            v_voucher.item,
                            v_voucher.nilai_tagihan,
                            v_voucher.nilai_ppn,
                            v_voucher.nilai_denda,
                            v_voucher.nilai_penalti,
                            FORMAT (v_voucher.tgl_bayar,'dd-MM-yyyy') as tgl_bayar,  
                            coa_tagihan.coa as coa_tagihan,
                            coa_ppn.coa as coa_ppn,
                            coa_denda.coa as coa_denda,
                            CASE 
                                WHEN t_tagihan_air.periode is not null 
                                    THEN FORMAT(t_tagihan_air.periode,'dd-MM-yyyy')
                                WHEN t_tagihan_lingkungan.periode is not null 
                                    THEN FORMAT(t_tagihan_lingkungan.periode,'dd-MM-yyyy')
                            END as periode

                        ")
                        // v_voucher.unit_id,
                        //     v_voucher.kawasan,
                        //     v_voucher.blok,
                        //     v_voucher.no_unit,
                        //     v_voucher.pemilik,
                        //     CASE service.service_jenis_id
                        //         WHEN 6 THEN CONCAT(v_voucher.item,' - ',paket_service.name)
                        //         ELSE v_voucher.item
                        //     END as item,
                        //     v_voucher.nilai_tagihan,
                        //     v_voucher.nilai_ppn,
                        //     v_voucher.nilai_denda,
                        //     v_voucher.nilai_penalti,
                        //     FORMAT (v_voucher.tgl_bayar,'dd-MM-yyyy') as tgl_bayar,  
                        //     coa_tagihan.coa as coa_tagihan,
                        //     coa_ppn.coa as coa_ppn,
                        //     coa_denda.coa as coa_denda,
                        //     CASE 
                        //         WHEN t_tagihan_air.periode is not null 
                        //             THEN FORMAT(t_tagihan_air.periode,'dd-MM-yyyy')
                        //         WHEN t_tagihan_lingkungan.periode is not null 
                        //             THEN FORMAT(t_tagihan_lingkungan.periode,'dd-MM-yyyy')
                        //         --WHEN t_tagihan_layanan_lain_detail.periode_awal is not null
                        //         --    THEN CONCAT(FORMAT(t_tagihan_layanan_lain_detail.periode_awal,'dd-MM-yyyy'),
                        //         --               '-<br>',
                        //         --                FORMAT(t_tagihan_layanan_lain_detail.periode_akhir,'dd-MM-yyyy'))
                        //     END as periode
            ->from("v_voucher")
            ->join(
                "service",
                "service.id = v_voucher.service_id"
            )
            ->join(
                "gl_2018.dbo.view_coa as coa_tagihan",
                "coa_tagihan.coa_id = service.service_coa_mapping_id",
                "LEFT"
            )
            ->join(
                "gl_2018.dbo.view_coa as coa_ppn",
                "coa_ppn.coa_id = service.ppn_coa_mapping_id",
                "LEFT"
            )
            ->join(
                "gl_2018.dbo.view_coa as coa_denda",
                "coa_denda.coa_id = service.denda_penalti_coa_mapping_id",
                "LEFT"
            )
            ->join(
                "t_tagihan_lingkungan",
                "service.service_jenis_id = 1
                AND t_tagihan_lingkungan.id = tagihan_service_id",
                "LEFT"
            )
            ->join(
                "t_tagihan_air",
                "service.service_jenis_id = 2
                AND t_tagihan_air.id = tagihan_service_id",
                "LEFT"
            )
            // ->join(
            //     "t_tagihan_layanan_lain_detail",
            //     "service.service_jenis_id = 6
            //     AND t_tagihan_layanan_lain_detail.id = tagihan_service_id",
            //     "LEFT"
            // )
            // ->join("
            //     paket_service",
            //     "paket_service.id = t_tagihan_layanan_lain_detail.paket_service_id")
            ->where("v_voucher.cara_pembayaran_id", $cara_pembayaran_id)
            ->where("v_voucher.pt_id", $pt_id)
            ->where('tgl_bayar >=', "$t1")
            ->where('tgl_bayar <=', "$t2")
            ->where('v_voucher.project_id', $project->id)
            ->order_by("kawasan,blok,no_unit,periode")
            ->get()->result();

        $data_detil = [];
        $total = 0;
        // die;
        foreach ($data as $k => $v) {
            array_push($data_detil, [
                "unit_id"       => $v->unit_id,
                "kawasan"       => $v->kawasan,
                "pemilik"       => $v->pemilik,
                "blok"          => $v->blok,
                "no_unit"       => $v->no_unit,
                "tgl_bayar"     => $v->tgl_bayar,
                "periode_tagihan" => $v->periode,
                "item"          => "Tagihan - $v->item",
                "coa_item"      => $v->coa_tagihan,
                "nilai_item"    => $v->nilai_tagihan
            ]);
            if ($v->nilai_ppn > 0) {
                array_push($data_detil, [
                    "unit_id"       => $v->unit_id,
                    "kawasan"       => $v->kawasan,
                    "pemilik"       => $v->pemilik,
                    "blok"          => $v->blok,
                    "no_unit"       => $v->no_unit,
                    "tgl_bayar"     => $v->tgl_bayar,
                    "periode_tagihan" => $v->periode,
                    "item"          => "PPN - $v->item",
                    "coa_item"      => $v->coa_ppn,
                    "nilai_item"    => $v->nilai_ppn
                ]);
            }
            if ($v->nilai_denda > 0) {
                array_push($data_detil, [
                    "unit_id"       => $v->unit_id,
                    "kawasan"       => $v->kawasan,
                    "pemilik"       => $v->pemilik,
                    "blok"          => $v->blok,
                    "no_unit"       => $v->no_unit,
                    "tgl_bayar"     => $v->tgl_bayar,
                    "periode_tagihan" => $v->periode,
                    "item"          => "Denda - $v->item",
                    "coa_item"      => $v->coa_denda,
                    "nilai_item"    => $v->nilai_denda
                ]);
            }

            $total = $total + ($v->nilai_tagihan + $v->nilai_ppn + $v->nilai_denda + $v->nilai_penalti);
        }

        $data = (object) [];

        $data->data = array_merge($data_detil);
        $data->header = $this->db->select("
                            pt,
                            cara_pembayaran,
                            coa_cara_pembayaran
                        ")
            ->from("v_voucher")
            ->where('tgl_bayar >=', "$t1")
            ->where('tgl_bayar <=', "$t2")
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->where("pt_id", $pt_id)
            ->where('v_voucher.project_id', $project->id)
            ->get()->row();

        $data->header->total = $total;
        return $data;
    }
    public function get_detail_gabungan($pt_id, $cara_pembayaran_id, $t1, $t2)
    {
        $t1 = substr($t1, -4) . "-" . substr($t1, 3, 2) . "-" . substr($t1, 0, 2);
        $t2 = substr($t2, -4) . "-" . substr($t2, 3, 2) . "-" . substr($t2, 0, 2);

        $project = $this->m_core->project();

        $data = $this->db->select("
                            service.name as item,
                            sum(v_voucher.nilai_tagihan) as nilai_tagihan,
                            sum(v_voucher.nilai_ppn) as nilai_ppn,
                            sum(v_voucher.nilai_denda) as nilai_denda,
                            sum(v_voucher.nilai_penalti) as nilai_penalti,
                            coa_tagihan.coa as coa_tagihan,
                            coa_ppn.coa as coa_ppn,
                            coa_denda.coa as coa_denda
                        ")
            ->from("v_voucher")
            ->join(
                "service",
                "service.id = v_voucher.service_id"
            )
            ->join(
                "gl_2018.dbo.view_coa as coa_tagihan",
                "coa_tagihan.coa_id = service.service_coa_mapping_id",
                "LEFT"
            )
            ->join(
                "gl_2018.dbo.view_coa as coa_ppn",
                "coa_ppn.coa_id = service.ppn_coa_mapping_id",
                "LEFT"
            )
            ->join(
                "gl_2018.dbo.view_coa as coa_denda",
                "coa_denda.coa_id = service.service_coa_mapping_id",
                "LEFT"
            )
            ->where("v_voucher.cara_pembayaran_id", $cara_pembayaran_id)
            ->where("v_voucher.pt_id", $pt_id)
            ->where('tgl_bayar >=', "$t1")
            ->where('tgl_bayar <=', "$t2")
            ->where('v_voucher.project_id', $project->id)
            ->group_by("
                                service.name,
                                coa_tagihan.coa,
                                coa_ppn.coa,
                                coa_denda.coa
                        ")
            ->get()->result();
        // echo("<pre>");
        //     print_r($data);
        // echo("</pre>");

        $data_detil = [];
        $total = 0;
        foreach ($data as $k => $v) {
            array_push($data_detil, [
                "item"          => "Tagihan - $v->item",
                "coa_item"      => $v->coa_tagihan,
                "nilai_item"    => $v->nilai_tagihan
            ]);
            if ($v->nilai_ppn > 0) {
                array_push($data_detil, [
                    "item"          => "PPN - $v->item",
                    "coa_item"      => $v->coa_ppn,
                    "nilai_item"    => $v->nilai_ppn
                ]);
            }
            if ($v->nilai_denda > 0) {
                array_push($data_detil, [
                    "item"          => "Denda - $v->item",
                    "coa_item"      => $v->coa_denda,
                    "nilai_item"    => $v->nilai_denda
                ]);
            }
            if ($v->nilai_penalti > 0) {
                array_push($data_detil, [
                    "item"          => "Penalti - $v->item",
                    "coa_item"      => $v->coa_penalti,
                    "nilai_item"    => $v->nilai_penalti
                ]);
            }
            $total = $total + ($v->nilai_tagihan + $v->nilai_ppn + $v->nilai_denda + $v->nilai_penalti);
        }

        $data = (object) [];

        $data->data = array_merge($data_detil);
        $data->header = $this->db->select("
                            pt,
                            cara_pembayaran,
                            coa_cara_pembayaran
                        ")
            ->from("v_voucher")
            ->where('tgl_bayar >=', "$t1")
            ->where('tgl_bayar <=', "$t2")
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->where("pt_id", $pt_id)
            ->where('v_voucher.project_id', $project->id)
            ->get()->row();
        $data->header->total = $total;
        return $data;
        echo ("<pre>");
        print_r($data);
        echo ("</pre>");
    }
    public function get_detail_gabungan_bu($pt_id, $cara_pembayaran_id)
    {
        $project = $this->m_core->project();

        $data_tagihan   = $this->db->select("
                                        pt_id,
                                        pt,
                                        erems_pt_id,
                                        cara_pembayaran_id,
                                        project_id,
                                        sum(nilai_item) as nilai_item,	
                                        item,
                                        item_coa,
                                        cara_pembayaran,
                                        coa_cara_pembayaran")
            ->from("view_belum_transfer_keuangan_tagihan")
            ->where("project_id", $project->id)
            ->where("pt_id", $pt_id)
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->group_by("pt_id,
                                                pt,
                                                erems_pt_id,
                                                cara_pembayaran_id,
                                                project_id,
                                                item,
                                                item_coa,
                                                cara_pembayaran,
                                                coa_cara_pembayaran")
            ->get()->result();
        $data_ppn       = $this->db->select("
                                        pt_id,
                                        pt,
                                        erems_pt_id,
                                        cara_pembayaran_id,
                                        project_id,
                                        sum(nilai_item) as nilai_item,	
                                        item,
                                        item_coa,
                                        cara_pembayaran,
                                        coa_cara_pembayaran")
            ->from("view_belum_transfer_keuangan_ppn")
            ->where("project_id", $project->id)
            ->where("pt_id", $pt_id)
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->group_by("pt_id,
                                                pt,
                                                erems_pt_id,
                                                cara_pembayaran_id,
                                                project_id,
                                                item,
                                                item_coa,
                                                cara_pembayaran,
                                                coa_cara_pembayaran")
            ->get()->result();
        $data_denda_penalti = $this->db->select("
                                            pt_id,
                                            pt,
                                            erems_pt_id,
                                            cara_pembayaran_id,
                                            project_id,
                                            sum(nilai_item) as nilai_item,	
                                            item,
                                            item_coa,
                                            cara_pembayaran,
                                            coa_cara_pembayaran")
            ->from("view_belum_transfer_keuangan_denda_penalti")
            ->where("project_id", $project->id)
            ->where("pt_id", $pt_id)
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->group_by("pt_id,
                                                    pt,
                                                    erems_pt_id,
                                                    cara_pembayaran_id,
                                                    project_id,
                                                    item,
                                                    item_coa,
                                                    cara_pembayaran,
                                                    coa_cara_pembayaran")
            ->get()->result();

        $data = array_merge($data_tagihan, $data_ppn, $data_denda_penalti);
        return $data;
        echo ("<pre>");
        print_r($data);
        echo ("</pre>");
    }
    public function validasi($id, $jenis, $total_nilai)
    {
        $project = $this->m_core->project();

        $project_id_erems = $this->db->select("source_id")
            ->from("project")
            ->where("id", $project->id)
            ->get()->row()->source_id;

        if ($jenis == "Service") {
            $data   = $this->db->select("*")
                ->from("view_belum_transfer_keuangan_tagihan")
                ->where("project_id", $project->id)
                ->where("id", $id)
                ->get()->row();
        }
        if ($jenis == "PPN") {
            $data       = $this->db->select("*")
                ->from("view_belum_transfer_keuangan_ppn")
                ->where("project_id", $project->id)
                ->where("id", $id)
                ->get()->row();
        }
        if ($jenis == "Denda/Penalti") {
            $data       = $this->db->select("*")
                ->from("view_belum_transfer_keuangan_denda_penalti")
                ->where("project_id", $project->id)
                ->where("id", $id)
                ->get()->row();
        }
        $pt_id_erems = $this->db->select("pt.source_id")
            ->from("unit")
            ->join(
                "pt",
                "pt.id = unit.pt_id"
            )
            ->where("unit.id", $data->unit_id)
            ->get()->row()->source_id;
        // $pt_id_erems = $this->db->select("pt.source_id")
        //     ->from("unit")
        //     ->join(
        //         "pt",
        //         "pt.id = unit.pt_id"
        //     )
        //     ->where("unit.id", $data->unit_id)
        //     ->get()->row()->source_id;

        $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/validasivoucher";

        $dataValidasi = (object) [];
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

        $dataValidasi->note                 = "ESTATE $data->cara_pembayaran " . date("d/m/Y");
        $dataValidasi->pengajuandate        = $data->tgl_bayar2; //tgl_bayar
        $dataValidasi->kwitansidate         = $data->tgl_bayar2; //tgl_bayar
        $dataValidasi->coa_detail           = $data->item_coa;
        $dataValidasi->description          = "ESTATE $data->kawasan $data->blok/$data->no_unit";
        $dataValidasi->sub_unit             = "$data->blok/$data->no_unit";
        // $dataValidasi->seq_detail           = $k;   
        $dataValidasi->amount               = $data->nilai_item;
        $dataValidasi->kawasan              = $data->kawasan;
        $dataValidasi->paymentdate          = $data->tgl_bayar2; //tgl_bayar 

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
        if (curl_errno($ch)) {
            // print "Error: " . curl_error($ch);
            return (object) [
                "result" => -1,
                "message" => "ERROR di API atau Koneksi Internet"
            ];
        } else {
            // Show me the result

            $transaction = json_decode($data, TRUE);

            curl_close($ch);
        }
        return (object) $transaction;
    }
    public function validasi_gabungan($data)
    {
        $data = (object) $data;
        $date = date("Y-m-d");
        $dataValidasi = (object) [];
        $dataValidasi->project_id           = $data->project_erems_id;
        // $dataValidasi->pt_id                = 4223;
        $dataValidasi->pt_id                = $data->pt_erems_id;

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
        $dataValidasi->amount_header        = $data->total_nilai;
        $dataValidasi->coa_header           = $data->coa_cara_bayar;

        $dataValidasi->note                 = "ESTATE $data->cara_bayar " . date("d/m/Y");
        $dataValidasi->pengajuandate        = $date; //tgl_bayar
        $dataValidasi->kwitansidate         = $date; //tgl_bayar
        $dataValidasi->description          = "ESTATE";
        $dataValidasi->sub_unit             = "$data->cara_bayar/$date";
        $dataValidasi->amount               = 0;
        $dataValidasi->paymentdate          = $date; //tgl_bayar 
        // echo("dataValidasi<pre>");
        // print_r($dataValidasi);
        // echo("</pre>");
        // var_dump(json_encode($dataValidasi));
        $result = [];
        foreach ($data->coa_item as $k => $v) {
            $result2 = (object) [];
            $result2->coa = $v;
            $dataValidasi->coa_detail           = $v;
            $ch = curl_init();
            $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/validasivoucher";
            // echo("<pre>");
            // print_r($dataValidasi);
            // echo("</pre>");


            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);
            $data = curl_exec($ch);
            // echo("data<pre>");
            // print_r($data);
            // echo("</pre>");
            if (curl_errno($ch)) {
                // print "Error: " . curl_error($ch);
                $result2->result = "Error";
                $result2->message = "ERROR di API atau Koneksi Internet";
            } else {
                // Show me the result
                $transaction = json_decode($data, TRUE);
                // var_dump($transaction);
                $result2->result = "Sukses";
                $result2->message = "Data Sukses ter-Validasi";
            }
            curl_close($ch);
            array_push($result, $result2);
        }
        // var_dump($result);
        return $result;
        // $dataValidasi->seq_detail           = $k;   
        // $dataValidasi->kawasan              = $data->kawasan;


        die;

        $project = $this->m_core->project();

        $project_id_erems = $this->db->select("source_id")
            ->from("project")
            ->where("id", $project->id)
            ->get()->row()->source_id;

        if ($jenis == "Service") {
            $data   = $this->db->select("*")
                ->from("view_belum_transfer_keuangan_tagihan")
                ->where("project_id", $project->id)
                ->where("id", $id)
                ->get()->row();
        }
        if ($jenis == "PPN") {
            $data       = $this->db->select("*")
                ->from("view_belum_transfer_keuangan_ppn")
                ->where("project_id", $project->id)
                ->where("id", $id)
                ->get()->row();
        }
        if ($jenis == "Denda/Penalti") {
            $data       = $this->db->select("*")
                ->from("view_belum_transfer_keuangan_denda_penalti")
                ->where("project_id", $project->id)
                ->where("id", $id)
                ->get()->row();
        }
        $pt_id_erems = $this->db->select("pt.source_id")
            ->from("unit")
            ->join(
                "pt",
                "pt.id = unit.pt_id"
            )
            ->where("unit.id", $data->unit_id)
            ->get()->row()->source_id;

        $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/validasivoucher";

        $dataValidasi = (object) [];
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
        $dataValidasi->amount_header        = $data->total_nilai;

        // $dataValidasi->coa_header           = $v->coa_cara_pembayaran;
        $dataValidasi->coa_header           = $data->coa_cara_pembayaran;

        $dataValidasi->note                 = "ESTATE $data->cara_pembayaran " . date("d/m/Y");
        $dataValidasi->pengajuandate        = $data->tgl_bayar2; //tgl_bayar
        $dataValidasi->kwitansidate         = $data->tgl_bayar2; //tgl_bayar
        $dataValidasi->coa_detail           = $data->item_coa;
        $dataValidasi->description          = "ESTATE $data->kawasan $data->blok/$data->no_unit";
        $dataValidasi->sub_unit             = "$data->blok/$data->no_unit";
        // $dataValidasi->seq_detail           = $k;   
        $dataValidasi->amount               = $data->nilai_item;
        $dataValidasi->kawasan              = $data->kawasan;
        $dataValidasi->paymentdate          = $data->tgl_bayar2; //tgl_bayar 

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
        if (curl_errno($ch)) {
            // print "Error: " . curl_error($ch);
            return (object) [
                "result" => -1,
                "message" => "ERROR di API atau Koneksi Internet"
            ];
        } else {
            // Show me the result

            $transaction = json_decode($data, TRUE);

            curl_close($ch);
        }
        return (object) $transaction;
    }
    public function kirim_voucher($pt_id, $cara_pembayaran_id, $total_nilai)
    {
        $project = $this->m_core->project();
        $user_id = $this->m_core->user_id();

        $project_id_erems = $this->db->select("source_id")
            ->from("project")
            ->where("id", $project->id)
            ->get()->row()->source_id;

        $data_tagihan   = $this->db->select("*")
            ->from("view_belum_transfer_keuangan_tagihan")
            ->where("project_id", $project->id)
            ->where("pt_id", $pt_id)
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->get()->result();

        $data_ppn       = $this->db->select("*")
            ->from("view_belum_transfer_keuangan_ppn")
            ->where("project_id", $project->id)
            ->where("pt_id", $pt_id)
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->get()->result();
        $data_denda_penalti       = $this->db->select("*")
            ->from("view_belum_transfer_keuangan_denda_penalti")
            ->where("project_id", $project->id)
            ->where("pt_id", $pt_id)
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->get()->result();
        $data = array_merge($data_tagihan, $data_ppn, $data_denda_penalti);
        var_dump($data);

        $pt_id_erems = $this->db->select("pt.source_id")
            ->from("unit")
            ->join(
                "pt",
                "pt.id = unit.pt_id"
            )
            ->where("unit.id", $data[0]->unit_id)
            ->get()->row()->source_id;
        $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/requestkey";
    
        $ch = curl_init();


        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);

        $tmp = curl_exec($ch);
        if (curl_errno($ch)) {
            return (object) [
                "result" => -1,
                "message" => "ERROR di API atau Koneksi Internet"
            ];
        } else {
            $key = json_decode($tmp, TRUE);
            $key = $key["key"];
            curl_close($ch);
            // echo(json_encode($key));
        }
        if (isset($key)) {
            // var_dump("Key : ". $key);

            $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/uploadvoucher";


            $this->db->insert("voucher_header_generate", ["generate" => date("ymdhis") . $pt_id_erems]);
            $id_header = $this->db->insert_id();


            $dataValidasi = (object) [];
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
            $dataValidasi->request_key           = $key;
            $dataValidasi->amount_header        = $total_nilai;
            $dataValidasi->coa_header           = $data[0]->coa_cara_pembayaran;
            $dataValidasi->note                 = "ESTATE " . $data[0]->cara_pembayaran . " " . date("d/m/Y");
            $jumlahBerhasil = 0;
            $jumlahGagal = 0;
            $data_uploud = [];
            $data_validasi = [];
            foreach ($data as $k => $v) {
                $data_uploud_detail = (object) [];
                // var_dump($v);
                $dataValidasi->pengajuandate        = $v->tgl_bayar2; //tgl_bayar
                $dataValidasi->kwitansidate         = $v->tgl_bayar2; //tgl_bayar
                $dataValidasi->coa_detail           = $v->item_coa;
                $dataValidasi->description          = "ESTATE $v->kawasan $v->blok/$v->no_unit : $v->periode_tagihan";
                $dataValidasi->sub_unit             = "$v->blok/$v->no_unit";
                $dataValidasi->seq_detail           = $k;
                $dataValidasi->amount               = $v->nilai_item;
                $dataValidasi->kawasan              = $v->kawasan;
                $dataValidasi->paymentdate          = $v->tgl_bayar2; //tgl_bayar 

                $ch = curl_init();
                echo('test');
                var_dump($dataValidasi);


                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);
                var_dump($dataValidasi);
                $data = curl_exec($ch);
                if (curl_errno($ch)) {
                    $jumlahGagal++;
                    // print "Error: " . curl_error($ch);
                    return (object) [
                        "result" => -1,
                        "message" => "ERROR di API atau Koneksi Internet"
                    ];
                } else {
                    // Show me the result

                    $transaction = json_decode($data, TRUE);
                    if ($transaction['result'] == 1) {
                        $jumlahBerhasil++;
                    } else {
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
                $data_uploud_detail->voucher_detail_id          = $k + 1;
                $data_uploud_detail->project_id_erems           = $project_id_erems;
                $data_uploud_detail->pt_id_erems                = $pt_id_erems;
                $data_uploud_detail->cara_pembayaran_id         = $cara_pembayaran_id;

                $data_uploud_detail->kawasan                    = $v->kawasan;
                $data_uploud_detail->blok                       = $v->blok;
                $data_uploud_detail->no_unit                    = $v->no_unit;
                $data_uploud_detail->periode_tagihan            = $v->periode_tagihan;
                $data_uploud_detail->tgl_bayar                  = $v->tgl_bayar2;
                $data_uploud_detail->update_ke                  = 0;

                array_push($data_uploud, $data_uploud_detail);
                array_push($data_validasi, $dataValidasi);
            }

            if ($jumlahGagal == 0) {
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
        if ($jumlahGagal == 0) {
            return (object) [
                "result" => 1,
                "jumlah_berhasil" => $jumlahBerhasil,
                "jumlah_gagal" => $jumlahGagal,
                "data_uploud"  => $data_uploud,
                "data_validasi" => $data_validasi
            ];
        } else {
            return (object) [
                "result" => 0,
                "jumlah_berhasil" => $jumlahBerhasil,
                "jumlah_gagal" => $jumlahGagal,
                "data_uploud"  => $data_uploud,
                "data_validasi" => $data_validasi
            ];
        }
    }
    public function kirim_voucher_gabungan($data)
    {
        $data = (object) $data;
        $date = date("Y-m-d");
        $user_id = $this->m_core->user_id();
        $project = $this->m_core->project();
        $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/requestkey";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        $tmp = curl_exec($ch);
        if (curl_errno($ch)) {
            return (object) [
                "result" => -1,
                "message" => "ERROR di API atau Koneksi Internet"
            ];
        } else {
            $key = json_decode($tmp, TRUE);
            $key = $key["key"];
            curl_close($ch);
        }
        // echo("Key<pre>");
        // print_r($tmp);
        // echo("</pre>");
        
        $check = (object) [];
        $check->item = [];
        $check->coa_item = [];
        $check->have_sub = [];
        $data_gabungan = [];

        if (isset($key)) {
            $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/uploadvoucher";

            $data_voucher_detail = $this->get_detail($data->pt_erems_id, $data->cara_bayar_id, $data->periode_awal, $data->periode_akhir);

            foreach ($data_voucher_detail->data as $k => $v) {
                $v = (object) $v;
                if (!in_array($v->item, $check->item)) {
                    array_push($check->item, $v->item);
                    array_push($check->coa_item, $v->coa_item);
                    $have_sub = $this->db->select("kelsub_id")
                        ->from("gl_2018.dbo.m_coa")
                        ->where("project_id", $data->project_erems_id)
                        ->where("pt_id", $data->pt_erems_id)
                        ->where("coa", $v->coa_item)
                        ->get()->row();
                    $have_sub = $have_sub ? 1 : 0;
                    array_push($check->have_sub, $have_sub);
                    if ($have_sub == 0) {
                        $data_gabungan[$data->coa_item[$k]]['nilai_item'] = 0;
                        $data_gabungan[$data->coa_item[$k]]['item'] = $v;
                    }
                }
            }
            // echo("data1<pre>");
            //     print_r($check);
            // echo("</pre>"); 
            // echo("data2<pre>");
            //     print_r($data_gabungan);
            // echo("</pre>"); 
            $periode_awal = $data->periode_awal;
            $periode_akhir = $data->periode_akhir;
            if($periode_awal == $periode_akhir){
                $note_periode = $periode_awal;
            }else{
                $note_periode = $periode_awal.'-'.$periode_akhir;
            }
            $this->db->insert("voucher_header_generate", ["generate" => date("ymdhis") . $data->pt_erems_id]);
            $id_header = $this->db->insert_id();

            // $id_header = 123;
            $dataValidasi = (object) [];
            $dataValidasi->project_id           = $data->project_erems_id;

            $dataValidasi->pt_id                = $data->pt_erems_id;
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
            $dataValidasi->amount_header        = $data->total_nilai;
            $dataValidasi->coa_header           = $data->coa_cara_bayar;
            $dataValidasi->note                 = strtoupper("ESTATE $data->cara_bayar " . $note_periode);
            $dataValidasi->request_key           = $key;

            $jumlahGagal = 0;
            $jumlahBerhasil = 0;
            $data_uploud = [];
            $data_validasi = [];
            $z = 0;

            foreach ($data_voucher_detail->data as $k => $v) {
                $v = (object) $v;
                foreach ($check->item as $k2 => $v2) {
                    if ($v->item == $v2 && $v->coa_item == $check->coa_item[$k2] && $check->have_sub[$k2] == 1) {
                        $dataValidasi->pengajuandate        = $date; //tgl_bayar
                        $dataValidasi->kwitansidate         = $date; //tgl_bayar
                        $dataValidasi->coa_detail           = $v->coa_item;
                        $dataValidasi->description          = strtoupper("ESTATE Pembayaran $v->item");
                        $dataValidasi->sub_unit             = strtoupper("$v->blok/$v->no_unit");
                        $dataValidasi->seq_detail           = ++$z;
                        $dataValidasi->amount               = $v->nilai_item;
                        $dataValidasi->kawasan              = strtoupper($v->kawasan);
                        $dataValidasi->paymentdate          = $date; //tgl_bayar 
                        $dataValidasi->mergebycoa           = 'yes';

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);
                        var_dump($dataValidasi);
                        $dataResult = curl_exec($ch);
                        echo("<pre>");
                        print_r($dataResult);
                        echo("</pre>");
                        
                        if (curl_errno($ch)) {
                            $jumlahGagal++;
                            return (object) [
                                "result" => -1,
                                "message" => "ERROR di API atau Koneksi Internet"
                            ];
                        } else {
                            $transaction = json_decode($dataResult, TRUE);
                            // echo ("transaction<pre>");
                            // print_r($transaction);
                            // echo ("</pre>");
                            if ($transaction['result'] == 1)    $jumlahBerhasil++;
                            else                                $jumlahGagal++;
                            curl_close($ch);
                        }
                        // array_push($data_uploud, $dataValidasi);

                        // echo ("data3<pre>");
                        // print_r($dataValidasi);
                        // echo ("</pre>");
                        $this->db->insert("voucher", $dataValidasi);
                    }
                }
            }
            // echo ("data_uploud<pre>");
            // print_r($data_uploud);
            // echo ("</pre>");
            if ($jumlahGagal == 0) {
                return (object) [
                    "result" => 1,
                    "jumlah_berhasil" => $jumlahBerhasil,
                    "jumlah_gagal" => $jumlahGagal,
                ];
            } else {
                return (object) [
                    "result" => 0,
                    "jumlah_berhasil" => $jumlahBerhasil,
                    "jumlah_gagal" => $jumlahGagal,
                ];
            }
        }
    }
    public function kirim_voucher_gabungan_bu($data)
    {
        $data = (object) $data;
        // echo ("<pre>");
        // print_r($data);
        // echo ("</pre>");
        $date = date("Y-m-d");
        $user_id = $this->m_core->user_id();
        $project = $this->m_core->project();

        $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/requestkey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        $tmp = curl_exec($ch);
        if (curl_errno($ch)) {
            return (object) [
                "result" => -1,
                "message" => "ERROR di API atau Koneksi Internet"
            ];
        } else {
            $key = json_decode($tmp, TRUE);
            $key = $key["key"];
            curl_close($ch);
            // echo(json_encode($key));
        }
        if (isset($key)) {
            $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/uploadvoucher";

            // $this->db->insert("voucher_header_generate", ["generate" => date("ymdhis") . $data->pt_erems_id]);
            // $id_header = $this->db->insert_id();

            // check punya sub atau tidak 
            // get semua tipe
            $check = (object) [];
            $check->tipe = [];
            $check->coa_item = [];
            $check->have_sub = [];
            $data_gabungan = [];
            foreach ($data->tipe as $k => $v) {
                if (!in_array($v, $check->tipe)) {
                    array_push($check->tipe, $v);
                    array_push($check->coa_item, $data->coa_item[$k]);
                    $have_sub = $this->db->select("kelsub_id")
                        ->from("gl_2018.dbo.m_coa")
                        ->where("project_id", $data->project_erems_id)
                        ->where("pt_id", $data->pt_erems_id)
                        ->where("coa", $data->coa_item[$k])
                        ->get()->row();
                    $have_sub = $have_sub ? 1 : 0;
                    $check->have_sub[$data->coa_item[$k]] = $have_sub;
                    if ($have_sub == 0) {
                        $data_gabungan[$data->coa_item[$k]]['nilai_item'] = 0;
                        $data_gabungan[$data->coa_item[$k]]['item'] = $v;
                    }
                }
            }
            $this->db->insert("voucher_header_generate", ["generate" => date("ymdhis") . $data->pt_erems_id]);
            $id_header = $this->db->insert_id();
            // $id_header = 123;
            $dataValidasi = (object) [];
            $dataValidasi->project_id           = $data->project_erems_id;

            $dataValidasi->pt_id                = $data->pt_erems_id;
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
            $dataValidasi->amount_header        = $data->total_nilai;
            $dataValidasi->coa_header           = $data->coa_cara_bayar;
            $dataValidasi->note                 = "ESTATE $data->cara_bayar " . date("d/m/Y");
            $dataValidasi->request_key           = $key;


            $jumlahGagal = 0;
            $jumlahBerhasil = 0;
            $data_uploud = [];
            $data_validasi = [];
            $z = 0; // index
            foreach ($data->tipe as $k => $v) {
                if ($check->have_sub[$data->coa_item[$k]] == 1) {
                    // var_dump($check->have_sub[$data->coa_item[$k]]);
                    $data_uploud_detail = (object) [];
                    // var_dump($v);
                    $dataValidasi->pengajuandate        = $date; //tgl_bayar
                    $dataValidasi->kwitansidate         = $date; //tgl_bayar
                    $dataValidasi->coa_detail           = $data->coa_item[$k];
                    $dataValidasi->description          = "ESTATE";
                    $dataValidasi->sub_unit             = $data->blok[$k] . "/" . $v->no_unit[$k] . " " . $v->pemilik;
                    $dataValidasi->seq_detail           = $k;
                    $dataValidasi->amount               = $data->nilai_item[$k];
                    // $dataValidasi->kawasan              = $v->kawasan;
                    $dataValidasi->paymentdate          = $date; //tgl_bayar 
                    $dataValidasi->mergebycoa           = 'yes';

                    // $ch = curl_init();
                    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    // curl_setopt($ch, CURLOPT_URL, $url);
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
                    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);
                    // $dataResult = curl_exec($ch);
                    // if (curl_errno($ch)) {
                    //     $jumlahGagal++;
                    //     // print "Error: " . curl_error($ch);
                    //     return (object) [
                    //         "result" => -1,
                    //         "message" => "ERROR di API atau Koneksi Internet"
                    //     ];
                    // } else {
                    //     // Show me the result

                    //     $transaction = json_decode($dataResult, TRUE);
                    //     if ($transaction['result'] == 1) {
                    //         $jumlahBerhasil++;
                    //     } else {
                    //         var_dump($transaction);
                    //         $jumlahGagal++;
                    //     }
                    //     $data_uploud_detail->status                     = json_encode($transaction);

                    //     curl_close($ch);
                    // }
                    $data_uploud_detail->voucher_header_id          = $id_header;
                    // $data_uploud_detail->voucher_id                 = $v->voucher_id;
                    $data_uploud_detail->project_id                 = $project->id;
                    $data_uploud_detail->pt_id                      = $data->pt_erems_id;
                    $data_uploud_detail->t_pembayaran_detail_id     = 0;
                    $data_uploud_detail->create_date                = date("Y-m-d h:i:s.000");;
                    $data_uploud_detail->user_create_id             = $user_id;
                    $data_uploud_detail->cara_pembayaran            = $data->cara_bayar;
                    $data_uploud_detail->item                       = $data->tipe[$k];
                    $data_uploud_detail->nilai_item                 = $data->nilai_item[$k];
                    $data_uploud_detail->nilai_total_item           = $data->total_nilai;
                    $data_uploud_detail->coa_item                   = $data->coa_item[$k];
                    $data_uploud_detail->coa_cara_pembayaran        = $data->coa_cara_bayar;
                    $data_uploud_detail->voucher_detail_id          = $z + 1;
                    $data_uploud_detail->project_id_erems           = $data->project_erems_id;
                    $data_uploud_detail->pt_id_erems                = $data->pt_erems_id;;
                    $data_uploud_detail->cara_pembayaran_id         = $data->cara_bayar_id;
                    $data_uploud_detail->mergebycoa                 = 'yes';
                    $data_uploud_detail->sub_unit                   = $data->blok[$k] . "/" . $v->no_unit[$k] . " " . $v->pemilik;

                    // $data_uploud_detail->kawasan                    = $v->kawasan;
                    // $data_uploud_detail->blok                       = $v->blok;
                    // $data_uploud_detail->no_unit                    = $v->no_unit;
                    // $data_uploud_detail->periode_tagihan            = $v->periode_tagihan;
                    $data_uploud_detail->tgl_bayar                  = $date;
                    $data_uploud_detail->update_ke                  = 0;
                    // echo ("data_uploud_detail<pre>");
                    // print_r($data_uploud_detail);
                    // echo ("</pre>");
                    array_push($data_uploud, $data_uploud_detail);
                    array_push($data_validasi, $dataValidasi);
                    $z++;
                } else {
                    // var_dump($data_gabungan[$data->coa_item[$k]]['nilai_item']);
                    array_push(
                        $data_gabungan[$data->coa_item[$k]],
                        [
                            'nilai_item' => $data_gabungan[$data->coa_item[$k]]['nilai_item'] + $data->nilai_item[$k]
                        ]
                    );
                }
            }
            foreach ($data_gabungan as $k => $v) {
                $data_uploud_detail = (object) [];
                // var_dump($v);
                $dataValidasi->pengajuandate        = $date; //tgl_bayar
                $dataValidasi->kwitansidate         = $date; //tgl_bayar
                $dataValidasi->coa_detail           = $k;
                $dataValidasi->description          = "ESTATE";
                $dataValidasi->sub_unit             = $data->blok[$k] . "/" . $v->no_unit[$k] . " " . $v->pemilik;
                $dataValidasi->seq_detail           = $k;
                $dataValidasi->amount               = $v->nilai_item;
                // $dataValidasi->kawasan              = $v->kawasan;
                $dataValidasi->paymentdate          = $date; //tgl_bayar 
                $dataValidasi->mergebycoa           = 'yes';

                // $ch = curl_init();
                // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                // curl_setopt($ch, CURLOPT_URL, $url);
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                // curl_setopt($ch, CURLOPT_HTTPHEADER, $dataValidasi);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                // curl_setopt($ch, CURLOPT_POST, true);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataValidasi);

                // $dataResult = curl_exec($ch);
                // if (curl_errno($ch)) {
                //     $jumlahGagal++;
                //     // print "Error: " . curl_error($ch);
                //     return (object) [
                //         "result" => -1,
                //         "message" => "ERROR di API atau Koneksi Internet"
                //     ];
                // } else {
                //     // Show me the result

                //     $transaction = json_decode($dataResult, TRUE);
                //     if ($transaction['result'] == 1) {
                //         $jumlahBerhasil++;
                //     } else {
                //         $jumlahGagal++;
                //     }

                //     curl_close($ch);
                // }

                $data_uploud_detail->voucher_header_id          = $id_header;
                // $data_uploud_detail->voucher_id                 = $v->voucher_id;
                $data_uploud_detail->project_id                 = $project->id;
                $data_uploud_detail->pt_id                      = $data->pt_erems_id;
                $data_uploud_detail->t_pembayaran_detail_id     = 0;
                $data_uploud_detail->create_date                = date("Y-m-d h:i:s.000");;
                $data_uploud_detail->user_create_id             = $user_id;
                $data_uploud_detail->cara_pembayaran            = $data->cara_bayar;
                $data_uploud_detail->item                       = $v->tipe;
                $data_uploud_detail->nilai_item                 = $v->nilai_item;
                $data_uploud_detail->nilai_total_item           = $data->total_nilai;
                $data_uploud_detail->coa_item                   = $v;
                $data_uploud_detail->coa_cara_pembayaran        = $data->coa_cara_bayar;
                $data_uploud_detail->voucher_detail_id          = $z + 1;
                $data_uploud_detail->project_id_erems           = $data->project_erems_id;
                $data_uploud_detail->pt_id_erems                = $data->pt_erems_id;;
                $data_uploud_detail->cara_pembayaran_id         = $data->cara_bayar_id;
                $data_uploud_detail->mergebycoa           = 'yes';
                $data_uploud_detail->sub_unit             = $data->blok[$k] . "/" . $v->no_unit[$k] . " " . $v->pemilik;

                // $data_uploud_detail->kawasan                    = $v->kawasan;
                // $data_uploud_detail->blok                       = $v->blok;
                // $data_uploud_detail->no_unit                    = $v->no_unit;
                // $data_uploud_detail->periode_tagihan            = $v->periode_tagihan;
                $data_uploud_detail->tgl_bayar                  = $date;
                $data_uploud_detail->update_ke                  = 0;
                // echo ("data_uploud_detail<pre>");
                // print_r($data_uploud_detail);
                // echo ("</pre>");
                array_push($data_uploud, $data_uploud_detail);
                array_push($data_validasi, $dataValidasi);
            }
            if ($jumlahGagal == 0) {
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
        if ($jumlahGagal == 0) {
            return (object) [
                "result" => 1,
                "jumlah_berhasil" => $jumlahBerhasil,
                "jumlah_gagal" => $jumlahGagal,
                "data_uploud"  => $data_uploud,
                "data_validasi" => $data_validasi
            ];
        } else {
            return (object) [
                "result" => 0,
                "jumlah_berhasil" => $jumlahBerhasil,
                "jumlah_gagal" => $jumlahGagal,
                "data_uploud"  => $data_uploud,
                "data_validasi" => $data_validasi
            ];
        }
        echo ("data_kirim<pre>");
        print_r($data_validasi);
        echo ("</pre>");

        die;
        $project = $this->m_core->project();

        $project_id_erems = $this->db->select("source_id")
            ->from("project")
            ->where("id", $project->id)
            ->get()->row()->source_id;

        $data_tagihan   = $this->db->select("*")
            ->from("view_belum_transfer_keuangan_tagihan")
            ->where("project_id", $project->id)
            ->where("pt_id", $pt_id)
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->get()->result();

        $data_ppn       = $this->db->select("*")
            ->from("view_belum_transfer_keuangan_ppn")
            ->where("project_id", $project->id)
            ->where("pt_id", $pt_id)
            ->where("cara_pembayaran_id", $cara_pembayaran_id)
            ->get()->result();

        $data = array_merge($data_tagihan, $data_ppn);

        $pt_id_erems = $this->db->select("pt.source_id")
            ->from("unit")
            ->join(
                "pt",
                "pt.id = unit.pt_id"
            )
            ->where("unit.id", $data[0]->unit_id)
            ->get()->row()->source_id;

        $url = "http://13.76.184.138:8080/api/cashierapi/index.php/ems/uploadvoucher";

        $dataValidasi = (object) [];
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
        $dataValidasi->note                 = "ESTATE " . $data[0]->cara_pembayaran . " " . date("d/m/Y");
        $jumlahBerhasil = 0;
        $jumlahGagal = 0;

        foreach ($data as $k => $v) {
            // var_dump($v);
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
            if (curl_errno($ch)) {
                $jumlahGagal++;
                // print "Error: " . curl_error($ch);
                return (object) [
                    "result" => -1,
                    "message" => "ERROR di API atau Koneksi Internet"
                ];
            } else {
                // Show me the result
                $transaction = json_decode($data, TRUE);
                if ($transaction['result'] == 1) {
                    $jumlahBerhasil++;
                } else {
                    $jumlahGagal++;
                }
                // var_dump($transaction);
                curl_close($ch);
            }
        }

        return (object) [
            "jumlah_berhasil" => $jumlahBerhasil,
            "jumlah_gagal" => $jumlahGagal
        ];
    }
}
