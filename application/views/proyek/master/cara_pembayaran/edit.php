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
		<button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
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
	<form id="form-cara-pembayaran" autocomplete="off" class="form-horizontal form-label-left" method="post">
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">PT <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="pt" required="required" class="select2 form-control col-md-7 col-xs-12" name="pt" style="width:100%" placeholder="--Pilih PT--" disabled require>
                    <option value="" selected="" disabled="">--Pilih PT--</option>
					<?php foreach ($pt as $v): ?>
						<?php if($v->pt_id == $dataSelected->pt_id):?>
							<option value='<?=$v->pt_id?>' selected><?=$v->name?></option>
						<?php else:?>
							<option value='<?=$v->pt_id?>'><?=$v->name?></option>
						<?php endif;?>
						
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Jenis Cara Pembayaran <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="jenis_cara_pembayaran" required="required" class="select2 form-control col-md-7 col-xs-12" name="jenis_cara_pembayaran" style="width:100%" placeholder="--Pilih Jenis Cara Pembayaran--" disabled require>

                    <option value="" selected="" disabled="">--Pilih Jenis Cara Pembayaran--</option>
					<?php foreach ($dataJenisCaraPembayaran as $v):?>
						<?php if($v->id == $dataSelected->jenis_cara_pembayaran_id):?>
							<option value='<?=$v->id?>' bank='<?=$v->bank?>' nama='<?=$v->name?>' kode='<?=$v->code?>' selected><?=$v->name?> - <?=$v->code?></option>");
						<?php else:?>
							<option value='<?=$v->id?>' bank='<?=$v->bank?>' nama='<?=$v->name?>' kode='<?=$v->code?>'><?=$v->name?> - <?=$v->code?></option>");
						<?php endif;?>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank">Bank<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="bank" required="required" class="select2 form-control col-md-7 col-xs-12" name="bank" style="width:100%" placeholder="--Pilih Bank--" require disabled>

                    <option value="" selected="" disabled="">--Pilih Bank--</option>
					<?php foreach ($bank as $v): ?>
						<?php if($v->id == $dataSelected->bank_id):?>
							<option value='<?=$v->id?>' selected><?=$v->name.' - '.$v->code?></option>						
						<?php else:?>
							<option value='<?=$v->id?>'><?=$v->name.' - '.$v->code?></option>						
						<?php endif;?>
					<?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode Cara Pembayaran <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Nama Cara Pembayaran <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="jenis_pembayaran" name="jenis_pembayaran" required="required" class="form-control col-md-7 col-xs-12" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin" id="label_biaya_admin">VA Merchant</label>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="va_bank" name="va_bank" class="form-control col-md-7 col-xs-12 disabled-form" placeholder="Masukkan Bank Merchant" value="<?=$dataSelected->va_bank?>" disabled>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="number" id="va_merchant" name="va_merchant" class="form-control col-md-7 col-xs-12 disabled-form" placeholder="Masukkan Sub Bank Merchant" value="<?=$dataSelected->va_merchant?>" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin" id="label_biaya_admin">Max Digit VA</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="max_digit" name="max_digit" class="form-control col-md-7 col-xs-12 disabled-form" placeholder="Masukkan Max Digit (Sub Bank Merchant + 8)" value="<?=$dataSelected->max_digit?>" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_biaya_admin">Jenis Biaya Admin <span class="required"></span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="jenis_biaya_admin" required="required" class="select2 form-control col-md-7 col-xs-12 disabled-form" name="nilai_flag" style="width:100%" placeholder="--Pilih Jenis Biaya Admin--" require disabled>
                    <option value="0" <?=$dataSelected->nilai_flag == 0?'selected':''?>>Rupiah</option>
                    <option value="1" <?=$dataSelected->nilai_flag == 1?'selected':''?>>Persen</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin" id="label_biaya_admin">Biaya Admin <span class="required">*<br>(Rp.)</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="biaya_admin" name="biaya_admin" required="required" class="form-control col-md-7 col-xs-12 currency disabled-form" value="<?=$dataSelected->biaya_admin?>" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">
                COA Biaya Admin
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="coa" required="required" class="select2 form-control col-md-7 col-xs-12 disabled-form" name="coa" style="width:100%" placeholder="--Pilih PT - COA Service--" disabled>

                    <option value="" selected="" disabled="">--Pilih PT COA--</option>
					<?php foreach ($dataCaraPembayaran as $v): ?>
						<?php if($v->id == $dataSelected->coa_mapping_id):?>
							<option value='<?= $v->id?>' selected><?= $v->ptName." ".$v->coaCode." - ".$v->coaName?></option>
						<?php else:?>
							<option value='<?= $v->id?>'><?= $v->ptName." ".$v->coaCode." - ".$v->coaName?></option>
						<?php endif;?>
					<?php endforeach;?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="middle-name" class="form-control col-md-7 col-xs-12 disabled-form" type="text" name="keterangan" disabled><?=$dataSelected->description?></textarea>
            </div>
        </div>


        <div class="form-group">
            <div>
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
		<table class="table table-striped jambo_table bulk_action tableDT2">
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
	$(".select2").select2();
	function currency(inp) {
		var input = inp.val().toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		console.log("test");
		console.log((input === 0) ? "" : input.toLocaleString("en-US"));
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}
	$(function () {
		function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
		$('#form-cara-pembayaran').on('submit', function (e) {
			console.log("test1");
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: "<?= site_url() ?>/P_master_cara_pembayaran/ajax_edit?id=<?=$this->input->get("id")?>",
				data: $('form').serialize(),
				dataType: "json",
				success: function (data) {
					console.log(data);
					if( data == "success")
						notif("Berhasil","Data Berhasil di Ubah	","success")
					else
						notif("Gagal","Data Tidak ada Perubahan","danger")
				}
			});
		});
		$(".tableDT2").DataTable();

		$("#jenis_cara_pembayaran").change(function() {
            var option = $('option:selected', this).attr('bank');
            var nama = $('option:selected', this).attr('nama');
            var kode = $('option:selected', this).attr('kode');
            $("#code").val(kode);
            $("#jenis_pembayaran").val(nama);
        });
        $("#jenis_biaya_admin").change(function() {
            if ($("#jenis_biaya_admin").val() == 0)
                $("#label_biaya_admin").html("Biaya Admin <span class='required'>*<br>(Rp.)");
            else
                $("#label_biaya_admin").html("Biaya Admin <span class='required'>*<br>(%)")
        });
        $(".select2").select2();
		$(".currency").val(currency($(".currency")));
		$("#btn-update").click(function() {
			disableForm = 0;
			$(".disabled-form").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
		});
		$("#btn-cancel").click(function() {
			disableForm = 1;
			$(".disabled-form").attr("disabled", "")
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
		$("#jenis_cara_pembayaran").trigger("change");
	});

</script>
