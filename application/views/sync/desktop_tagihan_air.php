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


    <!-- <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" method="post" action="<?= $fullUrl ?>/save2" autocomplete="off"> -->
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
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Jenis Denda</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <select id="denda_jenis_service" type="text" class="select2 form-control" name="denda_jenis_service" placeholder="Project">
                        <option value="1">Fixed</option>
                        <option value="2">Progresif</option>
                        <option value="3">Persen Progresif</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Nilai Denda</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <input id="denda_nilai_service" type="text" class="form-control" name="denda_nilai_service" placeholder="Masukkan Nilai Denda Default">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Jarak Periode Pemakaian dengan Periode Tagihan</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <input id="jarak_periode" type="text" class="form-control" name="jarak_periode" placeholder="Masukkan Jarak">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Ke Project</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <select id="project_id" type="text" class="select2 form-control" name="project_id" placeholder="Project">
                        <!-- <option></option> -->
                        <?php foreach ($project as $v):?>
                        <option value="<?=$v->id?>"><?=$v->name?></option>
                        <?php endforeach;?>
                    </select>
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
    <!-- </form> -->
    <br>
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
                    source: $("#source").val()
                },
                url: "<?= $fullUrl ?>/get_ajax_desktop_transaksi_by_source",
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
                        data[i].periode = data[i].periode?data[i].periode:'';
                        data[i].denda = data[i].denda?data[i].denda:'';
                        data[i].tagihan = data[i].tagihan?data[i].tagihan:'';
                        data[i].status = data[i].periode?data[i].status:'';
                        
                        $("#tbody_pt").append(
                            "<tr class='even pointer'>" +
                            "<td><input type='checkbox' class='flat table-check' name='data_id[]' value='" + data[i].id+
                            "'></td>" +
                            "<td>" + data[i].kawasan + "</td>" +
                            "<td>" + data[i].blok + "</td>" +
                            "<td>" + data[i].no_unit + "</td>" +
                            "<td>" + data[i].pemilik + "</td>" +
                            "<td>" + data[i].periode + "</td>" +
                            "<td>" + data[i].denda + "</td>" +
                            "<td>" + data[i].tagihan + "</td>" +
                            "<td>" + data[i].status + "</td>" +
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
            var total_get = 0;
            $("#loading").show();
            console.log(table_unit_dt.$("input").serialize());
            data = table_unit_dt.$("input").serialize() + "&source="+$("#source").val()+"&project="+$("#project2").val();
            $.ajax({
                type: "GET",
                data: {
                    project_id: $("#project_id").val(),
                    source:$("#source").val(),
                    denda_jenis_service:$("#denda_jenis_service").val(),
                    denda_nilai_service:$("#denda_nilai_service").val(),
                    jarak_periode:$("#jarak_periode").val()
                },
                url: "<?=$fullUrl?>/save2",
                dataType: "json",
                success: function(data) {
                    if(data>= 0)
                        new PNotify({
                            title: "Data Berhasil di Tambah",
                            text: "Sejumlah "+data+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                    });
                }
            }).done(function(data){
                    console.log("done "+data);
                    if(data != 0){
                        $("#loading").show();
                        total_get += data;
                        $("#get_data").trigger("click");
                    }else{
                        $("#loading").hide();
                        new PNotify({
                            title: "Total Data Berhasil di Tambah",
                            text: "Sejumlah "+total_get+" Record",
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                    }
            });
        });
    });
</script> 