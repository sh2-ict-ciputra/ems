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
	<table id="tableDT2" class="table table-striped jambo_table bulk_action">
		<tfoot id="tfoot" style="display: table-header-group">
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Kode</th>
				<th>Keterangan</th>
				<th hidden>Action</th>
				<th hidden>Delete</th>
			</tr>
		</tfoot>
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Kode</th>
				<th>Keterangan</th>
				<th>Action</th>
				<!-- <th>Delete</th> -->
			</tr>
		</thead>
		<tbody>
			<?php
            $i = 0;
			foreach ($data as $key => $v):
				++$i;
			?>
			<tr>
				<td><?=$i?></td>
				<td><?=$v->name?></td>
				<td><?=$v->code?></td>
				<td><?=$v->description?></td>
				<td class='col-md-1'>

					<?php if($v->code == 'void_pembayaran'):?>
					<a href='<?=site_url("Setting/P_setting_approval/edit/2?id=$v->id")?>' class='btn btn-md btn-primary col-md-12'>

					<?php else:?>
						<a href='<?=site_url("Setting/P_setting_approval/edit/1?id=$v->id")?>' class='btn btn-md btn-primary col-md-12'>
					<?php endif;?>
						<i class='fa fa-pencil'></i>
					</a>
				</td>

				<!-- <td class='col-md-1'>
					<a href='#'  class='btn btn-md btn-danger col-md-12' data-toggle='modal' onclick='confirm_modal(<?=$v->id?>)'data-target='#myModal'> 
						<i class='fa fa-trash'></i>
					</a>
				</td>  -->

             </tr>
            <?php endforeach;?>
		</tbody>
		
	</table>
