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
</style>

<body>
    <div id="header">
        <div style="width: fit-content;text-align: center; margin-top:20px">
            <img src="images/logoCiputra.png" width="15%" style="align-content:center">
        </div>

        <div>
            <div class="" style="width: fit-content;text-align: center; margin-top:5px; margin-bottom:100px">
                <p class="align-center f-20"><u>Surat Pemberitahuan</u></p>
            </div>
        </div>
    </div>

    <div id="container">
        <div id="body">
            <div>
                <p class="f-15">No : 1/CGCB/COL/SE/2019</p>
                <p class="f-15">Hal : Pemberitahuan Ke 1</p>
                <p class="f-15">Service : Lingkungan</p>
            </div>
            <div style="margin-top:10px">
                <p class="f-15">Kepada Yth,</p>
                <p class="f-15">Bpk/ibu <?= strtoupper($unit->pemilik) ?></p>
                <p class="f-15"><?= strtoupper($unit->address) ?></p>
            </div>
            <br>
            <div>
                <p class="f-15">Dengan Hormat,</p>
                <p class="f-15 lh-15">
                    Pertama-tama kami mengucapkan terima kasih atas kepercayaan Bapak/Ibu terhadap kami, dengan unit
                    <?="$unit->kawasan $unit->blok/$unit->no_unit"?> 
                </p>

            </div>
            <table class="table table-striped" style="margin-bottom:0">
                <thead>
                    <tr>
                        <th>Tanggal <br>Jatuh Tempo</th>
                        <th>Nilai Tagihan</th>
                        <th>Keterlambatan <br>(Hari)</th>
                        <th>Nomor SP - <br>Tanggal SP</th>
                        <th>Denda Keterlambatan <br> (Sampai Hari Ini)*</th>                            
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$tagihan_periode?></td>
                        <td><?=number_format($tagihan_service)?></td>
                        <td></td>
                        <td></td>
                        <td><?=number_format($denda_service)?></td>
                    </tr>
                </tbody>
            </table>
            <div>
                <p class="lh-18" style="margin-top:10px">
                    Sampai Saat ini kami belum menerima pembayaran. Kami mohon agar Bapak/Ibu dapat melunasi pembayaran tersebut. Adapun pembayarannya dapat di lakukan dengan mentransfer
                    ke VA(Virtual Account) yang ada di Aplikasi Ciputra.<br><br>
                    Akan tetapi jika Bapak/Ibu  telah menyelesaikan kewajiban tersebut, kami mohon bukti pembayarannya dapat di kirim melalui Whatsapp Ke No. 0812-2222-9974 atau Email rfajrika22@gmail.com<br>
                    Atas Perhatian dan Kerjasama Bapak/Ibu, kami ucapkan terimakasih.
                </p>
                <div style="margin-top: 15px;margin-bottom:-100px;">
                    <table class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row">
                        <tr>
                            <td class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <p class="lh-5 f-15">Hormat Kami,</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>