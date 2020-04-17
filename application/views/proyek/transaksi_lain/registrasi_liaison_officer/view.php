<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-dark" onClick="window.location.href='<?= site_url(); ?>/transaksi_lain/p_registrasi_liaison_officer/cetakform'">
			<i class="fa fa-paper-plane-o"></i>
			Cetak Form
		</button>
		<button class="btn btn-primary" onClick="window.location.href='<?= site_url(); ?>/transaksi_lain/p_registrasi_liaison_officer/add'">
			<i class="fa fa-plus"></i>
			Tambah
		</button>
		<button class="btn btn-warning" onClick="window.history.back()" disabled>
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/p_registrasi_liaison_officer'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<div class="row">
		<div class="col-sm-12">
			<div class="card-box table-responsive">
				<table class="table table-striped jambo_table bulk_action tableDT">
					<thead>
						<tr>
							<th>No.</th>
							<th>Unit</th>
							<th>Customer</th>
							<th>Paket LOI</th>
							<th>Follow Up</th>
							<th>Status_dokumen</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $k => $v) : ?>
							<tr>
								<td><?= $k+1?></td>
								<td><?= $v->unit ?></td>
								<td><?= $v->customer ?></td>
								<td><?= $v->paket ?></td>
								<td><?= $v->follow_up ?></td>
								<td><?= $v->status_dokumen ?></td>
								<td class='col-md-2'>
									<a title='Edit' href="<?= site_url("transaksi_lain/P_registrasi_liaison_officer/edit?id=$v->id") ?>" class='btn btn-xs btn-primary '>
										<i class='fa fa-pencil'></i>
									</a>
									<a title='Upload' data-toggle='modal' onclick='upload_modal(<?= $v->id ?>)' class='btn btn-xs btn-primary '>
										<i class='fa fa-upload'></i>
									</a>
								</td>
							<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
</div>



<!-- (Normal Modal)-->
<div class="modal fade" id="modal_delete_m_n" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content" style="margin-top:100px;">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" style="text-align:center;">Apakah anda yakin untuk mendelete data ini ?<span class="grt"></span> ?</h4>
			</div>

			<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
				<span id="preloader-delete"></span>
				</br>
				<a class="btn btn-danger" id="delete_link_m_n" href="">Delete</a>
				<button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Cancel</button>

			</div>
		</div>
	</div>
</div>

<div class="x_content">
	<div class="modal fade" id="modal_upload" data-backdrop="static" data-keyboard="false">
		<div class="" style="width:35%;margin:auto">
			<div class="modal-content" style="margin-top:100px;">
				<form id="form" data-parsley-validate="" enctype="multipart/form-data" class="form-horizontal form-label-left" method="post" action="<?= site_url(); ?>/transaksi_lain/P_registrasi_liaison_officer/upload?id=<?= $v->id ?>">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" style="text-align:center;">Upload Dokumen<span class="grt"></span></h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal ">

							<!-- Apakah anda yakin untuk menyimpan data deposit<br>
							( Note : Pastikan anda telah benar-benar menerima uang deposit ) -->
							<div class="clearfix"></div>

							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
								<div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="modal_label_biaya_admin">Upload Dokumen </label>
									<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
										<input type="file" name="dokumen" required id="dokumen" class="btn btn-dark">
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Biaya Admin (Metode Penagihan)</label>
									<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
										<input id="biaya_admin_penagihan" type="text" class="form-control" readonly>
									</div>
								</div> -->

							</div>
						</div>
					</div>
					<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="delete_cancel_link">Close</button>
						<button type="submit" class="btn btn-success">Upload</button>
						<!-- <a class="btn btn-success" id="button-modal-upload" href="">Upload</a> -->
						<!-- <button type="button" class="btn btn-success" data-dismiss="modal" id="button-modal-upload">Upload</button> -->
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	function notif(title, text, type) {
		new PNotify({
			title: title,
			text: text,
			type: type,
			styling: 'bootstrap3'
		});
	}

	function confirm_modal(id) {
		jQuery('#modal_delete_m_n').modal('show', {
			backdrop: 'static',
			keyboard: false
		});
		document.getElementById('delete_link_m_n').setAttribute("href", "<?= site_url('/transaksi_lain/P_registrasi_liaison_officer/delete?id="+id+"'); ?>");
		document.getElementById('delete_link_m_n').focus();
	}

	function upload_modal(id) {
		jQuery('#modal_upload').modal('show', {
			backdrop: 'static',
			keyboard: false,
			contentType: false
		});
		document.getElementById('button-modal-upload').setAttribute("href", "<?= site_url('/transaksi_lain/P_registrasi_liaison_officer/upload?id="+id+"'); ?>");
		document.getElementById('button-modal-upload').focus();
	}
</script>