<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>




<!-- switchery -->
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?=base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<!-- modals -->
<!-- Large modal -->
<div id="modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Detail Log</h4>
			</div>
			<div class="modal-body">
				<table class="table table-striped jambo_table bulk_action">
					<thead>
						<tr>
							<th>Point Detail</th>
							<th>Before</th>
							<th>After</th>
						</tr>
					</thead>
					<tbody id="dataModal">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>

<div style="float:right">
	<h2>
		<button class="btn btn-warning" onclick="location.href='<?=site_url(); ?>/P_biaya_tambahan_tvi';">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_biaya_tambahan_tvi/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/transaksi_lain/P_pembayaran_tvi/save_biaya_tambahan?id=<?=$this->input->get('id'); ?>">


	<div class="col-md-6">




	     <div class="form-group pay">
	     	<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Pembayaran</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text"  id="nomor_pembayaran" name="nomor_pembayaran" value="<?=$kode_pay?>" readonly class="form-control pay">
					<input type="hidden" name="registrasi_id"  id="registrasi_id" value="" >
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
									<option value="1">Cash</option>
									<option value="2">Debit</option>
									<option value="3">Payment Getway</option>
						    	</select>

			                   
				</div>
		</div>

			<div class="form-group unit pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" placeholder="Masukkan nama customer" id="customer_name" name="customer_name" value="<?=$data_select->customer_name; ?>"
					 readonly class="form-control unit">
				</div>
				<input type="hidden" name="jenis_paket_id" id="jenis_paket_id" value="<?=$data_select->jenis_paket_id; ?>">
				<input type="hidden" name="registrasi_id" id="registrasi_id" value="<?=$data_select->registrasi_id; ?>">
				<input type="hidden" name="biaya_tambahan_id" id="registrasi_id" value="<?=$data_select->id; ?>">

			</div>


			<div class="form-group unit pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="nomor_registrasi" id="nomor_registrasi" value="<?=$data_select->nomor_registrasi; ?>"
					 readonly class="form-control unit">
				</div>
			</div>
			<div class="form-group unit pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="nomor_telepon" id="nomor_telepon" value="<?=$data_select->nomor_telepon; ?>" readonly
					 class="form-control unit">
				</div>
			</div>
			<div class="form-group unit pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" readonly="" name="nomor_handphone" id="nomor_handphone" value="<?=$data_select->nomor_handphone; ?>"
					 class="form-control unit">
				</div>
			</div>
			<div class="form-group unit pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="email" required="" placeholder="Email" id="email" readonly="" name="email" value="<?=$data_select->email; ?>"
					 class="form-control unit">
				</div>
			</div>

            <div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea class="form-control pay disabled-form" name="keterangan" id="keterangan" value="<?=$data_select->keterangan; ?>"
					 placeholder="Masukkan keterangan" disabled></textarea>
				</div>
			</div>




		</div>



		<div class="col-md-6">


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
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Tagihan Biaya Tambahan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" id="nomor_tagihan" name="nomor_tagihan" value="<?=$data_select->nomor_tagihan; ?>"
					 readonly class="form-control pay">
				</div>
			</div>
			
			
			<div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Total</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" required="" placeholder="Masukkan Total tagihan biaya tambahan" id="total_tagihan"
					 name="total_tagihan" value="<?=$data_select->total_tagihan; ?>" class="form-control pay currency disabled-form" disabled>
				</div>
			</div>

			
			
			<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Discount</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  name="discount" id="discount"  
								 class="form-control currency">
							</div>
			</div>

			<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"> Total Tagihan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  name="total_akhir" id="total_akhir"   value=0 readonly
								 class="form-control pay currency">
							</div>
			</div>


			<div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Bayar</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" required="" placeholder="Masukkan Total tagihan biaya tambahan" id="total_bayar"
					 name="total_bayar" value=0   readonly class="form-control pay currency disabled-form" >
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
                            foreach ($dataBiayaTambahan  as $key => $v) {
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
	



<!-- jQuery -->

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

		$('#tanggal_tagihan_biaya_tambahan').val('<?=$data_select->tanggal_tagihan ?>');




	});



	$(function () {
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			format: 'MM-DD-YYYY'
		});


		$('#tanggal_tagihan_biaya_tambahan').val('<?=$data_select->tanggal_tagihan ?>');


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




	
		$(function () {
			$('#btn-add-item').click(function () {
				var row = "<tr>" +
					"</tr>";
			});
		});

		$("#btn-add-item").click(function () {
				if ($(".no").html()) {
					idf = parseInt($(".no").last().html()) + 1;
				} else {
					idf = 1;
				}
				var str = "<tr id='srow" + idf + "'>" +
				    "<td hidden><input name='id_item[]' value='0'></td>" +
					"<td class='no'>" + idf + "</td>" +
					"<td class='nolog'></td>" +
					"<td><input type='text' class='form-control' name='item[]' id='item' placeholder='Masukkan Nama Item'></td>" +
					"<td><input type='text' class='form-control' name='quantity[]' id='quantity' placeholder='Masukkan Quantity'></td>" +
					"<td><input type='text' class='form-control currency' name='harga_satuan[]' id='harga_satuan' placeholder='Masukkan Harga Satuan'></td>" +
					"<td><input type='text' class='form-control' name='description[]'id='description' placeholder='Masukkan Description'></td>" +
					"<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf +
			        "\"); return false;'><i class='fa fa-trash'></i> </a></td>" +
			        "</tr>";
		$("#tbody_item").append(str);
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



	  		

		$("#btn-update").click(function () {
			disableForm = 0;
			$(".disabled-form").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			$("#btn-update").attr("type", "submit");
		});
		$("#btn-cancel").click(function () {
			disableForm = 1;
			$(".disabled-form").attr("disabled", "")
			$("#btn-cancel").attr("style", "display:none");
			$("#btn-update").val("Edit")
			$("#btn-update").removeAttr("type");
		});

		$(".btn-modal").click(function () {
			url = '<?=site_url(); ?>/core/get_log_detail';
			console.log($(this).attr('data-transfer'));
			console.log($(this).attr('data-type'));
			$.ajax({
				type: "POST",
				data: {
					id: $(this).attr('data-transfer'),
					type: $(this).attr('data-type')
				},
				url: url,
				dataType: "json",
				success: function (data) {
					console.log(data);
					// var items = []; 
					// $("#changeJP").attr("style","display:none");
					// $("#saveJP").removeAttr('style');
					// $("#jabatan").removeAttr('disabled');
					// $("#jabatan")[0].innerHTML = "";
					// $("#project")[0].innerHTML = "";
					// $("#jabatan").append("<option value='' selected disabled>Pilih Jabatan</option>");
					console.log($(this).attr('data-type'));
					$("#dataModal").html("");
					if (data[data.length - 1] == 2) {
						console.log(data[0]);
						for (i = 0; i < data[0].length; i++) {
							var tmpj = 0;
							for (j = 0; j < data[0].length; j++) {
								if(data[1][j] != null){
									if (data[1][j].name == data[0][i].name) {
										$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + data[1][j].value + "</td><td>" + data[0]
											[i].value + "</td></tr>");
										tmpj ++;
									}
									
								}
							}
							if(tmpj == 0){
								$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td></td><td>" + data[0]
											[i].value + "</td></tr>");
							}
						}	
						
						// 	if(data[1].length > data[0].length){
						// 		$.each(data[1], function (key, val) {
						// 			if (val.name == data[0][i].name) {
						// 				console.log(val.name);
						// 				$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0]
						// 					[i].value + "</td></tr>");
						// 			}
						// 		});
						// 	}else{
						// 		$.each(data[0], function (key, val) {
						// 			if (val.name == data[1][i].name) {
						// 				console.log(val.name);
						// 				$("#dataModal").append("<tr><th>" + data[1][i].name + "</th><td>" + val.value + "</td><td>" + data[1]
						// 					[i].value + "</td></tr>");
						// 			}
						// 		});
						// 	}
						// }
					} else {
						$.each(data, function (key, val) {
							if (data[data.length - 1] == 1) {
								console.log(data);
								if (val.name)
									$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td></td><td>" + val.value +
										"</td></tr>");
							} else if (data[data.length - 1] == 2) {

							} else if (data[data.length - 1] == 3) {
								console.log(data);
								if (val.name)
									$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td>" + val.value +
										"</td><td></td></tr>");
							}
						});
					}

				}
			});

		});
		$('.select2').select2({
			width: 'resolve'
		});

	$(document).keydown(function (e) {  
		return (e.which || e.keyCode) != 116;  
	});

	$(document).keydown(function (e) {  
		if(e.ctrlKey){
			return (e.which || e.keyCode) != 82;
		}
	});
	







	</script>
