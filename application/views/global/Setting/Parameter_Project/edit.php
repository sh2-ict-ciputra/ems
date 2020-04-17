<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<!DOCTYPE html>

<style>
    .range_akhir,
    .range_awal {
        text-align: right;
    }

    .range_akhir {
        color: transparent;
        text-shadow: 0 0 0 black;

        &:focus {
            outline: none;
        }
    }
</style>

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
    <form id="form" class="form-horizontal form-label-left">

        <div class="com-lg-12 col-md-12 col-xs-12">
            <div class="form-group">

                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" value="<?= $data->name ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" value="<?= $data->code ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Value</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea type="text" class="form-control" name="value" required><?= $data->value?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea name="description" class="form-control" rows="4"><?= $data->description ?></textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <div class="center-margin">
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <a id="submit" type="submit" class="btn btn-success">Submit</a>
                </div>
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

            $("#submit").click(function() {
                $.ajax({
                    type: "POST",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/Setting/Parameter_project/ajax_save?id=<?= $this->input->get("id") ?>",
                    dataType: "json",
                    success: function(data) {
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
            });
        });
    </script>