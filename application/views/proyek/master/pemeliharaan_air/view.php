<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?= site_url(); ?>/P_master_pemeliharaan_air/add'">
            <i class="fa fa-plus"></i>
            Tambah
        </button>
        <button class="btn btn-warning" onClick="window.history.back()" disabled>
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_pemeliharaan_air'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table class="table table-striped jambo_table bulk_action tableDT">
                    <tfoot id="tfoot" style="display: table-header-group">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Ukuran Pipa</th>
                            <th class="text-right">Harga</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th hidden>Action</th>
                            <th hidden>Delete</th>
                        </tr>
                    </tfoot>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Ukuran Pipa</th>
                            <th class="text-right">Harga</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $key => $v) : ?>
                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= $v->code ?></td>
                                <td><?= $v->name ?></td>
                                <td><?= $v->ukuran_pipa ?></td>
                                <td class="text-right"><?= number_format($v->nilai) ?></td>
                                <td><?= $v->description ?></td>
                                <td><?= $v->active == 1 ? 'Aktif' : 'Tidak Aktif' ?></td>
                                </td>
                                <td class='col-md-1'>
                                    <a href='<?=site_url("/P_master_pemeliharaan_air/edit?id=$v->id")?>' class='btn btn-sm btn-primary col-md-12'>
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                </td>
                                <td class='col-md-1'>
                                    <a href='#' class='btn-delete btn btn-md btn-danger col-md-12' data-toggle='modal' data-target='#modal_delete' item_id='<?=$v->id?>'> <i class='fa fa-trash'></i>
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>



<!-- (Normal Modal)-->
<div class="modal fade" id="modal_delete" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:100px;">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="text-align:center;">Apakah anda yakin untuk mendelete data ini ?</h4>
            </div>

            <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                <span id="preloader-delete"></span>
                </br>
                <button class="btn btn-danger" id="btn-delete">Delete</button>
                <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Cancel</button>

            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        function notif(title, text, type) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                styling: 'bootstrap3'
            });
        }
        $(".btn-delete").click(function(){
            $("#btn-delete").attr('item_id',$(this).attr('item_id'));
        });
        $("#btn-delete").click(function(){
            $.ajax({
                type: "POST",
                data:{
                    id:$(this).attr('item_id')
                },
                url: "<?= site_url('P_master_pemeliharaan_air/ajax_delete') ?>",
                dataType: "json",
                success: function(data) {
                    if (data.status == 1) {
                        notif('Sukses', data.message, 'success')
                        setTimeout(function () { 
                            if(!alert('Halaman akan di refresh otomatis untuk update data!')){
                                window.location.reload();
                            }
                        }, 2 * 1000);
                    } else
                        notif('Gagal', data.message, 'danger')
                }
            });
        });

    });
</script>