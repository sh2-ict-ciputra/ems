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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/p_registrasi_tvi/save">

		<div class="col-md-12">
			<select required="" id="pilih_unit" name="pilih_unit" class="form-control select2">
				<option value="" selected="" disabled="">--Pilih Unit--</option>
				<option value="non_unit">--Non Unit--</option>
				<?php
                        foreach ($dataUnit as $v) {
                            echo("<option value='$v[id]'>$v[project_name] - $v[kawasan_name] - $v[blok_name]</option>");
                        }
                    ?>
			</select>
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
								<input type="hidden" name="customer_id"  id="customer_id"  >
								<input type="hidden" name="jumlah_hari"  id="jumlah_hari"  >
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
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="unit_name" name="unit_name" readonly class="form-control unit">
							</div>
						</div>

						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" placeholder="Masukkan nama customer" value="" id="customer_name" name="customer_name"
								 readonly class="form-control unit">
							</div>
						</div>

						<div class="form-group non_unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select name="customer_name2" id="customer_name2" class="form-control select2 non_unit">
									<option value="" selected="" disabled="">--Pilih Customer--</option>
									<?php
                        foreach ($dataCustomer as $v) {
                            echo("<option value='$v[id] | $v[name] '>$v[name]</option>");
                        }
                    ?>
								</select>
							</div>
						</div>

						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pemasangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select name="jenis_pemasangan" id="jenis_pemasangan" required="" class="form-control select2">
									<option value="">--Pilih Jenis Pemasangan--</option>
									<option value="1">Pemasangan Baru</option>
									<option value="0">Perpanjangan Paket</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-6">

						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor VA</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_va" id="nomor_va" value="" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_registrasi" id="nomor_registrasi" value="<?=$kode_reg?>" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_telepon" id="nomor_telepon" value="" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="nomor_handphone" id="nomor_handphone" value="" class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="email" required="" placeholder="Email" id="email" readonly="" name="email" value="" class="form-control unit">
							</div>
						</div>

						<div class="form-group non_unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_registrasi2" id="nomor_registrasi2" value="<?=$kode_reg?>" class="form-control non_unit">
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

			<h4 id="label_transaksi" hidden>Transaksi</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6">
						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Document </label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="tanggal_document" 
									id="tanggal_document"	placeholder="Masukkan Tanggal Document"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>										
								</div>
							</div>
						</div>

						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Pemasangan </label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker" name="tanggal_pemasangan_mulai" 
									id="tanggal_pemasangan_mulai"	placeholder="Masukkan Tanggal Pemasangan Mulai"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

                        <div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select name="jenis_bayar" id="jenis_bayar" required="" class="form-control select2">
									<option value="">--Pilih Jenis Pembayaran--</option>
									<option value="1">Pra Bayar</option>
									<option value="0">Pasca Bayar</option>
								</select>
							</div>
						</div>


					
					<div class="form-group two">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Paket</label>
					<div id="lihat_paket">
						<div class="col-md-9 col-sm-9 col-xs-12">
							<select id="jenis_paket" class="form-control select2" name="jenis_paket">
								<option value="">--Pilih Paket--</option>
							</select>
						</div>
					</div>
				</div>


				

						 <div class="form-group two">
                         <label class="control-label col-md-3 col-sm-3 col-xs-12">Aktifasi</label>
                         <div class="col-md-4 col-sm-9 col-xs-12">
                         <input type="text"  value="0" name="jumlah_hari_aktifasi" id="jumlah_hari_aktifasi" require  onkeyup="getDate();" class="form-control">
                         </div>
                          <div class="col-md-2 col-sm-9 col-xs-12">
                         hari
                         </div>
                       
                         </div>
                         
                        

                         <div class="form-group two ">
                         <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Aktifasi </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                         <div class='input-group date '>
                         <input type="text" class="form-control datetimepicker" name="tanggal_aktifasi" onclick="getDate();" readonly
                         id="tanggal_aktifasi" placeholder="Masukkan Tanggal Aktifasi"> <span class="input-group-addon">
                         <span class="glyphicon glyphicon-calendar"></span>
                         </span>
                         </div>
                         </div>
                         </div>


						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan keterangan"></textarea>
							</div>
						</div>

                      

					</div>
					<div class="col-md-6">
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan Paket</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control" id="keterangan_paket" name="keterangan_paket" readonly="" placeholder="Pilih Jenis Paket"></textarea>
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Bandwidth</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="bandwidth" value="" id="bandwidth" readonly placeholder="Pilih Bandwith" class="form-control ">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Paket</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="harga_paket" value="" id="harga_paket" readonly placeholder="Pilih Jenis Paket" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Pemasangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="harga_pasang" readonly="" id="harga_pasang" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Admin Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="harga_registrasi" readonly="" id="harga_registrasi" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Total</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="" readonly="" id="sub_total" name="sub_t" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon %</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="diskon" id="diskon" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Potongan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="jumlah_diskon" id="jumlah_diskon" readonly="" value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="total" readonly="" id="total" value="" class="form-control currency">
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
  
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();

  //today = mm + '/' + dd + '/' + yyyy;

   today = yyyy + '-' + mm + '-' + dd;

  var startdate = new Date(today);
  var newdate = new Date();
  newdate.setDate(startdate.getDate() +  parseInt(jumhari) );
  var dd = newdate.getDate();
  var mm = newdate.getMonth() + 1;
  var y = newdate.getFullYear();

  

  var someFormattedDate = ("0" + mm).slice(-2) + '-' + ("0" + dd).slice(-2) + '-' + y;
   
  $('#tanggal_aktifasi').val(someFormattedDate);  
}






	$(function () {
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			format: 'MM-DD-YYYY'
		});

	});

	$("#pilih_unit").change(function () {
        $("#label_transaksi").show();

		var pilih_unit = $("#pilih_unit").val();


		if (pilih_unit != 'non_unit')

		{


			url = '<?=site_url(); ?>/transaksi_lain/p_registrasi_tvi/lihat_unit';
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
			





			}


		});
	});

	$("#jenis_paket").change(function () {

		var paket = $("#jenis_paket").val();
		var pecah = paket.split('|');
		var data = pecah[0];
		if (data == "") {
			$("#harga_paket").val();
			$("#keterangan_paket").val();
		} else {
			var harga_paket = pecah[1];
			var keterangan = pecah[2];
			var harga_pasang = pecah[3];
			var harga_registrasi = pecah[4];
			var bandwidth = pecah[5];


			var jenis_pemasangan = $("#jenis_pemasangan").val();


            if (jenis_pemasangan == '0' )

            {

            $("#harga_registrasi").val('0'); 


            }

            else (jenis_pemasangan == '1' )


             {

            $("#harga_registrasi").val(currency(harga_registrasi));

             }


			$("#harga_paket").val(currency(harga_paket));
			$("#harga_pasang").val(currency(harga_pasang));
		    $("#bandwidth").val(currency(bandwidth));


			var total = parseInt(harga_pasang) + parseInt(harga_paket) + parseInt(harga_registrasi);
			var diskon = $("#diskon").val();
			var total_diskon = (diskon / 100) * total;
			var total_akhir = total - total_diskon;

			$("#sub_total").val(currency(total));
			$("#jumlah_diskon").val(currency(total_diskon));
			$("#total").val(currency(total_akhir));
			$("#keterangan_paket").val(currency(keterangan));
		}

	});


	$("#jenis_bayar").change(function () {


				//	alert('tess');

				url = '<?=site_url(); ?>/transaksi_lain//P_registrasi_tvi/jenis_paket';
				var jenis_bayar = $("#jenis_bayar").val();
				//console.log(this.value);
				$.ajax({
					type: "post",
					url: url,
					data: {
						jenis_bayar: jenis_bayar
					},
					dataType: "json",
					success: function (data) {
						console.log(data);

						$("#jenis_paket")[0].innerHTML = "";

						$("#jenis_paket").append("<option value='' >Pilih Jenis Paket</option>");
						$.each(data, function (key, val) {
							$("#jenis_paket").append("<option value='" + val.id + "|" + val.harga_jual + "|" + val.description + "|" + val.biaya_pasang_baru + "|" + val.biaya_registrasi + "|" + val.bandwidth + "'    >" + val.name.toUpperCase() + "</option>");
						});

					}


				});
			});



	

    
</script>
