<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<div style="float:right">
	<h2>
		<button class="btn btn-primary" onClick="window.location.href='<?= site_url(); ?>/P_master_item_survei_loi/add'">
			<i class="fa fa-plus"></i>
			Tambah
		</button>
		<button class="btn btn-warning" onClick="window.history.back()" disabled>
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


<div class="x_content">
	<table id="tableDT2" class="table table-striped jambo_table bulk_action">
		<tfoot id="tfoot" style="display: table-header-group">
			<tr>
				<th>Nama</th>
				<th>Harga Satuan</th>
				<th>Satuan</th>
				<th>Deskripsi</th>
				<th hidden>Action</th>
				<th hidden>Delete</th>
			</tr>
		</tfoot>
		<thead>
			<tr>
				<th>Nama</th>
				<th>Harga Harga</th>
				<th>Satuan</th>
				<th>Deskripsi</th>
				<th>Action</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data as $key => $v) : ?>
				<tr>
					<td><?= $v->name ?></td>
					<td><?= number_format($v->nilai) ?></td>
					<td><?= $v->satuan ?></td>
					<td><?= $v->description ?></td>
					<td class='col-md-1'>
						<a href='<?= site_url("P_master_item_survei_loi
			/edit?id=$v->id") ?>' class='btn btn-md btn-primary col-md-12'>
							<i class='fa fa-pencil'></i>
						</a>
					</td>
					<td class='col-md-1'>
						<a href='#' class='btn-delete btn btn-md btn-danger col-md-12' data-toggle='modal' data-target='#modal_delete' item_id='<?=$v->id?>'> 
							<i class='fa fa-trash'></i>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>

	</table>

</div>
</div>
</div>

<div class="modal fade" id="modal_delete" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content" style="margin-top:100px;">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" style="text-align:center;">Apakah anda yakin untuk mendelete data ini ?</h4>
			</div>

			<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
				<span id="preloader-delete"></span>
				</br>
				<button class="btn btn-danger" id="btn-delete">Delete</button>
				<button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Cancel</button>

			</div>
		</div>
	</div>
</div>
<script>
	$(function() {
		function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
		$(".btn-delete").click(function() {
			$("#btn-delete").attr('item_id', $(this).attr('item_id'));
		});
		$("#btn-delete").click(function() {
			$.ajax({
				type: "POST",
				data: {
					id: $(this).attr('item_id')
				},
				url: "<?= site_url('P_master_item_survei_loi/ajax_delete') ?>",
				dataType: "json",
				success: function(data) {
					if (data.status == 1) {
						notif('Sukses', data.message, 'success')
						setTimeout(function() {
							if (!alert('Halaman akan di refresh otomatis untuk update data!')) {
								window.location.reload();
							}
						}, 2 * 1000);
					} else
						notif('Gagal', data.message, 'danger')
				}
			});
		});

	});
</script>