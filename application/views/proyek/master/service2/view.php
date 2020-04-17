<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>

        <style>
            th{
                border : 0.5px solid #ddd;
            }
        </style>
            <div style="float:right">
                <h2>
                    <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/P_master_service/add'">
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
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <table class="table table-striped jambo_table bulk_action tableDT">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Kode</th>
                                    <th rowspan="2">Nama Service</th>
                                    <th colspan="2" style="text-align : center">Service</th>
                                    <th colspan="3" style="text-align : center">PPN</th>
                                    <!-- <th>Jenis Service</th></TH>-->
                                    <th rowspan="2">Keterangan</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2">Action</th>
									<th rowspan="2">Delete</th>
                                </tr>
                                <tr>
                                    <th>PT</th>
                                    <th>COA</th>
                                    <th>PT</th>
                                    <th>COA</th>
                                    <th>(%)</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($data as $key => $v){
                                $i++;
                                echo'<tr>';
                                    echo "<td>$i</td>";
                                    echo "<td>$v[code]</td>";
                                    echo "<td>$v[name]</td>";
                                    echo "<td>$v[pt_service]</td>";
                                    echo "<td>$v[coa_service]</td>";
                                    echo "<td>$v[pt_ppn]</td>";
                                    echo "<td>$v[coa_ppn]</td>";
                                    echo "<td>";
                                    echo($v['ppn']=="Aktif"?'10%':'Tidak Ada');
                                    echo"</td>";
                                    //echo("<td>");
                                    //echo($v['jenis_service']?'Retribusi':'Non Retribusi');
                                    //echo("</td>");
                                    echo "<td>$v[description]</td>";
                                    echo "<td>";
                                    echo ($v['active']?'Aktif':'Tidak Aktif');
                                    echo "</td>";
                                    echo "
                                        <td>
                                        <a href='".site_url()."/P_master_service/edit?id=$v[id]' class='btn btn-primary col-md-10'>
                                            <i class='fa fa-pencil'></i>
                                        </a>
                                        </td>
                                    ";
									echo "
										<td class='col-md-1'>
										<a href='#'  class='btn btn-md btn-danger col-md-12' data-toggle='modal' 
										onclick='confirm_modal(
										$v[id]
										)'";
									echo " data-target='#myModal'> ";
									echo "<i class='fa fa-trash'></i>
										</a>
										</td> ";

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
	<script>
		$(document).ready(function () {
			$('#tableDT2').DataTable();
			$("#a").html('');
			$('.select2').select2();

		});

	</script>


	

	<script>
		function confirm_modal(id) {
			jQuery('#modal_delete_m_n').modal('show', {
				backdrop: 'static',
				keyboard: false
			});
			document.getElementById('delete_link_m_n').setAttribute("href", "<?= site_url('P_master_service/delete?id="+id+"'); ?>");
			document.getElementById('delete_link_m_n').focus();
		}

	</script>