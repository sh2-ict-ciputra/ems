<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
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
<div class="x_content">
	<br>
	<form id="form" class="form-horizontal form-label-left" method="post">
		<div class="x_content">
			<br />
			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Group</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select id="group_tvi_id" name="group_tvi_id" class="form-control select2" disabled>
                            <option selected disabled>Pilih Group Tvi</option>
                            <?php
                                foreach ($dataGroupInternet as $c) {
                                    if ($data_select->group_id == $c['id']) {
                                        echo "<option value='$c[id]' selected>$c[name] </option>";
                                    } else {
                                        echo "<option value='$c[id]'>$c[name] </option>";
                                    }
                                }
                            ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Paket</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input id="code" type="text" class="form-control" required name="code" placeholder="Masukkan Kode Paket" value="<?=$data_select->code; ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Paket</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input id="name" type="text" class="form-control" required name="name" placeholder="Masukkan Nama Paket" value="<?=$data_select->name; ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Bandwidth (Kbps)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input id="bandwidth" type="text" class="form-control currency" name="bandwidth" placeholder="Masukkan Kbps" value="<?=$data_select->bandwidth; ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Prediksi</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="hrg_prediksi form-control currency" name="hrg_prediksi" id="hrg_prediksi" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Harga Hpp (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input id="harga_hpp" type="text" class="form-control currency" required name="harga_hpp" placeholder="Masukkan Harga HPP" value="<?=$data_select->harga_hpp; ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Jual (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control currency" id="harga_jual" required name="harga_jual" placeholder="Masukkan Harga Jual" value="<?=$data_select->harga_jual; ?>" disabled>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Pasang Baru (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input id="biaya_pasang_baru" type="text" class="form-control currency" required name="biaya_pasang_baru" placeholder="Masukkan Biaya Pasang Baru" value="<?=$data_select->biaya_pasang_baru; ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Registrasi (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input id="biaya_registrasi" type="text" value="0"  class="form-control currency" required name="biaya_registrasi" placeholder="Masukkan Biaya Registrasi" value="<?=$data_select->biaya_registrasi; ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<textarea id="description" class="form-control" rows="3" name="description" placeholder='Masukkan Keterangan' disabled><?=$data_select->description; ?></textarea>
					</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Active</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="checkbox">
                            <label id="label_active">
                                <input id="active" type="checkbox" class="js-switch disabled-form" name="active" value="1" <?=$data_select->active == 1 ? 'checked' : ''; ?> disabled/>
                            </label>
                        </div>
                    </div>
                </div>
			</div>
			<div class="col-md-8 col-xs-12">
				<table class="table table-responsive">
					<thead>
						<tr>
							<th class="col-md-3">Item</th>
							<th class="col-md-1">Volume</th>
							<th class="col-md-3">Satuan</th>
							<th class="col-md-2">Harga Satuan</th>
							<th class="col-md-3">Total</th>
							<th>Hapus</th>
						</tr>
					</thead>
					<tbody id="tbody">
						<?php foreach($dataItemDetail as $v){?>
						<tr class="isi">
							<td>
								<select class="nama_item form-control col-md-1 col-xs-12 select2" disabled value="0" id="nama_item" name="nama_item[]">
									<option disabled>Pilih Item</option>
									<?php
										foreach ($dataItemPilih as $c) {
                                            // echo "<option value='$c[id]'>$c[nama]</option>";
											if ($data_select->paket_id == $c['paket_id']) {
												echo "<option value='$c[paket_id]' selected>$c[nama] </option>";
											} else {
												echo "<option value='$c[paket_id]'>$c[nama] </option>";
											}
										}
									?>
								</select>
							</td>
							<td>
								<input id="volume" name="volume[]" required="" value="<?=$v['volume']; ?>" disabled class="volume form-control col-md-1 col-xs-12 currency">
							</td>
							<td>
								<input id="satuan" name="satuan[]" readonly value="<?=$v['satuan']?>" required="" class="satuan form-control col-md-1 col-xs-12">
							</td>
							<td>
								<input id="harga_satuan" name="harga_satuan[]" readonly value="<?=$v['harga_satuan']?>" required="" class="harga_satuan form-control col-md-1 col-xs-12">
							</td>  
							<td>
								<input id="total" name="total[]" readonly value="<?=$v['volume']*$v['harga_satuan']; ?>" required="" class="form-control col-md-1 col-xs-12 currency">
							</td>
							<td>
								<a class='delete btn btn-danger' href='#' id="btn-delete-paket" disabled><i class='fa fa-trash'></i> </a>
							</td>
						</tr>
						<?php }?>
					</tbody>
				</table>
				<button type="button" id='btn-add-paket' class="btn btn-danger pull-right" disabled><i class="fa fa-plus"></i> Add Paket</button>
			</div>
			<div class="col-md-12">
                <div class="form-group">
                    <input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
                    <input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
                </div>
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
<script>
	// var komponen = $(".isi")[0].outerHTML;
	var no_paket = 1;

	// function paket_item() {
	// 	nama_item = [];
	// 	$.each($('.nama_item'), function(key, tmp) {
	// 		if (tmp.value != 'Pilih Item')
	// 		nama_item.push(tmp.value);
	// 	});

	// 	// $(".paket-service>option").attr('disabled', false);

	// 	$.each(nama_item, function(key, value) {
	// 		// console.log($(".paket-service")[key].value);
	// 		// console.log(value);
	// 		$(".nama_item>option[value=" + value + "]").attr('disabled', true);

	// 	});
	// 	$.each(paket_service_id, function(key, value) {
	// 		for (index = 0; index < $(".paket-service").length; index++) {
	// 			if ($(".paket-service")[index].value == value) {
	// 				$(".paket-service").eq(index).children("option[value=" + value + "]").attr('disabled', false)
	// 				// console.log($(".paket-service")[index].value +" - "+value);
	// 				// console.log(index +" - "+value);
	// 				// console.log($(".paket-service"));
	// 			}
	// 		}
	// 	});
	// 	$(".select2").select2();
	// 	// 	$.each($('.paket-service'),function(key,tmp){
	// 	// })
	// }
	$("body").on("click",".delete",function(){
		$(this).parent().parent().remove();
	});
    $("#btn-update").click(function(){
        $("#group").removeAttr("disabled");
        $("#code").removeAttr("disabled");
        $("#name").removeAttr("disabled");
        $("#bandwidth").removeAttr("disabled");
        $("#harga_hpp").removeAttr("disabled");
        $("#harga_jual").removeAttr("disabled");
        $("#biaya_pasang_baru").removeAttr("disabled");
        $("#biaya_registrasi").removeAttr("disabled");
        $("#description").removeAttr("disabled");
        $("#active").removeAttr("disabled");
		$(".delete").removeAttr("disabled");
        $(".nama_item").removeAttr("disabled");
        $(".volume").removeAttr("disabled");
		$("#btn-add-paket").removeAttr("disabled");
        $("#btn-cancel").removeAttr("style");

        $("#btn-update").val("Update");
        $("#btn-update").attr("type","submit");
    });
    $("#btn-cancel").click(function(){
        $("#group").attr("disabled","")
        $("#code").attr("disabled","")
        $("#name").attr("disabled","");
        $("#bandwidth").attr("disabled","");
        $("#harga_hpp").attr("disabled","")
        $("#harga_jual").attr("disabled","")
        $("#biaya_pasang_baru").attr("disabled","")
        $("#biaya_registrasi").attr("disabled","")
        $("#description").attr("disabled","")
        $("#active").attr("disabled","")
        $(".nama_item").attr("disabled","")
        $(".volume").attr("disabled","")
		$(".delete").attr("disabled","")
		$("#btn-add-paket").attr("disabled","")
        $("#btn-cancel").attr("style","display:none");
        $("#btn-update").val("Edit")
        $("#btn-update").removeAttr("type");
    });
    $("#btn-add-paket").click(function(){
		// $("#tbody").append(komponen);
		var str =   "<tr class='isi'>"+
						"<td>"+
							"<select class='nama_item form-control col-md-1 col-xs-12 select2' value='0' id='nama_item' name='nama_item[]'>"+
                            "<option value=''>Pilih Item</option>"+
							<?php 
								foreach($dataItemPaket as $c){
                                	echo "\"<option satuan='$c->satuan' harga_satuan='$c->harga_satuan' value='$c->id'>$c->nama</option>\"+";
                            	}
                            ?>
							"</select>"+
                        "</td>"+
                        "<td>"+"<input id='volume-1' name='volume[]' required='' value='' class='volume form-control col-md-1 col-xs-12 currency'>"+"</td>"+
                        "<td>"+"<input id='satuan-1' name='satuan[]' readonly value='' required='' class='satuan form-control col-md-1 col-xs-12'>"+"</td>"+
                        "<td>"+"<input id='harga_satuan-1' name='harga_satuan[]' readonly value='' required='' class='harga_satuan form-control col-md-1 col-xs-12'>"+"</td>"+
                        "<td>"+"<input id='total-1' name='total[]' readonly value='' required='' class='form-control col-md-1 col-xs-12 currency'>"+"</td>"+
                        "<td>"+"<a class='delete btn btn-danger' href='#' id='btn-delete-paket'><i class='fa fa-trash'></i> </a>"+"</td>"
                    "</tr>";
        $("#tbody").append(str);
		$('.select2').select2();
    });

	$(".nama_item").change(function(){
		var satuan = $("option:selected",$(this)).attr("satuan");
		var hrg_satuan = $("option:selected",$(this)).attr("harga_satuan");
		$(this).parent().parent().children().eq(2).children().val(satuan);
		$(this).parent().parent().children().eq(3).children().val(hrg_satuan);
		// $(this).parent().parent().children().eq(4).children().val(4+7);
	});

	$("body").on("change",".nama_item",function(){
		var satuan = $("option:selected",$(this)).attr("satuan");
		var hrg_satuan = $("option:selected",$(this)).attr("harga_satuan");
		$(this).parent().parent().children().eq(2).children().val(satuan);
		$(this).parent().parent().children().eq(3).children().val(formatNumber(hrg_satuan));
		// $(this).parent().parent().children().eq(4).children().val(4+7);
	});

	$("body").on("keyup",".volume",function(){
		console.log($(this).val());
		console.log($(this).parent().parent().children().eq(3).children().val());
		console.log($(this));
		// $(this).val(formatNumber($(this).val()));
		$(this).parent().parent().children().eq(4).children().val(
			formatNumber(
				unformatNumber($(this).val()) * 
				unformatNumber($(this).parent().parent().children().eq(3).children().val())
			)
		);
		var total = 0;
		for(i = 0 ; i < $(".isi").length ; i++){
			total += parseInt(unformatNumber($(".isi").eq(i).children().eq(4).children().val()));
		}
		$("#hrg_prediksi").val(formatNumber(total));
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

	function notif(title, text, type) {
		new PNotify({
			title: title,
			text: text,
			type: type,
			styling: 'bootstrap3'
		});
	}
        $(function() {
			
            $('.select2').select2();

            $("#form").submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_paket_internet/ajax_edit?id=<?=$data_select->id?>",
                    dataType: "json",
                    success: function(data) {
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
            });
        });
	

function hapusElemen(idf) {
    $(idf).remove();
    var idf = document.getElementById("idf").value;
    idf = idf-1;
    document.getElementById("idf").value = idf;
}
</script>