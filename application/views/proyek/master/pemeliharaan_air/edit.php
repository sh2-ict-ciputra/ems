<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
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
				<table class="table table-striped jambo_table">
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
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_pemeliharaan_air/add'">
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
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="code" name="code" required class="form-control col-md-7 col-xs-12" placeholder="Masukkan Nama" value='<?=$data2->code?>' readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name" required class="form-disabled form-control col-md-7 col-xs-12" placeholder="Masukkan Nama" value='<?=$data2->name?>' disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ukuran_pipa">Ukuran Pipa<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="ukuran_pipa" name="ukuran_pipa" required class="form-disabled form-control col-md-7 col-xs-12" placeholder="Masukkan Ukuran Pipa Beserta Satuan" value='<?=$data2->ukuran_pipa?>' disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nilai">Harga<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                <input type="text" id="nilai" name="nilai" required class="form-disabled text-right form-control col-md-7 col-xs-12 currency" style="padding-left: 50px;" value='<?=number_format($data2->nilai)?>' disabled>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="description" class="form-disabled form-control col-md-7 col-xs-12" type="text" name="description" placeholder="Masukkan Keterangan jika diperlukan" disabled><?=$data2->description?></textarea>
            </div>
        </div>
		<div class="col-md-12">
            	<input id="btn-edit" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
            	<button id="btn-update" class="btn btn-success col-md-1 col-md-offset-5">Update</button>
				<input id="btn-cancel"class="btn btn-danger col-md-1" value="cancel">
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
            foreach ($data as $key => $v){
                $i++;
                echo('<tr>');
                    echo("<td>$i</td>");
                    echo("<td>$v[date]</td>");
                    echo("<td>$v[name]</td>");
                    echo("<td>");
                        if($v['status']==1)
                            echo("Tambah");
                        elseif($v['status']==2)
                            echo("Edit");
                        else
                            echo("Hapus");
                    echo("</td>");
                    echo("
                    <td class='col-md-1'>
                        <a class='btn-modal btn btn-sm btn-primary col-md-12' data-toggle='modal' data-target='#modal' data-transfer='$v[id]' data-type='$v[status]'>
                            <i class='fa fa-pencil'></i>
                        </a>
                    </td>
                ");
                echo('</td></tr>');                
            }
        ?>
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

    function notif(title, text, type) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            styling: 'bootstrap3'
        });
    }

    function unformatNumber(data) {
        data = data + '';
        return data.replace(/,/g, "");
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
		
        $("#name").keyup(function() {
            $("#code").val($("#name").val().toLowerCase().replace(/ /g, '_'));
        });
        $(".currency").keyup(function() {
            $(this).val(formatNumber($(this).val()));
        });
        $("form").submit(function(e) {
            $('.currency').val(unformatNumber($(".currency").val()));
            e.preventDefault();
            $.ajax({
                type: "POST",
                data: $("form").serialize()+"&id=<?=$this->input->get('id')?>",
                url: "<?= site_url('P_master_pemeliharaan_air/ajax_edit') ?>",
                dataType: "json",
                success: function(data) {
                    if (data.status == 1)
                        notif('Sukses', data.message, 'success')
                    else
                        notif('Gagal', data.message, 'danger')
                }
            });
            $('.currency').val(formatNumber($(".currency").val()));
        })
		$(".btn-modal").click(function(){
            url = '<?=site_url()?>/core/get_log_detail';
            console.log($(this).attr('data-transfer'));
            console.log($(this).attr('data-type'));
            $.ajax({
                type: "POST",
                data: {id:$(this).attr('data-transfer'),type:$(this).attr('data-type')},
                url: url,
                dataType: "json",
                success: function(data){
                    console.log(data);
                    // var items = []; 
                    // $("#changeJP").attr("style","display:none");
                    // $("#saveJP").removeAttr('style');
                    // $("#jabatan").removeAttr('disabled');
                    // $("#jabatan")[0].innerHTML = "";
                    // $("#project")[0].innerHTML = "";
                    // $("#jabatan").append("<option value='' selected disabled>Pilih Jabatan</option>");
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