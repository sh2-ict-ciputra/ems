<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
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
		<button class="btn btn-warning" onClick="window.location.href = '<?= substr(current_url(), 0, strrpos(current_url(), "/")) ?>'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_paket_loi/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form-cara-bayar" autocomplete="off" class="form-horizontal form-label-left">
		<div class="col-md-5 col-sm-5 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" id="code" name="code" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Nama" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" id="name" name="name" required class="form-control col-md-7 col-xs-12 form-disabled" placeholder="Masukkan Nama">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nilai">Nilai LOI<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
					<input type="text" id="nilai" name="nilai" required class="text-right form-control col-md-7 col-xs-12 currency form-disabled" style="padding-left: 50px;" value="0">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nilai_admin">Nilai Admin<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
					<input type="text" id="nilai_admin" name="nilai_admin" required class="text-right form-control col-md-7 col-xs-12 currency form-disabled" style="padding-left: 50px;" value="0">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="uang_jaminan">Uang Jaminan<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
					<input type="text" id="uang_jaminan" name="uang_jaminan" required class="text-right form-control col-md-7 col-xs-12 currency form-disabled" style="padding-left: 50px;" value="0">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="follow_up">Follow Up<span class="required">*</span>
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="follow_up" id="follow_up" class="form-control col-md-7 col-xs-12 form-disabled" required>
						<option value="1" selected>EMS (Lapangan)</option>
						<option value="2">CPMS</option>
						<option value="3">Customer</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-7 col-sm-7 col-xs-12">
			Item Outflow
			<hr style="margin: 10px 0px 3px 0px">
			<table class="table table-responsive">
				<thead>
					<tr>
						<th>Nama Item</th>
						<th>Kwantitas</th>
						<th>Satuan</th>
						<th>Harga Total</th>
						<th>Hapus</th>
					</tr>
				</thead>
				<tbody id="tbody_item_outfow">
					<?php foreach ($dataSelect->loi_paket_outflow as $v):?>
						<tr>
							<td><input type='text' class='col-md-12 form-control form-disabled' name='item_name[]' required value="<?=$v->name?>"></td>
							<td><input type='text' class='col-md-12 form-control currency form-disabled' name='item_kwantitas[]' required value="<?=$v->kwantitas?>"></td>
							<td><input type='text' class='col-md-12 form-control form-disabled' name='item_satuan[]' required value="<?=$v->satuan?>"></td>
							<td><input type='text' class='col-md-12 form-control currency form-disabled' name='item_nilai[]' required value="<?=$v->nilai?>"></td>
							<td><a type='text' class='col-md-12 btn btn-danger btn-hapus form-disabled'>Hapus</a></td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<a id="add_outflow" class="btn btn-info pull-right form-disabled">Add Outflow</a>
		</div>
		<div class="col-md-12">
            	<input id="btn-edit" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
            	<button id="btn-update" class="btn btn-success col-md-1 col-md-offset-5">Update</button>
				<input id="btn-cancel"class="btn btn-danger col-md-1" value="cancel">
        </div>
	</form>
</div>
</div>
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
				foreach ($data as $k => $v):?>
					<tr>
						<td><?=$k+1?></td>
						<td><?=$v['date']?></td>
						<td><?=$v['name']?></td>
						<td>
							<?php if($v['status'] == 1):?>
								Tambah
							<?php elseif ($v['status'] == 2):?>					
								Edit
							<?php else:?>
								Hapus
							<?php endif;?>
						</td>
							<td class='col-md-1'>
								<a class='btn-modal btn btn-sm btn-primary col-md-12' data-toggle='modal' data-target='#modal' data-transfer='<?=$v['id']?>' data-type='<?=$v['status']?>'>
									<i class='fa fa-pencil'></i>
								</a>
							</td>
						</td>
					</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>

