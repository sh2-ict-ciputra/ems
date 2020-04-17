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
    width: 685px;
    font-size: 12px;
    border:solid 1px black;
}
#box2{
    width: 685px;
    font-size: 12px;
}
#box3{
    border:solid 1px black;
    height:100px;
}
}
</style>

<body class="container2" style="padding-bottom:-120px">
<div>
    
    <table id="items" style="margin-top:130px;margin-left:60px">
        <tr>
            <td>NO. TRANS : XXXXXX</td>
            <!-- <td style="padding-right:-140px">PERIODE : <?=$periode_first?> S/D <?=$periode_last?></td> -->
        </tr>
    </table>
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center" border="1">
        <thead>
            <tr>
                <th><p style="text-align:center;height:8px;width:656px">KWITANSI SERVICE LAINNYA</th>
            </tr>
        </thead>
    </table>
    <table style="padding-left:70px;padding-top:5px">
            <td id="box2">
                <p style="margin-left:5px;margin-top:2px">NAMA <span class="tab">: <?= $dataUnit->customer_name?></span></p>
                <p style="margin-left:5px;margin-top:2px">NO. Registrasi <span class="tab">: <?= $no_regis ?></span></p>
                <p style="margin-left:5px;margin-top:2px">ALAMAT <span class="tab">: <?= $dataUnit->kawasan_name .' Blok '. $dataUnit->blok_name .' No. '. $dataUnit->no_unit?></span></p>
            </td>
    </table>
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center;padding-top:10px" border="1">
        <thead>
            <tr>
                <th><p style="text-align:center;height:10px;width:656px">PERINCIAN SERVICE LAINNYA</th>
            </tr>
        </thead>
    </table>
    <table style="padding-left:70px;padding-top:1px">
        <td id="box1">
            <p style="margin-left:5px;margin-top:2px">PAKET SERVICE <span class="tab">: <?= $data_paket_service->name?></span></p>
            <!-- <p style="margin-left:5px;margin-top:-8px">Rp.<?= $grand_total?></p> -->
            <p style="margin-left:5px;margin-top:2px">PERIODE <span class="tab">: <?= 
            
            $data_detail_service->periode_awal?> S/D <?= $data_detail_service->periode_akhir?> </span></p>
            <!-- <p style="margin-left:5px;margin-top:-8px">Rp.<?= $grand_total?></p> -->
            <p style="margin-left:5px;margin-top:2px">VOLUME <span class="tab">: <?= $data_detail_service->kuantitas?> <?= $data_detail_service->satuan?></span></p>
            <!-- <p style="margin-left:5px;margin-top:-8px">Rp.<?= $grand_total?></p> -->
            <p style="margin-left:5px;margin-top:2px">TAGIHAN BULAN KE-1 <span class="tab">: <?= 
													(($data_detail_service->biaya_satuan) * ($data_detail_service->kuantitas)) + ($data_detail_service->biaya_registrasi) + $data_detail_service->biaya_pemasangan
												?></span></p>
            <!-- <p style="margin-left:5px;margin-top:-8px">Rp.<?= $grand_total?></p> -->
            <?php
            $diff = abs(strtotime($data_detail_service->periode_awal) - strtotime($data_detail_service->periode_akhir)); 
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            ?>
            <p style="margin-left:5px;margin-top:2px">TAGIHAN BULAN LANJUT<span class="tab">: <?=
            $months + ($years * 12) + 1  == 1 ? '' : (($months + ($years * 12) + 1 < ($data_detail_service->minimal_langganan)) ? ((($data_detail_service->biaya_satuan) * ($data_detail_service->kuantitas)) + ($data_detail_service->biaya_registrasi)) : ((($data_detail_service->harga_satuan) * ($data_detail_service->kuantitas))))?></span></p>
            <!-- <p style="margin-left:5px;margin-top:-8px">Rp.<?= $grand_total?></p> -->
            <p style="margin-left:5px;margin-top:2px">TOTAL TAGIHAN <span class="tab">: <?= $months + ($years * 12) + 1 < ($data_detail_service->minimal_langganan) ? ((($data_detail_service->biaya_satuan) * ($data_detail_service->kuantitas)) + ($data_detail_service->biaya_registrasi)) * ($months + ($years * 12) + 1)  : (((($data_detail_service->harga_satuan) * ($data_detail_service->kuantitas))) * ($months + ($years * 12) + 1)) + ($data_detail_service->biaya_registrasi) ?></span></p>
        </td>
    </table>

    <div class="col-md-12 col-sm-12 col-lg-12 row" style="padding-top:5px">
        
        <div class="col-md-5 col-sm-5 col-lg-5" style="padding-left:580px">
            <p class="lh-5 f-15">BOGOR, <?=$tanggal?></p>
            <p class="lh-5 f-15" style="padding-top:50px;"><?=$this->session->userdata('name')?></p>
        </div>
    </div>
</div>
</body>

</html>