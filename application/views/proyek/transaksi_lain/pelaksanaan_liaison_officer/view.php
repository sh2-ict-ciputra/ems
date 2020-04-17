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
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_pelaksanaan_liaison_officer'">
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
                      if($v['status_dokumen'] == 3||$v['status_dokumen'] == 6){
                        echo "<td><span class='label label-warning'>Belum Pelaksanaan</span></td>";
                       }elseif($v['status_dokumen'] >= 4 || $v['status_dokumen'] == 5){
                        echo "<td><span class='label label-success'>Telah Pelaksanaan</span></td>";
                       }
                      if ($v['unit'] != 'non_unit' )
                       {
                        if($v['status_dokumen'] == 3||$v['status_dokumen'] == 6){
                          echo "<td class='col-md-1'>";
                          echo "<a title='Pelaksanaan' href='".site_url()."/transaksi_lain/P_pelaksanaan_liaison_officer/add?id=$v[id]' class='btn btn-xs btn-primary '>
                                      <i class='fa fa-pencil-square-o'></i>
                                  </a>";
                          echo "</td>";
                          }elseif($v['status_dokumen'] == 4 || $v['status_dokumen'] == 5){
                          echo "<td class='col-md-1'>";
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
