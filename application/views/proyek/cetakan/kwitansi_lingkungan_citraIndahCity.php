<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='UTF-8'>
    <title>Konfirmasi Tagihan</title>
    <link href="<?=base_url()?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="/ems/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->

</head>
<style>
    /* .casabanti {
        font-family: 'casbanti';
    } */

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .f-20 {
        /* font-size: 20px; */
        font-weight: 700;
        line-height: 15px;
    }

    .f-14 {
        /* font-size: 14px; */
        line-height: 10px;
    }

    html {
        margin: 5px 15px;
        padding: 0px;
    }

    .f-15 {
        /* font-size: 14px; */
        line-height: 10px;
    }

    table thead th,
    table tbody tr td,
    table tfoot tr td {
        /* font-size: 12px; */
    }

    .f-12 {
        /* font-size: 12px; */
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
        /* font-weight:bold; */
        padding-top:-5px;
        /* font-family: "Times New Roman", Times, serif; */

    }
    .tab {position:absolute;left:250px; }

}
@font-face {
    font-family: 'Open Sans';
    font-style: normal;
    font-weight: normal;
    src: url(https://ems.ciputragroup.com:11443/fonts/HIGHSPEED.TTF) format('truetype');
}
</style>

<body class="container2" style="padding-bottom:-120px">
    <div>
        <div style="margin-top:90px">
            <div class="" style="padding-left:160px"><?=$no_referensi?></div>
            <div style="margin-left:600px"><?=$periode_first?> S/D <?=$periode_last?></div>
        </div>
        <br>
        <div style="margin-top:20px;margin-left:170px">
            <div class="" style=""><?=$unit->pemilik?></div>
            <div>KAWASAN <?=ucfirst($unit->kawasan)?>,BLOK <?=ucfirst($unit->blok)?>/<?=ucfirst($unit->no_unit)?></div>
        </div>

        <div class="col-md-12 col-sm-12 col-lg-12 row" style="margin-top:30px">
            <div class="col-md-5 col-sm-5 col-lg-5" style="margin-left: 100px;height:20px; padding-left:10px">
                <div style="height:20px"></div>
                <p class="lh-15 f-12" style="margin-left:150px"><?=$tagihan->total?></p>
                <p class="lh-15 f-12" style="margin-top:50px"><?=$terbilang?></p>
            </div>
            <div class="col-md-7 col-sm-7 col-lg-7" style="height:100px;padding-left:575px;padding-top:-15px;">
                <p class="lh-5 f-15" style="padding-left:35px"><?=$tanggal?></p>
                <p class="lh-5 f-15" style="padding-top:110px"><?=$this->session->userdata('name')?></p>
            </div>
        </div>
    </div>
</body>

</html>