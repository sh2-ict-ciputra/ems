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
        line-height: 10px;
    }

    .lh-18 {
        line-height: 18px;
    }

    .lh-5 {
        line-height: 5px;
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
        margin: 0 auto;
    }
    .borderless table {
    border-top-style: none;
    border-left-style: none;
    border-right-style: none;
    border-bottom-style: none;
    
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
        <table id="items" style="margin-top:170px">
            <tr>
                <td class="" style="padding-left:160px"><?=$no_referensi?></td>
                <td style="float:right; padding-right:15px"><?=$periode_first?> S/D <?=$periode_last?></td>
            </tr>
        </table>
        <br>
        <table class="table" style="margin-top:35px;margin-left:115px">
            <tbody>
                <tr>
                    <td class="text-left" style="border:unset"><?=$unit->pemilik?></td>
                </tr>
                <tr>
                    <td class="text-left" style="border:unset">KAWASAN <?=ucfirst($unit->kawasan)?>,BLOK <?=ucfirst($unit->blok)?>/<?=ucfirst($unit->no_unit)?></td>
                </tr>
                <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="margin-bottom:10px">
                    <tr>
                        <td style="width:200px;padding-left:80px">
                            <p>&nbsp;</p>
                            <p class="lh-5 f-12">( - ) Diskon <span class="tab">:<?=$tagihan->diskon?></span></p>
                            <p class="lh-15 f-12">( - ) Pemakaian Deposit <span class="tab">:<?=$tagihan->deposit?></span></p>
                        </td>
                        <td style="width:350px">
                        </td>
                        <td>
                            <p class="lh-8 f-12"><?=$tagihan->tagihan?></p>
                            <p class="lh-8 f-12"><?=$tagihan->denda?> </p>
                            <p style="height:10px">&nbsp;</p>
                            <p class="lh-8 f-12"><?=$tagihan->total?></p>
                        </td>
                        
                    </tr>
                </table>
        </table>
        <div class="col-md-12 col-sm-12 col-lg-12 row" style="margin-top:20px">
            <div class="col-md-5 col-sm-5 col-lg-5" style="margin-left: 100px;height:20px; padding-left:10px">
                <div style="height:20px"></div>
                <p class="lh-15 f-12"><?=$terbilang?></p>
            </div>
            <div class="col-md-7 col-sm-7 col-lg-7" style="height:100px;padding-left:600px;padding-top:-7px;">
                <p class="lh-5 f-15" ><?=$tanggal?></p>
                <p class="lh-5 f-15" style="padding-top:65px"><?=$this->session->userdata('name')?></p>
            </div>
        </div>
    </div>
</body>

</html>