<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>


<!-- switchery -->
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>


<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/P_registrasi_layanan_lain'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/P_registrasi_layanan_lain/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" class="form-horizontal form-label-left">

		<div id="div-unit" class="col-md-6">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Unit ? <span class="required">*</span>
			</label>
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="checkbox">
					<label id="label_ppn">
						<p style="display:contents">Non Unit</p>
						<input id="is_unit" type="checkbox" class="denda_flag js-switch" name="is_unit" value="1" checked>
						<p style="display:contents">Unit</p>
					</label>
				</div>
			</div>
		</div>

		<div class="col-md-6 div-unit">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<div class="form-group">
					<select id='pilih_unit' class='form-control select2' name="unit_id" require>
					</select>
				</div>
			</div>
		</div>

		<div class="col-md-12 div-non-unit" style="margin-top:10px" hidden>
			<div class="col-md-6">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<select name="customer_id" id="pilih_customer" class="form-control select2 non_unit">
						<option value="" selected="" disabled="">--Pilih Customer--</option>
					</select>

				</div>

				<div class="col-md-2 col-sm-9 col-xs-12">
					<div class="btn btn-primary" onclick="popitup('<?= site_url(); ?>/p_master_customer/add')">
						DAFTAR
					</div>
				</div>
			</div>

			<div class="col-md-6">

				<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Virtual</label>
				<div class="col-md-7 col-sm-9 col-xs-12">
					<div class="form-group">
						<select id='pilih_unit_virtual' name="unit_virtual_id" class=' form-control select2'>
						</select>
					</div>
				</div>
				<div class="col-md-2 col-sm-9 col-xs-12">
					<div class="btn btn-primary" onclick="popitup('<?= site_url(); ?>/P_master_unit_virtual/add')">
						DAFTAR
					</div>
				</div>
			</div>
		</div>
		<div class="clear-fix"></div>
		<br>
		<div id="data_unit" class="col-md-12 col-xs-12">
			<div class="row" style="margin-top: 35px;">
				<div class="col-md-12">
					<div class="col-md-6">
						<!-- <div class="form-group unit div-unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="project_name" name="project_name" readonly class="form-control">

								<input type="hidden" name="jumlah_hari" id="jumlah_hari">
								<input type="hidden" name="type_unit" id="type_unit">
								<input type="hidden" name="unit_id" id="unit_id">
							</div>
						</div> -->
						<div class="form-group unit div-unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="kawasan_name" name="kawasan_name" readonly class="form-control">
							</div>
						</div>
						<div class="form-group unit div-unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="blok_name" name="blok_name" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit div-unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Unit</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="unit_name" name="unit_name" readonly class="form-control unit">
							</div>
						</div>

						<div class="form-group unit div-unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" placeholder="Masukkan nama customer" value="" id="customer_name" name="customer_name" readonly class="form-control unit">
							</div>
						</div>

						<div class="form-group div-non-unit" hidden>
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="alamat_non_unit" name="alamat_non_unit" readonly class="form-control">
							</div>
						</div>

						<div class="form-group  div-non-unit" hidden>
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Usaha</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" value="" id="jenis_usaha_non_unit" name="jenis_usaha_non_unit" readonly class="form-control">
							</div>
						</div>
					</div>
					<div class="col-md-6">

						<div class="form-group unit div-unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor VA</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_va" id="nomor_va" value="" readonly class="form-control unit">
							</div>
						</div>
						<div class="form-group unit">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="nomor_registrasi" id="nomor_registrasi" value="Auto Generate" readonly class="form-control unit">
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
								<input type="email" placeholder="Email" id="email" readonly="" name="email" value="" class="form-control unit">
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>


		<div id="data_registrasi" class="col-md-12 col-xs-12">
			<div class="x_title" style="margin-top:20px">
				<h2>Service</h2>
				<div class="clearfix"></div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Paket Service</label>
					<div class="col-md-9 col-sm-9 ">
						<select id="pilih_paket_service" class="form-control" name="paket_service" require>
							<option></option>
							<?php foreach ($dataServiceLain as $k => $v) : ?>
								<option value="<?= $v->id ?>"><?= $v->name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Periode</label>
					<div class="col-md-3 col-sm-3" style="padding-right:0px">
						<input id="periode-awal" name="periode_awal" type="text" class="hitung-periode form-control text-center datetimepicker" placeholder="m / y" value="<?= date('d/m/Y') ?>" readonly>
					</div>
					<div class="col-md-3 col-sm-3" style="padding-right:0px">
						<input id="periode-akhir" name="periode_akhir" type="text" class="hitung-periode form-control text-center datetimepicker" placeholder="m / y" value="<?= date('d/m/Y') ?>" readonly>
					</div>
					<div class="col-md-3 col-sm-3">
						<input id="jumlah_periode" name="jumlah_periode" type="text" class="form-control text-center" placeholder="-" style="padding-right: 60px" value="1" readonly>
						<span class="label-periode form-control-feedback right" aria-hidden="true" style="width: auto;padding-left:3px">Periode</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Harga Satuan</label>
					<div class="col-md-9 col-sm-9">
						<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
						<input id="harga_satuan" name="harga_satuan" type="text" class="currency form-control text-right" placeholder="-" style="padding-left: 50px;padding-right:70px" readonly>
						<span class="label-periode2 form-control-feedback right" aria-hidden="true" style="width: auto;padding-left:3px">/Periode</span>

					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Kuantitas</label>
					<div class="col-md-9 col-sm-9 ">
						<input id="kuantitas" value="1" name="kuantitas" type="number" class="form-control text-right" placeholder="-- Input kuantitas --" style="50px;padding-right:70px" min=1 readonly>
						<span id="satuan" class="form-control-feedback right" aria-hidden="true" style="width: auto;padding-left:3px">------</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Minimum Berlangganan</label>
					<div class="col-md-3 col-sm-3 ">
						<input id="min_berlangganan" name="min_berlangganan" type="text" class="form-control text-center" placeholder="-" style="padding-right:60px" readonly>
						<span class="label-periode form-control-feedback right" aria-hidden="true" style="width: auto;padding-left:3px">Periode</span>
					</div>
					<label class="col-form-label col-md-3 col-sm-3 control-label">Berlangganan ?</label>
					<div class="col-md-3 col-sm-3 ">
						<input id="status_berlangganan" name="status_berlangganan" type="text" class="form-control text-center" placeholder="Ya/Tidak" readonly>
					</div>

				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Biaya Pemasangan</label>
					<div class="col-md-9 col-sm-9">
						<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
						<input id="biaya_pemasangan" name="biaya_pemasangan" type="text" class="currency form-control text-right" placeholder="-" style="padding-left: 50px" readonly>
					</div>


				</div>
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Biaya Registrasi</label>
					<div class="col-md-9 col-sm-9">
						<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
						<input id="biaya_registrasi" name="biaya_registrasi" type="text" class="currency form-control text-right" placeholder="-" style="padding-left: 50px" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Harga Pokok</label>
					<div class="col-md-9 col-sm-9">
						<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
						<input id="harga_pokok" name="harga_pokok" type="text" class="currency form-control text-right" placeholder="-" style="padding-left: 50px" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Total PPN</label>
					<div class="col-md-9 col-sm-9 ">
						<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
						<input id="total_ppn" name="total_ppn" type="text" class="currency form-control text-right" placeholder="-" style="padding-left: 50px" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-3 col-sm-3 control-label">Total Tagihan</label>
					<div class="col-md-9 col-sm-9 ">
						<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
						<input id="total_tagihan" name="total_tagihan" type="text" class="currency form-control text-right" placeholder="-" style="padding-left: 50px" readonly>
					</div>
				</div>
			</div>

		</div>
		<div class="col-md-12 col-xs-12">
			<div class="center-margin">
				<button class="btn btn-primary" type="reset">Reset</button>
				<button id="btn-submit" type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>
	</form>

