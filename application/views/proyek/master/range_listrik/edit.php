<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>

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
		<button class="btn btn-primary" onClick="window.location.href='<?=site_url(); ?>/P_master_range_listrik/edit?id=<?=$dataRangeListrik[0]['id']?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br />
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_master_range_listrik/edit?id=<?=$this->input->get('id'); ?>">
		
		
		
		 <div class="col-md-5 col-xs-12">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Range</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control disabled-form" required name="kode_range" placeholder="Masukkan Kode Range" value="<?=$data_select->code; ?>"
					disabled> 
            </div>  
			</div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control disabled-form"  required name="name" placeholder="Masukkan Nama Range" value="<?=$data_select->name; ?>"
					disabled>
            </div>
          </div>
           <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Formula </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select name="formula" id="formula" class="form-control select2 disabled-form" placeholder="-- Masukkan Formula --" disabled>
                        <option value="" disabled>--Pilih Formula --</option>
                        <option value="1" <?=$data_select->formula==1?'selected':''?> >1</option>
                        <option value="2" <?=$data_select->formula==2?'selected':''?> >2</option>
                        <option value="3" <?=$data_select->formula==3?'selected':''?> >3</option>
                        <option value="4" <?=$data_select->formula==4?'selected':''?> >4</option>

                    </select>
                </div>
            </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <textarea class="form-control disabled-form" rows="3" name="keterangan" placeholder='Masukkan Keterangan' 
					disabled><?=$data_select->description; ?></textarea>
            </div>
          </div>
		  <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">Range Fix (Rp)</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
			<input type="text" class="form-control disabled-form currency"  id="range_fix" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  required name="range_fix" placeholder="Masukkan Range Fix" value="<?=$data_select->range_fix; ?>" disabled>
			</div>
          </div>

         
     	  <div class="col-md-6 col-xs-12">
				<label class="control-label col-md-6 col-sm-6 col-xs-12">Status</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="">
					<label>
						<input id="active" type="checkbox" class="js-switch disabled-form" name="active" value='1' <?=$data_select->active == 1 ? 'checked' : ''; ?> disabled/> Aktif
					</label>
					</div>
				</div>
			</div>
          
        </div>
        <div class="col-md-7 col-xs-12">
          <table class="table table-responsive">
            <thead>
              <tr>
                <th>Range</th>
				<th>No Log </th>
                <th>Range Awal</th>
                <th>Range Akhir</th>
                <th>Harga HPP</th>
                <th>Harga Range</th>
                <th>Hapus</th>
              </tr>
            </thead>
			 <tbody id="tbody_range"> 

                <?php
							$i = 0;
							$j = 0;
							//var_dump($dataRangeAirDetail);
                            foreach ($dataRangeListrikDetail as $v) {
								++$j;
								if($v['delete'] == 0){
									++$i;
									echo "<tr id='srow".$i."'>";
									echo "<td hidden><input name='id_range_listrik_detail[]' value='$v[id]'> </td>";
									echo "<td class='no' >".$i.'</td>';
									echo "<td class='nolog' >".$j.'</td>';
									echo "<td><input type='text' class='form-control disabled-form currency' name='range_awal[]' placeholder='Masukkan Range Awal'/ required value ='$v[range_awal]' disabled></td>";
									echo "<td><input type='text' class='form-control disabled-form currency' name='range_akhir[]' placeholder='Masukkan Range Akhir'/ required value ='$v[range_akhir]' disabled></td>";
									echo "<td><input type='text' class='form-control disabled-form currency' name='harga_hpp[]' placeholder='Masukkan Harga HPP'/ required value ='$v[harga_hpp]' disabled></td>";
									echo "<td><input type='text' class='form-control disabled-form currency' name='harga_range[]' placeholder='Masukkan Harga Range'/ required value ='$v[harga]' disabled></td>";
								    echo "<td> <a class='btn btn-danger disabled-form' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow".$i."\"); return false;' disabled><i class='fa fa-trash'></i> </a></td>";
									echo '</tr>';
								}
                            }
                        ?>
			 
                    <input id="idf" value="1" type="hidden" />

                </tbody>
            </table>
            <button type="button" id='btn-add-range' class="btn btn-danger pull-right disabled-form" disabled><i class="fa fa-plus"></i> Add Range</button>
        </div>
		
		<div class="col-md-12">
			<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
			<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
		</div>
	</form>
