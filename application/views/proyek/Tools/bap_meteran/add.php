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
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/P_registrasi_layanan_lain/save">
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12" style="width: 14%">Unit</label>
            <div class="col-md-11 col-sm-9 col-xs-12" style="width: 85%">
                <select required="" id="unit" name="unit" class="form-control select2">
                    <option value="" selected="" disabled="">--Pilih Unit-- ( Kawasan - Blok - No Unit )</option>
                    <!-- <option value="non_unit">--Non Unit--</option> -->
                    <?php
                            foreach ($dataUnit as $v) {
                                echo("<option value='$v[id]'>$v[kawasan_name] - $v[blok_name] - $v[no_unit]</option>");
                            }
                        ?>
                </select>
            </div>
        </div>

        <div class="clear-fix"></div>
        <br>
			
		<div id="data_unit" class="col-md-4 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">No. Registrasi</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="no_registrasi" type="text" class="form-control" required name="no_registrasi" value="<?=$no_regis?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Luas Tanah (m2)</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="unit_luas_tanah" type="text" class="form-control" required name="unit_luas_tanah" value="" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Luas Bangunan (m2)</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="unit_luas_bangunan" type="text" class="form-control" required name="unit_luas_bangunan" value="" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Luas Taman (m2)</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="unit_luas_taman" type="text" class="form-control" required name="unit_luas_taman" value="" readonly>
				</div>
			</div>
			
		</div>
		
		<div id="pemilik" class="col-md-4 col-xs-12">

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Kode Pemilik</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="pemilik_kode" type="text" class="form-control" required name="pemilik_kode" value="" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Pemilik</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="pemilik_nama" type="text" class="form-control" required name="pemilik_nama" value="" readonly>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Mobile Phone</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="pemilik_mobile_phone" type="text" class="form-control" required name="pemilik_mobile_phone" value="" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Home Phone</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="pemilik_home_phone" type="text" class="form-control" required name="pemilik_home_phone" value="" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Email</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="pemilik_email" type="text" class="form-control" required name="pemilik_email" value="" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Alamat</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<textarea id="pemilik_alamat" type="text" class="form-control" required name="pemilik_alamat" readonly>
					</textarea>
				</div>
			</div>
		</div>
		<div id="penghuni" class="col-md-4 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Kode Penghuni</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="penghuni_kode" type="text" class="form-control" required name="penghuni_kode" value="" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Penghuni</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="penghuni_nama" type="text" class="form-control" required name="penghuni_nama" value="" readonly>
				</div>
			</div>	
			
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Mobile Phone</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="penghuni_mobile_phone" type="text" class="form-control" required name="penghuni_mobile_phone" value="" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Home Phone</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="penghuni_home_phone" type="text" class="form-control" required name="penghuni_home_phone" value="" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Email</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input id="penghuni_email" type="text" class="form-control" required name="penghuni_email" value="" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Alamat</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<textarea id="penghuni_alamat" type="text" class="form-control" required name="penghuni_alamat" readonly>
					</textarea>
				</div>
			</div>
		</div>

		
		<div id="data_registrasi" class="col-md-12 col-xs-12">
			<div class="x_title" style="margin-top:20px">
				<h2>Data Meteran</h2>
				<div class="clearfix"></div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12" style="width: 14%">Jenis Transaksi</label>
                <div class="col-md-11 col-sm-9 col-xs-12" style="width: 85%">
                    <select required="" id="jenis_transaksi" name="jenis_transaksi" class="form-control select2">
                        <option value="" selected="" disabled="">--Pilih Jenis Transaksi</option>
                        <option value="1">Pasang Baru</option>
                        <option value="2">Ganti Meteran</option>
                        <option value="3">Putus</option>
                    </select>
                </div>
            </div>
			<table class="table table-responsive">
				<thead>
					<tr>
						<th style="text-align: right;width: 5%">No</th>
						<th class='col-md-2' style="text-align: center">Service</th>
						<th class='col-md-2' style="text-align: center">Paket Service</th>
						<th class='col-md-2' colspan='2' style="text-align: center">Periode</th>
						<th style="text-align: right">Jumlah Satuan</th>
						<th style="text-align: right">Satuan</th>
						<th style="text-align: right">Harga Satuan</th>
						<th style="text-align: right">Harga Registrasi</th>
						<th style="text-align: right">Diskon</th>
						<th style="text-align: right">Total</th>
						<th>Hapus</th>
					</tr>
				</thead>
				<tbody id="tbody_service">
					

				</tbody>
			</table>

			<button id='btn-add-service' type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add
						Service</button>
		</div>

		

		<div class="col-md-12 col-xs-12">
			<div class="center-margin">
				<button class="btn btn-primary" type="reset">Reset</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>

    </form>
</div>
</div>

