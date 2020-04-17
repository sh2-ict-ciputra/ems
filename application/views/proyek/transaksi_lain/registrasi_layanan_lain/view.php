<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?= site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/add'">
            <i class="fa fa-plus"></i>
            Tambah
        </button>
        <button class="btn btn-warning" onClick="window.history.back()" disabled>
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/P_registrasi_layanan_lain'">
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
                            <th>Jenis Unit</th>
                            <th>Unit</th>
                            <th>No. Registrasi</th>
                            <th>Pemilik</th>
                            <th>Status Bayar</th>
                            <th hidden>Action</th>
                            <!-- <th hidden>Delete</th> -->
                        </tr>
                    </tfoot>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Unit</th>
                            <th>Unit</th>
                            <th>No. Registrasi</th>
                            <th>Pemilik</th>
                            <th>Status Bayar</th>
                            <th style="text-align:center;">Action</th>
                            <!-- <th>Delete</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($data as $key => $v) {
                            ++$i;
                            echo '<tr>';
                            echo "<td>$i</td>";
                            echo "<td>$v->jenis_unit</td>";
                            echo "<td>$v->no_unit</td>";
                            echo "<td>$v->nomor_registrasi</td>";
                            echo "<td>$v->name</td>";
                            echo "<td>$v->status_bayar</td>";
                            echo "
                                <td class='col-md-1'>
                                <a href='" . site_url() . "/transaksi_lain/P_registrasi_layanan_lain/edit?id=$v->registrasi_id&unit_id=$v->unit_id' class='btn btn-sm btn-primary col-md-12'>
                                    <i class='fa fa-search'></i>
                                </a>
                                </td>
                            ";
                            // echo "
                            // <td class='col-md-1'>
					 		//     <a href='#'  class='btn btn-md btn-danger col-md-12' data-toggle='modal' 
					 		//         onclick='confirm_modal(
                            //         $v->registrasi_id)'";
                            // echo " data-target='#myModal'> ";
                            // echo "<i class='fa fa-trash'></i>
                            //         </a>
                            //     </td> ";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>



<!-- (Normal Modal)-->
<div class="modal fade" id="modal_delete_m_n" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:100px;">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="text-align:center;">Apakah anda yakin untuk data ini ?<span class="grt"></span>
                </h4>
            </div>

            <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                <span id="preloader-delete"></span>
                </br>
                <a class="btn btn-danger" id="delete_link_m_n" href="">Delete</a>
                <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Cancel</button>

            </div>
        </div>
    </div>
</div>
<script>
    function confirm_modal(id) {
        jQuery('#modal_delete_m_n').modal('show', {
            backdrop: 'static',
            keyboard: false
        });
        document.getElementById('delete_link_m_n').setAttribute("href",
            "<?= site_url('Transaksi_lain/P_registrasi_layanan_lain/delete?id=" + id + "'); ?>");
        document.getElementById('delete_link_m_n').focus();
    }
</script>