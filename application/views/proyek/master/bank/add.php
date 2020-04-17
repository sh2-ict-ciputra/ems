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
    <form id="form-bank" class="form-horizontal form-label-left">
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Bank
				<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select id='nama' class='col-md-12 form-control select2' name="bank_jenis_id" required>
                    <option value="" selected="" disabled="">--Pilih Bank--</option>
                    <?php foreach ($dataBank as $v): ?>
                        <option value='<?=$v->id?>' nama='<?=$v->name?>' kode='<?=$v->code?>'><?=$v->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
		</div>
		<div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Name Bank
				<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input id='name' class='col-md-12 form-control' placeholder="Pilih Bank Terlebih dahulu" name="name" required readonly>
            </div>
		</div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Kode Bank
				<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input id='kode' class='col-md-12 form-control' placeholder="Pilih Bank Terlebih dahulu" name="code" required readonly>
            </div>
		</div>
		
		<div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Biaya Admin 
				<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input id='biaya_admin' class='col-md-12 form-control' name="biaya_admin" placeholder="Pilih Biaya Admin ( yang di kenakan oleh bank ke customer)" required>
            </div>
		</div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Deskripsi 
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id='deskripsi' name="description" class='col-md-12 form-control' placeholder="Masukkan Deskripsi jika di perlukan"></textarea>
            </div>
		</div>
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <button id="submit" class="btn btn-success" type="submit">Submit</button>
            </div>
        </div>
    </form>


    <script>
        function formatNumber(data) {
            data = data + '';
            data = data.replace(/,/g, "");

            data = parseInt(data) ? parseInt(data) : 0;
            data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            return data;

        }
        
        $(function() {
            function notif(title, text, type) {
                new PNotify({
                    title: title,
                    text: text,
                    type: type,
                    styling: 'bootstrap3'
                });
            }
            $("#biaya_admin").keyup(function(){
                $("#biaya_admin").val(formatNumber($("#biaya_admin").val()))
            });
            $("#nama").change(function(){
                $("#kode").val($('option:selected', this).attr('kode')); 
                $("#name").val($('option:selected', this).attr('nama')); 
            });
            
            $('#form-bank').on('submit', function (e) {
                console.log("test1");
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: "<?= site_url() ?>/P_master_bank/ajax_save",
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

        });
    </script>