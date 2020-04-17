<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
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
	<table id="tableDT2" class="table table-striped jambo_table bulk_action">
		<tfoot id="tfoot" style="display: table-header-group">
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Kode</th>
				<th>Value</th>
				<th>Keterangan</th>
				<th hidden>Action</th>
			</tr>
		</tfoot>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Kode</th>
				<th>Value</th>
				<th>Keterangan</th>
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
				echo "<td>$v->name</td>";
				echo "<td>$v->code</td>";
				echo "<td>$v->value</td>";
				echo "<td>$v->description</td>";
				echo "
                        <td class='col-md-1'>
                        <a href='" . site_url() . "/Setting/P_parameter_project/edit?id=$v->jenis_id' class='btn btn-md btn-primary col-md-12'>
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