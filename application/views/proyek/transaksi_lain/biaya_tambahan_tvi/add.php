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


		<div class="col-md-12">
			<select required="" id="pilih_reg" name="pilih_reg" class="form-control select2">
				<option value="" selected="" disabled="">--Pilih No Registrasi--</option>
				<?php
                        foreach ($dataRegistrasi as $v) {
                            echo("<option value='$v[id]'>$v[customer_name] => $v[nomor_registrasi]</option>");
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

						
						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="customer_name" name="customer_name" readonly class="form-control unit">
							</div>
							  <input type="hidden" name="jenis_paket_id"  id="jenis_paket_id"  >
							  <input type="hidden" name="registrasi_id"  id="registrasi_id"  >
							  <input type="hidden" name="id_tagihan"  id="id_tagihan"  >
						</div>


						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_registrasi" id="nomor_registrasi"    readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_telepon" id="nomor_telepon"   readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="nomor_handphone" id="nomor_handphone"   class="form-control unit">
							</div>
						</div>
						<div class="form-group unit pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="email" required="" placeholder="Email" id="email" readonly="" name="email"  class="form-control unit">
							</div>
						</div>

					

					</div>
						
					<div class="col-md-6">


                      

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Layanan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="paket_layanan" id="paket_layanan"  readonly class="form-control payt">
							</div>
						</div>

							<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Layanan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="jenis_layanan" name="jenis_layanan"   readonly class="form-control pay">
							</div>
						</div>


					    <div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="total" id="total"   readonly 
								 class="form-control pay currency">
							</div>
						</div>


						  <div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Tagihan Biaya Tambahan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="nomor_tagihan_biaya" name="nomor_tagihan_biaya" value="<?=$kode_tagihan?>" readonly class="form-control pay">
							</div>
						</div>

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Tagihan Biaya Tambahan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker pay" name="tanggal_tagihan_biaya" id="tanggal_tagihan_biaya"
									 placeholder="Masukkan Tanggal Tagihan Tambahan"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>  

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan Biaya Tambahan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" required="" placeholder="Masukkan Total tagihan biaya tambahan" id="total_tagihan_biaya"  name="total_tagihan_biaya" value=0 readonly class="form-control pay currency" >
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
            <h2>List Biaya Tambahan</h2>
            <div class="clearfix"></div>
        </div>
		<div class="col-md-12 pay">
			<p>
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>No</th>
							<th>Item</th>
							<th>Volume</th>
							<th>Satuan</th>
							<th>Harga Satuan</th>
							<th>Total</th>
							<th>Description</th>
							<th>Hapus</th>
						</tr>
					</thead>
					<tbody id="tbody_biaya">
						<input id="idf" value="1" type="hidden" />

					</tbody>
				</table>

				<button id='btn-add-biaya' type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add
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

  
	
   $(".pay").hide();


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

		$("#btn-add-biaya").click(function () {
				if ($(".no").html()) {
					idf = parseInt($(".no").last().html()) + 1;
				} else {
					idf = 1;
				}
				var str = "<tr id='srow" + idf + "'>" +
					"<td class='no'>" + idf + "</td>" +
					"<td><input type='text' class='form-control' name='item[]' id='item' placeholder='Masukkan Nama Item'/ required></td>" +
					"<td><input type='text' class='form-control' name='volume[]' id='volume" + idf + "'  onkeyup='getTotal("+idf+");' placeholder='Masukkan Volume'/ required></td>" +
					"<td><input type='text' class='form-control' name='satuan[]' id='satuan' placeholder='Masukkan Satuan'/ required></td>" +
					"<td><input type='text' class='form-control currency' name='harga_satuan[]'  onkeyup='getTotal("+idf+");'  id='harga_satuan" +idf + "' placeholder='Masukkan Harga Satuan'/ required></td>" +
					"<td><input type='text' class='data_harga form-control' name='total[]' id='total" + idf + "' readonly placeholder='Masukkan Total'/ required></td>" +
					"<td><input type='text' class='form-control' name='description[]' id='description' placeholder='Masukkan Description'/ required></td>" +
					"<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf +
			        "\"); return false;'><i class='fa fa-trash'></i> </a></td>" +
			        "</tr>";
		$("#tbody_biaya").append(str);
		$('.select2').select2({
		width: 'resolve'
		});
		});






		function hapusElemen(idf) {
			$(idf).remove();
			var idf = document.getElementById("idf").value;
			idf = idf - 1;
			document.getElementById("idf").value = idf;

			 data = 0;
                  $.each($(".data_harga"), function(k,v){  



                   data = data +  parseInt(v.value);

                  console.log(data);




            });
                  $("#total_tagihan_biaya").val(data);	

		}



		  function getTotal(idf) 
         {


			  var volume = $("#volume" + idf).val();
			  var harga_satuan = $("#harga_satuan" + idf ).val();
			  var total = volume * harga_satuan; 
              $("#total" + idf ).val(total);  
              

               data = 0;
                  $.each($(".data_harga"), function(k,v){  



                   data = data +  parseInt(v.value);

                  console.log(data);




            });
                  $("#total_tagihan_biaya").val(data);	


         }



         	$(function () {
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			format: 'MM-DD-YYYY'
		});

        $("#tbody_biaya").on("change",'.total_tagihan',function(){

        	data = $(this).parent().parent().children('.data_harga').html();
            if($(this).is(':checked')){

            	
                if($("#total_tagihan_biaya").val() == ""){

                  $("#total_tagihan_biaya").val(0);
                }else{
                  data = parseInt(data) + parseInt($("#total_tagihan_biaya").val());
                }
            }else{

            	
                if($("#total_tagihan_biaya").val() == ""){
                  $("#total_tagihan_biaya").val(0);
                }else{
                  data = parseInt($("#total_tagihan_biaya").val()) - parseInt(data);
                }
            }
            $("#total_tagihan_biaya").val(data);  
        });
	
    });






		$("#pilih_reg").change(function () {
      

		   var pilih_reg = $("#pilih_reg").val();


	

			url = '<?=site_url(); ?>/transaksi_lain/p_biaya_tambahan_tvi/lihat_reg';
			
			//console.log(this.value);
			$.ajax({
				type: "get",
				url: url,
				data: {
					pilih_reg: pilih_reg
				},
				dataType: "json",
				success: function (data) {
					console.log(data);



					$(".pay").show();
				

					$("#id_tagihan").val(data.id_tagihan);
                    $("#customer_name").val(data.customer_name);
                    $("#nomor_registrasi").val(data.nomor_registrasi);
                    $("#nomor_telepon").val(data.nomor_telepon);
                    $("#nomor_handphone").val(data.nomor_handphone);
                    $("#email").val(data.email);
                    $("#jenis_paket_id").val(data.jenis_paket_id);
					$("#registrasi_id").val(data.registrasi_id);


					//alert(data.jenis_layanan);

                    if (data.jenis_layanan == '1')
                    {
					$("#jenis_layanan").val("Pemasangan Baru");
				    }

				    else  if (data.jenis_layanan == '0')
                    {
					$("#jenis_layanan").val("Perpanjangan Paket");}





					$("#total").val(data.total);
					
					$("#paket_layanan").val(data.paket_name);
				//	$("#total_tagihan").val(data.total_tagihan);
					


				}


			})





	});




	







	</script>
