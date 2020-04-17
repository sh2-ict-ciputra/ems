<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?=site_url().'/'.$this->uri->segment(1)?>/add'">Add/Sync</button>
        <button class="btn btn-success" onClick="window.location.reload()">Refresh</button>
        </h2>
    </div>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <table id="tableDT" class="table table-striped jambo_table bulk_action tableDT">
        <tfoot id="tfoot" style="display: table-header-group">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama PT</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>NPWP</th>
                <th>Kode Pos</th>
                <th>Rekening</th>
            </tr>
        </tfoot>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama PT</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>NPWP</th>
                <th>Kode Pos</th>
                <th>Rekening</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $i = 0;
            foreach ($data as $key => $v){
                $i++;
                echo('<tr>');
                    echo("<td>$i</td>");
                    echo("<td>$v[code]</td>");
                    echo("<td>$v[name]</td>");
                    echo("<td>$v[address]</td>");
                    echo("<td>$v[phone]</td>");
                    echo("<td>$v[npwp]</td>");
                    echo("<td>$v[zipcode]</td>");
                    echo("<td>$v[rekening]</td>");
                echo('</td></tr>');                
            }
        ?>
        </tbody>
    </table>