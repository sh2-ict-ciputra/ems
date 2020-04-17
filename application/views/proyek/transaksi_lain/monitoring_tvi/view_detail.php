<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
           
            <button class="btn btn-warning" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_monitoring_tvi'">
                <i class="fa fa-arrow-left"></i>
                Back
            </button>
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_monitoring_tvi'">>
                <i class="fa fa-repeat"></i>
                Refresh
            </button>
        </h2>
    </div>
    <div class="clearfix"></div>
</div>
    <div class="x_content">
     <div id="range" class="" role="tabpanel" data-example-id="togglable-tabs">
      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Tagihan</a>
        </li>
        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Pembayaran</a>
        </li>
      </ul>
      <div id="myTabContent" class="tab-content">
         <input type="hidden" name="id"  id="id" value="<?=$dataRegistrasi->id; ?>" >
    
    
        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
          <p><table class="table table-responsive">
            <thead>
              <tr>
          <th>No</th>
                <th>Kode Bill</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Harga</th>
                <th>TRP-TV</th>
                <th>Paket Layanan</th>
                <th>Mulai</th>
                <th>Berakhir</th>
                <th>Jumlah TV</th>
                <th>BWIDTH</th>
                <th>No Kwitansi</th>
                <th>Action</th>
                <th>Delete</th>

        <th></th>
              </tr>
            </thead>
            <tbody>



               <?php



                
               

             

               $tanggal_berakhir = $dataCekTanggal->tanggal_berakhir;

               if ( $tanggal_berakhir != '1900-01-01' )  
               {
             
               $today             = date('Y-m-d');	

               }


               
				  if ($today >=   $tanggal_berakhir )
						
					{
          
                       echo "<a href='".site_url()."/transaksi_lain/P_monitoring_tvi/add_tagihan?id=$dataRegistrasi->id' class='btn btn-sm btn-success col-md-2'>
                             <i class='fa fa-plus'> Add Tagihan</i>
                            </a>";

          }

          else

          {



          }

                ?>
        
                 <?php
         
                     $i = 0;
                     foreach ($dataListTagihan as $key => $v) {
                         ++$i;
                         echo'<tr>';
                         echo "<td>$i</td>";
                         echo "<td>$v[kode_bill]</td>";
                         echo "<td>$v[tanggal]</td>";
                         echo "<td>$v[total]</td>";
                         echo "<td>$v[harga]</td>";
                         echo "<td></td>";
                         echo "<td>$v[paket_name]</td>";
                         echo "<td>$v[tanggal_mulai]</td>";
                         echo "<td>$v[tanggal_berakhir]</td>";
                         echo "<td></td>";
                         echo "<td>$v[bandwidth]</td>";
                         echo "<td>$v[no_fisik_kwitansi]</td>";
                         echo"
                             <td class='col-md-1'>
                             <a href='".site_url()."/transaksi_lain/P_monitoring_tvi/edit_tagihan?id=$v[id_tagihan]' class='btn btn-xs btn-primary '>
                                 <i class='fa fa-pencil'></i>
                             </a>
                             </td>
                         ";
                         echo "
                         <td class='col-md-1'>
                        <a href='#'  class='btn btn-xs btn-danger ' data-toggle='modal' 
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
        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
          <p><table class="table table-responsive">
          <thead>
            <tr>
        <th>No</th>
              <th>Kode</th>
              <th>Mulai</th>
              <th>Berakhir</th>
              <th>Paket Layanan</th>
              <th>TGH+(PPN)</th>
              <th>TGH(DPP)</th>
              <th>Tanggal</th>
              <th>No Kwitansi</th>
            </tr>
          </thead>
      <tbody>
      
        

              <?php
         
                     $i = 0;
                     foreach ($dataListPembayaran as $key => $v) {
                         ++$i;
                         echo'<tr>';
                         echo "<td>$i</td>";
                         echo "<td>$v[kode_bill]</td>";
                         echo "<td>$v[tanggal_mulai]</td>";
                         echo "<td>$v[tanggal_berakhir]</td>";
                         echo "<td>$v[paket_name]</td>";
                         echo "<td></td>";
                         echo "<td></td>";
                         echo "<td>$v[tanggal_berakhir]</td>";
                         echo "<td>$v[bandwidth]</td>";
                         echo "<td>$v[no_fisik_kwitansi]</td>";
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
