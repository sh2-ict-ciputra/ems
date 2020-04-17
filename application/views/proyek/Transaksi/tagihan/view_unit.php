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
</style>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?=substr(current_url(),0,strrpos(current_url(),"/"))."/blok?id=".$blok[0]->kawasan_id."&periode=".$get_periode?>'">
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
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Kawasan</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<select name="kawasan" required="" id="kawasan" class="form-control select2" placeholder="-- Pilih kawasan --">
						<option value="" disabled selected>-- Pilih Kawasan --</option>
						<?php
						foreach ($kawasan as $v) {
							if($blok[0]->kawasan_id == $v->id)
							echo ("<option value='$v->id' selected>$v->kawasan_code - $v->kawasan_name</option>");
							else
							echo ("<option value='$v->id' >$v->kawasan_code - $v->kawasan_name</option>");
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Blok</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<select name="blok" required="" id="blok" class="form-control select2" placeholder="-- Pilih Blok --">
						<option value="" disabled selected>-- Pilih Blok --</option>
						<?php
						foreach ($blok as $v) {
							echo ("<option value='$v->id'>$v->blok_code - $v->blok_name</option>");
						}
						?>
					</select>
				</div>
			</div>
		</div>
		
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Periode</label>
				<div class="col-lg-7 col-md-9 col-sm-12">
					<div class="input-group date datetimepicker_month">
						<input id="periode" type="text" class="form-control datetimepicker_month" name="bulan" placeholder="Bulan">
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
					<a id="btn-load-blok" class="btn btn-primary">Load Unit</a>
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
				<table id="table_unit" class="table table-striped jambo_table bulk_action">
					<thead>
						<tr>
							<th>No</th>
							<th>No Unit</th>
							<th>Type Unit</th>
							<th>Pemilik</th>
							<th class='text-right'>Tunggakan <br>( Bln )</th>
							<th class='text-right'>Tot. Tunggakan <br>( Rp )</th>
							<th class='text-right'>Denda + Bunga <br>( Rp )</th>
							<th class='text-right'>Penalti <br>( Rp )</th>
							<th class='text-right'>Tagihan Bulan <br>( Rp )</th>
							<th class='text-right'>Total <br>( Rp )</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody id="tbody_unit">

					</tbody>
					<thead id="tfoot_unit">
						<tr>
							<th colspan="4">Total</th>
							<th id="total_tunggakan_bulan" class='text-right'>0</th>
							<th id="total_tunggakan" class='text-right'>0</th>
							<th id="total_denda" class='text-right'>0</th>
							<th id="total_penalty" class='text-right'>0</th>
							<th id="total_tagihan" class='text-right'>0</th>
							<th id="total_total_tagihan" class='text-right'>0</th>

							<th></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</form>
</div>

<!-- jQuery -->
<script type="text/javascript" src="<?= base_url(); ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
	var table_unit = $("#table_unit");
	var table_unit_dt = table_unit.dataTable({
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
	$(".select2").select2();
	$(function() {
		$('.datetimepicker_month').datetimepicker({
			viewMode: 'years',
			format: 'MM/YYYY'
		});
		$("#btn-load-blok").click(function() {
			if ($("#blok").val() == null) {
				$('#blok').next().find('.select2-selection').addClass('has-error');
			} else {
				$('#blok').next().find('.select2-selection').removeClass('has-error');
			}
			if ($("#blok").val() != null) {
				$.ajax({
					type: "GET",
					data: {
						id: $("#blok").val(),
						periode: $("#periode").val()
					},
					url: "<?= site_url() ?>/Transaksi/P_tagihan/ajax_get_unit2",
					dataType: "json",
					success: function(data) {
						var total_total_tagihan = 0;
						var total_tunggakan_bulan = 0;
						var total_tunggakan = 0;
						var total_denda = 0;
						var total_penalty = 0;
						var total_tagihan = 0;
						var total_total_tagihan = 0;
						var total_total = 0;
						console.log(data);
						table_unit_dt.fnDestroy();
						$("#tbody_unit").html("");

						for (var i = 0; i < data.length; i++) {
							total = parseInt(data[i].old_total_tunggakan) + parseInt(data[i].old_denda) + parseInt(data[i].now_penalti) + parseInt(data[i].now_tagihan);
							total_total_tagihan += parseInt(total);
							total_tunggakan += parseInt(data[i].tunggakan);
							total_tunggakan_bulan += parseInt(data[i].tunggakan_bulan);
							total_denda += parseInt(data[i].denda);
							total_penalty += parseInt(data[i].penalti);
							total_tagihan += parseInt(data[i].tagihan);
							total_total += parseInt(data[i].total);
							$("#tbody_unit").append(
								"<tr class='even pointer'>" +
								"<td>" + (i + 1) + "</td>" +
								"<td>" + data[i].no_unit + "</td>" +
								"<td>" + "</td>" +
								"<td>" + data[i].pemilik + "</td>" +
								"<td class='text-right'>" + formatC(data[i].tunggakan_bulan) + "</td>" +
								"<td class='text-right'>" + formatC(data[i].tunggakan) + "</td>" +
								"<td class='text-right'>" + formatC(data[i].denda) + "</td>" +
								"<td class='text-right'>" + formatC(0) + "</td>" +
								"<td class='text-right'>" + formatC(data[i].tagihan) + "</td>" +
								"<td class='text-right'>" + formatC(data[i].total) + "</td>" +
								"<td class='col-md-1'><a href='<?= site_url(); ?>/Transaksi/P_tagihan/unit_detail?id=" + data[i].unit_id + "&periode=<?= $get_periode ?>' class='save-row btn btn-primary' unit_id='" + data[i].id + "'>                                        <i class='fa fa-book'></i></a></td>" +
								"</tr>");
						}
						table_unit.dataTable({
							order: [
								[1, "asc"]
							]
						});
						if ($("#blok").val() != 'all')
							$(".table-blok").hide()
						else
							$(".table-blok").show()

						$("#total_total_tagihan").html(formatC(total_total_tagihan));
						$("#total_tunggakan_bulan").html(formatC(total_tunggakan_bulan));
						$("#total_tunggakan").html(formatC(total_tunggakan));
						$("#total_denda").html(formatC(total_denda));
						$("#total_penalty").html(formatC(total_penalty));
						$("#total_tagihan").html(formatC(total_tagihan));
						$("#total_total_tagihan").html(formatC(total_total));
					}
				});
			}
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
			url = '<?= site_url(); ?>/P_transaksi_meter_air/getInfoUnit';
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


		if (<?= $get_blok_id ?> != 0) {
			$("#blok").val(<?= $get_blok_id ?>).trigger("change");
			if (<?= $get_periode ?> != 0) {
				$("#periode").val("<?= $get_periode ?>");
			}
			if ($("#blok").val() && $("#periode").val()) {
				$("#btn-load-blok").trigger("click");
			}
		}
	});
</script>