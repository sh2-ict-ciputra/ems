<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<!-- modals -->
<!-- Large modal -->
<div id="modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Detail Log</h4>
			</div>
			<div class="modal-body">
				<table class="table table-striped jambo_table bulk_action">
					<thead>
						<tr>
							<th>Point Detail</th>
							<th>Before</th>
							<th>After</th>
						</tr>
					</thead>
					<tbody id="dataModal">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>

<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href = '<?= substr(current_url(), 0, strrpos(current_url(), "/")) ?>'">
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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/P_master_paket_service/edit?id=<?= $this->input->get('id'); ?>">




		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih jenis_service">
				Pilih Jenis Service
				<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select type="text" id="jenis_service" required="required" class="select2 form-control col-md-7 col-xs-12" name="jenis_service" style="width:100%" placeholder="--Pilih Jenis Service--" disabled>
					<option></option>
					<?php
					foreach ($dataService as $key => $c) {
						if ($data_select->service_id == $c['id']) {
							echo "<option value='$c[id]' selected>$c[name] </option>";
						} else {
							echo "<option value='$c[id]'>$c[name] </option>";
						}
					}
					?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode Paket <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12" value="<?= $data_select->code; ?>" disabled>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Nama Pekerjaan <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="nama_pekerjaan" name="nama_pekerjaan" required="required" class="form-control col-md-7 col-xs-12" value="<?= $data_select->name; ?>" disabled>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Satuan <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="satuan" name="satuan" required="required" class="form-control col-md-7 col-xs-12" value="<?= $data_select->satuan; ?>" disabled>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Biaya Satuan dengan Langganan<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="biaya_satuan_langganan" name="biaya_satuan_langganan" required="required" class="form-control col-md-7 col-xs-12 currency" value="<?= $data_select->biaya_satuan_langganan; ?>" disabled>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Biaya Satuan tanpa Langganan<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="biaya_satuan_tanpa_langganan" name="biaya_satuan_tanpa_langganan" required="required" class="form-control col-md-7 col-xs-12 currency" value="<?= $data_select->biaya_satuan_tanpa_langganan; ?>" disabled>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="minimal_langganan">Minimal Untuk Langganan (periode)<span class="required">*</span>
			</label>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<input type="text" id="minimal_langganan" name="minimal_langganan" required="required" class="form-control col-md-7 col-xs-12 currency" value="<?= $data_select->minimal_langganan; ?>" disabled>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<select class="form-control col-md-12" name="tipe_periode" id="tipe_periode" disabled>
					<option value="" disabled selected>Pilih Tipe</option>
					<option value="1" <?=$data_select->tipe_periode == 1?'selected':''?>>Hari</option>
					<option value="2" <?=$data_select->tipe_periode == 2?'selected':''?>>Bulan</option>
					<option value="3" <?=$data_select->tipe_periode == 3?'selected':''?>>Tahun</option>
				</select>
			</div>

		</div>
		<div id="view_biaya_registrasi">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Registrasi</label>
				<div class="col-md-1 col-sm-3 col-xs-12">
					<div class="">
						<label>
							<input id="biaya_registrasi_aktif" type="checkbox" class="js-switch" name="biaya_registrasi_aktif" value='1' <?= $data_select->biaya_registrasi_aktif == 1 ? 'checked' : ''; ?> disabled /> Aktif
						</label>
					</div>
				</div>
				<div class="col-md-5 col-sm-6 col-xs-12">
					<input type="text" id="biaya_registrasi" name="biaya_registrasi" required="required" value="<?= $data_select->biaya_registrasi; ?>" disabled class="form-control col-md-7 col-xs-12 currency">
				</div>
			</div>
		</div>

		<div id="view_biaya_pemasangan">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Pemasangan</label>
				<div class="col-md-1 col-sm-3 col-xs-12">
					<div class="">
						<label>
							<input id="biaya_pemasangan_aktif" type="checkbox" class="js-switch" name="biaya_pemasangan_aktif" value='1' <?= $data_select->biaya_pemasangan_aktif == 1 ? 'checked' : ''; ?> disabled /> Aktif
						</label>
					</div>
				</div>
				<div class="col-md-5 col-sm-6 col-xs-12">
					<input type="text" id="biaya_pemasangan" name="biaya_pemasangan" required="required" value="<?= $data_select->biaya_pemasangan; ?>" disabled class="form-control col-md-7 col-xs-12 currency ">
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<div class="">
					<label>
						<input id="status" type="checkbox" class="js-switch" name="status" value="1" <?= $data_select->active == 1 ? 'checked' : ''; ?> disabled /> Aktif
					</label>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<!-- <button id="btn-Reset" type="reset" class="btn btn-primary col-md-1 col-md-offset-4" disabled>Reset</button> -->
			<input id="btn-update" type="button" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
			<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
		</div>
	</form>
</div>
</div>

