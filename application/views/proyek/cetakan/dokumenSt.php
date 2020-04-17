<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document Serah Terima</title>
</head>
<body>
    <table width="100%" style="border: 1px solid black;" >
        <tr>
            <td colspan="5" style="text-align:center;"><h1>Bukti Serah Terima</h1><hr></td>
        </tr>
        <tr>
            <td style="width:200px"><?=$dataSelect->kategori_nama?></td>
            <td style="width:20px" >:</td>
            <td colspan="3"><?=$dataSelect->jenis_nama?> - <?= $dataSelect->peruntukan_nama?></td>
        </tr>
        <tr>
            <td style="width:200px">Nomor Registrasi</td>
            <td style="width:20px">:</td>
            <td colspan="3"><?=$dataSelect->nomor_registrasi?></td>
        </tr>
        <tr>
            <td style="width:200px">Project</td>
            <td style="width:20px" >:</td>
            <td colspan="3"><?=$dataSelect->project?></td>
        </tr>
        <tr>
            <td style="width:200px">Kawasan - Blok - No. Unit</td>
            <td style="width:20px">:</td>
            <td colspan="3"><?=$dataSelect->kawasan; ?> - <?=$dataSelect->blok; ?> - <?=$dataSelect->unit; ?></td>
        </tr>
        <tr>
            <td style="width:200px">Customer</td>
            <td style="width:20px" >:</td>
            <td colspan="3"><?=$dataSelect->customer?></td>
        </tr>
        <tr>
            <td style="width:200px">Telp</td>
            <td style="width:20px" >:</td>
            <td colspan="3"><?=$dataSelect->telepon?></td>
        </tr>
        <tr>
            <td style="width:200px">No. Hp</td>
            <td style="width:20px" >:</td>
            <td colspan="3"><?=$dataSelect->no_hp?></td>
        </tr>
        <tr>
            <td style="width:200px">Email</td>
            <td style="width:20px" >:</td>
            <td colspan="3"><?=$dataSelect->email?></td>
        </tr>
        <tr>
            <td style="width:200px">Nama Paket</td>
            <td style="width:20px" >:</td>
            <td colspan="3"><?=$dataSelect->nama_paket?></td>
        </tr>
        <tr>
            <td style="width:200px">Biaya Registrasi</td>
            <td style="width:20px">:</td>
            <td style="text-align:right;">Rp. <?= number_format($dataSelect->biaya_registrasi)?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style="width:200px">Harga jasa Pengurusan</td>
            <td style="width:20px">:</td>
            <td style="text-align:right;">Rp. <?=number_format($dataSelect->harga_jasa)?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style="width:200px">Prakiraan Biaya</td>
            <td style="width:20px">:</td>
            <td style="text-align:right;">Rp. <?=number_format($dataSelect->biaya_prakiraan)?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style="width:200px">Tanggal Registrasi</td>
            <td style="width:20px">:</td>
            <td colspan="3"><?= $dataSelect->tanggal_document?></td>
        </tr>
        <tr>
            <td style="width:200px">Tanggal Survei</td>
            <td style="width:20px">:</td>
            <td colspan="3"><?= $dataSelect->tanggal_rencana_survei?></td>
        </tr>
        <tr>
            <td style="width:200px">Tanggal Pelaksanaan</td>
            <td style="width:20px">:</td>
            <td colspan="3"><?= $dataSelect->tanggal_instalasi?></td>
        </tr>
        <tr>
            <td style="width:200px">Total Paket</td>
            <td style="width:20px" >:</td>
            <td style="text-align: right;">Rp. <?=number_format($dataSelect->total_paket)?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style="width:200px">Deposit</td>
            <td style="width:20px" >:</td>
            <td style="text-align: right;">Rp. <?=number_format($dataSelect->deposit_masuk)?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style="width:200px">Total Pemakaian Deposit</td>
            <td style="width:20px">:</td>
            <?php
                $sisa_deposit = $dataSelect->deposit_masuk-($dataSelect->deposit_pemakaian+$dataSelect->deposit_pemakaian2);
                if($sisa_deposit<0){ ?>
            <td style="text-align: right;">Rp. <?=number_format($dataSelect->deposit_masuk)?></td>
            <?php }else{ ?>
            <td style="text-align: right;">Rp. <?=number_format(($dataSelect->deposit_pemakaian+$dataSelect->deposit_pemakaian2))?></td>
            <?php } ?>
        </tr>
        <?php
            if ($sisa_deposit<0) { ?>
            
        <tr>
            <td style="width:200px">Tambah Bayar</td>
            <td style="width:20px">:</td>
            <td style="text-align: right;">Rp. <?=number_format(($dataSelect->deposit_pemakaian+$dataSelect->deposit_pemakaian2)- $dataSelect->deposit_masuk)?></td>
        </tr>
                <?php
            }
        ?>
        <?php
            
        ?>
        <tr>
            <td style="width:200px">Sisa Deposit</td>
            <td style="width:20px">:</td>
            <td style="text-align: right;">Rp. <?=number_format(($sisa_deposit<0)?0:$sisa_deposit)?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style="width:200px">Status Deposit</td>
            <td style="width:20px">:</td>
            <td colspan="3">
                <?php
                    if($dataSelect->status_deposit==3){
                        echo 'Mutasi Deposit';
                    }elseif($dataSelect->status_deposit==2){
                        echo 'Withdrawal';
                    }else{
                        echo 'Serah Terima';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="5"><br></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">Jakarta, <?=$tanggal?></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;Hormat Kami,</td>
            <td></td>
            <td></td>
            <td colspan="2">Customer</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;&nbsp;<?= $unit->pt ?></td>
            <td></td>
            <td colspan="2"><br></td>
        </tr>
        <tr>
            <td colspan="5"><br></td>
        </tr>
        <tr>
            <td colspan="5"><br></td>
        </tr>
        <tr>
            <td colspan="5"><br></td>
        </tr>
        <tr>
            <td colspan="5"><br></td>
        </tr>
        <tr>
            <td>

            <?php if($ttd):?>
            <!-- <img src="files/ttd/konfirmasi_tagihan/<?=$ttd?>" width="150px" height="150px" style="margin-top:10px"/> -->
            <?php else:?>
            <div style="height:150px;margin-top:10px">
            </div>
            <?php endif;?>
            </td>
            <td></td>
            <td></td>
            <td colspan="2"><br></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;<u><?= $unit->pp_value ?></u></td>
            <td></td>
            <td></td>
            <td colspan="2"><u><?=$dataSelect->customer?></u></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;<?= $unit->pp_name ?></td>
            <td></td>
            <td></td>
            <td colspan="2"></td>
        </tr>
    </table>
</body>
</html>

