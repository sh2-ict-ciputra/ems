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
			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Group</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="group_tvi_id" class="form-control select2 " >
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
						<input type="text" class="form-control" required name="code" placeholder="Masukkan Kode Paket">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Paket</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control" required name="name" placeholder="Masukkan Nama Paket">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Channel</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" name="jml_channel" id="jml_channel" readonly>
					</div>
				</div>
				<!-- <div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Prediksi</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" name="hrg_prediksi" id="hrg_prediksi" disabled placeholder="Masukkan Jumlah Channel">
					</div>
				</div> -->
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Channel</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select id="nama_channel" name="nama_channel[]" class="form-control multipleSelect js-example-basic-multiple" multiple="multiple">
							<option value=""></option>
							<?php foreach($dataChannel as $c){
								echo "<option value='$c->id'>$c->name</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group" hidden>
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Harga Hpp (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency currency" required name="harga_hpp" placeholder="Masukkan Harga HPP">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Jual (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" id="harga" required name="harga_jual" placeholder="Masukkan Harga Jual">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Pasang Baru (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" required name="biaya_pasang_baru" placeholder="Masukkan Biaya Pasang Baru">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Registrasi (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" value="0"  class="form-control currency" required name="biaya_registrasi" placeholder="Masukkan Biaya Registrasi">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<textarea class="form-control" rows="3" name="description" placeholder='Masukkan Keterangan'></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-xs-12">
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
									<?php
									foreach($dataItemPaket as $v){
										echo ("<option satuan=".$v->satuan." harga_satuan=".$v->harga_satuan." value=" . $v->id . ">" . $v->nama . "</option>");
									}
									?>
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
				<button type="button" id='btn-add-paket' class="btn btn-danger pull-right" ><i class="fa fa-plus"></i> Add Paket</button>
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
	var komponen = $(".isi")[0].outerHTML;
	$('.js-example-basic-multiple').select2({
		placeholder: '-- Masukkan Pilihan --',
		tags: true,
		tokenSeparators: [',', ' ']
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
        $(function() {
			
			// $('#btn-add-paket').click(function(){
			// 	var row = "<tr>"
			// 	+"</tr>";
        	// });

            $("#submit").click(function(e){
				e.preventDefault();
                $.ajax({
                    type: "POST",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_paket_tv/ajax_save",
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
        });

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