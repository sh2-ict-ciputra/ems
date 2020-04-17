<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<div style="float:right">
	<h2>
		<button class="btn btn-primary" onClick="window.location.href='<?=site_url(); ?>/P_master_peruntukan_loi/add'">
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
				<th>No</th>
				<th>Jenis Liaison Officer</th>
				<th>Nama Peruntukan</th>
				<th hidden>Action</th>
				<th hidden>Delete</th>
			</tr>
		</tfoot>
		<thead>
			<tr>
                <th>No</th>
				<th>Jenis Liaison Officer</th>
				<th>Nama Peruntukan</th>
				<th>Action</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php
            $i = 0;
            foreach ($data as $key => $v) {
                ++$i;
                echo '<tr>';
                echo "<td>$i</td>";
                echo "<td>$v->nama_jenis</td>";
                echo "<td>$v->nama</td>";
                echo "
                        <td class='col-md-1'>
                        <a href='".site_url()."/P_master_peruntukan_loi/edit?id=$v->id' class='btn btn-md btn-primary col-md-12'>
                            <i class='fa fa-pencil'></i>
                        </a>
                        </td>
					";
				echo "<td class='col-md-1'>
				<a href='#'  class='btn btn-md btn-danger col-md-12' data-toggle='modal' 
				onclick='confirm_modal($v->id)'";
				echo " data-target='#myModal'> ";
				echo "<i class='fa fa-trash'></i>
					</a>
					</td> ";
				echo '</tr>';
				
            }
            ?>
		</tbody>
		
	</table>

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
		$(document).ready(function () {
			$("#a").html('');
			$('.select2').select2();

		});
		$(document).ready(function() {
			// Setup - add a text input to each footer cell
			$('#tableDT2 tfoot th').each( function () {
				var title = $(this).text();
				$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
			} );
		
			// DataTable
			var table = $('#tableDT2').DataTable();
		
			// Apply the search
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
		} );
	</script>

	<script>
		$(".delete_data").click(function () {
			var r = confirm('Are You Sure Want To Delete This Data ?');
			if (r == true) {

				url = '<?=site_url(); ?>/Setting/Akun/user/delete';
				var id = $(this).attr('id');

				$.ajax({
					url: url,
					method: "POST",
					data: {
						id: id
					},
					dataType: "text",
					success: function (data) {
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
			document.getElementById('delete_link_m_n').setAttribute("href",
				"<?= site_url('P_master_peruntukan_loi/delete?id=" + id + "'); ?>");
			document.getElementById('delete_link_m_n').focus();
		}
	</script>