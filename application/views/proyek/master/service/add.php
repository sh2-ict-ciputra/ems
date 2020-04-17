<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<script>    
    $(".x_panel").hide();
</script>
<link rel="stylesheet" href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<script src="<?= base_url() ?>vendors/switchery/dist/switchery.min.js"></script>
<div style="float:right">
    <h2>
        <button class="btn btn-warning" onclick="location.href='<?= site_url() ?>/P_master_service';">
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
    <form id="form" class="form-horizontal form-label-left" method="post" action="<?= site_url() ?>/P_master_service/save" autocomplete="off">
        <div class="x_content">
            <br>
            <div id="div_jenis_retribusi" class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">Jenis Service<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-12 col-xs-2" hidden>
                    <div class="checkbox">
                        <label>
                            <p style="display:contents">List </p>
                            <input type="checkbox" class="js-switch" value="1" checked />
                            <p style="display:contents"> New</p>
                        </label>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-9">
                    <select type="text" id="jenis_retribusi" required="required" class="select2 form-control col-md-7 col-xs-12" name="jenis_retribusi" style="width:100%" placeholder="--Pilih Retribusi--">
                        <option disabled selected>-- Pilih Service Jenis --</option>
                        <?php foreach ($dataJenisService as $v) : ?>x
                            <option value="<?= $v->id ?>" name_default="<?= $v->name_default ?>" code_default="<?= $v->code_default ?>"><?= $v->jenis_service ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="code">
                    Kode - Name
                    <span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12">
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <input type="text" id="nama_service" name="nama_service" required="required" class="form-control col-md-7 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">
                    PT - COA Service
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <select type="text" id="coa_mapping_id_service" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_mapping_id_service" style="width:100%" placeholder="--Pilih PT - COA Service--">
                        <option></option>
                        <?php foreach ($dataService as $v) : ?>
                            <option value='<?=$v->id?>'><?="$v->ptName $v->coaCode - $v->coaName"?></option>");
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">
                    PT - COA Denda/Penalti Service
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <select type="text" id="coa_mapping_id_service_denda" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_mapping_id_service_denda" style="width:100%" placeholder="--Pilih PT - COA Denda/Penalti Service--">
                        <option></option>
                        <?php foreach ($dataService as $v) : ?>
                            <option value='<?=$v->id?>'><?="$v->ptName $v->coaCode - $v->coaName"?></option>");
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12">PPN - Coa PPN</label>
                <div class="col-md-2 col-sm-12 col-xs-2">
                    <div class="checkbox">
                        <label id="label_ppn">
                            <p style="display:contents">Unuse</p>
                                <input id="ppn" type="checkbox" class="js-switch" name="ppn_flag" value="1" checked />
                            <p style="display:contents">Use</p>
                        </label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-9">
                    <select type="text" id="coa_mapping_id_ppn" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_mapping_id_ppn" style="width:100%" placeholder="-- COA PPN--">
                        <option></option>
                        <?php foreach ($dataService as $v) : ?>
                            <option value='<?=$v->id?>'><?="$v->ptName $v->coaCode - $v->coaName"?></option>");
                        <?php endforeach; ?>
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Jarak Periode Tagihan - Periode Penggunaan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <input type="text" id="jarak_periode_penggunaan" required="required" class="form-control col-md-7 col-xs-12 currency" maxlength="2" name="jarak_periode_penggunaan" placeholder="--Masukkan Jarak Periode Penggunaan--">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">
                    Parameter Tanggal <br>Jatuh Tempo Tagihan
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <select type="text" id="parameter_tanggal_jatuh_tempo" required="required" class="select2 form-control col-md-7 col-xs-12" name="parameter_tanggal_jatuh_tempo" style="width:100%" placeholder="--Pilih PT - COA Service--">
                        <option></option>
                        <?php for ($i = 1; $i <= 31; $i++) : ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="middle-name" class="control-label col-md-3 col-sm-12 col-xs-12">Keterangan</label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <textarea id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="description"></textarea>
                </div>
            </div>

            <br>

            <br>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Denda <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="checkbox">
                            <label id="label_ppn">
                                <p style="display:contents">Unuse</p>
                                <input id="denda_flag" type="checkbox" class="js-switch" name="denda_flag" value="1" checked />
                                <p style="display:contents">Use</p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-8" for="pilih pt">Selisih Bulan<br>(Bulan)<span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <input type="text" id="selisih_bulan_denda" required="required" class="form_denda form-control col-md-7 col-xs-12 currency" name="selisih_bulan_denda" placeholder="--Masukkan Selisih Bulan--">
                    </div>
                    <label class="control-label col-md-2 col-sm-3 col-xs-8" for="pilih pt">Tanggal<span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <select type="text" id="tanggal_denda" required="required" class="form_denda select2 form-control col-md-7 col-xs-12" name="tanggal_denda" style="width:100%" placeholder="--Pilih Tanggal Denda--">
                            <option></option>
                            <?php for ($i = 1; $i <= 31; $i++) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Jenis Denda <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <select type="text" id="denda_jenis" required="required" class="form_denda select2 form-control col-md-7 col-xs-12" name="denda_jenis" style="width:100%" placeholder="--Pilih Jenis Denda--">
                            <option></option>
                            <option value="1">Fixed</option>
                            <option value="2">Progresif</option>
                            <option value="3">Persen Nilai Piutang</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Minimum Denda <span class="required">*<br>(Rp)</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="denda_minimum" required="required" class="form_denda form-control col-md-7 col-xs-12 currency" name="denda_minimum" placeholder="--Masukkan Minimum Denda--">
                    </div>
                </div>
                <div class="form-group">
                    <label id="label_nilai_denda" class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Nilai Denda <span class="required">*<br>(Rp)</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="number" id="denda_nilai" required="required" class="form_denda form-control col-md-7 col-xs-12" name="denda_nilai" placeholder="--Masukkan Nilai Denda--">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Denda Tanggal Putus</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="form_denda js-switch" name="denda_tgl_putus" value="1" checked /> Flag
                            </label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Penalti <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="checkbox">
                            <label id="label_ppn">
                                <p style="display:contents">Unuse</p>
                                <input id="penalti_flag" type="checkbox" class="js-switch" name="penalti_flag" value="1" checked />
                                <p style="display:contents">Use</p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-8" for="pilih pt">Selisih Bulan<br>(Bulan)<span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <input type="text" id="selisih_bulan_penalti" required="required" class="form_penalti form-control col-md-7 col-xs-12 currency" name="selisih_bulan_penalti" placeholder="--Masukkan Selisih Bulan--">
                    </div>
                    <label class="control-label col-md-2 col-sm-3 col-xs-8" for="pilih pt">Tanggal<span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <select type="text" id="tanggal_penalti" required="required" class="form_penalti select2 form-control col-md-7 col-xs-12" name="tanggal_penalti" style="width:100%" placeholder="--Pilih Tanggal Denda--">
                            <option></option>
                            <?php for ($i = 1; $i <= 31; $i++) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Jenis Penalti <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <select type="text" id="penalti_jenis" required="required" class="form_penalti select2 form-control col-md-7 col-xs-12" name="penalti_jenis" style="width:100%" placeholder="--Pilih Jenis Penalti--">
                            <option></option>
                            <option value="1">Fixed</option>
                            <option value="3">Persen Nilai Piutang</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Minimum Penalti <span class="required">*<br>(Rp)</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="penalti_minimum" required="required" class="form_penalti form-control col-md-7 col-xs-12 currency" name="penalti_minimum" placeholder="--Masukkan Minimum Penalti--">
                    </div>
                </div>
                <div class="form-group">
                    <label id="label_nilai_penalti" class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Nilai Penalti <span class="required">*<br>(Rp)</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="number" id="penalti_nilai" required="required" class="form_penalti form-control col-md-7 col-xs-12" name="penalti_nilai" placeholder="--Masukkan Nilai Penalti--">
                    </div>
                </div>
                <div class="form-group" hidden>
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Penalti Tanggal Putus</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="form_penalti js-switch" name="penalti_tgl_putus" value="1" checked /> Flag
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="clearfix"></div>
            <div class="form-group">
                <div class="center-margin">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>