<div class="x_panel">
	<div class="x_title">
		<h2>Log</h2>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<br>
		<table class="table table-striped jambo_table bulk_action tableDT">
			<thead>
				<tr>
					<th>No</th>
					<th>Waktu</th>
					<th>User</th>
					<th>Status</th>
					<th>Detail</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ($data as $key => $v) {
					++$i;
					echo '<tr>';
					echo "<td>$i</td>";
					echo "<td>$v[date]</td>";
					echo "<td>$v[name]</td>";
					echo '<td>';
					if ($v['status'] == 1) {
						echo 'Tambah';
					} elseif ($v['status'] == 2) {
						echo 'Edit';
					} else {
						echo 'Hapus';
					}
					echo '</td>';
					echo "
                    <td class='col-md-1'>
                        <a class='btn-modal btn btn-sm btn-primary col-md-12' data-toggle='modal' data-target='#modal' data-transfer='$v[id]' data-type='$v[status]'>
                            <i class='fa fa-pencil'></i>
                        </a>
                    </td>
                ";
					echo '</td></tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<!-- jQuery -->
<script type="text/javascript">
	$.each($(".currency"), function(index, currency) {
		currency.value = parseInt(currency.value.toString().replace(/[\D\s\._\-]+/g, ""), 10).toLocaleString("en-US");
	});




	$(".select2").select2();

	$("#btn-update").click(function() {




		$("#jenis_service").removeAttr("disabled");
		$("#code").removeAttr("disabled");
		$("#nama_pekerjaan").removeAttr("disabled");
		$("#satuan").removeAttr("disabled");
		$("#biaya_satuan_langganan").removeAttr("disabled");
		$("#biaya_satuan_tanpa_langganan").removeAttr("disabled");
		$("#status").removeAttr("disabled");
		$("#biaya_registrasi_aktif").removeAttr("disabled");
		$("#biaya_pemasangan_aktif").removeAttr("disabled");
		$("#minimal_langganan").removeAttr("disabled");
		$("#tipe_periode").removeAttr("disabled");

		if ($("#biaya_registrasi_aktif").is(':checked')) {
			$("#biaya_registrasi").attr('disabled', false);

		} else {
			$("#biaya_registrasi").attr('disabled', true);
			$("#biaya_registrasi").val('0');
		}



		if ($("#biaya_pemasangan_aktif").is(':checked')) {
			$("#biaya_pemasangan").attr('disabled', false);

		} else {
			$("#biaya_pemasangan").attr('disabled', true);
			$("#biaya_pemasangan").val('0');
		}




		$("#btn-cancel").removeAttr("style");
		$("#btn-update").val("Update");
		setTimeout(function() {
			$("#btn-update").attr("type", "submit");
		}, 100);
	});
	$("#btn-cancel").click(function() {


		$("#jenis_service").attr("disabled", "");
		$("#code").attr("disabled", "");
		$("#nama_pekerjaan").attr("disabled", "");
		$("#satuan").attr("disabled", "");
		$("#biaya_satuan_langganan").attr("disabled", "");
		$("#biaya_satuan_tanpa_langganan").attr("disabled", "");
		$("#status").attr("disabled", "");
		$("#biaya_registrasi_aktif").attr("disabled", "");
		$("#biaya_pemasangan_aktif").attr("disabled", "");
		$("#minimal_langganan").attr("disabled", "");
		$("#tipe_periode").attr("disabled","");

		if ($("#biaya_registrasi_aktif").is(':checked')) {
			$("#biaya_registrasi").attr('disabled', true);

		} else {
			$("#biaya_registrasi").attr('disabled', true);
			$("#biaya_registrasi").val('0');
		}



		if ($("#biaya_pemasangan_aktif").is(':checked')) {
			$("#biaya_pemasangan").attr('disabled', true);

		} else {
			$("#biaya_pemasangan").attr('disabled', true);
			$("#biaya_pemasangan").val('0');
		}


		$("#btn-cancel").attr("style", "display:none");
		$("#btn-update").val("Edit");
		$("#btn-update").removeAttr("type");
	});


	$(".btn-modal").click(function() {
		url = '<?= site_url(); ?>/core/get_log_detail';
		$.ajax({
			type: "POST",
			data: {
				id: $(this).attr('data-transfer'),
				type: $(this).attr('data-type')
			},
			url: url,
			dataType: "json",
			success: function(data) {
				$("#dataModal").html("");

				if (data[data.length - 1] == 2) {

					for (i = 0; i < data[0].length; i++) {
						$.each(data[1], function(key, val) {
							if (val.name == data[0][i].name) {
								$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0][i].value + "</td></tr>");
							}
						});
					}
				} else {
					$.each(data, function(key, val) {
						if (data[data.length - 1] == 1) {
							if (val.name)
								$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td></td><td>" + val.value + "</td></tr>");
						} else if (data[data.length - 1] == 2) {

						} else if (data[data.length - 1] == 3) {
							if (val.name)
								$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td>" + val.value + "</td><td></td></tr>");
						}
					});
				}

			}
		});

	});



	$(function() {

		$("#biaya_registrasi_aktif").change(function() {
			if ($("#biaya_registrasi_aktif").is(':checked')) {
				$("#biaya_registrasi").attr('disabled', false);

			} else {
				$("#biaya_registrasi").attr('disabled', true);
				$("#biaya_registrasi").val('0');
			}
		});
		$("#biaya_pemasangan_aktif").change(function() {
			if ($("#biaya_pemasangan_aktif").is(':checked')) {
				$("#biaya_pemasangan").attr('disabled', false);

			} else {
				$("#biaya_pemasangan").attr('disabled', true);
				$("#biaya_pemasangan").val('0');
			}
		});

	});
</script>