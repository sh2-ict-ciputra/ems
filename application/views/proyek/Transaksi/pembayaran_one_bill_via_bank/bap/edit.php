<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
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
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url()?>/P_transaksi_meter_air'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_transaksi_meter_air/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>

<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_transaksi_meter_air/edit?id=<?=$this->input->get('id')?>">
		<div class="col-md-6 col-xs-12">
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="code" class="form-control" readonly value="<?=$dataSelect->code?>">
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="name" class="form-control disabled-form" placeholder="Masukkan Nama Customer" value="<?=$dataSelect->name?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="unit" class="form-control select2 disabled-form" disabled>
                        <option value="" selected disabled>--Pilih Jenis Unit--</option>
                        <option value="1" <?=$dataSelect->unit==1?'selected':''?> >Unit</option>
                        <option value="0" <?=$dataSelect->unit==0?'selected':''?> >Non Unit</option>
					</select>
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat Domisili</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="alamat_domisili" class="form-control disabled-form" rows="3" placeholder='Masukkan Alamat Domisili' disabled><?=$dataSelect->address?></textarea>
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="email" class="form-control disabled-form" placeholder="Masukkan Email" value="<?=$dataSelect->email?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor NPWP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="nomor_npwp" class="form-control disabled-form" placeholder="Masukkan Nomor NPWP" value="<?=$dataSelect->npwp_no?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama NPWP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" name="nama_npwp" placeholder="Masukkan Nama NPWP" value="<?=$dataSelect->npwp_name?>" disabled>
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat NPWP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="alamat_npwp" class="form-control disabled-form" rows="3" placeholder='Masukkan Alamat NPWP' disabled><?=$dataSelect->npwp_address?></textarea>
				</div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="">
                    <label>
                        <input id="status" type="checkbox" class="js-switch disabled-form" name="status" value='1' <?=$dataSelect->active == 1 ? 'checked' : ''; ?> disabled/> Aktif
                    </label>
                    </div>
                </div>
            </div>
		</div>
		<div class="col-md-6 col-xs-12">
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">PT</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="pt_id" class="form-control select2 disabled-form" disabled>
                        <option value="" selected disabled>Pilih PT</option>
                        <?php
                            foreach ($dataPT as $v) {
                                echo "<option value='$v[id]' ";
                                echo $v['id']==$dataSelect->pt_id?'selected':'';
                                echo " >$v[name]</option>";
                            }
                        ?>
					</select>
				</div>
            </div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">NIK</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" placeholder="Masukkan Nik" name="nik" value="<?=$dataSelect->ktp?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat KTP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="alamat_ktp" class="form-control disabled-form" rows="3" placeholder='Masukkan Alamat KTP' disabled><?=$dataSelect->ktp_address?></textarea>
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Home Phone</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" placeholder="Masukkan Home Phone" name="home_phone" value="<?=$dataSelect->homephone?>" disabled>
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Office Phone</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" placeholder="Masukkan Office Phone" name="office_phone" value="<?=$dataSelect->officephone?>" disabled>
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Phone 1</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" placeholder="Masukkan Mobile Phone 1" name="mobile_phone_1" value="<?=$dataSelect->mobilephone1?>" disabled>
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Phone 2</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" placeholder="Masukkan Mobile Phone 2" name="mobile_phone_2" value="<?=$dataSelect->mobilephone2?>" disabled>
				</div>
            </div>
            <div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="keterangan" class="form-control disabled-form" rows="3" placeholder='Masukkan Keterangan' disabled><?=$dataSelect->description?></textarea>
				</div>
            </div>
			

		</div>
		<div class="clear-fix"></div>
        
		<div class="col-md-12">
			<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
			<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
		</div>



		<div id="isi_tabel" class="col-md-12">
		</div>

	</form>
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
	$(".select2").select2();

    $(function() {
        $("#btn-update").click(function () {
			disableForm = 0;
			$(".disabled-form").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			$("#btn-update").attr("type", "submit");
		});
		$("#btn-cancel").click(function () {
			disableForm = 1;
			$(".disabled-form").attr("disabled", "")
			$("#btn-cancel").attr("style", "display:none");
			$("#btn-update").val("Edit")
			$("#btn-update").removeAttr("type");
		});
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
