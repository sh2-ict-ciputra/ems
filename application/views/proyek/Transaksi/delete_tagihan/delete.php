<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!-- select2 -->
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">
<!-- JQUERY MASK -->

<style>
    .invalid {
        background-color: lightpink;
    }

    .has-error {
        border: 1px solid rgb(185, 74, 72) !important;
    }

    .text-right {
        text-align: right;
    }
</style>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br>
    <table id="table-tagihan" class="table tableDT2 table-striped table-bordered bulk_action" style="width:100%">

        <thead>
            <tr>
                <th>Periode Penggunaan</th>
                <th>Periode Tagihan</th>
                <th>Service</th>
                <th>Total Tagihan (Rp.)</th>
                <th>Delete</th>
            </tr>
        </thead>
        <?php foreach ($tagihan as $v) : ?>
            <tr>
                <td><?= $v->periode_pemakaian ?></td>
                <td><?= $v->periode ?></td>
                <td><?= $v->service ?></td>
                <td><?= $v->total ?></td>
                <td>
                    <button type="button" class="btn btn-primary btn-modal-delete" data-toggle="modal" data-target="#modal-delete" tagihan_id=<?=$v->tagihan_id?> service_id=<?=$v->service_id?> periode=<?=$v->periode?>>
                        Delete
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        <tbody>

        </tbody>
    </table>
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-delete" action="<?=site_url("Transaksi/P_delete_tagihan/destroy/$unit_id")?>" method="post">
                        <input type="hidden" name="method" value="delete">
                        <input type="hidden" name="tagihan_id" id="tagihan_id">
                        <input type="hidden" name="service_id" id="service_id">
                        <input type="hidden" name="periode" id="periode">
                        <div class="form-group">
                            <label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Alasan Delete Tagihan</label>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <textarea name="description" class="col-md-12" required></textarea>
                            </div>
                        </div>
                        <button hidden id="btn-submit" type="submit">submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="btn-submit-2" type="button" class="btn btn-primary">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function notif(title, text, type) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            styling: 'bootstrap3'
        });
    }
    $(function(){
        <?php if($this->session->flashdata('success')):?>
            notif('Sukses', '<?=$this->session->flashdata('success')?>', 'success');
        <?php endif;?>
        $(".btn-modal-delete").click(function(){
            $("#tagihan_id").val($(this).attr('tagihan_id'));
            $("#service_id").val($(this).attr('service_id'));
            $("#periode").val($(this).attr('periode'));
        });
        $("#btn-submit-2").click(function(){
            $("#btn-submit").trigger('click');
        })
    })
</script>