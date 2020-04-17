<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<!-- <link type="text/css" href="<?= base_url(); ?>DataTables/datatables.min.css" rel="stylesheet"/>
<link type="text/css" href="<?= base_url(); ?>DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link type="text/css" href="<?= base_url(); ?>DataTables/DataTables-1.10.18/css/jquery.dataTables.css" rel="stylesheet"/>
<link type="text/css" href="<?= base_url(); ?>DataTables/Buttons-1.5.6/css/buttons.dataTables.min.css" rel="stylesheet"/>
<link type="text/css" href="<?= base_url(); ?>DataTables/Buttons-1.5.6/css/buttons.dataTables.css" rel="stylesheet"/>
<link type="text/css" href="<?= base_url(); ?>DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css" rel="stylesheet"/>
<link type="text/css" href="<?= base_url(); ?>DataTables/Buttons-1.5.6/css/buttons.bootstrap.css" rel="stylesheet"/> -->
<!-- select2 -->
<link type="text/css" href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link type="text/css" href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>


<!-- <link type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet"> -->
<link type="text/css" href="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.dataTables.min.css" rel="stylesheet">
<link type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.0/css/fixedColumns.dataTables.min.css" rel="stylesheet">
<link type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
<!-- <link type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet"> -->
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

	.select2-container {
		width: 100% !important;
	}

	.btn:focus {
		outline: none;
	}

	#table-history-1>th {
		white-space: nowrap;
	}

	*/ #table-history-1>th>td {
		white-space: nowrap;
	}

	div.dataTables_wrapper {
		width: 99%;
		height: 100%;
		margin: 0 auto;
	}

	.DTFC_LeftBodyWrapper>.DTFC_LeftBodyLiner>.table {
		margin-top: -2.2px !important;
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
<div id="contentx" class="x_content" hidden>
	<br>
	<!-- <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/Transaksi/P_transaksi_generate_bill/save" autocomplete="off"> -->
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
		<div class="form-group" style="margin-top:40px">
			<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Blok</label>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<select name="blok" required="" id="blok" class="form-control select2" placeholder="-- Pilih Kawasan Dahulu --" disabled>
					<option value="" disabled selected>-- Pilih Kawasan Dahulu --</option>
					<option value="all">-- Semua Blok --</option>
				</select>
			</div>
		</div>

		<!-- <div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Jenis Service</label>
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<select id="jns_service" name="jns_service[]" class="form-control multipleSelect js-example-basic-multiple" multiple="multiple" placeholder="-- Masukkan Jenis Service --">
						<?php foreach ($service_jenis as $v) : ?>
							<option value='<?= $v->id ?>'><?= $v->name_default ?></option>";
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
					<a id="check-all-service" class="btn btn-primary col-md-12" onclick="check_all_service()">Semua</a>
				</div>
			</div> -->

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


		<!-- <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Cara Pembayaran</label>
				<div class="col-md-7 col-sm-7 col-xs-12">
					<select id="cara_bayar" name="cara_bayar[]" class="form-control multipleSelect js-example-basic-multiple" multiple="multiple" placeholder="-- Masukkan Cara Pembayaran --">
						<?php foreach ($cara_bayar as $v) : ?>
							<option value='<?= $v->id ?>'><?= $v->cara . '  ' . $v->bank_name ?></option>";
						<?php endforeach; ?>
						<option value='0'>Deposit</option>";
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
					<a id="check-all-service" class="btn btn-primary col-md-12" onclick="check_all_cara_bayar()">Semua</a>
				</div>

			</div> -->



	</div>
	<div class="col-lg-12 col-md-12 col-sm-12">

		<div class="form-group" style="margin-top:20px">
			<div class="center-margin">
				<!-- <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button> -->
				<a id="btn-load-unit" class="btn btn-primary">Load Unit</a>
				<a id="dlink" style="display:none;"></a>
				<input class="btn btn-primary" hidden type="button" onclick="toExcel()" value="Export to Excel">
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
		<div id="div_info" class="col-md-12 card-box table-responsive">
		</div>
		<div class="accordion" id="accordionExample" style="width:74vw">

			<div class="card">
				<div class="card-header" id="div-retribusi">
					<div class="mb-0" style="background-color: beige;border-radius: 10px;color: black;">
						<p class="mt-10" style="padding:10px">
							Retribusi : I.P.L (Iuran Perawatan Lingkungan) & Air
						</p>
					</div>
				</div>
				<div id="div-retribusi-child" class="collapse" style="margin-left:30px">
					<div class="card">
						<div class="card-header" id="div-retribusi-group">
							<div class="mb-0" style="background-color: beige;border-radius: 10px;color: black;">
								<p class="mt-10" style="padding:10px">
									Group
								</p>
							</div>
						</div>
						<div id="div-retribusi-group-child" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="padding-left:20px">
							<button id="btn-group-ipl-kawasan" type="button" class="btn btn-info btn-group-ipl" data-toggle="button" aria-pressed="false" autocomplete="off">
								Group Kawasan
							</button>
							<button id="btn-group-ipl-blok" type="button" class="btn btn-info btn-group-ipl" data-toggle="button" aria-pressed="false" autocomplete="off">
								Group Blok
							</button>
							<button id="btn-group-ipl-unit" type="button" class="btn btn-info btn-group-ipl" data-toggle="button" aria-pressed="false" autocomplete="off">
								Group Unit
							</button>
							<button id="btn-group-ipl-periode" type="button" class="btn btn-info btn-group-ipl" data-toggle="button" aria-pressed="false" autocomplete="off">
								Group Periode
							</button>
							<button id="btn-group-ipl-tglbayar" type="button" class="btn btn-info btn-group-ipl" data-toggle="button" aria-pressed="false" autocomplete="off">
								Group Tgl Bayar
							</button>
						</div>
					</div>
					<div class="card">
						<div class="card-header" id="div-retribusi-print">
							<div class="mb-0" style="background-color: beige;border-radius: 10px;color: black;">
								<p class="mt-10" style="padding:10px">
									Print
								</p>
							</div>
						</div>
						<div id="div-retribusi-print-child" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="padding-left:20px">
							<button id="btn-print-ipl-excel" type="button" class="btn btn-info btn-print-ipl col-md-1" data-toggle="button" aria-pressed="false" autocomplete="off">
								Excel
							</button>
							<form id="form-print-ipl-pdf" class="col-md-1" action="<?= site_url('Transaksi/P_history_pembayaranv2/pdf') ?>" method="POST">
								<input id="input-print-ipl-pdf" name='data' hidden>
								<button id="btn-print-ipl-pdf" class="btn btn-info btn-print-ipl col-md-12" type='button' type="button">
									PDF
								</button>
							</form>
						</div>
					</div>
					<div id="div_table_ipl" class="col-md-12 card-box table-responsive">
						<table id='table-history-1' class="table table-strip">
							<thead>
								<tr>
									<th>Kawasan</th>
									<th>Blok</th>
									<th>No. Unit</th>
									<th>No. Kwitansi</th>
									<th>Periode Tagihan</th>
									<th>Tgl Bayar IPL</th>
									<th>Nilai Tanah</th>
									<th>Nilai Bangunan</th>
									<th>Nilai Keamanan</th>
									<th>Nilai Kebersihan</th>
									<th>PPN IPL</th>
									<th>Disc IPL</th>
									<th>Pemutihan IPL</th>
									<th>T. Tagihan IPL</th>
									<th>Total Bayar IPL</th>
									<th>Tgl Bayar Air</th>
									<th>Meter Awal</th>
									<th>Meter Akhir</th>
									<th>Meter Pakai</th>
									<th>T.Tagihan Air</th>
									<th>Denda Air</th>
									<th>Disc Air</th>
									<th>Pemutihan Air</th>
									<th>T. Bayar Air</th>
									<th>T. Tagihan IPL & Air</th>
									<th>T. Bayar IPL & Air</th>
								</tr>
							</thead>
							<tbody id='tbody-history-1'>
							</tbody>
						</table>
					</div>
				</div>


			</div>
		</div>
	</div>
	<div id="div_rekap" class="col-md-12 card-box table-responsive">
	</div>
</div>
<!-- </form> -->
</div>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<script type="text/javascript" src="<?= base_url('vendors/jquery-excel/jquery.table2excel.min.js') ?>"></script>

<!-- <script type="text/javascript" src="<?= base_url('vendors/jquery-pdf/canvas.js') ?>"></script> -->
<!-- <script type="text/javascript" src="<?= base_url('vendors/jquery-pdf/html.js') ?>"></script> -->
<!-- <script type="text/javascript" src="<?= base_url('vendors/jquery-export/tableHTMLExport.js') ?>"></script> -->
<script src='<?= base_url('vendors/jspdf/dist/jspdf.debug.js') ?>'></script>
<script src='<?= base_url('vendors/jspdf/libs/html2pdf.js') ?>'></script>
<script type="text/javascript">
</script>

<script type="text/javascript">
	var tableToExcel = (function() {
		var uri = 'data:application/vnd.ms-excel;base64,',
			template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
			base64 = function(s) {
				return window.btoa(unescape(encodeURIComponent(s)))
			},
			format = function(s, c) {
				return s.replace(/{(\w+)}/g, function(m, p) {
					return c[p];
				})
			}
		return function(table, name) {
			if (!table.nodeType) table = document.getElementById(table)
			var ctx = {
				worksheet: name || 'Worksheet',
				table: table.innerHTML
			}
			window.location.href = uri + base64(format(template, ctx))
		}
	})();

	function Export() {
		html2canvas(document.getElementById('table-history-1'), {
			onrendered: function(canvas) {
				var data = canvas.toDataURL();
				var docDefinition = {
					content: [{
						image: data,
						width: 500
					}]
				};
				pdfMake.createPdf(docDefinition).download("Table.pdf");
			}
		});
	}

	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}

	function periode(e) {
		var tmp = e.val();
		console.log(tmp);
		tmp = new Date(tmp.substr(3, 4), tmp.substr(0, 2) - 1, 1);
		console.log(tmp);
		tmp.setMonth(tmp.getMonth() - 1);
		console.log(tmp);
		$("#periode-penggunaan-akhir").val(e.val());
		$("#periode-penggunaan-awal").val(("0" + (parseInt(tmp.getMonth()) + 1)).slice(-2) + "/" + tmp.getFullYear());
		console.log(tmp);
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

	var select2_jns_service = $("#jns_service").select2({
		placeholder: '-- Masukkan Pilihan --',
		tags: true,
		tokenSeparators: [',', ' ']
	});
	var select2_cara_bayar = $("#cara_bayar").select2({
		placeholder: '-- Masukkan Pilihan --',
		tags: true,
		tokenSeparators: [',', ' ']
	});

	$(function() {

		$(".btn-group").click(function() {
			$(".btn-group").removeClass("focus");
		});
		$("#div-retribusi").click(function() {
			$("#div-retribusi-child").toggle();
		})
		$("#div-retribusi-group").click(function() {
			$("#div-retribusi-group-child").toggle();
		})
		$("#div-retribusi-print").click(function() {
			$("#div-retribusi-print-child").toggle();
		})
		$(".btn-group-ipl").click(function() {
			var string = [];
			if ($(this).hasClass('btn-info')) {
				$(this).removeClass('btn-info');
				$(this).addClass('btn-primary');
			} else {
				$(this).removeClass('btn-primary');
				$(this).addClass('btn-info');
			}
			if ($("#btn-group-ipl-kawasan").hasClass("active") ^ ($("#btn-group-ipl-kawasan")[0] == $(this)[0]))
				string.push('0');
			if ($("#btn-group-ipl-blok").hasClass("active") ^ ($("#btn-group-ipl-blok")[0] == $(this)[0]))
				string.push('1');
			if ($("#btn-group-ipl-unit").hasClass("active") ^ ($("#btn-group-ipl-unit")[0] == $(this)[0]))
				string.push('2');
			if ($("#btn-group-ipl-periode").hasClass("active") ^ ($("#btn-group-ipl-periode")[0] == $(this)[0]))
				string.push('4');
			if ($("#btn-group-ipl-tglbayar").hasClass("active") ^ ($("#btn-group-ipl-tglbayar")[0] == $(this)[0]))
				string.push('5');

			// string = string.toString();
			console.log(string);
			$("#table-history-1").DataTable().destroy();
			table = $("#table-history-1").DataTable({
				paging: false,
				rowGroup: {
					dataSrc: string
				}
			});
			$.each(string, function(k, v) {
				column = table.column(v)
				column.visible(false)
			});
		})
		$("#btn-print-ipl-excel").click(function() {
			$("#table-history-1").table2excel({
				exclude: ".noExl",
				name: "Worksheet",
				filename: "Laporan Pembayaran", //do not include extension
				fileext: ".xls" // file extension
			});
		});

		$("#btn-print-ipl-pdf").click(function() {
			$("#input-print-ipl-pdf").val($("#table-history-1")[0].outerHTML.replace(/"/g, "'"));
			$("#form-print-ipl-pdf").submit();

			// $.ajax({
			// 	type: "POST",
			// 	data: {
			// 		data: $("#table-history-1")[0].outerHTML.replace(/"/g, "'")
			// 	},
			// 	url: "<?= site_url() ?>/Transaksi/P_history_pembayaranv2/pdf",
			// 	dataType: "text",
			// 	success: function(data) {
			// 		if (data)
			// 			notif('Sukses', 'Data Berhasil di Tambah', 'success');
			// 		else
			// 			notif('Gagal', 'Data Gagal di Tambah', 'danger');
			// 	}
			// });
		})
		// $("#btn-group-ipl-Blok").click(function(){

		// })
		// $("#btn-group-ipl-Unit").click(function(){

		// })
		// $("#btn-group-ipl-Periode").click(function(){

		// })
		$("#contentx").show();
		$("#jns_service").select2({
			placeholder: '-- Masukkan Pilihan --',
			tags: true,
			tokenSeparators: [',', ' ']
		});

		function notif(title, text, type) {
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

		$("#periode").on('dp.change', function() {
			periode($("#periode"));
		});
		$("body").on("keyup", ".meter-akhir", function() {
			awal = unformatNumber($(this).parent().parent().children('.meter-awal').html());
			akhir = unformatNumber($(this).val());
			pakai = formatNumber(akhir - awal);
			$(this).parent().parent().children('.meter-pakai').html(pakai);

			if (akhir - awal < 0) {
				$(this).parent().parent().children().children('.save-row').addClass("disabled");
			} else {
				$(this).parent().parent().children().children('.save-row').removeClass("disabled");
				$(this).parent().parent().children('.meter-pakai').html(pakai);
			}
		});

		$('body').on("click", ".btn-detail", function() {

			var mulaiShow = 0;
			for (var i = 0; i < $(".tbody_history").children().length; i++) {
				if (mulaiShow == 1) {
					if ($(".tbody_history").children().eq(i).attr("class") != "btn-detail") {
						if ($(".tbody_history").children().eq(i).attr("hidden"))
							$(".tbody_history").children().eq(i).attr("hidden", false);
						else
							$(".tbody_history").children().eq(i).attr("hidden", true);
					} else {
						break;
					}
				}
				if ($(this)[0] == $(".tbody_history").children().eq(i)[0]) {
					if ($(".tbody_history").children().eq(i).children().eq(0).html() == "+")
						$(".tbody_history").children().eq(i).children().eq(0).html("-");
					else
						$(".tbody_history").children().eq(i).children().eq(0).html("+");

					mulaiShow = 1;
				}
			}
		});
		$("#table-history-1").DataTable();
		$("#btn-load-unit").click(function() {
			tmp_jns_service = 1;
			tmp_cara_bayar = 18;
			i = 0;
			$.ajax({
				type: "GET",
				data: {
					kawasan: $("#kawasan").val(),
					blok: $("#blok").val(),
					periode_awal: $("#periode-awal").val(),
					periode_akhir: $("#periode-akhir").val(),
					jns_service: tmp_jns_service,
					cara_bayar: tmp_cara_bayar
				},
				url: "<?= site_url() ?>/Transaksi/P_history_pembayaranv2/ajax_get_all",
				dataType: "json",
				success: function(data) {
					i++;
					console.log("create table_history_" + data.jns_service);
					$("#table-history-1").DataTable().destroy();
					$("#tbody-history-1").html("");
					str = "";
					$.each(data, function(k, v) {
						str = str + "<tr>";
						str = str + "<td>" + v.kawasan + "</td>";
						str = str + "<td>" + v.blok + "</td>";
						str = str + "<td>" + v.no_unit + "</td>";
						str = str + "<td>" + v.no_kwitansi + "</td>";
						str = str + "<td>" + v.periode_tagihan + "</td>";
						str = str + "<td>" + v.tgl_bayar_ipl + "</td>";
						str = str + "<td>" + v.nilai_kavling + "</td>";
						str = str + "<td>" + v.nilai_bangunan + "</td>";
						str = str + "<td>" + v.nilai_keamanan + "</td>";
						str = str + "<td>" + v.nilai_kebersihan + "</td>";
						str = str + "<td>" + v.ppn + "</td>";
						str = str + "<td>" + 0 + "</td>";
						str = str + "<td>" + 0 + "</td>";
						str = str + "<td>" + v.tagihan_ipl + "</td>";
						str = str + "<td>" + v.bayar_ipl + "</td>";
						str = str + "<td>" + v.tgl_bayar_air + "</td>";
						str = str + "<td>" + v.meter_awal + "</td>";
						str = str + "<td>" + v.meter_akhir + "</td>";
						str = str + "<td>" + v.meter_pakai + "</td>";
						str = str + "<td>" + v.tagihan_air + "</td>";
						str = str + "<td>" + v.denda_air + "</td>";
						str = str + "<td>" + 0 + "</td>";
						str = str + "<td>" + 0 + "</td>";
						str = str + "<td>" + v.bayar_air + "</td>";
						str = str + "<td>" + v.tagihan_ipl_air + "</td>";
						str = str + "<td>" + v.bayar_ipl_air + "</td>";
						str = str + "</tr>";
					})
					$("#tbody-history-1").append(str);

				},
				complete: function(data) {
					$("#table-history-1").DataTable().destroy();
					table = $("#table-history-1").DataTable({
						dom: 'Bfrtip',
						paging: false,
						// scrollX: true,
						// scrollY:        '50vh',
						// scrollCollapse: true,
						// fixedColumns:   {
						// 	leftColumns: 1
						// },
						// bAutoWidth : true,
						buttons: [
							// 'colvis',
							'copyHtml5',
							'excelHtml5',
							'csvHtml5',
							'pdfHtml5'
						]
					});
				}
			});

		});
		// $("#btn-load-unit").click(function() {
		// 	$("#div_info").empty();
		// 	$("#div_table").empty();

		// 	var str = 
		// 		"<table id='table_info' class='table table-striped table-bordered bulk_action' style='width:100%'>"+
		// 			"<thead id='thead_info'>"+
		// 			"</thead>"+
		// 			"<tbody id='tbody_info'>"+
		// 			"</tbody>"+
		// 		"</table>";
		// 	console.log("div_info "+str);
		// 	$("#div_info").append(str);
		// 	var str = "<tr>";
		// 	str	= str+"<th></th>";
		// 	$.each($("#jns_service option:selected"),function(k,a){
		// 		str = str +"<th style='width:10px'>"+a.innerHTML+"</th>";
		// 	})
		// 	str = str +"</tr>";
		// 	console.log("thead_info "+str);

		// 	$("#thead_info").append(str);

		// 	$.each($("#cara_bayar option:selected"),function(k,b){
		// 		var str = "";
		// 		str	= str+"<tr>"+"<td style='width:10px'>"+b.innerHTML+"</td>";
		// 		for(var x = 0;x < $("#jns_service option:selected").length;x++){
		// 			str = str +"<td cara_bayar="+$("#cara_bayar option:selected").eq(k).val()+" service="+$("#jns_service option:selected").eq(x).val()+"></td>";
		// 		}
		// 		str = str + "</tr>";
		// 		console.log("tbody_info "+str);

		// 		$("#tbody_info").append(str);
		// 	})
		// 	$("#export").show();
		// 	$("#export").append("<b>Appended text</b>");
		// 	if ($("#kawasan").val() == null) {
		// 		$('#kawasan').next().find('.select2-selection').addClass('has-error');
		// 	} else {
		// 		$('#kawasan').next().find('.select2-selection').removeClass('has-error');
		// 	}
		// 	if ($("#blok").val() == null) {
		// 		$('#blok').next().find('.select2-selection').addClass('has-error');
		// 	} else {
		// 		$('#blok').next().find('.select2-selection').removeClass('has-error');
		// 	}
		// 	if ($("#periode-akhir").val() == "") {
		// 		$('#periode-akhir').addClass('has-error');
		// 	} else {
		// 		$('#periode-akhir').removeClass('has-error');
		// 	}
		// 	if ($("#periode-awal").val() == "") {
		// 		$('#periode-awal').addClass('has-error');
		// 	} else {
		// 		$('#periode-awal').removeClass('has-error');
		// 	}
		// 	if ($("#cara_bayar").val() == "") {
		// 		$('#cara_bayar').addClass('has-error');
		// 	} else {
		// 		$('#cara_bayar').removeClass('has-error');
		// 	}
		// 	if ($("#jns_service").val() == "") {
		// 		$('#jns_service').addClass('has-error');
		// 	} else {
		// 		$('#jns_service').removeClass('has-error');
		// 	}
		//     if ($("#kawasan").val() != null && $("#blok").val() != null && $("#periode-awal").val() != null && $("#periode-akhir").val() != null && $("#cara_bayar").val() != null && $("#jns_service").val() != null) {

		// 		var i = 0;

		// 		for(var k = 0; k < $("#jns_service").val().length; k++){
		// 			console.log('k '+i);
		// 			var tmp_jns_service = $("#jns_service").val()[k];
		// 			var iterasiKe = 0;
		// 			for (var j = 0; j <  $("#cara_bayar").val().length; j++) {
		// 				console.log('a '+i);
		// 				var tmp_cara_bayar = $("#cara_bayar").val()[j];
		// 				$.ajax({
		// 					// async : false,
		// 					type: "GET",
		// 					data: {
		// 						kawasan: $("#kawasan").val(),
		// 						blok: $("#blok").val(),
		// 						periode_awal: $("#periode-awal").val(),
		// 						periode_akhir: $("#periode-akhir").val(),
		// 						jns_service: tmp_jns_service,
		// 						cara_bayar: tmp_cara_bayar
		// 					},
		// 					url: "<?= site_url() ?>/Transaksi/P_history_pembayaran/ajax_get_all",
		// 					dataType: "json",
		// 					success: function(data) {
		// 						i++;
		// 						console.log("create table_history_"+data.jns_service);
		// 						str="";
		// 						if(!$("#table_history_"+data.jns_service).html()){
		// 							for (var index = 0; index < data.header.length; index++) {
		// 								str	= str+"<th>"+data.header[index]+"</th>";
		// 							}
		// 							str = "<tr><td></td>"+str+"</tr>";
		// 							var str = 
		// 								"<table id='table_history_"+data.jns_service+"' class='table table-striped table-bordered bulk_action' style='width:100%'>"+	
		// 									"<thead id='thead_history_"+data.jns_service+"' class='thead_history'>"+
		// 									str+
		// 									"</thead>"+
		// 									"<tbody id='tbody_history_"+data.jns_service+"' class='tbody_history'>"+
		// 									"</tbody>"+
		// 									"<tfoot id='tfoot_history_"+data.jns_service+"' class='tfoot_history'>"+
		// 									"</tfoot>"+
		// 								"</table>";
		// 							console.log("yuhuu "+str);
		// 							$("#div_table").append(str);

		// 						}

		// 						str = '';	
		// 						$.each(data.footer[1],function(k,v){
		// 							str = str+ "<td>"+formatNumber(v)+"</td>";
		// 						})
		// 						$("[service="+data.jns_service+"][cara_bayar="+data.cara_bayar+"]").html(formatNumber(data.footer[1].nilai_bayar));
		// 						str = 	"<td style='font-weight:bold'>+</td>"+
		// 								"<td colspan='"+data.footer[0]+"' style='text-align:center'>"+data.judul_rekap+"</td>"+str;
		// 						str = "<tr class='btn-detail'>"+str+"</tr>";
		// 						$("#tbody_history_"+data.jns_service).append(str);

		// 						for (var index = 0; index < data.isi.length; index++) {
		// 							var str = "<tr hidden class='even pointer'><td></td>";
		// 							for (var index2 = 0; index2 < data.header.length; index2++) {
		// 								str = str +"<td>" + data.isi[index][Object.keys(data.isi[0])[index2]] + "</td>";

		// 							}
		// 							str = str + "</tr>";
		// 							$("#tbody_history_"+data.jns_service).append(str);


		// 						}

		// 					}
		// 				});
		// 			}
		// 		}

		// 		// var array1 = new Array();
		// 		// var array2 = new Array();
		// 		// var n = 2; //Total table
		// 		// for ( var x=1; x<=n; x++ ) {
		// 		// 	array1[x-1] = x;
		// 		// 	array2[x-1] = x + 'th';
		// 		// }

		// 	}

		// });

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
					if (data)
						notif('Sukses', 'Data Berhasil di Tambah', 'success');
					else
						notif('Gagal', 'Data Gagal di Tambah', 'danger');
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