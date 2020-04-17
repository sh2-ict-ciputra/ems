<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/proyek/master/liaison_outflow/save">
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Nama Item Transaksi <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="nama_transaksi" name="nama_transaksi" required="required" class="form-control col-md-7 col-xs-12">
			</div>
		</div>
		<div class="form-group">
			<label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea id="keterangan" class="form-control col-md-7 col-xs-12" type="text" name="keterangan"></textarea>
			</div>
		</div>

		<div class="clearfix"></div>
		
		<div id="outflow">
			<div idFromItem="1" class="table_outflow col-md-12" style="margin-top:20px">
				<div class="col-md-12 x_title">
					<h2>Outflow Transaksi</h2>
					<div class="clearfix"></div>
				</div>
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>No</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Harga</th>
							<th>Keterangan</th>
							<th>Hapus</th>
						</tr>
					</thead>
					<tbody id="tbody_outflow_transaksi">
						<!--<tr id="srow2">-->
							<!-- <td><input type="text" class="form-control" value="" name="kode[]" placeholder="Masukkan Kode"></td>
							<td><input type="text" class="form-control" value="" placeholder="Masukkan Nama" name="nama[]"></td>
							<td><input type="text" name="harga[]" value="" placeholder="Masukkan Harga" onkeydown="return numbersonly(this, event);"
									onkeyup="javascript:tandaPemisahTitik(this);" class="form-control"></td>
							<td><input type="text" class="form-control" value="" name="keterangan_outflow[]" placeholder="Masukkan Keterangan"></td>
							<td class="delete" onclick="deleteRow($(this))"> <a class="btn btn-danger" style="color:#3399FD;"><i class="fa fa-trash"></i>
								</a></td> -->



					<!--	</tr>-->

					        	<td><input id="idf2" value="1" type="hidden" /></td>

					</tbody>
				</table>

				<button id='button_outflow_transaksi' type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>
					Add Outflow Transaksi</button>
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
	const outflow = $('.table_outflow');
	function deleteRow(row) {
		row.parent().remove();
	};
	function createOT(row){
		$(".createOT").parent().parent().removeClass('active')
		row.parent().parent().addClass('active');
	}
	$(function () {
		$("#button_outflow_transaksi").click(function () {
			if ($(".no2").html()) {
				idf2 = parseInt($(".no2").last().html()) + 1;
			} else {
				idf2 = 1;
			}
			var str = "<tr id='srow2" + idf2 + "'>" +
				 "<td class='no2'>"+ idf2 +"</td>"+
				"<td><input type='text' class='form-control' value='' name='kode[]' placeholder='Masukkan Kode' /></td>" +
				"<td><input type='text' class='form-control' value='' placeholder='Masukkan Nama' name='nama[]' placeholder='' /></td>" +
				"<td><input type='text' name='harga[]' value='' placeholder='Masukkan Harga' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control'/></td>" +
				"<td><input type='text' class='form-control' value='' name='keterangan_outflow[]' placeholder='Masukkan Keterangan' /></td>" +
				"<td class='delete' onclick='deleteRow($(this))'> <a class='btn btn-danger' style=\"color:#3399FD;\"><i class='fa fa-trash'></i> </a></td>" +
				"</tr>";
			$("#tbody_outflow_transaksi").append(str);
		});

	});

</script>
