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
		<button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_registrasi_tvi'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_registrasi_tvi/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" enctype="multipart/form-data" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/p_registrasi_liaison_officer/aksi_pembayaran?id=<?=$this->input->get('id');?>">

		<div class="col-md-6">
			<div class="form-group two">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori LOI</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="kategori_loi_id" id="kategori" required="" class="form-control select2" disabled>
						<option value="">--Pilih Kategori--</option>
						<?php foreach($dataKategori as $v){?>
							<option value="<?=$v['id']?>" <?=($dataSelect->kategori == $v['id'])?"selected":""?>><?=$v['nama']?></option>
						<?php }?>
					</select>
				</div>
			</div>
			<div class="form-group two">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis LOI</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="jenis_loi_id" id="jenis" required="" class="form-control select2" disabled>
						<option value="">--Pilih Jenis--</option>
						<?php foreach($dataJenis as $v){?>
                            <option value="<?=$v['id']?>"<?=($dataSelect->jenis == $v['id'])?"selected":""?>><?=$v['nama']?></option>
                        <?php }?>
					</select>
				</div>
			</div>
			<div class="form-group two">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Peruntukkan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="peruntukan_loi_id" id="peruntukan" required="" class="peruntukan form-control select2" disabled>
						<option value="">--Pilih Peruntukan--</option>
						<?php foreach($dataPeruntukan as $v){?>
                            <option value="<?=$v['id']?>"<?=($dataSelect->peruntukan == $v['id'])?"selected":""?>><?=$v['nama']?></option>
                        <?php }?>
					</select>
				</div>
			</div>            	
			
        </div>

        <div class="clear-fix"></div>
        <br>

		<div id="view_data">
			<div class="row" style="margin-top: 35px;">
				<div class="col-md-12">
					<div class="col-md-6">->
						
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="project_name" name="project_name" value="<?=$dataSelect->project?>" readonly class="form-control">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="<?=$dataSelect->kawasan?>" id="kawasan_name" name="kawasan_name" readonly class="form-control">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="<?=$dataSelect->blok?>" id="blok_name" name="blok_name" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Unit</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="<?=$dataSelect->unit?>" id="unit_name" name="unit_name" readonly class="form-control unit">
							</div>
						</div>

						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" placeholder="Masukkan nama customer" value="<?=$dataSelect->customer?>" id="customer_name" name="customer_name"
								 readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor VA</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_va" id="nomor_va" value="" readonly class="form-control unit">
							</div>
						</div>

						<div class="form-group non_unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
							<div class="col-md-7 col-sm-9 col-xs-12">
								<input type="hidden" name="customer_id"  id="customer_id"  >
								<select name="customer_name2" id="customer_name2" class="form-control select2 non_unit">
									<option value="" selected="" disabled="">--Pilih Customer--</option>
									<?php
										foreach ($dataCustomer as $v) {
											echo("<option value='$v[id] | $v[name] '>$v[name]</option>");
										}
									?>
								</select>
								
							</div>
							<div class="col-md-2 col-sm-9 col-xs-12">
							<a class="btn btn-primary" href="<?=site_url(); ?>/p_master_customer/add">           
								DAFTAR
							</a>
							</div>
						</div>
						
					</div>
					<div class="col-md-6">
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_registrasi" id="nomor_registrasi" value="<?= $dataSelect->nomor_registrasi?>" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_telepon" id="nomor_telepon" value="<?=$dataSelect->telepon?>" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="nomor_handphone" id="nomor_handphone" value="<?=$dataSelect->no_hp?>" class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="email" required="" placeholder="Email" id="email" readonly="" name="email" value="<?=$dataSelect->email?>" class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">L. Bangunan Lama (m<sup>2</sup>)</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" required="" placeholder="Luas Bangunan" id="luaslama" readonly="" name="luaslama" value="<?=$dataSelect->luaslama?>" class="form-control unit">
							</div>
						</div>
						<div class="form-group luasanbaru">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">L. Bangunan Baru (m<sup>2</sup>)</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" required="" disabled placeholder="Luas Bangunan" id="luasbaru" name="luasbaru" value="<?=$dataSelect->luasbaru?>" class="form-control unit">
							</div>
						</div>

						<div class="form-group non_unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_registrasi2" id="nomor_registrasi2" value="Auto Generate" class="form-control non_unit" readonly>
							</div>
						</div>
						<div class="form-group non_unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_telepon2" id="nomor_telepon2" placeholder="Masukkan Nomor Telepon" value=""
								 class="form-control non_unit">
							</div>
						</div>
						<div class="form-group non_unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" placeholder="Masukkan Nomor Handphone" name="nomor_handphone2" id="nomor_handphone2" value=""
								 class="form-control non_unit">
							</div>
						</div>
						<div class="form-group non_unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="email" placeholder="Masukkan Email" name="email2" id="email2" value="" class="form-control non_unit">
							</div>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="clearfix"></div>
			<h4 id="label_transaksi">Paket Liaison Officer</h4>
			<hr>
			<div class="col-md-6">
				
				<div class="form-group two">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Pilih Paket</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="jenis_paket" id="paket" required="" disabled class="paket form-control select2">
							<option value="">--Pilih Paket--</option>
							<?php foreach($dataPaket as $v){?>
								<option value="<?=$v['id']?>"<?=($dataSelect->paket == $v['id'])?"selected":""?>><?=$v['nama']?></option>
							<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-6 col-sm-6 col-xs-12 paket_internet">
				<div class="form-group two">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Paket</label>
					<div id="lihat_paket">
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" name="kode" id="kode" value="<?=$dataSelect->kode_paket?>" readonly class="kode form-control ">
						</div>
					</div>
				</div>
				<div class="form-group two">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Registrasi (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" name="biaya_registrasi" value="<?=number_format($dataSelect->biaya_registrasi)?>" id="biaya_registrasi" readonly class="form-control ">
					</div>
				</div>
                <div class="form-group two">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Pengurusan Jenis (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" name="harga_jasa" value="<?=number_format($dataSelect->harga_jasa)?>" id="harga_jasa" readonly class="form-control currency">
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 paket_internet">
				<div class="form-group two">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Prakiraan Biaya (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" name="biaya_prakiraan" readonly="" id="biaya_prakiraan" value="<?=number_format($dataSelect->biaya_prakiraan)?>" class="form-control currency">
					</div>
				</div>
				<div class="form-group two">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<textarea name="keterangan"  readonly="" id="keterangan" class="keterangan form-control currency"></textarea>
					</div>
				</div>
				<div class="form-group two">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Total (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" name="" readonly="" id="subtotal" name="subtotal" value="<?= number_format($dataSelect->total_paket)?>" class="form-control currency">
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<h4 id="label_transaksi">Transaksi</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6">
						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Registrasi Sekarang</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="tanggal_document" 
									id="tanggal_document" value='<?=$dataSelect->tanggal_document?>' disabled readonly placeholder="Masukkan Tanggal Document"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>										
								</div>
							</div>
						</div>
										
						<div class="form-group two start ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Rencana Survei</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" value="<?=$dataSelect->tanggal_rencana_survei?>" name="tanggal_rencana_survei" 
									id="tanggal_rencana_survei" disabled placeholder="Masukkan Tanggal Rencana Survei"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group two start ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Rencana Pasang Instalasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="tanggal_rencana_pemasangan" 
									id="tanggal_pemasangan_mulai" disabled placeholder="Masukkan Tanggal Pasang Instalasi" value="<?=$dataSelect->tanggal_rencana_pemasangan?>"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						
						<div class="form-group two start ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Rencana Aktifasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="tanggal_rencana_aktifasi" 
									id="tanggal_aktifasi" value="<?=$dataSelect->tanggal_rencana_aktifasi?>" disabled placeholder="Masukkan Tanggal Aktifasi"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control" name="keterangan" id="keterangan" disabled placeholder="Masukkan keterangan"></textarea>
							</div>
						</div>
					</div>
						<div class="col-md-6">
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Expired</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="expired_date" 
									id="expired_date" disabled placeholder="Masukkan Tanggal Expired" value="<?=$dataSelect->expired_date?>"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Paket LOI</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="totalpaket" readonly="" value="<?= number_format($dataSelect->total_paket)?>" id="totalpaket" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Deposit Jaminan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="deposit_masuk" id="deposit_masuk" readonly="" value="<?=number_format($dataSelect->deposit_masuk)?>" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nilai Diskon</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly name="jumlah_diskon" id="jumlah_diskon" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Yang Harus Dibayar</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="total_bayar" value="<?=number_format($dataSelect->total)?>" readonly="" id="total_bayar" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-12 col-xs-12">Status Bayar</label>
							<div class="col-md-5 col-sm-12 col-xs-2">
								<div class="checkbox">
									<label>
										<p style="display:contents">Belum Lunas</p>
										<input type="checkbox" name="status_bayar" class="js-switch" value="1" />
										<p style="display:contents">Lunas</p>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12 col-xs-12">
				<div class="center-margin">
					<button type="submit" class="btn btn-success" id="submit">Submit</button>
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
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}
	$(".unit").show();
	$(".non_unit").hide();
	$(".two").show();
	$("body").on("click",".preview",function(){
		var project = $("project_name").val();
		$("#modal_unit").val(project);
	});
	$("#jenis").change(function(){
		url = '<?=site_url(); ?>/transaksi_lain/P_registrasi_liaison_officer/ajax_get_peruntukan';
		$.ajax({
			type: "post",
			url: url,
			data: {
				jenis : $("#jenis").val()
			},
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("#peruntukan")[0].innerHTML = "";
				$("#peruntukan").append("<option value=''>Pilih Peruntukan</option>");
				$.each(data, function (key, val) {
					$("#peruntukan").append("<option value='" + val.id + "'    >" + val.nama + "</option>");
				});
			}
		});

		url2 = '<?= site_url();?>/transaksi_lain/P_registrasi_liaison_officer/ajax_get_paket';
		$.ajax({
			type: "post",
			url: url2,
			data: {
				jenis : $("#jenis").val()
			},
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("#paket")[0].innerHTML = "";
				$("#paket").append("<option value=''>--Pilih Paket--</option>");
				$.each(data, function (key, val) {
					$("#paket").append("<option value='" + val.id + "|" + val.kode + "|" + val.biaya_registrasi + "|" + val.harga_jasa +"|" + val.biaya_prakiraan + "'>" + val.nama + "</option>");
				});
			}
		});
	});

	$("#peruntukan").change(function(){
		var jenis = $("#jenis").val();
		var peruntukan = $("#peruntukan").val();

		if(jenis == '1'){
			if(peruntukan == '1'){
				$(".luasanbaru").show();
				$("#luasbaru").removeAttr("disabled");
			}
		}
	});

	$("#paket").change(function(){
		$(".paket_internet").show();
		var paket = $("#paket").val();
		var pecah = paket.split('|');
		var kode = pecah[1];
		var biaya_registrasi = pecah[2];
		var harga_jasa = pecah[3];
		var biaya_prakiraan = pecah[4];
		$("#kode").val(kode);
		$("#biaya_registrasi").val(currency(biaya_registrasi));
		$("#harga_jasa").val(currency(harga_jasa));
		$("#biaya_prakiraan").val(currency(biaya_prakiraan));

		var total = parseInt(biaya_registrasi) + parseInt(harga_jasa) + parseInt(biaya_prakiraan);
		$("#subtotal").val(currency(total));
		var subtotal = $("#subtotal").val();
		$("#totalpaket").val(subtotal);
		$("#total_bayar").val(subtotal);
	});

	function getDate() 
	{
		var jumhari = $('#jumlah_hari_aktifasi').val();

		var  awal = $('#tanggal_pemasangan_mulai').val();

		awal = awal.substr(3,2)+"-"+awal.substr(0,2)+"-"+awal.substr(6,4)
		
		var pasang = new Date(awal);
		var dd = String(pasang.getDate()).padStart(2, '0');
		var mm = String(pasang.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = pasang.getFullYear();

		//today = mm + '/' + dd + '/' + yyyy;

		var pemasangan = yyyy + '-' + mm + '-' + dd;

		var startdate = new Date(pemasangan);
		var newdate = new Date();
		newdate.setDate(startdate.getDate() +  parseInt(jumhari) );
		var dd = newdate.getDate();
		var mm = newdate.getMonth() + 1;
		var y = newdate.getFullYear();

		//var someFormattedDate = ("0" + mm).slice(-2) + '-' + ("0" + dd).slice(-2) + '-' + y;

		var someFormattedDate = ("0" + dd).slice(-2) + '-' +  ("0" + mm).slice(-2) + '-' +   y;

		console.log(dd);
		console.log(mm);
		console.log(y);
	
		$('#tanggal_aktifasi').val(someFormattedDate);  
	}

	$(function () {
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			format: 'DD-MM-YYYY'
		});

	});

	$("#internet_flag").change(function(){
		if($("#internet_flag").is(':checked')){
			$(".form_inet").attr('disabled',false);
		}else{
			$(".form_inet").attr('disabled','disabled');
			$(".form_inet").val('0');
		}
	})
	$("#tv_flag").change(function(){
		if($("#tv_flag").is(':checked')){
			$(".form_tv").attr('disabled',false);
		}else{
			$(".form_tv").attr('disabled','disabled');
			$(".form_tv").val('0');
		}
	})

	$("#pilih_unit").change(function () {
		$("#submit").removeAttr("disabled");
		$("#paket").removeAttr("disabled");
		$("#jenis_pemasangan").removeAttr("disabled");
		$("#tanggal_document").removeAttr("disabled");
		$("#tanggal_rencana_survei").removeAttr("disabled");
		$("#tanggal_pemasangan_mulai").removeAttr("disabled");
		$("#jumlah_hari_aktifasi").removeAttr("disabled");
		$("#tanggal_aktifasi").removeAttr("disabled");
		$("#keterangan").removeAttr("disabled");
		$("#dokumen").removeAttr("disabled");
		$("#jumlah_hari_aktifasi").removeAttr("disabled");
		var pilih_unit = $("#pilih_unit").val();
		if (pilih_unit != 'non_unit')
		{
			url = '<?=site_url(); ?>/transaksi_lain/p_registrasi_liaison_officer/lihat_unit';
			var pilih_unit = $("#pilih_unit").val();
			//console.log(this.value);
			$.ajax({
				type: "post",
				url: url,
				data: {
					pilih_unit: pilih_unit
				},
				dataType: "json",
				success: function (data) {
					console.log(data);
					$(".unit").show();
					$(".non_unit").hide();
					$(".two").show();
					$("#project_name").val(data.project_name);
					$("#kawasan_name").val(data.kawasan_name);
					$("#blok_name").val(data.blok_name);
					$("#unit_name").val(data.no_unit);
					$("#luaslama").val(data.luas_bangunan);
					$("#customer_name").val(data.customer_name);
					$("#customer_id").val(data.customer_id);
					$("#type_unit").val('unit');
					$("#unit_id").val(data.unit_id);
					$("#nomor_va").val('0');
					$("#nomor_telepon").val(data.customer_homephone);
					$("#nomor_handphone").val(data.customer_mobilephone);
					$("#email").val(data.customer_email);
				}
			})

		} else if (pilih_unit == 'non_unit') {
			$(".unit").hide();
			$(".non_unit").show();
			$(".two").show();
			$("#type_unit").val('non_unit');

		}

	});

		$("#jenis_pemasangan").change(function () {
       
		var jenis_pemasangan = $("#jenis_pemasangan").val();

		if (jenis_pemasangan == '0')

		{

			$(".start").hide();

		var type_unit = $("#type_unit").val();

        if (type_unit == 'unit')
		{
			url = '<?=site_url(); ?>/transaksi_lain/p_registrasi_tvi/lihat_nomorreg_unit';
			var unit_id = $("#unit_id").val();
			//console.log(this.value);
			$.ajax({
				type: "post",
				url: url,
				data: {
					unit_id: unit_id
				},
				dataType: "json",
				success: function (data) {
					console.log(data);
     				$("#nomor_registrasi").val(data);
				}
			})
			url = '<?=site_url(); ?>/transaksi_lain/p_registrasi_tvi/lihat_aktifasi_unit';

			var unit_id = $("#unit_id").val();

			$.ajax({
				type: "post",
				url: url,
				data: {
					unit_id: unit_id
				},
				dataType: "json",
				success: function (data) {
					console.log(data);


     				$("#tanggal_aktifasi").val(data);
				}

			})

        }else if (type_unit == 'non_unit')

        {
            url = '<?=site_url(); ?>/transaksi_lain/p_registrasi_tvi/lihat_nomorreg_non_unit';


			var customer_id = $("#customer_id").val();
			//console.log(this.value);
			$.ajax({
				type: "post",
				url: url,
				data: {
					customer_id: customer_id
				},
				dataType: "json",
				success: function (data) {
					console.log(data);
     				$("#nomor_registrasi2").val(data);
				}


			})
			url = '<?=site_url(); ?>/transaksi_lain/p_registrasi_tvi/lihat_aktifasi_non_unit';


			var customer_id = $("#customer_id").val();


           	alert(customer_id);


			//console.log(this.value);
			$.ajax({
				type: "post",
				url: url,
				data: {
					customer_id: customer_id
				},
				dataType: "json",
				success: function (data) {
					console.log(data);


     				$("#tanggal_aktifasi").val(data);
				

				}

			})
        }
      
		} else if (jenis_pemasangan == '1') {

			$(".start").show();

		var type_unit = $("#type_unit").val();

        if (type_unit == 'unit')

		{
			
			$("#nomor_registrasi").val('Auto Generate');

		}
		else  if (type_unit == 'non_unit')
		{
            $("#nomor_registrasi2").val('Auto Generate');
		}
	}

	});

    $("#jenis_pemasangan").change(function () {

		// alert('tess');
       	var jenis_pemasangan = $("#jenis_pemasangan").val();
       	if (jenis_pemasangan == '0' )
       	{
           $("#harga_registrasi").val('0');
       	}
    });


	$("#customer_name2").change(function () {
		// alert('tess');
       	var customer = $("#customer_name2").val();
		var customer = customer.split('|');
		var pilih_customer = customer[0];
		url = '<?=site_url(); ?>/transaksi_lain/p_registrasi_tvi/lihat_customer';
		$.ajax({
			type: "post",
			url: url,
			data: {
				pilih_customer: pilih_customer
			},
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("#nomor_telepon2").val(data.customer_homephone);
				$("#nomor_handphone2").val(data.customer_mobilephone);
				$("#email2").val(data.customer_email);
				$("#customer_id").val(data.customer_id);
			}
		});
	});

</script>
