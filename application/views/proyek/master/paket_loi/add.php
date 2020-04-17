<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<style>
	.select2-container {
		width: 100%;
	}
</style>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href = '<?= substr(current_url(), 0, strrpos(current_url(), "/")) ?>'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_paket_loi/add'">
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
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" id="code" name="code" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Nama" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" id="name" name="name" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Nama">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nilai">Nilai LOI<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
					<input type="text" id="nilai" name="nilai" required class="text-right form-control col-md-7 col-xs-12 currency" style="padding-left: 50px;" value="0">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nilai_admin">Nilai Admin<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
					<input type="text" id="nilai_admin" name="nilai_admin" required class="text-right form-control col-md-7 col-xs-12 currency" style="padding-left: 50px;" value="0">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="uang_jaminan">Uang Jaminan<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
					<input type="text" id="uang_jaminan" name="uang_jaminan" required class="text-right form-control col-md-7 col-xs-12 currency" style="padding-left: 50px;" value="0">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="follow_up">Follow Up<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="follow_up" id="follow_up" class="form-control col-md-7 col-xs-12" required>
						<option value="1" selected>EMS (Lapangan)</option>
						<option value="2">CPMS</option>
						<option value="3">Customer</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12">
			Item Outflow
			<hr style="margin: 10px 0px 3px 0px">
			<table class="table table-responsive">
				<thead>
					<tr>
						<th>Nama Item</th>
						<th>Kwantitas</th>
						<th>Satuan</th>
						<th>Harga Total</th>
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
	const row_outflow = "<tr>" +
		"<td class='col-md-3'>" +
		"<select class='col-md-12 form-control select2-tag-name' name='item_name[]' required>" +
		"</select>" +
		"</td>" +
		"<td><input type='text' class='col-md-12 form-control currency' name='item_kwantitas[]' required></td>" +
		"<td class='col-md-3'>" +
		"<select class='col-md-12 form-control select2-tag-satuan' name='item_satuan[]' required>" +
		"</select>" +
		"</td>" +
		"<td><input type='text' class='col-md-12 form-control currency' name='item_nilai[]' required></td>" +
		"<td><a type='text' class='col-md-12 btn btn-danger btn-hapus'>Hapus</a></td>" +
		"</tr>";

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
		// --start-- item outflow
		$("#add_outflow").click(function() {
			$("#tbody_item_outfow").append(row_outflow);
			$(".select2-tag-name").select2({
				tags: true,
				width: 'resolve',
				minimumInputLength: 1,
				placeholder: 'Pilih',
				ajax: {
					type: "GET",
					dataType: "json",
					url: "<?= site_url("P_master_paket_loi/get_ajax_item") ?>",
					data: function(params) {
						return {
							data: params.term
						}
					},
					processResults: function(data) {
						return {
							results: data
						};
					}
				},
				createTag: function(tag) {
					// Check if the option is already there
					var found = false;
					var input = tag.term;
					if (!found) {
						return {
							id: input,
							text: input + " .::Data Baru::.",
						};
					}
				},
			});
			$(".select2-tag-satuan").select2({
				tags: true,
				width: 'resolve',
				minimumInputLength: 1,
				placeholder: 'Pilih',
				ajax: {
					type: "GET",
					dataType: "json",
					url: "<?= site_url("P_master_paket_loi/get_ajax_item_satuan") ?>",
					data: function(params) {
						return {
							data: params.term,
							loi_item_outflow_id: $(this).parents('tr').find('.select2-tag-name').val()
						}
					},
					processResults: function(data) {
						return {
							results: data
						};
					}
				},
				createTag: function(tag) {
					// Check if the option is already there
					var found = false;
					var input = tag.term;
					if (!found) {
						return {
							id: input,
							text: input + " .::Data Baru::.",
						};
					}
					alert($(this))
				},
			});
		});
		// --end-- item outflow 

		// --start-- check and replace Nilai select2 dari database
		$("body").on('select2:select', '.select2-tag-name', function() {
			this2 = $(this);
			this2.parents('tr').find('.select2-tag-satuan').select2("trigger", "select", {
				data: {
					id: '',
					text: ''
				}
			});
			ajax = $.ajax({
				type: "GET",
				data: {
					data: this2.val().toUpperCase()
				},
				url: "<?= site_url('P_master_paket_loi/get_ajax_item_cek') ?>",
				dataType: "json",
				success: function(data) {
					if (data) {
						this2.select2("trigger", "select", {
							data: {
								id: data.id,
								text: data.text
							}
						});
					}
				}
			});
		})
		// --end-- check and replace Nilai select2 dari database
		// --start-- check and replace Nilai select2 dari database
		$("body").on('select2:select', '.select2-tag-satuan', function() {
			this2 = $(this);
			console.log(this2);
			if (parseInt((this2).parents('tr').find('.select2-tag-name').val())) {
				ajax = $.ajax({
					type: "GET",
					data: {
						data: this2.val().toUpperCase(),
						id: parseInt((this2).parents('tr').find('.select2-tag-name').val())
					},
					url: "<?= site_url('P_master_paket_loi/get_ajax_item_satuan_cek') ?>",
					dataType: "json",
					success: function(data) {
						if (data) {
							this2.select2("trigger", "select", {
								data: {
									id: data.id,
									text: data.text
								}
							});
						}
					}
				});
			}
		})
		// --end-- check and replace Nilai select2 dari database

		$("body").on('click', '.btn-hapus', function() {
			$(this).parents('tr').remove();
		})

		$("#follow_up").select2();
		$("#name").keyup(function() {
			$("#code").val($("#name").val().toLowerCase().replace(/ /g, '_'));
		});
		$(".currency").keyup(function() {
			$(this).val(formatNumber($(this).val()));
		});
		$("form").submit(function(e) {
			$.each($(".currency"), function(k, v) {
				$(".currency").eq(k).val(unformatNumber(v.value))
			});
			e.preventDefault();
			$.ajax({
				type: "POST",
				data: $("form").serialize(),
				url: "<?= site_url('P_master_paket_loi/ajax_save') ?>",
				dataType: "json",
				success: function(data) {
					if (data.status == 1) {
						notif('Sukses', data.message, 'success')
						setTimeout(function() {
							window.location.href = '<?= site_url('P_master_paket_loi') ?>'
						}, 2 * 1000);
					} else
						notif('Gagal', data.message, 'danger')
				}
			});
			$.each($(".currency"), function(k, v) {
				$(".currency").eq(k).val(formatNumber(v.value))
			});
		})
	});
</script>