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
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_diskon/edit?id=<?=$this->input->get('id')?>">

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" class="form-control disabled-form"  required name="nama" placeholder="Masukkan Nama Golongan Diskon" value="<?=$dataDiskonSelect->name?>" disabled>
            </div>
          </div>

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_category_id">
                Purpose Unit
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="product_category_id" required="required" class="select2 form-control col-md-7 col-xs-12 disabled-form" name="product_category_id" style="width:100%" disabled>
                <option>--Pilih Purpose Unit--</option>
                    <?php
                        foreach ($dataProductCategory as $v) {
                            if($v['product_category_id'] == $dataDiskonSelect->product_category_id){
                                echo("<option value='$v[product_category_id]' selected>$v[product_category_name]</option>");
                            }else{
                                echo("<option value='$v[product_category_id]'>$v[product_category_name]</option>");
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textArea id="description" class="form-control col-md-7 col-xs-12 disabled-form" type="text" name="description"
                    disabled><?=$dataDiskonSelect->description?></textArea>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="">
                    <label>
                        <input id="active" type="checkbox" class="js-switch disabled-form" name="active" value='1' <?=$dataDiskonSelect->active==1?'checked':''?>
                        disabled/> Aktif
                    </label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <p>
                <table class="table table-responsive">
                    <thead>
						<tr>
                        <th>No</th>
                        <th>No Log </th>
                        <th>Service</th>
                        <th>COA</th>
                        <th>Jenis Diskon</th>
                        <th>Parameter</th>
                        <th>Nilai</th>
                        <th>Minimum Bulan</th>
                        <th>Hapus</th>
						</tr>
					</thead>
                    <tbody id="tbody_diskon_detail">
                        <?php



                            $i = 0;
                            $j = 0;
                            foreach ($dataDiskonDetail  as $key => $v){

                                    ++$j;
								if($v['delete'] == 0){
									++$i;
                                echo "<tr id='srow".$i."'>";
                                echo "<td hidden><input name='diskon_detail_id[]' value='$v[diskon_detail_id]'> </td>";
                                echo "<td class='no' >".$i.'</td>';
                                echo "<td class='nolog' >".$j.'</td>';
                                echo("<td>");
                                echo("<select class='form-control disabled-form select2' name='service[]' disabled>");
                                echo("<option value=''>--Pilih Jenis Service--</option>");
                                foreach ($dataService as $key => $c){
									if($v['service_id']==$c['id']){
										echo("<option value='$c[id]' selected>$c[name] </option>"); 
									}else{
										echo("<option value='$c[id]'>$c[name] </option>");  
									}
                                }
                                
                              

                                echo("</td>");
                                echo("<td>");
                                echo("<select class='form-control disabled-form select2' name='coa[]' disabled>");
                                echo("<option value=''>--Pilih Jenis COA--</option>");
                                foreach ($dataCOA as $key => $c){
									if($v['coa_mapping_id_diskon']==$c['id']){
										echo("<option value='$c[id]' selected>$c[coaCode] $c[coaName] - $c[ptName]  </option>"); 
									}else{
										echo("<option value='$c[id]'>$c[coaCode] $c[coaName] - $c[ptName] </option>");  
									}
								}
                                echo("</td>");
                                echo("<td>");
                                echo("<select class='form-control disabled-form select2' name='jenisDiskon[]' disabled>");
                                echo("<option value=''>--Pilih Jenis Diskon--</option>");
                               		if($v['flag_umum_event']=='1'){
                                        echo("<option value='1' selected>Umum </option>"); 
                                        echo("<option value='2' >Event </option>"); 
                                       }
                                    else if($v['flag_umum_event']=='2'){
                                            echo("<option value='2' selected>Event</option>"); 
                                            echo("<option value='1' >Umum</option>"); 
                                        }            
                                                                 
                                echo("</td>");
                                echo("<td>");
                                echo("<select class='form-control disabled-form' name='parameter_id[] select2' disabled>");
                                echo("<option value=''>--Pilih Parameter--</option>");
                               		if($v['parameter_id']=='1'){
                                        echo("<option value='1' selected>Bulan </option>"); 
                                        echo("<option value='2' >Rupiah </option>");
                                        echo("<option value='3' >Persen(%) </option>");
                                       }
                                    else if($v['parameter_id']=='2'){
                                        echo("<option value='1' >Bulan </option>"); 
                                        echo("<option value='2' selected >Rupiah </option>");
                                        echo("<option value='3' >Persen(%) </option>");
                                        }            
                                    else if($v['parameter_id']=='3'){
										echo("<option value='1' >Bulan </option>"); 
                                        echo("<option value='2' >Rupiah </option>");
                                        echo("<option value='3' selected >Persen(%) </option>");
									}
                                
                                echo("</td>");
                                echo("<td><input type='number' class='form-control disabled-form' name='nilai[]' placeholder='Masukkan Nilai Diskon'/ required value ='$v[nilai]' disabled></td>");
                                echo("<td><input type='number' class='form-control disabled-form' name='minimum_bulan[]' placeholder='Masukkan Minimum Bulan' required value ='$v[min_bulan]' disabled></td>");
                                echo "<td> <a class='btn btn-danger disabled-form' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow".$i."\"); return false;' disabled><i class='fa fa-trash'></i> </a></td>";
                                echo("</tr>");
                            }

                        }
                        ?>
                        <input id="idf" value="1" type="hidden" />
                    </tbody>
                </table>
                <button id='btn-add-diskon-detail' type="button" class="btn btn-danger pull-right disabled-form" disabled><i class="fa fa-plus"></i> Add Diskon Service</button>

            </pre>
        </div>

        <div class="col-md-12">
            <input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
            <input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
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
disableForm = 1;



    $(function() {
        $('.select2').select2();
        $('#btn-add-diskon-detail').click(function(){
            var row = "<tr>"
            +"</tr>";
        });

    });

    $("#btn-add-diskon-detail").click(function(){

       // alert('tess');
        if($(".no").html()){
            idf = parseInt($(".no").last().html()) + 1;
        }else{
            idf = 1;
        }

       

        var str =  "<tr id='srow" + idf + "'>"+
		            "<td hidden><input name='diskon_detail_id[]' value='0'></td>" +
					"<td class='no'>" + idf + "</td>" +
					"<td class='nolog' ></td>" +
                    "<td>"+
                        "<select class='select2 form-control col-md-7 col-xs-12' name='service[]'>"+
                            "<option value=''>--Pilih Jenis Service--</option>"+
                            "<?php foreach ($dataService as $key => $v){ ?>"+
                                "<option value='<?=$v['id']?>'><?=$v['name']?> </option>"+
                            "<?php } ?>"+
                        "</select>"+
                    "</td>"+
                    "<td>"+
                        "<select class='form-control select2' name='coa[]' placeholder='-- Masukkan COA --' required>"+
                            "<option value='' selected disabled>-- Masukkan COA --</option>"+
                            "<?php foreach ($dataCOA as $key => $v){ ?>"+
                                "<option value='<?=$v['id']?>'><?=$v['coaCode']?> <?=$v['coaName']?> - <?=$v['ptName']?> </option>"+
                            "<?php } ?>"+
                        "</select>"+
                    "</td>"+
                    "<td>"+
                        "<select class='form-control select2' name='jenisDiskon[]' placeholder='-- Masukkan Jenis Diskon --' required>"+
                        "<option value='' selected disabled>-- Masukkan Jenis Diskon --</option>"+
                        "<option value='1'>Umum</option>"+
                        "<option value='2'>Event</option>"+
                    "</td>"+
                    "<td>"+
                        "<select class='form-control select2' name='parameter_id[]' placeholder='-- Masukkan parameter --' required>"+
                            "<option value='' selected disabled>-- Masukkan Parameter --</option>"+
                            "<option value='1'>Bulan</option>"+
                            "<option value='2'>Rupiah</option>"+
                            "<option value='3'>Persen (%)</option>"+
                        "</select>"+
                    "</td>"+
                    "<td><input type='number' class='form-control' name='nilai[]' placeholder='Masukkan Nilai'/ required></td>"+
                    "<td><input type='number' class='form-control' name='minimum_bulan[]' placeholder='Masukkan Minimum Bulan'/ required></td>"+
                    "<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>"+
                "</tr>";
        $("#tbody_diskon_detail").append(str);    
        $('.select2').select2({
			width: 'resolve'
		});   
    });

    function hapusElemen(idf) {
        $(idf).remove();
        var idf = document.getElementById("idf").value;
        idf = idf-1;
        document.getElementById("idf").value = idf;
    }



    $("#btn-update").click(function(){
        disableForm = 0;
        $(".disabled-form").removeAttr("disabled");
        $("#btn-cancel").removeAttr("style");
        $("#btn-update").val("Update");
        $("#btn-update").attr("type","submit");
    });

    $("#btn-cancel").click(function(){
        disableForm = 1;
        $(".disabled-form").attr("disabled","")
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
                console.log(data);
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
    $('.select2').select2({ width: 'resolve' });


</script>