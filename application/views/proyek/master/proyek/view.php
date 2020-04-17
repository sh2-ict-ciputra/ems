<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
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
</div>
<div class="x_content">
	<div class="row">
		<div class="col-sm-12">
			<div class="card-box table-responsive">
				<table class="table table-striped jambo_table bulk_action tableDT">
					<tfoot id="tfoot" style="display: table-header-group">
						<tr>
							<th>No</th>
							<th>Kode Proyek</th>
							<th>Nama Proyek</th>
							<th>Alamat</th>
							<th>Phone</th>
							<th>Fax</th>
							<th>Email</th>
							<th>Kawasan</th>
						</tr>
					</tfoot>
					<thead>
						<tr>
							<th>No</th>
							<th>Kode Proyek</th>
							<th>Nama Proyek</th>
							<th>Alamat</th>
							<th>Phone</th>
							<th>Fax</th>
							<th>Email</th>
							<th>Kawasan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 0;
						foreach ($data as $key => $v) {
							++$i;
							echo '<tr>';
							echo "<td>$i</td>";
							echo "<td>$v[code]</td>";
							echo "<td>$v[name]</td>";
							echo "<td>$v[address]</td>";
							echo "<td>$v[phone]</td>";
							echo "<td>$v[fax]</td>";
							echo "<td>$v[email]</td>";
							echo "
                                    <td>
                                    <a href='" . site_url() . "/P_master_kawasan' class='btn btn-primary col-md-10'>
                                        Kawasan</i>
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
	</div>
</div>
</div>
</div>