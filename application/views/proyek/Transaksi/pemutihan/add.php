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
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<style>
	.invalid {
		background-color: lightpink;
	}

	.has-error {
		border: 1px solid rgb(185, 74, 72) !important;
	}

	.text-right {
		text-align: right;
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
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/Transaksi/P_transaksi_generate_bill/save" autocomplete="off">
		<div class="col-lg-4 col-md-4 col-sm-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Kawasan</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<select name="kawasan" required="" id="kawasan" class="form-control select2" placeholder="-- Pilih Kawasan --">
						<option value="" disabled selected>-- Pilih Kawasan --</option>
						<option value="all">-- Semua Kawasan --</option>
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
						<option value="all">-- Semua Blok --</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Periode Pemutihan</label>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class='input-group date datetimepicker'>
						<input id="periode_awal" type="text" class="form-control datetimepicker" name="periode_awal" placeholder="Periode Awal">
					</div>
				</div>
				<div class="col-lg-1 col-md-1">
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class='input-group date datetimepicker'>
						<input id="periode_akhir" type="text" class="form-control datetimepicker" name="periode_akhir" placeholder="Periode Akhir">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Lama Berlakunya Pemutihan<br>(DD/MM/YYYY)</label>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class='input-group date datetimepickerFull'>
						<input id="lama_awal" type="text" class="form-control datetimepickerFull" name="masa_awal" placeholder="Awal">
					</div>
				</div>
				<div class="col-lg-1 col-md-1">
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class='input-group date datetimepickerFull'>
						<input id="lama_akhir" type="text" class="form-control datetimepickerFull" name="masa_akhir" placeholder="Akhir">
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Service</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select id="metode_tagihan" name="service_jenis[]" class="form-control multipleSelect js-example-basic-multiple" multiple="multiple" placeholder="-- Masukkan Metode Penagihan --">
						<option value=""></option>
						<?php foreach ($service as $v) : ?>
							<option value='<?= $v->id ?>'><?= $v->name ?></option>";
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Keterangan<br></label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<textarea class="form-control" name="description" id="" rows="3"></textarea>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Kode Dokumen</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<input id="kode" type="text" name="kode" class="form-control text-right" placeholder="DOK/PMTHN//" value="<?= $kode ?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Jumlah Nilai Pokok<br>(Rp)</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<input id="jumlah_nilai_pokok" type="text" class="form-control text-right" placeholder="Jumlah Nilai Pokok" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Jumlah Denda<br>(Rp)</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<input id="jumlah_denda" type="text" class="form-control text-right" placeholder="Jumlah Denda" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Jumlah Total<br>(Rp)</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<input id="jumlah_total" type="text" class="form-control text-right" placeholder="Jumlah Total" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">File Pendukung</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<input id="file" type="file" class="form-control" name="file">
				</div>
			</div>

		</div>
		<div class="col-lg-4 col-md-4 col-sm-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Nilai Pemutihan<br>Pokok</label>
				<div class="col-lg-2 col-md-2 col-sm-12">
					<select class="form-control" id="tipe_pemutihan_pokok" style="padding:0" name="nilai_tagihan_type">
						<option value="0">Rp</option>
						<option value="1">%</option>
					</select>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-12">
					<input id="nilai_pemutihan_pokok" type="text" class="form-control text-right" name="nilai_tagihan" placeholder="Pemutihan Nilai Pokok">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Nilai Pemutihan<br>Denda</label>
				<div class="col-lg-2 col-md-2 col-sm-12">
					<select class="form-control" id="tipe_pemutihan_denda" style="padding:0" name="nilai_denda_type">
						<option value="0">Rp</option>
						<option value="1">%</option>
					</select>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-12">
					<input id="nilai_pemutihan_denda" type="text" class="form-control text-right" name="nilai_denda" placeholder="Pemutihan Denda">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Jumlah Nilai Pokok yang di putihkan<br></label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<input id="nilai_pokok_diputihkan" name="perkiraan_pemutihan_nilai_tagihan" type="text" class="form-control text-right" placeholder="Jumlah Pemutihan Nilai Pokok" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Jumlah Denda yang di Putihkan<br></label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<input id="denda_diputihkan" type="text" name="perkiraan_pemutihan_nilai_denda" class="form-control text-right" placeholder="Jumlah Pemutihan Denda" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Jumlah yang di Putihkan<br></label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<input id="diputihkan" type="text" class="form-control text-right" name="perkiraan_pemutihan_total" placeholder="Jumlah Pemutihan" readonly>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="form-group" style="margin-top:20px">
				<div class="center-margin">
					<!-- <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button> -->
					<a id="btn-load-unit" class="btn btn-primary">Load Unit</a>
					<a id="btn-load-save" class="btn btn-success">Buat Pemutihan</a>
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
			<div id="div_table" class="col-md-12 card-box table-responsive">
				<table id="table_unit" class="table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th class="col-lg-1 col-md-1"><input type="checkbox" id="check-all" class="flat"></th>
							<th class='col-lg-2 col-md-2 table-kawasan'>Kawasan</th>
							<th class='col-lg-1 col-lg-1 table-blok'>Blok</th>
							<th class="col-lg-1 col-lg-1">Unit</th>
							<th class="col-lg-2 col-lg-2">Pemilik</th>
							<th class="col-lg-2 col-lg-2">Nilai Pokok</th>
							<th class="col-lg-1 col-lg-1">Denda</th>
							<th class="col-lg-2 col-lg-2">Total</th>
						</tr>
					</thead>
					<tbody id="tbody_unit">

					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>

<!-- jQuery -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
	var table_unit = $("#table_unit");
	var table_unit_dt = table_unit.dataTable({
		"bAutoWidth": false,
		"paging": false,
		order: [
			[1, "asc"]
		],
		columnDefs: [{
			orderable: !1,
			targets: [1]
		}]
	});
	table_unit.on("draw.dt", function() {
		$("checkbox input").iCheck({
			checkboxClass: "icheckbox_flat-green"
		})
	})

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
	$(".select2").select2();

	function set_jumlah() {
		var total_nilai_pokok = 0;
		var total_denda = 0;
		var total = 0;
		for (var i = 0; i < $(".trow").length; i++) {
			if ($(".trow").eq(i).children().children().children().is(":checked")) {
				total_nilai_pokok += parseInt(unformatNumber($(".trow").eq(i).children().eq(5).html()));
				total_denda += parseInt(unformatNumber($(".trow").eq(i).children().eq(6).html()));
				total += parseInt(unformatNumber($(".trow").eq(i).children().eq(7).html()));
			}
		}
		$("#jumlah_nilai_pokok").val(formatNumber(total_nilai_pokok));
		$("#jumlah_denda").val(formatNumber(total_denda));
		$("#jumlah_total").val(formatNumber(total));
	}

	function set_prediksi() {
		var total_nilai_pokok = 0;
		var total_denda = 0;
		for (var i = 0; i < $(".trow").length; i++) {
			if ($(".trow").eq(i).children().children().children().is(":checked")) {
				if ($("#tipe_pemutihan_pokok").val() == 0) {
					var nilai_pemutihan_pokok = parseInt(unformatNumber($("#nilai_pemutihan_pokok").val()));
					if (nilai_pemutihan_pokok > parseInt(unformatNumber($(".trow").eq(i).children().eq(5).html()))) {
						total_nilai_pokok += parseInt(unformatNumber($(".trow").eq(i).children().eq(5).html()));
					} else {
						total_nilai_pokok += nilai_pemutihan_pokok;
					}
				} else {
					var nilai_pemutihan_pokok = parseInt(unformatNumber($("#nilai_pemutihan_pokok").val()));
					total_nilai_pokok += (parseInt(unformatNumber($(".trow").eq(i).children().eq(5).html())) * nilai_pemutihan_pokok / 100);
				}
				if ($("#tipe_pemutihan_denda").val() == 0) {
					var nilai_pemutihan_denda = parseInt(unformatNumber($("#nilai_pemutihan_denda").val()));
					if (nilai_pemutihan_denda > parseInt(unformatNumber($(".trow").eq(i).children().eq(6).html()))) {
						total_denda += parseInt(unformatNumber($(".trow").eq(i).children().eq(6).html()));
					} else {
						total_denda += nilai_pemutihan_denda;
					}
				} else {
					var nilai_pemutihan_denda = parseInt(unformatNumber($("#nilai_pemutihan_denda").val()));
					total_denda += (parseInt(unformatNumber($(".trow").eq(i).children().eq(6).html())) * nilai_pemutihan_denda / 100);

				}
			}
		}
		$("#nilai_pokok_diputihkan").val(formatNumber(total_nilai_pokok));
		$("#denda_diputihkan").val(formatNumber(total_denda));
		$("#diputihkan").val(formatNumber(total_nilai_pokok + total_denda));
	}

	function notif(title, text, type) {
		new PNotify({
			title: title,
			text: text,
			type: type,
			styling: 'bootstrap3'
		});
	}
	$(function() {
		$("#nilai_pemutihan_pokok").keyup(function() {
			$("#nilai_pemutihan_pokok").val(formatNumber($("#nilai_pemutihan_pokok").val()));
			set_prediksi();
		});
		$("#nilai_pemutihan_denda").keyup(function() {
			$("#nilai_pemutihan_denda").val(formatNumber($("#nilai_pemutihan_denda").val()));

			set_prediksi();
		});

		$('#div_table').on('ifClicked', '#check-all', function(event) {
			if ($("#check-all").is(":checked")) flag = 'uncheck';
			else flag = 'check';
			$(".check-row").iCheck(flag);
			table_unit_dt.fnPageChange("first");
			set_prediksi();

		});
		$('#div_table').on('ifChanged', '.check-row', function(event) {
			set_jumlah();
			if (!$(this).is(":checked"))
				$("#check-all").iCheck("uncheck");
			set_prediksi();

		});
		$('.js-example-basic-multiple').select2({
			placeholder: '-- Pilih Service --',
			tags: true,
			tokenSeparators: [',', ' ']
		});
		var date = new Date();
		$("#periode").val(date.getMonth() + 1 + "/" + date.getFullYear());
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
			if ($("#kawasan").val() != null && $("#blok").val() != null) {
				$.ajax({
					type: "GET",
					data: {
						kawasan: $("#kawasan").val(),
						blok: $("#blok").val(),
						periode_awal: $("#periode_awal").val(),
						periode_akhir: $("#periode_akhir").val(),
						metode_tagihan: $("#metode_tagihan").val()
					},
					url: "<?= site_url() ?>/Transaksi/P_pemutihan/ajax_get_unit",
					dataType: "json",
					success: function(data) {
						console.log(data);
						table_unit_dt.fnDestroy();
						$("#tbody_unit").html("");

						for (var i = 0; i < data.length; i++) {
							if (data[i].total != 0) {
								$("#tbody_unit").append(
									"<tr class='trow even pointer'>" +
									"<td><input type='checkbox' class='check-row flat' val='" + data[i].unit_id + "'></td>" +
									"<td class='table-kawasan'>" + data[i].kawasan + "</td>" +
									"<td class='table-blok'>" + data[i].blok + "</td>" +
									"<td>" + data[i].no_unit + "</td>" +
									"<td>" + data[i].pemilik + "</td>" +
									"<td class='text-right trow_nilai_pokok'>" + formatNumber(data[i].nilai_pokok) + "</td>" +
									"<td class='text-right trow_denda'>" + formatNumber(data[i].denda) + "</td>" +
									"<td class='text-right trow_total'>" + formatNumber(data[i].total) + "</td>" +
									"</tr>");
							}
						}
						tableICheck();
						table_unit.dataTable({
							"bAutoWidth": false,
							"paging": false,
							order: [
								[1, "asc"]
							],
							columnDefs: [{
								orderable: !1,
								targets: [1]
							}]
						});

						set_jumlah();
						set_prediksi();

						$("#check-all").iCheck("uncheck");
					}
				});
			}

		});
		// $("#btn-load-save").click(function() {
		// 	console.log(table_unit_dt.$("input").serialize());
		// 	data = table_unit_dt.$("input").serialize() + "&source=" + $("#source").val();
		// 	$.ajax({
		// 		type: "POST",
		// 		data: data,
		// 		url: "<?= site_url() ?>/Transaksi/P_pemutihan/save",
		// 		dataType: "json",
		// 		success: function(data) {
		// 			console.log("hahaha");
		// 			console.log(data);
		// 			if (data.success) {
		// 				$("#btn-load").trigger("click");
		// 			}
		// 		}
		// 	});
		// });
		$("#btn-load-save").click(function() {
			var data2 = new FormData();

			$('input[type="file"]').each(function($i) {
				data2.append($(this).prop("id"), $(this)[0].files[0]);
			});

			// var other_data = $('#form_id').serializeArray();
			// $.each(other_data, function(key, input) {
			// 	data.append(input.name, input.value);
			// });
			// data = table_unit_dt.$("input").serialize() + "&source=" + $("#source").val();
			// console.log(data);
			data = $("#form").serialize();
			$.each($(".check-row"), function(k, v) {
				if ($(this).is(":checked")) {
					data = data + "&unit_id[]=" + ($(this).attr("val"));
				}
			})
			$.ajax({
				type: "POST",
				data: data2,
				cache: false,
				contentType: false,
				processData: false,
				url: "<?= site_url() ?>/Transaksi/P_pemutihan/save?" + data,
				dataType: "json",
				success: function(data) {
					if (data)
						notif('Sukses', 'Pemutihan Telah Berhasil Ditambah', 'success');
					else
						notif('Gagal', 'Pemutihan Gagal Ditambah', 'danger');
				}
			});
		});
		$("body").on("click", ".save-row", function() {
			console.log($(this));
			var meter = $(this).parent().parent().find('.meter-row').val();
			var periode = $(this).attr('periode');
			var unit_id = $(this).attr('unit_id');

			$.ajax({
				type: "GET",
				data: {
					meter: meter,
					periode: periode,
					unit_id: unit_id
				},
				url: "<?= site_url() ?>/Transaksi/P_transaksi_meter_air/ajax_save_meter",
				dataType: "json",
				success: function(data) {
					console.log(data);
				}
			});
		})

		$('.datetimepicker').datetimepicker({
			viewMode: 'years',
			format: 'MM/YYYY'
		});
		$('.datetimepickerFull').datetimepicker({
			viewMode: 'years',
			format: 'DD/MM/YYYY'
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
				url: "<?= site_url() ?>/Transaksi/P_transaksi_meter_air/ajax_get_blok",
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
	});
</script>