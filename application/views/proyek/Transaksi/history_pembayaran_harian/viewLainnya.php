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
                <!-- <th>No</th>
                <th>Kawasan</th>
                <th>Blok</th>
                <th>No. unit</th>
                <th>Pemilik</th>
                <th>No Kwitansi</th>
                <th>No Referensi</th>
                <th>Tagihan+PPN</th>
                <th>Tunggakan+PPN</th>
                <th>Denda</th>
                <th>Penalti</th>
                <th>Total</th> -->
                <th>No</th>
                <th>Kode Blok</th>
                <th>Kode Project</th>
                <th>Nomor Bukti</th>
                <th>Notes</th>
                <th>Periode</th>
                <th>Pakai (m<sup>3</sup>)</th>
                <th>Tanggal Bayar</th>
                <th>Nilai Kebersihan</th>
                <th>Nilai Keamanan</th>
                <th>Nilai Lapak</th>
                <th>PPN Air</th>
                <th>Nilai Admin</th>
                <th>Denda</th>
                <th>Pemakaian</th>
                <th>Disc</th>
                <th>Total Bayar</th>
        </tr>
        </tfoot>
        <thead>
            <tr>
                <!-- <th>No</th>
                <th>Kawasan</th>
                <th>Blok</th>
                <th>No. unit</th>
                <th>Pemilik</th>
                <th>No Kwitansi</th>
                <th>No Referensi</th>
                <th>Tagihan+PPN</th>
                <th>Tunggakan+PPN</th>
                <th>Denda</th>
                <th>Penalti</th>
                <th>Total</th> -->
                <th>No</th>
                <th>Kode Blok</th>
                <th>Kode Project</th>
                <th>No. Bukti</th>
                <th>No. Kwitansi</th>
                <th>Periode</th>
                <th>Pakai (m<sup>3</sup>)</th>
                <th>Tanggal Bayar</th>
                <th>Nilai Kebersihan</th>
                <th>Nilai Keamanan</th>
                <th>Nilai Lapak</th>
                <th>PPN Air</th>
                <th>Nilai Admin</th>
                <th>Denda</th>
                <th>Pemakaian</th>
                <th>Disc</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $i = 0;
            foreach ($data as $key => $v){
                $i++;
                echo('<tr>');
                    echo("<td>$i</td>");
                    echo("<td>$v[code_blok]</td>");
                    echo("<td>$v[code_project]</td>");
                    echo("<td></td>");
                    echo("<td>$v[no_kwitansi]</td>");
                    echo("<td></td>");
                    echo("<td></td>");
                    echo("<td>$v[tgl_bayar]</td>");
                    echo("<td></td>");
                    echo("<td></td>");
                    echo("<td></td>");
                    echo("<td></td>");
                    echo("<td></td>");
                    echo("<td></td>");
                    echo("<td></td>");
                    echo("<td></td>");
                    echo("<td></td>");
                echo('</td></tr>');                
            }
        ?>
        </tbody>
    </table>