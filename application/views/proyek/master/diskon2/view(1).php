<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<link rel="stylesheet" href="<?=base_url()?>vendors/select2/dist/css/select2.min.css">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>

<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/P_master_diskon/add'">
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
    <table class="table table-striped jambo_table bulk_action tableDT">
        <thead>
            <tr>
                <th>No</th>
                <th>Golongan Diskon</th>
                <th>Product Category</th>
                <th>Keterangan</th>
                <th>Status</th>
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
                    echo("<td>$v[gol_diskon_name]</td>");
                    echo("<td>$v[product_category_name]</td>");
                    echo("<td>$v[description]</td>");
                    echo("<td>");
                    echo($v['active']?'Aktif':'Tidak Aktif');
                    echo("</td>");
                    echo("
                        <td class='col-md-1'>
                        <a href='".site_url()."/P_master_diskon/edit?id=$v[id]' class='btn btn-sm btn-primary col-md-12'>
                            <i class='fa fa-pencil'></i>
                        </a>
                        </td>
                    ");
                echo('</td></tr>');                
            }
        ?>
        </tbody>

    </table>
