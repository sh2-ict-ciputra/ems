<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='UTF-8'>
    <title>Konfirmasi Tagihan</title>
    <link href="<?= base_url() ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="/ems/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->

</head>
<style>
    @font-face {
        font-family: 'DotMatrix';
        font-style: normal;
        font-weight: normal;
        src: url('<?= base_url() ?>vendor/dompdf/dompdf/lib/fonts/DotMatrix-TwoRegular.ttf') format('truetype');
    }
    *{
        font-family: 'DotMatrix';
    }
    .casabanti {
        /* font-family: 'casbanti'; */
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
        font-size: 14px;
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
        /* font-weight: bold; */
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
        width: 758px;
        font-size: 12px;
        /* border:solid 1px black; */
    }

    #box2 {
        font-size: 12px;
        width: 395px;
        /* border:solid 1px black; */
    }

    #box3 {
        /* border:solid 1px black; */
        font-size: 12px;
        height: 100px;
    }
</style>

<body class="container2" style="padding-bottom:-120px">
    <div>
        <table id="items" style="margin-top:75px;margin-left:455px">
            <tr>
                <td style="padding-right:-190px"><?= strtoupper(substr($periode_first, 0, 3)) . ' ' . substr($periode_first, -4) ?> S/D <?= strtoupper(substr($periode_last, 0, 3)) . ' ' . substr($periode_last, -4) ?></td>
            </tr>
        </table>

        <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="margin-top:63px;margin-left:87px;border-collapse: collapse; ">
            <tr>
                <td><?= $no_referensi2 ?></td>
            </tr>
            <tr>
                <td> - </td>
            </tr>
            <tr>
                <td> <?= $unit->pemilik ?></td>
            </tr>
            <tr>
                <td>KAWASAN <?= ucfirst($unit->kawasan) ?>,BLOK <?= ucfirst($unit->blok) ?>/<?= ucfirst($unit->no_unit) ?></td>

            </tr>
        </table>
        <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="margin-top: 32px;">
            <tbody>
                <table id="box1" style="padding-left:70px;padding-top:-1.8">
                    <td style="text-align:right">
                        <p style="margin-right:25px;margin-top:2px"><?= $tagihan->tagihan ?></p>
                        <p style="margin-right:25px;margin-top:-8px"><?= $tagihan->denda ?></p>
                        <p style="margin-right:25px;margin-top:0px"><?= $tagihan->total ?></p>
                    </td>
                </table>
            </tbody>
        </table>

        <div class="col-md-12 col-sm-12 col-lg-12 row" style="padding-top:20px">
            <div class="col-md-5 col-sm-5 col-lg-5" style="margin-left:50px;" id="box3">
                <table>
                    <tr>
                        <p style="margin-top:30px;padding-left:10px"><?= $terbilang ?></p>
                    </tr>
                </table>
            </div>
            <div class="" style="margin-left:580px;">
                <p class="lh-5 f-15" style="text-align: center"><?= $tanggal ?></p>
                <p class="lh-5 f-15" style="font-size:14px; text-align: center;padding-top:40px;"><?= $this->session->userdata('name') ?></p>
            </div>
        </div>
    </div>
</body>

</html>