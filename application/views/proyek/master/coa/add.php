<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?=base_url()?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?=base_url()?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<!DOCTYPE html>
    <div style="float:right">
        <h2>
            <button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
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
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_mappingCoa/save">
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">PT 
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="pt" required="required" class="select2 form-control col-md-7 col-xs-12" name="pt_id">
                    <option selected disabled>Pilih PT</option>
                    <?php
                        foreach($dataPT as $key => $v){
                            echo("<option value='$v[id]'>$v[name]</option>");
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">COA 
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="last-name" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_id">
                    <option selected disabled>Pilih COA</option>
                    <?php
                        foreach($dataCOA as $key => $v){
                            echo("<option value='$v[id]'>$v[code] - $v[description]</option>");
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textArea id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="description"></textArea>
            </div>
        </div>
        <div class="ln_solid"></div>
        <div class="form-group">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>

    
<script>
    $(function() {
        
        $('.select2').select2();

        // $("#pt").change(function(){
        //     url = '<?=site_url()?>/P_master_service/add_get_coa';
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