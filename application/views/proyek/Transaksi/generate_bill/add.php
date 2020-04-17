<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!-- select2 -->
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?=base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link href="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- switchery -->
<link href="<?=base_url()?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/switchery/dist/switchery.min.js"></script>
<!-- flat -->
<link href="<?=base_url()?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">


<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url()?>/Transaksi/P_transaksi_generate_bill'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/Transaksi/P_transaksi_generate_bill/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/Transaksi/P_transaksi_generate_bill/save"
	 autocomplete="off">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="form-group">
				<label class="control-label col-lg-6 col-md-6 col-sm-6">Single</label>
				<div class="col-lg-1 col-md-1 col-sm-1">
					<div style="text-align: center">
						<label>
							<input id="single_multiple" type="checkbox" class="js-switch" name="type" value='1'>
						</label>
					</div>
				</div>
				<label class="control-label col-lg-5 col-md-5 col-sm-5" style="text-align: left;">Multiple</label>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Periode</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<div class='input-group date datetimepicker_month'>
						<input type="text" class="form-control datetimepicker_month" name="bulan" placeholder="Bulan">
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Sampai Periode</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<div class='input-group date datetimepicker_month'>
						<input type="text" class="form-control datetimepicker_month multiple" name="sampai_bulan" placeholder="Bulan" disabled>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Kawasan</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<select name="kawasan" required="" id="kawasan" class="form-control select2" placeholder="-- Pilih Kawasan --">
						<option value="" disabled selected>-- Pilih Kawasan --</option>
						<option value="all">-- Semua Kawasan --</option>
						<?php
							foreach ($kawasan as $v) {
								echo("<option value='$v->id'>$v->code - $v->name</option>");
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Blok</label>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<select name="blok" required="" id="blok" class="form-control select2" placeholder="-- Pilih Kawasan Dahulu --"
					 disabled>
						<option value="" disabled selected>-- Pilih Kawasan Dahulu --</option>
						<option value="all">-- Semua Blok --</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			<div class="form-group">
				<label class="control-label col-lg-2 col-md-1 col-sm-1" style="width:13.33334%">Service</label>
				<div class="checkbox col-lg-1 col-md-3">
					<label>
						<input id="service_all" type="checkbox" class="flat service" name="service[]" checked="checked" value="all"> ALL
					</label>
				</div>
				<div class="checkbox col-lg-1 col-md-2" style="margin-left:-25px">
					<label>
						<input id="service_air" type="checkbox" class="flat service" name="service[]" checked="checked" value="air"> AIR
					</label>
				</div>
				<div class="checkbox col-lg-1 col-md-2" style="margin-left:-25px">
					<label>
						<input id="service_listrik" type="checkbox" class="flat service" name="service[]" checked="checked" value="listrik">
						LISTRIK
					</label>
				</div>
				<div class="checkbox col-lg-2 col-md-2">
					<label>
						<input id="service_pl" type="checkbox" class="flat service" name="service[]" checked="checked" value="pl"> LINGKUNGAN
					</label>
				</div>
				<div class="checkbox col-lg-1 col-md-2" style="margin-left:-65px">
					<label>
						<input id="service_lain" type="checkbox" class="flat service" name="service[]" checked="checked" value="lain">
						LAINNYA
					</label>
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
			<div class="card-box table-responsive">
				<table id="table_unit" class="table table-striped table-bordered bulk_action">
					<thead>
						<tr>
							<th>
							<th><input type="checkbox" id="check-all" class="flat"></th>
							</th>
							<th>No</th>
							<th>Kawasan</th>
							<th>Blok</th>
							<th>Unit</th>
							<th>Customer</th>
							<th>Email</th>
							<th>Phone</th>
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
<script type="text/javascript" src="http://localhost/emsNew/vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
	var table_unit = $("#table_unit");
	var table_unit_dt = table_unit.dataTable({
		order: [[1, "asc"]],
		columnDefs: [{
			orderable: !1,
			targets: [0]
		}]
	});
	table_unit.on("draw.dt", function () {
		$("checkbox input").iCheck({
			checkboxClass: "icheckbox_flat-green"
		})
	})
	$("#service_all").on('ifChanged', function () {
		if ($("#service_all").is(':checked')) {
			console.log('all');
		}
	});
	$("#service_air").on('ifChanged', function () {

	});
	$("#service_listrik").on('ifChanged', function () {

	});
	$("#service_pl").on('ifChanged', function () {

	});
	$("#service_all").on('ifChanged', function () {

	});

	function tableICheck() {
		$("input.flat").iCheck({
			checkboxClass: "icheckbox_flat-green",
			radioClass: "iradio_flat-green"
		})
	}
	// checkboxes.on('ifChanged',function(){
	// 		var jumlah_check = 0;
	// 		jQuery.each( checkboxes, function( i, val ) {
	// 			if(i!= 0 && val.checked)
	// 				jumlah_check++;
	// 		});
	// 		console.log(jumlah_check);

	// 	if($(this).val() =='all'){

	// 		if($(this).is(':checked')){
	// 			console.log('wah');
	// 			checkboxes.iCheck('check');
	// 		}else{
	// 			checkboxes.iCheck('uncheck');
	// 		}
	// 	}
	// 	if(jumlah_check <6){
	// 		$("#service_all").iCheck('uncheck');
	// 	}
	// });
	for (i = 0; i < $('.select2').length; i++) {
		$('#' + $('.select2')[i].id).select2({
			placeholder: $('#' + $('.select2')[i].id).attr("placeholder")
		});
	}
	$('.datetimepicker_month').datetimepicker({
		viewMode: 'years',
		format: 'MM/YYYY'
	});
	$('.datetimepicker_year').datetimepicker({
		format: 'YYYY'
	});
	$(function () {
		$("#single_multiple").change(function () {
			if ($("#single_multiple").is(':checked')) {
				$(".multiple").attr('disabled', false);
			} else {
				$(".multiple").attr('disabled', true);
				$(".multiple").val('');
			}
		});
		$(".service").change(function () {
			console.log("haha");
			console.log($(this));
		});
		$("#kawasan").change(function () {
			$.ajax({
				type: "GET",
				data: {
					id: $(this).val()
				},
				url: "<?=site_url()?>/Transaksi/P_transaksi_generate_bill/ajax_get_blok",
				dataType: "json",
				success: function (data) {
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
		$("#blok").change(function () {
			$.ajax({
				type: "GET",
				data: {
					blok_id: $(this).val(),
					kawasan_id: $("#kawasan").val()
				},
				url: "<?=site_url()?>/Transaksi/P_transaksi_generate_bill/ajax_get_unit",
				dataType: "json",
				success: function (data) {
					console.log(data);
					table_unit_dt.fnDestroy();

					$("#tbody_unit").html("");
					// $("#blok").attr("disabled",false);
					for (var i = 0; i < data.length; i++) {
						$("#tbody_unit").append(
							"<tr class='even pointer'>" +
								"<td><th><input type='checkbox' class='flat table-check' name='unit[]' value='"+data[i].id+"'></th></td>"+
								"<td>" + (i + 1) + "</td>" +
								"<td>" + data[i].kawasan + "</td>" +
								"<td>" + data[i].blok + "</td>" +
								"<td>" + data[i].unit + "</td>" +
								"<td>" + data[i].customer + "</td>" +
								"<td class='a-right a-right'>" + data[i].email + "</td>" +
								"<td>" + data[i].phone + "</td>" +
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
						order: [[1, "asc"]],
						columnDefs: [{
							orderable: !1,
							targets: [0]
						}]
					});
					table_unit.on("draw.dt", function () {
						$("checkbox input").iCheck({
							checkboxClass: "icheckbox_flat-green"
						})
					})
					// $("#blok").append("<option value='all'>-- Semua Blok --</option>");						
					// for(var i = 0;i < data.length;i++){
					//     $("#blok").append("<option value='"+data[i].id+"'>"+data[i].name+"</option>");
					// }
				}
			});
		});
	});

</script>
