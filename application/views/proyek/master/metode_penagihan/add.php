<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href = '<?= substr(current_url(), 0, strrpos(current_url(), "/")) ?>'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onclick="location.href='<?= site_url(); ?>/P_master_metode_penagihan/add';">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" class="form-horizontal form-label-left" method="post" action="<?= site_url() ?>/P_master_metode_penagihan/save">
		<div id="div_jenis_metode_penagihan" class="form-group">
			<label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">Jenis Metode Penagihan<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-12 col-xs-9">
				<select type="text" id="jenis_metode_penagihan" required="required" class="select2 form-control col-md-7 col-xs-12" name="jenis_metode_penagihan" style="width:100%" placeholder="--Pilih Jenis Meotde Penagihan--">
					<option disabled selected>-- Pilih Jenis Metode Penagihan --</option>
					<?php foreach ($dataMetodePenagihanJenis as $v) : ?>x
						<option value="<?= $v->id ?>"><?= $v->name ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12" placeholder="-- Masukkan Kode --">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Metode Penagihan <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="metode_penagihan" name="metode_penagihan" required="required" class="form-control col-md-7 col-xs-12" placeholder="-- Masukkan Nama dari Metode Penagihan --">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin">Biaya Admin <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="biaya_admin" name="biaya_admin" required="required" class="form-control col-md-7 col-xs-12 currency" placeholder="-- Masukkan Biaya Admin --">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">
				COA Biaya Admin
				<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select type="text" id="coa" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa" style="width:100%" placeholder="-- Pilih PT - COA Service --">
					<option value="" selected="" disabled="">--Pilih PT COA--</option>
					<?php
					foreach ($dataMetodePenagihan as $v) {
                        echo ("<option value='$v->id'>$v->ptName $v->coaCode - $v->coaName</option>");
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="keterangan" placeholder="-- Masukkan Keterangan --"></textarea>
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
<!-- jQuery -->
<script type="text/javascript">
	$(function() {
		$(".select2").select2({
			placeholder: $('.select2').attr('placeholder')
		});
	});
</script>