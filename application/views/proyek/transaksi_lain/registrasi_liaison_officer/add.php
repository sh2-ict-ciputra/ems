<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>


<!-- switchery -->
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>


<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/p_registrasi_liaison_officer'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/p_registrasi_liaison_officer/add'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <form id="form" data-parsley-validate="" enctype="multipart/form-data" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url() ?>/transaksi_lain/p_registrasi_liaison_officer/save">


        <div class="col-md-6">

        </div>
        <div class="clear-fix"></div>
        <div class="row" style="margin-top: 35px;">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pilih Unit</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select required id="unit_id" name="unit_id" class="form-control select2">
                                <option value="" selected disabled=>--Pilih Unit--</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Bangunan </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <span class="form-control-feedback right" aria-hidden="true">(m<sup>2</sup>)</span>
                            <input id="luas_bangunan" class="form-control text-right" placeholder="Luas Bangunan" disabled style="padding-right: 50px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Tanah </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <span class="form-control-feedback right" aria-hidden="true">(m<sup>2</sup>)</span>
                            <input id="luas_tanah" class="form-control text-right" placeholder="Luas Tanah" disabled style="padding-right: 50px;">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tagihan Outstanding</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                            <input id="outstading" class="form-control text-right" style="padding-left: 50px;" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input id="mobilephone" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input placeholder="Email" id="email" class="form-control" disabled>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <br>
        <div class="clearfix"></div>
        <h4 id="label_transaksi">Paket Liaison Officer</h4>
        <hr>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Pilih Paket</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select id="paket_id" name="paket_id" class="form-control select2" require>
                        <option value="" selected disabled=>--Pilih Paket--</option>
                    </select>
                </div>
            </div>
            <div class="form-group ">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Follow Up</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input id="follow_up" class="form-control" disabled>
                </div>
            </div>
            <div class="form-group ">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Uang Jaminan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                    <input id="uang_jaminan" class="form-control text-right currency" name="uang_jaminan" style="padding-left: 50px;" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group ">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nilai LOI</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                    <input id="nilai" class="form-control text-right currency" name="nilai_loi" style="padding-left: 50px;" readonly>
                </div>
            </div>
            <div class="form-group ">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nilai Admin</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                    <input id="nilai_admin" class="form-control text-right currency" name="nilai_admin" style="padding-left: 50px;" readonly>
                </div>
            </div>
            <div class="form-group ">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Nilai</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                    <input id="total_nilai_paket" class="form-control text-right" style="padding-left: 50px;" disabled>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <h4 id="label_transaksi">Transaksi</h4>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group  start ">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tgl Rencana Mulai</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class='input-group date '>
                                <input type="text" class="form-control" name="tgl_rencana_mulai" id="tgl_rencana_mulai" placeholder="Masukkan Tanggal Rencana Mulai" value='<?= date('d-m-Y') ?>'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group  start ">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tgl Rencana Selesai</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class='input-group date '>
                                <input type="text" class="form-control" name="tgl_rencana_selesai" id="tgl_rencana_selesai" placeholder="Masukkan Tanggal Rencana Selesai" value='<?= date('d-m-Y') ?>'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea id='keterangan2' class="form-control" name="keterangan" placeholder="Masukkan keterangan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tgl Expired</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class='input-group date '>
                                <input type="text" class="form-control" name="tgl_exp" id="tgl_exp" placeholder="Masukkan Tanggal Expired" value='<?= date('d-m-Y') ?>'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nilai Diskon</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                            <input id="nilai_diskon" class="form-control text-right" name="nilai_diskon" style="padding-left: 50px;" readonly>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Bayar</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                            <input id="total" class="form-control text-right" name="total" style="padding-left: 50px;" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xs-12">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <button type="submit" class="btn btn-success" id="submit">Submit</button>
            </div>
        </div>


    </form>
</div>
</div>

