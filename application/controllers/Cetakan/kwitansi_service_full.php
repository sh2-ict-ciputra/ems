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
    width: 240px;
    font-size: 12px;
    border:solid 1px black;
}
#box2{
    font-size: 12px;
    height:30px;
    width:303px;
    border:solid 1px black;
}
#box3{
    border:solid 1px black;
    height:50px;
}
}
</style>

<body class="container2" style="padding-bottom:-120px">
    <div>
        <table id="items" style="margin-top:130px;margin-left:60px">
            <tr>
                <td>PT SINAR BAHANA  MULYA</td>
            </tr>
            <tr>
                <td style="padding-top:-4px">Jl. Alternatif Cibubur, Cileungsi KM.4, Kranggan - Jati Karya, CIBUBUR,Telp. 8454224, Fax. 8454247</td>
            </tr>
        </table>
        <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;padding-top:5px">
            <td id="box3">
                <p class="lh-5 f-12" style="margin-left:5px;margin-top:-15px">Sudah terima dari <span class="tab">: <?= $dataUnit->customer_name?></span></p>
            </td>
        </table>
        <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;padding-top:5px">
            <td id="box3">
                <p class="lh-5 f-12" style="margin-left:5px;margin-top:-15px">Jumlah Uang <span class="tab">: <?=$terbilang?></span></p>
            </td>
        </table>
        <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;padding-top:5px">
            <td id="box3">
                <p class="lh-5 f-12" style="margin-left:5px;margin-top:-15px">Untuk Pembayaran <span class="tab">: <?= $data_paket_service->name?></span></p>
            </td>
        </table>
        <div class="col-md-12 col-sm-12 col-lg-12 row" style="padding-top:10px">
            <div class="col-md-5 col-sm-5 col-lg-5" style="margin-left:70px">
                <table style="padding-left:-15px;padding-top:5px">
                    <td id="box2"><p class="lh-5 f-12" style="margin-left:5px">Jumlah : Rp.<?=number_format($total_tagihan)?></p></td>
                </table>
                <table style="padding-left:-15px;padding-top:15px">
                    <td id="box2"><p style="margin-top:5px;font-size:10px;margin-left:5px;line-height:10px">Apabila pembayaran dengan Cheque / Giro maka kwitansi ini <br> baru sah bila 
                        jumlah tersebut telah diterima oleh bank kami.
                    </p></td>
                </table>
            </div>
            <div class="col-md-5 col-sm-5 col-lg-5" style="padding-left:580px">
                <p class="lh-5 f-15">Tanggal, <?=$tanggal?></p>
                <p class="lh-5 f-15" style="padding-top:50px;"><?=$this->session->userdata('name')?></p>
            </div>
        </div>
    </div>
</body>

</html>