<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
    <h2>
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
                <th>API Key</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>NPWP</th>
                <th>Kode Pos</th>
                <th>Edit</th>
            </tr>
        </tfoot>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama PT</th>
                <th>API Key</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>NPWP</th>
                <th>Kode Pos</th>
                <th>Edit Api Key</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $k => $v) : ?>
                <tr>
                    <td><?= ++$k ?></td>
                    <td><?= $v->code ?></td>
                    <td><?= $v->name ?></td>
                    <td><?= $v->apikey ?></td>
                    <td><?= $v->address ?></td>
                    <td><?= $v->phone ?></td>
                    <td><?= $v->npwp ?></td>
                    <td><?= $v->zipcode ?></td>
                    <td>
                        <a class="btn btn-primary" href='<?= site_url("p_master_pt/edit?id=$v->pt_id") ?>'>
                            Edit
                        </a>
                    </td>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>