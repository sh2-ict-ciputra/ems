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

<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url()?>/P_transaksi_meter_listrik'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_transaksi_meter_listrik/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_transaksi_meter_listrik/save">
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select id="unit" name="unit" class="form-control select2">
						<option value="" selected disabled>-- Pilih Jenis Unit --</option>
						<?php
							foreach ($data as $v) {
								echo("<option value='$v->id'>$v->kawasan  -  $v->blok  -  $v->unit  -  $v->pemilik</option>");
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Periode Pemakaian</label>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class='input-group date '>
						<input type="text" class="form-control datetimepicker_month" name="bulan" placeholder="Masukkan Tanggal Aktif">
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<div class='input-group date '>
						<input type="text" class="form-control datetimepicker_year" name="tahun" placeholder="Masukkan Tanggal Aktif">
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="keterangan" class="form-control" rows="3" placeholder='-- Masukkan Keterangan --'></textarea>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input id="customer" type="text" class="form-control" placeholder="-- Auto Generated --" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">ID Barcode</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input id="barcode" type="text" class="form-control" placeholder="-- Auto Generated --" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Meter Awal</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input id="meter_awal" type="text" class="form-control currency" placeholder="-- Auto Generated --" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Meter Akhir</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input id="meter_akhir" type="text" class="form-control currency" placeholder="-- Pilih Unit Dahulu --" name="meter" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Pemakaian</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input id="pemakaian" type="text" class="form-control" placeholder="-- Auto Generated --" disabled>
				</div>
			</div>

		</div>
		<div class="clear-fix"></div>

		<div class="col-md-12 col-xs-12">
			<div class="center-margin">
				<button class="btn btn-primary" type="reset">Reset</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>
		<div id="isi_tabel" class="col-md-12">
		</div>

	</form>
</div>

<!-- jQuery -->

<script type="text/javascript">
	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}
	$(".select2").select2();
	$(function () {
		$('.datetimepicker_month').datetimepicker({
			format: 'MMMM'
		});
		$('.datetimepicker_year').datetimepicker({
			format: 'YYYY'
		});
		$("#unit").change(function () {
			url = '<?=site_url(); ?>/P_transaksi_meter_listrik/getInfoUnit';
			var id= $("#unit").val();
			//console.log(this.value);
			$.ajax({
				type: "get",
				url: url,
				data: {
					id: id
				},
				dataType: "json",
				success: function (data) {
					$("#customer").val(data.customer);
					$("#barcode").val(data.barcode);
					$("#meter_awal").val(currency(data.meter));
					$("#meter_akhir").attr('disabled',false);
					$("#meter_akhir").attr('placeholder','-- Masukkan Meter Akhir --');
				}
			});
		});
		$("#meter_akhir").keyup(function(){
			$("#pemakaian").val($("#meter_akhir").val().replace(/,/g, '') - $("#meter_awal").val().replace(/,/g, ''));
		});
	});
</script>