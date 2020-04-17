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
		<button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_pembayaran_tvi'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_pembayaran_tvi/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/p_pembayaran_via_bank_tvi/save">


     <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Pembayaran Via Bank TV Internet</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                      <div class="title" id="print_proses"></div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?=$kode_transfer?>" required name="kode" readonly>
                        </div>
                     
                      </div>

                     
                    
                      <div class="form-group  ">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Pembayaran </label>
              <div class="col-md-6 col-sm-9 col-xs-12">
                <div class='input-group date '>
                  <input type="text" class="form-control datetimepicker" name="tanggal_pembayaran" id="tanggal_pembayaran"
                   placeholder="Masukkan Tanggal Pembayaran"> <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                         <select required="" id="banlk" name="bank" class="form-control select2">
							<option value="" selected="" disabled="">--Pilih Bank--</option>
							<?php
			                        foreach ($dataBank as $v) {
			                            echo("<option value='$v[id]'>$v[name]</option>");
			                        }
			                    ?>
						</select>
                        </div>
                      </div>

                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pembayaran</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <select name="jenis_pembayaran" class="form-control select2">
                            <option value="">--Pilih Jenis Pembayaran--</option>
                            <option value="va">Virtual Account</option>
                            <option value="transfer">Transfer</option>
                            <option value="auto_debet">Auto Debet</option>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Rekening</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required name="nomor_rekening" placeholder="Masukkan Nomor Rekening">
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Rekening</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required name="nama_rekening" placeholder="Masukkan Nama Rekening">
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Transfer</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <input type="text" class="form-control currency" required name="total_transfer" placeholder="Masukkan Total Transfer">
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <textarea class="form-control" name="keterangan" placeholder="Masukkan Keterangan"></textarea>
                        </div>
                      </div>  

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <select name="status" class="form-control select2">
                            <option value="open">Open</option>
                            <option value="posted">Posted</option>
                          </select>
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
			format: 'MM-DD-YYYY'
		});

	});

	

	
	
    
</script>