<script type="text/javascript">
	var no_service = 1;
	var tmp_service = "<tr id='row-1'>"+
								"<td>"+
									"<input class='form-control no' value='1' readonly style='text-align: right'>"+
								"</td>"+
								"<td>"+
									"<select class='service form-control' name='service[]'>"+
										"<?php
											echo('<option selected disabled>-- Pilih Service --</option>');
											foreach ($dataService as $v) {
												echo('<option value='.$v->id.'>'.$v->name.'</option>');
											}
										?>"+
									"</select>"+
								"</td>"+
								"<td>"+
									"<select id='' class='paket-service form-control' name=''>"+
									"<option selected disabled>-- Pilih Paket Service --</option>"+

									"</select>"+
								"</td>"+
								"<td>"+
									"<input type='text' id='' class='form-control datetimepicker_month' name=''>"+
								"</td>"+
								"<td>"+
									"<input type='text' id='' class='form-control datetimepicker_month' name=''>"+
								"</td>"+
								"<td>"+
									"<input id='' class='jumlah-satuan form-control' name=''>"+
								"</td>"+
								"<td>"+
									"<input id='' class='satuan form-control' name='' readonly style='text-align: right'>"+
								"</td>"+
								"<td>"+
									"<input id='' class='harga-satuan form-control' name='' readonly style='text-align: right'>"+
								"</td>"+
								"<td>"+
									"<input id='' class='harga-registrasi form-control' name='' style='text-align: right'>"+
								"</td>"+
								"<td>"+
									"<input id='' class='diskon form-control' name='' readonly style='text-align: right'>"+
								"</td>"+
								"<td>"+
									"<input id='' class='total form-control' name='' readonly style='text-align: right'>"+
								"</td>"+
								"<td> <a class='btn btn-danger delete'><i class='fa fa-trash'></i> </a></td>"+
							"</tr>";
	
	$("#tbody_service").append(tmp_service);
    function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}


	$(function () {
		$('.datetimepicker_month').datetimepicker({
			viewMode: 'years',
			format: 'MM/YYYY'
		});
		$("#btn-add-service").click(function(){
			no_service++;
			$("#tbody_service").append(tmp_service);
			$(".no").last().val(no_service);
			$('.datetimepicker_month').datetimepicker({
			viewMode: 'years',
			format: 'MM/YYYY'
		});
		});
		$("#tbody_service").on("click",".delete", function(){
			console.log('delete');
			$(this).parent().parent().remove();
		});
		$("#tbody_service").on("change",".service", function(){
			console.log("haha");
		// $(".service").change(function(){
			url = '<?=site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_paket_service';
			var thisRow = $(this);
			var id = thisRow.val();
			
			$.ajax({
				type: "post",
				url: url,
				data: {
					id: id
				},
				dataType: "json",
				success: function (data) {
					thisRow.parent().parent().children().children(".paket-service").html('');
					thisRow.parent().parent().children().children(".paket-service").append("<option selected disabled>-- Pilih Paket Service --</option>");
					data.forEach(v => {
						thisRow.parent().parent().children().children(".paket-service").append("<option value='" + v.id + "'>" + v.name + "</option>");
					});					
				}
			});
		});
		$("#tbody_service").on("change",".paket-service", function(){
			url = '<?=site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_info_paket_service';
			var thisRow = $(this);
			var id = thisRow.val();
			
			$.ajax({
				type: "post",
				url: url,
				data: {
					id: id
				},
				dataType: "json",
				success: function (data) {
					console.log(data);
					console.log(thisRow);
					thisRow.parent().parent().children().children(".jumlah-satuan").val(1);
					thisRow.parent().parent().children().children(".satuan").val(data.satuan);
					thisRow.parent().parent().children().children(".harga-satuan").val(data.harga);
					thisRow.parent().parent().children().children(".harga-registrasi").val(data.biaya_registrasi);
					thisRow.parent().parent().children().children(".total").val(data.harga+data.biaya_registrasi);
					
							
				}
			});
		});
        $('.select2').select2();
		$("#unit").change(function () {
			url = '<?=site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_pemilik_penghuni';
			var id = $("#unit").val();
			//console.log(this.value);
			$.ajax({
				type: "post",
				url: url,
				data: {
					id: id
				},
				dataType: "json",
				success: function (data) {
					console.log(data);
					$("#pemilik_email").val(data.pemilik_email);
					$("#pemilik_home_phone").val(data.pemilik_home_phone);
					$("#pemilik_kode").val(data.pemilik_kode);
					$("#pemilik_mobile_phone").val(data.pemilik_mobile_phone);
					$("#pemilik_nama").val(data.pemilik_nama);
					$("#pemilik_alamat").html(data.pemilik_alamat);
					$("#penghuni_email").val(data.penghuni_email);
					$("#penghuni_home_phone").val(data.penghuni_home_phone);
					$("#penghuni_kode").val(data.penghuni_kode);
					$("#penghuni_mobile_phone").val(data.penghuni_mobile_phone);
					$("#penghuni_nama").val(data.penghuni_nama);
					$("#penghuni_alamat").html(data.penghuni_alamat);

					$("#unit_luas_tanah").val(data.luas_tanah);
					$("#unit_luas_bangunan").val(data.luas_bangunan);
					$("#unit_luas_taman").val(data.luas_taman);

				}
			});
		});

	});

	
	$("#service").change(function () {
		url = '<?=site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_paket';
		var id = $("#service").val();
		//console.log(this.value);
		$.ajax({
			type: "post",
			url: url,
			data: {
				id: id
			},
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("#paket").html("");
				$("#paket").append("<option value='' disabled selected>-- Pilih Paket --</option>");
				data.forEach(v => {
					$("#paket").append("<option value='" + v.id + "'>" + v.name + "</option>");		
				});
			}
		});
	});
	$("#paket").change(function () {
		url = '<?=site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_harga_paket';
		var id = $("#paket").val();
		console.log(id);
		$("#tagihan_total").val("");
		$.ajax({
			type: "post",
			url: url,
			data: {
				id: id
			},
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("#tagihan_total").val(currency(data.harga));				
			}
		});
	});
</script>
