<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- Switchery -->
<script src="<?= base_url() ?>vendors/switchery/dist/switchery.min.js"></script>

<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href = '<?= substr(current_url(), 0, strrpos(current_url(), "/")) ?>'">
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
    <form id="form" class="form-horizontal form-label-left" autocomplete="off">
        <div class="com-lg-6 col-md-6 col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Golongan Diskon</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control select2" name="gol_diskon" id="gol_diskon" required>
                        <option value="" disabled selected>Pilih Golongan Diskon</option>
                        <option value=0>All</option>
                        <?php foreach ($dataGolDiskon as $v):?>
                        <option value=<?=$v->id?>><?=$v->name?></option>    
                        <?php endforeach;?>
                        
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Purpose Use</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control select2" name="purpose_use" id="purpose_use" required>
                        <option value="" disabled selected>Pilih Purpose Use</option>
                        <option value=0>All</option>
                        <?php foreach ($dataPurposeUse as $v):?>
                        <option value=<?=$v->id?>><?=$v->name?></option>    
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Service</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control select2" name="service" id="service" required>
                        <option value="" disabled selected>Pilih Service</option>
                        <option value=0>All</option>
                        <?php foreach ($dataService as $v) : ?>
                            <option service_jenis_id="<?= $v->service_jenis_id ?>" value="<?= $v->id ?>"><?= $v->code ?> - <?= $v->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Service</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control select2" name="paket_service" id="paket_service" disabled required>
                        <option value="" disabled selected>Pilih Paket Service</option>
                        <option value=0>All</option>
                        <?php foreach ($dataJabatan as $v) : ?>
                            <option value="<?= $v->id ?>"><?= $v->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon Berlaku di</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control select2" name="flag_berlaku" id="flag_berlaku" required>
                        <option value="" disabled selected>Pilih Tipe Berlaku</option>
                        <option value="1">Pendaftaran</option>
                        <option value="2">Tagihan</option>

                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12">Periode</label>
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="checkbox">
                        <label id="label_periode">
                            <p style="display:contents">Unuse</p>
                                <input id="periode" type="checkbox" class="js-switch" name="periode" value="1" checked required>
                            <p style="display:contents">Use</p>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-12">Periode Promo Diskon</label>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class='date datetimepicker'>
                        <input id="periode_awal" type="text" class="periode form-control datetimepicker" name="periode_awal" placeholder="Periode Awal" required>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1" style="text-align: center;margin-top: 6px">
                    -
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class='date datetimepicker'>
                        <input id="periode_akhir" type="text" class="periode form-control datetimepicker" name="periode_akhir" placeholder="Periode Akhir" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-12">Periode Berlaku Diskon<br>(DD/MM/YYYY)</label>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class='date datetimepickerFull'>
                        <input id="lama_awal" type="text" class="periode form-control datetimepickerFull" name="masa_awal" placeholder="Awal" required>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1" style="text-align: center;margin-top: 6px">
                    -
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class='date datetimepickerFull'>
                        <input id="lama_akhir" type="text" class="periode form-control datetimepickerFull" name="masa_akhir" placeholder="Akhir" required>
                    </div>
                </div>
            </div>            
        </div>


        <div class="com-lg-6 col-md-6 col-xs-6">
            
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Minimal Bulan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input class="form-control" name="minimal_bulan" id="minimal_bulan" placeholder="Masukkan Minimal Bulan untuk Mendapatkan Diskon" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Parameter</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control select2" name="parameter" id="parameter" required>
                        <option value="" disabled selected>Pilih Parameter</option>
                        <option value='1'>Bulan</option>
                        <option value='2'>Rupiah</option>
                        <option value='3'>Persen (%)</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nilai Diskon</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input class="form-control" name="nilai" id="nilai" placeholder="Masukkan Nilai" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <textarea class="form-control" rows="4" id="description" name="description" placeholder='Masukkan Keterangan'></textarea>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="form-group">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <a id="submit" type="submit" class="btn btn-success">Submit</a>
            </div>
        </div>
    </form>


    <script>
        function notif(title, text, type) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                styling: 'bootstrap3'
            });
        }
        $(function() {
            $("#flag_berlaku").change(function(){
                if($("#flag_berlaku").val() == 1){
                    $("#minimal_bulan").attr("disabled",true);
                    $("#minimal_bulan").val("");
                    
                    if($("#periode").is(":checked")){
                        $("#periode_awal").attr("disabled",false);
                        $("#periode_akhir").attr("disabled",false);
                    }

                }else{
                    $("#minimal_bulan").attr("disabled",false);
                    $("#periode_awal").attr("disabled",true);
                    $("#periode_akhir").attr("disabled",true);
                    
                }


            })
            $("#periode").on("change",function(){
                if($("#periode").is(":checked")){
                    $(".periode").attr("disabled",false);
                    if($("#flag_berlaku").val() == 2){
                        $("#periode_awal").attr("disabled",true);
                        $("#periode_akhir").attr("disabled",true);
                    
                    }

                }else{
                    $(".periode").attr("disabled",true);
                    $(".periode").val("");
                }
            });
            $('.datetimepicker').datetimepicker({
                viewMode: 'years',
                format: 'MM/YYYY'
            });
            $('.datetimepickerFull').datetimepicker({
                viewMode: 'years',
                format: 'DD/MM/YYYY'
            });
            $('.select2').select2();

            $("#service").change(function() {
                if ($("#service option:selected").attr("service_jenis_id") == 6) {
                    $("#paket_service").attr("disabled", false);
                    $.ajax({
                        type: "GET",
                        data: {
                            id: $("#service").val()
                        },
                        url: "<?= site_url() ?>/P_master_diskon/ajax_paket_service",
                        dataType: "json",
                        success: function(data) {
                            $("#paket_service").html("");
                            $("#paket_service").append("<option value='' disabled selected>Pilih Paket Service</option>");

                            for (var i = 0; i < data.length; i++) {
                                $("#paket_service").append("<option value=" + data[i].id + ">" + data[i].code + " - " + data[i].name + "</option>");
                            }
                        }
                    });
                } else {
                    $("#paket_service").attr("disabled", true);
                    $("#paket_service").html("");
                    $("#paket_service").append("<option value='' disabled selected>Pilih Paket Service</option>");
                }
            });

            $("#submit").click(function() {
                var gol_diskon = $("#gol_diskon").val()||$("#gol_diskon").attr('disabled');
                var purpose_use = $("#purpose_use").val()||$("#purpose_use").attr('disabled');
                var service = $("#service").val()||$("#service").attr('disabled');
                var paket_service = $("#paket_service").val()||$("#paket_service").attr('disabled');
                var flag_berlaku = $("#flag_berlaku").val()||$("#flag_berlaku").attr('disabled');
                var periode_awal = $("#periode_awal").val()||$("#periode_awal").attr('disabled');
                var periode_akhir = $("#periode_akhir").val()||$("#periode_akhir").attr('disabled');
                var lama_awal = $("#lama_awal").val()||$("#lama_awal").attr('disabled');
                var lama_akhir = $("#lama_akhir").val()||$("#lama_akhir").attr('disabled');
                var minimal_bulan = $("#minimal_bulan").val()||$("#minimal_bulan").attr('disabled');
                var parameter = $("#parameter").val()||$("#parameter").attr('disabled');
                var nilai = $("#nilai").val()||$("#nilai").attr('disabled');
                // var description = $("#description").val()||$("#description").attr('disabled');

                if(gol_diskon && purpose_use && service && paket_service && flag_berlaku && periode_awal && periode_akhir && lama_awal && lama_akhir && minimal_bulan && parameter && nilai && description){
                    $.ajax({
                        type: "GET",
                        data: $("#form").serialize(),
                        url: "<?= site_url() ?>/P_master_diskon/ajax_save",
                        dataType: "json",
                        success: function(data) {
                            if (data.status)
                                notif('Sukses', data.message, 'success');
                            else
                                notif('Gagal', data.message, 'danger');
                        }
                    });
                }else{
                    notif('Gagal', "Ada Form belum di isi", 'danger');
                }
            });
        });
    </script>