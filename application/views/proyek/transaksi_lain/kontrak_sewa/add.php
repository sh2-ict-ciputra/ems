<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/P_kontrak_sewa/save">

		<div class="col-md-10">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe Unit</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select required="" id="pilih_unit" name="pilih_unit" class="form-control select2">
						<option value="" selected="" disabled="">--Pilih Unit--</option>
						<option value="non_unit">--Non Unit--</option>
						<?php
								foreach ($dataUnit as $v) {
									echo("<option value='$v[id]'>$v[project_name] - $v[kawasan_name] - $v[blok_name] -  $v[no_unit]</option>");
								}
							?>
					</select>
				</div>
			</div>
        </div>

        <div class="clear-fix"></div>
        <br>

		<div id="view_data">
			<div class="row" style="margin-top: 35px;">
				<div class="col-md-12">
					<div class="col-md-6">
                        	
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
							
								<input type="hidden" name="jumlah_hari"  id="jumlah_hari"  >
								<input type="hidden" name="type_unit"  id="type_unit"  >
								<input type="hidden" name="unit_id"  id="unit_id"  >
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="kawasan_name" name="kawasan_name" readonly class="form-control">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="blok_name" name="blok_name" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Unit</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="unit_name" name="unit_name" readonly class="form-control unit">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Tanah (m<sup>2</sup>)</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="luas_tanah" name="luas_tanah" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Bangunan (m<sup>2</sup>)</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="luas_bangunan" name="luas_bangunan" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Taman (m<sup>2</sup>)</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="luas_taman" name="luas_taman" readonly class="form-control unit">
							</div>
						</div>
					</div>
				</div>
			</div>

			<h4 id="label_transaksi" hidden>Form Kontrak</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6">
						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Document </label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="tanggal_document" 
									id="tanggal_document" value='<?= date('d-m-Y') ?>'  placeholder="Masukkan Tanggal Document"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>										
								</div>
							</div>
						</div>

						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Penyewa</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select name="penyewa" id="penyewa" required="" class="form-control select2">
									<option value="">-- Pilih Nama Penyewa --</option>
									<?php
										foreach ($dataCustomer as $v) {
											echo("<option value='$v[id]'>$v[name]</option>");
										}
									?>
								</select>
							</div>
						</div>
                        <div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select name="jenis_bayar" id="jenis_bayar" required="" class="form-control select2">
									<option value="">--Pilih Jenis Pembayaran--</option>
									<option value="1">Lunas</option>
									<option value="0">Angsuran</option>
								</select>
							</div>
						</div>

						 <div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Penyewaan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select name="jenis_sewa" id="jenis_sewa" required="" class="form-control select2">
									<option value="">--Pilih Jenis Penyewaan--</option>
									<option value="1">Ruko</option>
									<option value="0">Restoran</option>
								</select>
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control" id="alamat" name="alamat" placeholder=""></textarea>
							</div>
						</div>
						

					</div>
					<div class="col-md-6">
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Handphone</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nohp" value="" id="nohp" class="form-control ">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Penyewaan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="biaya_sewa" id="biaya_sewa" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Deposit Jaminan Sewa</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="deposit_sewa" id="deposit_sewa" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Mulai Sewa</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="tanggal_sewa" 
									id="tanggal_sewa"  placeholder="Masukkan Tanggal Document"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>										
								</div>
							</div>
						</div>
						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Batas Akhir Sewa </label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="batas_sewa" 
									id="batas_sewa"  placeholder="Masukkan Tanggal Document"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>										
								</div>
							</div>
						</div>
					</div>
					<div class="x_content">
                        <div class="form-group two" id="range" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li id="komponen-luas-bangunan" role="presentation" class="active">
                                    <a href="#tab_content1" id="komponen-bangunan" role="tab" data-toggle="tab" aria-expanded="true">Detail Penghuni</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <p>
                                        <table class="table table-responsive">
                                            <thead>
                                                <th>No</th>
												<th>Nama Penghuni</th>
												<th>Alamat</th>
                                                <th>No. Handhphone</th>
                                                <th>Pekerjaan</th>
                                                <th>Hapus</th>
                                            </thead>
                                            <tbody id="tbody_add_penghuni">
                                                <tr>
                                                    <td><input id="idf" value="1" type="hidden" /></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </p>
                                    <button type="button" id="button_add_penghuni" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>
                                        Tambah Penghuni </button>
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
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}
	$(".unit").hide();
	$(".non_unit").hide();
	$(".two").hide();



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
	$("#button_add_penghuni").click(function() {
        if ($(".no").html()) {
            idf = parseInt($(".no").last().html()) + 1;
        } else {
            idf = 1;
        }
        var str = "<tr id='srow" + idf + "'>" +
            "<td class='no'>" + idf + "</td>" +
            "<td><input type='text' class='form-control' value='' name='penghuni[]' placeholder='Masukkan Nama Penghuni' /></td>" +
			"<td><input type='text' class='form-control' value='' placeholder='Masukkan Alamat' name='alamatpenghuni[]' placeholder='' /></td>" +
            "<td><input type='text' onkeydown='return numbersonly(this, event);' class='form-control' placeholder='Masukkan Nomor Handphone'  name='no_hp[]'  /></td>" +
            "<td><input type='text' class='form-control' value='' placeholder='Masukkan Pekerjaan' name='pekerjaan[]' placeholder='' /></td>" +
            "<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>" +
            "</tr>";
        $("#tbody_add_penghuni").append(str);
    });

	function hapusElemen(idf) {
        $(idf).remove();
        var idf = document.getElementById("idf").value;
        idf = idf - 1;
        document.getElementById("idf").value = idf;
    }



	$(function () {
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			format: 'DD-MM-YYYY'
		});

	});

	$("#pilih_unit").change(function () {
        $("#label_transaksi").show();

		var pilih_unit = $("#pilih_unit").val();


		if (pilih_unit != 'non_unit')

		{


			url = '<?=site_url(); ?>/transaksi_lain/p_kontrak_sewa/lihat_unit';
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
					$("#customer_name").val(data.customer_name);
					$("#customer_id").val(data.customer_id);
					$("#type_unit").val('unit');
					$("#unit_id").val(data.unit_id);
					$("#luas_tanah").val(data.luas_tanah);
					$("#luas_bangunan").val(data.luas_bangunan);
					$("#luas_taman").val(data.luas_taman);

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

			//alert('tess');
       

		var jenis_pemasangan = $("#jenis_pemasangan").val();


		//alert(jenis_pemasangan);


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


           alert(unit_id);


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


     				$("#tanggal_aktifasi").val(data);
				

				}


			})




        }  else if (type_unit == 'non_unit')


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
</script>
