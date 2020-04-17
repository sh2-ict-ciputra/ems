<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!-- select2 -->
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- switchery -->
<link href="<?= base_url() ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">


<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href='<?= site_url() . '/' . $this->uri->segment(1) ?>'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() . '/' . $this->uri->segment(1) ?>/add'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<?php 
    $fullUrl = site_url()."/".implode("/",(array_slice($this->uri->segment_array(),0,-1)));
?>
<div class="x_content">
    <br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" method="post" action="<?= $fullUrl ?>/save" autocomplete="off">
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Source</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <input id="source" type="text" class="form-control" name="source" placeholder="Masukkan Nama Database Source">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Formula Air</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <input id="formula_air" type="text" class="form-control" name="formula_air" placeholder="Masukkan Formula Air (1-4)">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Formula Bangunan</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <input id="formula_bangunan" type="text" class="form-control" name="formula_bangunan" placeholder="Masukkan Formula Bangunan (1-4)">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Formula kavling</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <input id="formula_kavling" type="text" class="form-control" name="formula_kavling" placeholder="Masukkan Formula Kavling (1-4)">
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Ke Project</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <select id="project2" type="text" class="select2 form-control" name="project" placeholder="Project">
                        <!-- <option></option> -->
                        <?php foreach ($project as $v):?>
                        <option value="<?=$v->id?>"><?=$v->name?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12">

			<div class="form-group" style="margin-top:20px" hidden>
				<div class="center-margin">
					<!-- <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button> -->
					<a id="btn-load" class="btn btn-primary">Load Unit</a>
				</div>
			</div>
		</div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group" style="margin-top:20px">
                <div class="center-margin">
                    <!-- <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button> -->
                    <a id="get_data" class="btn btn-success">Get Data</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <br>
        <br>
        <div class="table-responsive">

        </div>
        <div class="col-md-12" id="dataisi" hidden>
            <div class="card-box table-responsive">
            </div>
            <div id="div_table" class="card-box table-responsive">
                <table id="table_unit" class="table table-striped table-bordered bulk_action">
                    <thead id="thead_pt">
                        <tr>
                            <th><input type="checkbox" id="check-all" class="flat"></th>
                            <th>Kawasan </th>
                            <th>Blok</th>
                            <th>No Unit</th>
                            <th>Pemilik</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_pt">

                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <br>
    <!-- * : Komponen yang Harus di import sebelumnya -->
</div>

