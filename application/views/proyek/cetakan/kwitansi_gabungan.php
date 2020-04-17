<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='UTF-8'>
    <title>Konfirmasi Tagihan</title>
    <link href="<?=base_url()?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
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
        line-height: 35px;
    }
    .lh-15 {
        line-height: 15px;
    }
    .lh-8 {
        line-height: 5px;
    }

    #items
    {
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
.superscript{
    font-size: 9px;
    vertical-align: super;
}
body{
        font-weight:bold;
        padding-top:-5px
    }
    .tab {position:absolute;left:250px; }

}
</style>

<body class="container2" style="padding-bottom:-120px">
<div>
    <table id="items" style="margin-top:130px">
        <tr>
            <td class="" style="padding-left:180px">-</td>
            <td style="float:right; padding-right:-15px"><?=$periode_first?> S/D <?=$periode_last?></td>
        </tr>
    </table>
    <table style="margin-left:140px">
        <tr>
            <td><?= $unit->pemilik?></td>
        </tr>
        <tr>
            <td>-</td>
        </tr>
        <tr>
            <td>
                <?php if($unit->no_meter == null){?>
                <br>
                <?php }else{ ?>
                <?= $unit->no_meter?>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td>KAWASAN <?= $unit->kawasan . ' ' .'BLOK' . ' ' . $unit->blok . ' ' . 'NOMOR' . ' ' . $unit->no_unit?></td>
        </tr>
    </table>
    <table id="items" style="padding-left:100px;padding-top:50px">
        <tr>
            <td><?=$meter->meter_awal ?> m<span class="superscript">3</span></td>
            <td style="padding-left:60px"><?=$meter->meter_akhir ?> m<span class="superscript">3</span></td>
            <td style="padding-left:60px"><?=$meter->meter_pakai ?> m<span class="superscript">3</span></td>
            <td style="padding-left:60px"><?=$pembayaran_air->denda ?></td>
            <td style="padding-left:60px"><?=$pembayaran_air->diskon ?></td>
            <td style="padding-left:60px"><?=$pembayaran_air->total ?></td>
        </tr>
    </table>
    <table id="items" style="padding-left:100px;padding-top:50px">
        <tr>
            <td><?= $pembayaran_lingkungan->tagihan?></td>
            <td style="padding-left:40px"><?= $pembayaran_lingkungan->ppn?></td>
            <td style="padding-left:40px"><?= $pembayaran_lingkungan->denda?></td>
            <td style="padding-left:40px"><?= $pembayaran_lingkungan->diskon?></td>
            <td style="padding-left:40px"><?= $pembayaran_lingkungan->total?></td>
        </tr>
    </table>
    <table style="padding-left:100px;padding-top:35px">
        <tr>
            <td><?= $grand_total?></td>
            <td style="padding-left:140px"><?= $terbilang?></td>
        </tr>
    </table>
    <div class="col-md-12 col-sm-12 col-lg-12 row" style="margin-top:20px">
        <div class="col-md-5 col-sm-5 col-lg-5" style="margin-left: 100px; padding-left:110px;padding-top:20px">
            <p class="lh-15 f-12"><?=$saldo_deposit?></p>
            <p class="lh-15 f-12"><?=$pemakaian_deposit?></p>
            <p class="lh-15 f-12"><?=$sisa_deposit?></p>
        </div>
        <div class="col-md-5 col-sm-5 col-lg-5" style="padding-left:555px">
            <p class="lh-5 f-15"><?=$date?></p>
            <p class="lh-5 f-15" style="padding-top:40px;"><?=$user?></p>
        </div>
    </div>
    <br>
    
</div>
</body>

</html>