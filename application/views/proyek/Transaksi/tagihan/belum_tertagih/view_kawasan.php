<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="http://localhost/emsNew/index.php/Transaksi/P_transaksi_generate_bill/save" autocomplete="off">

		<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:20px">
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
					<a id="btn-load-kawasan" class="btn btn-primary">Load Unit</a>
				</div>
			</div>
		</div>
	</form>
	<div class="row">
		<div class="col-sm-12">
			<div class="card-box table-responsive">
				<table class="table table-striped jambo_table bulk_action">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Kawasan</th>
							<th>Total Unit</th>
							<th class='text-right'>Tot. Tunggakan ( Rp )</th>
							<th class='text-right'>Denda + Bunga ( Rp )</th>
							<th class='text-right'>Penalti ( Rp )</th>
							<th class='text-right'>Tagihan Bulan ( Rp )</th>
							<th class="text-right">Total Tagihan ( Rp. )</th>
							<th>View Blok</th>
						</tr>
					</thead>
					<tbody id="tbody_kawasan">
						<?php
								// 	$i = 0;
								// 	foreach ($data as $key => $v) {
								// 		++$i;
								// 		echo '<tr>';
								// 		echo "<td>$i</td>";
								// 		echo "<td>$v->kawasan_code</td>";
								// 		echo "<td>$v->kawasan_name</td>";
								// 		echo "<td>$v->total_tagihan</td>";
								// 		echo "
                                
                                //     <td>
                                //     <a href='" . site_url() . "/Transaksi/P_belum_tertagih/Blok?id=$v->id' class='btn btn-primary col-md-10'>
                                //         <i class='fa fa-book'></i>
                                //     </a>
                                //     </td>
                                // ";

								// 		echo '</tr>';
								// 	}
									?>
					</tbody>
					<thead>
						<tr>
							<th colspan=2>Total Tagihan Proyek</th>
							<th id="total_unit" class="text-right">0</th>
							<th id="total_tunggakan" class="text-right">0</th>
							<th id="total_denda" class="text-right">0</th>
							<th id="total_penalty" class="text-right">0</th>
							<th id="total_tagihan" class="text-right">0</th>
							
							<th id="total_total_tagihan" class="text-right">0</th>
							<th></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
</div>

<script type="text/javascript">
	$(function() {
				var table = $(".table").dataTable();
				$('.datetimepicker_month').datetimepicker({
					viewMode: 'years',
					format: 'MM/YYYY'
				});

				$('.datetimepicker_year').datetimepicker({
					format: 'YYYY'
				});
				var date = new Date();
				$("#periode").val(('0'+(date.getMonth() + 1)).slice(-2) + "/" + date.getFullYear());
				$("#btn-load-kawasan").click(function() {
					if ($("#periode").val() == "") {
						$('#periode').addClass('has-error');
					} else {
						$('#periode').removeClass('has-error');
					}
					if ($("#periode").val() != null) {
						$.ajax({
							type: "GET",
							data: {
								periode: $("#periode").val()
							},
							url: "<?= site_url() ?>/Transaksi/P_belum_tertagih/ajax_get_kawasan2",
							dataType: "json",
							success: function(data) {
								console.log(data);
								table.fnDestroy();
								total_tagihan = 0;
								$("#tbody_kawasan").html("");

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
									$("#tbody_kawasan").append(
										"<tr class='even pointer'>" +
										"<td>" + (i + 1) + "</td>" +
										"<td class='table-blok'>" + data[i].kawasan + "</td>" +
										"<td class='table-blok text-right'>"+formatC(data[i].unit) + "</td>" +
										"<td class='table-blok text-right'>"+formatC(data[i].tunggakan) + "</td>" +
										"<td class='table-blok text-right'>"+formatC(data[i].denda) + "</td>" +
										"<td class='table-blok text-right'>"+formatC(0) + "</td>" +
										"<td class='table-blok text-right'>"+formatC(data[i].tagihan) + "</td>" +
										"<td class='text-right'>" + formatC(data[i].total) + "</td>" +
										"<td class='col-md-1'><a href='<?= site_url(); ?>/Transaksi/P_belum_tertagih/Blok?id="+data[i].kawasan_id+"&periode="+$("#periode").val()+"' class='save-row btn btn-primary' unit_id='" + data[i].id + "'><i class='fa fa-book'></i></a></td>" +
										"</tr>");
								}
								$("#total_tagihan").html(formatC(total_tagihan));

								table.dataTable({
									order: [
										[1, "asc"]
									]
								});
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
			});
</script>