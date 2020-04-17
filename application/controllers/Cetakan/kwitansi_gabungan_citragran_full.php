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

#tabel{
    width: 240px;
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
    height:100px;
}
.superscript{
    font-size: 9px;
    vertical-align: super;
}
}
</style>

<body class="container2" style="padding-bottom:-120px">
<div>
    
    <table id="items" style="margin-top:130px;margin-left:60px">
        <tr>
            <td>NO. TRANS : XXXXXX</td>
            <td style="padding-right:-140px">PERIODE : <?=$periode_first?> S/D <?=$periode_last?></td>
        </tr>
    </table>
    
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center" border="1">
        <thead>
            <tr>
                <th><p style="text-align:center;height:8px;width:656px">REKENING AIR BERSIH, IURAN PENGELOLAAN LINGKUNGAN DAN KEAMANAN</th>
            </tr>
        </thead>
    </table>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form group">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row">
                <table style="margin-left:50px">
                    <tr>
                        <td>NAMA</td>
                        <td>:</td>
                        <td> <?= $unit->pemilik?></td>
                    </tr>
                    <tr>
                        <td>NO. PELANGGAN</td>
                        <td>:</td>
                        <td> XXXXXXX</td>
                    </tr>
                    <tr>
                        <td>NO. METERAN</td>
                        <td>:</td>
                        <td>
                            <?php if($unit->no_meter == null){?>
                            <br>
                            <?php }else{ ?>
                            <?= $unit->no_meter?>
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td>ALAMAT</td>
                        <td>:</td>
                        <td>KAWASAN <?= $unit->kawasan . ' ' .'BLOK' . ' ' . $unit->blok . ' ' . 'NOMOR' . ' ' . $unit->no_unit?></td>
                    </tr>   
                </table>
                </div>
            </div>
            <div class="form group">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 row">
                    <table border="1">
                        <tr>
                            <td style="text-align:center" colspan="2">ANGKA METER AIR</td>
                            <td style="text-align:center" rowspan="2">PEMAKAIAN AIR<br>(m<span class="superscript">3</span>)</td>
                        </tr>
                        <tr>
                            <td style="text-align:center">AKHIR</td>
                            <td style="text-align:center">AWAL</td>   
                        </tr>   
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center;padding-top:10px" border="1">
        <!-- <thead>
            <tr>
                <th><p style="text-align:center;height:10px;width:656px">PERINCIAN PENGGUNAAN DAN BIAYA AIR BERSIH</th>
            </tr>
        </thead> -->
        <tr>
            <td colspan="3" width="250px">PERINCIAN HARGA AIR (Rp)</td>
            <td rowspan="2">BIAYA ADM <br>(RP)</td>
            <td rowspan="2">BIAYA ABODEMEN AIR <br>(RP)</td>
            <td rowspan="2">DENDA</td>
            <td rowspan="2">JUMLAH YG HRS DIBAYAR<br>(RP)</td>
        </tr>
        <tr>
            <td>0 - 10 m<span class="superscript">3</span></td>
            <td>11 - 20 m<span class="superscript">3</span></td>
            <td>> 20 m<span class="superscript">3</span></td>
        </tr>
        <tr>
            <?php if($meter->meter_pakai <= 10){?>
            <td>Rp.<?=number_format($pembayaran_air->total)?></td>
            <?php }else{?>
            <td>0</td>
            <?php }?>

            <?php if($meter->meter_pakai > 10 && $meter->meter_pakai <= 20){?>
            <td>Rp.<?=number_format($pembayaran_air->total)?></td>
            <?php }else{?>
            <td>0</td>
            <?php }?>

            <?php if($meter->meter_pakai > 20){?>
            <td>Rp.<?=number_format($pembayaran_air->total)?></td>
            <?php }else{?>
            <td>0</td>
            <?php }?>
            <td></td>
            <td></td>
            <td>Rp.<?=number_format($pembayaran_air->denda)?></td>
            <td>Rp.<?=number_format($pembayaran_air->total)?></td>
        </tr>
        <tr>
            <td colspan="4">IURAN PENGELOLAAN LINGKUNGAN DAN KEAMANAN</td>
            <td></td>
            <td>Rp.<?=number_format($pembayaran_lingkungan->denda)?></td>
            <td>Rp.<?=number_format($pembayaran_lingkungan->total)?></td>
        </tr>
        <tr>
            <td colspan="4">PPN IPL 10% = Rp.<?=$pembayaran_lingkungan->ppn?></td>
            <td></td>
            <td>TOTAL</td>
            <td>Rp.<?=$grand_total?></td>
        </tr>
    </table>
    <!-- <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center;padding-top:2px" border="1">
        <thead>
            <tr>
                <th><p style="text-align:center;height:6px;width:100px">METER AWAL</p></th>
                <th><p style="text-align:center;height:6px;width:100px">METER AKHIR</p></th>
                <th><p style="text-align:center;height:6px;width:120px">PEMAKAIAN</p></th>
                <th><p style="text-align:center;height:6px;width:100px">DENDA</p></th>
                <th><p style="text-align:center;height:6px;width:100px">DISKON</p></th>
                <th><p style="text-align:center;height:6px;width:130px">TOTAL TAGIHAN</p></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$meter->meter_awal ?> m<span class="superscript">3</span></td>
            
                <td><?=$meter->meter_akhir ?> m<span class="superscript">3</span></td>
            
                <td><?=$meter->meter_pakai ?> m<span class="superscript">3</span></td>
            
                <td><?=$pembayaran_air->denda ?></td>
            
                <td><?=$pembayaran_air->diskon ?></td>

                <td><?=$pembayaran_air->total ?></td>
            </tr>
        </tbody>
    </table>
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center;padding-top:2px" border="1">
        <thead>
            <tr>
                <th><p style="text-align:center;height:10px;width:656px">PERINCIAN IURAN PENGELOLAAN LINGKUNGAN DAN KEAMANAN (I.P.L.K)</th>
            </tr>
        </thead>
    </table>
    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row" style="padding-left:90px;text-align:center;padding-top:2px" border="1">
        <thead>
            <tr>
                <th><p style="text-align:center;height:6px;width:200px">TAGIHAN (I.P.L.K)</p></th>
                <th><p style="text-align:center;height:6px;width:120px">PPN</p></th>
                <th><p style="text-align:center;height:6px;width:100px">DENDA</p></th>
                <th><p style="text-align:center;height:6px;width:100px">DISKON</p></th>
                <th><p style="text-align:center;height:6px;width:130px">TOTAL TAGIHAN</p></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $pembayaran_lingkungan->tagihan?></td>
                <td><?= $pembayaran_lingkungan->ppn?></td>
                <td><?= $pembayaran_lingkungan->denda?></td>
                <td><?= $pembayaran_lingkungan->diskon?></td>
                <td><?= $pembayaran_lingkungan->total?></td>
            </tr>
        </tbody>
    </table>
    <table style="padding-left:70px;padding-top:5px">
        <td id="box1">
            <p style="margin-left:5px;margin-top:2px">GRAND TOTAL :</p>
            <p style="margin-left:5px;margin-top:-8px">Rp.<?= $grand_total?></p>
        </td>
        <td style="width:3px">
        </td>
        <td id="box2" style="text-align:left">
            <p style="margin-left:5px;margin-top:2px">TERBILANG :</p>
            <p style="margin-left:5px;margin-top:-8px"><?= $terbilang?></p>
        </td>
    </table> -->

    <div class="col-md-12 col-sm-12 col-lg-12 row" style="padding-top:5px">
        <div class="col-md-5 col-sm-5 col-lg-5" style="margin-left:70px" id="box3">
            <table>
                <tr>
                    <td><p style="margin-top:5px">TERBILANG :</p></td>
                </tr>
                <tr>
                    <td><p style="margin-top:3px"><?= $terbilang?></p></td>
                </tr>
            </table>
        </div>
        <div class="col-md-5 col-sm-5 col-lg-5" style="padding-left:580px">
            <p class="lh-5 f-15">BOGOR, <?=$date?></p>
            <p class="lh-5 f-15" style="padding-top:50px;"><?=$user?></p>
        </div>
    </div>
</div>
</body>

</html>