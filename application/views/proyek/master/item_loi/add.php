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
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_item_loi/add'">
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
		<div class="col-md-5 col-sm-5 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Item Outflow<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" id="name" name="name" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Nama">
				</div>
			</div>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12">
			Satuan Item Outflow
			<hr style="margin: 10px 0px 3px 0px">
			<table class="table table-responsive">
				<thead>
					<tr>
						<th>Nama</th>
						<th>Hapus</th>
					</tr>
				</thead>
				<tbody id="tbody_item_outfow">
					
				</tbody>
			</table>
			<a id="add_outflow" class="btn btn-info pull-right">Add Outflow</a>
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
	const row_outflow = "<tr>"+
							"<td><input type='text' class='col-md-12 form-control' name='satuan_name[]' required></td>"+
							"<td><a type='text' class='col-md-12 btn btn-danger btn-hapus'>Hapus</a></td>"+
						"</tr>";
	function notif(title, text, type) {
		new PNotify({
			title: title,
			text: text,
			type: type,
			styling: 'bootstrap3'
		});
	}

	$(function() {
		// --start-- item outflow
		$("#add_outflow").click(function(){
			$("#tbody_item_outfow").append(row_outflow);
		});
		$("body").on('click','.btn-hapus',function(){
			$(this).parents('tr').remove();
		})
		// --end-- item outflow 

		$("form").submit(function(e) {
			e.preventDefault();
			$.ajax({
				type: "POST",
				data: $("form").serialize(),
				url: "<?= site_url('P_master_item_loi/ajax_save') ?>",
				dataType: "json",
				success: function(data) {
					if (data.status == 1) {
						notif('Sukses', data.message, 'success')
						setTimeout(function() {
							window.location.href = '<?= site_url('P_master_item_loi') ?>'
						}, 2 * 1000);
					} else
						notif('Gagal', data.message, 'danger')
				}
			});
		})
	});
</script>