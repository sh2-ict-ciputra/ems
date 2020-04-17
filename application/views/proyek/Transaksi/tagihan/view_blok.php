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
		<button class="btn btn-warning" onClick="window.location.href='<?=substr(current_url(),0,strrpos(current_url(),"/"))?>/kawasan'">
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
<div class="x_conte	nt">

	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/Transaksi/P_transaksi_generate_bill/save" autocomplete="off">

		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Kawasan</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<select name="kawasan" required="" id="kawasan" class="form-control select2" placeholder="-- Pilih Kawasan --">
						<option value="" disabled selected>-- Pilih Kawasan --</option>
						<?php
						foreach ($kawasan as $v) {
							echo ("<option value='$v->id'>$v->kawasan_code - $v->kawasan_name</option>");
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

				<table id="table_blok" class="table table-striped jambo_table bulk_action">
					<thead>
						<tr>
							<th>No</th>
							<th class='table-Blok'>Blok</th>
							<th>Total Unit</th>
							<th class='text-right'>Tot. Tunggakan ( Rp )</th>
							<th class='text-right'>Denda + Bunga ( Rp )</th>
							<th class='text-right'>Penalti ( Rp )</th>
							<th class='text-right'>Tagihan Bulan ( Rp )</th>
							<th class="text-right">Total Tagihan ( Rp. )</th>
							<th>View Unit</th>
						</tr>
					</thead>
					<tbody id="tbody_unit">

					</tbody>
					<thead id="tfoot_unit">
						<tr>
							<th colspan="2">Total Tagihan Kawasan</th>
							<th id="total_unit" class='text-right'>0</th>
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
	var table_blok = $("#table_blok");
	var table_blok_dt = table_blok.dataTable({
		order: [
			[1, "asc"]
		],
		columnDefs: [{
			orderable: !1,
			targets: [1]
		}]
	});
	table_blok.on("draw.dt", function() {
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
		$("#btn-load-blok").click(function() {
			var total_tagihan = 0;
			if ($("#kawasan").val() == null) {
				$('#kawasan').next().find('.select2-selection').addClass('has-error');
			} else {
				$('#kawasan').next().find('.select2-selection').removeClass('has-error');
			}
			if ($("#periode").val() == "") {
				$('#periode').addClass('has-error');
			} else {
				$('#periode').removeClass('has-error');
			}
			if ($("#kawasan").val() != null) {
				$.ajax({
					type: "GET",
					data: {
						id: $("#kawasan").val(),
						periode: $("#periode").val(),
					},
					url: "<?= site_url() ?>/Transaksi/P_tagihan/ajax_get_blok2",
					dataType: "json",
					success: function(data) {
						total_tagihan = 0;
						console.log(data);
						table_blok_dt.fnDestroy();
						$("#tbody_unit").html("");
						total_total_tagihan = 0;
						total_tunggakan 	= 0;
						total_unit			= 0;
						total_denda 		= 0;
						total_penalty 		= 0;
						total_tagihan  		= 0;
					for (var i = 0; i < data.length; i++) {
							
							total_total_tagihan += parseInt(data[i].total);
							total_tunggakan += parseInt(data[i].tunggakan);
							total_unit += parseInt(data[i].unit);
							total_denda += parseInt(data[i].denda);
							total_penalty += parseInt(data[i].penalti);
							total_tagihan += parseInt(data[i].tagihan);

							$("#tbody_unit").append(
								"<tr class='even pointer'>" +
								"<td>" + (i + 1) + "</td>" +
								"<td class='text-right'>" + data[i].blok + "</td>" +
								"<td class='text-right'>" + formatC(data[i].unit)+"</td>" +
								"<td class='text-right'>" + formatC(data[i].tunggakan)+"</td>" +
								"<td class='text-right'>" + formatC(data[i].denda)+"</td>" +
								"<td class='text-right'>" + formatC(0) +"</td>" +
								"<td class='text-right'>" + formatC(data[i].tagihan) +"</td>" +
								"<td class='text-right'>" + formatC(data[i].total) + "</td>" +
								"<td class='col-md-1'><a href='<?= site_url(); ?>/Transaksi/P_tagihan/unit?id=" + data[i].blok_id + "&periode="+$("#periode").val()+"' class='save-row btn btn-primary' unit_id='" + data[i].id + "'><i class='fa fa-book'></i></a></td>" +
								"</tr>");
								
						}
						table_blok.dataTable({
							order: [
								[1, "asc"]
							]
						});
						if ($("#kawasan").val() != 'all')
							$(".table-kawasan").hide()
						else
							$(".table-kawasan").show()

							// <th id="total_unit" class='text-right'>0</th>
							// <th id="total_tunggakan" class='text-right'>0</th>
							// <th id="total_denda" class='text-right'>0</th>
							// <th id="total_penalty" class='text-right'>0</th>
							// <th id="total_tagihan" class='text-right'>0</th>
							// <th id="total_total_tagihan" class='text-right'>0</th>
						$("#total_unit").html(formatC(total_unit));

						$("#total_tunggakan").html(formatC(total_tunggakan));
						$("#total_denda").html(formatC(total_denda));
						$("#total_penalty").html(formatC(total_penalty));
						$("#total_tagihan").html(formatC(total_tagihan));
						$("#total_total_tagihan").html(formatC(total_total_tagihan));
					}
				});
			}
		});

		$('.datetimepicker_month').datetimepicker({
			viewMode: 'years',
			format: 'MM/YYYY'
		});
		$("#kawasan").change(function() {
			if ($("#kawasan").val() == null) {
				$('#kawasan').next().find('.select2-selection').addClass('has-error');
			} else {
				$('#kawasan').next().find('.select2-selection').removeClass('has-error');
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
		if (<?= $get_kawasan_id ?> != 0) {
			$("#kawasan").val(<?= $get_kawasan_id ?>).trigger("change");
			if(<?= $get_periode ?>!=0){
				$("#periode").val("<?= $get_periode ?>");	
			}
			if ($("#kawasan").val() && $("#periode").val()) {
				$("#btn-load-blok").trigger("click");
			}
		}
	});
</script>