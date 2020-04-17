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
		<button class="btn btn-warning" onclick="location.href='<?=site_url(); ?>/P_registrasi_tvi';">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/P_registrasi_tvi/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/transaksi_lain/P_registrasi_tvi/edit?id=<?=$this->input->get('id'); ?>">



		<div id="view_data">
			<div class="row" style="margin-top: 35px;">
				<div class="col-md-12">
					<div class="col-md-6">




						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="project_name" name="project_name" value="<?=$data_select->project; ?>" readonly class="form-control">
								<!-- <input type="hidden" name="id_tagihan"  id="id_tagihan" value="<?=$data_select->id_tagihan; ?>" > -->
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="kawasan_name" name="kawasan_name" value="<?=$data_select->kawasan; ?>" readonly class="form-control">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="blok_name" name="blok_name" readonly value="<?=$data_select->blok; ?>" class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  id="unit_name" name="unit_name" value="<?=$data_select->unit; ?>" readonly class="form-control unit">
							</div>
						</div>

						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" placeholder="Masukkan nama customer" id="customer_name" name="customer_name"
								 value="<?=$data_select->customer; ?>" readonly class="form-control unit">
							</div>
						</div>


						 



						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pemasangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select name="jenis_pemasangan" id="jenis_pemasangan" required="" class="form-control select2 disabled-form" disabled>
                                   <option value="" selected disabled>--Pilih Jenis Pemasangan--</option>
                                   <option value="pemasangan_baru" <?=$data_select->jenis_pemasangan=='1'?'selected':''?> >Pemasangan Baru</option>
                                   <option value="pindah_paket" <?=$data_select->jenis_pemasangan=='0'?'selected':''?> >Perpanjangan Paket</option>
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
								<input type="text" name="nomor_registrasi" id="nomor_registrasi" value="<?=$data_select->nomor_registrasi; ?>" value="" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_telepon" id="nomor_telepon" value="<?=$data_select->telepon; ?>" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="nomor_handphone" id="nomor_handphone" value="<?=$data_select->no_hp; ?>" class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="email" required="" placeholder="Email" id="email" readonly="" name="email" value="<?=$data_select->email; ?>" class="form-control unit">
							</div>
						</div>


					</div>
				</div>
			</div>

			<!--
      <div class="clear-fix"></div>
      <br>
      <h4 align="left">Tunggakan</h4>
      <hr>
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Tunggakan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" name="jumlah_tunggakan" readonly value="" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Layanan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" name="paket_layanan" value="" readonly class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Periode Tunggakan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" name="periode_tunggakan" value="" readonly class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>     
        <div class="clear-fix"></div>
        <br>
        -->
			<h4 id="label_transaksi" hidden>Transaksi</h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-6">
						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Document </label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker disabled-form" name="tanggal_document"  value="<?=$data_select->tanggal_document; ?>"
									id="tanggal_document"	placeholder="Masukkan Tanggal Document" disabled> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Pemasangan Mulai </label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker disabled-form" name="tanggal_pemasangan_mulai" value="<?=$data_select->tanggal_pemasangan_mulai; ?>"
									id="tanggal_pemasangan_mulai"	placeholder="Masukkan Tanggal Pemasangan Mulai" disabled> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Paket</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select id="pilih_paket" required="" name="pilih_paket" class="form-control select2 disabled-form" disabled>
									<option value="" selected="" disabled="">--Pilih Paket--</option>
							<?php
                        foreach ($dataPaket as  $v) {
                            if ($data_select->jenis_paket_id == $v['id']) {
                                  echo("<option value='$v[id] | $v[harga_jual] | $v[description] | $v[biaya_pasang_baru] | $v[biaya_registrasi] | $v[bandwidth] ' selected>$v[name]</option>");
                            } else {
                                  echo("<option value='$v[id] | $v[harga_jual] | $v[description] | $v[biaya_pasang_baru] | $v[biaya_registrasi] | $v[bandwidth] '>$v[name]</option>");
                            }
                        }
                    ?>


								</select>
							</div>
						</div>

						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control disabled-form" name="keterangan" id="keterangan"   value="<?=$data_select->keterangan; ?>" placeholder="Masukkan keterangan" disabled></textarea>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan Paket</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control" id="keterangan_paket" name="keterangan_paket" value="<?=$data_select->keterangan_paket; ?>"  readonly="" placeholder="Pilih Jenis Paket"></textarea>
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Bandwidth</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="bandwidth"  id="bandwidth" readonly  value="<?=$data_select->bandwidth; ?>" placeholder="Pilih Bandwith" class="form-control ">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Paket</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="harga_paket"  id="harga_paket" value="<?=$data_select->harga_paket; ?>" readonly placeholder="Pilih Jenis Paket" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Pasang</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="harga_pasang" readonly="" id="harga_pasang" value="<?=$data_select->harga_pasang; ?>" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="harga_registrasi" readonly="" id="harga_registrasi"  value="<?=$data_select->biaya_registrasi; ?>" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Total</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="sub_total" readonly="" id="sub_total"  value="0" class="form-control currency">
							</div>
						</div>
						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon %</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="diskon" id="diskon" value="<?=$data_select->diskon; ?>" class="form-control currency disabled-form" disabled>
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
								<input type="text" name="total" readonly="" id="total" value="<?=$data_select->total; ?>" class="form-control currency">
							</div>
						</div>
					</div>
				</div>
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


<script type="text/javascript">
	$.each($(".currency"), function (index, currency) {
		currency.value = parseInt(currency.value.toString().replace(/[\D\s\._\-]+/g, ""), 10).toLocaleString("en-US");
	});

	  function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
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
							if (data[1][j] != null) {
								if (data[1][j].name == data[0][i].name) {
									$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + data[1][j].value + "</td><td>" + data[
											0]
										[i].value + "</td></tr>");
									tmpj++;
								}

							}
						}
						if (tmpj == 0) {
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
		if (e.ctrlKey) {
			return (e.which || e.keyCode) != 82;
		}
	});

</script>


<script type="text/javascript">

 
	



	$(function () {
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			format: 'MM-DD-YYYY'
		});

	});

	$("#pilih_unit").change(function () {
       
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

	$("#pilih_paket").change(function () {

		var paket = $("#pilih_paket").val();
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


			$("#harga_paket").val(currency(harga_paket));
			$("#harga_pasang").val(currency(harga_pasang));
			$("#harga_registrasi").val(currency(harga_registrasi));
			$("#bandwidth").val(currency(bandwidth));


			var total = parseInt(harga_pasang) + parseInt(harga_paket) + parseInt(harga_registrasi) ;
			var diskon = $("#diskon").val();
			var total_diskon = (diskon / 100) * total;
			var total_akhir = total - total_diskon;

			$("#sub_total").val(currency(total));
			$("#jumlah_diskon").val(currency(total_diskon));
			$("#total").val(currency(total_akhir));
			$("#keterangan_paket").val(currency(keterangan));
		}

	});

    
</script>
