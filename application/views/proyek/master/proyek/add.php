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

<div class="x_content">
    <br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" method="post" action="<?= site_url() . '/' . $this->uri->segment(1) ?>/save" autocomplete="off">
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-1 col-md-1 col-sm-12 col-md-offset-3">Source</label>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <select id="source" type="text" class="select2 form-control" name="source" placeholder="Source">
                        <option></option>
                        <option value="1">Global</option>
                        <option value="2">EREMS</option>
                        <option value="3">QS</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group" style="margin-top:20px">
                <div class="center-margin">
                    <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <br>
        <br>
        <div class="table-responsive">

        </div>
        <div class="col-md-12" id="dataisi">
            <div class="card-box table-responsive">
            </div>
            <div id="div_table" class="card-box table-responsive">
                <table id="table_unit" class="table table-striped table-bordered bulk_action">
                    <thead id="thead_pt">
                        <tr>
                            <th><input type="checkbox" id="check-all" class="flat"></th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Alamat</th>
                            <th>Phone</th>
                            <th>Fax</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_pt">

                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<!-- jQuery -->
<script type="text/javascript" src="http://localhost/emsNew/vendors/datatables.net/js/jquery.dataTables.min.js"></script>

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
            $("[name='pt_id[]']").iCheck(flag);
            for (var i = 0; i < $(".paginate_button").length - 2; i++) {
                table_unit_dt.fnPageChange(i);
                $("[name='pt_id[]']").iCheck(flag);
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

        $("#source").change(function() {
            $.ajax({
                type: "POST",
                data: {
                    source: $("#source").val()
                },
                url: "<?= site_url() . '/' . $this->uri->segment(1) ?>/get_ajax_proyek_source",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    table_unit_dt.fnDestroy();

                    $("#tbody_pt").html("");
                    // $("#blok").attr("disabled",false);
                    for (var i = 0; i < data.length; i++) {
                        $("#tbody_pt").append(
                            "<tr class='even pointer'>" +
                            "<td><input type='checkbox' class='flat table-check' name='pt_id[]' value='" + data[i].id +
                            "'></td>" +
                            // "<td>" + (i + 1) + "</td>" +
                            "<td>" + data[i].code + "</td>" +
                            "<td>" + data[i].name + "</td>" +
                            "<td>" + data[i].npwp + "</td>" +
                            // "<td class='a-center'>"+
                            // 	"<div class='icheckbox_flat-green checked' style='position: relative;'>"+
                            // 		"<input type='checkbox' class='flat' name='table_records' style='position: absolute; opacity: 0;'>"+
                            // 		"<ins class='iCheck-helper' style='position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;'></ins>"+
                            // 	"</div>"+
                            // "</td>"+
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
    });
</script> 