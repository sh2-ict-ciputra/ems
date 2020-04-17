<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!-- select2 -->
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<style>
	.invalid {
		background-color: lightpink;
	}

	.has-error {
		border: 1px solid rgb(185, 74, 72) !important;
	}
	.text-right{
		text-align: right;
	}
</style>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?= site_url() ?>/Transaksi/P_unit/index/<?=$unit_id?>'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_transaksi_meter_air/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">

	<br>
	<form data-parsley-validate="" class="form-horizontal form-label-left" autocomplete="off">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Customer</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<select name="customer" required="" id="customer" class="form-control select2" placeholder="-- Pilih Kawasan - Blok - Unit - Pemilik --">
						<?php if($customer->id !=0):?>
							<option value="<?=$customer->id?>"selected><?=$customer->name?></option>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>


		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Saldo Deposit (Rp.)</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="saldo_deposit" type="text" class="form-control text-right" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Jumlah Deposit yang di Tambah (Rp.)</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="tambah_deposit" type="text" class="form-control text-right" readonly>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Saldo Akhir Deposit (Rp.)</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="saldo_akhir" type="text" class="form-control text-right" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Cara Pembayaran</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<select required="" id="cara_pembayaran" class="form-control select2" placeholder="-- Pilih Cara Pembayaran (Code - Name) --">
						<option value="" disabled selected>-- Pilih Cara Pembayaran (Code - Name) --</option>
						<?php
						foreach ($cara_pembayaran as $v) {
							echo ("<option value='$v->id' kode='$v->code' nama='$v->name'>$v->code - $v->name</option>");
						}
						?>
					</select> 
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="label_di_bayar_dengan">Di Bayar (Rp.)<br>dengan -</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="di_bayar_dengan" type="text" class="form-control text-right" readonly>
				</div>
			</div>
			
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Tanggal Pembayaran</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input type="text" value="<?=date("Y-m-d")?>" class="form-control text-right" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Deskripsi</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<textarea rows=8 type="text" id="deskripsi" name="deskripsi" class="form-control"></textarea>
				</div>
			</div>

		</div>
		<div class="clearfix"></div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="form-group" style="margin-top:20px">
				<div class="center-margin">
					<button class="btn btn-primary" type="reset">Reset</button>
					<a data-toggle="modal" data-target="#modal-save" id="button-submit" class="btn btn-success" >Submit</a>
            	</div>
			</div>
		</div>
	</form>
	<div class="x_content">
		<div class="modal fade" id="modal-save" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content" style="margin-top:100px;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" style="text-align:center;">Deposit<span class="grt"></span></h4>
					</div>
					<div class="modal-body">
						Apakah anda yakin untuk menyimpan data deposit<br>
						( Note : Pastikan anda telah benar-benar menerima uang deposit ) 
					</div>
					<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="delete_cancel_link">Close</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" id="button-modal-submit" disabled>Submit</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- jQuery -->
<!-- <script type="text/javascript" src="<?=base_url()?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script> -->

<script type="text/javascript">
	function tableICheck() {
        $("input.flat").iCheck({
            checkboxClass: "icheckbox_flat-green",
            radioClass: "iradio_flat-green"
        })
    }
	$(".select2").select2();

	$(function() {
		
		function notif(title,text,type){
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
		function enable_submit(){
			$("#button-modal-submit").attr("disabled",false);
		}
		function disable_submit(){
			$("#button-modal-submit").attr("disabled",true);
		}
		$(document).on("click", "button[type='reset']", function(){
			$("#customer").val('').change();
			$("#cara_pembayaran").val('').change();
			$("#label_di_bayar_dengan").html("Di Bayar (Rp.)<br>dengan -");
			
		});

		$("#button-modal-submit").click(function(){
			$.ajax({
				type: "POST",
				data:{
					tambah_deposit: unformatNumber($("#tambah_deposit").val()),
					customer_id : $("#customer").val(),
					cara_pembayaran_id : $("#cara_pembayaran").val(),
					deskripsi : $("#deskripsi").val()
				},
				url: "<?= site_url() ?>/Transaksi/P_deposit/ajax_save",
				dataType: "json",
				success: function(data) {
					if(data)
						notif('Sukses','Saldo Deposit Berhasil di Tambah','success');
					else
						notif('Gagal','Saldo Deposit Tidak Berhasil di Tambah','danger');
					$("#customer").val($("#customer").val()).change(); 

				}			
			})
		});
		$("#customer").select2({
			width: 'resolve',
			// resize:true,
			minimumInputLength: 1,
			placeholder: 'Kawasan - Blok - Unit - Pemilik',
			ajax: {
				type: "GET",
				dataType: "json",
				data : $(this).val(),
				url: "<?= site_url() ?>/Transaksi/P_deposit/get_ajax_customer/",
				data: function(params) {
					return {data: params.term}
				},
				processResults: function(data) {
					console.log(data);
					return {results: data};
				}
			}
		});
		function formatNumber(data){
			data = data+'';
			data = data.replace(/,/g,"");

			data = parseInt(data)?parseInt(data):0;
			data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
			return data;
			
		}
		function unformatNumber(data){
			data = data+'';
			return data.replace(/,/g,"");
		}
		$("#tambah_deposit").keyup(function(){
			data = formatNumber($(this).val());
			$(this).val(data);
			$("#di_bayar_dengan").val(data);
			$("#saldo_akhir").val(formatNumber(parseInt(unformatNumber(data))+parseInt(unformatNumber($("#saldo_deposit").val()))));
			if(($("#tambah_deposit").val() ||($("#tambah_deposit").val() != 0)) && $("#cara_pembayaran").val()){
				enable_submit();
			}else{
				disable_submit();
			}
		});
		$("#cara_pembayaran").change(function(){
			$("#label_di_bayar_dengan").html("Di Bayar (Rp.)<br>dengan "+$('option:selected', this).attr('nama'));
			$("#di_bayar_dengan_table").html($('option:selected', this).attr('kode'));
			if($("#tambah_deposit").val() && $("#cara_pembayaran").val()){
				enable_submit();
			}else{
				disable_submit();
			}
		});
		$("#customer").change(function() {
			$.ajax({
				type: "POST",
				url: "<?= site_url() ?>/Transaksi/P_deposit/ajax_get_deposit/"+$("#customer").val(),
				dataType: "json",
				success: function(data) {
					$("#saldo_deposit").val(formatNumber(data));
					
					$("#tambah_deposit").attr("readonly",false);
					$("#tambah_deposit").val("");
					$("#saldo_akhir").val("");
					$("#di_bayar_dengan").val("");
							
				}
			})
		});
		if(<?=$customer->id?>!=0)
			$("#customer").val(<?=$customer->id?>).change();
	});
</script>


<style>
	.full-chekclist {
		background-color: blue
	}
</style>