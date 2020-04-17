<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>

<link rel="stylesheet" href="<?=base_url()?>vendors/select2/dist/css/select2.min.css">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>
<script src="<?=base_url()?>vendors/switchery/dist/switchery.min.js"></script>
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
            <button class="btn btn-warning" onclick="location.href='<?=site_url()?>/P_master_service';">
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
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_service/save">
        <div class="x_content">
            <br>
            <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Service</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="">
                    <label class="label_jenis_service btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                        <input id="retribusi" type="radio" class="input_jenis_service" name="jenis_service" value="Retribusi" checked> &nbsp; Retribusi &nbsp;
                    </label>
                    <label class="label_jenis_service btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                        <input id="non_retribusi" type="radio" class="input_jenis_service" name="jenis_service" value="NonRetribusi"> Non Retribusi
                    </label>
                    </div>
                </div>
            </div>
			<div id="div_jenis_retribusi" class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Jenis Retribusi<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select type="text" id="jenis_retribusi" required="required" class="select2 form-control col-md-7 col-xs-12" name="jenis_retribusi" style="width:100%" placeholder="--Pilih Retribusi--">
                        <option></option>
                        <option value="1">Air</option>
                        <option value="2">Lingkungan</option>
                        <option value="3">Listrik</option>
                    </select>
                </div>
            </div>
		    <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Kode <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="code" name="code" required="required" class="form-control col-md-7 col-xs-12" readonly="true">
                </div>
            </div>
			<div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_pembayaran">Nama Service <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="nama_service" name="nama_service" required="required" class="form-control col-md-7 col-xs-12" readonly="true">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">
                    PT - COA Service
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select type="text" id="coa_mapping_id_service" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_mapping_id_service" style="width:100%" placeholder="--Pilih PT - COA Service--">
                    <option></option>
                        <?php
                            foreach ($dataService as $v) {
                                echo("<option value='$v[id]'>$v[pt_name] - $v[coa_name]</option>");
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">PPN</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="checkbox">
                        <label id="label_ppn">
                            <input id="ppn" type="checkbox" class="js-switch" name="ppn" value="1" checked /> PPN 10%
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">
                    PT - COA PPN
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select type="text" id="coa_mapping_id_ppn" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_mapping_id_ppn" style="width:100%" placeholder="--Pilih PT - COA PPN--">
                    <option></option>
                        <?php
                            foreach ($dataService as $v) {
                                echo("<option value='$v[id]'>$v[pt_name] - $v[coa_name]</option>");
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Parameter Denda <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select type="text" id="denda_parameter" required="required" class="select2 form-control col-md-7 col-xs-12" name="denda_parameter" style="width:100%" placeholder="--Pilih Parameter Denda--">
                        <option></option>
                        <option value="1">Bulan yang Sama</option>
                        <option value="2">Bulan yang Berbeda</option>                
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Jenis Denda <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select type="text" id="denda_jenis" required="required" class="select2 form-control col-md-7 col-xs-12" name="denda_jenis" style="width:100%" placeholder="--Pilih Jenis Denda--">
                        <option></option>
                        <option value="1">Fixed</option>
                        <option value="2">Progresif</option>
                        <option value="3">Persen Nilai Piutang</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Minimum Denda <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="number" id="denda_minimum" required="required" class="form-control col-md-7 col-xs-12" name="denda_minimum" placeholder="--Masukkan Minimum Denda--">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Persen Denda (%) <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="number" id="denda_persen" required="required" class="form-control col-md-7 col-xs-12" name="denda_persen" placeholder="--Masukkan Persen Denda--" disabled="disabled">

                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Denda Tanggal Putus</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="js-switch" name="denda_tgl_putus" value="1" checked /> Flag
                        </label>
                    </div>
                </div>
            </div>
			<div class="form-group">
                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="description"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="center-margin">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
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

    for(i=0;i<$('.select2').length;i++){
        console.log($('.select2')[i].id);
        $('#'+$('.select2')[i].id).select2({
            placeholder: $('#'+$('.select2')[i].id).attr("placeholder")
        });
    }
    

    function hapusElemen(idf) {
        $(idf).remove();
        var idf = document.getElementById("idf").value;
        idf = idf-1;
        document.getElementById("idf").value = idf;
    }

    $(document).ready(function() {	
        
        // $(".numberings").number(true);
        $(".input_jenis_service").click(function(){
            $(".label_jenis_service").removeClass("btn-primary");
            $(".label_jenis_service").addClass("btn-default");
            $(this).parent().addClass("btn-primary");
        });
        $("#pt").change(function(){
            console.log($(this).val());
            $.ajax({
                type: "POST",
                data: {id:$(this).val()},
                url: "<?=site_url()?>/P_master_service/add_get_coa",
                dataType: "json",
                success: function(data){
                    console.log(data);
                    $("#coa").html("");
                    $("#coa").append("<option></option>");
                    for(var i = 0;i < data.length;i++){
                        $("#coa").append("<option value='"+data[i].id+"'>"+data[i].name+"</option>");
                    }
                }
            });
        });
        $("#jenis_retribusi").change(function(){
            if($(this).val() == 1){
                $("#code").val("1. Air");
                $("#nama_service").val("Air");
            }else if($(this).val() == 2){
                $("#code").val("2. Lingkungan");
                $("#nama_service").val("Linkungan");
            }else if($(this).val() == 3){
                $("#code").val("3. Listrik");
                $("#nama_service").val("Listrik");
            }
        });
        $("#non_retribusi").change(function(){
            // $("#div_jenis_retribusi").attr('style','display:none');
            $("#code").prop("readonly", false);
            $("#nama_service").prop("readonly", false);
            $("#code").val("");
            $("#nama_service").val("");
            $("#jenis_retribusi").attr("disabled","disabled");
            $("#jenis_retribusi").val("").trigger("change");
        });
        $("#retribusi").change(function(){
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").removeAttr("disabled");
            $("#code").prop("readonly", true);
            $("#nama_service").prop("readonly", true);
        });
        $("#ppn").click(function(){
            if ($("#ppn").is(':checked')) {
                $("#coa_mapping_id_ppn").removeAttr("disabled");
            }else{
                $("#coa_mapping_id_ppn").val("").trigger("change");

                $("#coa_mapping_id_ppn").attr("disabled","disabled");
            }
        });
        $("#denda_jenis").change(function(){
            if($("#denda_jenis").val() == 1 || $("#denda_jenis").val() == 2){
                $("#denda_persen").val("").trigger("change");
                $("#denda_persen").attr("disabled","disabled");
            }else{
                $("#denda_persen").removeAttr("disabled");
            }
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
