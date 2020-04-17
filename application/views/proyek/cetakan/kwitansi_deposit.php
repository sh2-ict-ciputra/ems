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
        <table id="items" style="margin-top:170px;padding-left:100px">
            <tr>
                <td>
                    <p class="lh-5 f-12" style="padding-left:150px"><?=$customer->pemilik?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="lh-5 f-12" style="padding-left:150px"><?=$terbilang?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="lh-5 f-12" style="padding-left:150px"><?=$customer->description?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="lh-5 f-12" style="padding-left:150px"><?=number_format($customer->nilai)?></p>
                </td>
            </tr>
        </table>
        <div class="col-md-12 col-sm-12 col-lg-12 row" style="margin-top:20px">
            <div class="col-md-5 col-sm-5 col-lg-5" style="margin-left: 100px;height:20px; padding-left:10px">
                <div style="height:20px"></div>
                <p class="lh-15 f-12"></p>
            </div>
            <div class="col-md-7 col-sm-7 col-lg-7" style="height:100px;padding-left:600px;padding-top:-7px;">
                <p class="lh-5 f-15" ><?=$periode?></p>
                <p class="lh-5 f-15" style="padding-top:65px"><?=$this->session->userdata('name')?></p>
            </div>
        </div>
    </div>
    <!-- Sudah diterima dari : <?=$customer->pemilik?><br>
    Jumlah Uang : <?=$terbilang?><br>
    untuk pembayaran : <?=$customer->description?><br>
    jumlah : <?=number_format($customer->nilai)?><br>
    Tanggal : <?=$periode?><br>
    untuk tanda tangan, jika orang yang login : <?=$this->session->userdata('name')?> -->
</body>

</html>