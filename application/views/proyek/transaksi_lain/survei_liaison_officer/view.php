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
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_survei_liaison_officer'">
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
                            <th>Survei 1</th>
                            <th>Survei 2</th>
                            <!-- <th>Pembayaran</th> -->
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
                      if ($v['unit'] != 'non_unit' )
                      {
                        if($v['status_dokumen'] < 2){
                          echo "<td class='col-md-1'>";
                          echo "<a title='Survei' href='".site_url()."/transaksi_lain/P_survei_liaison_officer/add?id=$v[id]' class='btn btn-xs btn-primary '>
                                    <i class='fa fa-pencil-square-o'></i>
                                </a>";
                          // echo "<a title='Edit' href='".site_url()."/transaksi_lain/P_registrasi_tvi/edit?id=$v[id]' class='btn btn-xs btn-primary '>
                          //             <i class='fa fa-pencil'></i>
                          //         </a>";
                          echo "</td>";
                        }elseif($v['status_dokumen'] >= 2){
                          echo "<td><span class='label label-success'>Telah di Survei 1</span></td>";
                        }
                      }

                      if ($v['unit'] != 'non_unit' )
                      {
                        if($v['status_dokumen'] == 4){
                          echo "<td class='col-md-1'>";
                          echo "<a title='Survei' href='".site_url()."/transaksi_lain/P_survei_liaison_officer/add2?id=$v[id]' class='btn btn-xs btn-primary '>
                                    <i class='fa fa-pencil-square-o'></i>
                                </a>";
                          echo "</td>";
                        }elseif($v['status_dokumen'] >= 7 || $v['status_dokumen'] == 5){
                          echo "<td><span class='label label-success'>Telah di Survei 2</span></td>";
                        }elseif($v['status_dokumen'] != 4){
                          echo "<td><span class='label label-warning'>Belum Pelaksanaan</span></td>";
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
