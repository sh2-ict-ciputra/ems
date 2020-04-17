<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/P_master_unit/add'">
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
                            <th>No</th>
                            <th>Nama Unit</th>
                            <th>No. Unit</th>
                            <th>Penyewa</th>
                            <th>Sumber Data Dari</th>
                            <th>Luas Tanah</th>
                            <th>Luas Bangunan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
					<tfoot id="tfoot" style="display: table-header-group">
                        <tr>
                            <th>No</th>
                            <th>Nama Unit</th>
                            <th>No. Unit</th>
                            <th>Penyewa</th>
                            <th>Sumber Data Dari</th>
                            <th>Luas Tanah</th>
                            <th>Luas Bangunan</th>
                            <th>Status Jual</th>
                            <th hidden>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $i = 0;
                            foreach ($data as $key => $v){
                            $i++;
                            echo('<tr>');
                                echo("<td>$i</td>");
                                echo("<td>$v[kawasan] BLOK $v[blok]</td>");
                                echo("<td>$v[unit]</td>");
                                echo("<td>$v[pemilik]</td>");
                                echo("<td>$v[source]</td>");
                                echo("<td>$v[luas_tanah] m<sup>2</sup></td>");
                                echo("<td>$v[luas_bangunan] m<sup>2</sup></td>");
                                if($v['status'] == '1'){
                                    echo("<td>Saleable</td>");
                                }else{
                                    echo("<td>Not Saleable</td>");
                                }
                                
                                echo("
                                    <td>
                                    <a href='".site_url()."/Transaksi_lain/P_unit_sewa/detail?id=$v[id]' class='btn btn-primary col-md-10'>
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                ");
                                echo('</tr>');
                            }?>                
                            
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>