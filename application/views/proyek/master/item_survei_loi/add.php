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
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_item_survei_loi/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form-cara-bayar" autocomplete="off" class="form-horizontal form-label-left">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<input type="text" id="name" name="name" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Nama">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nilai">Nilai<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
					<input type="text" id="nilai" name="nilai" required class="text-right form-control col-md-7 col-xs-12 currency" style="padding-left: 50px;" value="0">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Satuan<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<input type="text" id="satuan" name="satuan" required class="form-control col-md-7 col-xs-12" placeholder="-- Masukkan Satuan --">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Deskripsi<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12" placeholder="-- Masukkan Deskripsi --"></textarea>
				</div>
			</div>
		</div>
		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<div class="center-margin">
					<button type="submit" class="btn-submit btn btn-success">Submit</button>
				</div>
			</div>
		</div>
	</form>
</div>


<!-- jQuery -->
<script type="text/javascript">
	function formatNumber(data) {
		data = data + '';
		data = data.replace(/,/g, "");

		data = parseInt(data) ? parseInt(data) : 0;
		data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		return data;

	}

	function unformatNumber(data) {
		data = data + '';
		return data.replace(/,/g, "");
	}

	function notif(title, text, type) {
		new PNotify({
			title: title,
			text: text,
			type: type,
			styling: 'bootstrap3'
		});
	}


	$(function() {

		$(".currency").keyup(function() {
			$(this).val(formatNumber($(this).val()));
		});
		$("form").submit(function(e) {
			$.each($(".currency"), function( k, v ) {
				$(".currency").eq(k).val(unformatNumber(v.value))
			});
			e.preventDefault();
			$.ajax({
				type: "POST",
				data: $("form").serialize(),
				url: "<?= site_url('P_master_item_survei_loi/ajax_save') ?>",
				dataType: "json",
				success: function(data) {
					if (data.status == 1) {
						notif('Sukses', data.message, 'success')
						setTimeout(function() {
							window.location.href = '<?= site_url('P_master_item_survei_loi') ?>'
						}, 2 * 1000);
					} else
						notif('Gagal', data.message, 'danger')
				}
			});
			$.each($(".currency"), function( k, v ) {
				$(".currency").eq(k).val(formatNumber(v.value))
			});
		})
	});
</script>