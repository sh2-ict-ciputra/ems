<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<!DOCTYPE html>
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Kategori</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" required name="kode" id="kode" placeholder="Masukkan Kode Kategori">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Kategori</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" required name="nama" id="nama" placeholder="Masukkan Nama Kategori">
                </div>
            </div>
        </div>
		<div class="form-group">
			<div class="center-margin">
				<button class="btn btn-primary" type="reset">Reset</button>
				<button type="submit" id="submit" class="btn btn-success">Submit</button>
			</div>
		</div>
	</form>


    <script>
        function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
        $("#btn-add-paket").click(function() {
            if ($(".no").html()) {
                idf = parseInt($(".no").last().html()) + 1;
            } else {
                idf = 1;
            }
            var str = "<tr id='srow" + idf + "'>" +
                "<td class='no'>" + idf + "</td>" +
                "<td><input type='text' class='form-control'  name='kode_jenis[]' placeholder='' /></td>" +
                "<td><input type='text' name='nama_jenis[]'  class='form-control'/></td>" +
                "<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>" +
                "</tr>";
            $("#tbody").append(str);
        });
        function hapusElemen(idf) {
            $(idf).remove();
            var idf = document.getElementById("idf").value;
            idf = idf - 1;
            document.getElementById("idf").value = idf;
        }
        $(function() {

            $('.select2').select2();

            $("#submit").click(function(e){
				e.preventDefault();
                $.ajax({
                    type: "POST",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_kategori_loi/ajax_save_kategori_loi",
                    dataType: "json",
                    success: function(data) {
                        location.reload();
						console.log(data);
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
            });
        });
    </script>