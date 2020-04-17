<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url(); ?>/P_master_paket_tvi'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url(); ?>/P_master_paket_tvi/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_master_paket_tvi/save">
		<div class="x_content">
			<br />

			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Group</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
				<input type="text" class="form-control" required name="group" placeholder="Masukkan Group"  value="<?=$dataGroupTvi->name;
                    ?>"  readonly    >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Paket</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
					<input type="text" class="form-control" required name="kode" placeholder="Masukkan Kode Paket">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Paket</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
					<input type="text" class="form-control" required name="nama_paket" placeholder="Masukkan Nama Paket">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Bandwith (Kbps)</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
					<input type="text" class="form-control currency" name="bandwith" placeholder="Masukkan Kbps">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12"> Harga Hpp (Rp)</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
					<input type="text" class="form-control currency currency" required name="hpp" placeholder="Masukkan Harga HPP">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Jual (Rp)</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
					<input type="text" class="form-control currency" id="harga" required name="harga_jual" placeholder="Masukkan Harga Jual">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Pasang Baru (Rp)</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
					<input type="text" class="form-control currency" required name="biaya_pasang" placeholder="Masukkan Biaya Pasang Baru">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Registrasi (Rp)</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
					<input type="text" value="0"  class="form-control currency" required name="biaya_registrasi" placeholder="Masukkan Biaya Registrasi">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-6 col-sm-9 col-xs-12">
					<textarea class="form-control" rows="3" name="keterangan" placeholder='Masukkan Keterangan'></textarea>
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

<!-- jQuery -->
<script type="text/javascript">
	$(function () {});

</script>
