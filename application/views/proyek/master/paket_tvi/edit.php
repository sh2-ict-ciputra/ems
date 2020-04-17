<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url(); ?>/P_master_paket_tvi'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url(); ?>/P_master_paket_tvi/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" class="form-horizontal form-label-left" method="post">
		<div class="x_content">
			<br />
			<div class="col-md-6 col-xs-12">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Pilih Paket</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select id="pilih_paket" class="form-control select2">
							<option value="">--Pilih Paket--</option>
							<option value="1">Internet</option>
							<option value="2">TV</option>
							<option value="3">TV dan Internet</option>
						</select>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-6 col-xs-12">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Group</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="group_tvi_id" id="group" disabled class="form-control select2 " >
							<option selected disabled>Pilih Group Tvi</option>
							<?php
								foreach($dataGroupTvi as $key => $v){
									echo("<option value='$v[id]'>$v[name]</option>");
								}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Paket</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" id="code" disabled required name="code" placeholder="Masukkan Kode Paket">
					</div>
                </div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Paket</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" id="name" disabled required name="name" placeholder="Masukkan Nama Paket">
					</div>
				</div>
				<div class="form-group bandwidth" hidden>
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Kecepatan Bandwidth (Kbps)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" disabled id="bandwidth" name="bandwidth" placeholder="Masukkan Kbps">
					</div>
				</div>
				<div class="form-group jml_channel" hidden>
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Channel</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" disabled name="jml_channel" id="jml_channel" readonly>
					</div>
				</div>
				<!-- <div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Prediksi</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" name="hrg_prediksi" id="hrg_prediksi" disabled placeholder="Masukkan Jumlah Channel">
					</div>
				</div> -->
				
				<div class="form-group" hidden>
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Harga Hpp (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency currency" required name="harga_hpp" placeholder="Masukkan Harga HPP">
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xs-12">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Pasang Baru (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" disabled id="biaya_pasang_baru" required name="biaya_pasang_baru" placeholder="Masukkan Biaya Pasang Baru">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Jual (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" disabled id="harga_jual" required name="harga_jual" placeholder="Masukkan Harga Jual">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Registrasi (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" value="0" disabled id="biaya_registrasi" class="form-control currency" required name="biaya_registrasi" placeholder="Masukkan Biaya Registrasi">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<textarea class="form-control" rows="3" id="description" disabled name="description" placeholder='Masukkan Keterangan'></textarea>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-12 col-xs-12">
				<div class="form-group nama_channel">
					<label class="control-label col-md-2 col-sm-2 col-xs-12" style="width: 12.3%">Nama Channel</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<select id="nama_channel" name="nama_channel[]" disabled class="form-control multipleSelect js-example-basic-multiple" multiple="multiple" style="width:105.5%">
							<option value=""></option>
							<?php foreach($dataChannel as $c){
								echo "<option value='$c->id'>$c->name</option>";
							}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<hr>
			<div class="col-md-12 col-xs-12">
				<table class="table table-responsive">
					<thead>
						<tr>
							<th class="col-md-2">Item</th>
							<th class="col-md-1">Volume</th>
							<th class="col-md-2">Satuan</th>
							<th class="col-md-2">Harga Satuan</th>
							<th class="col-md-3">Jenis</th>
							<th class="col-md-2">Total</th>
							<th>Hapus</th>
						</tr>
					</thead>
					<tbody id="tbody">
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><center>TOTAL</center></td>
							<td><input name="hrg_prediksi" id="hrg_prediksi" disabled required="" class="form-control col-md-1 col-xs-12 currency"></td>
						</tr>
						<tr class="isi">
							<td>
								<select class="nama_item form-control col-md-1 col-xs-12 select2" value="0" id="nama_item" name="nama_item[]">
									<option value="">Pilih Item</option>
								</select>
							</td>
							<td>
								<input id="volume-1" name="volume[]" required="" value="" class="volume form-control col-md-1 col-xs-12 currency">
							</td>
							<td>
								<input id="satuan-1" name="satuan[]" readonly required="" class="satuan form-control col-md-1 col-xs-12">
							</td>
							<td>
								<input id="harga_satuan-1" name="harga_satuan[]" readonly required="" class="harga_satuan form-control col-md-1 col-xs-12">
							</td>
							<td>
								<select class="status_item form-control col-md-1 col-xs-12 select2" value="0" id="status_item" name="status_item[]">
									<option value="">Pilih Item</option>
									<option value="1">Dipinjamkan</option>
									<option value="2">Habis Pakai</option>
								</select>
							</td>    
							<td>
								<input id="total-1" name="total[]" readonly required="" value="" class="form-control col-md-1 col-xs-12 currency">
							</td>
							<td>
							<a class='delete btn btn-danger' href='#'><i class='fa fa-trash'></i> </a>
							</td>
						</tr>              
					</tbody>
				</table>
				<button type="button" id='btn-add-paket' class="btn btn-danger pull-right" ><i class="fa fa-plus"></i> Add Item</button>
			</div>
			
			<div class="col-md-12">
				<div class="form-group">
					<div class="center-margin">
						<button class="btn btn-primary" type="reset">Reset</button>
						<button type="submit" id="submit" class="btn btn-success">Submit</button>
					</div>
				</div>
			</div>
	</form>
</div>

<!-- jQuery -->
<script>
	$(".nama_channel").hide();
	var komponen = $(".isi")[0].outerHTML;
	$('.js-example-basic-multiple').select2({
		placeholder: '-- Masukkan Pilihan --',
		tags: true,
		tokenSeparators: [',', ' ']
	});
    $("#pilih_paket").change(function(){
		$("#code").removeAttr("disabled");
		$("#group").removeAttr("disabled");
		$("#name").removeAttr("disabled");
		$("#harga_jual").removeAttr("disabled");
		$("#biaya_pasang_baru").removeAttr("disabled");
		$("#biaya_registrasi").removeAttr("disabled");
		$("#description").removeAttr("disabled");
		var pilih_paket = $("#pilih_paket").val();
		if(pilih_paket == '1'){
			$(".bandwidth").show();
			$("#bandwidth").removeAttr("disabled");
			$(".jml_channel").hide();
			$(".nama_channel").hide();
			$("#jml_channel").attr("disabled", "")
			$("#nama_channel").attr("disabled", "")

			url = '<?=site_url(); ?>/P_master_paket_tvi/ajax_get_item';
			$.ajax({
				type: "post",
				url: url,
				data: {
					is_channel : $("#pilih_paket").val()
				},
				dataType: "json",
				success: function (data) {
					console.log(data);

					$("#nama_item")[0].innerHTML = "";

					$("#nama_item").append("<option value='' >Pilih Item</option>");
					$.each(data, function (key, val) {
						$("#nama_item").append("<option satuan='"+val.satuan+"' harga_satuan='"+val.harga_satuan+"' value='" + val.id + "'    >" + val.nama + "</option>");
					});
				}
			});

			$("#submit").click(function(e){
				e.preventDefault();
                $.ajax({
                    type: "POST",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_paket_tvi/ajax_save_internet",
                    dataType: "json",
                    success: function(data) {
						console.log(data);
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
            });
		}else if(pilih_paket == '2'){
			$(".jml_channel").show();
			$(".nama_channel").show();
			$("#jml_channel").removeAttr("disabled");
			$("#nama_channel").removeAttr("disabled");
			$(".bandwidth").hide();
			$("#bandwidth").attr("disabled", "")

			url = '<?=site_url(); ?>/P_master_paket_tvi/ajax_get_item';
			$.ajax({
				type: "post",
				url: url,
				data: {
					is_channel : $("#pilih_paket").val()
				},
				dataType: "json",
				success: function (data) {
					console.log(data);

					$("#nama_item")[0].innerHTML = "";

					$("#nama_item").append("<option value='' >Pilih Item</option>");
					$.each(data, function (key, val) {
						$("#nama_item").append("<option satuan='"+val.satuan+"' harga_satuan='"+val.harga_satuan+"' value='" + val.id + "'    >" + val.nama + "</option>");
					});
				}
			});

			$("#submit").click(function(e){
				e.preventDefault();
                $.ajax({
                    type: "POST",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_paket_tvi/ajax_save_tv",
                    dataType: "json",
                    success: function(data) {
						console.log(data);
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
            });
		}else if(pilih_paket == '3'){
			$(".bandwidth").show();
			$("#bandwidth").removeAttr("disabled");
			$(".jml_channel").show();
			$(".nama_channel").show();
			$("#jml_channel").removeAttr("disabled");
			$("#nama_channel").removeAttr("disabled");

			url = '<?=site_url(); ?>/P_master_paket_tvi/ajax_get_item';
			$.ajax({
				type: "post",
				url: url,
				data: {
					is_channel : $("#pilih_paket").val()
				},
				dataType: "json",
				success: function (data) {
					console.log(data);

					$("#nama_item")[0].innerHTML = "";

					$("#nama_item").append("<option value='' >Pilih Item</option>");
					$.each(data, function (key, val) {
						$("#nama_item").append("<option satuan='"+val.satuan+"' harga_satuan='"+val.harga_satuan+"' value='" + val.id + "'    >" + val.nama + "</option>");
					});
				}
			});

			$("#submit").click(function(e){
				e.preventDefault();
                $.ajax({
                    type: "POST",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_paket_tvi/ajax_save_tvi",
                    dataType: "json",
                    success: function(data) {
						console.log(data);
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
            });
		}
	});

    $("body").on("change",".nama_item",function(){
		var satuan = $("option:selected",$(this)).attr("satuan");
		var hrg_satuan = $("option:selected",$(this)).attr("harga_satuan");
		$(this).parent().parent().children().eq(2).children().val(satuan);
		$(this).parent().parent().children().eq(3).children().val(hrg_satuan);
		// $(this).parent().parent().children().eq(4).children().val(4+7);
	});

	$(".nama_item").change(function(){
		var satuan = $("option:selected",$(this)).attr("satuan");
		var hrg_satuan = $("option:selected",$(this)).attr("harga_satuan");
		$(this).parent().parent().children().eq(2).children().val(satuan);
		$(this).parent().parent().children().eq(3).children().val(hrg_satuan);
		// $(this).parent().parent().children().eq(4).children().val(4+7);
	});
	
	$("body").on("keyup",".volume",function(){
		console.log($(this).val());
		console.log($(this).parent().parent().children().eq(3).children().val());
		console.log($(this));
		$(this).val(formatNumber($(this).val()));
		$(this).parent().parent().children().eq(5).children().val(
			formatNumber(
				unformatNumber($(this).val()) * 
				unformatNumber($(this).parent().parent().children().eq(3).children().val())
			)
		);
		var total = 0;
		for(i = 0 ; i < $(".isi").length ; i++){
			total += parseInt(unformatNumber($(".isi").eq(i).children().eq(5).children().val()));
		}
		$("#hrg_prediksi").val(formatNumber(total));
		// $("#hrg_prediksi").val(4+7);
	});
	
	$("#nama_channel").on("change",function(){
		var channel = $("#nama_channel").val().length;
		$("#jml_channel").val(formatNumber(channel));
	})

	$("body").on("click",".delete",function(){
		$(this).parent().parent().remove();
	});

	$("#btn-add-paket").click(function(){
		$("#tbody").append(komponen);
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
	function unformatNumber(data) {
		data = data + '';
		return data.replace(/,/g, "");
	}

function hapusElemen(idf) {
    $(idf).remove();
    var idf = document.getElementById("idf").value;
    idf = idf-1;
    document.getElementById("idf").value = idf;
}
</script>