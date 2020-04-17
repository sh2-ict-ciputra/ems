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
            <label class="control-label col-md-3 col-sm-3 col-xs-12">PT <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input class="form-control col-md-7" value="<?=$data->name?>" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="apikey" class="control-label col-md-3 col-sm-3 col-xs-12">Api Key</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="apikey" class="form-control col-md-7 col-xs-12" type="text" name="apikey"><?=$data->apikey?></textarea>
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
                url: "<?= site_url() ?>/P_master_pt/ajax_save",
                data: {
                    pt_id : <?=$this->input->get('id')?>,
                    apikey : $("#apikey").val()
                },
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