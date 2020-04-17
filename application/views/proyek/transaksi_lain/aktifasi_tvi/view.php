<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
            <button class="btn btn-primary" onClick="window.location.href='<?=site_url(); ?>/transaksi_lain/p_aktifasi_tvi/add'">
                <i class="fa fa-plus"></i>                
                Tambah
            </button>
            <button class="btn btn-warning" onClick="window.history.back()" disabled>
                <i class="fa fa-arrow-left"></i>
                Back
            </button>
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_aktifasi_tvi'">>
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
                  <th>Customer</th>
                  <th>Paket Layanan</th>
                  <th>Jenis Layanan</th>
                  <th>Mulai</th>
                  <th>Berakhir</th>
                  <th>Status</th>
                  <th>Biaya Tambahan</th>
                  <th>Status Bayar Registrasi</th>
                  <th>Status Bayar Biaya Tambahan</th>
                  <th>Status Aktifasi</th>
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
                         echo "<td>$v[customer_name]</td>";
                         echo "<td>$v[paket_name]</td>";
                         echo "<td>$v[jenis_pemasangan]</td>";
                         echo "<td>$v[tanggal_mulai]</td>";
                         echo "<td>$v[tanggal_berakhir]</td>";
                         echo '<td>';
                         echo $v['active'] ? 'Aktif' : 'Tidak Aktif';
                         echo '</td>';
                         echo"
                          <td class='col-md-1'>
                           <a title='Biaya Tamabahan' href='".site_url()."/transaksi_lain/P_biaya_tambahan_tvi/add_reg?id=$v[id]' class='btn btn-xs btn-warning'>
                              <i class='fa fa-money'></i>
                           </a>
                          </td>
                       ";

                       
                          
                        echo "
                        <td class='col-md-1'>
                        <font color='blue'><b>" .$v['status_bayar']. "</b></font>
                        </td>

                        ";

                          
                        echo "
                        <td class='col-md-1'>
                        <font color='blue'><b>" .$v['status_bayar_biaya']. "</b></font>
                        </td>

                        ";
               
                        
                        if (($v['active'] == 0 )  && ($v['status_bayar'] == 'Lunas'  ) )
                       { 
                          echo"
                             <td class='col-md-1'>
                             <a title = 'Aktifasi' href='".site_url()."/transaksi_lain/P_aktifasi_tvi/add?id=$v[id]' class='btn btn-sm btn-warning col-md-12'>
                             <i class='fa fa-pencil'></i>
                            </a>
                             </td>
                         ";

                       
                        } else if (( $v['active'] == 1) &&  ($v['status_bayar'] == 'Lunas'  ) ) {

                           echo "
                                 <td class='col-md-1'>
                                <font color='blue'><b>Active</b></font>
                                 </td>

                                 ";

                         
                        } else if (( $v['active'] == 0) &&  ($v['status_bayar'] == 'Lunas'  ) ) {

                             echo "
                                <td class='col-md-1'>
                                <font color='blue'><b> Not Active </b></font>
                                </td>
         
                             ";



                        } else if (( $v['active'] == 0) &&  ($v['status_bayar_biaya'] == 'Lunas'  ) ) {

                                echo "
                                   <td class='col-md-1'>
                                   <font color='blue'><b> Not Active </b></font>
                                   </td>
            
                                ";
                        

                         } else if ($v['status_bayar'] == 'Tagihan'  ) {


                            echo "  <td class='col-md-1'> 
                                    <font color='red'><b>Not Yet Paid</b></font>
                                    </td>

                                    ";
                         }



                       

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
			document.getElementById('delete_link_m_n').setAttribute("href", "<?= site_url('P_master_cara_pembayaran/delete?id="+id+"'); ?>");
			document.getElementById('delete_link_m_n').focus();
		}

	</script>
