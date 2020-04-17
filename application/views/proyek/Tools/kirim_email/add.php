<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?=base_url()?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
            <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/P_master_cara_pembayaran'">
                <i class="fa fa-arrow-left"></i>
                Back
            </button>
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_master_cara_pembayaran/add'">
                <i class="fa fa-repeat"></i>
                Refresh
            </button>
        </h2>
    </div>
    <div class="clearfix"></div>
</div>
<div class="x_content">
    <br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_cara_pembayaran/save">
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Jenis Pembayaran <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="jenis_pembayaran" name="jenis_pembayaran" required="required" class="form-control col-md-7 col-xs-12">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biaya_admin">Biaya Admin <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="biaya_admin" name="biaya_admin" required="required" class="form-control col-md-7 col-xs-12 currency">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">
                COA Biaya Admin
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="coa" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa" style="width:100%" placeholder="--Pilih PT - COA Service--">
              
				     <option value="" selected="" disabled="">--Pilih PT COA--</option>
                    <?php
                        foreach ($dataCaraPembayaran as $v) {
                            echo("<option value='$v[id]'>$v[coa_code] - $v[coa_name] - $v[pt_name]</option>");
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="keterangan"></textarea>
            </div>
        </div>

       
        <div class="form-group">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>

	
<!-- jQuery -->
<script type="text/javascript">	

$(function() {
    $(".select2").select2();
});

</script>
