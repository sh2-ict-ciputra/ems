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
		<button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_item_survei_loi/edit?id=<?=$this->input->get('id')?>'">
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
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<input type="text" id="name" name="name" required class="form-control col-md-7 col-xs-12 form-disabled" placeholder="Masukkan Nama">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nilai">Nilai<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<span class="form-control-feedback left" aria-hidden="true">Rp.</span>
					<input type="text" id="nilai" name="nilai" required class="text-right form-control col-md-7 col-xs-12 currency form-disabled" style="padding-left: 50px;" value="0">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="satuan">Satuan<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<input type="text" id="satuan" name="satuan" required class="form-control col-md-7 col-xs-12 form-disabled">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Deskripsi<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12 form-disabled" placeholder="-- Masukkan Deskripsi --"></textarea>
				</div>
			</div>
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
				url: "<?= site_url('P_master_item_survei_loi/ajax_edit') ?>",
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
		$("#name").val("<?=$dataSelect->name?>").trigger("keyup");
		$("#nilai").val("<?=$dataSelect->nilai?>").trigger("change");
		$("#satuan").val("<?=$dataSelect->satuan?>").trigger("change");
		$("#description").val("<?=$dataSelect->description?>").trigger("change");
		$(".currency").trigger("keyup");
		$(".form-disabled").attr("disabled",true);
		// --end-- isi nilai dari db
	});
</script>