</div>
<!-- <button id='btn-add-service' type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>Add Service</button> -->

<div class="x_content">
	<div class="modal fade" id="modal-iframe-large" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style="width: 100%">
			<div class="modal-content">

				<div class="modal-header" style="height: 45px">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="modal_iframe_close()">&times;</button>
					<h4 id="modal-title-iframe-large" class="modal-title" style="text-align:center;">Pembayaran<span class="grt"></span></h4>
				</div>
				<div class="modal-body" style="height: 80%">
					<div id="iframe-large-modal-body">
					</div>
				</div>

				<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center; height:65px">
					<button id="modal-iframe-large-newtab" type="button" class="btn btn-primary" disabled>Open New Tab</button>

					<button type="button" class="btn btn-info" data-dismiss="modal" id="delete_cancel_link" onclick="modal_iframe_large_close()">Close</button>

				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	function popitup(a) {
		newtab = window.open(a,
			'open_window',
			'menubar=no, toolbar=no, location=no, directories=no, status=no, scrollbars=no, resizable=no, dependent, width=800, height=620, left=50, top=50');
		newtab.onbeforeunload = function() {
			console.log('haha1')
		}
		newtab.onclose = function() {
			console.log('haha2')
		}
		console.log(newtab);

	}
	var biaya_satuan_tanpa_langganan = 0;
	var biaya_satuan_langganan = 0;
	minimal_langganan = 0;
	var ppn_flag = 0;
	dtp = $('.datetimepicker').datetimepicker({
		viewMode: 'years',
		format: 'DD/MM/YYYY'
	});
	function notif(title, text, type) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            styling: 'bootstrap3'
        });
    }
	function formatNumber(data) {
		data = data + '';
		data = data.replace(/,/g, "");

		data = parseInt(data) ? parseInt(data) : 0;
		data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		return data;

	}

	function change_nilai_pokok() {
		jumlah_periode = $("#jumlah_periode").val();
		kuantitas = $("#kuantitas").val();
		if (jumlah_periode < minimal_langganan) { //tanpa registrasi/langganan
			$("#status_berlangganan").val('Tidak');
			$("#harga_satuan").val(formatNumber(biaya_satuan_tanpa_langganan));
			harga_pokok = jumlah_periode * biaya_satuan_tanpa_langganan * kuantitas;
		} else {
			$("#status_berlangganan").val('Ya');
			$("#harga_satuan").val(formatNumber(biaya_satuan_langganan));
			harga_pokok = jumlah_periode * biaya_satuan_langganan * kuantitas;
		}
		$("#harga_pokok").val(formatNumber(harga_pokok));
		if(ppn_flag == 1){
			$("#total_ppn").val(
				formatNumber(
					((unformatNumber($("#biaya_pemasangan").val())
					+unformatNumber($("#biaya_registrasi").val())
					+unformatNumber($("#harga_pokok").val()))/10)
				)
			);
		}
		$("#total_tagihan").val(
			formatNumber(
				unformatNumber($("#biaya_pemasangan").val())
				+unformatNumber($("#biaya_registrasi").val())
				+unformatNumber($("#harga_pokok").val())
				+unformatNumber($("#total_ppn").val())
			)
		)
		
	}

	function unformatNumber(data) {
		data = data + '';
		return parseInt(data.replace(/,/g, ""));
	}

	var DateDiff = {

		inDays: function(d1, d2) {
			var t2 = d2.getTime();
			var t1 = d1.getTime();

			return parseInt((t2 - t1) / (24 * 3600 * 1000));
		},

		inWeeks: function(d1, d2) {
			var t2 = d2.getTime();
			var t1 = d1.getTime();

			return parseInt((t2 - t1) / (24 * 3600 * 1000 * 7));
		},

		inMonths: function(d1, d2) {
			var d1Y = d1.getFullYear();
			var d2Y = d2.getFullYear();
			var d1M = d1.getMonth();
			var d2M = d2.getMonth();

			return (d2M + 12 * d2Y) - (d1M + 12 * d1Y);
		},

		inYears: function(d1, d2) {
			return d2.getFullYear() - d1.getFullYear();
		}
	}
	var no_service = 1;
	// var form = $(".form_untuk_service")[0].outerHTML;

	const item_struct = $(".form_untuk_service");
	// const a = item_struct[0].outerHTML;

	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}

	function diffDate(m1, y1, m2, y2) {
		return (((parseInt(y2) - parseInt(y1)) * 12) + (parseInt(m2) - parseInt(m1)));
	}

	//id_paket_service = array


	$(function() {
		$("form").submit(function(e) {
			for (let i = 0; i < $('.currency').length; i++) {
				$('.currency').eq(i).val(unformatNumber($(".currency").eq(i).val()));
			}
            e.preventDefault();
            $.ajax({
                type: "POST",
                data: $("form").serialize(),
                url: "<?= site_url('transaksi_lain/P_registrasi_layanan_lain/ajax_save') ?>",
                dataType: "json",
                success: function(data) {
                    if (data.status == 1) {
                        notif('Sukses', data.message, 'success')
                        setTimeout(function() {
                            window.location.href = '<?=site_url('transaksi_lain/P_registrasi_layanan_lain')?>'
                        }, 2 * 1000);
                    } else
                        notif('Gagal', data.message, 'danger')
                }
            });
			for (let i = 0; i < $('.currency').length; i++) {
				$('.currency').eq(i).val(formatNumber($(".currency").eq(i).val()));
			}
        })
		$("#kuantitas").keyup(function() {
			change_nilai_pokok();
		})
		$("#kuantitas").change(function() {
			change_nilai_pokok();
		})
		$("#is_unit").click(function() {
			console.log($("#is_unit").is(':checked'));
			if ($("#is_unit").is(':checked')) {


				$(".div-non-unit").hide();
				$(".div-unit").show();
			} else {

				$(".div-non-unit").show();
				$(".div-unit").hide();
			}
			console.log('denda_flag change');
		})
		$("#pilih_unit").select2({
			width: '100%',
			resize: true,
			minimumInputLength: 1,
			placeholder: 'Kawasan - Blok - Unit - Pemilik',
			ajax: {
				type: "GET",
				dataType: "json",
				url: "<?= site_url() ?>/transaksi_lain/P_registrasi_layanan_lain/get_ajax_unit",
				data: function(params) {
					return {
						data: params.term
					}
				},
				processResults: function(data) {
					console.log(data);
					// Tranforms the top-level key of the response object from 'items' to 'results'
					return {
						results: data
					};
				}
			}
		});
		$("#pilih_customer").select2({
			width: '100%',
			resize: true,
			minimumInputLength: 1,
			placeholder: '-- Pilih Customer -- ',
			ajax: {
				type: "GET",
				dataType: "json",
				url: "<?= site_url() ?>/Transaksi_lain/P_registrasi_tvi/get_select2_customer",
				data: function(params) {
					return {
						data: params.term
					}
				},
				processResults: function(data) {
					console.log(data);
					// Tranforms the top-level key of the response object from 'items' to 'results'
					return {
						results: data
					};
				}
			}
		});
		$("#pilih_unit_virtual").select2({
			width: '100%',
			resize: true,
			minimumInputLength: 1,
			placeholder: '-- Pilih Unit Virutal --',
			ajax: {
				type: "GET",
				dataType: "json",
				url: "<?= site_url() ?>/Transaksi_lain/P_registrasi_tvi/get_select2_unit_virtual",
				data: function(params) {
					return {
						data: params.term,
						customer_id: $('#pilih_customer').val()
					}
				},
				processResults: function(data) {
					console.log(data);
					// Tranforms the top-level key of the response object from 'items' to 'results'
					return {
						results: data
					};
				}
			}
		});
		$("#pilih_paket_service").change(function() {
			url = '<?= site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_info_paket_service';
			var parent = $(this).parents(".service_form");
			var id = $("#pilih_paket_service").val();
			date_awal = Date.parse($("#periode_awal").val());
			date_akhir = Date.parse($("#periode_akhir").val());
			$("#kuantitas").attr('readonly', false);
			// minimal_berlangganan = parent.find(".minimal-langganan").val(data.minimal_langganan);
			$.ajax({
				type: "post",
				url: url,
				data: {
					id: id
				},
				dataType: "json",
				success: function(data) {
					ppn_flag = data.ppn_flag;
					dtp.datetimepicker('destroy');
					if (data.tipe_periode == 1) {
						d1 = $("#periode-awal").val().substr(0, 2);
						m1 = $("#periode-awal").val().substr(3, 2);
						y1 = $("#periode-awal").val().substr(6, 4);
						d2 = $("#periode-akhir").val().substr(0, 2);
						m2 = $("#periode-akhir").val().substr(3, 2);
						y2 = $("#periode-akhir").val().substr(6, 4);
						var date1 = new Date(y1, m1, d1);
						var date2 = new Date(y2, m2, d2);
						var diff = DateDiff.inDays(date1, date2);
						$('.label-periode').html('Hari');
						$('.label-periode2').html('/Hari');
						dtp = $('.datetimepicker').datetimepicker({
							viewMode: 'years',
							format: 'DD/MM/YYYY'
						});
						$('.datetimepicker').val("<?= date('d/m/Y') ?>");

					} else if (data.tipe_periode == 2) {
						m1 = $("#periode-awal").val().substr(0, 2);
						y1 = $("#periode-awal").val().substr(3, 4);
						m2 = $("#periode-akhir").val().substr(0, 2);
						y2 = $("#periode-akhir").val().substr(3, 4);
						var date1 = new Date(y1, m1, 01);
						var date2 = new Date(y2, m2, 01);
						var diff = DateDiff.inMonths(date1, date2);
						$('.label-periode').html('Bulan');
						$('.label-periode2').html('/Bulan');
						dtp = $('.datetimepicker').datetimepicker({
							viewMode: 'years',
							format: 'MM/YYYY'
						});
						$('.datetimepicker').val("<?= date('m/Y') ?>");

					} else if (data.tipe_periode == 3) {
						y1 = $("#periode-awal").val().substr(0, 4);
						y2 = $("#periode-akhir").val().substr(0, 4);
						var date1 = new Date(y1, 01, 01);
						var date2 = new Date(y2, 01, 01);
						var diff = DateDiff.inYears(date1, date2);
						$('.label-periode').html('Tahun');
						$('.label-periode2').html('/Tahun');
						dtp = $('.datetimepicker').datetimepicker({
							viewMode: 'years',
							format: 'YYYY'
						});
						$('.datetimepicker').val("<?= date('Y') ?>");

					}
					$("#periode_awal").attr('readonly', false);
					$("#periode_akhir").attr('readonly', false);

					// console.log(thisRow);
					// parent.find(".jumlah-satuan").val(1);
					$("#satuan").html(data.satuan);

					$("#biaya_registrasi").val(formatNumber(data.biaya_registrasi));
					$("#biaya_pemasangan").val(formatNumber(data.biaya_pemasangan));
					// $("#harga_bulan_pertama").val(data.harga + data.biaya_registrasi);
					$("#min_berlangganan").val(data.minimal_langganan);
					// parent.find(".pemasangan").val(data.biaya_pemasangan);
					// parent.find(".harga-bulanan").val(data.harga);
					biaya_satuan_tanpa_langganan = data.biaya_satuan_tanpa_langganan;
					biaya_satuan_langganan = data.biaya_satuan_langganan;
					minimal_langganan = data.minimal_langganan;

					if (diff < data.minimal_langganan) {
						$("#harga_satuan").val(formatNumber(data.biaya_satuan_tanpa_langganan));
						$("#harga_pokok").val(formatNumber(data.biaya_satuan_tanpa_langganan));
						$("#total_ppn").val(formatNumber(data.ppn_biaya_satuan_tanpa_langganan +
							data.ppn_biaya_registrasi +
							data.ppn_biaya_pemasangan));
						$("#total_tagihan").val(formatNumber(
							data.biaya_registrasi +
							data.biaya_pemasangan +
							data.biaya_satuan_tanpa_langganan +
							data.ppn_biaya_satuan_tanpa_langganan +
							data.ppn_biaya_registrasi +
							data.ppn_biaya_pemasangan));
						$("#status_berlangganan").val('Tidak');
					} else {
						$("#harga_satuan").val(formatNumber(data.biaya_satuan_langganan));
						$("#harga_pokok").val(formatNumber(data.biaya_satuan_langganan));
						$("#total_ppn").val(formatNumber(data.ppn_biaya_satuan_langganan +
							data.ppn_biaya_registrasi +
							data.ppn_biaya_pemasangan));
						$("#total_tagihan").val(formatNumber(
							data.biaya_registrasi +
							data.biaya_pemasangan +
							data.biaya_satuan_tanpa_langganan +
							data.ppn_biaya_satuan_langganan +
							data.ppn_biaya_registrasi +
							data.ppn_biaya_pemasangan));
						$("#status_berlangganan").val('Ya');

					}
					$("#periode-awal").attr('readonly', false);
					$("#periode-akhir").attr('readonly', false);
					change_nilai_pokok();
				}
			});
		})
		$("body").on('change', '.paket-service', function() {
			disabled_paket_service();
			console.log($(this));
			var index = $(this).attr('id');
			index = parseInt(index.substr(index.indexOf('-') + 1));
			$(".paket_service_form-" + index).attr('disabled', false);
		});

		$(document).on('dp.change', ".hitung-periode", function() {
			url = '<?= site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_info_paket_service';
			var parent = $(this).parents(".service_form");
			var id = parent.find(".paket-service").val();
			if (dtp.eq(0).val().length == 10) {
				d1 = $("#periode-awal").val().substr(0, 2);
				m1 = $("#periode-awal").val().substr(3, 2);
				y1 = $("#periode-awal").val().substr(6, 4);
				d2 = $("#periode-akhir").val().substr(0, 2);
				m2 = $("#periode-akhir").val().substr(3, 2);
				y2 = $("#periode-akhir").val().substr(6, 4);
				var date1 = new Date(y1, m1, d1);
				var date2 = new Date(y2, m2, d2);
				var diff = DateDiff.inDays(date1, date2);
			} else if (dtp.eq(0).val().length == 7) {
				m1 = $("#periode-awal").val().substr(0, 2);
				y1 = $("#periode-awal").val().substr(3, 4);
				m2 = $("#periode-akhir").val().substr(0, 2);
				y2 = $("#periode-akhir").val().substr(3, 4);
				var date1 = new Date(y1, m1, 01);
				var date2 = new Date(y2, m2, 01);
				var diff = DateDiff.inMonths(date1, date2);
			} else if (dtp.eq(0).val().length == 4) {
				y1 = $("#periode-awal").val().substr(0, 4);
				y2 = $("#periode-akhir").val().substr(0, 4);
				var date1 = new Date(y1, 01, 01);
				var date2 = new Date(y2, 01, 01);
				var diff = DateDiff.inYears(date1, date2);
			}
			$("#jumlah_periode").val(diff + 1);
			console.log(date1);
			console.log(date2);
			console.log(diff);
			change_nilai_pokok();
			return 0;
			// minimal_berlangganan = parent.find(".minimal-langganan").val(data.minimal_langganan);
			$.ajax({
				type: "post",
				url: url,
				data: {
					id: id
				},
				dataType: "json",
				success: function(data) {
					console.log(data);
					hasil = diffDate(m1, y1, m2, y2);
					parent.find(".jumlah-periode").val(hasil ? hasil + 1 : 1);
					if (parent.find(".jumlah-periode").val() < parent.find(".minimal-langganan").val()) {
						console.log('a');
						if (parent.find(".jumlah-periode").val() == 1) {
							parent.find(".status-berlangganan").val('Non Berlangganan');
							parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
							parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
							parent.find(".harga-bulanan").val('');

							harga_total = (parseInt(parent.find(".jumlah-satuan").val() ? parent.find(".jumlah-satuan").val() : 0) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt($(".harga-registrasi").val()));
							parent.find(".total").val(harga_total);
						} else {
							parent.find(".status-berlangganan").val('Non Berlangganan');
							parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
							parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
							parent.find(".harga-bulanan").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
							harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val())));
							parent.find(".total").val(harga_total);
						}
					} else if (parent.find(".jumlah-periode").val() >= parent.find(".minimal-langganan").val()) {
						console.log('b');
						parent.find(".status-berlangganan").val('Berlangganan');
						parent.find(".harga-satuan").val(data.harga);
						parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
						parent.find(".harga-bulanan").val(parseInt(parent.find(".harga-satuan").val()));
						harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val()))) + (parseInt($(".harga-registrasi").val()));
						parent.find(".total").val(harga_total);
					}

					// parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
					// harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val()))) + (parseInt($(".harga-registrasi").val()));
					// parent.find(".total").val(harga_total);

				}
			});



		});




		$("#berlangganan_aktif").change(function(data) {
			if ($("#berlangganan_aktif").is(':checked')) {
				$("#berlangganan").attr('disabled', false);

			} else {
				$("#berlangganan").attr('disabled', true);
				$("#berlangganan").val('0');
			}
		});




		// $("#btn-delete-service").click(function() {
		$(document).on("click", ".btn-delete-service", function() {
			var parent = $(this).parents(".form_untuk_service");
			parent.remove();
			i = 0;
			$(".form_untuk_service").each(function() {
				i++;
			});
			// console.log('delete-service');
			// no_service--;
			// var numItems = $('.no').length;
			// console.log(numItems);
			// $(this).parent().parent().remove();
		});

		$(document).on("change", ".jumlah-periode", function() {
			console.log($(this));
			url = '<?= site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_info_paket_service';
			var parent = $(this).parents(".service_form");
			var id = parent.find(".paket-service").val();

			$.ajax({
				type: "post",
				url: url,
				data: {
					id: id
				},
				dataType: "json",
				success: function(data) {
					console.log($(this).val());
					// console.log(data.biaya_satuan_tanpa_langganan);
					// console.log(data.harga);
				}
			});
		});

		// $(document).on("change", "#pilih_paket_service", function() {
		// 	url = '<?= site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_info_paket_service';
		// 	var thisRow = $(this);
		// 	var id = thisRow.val();

		// 	$.ajax({
		// 		type: "post",
		// 		url: url,
		// 		data: {
		// 			id: id
		// 		},
		// 		dataType: "json",
		// 		success: function(data) {
		// 			// console.log(data);
		// 			// console.log(thisRow);
		// 			// parent.find(".jumlah-satuan").val(1);
		// 			$("#satuan").val(data.satuan);
		// 			$("#harga_satuan").val(data.harga);
		// 			$("#harga_registrasi").val(data.biaya_registrasi);
		// 			// $("#harga_bulan_pertama").val(data.harga + data.biaya_registrasi);
		// 			$("#minimal_langganan").val(data.minimal_langganan);
		// 			// parent.find(".pemasangan").val(data.biaya_pemasangan);
		// 			// parent.find(".harga-bulanan").val(data.harga);
		// 			$(".total").val(data.harga + data.biaya_registrasi);
		// 		}
		// 	});
		// });

		// $(document).on('change', ".biaya-pemasangan-aktif", function() {
		// 	url = '<?= site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_info_paketservice';
		// 	var parent = $(this).parents(".service_form");
		// 	var id = parent.find(".paket-service").val();
		// 	console.log(id);
		// 	$.ajax({
		// 		type: "post",
		// 		url: url,
		// 		data: {
		// 			id: id
		// 		},
		// 		dataType: "json",
		// 		success: function(data) {
		// 			console.log(data.biaya_pemasangan);
		// 			if (parent.find(".biaya-pemasangan-aktif").is(':checked')) {
		// 				parent.find(".biaya-pemasangan-aktif").val(data.biaya_pemasangan);
		// 				parent.find(".jumlah-periode").val(hasil ? hasil + 1 : 1);
		// 				if (parent.find(".jumlah-periode").val() < parent.find(".minimal-langganan").val()) {
		// 					if (parent.find(".jumlah-periode").val() == 1) {
		// 						parent.find(".status-berlangganan").val('Non Berlangganan');
		// 						parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
		// 						parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())) +
		// 							(parseInt(parent.find(".biaya-pemasangan-aktif").val())));
		// 						harga_total = (parseInt(parent.find(".jumlah-satuan").val() ? parent.find(".jumlah-satuan").val() : 0) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt($(".harga-registrasi").val())) +
		// 							(parseInt(parent.find(".biaya-pemasangan-aktif").val()));
		// 						parent.find(".total").val(harga_total);
		// 					} else {
		// 						parent.find(".status-berlangganan").val('Non Berlangganan');
		// 						parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
		// 						parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())) +
		// 							(parseInt(parent.find(".biaya-pemasangan-aktif").val())));
		// 						parent.find(".harga-bulanan").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
		// 						harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val()))) + (parseInt(parent.find(".biaya-pemasangan-aktif").val()));
		// 						parent.find(".total").val(harga_total);
		// 					}
		// 				} else if (parent.find(".jumlah-periode").val() >= parent.find(".minimal-langganan").val()) {
		// 					parent.find(".status-berlangganan").val('Berlangganan');
		// 					parent.find(".harga-satuan").val(data.harga);
		// 					parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())) + (parseInt(parent.find(".biaya-pemasangan-aktif").val())));
		// 					parent.find(".harga-bulanan").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
		// 					harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val()))) + (parseInt(parent.find(".biaya-pemasangan-aktif").val()));
		// 					parent.find(".total").val(harga_total);
		// 				}
		// 				// parent.find(".biaya-pemasangan").attr('disabled', false);
		// 			} else {
		// 				// parent.find(".biaya-pemasangan-aktif").attr('disabled', true);
		// 				parent.find(".biaya-pemasangan-aktif").val('0');
		// 				parent.find(".jumlah-periode").val(hasil ? hasil + 1 : 1);
		// 				if (parent.find(".jumlah-periode").val() < parent.find(".minimal-langganan")) {
		// 					parent.find(".status-berlangganan").val('Non Berlangganan')
		// 					parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
		// 					parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
		// 					harga_total = (parseInt(parent.find(".jumlah-satuan").val() ? parent.find(".jumlah-satuan").val() : 0) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt($(".harga-registrasi").val()));
		// 					parent.find(".total").val(harga_total);
		// 				} else if (parent.find(".jumlah-periode").val() >= parent.find(".minimal-langganan").val()) {
		// 					parent.find(".status-berlangganan").val('Berlangganan')
		// 					parent.find(".harga-satuan").val(data.harga);
		// 					parent.find(".harga-bulan-pertama").val((parseInt(parent.find(".harga-satuan").val())) + (parseInt($(".harga-registrasi").val())));
		// 					parent.find(".harga-bulanan").val((parseInt(parent.find(".harga-satuan").val())) * (parseInt(parent.find(".jumlah-satuan").val() ? parent.find(".jumlah-satuan").val() : 0)));
		// 					harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val()))) + (parseInt($(".harga-registrasi").val()));
		// 					parent.find(".total").val(harga_total);
		// 				}
		// 			}


		// 		}
		// 	});

		// });

		// $(document).on("keyup", ".jumlah-satuan", function() {
		// 	url = '<?= site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_info_paketservice';
		// 	var parent = $(this).parents(".service_form");
		// 	var id = parent.find(".paket-service").val();
		// 	console.log(id);
		// 	$.ajax({
		// 		type: "post",
		// 		url: url,
		// 		data: {
		// 			id: id
		// 		},
		// 		dataType: "json",
		// 		success: function(data) {
		// 			console.log(data.biaya_pemasangan);
		// 			if (parent.find(".biaya-pemasangan-aktif").is(':checked')) {
		// 				parent.find(".biaya-pemasangan-aktif").val(data.biaya_pemasangan);
		// 				parent.find(".jumlah-periode").val(hasil ? hasil + 1 : 1);
		// 				if (parent.find(".jumlah-periode").val() < parent.find(".minimal-langganan").val()) {
		// 					if (parent.find(".jumlah-periode").val() == 1) {
		// 						parent.find(".status-berlangganan").val('Non Berlangganan');
		// 						parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
		// 						harga_bulanan_pertama = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val())) + (parseInt(parent.find(".biaya-pemasangan-aktif").val()));
		// 						parent.find(".harga-bulan-pertama").val(harga_bulanan_pertama);
		// 						harga_total = (parseInt(parent.find(".harga-bulan-pertama").val()));
		// 						parent.find(".total").val(harga_total);
		// 					} else {
		// 						parent.find(".status-berlangganan").val('Non Berlangganan');
		// 						parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
		// 						harga_bulanan_pertama = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val())) + (parseInt(parent.find(".biaya-pemasangan-aktif").val()));
		// 						parent.find(".harga-bulan-pertama").val(harga_bulanan_pertama);
		// 						harga_bulanan = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val()));
		// 						parent.find(".harga-bulanan").val(harga_bulanan);
		// 						harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val()))) + (parseInt(parent.find(".biaya-pemasangan-aktif").val()));
		// 						parent.find(".total").val(harga_total);
		// 					}
		// 				} else if (parent.find(".jumlah-periode").val() >= parent.find(".minimal-langganan").val()) {
		// 					parent.find(".status-berlangganan").val('Berlangganan');
		// 					parent.find(".harga-satuan").val(data.harga);
		// 					harga_bulanan_pertama = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val())) + (parseInt(parent.find(".biaya-pemasangan-aktif").val()));
		// 					parent.find(".harga-bulan-pertama").val(harga_bulanan_pertama);
		// 					harga_bulanan = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val())));
		// 					parent.find(".harga-bulanan").val(harga_bulanan);
		// 					harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val()))) + (parseInt($(".harga-registrasi").val())) + (parseInt(parent.find(".biaya-pemasangan-aktif").val()));
		// 					parent.find(".total").val(harga_total);
		// 				}
		// 			} else {
		// 				parent.find(".biaya-pemasangan-aktif").attr('readonly', true);
		// 				parent.find(".biaya-pemasangan-aktif").val('0');
		// 				parent.find(".jumlah-periode").val(hasil ? hasil + 1 : 1);
		// 				if (parent.find(".jumlah-periode").val() < parent.find(".minimal-langganan").val()) {
		// 					if (parent.find(".jumlah-periode").val() == 1) {
		// 						parent.find(".status-berlangganan").val('Non Berlangganan');
		// 						parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
		// 						harga_bulanan_pertama = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val()));
		// 						parent.find(".harga-bulan-pertama").val(harga_bulanan_pertama);
		// 						harga_total = (parseInt(parent.find(".harga-bulan-pertama").val()));
		// 						parent.find(".total").val(harga_total);
		// 					} else {
		// 						parent.find(".status-berlangganan").val('Non Berlangganan');
		// 						parent.find(".harga-satuan").val(data.biaya_satuan_tanpa_langganan);
		// 						harga_bulanan_pertama = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val()));
		// 						parent.find(".harga-bulan-pertama").val(harga_bulanan_pertama);
		// 						harga_bulanan = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val()));
		// 						parent.find(".harga-bulanan").val(harga_bulanan);
		// 						harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val())));
		// 						parent.find(".total").val(harga_total);
		// 					}
		// 				} else if (parent.find(".jumlah-periode").val() >= parent.find(".minimal-langganan").val()) {
		// 					parent.find(".status-berlangganan").val('Berlangganan');
		// 					parent.find(".harga-satuan").val(data.harga);
		// 					harga_bulanan_pertama = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val()));
		// 					parent.find(".harga-bulan-pertama").val(harga_bulanan_pertama);
		// 					harga_bulanan = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val())));
		// 					parent.find(".harga-bulanan").val(harga_bulanan);
		// 					harga_total = (parseInt(parent.find(".harga-bulanan").val()) * (parseInt(parent.find(".jumlah-periode").val()))) + (parseInt($(".harga-registrasi").val()));
		// 					parent.find(".total").val(harga_total);
		// 				}

		// 			}
		// 		}

		// 	});
		//takut kepakai 
		// harga_bulanan_pertama = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val()))) + (parseInt(parent.find(".harga-registrasi").val()));
		// parent.find(".harga-bulan-pertama").val(harga_bulanan_pertama);


		// harga_bulanan = (parseInt(parent.find(".jumlah-satuan").val()) * (parseInt(parent.find(".harga-satuan").val())));
		// parent.find(".harga-bulanan").val(harga_bulanan);

		// harga_total = (parseInt(parent.find(".harga-bulan-pertama").val()) + (parseInt(parent.find(".harga-bulanan").val())));
		// parent.find(".total").val(harga_total);
		// });
		// $(".service_form").on("keyup", ".jumlah-satuan", function() {
		// 	console.log($(".jumlah-satuan").val());
		// 	harga_bulanan = (parseInt($(".jumlah-satuan").val()) * (parseInt($(".harga-satuan").val())));
		// 	$(".service_form").find(".harga-bulanan").val(harga_bulanan);
		// });
		// $(".service_form").on("keyup", ".jumlah-satuan", function() {
		// 	console.log($(".jumlah-satuan").val());

		// });
		$("#pilih_paket_service").select2({
			placeholder: "-- Pilih Paket Service --",
			allowClear: true
		});
		$("#pilih_unit").change(function() {
			$("#submit").removeAttr("disabled");
			$("#pilih_paket").removeAttr("disabled");
			$("#pilih_paket").trigger("change");

			// $("#jenis_pemasangan").removeAttr("disabled");
			$("#tanggal_document").removeAttr("disabled");
			$("#tanggal_rencana_survei").removeAttr("disabled");
			$("#tanggal_pemasangan_mulai").removeAttr("disabled");
			$("#jenis_bayar").removeAttr("disabled");
			$("#jumlah_hari_aktifasi").removeAttr("disabled");
			$("#tanggal_aktifasi").removeAttr("disabled");
			$("#keterangan").removeAttr("disabled");
			$("#dokumen").removeAttr("disabled");
			$("#jumlah_hari_aktifasi").removeAttr("disabled");
			var pilih_unit = $("#pilih_unit").val();
			if (pilih_unit != 'non_unit') {
				id = $("#pilih_unit").val();
				var pilih_unit = $("#pilih_unit").val();
				$.ajax({
					type: "post",
					url: '<?= site_url(); ?>/transaksi_lain/p_registrasi_tvi/lihat_unit',
					data: {
						pilih_unit: id
					},
					dataType: "json",
					success: function(data) {
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
						$("#nomor_va").val('0');
						$("#nomor_telepon").val(data.customer_homephone);
						$("#nomor_handphone").val(data.customer_mobilephone);
						$("#email").val(data.customer_email);
						$("#pilih_paket").trigger("change");

					}
				})
			} else if (pilih_unit == 'non_unit') {


				$(".unit").hide();
				$(".non_unit").show();
				$(".two").show();
				$("#type_unit").val('non_unit');

			}

		});

	});
	$("#pilih_unit_virtual").change(function() {
		$("#submit").removeAttr("disabled");
		$("#pilih_paket").removeAttr("disabled");

		$("#tanggal_document").removeAttr("disabled");
		$("#tanggal_rencana_survei").removeAttr("disabled");
		$("#tanggal_pemasangan_mulai").removeAttr("disabled");
		$("#jenis_bayar").removeAttr("disabled");
		$("#jumlah_hari_aktifasi").removeAttr("disabled");
		$("#tanggal_aktifasi").removeAttr("disabled");
		$("#keterangan").removeAttr("disabled");
		$("#dokumen").removeAttr("disabled");
		$("#jumlah_hari_aktifasi").removeAttr("disabled");

		$.ajax({
			type: "post",
			url: '<?= site_url(); ?>/transaksi_lain/p_registrasi_tvi/get_ajax_pilih_unit_virtual',
			data: {
				unit_virtual_id: $("#pilih_unit_virtual").val()
			},
			dataType: "json",
			success: function(data) {
				$("#alamat_non_unit").val(data.alamat);
				$("#jenis_usaha_non_unit").val(data.jenis_usaha);
			}
		})
	});
	$("#pilih_customer").change(function() {

		$.ajax({
			type: "post",
			url: '<?= site_url(); ?>/transaksi_lain/p_registrasi_tvi/get_ajax_customer',
			data: {
				customer_id: $("#pilih_customer").val()
			},
			dataType: "json",
			success: function(data) {
				$("#customer_name").val(data.name);
				$("#nomor_telepon").val(data.homephone);
				$("#nomor_handphone").val(data.mobilephone);
				$("#email").val(data.email);
			}
		})
	});


	$("#paket").change(function() {
		url = '<?= site_url(); ?>/transaksi_lain/P_registrasi_layanan_lain/get_harga_paket';
		var id = $("#paket").val();
		// console.log(id);
		$("#tagihan_total").val("");
		$.ajax({
			type: "post",
			url: url,
			data: {
				id: id
			},
			dataType: "json",
			success: function(data) {
				// console.log(data);
				$("#tagihan_total").val(currency(data.harga));
			}
		});
	});
</script>