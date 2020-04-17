<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>


<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/P_master_sub_golongan/save">
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Golongan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="golongan" class="form-control select2">
						<?php
						if (isset($_GET['id'])) {
							foreach ($dataGolongan as $v) {
								if ($_GET['id'] == $v['id']) {
									echo "<option value='$v[id]' selected >$v[description]</option>";
								}
							}
						} else {
							?>
							<option value="" selected="" disabled="">--Pilih Golongan--</option>
							<?php
							foreach ($dataGolongan as $v) {
								echo "<option value='$v[id]'>$v[description]</option>";
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Sub </label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="code" class="form-control" placeholder="Masukkan Kode Sub Golongan">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Sub</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" name="nama_sub" placeholder="Masukkan Nama Sub">
				</div>
			</div>
			<div class="col-md-12 col-xs-12">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Minimum</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="">
						<label>
							<input id="minimum_flag" type="checkbox" class="js-switch disabled-form" name="minimum_flag" value='1'>
							<p id="label_flag_minimum" style="display:contents"></p>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Minimum Pemakaian (m3)
				</label>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<input type="text" class="form-control currency disabled-form" min="0" placeholder="Masukkan Minimum Pemakaian" name="minimum_pemakaian" id="input_pemakaian" disabled>
				</div>
				<label class="control-label col-md-1 col-sm-1 col-xs-12">(Rp)
				</label>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<input type="text" class="form-control currency" min="0" placeholder="Masukkan Minimum Pemakaian" name="" id="harga_pemakaian" value="" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Minimum (Rp)
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control numbering " value="" placeholder="Masukkan Minimum" id="nilai_minimum" name="nilai_minimum">
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Administrasi (Rp)</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control numbering currency" placeholder="Masukkan Nilai Administrasi" id="inputku" value="0" name="administrasi">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Service</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="range_flag" id="range_flag" required class="form-control select2">
						<option value="">---Pilih Jenis Service---</option>
						<?php
						foreach ($dataService as $v) {
							echo "<option value='$v[id]'>$v[code]</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Range</label>
				<div id="lihat_range">
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select id="range_id" class="form-control select2" name="range_id">
							<option value="">--Pilih Range--</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Pemeliharaan Air</label>
				<div id="lihat_range">
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select id="pemeliharaan_air_id" class="form-control select2" name="pemeliharaan_air_id">
							<option value="0">--Tidak Ada--</option>
							<?php foreach ($dataPemeliharaanAir as $k => $v):?>
								<option value="<?=$v->id?>"><?=$v->name?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="keterangan" class="form-control" rows="3" placeholder='Masukkan Keterangan'></textarea>
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
<script type="text/javascript">
	var harga_minimum = 0;

	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}

	function ajax_minimum() {
		$.ajax({
			type: "GET",
			data: {
				data1: $('#range_flag').val(),
				data2: $('#range_id').val(),
				data3: $('#input_pemakaian').val()
			},
			url: "<?= site_url(); ?>/P_master_sub_golongan/ajax_get_minimum",
			dataType: "json",
			success: function(data) {
				console.log(data);
				$("#harga_pemakaian").val(currency(data));
			}
		});
	}
	$(document).ready(function() {

		$("#minimum_flag").change(function() {
			if ($("#minimum_flag")[0].checked) {
				$("#label_flag_minimum").html("Anda Menggunakan Minimum (Rp)");
				$("#input_pemakaian").val(null);
				$("#input_pemakaian").attr('placeholder', '');
				$("#harga_pemakaian").val(null);
				$("#harga_pemakaian").attr('placeholder', '');
				$("#input_pemakaian").attr('disabled', true);
				$("#nilai_minimum").attr('placeholder', 'Masukkan Minimum (Rp)');
				$("#nilai_minimum").attr('disabled', false);
			} else {
				$("#label_flag_minimum").html("Anda Menggunakan Minimum Pemakaian (m3)");
				$("#nilai_minimum").val(null);
				$("#input_pemakaian").attr('disabled', false);
				$("#nilai_minimum").attr('disabled', true);
				$("#nilai_minimum").attr('placeholder', '');
				$("#input_pemakaian").attr('placeholder', 'Masukkan Minimum Pemakaian');
				$("#harga_pemakaian").attr('placeholder', '');

			}
		});
		$("#minimum_flag").trigger('change');

		$("#input_pemakaian").keyup(function() {
			// $("#nilai_minimum").val(currency_to_number(harga_minimum) * currency_to_number($(this).val()));
			ajax_minimum();
		});
		$("#range_flag").change(function() {
			$.ajax({
				type: "post",
				url: '<?= site_url(); ?>/P_master_sub_golongan/lihat_range',
				data: {
					range_flag: $("#range_flag").val()
				},
				dataType: "json",
				success: function(data) {
					console.log(data);

					$("#range_id")[0].innerHTML = "";

					$("#range_id").append("<option value='' >Pilih Range</option>");
					$.each(data, function(key, val) {
						val.code = val.code?val.code.toUpperCase():"";
						val.name = val.name?val.name.toUpperCase():"";
						

						var str = "<option value='" + val.id + "'>"+val.code+ " - " + val.name+ "</option>";
						$("#range_id").append(str);
					});
				}
			});
			
		});

		$(".select2").select2();
		$("#range_id").change(function() {

			url = '<?= site_url(); ?>/P_master_sub_golongan/lihat_tabel';
			var range = $("#range_id").val();
			var min_use = $("#input_pemakaian").val();
			var action = "lihat_tabel";
			var jenis = "sub_golongan";
			var range_flag = $("#range_flag").val();
			//var loading = '<p align="center"><img src="images/tenor.gif"> </p>';
			// $("#print_range").html(loading);
			$.ajax({
				url: url,
				method: "POST",
				data: {
					range: range,
					action: action,
					jenis: jenis,
					id: range,
					min_use: min_use,
					range_flag: range_flag
				},
				dataType: "json",
				success: function(data) {
					var tmp = "<table class='table table-responsive'>";
					tmp += "<thead>";
					tmp += "<tr>";
					tmp += "<th>Range</th>";
					tmp += "<th>Range Awal</th>";
					tmp += "<th>Range Akhir</th>";
					tmp += "<th>Harga</th>";
					tmp += "<tr>";
					tmp += "<thead>";
					tmp += "<tbody>";

					var no = 0;
					// var nilai = 0;

					$.each(data, function(key, val) {
						console.log(val);
						no = no + 1;

						tmp += "<tr>";
						tmp += "<td> Range" + no + " </td>";
						tmp += "<td>" + val.range_awal + " </td>";
						tmp += "<td>" + val.range_akhir + " </td>";
						tmp += "<td>" + val.harga + " </td>";
						tmp += "<tr>";

						// if ((min_use >= val.range_awal) && (min_use <= val.range_akhir)) {
						// 	nilai = min_use * val.harga;
						// }

					});

					tmp += "<tbody>";
					tmp += "<table>";
					$("#isi_tabel")[0].innerHTML = tmp;

					// $('#nilai_minimum').val(nilai);
					ajax_minimum();
				}
			});
		});
	});
</script>