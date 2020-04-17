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
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_pipa/add'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br>
    <form id="form-cara-bayar" autocomplete="off" class="form-horizontal form-label-left">
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="code" name="code" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Nama" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Nama">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ukuran_pipa">Ukuran Pipa<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="ukuran_pipa" name="ukuran_pipa" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Ukuran Pipa Beserta Satuan">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nilai">Harga<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                <input type="text" id="nilai" name="nilai" required class="text-right form-control col-md-7 col-xs-12 currency" style="padding-left: 50px;">
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="description" class="form-control col-md-7 col-xs-12" type="text" name="description" placeholder="Masukkan Keterangan jika diperlukan"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="center-margin">
                <button type="submit" class="btn-submit btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>


<!-- jQuery -->
<script type="text/javascript">
    function formatNumber(data) {
        data = data + '';
        data = data.replace(/,/g, "");

        data = parseInt(data) ? parseInt(data) : 0;
        data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        return data;

    }
    function unformatNumber(data) {
        data = data + '';
        return data.replace(/,/g, "");
    }
    function notif(title, text, type) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            styling: 'bootstrap3'
        });
    }

    
    $(function() {
        $("#name").keyup(function() {
            $("#code").val($("#name").val().toLowerCase().replace(/ /g, '_'));
        });
        $(".currency").keyup(function() {
            $(this).val(formatNumber($(this).val()));
        });
        $("form").submit(function(e) {
            $('.currency').val(unformatNumber($(".currency").val()));
            e.preventDefault();
            $.ajax({
                type: "POST",
                data: $("form").serialize(),
                url: "<?= site_url('P_master_pemeliharaan_air/ajax_save') ?>",
                dataType: "json",
                success: function(data) {
                    if (data.status == 1) {
                        notif('Sukses', data.message, 'success')
                        setTimeout(function() {
                            window.location.href = '<?=site_url('P_master_pemeliharaan_air')?>'
                        }, 2 * 1000);
                    } else
                        notif('Gagal', data.message, 'danger')
                }
            });
            $('.currency').val(formatNumber($(".currency").val()));
        })
    });
</script>