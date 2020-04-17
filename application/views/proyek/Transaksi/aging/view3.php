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



<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet">


<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<style>
    .invalid {
        background-color: lightpink;
    }

    .has-error {
        border: 1px solid rgb(185, 74, 72) !important;
    }

    a.disabled {
        pointer-events: none;
        cursor: default;
    }
</style>
<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href='<?= site_url() ?>/P_transaksi_meter_air'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_transaksi_meter_air/add'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_conte	nt">

    <br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/Transaksi/P_transaksi_generate_bill/save" autocomplete="off">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Kawasan</label>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <select name="kawasan" required="" id="kawasan" class="form-control select2" placeholder="-- Pilih Kawasan --">
                        <option value="" disabled selected>-- Pilih Kawasan --</option>
                        <option value='0'>-- Semua Kawasan --</option>
                        <?php
                        foreach ($kawasan as $v) {
                            echo ("<option value='$v->id'>$v->code - $v->name</option>");
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Blok</label>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <select name="blok" required="" id="blok" class="form-control select2" placeholder="-- Pilih Kawasan Dahulu --" disabled>
                        <option value="" disabled selected>-- Pilih Kawasan Dahulu --</option>
                        <option value='0'>-- Semua Blok --</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <label class="control-label col-lg-3 col-md-3 col-sm-12">Tanggal Aging</label>
                <div class="col-lg-9 col-md-9 col-sm-12">
                    <div class='input-group date datetimepicker_month'>
                        <input id="periode" type="text" class="form-control datetimepicker" name="bulan" placeholder="Bulan">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <div class="form-group">
                <div class="center-margin">
                    <!-- <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button> -->
                    <a id="btn-load-unit" class="btn btn-primary">Load Unit</a>
                    <a id="btn-print-excel" class="btn btn-primary">Expot Excel</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <br>
        <br>
        <div class="table-responsive">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
            <!-- <div class="card-box table-responsive">
			</div> -->
            <!-- <div id="div_table" class="col-md-12 card-box table-responsive"> -->
            <!-- table table-striped table-bordered bulk_action -->
            <table id="table_unit" class="display nowrap table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Kawasan</th>
                        <th rowspan="2">Kode Blok</th>
                        <th rowspan="2">No Unit</th>
                        <th rowspan="2">Nama</th>

                        <th rowspan="2">Luas Tanah</th>
                        <th rowspan="2">Luas Bangunan</th>
                        <!-- <th rowspan="2">Status Huni</th> -->
                        <th colspan="4">30 Hari
                        </th>
                        <th colspan="4">60 Hari
                        </th>
                        <th colspan="4">90 Hari
                        </th>
                        <th colspan="4">>90 Hari
                        </th>
                        <th rowspan="2">Total
                        </th>
                    </tr>
                    <tr>
                        <th>Air</th>
                        <th>Lingkungan</th>
                        <th>PPN</th>
                        <th>Denda</th>
                        <th>Air</th>
                        <th>Lingkungan</th>
                        <th>PPN</th>
                        <th>Denda</th>
                        <th>Air</th>
                        <th>Lingkungan</th>
                        <th>PPN</th>
                        <th>Denda</th>
                        <th>Air</th>
                        <th>Lingkungan</th>
                        <th>PPN</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody id="tbody_unit">

                </tbody>
            </table>
            <!-- </div> -->
        </div>
    </form>
</div>

<!-- jQuery -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url('vendors/jquery-excel/jquery.table2excel.min.js') ?>"></script>

<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script> -->
<script type="text/javascript">
    // $("#table_unit").dataTable({
    //     order: [
    //         [1, "asc"]
    //     ],
    //     columnDefs: [{
    //         orderable: !1,
    //         targets: [1]
    //     }]
    // });

    // table_unit.on("draw.dt", function() {
    //     $("checkbox input").iCheck({
    //         checkboxClass: "icheckbox_flat-green"
    //     })
    // })
    var is_small_version = 1;
    function currency(input) {
        var input = input.toString().replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt(input, 10) : 0;
        return (input === 0) ? "" : input.toLocaleString("en-US");
    }

    function tableICheck() {
        $("input.flat").iCheck({
            checkboxClass: "icheckbox_flat-green",
            radioClass: "iradio_flat-green"
        })
    }

    function periode(e) {
        var tmp = e.val();
        console.log(tmp);
        tmp = new Date(tmp.substr(3, 4), tmp.substr(0, 2) - 1, 1);
        console.log(tmp);
        tmp.setMonth(tmp.getMonth() - 1);
        console.log(tmp);
        $("#periode-penggunaan-akhir").val(e.val());
        $("#periode-penggunaan-awal").val(("0" + (parseInt(tmp.getMonth()) + 1)).slice(-2) + "/" + tmp.getFullYear());
        console.log(tmp);
    }

    function formatNumber(data) {
        data = data + '';
        data = data.replace(/,/g, "");

        data = parseInt(data) ? parseInt(data) : 0;
        data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        return data;

    }

    function unformatNumber(data) {
        data = data + '';
        return data.replace(/,/g, "");
    }
    function table_version(){ //fitur nanti
        $("#table_unit").dataTable().fnDestroy();
        $("#table_unit").dataTable({
            "paging": false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
            ]
        });
    }
    $(".select2").select2();
    $(function() {
        $("#btn-print-excel").click(function() {
			$("#table_unit").table2excel({
				exclude: ".noExl",
				name: "Worksheet",
				filename: "Laporan Pembayaran", //do not include extension
				fileext: ".xls" // file extension
			});
		});

        $("#table_unit").dataTable().fnDestroy();

        // $("#table_unit").DataTable( {
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'copy', 'csv', 'excel', 'pdf', 'print'
        //     ]
        // } );
        function notif(title, text, type) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                styling: 'bootstrap3'
            });
        }
        var date = new Date();

        // $("#periode").val(date.getDay()+"/"+date.getMonth() + 1 + "/" + date.getFullYear());
        $("#periode").trigger("change");

        $("#periode").on('dp.change', function() {
            periode($("#periode"));
        });
        $("body").on("keyup", ".meter-akhir", function() {
            awal = unformatNumber($(this).parent().parent().children('.meter-awal').html());
            akhir = unformatNumber($(this).val());
            pakai = formatNumber(akhir - awal);
            $(this).parent().parent().children('.meter-pakai').html(pakai);

            if (akhir - awal < 0) {
                $(this).parent().parent().children().children('.save-row').addClass("disabled");
            } else {
                $(this).parent().parent().children().children('.save-row').removeClass("disabled");
                $(this).parent().parent().children('.meter-pakai').html(pakai);
            }

        });
        $("#btn-load-unit").click(function() {
            if ($("#kawasan").val() == null) {
                $('#kawasan').next().find('.select2-selection').addClass('has-error');
            } else {
                $('#kawasan').next().find('.select2-selection').removeClass('has-error');
            }
            if ($("#blok").val() == null) {
                $('#blok').next().find('.select2-selection').addClass('has-error');
            } else {
                $('#blok').next().find('.select2-selection').removeClass('has-error');
            }
            if ($("#periode").val() == "") {
                $('#periode').addClass('has-error');
            } else {
                $('#periode').removeClass('has-error');
            }
            if ($("#kawasan").val() != null && $("#blok").val() != null && $("#periode").val() != null) {
                $.ajax({
                    type: "GET",
                    data: {
                        kawasan: $("#kawasan").val(),
                        blok: $("#blok").val(),
                        tgl_aging: $("#periode").val(),
                    },
                    url: "<?= site_url() ?>/Transaksi/P_aging/ajax_get_aging",
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        $("#table_unit").dataTable().fnDestroy();
                        $("#tbody_unit").html("");

                        for (var i = 0; i < data.length; i++) {
                            var total = +(data[i].nilai_tagihan_ipl_30) +
                                (data[i].nilai_ppn_air_30 + data[i].nilai_ppn_ipl_30) +
                                (data[i].nilai_denda_air_30 + data[i].nilai_denda_ipl_30) +
                                (data[i].nilai_tagihan_air_60) +
                                (data[i].nilai_tagihan_ipl_60) +
                                (data[i].nilai_ppn_air_60 + data[i].nilai_ppn_ipl_60) +
                                (data[i].nilai_denda_air_60 + data[i].nilai_denda_ipl_60) +
                                (data[i].nilai_tagihan_air_90) +
                                (data[i].nilai_tagihan_ipl_90) +
                                (data[i].nilai_ppn_air_90 + data[i].nilai_ppn_ipl_90) +
                                (data[i].nilai_denda_air_90 + data[i].nilai_denda_ipl_90) +
                                (data[i].nilai_tagihan_air_120) +
                                (data[i].nilai_tagihan_ipl_120) +
                                (data[i].nilai_ppn_air_120 + data[i].nilai_ppn_ipl_120) +
                                (data[i].nilai_denda_air_120 + data[i].nilai_denda_ipl_120);
                            if (total != 0) {
                                $("#tbody_unit").append(
                                    "<tr class='even pointer'>" +
                                    "<td>" + (i + 1) + "</td>" +
                                    "<td class='table-kawasan'>" + data[i].kawasan + "</td>" +
                                    "<td class='table-blok'>" + data[i].blok + "</td>" +
                                    "<td class='table-blok'>" + data[i].no_unit + "</td>" +
                                    "<td>" + data[i].pemilik + "</td>" +
                                    "<td>" + data[i].luas_tanah + "</td>" +
                                    "<td>" + data[i].luas_bangunan + "</td>" +

                                    "<td class='text-right'>" + formatNumber(data[i].nilai_tagihan_air_30) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_tagihan_ipl_30) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_ppn_air_30 + data[i].nilai_ppn_ipl_30) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_denda_air_30 + data[i].nilai_denda_ipl_30) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_tagihan_air_60) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_tagihan_ipl_60) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_ppn_air_60 + data[i].nilai_ppn_ipl_60) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_denda_air_60 + data[i].nilai_denda_ipl_60) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_tagihan_air_90) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_tagihan_ipl_90) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_ppn_air_90 + data[i].nilai_ppn_ipl_90) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_denda_air_90 + data[i].nilai_denda_ipl_90) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_tagihan_air_120) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_tagihan_ipl_120) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_ppn_air_120 + data[i].nilai_ppn_ipl_120) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(data[i].nilai_denda_air_120 + data[i].nilai_denda_ipl_120) + "</td>" +
                                    "<td class='text-right'>" + formatNumber(total) + "</td>" +

                                    "</tr>");
                            }
                        }
                        $("#table_unit").dataTable({
                            "paging": false,

                            dom: 'Bfrtip',

                            buttons: [
                                'copy', 'csv', 'excel', 'pdf'
                            ]

                        });
                        // for (var i = 0; i < $(".paginate_button")[$(".paginate_button").length - 2].innerHTML; i++) {
                        //     table_unit_dt.fnPageChange(i);
                        //     if ($("#kawasan").val() != 'all')
                        //         $(".table-kawasan").hide()
                        //     else
                        //         $(".table-kawasan").show()

                        //     if ($("#blok").val() != 'all')
                        //         $(".table-blok").hide()
                        //     else
                        //         $(".table-blok").show()
                        // }
                        // table_unit_dt.fnPageChange("first");


                    }
                });
            }

        });

        $('.datetimepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD/MM/YYYY'
        });
        $('.datetimepicker_month').datetimepicker({
            viewMode: 'years',
            format: 'MM/YYYY'
        });
        $('.datetimepicker_year').datetimepicker({
            format: 'YYYY'
        });
        $("#kawasan").change(function() {
            if ($("#kawasan").val() == null) {
                $('#kawasan').next().find('.select2-selection').addClass('has-error');
            } else {
                $('#kawasan').next().find('.select2-selection').removeClass('has-error');
            }
            $.ajax({
                type: "GET",
                data: {
                    id: $(this).val()
                },
                url: "<?= site_url() ?>/Transaksi/P_aging/ajax_get_blok",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $("#blok").html("");
                    $("#blok").attr("disabled", false);
                    $("#blok").append("<option value='' disabled selected>-- Pilih Kawasan Dahulu --</option>");
                    $("#blok").append("<option value='all'>-- Semua Blok --</option>");
                    for (var i = 0; i < data.length; i++) {
                        $("#blok").append("<option value='" + data[i].id + "'>" + data[i].name + "</option>");
                    }
                }
            });
        });
        $("#blok").change(function() {
            if ($("#blok").val() == null) {
                $('#blok').next().find('.select2-selection').addClass('has-error');
            } else {
                $('#blok').next().find('.select2-selection').removeClass('has-error');
            }
        });
        $("#periode").on('dp.change', function(e) {
            console.log(e);
            if ($("#periode").val() == "") {
                $('#periode').addClass('has-error');
            } else {
                $('#periode').removeClass('has-error');
            }
        });
        $("#unit").change(function() {
            url = '<?= site_url(); ?>/P_aging/getInfoUnit';
            var id = $("#unit").val();
            //console.log(this.value);
            $.ajax({
                type: "get",
                url: url,
                data: {
                    id: id
                },
                dataType: "json",
                success: function(data) {
                    $("#customer").val(data.customer);
                    $("#barcode").val(data.barcode);
                    $("#meter_awal").val(currency(data.meter));
                    $("#meter_akhir").attr('disabled', false);
                    $("#meter_akhir").attr('placeholder', '-- Masukkan Meter Akhir --');
                }
            });
        });
        $("#meter_akhir").keyup(function() {
            $("#pemakaian").val($("#meter_akhir").val().replace(/,/g, '') - $("#meter_awal").val().replace(/,/g, ''));
        });
    });
</script>