<script type="text/javascript">
    function currency(input) {
        var input = input.toString().replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt(input, 10) : 0;
        return (input === 0) ? "" : input.toLocaleString("en-US");
    }

    function formatNumber(data) {
        data = data.toString().replace(/,/g, "");
        data = parseInt(data) ? parseInt(data) : 0;
        return data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    function unformatNumber(data) {
        return data.toString().replace(/,/g, "");
    }

    function getDate() {
        var jumhari = $('#jumlah_hari_aktifasi').val();

        var awal = $('#tgl_rencana_mulai').val();

        awal = awal.substr(3, 2) + "-" + awal.substr(0, 2) + "-" + awal.substr(6, 4)

        var pasang = new Date(awal);
        var dd = String(pasang.getDate()).padStart(2, '0');
        var mm = String(pasang.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = pasang.getFullYear();


        var pemasangan = yyyy + '-' + mm + '-' + dd;

        var startdate = new Date(pemasangan);
        var newdate = new Date();
        newdate.setDate(startdate.getDate() + parseInt(jumhari));
        var dd = newdate.getDate();
        var mm = newdate.getMonth() + 1;
        var y = newdate.getFullYear();


        var someFormattedDate = ("0" + dd).slice(-2) + '-' + ("0" + mm).slice(-2) + '-' + y;

        $('#tgl_rencana_selesai').val(someFormattedDate);
    }

    $(function() {
        $("form").submit(function(e) {
			$.each($(".currency"), function(k, v) {
				$(".currency").eq(k).val(unformatNumber(v.value))
			});
			e.preventDefault();
			$.ajax({
				type: "POST",
				data: $("form").serialize(),
				url: "<?= site_url('transaksi_lain/p_registrasi_liaison_officer/ajax_save') ?>",
				dataType: "json",
				success: function(data) {
					if (data.status == 1) {
						notif('Sukses', data.message, 'success')
						setTimeout(function() {
							window.location.href = '<?= site_url('P_master_paket_loi') ?>'
						}, 2 * 1000);
					} else
						notif('Gagal', data.message, 'danger')
				}
			});
			$.each($(".currency"), function(k, v) {
				$(".currency").eq(k).val(formatNumber(v.value))
			});
		})
        $("#unit_id").select2({
            width: 'resolve',
            minimumInputLength: 1,
            placeholder: 'Kawasan - Blok - Unit - Pemilik',
            ajax: {
                type: "GET",
                dataType: "json",
                url: "<?= site_url("transaksi_lain/p_registrasi_liaison_officer/get_ajax_unit") ?>",
                data: function(params) {
                    return {
                        data: params.term
                    }
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });
        $("#unit_id").change(function() {
            $.ajax({
                type: "GET",
                data: {
                    id: $(this).val()
                },
                url: "<?= site_url("transaksi_lain/p_registrasi_liaison_officer/get_ajax_unit_detail") ?>",
                dataType: "json",
                success: function(data) {
                    $("#outstading").val(formatNumber(data.outstading));
                    $("#luas_bangunan").val(data.luas_bangunan);
                    $("#luas_tanah").val(data.luas_tanah);
                    $("#mobilephone").val(data.mobilephone);
                    $("#email").val(data.email);

                }
            })
        })
        $("#paket_id").select2({
            width: 'resolve',
            minimumInputLength: 1,
            placeholder: 'Nama Paket - Kode Paket, Note: isi space untuk melihat semua',
            ajax: {
                type: "GET",
                dataType: "json",
                url: "<?= site_url("transaksi_lain/p_registrasi_liaison_officer/get_ajax_paket") ?>",
                data: function(params) {
                    return {
                        data: params.term
                    }
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });
        $("#paket_id").change(function() {
            $.ajax({
                type: "GET",
                data: {
                    id: $(this).val()
                },
                url: "<?= site_url("transaksi_lain/p_registrasi_liaison_officer/get_ajax_paket_detail") ?>",
                dataType: "json",
                success: function(data) {
                    $("#follow_up").val(data.follow_up);
                    $("#uang_jaminan").val(formatNumber(data.uang_jaminan));
                    $("#nilai").val(formatNumber(data.nilai));
                    $("#nilai_admin").val(formatNumber(data.nilai_admin));
                    $("#total_nilai_paket").val(formatNumber(data.uang_jaminan+data.nilai+data.nilai_admin));
                    $("#nilai_diskon").val(formatNumber(0));
                    $("#total").val(formatNumber((data.uang_jaminan+data.nilai+data.nilai_admin)-0));
                }
            })
        })
        $('#tgl_rencana_mulai').datetimepicker({
            viewMode: 'days',
            format: 'DD-MM-YYYY',
            minDate: "<?= date('Y-m-d') ?>"
        });
        $('#tgl_rencana_selesai').datetimepicker({
            viewMode: 'days',
            format: 'DD-MM-YYYY',
            minDate: "<?= date('Y-m-d') ?>"
        });
        $('#tgl_exp').datetimepicker({
            viewMode: 'days',
            format: 'DD-MM-YYYY',
            minDate: "<?= date('Y-m-d') ?>"
        });
        $("#tgl_rencana_mulai").on('dp.change', function(e) {
            $("#tgl_rencana_selesai").data("DateTimePicker").options({
                minDate: e.date
            })
        })
        $("#tgl_rencana_selesai").on('dp.change', function(e) {
            $("#tgl_exp").data("DateTimePicker").options({
                minDate: e.date
            })
        })
    });


    $("#status_dokumen").change(function() {
        if ($("#status_dokumen").is(':checked')) {
            $("#nilai_jaminan").attr('disabled', false);
        } else {
            $("#nilai_jaminan").attr('disabled', 'disabled');
        }
    })
</script>