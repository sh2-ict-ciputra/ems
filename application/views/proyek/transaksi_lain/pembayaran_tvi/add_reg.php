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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/p_pembayaran_tvi/save">

		
        <div class="clear-fix"></div>
        <br>

		<div id="view_data">
			<div class="row" style="margin-top: 35px;">
				<div class="col-md-12">
					<div class="col-md-6">

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="nomor_pembayaran" name="nomor_pembayaran" value="<?=$kode_pay?>" readonly class="form-control pay">
								<input type="hidden" name="jenis_paket_id" id="jenis_paket_id" value="<?=$dataRegistrasi->jenis_paket_id; ?>">
								<input type="hidden" name="registrasi_id"  id="registrasi_id"  value="<?=$dataRegistrasi->registrasi_id; ?>">
								<!-- <input type="hidden" name="id_tagihan"  id="id_tagihan" value="<?=$dataRegistrasi->id_tagihan; ?>"> -->
							</div>
						</div>
						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker pay" name="tanggal_pembayaran" id="tanggal_pembayaran"
									 placeholder="Masukkan Tanggal Pembayaran"> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Cara Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
							   <select name="cara_pembayaran" id="cara_pembayaran" required="" class="form-control select2">
									<option value="">--Pilih Cara Pembayaran--</option>
									<option value="cash">Cash</option>
						    	</select>

			                   
							</div>
						</div>

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Layanan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="jenis_layanan" name="jenis_layanan" value="<?=$dataRegistrasi->jenis_layanan; ?>"  readonly class="form-control pay">
							</div>
						</div>

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control pay" name="keterangan" id="keterangan" placeholder="Masukkan keterangan"></textarea>
							</div>
						</div>

					</div>
						
					<div class="col-md-6">

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Layanan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="paket_layanan" id="paket_layanan"  value="<?=$dataRegistrasi->paket_name; ?>"  readonly class="form-control payt">
							</div>
						</div>
						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Ref Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_ref_pembayaran" id="nomor_ref_pembayaran" value=""  class="form-control pay">
							</div>
						</div>
						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Fisik Kwitasni</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_fisik_kwitansi" id="nomor_fisik_kwitansi" value=""  class="form-control pay">
							</div>
						</div>

                        <div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Tagihan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="nomor_tagihan" id="nomor_tagihan"  value="<?=$dataRegistrasi->nomor_tagihan; ?>"  class="form-control pay">
							</div>
						</div>

                        <div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Total</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="sub_total" id="sub_total"   readonly  value=0
								 class="form-control  currency">
								 <input type="hidden" name="sub_total2"  id="sub_total2" value=0 >
							</div>
						</div>


						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Discount</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  name="diskon" id="diskon"  
								 class="form-control currency">
							</div>
						</div>

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"> Total Tagihan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  name="total_tagihan" id="total_tagihan"    value=0
								 class="form-control pay currency" readonly>
							</div>
						</div>

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total bayar</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" required="" placeholder="Total Bayar" id="total_bayar"  name="total_bayar"   value=0  class="form-control pay currency" readonly>
							</div>
						</div>

										
					</div>
				</div>
			</div>


	  <div class="clearfix"></div>
      <br>
      <br>
      <div class="col-sm-12 " id="list_tagihan">
        <div class="card-box table-responsive">
          <table id="example" class="table table-responsive table-stripped table-hover table-bordered">
            <thead>
			  <th>No</th>
			  <th>Pilih Bayar</th>
              <th>Jenis Tagihan</th>
              <th>Tanggal Tagihan</th>
              <th>Jenis Paket</th>
              <th>Total Tagihan</th>
             </thead>
			<tbody id="isi">
			<?php
							$i = 0;
							$j = 0;
                            foreach ($dataTagihan  as $key => $v) {
								++$j;
								if($v['delete_tagihan'] == 0){
									++$i;
									echo "<tr id='srow".$i."'>";
									echo "<td hidden><input name='id_tagihan[]' value='$v[id_tagihan]'> </td>";
									echo "<td class='no' >".$i.'</td>';
								    echo "<td><input type='checkbox' class='total_tagihan flat table-check check' name='pilihan'  value='$v[id_tagihan]' id='pilihan'></td>";
									echo "<td>$v[flag_type]</td>";
									echo "<td>$v[tanggal_tagihan]</td>";
									echo "<td>$v[jenis_paket]</td>";
									echo "<td  class='data_harga' >$v[total_tagihan]</td>";								
									echo '</tr>';
								}
                            }
                        ?>

						<input id="idf" value="1" type="hidden" />
					</tbody>
          </table>
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
			format: 'MM-DD-YYYY'
		});


		$("#isi").on("change",'.total_tagihan',function(){


			alert(data);


            data = $(this).parent().parent().children('.data_harga').html();
            if($(this).is(':checked')){
                if($("#sub_total").val() == ""){
                  $("#sub_total").val(0);
                }else{
                  data = parseInt(data) + parseInt($("#sub_total2").val());

			
				  $("#sub_total").val(currency(data));  

				  $("#sub_total2").val(data);  

                  var sub_total = $("#sub_total2").val();

				  var diskon = $("#diskon").val();

                  var total_diskon = (diskon / 100) * sub_total;

			      var total_akhir = sub_total - total_diskon;

		          $("#total_tagihan").val(currency(total_akhir));


				  $("#total_bayar").val(currency(total_akhir));
					




                }
            }else{
                if($("#sub_total").val() == ""){
                  $("#sub_total").val(0);
                }else{
                  data = parseInt($("#sub_total2").val())- parseInt(data);

				 

				  $("#sub_total").val(currency(data));  

				  $("#sub_total2").val(data);  


				  var sub_total = $("#sub_total2").val();

				  var diskon = $("#diskon").val();
				  
                  var total_diskon = (diskon / 100) * sub_total;

			      var total_akhir = sub_total - total_diskon;

		          $("#total_tagihan").val(currency(total_akhir));


				  $("#total_bayar").val(currency(total_akhir));
					





                }
            }
          
        });
	


	});

	$("#pilih_reg").change(function () {
      

		   var pilih_reg = $("#pilih_reg").val();


	

			url = '<?=site_url(); ?>/transaksi_lain/p_pembayaran_tvi/lihat_reg';
			
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
				




					$("#registrasi_id").val(data.registrasi_id);
					$("#jenis_layanan").val(data.jenis_layanan);
					$("#nomor_tagihan").val(data.nomor_tagihan);
					
					$("#paket_layanan").val(data.paket_name);
					

					$("#sub_total").val(data.total);

					var diskon = $("#diskon").val();

                    var sub_total = $("#sub_total").val();

                    var total_diskon = (diskon / 100) * sub_total;

			        var total_akhir = sub_total - total_diskon;

		           	$("#total_tagihan").val(currency(total_akhir));


					$("#total_bayar").val(currency(total_akhir));
					
					


				}


			})





	});

	
	
    $("#diskon").keyup(function(){
        if($("#diskon").val()<=100){
            $("#jumlah_diskon").val(currency(parseInt($("#diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))*parseInt($("#sub_total").val().toString().replace(/[\D\s\._\-]+/g, ""))/100));
            console.log(
                parseInt($("#total").val().toString().replace(/[\D\s\._\-]+/g, ""))
            );
            console.log(
                parseInt($("#jumlah_diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))
            );
            $("#total").val(
                currency(
                parseInt($("#sub_total").val().toString().replace(/[\D\s\._\-]+/g, ""))-parseInt($("#jumlah_diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))
                )
            );
        }else{
            $("#diskon").val(100);
        }
    });
</script>
