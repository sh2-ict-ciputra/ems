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
        line-height: 20px;
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
#tabel{
    text-align: center;
    width:750px;
}
#box1{
    width: 758px;
    font-size: 12px;
    border:solid 1px black;
}
#box2{
    font-size: 12px;
    width:395px;
    border:solid 1px black;
}
#box3{
    border:solid 1px black;
    font-size: 12px;
    height:100px;
}
}
</style>

<body class="container2" style="padding-bottom:-120px">
<div>
    
    <table id="items" style="margin-top:130px;margin-left:55px">
        <tr>
            <td>NO. TRANS : <?=$no_referensi?></td>
            <td style="padding-right:-190px">PERIODE : <?=$periode_first?> S/D <?=$periode_last?></td>
        </tr>
    </table>
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center" border="1">
        <thead>
            <tr>
                <th><p style="text-align:center;font-size:14px;height:10px;width:656px">IURAN PERAWATAN LINGKUNGAN (IPL)</th>
            </tr>
        </thead>
    </table>
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="margin-left:45px">
        <tr>
            <td>NAMA</td>
            <td>&nbsp;:&nbsp;</td>
            <td> <?= $unit->pemilik?></td>
        </tr>
        <tr>
            <td>ALAMAT</td>
            <td>&nbsp;:&nbsp;</td>
            <td>KAWASAN <?=ucfirst($unit->kawasan)?>,BLOK <?=ucfirst($unit->blok)?>/<?=ucfirst($unit->no_unit)?></td>
        </tr>
    </table>
    <!-- <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center;padding-top:10px" border="1">
        <thead>
            <tr>
                <th><p style="text-align:center;height:10px;width:656px">RINCIAN PENGGUNAAN DAN BIAYA AIR (WTP)</th>
            </tr>
        </thead>
    </table> -->
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px" border="1">
        <tbody>
            <table id="box1" style="padding-left:70px;padding-top:-1.8">
                <td>
                    <p style="padding-top:5px;margin-left:5px">JUMLAH UANG (RP)</p>
                    <p style="padding-top:-10px;margin-left:5px">(-) DISKON <span class="tab">: <?=$tagihan->diskon?></span></p>
                    <p style="padding-top:-10px;margin-left:5px">(-) PEMAKAIAN DEPOSIT <span class="tab">: <?=$tagihan->deposit?></span></p>
                </td>
                <td style="width:3px">
                </td>
                <td style="text-align:left">
                    <p style="margin-left:5px;margin-top:2px">TAGIHAN : <?=$tagihan->tagihan?></p>
                    <p style="margin-left:5px;margin-top:-8px">DENDA &nbsp;&nbsp;&nbsp;&nbsp;: <?=$tagihan->denda?></p>
                    <p style="margin-left:5px;margin-top:-8px">TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?=$tagihan->total?></p>
                </td>
            </table>
        </tbody>
    </table>

    <div class="col-md-12 col-sm-12 col-lg-12 row" style="padding-top:5px">
        <div class="col-md-5 col-sm-5 col-lg-5" style="margin-left:70px" id="box3">
            <table>
                <tr>
                    <p>TERBILANG :</p>
                    <p style="margin-top:-13px"><?=$terbilang?></p>
                </tr>
            </table>
        </div>
        <div class="col-md-5 col-sm-5 col-lg-5" style="padding-left:580px">
            <p class="lh-5 f-15">BOGOR, <?=$tanggal?></p>
            <p class="lh-5 f-15" style="padding-top:50px;"><?=$this->session->userdata('name')?></p>
        </div>
    </div>
</div>
</body>

</html>