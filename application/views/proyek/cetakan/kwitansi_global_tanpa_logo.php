<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='UTF-8'>
    <title>Konfirmasi Tagihan</title>
    <link href="<?= base_url() ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="/ems/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->

</head>
<style>
    .casabanti {
        font-family: 'casbanti';
    }

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .f-20 {
        font-size: 20px;
        font-weight: 700;
        line-height: 15px;
    }

    .f-14 {
        font-size: 14px;
        line-height: 10px;
    }

    html {
        margin: 5px 15px;
        padding: 0px;
    }

    .f-15 {
        font-size: 14px;
        line-height: 10px;
    }

    table thead th,
    table tbody tr td,
    table tfoot tr td {
        font-size: 12px;
    }

    .f-12 {
        font-size: 12px;
        line-height: 15px;
    }

    .f-8 {
        font-size: 8px;
        line-height: 10px;
    }

    .lh-18 {
        line-height: 18px;
    }

    .lh-5 {
        line-height: 20px;
    }

    .lh-15 {
        line-height: 15px;
    }

    .lh-8 {
        line-height: 5px;
    }

    #items {
        clear: both;
        width: 100%;
        margin: 0;
    }

    .borderless table {
        border-top-style: none;
        border-left-style: none;
        border-right-style: none;
        border-bottom-style: none;

    }

    .superscript {
        font-size: 9px;
        vertical-align: super;
    }

    body {
        font-weight: bold;
        padding-top: -5px
    }

    .tab {
        position: absolute;
        left: 250px;
    }

    #tabel {
        text-align: center;
        width: 750px;
    }

    #box1 {
        width: 240px;
        font-size: 12px;
        border: solid 1px black;
    }

    #tabel {
        width: 240px;
        font-size: 12px;
        border: solid 1px black;
    }

    #box2 {
        font-size: 12px;
        width: 395px;
        border: solid 1px black;
    }

    #box3 {
        height: 100px;
    }

    .superscript {
        font-size: 9px;
        vertical-align: super;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
        padding-top: 3px;
        padding-bottom: 0px;
    }
</style>

