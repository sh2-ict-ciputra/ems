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

<style>
	.invalid {
		background-color: lightpink;
	}

	.has-error {
		border: 1px solid rgb(185, 74, 72) !important;
	}
	a.disabled {
		pointer-events: none;
		cursor: default;
	}
</style>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?= site_url() ?>/P_transaksi_meter_air'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_transaksi_meter_air/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_conte	nt">

	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/Transaksi/P_transaksi_generate_bill/save" autocomplete="off">
		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Kawasan</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<select name="kawasan" required="" id="kawasan" class="form-control select2" placeholder="-- Pilih Kawasan --">
						<option value="" disabled selected>-- Pilih Kawasan --</option>
						<option value="all">-- Semua Kawasan --</option>
						<?php
						foreach ($kawasan as $v) {
							echo ("<option value='$v->id'>$v->code - $v->name</option>");
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Blok</label>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<select name="blok" required="" id="blok" class="form-control select2" placeholder="-- Pilih Kawasan Dahulu --" disabled>
						<option value="" disabled selected>-- Pilih Kawasan Dahulu --</option>
						<option value="all">-- Semua Blok --</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12" style="margin-top:20px">
			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12">Periode<br>(dd/mm/yyy)</label>
				<div class="col-lg-4 col-md-4 col-sm-5">
					<div class='input-group date datetimepicker_month'>
						<input id="periode-awal" type="text" class="form-control datetimepicker_month" placeholder="Periode Awal">
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
				<label class="control-label col-lg-1 col-md-1 col-sm-2" style="text-align:center">-</label>
				<div class="col-lg-4 col-md-4 col-sm-5">
					<div class='input-group date datetimepicker_month'>
						<input id="periode-akhir" type="text" class="form-control datetimepicker_month" placeholder="Periode Akhir">
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">

			<div class="form-group" style="margin-top:20px">
				<div class="center-margin">
					<!-- <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button> -->
					<a id="btn-load-unit" class="btn btn-primary">Load Unit</a>
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
				<table id="table_unit" class="table table-striped table-bordered bulk_action" style="width:100%">
					<thead>
						<tr>
                            <th>No</th>
                            <th>Kode Blok</th>
                            <th>Kode Project</th>
                            <th>No. Bukti</th>
                            <th>No. Kwitansi</th>
                            <th>Periode</th>
                            <th>Pakai m<sup>3</sup></th>
                            <th>Tanggal Bayar</th>
                            <th>Nilai Admin</th>
                            <th>Denda</th>
                            <th>Pemakaian</th>
                            <th>Disc</th>
                            <th>Total Bayar</th>
						</tr>
					</thead>
					<tbody id="tbody_unit">

					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>

<!-- jQuery -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
	var table_unit = $("#table_unit");
	var table_unit_dt = table_unit.dataTable({
		order: [
			[1, "asc"]
		],
		columnDefs: [{
			orderable: !1,
			targets: [1]
		}]
	});
	table_unit.on("draw.dt", function() {
		$("checkbox input").iCheck({
			checkboxClass: "icheckbox_flat-green"
		})
	})

	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}

	function periode(e){
		var tmp = e.val();
		console.log(tmp);
		tmp = new Date(tmp.substr(3,4),tmp.substr(0,2)-1,1);
		console.log(tmp);
		tmp.setMonth(tmp.getMonth()-1);
		console.log(tmp);
		$("#periode-penggunaan-akhir").val(e.val());
		$("#periode-penggunaan-awal").val(("0" + (parseInt(tmp.getMonth())+1)).slice(-2)+"/"+tmp.getFullYear());
		console.log(tmp);
	}
	function formatNumber(data){
		data = data+'';
		data = data.replace(/,/g,"");

		data = parseInt(data)?parseInt(data):0;
		data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
		return data;
		
	}
	function unformatNumber(data){
		data = data+'';
		return data.replace(/,/g,"");
	}
	$(".select2").select2();
	$(function() {
		function notif(title,text,type){
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
		var date = new Date();

		$("#periode").val(date.getMonth() + 1 + "/" + date.getFullYear());
		$("#periode").trigger("change");

		$("#periode").on('dp.change',function(){
			periode($("#periode"));
		});
		$("body").on("keyup",".meter-akhir",function(){
			awal = unformatNumber($(this).parent().parent().children('.meter-awal').html());
			akhir = unformatNumber($(this).val());
			pakai = formatNumber(akhir-awal);
			$(this).parent().parent().children('.meter-pakai').html(pakai);

			if(akhir-awal < 0){
				$(this).parent().parent().children().children('.save-row').addClass("disabled");
			}else{
				$(this).parent().parent().children().children('.save-row').removeClass("disabled");
				$(this).parent().parent().children('.meter-pakai').html(pakai);
			}

		});
		$("#btn-load-unit").click(function() {
			if ($("#kawasan").val() == null) {
				$('#kawasan').next().find('.select2-selection').addClass('has-error');
			} else {
				$('#kawasan').next().find('.select2-selection').removeClass('has-error');
			}
			if ($("#blok").val() == null) {
				$('#blok').next().find('.select2-selection').addClass('has-error');
			} else {
				$('#blok').next().find('.select2-selection').removeClass('has-error');
			}
			if ($("#periode-akhir").val() == "") {
				$('#periode-akhir').addClass('has-error');
			} else {
				$('#periode-akhir').removeClass('has-error');
			}
			if ($("#periode-awal").val() == "") {
				$('#periode-awal').addClass('has-error');
			} else {
				$('#periode-awal').removeClass('has-error');
			}
            if ($("#kawasan").val() != null && $("#blok").val() != null && $("#periode-awal").val() != null && $("#periode-akhir").val() != null) {
				$.ajax({
					type: "GET",
					data: {
						kawasan: $("#kawasan").val(),
						blok: $("#blok").val(),
						periode_awal: $("#periode-awal").val(),
						periode_akhir: $("#periode-akhir").val()
					},
					url: "<?= site_url() ?>/Transaksi/P_history_pembayaran/ajax_get_air",
					dataType: "json",
					success: function(data) {
						console.log(data);
						table_unit_dt.fnDestroy();
						$("#tbody_unit").html("");

						for (var i = 0; i < data.length; i++) {

							$("#tbody_unit").append(
								"<tr class='even pointer'>" +
								"<td>" + (i + 1) + "</td>" +
								"<td>" + data[i].kode_blok + "</td>" +
								"<td>" + data[i].kode_proyek + "</td>" +
								"<td>" + data[i].no_bukti + "</td>" +
								"<td>" + data[i].no_kwitansi + "</td>" +
								"<td>" + data[i].periode + "</td>" +
                                "<td>" + data[i].pakai_air + " m<sup>3</sup>" + "</td>" +
                                "<td>" + data[i].tgl_bayar + "</td>" +
                                "<td>" + data[i].nilai_admin + "</td>" +
                                "<td>" + data[i].denda + "</td>" +
                                "<td>" + data[i].pemakaian + "</td>" +
                                "<td>" + data[i].nilai_diskon + "</td>" +
                                "<td>" + data[i].total + "</td>" +
								"</tr>");
						}
						table_unit.dataTable({
							order: [
								[1, "asc"]
							]
						});
						for(var i = 0; i < $(".paginate_button")[$(".paginate_button").length-2].innerHTML ; i++){
							table_unit_dt.fnPageChange(i);
							if ($("#kawasan").val() != 'all')
								$(".table-kawasan").hide()
							else
								$(".table-kawasan").show()

							if ($("#blok").val() != 'all')
								$(".table-blok").hide()
							else
								$(".table-blok").show()						
						}
						table_unit_dt.fnPageChange("first");
						

					}
				});
			}

		});

		$("body").on("click", ".save-row", function() {
			console.log($(this));
			var meter = $(this).parent().parent().find('.meter-akhir').val();
			var periode = $(this).attr('periode');
			var unit_id = $(this).attr('unit_id');

			$.ajax({
				type: "GET",
				data: {
					meter: meter,
					periode: periode,
					unit_id: unit_id
				},
				url: "<?= site_url() ?>/Transaksi/P_transaksi_meter_air/ajax_save_meter",
				dataType: "json",
				success: function(data) {
					if(data)
						notif('Sukses','Data Berhasil di Tambah','success');
					else
						notif('Gagal','Data Gagal di Tambah','danger');
				}
			});
		})

		$('.datetimepicker_month').datetimepicker({
			viewMode: 'years',
			format: 'DD/MM/YYYY'
		});
		$('.datetimepicker_year').datetimepicker({
			format: 'YYYY'
		});
		$("#kawasan").change(function() {
			if ($("#kawasan").val() == null) {
				$('#kawasan').next().find('.select2-selection').addClass('has-error');
			} else {
				$('#kawasan').next().find('.select2-selection').removeClass('has-error');
			}
			$.ajax({
				type: "GET",
				data: {
					id: $(this).val()
				},
				url: "<?= site_url() ?>/Transaksi/P_transaksi_meter_air/ajax_get_blok",
				dataType: "json",
				success: function(data) {
					console.log(data);
					$("#blok").html("");
					$("#blok").attr("disabled", false);
					$("#blok").append("<option value='' disabled selected>-- Pilih Kawasan Dahulu --</option>");
					$("#blok").append("<option value='all'>-- Semua Blok --</option>");
					for (var i = 0; i < data.length; i++) {
						$("#blok").append("<option value='" + data[i].id + "'>" + data[i].name + "</option>");
					}
				}
			});
		});
		$("#blok").change(function() {
			if ($("#blok").val() == null) {
				$('#blok').next().find('.select2-selection').addClass('has-error');
			} else {
				$('#blok').next().find('.select2-selection').removeClass('has-error');
			}
		});
		$("#periode").on('dp.change', function(e) {
			console.log(e);
			if ($("#periode").val() == "") {
				$('#periode').addClass('has-error');
			} else {
				$('#periode').removeClass('has-error');
			}
		});
		$("#unit").change(function() {
			url = '<?= site_url(); ?>/P_transaksi_meter_air/getInfoUnit';
			var id = $("#unit").val();
			//console.log(this.value);
			$.ajax({
				type: "get",
				url: url,
				data: {
					id: id
				},
				dataType: "json",
				success: function(data) {
					$("#customer").val(data.customer);
					$("#barcode").val(data.barcode);
					$("#meter_awal").val(currency(data.meter));
					$("#meter_akhir").attr('disabled', false);
					$("#meter_akhir").attr('placeholder', '-- Masukkan Meter Akhir --');
				}
			});
		});
		$("#meter_akhir").keyup(function() {
			$("#pemakaian").val($("#meter_akhir").val().replace(/,/g, '') - $("#meter_awal").val().replace(/,/g, ''));
		});
	});
</script>