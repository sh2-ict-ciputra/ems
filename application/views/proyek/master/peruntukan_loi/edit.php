<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<!DOCTYPE html>
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
    <form id="form" class="form-horizontal form-label-left">
        <div class="col-md-12 col-xs-12">
            
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis LOI</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select required="" id="jenis" name="jenis_loi_id" disabled class="form-control select2">
                        <option value=>--Pilih Jenis--</option>
                        <?php foreach($dataJenis as $v){?>
                            <option value="<?=$v['id']?>"<?=($dataSelect->jenis == $v['id'])?"selected":""?>><?=$v['nama']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Peruntukan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" disabled required name="nama" id="nama" value="<?=$dataSelect->nama?>" placeholder="Masukkan Nama Jenis LOI">
                </div>
            </div>
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


    <script>
        function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
        $("#btn-update").click(function () {
            $("#jenis").removeAttr("disabled");
            $("#nama").removeAttr("disabled");
            $("#btn-cancel").removeAttr("style");
            $("#btn-update").val("Update");
            setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
        });
        $("#btn-cancel").click(function () {
            $("#jenis").attr("disabled", "")
            $("#nama").attr("disabled", "")
            $("#btn-cancel").attr("style", "display:none");
            $("#btn-update").val("Edit")
            $("#btn-update").removeAttr("type");
        });
        $(function() {
            $('.select2').select2();

            $("#form").submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_peruntukan_loi/ajax_edit?id=<?=$dataSelect->id?>",
                    dataType: "json",
                    success: function(data) {
                        location.reload();
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
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