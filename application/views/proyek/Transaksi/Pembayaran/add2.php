<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!-- select2 -->
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">
<!-- JQUERY MASK -->

<style>
	.invalid {
		background-color: lightpink;
	}

	.has-error {
		border: 1px solid rgb(185, 74, 72) !important;
	}

	.text-right {
		text-align: right;
	}
</style>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?= site_url() ?>/Transaksi/P_unit/index/<?= $unit_id ?>'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="location.reload()">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">

	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/Transaksi/P_transaksi_generate_bill/save" autocomplete="off">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Kawasan - Blok - Unit - Pemilik</label>
				<div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
					<select name="unit" required="" id="unit" class="form-control select2" placeholder="-- Pilih Kawasan - Blok - Unit - Pemilik --">
						<?php if ($unit->id != 0) : ?>
							<option selected value="<?= $unit->id ?>"><?= $unit->text ?></option>
						<?php endif; ?>
					</select>
				</div>
			</div>
		</div>



		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Total Tagihan</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="total_tagihan" type="text" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Cara Pembayaran</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<select name="cara_pembayaran" required="" id="cara_pembayaran" class="form-control select2" placeholder="-- Pilih Cara Pembayaran (Code - Name) --">
						<option value="" disabled selected>-- Pilih Cara Pembayaran (Code - Name) --</option>
						<?php
						foreach ($cara_pembayaran as $v) {
							echo ("<option value='$v->id' kode='$v->code' jenis_pembayaran='$v->jenis_cara_pembayaran_id' nama='$v->name' biaya_admin='$v->biaya_admin'>$v->code - $v->name</option>");
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Di Kirim Ke Bank</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<select name="bank" required="" id="bank" class="form-control select2" placeholder="-- Pilih Bank --" disabled>
						<option value="" disabled selected>-- Pilih Bank --</option>
						<?php
						foreach ($bank as $v) {
							echo ("<option value='$v->id' jenis_cara_pembayaran_id='$v->jenis_cara_pembayaran_id' nama='$v->name' biaya_admin='$v->biaya_admin'>$v->name</option>");
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="label_biaya_admin">Biaya Admin</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="view_biaya_admin_pembayaran" type="text" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="label_di_bayar_dengan">Di Bayar dengan<br>-</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="di_bayar_dengan" type="text" class="form-control" readonly>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Tanggal <br>Input</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input type="date" value="<?= date("Y-m-d") ?>" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Tanggal Pembayaran</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="tgl_pembayaran" type="text" value="<?= date("d/m/Y") ?>" class="form-control tgl_pembayaran">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Saldo Deposit (Rp)</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="saldo_deposit" type="text" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Deposit yang Digunakan</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="deposit_digunakan" type="text" class="form-control" value=0 readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Saldo Akhir Deposit</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<input id="saldo_akhir" type="text" class="form-control" readonly>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<br>
		<br>
		<div class="table-responsive">

		</div>
		<div class="col-md-12" id="dataisi">
			<div class="card-box table-responsive">
			</div>
			<div id="div_table" class="col-md-12 card-box table-responsive">
				<table id="table_unit" class="table tableDT2 table-striped table-bordered bulk_action" style="width:100%">
					<thead>
						<tr>
							<th class="col-md-1 col-sm-1 col-lg-1 col-xs-1" id="di_bayar_dengan_table">
								<input id="check-all-bayar" type='checkbox' class='flat table-check'> Bayar
							</th>
							<th style="width:1px">Deposit</th>
							<th class='text-right'>Periode Tagihan</th>
							<th>Service</th>
							<th class='text-right'>Nilai Pokok</th>
							<th class='text-right'>Denda</th>
							<th class='text-right'>Pemutihan Nilai Pokok</th>
							<th class='text-right'>Pemutihan Denda</th>
							<th class='text-right'>Nilai Pokok<br>Yang Harus di Bayar</th>
							<th class='text-right'>Denda<br>Yang Harus di Bayar</th>
							<th class='text-right'>Pinalty</th>
							<th class='text-right'>Tunggakan</th>
							<th class='text-right'>Total</th>
							<th class='text-right col-lg-1 col-md-1'>Dibayar</th>
						</tr>
					</thead>
					<tbody id="tbody_unit">

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="form-group" style="margin-top:20px">
				<div class="center-margin">
					<button class="btn btn-primary" type="reset">Reset</button>
					<a data-toggle="modal" id="button-submit" class="btn btn-success">Submit</a>
				</div>
			</div>
		</div>
	</form>
	<div class="x_content">
		<div class="modal fade modal-move" id="modal-save" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" style="width:35%;margin:auto">
				<div class="modal-content" style="margin-top:100px;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" style="text-align:center;">Pembayaran<span class="grt"></span></h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal ">

							<!-- Apakah anda yakin untuk menyimpan data deposit<br>
							( Note : Pastikan anda telah benar-benar menerima uang deposit ) -->
							<div class="clearfix"></div>
							<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
								<label>
									Rincian <span id="label-diskon">(Diskon - Pegawai - Rumah)</span>
								</label>
								<table class="table table-striped table-bordered">
									<thead>
										<tr>
											<td>Service</td>
											<td>Tagihan yang dibayar* (Bulan)</td>
											<td>Tagihan yang dibayar* (Rp.)</td>
											<td>Nilai Diskon Awal</td>
											<td>Nilai Diskon (Rp.)</td>
										</tr>
									</thead>
									<tbody id="tbody-diskon">

									</tbody>
								</table>
								<br>* Tidak termasuk Tunggakan
							</div> -->
							<div class="col-md-offset-3 col-lg-9 col-md-9 col-sm-12 col-xs-12" style="margin-top:20px">
								<div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="">Total Bayar</label>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
										Rp.
									</div>
									<div class="col-lg-8 col-md-8 col-sm-11 col-xs-11">
										<input id="total-bayar" type="text" class="form-control" val=0 readonly style="text-align: right;">
									</div>
								</div>
							</div>
							<div class="col-md-offset-3 col-lg-9 col-md-9 col-sm-12 col-xs-12" style="margin-top:20px">
								<div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="modal_label_biaya_admin">Biaya Admin <br> - </label>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
										Rp.
									</div>
									<div class="col-lg-8 col-md-8 col-sm-1 col-xs-11">
										<input id="biaya_admin_pembayaran" type="text" class="form-control" readonly style="text-align: right;">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="modal_label_biaya_admin">Total Diskon </label>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
										Rp.
									</div>
									<div class="col-lg-8 col-md-8 col-sm-1 col-xs-11">
										<input id="total_diskon" type="text" class="form-control" readonly style="text-align: right;">
									</div>
								</div>
								<!-- <div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Biaya Admin (Metode Penagihan)</label>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
										<inputtype="text" class="form-control" val="Rp." readonly style="text-align: left;">
									</div>
									<div class="col-lg-8 col-md-8 col-sm-1 col-xs-11">
										<input id="biaya_admin_penagihan" type="text" class="form-control" readonly style="text-align: right;">
									</div>
								</div> -->

							</div>
							<div class="col-md-offset-3 col-lg-9 col-md-9 col-sm-12 col-xs-12">

								<div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="modal_label_di_bayar_dengan">Di Bayar dengan<br>-</label>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
										Rp.
									</div>
									<div class="col-lg-8 col-md-8 col-sm-1 col-xs-11">
										<input id="modal_di_bayar_dengan" type="text" class="form-control" readonly style="text-align: right;">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12" id="modal_label_di_bayar_dengan_deposit">Di Bayar dengan<br>Deposit</label>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
										Rp.
									</div>
									<div class="col-lg-8 col-md-8 col-sm-1 col-xs-11">
										<input id="modal_di_bayar_dengan_deposit" type="text" class="form-control" readonly style="text-align: right;">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Saldo Akhir Deposit</label>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
										Rp.
									</div>
									<div class="col-lg-8 col-md-8 col-sm-1 col-xs-11">
										<input id="modal_saldo_akhir" type="text" class="form-control" readonly style="text-align: right;">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="delete_cancel_link">Close</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" id="button-modal-submit">Submit</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="x_content">
		<div class="modal fade" id="modal_cetak_kwitansi" data-backdrop="static" data-keyboard="false" style="width:100vw">
			<div class="modal-dialog">
				<div class="modal-content" style="margin-top:100px; width:fit-content">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" style="text-align:center;">Service<span class="grt"></span></h4>
					</div>
					<div class="modal-body">
						<table id="table-kwitansi" class="table table-striped jambo_table">
							<thead>
								<tr>
									<th>Check</th>
									<th>Kode Service</th>
									<th>Nama Service</th>
									<th>Tgl Bayar</th>
									<th>Cetak</th>
									<th class='input_kwitansi'>No. Kwitansi</th>
									<th class='input_kwitansi'>Save</th>
								</tr>
							</thead>
							<tbody id="tbody-kwitansi">
							</tbody>
						</table>
					</div>

					<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
						<button id="cetak-kwitansi-multiple" class="btn btn-primary">Cetak Multiple</button>
						<button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="x_content">
		<div class="modal fade" id="modal_cetak_kwitansi" data-backdrop="static" data-keyboard="false" style="width:100vw">
			<div class="modal-dialog">
				<div class="modal-content" style="margin-top:100px; width:fit-content">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" style="text-align:center;">Service<span class="grt"></span></h4>
					</div>
					<div class="modal-body">
						<table id="table-kwitansi" class="table table-striped jambo_table bulk_action">
							<thead>
								<tr>
									<th>No.</th>
									<th>Kode Service</th>
									<th>Nama Service</th>
									<th>Tgl Bayar</th>
									<th>Total Bayar</th>
									<th>Cetak</th>
									<th class='input_kwitansi'>No. Kwitansi</th>
									<th class='input_kwitansi'>Save</th>
								</tr>
							</thead>
							<tbody id="tbody-kwitansi">
							</tbody>
						</table>
					</div>

					<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
						<button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div> -->
</div>

<!-- jQuery -->
<!-- <script type="text/javascript" src="<?= base_url() ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script> -->

<script type="text/javascript">
	var rincian_service = [];
	var mulai_diskon = null;
	
	$('.tgl_pembayaran').datetimepicker({
		viewMode: 'years',
		format: 'DD/MM/YYYY',
		minDate: "<?= $backdate ?>",
		maxDate: "<?= date("Y-m-d") ?>"
	});

	function tableICheck() {
		$("input.flat").iCheck({
			checkboxClass: "icheckbox_flat-green",
			radioClass: "iradio_flat-green"
		})
	}

	function formatNumber(data) {
		data = data + '';
		data = data.replace(/,/g, "");

		data = parseInt(data) ? parseInt(data) : 0;
		data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		return data;

	}

	function unformatNumber(data) {
		data = data + '';
		return data.replace(/,/g, "");
	}

	function setTotalModal() {
		$("#total-bayar").val(
			formatNumber(parseInt(unformatNumber($("#biaya_admin_pembayaran").val())) +
				parseInt(unformatNumber($("#modal_di_bayar_dengan").val()))
			));

	}

	function get_name_service() {
		console.log("yuhuu");
		rincian_service = [];
		$.each($("#tbody_unit").children(), function(index, value) {
			// bayar biasa
			console.log($(this));

			if ($(this).children().eq(0).find("input").is(":checked")) {
				var name_service = $(this).children().eq(0).children().eq(3).html();
				if ($.inArray(name_service, rincian_service) == -1) {
					rincian_service.push(name_service);
					console.log(name_service);
				}
			}
			// bayar deposit
			if ($(this).children().eq(1).find("input").is(":checked")) {
				var name_service = $(this).parents('tr').children().eq(3).html();
				if ($.inArray(name_service, rincian_service) == -1) {
					rincian_service.push(name_service);
				}
			}
		})
	}
	$(function() {
		$('#table-kwitansi').DataTable( {
			"scrollY":        '40%',
			// "scrollCollapse": true,
			"paging":         false
		} );
		$("body").on('keyup', '.bisa_bayar', function() {
			console.log($(this).attr('max'));
			console.log(unformatNumber($(this).val()));
			if (parseInt(unformatNumber($(this).val())) <= parseInt($(this).attr('max')))
				$(this).val(formatNumber(unformatNumber($(this).val())));
			else
				$(this).val(formatNumber($(this).attr('max')));
			disable_check();

		})
		$("#cetak-kwitansi-multiple").click(function() {
			var pembayaran_id =
				$('.check-pembayaran-kwitansi:checkbox:checked').map(function() {
					return $(this).attr('val');
				}).get().join();
			window.open('<?= site_url() ?>/Cetakan/kwitansi/gabungan?pembayaran_id=' + pembayaran_id);
			$.ajax({
				type: "GET",
				data: {
					pembayaran_id: $(".check-pembayaran-kwitansi").val()
				},
				url: "<?= site_url() ?>/Cetakan/P_unit/kwitansi/gabungan",
				dataType: "json",
				success: function(data) {

				}
			});
		});

		function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
		$("#button-submit").click(function() {
			if ($("#cara_pembayaran").val()) {
				if ($("#bank").is(":disabled") || $("#bank").val()) {
					$("#modal-save").modal("show");
					setTotalModal();
					// test
					var diskon = {};
					$.each($("table").find("tbody").children(), function(i, v) {
						if ($(this).find(".check_bayar").is(":checked") || $(this).find(".check_deposit").is(":checked")) {
							var service_id = parseInt($(this).attr("service_id"));
							var is_tagihan = parseInt($(this).attr("is_tagihan"));
							if (diskon[service_id] !== undefined) {
								diskon[service_id] = parseInt(diskon[service_id]) + is_tagihan;
							} else {
								diskon[service_id] = is_tagihan;
							}
						}
					});
					console.log("test1");
					$.ajax({
						type: "GET",
						data: {
							diskon: diskon,
							unit_id: $("#unit").val()
						},
						url: "<?= site_url() ?>/Transaksi/P_pembayaran/ajax_diskon",
						dataType: "json",
						success: function(abc) {
							console.log(abc);
							console.log("hahaha");
							if (abc) {
								if (abc.parameter == 1) {
									console.log(abc.nilai);
									$.each($("#tbody_unit").find("tr"), function(k, v) {
										check_bayar = $("#tbody_unit").find("tr").eq(k).find('.check_bayar').is(':checked');
										check_bayar_deposit = $("#tbody_unit").find("tr").eq(k).find('.check_deposit').is(':checked');
										if((check_bayar == 1 || check_bayar_deposit == 1) && $("#tbody_unit").find("tr").eq(k).children().eq(2).html() >= '<?=date("Y-m-01")?>'){
											a = unformatNumber($("#tbody_unit").find("tr").eq(k).find("td").eq(8).html()) * abc.nilai; 
											return;
										}
									});
									// var a = unformatNumber($("#tbody_unit").find("tr").eq(0).find("td").eq(8).html()) * abc.nilai;
									console.log(unformatNumber($("#tbody_unit").find("tr").eq(1).find("td").eq(8).html()));
									console.log(a);

									$("#total_diskon").val(formatNumber(a));
									$("#total-bayar").val(formatNumber(unformatNumber($("#total-bayar").val()) - a));

								}
								if (abc.parameter == 2) {
									console.log(abc.nilai);
									var a = abc.nilai;
									console.log(unformatNumber($("#tbody_unit").find("tr").eq(1).find("td").eq(8).html()));
									console.log(a);

									$("#total_diskon").val(formatNumber(a));
									$("#total-bayar").val(formatNumber(unformatNumber($("#total-bayar").val()) - a));
								}
							} else {
								var a = 0;
								$("#total_diskon").val(formatNumber(a));
								$("#total-bayar").val(formatNumber(unformatNumber($("#total-bayar").val()) - a));

							}

						}
						// complete: function(abc) {
						// 	// console.log(abc);

						// }

					});
					console.log("test2");
				}
			}
		});
		$("#button-modal-submit").click(function() {
			var bayar = $("input[name='check_bayar[]']").map(function() {
				if ($(this).is(":checked")) {
					console.log($(this));
					return $(this).val() + "|" + unformatNumber($(this).parents('tr').find("input").last().val());
				}
			}).get();
			var bayar_deposit = $("input[name='check_deposit[]']").map(function() {
				if ($(this).is(":checked")) {
					return $(this).val() + "|" + unformatNumber($(this).parents('tr').find("input").last().val());
				}
			}).get();
			// for (let i = 0; i < $("#tbody_unit").find("tr").length; i++) {
			// 	// if($("#tbody_unit").find("tr").eq(i).find("td").eq(2).html() == "<?= date("Y-m-01") ?>"){
			// 	// 	mulai_diskon = $("#tbody_unit").find("tr").eq(10).find("td").eq(0).find(".check_bayar").val();

			// 	// 	break;
			// 	// }	
			// 	if($("#tbody_unit").find("tr").eq(3).find("td").eq(0).find("input").is(":checked")){
			// 		mulai_diskon = $("#tbody_unit").find("tr").eq(10).find("td").eq(0).find(".check_bayar").val();

			// 		break;
			// 	}

			// }
			for (i = 0; i < $("#tbody_unit").find("tr").length; i++) {
				if ($("#tbody_unit").find("tr").eq(i).find("td").eq(0).find("input").is(":checked")) {
					mulai_diskon = $("#tbody_unit").find("tr").eq(i).find("td").eq(0).find(".check_bayar").val();
					break;
				}
			}
			$.ajax({
				type: "POST",
				data: {
					unit_id: $("#unit").val(),
					bayar: bayar,
					bayar_deposit: bayar_deposit,
					cara_pembayaran: $("[name = cara_pembayaran]").val(),
					biaya_admin: unformatNumber($("#biaya_admin_pembayaran").val()),
					date: $("#tgl_pembayaran").val(),
					diskon: unformatNumber($("#total_diskon").val()),
					mulai_diskon: mulai_diskon
				},
				url: "<?= site_url() ?>/Transaksi/P_pembayaran/ajax_save",
				dataType: "json",
				success: function(data) {
					if (data)
						notif('Sukses', 'Pembayaran Telah Berhasil', 'success');
					else
						notif('Gagal', 'Pembayaran Gagal', 'danger');
				}

			});
			$('#modal_cetak_kwitansi').modal('show');
			$("#unit").val($("#unit").val()).change();

			$.ajax({
				type: "GET",
				data: {
					unit_id: $("#unit").val(),
				},
				url: "<?= site_url() ?>/Transaksi/P_unit/get_ajax_unit_detail",
				dataType: "json",
				success: function(data) {
					$('#table-kwitansi').DataTable().destroy();

					$("#tbody-kwitansi").html("");
					$.each(data.kwitansi, function(key, value) {
						var str = "<tr>";
						// str += "<td>" + (key + 1) + "</td>";
						str += "<td><input type='checkbox' class='check-pembayaran-kwitansi flat' name='check-pembayaran-kwitansi[]' val='" + value.service_jenis_id + "." + value.pembayaran_id + "'></td>";

						str += "<td>" + value.code_service + "</td>";
						str += "<td>" + value.name_service + "</td>";
						str += "<td>" + value.tgl_bayar + "</td>";
						// str += "<td>" + formatC(value.bayar) + "</td>";
						if (value.service_jenis_id == 1) {
							str += "<td><button class='btn btn-primary' onClick=\"window.open('<?= site_url() ?>/Cetakan/kwitansi/lingkungan/" + value.pembayaran_id + "')\">Cetak</button></td>";
						} else if (value.service_jenis_id == 2) {
							str += "<td><button class='btn btn-primary' onClick=\"window.open('<?= site_url() ?>/Cetakan/kwitansi/air/" + value.pembayaran_id + "')\">Cetak</button></td>";
						} else if (value.service_jenis_id == 5) {
							str += "<td><button class='btn btn-primary' onClick=\"window.open('<?= site_url() ?>/Cetakan/kwitansi/lo/" + value.pembayaran_id + "')\">Cetak</button></td>";
						}

						if (value.no_kwitansi == 0) {
							str += "<form method='post'><td class='input_kwitansi'><input name='no_kwitansi' value=''></td>";
							str += "<td class='input_kwitansi'><button type='submit' class='btn-save-kwitansi btn btn-primary' pembayaran_id='" + value.pembayaran_id + "'>Save</button></td></form>";
						} else {
							str += "<td>" + (value.no_kwitansi) + "</td>";
							str += "<td></td>";

						}
						str += "</tr>"
						$("#tbody-kwitansi").append(str);
						
					});
					$('#table-kwitansi').DataTable( {
						"scrollY":        '40%',
						"paging":         false
					} );
				}
			});
			$("#unit").val($("#unit").val()).change();

		});
		$("body").on("click", ".btn-save-kwitansi", function() {
			var no_kwitansi = $(this).parent().parent().children(".input_kwitansi").children().val();
			var pembayaran_id = $(this).attr("pembayaran_id");
			$.ajax({
				type: "POST",
				data: {
					pembayaran_id: pembayaran_id,
					no_kwitansi: no_kwitansi
				},
				url: "<?= site_url() ?>/Transaksi/P_unit/ajax_save_kwitansi",
				dataType: "json",
				success: function(data) {
					console.log(data);
					if (data) {
						notif('Sukses', 'Input Kwitansi Berhasil', 'success');
					} else {
						notif('Sukses', 'Input Kwitansi Gagal', 'danger');
					}
				}
			});
		});

		function disable_check() {
			total = 0;
			for (var i = 0; i < $(".check_deposit").length; i++) {
				if (!$(".check_deposit").eq(i).is(":checked")) {
					$(".check_bayar").eq(i).parent().css("pointer-events", "unset");
					if (parseInt(unformatNumber($("#saldo_akhir").val())) < unformatNumber($(".check_deposit").eq(i).parent().parent().parent().children(".total").html()))
						$(".check_deposit").eq(i).parent().css("pointer-events", "none");
					else
						$(".check_deposit").eq(i).parent().css("pointer-events", "unset");
				} else
					$(".check_bayar").eq(i).parent().css("pointer-events", "none");

				if ($(".check_bayar").eq(i).is(":checked")) {
					$(".check_deposit").eq(i).parent().css("pointer-events", "none");
					total += parseInt(unformatNumber($(".check_deposit").eq(i).parent().parent().parent().children(".td_bisa_bayar").children('.bisa_bayar').val()));
				} else
				if (parseInt(unformatNumber($("#saldo_akhir").val())) >= unformatNumber($(".check_deposit").eq(i).parent().parent().parent().children(".td_bisa_bayar").children(".td_bisa_bayar").val()))
					$(".check_deposit").eq(i).parent().css("pointer-events", "unset");
			}
			$("#di_bayar_dengan").val(formatNumber(total));
			$("#modal_di_bayar_dengan").val(formatNumber(total));

		}
		$("table").on("ifChanged", "#check-all-bayar", function() {
			if ($("#check-all-bayar").is(":checked")) {
				$(".check_bayar").iCheck("check");
			} else {
				$(".check_bayar").iCheck("uncheck");
			}
			setTotalModal();
		});


		$("#tbody_unit").on("ifChanged", ".check_bayar", function() {
			disable_check();
			var name_service = $(this).parents('tr').children().eq(3).html();
			get_name_service();
		});

		$("#tbody_unit").on("ifChanged", ".check_deposit", function() {
			if ($(this).is(":checked")) {
				var nilai1 = parseInt(unformatNumber($(this).parent().parent().parent().children(".total").html()));
				var nilai2 = parseInt(unformatNumber($("#deposit_digunakan").val()));
				hasil = nilai1 + (nilai2 ? nilai2 : 0);
				$("#deposit_digunakan").val(formatNumber(hasil));
				$("#modal_di_bayar_dengan_deposit").val(formatNumber(hasil));

			} else {
				var nilai1 = parseInt(unformatNumber($(this).parent().parent().parent().children(".total").html()));
				var nilai2 = parseInt(unformatNumber($("#deposit_digunakan").val()));
				hasil = (nilai2 ? nilai2 : 0) - nilai1;
				$("#deposit_digunakan").val(formatNumber(hasil));
				$("#modal_di_bayar_dengan_deposit").val(formatNumber(hasil));

			}
			$("#saldo_akhir").val(formatNumber(unformatNumber($("#saldo_deposit").val()) - unformatNumber($("#deposit_digunakan").val())));
			$("#modal_saldo_akhir").val(formatNumber(unformatNumber($("#saldo_deposit").val()) - unformatNumber($("#deposit_digunakan").val())));

			disable_check();
			get_name_service();
		})




		$("#cara_pembayaran").change(function() {
			$("#bank").prop('selectedIndex', 0);

			$("#label_di_bayar_dengan").html("Di Bayar dengan <br>" + $('option:selected', this).attr('nama'));
			$("#modal_label_di_bayar_dengan").html("Di Bayar dengan<br>" + $('option:selected', this).attr('nama'));

			$("#label_biaya_admin").html(("Biaya Admin <br>" + $('option:selected', this).attr('nama')));
			$("#modal_label_biaya_admin").html(("Biaya Admin <br>" + $('option:selected', this).attr('nama')));
			$("#di_bayar_dengan_table").html("<input id='check-all-bayar' type='checkbox' class='flat table-check'> " + $('option:selected', this).attr('kode'));
			$("#modal_di_bayar_dengan_table").html("<input id='check-all-bayar' type='checkbox' class='flat table-check'> " + $('option:selected', this).attr('kode'));
			tableICheck();


			var j = 0;
			for (var i = 1; i < $("#bank").children().length; i++) {
				if ($("#bank").children().eq(i).attr("jenis_cara_pembayaran_id") == $("#cara_pembayaran option:selected").attr("jenis_pembayaran")) {
					$("#bank").children().eq(i).show();
					j++;
				} else {
					$("#bank").children().eq(i).hide();
				}

			}
			var biaya_admin = 0;
			if (j > 0) {
				$("#bank").attr("disabled", false);
				$("#cara_pembayaran").attr("name", "");
				$("#bank").attr("name", "cara_pembayaran");
			} else {
				$("#bank").attr("disabled", true);
				$("#cara_pembayaran").attr("name", "cara_pembayaran");
				$("#bank").attr("name", "");
				biaya_admin = $("#cara_pembayaran").children("option:selected").attr('biaya_admin');
			}
			$("#biaya_admin_pembayaran").val(formatNumber(biaya_admin));
			$("#view_biaya_admin_pembayaran").val(formatNumber(biaya_admin));
			setTotalModal();
		});
		$("#bank").change(function() {
			$("#biaya_admin_pembayaran").val(formatNumber($("#bank").children("option:selected").attr('biaya_admin')));
			$("#view_biaya_admin_pembayaran").val(formatNumber($("#bank").children("option:selected").attr('biaya_admin')));
			setTotalModal();
		});
		$("#unit").select2({
			width: 'resolve',
			// resize:true,
			minimumInputLength: 1,
			placeholder: 'Kawasan - Blok - Unit - Pemilik',
			ajax: {
				type: "GET",
				dataType: "json",
				data: $(this).val(),
				url: "<?= site_url() ?>/Transaksi/P_pembayaran/ajax_get_unit/",
				data: function(params) {
					return {
						data: params.term
					}
				},
				processResults: function(data) {
					console.log(data);
					return {
						results: data
					};
				}
			}
		});
		$('#tgl_pembayaran').on('dp.change', function(e) {
			$("#unit").trigger("change");
		})

		$("#unit").change(function() {
			$.ajax({
				type: "POST",
				data: {
					unit_id: $("#unit").val()
				},
				url: "<?= site_url() ?>/Transaksi/P_pembayaran/ajax_get_deposit/" + $("#unit").val(),
				dataType: "json",
				success: function(data) {
					$("#saldo_deposit").val(formatNumber(data));
					$("#saldo_akhir").val(formatNumber(data));
					disable_check();

				}
			});
			$.ajax({
				type: "POST",
				data: {
					unit_id: $("#unit").val(),
					date: $("#tgl_pembayaran").val()

				},
				url: "<?= site_url() ?>/Transaksi/P_pembayaran/ajax_get_tagihan",
				dataType: "json",
				success: function(data) {
					console.log(data);

					sumTagihan = 0;
					sumDenda = 0;
					sumPinalty = 0;
					sumTelahDibayar = 0;
					sumTotalTagihan = 0;
					$(".tableDT2").DataTable().destroy();
					$("#tbody_unit").html("");

					table = "";
					total = 0;
					total_pemutihan_tagihan = 0;
					total_pemutihan_denda = 0;
					tmp2 = '';
					$.each(data.tagihan_lingkungan, function(k, v) {
						total_row = 
							(v.belum_bayar==0?v.nilai_tagihan:0) 
							+ (v.belum_bayar==0?v.nilai_denda:0) 
							+ v.belum_bayar 
							+ 0 
							- (v.view_pemutihan_nilai_tagihan + v.view_pemutihan_nilai_denda);
						total += v.nilai_tagihan + v.nilai_denda + v.belum_bayar + 0 - (v.view_pemutihan_nilai_tagihan + v.view_pemutihan_nilai_denda);
						sisa_tagihan = v.nilai_tagihan - v.view_pemutihan_nilai_tagihan;
						sisa_denda = v.nilai_denda - v.view_pemutihan_nilai_denda;
						var tmp1 = '';
						if (v.belum_bayar)
							tmp1 = 'disabled';
						color = '';
						color_font = '';
						console.log(v.status_tagihan);
						if (v.status_tagihan == 2 || v.status_tagihan == 3) {
							color = "background-color:#f0ad4e";
							color_font = "color:white";
							tmp1 = 'disabled';
							tmp2 = 'disabled';
						}

						table += "<tr service_id='" + v.service_id + "' is_tagihan='" + v.is_tagihan + "' style='" + color + ";" + color_font + "'>" +
							"<td>" +
							"<input type='checkbox' class='check_bayar flat table-check' name='check_bayar[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + v.nilai_denda + "|" + 0 + "|" + v.view_pemutihan_nilai_tagihan + "|" + v.view_pemutihan_nilai_denda + "|" + v.belum_bayar + "' " + tmp2 + ">" +
							"</td>" +
							"<td>" +
							"<input type='checkbox' class='check_deposit flat table-check' name='check_deposit[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + v.nilai_denda + "|" + 0 + "|" + v.view_pemutihan_nilai_tagihan + "|" + v.view_pemutihan_nilai_denda + "|" + v.belum_bayar + "' " + tmp2 + ">" +
							"</td>" +
							"<td class='text-right'>" +
							v.periode +
							"</td>" +
							"<td>Lingkungan</td>" +
							"<td class='text-right'>" +
							formatNumber(v.belum_bayar==0?v.nilai_tagihan:0) +
							"<td class='text-right'>" +
							formatNumber(v.belum_bayar==0?v.nilai_denda:0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(v.view_pemutihan_nilai_tagihan) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(v.view_pemutihan_nilai_denda) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(sisa_tagihan) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(sisa_denda) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(v.belum_bayar) +
							"</td>" +
							"<td class='total text-right'>" +
							formatNumber((total_row)) +
							"</td>" +
							"<td class='td_bisa_bayar text-right col-md-2 col-lg-2'>" +
							"<input class='text-right bisa_bayar form-input col-md-12 col-lg-12' max='" + total_row + "' name='bisa_bayar' value='" + formatNumber(total_row) + "' " + tmp1 + ">" +
							"</td>" +

							"</tr>";
						total_pemutihan_tagihan += v.view_pemutihan_nilai_tagihan;
						total_pemutihan_denda += v.view_pemutihan_nilai_denda;
					});
					$.each(data.tagihan_layanan_lain, function(k, v) {
						total_row = v.total;
						total += v.total;

						var tmp1 = '';
						if (v.belum_bayar)
							tmp1 = 'disabled';
						color = '';
						color_font = '';
						console.log(v.status_tagihan);
						if (v.status_tagihan == 2 || v.status_tagihan == 3) {
							color = "background-color:#f0ad4e";
							color_font = "color:white";
							tmp1 = 'disabled';
							tmp2 = 'disabled';
						}
						sisa_tagihan = 0;
						// "<input type='checkbox' class='check_bayar flat table-check' name='check_bayar[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + v.nilai_denda + "|" + 0 + "|" + v.view_pemutihan_nilai_tagihan + "|" + v.view_pemutihan_nilai_denda + "|" + v.belum_bayar + "' " + tmp2 + ">" +

						table += "<tr service_id='" + v.service_id + "' is_tagihan='" + v.is_tagihan + "' style='" + color + ";" + color_font + "'>" +
							"<td>" +
							"<input type='checkbox' class='check_bayar flat table-check' name='check_bayar[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.total +"|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 +"' " + tmp2 + ">" +
							"</td>" +
							"<td>" +
							"<input type='checkbox' class='check_deposit flat table-check' name='check_deposit[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.total +"|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 +"' " + tmp2 + ">" +
							"</td>" +
							"<td class='text-right'>" +
							v.periode +
							"</td>" +
							"<td>"+v.service+"</td>" +
							"<td class='text-right'>" +
							formatNumber(v.nilai_tagihan) +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(sisa_tagihan) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(v.belum_bayar) +
							"</td>" +
							"<td class='total text-right'>" +
							formatNumber((v.total)) +
							"</td>" +
							"<td class='td_bisa_bayar text-right col-md-2 col-lg-2'>" +
							"<input class='text-right bisa_bayar form-input col-md-12 col-lg-12' max='" + v.total + "' name='bisa_bayar' value='" + formatNumber(v.total) + "' " + tmp1 + ">" +
							"</td>" +

							"</tr>";
						total_pemutihan_tagihan += v.view_pemutihan_nilai_tagihan;
						total_pemutihan_denda += v.view_pemutihan_nilai_denda;
					})

					$.each(data.tagihan_air, function(k, v) {
						total_row = 
							(v.belum_bayar==0?v.nilai_tagihan:0) 
							+ (v.belum_bayar==0?v.nilai_denda:0) 
							+ v.belum_bayar 
							+ 0 
							- (v.view_pemutihan_nilai_tagihan + v.view_pemutihan_nilai_denda);
						total += v.nilai_tagihan + v.nilai_denda + v.belum_bayar + 0 - (v.view_pemutihan_nilai_tagihan + v.view_pemutihan_nilai_denda);
						sisa_tagihan = v.nilai_tagihan - v.view_pemutihan_nilai_tagihan;
						sisa_denda = v.nilai_denda - v.view_pemutihan_nilai_denda;
						var tmp1 = '';
						if (v.belum_bayar)
							tmp1 = 'disabled';
						color = '';
						color_font = '';
						if (v.status_tagihan == 2 || v.status_tagihan == 3) {
							color = "background-color:#f0ad4e";

							color_font = "color:white";
							tmp1 = 'disabled';
							tmp2 = 'disabled';
						}
						table += "<tr service_id='" + v.service_id + "' is_tagihan='" + v.is_tagihan + "' style='" + color + ";" + color_font + "'>" +
							"<td>" +
							"<input type='checkbox' class='check_bayar flat table-check' name='check_bayar[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + v.nilai_denda + "|" + 0 + "|" + v.view_pemutihan_nilai_tagihan + "|" + v.view_pemutihan_nilai_denda + "|" + v.belum_bayar + "' " + tmp2 + ">" +
							"</td>" +
							"<td>" +
							"<input type='checkbox' class='check_deposit flat table-check' name='check_deposit[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + v.nilai_denda + "|" + 0 + "|" + v.view_pemutihan_nilai_tagihan + "|" + v.view_pemutihan_nilai_denda + "|" + v.belum_bayar + "'>" +
							"</td>" +
							"<td class='text-right'>" + v.periode + "</td>" +
							"<td>AIR</td>" +
							"<td class='text-right'>" +
							formatNumber(v.belum_bayar==0?v.nilai_tagihan:0) +
							"<td class='text-right'>" +
							formatNumber(v.belum_bayar==0?v.nilai_denda:0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(v.view_pemutihan_nilai_tagihan) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(v.view_pemutihan_nilai_denda) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(sisa_tagihan) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(sisa_denda) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(v.belum_bayar) +
							"</td>" +
							"<td class='total text-right'>" +
							formatNumber((total_row)) +
							"</td>" +
							"<td class='td_bisa_bayar total text-right col-md-2 col-lg-2'>" +
							"<input class='text-right bisa_bayar form-input col-md-12 col-lg-12' max='" + total_row + "' name='bisa_bayar' value='" + formatNumber(total_row) + "' " + tmp1 + " >" +
							"</td>" +

							"</tr>";
						total_pemutihan_tagihan += v.view_pemutihan_nilai_tagihan;
						total_pemutihan_denda += v.view_pemutihan_nilai_denda;
					});
					$.each(data.tagihan_loi, function(k, v) {
						total_row = v.nilai_tagihan;
						total += v.nilai_tagihan;
						sisa_tagihan = v.nilai_tagihan;
						table += "<tr service_id='" + 0 + "' is_tagihan='" + v.is_tagihan + "'>" +
							"<td>" +
							"<input type='checkbox' class='check_bayar flat table-check' name='check_bayar[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "'>" +
							"</td>" +
							"<td>" +
							"<input type='checkbox' class='check_deposit flat table-check' name='check_deposit[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "'>" +
							"</td>" +
							"<td class='text-right'>" + v.periode + "</td>" +
							"<td>" + v.service + "</td>" +
							"<td class='text-right'>" +
							formatNumber(v.nilai_tagihan) +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(sisa_tagihan) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='total text-right'>" +
							formatNumber((total_row)) +
							"</td>" +
							"<td class='td_bisa_bayar total text-right col-md-2 col-lg-2'>" +
							"<input class='text-right bisa_bayar form-input col-md-12 col-lg-12' max='" + total_row + "' name='bisa_bayar' value='" + formatNumber(total_row) + "' disabled>" +
							"</td>" +

							"</tr>";
					});
					$.each(data.tagihan_tvi, function(k, v) {
						total_row = v.nilai_tagihan;
						total += v.nilai_tagihan;
						sisa_tagihan = v.nilai_tagihan;
						table += "<tr service_id='" + 0 + "' is_tagihan='" + v.is_tagihan + "'>" +
							"<td>" +
							"<input type='checkbox' class='check_bayar flat table-check' name='check_bayar[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "'>" +
							"</td>" +
							"<td>" +
							"<input type='checkbox' class='check_deposit flat table-check' name='check_deposit[]' value='" + v.service_id + "|" + v.service_jenis_id + "|" + v.tagihan_id + "|" + v.nilai_tagihan + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "|" + 0 + "'>" +
							"</td>" +
							"<td class='text-right'>" + v.periode + "</td>" +
							"<td>" + v.service + "</td>" +
							"<td class='text-right'>" +
							formatNumber(v.nilai_tagihan) +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(sisa_tagihan) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='text-right'>" +
							formatNumber(0) +
							"</td>" +
							"<td class='total text-right'>" +
							formatNumber((total_row)) +
							"</td>" +
							"<td class='td_bisa_bayar total text-right col-md-2 col-lg-2'>" +
							"<input class='text-right bisa_bayar form-input col-md-12 col-lg-12' max='" + total_row + "' name='bisa_bayar' value='" + formatNumber(total_row) + "' disabled>" +
							"</td>" +

							"</tr>";
					});
					$("#total_tagihan").val(formatNumber(total));
					$("#tbody_unit").append(table);
					tableICheck();
					// $("#tbody_unit").append(data);	

					var tableDT = $(".tableDT2").dataTable({
						"paging": false,
						"order": [
							[2, "asc"],
							[3, "asc"]
						]

					});
					if (total_pemutihan_tagihan == 0) {
						tableDT.fnSetColumnVis(6, false);
						tableDT.fnSetColumnVis(8, false);
					}
					if (total_pemutihan_denda == 0) {
						tableDT.fnSetColumnVis(7, false);
						tableDT.fnSetColumnVis(9, false);
					}
					if (tmp2 == '')
						$("#check-all-bayar").attr("disabled", false)
					else
						$("#check-all-bayar").attr("disabled", true)
				}
			})
		});
		if (<?= $unit->id ?> != 0)
			$("#unit").val(<?= $unit->id ?>).change();
		$(".tableDT2").DataTable({
			"paging": false
		});

	});
</script>