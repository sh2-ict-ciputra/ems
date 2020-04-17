<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<!-- modals -->
<!-- Large modal -->
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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_master_paket_tvi/edit?id=<?=$this->input->get('id'); ?>">
		<div class="col-md-5 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Group</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="group" class="form-control select2 disabled-form" disabled>
						<?php
							foreach ($dataGroupTvi as $c) {
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
					<input type="text" class="form-control disabled-form" required name="kode" placeholder="Masukkan Kode Paket" value="<?=$data_select->code; ?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Paket</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" required name="nama_paket" placeholder="Masukkan Nama Paket" value="<?=$data_select->name; ?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Bandwith (Kbps)</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form currency" name="bandwith" placeholder="Masukkan Kbps" value="<?=$data_select->bandwidth; ?>" disabled>
				</div>
			</div>
			<!--
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" class="form-control" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required name="harga" placeholder="Masukkan Harga">
							</div>
									</div>
						-->
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12"> Harga Hpp (Rp)</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form currency" required name="hpp" placeholder="Masukkan Harga HPP" value="<?=$data_select->harga_hpp; ?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Jual (Rp)</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form currency" id="harga" required name="harga_jual" placeholder="Masukkan Harga Jual"
					value="<?=$data_select->harga_jual; ?>" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Pasang Baru (Rp)</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form currency" required name="biaya_pasang" placeholder="Masukkan Biaya Pasang Baru"
					value="<?=$data_select->biaya_pasang_baru; ?>" disabled>
				</div>
			</div>

			<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Biaya Registrasi (Rp)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text"  class="form-control currency disabled-form" required name="biaya_registrasi" placeholder="Masukkan Biaya Registrasi" value="<?=$data_select->biaya_registrasi; ?>" disabled>
					</div>
				</div>

			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea class="form-control disabled-form" rows="3" name="keterangan" placeholder='Masukkan Keterangan' disabled><?=$data_select->description; ?></textarea>
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
		<div class="col-md-7 col-xs-12">
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
					<?php foreach ($dataPaketItemDetail as $v) { ?>
						<tr class="isi">
							<td>
								<select class="nama_item form-control col-md-1 col-xs-12 select2" value="0" id="nama_item" name="nama_item[]">
									<option value="">Pilih Item</option>
									<?php
									foreach($dataItemPaket as $b){
										echo ("<option satuan=".$b->satuan." harga_satuan=".$b->harga_satuan." balue=" . $b->id . ">" . $b->nama . "</option>");
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
								<input id="total-1" name="total[]" readonly required="" value="" class="form-control col-md-1 col-xs-12 currency">
							</td>
							<td>
							<a class='btn btn-danger' href='#' id="btn-delete-paket"><i class='fa fa-trash'></i> </a>
							</td>
						</tr>
					<?php }?>         
					<!-- <input id="idf" value="1" type="hidden" /> -->
				</tbody>
			</table>
			<button type="button" id='btn-add-paket' class="btn btn-danger pull-right" disabled><i class="fa fa-plus"></i> Add Paket</button>
			<!-- <button type="button" id='btn-add-paket' class="btn btn-danger pull-right disabled-form" disabled><i class="fa fa-plus"></i> Add Paket</button> -->
		</div>
		<div class="form-group">
			<div class="col-md-12">
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

    // $("#btn-add-paket").click(function(){
    //     if($(".no").html()){
    //         idf = parseInt($(".no").last().html()) + 1;
    //     }else{
    //         idf = 1;
    //     }
    //     var str = "<tr id='srow" + idf + "'>"+
	// 	            "<td class='no'>"+idf+"</td>"+
    //                 "<td><input type='text'  name='nama_item[]' class='form-control' placeholder=''/></td>"+
	// 				// "<td>"+
	// 				// 	"<select id='nama_item' name='nama_item[]' class='form-control select2' >" +
	// 				// 		"<option selected disabled>Pilih Item</option>"+
	// 				// 	"</select>" +
	// 				// "</td>" +
	// 				"<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' name='kuantitas[]' value='0' class='form-control'/></td>" +
	// 				"<td><input type='text' class='form-control'  name='satuan[]' placeholder=''/></td>" +
	// 				"<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>"+
    //             "</tr>";
    //     $("#tbody_range").append(str);
    // });

	function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
        $(function() {
			
			$('#btn-add-paket').click(function(){
				var row = "<tr>"
				+"</tr>";
        	});
            $('.select2').select2();

            $("#submit").click(function(e){
				e.preventDefault();
                $.ajax({
                    type: "POST",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_paket_tvi/ajax_save",
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
	

function hapusElemen(idf) {
    $(idf).remove();
    var idf = document.getElementById("idf").value;
    idf = idf-1;
    document.getElementById("idf").value = idf;
}



</script>
<script type="text/javascript">
	$(function () {
		//untuk currency edi
		$.each($(".currency"), function( index, currency ) {
			currency.value = parseInt( currency.value.toString().replace(/[\D\s\._\-]+/g, ""), 10 ).toLocaleString("en-US");
		});
		$("#btn-update").click(function () {
			disableForm = 0;
			$(".disabled-form").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
		});
		$("#btn-cancel").click(function () {
			disableForm = 1;
			$(".disabled-form").attr("disabled", "")
			$("#btn-cancel").attr("style", "display:none");
			$("#btn-update").val("Edit")
			$("#btn-update").removeAttr("type");
		});
		
		$(".btn-modal").click(function(){
            url = '<?=site_url(); ?>/core/get_log_detail';
            console.log($(this).attr('data-transfer'));
            console.log($(this).attr('data-type'));
            $.ajax({
                type: "POST",
                data: {id:$(this).attr('data-transfer'),type:$(this).attr('data-type')},
                url: url,
                dataType: "json",
                success: function(data){
                    console.log(data);
                    console.log($(this).attr('data-type'));
                    $("#dataModal").html("");
                    if(data[data.length-1] == 2){
                        console.log(data[0]);
                        for (i = 0; i < data[0].length; i++) { 
                            $.each(data[1], function(key, val){
                                if(val.name == data[0][i].name){
                                    console.log(val.name);
                                    $("#dataModal").append("<tr><th>"+data[0][i].name+"</th><td>"+val.value+"</td><td>"+data[0][i].value+"</td></tr>");        
                                }
                            }); 
                        }
                    }else{
                        $.each(data, function(key, val){
                            if(data[data.length-1] == 1){
                                console.log(data);
                                if(val.name)
                                    $("#dataModal").append("<tr><th>"+val.name.toUpperCase()+"</th><td></td><td>"+val.value+"</td></tr>");
                            }else if(data[data.length-1] == 2){
                                
                            }else if(data[data.length-1] == 3){
                                console.log(data);
                                if(val.name)
                                    $("#dataModal").append("<tr><th>"+val.name.toUpperCase()+"</th><td>"+val.value+"</td><td></td></tr>");
                            }
                        });
                    }
                    
                }
            });

        });

	});

</script>
<script>

$("#btn-add-paket").click(function(){
	if($(".no").html()){
		idf = parseInt($(".no").last().html()) + 1;
	}else{
		idf = 1;
	}
	var str = "<tr id='srow" + idf + "'>"+
				"<td class='no'>"+idf+"</td>"+
				"<td><input type='text'  name='nama_item[]' class='form-control' placeholder=''/></td>"+
				// "<td>"+
				// 	"<select id='nama_item' name='nama_item[]' class='form-control select2' >" +
				// 		"<option selected disabled>Pilih Item</option>"+
				// 	"</select>" +
				// "</td>" +
				"<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' name='kuantitas[]' value='0' class='form-control'/></td>" +
				"<td><input type='text' class='form-control'  name='satuan[]' placeholder=''/></td>" +
				"<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>"+
			"</tr>";
	$("#tbody_range").append(str);
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
		
		$('#btn-add-paket').click(function(){
			var row = "<tr>"
			+"</tr>";
		});
		$('.select2').select2();

		$("#submit").click(function(e){
			e.preventDefault();
			$.ajax({
				type: "POST",
				data: $("#form").serialize(),
				url: "<?= site_url() ?>/P_master_paket_tvi/ajax_save",
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


function hapusElemen(idf) {
$(idf).remove();
var idf = document.getElementById("idf").value;
idf = idf-1;
document.getElementById("idf").value = idf;
}



</script>
