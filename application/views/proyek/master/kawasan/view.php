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
					<thead>
						<tr>
							<th>No</th>
							<th>Kode Kawasan</th>
							<th>Nama Kawasan</th>
							<th>Blok</th>
						</tr>
					</thead>
					<tfoot id="tfoot" style="display: table-header-group">
						<tr>
							<th>No</th>
							<th>Kode Kawasan</th>
							<th>Nama Kawasan</th>
							<th hidden>Blok</th>
						</tr>
					</tfoot>
					<tbody>
						<?php
                            $i = 0;
                            foreach ($data as $key => $v) {
                                ++$i;
                                echo '<tr>';
                                echo "<td>$i</td>";
                                echo "<td>$v[kawasan_code]</td>";
                                echo "<td>$v[kawasan_name]</td>";
                                echo "
                                
                                    <td>
                                    <a href='".site_url()."/P_master_blok?id=$v[id]' class='btn btn-primary col-md-10'>
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                    </td>
                                ";

                                echo '</tr>';
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
