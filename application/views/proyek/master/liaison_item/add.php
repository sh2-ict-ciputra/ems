<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link href="<?=base_url()?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>
<style>
	/* The container */
.label_checkbox {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.label_checkbox input[type=checkbox] {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: -7px;
  left: 7px;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 5px;
}

/* On mouse-over, add a grey background color */
.label_checkbox:hover input[type=checkbox] ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.label_checkbox input[type=checkbox]:checked ~ .checkmark {
  background-color: #169F85;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.label_checkbox input[type=checkbox]:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.label_checkbox .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.history.back()">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.reload()">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/proyek/master/liaison_item/save">
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Liaison Officer<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select type="text" id="liaison_officer_id" required="required" class="select2 form-control col-md-7 col-xs-12"
				 name="liaison_officer_id" placeholder="-- Pilih Liaison officer --">
					<option selected disabled>-- Pilih Liaison Officer --</option>
					<?php
                        foreach($dataLiaison as $key => $v){
                            echo("<option value='$v->id'>$v->code - $v->name</option>");
                        }
                    ?>
				</select>
			</div>
		</div>

		<div class="clearfix"></div>


		<div class="col-md-6" style="margin-top:20px">
			<div class="col-md-12 x_title">
				<h2>Item Transaksi</h2>
				<div class="clearfix"></div>
			</div>

			<p>
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>No</th>
							<th>Kode</th>
							<th>Keterangan</th>
							<th>Range</th>
							<th>Harga</th>
							<th>Hapus</th>
							<th>Select</th>
						</tr>
					</thead>
					<tbody id="tbody_detail_item">
						<tr idForOutflow='1' id="srow1" class="active">
							<td>1</td>
							<td><input type="text" class="form-control" value="" name="item_kode[1]" placeholder="Masukkan Kode"></td>
							<td><input type="text" class="form-control" value="" name="item_keterangan[1]" placeholder="Masukkan Keterangan"></td>
							<td><input type="text" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"
								 name="item_range[1]" class="form-control"></td>
							<td><input type="text" class="form-control" value="" placeholder="Masukkan Harga" onkeydown="return numbersonly(this, event);"
								 onkeyup="javascript:tandaPemisahTitik(this);" name="item_harga[1]"></td>
							<td onclick="deleteRow($(this))">
								<a class="btn btn-danger" href="#" style="color:#3399FD;"><i class="fa fa-trash"></i></a>
							</td>
							<td><a class="btn btn-primary selectOT" onclick='selectOT($(this))'>OT</a></td>
						</tr>
					</tbody>
				</table>

				<button id='button_detail_item' type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add
					Item Transaksi</button>
			</p>
		</div>
		<div id="outflow">
			<div idFromItem="1" class="table_outflow col-md-6" style="margin-top:20px">
				<div class="col-md-12 x_title">
					<h2 class="outflow_title">Outflow Transaksi : Item No. 1</h2>
					<div class="clearfix"></div>
				</div>
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>Select</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Harga</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody id="tbody_outflow_transaksi">
					</tbody>
				</table>
			</div>

		</div>

		<div class="clearfix"></div>

		<div class="form-group">
			<div class="center-margin">
				<button class="btn btn-primary" type="reset">Reset</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>
	</form>
</div>
</div>





<script type="text/javascript">
	var tmp_outflow = "";

	function deleteRow(row) {
		console.log(row);
		row.parent().remove();
		$("div[idfromitem=" + row.parent().attr('idforoutflow') + "]").remove();
	};

	function selectOT(row) {
		$(".table_outflow").hide()
		$("div[idFromItem=" + row.parent().parent().attr('idforoutflow') + "]").show();
		$(".selectOT").parent().parent().removeClass('active')
		row.parent().parent().addClass('active');
	}

	function tableICheck() {
		$("input.flat").iCheck({
			checkboxClass: "icheckbox_flat-green",
			radioClass: "iradio_flat-green"
		})
	}
	$(function () {
		$(".select2").select2();
		$("#liaison_officer_id").change(function () {
			$("#tbody_outflow_transaksi").html('');
			$.ajax({
				type: "GET",
				data: {
					id: $(this).val()
				},
				url: "<?=site_url()?>/proyek/master/liaison_item/get_outflow",
				dataType: "json",
				success: function (data) {
					var row_outflow = 1;
					data.forEach(function (data) {
						var str =
							"<tr id='row" + row_outflow + "'>" +
							// "<td><input type='checkbox' class='checkbox big-checkbox' name='unit[]' value='"+data.id+"'></td>"+

							"<td><label class='label_checkbox'>" +
							"<input type='checkbox' name='outflow[1][]' value='" + data.id + "'>" +
							"<span class='checkmark'></span>" +
							"</label></td>" +
							"<td><input type='text' class='form-control' value='" + data.code +
							"'  		placeholder='Masukkan Kode'  readonly/></td>" +
							"<td><input type='text' class='form-control' value='" + data.name +
							"'  		placeholder='Masukkan Nama'  readonly/></td>" +
							"<td><input type='text' class='form-control' value='" + data.harga +
							"' 		placeholder='Masukkan Harga' readonly/></td>" +
							"<td><input type='text' class='form-control' value='" + data.description +
							"' 	placeholder='Masukkan Keterangan' readonly/></td>" +
							"</tr>";
						$("#tbody_outflow_transaksi").append(str);
						row_outflow++;
					});
					// tableICheck();
					if (tmp_outflow == "") {
						console.log(tmp_outflow);
						tmp_outflow = $("div[idFromItem=1]")[0].outerHTML;
					}


					// $("#blok").html("");
					// $("#blok").attr("disabled", false);
					// $("#blok").append("<option value='' disabled selected>-- Pilih Kawasan Dahulu --</option>");
					// $("#blok").append("<option value='all'>-- Semua Blok --</option>");
					// for (var i = 0; i < data.length; i++) {
					// 	$("#blok").append("<option value='" + data[i].id + "'>" + data[i].name + "</option>");
					// }
				}
			});

		});
		$("#button_detail_item").click(function () {
			if (!(parseInt($("tr[idForOutflow]").last().attr('idForOutflow')) + 1)) {
				idForOutflow = 1;
			} else {
				idForOutflow = (parseInt($("tr[idForOutflow]").last().attr('idForOutflow')) + 1);
			}
			if ($(".no").html()) {
				idf = parseInt($(".no").last().html()) + 1;
			} else {
				idf = 1;
			}
			var str = "<tr idForOutflow='" + idForOutflow + "'>" +
				// "<td class='no'>"+idf+"</td>"+
				"<td>" + idForOutflow + "</td>" +
				"<td><input type='text' class='form-control' value='' name='item_kode[" + idForOutflow +
				"]' placeholder='Masukkan Kode' /></td>" +
				"<td><input type='text' class='form-control' value='' name='item_keterangan[" + idForOutflow +
				"]' placeholder='Masukkan Keterangan' /></td>" +
				"<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' name='item_range[" +
				idForOutflow + "]'  class='form-control'/></td>" +
				"<td><input type='text' class='form-control' value='' placeholder='Masukkan Harga' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' name='item_harga[" +
				idForOutflow + "]'  /></td>" +
				"<td onclick='deleteRow($(this))'> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\"><i class='fa fa-trash'></i> </a></td>" +
				"<td><a class='btn btn-primary selectOT' onclick='selectOT($(this))'>OT</a></td>" +
				"</tr>";
			$("#tbody_detail_item").append(str);
			$("#outflow").append(tmp_outflow.replace(/outflow\[1\]\[\]/g, "outflow[" + idForOutflow + "][]"));
			$("div[idFromItem]").last().attr('idfromitem', idForOutflow);
			$("div[idFromItem]").last().hide();
			$(".outflow_title").last().html("Outflow Transaksi : Item No. " + idForOutflow);
		});
	});

</script>
