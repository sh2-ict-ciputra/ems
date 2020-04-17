<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- switchery -->
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.reload()">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/P_master_unit/save" autocomplete="off">



        <div class="x_content">
            <br />
            <div class="title" id="print_proses"></div>
            <div class="col-md-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="kawasan_flag" name="kawasan_flag" class="form-control select2" required>
                            <option value="">--Pilih Kawasan--</option>
                            <?php
                            foreach ($dataKawasan as $key => $v) {
                                echo "<option value='$v[id]'>$v[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
                    <div id="lihat_blok">
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select id="blok" class="form-control select2" name="blok">
                                <option value="">--Pilih Blok--</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">No Unit</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" required name="nomor_unit" value="" placeholder="Masukkan Nomor Unit">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Pemilik</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="pemilik" required="" class="form-control select2">
                            <option value="">--Pilih Pemilik--</option>
                            <?php
                            foreach ($dataCustomer as $key => $v) {
                                echo "<option value='$v[id]'>$v[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Penghuni</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="penghuni" name="penghuni" class="form-control select2" disabled>
                            <option value="">--Pilih Penghuni--</option>
                            <?php
                            foreach ($dataCustomer as $key => $v) {
                                echo "<option value='$v[id]'>$v[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Unit</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="jenis_unit" class="form-control select2">
                            <option value="">--Pilih Jenis Unit--</option>
                            <option value="1">Rental (Disewakan)</option>
                            <option value="2">Dihuni</option>
                            <option value="3">Non Proyek, yang Menyewa</option>

                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Purpose Use</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="produk_kategori" class="form-control select2">
                            <option value="">--Pilih Purpose Use--</option>
                            <?php
                            foreach ($dataProductCategory as $key => $v) {
                                echo "<option value='$v[id]'>$v[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Golongan</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="golongan" id="golongan" required="" class="form-control select2">
                            <option value="">--Pilih Golongan--</option>
                            <?php
                            foreach ($dataGolongan as $key => $v) {
                                echo "<option value='$v[id]'>$v[description]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status Jual</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="status_jual" id="status_jual" required="" class="form-control select2">
                            <option value="">--Pilih Status Jual--</option>
                            <option value="0">Non Saleable</option>
                            <option value="1">Saleable</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Status Tagihan</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input id="status_tagihan" type="checkbox" class="js-switch" name="status_tagihan" value='1' /> Aktif
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Tanah (m2)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" required name="luas_tanah" value="" placeholder="Masukkan Luas Tanah">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Bangunan (m2)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input id="luas_bangunan" type="text" class="form-control" required name="luas_bangunan" value="" placeholder="Masukkan Luas Bangunan">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Taman (m2)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" required name="luas_taman" value="" placeholder="Masukkan Luas Taman">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Virtual Account</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" required name="virtual_account" value="0" required="" placeholder="Masukkan Nomor Virtual Account">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">PT</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="pt" id="pt" required="" class="form-control select2">
                            <option value="">--Pilih PT--</option>
                            <?php
                            foreach ($dataPT as $key => $v) {
                                echo "<option value='$v[id]'>$v[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Metode Penagihan</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="metode_tagihan[]" class="form-control multipleSelect js-example-basic-multiple" multiple="multiple" placeholder="-- Masukkan Metode Penagihan --">
                            <option value=""></option>
                            <?php
                            foreach ($dataMetodePenagihan as $key => $v) {
                                echo "<option value='$v[id]'>$v[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kirim Tagihan</label>
                    <div class="col-lg-4 col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input id="kirim_tagihan" type="checkbox" class="js-switch" name="kirim_tagihan[]" value='1' /> Pemilik
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input id="kirim_tagihan" type="checkbox" class="js-switch" name="kirim_tagihan[]" value='2' /> Penghuni
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal ST</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date '>
                            <input type="text" class="form-control datetimepicker" name="tgl_st" id="tgl_st" placeholder="Masukkan Tanggal Aktif"> <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon</label>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input id="flag_diskon" type="checkbox" class="js-switch" name="flag_diskon" value='1' /> Aktif
                            </label>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="clearfix"></div>
            <div class="" id="range" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Informasi Air</a>
                    </li>
                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Informasi PL</a>
                    </li>
                    <!-- <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Informasi Listrik</a>
                    </li> -->

                </ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                        <p>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Meter Air Aktif</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <div class="">
                                            <label>
                                                <input id="meter_air_aktif" type="checkbox" class="js-switch" name="meter_air_aktif" value='1' /> Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="view_meter_air">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Aktif</label>
                                        <div class="col-md-4 col-sm-9 col-xs-12">
                                            <div class='input-group date '>
                                                <input type="text" class="form-control datetimepicker" name="tanggal_aktif_air" id="tanggal_aktif_air" placeholder="Masukkan Tanggal Aktif" disabled> <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-9 col-xs-12">
                                            <div class="input-group date mydatepicker" style="width: -webkit-fill-available;margin-top: -7px;font-size: xx-large;height: 40px;text-align: center">
                                                -
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-9 col-xs-12">
                                            <div class='input-group date mydatepicker'>
                                                <input type="text" class="form-control datetimepicker" id="tanggal_putus_air" name="tanggal_putus_air" placeholder="Masukkan Tanggal Putus"><span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Golongan</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12" id="isi_air">

                                            <select name="sub_golongan_air" class="form-control select2" id="sub_golongan_air">
                                                <option value="">--Pilih Sub Golongan-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pemeliharaan Meter Air</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select name="pemeliharaan_meter_air" class="form-control select2">
                                                <option value="">--Pilih Pemeliharaan Meter Air--</option>
                                                <?php
                                                foreach ($dataPemeliharaanMeterAir as $key => $v) {
                                                    echo "<option value='$v[id]'>$v[code]</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nilai Penyambungan</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="number" class="form-control" name="nilai_penyambungan" value="" placeholder="Masukkan Nilai Penyambungan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="view_meter_air2">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Angka Meter Sekarang</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="number" class="form-control" name="angka_meter_air" value="" placeholder="Masukkan Angka Meter Sekarang">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">ID Barcode Meter</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="barcode_id" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">No Meter Air</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="nomor_meter_air" value="" placeholder="Masukkan Nomor Meter Air">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                    </div>
                    <!-- Area Untuk PL -->
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                        <p>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">PL Aktif</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <div class="">
                                            <label>
                                                <input id="meter_pl_aktif" type="checkbox" class="js-switch" name="meter_pl_aktif" value='1' /> Aktif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="view_meter_pl">
                                    <input type="hidden" name="check_pl" value="" id="check_pl">
                                    <div id="view_pl">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Aktif</label>
                                            <div class="col-md-4 col-sm-9 col-xs-12">
                                                <div class='input-group date '>
                                                    <input type="text" class="form-control datetimepicker" name="pl_tanggal_aktif" id="tanggal_aktif_pl" placeholder="Masukkan Tanggal Aktif" disabled><span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-9 col-xs-12">
                                                <div class="input-group date mydatepicker" style="width: -webkit-fill-available;margin-top: -7px;font-size: xx-large;height: 40px;text-align: center">
                                                    -
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-9 col-xs-12">
                                                <div class='input-group date mydatepicker'>
                                                    <input type="text" class="form-control datetimepicker" name="tanggal_non_aktif_pl" value="" id='tanggal_putus_pl' placeholder="Masukkan Tanggal Non Aktif"><span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Golongan</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12" id="isi_pl">
                                                <select name="sub_golongan_lingkungan" class="form-control select2" id="sub_golongan_lingkungan">
                                                    <option value="">--Pilih Sub Golongan-</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Mandiri</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class='input-group date mydatepicker'>
                                                <input type="text" class="form-control datetimepicker" id='tanggal_mandiri_pl' name="tanggal_mandiri_pl" placeholder="Masukkan Tanggal Mandiri">
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Mulai Denda</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class='input-group date mydatepicker'>
                                                <input type="text" class="form-control datetimepicker" id='tanggal_mandiri_pl' name="tanggal_mandiri_pl" placeholder="Masukkan Tanggal Mandiri">
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </p>
                    </div>
                    <!-- <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                        <p>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Listrik Aktif</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class="">
                                                <label>
                                                    <input id="meter_listrik_aktif" type="checkbox" class="js-switch" name="meter_listrik_aktif" value='1' />
                                                    Aktif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="view_listrik">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Aktif</label>
                                            <div class="col-md-4 col-sm-9 col-xs-12">
                                                <div class='input-group date mydatepicker'>
                                                    <input id="tanggal_aktif_listrik" type="text" class="form-control datetimepicker" name="tanggal_aktif_listrik" placeholder="Masukkan Tanggal Aktif" disabled>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-9 col-xs-12">
                                                <div class="input-group date mydatepicker" style="width: -webkit-fill-available;margin-top: -7px;font-size: xx-large;height: 40px;text-align: center">
                                                    -
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-9 col-xs-12">
                                                <div class='input-group date mydatepicker'>
                                                    <input type="text" class="form-control datetimepicker" name="tanggal_putus_listrik" id="tanggal_putus_listrik" placeholder="Masukkan Tanggal Putus"><span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Golongan</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12" id="isi_listrik">
                                                <select name="sub_golongan_listrik" class="form-control select2" id="sub_golongan_listrik">
                                                    <option value="">--Pilih Sub Golongan-</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sewa Meter Listrik</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <select name="sewa_meter_listrik" class="form-control">

                                                    <option value="">--Pilih Sewa Meter Listrik--</option>
                                                    <?php
                                                    // foreach ($dataPemeliharaanMeterListrik as $key => $v) {
                                                    //     echo "<option value='$v[id]'>$v[code]</option>";
                                                    // }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Seri Meter</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="nomor_seri_listrik" value="" placeholder="Masukkan Nomor Meter Air">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Angka Meter Sekarang</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="number" class="form-control" name="angka_meter_listrik" value="" placeholder="Masukkan Angka Meter Sekarang">
                                        </div>
                                    </div>
                                </div>
                        </p>
                    </div>

                </div> -->
            </div>
            <div class="col-md-12 col-xs-12">
                <div class="center-margin">
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>

    </form>
    <!-- jQuery -->
    <script src="<?= base_url(); ?>vendors/validator/validator.js"></script>

    <script type="text/javascript">
        function currency(input) {
            var input = input.toString().replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt(input, 10) : 0;
            return (input === 0) ? "" : input.toLocaleString("en-US");
        }


        $(function() {
            $('.select2').select2();
            $("#luas_bangunan").keyup(function() {
                console.log($("#luas_bangunan").val());
                if ($("#luas_bangunan").val() == null || $("#luas_bangunan").val() != 0) {
                    $("#penghuni").attr('disabled', false);
                } else {
                    $("#penghuni").attr('disabled', true);
                }
            });
            $('.datetimepicker').datetimepicker({
                viewMode: 'days',
                format: 'MM-DD-YYYY'
            });
            $("#meter_air_aktif").change(function() {
                if ($("#meter_air_aktif").is(':checked')) {
                    $("#tanggal_aktif_air").attr('disabled', false);
                    $("#tanggal_putus_air").attr('disabled', true);
                    $("#tanggal_putus_air").val('');
                } else {
                    $("#tanggal_aktif_air").attr('disabled', true);
                    $("#tanggal_putus_air").attr('disabled', false);
                    $("#tanggal_aktif_air").val('');
                }
            })
            $("#meter_pl_aktif").change(function() {
                if ($("#meter_pl_aktif").is(':checked')) {
                    $("#tanggal_aktif_pl").attr('disabled', false);
                    $("#tanggal_putus_pl").attr('disabled', true);
                    $("#tanggal_putus_pl").val('');
                } else {
                    $("#tanggal_aktif_pl").attr('disabled', true);
                    $("#tanggal_putus_pl").attr('disabled', false);
                    $("#tanggal_aktif_pl").val('');
                }
            })
            // $("#meter_listrik_aktif").change(function() {
            //     if ($("#meter_listrik_aktif").is(':checked')) {
            //         $("#tanggal_aktif_listrik").attr('disabled', false);
            //         $("#tanggal_putus_listrik").attr('disabled', true);
            //         $("#tanggal_putus_listrik").val('');
            //     } else {
            //         $("#tanggal_aktif_listrik").attr('disabled', true);
            //         $("#tanggal_putus_listrik").attr('disabled', false);
            //         $("#tanggal_aktif_listrik").val('');
            //     }
            // })



            $('.js-example-basic-multiple').select2({
                placeholder: '-- Masukkan Metode Penagihan --',
                tags: true,
                tokenSeparators: [',', ' ']
            });

            $("#kawasan_flag").change(function() {


                //	alert('tess');

                url = '<?= site_url(); ?>/P_master_unit/lihat_blok';
                var kawasan_flag = $("#kawasan_flag").val();
                //console.log(this.value);
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        kawasan_flag: kawasan_flag
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);

                        $("#blok")[0].innerHTML = "";

                        $("#blok").append("<option value='' >Pilih Blok</option>");
                        $.each(data, function(key, val) {
                            $("#blok").append("<option value='" + val.id + "'>" + val.name.toUpperCase() + "</option>");
                        });

                    }


                });
            });

            $("#golongan").change(function() {

                // url = '<?= site_url(); ?>/P_master_unit/lihat_blok';
                url = '<?= site_url(); ?>/P_master_unit/get_sub_golongan';

                var jenis_golongan_id = $("#golongan").val();
                //console.log(this.value);
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        jenis_golongan_id: jenis_golongan_id
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);

                        $("#sub_golongan_air")[0].innerHTML = "";
                        $("#sub_golongan_air").append("<option value='' >Pilih Sub Gol Air</option>");
                        $("#sub_golongan_lingkungan")[0].innerHTML = "";
                        $("#sub_golongan_lingkungan").append("<option value='' >Pilih Sub Gol Lingkungan</option>");
                        // $("#sub_golongan_listrik")[0].innerHTML = "";
                        // $("#sub_golongan_listrik").append("<option value='' >Pilih Sub Gol Listrik</option>");
                        $.each(data, function(key, val) {
                            if (val.range_flag == 2)
                                $("#sub_golongan_air").append("<option value='" + val.id + "'>" + val.name.toUpperCase() + "</option>");
                            else if (val.range_flag == 1)
                                $("#sub_golongan_lingkungan").append("<option value='" + val.id + "'>" + val.name.toUpperCase() +
                                    "</option>");
                            // else
                            //     $("#sub_golongan_listrik").append("<option value='" + val.id + "'>" + val.name.toUpperCase() +
                            //         "</option>");
                        });

                    }


                });
            });



        });
    </script>