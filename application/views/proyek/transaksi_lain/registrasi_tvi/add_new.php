<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>


<!-- switchery -->
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>


<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/p_registrasi_tvi'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/p_registrasi_tvi/add'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url() ?>/transaksi_lain/p_registrasi_tvi/save">

        <div class="form-group unit">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Kawasan - Blok - No. Unit</label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <select required="" id="pilih_unit" name="pilih_unit" class="form-control select2">
                    <option value="" selected="" disabled="">--Pilih Unit--</option>
                    <option value="non_unit">--Non Unit--</option>
                    <?php
                    foreach ($dataUnit as $v) {
                        echo ("<option value='$v[id]'>$v[kawasan_name] - $v[blok_name] - $v[no_unit]</option>");
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="clear-fix"></div>
        <br>

        <div id="data-view" class="col-md-12">
            <div id="data-unit" class="col-md-6">
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
            </div>
            <div id="data-unit" class="col-md-6">
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
            </div>
        </div>
        
        

        <div id="data-view" class="col-md-12">
            <div id="data-unit" class="col-md-6">
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
            </div>
            <div id="data-unit" class="col-md-6">
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
                <div class="form-group unit">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Project</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="" id="project_name" name="project_name" readonly class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>
</div>

<script type="text/javascript">
    $(".select2").select2();
</script>