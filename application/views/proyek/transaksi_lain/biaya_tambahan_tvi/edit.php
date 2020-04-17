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
		<button class="btn btn-warning" onclick="location.href='<?=site_url(); ?>/transaksi_lain/P_biaya_tambahan_tvi';">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/P_biaya_tambahan_tvi/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/transaksi_lain/P_biaya_tambahan_tvi/edit?id=<?=$this->input->get('id'); ?>">


		<div class="col-md-6">

			<div class="form-group unit pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" placeholder="Masukkan nama customer" id="customer_name" name="customer_name" value="<?=$data_select->customer_name; ?>"
					 readonly class="form-control unit">
				</div>
				<input type="hidden" name="jenis_paket_id" id="jenis_paket_id" value="<?=$data_select->jenis_paket_id; ?>">
				<input type="hidden" name="registrasi_id" id="registrasi_id" value="<?=$data_select->registrasi_id; ?>">

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
		</div>



		<div class="col-md-6">


			
			<div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Layanan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="paket_layanan" id="paket_layanan" value="<?=$data_select->paket_name; ?>" readonly class="form-control payt">
				</div>
			</div>

			<div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Layanan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" id="jenis_layanan" name="jenis_layanan" value="<?=$data_select->jenis_layanan; ?>" readonly
					 class="form-control pay">
				</div>
			</div>


			<div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" readonly="" name="total" id="total" value="<?=$data_select->total; ?>" readonly class="form-control pay currency">
				</div>
			</div>


			<div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Tagihan Biaya Tambahan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" id="nomor_tagihan_biaya_tambahan" name="nomor_tagihan_biaya_tambahan" value="<?=$data_select->nomor_tagihan; ?>"
					 readonly class="form-control pay">
				</div>
			</div>


			<div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Tagihan Biaya Tambahan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<div class='input-group date '>
						<input type="text" class="form-control datetimepicker pay disabled-form" name="tanggal_tagihan_biaya_tambahan" id="tanggal_tagihan_biaya_tambahan"
						 value="<?=$data_select->tanggal_tagihan; ?>" placeholder="Masukkan Tanggal Tagihan Tambahan" disabled> <span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>

			
			<div class="form-group pay">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan Biaya Tambahan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" required="" placeholder="Masukkan Total tagihan biaya tambahan" id="total_tagihan_biaya_tambahan"
					 name="total_tagihan_biaya_tambahan" value="<?=$data_select->total_tagihan; ?>" class="form-control pay currency disabled-form" disabled>
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
							<th>No Log</th>
							<th>Item</th>
							<th>Quantity</th>
							<th>Harga Satuan</th>
							<th>Description</th>
							<th>Hapus</th>
						</tr>
					</thead>
					<tbody id="tbody_item">
						<?php
							$i = 0;
							$j = 0;

                            // echo '<PRE>';
                           	// echo print_r($dataBiayaTambahanDetail);
                           	// echo '</PRE>';

                            foreach ($dataBiayaTambahanDetail  as $key => $v) {


								++$j;
								if($v['delete_item'] == 0){
									++$i;
									echo "<tr id='srow".$i."'>";
									echo "<td hidden><input name='id_item[]' value='$v[id_item]'> </td>";
									echo "<td class='no' >".$i.'</td>';
									echo "<td class='nolog' >".$j.'</td>';
									echo "<td><input type='text' class='form-control disabled-form' name='item[]' id='item' value ='$v[item]' placeholder='Masukkan Nama Item' disabled></td>";
									echo "<td><input type='text' class='form-control disabled-form' name='quantity[]' id='quantity' value ='$v[quantity]' placeholder='Masukkan Quantity' disabled></td>";
					                echo "<td><input type='text' class='form-control currency disabled-form' name='harga_satuan[]' id='harga_satuan' value ='$v[harga_satuan]' placeholder='Masukkan Harga Satuan' disabled></td>";
					                echo "<td><input type='text' class='form-control disabled-form' name='description[]'id='description' value ='$v[keterangan]' placeholder='Masukkan Description' disabled></td>";
									echo "<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow".$i."\"); return false;'><i class='fa fa-trash'></i> </a></td>";
									echo '</tr>';
								}
                            }
                        ?>
						<input id="idf" value="1" type="hidden" />

					</tbody>
				</table>

				<button id='btn-add-item' type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add
					Item</button>
			</p>
		</div>




		<div class="col-md-12">
			<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
			<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
		</div>
	</form>
</div>

<div class="x_panel">
	<div class="x_title">
		<h2>Log</h2>
		<div class="clearfix"></div>
	</div>
	<div class="x_content">
		<br>
		<table class="table table-striped jambo_table bulk_action tableDT">
			<thead>
				<tr>
					<th>No</th>
					<th>Waktu</th>
					<th>User</th>
					<th>Status</th>
					<th>Detail</th>
				</tr>
			</thead>
			<tbody>
				<?php
            $i = 0;
            foreach ($data as $key => $v) {
                ++$i;
                echo '<tr>';
                echo "<td>$i</td>";
                echo "<td>$v[date]</td>";
                echo "<td>$v[name]</td>";
                echo '<td>';
                if ($v['status'] == 1) {
                    echo 'Tambah';
                } elseif ($v['status'] == 2) {
                    echo 'Edit';
                } else {
                    echo 'Hapus';
                }
                echo '</td>';
                echo "
                    <td class='col-md-1'>
                        <a class='btn-modal btn btn-sm btn-primary col-md-12' data-toggle='modal' data-target='#modal' data-transfer='$v[id]' data-type='$v[status]'>
                            <i class='fa fa-pencil'></i>
                        </a>
                    </td>
                ";
                echo '</td></tr>';
            }
        ?>
			</tbody>
		</table>
	</div>
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