<!-- jQuery -->
<script type="text/javascript" src="<?= base_url(); ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $.each($(".select2"), function(key, value) {
        $(this).select2({
            placeholder: $(this).attr('placeholder')
        });
    })
    var table_unit = $("#table_unit");
    var table_unit_dt = table_unit.dataTable({
        order: [
            [1, "asc"]
        ],
        columnDefs: [{
            orderable: !1,
            targets: [0]
        }]
    });
    table_unit.on("draw.dt", function() {
        $("checkbox input").iCheck({
            checkboxClass: "icheckbox_flat-green"
        })
    })

    function tableICheck() {
        $("input.flat").iCheck({
            checkboxClass: "icheckbox_flat-green",
            radioClass: "iradio_flat-green"
        })
    }
    $(function() {
        $('#div_table').on('ifChanged', '#check-all', function(event) {
            if ($("#check-all").is(":checked")) flag = 'check';
            else flag = 'uncheck';
            $("[name='data_id[]']").iCheck(flag);
            for(var i = 0; i < $(".paginate_button")[$(".paginate_button").length-2].innerHTML ; i++){
                table_unit_dt.fnPageChange(i);
                $("[name='data_id[]']").iCheck(flag);
            }
            table_unit_dt.fnPageChange("first");

        });
        $("#div_table div #table_unit_paginate").click(function() {
            console.log("hahaha")
        })
        $("#single_multiple").change(function() {
            if ($("#single_multiple").is(':checked')) {
                $(".multiple").attr('disabled', false);
            } else {
                $(".multiple").attr('disabled', true);
                $(".multiple").val('');
            }
        });
        $(".service").change(function() {
            console.log("haha");
            console.log($(this));
        });
        $("#btn-load").click(function() {
            $.ajax({
                type: "POST",
                data: {
                    source: $("#source").val(),
                    project_id: $("#project2").val()
                },
                // url: "<?= $fullUrl ?>/get_ajax_desktop_unit_by_source",
                url: "<?= $fullUrl ?>/get_ajax_desktop_unit",
                
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    table_unit_dt.fnDestroy();

                    $("#tbody_pt").html("");
                    for (var i = 0; i < data.length; i++) {
                        data[i].kawasan = data[i].kawasan?data[i].kawasan:'';
                        data[i].blok = data[i].blok?data[i].blok:'';
                        data[i].no_unit = data[i].no_unit?data[i].no_unit:'';
                        data[i].pemilik = data[i].pemilik?data[i].pemilik:'';
                        
                        $("#tbody_pt").append(
                            "<tr class='even pointer'>" +
                            "<td><input type='checkbox' class='flat table-check' name='data_id[]' value='" + data[i].id+
                            "'></td>" +
                            "<td>" + data[i].kawasan + "</td>" +
                            "<td>" + data[i].blok + "</td>" +
                            "<td>" + data[i].no_unit + "</td>" +
                            "<td>" + data[i].pemilik + "</td>" +
                            "</tr>");
                    }
                    tableICheck();

                    table_unit.dataTable({
                        order: [
                            [1, "asc"]
                        ],
                        columnDefs: [{
                            orderable: !1,
                            targets: [0]
                        }]
                    });
                    table_unit.on("draw.dt", function() {
                        $("checkbox input").iCheck({
                            checkboxClass: "icheckbox_flat-green"
                        })
                    })
                }
            });
        });
        $("#get_data").click(function() {
            $("#loading").show();

            console.log(table_unit_dt.$("input").serialize());
            $.ajax({
                type: "POST",
                data: {
                    source: $("#source").val(),
                    formula_air:$("#formula_air").val(),
                    formula_bangunan:$("#formula_bangunan").val(),
                    formula_kavling:$("#formula_kavling").val(),
                    project_id:$("#project2").val()
                },
                url: "<?=$fullUrl?>/Desktop_Unit/save2",
                dataType: "json",
                success: function(data) {
                    $("#loading").hide();

                    if(data.blok > 0)
                        new PNotify({
                            title: "Blok Berhasil di Tambah",
                            text: "Sejumlah "+data.blok+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                    if(data.customer > 0)
                        new PNotify({
                            title: "Customer Berhasil di Tambah",
                            text: "Sejumlah "+data.customer+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                    if(data.golongan > 0)
                        new PNotify({
                            title: "Golongan Berhasil di Tambah",
                            text: "Sejumlah "+data.golongan+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                    if(data.kawasan > 0)
                        new PNotify({
                            title: "Kawasan Berhasil di Tambah",
                            text: "Sejumlah "+data.kawasan+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        if(data.range_air > 0)
                        new PNotify({
                            title: "Range Air Berhasil di Tambah",
                            text: "Sejumlah "+data.range_air+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        if(data.range_lingkungan > 0)
                        new PNotify({
                            title: "Range Lingkungan Berhasil di Tambah",
                            text: "Sejumlah "+data.range_lingkungan+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        if(data.sub_golongan > 0)
                        new PNotify({
                            title: "Sub Golongan Berhasil di Tambah",
                            text: "Sejumlah "+data.sub_golongan+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        if(data.unit > 0)
                        new PNotify({
                            title: "Unit Berhasil di Tambah",
                            text: "Sejumlah "+data.unit+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        if(data.unit_air > 0)
                        new PNotify({
                            title: "Unit Air Berhasil di Tambah",
                            text: "Sejumlah "+data.unit_air+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        if(data.unit_lingkungan > 0)
                        new PNotify({
                            title: "Unit Lingkungan Berhasil di Tambah",
                            text: "Sejumlah "+data.unit_lingkungan+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                }
            });
        });
    });
</script> 