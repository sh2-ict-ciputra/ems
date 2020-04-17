<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href = '<?= substr(current_url(), 0, strrpos(current_url(), "/")) ?>'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_cara_pembayaran/add'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br>
    <form id="form-cara-bayar" autocomplete="off" class="form-horizontal form-label-left" method="post">
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">PT <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="pt" required="required" class="select2 form-control col-md-7 col-xs-12" name="pt" style="width:100%" placeholder="--Pilih PT--" require>

                    <option value="" selected="" disabled="">--Pilih PT--</option>
                    <?php foreach ($pt as $v): ?>
                        <option value='<?=$v->pt_id?>'><?=$v->name?></option>");
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Jenis Cara Pembayaran <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="jenis_cara_pembayaran" required="required" class="select2 form-control col-md-7 col-xs-12" name="jenis_cara_pembayaran" style="width:100%" placeholder="--Pilih Jenis Cara Pembayaran--" require>

                    <option value="" selected="" disabled="">--Pilih Jenis Cara Pembayaran--</option>
                    <?php foreach ($dataJenisCaraPembayaran as $v):?>
                        <option value='<?=$v->id?>' bank='<?=$v->bank?>' nama='<?=$v->name?>' kode='<?=$v->code?>'><?=$v->name?> - <?=$v->code?></option>");
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank">Bank<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="bank" required="required" class="select2 form-control col-md-7 col-xs-12" name="bank" style="width:100%" placeholder="--Pilih Bank--" require disabled>

                    <option value="" selected="" disabled="">--Pilih Bank--</option>
                    <?php
                    foreach ($bank as $v) {

                        echo ("<option value='$v->id'>$v->name - $v->code</option>");
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode Cara Pembayaran <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Nama Cara Pembayaran <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="jenis_pembayaran" name="jenis_pembayaran" required="required" class="form-control col-md-7 col-xs-12" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin" id="label_biaya_admin">VA Merchant</label>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="va_bank" name="va_bank" class="form-control col-md-7 col-xs-12" placeholder="Masukkan Bank Merchant">
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="va_merchant" name="va_merchant" class="form-control col-md-7 col-xs-12" placeholder="Masukkan Sub Bank Merchant">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin" id="label_biaya_admin">Max Digit VA</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="max_digit" name="max_digit" class="form-control col-md-7 col-xs-12" placeholder="Masukkan Max Digit (Sub Bank Merchant + 8)">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_biaya_admin">Jenis Biaya Admin <span class="required"></span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="jenis_biaya_admin" required="required" class="select2 form-control col-md-7 col-xs-12" name="nilai_flag" style="width:100%" placeholder="--Pilih Jenis Biaya Admin--" require>
                    <option value="0" selected="">Rupiah</option>
                    <option value="1">Persen</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin" id="label_biaya_admin">Biaya Admin <span class="required">*<br>(Rp.)</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="biaya_admin" name="biaya_admin" required="required" class="form-control col-md-7 col-xs-12 currency">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">
                COA Biaya Admin
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="coa" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa" style="width:100%" placeholder="--Pilih PT - COA Service--">

                    <option value="" selected="" disabled="">--Pilih PT COA--</option>
                    <?php
                    foreach ($dataCaraPembayaran as $v) {
                        echo ("<option value='$v->id'>$v->ptName $v->coaCode - $v->coaName</option>");
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="keterangan"></textarea>
            </div>
        </div>


        <div class="form-group">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>


<!-- jQuery -->
<script type="text/javascript">
    $(function() {
        function notif(title, text, type) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                styling: 'bootstrap3'
            });
        }
        $('#form-cara-bayar').on('submit', function (e) {
            console.log("test1");
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: "<?= site_url() ?>/P_master_cara_pembayaran/save",
                data: $('form').serialize(),
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if( data == "success")
                        notif("Berhasil","Data Berhasil di Tambah","success")
                    else
                        notif("Gagal","Data Sudah Ada","danger")
                }
            });
        });

        $("#jenis_cara_pembayaran").change(function() {
            var option = $('option:selected', this).attr('bank');
            var nama = $('option:selected', this).attr('nama');
            var kode = $('option:selected', this).attr('kode');
            var val = $('option:selected', this).val();
            $("#code").val(kode);
            $("#jenis_pembayaran").val(nama);
            console.log(option);
            if (option == 1) {
                $("#bank").attr("disabled", false);
            } else {
                $("#bank").prop('selectedIndex', 0);
                $("#bank").select2();
                $("#bank").attr("disabled", true);
            }
            if(val != 3 && val != 1){
                $("#jenis_pembayaran").attr('readonly',false);
                $("#code").attr('readonly',false);
            }else{
                $("#jenis_pembayaran").attr('readonly',true);
                $("#code").attr('readonly',true);
            }

        });
        $("#jenis_biaya_admin").change(function() {
            if ($("#jenis_biaya_admin").val() == 0)
                $("#label_biaya_admin").html("Biaya Admin <span class='required'>*<br>(Rp.)");
            else
                $("#label_biaya_admin").html("Biaya Admin <span class='required'>*<br>(%)")
        });
        $(".select2").select2();
    });
</script>