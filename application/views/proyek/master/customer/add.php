<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?=base_url()?>vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_master_customer/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" class="form-horizontal form-label-left" method="post" action="<?=site_url(); ?>/P_master_customer/save">
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="code" class="form-control" readonly value="<?=$kode_cust?>" required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="name" class="form-control" placeholder="Masukkan Nama Customer" required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Kepemilikan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="unit" class="form-control select2" required>
						<option value="" selected disabled>--Pilih Jenis Unit--</option>
						<option value="1">Unit</option>
						<option value="0">Non Unit</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat Domisili</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="alamat_domisili" class="form-control" rows="3" placeholder='Masukkan Alamat Domisili' required></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="email" class="form-control" placeholder="Masukkan Email" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama NPWP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="nama_npwp" class="form-control" placeholder="Masukkan Nomor NPWP" > 
				</div>
			</div>
			<div class="form-group">
				
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor NPWP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" name="nomor_npwp" data-inputmask="'mask': '99.999.999.9-999.999'" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat NPWP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="alamat_npwp" class="form-control" rows="3" placeholder='Masukkan Alamat NPWP' ></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Golongan Diskon</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="gol_diskon_id" class="form-control select2" placeholder="-- Pilih Golongan Diskon --">
						<option value="" selected disabled>Pilih Golongan Diskon</option>
						<?php
							foreach ($dataGolonganDiskon as $v) {
								echo("<option value='$v[id]'>$v[name]</option>");
							}
                        ?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">PT</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="pt_id" class="form-control select2">
						<option value="" selected disabled>Pilih PT</option>
						<?php
									foreach ($dataPT as $v) {
										echo("<option value='$v[id]'>$v[name]</option>");
									}
                        ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">NIK KTP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="nik" class="form-control" data-inputmask="'mask': '99-99-99-999999-9999'" required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat KTP</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="alamat_ktp" class="form-control" rows="3" placeholder='Masukkan Alamat KTP' required></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Home Phone</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Masukkan Home Phone" name="home_phone" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Office Phone</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Masukkan Office Phone" name="office_phone" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Phone 1</label>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<select id="mobile_phone_1_select" class="form-control select2"  name= "mobile_phone_1_select" value="+62" required>
						<option value=""></option>
						<?php
                            foreach ($dataCodeTelp as $v)
                                echo("<option value='$v[code]'>$v[name]</option>");	
                        ?>
                    </select>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
					<input id="mobile_phone_1" type="text" class="form-control" data-inputmask="'mask' : '(+99) 999999999999999'" name="mobile_phone_1" required>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Phone 2</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control" placeholder="Masukkan Mobile Phone 2" name="mobile_phone_2" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="keterangan" class="form-control" rows="3" placeholder='Masukkan Keterangan' ></textarea>
				</div>
			</div>


		</div>
		<div class="clear-fix"></div>

		<div class="col-md-12 col-xs-12">
			<div class="center-margin">
				<button class="btn btn-primary" type="reset">Reset</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>



		<div id="isi_tabel" class="col-md-12">
		</div>

	</form>
</div>

<!-- jQuery -->

<script type="text/javascript">
	$(".select2").select2();
    $("#mobile_phone_1_select").val("62");
    $('#mobile_phone_1_select').trigger('change.select2'); 
    $("#mobile_phone_1" ).val("62");

    $(function() {
        $("#mobile_phone_1_select").change(function(){
            $("#mobile_phone_1").attr('data-inputmask',"'mask' : '(+"+("9".repeat($(this).val().length))+")999999999999999'");
            $("#mobile_phone_1").inputmask();
            $("#mobile_phone_1").val($(this).val());
        });
        // $("#mobile_phone_1").keyup(function(){
        //     $("#mobile_phone_1_select").val($("#mobile_phone_1").val().substring(2,$("#mobile_phone_1").val().indexOf(')')));
        //     $('#mobile_phone_1_select').trigger('change.select2');
        // });
        
    });
</script>
