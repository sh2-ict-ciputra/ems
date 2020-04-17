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

			<div class="form-group">
				<label class="control-label col-lg-3 col-md-3 col-sm-12 col-xs-12">Jenis Service</label>
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<select id="jns_service" name="jns_service[]" class="form-control multipleSelect js-example-basic-multiple" multiple="multiple" placeholder="-- Masukkan Jenis Service --">
						<?php foreach ($service_jenis as $v) : ?>
							<option value='<?= $v->id ?>'><?=$v->name_default?></option>";
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
					<a id="check-all-service" class="btn btn-primary col-md-12" onclick="check_all_service()">Semua</a>
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


			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Cara Pembayaran</label>
				<div class="col-md-7 col-sm-7 col-xs-12">
					<select id="cara_bayar" name="cara_bayar[]" class="form-control multipleSelect js-example-basic-multiple" multiple="multiple" placeholder="-- Masukkan Cara Pembayaran --">
						<?php foreach ($cara_bayar as $v) : ?>
							<option value='<?= $v->id?>'><?= $v->cara.'  '.$v->bank_name ?></option>";
						<?php endforeach; ?>
						<option value='0'>Deposit</option>";
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
					<a id="check-all-service" class="btn btn-primary col-md-12" onclick="check_all_cara_bayar()">Semua</a>
				</div>

			</div>

			
			
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">

			<div class="form-group" style="margin-top:20px">
				<div class="center-margin">
					<!-- <button class="btn btn-primary" type="reset" style="margin-left: 110px">Reset</button> -->
					<a id="btn-load-unit" class="btn btn-primary">Load Unit</a>
					<a id="dlink"  style="display:none;"></a>
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
			<div id="div_table" class="col-md-12 card-box table-responsive">
			</div>
			<div id="div_rekap" class="col-md-12 card-box table-responsive">
			</div>
		</div>
	</form>
</div>

<!-- jQuery -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<!-- <script type="text/javascript" src="<?= base_url(); ?>DataTables/datatables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/DataTables-1.10.18/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/Buttons-1.5.6/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/JSZip-2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>DataTables/Buttons-1.5.6/js/buttons.print.min.js"></script> -->

