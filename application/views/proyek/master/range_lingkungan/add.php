<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
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
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>


</div>
<div class="x_content">
    <br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url() ?>/P_master_range_lingkungan/save">

        <div class="col-md-6 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-3 col-xs-12">Kode</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input type="text" class="form-control" required name="kode_range" placeholder="Masukkan Kode Range">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-3 col-xs-12">Nama</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input type="text" class="form-control" required name="nama" placeholder="Masukkan Nama Range">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <textarea class="form-control" rows="3" name="keterangan" placeholder='Masukkan Keterangan'></textarea>
                </div>
            </div>
            <div id="view_formula_bangunan">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12">Formula Bangunan</label>
                    <div class="col-md-2 col-sm-3 col-xs-12">
                        <div class="">
                            <label>
                                <input id="flag_bangunan" type="checkbox" class="js-switch" name="flag_bangunan" value="1" />
                                Fix
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="formula_bangunan" id="formula_bangunan" class="form-control select2" placeholder="-- Masukkan Formula --">
                            <option value="0" disabled>Fix</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>

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
                                <input id="flag_kavling" type="checkbox" class="js-switch" name="flag_kavling" value="1" />
                                Fix
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="formula_kavling" id="formula_kavling" class="form-control select2" placeholder="-- Masukkan Formula --">
                            <option value="0" disabled>Fix</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>

                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-4">Keamanan ( Rp/Bln )</label>
                <div class="col-md-8">
                    <input type="text" value="0" class="form-control currency" required name="keamanan" placeholder="IDR">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Kebersihan ( Rp/Bln )</label>
                <div class="col-md-8">
                    <input type="text" value="0" class="form-control currency" required name="kebersihan" placeholder="IDR">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Biaya Bangunan Fix ( Rp )</label>
                <div class="col-md-8">
                    <input type="text" value="0" id="bangunan_fix" class="form-control currency" required name="biaya_bangunan" placeholder="IDR" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Biaya Kavling Fix ( Rp )</label>
                <div class="col-md-8">
                    <input type="text" value="0" id="kavling_fix" class="form-control currency" required name="biaya_kavling" placeholder="IDR" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Service Charge ( Rp )</label>
                <div class="col-md-8">
                    <input type="text" value="0" class="form-control currency" required name="service_charge" placeholder="IDR">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-3 col-xs-12">PPN Service Charge</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="checkbox">
                        <label id="label_active">
                            <input id="active" type="checkbox" class="js-switch" name="ppn" value="1"/>
                            Aktif
                        </label>
                    </div>
                </div>
            </div>
        </div>


        <div id="detail" class="">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Detail Range PL</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="" id="range" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li id="komponen-luas-bangunan" role="presentation" class="active">
                                    <a href="#tab_content1" id="komponen-bangunan" role="tab" data-toggle="tab" aria-expanded="true">Komponen Luas Bangunan</a>
                                </li>
                                <li id="komponen-luas-kavling" role="presentation" class="">
                                    <a href="#tab_content2" role="tab" id="komponen-kavling" data-toggle="tab" aria-expanded="false">Komponen Luas Kavling</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <p>
                                        <table class="table table-responsive">
                                            <thead>
                                                <th>Range</th>
                                                <th>Range Awal</th>
                                                <th>Range Akhir</th>
                                                <th>Harga HPP</th>
                                                <th>Harga Range</th>
                                                <th>Hapus</th>
                                            </thead>
                                            <tbody id="tbody_range_bangunan">
                                                <tr>
                                                    <td><input id="idf" value="1" type="hidden" /></td>
                                                </tr>
                                            </tbody>



                                        </table>
                                    </p>
                                    <button type="button" id="button_range_bangunan" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>
                                        Range Bangunan </button>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                    <p>
                                        <table class="table table-responsive">
                                            <thead>
                                                <th>Range</th>
                                                <th>Range Awal</th>
                                                <th>Range Akhir</th>
                                                <th>Harga HPP</th>
                                                <th>Harga Range</th>
                                                <th>Hapus</th>
                                            </thead>
                                            <tbody id="tbody_range_kavling">
                                                <tr>
                                                    <td><input id="idf2" value="1" type="hidden" /></td>
                                                </tr>
                                            </tbody>

                                        </table>
                                        <button type="button" id="button_range_kavling" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>
                                            Range Kavling</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>



</div>


<script type="text/javascript">
    $(function() {
        $("#flag_bangunan").change(function() {
            if ($("#flag_bangunan").is(':checked')) {
                $("#formula_bangunan").attr('disabled', true);
                $("#formula_bangunan").val('0');

                $("#bangunan_fix").attr('readonly', false);
                $("#bangunan_fix").val('0');

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
                $("#formula_kavling").attr('disabled', true);
                $("#formula_kavling").val('0');

                $("#kavling_fix").attr('readonly', false);
                $("#kavling_fix").val('0');

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

        $("#button_range_bangunan").click(function() {
            var row = "<tr> </tr>";
        });

        $("#button_range_kavling").click(function() {
            var row = "<tr> </tr>";
        });
    });

    $("#button_range_bangunan").click(function() {
        if ($(".no").html()) {
            idf = parseInt($(".no").last().html()) + 1;
        } else {
            idf = 1;
        }
        var str = "<tr id='srow" + idf + "'>" +
            "<td class='no'>" + idf + "</td>" +
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

    $("#button_range_kavling").click(function() {
        if ($(".no2").html()) {
            idf2 = parseInt($(".no2").last().html()) + 1;
        } else {
            idf2 = 1;
        }
        var str = "<tr id='srow" + idf2 + "'>" +
            "<td class='no2'>" + idf2 + "</td>" +
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