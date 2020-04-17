<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<style>
    .icon,
    i {
        margin-top: 10px;
    }
</style>
<?php
$fullUrl = site_url() . "/" . implode("/", (array_slice($this->uri->segment_array(), 0, -1)));
?>
<style type="text/css">
    @keyframes lds-double-ring {
        0% {
            -webkit-transform: rotate(0);
            transform: rotate(0);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @-webkit-keyframes lds-double-ring {
        0% {
            -webkit-transform: rotate(0);
            transform: rotate(0);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes lds-double-ring_reverse {
        0% {
            -webkit-transform: rotate(0);
            transform: rotate(0);
        }

        100% {
            -webkit-transform: rotate(-360deg);
            transform: rotate(-360deg);
        }
    }

    @-webkit-keyframes lds-double-ring_reverse {
        0% {
            -webkit-transform: rotate(0);
            transform: rotate(0);
        }

        100% {
            -webkit-transform: rotate(-360deg);
            transform: rotate(-360deg);
        }
    }

    .lds-double-ring {
        position: absolute;
        z-index: 99;
        margin-top: 20%;
    }

    .lds-double-ring div {
        position: absolute;
        width: 160px;
        height: 160px;
        top: 20px;
        left: 20px;
        border-radius: 50%;
        border: 8px solid #000;
        border-color: #1d3f72 transparent #1d3f72 transparent;
        -webkit-animation: lds-double-ring 2s linear infinite;
        animation: lds-double-ring 2s linear infinite;
    }

    .lds-double-ring div:nth-child(2) {
        width: 140px;
        height: 140px;
        top: 30px;
        left: 30px;
        border-color: transparent #5699d2 transparent #5699d2;
        -webkit-animation: lds-double-ring_reverse 2s linear infinite;
        animation: lds-double-ring_reverse 2s linear infinite;
    }

    .lds-double-ring {
        width: 200px !important;
        height: 200px !important;
        -webkit-transform: translate(-100px, -100px) scale(1) translate(100px, 100px);
        transform: translate(-100px, -100px) scale(1) translate(100px, 100px);
    }
</style>
<!-- body -->

<style>
    .dataTables_length {
        display: none
    }

    .dataTables_info {
        display: none
    }

    #DataTables_Table_1 thead {
        display: none
    }

    .dataTables_paginate {
        display: none
    }

    .modal-content {
        height: inherit
    }
</style>
<div class="right_col" role="main" style="min-height: 100vh;">
    <div id="loading" class="lds-css ng-scope" hidden>
        <div style="width:100%;height:100%" class="col-md-offset-4 lds-double-ring">
            <div></div>
            <div></div>
        </div>
    </div>

    <div class>
        <div class="page-title">
            <div class="title_left" style="margin-bottom: 10px">
                <h3>
                    Rincian Unit
                </h3>
            </div>
            <div class="clearfix"></div>
            <div id='content' class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group" style="margin-top:20px">
                                    <label class="control-label col-lg-2 col-md-2 col-sm-12 col-md-offset-2" style="margin-top:10px">
                                        Kawasan - Blok - Unit - Pemilik:
                                    </label>
                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <select id='unit' class='col-md-12 form-control select2'>
                                            <?php if ($unit->id != 0) : ?>
                                                <option selected value="<?= $unit->id ?>"><?= $unit->text ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix" style="margin-bottom:10px"></div>
                        </div>
                        <div class="clearfix" style="margin-top:15px"></div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="x_panel ">
                                <div class="x_title">
                                    <h2>Info<small>Unit</small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table id="" class="table table-striped jambo_table">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Info</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                                <td>Pemilik</td>
                                                <td class='pemilik'>-</td>
                                            </tr>
                                            <tr>
                                                <td>Penghuni</td>
                                                <td class='penghuni'>-</td>
                                            </tr> -->
                                            <tr>
                                                <td>UID (Mobile Apps)</td>
                                                <td class='uid_mobile_app'>-</td>
                                            </tr>
                                            <tr>
                                                <td>Purpose Use</td>
                                                <td class='purpose_use'>-</td>
                                            </tr>
                                            <tr>
                                                <td>Type Unit</td>
                                                <td class='type-unit'>-</td>
                                            </tr>
                                            <tr>
                                                <td>Golongan</td>
                                                <td class='golongan'>-</td>
                                            </tr>
                                            <tr>
                                                <td>Luas Tanah</td>
                                                <td class='luas-tanah'>-</td>
                                            </tr>
                                            <tr>
                                                <td>Luas Bangunan</td>
                                                <td class='luas-bangunan'>-</td>
                                            </tr>
                                            <tr>
                                                <td>Luas Taman</td>
                                                <td class='luas-taman'>-</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal ST ( dd/mm/yyy )</td>
                                                <td class='tanggal-st'>-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div id="read_more_unit">
                                        <button class="btn btn-primary col-md-12" onclick="window.open('<?= site_url() ?>/P_master_unit/','_blank')">Read More</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="x_panel ">
                                <div class="x_title">
                                    <h2>Info<small>Customer</small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="" id="range" role="tabpanel" data-example-id="togglable-tabs">

                                        <div id="myTabContent">
                                            <div role="tabpanel" class="tab-pane fade active in " id="tab_content1" aria-labelledby="home-tab">
                                                <p>
                                                </p>
                                                <table id="" class="table table-striped jambo_table">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-3">Data</th>
                                                            <th>Info</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                                    <li id="komponen-luas-kavling" role="presentation" class="active">

                                                        <a href="" id="komponen-bangunan" role="tab" data-toggle="tab" aria-expanded="true" onclick="$('.tab_content1').show();$('.tab_content2').hide()">Pemilik</a>
                                                    </li>
                                                    <li id="komponen-luas-bangunan" role="presentation" class="">
                                                        <a href="" role="tab" id="komponen-kavling" data-toggle="tab" aria-expanded="false" onclick="$('.tab_content2').show();$('.tab_content1').hide()">Penghuni</a>
                                                    </li>
                                                </ul>
                                                <table id="" class="table table-striped jambo_table">
                                                    <tbody>
                                                        <tr>
                                                            <td class="col-md-3">Nama</td>
                                                            <td id="pemilik-nama" class='tab_content1 tab-content'>-</td>
                                                            <td id="penghuni-nama" class='tab_content2 tab-content' hidden>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td id="pemilik-email" class='tab_content1 tab-content pemilik-email'>-</td>
                                                            <td id="penghuni-email" class='tab_content2 tab-content' hidden>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td>No. Hp 1</td>
                                                            <td id="pemilik-no-hp-1" class='tab_content1 tab-content'>-</td>
                                                            <td id="penghuni-no-hp-1" class='tab_content2 tab-content' hidden>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td>No. Hp 2</td>
                                                            <td id="pemilik-no-hp-2" class='tab_content1 tab-content'>-</td>
                                                            <td id="penghuni-no-hp-2" class='tab_content2 tab-content' hidden>-</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Alamat Domisili</td>
                                                            <td id="pemilik-alamat-domisili" class='tab_content1 tab-content'>-</td>
                                                            <td id="penghuni-alamat-domisili" class='tab_content2 tab-content' hidden>-</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div id="read_more_customer-pemilik" class="tab_content1">
                                                    <button class="btn btn-primary col-md-12" onclick="window.open('<?= site_url() ?>/P_master_customer/','_blank')">Read More</button>
                                                </div>
                                                <div id="read_more_customer-penghuni" class="tab_content2" hidden>
                                                    <button class="btn btn-primary col-md-12" onclick="window.open('<?= site_url() ?>/P_master_unit/','_blank')">Read More</button>
                                                </div>

                                                <p></p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Tagihan<small>Unit</small></h2>
                                    <div style="float:right" id="cetak_tagihan">
                                        <button onclick="window.open('<?= site_url() ?>/Cetakan/konfirmasi_tagihan/unit/18')" class="btn btn-primary">Cetak Tagihan</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table id="" class="table table-striped jambo_table">

                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Info</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Periode Sekarang</td>
                                                <td>
                                                    <a>
                                                        <u>
                                                            <?= date("F Y") ?>
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr hidden>
                                                <td>Service</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#service">
                                                        <u id="jumlah_tagihan_service">
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Nilai Pokok</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#Tunggakan">
                                                        <u id="total_nilai_pokok">
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total PPN</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#Tunggakan">
                                                        <u id="total_ppn">
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Denda</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#Tunggakan">
                                                        <u id="total_denda">
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Penalti</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#Tunggakan">
                                                        <u id="total_penalti">
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr id="head_total_pemutihan_nilai_pokok" hidden>
                                                <td>Total Pemutihan Nilai Pokok</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#Tunggakan">
                                                        <u id="total_pemutihan_nilai_pokok">
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr id="head_total_pemutihan_nilai_denda" hidden>
                                                <td>Total Pemutihan Nilai Denda</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#Tunggakan">
                                                        <u id="total_pemutihan_nilai_denda">
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Grand Total</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#Tunggakan">
                                                        <u id="total_semua">
                                                            <!-- Rp. 1.190.000 -->
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Action<small>Unit</small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <button id="redirect-pembayaran" onclick="" class="btn btn-primary col-md-2" style="margin-left:4.1%">Pembayaran</button>
                                    <button id="redirect-deposit" onclick="" class="btn btn-primary col-md-2 col-md-offset-1">Deposit</button>
                                    <button data-toggle="modal" data-target="#modal_cetak_kwitansi" onclick="" class="btn btn-primary col-md-2 col-md-offset-1">Cetak Kwitansi</button>
                                    <button data-toggle="modal" data-target="#modal_void_pembayaran" onclick="" class="btn btn-primary col-md-2 col-md-offset-1">Void Pembayaran</button>
                                </div>
                                <div class="x_content">
                                    <button id="redirect-delete-tagihan" onclick="" class="btn btn-primary col-md-2" style="margin-left:4.1%">Delete Tagihan</button>
                                    <?php if ($project->id == 13) : ?>
                                    <button id="redirect-ubah-tagihan" onclick="" class="btn btn-primary col-md-2 col-md-offset-1">Ubah Tagihan</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Action<small>Lain</small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <button class="btn btn-primary col-md-2" onclick="window.open('<?= site_url() ?>/report/P_history_pembayaran/')" style="margin-left:4.1%">History Pembayaran</button>
                                    <button class="btn btn-primary col-md-2 col-md-offset-1" onclick="">Input Meter Air</button>
                                    <button class="btn btn-primary col-md-2 col-md-offset-1" onclick="window.open('<?= site_url() ?>/Transaksi/P_kirim_konfirmasi_tagihan')">Konfirmasi Tagihan</button>
                                    <!-- <button class="btn btn-primary col-md-2 col-md-offset-1" onclick="window.open('<?= site_url() ?>/Transaksi/P_surat_peringatan')">Surat Peringatan</button> -->
                                </div>
                                <div class="x_content">
                                    <button class="btn btn-primary col-md-2" onclick="window.open('<?= site_url() ?>/Transaksi/P_voucher_tagihan')" style="margin-left:4.1%">Buat Voucher</button>
                                    <button class="btn btn-primary col-md-2 col-md-offset-1" onclick="window.open('<?= site_url() ?>/Transaksi/P_voucher_tagihan_history')" disabled>History Voucher</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12" hidden>
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Action<small>Unit</small></h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table class="table tableDT4 col-md-12">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Menu</th>
                                                <th>Menu</th>
                                                <th>Menu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a id="redirect-pembayaran" href="">-
                                                        <u class="btn btn-primary">
                                                            Pembayaran
                                                        </u>
                                                    </a>
                                                </td>
                                                <td><a id="redirect-deposit" href="">-
                                                        <u class="btn btn-primary">
                                                            Deposit
                                                        </u>
                                                    </a>
                                                </td>
                                                <td><a data-toggle="modal" data-target="#modal_cetak_kwitansi">-
                                                        <u id="cetak_kwitansi" class="btn btn-primary">
                                                            Cetak Kwitansi
                                                        </u>
                                                    </a>
                                                </td>
                                                <td><a href="">-
                                                        <u class="btn btn-primary">
                                                            Input Meter Air
                                                        </u>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr hidden>
                                                <td><a data-toggle="modal" data-target="#modal_history_pembayaran">-
                                                        <u id='btn-history-pembayaran' class="btn btn-primary">
                                                            History Pembayaran
                                                        </u>
                                                    </a>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="x_content">
                            <div class="modal fade" id="modal_history_pembayaran" data-backdrop="static" data-keyboard="false" style="width:100vw">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="margin-top:100px; width:max-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" style="text-align:center;">Service<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <table id="table-history-pembayaran" class="table table-striped jambo_table">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Service</th>
                                                        <th>Nama Service</th>
                                                        <th>Tgl Bayar</th>
                                                        <th>Total Bayar</th>
                                                        <th>Cetak</th>
                                                        <th class='input_kwitansi'>No. Kwitansi</th>
                                                        <th class='input_kwitansi'>Save</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-history-pembayaran">
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div class="modal fade" id="modal_cetak_kwitansi" data-backdrop="static" data-keyboard="false" style="width:100vw">
                                <div class="modal-dialog" style="width: fit-content">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" style="text-align:center;">Cetak Kwitansi<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <table id="table-kwitansi" class="table table-striped jambo_table">
                                                <thead>
                                                    <tr>
                                                        <th>Check</th>
                                                        <th>Kode Service</th>
                                                        <th>Nama Service</th>
                                                        <th>Tgl Bayar</th>
                                                        <th>Cetak</th>
                                                        <th class='input_kwitansi'>No. Kwitansi</th>
                                                        <th class='input_kwitansi'>Save</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-kwitansi">
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button id="cetak-kwitansi-multiple" class="btn btn-primary" disabled>Cetak Multiple</button>
                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="x_content">
                            <div class="modal fade" id="modal_void_pembayaran" data-backdrop="static" data-keyboard="false" style="width:100vw">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="margin-top:100px;">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" style="text-align:center;">Void Pembayaran<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <table id="table-kwitansi" class="table table-striped jambo_table">
                                                <thead>
                                                    <tr>
                                                        <th>No. Referensi</th>
                                                        <th>Tanggal Bayar</th>
                                                        <th>Cara Bayar</th>
                                                        <th>Total_tagihan</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-void_pembayaran">
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="x_content">
                            <div class="modal fade" id="service" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="margin-top:100px;">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" style="text-align:center;">Service<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped jambo_table">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Service</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>IPL</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Air</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div class="modal fade" id="Tunggakan" data-backdrop="static" data-keyboard="false">
                                <div class="">
                                    <div class="modal-content" style="margin-top:100px;">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" style="text-align:center;">Tunggakan<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="tableDT3 table table-striped jambo_table">
                                                <thead>
                                                    <tr>
                                                        <th>Periode Penggunaan</th>
                                                        <th>Periode Tagihan</th>
                                                        <th>Service</th>
                                                        <th>Nilai Pokok ( Rp. )</th>
                                                        <th>Nilai PPN ( Rp. )</th>
                                                        <th>Nilai Denda( Rp. )</th>
                                                        <th>Nilai Penalti( Rp. )</th>
                                                        <th>Pemutihan Nilai Pokok ( Rp. )</th>
                                                        <th>Pemutihan Nilai Denda( Rp. )</th>
                                                        <th>Total ( Rp. )</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_tagihan">
                                                </tbody>
                                                <tfoot id="tfoot_tagihan">
                                                </tfoot>
                                            </table>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div class="modal fade" id="TagihanIPL" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="margin-top:100px;">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" style="text-align:center;">Detail IPL<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped jambo_table">
                                                <thead>
                                                    <tr>
                                                        <th>Data</th>
                                                        <th>Info</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Golongan</td>
                                                        <td class='golongan'>Rumah Elite</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sub Golongan</td>
                                                        <td>Elite/A (Dummy)</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Range</td>
                                                        <td>P-01</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Formula</td>
                                                        <td>2</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Luas Tanah</td>
                                                        <td class='luas-tanah'>200 m2</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Luas Bangunan</td>
                                                        <td class='luas-bangunan'>100 m2</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div class="modal fade" id="TagihanAir" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="margin-top:100px;">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" style="text-align:center;">Detail Air<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped jambo_table">
                                                <thead>
                                                    <tr>
                                                        <th>Data</th>
                                                        <th>Info</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Golongan</td>
                                                        <td>Rumah Elite</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sub Golongan</td>
                                                        <td>Elite/A</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Range</td>
                                                        <td>A-01</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Formula</td>
                                                        <td>1</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Meter Awal</td>
                                                        <td>100 m3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Meter Akhir</td>
                                                        <td>150 m3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pemakaian</td>
                                                        <td>50 m3</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="x_content">
                            <div class="modal fade" id="FormulaIPL1" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="margin-top:100px;">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" style="text-align:center;">Detail Air<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped jambo_table">
                                                <thead>
                                                    <tr>
                                                        <th>Data</th>
                                                        <th>Info</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Golongan</td>
                                                        <td>Rumah Elite</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sub Golongan</td>
                                                        <td>Elite/A</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Range</td>
                                                        <td>A-01</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Formula</td>
                                                        <td>1</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Meter Awal</td>
                                                        <td>100 m3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Meter Akhir</td>
                                                        <td>150 m3</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pemakaian</td>
                                                        <td>50 m3</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div class="modal fade" id="modal-iframe" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="modal_iframe_close()">&times;</button>
                                            <h4 id="modal-title-iframe" class="modal-title" style="text-align:center;">Service<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="iframe-modal-body">
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button id="modal-iframe-newtab" type="button" class="btn btn-primary" disabled>Open New Tab</button>

                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link" onclick="modal_iframe_close()">Close</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div class="modal fade" id="modal-iframe-large" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-lg" style="width: 100%">
                                    <div class="modal-content">

                                        <div class="modal-header" style="height: 45px">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="modal_iframe_close()">&times;</button>
                                            <h4 id="modal-title-iframe-large" class="modal-title" style="text-align:center;">Pembayaran<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body" style="height: 80%">
                                            <div id="iframe-large-modal-body">
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center; height:65px">
                                            <button id="modal-iframe-large-newtab" type="button" class="btn btn-primary" disabled>Open New Tab</button>

                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link" onclick="modal_iframe_large_close()">Close</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div class="modal fade" id="modal-void-description" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="modal_iframe_close()">&times;</button>
                                            <h4 id="modal-title-iframe-large" class="modal-title" style="text-align:center;">Void Description<span class="grt"></span></h4>
                                        </div>
                                        <div class="modal-body" style="height: 20vh">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3 col-md-3 col-sm-12">Keterangan<br></label>
                                                <div class="col-lg-9 col-md-9 col-sm-12">
                                                    <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-submit-void">Submit</button>

                                            <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function modal_iframe($url1, $url2 = null, $title = null) {
                                $("#iframe-modal-body").html("<iframe id='modal-iframe-id' src='' frameborder='0' style='width: 100%; height:100%'></iframe>");
                                $("#modal-iframe-id").attr("src", $url1);
                                // $("#modal-iframe_id").attr("onload",$('#loading').show());
                                $("#modal-iframe").modal("show");
                                $("#modal-iframe-newtab").attr('disabled', false);
                                $("#modal-iframe-newtab").attr("onclick", "window.open('" + $url1 + "')");
                                $("#modal-title-iframe").html($title);
                            }

                            function modal_iframe_large($url1, $url2 = null, $title = null) {

                                $("#iframe-large-modal-body").html("<iframe id='modal-iframe-large-id' src='' frameborder='0' style='width: 100%; height:100%'></iframe>");
                                $("#modal-iframe-large-id").attr("src", $url1);
                                // $("#modal-iframe-large_id").attr("onload",$('#loading').show());
                                $("#modal-iframe-large").modal("show");
                                $("#modal-iframe-large-newtab").attr('disabled', false);
                                $("#modal-iframe-large-newtab").attr("onclick", "window.open('" + $url2 + "')");
                                $("#modal-title-iframe-large").html($title);

                            }

                            function modal_iframe_large_close() {
                                $("#modal-iframe-large-id").remove();

                            }

                            function modal_iframe_close() {
                                $("#modal-iframe-id").remove();

                            }

                            function tableICheck() {
                                $("input.flat").iCheck({
                                    checkboxClass: "icheckbox_flat-green",
                                    radioClass: "iradio_flat-green"
                                })
                            }

                            $(document).ready(function() {

                                $("body").on("click", ".btn-void_pembayaran", function() {
                                    $("#modal-void-description").modal("show");
                                    $("#description").attr('pembayaran_id', $(this).val());
                                });
                                $("#btn-submit-void").click(function() {
                                    $.ajax({
                                        type: "GET",
                                        data: {
                                            pembayaran_id: $("#description").attr('pembayaran_id'),
                                            description: $("#description").val()
                                        },
                                        url: "<?= site_url() ?>/Transaksi/P_unit/ajax_save_void",
                                        dataType: "json",
                                        success: function(data) {
                                            if (data.status == 1) {
                                                notif('Sukses', data.message, 'success')
                                            } else {
                                                notif('Gagal', data.message, 'danger')
                                            }
                                            setTimeout(function() {
                                                if (!alert('Halaman akan di refresh otomatis untuk update data!')) {
                                                    window.location.reload();
                                                }
                                            }, 5 * 1000);
                                        }
                                    });
                                });
                                $("body").on("ifChanged", ".check-pembayaran-kwitansi", function() {
                                    $("#cetak-kwitansi-multiple").attr("disabled", true);
                                    $.each($(".check-pembayaran-kwitansi"), function(key, value) {
                                        if (value.checked) {
                                            $("#cetak-kwitansi-multiple").attr("disabled", false);
                                        }
                                    })
                                })
                                $("#cetak-kwitansi-multiple").click(function() {
                                    var pembayaran_id =
                                        $('.check-pembayaran-kwitansi:checkbox:checked').map(function() {
                                            return $(this).attr('val');
                                        }).get().join();
                                    window.open('<?= site_url() ?>/Cetakan/kwitansi/gabungan?pembayaran_id=' + pembayaran_id);
                                    $.ajax({
                                        type: "GET",
                                        data: {
                                            pembayaran_id: $(".check-pembayaran-kwitansi").val()
                                        },
                                        url: "<?= site_url() ?>/Cetakan/P_unit/kwitansi/gabungan",
                                        dataType: "json",
                                        success: function(data) {

                                        }
                                    });
                                });
                                $("#a").html('');
                                $('.select2').select2();
                                $("#tbody-kwitansi").on("click", ".unhide_input_kwitansi", function() {
                                    $(".input_kwitansi").css("display", "table-cell");
                                });

                                function notif(title, text, type) {
                                    new PNotify({
                                        title: title,
                                        text: text,
                                        type: type,
                                        styling: 'bootstrap3'
                                    });
                                }
                                $("#unit").change(function() {
                                    tipe = $("#unit").val().split('.')[0];
                                    id = $("#unit").val().split('.')[1];
                                    if (tipe == 1) {
                                        $.ajax({
                                            type: "GET",
                                            data: {
                                                unit_id: id
                                            },
                                            url: "<?= site_url() ?>/Transaksi/P_unit/get_ajax_unit_detail",
                                            dataType: "json",
                                            success: function(data) {
                                                console.log(data);
                                                $('.pemilik').html(data.pemilik);
                                                $('.penghuni').html(data.penghuni);
                                                $('.uid_mobile_app').html(data.uid);
                                                $('.purpose-use').html(data.purpose_use);
                                                $('.golongan').html(data.golongan);
                                                $('.tanggal-st').html(data.tgl_st);
                                                $('.luas-tanah').html(data.luas_tanah + ' m2');
                                                $('.luas-bangunan').html(data.luas_bangunan + ' m2');
                                                $('.luas-taman').html(data.luas_taman + ' m2');
                                                $('.type-unit').html(data.type_unit);


                                                $('#pemilik-nama').html(data.pemilik.name + " ");
                                                $('#pemilik-email').html(data.pemilik.email + " ");
                                                $('#pemilik-no-hp-1').html(data.pemilik.mobilephone1 + " ");
                                                $('#pemilik-no-hp-2').html(data.pemilik.mobilephone2 + " ");
                                                $('#pemilik-alamat-domisili').html(data.pemilik.address + " ");


                                                $('#penghuni-nama').html("");
                                                $('#penghuni-email').html("");
                                                $('#penghuni-no-hp-1').html("");
                                                $('#penghuni-no-hp-2').html("");
                                                $('#penghuni-alamat-domisili').html("");

                                                if (data.penghuni) {
                                                    $('#penghuni-nama').html(data.penghuni.name + " ");
                                                    $('#penghuni-email').html(data.penghuni.email + " ");
                                                    $('#penghuni-no-hp-1').html(data.penghuni.mobilephone1 + " ");
                                                    $('#penghuni-no-hp-2').html(data.penghuni.mobilephone2 + " ");
                                                    $('#penghuni-alamat-domisili').html(data.penghuni.address + " ");
                                                }
                                                $('#total_nilai_pokok').html("Rp. " + formatC(data.jumlah_nilai_pokok))
                                                $('#total_ppn').html("Rp. " + formatC(data.jumlah_nilai_ppn))
                                                $('#total_denda').html("Rp. " + formatC(data.jumlah_nilai_denda))
                                                $('#total_penalti').html("Rp. " + formatC(data.jumlah_nilai_penalti))
                                                $('#total_pemutihan_nilai_pokok').html("Rp. " + formatC(data.jumlah_nilai_pemutihan_pokok))
                                                $('#total_pemutihan_nilai_denda').html("Rp. " + formatC(data.jumlah_nilai_pemutihan_denda))
                                                $('#total_semua').html("Rp. " + formatC(data.jumlah_total))

                                                // $('#jumlah_tagihan_service').html(data.jumlah_tagihan_service);
                                                // $('#jumlah_tunggakan_bulan').html(data.jumlah_tunggakan_bulan + " Bulan");
                                                // $('#jumlah_tunggakan').html("Rp. " + formatC(data.jumlah_tunggakan));
                                                // $('#jumlah_denda').html("Rp. " + formatC(data.jumlah_denda));
                                                // $('#jumlah_penalti').html("Rp. " + formatC(data.jumlah_penalti));
                                                // $('#jumlah_tagihan').html("Rp. " + formatC(data.jumlah_tagihan));
                                                // $('#jumlah_semua').html("Rp. " + formatC(data.jumlah_semua));
                                                $(".tableDT3").DataTable().destroy();
                                                $("#tbody_tagihan").html("");
                                                $("#tbody-kwitansi").html("");
                                                $("#tbody-void_pembayaran").html("");


                                                $("#redirect-pembayaran").attr("onclick", "modal_iframe_large('<?= site_url() ?>/Transaksi/P_pembayaran/add_modal/" + id + "','<?= site_url() ?>/Transaksi/P_pembayaran/add/" + id + "','Pembayaran')");
                                                $("#redirect-delete-tagihan").attr("onclick", "modal_iframe_large('<?= site_url() ?>/Transaksi/P_delete_tagihan/delete/" + id + "','<?= site_url() ?>/Transaksi/P_delete_tagihan/delete/" + id + "','Delete Tagihan')");
                                                $("#redirect-ubah-tagihan").attr("onclick", "modal_iframe_large('<?= site_url() ?>/Transaksi/P_ubah_tagihan/edit/" + id + "','<?= site_url() ?>/Transaksi/P_ubah_tagihan/edit/" + id + "','Ubah Tagihan')");
                                                $("#redirect-deposit").attr("onclick", "modal_iframe_large('<?= site_url() ?>/Transaksi/P_deposit/add_modal/" + id + "','<?= site_url() ?>/Transaksi/P_deposit/add/" + id + "'),'Deposit'");


                                                $("#cetak_tagihan").html("<button onclick=\"modal_iframe('<?= site_url() ?>/Cetakan/konfirmasi_tagihan/unit/" + id + "','<?= site_url() ?>/Cetakan/konfirmasi_tagihan/unit/" + id + "','Konfirmasi Tagihan')\" class=\"btn btn-primary\">Cetak Tagihan</button>");
                                                $("#read_more_unit").html("<button onclick=\"modal_iframe_large('<?= site_url() ?>/P_master_unit/edit?id=" + id + "','<?= site_url() ?>/P_master_unit/edit?id=" + id + "','Informasi Unit')\" class=\"btn btn-primary col-md-12\">Read More</button>");
                                                $("#read_more_customer-pemilik").html("<button onclick=\"modal_iframe_large('<?= site_url() ?>/P_master_customer/edit?id=" + data.pemilik_id + "','<?= site_url() ?>/P_master_customer/edit?id=" + data.pemilik_id + "','Informasi Pemilik')\" class=\"btn btn-primary col-md-12\">Read More</button>");
                                                $("#read_more_customer-penghuni").html("<button onclick=\"modal_iframe_large('<?= site_url() ?>/P_master_customer/edit?id=" + data.penghuni_id + "','<?= site_url() ?>/P_master_customer/edit?id=" + data.penghuni_id + "','Informasi Penghuni')\" class=\"btn btn-primary col-md-12\">Read More</button>");

                                                $.each(data.tagihan_air, function(key, value) {
                                                    var str = "<tr>";
                                                    str += "<td>" + value.periode_pemakaian.substr(5, 2) + "-" + value.periode_pemakaian.substr(0, 4) + "</td>";
                                                    str += "<td>" + value.periode.substr(5, 2) + "-" + value.periode.substr(0, 4) + "</td>";
                                                    str += "<td>" + value.service + "</td>";
                                                    str += "<td><a data-toggle='modal' data-target='#TagihanAIR'><u>" + formatC(value.total_tanpa_ppn) + "</u></a></td>";
                                                    str += "<td><a data-toggle='modal' data-target='#TagihanAIR'><u>" + formatC(value.ppn) + "</u></a></td>";

                                                    // if(value.nilai_tagihan>0)
                                                    //     str += "<td><a data-toggle='modal' data-target='#TagihanAIR'><u>"+value.nilai_tagihan+"</u></a></td>";
                                                    // else
                                                    //     str += "<td>"+value.nilai_tagihan+"</td>";
                                                    // if(value.nilai_tunggakan>0)
                                                    //     str += "<td><a data-toggle='modal' data-target='#TagihanAIR'><u>"+value.nilai_tunggakan+"</u></a></td>";
                                                    // else
                                                    //      str += "<td>"+value.nilai_tunggakan+"</td>";                                                
                                                    str += "<td>" + formatC(value.nilai_denda) + "</td>";
                                                    str += "<td>" + formatC(value.nilai_penalti) + "</td>";
                                                    str += "<td>" + formatC(value.view_pemutihan_nilai_tagihan) + "</td>";
                                                    str += "<td>" + formatC(value.view_pemutihan_nilai_denda) + "</td>";
                                                    console.log("ppn " + value.total_tanpa_ppn + value.ppn + value.nilai_denda + value.nilai_penalti);
                                                    str += "<td>" + formatC(value.total_tanpa_ppn + value.ppn + value.nilai_denda + value.nilai_penalti - (value.view_pemutihan_nilai_tagihan + value.view_pemutihan_nilai_denda)) + "</td>";
                                                    str += "</tr>"
                                                    $("#tbody_tagihan").append(str);
                                                });
                                                $.each(data.tagihan_lingkungan, function(key, value) {
                                                    var str = "<tr>";
                                                    str += "<td>" + value.periode_pemakaian.substr(5, 2) + "-" + value.periode_pemakaian.substr(0, 4) + "</td>";
                                                    str += "<td>" + value.periode.substr(5, 2) + "-" + value.periode.substr(0, 4) + "</td>";
                                                    str += "<td>" + value.service + "</td>";
                                                    str += "<td><a data-toggle='modal' data-target='#TagihanAIR'><u>" + formatC(value.total_tanpa_ppn) + "</u></a></td>";
                                                    str += "<td><a data-toggle='modal' data-target='#TagihanAIR'><u>" + formatC(value.ppn) + "</u></a></td>";

                                                    // if(value.nilai_tagihan>0)
                                                    //     str += "<td><a id='detail_perhitungan_ipl' id_service_jenis='"+value.service_jenis_id+"' id_tagihan='"+value.tagihan_id+"' data-toggle='modal' data-target='#TagihanIPL'><u>"+value.nilai_tagihan+"</u></a></td>";
                                                    // else
                                                    //     str += "<td>"+value.nilai_tagihan+"</td>";                                                
                                                    // if(value.nilai_tunggakan>0)
                                                    //     str += "<td><a id='detail_perhitungan_ipl' id_service_jenis='"+value.service_jenis_id+"' id_tagihan='"+value.tagihan_id+"' data-toggle='modal' data-target='#TagihanIPL'><u>"+value.nilai_tunggakan+"</u></a></td>";
                                                    // else
                                                    //     str += "<td>"+value.nilai_tunggakan+"</td>";                                                
                                                    str += "<td>" + formatC(value.nilai_denda) + "</td>";
                                                    str += "<td>" + formatC(value.nilai_penalti) + "</td>";
                                                    str += "<td>" + formatC(value.view_pemutihan_nilai_tagihan) + "</td>";
                                                    str += "<td>" + formatC(value.view_pemutihan_nilai_denda) + "</td>";
                                                    console.log(value.total_tanpa_ppn + value.ppn + value.nilai_denda + value.nilai_penalti);

                                                    str += "<td>" + formatC(value.total_tanpa_ppn + value.ppn + value.nilai_denda + value.nilai_penalti - (value.view_pemutihan_nilai_tagihan + value.view_pemutihan_nilai_denda)) + "</td>";
                                                    str += "</tr>"
                                                    $("#tbody_tagihan").append(str);
                                                });
                                                $.each(data.kwitansi, function(key, value) {
                                                    var str = "<tr>";
                                                    if (value.service_jenis_id != 5) {
                                                        str += "<td><input type='checkbox' class='check-pembayaran-kwitansi flat' name='check-pembayaran-kwitansi[]' val='" + value.service_jenis_id + "." + value.pembayaran_id + "'></td>";
                                                    } else {
                                                        str += "<td></td>";
                                                    }

                                                    str += "<td>" + value.code_service + "</td>";
                                                    str += "<td>" + value.name_service + "</td>";
                                                    str += "<td>" + value.tgl_bayar + "</td>";
                                                    if (value.service_jenis_id == 1) {
                                                        str += "<td><button class='btn btn-primary' onClick=\"modal_iframe('<?= site_url() ?>/Cetakan/kwitansi/lingkungan/" + value.pembayaran_id + "')\">Cetak</button></td>";
                                                    } else if (value.service_jenis_id == 2) {
                                                        str += "<td><button class='btn btn-primary' onClick=\"modal_iframe('<?= site_url() ?>/Cetakan/kwitansi/air/" + value.pembayaran_id + "')\">Cetak</button></td>";
                                                    } else if (value.service_jenis_id == 5) {
                                                        str += "<td><button class='btn btn-primary' onClick=\"modal_iframe('<?= site_url() ?>/Cetakan/kwitansi/lo/" + value.pembayaran_id + "')\">Cetak</button></td>";
                                                    } else {
                                                        str += "<td></td>";
                                                    }

                                                    if (value.no_kwitansi == 0) {
                                                        str += "<form method='post'><td class='input_kwitansi'><input name='no_kwitansi' value=''></td>";
                                                        str += "<td class='input_kwitansi'><button type='submit' class='btn-save-kwitansi btn btn-primary' pembayaran_id='" + value.pembayaran_id + "'>Save</button></td></form>";
                                                    } else {
                                                        str += "<td>" + formatC(value.no_kwitansi) + "</td>";
                                                        str += "<td></td>";

                                                    }
                                                    str += "</tr>"
                                                    $("#tbody-kwitansi").append(str);
                                                });
                                                $.each(data.kwitansi_deposit, function(key, value) {
                                                    var str = "<tr>";
                                                    str += "<td></td>";
                                                    str += "<td></td>";
                                                    str += "<td>" + value.name_service + "</td>";
                                                    str += "<td>" + value.tgl_bayar + "</td>";
                                                    str += "<td><button class='btn btn-primary' onClick=\"window.open('<?= site_url() ?>/Cetakan/kwitansi/deposit/" + value.deposit_id + "')\">Cetak</button></td>";
                                                    if (value.no_kwitansi == 0) {
                                                        str += "<form method='post'><td class='input_kwitansi'><input name='no_kwitansi' value=''></td>";
                                                        str += "<td class='input_kwitansi'><button type='submit' class='btn-save-kwitansi btn btn-primary' pembayaran_id='" + value.deposit_id + "'>Save</button></td></form>";
                                                    } else {
                                                        str += "<td>" + formatC(value.no_kwitansi) + "</td>";
                                                        str += "<td></td>";
                                                    }
                                                    str += "</tr>"
                                                    $("#tbody-kwitansi").append(str);
                                                });
                                                $.each(data.void_pembayaran, function(key, value) {
                                                    var str = "<tr>";
                                                    str += "<td>" + value.no_kwitansi + "</td>";
                                                    str += "<td>" + value.tgl_bayar + "</td>";
                                                    str += "<td>" + value.cara_pembayaran + "</td>";
                                                    str += "<td class='nilai-total-void'>" + value.bayar + "</td>";
                                                    if (value.is_void == 0)
                                                        str += "<td>" +
                                                        "<button class='btn btn-primary btn-void_pembayaran' value=" + value.id + ">" +
                                                        "Void" +
                                                        "</button>" +
                                                        "</td></tr>";
                                                    else if (value.is_void == 3)
                                                        str += "<td>Menunggu Approval</td></tr>";
                                                    else if (value.is_void == 1)
                                                        str += "<td>Sudah di Void</td></tr>";
                                                    else if (value.is_void == 2)
                                                        str += "<td><button class='btn btn-primary btn-void_pembayaran' value=" + value.id + ">Void (Void Reject)</button></td></tr>";
                                                    $("#tbody-void_pembayaran").append(str);

                                                });
                                                $("#tfoot_tagihan").html("");
                                                var str = "<tr>";
                                                str += "<th colspan=3>Total</th>";
                                                // str += "<th>"+formatC(data.jumlah_tagihan+data.jumlah_tunggakan)+"</th>";
                                                str += "<th>" + formatC(data.jumlah_nilai_pokok) + "</th>";
                                                str += "<th>" + formatC(data.jumlah_nilai_ppn) + "</th>";
                                                str += "<th>" + formatC(data.jumlah_nilai_denda) + "</th>";
                                                str += "<th>" + formatC(data.jumlah_nilai_penalti) + "</th>";
                                                str += "<th>" + formatC(data.jumlah_nilai_pemutihan_pokok) + "</th>";
                                                str += "<th>" + formatC(data.jumlah_nilai_pemutihan_denda) + "</th>";
                                                str += "<th>" + formatC(data.jumlah_total) + "</th>";
                                                str += "</tr>";
                                                var table_pemutihan_tagihan = true;
                                                var table_pemutihan_denda = true;
                                                if (data.jumlah_pemutihan_tagihan > 0) {
                                                    $("#head_total_pemutihan_nilai_pokok").show();
                                                    $("#total_pemutihan_nilai_pokok").html("Rp. " + formatC(data.jumlah_pemutihan_tagihan));
                                                    table_pemutihan_tagihan = true;
                                                } else {
                                                    $("#head_total_pemutihan_nilai_pokok").hide();
                                                    table_pemutihan_tagihan = false;

                                                }
                                                if (data.jumlah_pemutihan_denda > 0) {
                                                    $("#head_total_pemutihan_nilai_denda").show();
                                                    $("#total_pemutihan_nilai_denda").html("Rp. " + formatC(data.jumlah_pemutihan_denda));
                                                    table_pemutihan_denda = true;
                                                } else {
                                                    $("#head_total_pemutihan_nilai_denda").hide();
                                                    table_pemutihan_denda = false;
                                                }

                                                $("#tfoot_tagihan").append(str);
                                                // $(".tableDT3").DataTable({
                                                //     "paging": false,
                                                //     "order": [
                                                //         [1, 'asc']
                                                //     ],
                                                //     "columnDefs": [
                                                //         {
                                                //             "targets": [ 8 ],
                                                //             "visible": table_pemutihan_tagihan
                                                //         },
                                                //         {
                                                //             "targets": [ 7 ],
                                                //             "visible": table_pemutihan_denda
                                                //         }
                                                //     ]
                                                // });
                                                tableICheck();

                                            }
                                        });
                                    } else if (tipe == 2) {
                                        $.ajax({
                                            type: "GET",
                                            data: {
                                                unit_id: id
                                            },
                                            url: "<?= site_url() ?>/Transaksi/P_unit/get_ajax_unit_virtual_detail",
                                            dataType: "json",
                                            success: function(data) {
                                                $('#pemilik-nama').html(data.pemilik.name + " ");
                                                $('#pemilik-email').html(data.pemilik.email + " ");
                                                $('#pemilik-no-hp-1').html(data.pemilik.mobilephone1 + " ");
                                                $('#pemilik-no-hp-2').html(data.pemilik.mobilephone2 + " ");
                                                $('#pemilik-alamat-domisili').html(data.pemilik.address + " ");


                                                $('#penghuni-nama').html("");
                                                $('#penghuni-email').html("");
                                                $('#penghuni-no-hp-1').html("");
                                                $('#penghuni-no-hp-2').html("");
                                                $('#penghuni-alamat-domisili').html("");

                                                if (data.penghuni) {
                                                    $('#penghuni-nama').html(data.penghuni.name + " ");
                                                    $('#penghuni-email').html(data.penghuni.email + " ");
                                                    $('#penghuni-no-hp-1').html(data.penghuni.mobilephone1 + " ");
                                                    $('#penghuni-no-hp-2').html(data.penghuni.mobilephone2 + " ");
                                                    $('#penghuni-alamat-domisili').html(data.penghuni.address + " ");
                                                }

                                                $("#redirect-pembayaran").attr("onclick", "modal_iframe_large('<?= site_url() ?>/Transaksi/P_pembayaran/add_modal/" + id + "?jenis_unit=2','<?= site_url() ?>/Transaksi/P_pembayaran/add/" + id + "?jenis_unit=2','Pembayaran')");
                                                $("#redirect-deposit").attr("onclick", "modal_iframe_large('<?= site_url() ?>/Transaksi/P_deposit/add_modal/" + id + "','<?= site_url() ?>/Transaksi/P_deposit/add/" + id + "'),'Deposit'");


                                                $("#cetak_tagihan").html("<button onclick=\"modal_iframe('<?= site_url() ?>/Cetakan/konfirmasi_tagihan/unit/" + id + "','<?= site_url() ?>/Cetakan/konfirmasi_tagihan/unit/" + id + "','Konfirmasi Tagihan')\" class=\"btn btn-primary\">Cetak Tagihan</button>");
                                                $("#read_more_unit").html("<button onclick=\"modal_iframe_large('<?= site_url() ?>/P_master_unit/edit?id=" + id + "','<?= site_url() ?>/P_master_unit/edit?id=" + id + "','Informasi Unit')\" class=\"btn btn-primary col-md-12\">Read More</button>");
                                                $("#read_more_customer-pemilik").html("<button onclick=\"modal_iframe_large('<?= site_url() ?>/P_master_customer/edit?id=" + data.pemilik_id + "','<?= site_url() ?>/P_master_customer/edit?id=" + data.pemilik_id + "','Informasi Pemilik')\" class=\"btn btn-primary col-md-12\">Read More</button>");
                                                $("#read_more_customer-penghuni").html("<button onclick=\"modal_iframe_large('<?= site_url() ?>/P_master_customer/edit?id=" + data.penghuni_id + "','<?= site_url() ?>/P_master_customer/edit?id=" + data.penghuni_id + "','Informasi Penghuni')\" class=\"btn btn-primary col-md-12\">Read More</button>");
                                            }
                                        });
                                    }
                                });

                                // $("body").on("click", ".btn-void_pembayaran", function() {
                                //     console.log($(this));
                                //     $.ajax({
                                //         type: "POST",
                                //         data: {
                                //             pembayaran_id: $(this).val(),
                                //             nilai : unformatC($(this).parents('tr').find('.nilai-total-void').html())
                                //         },
                                //         url: "<?= site_url() ?>/Transaksi/P_unit/ajax_save_void",
                                //         dataType: "json",
                                //         success: function(data) {
                                //             console.log(data);
                                //             if (data.status) {
                                //                 notif('Sukses', data.message, 'success');
                                //             } else {
                                //                 notif('Sukses', data.message, 'danger');
                                //             }
                                //         }
                                //     });
                                // });
                                $("body").on("click", ".btn-save-kwitansi", function() {
                                    var no_kwitansi = $(this).parent().parent().children(".input_kwitansi").children().val();
                                    var pembayaran_id = $(this).attr("pembayaran_id");
                                    $.ajax({
                                        type: "POST",
                                        data: {
                                            pembayaran_id: pembayaran_id,
                                            no_kwitansi: no_kwitansi
                                        },
                                        url: "<?= site_url() ?>/Transaksi/P_unit/ajax_save_kwitansi",
                                        dataType: "json",
                                        success: function(data) {
                                            console.log(data);
                                            if (data) {
                                                notif('Sukses', 'Input Kwitansi Berhasil', 'success');
                                            } else {
                                                notif('Sukses', 'Input Kwitansi Gagal', 'danger');
                                            }
                                        }
                                    });
                                });
                                $(".tableDT3").DataTable({
                                    "paging": false,
                                    "order": [
                                        [1, 'asc']
                                    ]
                                });
                                $(".tableDT4").dataTable({
                                    "order": []
                                });


                                $("#unit").select2({
                                    width: 'resolve',
                                    // resize:true,
                                    minimumInputLength: 1,
                                    placeholder: 'Kawasan - Blok - Unit - Pemilik',
                                    ajax: {
                                        type: "GET",
                                        dataType: "json",
                                        url: "<?= site_url() ?>/Transaksi/P_unit/get_ajax_unit",
                                        data: function(params) {
                                            return {
                                                data: params.term
                                            }
                                        },
                                        processResults: function(data) {
                                            console.log(data);
                                            // Tranforms the top-level key of the response object from 'items' to 'results'
                                            return {
                                                results: data
                                            };
                                        }
                                    }
                                });
                                // Setup - add a text input to each footer cell
                                $('#tableDT2 tfoot th').each(function() {
                                    var title = $(this).text();
                                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                                });

                                // DataTable
                                var table = $('#tableDT2').DataTable();

                                // Apply the search
                                table.columns().every(function() {
                                    var that = this;
                                    $('input', this.footer()).on('keyup change', function() {
                                        if (that.search() !== this.value) {
                                            that
                                                .search(this.value)
                                                .draw();
                                        }
                                    });
                                });
                                if (<?= $unit->id ?> != 0)
                                    $("#unit").val(<?= $unit->id ?>).change();
                            });


                            function confirm_modal(id) {
                                jQuery('#modal_delete_m_n').modal('show', {
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                document.getElementById('delete_link_m_n').setAttribute("href", "<?= site_url('P_master_mappingCoa/delete?id="+id+"'); ?>");
                                document.getElementById('delete_link_m_n').focus();
                            }
                            $(function() {
                                var total_tagihan = 0;
                                for (var i = 0; i < $(".total_tagihan").length; i++) {
                                    total_tagihan = total_tagihan + parseInt($(".total_tagihan")[i].innerHTML);
                                }
                                $("#total_tagihan").html(total_tagihan);
                                $("#total_service").html($(".service-row").length);
                            });
                        </script>