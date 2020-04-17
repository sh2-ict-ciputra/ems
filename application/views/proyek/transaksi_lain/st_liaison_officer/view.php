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
                            <th>Unit</th>
                            <th>Customer</th>
                            <th>Paket LOI</th>
                            <!-- <th>Paket TV</th>
                            <th>Paket Internet</th> -->
                            <th>Registrasi</th>
                            <th>Total Bayar</th>
                            <!-- <th>Status Bayar</th> -->
                            <th>Status Dokumen</th>
                            <!-- <th>Pembayaran</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                <tbody>
                <?php
                    $i = 0;
                    foreach($data as $v){
                      ++$i;
                      echo "<tr>";
                      echo "<td>$i</td>";
                      echo "<td>$v[unit]</td>";
                      echo "<td>$v[customer_name]</td>";
                      echo "<td>$v[nama_paket]</td>";
                      echo "<td>$v[nomor_registrasi]</td>";
                      echo "<td>".number_format($v['total'])."</td>";
                      // if($v['status_bayar']=='1'){
                      //   echo "<td><span class='label label-success'>Sudah Bayar</span></td>";
                      // }else{
                      //   echo "<td><span class='label label-warning'>Belum Bayar</span></td>";
                      // }
                     
                      if($v['status_dokumen'] == 7 || $v['status_dokumen'] == 4){
                        echo "<td><span class='label label-warning'>Belum Serah Terima</span></td>";
                       }elseif($v['status_dokumen'] == 5){
                        echo "<td><span class='label label-success'>Sudah Serah Terima</span></td>";
                       }
                      if ($v['unit'] != 'non_unit' )
                       {
                        if($v['status_dokumen'] == 7 || $v['status_dokumen'] == 4){
                          echo "<td class='col-md-1'>";
                          echo "<a title='Serah Terima' href='".site_url()."/transaksi_lain/P_st_liaison_officer/add?id=$v[id]' class='btn btn-xs btn-primary '>
                                      <i class='fa fa-pencil-square-o'></i>
                                  </a>";
                          // echo "<a title='Edit' href='".site_url()."/transaksi_lain/P_registrasi_tvi/edit?id=$v[id]' class='btn btn-xs btn-primary '>
                          //             <i class='fa fa-pencil'></i>
                          //         </a>";
                          echo "</td>";
                          }elseif($v['status_dokumen'] == 5){
                          echo "<td class='col-md-1'>";
                          echo "<a title='Print' href='".site_url()."/transaksi_lain/P_st_liaison_officer/buktiSt?id=$v[id]' class='btn btn-xs btn-primary ' target='_blank'>
                                      <i class='fa fa-print'></i>
                                  </a>";
                          echo "</td>";
                          }
                      }
                      echo "</tr>";
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