<body class="container2">
    <p>&nbsp;</p>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="width: 50%">
                    <p><div style="width:150px;height:81px"></div></p>
                </td>
                <td style="width: 50%">
                    <p style="text-align: center;"><strong><strong>No Kwitansi : <?=$project->code?>-<?=$unit->tgl_bayar?><?=$no_kwitansi?></strong></strong></p>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered" style="width: 100%; margin-bottom:0px" >
        <tbody>
            <tr>
                <td>
                    <p style="text-align: center;"><strong><strong>Kwitansi</strong></strong></p>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="width: 50%">
        <tr>
            <td style="width: 20%">Nama</td>
            <td>:</td>
            <td><?=$unit->pemilik?></td>
        </tr>
        <tr>
            <td style="width: 20%">Unit ID</td>
            <td>:</td>
            <td><?=$unit_id?></td>
        </tr>
        <tr>
            <td style="width: 20%">No. Meter</td>
            <td>:</td>
            <td><?=$unit->no_meter?></td>
        </tr>
        <tr>
            <td style="width: 20%">Alamat</td>
            <td>:</td>
            <td><?="$unit->kawasan $unit->blok/$unit->no_unit"?></td>
        </tr>
    </table>
    <?php if($pembayaran_air->tagihan):?>
    <table class="table table-bordered jambo_table" style="width: 100%">
        <tbody>
            <tr>
                <td colspan="6">
                    <p style="text-align: center;"><strong><strong>Perincian Biaya Air Bersih</strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong><strong>Periode</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>Meter Awal</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>Meter Akhir</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>Denda (Rp.)</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>Diskon (Rp.)</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>Tagihan (Rp.)</strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <?php if($pembayaran_air_periode_awal == $pembayaran_air_periode_akhir):?>
                        <?=$pembayaran_air_periode_awal?>
                        <?php else:?>
                        <?="$pembayaran_air_periode_awal - $pembayaran_air_periode_akhir"?>
                        <?php endif;?>                        
                    </p>
                </td>
                <td>
                    <p style="text-align: right;"><?=$meter->meter_awal?> m<sup>3</sup></p>
                </td>
                <td style="text-align: right;">
                    <p><?=$meter->meter_akhir?> m<sup>3</sup></p>
                </td>
                <td style="text-align: right;">
                    <p><?=$pembayaran_air->denda?></p>
                </td>
                <td style="text-align: right;">
                    <p><?=$pembayaran_air->diskon?></p>
                </td>
                <td>
                    <p style="text-align: right;"><?=$pembayaran_air->tagihan?></p>
                </td>
            </tr>
        </tbody>
    </table>
    <?php endif;?>
    <?php if($pembayaran_lingkungan->tagihan):?>
    <table class="table table-bordered" style="width: 100%; margin-bottom:0px" >
        <tbody>
            <tr>
                <td colspan="5">
                    <p style="text-align: center;"><strong><strong>Perincian Iuran Pengelolaan Lingkungan (I.P.L)</strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong><strong>Periode</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>PPN (Rp.)</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>Denda (Rp.)</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>Diskon (Rp.)</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>Tagihan (Rp.)</strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <?php if($pembayaran_lingkungan_periode_awal == $pembayaran_lingkungan_periode_akhir):?>
                        <?=$pembayaran_lingkungan_periode_awal?>
                        <?php else:?>
                        <?="$pembayaran_lingkungan_periode_awal - $pembayaran_lingkungan_periode_akhir"?>
                        <?php endif;?> 
                    </p>
                </td>
                <td style="text-align: right;">
                    <p><?=$pembayaran_lingkungan->ppn_rupiah?></p>
                </td>
                <td style="text-align: right;">
                    <p><?=$pembayaran_lingkungan->denda?></p>
                </td>
                <td style="text-align: right;">
                    <p><?=$pembayaran_lingkungan->diskon?></p>
                </td>
                <td style="text-align: right;">
                    <p><?=$pembayaran_lingkungan->tagihan_tanpa_ppn?></p>
                </td>
            </tr>
        </tbody>
    </table>
    <?php endif;?>

    <p>&nbsp;</p>
    <?php if(false):?>
    <table class="table table-bordered" style="width: 100%; margin-bottom:0px" >
        <tbody>
            <tr>
                <td colspan="6">
                    <p style="text-align: center;"><strong><strong>Perincian Service Lain</strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong><strong>Service</strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>Periode</strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>PPN (Rp.)</strong></strong></p>
                </td>
                <td style="text-align: right; ">
                    <p><strong><strong>Denda (Rp.)</strong></strong></p>
                </td>
                <td style="text-align: right; ">
                    <p><strong><strong>Diskon (Rp.)</strong></strong></p>
                </td>
                <td style="text-align: right; ">
                    <p><strong><strong>Tagihan (Rp.)</strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>TV &amp; Internet</p>
                </td>
                <td>
                    <p>05/2019 - 06/2019</p>
                </td>
                <td style="text-align: right;">
                    <p>10.000</p>
                </td>
                <td style="text-align: right; ">
                    <p>5.000</p>
                </td>
                <td style="text-align: right; ">
                    <p>0</p>
                </td>
                <td style="text-align: right; ">
                    <p>100.000</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Liaison Officer</p>
                </td>
                <td>
                    <p>07/2019</p>
                </td>
                <td style="text-align: right;">
                    <p>5.000</p>
                </td>
                <td style="text-align: right;">
                    <p>5.000</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>50.000</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Sewa Properti</p>
                </td>
                <td>
                    <p>07/2019</p>
                </td>
                <td style="text-align: right;">
                    <p>100.000</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>1.000.000</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Layanan Lain</p>
                    <p>Perawatan Taman</p>
                </td>
                <td>
                    <p>07/2019</p>
                </td>
                <td style="text-align: right;">
                    <p>5.000</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>50.000</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Layanan Lain</p>
                    <p>Perawatan Kucing</p>
                </td>
                <td>
                    <p>07/2019</p>
                </td>
                <td style="text-align: right;">
                    <p>10.000</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>100.000</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Layanan Lain</p>
                    <p>Jual Bakso Keliling</p>
                </td>
                <td>
                    <p>07/2019</p>
                </td>
                <td style="text-align: right;">
                    <p>2.500</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>25.000</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Sub Total</p>
                </td>
                <td style="text-align: right;">
                    <p>132.500</p>
                </td>
                <td style="text-align: right;">
                    <p>10.000</p>
                </td>
                <td style="text-align: right;">
                    <p>0</p>
                </td>
                <td style="text-align: right;">
                    <p>1.325.000</p>
                </td>
            </tr>
        </tbody>
    </table>
    <?php endif;?>

    <p>&nbsp;</p>
    <table class="table table-bordered" style="width: 100%; margin-bottom:0px" >
        <tbody>
            <tr>
                <td colspan="3">
                    <p><strong><strong>Grand Total</strong></strong></p>
                </td>
                <td colspan="2">
                    <p><strong><strong>Terbilang</strong></strong></p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p style="text-align: left;"><strong><strong>Rp. <?=$grand_total?></strong></strong></p>
                </td>
                <td colspan="2">
                    <p><strong><strong><?=$terbilang?></strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong><strong>Outstanding</strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>: Rp. </strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong>0</strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>&nbsp;</strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>Bekasi, <?=date("d - m - Y")?></strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong><strong>Pemakaian Deposit</strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>: Rp. </strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong><?=$pemakaian_deposit?></strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>&nbsp;</strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>&nbsp;</strong></strong></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong><strong>Sisa Deposit</strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>: Rp. </strong></strong></p>
                </td>
                <td style="text-align: right;">
                    <p><strong><strong><?=$sisa_deposit?></strong></strong></p>
                </td>
                <td>
                    <p><strong><strong>&nbsp;</strong></strong></p>
                </td>
                <td>
                    <p><strong>&nbsp;</strong><strong><strong><?=$user?></strong></strong></p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>