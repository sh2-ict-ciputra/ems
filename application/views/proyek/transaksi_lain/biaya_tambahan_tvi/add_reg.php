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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/p_biaya_tambahan_tvi/save">

        <div class="clear-fix"></div>
        <br>

		<div id="view_data">
			<div class="row" style="margin-top: 35px;">
				<div class="col-md-12">
					<div class="col-md-6">

						
						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" placeholder="Masukkan nama customer" id="customer_name" name="customer_name" value="<?=$dataRegistrasi->customer_name; ?>" 
								 readonly class="form-control unit">
							</div>
							  <input type="hidden" name="jenis_paket_id"  id="jenis_paket_id" value="<?=$dataRegistrasi->jenis_paket_id; ?>" >
							  <input type="hidden" name="registrasi_id"  id="registrasi_id" value="<?=$dataRegistrasi->registrasi_id; ?>" >
							  <input type="hidden" name="id_tagihan"  id="id_tagihan" value="<?=$dataRegistrasi->id_tagihan; ?>" >
						</div>


						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_registrasi" id="nomor_registrasi"  value="<?=$dataRegistrasi->nomor_registrasi; ?>"  readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_telepon" id="nomor_telepon" value="<?=$dataRegistrasi->nomor_telepon; ?>"  readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="nomor_handphone" id="nomor_handphone" value="<?=$dataRegistrasi->nomor_handphone; ?>"   class="form-control unit">
							</div>
						</div>
						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="email" required="" placeholder="Email" id="email" readonly="" name="email" value="<?=$dataRegistrasi->email; ?>"  class="form-control unit">
							</div>
						</div>

					

					</div>
						
					<div class="col-md-6">


                       
						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Layanan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="paket_layanan" id="paket_layanan" value="<?=$dataRegistrasi->paket_name; ?>"  readonly class="form-control payt">
							</div>
						</div>

							<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Layanan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="jenis_layanan" name="jenis_layanan" value="<?=$dataRegistrasi->jenis_layanan; ?>"  readonly class="form-control pay">
							</div>
						</div>


					    <div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="total" id="total" value="<?=$dataRegistrasi->total; ?>"   readonly 
								 class="form-control pay currency">
							</div>
						</div>


                         <div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Tagihan Biaya Tambahan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="nomor_tagihan_biaya_tambahan" name="nomor_tagihan_biaya_tambahan" value="<?=$kode_tagihan?>" readonly class="form-control pay">
							</div>
						</div>
						
						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Tagihan Biaya Tambahan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker pay" name="tanggal_tagihan_biaya_tambahan" id="tanggal_tagihan_biaya_tambahan"
									 placeholder="Masukkan Tanggal Tagihan Tambahan"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan Biaya Tambahan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" required="" placeholder="Masukkan Total tagihan biaya tambahan" id="total_tagihan_biaya_tambahan"  name="total_tagihan_biaya_tambahan" value="" class="form-control pay currency">
							</div>
						</div>



						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control pay" name="keterangan" id="keterangan" placeholder="Masukkan keterangan"></textarea>
							</div>
						</div>

										
					</div>
				</div>
			</div>

			  <div class="clearfix"></div>
        <div class="x_title pay" style="margin-top:20px">
            <h2>Rekening</h2>
            <div class="clearfix"></div>
        </div>
		<div class="col-md-12 pay">
			<p>
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>No</th>
							<th>Item</th>
							<th>Quantity</th>
							<th>Harga Satuan</th>
							<th>Description</th>
							<th>Hapus</th>
						</tr>
					</thead>
					<tbody id="tbody_rekening">
						<input id="idf" value="1" type="hidden" />

					</tbody>
				</table>

				<button id='btn-add-rekening' type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add
					Item</button>
			</p>
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

	
		$(function () {
			$('#btn-add-rekening').click(function () {
				var row = "<tr>" +
					"</tr>";
			});
		});

		$("#btn-add-rekening").click(function () {
				if ($(".no").html()) {
					idf = parseInt($(".no").last().html()) + 1;
				} else {
					idf = 1;
				}
				var str = "<tr id='srow" + idf + "'>" +
					"<td class='no'>" + idf + "</td>" +
					"<td><input type='text' class='form-control' name='item[]' id='item' placeholder='Masukkan Nama Item'/ required></td>" +
					"<td><input type='text' class='form-control' name='quantity[]' id='quantity' placeholder='Masukkan Quantity'/ required></td>" +
					"<td><input type='text' class='form-control currency' name='harga_satuan[]' id='harga_satuan' placeholder='Masukkan Harga Satuan'/ required></td>" +
					"<td><input type='text' class='form-control' name='description[]'id='description' placeholder='Masukkan Description'/ required></td>" +
					"<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf +
			        "\"); return false;'><i class='fa fa-trash'></i> </a></td>" +
			        "</tr>";
		$("#tbody_rekening").append(str);
		$('.select2').select2({
		width: 'resolve'
		});
		});






		function hapusElemen(idf) {
			$(idf).remove();
			var idf = document.getElementById("idf").value;
			idf = idf - 1;
			document.getElementById("idf").value = idf;
		}



	







	</script>
