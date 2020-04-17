<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>

<!-- modals -->
<!-- Large modal -->
<div id="modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Detail Log</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                        <tr>
                            <th>Point Detail</th>
                            <th>Before</th>
                            <th>After</th>
                        </tr>
                    </thead>
                    <tbody id="dataModal">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-primary" onClick="window.location.href='<?= site_url(); ?>/P_master_range_lingkungan/edit?id=<?= $dataRangeLingkungan[0]['id'] ?>'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br />
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/P_master_range_lingkungan/edit?id=<?= $this->input->get('id'); ?>">



        <div class="col-md-6 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-3 col-xs-12">Kode</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input type="text" class="form-control " required name="kode_harga" placeholder="Masukkan Kode Range" value="<?= $data_select->code; ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-3 col-xs-12">Nama</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input type="text" class="form-control disabled-form" required name="nama" placeholder="Masukkan Nama Range" value="<?= $data_select->name; ?>" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <textarea class="form-control disabled-form" rows="3" name="keterangan" placeholder='Masukkan Keterangan' disabled> <?= $data_select->description; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Deposit Jaminan Sewa ( Rp )</label>
                <div class="col-md-8">
                    <input type="text" id="deposit_sewa" class="form-control currency" required name="deposit_sewa" value="<?= $data_select->deposit_sewa?>" placeholder="IDR" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-3 col-xs-12">Status</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="">
                        <label>
                            <input id="active" type="checkbox" class="js-switch disabled-form" name="active" value='1' <?= $data_select->active == 1 ? 'checked' : ''; ?> disabled /> Aktif
                        </label>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-6 col-xs-12">
            <div id="view_formula_bangunan">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12">Formula Bangunan</label>
                    <div class="col-md-2 col-sm-3 col-xs-12">
                        <div class="">
                            <label>
                                <input id="flag_bangunan" type="checkbox" class="js-switch disabled-form" name="flag_bangunan" value="1" <?= $data_select->flag_bangunan == 1 ? 'checked' : ''; ?> disabled />
                                Fix
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="formula_bangunan" id="formula_bangunan" class="form-control select2 " placeholder="-- Masukkan Formula --" disabled>
                            <option value="0" <?= $data_select->formula_bangunan == '' ? 'selected' : '' ?>>Fix</option>
                            <option value="1" <?= $data_select->formula_bangunan == 1 ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= $data_select->formula_bangunan == 2 ? 'selected' : '' ?>>2</option>
                            <option value="3" <?= $data_select->formula_bangunan == 3 ? 'selected' : '' ?>>3</option>
                            <option value="4" <?= $data_select->formula_bangunan == 4 ? 'selected' : '' ?>>4</option>

                        </select>
                    </div>
                </div>

            </div>
            <div id="view_formula_kavling">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12">Formula Kavling</label>
                    <div class="col-md-2 col-sm-3 col-xs-12">
                        <div class="">
                            <label>
                                <input id="flag_kavling" type="checkbox" class="js-switch disabled-form " name="flag_kavling" value="1" <?= $data_select->flag_kavling == 1 ? 'checked' : ''; ?> disabled />
                                Fix
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="formula_kavling" id="formula_kavling" class="form-control select2 " placeholder="-- Masukkan Formula --" disabled>
                            <option value="0" <?= $data_select->formula_bangunan == 1 ? 'selected' : '' ?>>Fix</option>
                            <option value="1" <?= $data_select->formula_kavling == 1 ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= $data_select->formula_kavling == 2 ? 'selected' : '' ?>>2</option>
                            <option value="3" <?= $data_select->formula_kavling == 3 ? 'selected' : '' ?>>3</option>
                            <option value="4" <?= $data_select->formula_kavling == 4 ? 'selected' : '' ?>>4</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Biaya Bangunan Fix ( Rp )</label>
                <div class="col-md-8">
                    <input type="text" id="bangunan_fix" class="form-control numbering currency " required name="biaya_bangunan" placeholder="IDR" value="<?= $data_select->bangunan_fix; ?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Biaya Kavling Fix ( Rp )</label>
                <div class="col-md-8">
                    <input type="text" id="kavling_fix" class="form-control numbering currency " required name="biaya_kavling" placeholder="IDR" value="<?= $data_select->kavling_fix; ?>" readonly>
                </div>
            </div>
        </div>


        <div id="detail" class="">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Detail Range Harga Sewa</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="" id="range" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li id="komponen-luas-bangunan" role="presentation" class="active">
                                    <a href="#tab_content1" id="komponen-bangunan" role="tab" data-toggle="tab" aria-expanded="true">Komponen
                                        Luas Bangunan</a>
                                </li>
                                <li id="komponen-luas-kavling" role="presentation" class="">
                                    <a href="#tab_content2" role="tab" id="komponen-kavling" data-toggle="tab" aria-expanded="false">Komponen
                                        Luas Kavling</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <p>
                                        <table class="table table-responsive">
                                            <thead>
                                                <th>Range</th>
                                                <th>No Log</th>
                                                <th>Range Awal</th>
                                                <th>Range Akhir</th>
                                                <th>Harga HPP</th>
                                                <th>Harga Range</th>
                                                <th>Hapus</th>
                                            </thead>
                                            <tbody id="tbody_range_bangunan">

                                                <?php
                                                $i = 0;
                                                $j = 0;
                                                //var_dump($dataRangeAirDetail);
                                                foreach ($dataRangeDetailBangunan as $v) {
                                                    ++$j;
                                                    if ($v['delete'] == 0) {
                                                        ++$i;
                                                        echo "<tr id='srow" . $i . "'>";
                                                        echo "<td hidden><input name='id_range_bangunan[]' value='$v[id]'> </td>";
                                                        echo "<td class='no' >" . $i . '</td>';
                                                        echo "<td class='nolog' >" . $j . '</td>';
                                                        echo "<td><input type='text' class='form-control disabled-form currency' name='range_awal[]' placeholder='Masukkan Range Awal'/ required value ='$v[range_awal]' disabled></td>";
                                                        echo "<td><input type='text' class='form-control disabled-form currency' name='range_akhir[]' placeholder='Masukkan Range Akhir'/ required value ='$v[range_akhir]' disabled></td>";
                                                        echo "<td><input type='text' class='form-control disabled-form currency' name='harga_hpp[]' placeholder='Masukkan Harga HPP'/ required value ='$v[harga_hpp]' disabled></td>";
                                                        echo "<td><input type='text' class='form-control disabled-form currency' name='harga_range[]' placeholder='Masukkan Harga Range'/ required value ='$v[harga]' disabled></td>";
                                                        echo "<td> <a class='btn btn-danger disabled-form' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" . $i . "\"); return false;' disabled><i class='fa fa-trash'></i> </a></td>";
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>


                                                <input id="idf" value="1" type="hidden" />

                                            </tbody>



                                        </table>
                                    </p>
                                    <button type="button" id="button_range_bangunan" class="btn btn-primary pull-right  disabled-form" disabled><i class="fa fa-plus"></i> Range Bangunan </button>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                    <p>
                                        <table class="table table-responsive">
                                            <thead>
                                                <th>Range</th>
                                                <th> No Log</th>
                                                <th>Range Awal</th>
                                                <th>Range Akhir</th>
                                                <th>Harga HPP</th>
                                                <th>Harga Range</th>
                                                <th>Hapus</th>
                                            </thead>
                                            <tbody id="tbody_range_kavling">

                                                <?php
                                                $i = 0;
                                                $j = 0;

                                                foreach ($dataRangeDetailKavling as $v) {
                                                    ++$j;
                                                    if ($v['delete'] == 0) {
                                                        ++$i;
                                                        echo "<tr id='srow" . $i . "'>";
                                                        echo "<td hidden><input name='id_range_kavling[]' value='$v[id]'> </td>";
                                                        echo "<td class='no' >" . $i . '</td>';
                                                        echo "<td class='nolog' >" . $j . '</td>';
                                                        echo "<td><input type='text' class='form-control disabled-form currency' name='range_awal2[]' placeholder='Masukkan Range Awal'/ required value ='$v[range_awal]' disabled></td>";
                                                        echo "<td><input type='text' class='form-control disabled-form currency' name='range_akhir2[]' placeholder='Masukkan Range Akhir'/ required value ='$v[range_akhir]' disabled></td>";
                                                        echo "<td><input type='text' class='form-control disabled-form currency' name='harga_hpp2[]' placeholder='Masukkan Harga HPP'/ required value ='$v[harga_hpp]' disabled></td>";
                                                        echo "<td><input type='text' class='form-control disabled-form currency' name='harga_range2[]' placeholder='Masukkan Harga Range'/ required value ='$v[harga]' disabled></td>";
                                                        echo "<td> <a class='btn btn-danger disabled-form' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" . $i . "\"); return false;' disabled><i class='fa fa-trash'></i> </a></td>";
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>





                                                <input id="idf2" value="1" type="hidden" />

                                            </tbody>

                                        </table>
                                        <button type="button" id="button_range_kavling" class="btn btn-primary pull-right  disabled-form" disabled><i class="fa fa-plus"></i> Range Kavling</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
                        <input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
                    </div>
    </form>
</div>
</div>
</div>
</div>
</form>



</div>


<div class="x_panel">
    <div class="x_title">
        <h2>Log</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <br>
        <table class="table table-striped jambo_table bulk_action tableDT">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($data as $key => $v) {
                    ++$i;
                    echo '<tr>';
                    echo "<td>$i</td>";
                    echo "<td>$v[date]</td>";
                    echo "<td>$v[name]</td>";
                    echo '<td>';
                    if ($v['status'] == 1) {
                        echo 'Tambah';
                    } elseif ($v['status'] == 2) {
                        echo 'Edit';
                    } else {
                        echo 'Hapus';
                    }
                    echo '</td>';
                    echo "
                    <td class='col-md-1'>
                        <a class='btn-modal btn btn-sm btn-primary col-md-12' data-toggle='modal' data-target='#modal' data-transfer='$v[id]' data-type='$v[status]'>
                            <i class='fa fa-pencil'></i>
                        </a>
                    </td>
                ";
                    echo '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>








<!-- jQuery -->
<script type="text/javascript">
    $(function() {


        $("#flag_bangunan").change(function() {
            if ($("#flag_bangunan").is(':checked')) {
                $("#formula_bangunan").val('0');
                $("#formula_bangunan").attr('disabled', true);
                $("#formula_bangunan").val('0');

                $("#bangunan_fix").attr('readonly', false);
                //$("#bangunan_fix").val('0');

                $("#komponen-bangunan").tab().hide();

                if (!$("#flag_kavling").is(':checked')) {
                    $("#detail").show();
                    $("#komponen-kavling").tab("show");
                } else {
                    $("#detail").hide();
                }
            } else {
                $("#detail").show();
                $("#formula_bangunan").attr('disabled', false);
                $("#formula_bangunan").val('1');

                $("#bangunan_fix").attr('readonly', true);
                $("#bangunan_fix").val('0');

                $("#komponen-luas-bangunan").css('display', '');
                $("#tab_content1").css('display', '');
                $("#komponen-bangunan").tab().show();

            }
        });
        $("#flag_kavling").change(function() {
            if ($("#flag_kavling").is(':checked')) {
                $("#formula_kavling").val('0');
                $("#formula_kavling").attr('disabled', true);
                $("#formula_kavling").val('0');

                $("#kavling_fix").attr('readonly', false);
                //  $("#kavling_fix").val('0');

                $("#komponen-kavling").tab().hide();
                if (!$("#flag_bangunan").is(':checked')) {
                    $("#detail").show();
                    $("#komponen-bangunan").tab("show")
                } else {
                    $("#detail").hide();
                }
            } else {
                $("#detail").show();

                $("#formula_kavling").attr('disabled', false);
                $("#formula_kavling").val('1');

                $("#kavling_fix").attr('readonly', true);
                $("#kavling_fix").val('0');

                $("#komponen-luas-kavling").css('display', '');
                $("#tab_content2").css('display', '');
                $("#komponen-kavling").tab().show();

            }
        });


    });
</script>








!-- jQuery -->
<script type="text/javascript">
    $(function() {
        $('#button_range_bangunan').click(function() {
            var row = "<tr>" +
                "</tr>";
        });
    });

    $("#button_range_bangunan").click(function() {
        if ($(".no").html()) {
            idf = parseInt($(".no").last().html()) + 1;
        } else {
            idf = 1;
        }
        var str = "<tr id='srow" + idf + "'>" +
            "<td hidden><input name='id_range_bangunan[]' value='0'></td>" +
            "<td class='no'>" + idf + "</td>" +
            "<td class='nolog' ></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control currency'  name='range_awal[]' placeholder='' /></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' name='range_akhir[]'  class='form-control currency'/></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control currency'  name='harga_hpp[]'  /></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control currency'  name='harga_range[]'  /></td>" +
            "<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>" +
            "</tr>";
        $("#tbody_range_bangunan").append(str);
    });






    function hapusElemen(idf) {
        $(idf).remove();
        var idf = document.getElementById("idf").value;
        idf = idf - 1;
        document.getElementById("idf").value = idf;
    }
</script>



<!-- jQuery -->
<script type="text/javascript">
    $(function() {
        $('#button_range_kavling').click(function() {
            var row = "<tr>" +
                "</tr>";
        });
    });

    $("#button_range_kavling").click(function() {
        if ($(".no2").html()) {
            idf2 = parseInt($(".no2").last().html()) + 1;
        } else {
            idf2 = 1;
        }
        var str = "<tr id='srow" + idf2 + "'>" +
            "<td hidden><input name='id_range_kavling[]' value='0'></td>" +
            "<td class='no'>" + idf2 + "</td>" +
            "<td class='nolog' ></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control'  name='range_awal2[]' placeholder='' /></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' name='range_akhir2[]'  class='form-control'/></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control'  name='harga_hpp2[]'  /></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control'  name='harga_range2[]'  /></td>" +
            "<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf2 + "\"); return false;'><i class='fa fa-trash'></i> </a></td>" +
            "</tr>";
        $("#tbody_range_kavling").append(str);
    });






    function hapusElemen2(idf2) {
        $(idf2).remove();
        var idf2 = document.getElementById("idf2").value;
        idf2 = idf2 - 1;
        document.getElementById("idf2").value = idf2;
    }
</script>

<script type="text/javascript">
    $.each($(".currency"), function(index, currency) {
        currency.value = parseInt(currency.value.toString().replace(/[\D\s\._\-]+/g, ""), 10).toLocaleString("en-US");
    });

    $("#btn-update").click(function() {
        disableForm = 0;
        $(".disabled-form").removeAttr("disabled");
        $("#btn-cancel").removeAttr("style");

        if ($("#btn-update").val() == "Edit") {
            $("#btn-update").val("Update");
            setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
            $("#deposit_sewa").attr('readonly', false);
            if ($("#flag_bangunan").is(':checked')) {
                $("#formula_bangunan").val('0');
                $("#formula_bangunan").attr('disabled', true);
                $("#formula_bangunan").val('0');

                $("#bangunan_fix").attr('readonly', false);


                $("#komponen-bangunan").tab().hide();

                if (!$("#flag_kavling").is(':checked')) {
                    $("#detail").show();
                    $("#komponen-kavling").tab("show");
                } else {
                    $("#detail").hide();
                }
            } else {
                $("#detail").show();
                $("#formula_bangunan").attr('disabled', false);
                $("#formula_bangunan").val('1');

                $("#bangunan_fix").attr('readonly', true);
                $("#bangunan_fix").val('0');

                $("#komponen-luas-bangunan").css('display', '');
                $("#tab_content1").css('display', '');
                $("#komponen-bangunan").tab().show();

            }

            if ($("#flag_kavling").is(':checked')) {
                $("#formula_kavling").val('0');
                $("#formula_kavling").attr('disabled', true);
                $("#formula_kavling").val('0');

                $("#kavling_fix").attr('readonly', false);


                $("#komponen-kavling").tab().hide();
                if (!$("#flag_bangunan").is(':checked')) {
                    $("#detail").show();
                    $("#komponen-bangunan").tab("show")
                } else {
                    $("#detail").hide();
                }
            } else {
                $("#detail").show();

                $("#formula_kavling").attr('disabled', false);
                $("#formula_kavling").val('1');

                $("#kavling_fix").attr('readonly', true);
                $("#kavling_fix").val('0');

                $("#komponen-luas-kavling").css('display', '');
                $("#tab_content2").css('display', '');
                $("#komponen-kavling").tab().show();

            }
        }
    });
    $("#btn-cancel").click(function() {
        disableForm = 1;
        $(".disabled-form").attr("disabled", "")
        $("#btn-cancel").attr("style", "display:none");
        $("#btn-update").val("Edit")
        $("#btn-update").removeAttr("type");
    });


    $(".btn-modal").click(function() {
        url = '<?= site_url(); ?>/core/get_log_detail';
        console.log($(this).attr('data-transfer'));
        console.log($(this).attr('data-type'));
        $.ajax({
            type: "POST",
            data: {
                id: $(this).attr('data-transfer'),
                type: $(this).attr('data-type')
            },
            url: url,
            dataType: "json",
            success: function(data) {
                console.log(data);
                // var items = []; 
                // $("#changeJP").attr("style","display:none");
                // $("#saveJP").removeAttr('style');
                // $("#jabatan").removeAttr('disabled');
                // $("#jabatan")[0].innerHTML = "";
                // $("#project")[0].innerHTML = "";
                // $("#jabatan").append("<option value='' selected disabled>Pilih Jabatan</option>");
                console.log($(this).attr('data-type'));
                $("#dataModal").html("");
                if (data[data.length - 1] == 2) {
                    console.log(data[0]);
                    for (i = 0; i < data[0].length; i++) {
                        var tmpj = 0;
                        for (j = 0; j < data[0].length; j++) {
                            if (data[1][j] != null) {
                                if (data[1][j].name == data[0][i].name) {
                                    $("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + data[1][j].value + "</td><td>" + data[0]
                                        [i].value + "</td></tr>");
                                    tmpj++;
                                }

                            }
                        }
                        if (tmpj == 0) {
                            $("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td></td><td>" + data[0]
                                [i].value + "</td></tr>");
                        }
                    }

                    // 	if(data[1].length > data[0].length){
                    // 		$.each(data[1], function (key, val) {
                    // 			if (val.name == data[0][i].name) {
                    // 				console.log(val.name);
                    // 				$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0]
                    // 					[i].value + "</td></tr>");
                    // 			}
                    // 		});
                    // 	}else{
                    // 		$.each(data[0], function (key, val) {
                    // 			if (val.name == data[1][i].name) {
                    // 				console.log(val.name);
                    // 				$("#dataModal").append("<tr><th>" + data[1][i].name + "</th><td>" + val.value + "</td><td>" + data[1]
                    // 					[i].value + "</td></tr>");
                    // 			}
                    // 		});
                    // 	}
                    // }
                } else {
                    $.each(data, function(key, val) {
                        if (data[data.length - 1] == 1) {
                            console.log(data);
                            if (val.name)
                                $("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td></td><td>" + val.value +
                                    "</td></tr>");
                        } else if (data[data.length - 1] == 2) {

                        } else if (data[data.length - 1] == 3) {
                            console.log(data);
                            if (val.name)
                                $("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td>" + val.value +
                                    "</td><td></td></tr>");
                        }
                    });
                }

            }
        });

    });
    $('.select2').select2({
        width: 'resolve'
    });

    $(document).keydown(function(e) {
        return (e.which || e.keyCode) != 116;
    });

    $(document).keydown(function(e) {
        if (e.ctrlKey) {
            return (e.which || e.keyCode) != 82;
        }
    });
</script>