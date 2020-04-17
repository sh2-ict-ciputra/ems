<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url(); ?>/P_master_group_tvi'">
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
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_master_group_tvi/save">
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Nama Grup TV Internet <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="nama_group" name="nama_group" required="required" class="form-control col-md-7 col-xs-12">
			</div>
		</div>

		<div class="form-group two">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe Durasi</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select name="tipe_durasi" id="tipe_durasi" required="" class="form-control select2">
					<option value="">--Pilih Tipe Durasi--</option>
					<option value="1">Hari</option>
					<option value="2">Bulan</option>
					<option value="3">Tahun</option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Durasi</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" class="form-control currency" placeholder="Masukkan Jumlah Durasi" name="durasi" value="0">
			</div>
		</div>

        <div class="form-group two">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pembayaran</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select name="jenis_bayar" id="jenis_bayar" required="" class="form-control select2">
					<option value="">--Pilih Jenis Pembayaran--</option>
					<option value="1">Pra Bayar</option>
					<option value="0">Pasca Bayar</option>
				</select>
			</div>
		</div>



		<div class="form-group">
			<label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="keterangan"></textarea>
			</div>
		</div>

		

		<div class="form-group">
			<div class="center-margin">
				<button class="btn btn-primary" type="reset">Reset</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>
	</form>

</div>
</form>


<!-- jQuery -->
<script type="text/javascript">

</script>