</div>
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
            foreach ($data as $key => $v) {
                ++$i;
                echo '<tr>';
                echo "<td>$i</td>";
                echo "<td>$v[date]</td>";
                echo "<td>$v[name]</td>";
                echo '<td>';
                if ($v['status'] == 1) {
                    echo 'Tambah';
                } elseif ($v['status'] == 2) {
                    echo 'Edit';
                } else {
                    echo 'Hapus';
                }
                echo '</td>';
                echo "
                    <td class='col-md-1'>
                        <a class='btn-modal btn btn-sm btn-primary col-md-12' data-toggle='modal' data-target='#modal' data-transfer='$v[id]' data-type='$v[status]'>
                            <i class='fa fa-pencil'></i>
                        </a>
                    </td>
                ";
                echo '</td></tr>';
            }
        ?>
			</tbody>
		</table>
	</div>
</div>


<<!-- jQuery -->
<script type="text/javascript">	
    	disableForm = 1;	
    $(function() {
        $('#btn-add-range').click(function(){
            var row = "<tr>"
            +"</tr>";
        });
    });

    $("#btn-add-range").click(function(){
        if($(".no").html()){
            idf = parseInt($(".no").last().html()) + 1;
        }else{
            idf = 1;
        }
        var str = "<tr id='srow" + idf + "'>"+
		            "<td hidden><input name='id_range_listrik_detail[]' value='0'></td>" +
					"<td class='no'>" + idf + "</td>" +
					"<td class='nolog' ></td>" +
					"<td><input type='text' class='form-control currency'  name='range_awal[]' value='0' placeholder='' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' /></td>" +
                    "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' name='range_akhir[]' value='0' class='form-control currency'/></td>" +
					"<td><input type='text' class='form-control currency' value='0' name='harga_hpp[]' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);'  /></td>" +
					"<td><input type='text' class='form-control currency' value='0' name='harga_range[]' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);'  /></td>" +
					"<td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>"+
                "</tr>";
        $("#tbody_range").append(str);
    });
	
	
	
	
	 

function hapusElemen(idf) {
    $(idf).remove();
    var idf = document.getElementById("idf").value;
    idf = idf-1;
    document.getElementById("idf").value = idf;
}

        $.each($(".currency"), function( index, currency ) {
			currency.value = parseInt( currency.value.toString().replace(/[\D\s\._\-]+/g, ""), 10 ).toLocaleString("en-US");
		});


        $("#btn-update").click(function () {
			disableForm = 0;
			$(".disabled-form").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
		});
		$("#btn-cancel").click(function () {
			disableForm = 1;
			$(".disabled-form").attr("disabled", "")
			$("#btn-cancel").attr("style", "display:none");
			$("#btn-update").val("Edit")
			$("#btn-update").removeAttr("type");
		});
		
		
		$(".btn-modal").click(function () {
			url = '<?=site_url(); ?>/core/get_log_detail';
			console.log($(this).attr('data-transfer'));
			console.log($(this).attr('data-type'));
			$.ajax({
				type: "POST",
				data: {
					id: $(this).attr('data-transfer'),
					type: $(this).attr('data-type')
				},
				url: url,
				dataType: "json",
				success: function (data) {
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
					if (data[data.length - 1] == 2) {
						console.log(data[0]);
						for (i = 0; i < data[0].length; i++) {
							var tmpj = 0;
							for (j = 0; j < data[0].length; j++) {
								if(data[1][j] != null){
									if (data[1][j].name == data[0][i].name) {
										$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + data[1][j].value + "</td><td>" + data[0]
											[i].value + "</td></tr>");
										tmpj ++;
									}
									
								}
							}
							if(tmpj == 0){
								$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td></td><td>" + data[0]
											[i].value + "</td></tr>");
							}
						}	
						
						// 	if(data[1].length > data[0].length){
						// 		$.each(data[1], function (key, val) {
						// 			if (val.name == data[0][i].name) {
						// 				console.log(val.name);
						// 				$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0]
						// 					[i].value + "</td></tr>");
						// 			}
						// 		});
						// 	}else{
						// 		$.each(data[0], function (key, val) {
						// 			if (val.name == data[1][i].name) {
						// 				console.log(val.name);
						// 				$("#dataModal").append("<tr><th>" + data[1][i].name + "</th><td>" + val.value + "</td><td>" + data[1]
						// 					[i].value + "</td></tr>");
						// 			}
						// 		});
						// 	}
						// }
					} else {
						$.each(data, function (key, val) {
							if (data[data.length - 1] == 1) {
								console.log(data);
								if (val.name)
									$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td></td><td>" + val.value +
										"</td></tr>");
							} else if (data[data.length - 1] == 2) {

							} else if (data[data.length - 1] == 3) {
								console.log(data);
								if (val.name)
									$("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td>" + val.value +
										"</td><td></td></tr>");
							}
						});
					}

				}
			});

		});
		$('.select2').select2({
			width: 'resolve'
		});
	
	$(document).keydown(function (e) {  
		return (e.which || e.keyCode) != 116;  
	});

	$(document).keydown(function (e) {  
		if(e.ctrlKey){
			return (e.which || e.keyCode) != 82;
		}
	});
	



</script>


