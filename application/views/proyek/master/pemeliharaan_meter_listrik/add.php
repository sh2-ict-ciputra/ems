<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?=base_url()?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/switchery/dist/switchery.min.js"></script>

<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url(); ?>/P_master_pemeliharaan_meter_listrik/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_pemeliharaan_meter_listrik/save">
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12" placeholder="Masukkan Kode">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Daya</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" class="form-control currency" placeholder="Masukkan Daya" name="daya">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Sewa</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" class="form-control currency" placeholder="Masukkan Harga Sewa" name="harga_sewa">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">PPN</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<div class="">
					<label>
						<input id="ppn" type="checkbox" class="js-switch" name="ppn" value='1'> Aktif
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Pasang Baru</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" class="form-control currency" required name="biaya_pasang_baru" placeholder="Masukkan Biaya Pasang Baru">
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
</div>
</form>
</div>
