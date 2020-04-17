<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link href="<?=base_url()?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?=base_url()?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>

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
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_mappingCoa/edit?id=<?=$this->input->get('id')?>">
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pt_id">PT 
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="pt_id" required="required" class="select2 form-control col-md-7 col-xs-12" name="pt_id" disabled>
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
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coa_id">COA 
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="coa_id" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_id" disabled>
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
                <textArea id="description" class="form-control col-md-7 col-xs-12" type="text" name="description" disabled><?=$data_select->description?></textArea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="">
                <label>
                    <input id="status" type="checkbox" class="js-switch" name="status" value='1' <?=$data_select->active==1?'checked':''?> disabled/> Aktif
                </label>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <!-- <button id="btn-Reset" type="reset" class="btn btn-primary col-md-1 col-md-offset-4" disabled>Reset</button> -->
            <input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
            <input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
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

<!-- Modal -->


<script>
    $(function() {
        
        


        //selected
        $("#pt_id").val(<?=$data_select->pt_id?>);
        $("#coa_id").val(<?=$data_select->coa_id?>);
        $("#btn-update").click(function(){
            $("#pt_id").removeAttr("disabled");
            $("#coa_id").removeAttr("disabled");
            $("#description").removeAttr("disabled");
            $("#status").removeAttr("disabled");
            $("#btn-cancel").removeAttr("style");
            $("#btn-update").val("Update");
			setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
        });
        $("#btn-cancel").click(function(){
            $("#pt_id").attr("disabled","")
            $("#coa_id").attr("disabled","")
            $("#description").attr("disabled","")
            $("#status").attr("disabled","")
            $("#pt_id").attr("disabled","")
            $("#btn-cancel").attr("style","display:none");
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
        $('.select2').select2();
    });

</script>