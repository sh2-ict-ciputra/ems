<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<div style="float:right">
	<h2>
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
                <th>Level</th>
                <th>Read</th>
                <th>Create</th>
                <th>Update</th>
                <th>Delete</th>
                <th hidden>Action</th>
			</tr>
		</tfoot>
		<thead>
			<tr>
            <th>No</th>
                <th>Level</th>
                <th>Read</th>
                <th>Create</th>
                <th>Update</th>
                <th>Delete</th>				
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
                echo "<td>$v->level</td>";
                echo "<td>$v->read</td>";
                echo "<td>$v->create</td>";
                echo "<td>$v->update</td>";
                echo "<td>$v->delete</td>";
                echo "
                        <td class='col-md-1'>
                        <a href='".site_url()."/Setting/Akun/Permission_menu/edit?id=$v->id' class='btn btn-md btn-primary col-md-12'>
                            <i class='fa fa-pencil'></i>
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

				url = '<?=site_url(); ?>/Setting/Akun/Permission_menu/ajax_delete';
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
