<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?=base_url()?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>


<!-- switchery -->
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?=base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>


<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url()?>/transaksi_lain/P_registrasi_layanan_lain'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/P_registrasi_layanan_lain/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/Tools/Setting_project/P_pengiriman_sp/save">
		<div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12" style="width: 14%">Service</label>
            <div class="col-md-11 col-sm-9 col-xs-12" style="width: 85%">
                <input id="jumlah-sp" name="jumlah-sp" type="number" class="form-control" placeholder="Jumlah SP yang di perlukan untuk mencapai putus, minimal 1" min="1">
            </div>
		</div>
		<div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12" style="width: 14%">Jumlah SP</label>
            <div class="col-md-11 col-sm-9 col-xs-12" style="width: 85%">
                <input id="jumlah-sp" name="jumlah-sp" type="number" class="form-control" placeholder="Jumlah SP yang di perlukan untuk mencapai putus, minimal 1" min="1">
            </div>
		</div>
		<div style="text-align:center; margin: 10px 0px">
			<a id="generate" class="btn btn-primary">Generate</a>
		</div>
		<div id="sp">
			<div class="form-group form-group-jarak-sp">
				<label class="control-label col-md-2 col-sm-3 col-xs-12" style="width: 14%">Tunggakan - SP 1<br>( Hari Kalender )</label>
				<div class="col-md-11 col-sm-9 col-xs-12" style="width: 85%">
					<input name="jarak-sp[]" type="number" class="form-control jarak-sp" placeholder="Jarak Antara Tunggakan dengan SP 1" min="0">
				</div>
			</div>	
		</div>

		<div id="akhir" class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12" style="width: 14%">SP 1 - Pemutusan/SIP <br>( Hari Kalender )</label>
            <div class="col-md-11 col-sm-9 col-xs-12" style="width: 85%">
                <input name="jarak-sp[]" type="number" class="form-control jarak-sp" placeholder="Jarak Antara SP 1 dengan Pemutusan Service Air" min="0">
            </div>
		</div>
		<div class="col-md-12 col-xs-12">
			<div class="center-margin">
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>

    </form>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2>Log</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <br>
        <table class="table table-striped jambo_table bulk_action tableDT">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>User</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // $i = 0;
            // foreach ($data as $key => $v){
            //     $i++;
            //     echo('<tr>');
            //         echo("<td>$i</td>");
            //         echo("<td>$v[date]</td>");
            //         echo("<td>$v[name]</td>");
            //         echo("<td>");
            //             if($v['status']==1)
            //                 echo("Tambah");
            //             elseif($v['status']==2)
            //                 echo("Edit");
            //             else
            //                 echo("Hapus");
            //         echo("</td>");
            //         echo("
            //         <td class='col-md-1'>
            //             <a class='btn-modal btn btn-sm btn-primary col-md-12' data-toggle='modal' data-target='#modal' data-transfer='$v[id]' data-type='$v[status]'>
            //                 <i class='fa fa-pencil'></i>
            //             </a>
            //         </td>
            //     ");
            //     echo('</td></tr>');                
            // }
        ?>
        </tbody>
    </table>
    </div>
</div>
</div>

<script type="text/javascript">
	var before_sp = 1;
	var after_sp = 1;
	const sp_awal=$("#sp").html();
	$(function () {
		$("#generate").click(function(){	
			after_sp = $("#jumlah-sp").val()?parseInt($("#jumlah-sp").val()):0;
			console.log('after'+after_sp);
			console.log('before'+before_sp);
			
			if(after_sp > before_sp){
				selisih = after_sp - before_sp;
				console.log('selisih');
				j = before_sp;
				for(i=0;i<selisih;i++){
					console.log(i);
					j++;
					if(j>1)	$("#sp").append(sp_awal.replace(/SP 1/g,"SP "+j).replace(/Tunggakan/g,"SP "+(j-1)));
					else	$("#sp").append(sp_awal.replace(/SP 1/g,"SP "+j));
				}
			}
			else{
				selisih = before_sp - after_sp;
				console.log('selisih = '+selisih);
				fgjb = $(".form-group-jarak-sp").length; 
				for(i= fgjb - 1;i>=fgjb-selisih;i--){
					console.log("else"+i);
					$(".form-group-jarak-sp")[i].remove();
				}
			}
			$("#akhir").html($("#akhir").html().replace(RegExp("SP "+before_sp),"SP "+after_sp));
			before_sp = after_sp?after_sp:0;

		});
	});
</script>
