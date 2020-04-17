<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='UTF-8'>
    <title>Konfirmasi Tagihan</title>
    <link href="<?= base_url() ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

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
        margin-top: 200px;
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

    .lh-15 {
        line-height: 15px;
    }

    .lh-5 {
        line-height: 5px;
    }

    .lh-8 {
        line-height: 5px;
    }

    #header {
        position: fixed;
        top: -200px;
    }

    .page_break {
        page-break-before: always;
    }
    <?php if($total_tagihan->tunggakan!=0):?>
    .f-table {
        font-size: 10px;
        line-height: 10px;
    }
    <?php else:?>
    .f-table {
        font-size: 12px;
        line-height: 10px;
    }

    <?php endif;?>
</style>
<body>
    <div id="header">
        <div style="width: fit-content;text-align: center; margin-top:20px">
            <img src="images/logoCiputra.png" width="15%" style="align-content:center">
        </div>

        <div>
            <div class="" style="width: fit-content;text-align: center; margin-top:5px; margin-bottom:200px">
                <p class="align-center f-20"><u>Informasi Tagihan Retribusi Estate</u></p>
                <p class="align-center f-20 casabanti"><?= $unit->project ?></p>
                <p class="align-center f-14"><?= $unit->project_address ?></p>
            </div>
        </div>
    </div>

    <div id="container">
        <div id="body">
            <div>
                <p class="f-15">Kepada Yth,</p>
                <p class="f-15 lh-15">Bpk/ibu <?= $unit->customer_name ?></p>
                <p class="f-15 lh-15"><?=$unit->alamat?></p>
                <p class="f-15">Perumahan <?= $unit->project ?></p>
            </div>
            <br>
            <div>
                <p class="f-15">Dengan Hormat,</p>
                <p class="f-15 lh-15">Dengan ini kami sampaikan informasi total tagihan
                    <?php
                    if ($periode_first == $periode_last) {
                        echo (" bulan " . strtolower($periode_first));
                    } else {
                        echo (" dari bulan " . strtolower($periode_first) . " sampai " . strtolower($periode_last));
                    }
                    ?>
                    , dengan perincian sebagai
                    berikut :</p>
            </div>
            <table class="table table-striped" style="margin-bottom:0">
                <thead>
                    <tr>
                        <th class="f-table text-right" rowspan="2" style="vertical-align: middle">No</th>
                        <th class="f-table text-center" colspan="2" style="padding-bottom:0px">Periode</th>
                        <?php if ($total_tagihan->air) : ?>
                        <th class="f-table text-center" colspan="3" style="padding-bottom:0px">Meter</th>
                        <?php endif; ?>
                        <?php if ($total_tagihan->lain) : ?>
                        <th class="f-table text-right" rowspan="2" style="vertical-align: middle">LAIN(Rp.)</th>
                        <?php endif; ?>
                        <?php if ($total_tagihan->air) : ?>
                        <th class="f-table text-right" rowspan="2" style="vertical-align: middle">AIR(Rp.)</th>
                        <?php endif; ?>
                        <?php if ($total_tagihan->ipl) : ?>
                        <th class="f-table text-right" rowspan="2" style="vertical-align: middle">IPL(Rp.)</th>
                        <th class="f-table text-right" rowspan="2" style="vertical-align: middle">PPN(Rp.)</th>
                        <?php endif; ?>
                        <th class="f-table text-right" rowspan="2" style="vertical-align: middle">Denda(Rp.)</th>
                        <?php if ($total_tagihan->tunggakan) : ?>
                        <th class="f-table text-right" rowspan="2" style="vertical-align: middle">Tunggakan(Rp.)</th>
                        <?php endif; ?>
                        <th class="f-table text-right" rowspan="2" style="vertical-align: middle">Total(Rp.)</th>
                    </tr>
                    <tr>
                        <th class="f-table text-center">Penggunaan</th>
                        <th class="f-table text-center">Tagihan</th>
                        <?php if ($total_tagihan->air) : ?>
                        <th class="f-table text-right">Awal</th>
                        <th class="f-table text-right">Akhir</th>
                        <th class="f-table text-right">Pakai</th>
                        <?php endif; ?>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0 ;
                    foreach ($tagihan as $i => $v) :
                        ?>
                    <tr>
                        <td class="f-table text-right"><?= $i + 1 ?></td>                        
                        <td class="f-table text-center"><?= $v->periode_penggunaan ?></td>
                        <td class="f-table text-center"><?= $v->periode ?></td>
                        <?php if ($total_tagihan->air) : ?>
                        <td class="f-table text-right"><?= $v->meter_awal!==null?number_format($v->meter_awal):'' ?></td>
                        <td class="f-table text-right"><?= $v->meter_akhir!==null?number_format($v->meter_akhir):'' ?></td>
                        <td class="f-table text-right"><?= $v->pakai!==null?number_format($v->pakai):'' ?></td>
                        <?php endif; ?>
                        <?php if ($total_tagihan->lain) : ?>
                        <td class="f-table text-right"><?= number_format($v->tagihan_lain) ?></td>
                        <?php endif; ?>
                        <?php if ($total_tagihan->air) : ?>
                        <td class="f-table text-right"><?= number_format($v->air) ?></td>
                        <?php endif; ?>
                        <?php if ($total_tagihan->ipl) : ?>
                        <td class="f-table text-right"><?= number_format($v->ipl) ?></td>
                        <td class="f-table text-right"><?= number_format($v->ppn) ?></td>
                        <?php endif; ?>
                        <td class="f-table text-right"><?= number_format($v->denda) ?></td>
                        <?php if ($total_tagihan->tunggakan) : ?>
                        <td class="f-table text-right"><?= number_format($v->tunggakan) ?></td>
                        <?php endif; ?>
                        <td class="f-table text-right"><?= number_format($v->total) ?></td>
                    </tr>
                    <?php
                    endforeach;
                    ?>

                <tfoot>
                    <tr>

                        <td class='f-table' colspan="
                        <?php
                        if ($total_tagihan->air) {
                                echo(5);
                        } else {
                                echo(3);
                        }
                        ?>
                        "><b>Grand Total :</b></td>
                        <?php if ($total_tagihan->air) : ?>
                        <td class="f-table text-right"><?= number_format($total_tagihan->pakai) ?></td>
                        <?php endif; ?>
                        <?php if ($total_tagihan->lain) : ?>
                        <td class="f-table text-right"><?= number_format($total_tagihan->lain) ?></td>
                        <?php endif; ?>
                        <?php if ($total_tagihan->air) : ?>
                        <td class="f-table text-right"><?= number_format($total_tagihan->air) ?></td>
                        <?php endif; ?>
                        <?php if ($total_tagihan->ipl) : ?>
                        <td class="f-table text-right"><?= number_format($total_tagihan->ipl) ?></td>
                        <td class="f-table text-right"><?= number_format($total_tagihan->ppn) ?></td>
                        <?php endif; ?>
                        <td class="f-table text-right"><?= number_format($total_tagihan->denda) ?></td>
                        <?php if ($total_tagihan->tunggakan) : ?>
                        <td class="f-table text-right"><?= number_format($total_tagihan->tunggakan) ?></td>
                        <?php endif; ?>

                        <td class="f-table text-right"><?= number_format($total_tagihan->total) ?></td>
                    </tr>
                </tfoot>

            </table>
            <div <?php
                    if (($i + 1 >= 13 && $i + 1 <= 20) || (((($i + 1) - 20) % 23 >= 20) && ((($i + 1) - 21) % 23 <= 23)))
                        echo ("style='page-break-before: always;'");

                    ?>>
                <?php if($status_saldo_deposit==1):?>
                    <p class="lh-18 f-15" style="margin-bottom:6px;font-weight:bold;">
                        Saldo deposit sebesar : Rp.<?=$saldo_deposit?$saldo_deposit:0?>
                    </p>
                <?php endif;?>

                <p class="lh-18 f-15">
                    Jika Pembayaran dilakukan setelah tanggal 20 bulan berjalan akan dikenakan denda
                    kumulatif/penalti. Untuk Informasi lebih lanjut dapat menghubungi Customer Service di
                    kantor Estate Office
                    <?php
                    if ($unit->contactperson || $unit->phone) {
                        echo (" di ");
                        if ($unit->contactperson && $unit->phone) {
                            echo ("$unit->contactperson dan $unit->phone.");
                        } else if ($unit->contactperson) {
                            echo ("$unit->contactperson.");
                        } else if ($unit->phone) {
                            echo ("$unit->phone.");
                        }
                    } else {
                        echo (".");
                    }

                    ?>
                </p>
                <p class="lh-5">
                    Demikian Informasi yang dapat kami sampaikan, Atas kerjasamanya yang baik kami ucapkan terima
                    kasih.
                </p>
                <br>
                <div style="margin-top: 15px;margin-bottom:-100px;">
                    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row">
                        <tr>
                            <td class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <p class="lh-5 f-15">Hormat Kami,</p>
                                <p class="lh-5 f-15"><?= $unit->pt ?></p>
                                <?php if($ttd):?>
                                <img src="files/ttd/konfirmasi_tagihan/<?=$ttd?>" width="150px" height="150px" style="margin-top:10px"/>
                                <?php else:?>
                                <div style="height:150px;margin-top:10px">
                                </div>
                                <?php endif;?>
                                <p class="lh-5 f-15" ><u><?= $unit->pp_value ?></u></p>
                                <p class="lh-5 f-15"><?= $unit->pp_name ?></p>
                            </td>
                            <td>
                                <div style="border: 2px solid black; padding:10px">
                                    <?=$catatan?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>