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
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Channel</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" class="form-control" required name="name" id="name" placeholder="Masukkan Nama">
            </div>
        </div>
        <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select name="category" id="category" required="" class="form-control select2">
					<option value="">--Pilih Kategori--</option>
					<option value="General Entertainment">General Entertainment</option>
					<option value="Kids">Kids</option>
                    <option value="Knowledge">Knowledge</option>
                    <option value="Lifestyle">Lifestyle</option>
                    <option value="Local">Local</option>
                    <option value="InHouse">InHouse</option>
                    <option value="Movies">Movies</option>
                    <option value="Music">Music</option>
                    <option value="News">News</option>
                    <option value="Religion">Religion</option>
                    <option value="Sport">Sport</option>
				</select>
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
        $(function() {

            $('.select2').select2();

            $("#submit").click(function(e){
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/P_master_channel/ajax_save",
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
    </script>