<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<div style="float:right">
	<h2>
		<button class="btn btn-primary" onClick="window.location.href='<?=site_url(); ?>/Setting/Akun/Group_level/add'">
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
	<table id="tableDT2" class="data-view table table-striped jambo_table bulk_action">
		<tfoot id="tfoot" style="display: table-header-group">
			<tr>
                <th>No</th>
                <th>Project</th>
                <th>Jabatan</th>
                <th>User</th>
                <th>Level</th>
				<th>Keterangan</th>
				<th hidden>Delete</th>
			</tr>
		</tfoot>
		<thead>
			<tr>
				<th>No</th>
                <th>Project</th>
                <th>Jabatan</th>
                <th>User</th>
                <th>Level</th>
				<th>Keterangan</th>
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
                echo "<td>$v->project</td>";
                echo "<td>$v->jabatan</td>";
                echo "<td>$v->user</td>";
                echo "<td>$v->level</td>";
                echo "<td>$v->description</td>";
                echo "
                        <td class='col-md-1'>
                        <a id='".$v->id."' class='delete-data btn btn-md btn-danger col-md-12'>
                            <i class='fa fa-trash'></i>
                        </a>
                        </td>
                    ";
                echo '</tr>';
            }
            ?>
		</tbody>
		
	</table>


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
		function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
		$(".delete-data").click(function () {
			var r = confirm('Yakin untuk menghapus data '+$(this).parent().parent().children().eq(1).html()+' ?');
			if (r == true) {

				url = '<?=site_url(); ?>/Setting/Akun/Group_level/ajax_delete';
				var id = $(this).attr('id');

				$.ajax({
					url: url,
					method: "POST",
					data: {
						id: id
					},
					dataType: "json",
					success: function (data) {
						if (data.status){
                            notif('Sukses', data.message, 'success');
							console.log(id);
							$(".delete-data#"+id).parents("tr").hide()						
						}
                        else
                            notif('Gagal', data.message, 'danger');					
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
