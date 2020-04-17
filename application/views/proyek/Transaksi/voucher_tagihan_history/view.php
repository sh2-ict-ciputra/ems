<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link type="text/css" href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.history.back()" disabled>
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
    <div class="modal fade" id="detail" data-backdrop="static" data-keyboard="false">
        <div class="">
            <div class="modal-content" style="margin-top:100px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="text-align:center;">Detail<span class="grt"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <table class="tableDT3 table table-striped jambo_table bulk_action">
                            <tbody>
                                <tr>
                                    <td>Project</td>
                                    <td><?= $project_name ?></td>
                                </tr>
                                <tr>
                                    <td>PT</td>
                                    <td id="detail_pt"></td>
                                </tr>
                                <tr>
                                    <td>Cara Bayar</td>
                                    <td id="detail_cara_bayar"></td>
                                </tr>
                                <tr>
                                    <td>Coa Cara Bayar</td>
                                    <td id="detail_coa_cara_bayar"></td>
                                </tr>
                                <tr>
                                    <td>Total Nilai Item</td>
                                    <td id="detail_total_nilai_item"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="col-md-12">
                            <a id="btn-validasi-detail" class="btn btn-primary col-md-3" style="float:right;height:64px;padding-top:20px;font-size:20px">Validasi</a>
                        </div>
                        <div class="col-md-12">
                            <a id="btn-kirim-detail" class="btn btn-primary col-md-3" style="float:right;height:64px;padding-top:20px;font-size:20px" disabled>Kirim</a>
                        </div>
                    </div>
                    <table class="tableDT3 table table-striped jambo_table bulk_action">
                        <thead>
                            <tr>
                                <th>Check</th>
                                <th>Kawasan</th>
                                <th>Blok</th>
                                <th>No. Unit</th>
                                <th>Item</th>
                                <th>Coa Item</th>
                                <th>Nilai (Rp.)</th>
                                <th>Tgl Bayar</th>
                                <th>Periode Tagihan</th>
                                <th>Status</th>
                                <th>Pesan</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_detail">

                        </tbody>
                        <tfoot id="tfoot_detail">
                            <tr>
                                <th>Check</th>
                                <th>Kawasan</th>
                                <th>Blok</th>
                                <th>No. Unit</th>
                                <th>Item</th>
                                <th>Coa Item</th>
                                <th>Nilai (Rp.)</th>
                                <th>Tgl Bayar</th>
                                <th>Periode Tagihan</th>
                                <th>Status</th>
                                <th>Pesan</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="x_content">
    <div class="row">
        <div class="col-sm-12">
        <form id="form" class="form-horizontal form-label-left" method="post" action="<?= site_url() ?>/P_master_service/save" autocomplete="off">
                    <div class="x_content">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12" for="code">
                                Tanggal Input
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input id="periode-awal" type="text" class="form-control datetimepicker" placeholder="Periode Awal">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12 text-center" style="font-size:20px">
                                -
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input id="periode-akhir" type="text" class="form-control datetimepicker" placeholder="Periode Awal">
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
                                <div class="center-margin">
                                    <a id="btn-load-unit" class="btn btn-primary">Load Unit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <div class="card-box table-responsive">
                <table class="table table-striped jambo_table bulk_action tableDT">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>PT</th>
                            <th>Cara Bayar</th>
                            <th>Coa Cara Bayar</th>
                            <th>Nilai (Rp.)</th>
                            <th>Tgl Buat Invoice</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tfoot id="tfoot" style="display: table-header-group">
                        <tr>
                            <th>No</th>
                            <th>PT</th>
                            <th>Cara Bayar</th>
                            <th>Coa Cara Bayar</th>
                            <th>Nilai (Rp.)</th>
                            <th>Tgl Buat Invoice</th>
                            <th>Detail</th>
                        </tr>
                    </tfoot>
                    <tbody id="tbody-header">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function formatNumber(data) {
        data = data + '';
        data = data.replace(/,/g, "");

        data = parseInt(data) ? parseInt(data) : 0;
        data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        return data;

    }
    function tableICheck() {
		$("input.flat").iCheck({
			checkboxClass: "icheckbox_flat-green",
			radioClass: "iradio_flat-green"
		})
	}
    function unformatNumber(data) {
        data = data + '';
        return data.replace(/,/g, "");
    }
    $(function() {
        $('.datetimepicker').datetimepicker({
			viewMode: 'years',
			format: 'DD/MM/YYYY'
		});
        $("#btn-load-unit").click(function(){
            $(".tableDT").DataTable().destroy();
            $.ajax({
                type: "GET",
                data: {
                    awal: $("#periode-awal").val(),
                    akhir: $("#periode-akhir").val()
                },
                url: '<?= site_url() ?>/Transaksi/P_voucher_tagihan_history/ajax_get_header',
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $("#tbody-header").html("");

                    $.each(data, function(i, v) {
                        var str = "<tr>";
                        str += "<td>" + (i+1) + "</td>";
                        str += "<td>" + v.pt_name + "</td>";
                        str += "<td>" + v.cara_pembayaran + "</td>";
                        str += "<td>" + v.coa_cara_pembayaran + "</td>";
                        str += "<td>" + formatNumber(v.nilai_item) + "</td>";
                        str += "<td>" + v.create_date + "</td>";
                        str += "<td>" +
                                    "<a data-toggle='modal' data-target='#detail' class='btn-detail btn btn-primary col-md-12' pt_id='"+v.pt_id+"' cara_pembayaran_id='"+v.cara_pembayaran_id+"' voucher_header_id='"+v.voucher_header_id+"'>Detail</a>"+
                                "</td>";
                        str += "</tr>";
                        console.log(str);
                        $("#tbody-header").append(str);

                    });
                    var table = $('.tableDT').DataTable();
            
                    // Apply the search
                    var table = $('.tableDT').DataTable();
                    table.columns().every( function () {
                        var that = this;
                        $( 'input', this.footer() ).on( 'keyup change', function () {
                            if ( that.search() !== this.value ) {
                                that
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                    } );

                }
            });
        });
        $("#btn-kirim-detail").click(function() {
            $.ajax({
                type: "GET",
                data: {
                    pt_id: $(this).attr("pt_id"),
                    cara_pembayaran_id: $(this).attr("cara_pembayaran_id"),
                    total_nilai: $("#detail_total_nilai_item").html()
                },
                url: '<?= site_url() ?>/Transaksi/P_voucher_tagihan_history/ajax_kirim',
                dataType: "json",
                success: function(data) {
                    console.log(data);
                }
            });
        });

        $("#btn-kirim-detail").hide();

        $("#btn-validasi-detail").click(function() {
            var validasi = 0;
            var loading = 0;

            $.each($("#tbody_detail").children(), function(i, v) {
                if($(".check-detail").eq(i).is(":checked")){
                    console.log(v);
                    console.log(v.id);
                    $("#loading").show();

                    $.ajax({
                        type: "GET",
                        data: {
                            id: v.id,
                            total_nilai: $("#detail_total_nilai_item").html()
                        },
                        url: '<?= site_url() ?>/Transaksi/P_voucher_tagihan_history/ajax_validasi',
                        dataType: "json",
                        success: function(data) {
                            if (data.result == 1) {
                                $("#tbody_detail").children().eq(i).children().eq(9).html("Sukses");
                                validasi++;
                                loading++;
                                $("#loading").show();
                            } else {
                                $("#tbody_detail").children().eq(i).children().eq(9).html("Error");
                                loading++;
                                $("#loading").show();
                            }
                            var total_check = 0;
                            $.each($("input[class='check-detail flat']"),function(k,v){
                                if($("input[class='check-detail flat']").eq(k).is(":checked"))
                                    total_check++;
                            })
                            $("#tbody_detail").children().eq(i).children().eq(10).html(data.message);
                            if (total_check == validasi) {
                                $("#btn-kirim-detail").attr("disabled", false);
                                $("#btn-kirim-detail").show();
                            } else {
                                $("#btn-kirim-detail").attr("disabled", true);
                                $("#btn-kirim-detail").hide();
                            }
                            
                            if ($("#tbody_detail").children == loading) {
                                $("#loading").hide();
                            } else {
                                $("#loading").show();
                            }
                        }
                    });
                }
            });   
        })
        $("body").on("ifChanged",".check-detail",function(){
            var total = 0;

            $.each($(".check-detail"),function(k,v){
                if($(".check-detail").eq(k).is(":checked")){
                    total += parseInt(unformatNumber($("#tbody_detail").children().eq(k).children().eq(6).html()));
                }
            })
            $("#detail_total_nilai_item").html("Rp. " + formatNumber(total));

        })
        $("body").on("click",".btn-detail",function() {
            console.log("yuhuu");
            $("#btn-kirim-detail").hide();
            $("#btn-kirim-detail").attr("pt_id", $(this).attr("pt_id"));
            $("#btn-kirim-detail").attr("cara_pembayaran_id", $(this).attr("cara_pembayaran_id"));


            url = '<?= site_url() ?>/Transaksi/P_voucher_tagihan_history/ajax_get_detail';
            $.ajax({
                type: "GET",
                data: {
                    pt_id: $(this).attr('pt_id'),
                    cara_pembayaran_id: $(this).attr('cara_pembayaran_id')
                },
                url: url,
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $("#detail_pt").html(data.pt_name);
                    $("#detail_cara_bayar").html(data.data[0].cara_pembayaran);
                    $("#detail_coa_cara_bayar").html(data.data[0].coa_cara_pembayaran);

                    $("#tbody_detail").html("");
                    var total = 0;

                    $.each(data.data, function(i, v) {
                        total += v.nilai_item;
                        var str = "<tr id='" + v.id + "." + v.item.substr(0, v.item.indexOf("-") - 1) + "'>";
						str += "<td><input type='checkbox' class='check-detail flat' name='check-detail[]' val='" + v.id + "' checked></td>";
                        str += "<td>" + v.kawasan + "</td>";
                        str += "<td>" + v.blok + "</td>";
                        str += "<td>" + v.no_unit + "</td>";
                        str += "<td>" + v.item + "</td>";
                        str += "<td>" + v.coa_item + "</td>";
                        str += "<td class='text-right'>" + formatNumber(v.nilai_item) + "</td>";
                        str += "<td>" + v.tgl_bayar + "</td>";
                        str += "<td>" + v.periode_tagihan + "</td>";
                        str += "<td>" +
                            "<div style='width:100%;height:100%' class='col-md-offset-4 lds-double-ring'></div>" +
                            "</td>";
                        str += "<td></td>";
                        str += "</tr>";
                        console.log(str);
                        $("#tbody_detail").append(str);

                    });
                    // var str = "<tr>";
                    // str += "<td colspan=5></td>";
                    // str += "<td>"+total+"</td>";
                    // str += "<td></td><td></td><td></td><td></td>";

                    // str += "</tr>";
                    $("#detail_total_nilai_item").html("Rp. " + formatNumber(total));
                    tableICheck();



                }
            });

        });
    });
</script>