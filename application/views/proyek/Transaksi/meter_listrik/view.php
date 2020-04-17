<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
            <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/P_transaksi_meter_listrik/add'">
                <i class="fa fa-plus"></i>                
                Tambah
            </button>
            <button class="btn btn-warning" onClick="window.history.back()" disabled>
                <i class="fa fa-arrow-left"></i>
                Back
            </button>
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_transaksi_meter_listrik'">
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
                <th>Periode</th>
                <th>Kawasan</th>
                <th>Blok</th>
                <th>Unit</th>
                <th>Meter Awal</th>
                <th>Meter Akhir</th>
                <th>Pemakaian</th>
                <th>Customer</th>
                <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 0;
                foreach ($data as $key => $v){
                $i++;
                echo('<tr>');
                    echo("<td>$i</td>");
					echo("<td>$v[periode]</td>");
                    echo("<td>$v[kawasan]</td>");
					echo("<td>$v[blok]</td>");
					echo("<td>$v[unit]</td>");
					echo("<td>$v[meter_awal]</td>");
					echo("<td>$v[meter_akhir]</td>");
					echo("<td>$v[pemakaian]</td>");
					echo("<td>$v[customer]</td>");
					echo("
                        <td>
                        <a href='".site_url()."/P_transaksi_meter_listrik/edit?id=$v[id]' class='btn btn-primary col-md-10'>
                            <i class='fa fa-pencil'></i>
                        </a>
                        </td>
                    ");
                    echo('</td></tr>');                
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