<script type="text/javascript">

	var table_history = $("#table_history");
	var table_history_dt = table_history.dataTable({
		order: [
			[1, "asc"]
		],
		columnDefs: [{
			orderable: !1,
			targets: [1]
		}]
	});
	table_history.on("draw.dt", function() {
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
	function toExcel() {

		if ("ActiveXObject" in window) {
			alert("This is Internet Explorer!");
		} else {
			var cache = {};
			this.tmpl = function tmpl(str, data) {
				var fn = !/\W/.test(str) ? cache[str] = cache[str] || tmpl(document.getElementById(str).innerHTML) :
				new Function("obj",
							"var p=[],print=function(){p.push.apply(p,arguments);};" +
							"with(obj){p.push('" +
							str.replace(/[\r\t\n]/g, " ")
							.split("{{").join("\t")
							.replace(/((^|}})[^\t]*)'/g, "$1\r")
							.replace(/\t=(.*?)}}/g, "',$1,'")
							.split("\t").join("');")
							.split("}}").join("p.push('")
							.split("\r").join("\\'") + "');}return p.join('');");
				return data ? fn(data) : fn;
			};
			var tableToExcel = (function () {
				var uri = 'data:application/vnd.ms-excel;base64,',
					template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{{=worksheet}}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>{{for(var i=0; i<tables.length;i++){ }}<table>{{=tables[i]}}</table>{{ } }}</body></html>',
					base64 = function (s) {
						return window.btoa(unescape(encodeURIComponent(s)));
					},
					format = function (s, c) {
						return s.replace(/{(\w+)}/g, function (m, p) {
							return c[p];
						});
					};
				return function (tableList, name) {
					if (!tableList.length > 0 && !tableList[0].nodeType) table = document.getElementById(table);
					var tables = [];
					for (var i = 0; i < tableList.length; i++) {
						tables.push(tableList[i].innerHTML);
					}
					var ctx = {
						worksheet: name || 'Worksheet',
						tables: tables
					};
					window.location.href = uri + base64(tmpl(template, ctx));
				};
			})();
			tableToExcel(document.getElementsByTagName("table"), "one");
		}
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
	function check_all_service(){
		options = select2_jns_service[0].options;
		for (var i = 0;  i < options.length;  i++)
			options[i].selected = true;
		select2_jns_service.trigger("change");
	}
	function check_all_cara_bayar(){
		options = select2_cara_bayar[0].options;
		for (var i = 0;  i < options.length;  i++)
			options[i].selected = true;
			select2_cara_bayar.trigger("change");
	}
	$(function() {
		$("#contentx").show();
		$("#jns_service").select2({
			placeholder: '-- Masukkan Pilihan --',
			tags: true,
			tokenSeparators: [',', ' ']
		});
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

		$('body').on("click",".btn-detail",function(){		

			var mulaiShow = 0;
			for (var i = 0; i < $(".tbody_history").children().length; i++) {
				if(mulaiShow == 1){
					if($(".tbody_history").children().eq(i).attr("class") != "btn-detail"){
						if($(".tbody_history").children().eq(i).attr("hidden"))
							$(".tbody_history").children().eq(i).attr("hidden",false);
						else
							$(".tbody_history").children().eq(i).attr("hidden",true);
					}
					else{
						break;
					}
				}
				if($(this)[0] == $(".tbody_history").children().eq(i)[0]){
					if($(".tbody_history").children().eq(i).children().eq(0).html()=="+")
						$(".tbody_history").children().eq(i).children().eq(0).html("-");
					else
						$(".tbody_history").children().eq(i).children().eq(0).html("+");

					mulaiShow = 1;
				}
			}
		});
			
		$("#btn-load-unit").click(function() {
			$("#div_info").empty();
			$("#div_table").empty();

			var str = 
				"<table id='table_info' class='table table-striped table-bordered bulk_action' style='width:100%'>"+
					"<thead id='thead_info'>"+
					"</thead>"+
					"<tbody id='tbody_info'>"+
					"</tbody>"+
				"</table>";
			console.log("div_info "+str);
			$("#div_info").append(str);
			var str = "<tr>";
			str	= str+"<th></th>";
			$.each($("#jns_service option:selected"),function(k,a){
				str = str +"<th style='width:10px'>"+a.innerHTML+"</th>";
			})
			str = str +"</tr>";
			console.log("thead_info "+str);

			$("#thead_info").append(str);
			
			$.each($("#cara_bayar option:selected"),function(k,b){
				var str = "";
				str	= str+"<tr>"+"<td style='width:10px'>"+b.innerHTML+"</td>";
				for(var x = 0;x < $("#jns_service option:selected").length;x++){
					str = str +"<td cara_bayar="+$("#cara_bayar option:selected").eq(k).val()+" service="+$("#jns_service option:selected").eq(x).val()+"></td>";
				}
				str = str + "</tr>";
				console.log("tbody_info "+str);

				$("#tbody_info").append(str);
			})
			$("#export").show();
			$("#export").append("<b>Appended text</b>");
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
			if ($("#cara_bayar").val() == "") {
				$('#cara_bayar').addClass('has-error');
			} else {
				$('#cara_bayar').removeClass('has-error');
			}
			if ($("#jns_service").val() == "") {
				$('#jns_service').addClass('has-error');
			} else {
				$('#jns_service').removeClass('has-error');
			}
            if ($("#kawasan").val() != null && $("#blok").val() != null && $("#periode-awal").val() != null && $("#periode-akhir").val() != null && $("#cara_bayar").val() != null && $("#jns_service").val() != null) {
				
				var i = 0;
			
				for(var k = 0; k < $("#jns_service").val().length; k++){
					console.log('k '+i);
					var tmp_jns_service = $("#jns_service").val()[k];
					var iterasiKe = 0;
					for (var j = 0; j <  $("#cara_bayar").val().length; j++) {
						console.log('a '+i);
						var tmp_cara_bayar = $("#cara_bayar").val()[j];
						$.ajax({
							// async : false,
							type: "GET",
							data: {
								kawasan: $("#kawasan").val(),
								blok: $("#blok").val(),
								periode_awal: $("#periode-awal").val(),
								periode_akhir: $("#periode-akhir").val(),
								jns_service: tmp_jns_service,
								cara_bayar: tmp_cara_bayar
							},
							url: "<?= site_url() ?>/Transaksi/P_history_pembayaran/ajax_get_all",
							dataType: "json",
							success: function(data) {
								i++;
								console.log("create table_history_"+data.jns_service);
								str="";
								if(!$("#table_history_"+data.jns_service).html()){
									for (var index = 0; index < data.header.length; index++) {
										str	= str+"<th>"+data.header[index]+"</th>";
									}
									str = "<tr><td></td>"+str+"</tr>";
									var str = 
										"<table id='table_history_"+data.jns_service+"' class='table table-striped table-bordered bulk_action' style='width:100%'>"+	
											"<thead id='thead_history_"+data.jns_service+"' class='thead_history'>"+
											str+
											"</thead>"+
											"<tbody id='tbody_history_"+data.jns_service+"' class='tbody_history'>"+
											"</tbody>"+
											"<tfoot id='tfoot_history_"+data.jns_service+"' class='tfoot_history'>"+
											"</tfoot>"+
										"</table>";
									console.log("yuhuu "+str);
									$("#div_table").append(str);
									
								}
								
								str = '';	
								$.each(data.footer[1],function(k,v){
									str = str+ "<td>"+formatNumber(v)+"</td>";
								})
								$("[service="+data.jns_service+"][cara_bayar="+data.cara_bayar+"]").html(data.footer[1].nilai_bayar);
								str = 	"<td>+</td>"+
										"<td colspan='"+data.footer[0]+"' style='text-align:center'>"+data.judul_rekap+"</td>"+str;
								str = "<tr class='btn-detail'>"+str+"</tr>";
								$("#tbody_history_"+data.jns_service).append(str);
								
								for (var index = 0; index < data.isi.length; index++) {
									var str = "<tr hidden class='even pointer'><td></td>";
									for (var index2 = 0; index2 < data.header.length; index2++) {
										str = str +"<td>" + data.isi[index][Object.keys(data.isi[0])[index2]] + "</td>";

									}
									str = str + "</tr>";
									$("#tbody_history_"+data.jns_service).append(str);


								}
								
							}
						});
					}
				}

				// var array1 = new Array();
				// var array2 = new Array();
				// var n = 2; //Total table
				// for ( var x=1; x<=n; x++ ) {
				// 	array1[x-1] = x;
				// 	array2[x-1] = x + 'th';
				// }

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