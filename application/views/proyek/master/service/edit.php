<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<script>
    $(".x_panel").hide();
</script>
<link rel="stylesheet" href="<?=base_url()?>vendors/select2/dist/css/select2.min.css">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>
<script src="<?=base_url()?>vendors/switchery/dist/switchery.min.js"></script>
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
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_service/edit?id=<?=$this->input->get('id')?>" autocomplete="off">
        <div class="x_content">
            <br>
            <div id="div_jenis_retribusi" class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">Jenis Service<span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-12 col-xs-2" hidden>
                    <div class="checkbox">
                        <label>
                            <p style="display:contents">List </p>
                            <input type="checkbox" class="js-switch" value="1" checked />
                            <p style="display:contents"> New</p>
                        </label>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-9">
                    <select type="text" disabled id="jenis_retribusi" required="required" class="select2 form-control col-md-7 col-xs-12" name="jenis_retribusi" style="width:100%">
                        <option disabled selected>-- Pilih Service Jenis --</option>
                        <?php foreach ($dataJenisService as $v): ?>
                            <option value="<?=$v->id?>" name_default="<?=$v->name_default?>" code_default="<?=$v->code_default?>" <?=($dataSelect->service_jenis_id == $v->id)?"selected":""?>><?=$v->jenis_service?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="code">
                    Kode - Name
                    <span class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <input type="text" disabled id="code" name="code" required="required" class="form-control col-md-7 col-xs-12" value="<?=$dataSelect->code?>">
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <input type="text" disabled id="nama_service" name="nama_service" required="required" class="form-control col-md-7 col-xs-12" value="<?=$dataSelect->name?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">
                    PT - COA Service
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <select type="text" disabled id="coa_mapping_id_service" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_mapping_id_service" style="width:100%" placeholder="--Pilih PT - COA Service--">
                        <option></option>
                        <?php foreach ($dataService as $v): ?>
                            <option value='<?=$v->id?>' <?=($dataSelect->service_coa_mapping_id == $v->id)?"selected":""?>><?="$v->ptName $v->coaCode - $v->coaName"?></option>;
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">
                    PT - COA Denda/Penalti Service
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <select type="text" disabled id="coa_mapping_id_service_denda" required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_mapping_id_service_denda" style="width:100%" placeholder="--Pilih PT - COA Denda/Penalti Service--">
                        <option></option>
                        <?php foreach ($dataService as $v) : ?>
                            <option value='<?=$v->id?>' <?=($dataSelect->denda_penalti_coa_mapping_id == $v->id)?"selected":""?>><?="$v->ptName $v->coaCode - $v->coaName"?></option>;
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12">PPN - Coa PPN</label>
                <div class="col-md-2 col-sm-12 col-xs-2">
                    <div class="checkbox">
                        <label id="label_ppn">
                            <p style="display:contents">Unuse</p>
                            <input id="ppn" type="checkbox" disabled class="ppn_flag js-switch" name="ppn_flag" value="1" <?=($dataSelect->ppn_coa_mapping_id)?"checked":"unchecked"?> />
                            <p style="display:contents">Use</p>
                        </label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-9">
                    <select type="text" id="coa_mapping_id_ppn" disabled required="required" class="select2 form-control col-md-7 col-xs-12" name="coa_mapping_id_ppn" style="width:100%" placeholder="-- COA PPN--">
                        <option></option>
                        <?php foreach ($dataService as $v): ?>
                            <option value='<?=$v->id?>' <?=($dataSelect->ppn_coa_mapping_id == $v->id)?"selected":""?>><?="$v->ptName $v->coaCode - $v->coaName"?></option>;

                        <?php endforeach;?>
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Jarak Periode Tagihan - Periode Penggunaan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <input type="text" id="jarak_periode_penggunaan" disabled required="required" class="form-control col-md-7 col-xs-12 currency" maxlength="2"name="jarak_periode_penggunaan" placeholder="--Masukkan Jarak Periode Penggunaan--" value="<?=$dataSelect->jarak_periode_penggunaan?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-12 col-xs-12" for="pilih pt">
                    Parameter Tanggal <br>Jatuh Tempo Tagihan
                    <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <select type="text" id="parameter_tanggal_jatuh_tempo" disabled required="required" class="select2 form-control col-md-7 col-xs-12" name="parameter_tanggal_jatuh_tempo" style="width:100%" placeholder="--Pilih PT - COA Service--">
                        <option></option>
                        <?php for ($i = 1; $i <= 31; $i++): ?>
                            <option value="<?=$i?>" <?=($dataSelect->tgl_jatuh_tempo == $i)?"selected":""?>><?=$i?></option>
                        <?php endfor;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="middle-name" class="control-label col-md-3 col-sm-12 col-xs-12">Keterangan</label>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <textarea class="form-control col-md-7 col-xs-12" disabled type="text" id="keterangan" name="description"><?=$dataSelect->description?></textarea>
                </div>
            </div>

            <br>

            <br>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Denda <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="checkbox">
                            <label id="label_ppn">
                                <p style="display:contents">Unuse</p>
                                <input id="denda" type="checkbox" disabled class="denda_flag js-switch" name="denda_flag" value="1" disabled <?=($dataSelect->denda_flag)?"checked":"unchecked"?> />
                                <p style="display:contents">Use</p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-8" for="pilih pt">Selisih Bulan<br>(Bulan)<span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <input type="text" id="selisih_bulan_denda" disabled required="required" class="form_denda form-control col-md-7 col-xs-12 currency" name="selisih_bulan_denda" placeholder="--Masukkan Selisih Bulan--" value="<?=$dataSelect->denda_selisih_bulan?>">
                    </div>
                    <label class="control-label col-md-2 col-sm-3 col-xs-8" for="pilih pt">Tanggal<span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <select type="text" id="tanggal_denda" disabled required="required" class="form_denda select2 form-control col-md-7 col-xs-12" name="tanggal_denda" style="width:100%" placeholder="--Pilih Tanggal Denda--">
                            <option></option>
                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                <option value="<?=$i?>" <?= $dataSelect->denda_tanggal_jt == $i ? 'selected' : '' ?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Jenis Denda <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <select type="text" id="denda_jenis" disabled required="required" class="form_denda select2 form-control col-md-7 col-xs-12" name="denda_jenis" style="width:100%" placeholder="--Pilih Jenis Denda--">
                            <option></option>
                            <option value="1" <?= $dataSelect->denda_jenis == 1 ? 'selected' : '' ?>>Fixed</option>
                            <option value="2" <?= $dataSelect->denda_jenis == 2 ? 'selected' : '' ?>>Progresif</option>
                            <option value="3" <?= $dataSelect->denda_jenis == 3 ? 'selected' : '' ?>>Persen Nilai Piutang</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Minimum Denda <span class="required">*<br>(Rp)</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="denda_minimum" disabled required="required" class="form_denda form-control col-md-7 col-xs-12 currency" name="denda_minimum" placeholder="--Masukkan Minimum Denda--" value="<?=$dataSelect->denda_minimum?>">
                    </div>
                </div>
                <div class="form-group">
                    <label id="label_nilai_denda" class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Nilai Denda <span class="required">*<br>(Rp)</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="number" id="denda_nilai" disabled required="required" class="form_denda form-control col-md-7 col-xs-12" name="denda_nilai" placeholder="--Masukkan Nilai Denda--" value="<?=$dataSelect->denda_nilai?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Denda Tanggal Putus</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="denda_tgl_putus" class="js-switch disabled-form" name="denda_tgl_putus" value="1" disabled <?=($dataSelect->denda_tgl_putus_flag)?"checked":"unchecked"?>  /> Flag
                            </label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Penalti <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="checkbox">
                            <label id="label_ppn">
                                <p style="display:contents">Unuse</p>
                                <input id="penalti" type="checkbox" class="form_penalti js-switch" name="penalti_flag" value='1' disabled <?=($dataSelect->penalti_flag)?"checked":"unchecked"?>/>
                                <p style="display:contents">Use</p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-8" for="pilih pt">Selisih Bulan<br>(Bulan)<span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <input type="text" id="selisih_bulan_penalti" disabled required="required" class="form_penalti form-control col-md-7 col-xs-12 currency" name="selisih_bulan_penalti" placeholder="--Masukkan Selisih Bulan--" value="<?=$dataSelect->penalti_selisih_bulan?>">
                    </div>
                    <label class="control-label col-md-2 col-sm-3 col-xs-8" for="pilih pt">Tanggal<span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <select type="text" id="tanggal_penalti" disabled required="required" class="form_penalti select2 form-control col-md-7 col-xs-12" name="tanggal_penalti" style="width:100%" placeholder="--Pilih Tanggal Denda--">
                            <option></option>
                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                <option value="<?=$i?>" <?= $dataSelect->penalti_tanggal_jt == $i ? 'selected' : '' ?>><?=$i?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Jenis Penalti <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <select type="text" id="penalti_jenis" disabled required="required" class="form_penalti select2 form-control col-md-7 col-xs-12" name="penalti_jenis" style="width:100%" placeholder="--Pilih Jenis Penalti--">
                            <option></option>
                            <option value="1"<?= $dataSelect->penalti_jenis == 1 ? 'selected' : '' ?>>Fixed</option>
                            <option value="3" <?= $dataSelect->penalti_jenis == 3 ? 'selected' : '' ?>>Persen Nilai Piutang</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Minimum Penalti <span class="required">*<br>(Rp)</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="penalti_minimum" disabled required="required" class="form_penalti form-control col-md-7 col-xs-12 currency" name="penalti_minimum" placeholder="--Masukkan Minimum Penalti--" value="<?=$dataSelect->penalti_minimum?>">
                    </div>
                </div>
                <div class="form-group">
                    <label id="label_nilai_penalti" class="control-label col-md-3 col-sm-3 col-xs-12" for="pilih pt">Nilai Penalti <span class="required">*<br>(Rp)</span>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="number" id="penalti_nilai" disabled required="required" class="form_penalti form-control col-md-7 col-xs-12" name="penalti_nilai" placeholder="--Masukkan Nilai Penalti--" value="<?=$dataSelect->penalti_nilai?>">
                    </div>
                </div>
                <div class="form-group" hidden>
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Penalti Tanggal Putus</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" disabled class="form_penalti js-switch" name="penalti_tgl_putus" value="1" checked /> Flag
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <!-- <button id="btn-Reset" type="reset" class="btn btn-primary col-md-1 col-md-offset-4" disabled>Reset</button> -->
                <input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
                <input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
            </div>
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

    for(i=0;i<$('.select2').length;i++){
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
    function currency(inp){
        var input = inp.val().toString().replace(/[\D\s\._\-]+/g, "");  
        input = input ? parseInt( input, 10 ) : 0;
        console.log("test");
        console.log(( input === 0 ) ? "" : input.toLocaleString( "en-US" ));
        return ( input === 0 ) ? "" : input.toLocaleString( "en-US" ) ;
    }
    $(document).ready(function() {	
        
        $("#denda_minimum").val( currency($("#denda_minimum")) );
        $("#btn-update").click(function(){
            $("#coa_mapping_id_service").removeAttr("disabled");
            $("#coa_mapping_id_service_denda").removeAttr("disabled");
            
            $("#ppn").removeAttr("disabled");
            $("#denda").removeAttr("disabled");
            $("#penalti").removeAttr("disabled");
            $("#jarak_periode_penggunaan").removeAttr("disabled");
            $("#coa_mapping_id_ppn").removeAttr("disabled");
            $("#denda_parameter").removeAttr("disabled");
            $("#denda_jenis").removeAttr("disabled");
            $("#denda_minimum").removeAttr("disabled");
            $("#parameter_tanggal_jatuh_tempo").removeAttr("disabled");
            $("#denda_tanggal_putus").removeAttr("disabled");
            $("#selisih_bulan_denda").removeAttr("disabled");
            $("#denda_nilai").removeAttr("disabled");
            $("#selisih_bulan_penalti").removeAttr("disabled");
            $("#active").removeAttr("disabled");
            $("#penalti_jenis").removeAttr("disabled");
            $("#denda_tgl_putus").removeAttr("disabled");
            
            $("#penalti_minimum").removeAttr("disabled");
            $("#penalti_nilai").removeAttr("disabled");
            $("#tanggal_penalti").removeAttr("disabled");
            
            $("#tanggal_denda").removeAttr("disabled");
            $("#keterangan").removeAttr("disabled");
            $("#btn-cancel").removeAttr("style");

            if($("#denda_jenis").val()==3){
                $("#denda_persen").removeAttr("disabled");
            }
            $("#btn-update").val("Update");
			setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
        });
        $("#btn-cancel").click(function(){
            $("#jenis_retribusi").attr("disabled","")
            $("#coa_mapping_id_service_denda").attr("disabled","");

            $("#code").attr("disabled","")
            $("#denda").removeAttr("disabled");
            $("#penalti").removeAttr("disabled");
            $("#nama_service").attr("disabled","")
            $("#penalti_jenis").attr("disabled","")
            $("#coa_mapping_id_service").attr("disabled","")
            $("#ppn").attr("disabled","")
            $("#jarak_periode_penggunaan").attr("disabled","")
            $("#coa_mapping_id_ppn").attr("disabled","")
            $("#denda_parameter").attr("disabled","")
            $("#denda_jenis").attr("disabled","")
            $("#denda_tgl_putus").removeAttr("disabled");
            $("#denda_minimum").attr("disabled","")
            $("#parameter_tanggal_jatuh_tempo").attr("disabled","")
            $("#denda_persen").attr("disabled","")
            $("#tanggal_denda").attr("disabled","")
            $("#tanggal_penalti").attr("disabled","")
            $("#denda_nilai").attr("disabled","")
            $("#selisih_bulan_penalti").attr("disabled","")
            $("#denda_tanggal_putus").attr("disabled","")
            $("#penalti_minimum").attr("disabled","")
            $("#penalti_nilai").attr("disabled","")
            $("#active").attr("disabled","")
            $("#selisih_bulan_denda").attr("disabled","")
            $("#keterangan").attr("disabled","")
            $("#btn-cancel").attr("style","display:none");
            $("#btn-update").val("Edit")
            $("#btn-update").removeAttr("type");
        });

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

        $("#denda").click(function(){
            if ($("#denda").is(':checked')) {
                $("#selisih_bulan_denda").removeAttr("disabled");
                $("#tanggal_denda").removeAttr("disabled");
                $("#denda_jenis").removeAttr("disabled");
                $("#denda_minimum").removeAttr("disabled");
                $("#denda_nilai").removeAttr("disabled");
                
            }else{
                $("#selisih_bulan_denda").val("").trigger("change");
                $("#tanggal_denda").val("").trigger("change");
                $("#denda_jenis").val("").trigger("change");
                $("#denda_minimum").val("").trigger("change");
                $("#denda_nilai").val("").trigger("change");

                $("#selisih_bulan_denda").attr("disabled","disabled");
                $("#tanggal_denda").attr("disabled","disabled");
                $("#denda_jenis").attr("disabled","disabled");
                $("#denda_minimum").attr("disabled","disabled");
                $("#denda_nilai").attr("disabled","disabled");
            }
        });

        $("#penalti").click(function(){
            if ($("#penalti").is(':checked')) {
                $("#selisih_bulan_penalti").removeAttr("disabled");
                $("#tanggal_penalti").removeAttr("disabled");
                $("#penalti_jenis").removeAttr("disabled");
                $("#penalti_minimum").removeAttr("disabled");
                $("#penalti_nilai").removeAttr("disabled");
                
            }else{
                $("#selisih_bulan_penalti").val("").trigger("change");
                $("#tanggal_penalti").val("").trigger("change");
                $("#penalti_jenis").val("").trigger("change");
                $("#penalti_minimum").val("").trigger("change");
                $("#penalti_nilai").val("").trigger("change");

                $("#selisih_bulan_penalti").attr("disabled","disabled");
                $("#tanggal_penalti").attr("disabled","disabled");
                $("#penalti_jenis").attr("disabled","disabled");
                $("#penalti_minimum").attr("disabled","disabled");
                $("#penalti_nilai").attr("disabled","disabled");
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

<!-- jQuery -->
<script type="text/javascript">
    for (i = 0; i < $('.select2').length; i++) {
        console.log($('.select2')[i].id);
        $('#' + $('.select2')[i].id).select2({
            placeholder: $('#' + $('.select2')[i].id).attr("placeholder")
        });
    }

    $(document).ready(function() {
        $("#denda_flag").change(function(){
            if($("#denda_flag").is(':checked')){
                $(".denda_flag").attr('disabled',false);
            }else{
                $(".denda_flag").attr('disabled','disabled');
            }
            console.log('denda_flag change');
        })
        $("#penalti_flag").change(function(){
            if($("#penalti_flag").is(':checked')){
                $(".form_penalti").attr('disabled',false);
            }else{
                $(".form_penalti").attr('disabled','disabled');
            }
            console.log('denda_flag change');
        })
        $("#ppn").change(function(){
            if($("#ppn").is(':checked')){
                $(".ppn_flag").attr('disabled',false);
            }else{
                $(".ppn_flag").attr('disabled','disabled');
            }
            console.log('denda_flag change');
        })
        $("#denda_jenis").change(function(){
            if($(this).val()==3)
                $("#label_nilai_denda").html("Nilai Denda <span class='required'>*</span><br>(%)");
            else
                $("#label_nilai_denda").html("Nilai Denda <span class='required'>*</span><br>(Rp)");
        });
        $("#penalti_jenis").change(function(){
            if($(this).val()==3)
                $("#label_nilai_penalti").html("Nilai Penalti <span class='required'>*</span><br>(%)");
            else
                $("#label_nilai_penalti").html("Nilai Penalti <span class='required'>*</span><br>(Rp)");
        });
        $("#jenis_retribusi").change(function() {
            $("#code").val($('#jenis_retribusi')[0].options[$('#jenis_retribusi')[0].selectedIndex].getAttribute('code_default'));
            $("#nama_service").val($('#jenis_retribusi')[0].options[$('#jenis_retribusi')[0].selectedIndex].getAttribute('name_default'));
        });

        $("#non_retribusi").change(function() {
            // $("#div_jenis_retribusi").attr('style','display:none');
            $("#code").prop("readonly", false);
            $("#nama_service").prop("readonly", false);
            $("#code").val("");
            $("#nama_service").val("");
            $("#jenis_retribusi").attr("disabled", "disabled");
            $("#jenis_retribusi").val("").trigger("change");
        });
        $("#retribusi").change(function() {
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").removeAttr("disabled");
            $("#code").val("");
            $("#code").prop("readonly", true);
            $("#nama_service").prop("readonly", true);
        });
        $("#TVI").change(function() {
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").attr("disabled", "disabled");
            $("#code").prop("readonly", true);
            $("#code").val("4. TV & Internet");
            $("#nama_service").val("TV & Internet");
            $("#nama_service").prop("readonly", true);
            $("#jenis_retribusi").val("").trigger("change");
        });
        $("#LO").change(function() {
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").attr("disabled", "disabled");
            $("#code").prop("readonly", true);
            $("#code").val("5. Liaison Officer");
            $("#nama_service").val("Liaison Officer");
            $("#nama_service").prop("readonly", true);
            $("#jenis_retribusi").val("").trigger("change");
        });
        $("#sewa_properti").change(function() {
            // $("#div_jenis_retribusi").removeAttr('style');
            $("#jenis_retribusi").attr("disabled", "disabled");
            $("#code").prop("readonly", true);
            $("#code").val("6. Sewa Properti");
            $("#nama_service").val("Sewa Properti");
            $("#nama_service").prop("readonly", true);
            $("#jenis_retribusi").val("").trigger("change");
        });
        $(".x_panel").show();

    });
</script>