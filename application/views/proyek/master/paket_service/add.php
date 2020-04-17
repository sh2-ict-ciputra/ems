<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
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
		<button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.reload()">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" class="form-horizontal form-label-left" method="post" action="<?=site_url(); ?>/P_master_paket_service/save">
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih jenis_service">
				Pilih Jenis Service
				<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select type="text" id="jenis_service" required class="select2 form-control col-md-7 col-xs-12" name="jenis_service"
				 style="width:100%" placeholder="--Pilih Jenis Service--">
					<option disabled selected>-- Pilih Service --</option>
					<?php
                            foreach ($dataPaketService as $v) {
                                echo "<option value='$v[id]'>$v[name]</option>";
                            }
                        ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode Paket <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="code" name="code" required class="form-control col-md-7 col-xs-12" readonly>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Nama Pekerjaan <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="nama_pekerjaan" name="nama_pekerjaan" required class="form-control col-md-7 col-xs-12">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Satuan <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="satuan" name="satuan" required class="form-control col-md-7 col-xs-12">
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Biaya Satuan dengan Langganan<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="biaya_satuan_langganan" name="biaya_satuan_langganan" required class="form-control col-md-7 col-xs-12 currency">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Biaya Satuan tanpa Langganan<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="biaya_satuan_tanpa_langganan" name="biaya_satuan_tanpa_langganan" required class="form-control col-md-7 col-xs-12 currency">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="minimal_langganan">Minimal Untuk Langganan (periode)<span class="required">*</span>
			</label>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<input type="text" id="minimal_langganan" name="minimal_langganan" required class="form-control col-md-7 col-xs-12 currency">
			</div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<select class="form-control col-md-12" name="tipe_periode" id="tipe_periode" required>
					<option value="" disabled selected>Pilih Tipe</option>
					<option value="1">Hari</option>
					<option value="2">Bulan</option>
					<option value="3">Tahun</option>
				</select>
			</div>
		</div>
		<div id="view_biaya_registrasi">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Registrasi</label>
				<div class="col-md-1 col-sm-3 col-xs-12">
					<div class="">
						<label>
							<input id="biaya_registrasi_aktif" type="checkbox" class="js-switch" name="biaya_registrasi_aktif" value='1' />
							Aktif
						</label>
					</div>
				</div>
				<div class="col-md-5 col-sm-6 col-xs-12">
					<input type="text" id="biaya_registrasi" name="biaya_registrasi" required value="0" disabled class="form-control col-md-7 col-xs-12 currency">
				</div>
			</div>
		</div>
		<div id="view_biaya_pemasangan">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Pemasangan</label>
				<div class="col-md-1 col-sm-3 col-xs-12">
					<div class="">
						<label>
							<input id="biaya_pemasangan_aktif" type="checkbox" class="js-switch" name="biaya_pemasangan_aktif" value='1' />
							Aktif
						</label>
					</div>
				</div>
				<div class="col-md-5 col-sm-6 col-xs-12">
					<input type="text" id="biaya_pemasangan" name="biaya_pemasangan" required value="0" disabled class="form-control col-md-7 col-xs-12 currency ">
				</div>
			</div>
		</div>






		<div class="form-group">
			<div class="center-margin">
				<button class="btn btn-primary" type="reset">Reset</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>
	</form>
</div>
</div>
</form>


<script type="text/javascript">
	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}

	$(function () {
		$(".select2").select2();
		$("#nama_pekerjaan").keyup(function(){
			$("#code").val($("#nama_pekerjaan").val().toLowerCase().replace(/ /g,'_'));
		});
		$("#biaya_registrasi_aktif").change(function () {
			if ($("#biaya_registrasi_aktif").is(':checked')) {
				$("#biaya_registrasi").attr('disabled', false);

			} else {
				$("#biaya_registrasi").attr('disabled', true);
				$("#biaya_registrasi").val('0');
			}
		});
		$("#biaya_pemasangan_aktif").change(function () {
			if ($("#biaya_pemasangan_aktif").is(':checked')) {
				$("#biaya_pemasangan").attr('disabled', false);

			} else {
				$("#biaya_pemasangan").attr('disabled', true);
				$("#biaya_pemasangan").val('0');
			}
		});

	});

</script>
