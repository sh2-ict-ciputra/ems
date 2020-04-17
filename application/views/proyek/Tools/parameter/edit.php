<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>

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
		<button class="btn btn-warning" onclick="location.href='<?=site_url(); ?>/P_master_cara_pembayaran';">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_master_cara_pembayaran/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_master_cara_pembayaran/edit?id=<?=$this->input->get('id'); ?>">
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12" value="<?=$data_select->code; ?>"
				disabled>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Jenis Pembayaran <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="jenis_pembayaran" name="jenis_pembayaran" required="required" class="form-control col-md-7 col-xs-12"
				value="<?=$data_select->name; ?>" disabled>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin">Biaya Admin <span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="biaya_admin" name="biaya_admin" required="required" class="form-control col-md-7 col-xs-12 currency"
				value="<?=$data_select->biaya_admin; ?>" disabled>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">
				COA Biaya Admin
				<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="coa" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa" style="width:100%" 
                placeholder="--Pilih PT - COA Service--" disabled>
					<option value="" selected="" disabled="">--Pilih PT COA--</option>
					<?php
                        foreach ($dataPTCOA as $key => $c) {
                            if ($data_select->coa_mapping_id == $c['id']) {
                                echo "<option value='$c[id]' selected>$c[coa_code] - $c[coa_name] - $c[pt_name] </option>";
                            } else {
                                echo "<option value='$c[id]'>$c[coa_code] - $c[coa_name] - $c[pt_name] </option>";
                            }
                        }
                    ?>



				</select>
			</div>
		</div>

		<div class="form-group">
			<label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea id="keterangan" class="form-control col-md-7 col-xs-12" type="text" name="keterangan" disabled><?=$data_select->description; ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<div class="">
					<label>
						<input id="status" type="checkbox" class="js-switch" name="status" value='1' <?=$data_select->active == 1 ?
						'checked' : ''; ?> disabled/> Aktif
					</label>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
			<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
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
<script type="text/javascript">
	function currency(inp) {
		var input = inp.val().toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		console.log("test");
		console.log((input === 0) ? "" : input.toLocaleString("en-US"));
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}
	$(function () {
		$(".currency").val(currency($(".currency")));
		$("#btn-update").click(function () {
			$("#code").removeAttr("disabled");
			$("#jenis_pembayaran").removeAttr("disabled");
			$("#biaya_admin").removeAttr("disabled");
			$("#coa").removeAttr("disabled");
			$("#keterangan").removeAttr("disabled");
			$("#status").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			$("#btn-update").attr("type", "submit");
		});
		$("#btn-cancel").click(function () {
			$("#code").attr("disabled", "")
			$("#jenis_pembayaran").attr("disabled", "")
			$("#biaya_admin").attr("disabled", "")
			$("#coa").attr("disabled", "")
			$("#keterangan").attr("disabled", "")
			$("#status").attr("disabled", "")
			$("#btn-cancel").attr("style", "display:none");
			$("#btn-update").val("Edit")
			$("#btn-update").removeAttr("type");
		});
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
						console.log(data[0]);
						for (i = 0; i < data[0].length; i++) {
							$.each(data[1], function (key, val) {
								if (val.name == data[0][i].name) {
									console.log(val.name);
									$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0]
										[i].value + "</td></tr>");
								}
							});
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
	});

</script>
