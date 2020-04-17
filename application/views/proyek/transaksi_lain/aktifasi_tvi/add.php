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
		<button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_aktifasi_tvi'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_aktifasi_tvi/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/p_aktifasi_tvi/save">


     <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Posting TV Internet</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                     

         <div class="row">
          <div class="col-md-12">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Registrasi</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                   <input type="hidden" name="jumlah_hari"  id="jumlah_hari" value="<?=$dataRegistrasi->jumlah_hari; ?>" >
                    <input type="hidden" name="id"  id="id" value="<?=$dataRegistrasi->id; ?>" >
                    <input type="hidden" name="id_tagihan"  id="id_tagihan" value="<?=$dataRegistrasi->id_tagihan; ?>" >
                   <input type="hidden"  value="<?=$dataRegistrasi->nomor_registrasi; ?>" name="nomor_registrasi" readonly class="form-control">
                </div>
              </div>
               <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Customer</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text"  value="<?=$dataRegistrasi->customer_name; ?>" name="customer" id="customer" readonly class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Paket</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text"  value="<?=$dataRegistrasi->paket_name; ?>" name="jenis_paket" id="jenis_paket" readonly class="form-control">
                </div>
              </div>

              <div class="form-group two ">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Pemasangan Berakhir </label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class='input-group date '>
                  <input type="text" class="form-control datetimepicker" name="tanggal_pemasangan_berakhir" 
                  id="tanggal_pemasangan_berakhir" placeholder="Masukkan Tanggal Pemasangan Berakhir"> <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>
             
              <div class="form-group two ">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Awal </label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class='input-group date '>
                  <input type="text" class="form-control datetimepicker" name="tanggal_awal" onclick="getDate();"
                  id="tanggal_awal" placeholder="Masukkan Tanggal Awal"> <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>
              <div class="form-group two ">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Akhir </label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class='input-group date '>
                  <input type="text" class="form-control datetimepicker" name="tanggal_akhir" 
                  id="tanggal_akhir" placeholder="Masukkan Tanggal Akhir" readonly> <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>
              
            </div>
          </div>
        </div>
      
      

			<div class="col-md-12 col-xs-12">
				<div class="center-margin">
					<button class="btn btn-primary" type="reset">Reset</button>
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
			</div>

        </div>
    </form>
</div>
</div>


<script type="text/javascript">

  




	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? 0 : input.toLocaleString("en-US");
	}


	$(function () {
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			//format: 'MM-DD-YYYY'
      format: 'YYYY-MM-DD'
		});

	});



  function getDate() 
{
  var jumhari = $('#jumlah_hari').val();
  var awal = $('#tanggal_awal').val();
  var startdate = new Date(awal);
  var newdate = new Date();
  newdate.setDate(startdate.getDate() +  parseInt(jumhari) );
  var dd = newdate.getDate();
  var mm = newdate.getMonth() + 1;
  var y = newdate.getFullYear();
  var someFormattedDate = y + '-' + mm + '-' + dd;
   
  $('#tanggal_akhir').val(someFormattedDate);  
}


	

	
	
    
</script>
