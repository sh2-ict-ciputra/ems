<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        html{margin:20px 20px}
    </style>
</head>
<body>
<?php 
$lembar = ($ttd==0)?1:3;
for ($i=0; $i < $lembar; $i++) { ?>
    <table  width="100%" style="text-align:center;border:1px solid;padding-bottom:20px">
        <tr>
            <td colspan="5"></td>
            <td><h3 style="margin:0px;">KWITANSI</h3><?=$no_referensi?></td>
        </tr>
        <tr>
            <td colspan="6" style="margin:0px;"><img src="images/logo-ciputra.png" alt="" style="width:100px"><br><h5 style="margin:10px;"><?=$project->name?></h5></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr style="margin:2px 2px;">
            <td style="text-align:left;font-size:12px;">Telah terima dari</td>
            <td>:</td>
            <td colspan="4" style="text-align:left;font-size:12px;"> &nbsp; <?=$unit->blok.' - '.$unit->pemilik.' - '.$unit->kawasan?></td>
        </tr>
        <tr style="margin:2px 2px;">
            <td style="text-align:left;font-size:12px;">Banyaknya Uang</td>
            <td>:</td>
            <td colspan="4" style="text-align:left;font-size:12px;"> &nbsp; <?=$terbilang?></td>
        </tr>
        <tr style="margin:2px 2px;">
            <td style="text-align:left;font-size:12px;">Untuk pembayaran</td>
            <td>:</td>
            <td colspan="4" style="text-align:left;font-size:12px;"> &nbsp; LIAISON OFICER - <?=$dataRegistrasi->kategori_nama?> - <?=$dataRegistrasi->jenis_nama?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align:left;font-size:12px;"> &nbsp; <?=$dataRegistrasi->paket_nama?></td>
        </tr>
        <tr style="height:100px;">
            <td colspan="5"></td>
            <td>Sidoarjo, <?=$tanggal?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding"><h4 style="border-style:solid;">Rp. <?=$tagihan->total?>,-</h4></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:left;font-size:8px;font-style:italic;">Pembayaran ini sah apabila cheque / giro telah dicairkan<br>Lembar Putih untuk PEMILIK, Merah untuk MANAGEMENT ESTATE, Hijau untuk KEUANGAN<br>RUKO SENTRA NIAGA RK – 32<br>CITRAGARDEN – Sidoarjo Telp 62 31 8068282 Fax 62 31 8068288 </td>
            <td>(<?=$this->session->userdata('name')?>)</td>
        </tr>
    </table>
    <?php    # code...
} ?>
</body>
</html>