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
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Kawasan</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<input class="form-control" id="kawasan" value="<?=$unit?$unit->kawasan:''?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Blok</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<input class="form-control" id="blok" value="<?=$unit?$unit->blok:''?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">No. Unit</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<input class="form-control" id="no_unit" value="<?=$unit?$unit->no_unit:''?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Pemilik</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<input class="form-control" id="pemilik" value="<?=$unit?$unit->pemilik:''?>" readonly>
				</div>
			</div>
		</div>

		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Periode<br> ( DD-MM-YYYY )</label>
				<div class="col-lg-7 col-md-9 col-sm-12 col-xs-12">
					<input class="form-control" id="pemilik" value="<?=$periode?$periode:''?>" readonly>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Tanggal Serah Terima <br> ( DD-MM-YYY )</label>
				<div class="col-lg-7 col-md-9 col-sm-12 col-xs-12">
					<input class="form-control" id="pemilik" value="<?=substr($unit->tgl_st, 8, 2) . substr($unit->tgl_st, 4,4) . substr($unit->tgl_st, 0, 4)?>" readonly>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12" hidden>

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
							<th class=''>No.</th>
							<th class=''>Service</th>
							<th class='text-right'>Tunggakan ( Bln )</th>
							<th class='text-right'>Tunggakan ( Rp )</th>
							<th class='text-right'>Denda + Bunga ( Rp )</th>
							<th class='text-right'>Penalti ( Rp )</th>
							<th class='text-right'>Tagihan Bulan ( Rp )</th>
							<th class='text-right'>Total ( Rp )</th>
						</tr>
					</thead>
					<tbody id="tbody_unit">
						<?php
							$no = 1;
						?>
						<?php 
							$sum_tunggakan_bln	= $tagihan_lingkungan->tunggakan_bulan; 
							$sum_tunggakan		= $tagihan_lingkungan->tunggakan; 
							$sum_denda			= $tagihan_lingkungan->denda; 
							$sum_total_tagihan	= $tagihan_lingkungan->tagihan; 
							$flag 				= $tagihan_lingkungan->denda+$tagihan_lingkungan->tagihan+$tagihan_lingkungan->tunggakan;
							$sum_total			= $flag;
							if($flag != 0): 
						?>
						<tr>
							<td><?=$no++?></td>
							<td><?=$tagihan_lingkungan->service?></td>
							<td class='text-right'><?=$tagihan_lingkungan->tunggakan_bulan?></td>
							<td class='text-right'><?=number_format($tagihan_lingkungan->tunggakan)?></td>
							<td class='text-right'><?=number_format($tagihan_lingkungan->denda)?></td>
							<td class='text-right'><?=0?></td>
							<td class='text-right'><?=number_format($tagihan_lingkungan->tagihan)?></td>
							<td class='text-right'><?=number_format($tagihan_lingkungan->denda + $tagihan_lingkungan->tagihan+$tagihan_lingkungan->tunggakan)?></td>
							<?php
								
							?>
						</tr>
						<?php endif;?>
						<?php 
							$sum_tunggakan_bln	+= $tagihan_air->tunggakan_bulan; 
							$sum_tunggakan		+= $tagihan_air->tunggakan; 
							$sum_denda			+= $tagihan_air->denda; 
							$sum_total_tagihan	+= $tagihan_air->tagihan; 
							$flag 				=  $tagihan_air->denda+$tagihan_air->tagihan+$tagihan_air->tunggakan;
							$sum_total			+= $flag;
							if($flag != 0): 
						?>
						<tr>
							<td><?=$no++?></td>
							<td><?=$tagihan_air->service?></td>
							<td class='text-right'><?=$tagihan_air->tunggakan_bulan?></td>
							<td class='text-right'><?=number_format($tagihan_air->tunggakan)?></td>
							<td class='text-right'><?=number_format($tagihan_air->denda)?></td>
							<td class='text-right'><?=0?></td>
							<td class='text-right'><?=number_format($tagihan_air->tagihan)?></td>
							<td class='text-right'><?=number_format($tagihan_air->denda+$tagihan_air->tagihan+$tagihan_air->tunggakan)?></td>
						</tr>
						<?php endif;?>
					</tbody>
					<thead id="tfoot_unit">
						<tr>
							<th class="text-right" colspan=2>Total</th>
							<th id="total_tunggakan_bulan" class='text-right'></th>
							<th id="total_tunggakan" class='text-right'><?=number_format($sum_tunggakan)?></th>
							<th id="total_denda" class='text-right'><?=number_format($sum_denda)?></th>
							<th id="total_penalty" class='text-right'>0</th>
							<th id="total_tagihan" class='text-right'><?=number_format($sum_total_tagihan)?></th>
							<th id="total_total_tagihan" class='text-right'><?=number_format($sum_total)?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</form>
</div>

<!-- jQuery -->
<script type="text/javascript" src="http://localhost/emsNew/vendors/datatables.net/js/jquery.dataTables.min.js"></script>

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
					url: "<?= site_url() ?>/Transaksi/P_belum_tertagih/ajax_get_unit_detail",
					dataType: "json",
					success: function(data) {
						var total_total_tagihan = 0;
						var total_tunggakan_bulan = 0;
						var total_tunggakan = 0;
						var total_denda = 0;
						var total_penalty = 0;
						var total_tagihan = 0;
						var total_total_tagihan = 0;
						console.log(data);
						table_unit_dt.fnDestroy();
						$("#tbody_unit").html("");

						for (var i = 0; i < data.length; i++) {
							total = parseInt(data[i].old_total_tunggakan) + parseInt(data[i].old_denda) + parseInt(data[i].now_penalti) + parseInt(data[i].now_tagihan);
							total_total_tagihan += parseInt(total);
							total_tunggakan_bulan += parseInt(data[i].old_tunggakan);
							total_tunggakan += parseInt(data[i].old_total_tunggakan);
						 	total_denda += parseInt(data[i].old_denda);
							total_penalty += parseInt(data[i].now_penalti);
							total_tagihan += parseInt(data[i].now_tagihan);

							$("#tbody_unit").append(
								"<tr class='even pointer'>" +
								"<td>" + (i + 1) + "</td>" +
								"<td>" + data[i].unit_no + "</td>" +
								"<td>"  + "</td>" +
								"<td>" + data[i].pemilik + "</td>" +
								"<td class='text-right'>" + formatC(data[i].old_tunggakan) + "</td>" +
								"<td class='text-right'>" + formatC(data[i].old_total_tunggakan) + "</td>" +
								"<td class='text-right'>" + formatC(data[i].old_denda) + "</td>" +
								"<td class='text-right'>" + formatC(data[i].now_penalti) + "</td>" +
								"<td class='text-right'>" + formatC(data[i].now_tagihan) + "</td>" +
								"<td class='text-right'>" + formatC(total) + "</td>" +
								"<td class='col-md-1'><a href='<?= site_url(); ?>/Transaksi/P_unit/?unit_id=" + data[i].unit_id + "' class='save-row btn btn-primary' unit_id='" + data[i].id + "'>                                        <i class='fa fa-book'></i></a></td>" +
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
						$("#total_total_tagihan").html(formatC(total_total_tagihan));
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
		
		
		if (<?= $this->input->get("id") ?> > 0) {
			$("#blok").val(<?= $get_blok_id ?>).trigger("change");
			if(<?= $get_periode ?>!=0){
				$("#periode").val("<?= $get_periode ?>");	
			}
			if ($("#blok").val() && $("#periode").val()) {
				$("#btn-load-blok").trigger("click");
			}
		}
	});
</script>