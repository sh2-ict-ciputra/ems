<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url()?>/P_transaksi_meter_air'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_transaksi_meter_air/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_transaksi_meter_air/save">
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="unit" class="form-control select2">
						<option value="" selected disabled>-- Pilih Jenis Unit --</option>
						<option value="1">Unit</option>
						<option value="0">Non Unit</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Periode Pemakaian</label>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<select name="unit" class="form-control select2">
						<option value="" selected disabled>-- Pilih Jenis Unit --</option>
						<option value="1">Unit</option>
						<option value="0">Non Unit</option>
					</select>
				</div>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<select name="unit" class="form-control select2">
						<option value="" selected disabled>-- Pilih Jenis Unit --</option>
						<option value="1">Unit</option>
						<option value="0">Non Unit</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="alamat_domisili" class="form-control" rows="3" placeholder='-- Masukkan Keterangan --'></textarea>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="-- Auto Generated --" name="nik">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">ID Barcode</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="-- Auto Generated --" name="nik">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Meter Awal / m3</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="-- Auto Generated --" name="nik">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Meter Akhir / m3</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="-- Masukkan Meter Akhir --" name="nik">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Pemakaian / m3</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="-- Auto Generated --" name="nik">
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
	$(".select2").select2();

</script>
