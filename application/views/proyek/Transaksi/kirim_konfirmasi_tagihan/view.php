<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<div style="float:right">
	<h2>
		<button id="btn-kirim-email" class="btn btn-primary">
			<i class="fa fa-plus"></i>
			Kirim Email
		</button>
		<button id="btn-kirim-sms" class="btn btn-primary">
			<i class="fa fa-plus"></i>
			Kirim SMS
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
	<form>
		<table id="tableDTServerSite" class="table table-striped jambo_table bulk_action">
			<tfoot id="tfoot" style="display: table-header-group">
				<tr>
					<th>Check</th>
					<th>Kawasan</th>
					<th>Blok</th>
					<th>No. Unit</th>
					<th>Tujuan</th>
					<th>Pemilik</th>
					<th>Email</th>
					<th>SMS</th>
					<th>Surat</th>
					<th>Dokumen Live</th>
					<th>Dokumen Downloaded</th>
				</tr>
			</tfoot>
			<thead>
				<tr>
					<th class="col-md-1 col-sm-1 col-lg-1 col-xs-1" id="di_bayar_dengan_table">
						<input id="check-all" type='checkbox' class='flat'> Check
					</th>
					<th>Kawasan</th>
					<th>Blok</th>
					<th>No. Unit</th>
					<th>Tujuan</th>
					<th>Pemilik</th>
					<th>Email</th>
					<th>SMS</th>
					<th>Surat</th>
					<th>Dokumen Live</th>
					<th>Dokumen Downloaded</th>
				</tr>
			</thead>
			<tbody>
			</tbody>

		</table>
	</form>

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
					<br>
					<a class="btn btn-danger" id="delete_link_m_n" href="">Delete</a>
					<button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Cancel</button>

				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$("#a").html('');
			$('.select2').select2();

		});
		$(document).ready(function() {
			$('#tableDTServerSite tfoot th').each( function () {
				var title = $(this).text();
				$(this).html( '<input type="text" placeholder="Filter '+title+'" />' );
			} );
			var table = 
				$('#tableDTServerSite').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": "<?=site_url("Transaksi/P_kirim_konfirmasi_tagihan/ajax_get_view")?>",
					"order": [[ 1, "asc" ]]

				});

			table.columns().every( function () {
				var that = this;
				$( 'input', this.footer() ).on( 'keyup change', function () {
					if ( that.search() !== this.value ) {
						that
							.search( this.value )
							.draw();
					}
				} );
			} );

			$("table").on("ifChanged", "#check-all", function() {
				if ($("#check-all").is(":checked")) {
					$(".table-check").iCheck("check");
				}else{
					$(".table-check").iCheck("uncheck");
				}
			});
			// Setup - add a text input to each footer cell
			$('#tableDT2 tfoot th').each(function() {
				var title = $(this).text();
				$(this).html('<input type="text" placeholder="Search ' + title + '" />');
			});

			// DataTable
			// var table = $('#tableDT2').DataTable({
			// 	"iDisplayLength": 100
			// });

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
		$("#btn-kirim-email").click(function() {
			var unit_id = $("input[name='unit_id[]']").map(function() {
				if ($(this).is(":checked")) {
					return $(this).attr("val");
				}
			}).get();
			$.ajax({
				type: "POST",
				data: {
					unit_id: unit_id
				},
				url: "<?= site_url() ?>/Transaksi/P_kirim_konfirmasi_tagihan/kirim_email",
				dataType: "json",
				success: function(data) {
					if (data)
						notif('Sukses', 'Pengiriman Email Sukses', 'success');
					else
						notif('Gagal', 'Pengiriman Email Gagal', 'danger');
				}

			});
		})
		$("#btn-kirim-sms").click(function() {
			var unit_id = $("input[name='unit_id[]']").map(function() {
				if ($(this).is(":checked")) {
					return $(this).attr("val");
				}
			}).get();
			$.ajax({
				type: "POST",
				data: {
					unit_id: unit_id
				},
				url: "<?= site_url() ?>/Transaksi/P_kirim_konfirmasi_tagihan/kirim_sms",
				dataType: "json",
				success: function(data) {
					if (data)
						notif('Sukses', 'Pengiriman SMS Sukses', 'success');
					else
						notif('Gagal', 'Pengiriman SMS Gagal', 'danger');
				}

			});
		})
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