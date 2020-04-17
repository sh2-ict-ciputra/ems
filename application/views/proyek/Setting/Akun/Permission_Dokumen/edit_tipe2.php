<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">


<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href = '<?=site_url('Setting/P_setting_approval')?>'">
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
        <div class="com-lg-4 col-md-4 col-xs-4">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Dokumen</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" value="<?= $data->name ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Dokumen</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" value="<?= $data->code ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <textarea class="form-control" rows="4" readonly><?= $data->description ?></textarea>
                </div>
            </div>
        </div>
        <div class="com-lg-8 col-md-8 col-xs-8s">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">User</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control select2" name="user_id">
                        <option></option>
                        <?php foreach ($data_user as $k => $v):?>
                        <?php if($v->id == $data_user_selected->user_id):?>
                            <option value="<?=$v->id?>" selected><?=$v->name?></option>
                        <?php else:?>
                            <option value="<?=$v->id?>"><?=$v->name?></option>

                        <?php endif;?>
                        <?php endforeach;?>
                    </select>
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
            $(".select2").select2({
                'placeholder' : '-- Pilih User --'
            });
            $("#submit").click(function() {
                $.ajax({
                    type: "GET",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/Setting/P_setting_approval/ajax_save_tipe2?id_dokumen=<?= $this->input->get("id") ?>",
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