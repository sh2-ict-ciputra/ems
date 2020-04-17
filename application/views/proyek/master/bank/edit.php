<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>

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
		<button class="btn btn-primary" onClick="window.location.href='<?= site_url(); ?>/P_master_bank/edit?id=<?= $this->input->ge['id'] ?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br />
	<form id="form-bank" class="form-horizontal form-label-left" method="post">
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Bank
				<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select id='nama' class='col-md-12 form-control select2' name="bank_jenis_id" disabled required>
					<option value="" selected="" disabled="">--Pilih Bank--</option>
					<?php foreach ($dataBank as $v) : ?>
						<?php if($v->id == $dataSelected->bank_jenis_id):?>
						<option value='<?= $v->id ?>' nama='<?= $v->name ?>' kode='<?= $v->code ?>' selected><?= $v->name ?></option>
						<?php else:?>
						<option value='<?= $v->id ?>' nama='<?= $v->name ?>' kode='<?= $v->code ?>'><?= $v->name ?></option>
						<?php endif;?>
						
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Name Bank
				<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input id='name' class='col-md-12 form-control' placeholder="Pilih Bank Terlebih dahulu" name="name" required readonly>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Kode Bank
				<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input id='kode' class='col-md-12 form-control' placeholder="Pilih Bank Terlebih dahulu" name="code" required readonly>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Biaya Admin
				<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input id='biaya_admin' class='col-md-12 form-control disabled-form' name="biaya_admin" placeholder="Pilih Biaya Admin ( yang di kenakan oleh bank ke customer)" value="<?=$dataSelected->biaya_admin?>" disabled required>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
				Deskripsi
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea id='deskripsi' name="description" class='col-md-12 form-control disabled-form' disabled placeholder="Masukkan Deskripsi jika di perlukan"><?=$dataSelected->description?></textarea>
			</div>
		</div>
		<div class="col-md-12">
			<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
			<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
		</div>
	</form>
</div>
</div>
</div>

<div class="x_panel">
	<div class="x_title">
		<h2>Log</h2>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<br>
		<table class="table table-striped jambo_table bulk_action tableDT2">
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
	disableForm = 1;
	function formatNumber(data) {
		data = data + '';
		data = data.replace(/,/g, "");

		data = parseInt(data) ? parseInt(data) : 0;
		data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		return data;

	}
	$(function() {
		$(".tableDT2").DataTable();
		function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
		$('#form-bank').on('submit', function (e) {
			console.log("test1");
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: "<?= site_url() ?>/P_master_bank/ajax_edit?id=<?=$this->input->get("id")?>",
				data: $('form').serialize(),
				dataType: "json",
				success: function (data) {
					console.log(data);
					if( data == "success")
						notif("Berhasil","Data Berhasil di Ubah	","success")
					else
						notif("Gagal","Data Tidak ada Perubahan","danger")
				}
			});
		});
		$("#biaya_admin").keyup(function(){
			$("#biaya_admin").val(formatNumber($("#biaya_admin").val()))
		});
		$("#nama").change(function(){
			$("#kode").val($('option:selected', this).attr('kode')); 
			$("#name").val($('option:selected', this).attr('nama')); 
		});

		$("#btn-update").click(function() {
			disableForm = 0;
			$(".disabled-form").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
		});
		$("#btn-cancel").click(function() {
			disableForm = 1;
			$(".disabled-form").attr("disabled", "")
			$("#btn-cancel").attr("style", "display:none");
			$("#btn-update").val("Edit")
			$("#btn-update").removeAttr("type");
		});

		$(".btn-modal").click(function() {
			url = '<?= site_url(); ?>/core/get_log_detail';
			console.log($(this).attr('data-transfer'));
			console.log($(this).attr('data-type'));
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
							var tmpj = 0;
							for (j = 0; j < data[0].length; j++) {
								if (data[1][j] != null) {
									if (data[1][j].name == data[0][i].name) {
										$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + data[1][j].value + "</td><td>" + data[0]
											[i].value + "</td></tr>");
										tmpj++;
									}
								}
							}
							if (tmpj == 0) {
								$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td></td><td>" + data[0]
									[i].value + "</td></tr>");
							}
						}
					} else {
						$.each(data, function(key, val) {
							if (data[data.length - 1] == 1) {
								console.log(data);
								if (val.name)
									$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td></td><td>" + val.value +
										"</td></tr>");
							} else if (data[data.length - 1] == 2) {

							} else if (data[data.length - 1] == 3) {
								console.log(data);
								if (val.name)
									$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td>" + val.value +
										"</td><td></td></tr>");
							}
						});
					}

				}
			});

		});
		$('.select2').select2({
			width: 'resolve'
		});
		$("#nama").trigger("change");
		$("#biaya_admin").trigger("keyup");
		
	});
</script>