<!-- jQuery -->
<script type="text/javascript">
	const row_outflow = "<tr>"+
							"<td><input type='text' class='col-md-12 form-control form-disabled' name='item_name[]' required></td>"+
							"<td><input type='text' class='col-md-12 form-control currency form-disabled' name='item_kwantitas[]' required></td>"+
							"<td><input type='text' class='col-md-12 form-control form-disabled' name='item_satuan[]' required></td>"+
							"<td><input type='text' class='col-md-12 form-control currency form-disabled' name='item_nilai[]' required></td>"+
							"<td><a type='text' class='col-md-12 btn btn-danger btn-hapus form-disabled'>Hapus</a></td>"+
						"</tr>";
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
		$("#btn-update").hide();
		$("#btn-cancel").hide();
		$("#btn-edit").click(function(){
			$("#btn-update").show();
			$("#btn-cancel").show();
			$("#btn-edit").hide();
			$(".form-disabled").attr('disabled',false);
		});
		$("#btn-cancel").click(function(){
			$("#btn-update").hide();
			$("#btn-cancel").hide();
			$("#btn-edit").show();
			$(".form-disabled").attr('disabled',true );

		});
		$(".form-disabled").attr("disabled",true);

		// --start-- item outflow
		$("#add_outflow").click(function(){
			if(!$(this).attr('disabled'))
				$("#tbody_item_outfow").append(row_outflow);
		});
		$("body").on('click','.btn-hapus',function(){
			$(this).parents('tr').remove();
		})
		// --end-- item outflow 
		$("#follow_up").select2();
		$("#name").keyup(function() {
			$("#code").val($("#name").val().toLowerCase().replace(/ /g, '_'));
		});
		$(".currency").keyup(function() {
			$(this).val(formatNumber($(this).val()));
		});
		$("form").submit(function(e) {
			$.each($(".currency"), function( k, v ) {
				$(".currency").eq(k).val(unformatNumber(v.value))
			});
			e.preventDefault();
			$.ajax({
				type: "POST",
                data: $("form").serialize()+"&id=<?=$this->input->get('id')?>",
				url: "<?= site_url('P_master_paket_loi/ajax_edit') ?>",
				dataType: "json",
				success: function(data) {
					if (data.status == 1)
						notif('Sukses', data.message, 'success')
					else
						notif('Gagal', data.message, 'danger')
				}
			});
			$.each($(".currency"), function( k, v ) {
				$(".currency").eq(k).val(formatNumber(v.value))
			});
		})
		// --start-- Get Detail Log
		$(".btn-modal").click(function () {
			url = '<?=site_url(); ?>/core/get_log_detail';
			console.log($(this).attr('data-transfer'));
			console.log($(this).attr('data-type'));
			$.ajax({
				type: "POST",
				data: {
					id: $(this).attr('data-transfer'),
					type: $(this).attr('data-type')
				},
				url: url,
				dataType: "json",
				success: function (data) {
					$("#dataModal").html("");
					if (data[data.length - 1] == 2) {
						for (i = 0; i < data[0].length; i++) {
							var tmpj = 0;
							for (j = 0; j < data[0].length; j++) {
								if(data[1][j] != null){
									if (data[1][j].name == data[0][i].name) {
										$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + data[1][j].value + "</td><td>" + data[0]
											[i].value + "</td></tr>");
										tmpj ++;
									}
									
								}
							}
							if(tmpj == 0){
								$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td></td><td>" + data[0][i].value + "</td></tr>");
							}
						}	
					} else {
						$.each(data, function (key, val) {
							if (data[data.length - 1] == 1) {
								console.log(data);
								if (val.name)
									$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td></td><td>" + val.value +
										"</td></tr>");
							} else if (data[data.length - 1] == 2) {

							} else if (data[data.length - 1] == 3) {
								console.log(data);
								if (val.name)
									$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td>" + val.value +
										"</td><td></td></tr>");
							}
						});
					}

				}
			});

		});
		// --end-- Get Detail Log

		// --start-- isi nilai dari db
		$("#code").val("<?=$dataSelect->loi_paket->code?>").trigger("change");
		$("#name").val("<?=$dataSelect->loi_paket->name?>").trigger("keyup");
		$("#nilai").val("<?=$dataSelect->loi_paket->nilai?>").trigger("change");
		$("#nilai_admin").val("<?=$dataSelect->loi_paket->nilai_admin?>").trigger("change");
		$("#uang_jaminan").val("<?=$dataSelect->loi_paket->uang_jaminan?>").trigger("change");
		$("#follow_up").val("<?=$dataSelect->loi_paket->follow_up?>").trigger("change");
		$(".currency").trigger("keyup");
		$(".form-disabled").attr("disabled",true);
		// --end-- isi nilai dari db
	});
</script>