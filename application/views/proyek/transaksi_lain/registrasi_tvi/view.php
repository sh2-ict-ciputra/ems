<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
            <button class="btn btn-primary" onClick="window.location.href='<?=site_url(); ?>/transaksi_lain/p_registrasi_tvi/add'">
                <i class="fa fa-plus"></i>                
                Tambah
            </button>
            <button class="btn btn-warning" onClick="window.history.back()" disabled>
                <i class="fa fa-arrow-left"></i>
                Back
            </button>
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_registrasi_tvi'">>
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
                 <th>Unit</th>
                 <th>Customer</th>
                 <th>Jenis Layanan</th>
                 <th>Paket</th>
                 <th>Registrasi</th>
                 <th>Harga</th>
                 <th>Status Bayar</th>
                 <th>Status Aktifasi</th>
                 <th>Pembayaran</th>
                 <th>Action</th>
                 <th></th>
                 <th></th>
                 </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                     foreach ($data as $key => $v) {
                         ++$i;
                       echo'<tr>';
                       echo "<td>$i</td>";
                       echo "<td>$v[unit]</td>";
                       echo "<td>$v[customer]</td>";
                       echo "<td>$v[jenis_layanan]</td>";
                       echo "<td>$v[jenis_paket]</td>";
                       echo "<td></td>";
                       echo '<td>'.number_format($v['total']).'</td>';
                       echo "<td>$v[status_bayar]</td>";
                       echo '<td>';
                       echo $v['active'] ? 'Aktif' : 'Tidak Aktif';
                       echo '</td>';
                     //     if ($v['status_bayar_biaya'] == 0 )
                     //   { 
                     //   echo"
                     //      <td class='col-md-1'>
                     //       <a title='Edit' href='".site_url()."/transaksi_lain/P_biaya_tambahan_tvi/add_reg?id=$v[id]' class='btn btn-xs btn-warning '>
                     //          <i class='fa fa-money'></i>
                     //       </a>
                     //      </td>
                     //   ";
                     //     } else if  ($v['status_bayar_biaya'] == 1 )
                     //     {
                     //    echo"
                     //      <td class='col-md-1'>
                     //       <a title='Edit' href='#' class='btn btn-xs btn-warning 'disabled>
                     //          <i class='fa fa-money'></i>
                     //       </a>
                     //      </td>
                     //   "; }

                     if ($v['status_bayar'] == 'Tagihan' )
                     {  
                     echo"
                        <td class='col-md-1'>
                         <a title='Pembayaran' href='".site_url()."/transaksi_lain/P_pembayaran_tvi/add_reg?id=$v[id]' class='btn btn-xs btn-success'>
                            <i class='fa fa-money'></i>
                         </a>
                        </td>
                     ";   } else if ( $v['status_bayar'] == 'Lunas')  {
                       echo"
                        <td class='col-md-1'>
                         <a title='Pembayaran' href='#' class='btn btn-xs btn-success' disabled>
                            <i class='fa fa-money'></i>
                         </a>
                        </td>
                    ";   } else if ( $v['status_bayar'] == 'Pemutihan')  {
                      echo"
                       <td class='col-md-1'>
                        <a title='Pembayaran' href='#' class='btn btn-xs btn-success' disabled>
                           <i class='fa fa-money'></i>
                        </a>
                       </td>
                    ";   }
                       if ($v['unit'] != 'non_unit' )
                       { 
                       echo"
                          <td class='col-md-1'>
                           <a title='Edit' href='".site_url()."/transaksi_lain/P_registrasi_tvi/edit?id=$v[id]' class='btn btn-xs btn-primary '>
                              <i class='fa fa-pencil'></i>
                           </a>
                          </td>
                       ";
                         } else if ( $v['unit'] == 'non_unit')  {
                        echo"
                          <td class='col-md-1'>
                           <a title='Edit' href='".site_url()."/transaksi_lain/P_registrasi_tvi/edit_non_unit?id=$v[id]' class='btn btn-xs btn-primary '>
                              <i class='fa fa-pencil'></i>
                           </a>
                          </td>
                       "; }
                       
                       echo "
                        <td class='col-md-1'>
					              <a title='Delete' href='#'  class='btn btn-xs btn-danger ' data-toggle='modal' 
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
		function confirm_modal(id) {
			jQuery('#modal_delete_m_n').modal('show', {
				backdrop: 'static',
				keyboard: false
			});
			document.getElementById('delete_link_m_n').setAttribute("href", "<?= site_url('/transaksi_lain/P_registrasi_tvi/delete?id="+id+"'); ?>");
			document.getElementById('delete_link_m_n').focus();
		}

	</script>
