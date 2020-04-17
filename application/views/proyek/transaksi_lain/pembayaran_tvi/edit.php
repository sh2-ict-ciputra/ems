<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>

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
		<button class="btn btn-warning" onclick="location.href='<?=site_url(); ?>/P_pembayaran_tvi';">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_pembayaran_tvi/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/transaksi_lain/P_pembayaran_tvi/edit?id=<?=$this->input->get('id'); ?>">



		<div id="view_data">
			<div class="row" style="margin-top: 35px;">
				<div class="col-md-12">
					<div class="col-md-6">

						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="<?=$data_select->nomor_pembayaran; ?>" id="nomor_pembayaran" name="nomor_pembayaran" readonly class="form-control ">
								<input type="hidden" name="registrasi_id"  id="registrasi_id"  value="<?=$data_select->registrasi_id; ?>">
								<!-- <input type="hidden" name="id_tagihan"  id="id_tagihan"  value="<?=$data_select->id_tagihan; ?>"> -->
							</div>
						</div>
						<div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker disabled-form" name="tanggal_pembayaran" id="tanggal_pembayaran" value="<?=$data_select->tanggal_pembayaran; ?>"
									 placeholder="Masukkan Tanggal Pengajuan"  disabled> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Cara Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<select required="" id="cara_pembayaran" name="cara_pembayaran" class="form-control select2 disabled-form" disabled>
				                  <option value="">--Pilih Cara Pembayaran--</option>
								  <option value="cash"  <?=$data_select->cara_pembayaran=='cash'?'selected':''?> >Cash</option>
			                    </select>
							</div>
						</div>

						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Layanan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="<?=$data_select->jenis_layanan; ?>" id="jenis_layanan" name="jenis_layanan" readonly class="form-control unit">
							</div>
						</div>

						<div class="form-group two">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<textarea class="form-control disabled-form" value="<?=$data_select->keterangan; ?>" name="keterangan" id="keterangan" placeholder="Masukkan keterangan" disabled></textarea>
							</div>
						</div>
                        




					</div>
						
					<div class="col-md-6">

						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Layanan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="paket_layanan" id="paket_layanan" value="<?=$data_select->paket_layanan; ?>" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Ref Pembayaran</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_ref_pembayaran" id="nomor_ref_pembayaran" value="<?=$data_select->nomor_ref_pembayaran; ?>"  class="form-control unit  disabled-form" disabled>
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Fisik Kwitasni</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_fisik_kwitansi" id="nomor_fisik_kwitansi" value="<?=$data_select->no_fisik_kwitansi; ?>"  class="form-control unit disabled-form" disabled>
							</div>
						</div>

						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No Tagihan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="nomor_tagihan" id="nomor_tagihan" value="<?=$data_select->nomor_tagihan; ?>"  class="form-control pay">
							</div>
						</div>
						<div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Total</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" readonly="" name="sub_total" id="sub_total" value="<?=$data_select->total_tagihan; ?>"  
								 class="form-control currency">
							</div>
						</div>
                        <div class="form-group pay">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Discount</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  name="discount" id="discount"  value="<?=$data_select->diskon; ?>" 
								 class="form-control currency" disabled>
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text"  name="total_tagihan" id="total_tagihan" value="<?=$data_select->total_akhir; ?>"  
								 class="form-control currency" disabled>
							</div>
						</div>      

						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Total bayar</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" required="" placeholder="Total Bayar" id="total_bayar"  name="total_bayar" value="<?=$data_select->total_bayar; ?>" class="form-control currency" disabled>
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


<!-- jQuery -->
<script type="text/javascript">
	function currency(inp) {
		var input = inp.val().toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		console.log("test");
		console.log((input === 0) ? "" : input.toLocaleString("en-US"));
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}
	$(function () {
		$(".currency").val(currency($(".currency")));
		$("#btn-update").click(function () {
			$("#tanggal_pembayaran").removeAttr("disabled");
			$("#cara_pembayaran").removeAttr("disabled");
			$("#keterangan").removeAttr("disabled");
			$("#nomor_ref_pembayaran").removeAttr("disabled");
			$("#nomor_fisik_kwitansi").removeAttr("disabled");
			$("#sub_total").removeAttr("disabled");
			$("#discount").removeAttr("disabled");
			$("#total_tagihan").removeAttr("disabled");
			$("#total_bayar").removeAttr("disabled");
			$("#status").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			$("#btn-update").attr("type", "submit");
		});
		$("#btn-cancel").click(function () {
			$("#tanggal_pembayaran").attr("disabled", "")
			$("#cara_pembayaran").attr("disabled", "")
			$("#keterangan").attr("disabled", "")
			$("#nomor_ref_pembayaran").attr("disabled", "")
			$("#nomor_fisik_kwitansi").attr("disabled", "")
			$("#total_bayar").attr("disabled", "")
			$("#status").attr("disabled", "")
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
					$("#dataModal").html("");
					if (data[data.length - 1] == 2) {
						console.log(data[0]);
						for (i = 0; i < data[0].length; i++) {
							$.each(data[1], function (key, val) {
								if (val.name == data[0][i].name) {
									console.log(val.name);
									$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0]
										[i].value + "</td></tr>");
								}
							});
						}
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
	});

</script>
