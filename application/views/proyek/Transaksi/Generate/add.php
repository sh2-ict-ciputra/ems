<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>
<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kawasan - Blok - Unit - Pemilik:

                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select id='unit' class='col-md-12 form-control select2' required>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 col-md-3 col-sm-12">Periode Tagihan</label>
            <div class="col-lg-3 col-md-3 col-sm-5">
                <div class='input-group date datetimepicker'>
                    <input id="periode_awal" type="text" class="form-control datetimepicker" name="periode_awal" placeholder="Dari Periode" required>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-5">
                <div class='input-group date datetimepicker'>
                    <input id="periode_akhir" type="text" class="form-control datetimepicker" name="periode_akhir" placeholder="Sampai Periode" required>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <a id="button-submit" class="btn btn-success">Submit</a>
                <button id="submit" class="btn btn-success" style="display:none">Submit</button>
            </div>
        </div>
    </form>
    note: Jika ingin mengenerate 1 periode, maka input periode yang sama


    <script>
        $(function() {
            function notif(title, text, type) {
			new PNotify({
				title: title,
				text: text,
				type: type,
				styling: 'bootstrap3'
			});
		}
            $('#button-submit').click(function(){
                if($("#unit").val() != null && $("#periode_awal").val() != ""  && $("#periode_akhir").val() != "" ){
                    $.ajax({
                        type: "POST",
                        data: {
                            unit_id         : $("#unit").val(),
                            periode_awal    : $("#periode_awal").val(),
                            periode_akhir   : $("#periode_akhir").val()
                        },
                        url: "<?= site_url() ?>/auto_generate/P_tagihan/pl_unit",
                        dataType: "json",
                        success: function(data) {
                            if (data>0)
                                notif('Sukses', 'Data Berhasil Ter-Generate sebanyak : '+data+' data', 'success');
                            else
                                notif('Gagal', 'Data Gagal di generate, kemungkinan sudah tergenerate sebelumnya', 'danger');
                        }
                    });
                }else{
                    $('#submit').trigger("click");
                    console.log($(this));
                }
            });
            $('.datetimepicker').datetimepicker({
                viewMode: 'years',
                format: 'MM/YYYY',
                // minDate : '<?=date("Y-m-01", strtotime("+1 month", strtotime(date("Y/m/01"))))?>'

            });
            $('.select2').select2();
            $("#unit").select2({
                width: 'resolve',
                // resize:true,
                minimumInputLength: 1,
                placeholder: 'Kawasan - Blok - Unit - Pemilik',
                ajax: {
                    type: "GET",
                    dataType: "json",
                    url: "<?= site_url() ?>/Transaksi/P_unit/get_ajax_unit2",
                    data: function(params) {
                        return {
                            data: params.term
                        }
                    },
                    processResults: function(data) {
                        console.log(data);
                        // Tranforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            });
            // $("#pt").change(function(){
            //     url = '<?= site_url() ?>/P_master_service/add_get_coa';
            //     $.ajax({
            //         type: "POST",
            //         data: {id:$(this).attr('data-transfer'),type:$(this).attr('data-type')},
            //         url: url,
            //         dataType: "json",
            //         success: function(data){
            //             console.log(data);
            //             // var items = []; 
            //             // $("#changeJP").attr("style","display:none");
            //             // $("#saveJP").removeAttr('style');
            //             // $("#jabatan").removeAttr('disabled');
            //             // $("#jabatan")[0].innerHTML = "";
            //             // $("#project")[0].innerHTML = "";
            //             // $("#jabatan").append("<option value='' selected disabled>Pilih Jabatan</option>");
            //             console.log($(this).attr('data-type'));
            //             $("#dataModal").html("");
            //             if(data[data.length-1] == 2){
            //                 console.log(data[0]);
            //                 for (i = 0; i < data[0].length; i++) { 
            //                     $.each(data[1], function(key, val){
            //                         if(val.name == data[0][i].name){
            //                             console.log(val.name);
            //                             $("#dataModal").append("<tr><th>"+data[0][i].name+"</th><td>"+val.value+"</td><td>"+data[0][i].value+"</td></tr>");        
            //                         }
            //                     }); 
            //                 }
            //             }else{
            //                 $.each(data, function(key, val){
            //                     if(data[data.length-1] == 1){
            //                         console.log(data);
            //                         if(val.name)
            //                             $("#dataModal").append("<tr><th>"+val.name.toUpperCase()+"</th><td></td><td>"+val.value+"</td></tr>");
            //                     }else if(data[data.length-1] == 2){

            //                     }else if(data[data.length-1] == 3){
            //                         console.log(data);
            //                         if(val.name)
            //                             $("#dataModal").append("<tr><th>"+val.name.toUpperCase()+"</th><td>"+val.value+"</td><td></td></tr>");
            //                     }
            //                 });
            //             }

            //         }
            //     });
            // });
        });
    </script>