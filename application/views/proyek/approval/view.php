<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<div style="float:right">
	<h2>
		<button class="btn btn-primary" onClick="window.location.href='<?= site_url(); ?>/P_master_mappingCoa/add'">
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
	<!-- <ul class="nav nav-pills col-md-12" style="margin-bottom: 30px">
		<li class="col-md-2 active"><a data-toggle="pill" href="#home">Request</a></li>
		<li class="col-md-2"><a data-toggle="pill" href="#menu1">Approve</a></li>
		<li class="col-md-2"><a data-toggle="pill" href="#menu2">Reject</a></li>
		<li class="col-md-2"><a data-toggle="pill" href="#menu3">All</a></li>
	</ul> -->
	<div class="tab-content">
		<div id="home" class="tab-pane fade in active">


			<table id="tableDT2" class="table table-striped jambo_table bulk_action">
				<tfoot id="tfoot" style="display: table-header-group">
					<tr>
						<th>No</th>
						<th>Kode Dokumen</th>
						<th>Jenis Dokumen</th>
						<th>Tanggal Request</th>
						<th>User Request</th>
						<th>Status</th>
						<th hidden>Action</th>
					</tr>
				</tfoot>
				<thead>
					<tr>
						<th>No</th>
						<th>Kode Dokumen</th>
						<th>Jenis Dokumen</th>
						<th>Tanggal Request</th>
						<th>User Request</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($data as $key => $v) {
						++$i;
						echo '<tr>';
						echo "<td>$i</td>";
						echo "<td>$v->dokumen_code</td>";
						echo "<td>$v->dokumen_jenis</td>";
						echo "<td>$v->tgl_tambah</td>";
						echo "<td>$v->user_request</td>";
						echo "<td>$v->status_dokumen</td>";
						// if ($v->dokumen_code == 'void_pembayaran') {
						// 	echo "
						// 	<td class='col-md-1'>
						// 	<a data-toggle='modal' data-target='#modal_action_void_pembayaran' class='btn-modal btn btn-md btn-primary col-md-12' val='$v->id'>
						// 	<i class='fa fa-pencil'></i>
						// 	</a>
						// 	</td>
						// ";
						// } else {
							echo "
							<td class='col-md-1'>
							<a href='" . site_url() . "/P_approval/edit?id=$v->id' class='btn btn-md btn-primary col-md-12'>
								<i class='fa fa-pencil'></i>
							</a>
							</td>
						";
						// }
						echo '</tr>';
					}
					?>
				</tbody>

			</table>
		</div>
		<div id="menu1" class="tab-pane fade">
			<h3>Menu 1</h3>
			<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		</div>
		<div id="menu2" class="tab-pane fade">
			<h3>Menu 2</h3>
			<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
		</div>
		<div id="menu3" class="tab-pane fade">
			<h3>Menu 3</h3>
			<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
		</div>
	</div>

	<!-- (Normal Modal)-->
	<div class="modal fade" id="modal_action_void_pembayaran" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content" style="margin-top:100px;">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" style="text-align:center;">Pilih salah satu action ?<span class="grt"></span> ?</h4>
				</div>

				<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
					<span id="preloader-delete"></span>
					</br>
					<!-- <div class="col-md-12"> -->
					<a class="btn btn-danger" id="reject_void_pembayaran" val=''>Reject</a>
					<a class="btn btn-success" id="approve_void_pembayaran" val=''>Approve</a>
					<!-- </div> -->
					<button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Cancel</button>

				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$("#a").html('');
			$('.select2').select2();
			$(".btn-modal").click(function() {
				$("#reject_void_pembayaran").attr('val', $(this).attr('val'));
				$("#approve_void_pembayaran").attr('val', $(this).attr('val'));

			});
			$("#reject_void_pembayaran").click(function() {
				$.ajax({
					url: '<?= site_url(); ?>/P_approval/ajax_reject_void_pembayaran',
					method: "POST",
					data: {
						id: $(this).attr('val')
					},
					dataType: "text",
					success: function(data) {}
				});
			});
			$("#approve_void_pembayaran").click(function() {
				$.ajax({
					url: '<?= site_url(); ?>/P_approval/ajax_approve_void_pembayaran',
					method: "POST",
					data: {
						id: $(this).attr('val')
					},
					dataType: "text",
					success: function(data) {}
				});
			});
		});
		$(document).ready(function() {
			// Setup - add a text input to each footer cell
			$('#tableDT2 tfoot th').each(function() {
				var title = $(this).text();
				$(this).html('<input type="text" placeholder="Search ' + title + '" />');
			});

			// DataTable
			var table = $('#tableDT2').DataTable();

			// Apply the search
			table.columns().every(function() {
				var that = this;
				$('input', this.footer()).on('keyup change', function() {
					if (that.search() !== this.value) {
						that
							.search(this.value)
							.draw();
					}
				});
			});
		});
	</script>


	<script>
		$(".delete_data").click(function() {
			var r = confirm('Are You Sure Want To Delete This Data ?');
			if (r == true) {

				url = '<?= site_url(); ?>/P_master_mappingCoa/delete';
				var id = $(this).attr('id');

				$.ajax({
					url: url,
					method: "POST",
					data: {
						id: id
					},
					dataType: "text",
					success: function(data) {
						alert('Data berhasil dihapus...');
					}
				});
			}
		});
	</script>

	<script>
		function confirm_modal(id) {
			jQuery('#modal_delete_m_n').modal('show', {
				backdrop: 'static',
				keyboard: false
			});
			document.getElementById('delete_link_m_n').setAttribute("href", "<?= site_url('P_master_mappingCoa/delete?id="+id+"'); ?>");
			document.getElementById('delete_link_m_n').focus();
		}
	</script>