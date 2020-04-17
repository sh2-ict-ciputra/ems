<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!-- select2 -->
<link type="text/css" href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link type="text/css" href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<div style="float:right">
    <h2>
        <!-- <button class="btn btn-primary" onClick="window.location.href='<?= site_url() ?>/Transaksi_lain/P_unit_sewa/add'">
            <i class="fa fa-plus"></i>
            Kirim
        </button> -->
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
<div id="contentx" class="x_content">
    <!-- <form id="form" class="form-horizontal form-label-left" novalidate="" method="post" autocomplete="off"> -->
        <div class="col-md-12 col-lg-12">
            <div class="form-group">
                <label class="control-label col-lg-2 col-md-3 col-sm-12">Tanggal Pembayaran<br>(dd/mm/yyy)</label>
                <div class="col-lg-4 col-md-4 col-sm-5">
                    <div class='input-group date'>
                        <input id="tanggal-awal" type="text" class="form-control datetimepicker" placeholder="Tanggal Awal">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <label class="control-label col-lg-1 col-md-1 col-sm-2" style="text-align:center">-</label>
                <div class="col-lg-4 col-md-4 col-sm-5">
                    <div class='input-group date'>
                        <input id="tanggal-akhir" type="text" class="form-control datetimepicker" placeholder="Tanggal Akhir">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">

            <div class="form-group" style="margin-top:20px">
                <div class="center-margin">
                    <!-- <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button> -->
                    <a id="btn-load" class="btn btn-primary">Load Unit</a>
                </div>
            </div>
        </div>
    <!-- </form> -->
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
                        <table class="tableDT3 table table-striped jambo_table">
                            <tbody>
                                <tr>
                                    <td>Project</td>
                                    <td id="project_erems_id" val="<?=$project_erems_id?>"><?= $project_name ?></td>
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
                            <a id="btn-validasi-detail" class="btn btn-primary col-md-3" style="float:right;height:64px;padding-top:20px;font-size:20px" disabled>Validasi</a>
                        </div>
                        <div class="col-md-12">
                            <a id="btn-kirim-detail" class="btn btn-primary col-md-3" style="float:right;height:64px;padding-top:20px;font-size:20px" disabled>Kirim</a>
                        </div>
                    </div>
                    <table class="tableDT3 table table-striped jambo_table">
                        <thead>
                            <tr>
                                <th>Kawasan</th>
                                <th>Blok</th>
                                <th>No. Unit</th>
                                <th>Pemilik</th>
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
                                <th>Kawasan</th>
                                <th>Blok</th>
                                <th>No. Unit</th>
                                <th>Pemilik</th>
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
            <div class="card-box table-responsive">
                <table class="table table-striped jambo_table tableDT">
                    <thead>
                        <tr>
                            <th>PT</th>
                            <th>Cara Bayar</th>
                            <th>Coa Cara Bayar</th>
                            <th>Nilai</th>
                            <!-- <th>Kirim</th> -->
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-voucher">
                    </tbody>
                </table>
            </div>
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
    const row = "<tr>"+
                "<td>{{pt}}</td>"+
                "<td>{{cara_pembayaran}}</td>"+
                "<td>{{coa_cara_pembayaran}}</td>"+
                "<td class='text-right'>{{tagihan}}</td>"+
                "<td>"+
                    "<a data-toggle='modal' data-target='#detail' class='btn-detail btn btn-primary col-md-12' pt_id='{{pt_id}}' cara_pembayaran_id='{{cara_pembayaran_id}}'>Detail</a>" +
                "</td>"+
                "</tr>";
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
    $(function() {
        $("#btn-load").click(function(){
            $.ajax({
                type: "GET",
                data: {
                    tanggal_awal : $("#tanggal-awal").val(),
                    tanggal_akhir : $("#tanggal-akhir").val()
                },
                url: '<?= site_url() ?>/Transaksi/P_voucher_tagihan/ajax_get_voucher',
                dataType: "json",
                success: function(data) {
                    $(".tableDT").DataTable().destroy();
                    $("#tbody-voucher").html('');
                    $.each(data,function(i,v){
                        var tmp = row;
                        tmp = tmp.replace("{{pt}}",v.pt);
                        tmp = tmp.replace("{{pt_id}}",v.pt_id);
                        tmp = tmp.replace("{{cara_pembayaran}}",v.cara_pembayaran);
                        tmp = tmp.replace("{{cara_pembayaran_id}}",v.cara_pembayaran_id);
                        
                        tmp = tmp.replace("{{coa_cara_pembayaran}}",v.coa_cara_pembayaran);
                        tmp = tmp.replace("{{tagihan}}",formatNumber(v.tagihan));
                        $("#tbody-voucher").append(tmp);
                    })
                    $(".tableDT").DataTable();
                    console.log(data);
                }
            });
        })
        $('.datetimepicker').datetimepicker({
			viewMode: 'years',
			format: 'DD/MM/YYYY'
		});
        var select2_jns_service = $("#pt").select2({
            placeholder: '-- Masukkan Pilihan --',
            tags: true,
            tokenSeparators: [',', ' ']
        });
        var select2_cara_bayar = $("#cara_bayar").select2({
            placeholder: '-- Masukkan Pilihan --',
            tags: true,
            tokenSeparators: [',', ' ']
        });
        $("#btn-kirim-detail").click(function() {
            
            var tipe = [];
            var coa_item = [];
            $.each($("#tbody_detail").children(), function( i, v ) {
                if(!tipe.find(function(data){if(data==$("#tbody_detail").children().eq(i).attr('id').toLowerCase())return 1;else return 0})){
                    tipe.push($("#tbody_detail").children().eq(i).attr('id').toLowerCase());
                    coa_item.push($("#tbody_detail").children().eq(i).find(".coa_item").html());
                }
            });
            $.ajax({
                type: "GET",
                data: {
                    total_nilai : $("#detail_total_nilai_item").attr('val'),
                    cara_bayar : $("#detail_cara_bayar").html(),
                    coa_cara_bayar : $("#detail_coa_cara_bayar").html(),
                    project_erems_id :  $("#project_erems_id").attr('val'),
                    pt_erems_id : $("#btn-validasi-detail").attr("pt_id"),
                    tipe : tipe,
                    coa_item : coa_item,
                    cara_bayar_id : $("#btn-kirim-detail").attr("cara_pembayaran_id"),
                    periode_awal : $("#tanggal-awal").val(),
                    periode_akhir : $("#tanggal-akhir").val()

                },
                url: '<?=site_url()?>/Transaksi/P_voucher_tagihan/test',
                dataType: "json",
                success: function(data) {
                    validasi = 0;
                    $.each($("#tbody_detail").children(), function( i, v ) {
                        $.each(data,function(i2,v2){
                            if($("#tbody_detail").children().eq(i).find(".coa_item").html() == v2.coa){
                                $("#tbody_detail").children().eq(i).find('.status_row').html(v2.result);
                                $("#tbody_detail").children().eq(i).find('.pesan_row').html(v2.message);
                                validasi++;
                            }
                        });
                    });

                    console.log(validasi);
                    // if($("#tbody_detail").children().length == validasi){
                    //     $("#btn-kirim-detail").attr("disabled",false);
                    //     $("#btn-kirim-detail").show();
                    // }else{
                    //     $("#btn-kirim-detail").attr("disabled",true);
                    //     $("#btn-kirim-detail").hide();
                    // }
                }
            });
        })
        // $("#btn-kirim-detail").click(function(){
        //     var cara_pembayaran_id = $(this).attr("cara_pembayaran_id");
        //     var pt_id = $(this).attr("pt_id");
        //     console.log("cara_pembayaran_id "+cara_pembayaran_id);
        //     console.log("pt_id "+pt_id);
        //     var tipe = [];
        //     var coa_item = [];
        //     var nilai_item = [];
        //     var blok = [];
        //     var no_unit = [];
        //     var pemilik = [];
        //     $.each($("#tbody_detail").children(), function( i, v ) {
        //         tipe.push($("#tbody_detail").children().eq(i).attr('id').toLowerCase());
        //         coa_item.push($("#tbody_detail").children().eq(i).find(".coa_item").html());
        //         nilai_item.push(unformatNumber($("#tbody_detail").children().eq(i).find(".nilai_item").html()));
        //         blok.push(unformatNumber($("#tbody_detail").children().eq(i).find(".blok").html()));
        //         no_unit.push(unformatNumber($("#tbody_detail").children().eq(i).find(".no_unit").html()));
        //         pemilik.push(unformatNumber($("#tbody_detail").children().eq(i).find(".pemilik").html()));
        //         unit_id.push(unformatNumber($("#tbody_detail").children().eq(i).find(".no_unit").attr('unit_id')));
        //     });
        //     $.ajax({
        //         type: "POST",
        //         data: {
        //             total_nilai : $("#detail_total_nilai_item").attr('val'),
        //             cara_bayar : $("#detail_cara_bayar").html(),
        //             coa_cara_bayar : $("#detail_coa_cara_bayar").html(),
        //             project_erems_id :  $("#project_erems_id").attr('val'),
        //             cara_bayar_id : $(this).attr("cara_pembayaran_id"),
        //             pt_erems_id : $("#btn-validasi-detail").attr("pt_id"),
        //             tipe : tipe,
        //             coa_item : coa_item,
        //             nilai_item : nilai_item,
        //             unit_id : unit_id
        //         },
        //         url: '<?=site_url()?>/Transaksi/P_voucher_tagihan/ajax_kirim_gabungan',
        //         dataType: "json",
        //         success: function(data) {
        //             console.log(data);
        //             if(data.result == 1){
        //                 console.log("a[cara_pembayaran_id='"+cara_pembayaran_id+"'][pt_id='"+pt_id+"']");
        //                 console.log("cara_pembayaran_id "+cara_pembayaran_id);
        //                 console.log("pt_id "+pt_id);
		// 				notif('Sukses', 'Voucher Berhasil dibuat', 'success');
        //                 $("#btn-kirim-detail").hide();
        //                 $("#btn-validasi-detail").hide();
        //                 $("a[cara_pembayaran_id='"+cara_pembayaran_id+"'][pt_id='"+pt_id+"']").parents("tr").remove()
        //             }else
		// 				notif('Gagal', 'Voucher Gagal di buat, coba lagi', 'danger');
        //         }
        //     });
        // });
        // $("#btn-kirim-detail").click(function() {
        //     var cara_pembayaran_id = $(this).attr("cara_pembayaran_id");
        //     var pt_id = $(this).attr("pt_id");
        //     console.log("cara_pembayaran_id " + cara_pembayaran_id);
        //     console.log("pt_id " + pt_id);
        //     $.ajax({
        //         type: "GET",
        //         data: {
        //             pt_id: $(this).attr("pt_id"),
        //             cara_pembayaran_id: $(this).attr("cara_pembayaran_id"),
        //             total_nilai: unformatNumber($("#detail_total_nilai_item").html()).substr(4),
        //             tanggal_awal : $("#tanggal-awal").val(),
        //             tanggal_akhir : $("#tanggal-akhir").val()

        //         },
        //         url: '<?= site_url() ?>/Transaksi/P_voucher_tagihan/ajax_kirim',
        //         dataType: "json",
        //         success: function(data) {
        //             console.log(data);
        //             if (data.result == 1) {
        //                 console.log("a[cara_pembayaran_id='" + cara_pembayaran_id + "'][pt_id='" + pt_id + "']");
        //                 console.log("cara_pembayaran_id " + cara_pembayaran_id);
        //                 console.log("pt_id " + pt_id);
        //                 notif('Sukses', 'Voucher Berhasil dibuat', 'success');
        //                 $("#btn-kirim-detail").hide();
        //                 $("#btn-validasi-detail").hide();
        //                 $("a[cara_pembayaran_id='" + cara_pembayaran_id + "'][pt_id='" + pt_id + "']").parents("tr").remove()
        //             } else
        //                 notif('Gagal', 'Voucher Gagal di buat, coba lagi', 'danger');
        //         }
        //     });
        // });

        $("#btn-kirim-detail").hide();
        $("#btn-validasi-detail").hide();

        $("#btn-validasi-detail").click(function() {
            
            var tipe = [];
            var coa_item = [];
            $.each($("#tbody_detail").children(), function( i, v ) {
                if(!coa_item.find(
                    function(data){
                        console.log(data);
                        if(data == $("#tbody_detail").children().eq(i).find(".coa_item").html())
                            return 1;
                        else 
                            return 0
                    }
                ))
                {
                    tipe.push($("#tbody_detail").children().eq(i).attr('id').toLowerCase());
                    coa_item.push($("#tbody_detail").children().eq(i).find(".coa_item").html());
                }
            });
            $.ajax({
                type: "GET",
                data: {
                    total_nilai : $("#detail_total_nilai_item").attr('val'),
                    cara_bayar : $("#detail_cara_bayar").html(),
                    coa_cara_bayar : $("#detail_coa_cara_bayar").html(),
                    project_erems_id :  $("#project_erems_id").attr('val'),
                    pt_erems_id : $("#btn-validasi-detail").attr("pt_id"),
                    tipe : tipe,
                    coa_item : coa_item
                },
                url: '<?=site_url()?>/Transaksi/P_voucher_tagihan/ajax_validasi_gabungan',
                dataType: "json",
                success: function(data) {
                    validasi = 0;
                    $.each($("#tbody_detail").children(), function( i, v ) {
                        $.each(data,function(i2,v2){
                            if($("#tbody_detail").children().eq(i).find(".coa_item").html() == v2.coa){
                                $("#tbody_detail").children().eq(i).find('.status_row').html(v2.result);
                                $("#tbody_detail").children().eq(i).find('.pesan_row').html(v2.message);
                                validasi++;
                            }
                        });
                    });

                    console.log(validasi);
                    if($("#tbody_detail").children().length == validasi){
                        $("#btn-kirim-detail").attr("disabled",false);
                        $("#btn-kirim-detail").show();
                    }else{
                        $("#btn-kirim-detail").attr("disabled",true);
                        $("#btn-kirim-detail").hide();
                    }
                }
            });
        })
        $("body").on("click",".btn-detail",function(){
            $("#btn-kirim-detail").hide();
            $("#btn-validasi-detail").hide();
            $("#btn-validasi-detail").attr('disabled',true);

            $("#btn-validasi-detail").attr("pt_id",$(this).attr("pt_id"));
            $("#btn-kirim-detail").attr("pt_id", $(this).attr("pt_id"));
            $("#btn-kirim-detail").attr("cara_pembayaran_id", $(this).attr("cara_pembayaran_id"));
            $("#btn-validasi-detail").attr("cara_pembayaran_id",$(this).attr("cara_pembayaran_id"));


            url = '<?= site_url() ?>/Transaksi/P_voucher_tagihan/ajax_get_detail';
            $.ajax({
                type: "GET",
                data: {
                    pt_id: $(this).attr('pt_id'),
                    cara_pembayaran_id: $(this).attr('cara_pembayaran_id'),
                    tanggal_awal : $("#tanggal-awal").val(),
                    tanggal_akhir : $("#tanggal-akhir").val()
                },
                url: url,
                dataType: "json",
                success: function(data) {

                    console.log(data);
                    $("#detail_pt").html(data.header.pt);
                    $("#detail_cara_bayar").html(data.header.cara_pembayaran);
                    $("#detail_coa_cara_bayar").html(data.header.coa_cara_pembayaran);

                    $("#tbody_detail").html("");
                    $.each(data.data, function(i, v) {
                        var str = "<tr id='" + v.item.substr(0, v.item.indexOf("-") - 1) + "'>";
                        str += "<td>" + v.kawasan + "</td>";
                        str += "<td class='blok'>" + v.blok + "</td>";
                        str += "<td class='no_unit' unit_id='"+v.unit_id+"'>" + v.no_unit + "</td>";
                        str += "<td class='pemilik'>" + v.pemilik + "</td>";
                        str += "<td>" + v.item + "</td>";
                        str += "<td class='coa_item'>" + v.coa_item + "</td>";
                        str += "<td class='nilai_item text-right'>" + formatNumber(v.nilai_item) + "</td>";
                        str += "<td>" + v.tgl_bayar + "</td>";
                        str += "<td>" + v.periode_tagihan + "</td>";
                        str += "<td class='status_row'>" +
                            "<div style='width:100%;height:100%' class='col-md-offset-4 lds-double-ring'></div>" +
                            "</td>";
                        str += "<td class='pesan_row'></td>";
                        str += "</tr>";
                        console.log(str);
                        $("#tbody_detail").append(str);

                    });
                    $("#detail_total_nilai_item").html("Rp. " + formatNumber(data.header.total));
                    $("#detail_total_nilai_item").attr('val',data.header.total);
                    $("#btn-validasi-detail").show();
                    $("#btn-validasi-detail").attr('disabled',false);


                }
            });

        });
    });
</script>