<!-- jQuery -->
<script type="text/javascript">
    for (i = 0; i < $('.select2').length; i++) {
        console.log($('.select2')[i].id);
        $('#' + $('.select2')[i].id).select2({
            placeholder: $('#' + $('.select2')[i].id).attr("placeholder")
        });
    }

    $(document).ready(function() {
        $("#denda_flag").change(function(){
            if($("#denda_flag").is(':checked')){
                $(".form_denda").attr('disabled',false);
            }else{
                $(".form_denda").attr('disabled','disabled');
            }
            console.log('denda_flag change');
        })
        $("#penalti_flag").change(function(){
            if($("#penalti_flag").is(':checked')){
                $(".form_penalti").attr('disabled',false);
            }else{
                $(".form_penalti").attr('disabled','disabled');
            }
            console.log('denda_flag change');
        })
        $("#ppn").change(function(){
            if($("#ppn").is(':checked')){
                $("#coa_mapping_id_ppn").attr('disabled',false);
            }else{
                $("#coa_mapping_id_ppn").attr('disabled','disabled');
            }
            console.log('denda_flag change');
        })
        $("#denda_jenis").change(function(){
            if($(this).val()==3)
                $("#label_nilai_denda").html("Nilai Denda <span class='required'>*</span><br>(%)");
            else
                $("#label_nilai_denda").html("Nilai Denda <span class='required'>*</span><br>(Rp)");
        });
        $("#penalti_jenis").change(function(){
            if($(this).val()==3)
                $("#label_nilai_penalti").html("Nilai Penalti <span class='required'>*</span><br>(%)");
            else
                $("#label_nilai_penalti").html("Nilai Penalti <span class='required'>*</span><br>(Rp)");
        });
        $("#jenis_retribusi").change(function() {
            $("#code").val($('#jenis_retribusi')[0].options[$('#jenis_retribusi')[0].selectedIndex].getAttribute('code_default'));
            $("#nama_service").val($('#jenis_retribusi')[0].options[$('#jenis_retribusi')[0].selectedIndex].getAttribute('name_default'));
        });
        
        $("#non_retribusi").change(function() {
            // $("#div_jenis_retribusi").attr('style','display:none');
            $("#code").prop("readonly", false);
            $("#nama_service").prop("readonly", false);
            $("#code").val("");
            $("#nama_service").val("");
            $("#jenis_retribusi").attr("disabled", "disabled");
            $("#jenis_retribusi").val("").trigger("change");
        });
        $("#retribusi").change(function() {
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").removeAttr("disabled");
            $("#code").val("");
            $("#code").prop("readonly", true);
            $("#nama_service").prop("readonly", true);
        });
        $("#TVI").change(function() {
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").attr("disabled", "disabled");
            $("#code").prop("readonly", true);
            $("#code").val("4. TV & Internet");
            $("#nama_service").val("TV & Internet");
            $("#nama_service").prop("readonly", true);
            $("#jenis_retribusi").val("").trigger("change");
        });
        $("#LO").change(function() {
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").attr("disabled", "disabled");
            $("#code").prop("readonly", true);
            $("#code").val("5. Liaison Officer");
            $("#nama_service").val("Liaison Officer");
            $("#nama_service").prop("readonly", true);
            $("#jenis_retribusi").val("").trigger("change");
        });
        $("#sewa_properti").change(function() {
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").attr("disabled", "disabled");
            $("#code").prop("readonly", true);
            $("#code").val("6. Sewa Properti");
            $("#nama_service").val("Sewa Properti");
            $("#nama_service").prop("readonly", true);
            $("#jenis_retribusi").val("").trigger("change");
        });
        $(".x_panel").show();

    });
</script>