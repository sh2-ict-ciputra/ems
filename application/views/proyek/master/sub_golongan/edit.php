<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

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
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_master_sub_golongan/edit?id=<?=$this->input->get('id'); ?>" autocomplete="false">
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Golongan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="golongan" class="form-control select2 disabled-form" disabled>
						<option value="" disabled="">--Pilih Golongan--</option>
						<?php
                            foreach ($dataGolongan as $v) {
                                echo "<option value='$v[id]' ";
								echo $v['id'] == $dataSelect->jenis_golongan_id ? 'selected' : '';
								echo ">$v[description]</option>";
                            }
                        ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Sub </label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" name="code" class="form-control disabled-form" placeholder="Masukkan Kode Sub Golongan" value="<?=$dataSelect->code; ?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Sub</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" name="nama_sub" placeholder="Masukkan Nama Sub" value="<?=$dataSelect->name; ?>" disabled>
				</div>
			</div>
			
			<div class="col-md-12 col-xs-12">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Minimum</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<div class="">
						<label>
							<input id="minimum_flag" type="checkbox" class="js-switch disabled-form" name="minimum_flag" value='1' 
							<?=($dataSelect->minimum_pemakaian==0)?'checked':'' ?> disabled>
							<p id="label_flag_minimum" style="display:contents"></p>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Minimum Pemakaian (m3)
				</label>
				<div class="col-md-3 col-sm-3 col-xs-12">
					<input type="text" class="form-control currency disabled-form" min="0" placeholder="Masukkan Minimum Pemakaian"
					 name="minimum_pemakaian" id="input_pemakaian" value="<?=$dataSelect->minimum_pemakaian; ?>" disabled>
				</div>
				<label class="control-label col-md-1 col-sm-1 col-xs-12">(Rp)
				</label>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<input type="text" class="form-control currency" min="0" placeholder="Masukkan Minimum Pemakaian"
					 name="" id="harga_pemakaian" value="" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Minimum (Rp)
				</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control disabled-form" placeholder="Masukkan Minimum" id="nilai_minimum" name="nilai_minimum" value="<?=$dataSelect->minimum_rp; ?>" disabled>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Administrasi (Rp)</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<input type="text" class="form-control currency disabled-form" placeholder="Masukkan Nilai Administrasi" id="administrasi" 
					name="administrasi" value="<?=$dataSelect->administrasi; ?>" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Service</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<select name="range_flag" id="range_flag" required class="form-control select2 disabled-form" disabled>
						<option value="">---Pilih Jenis Service---</option>
						<?php
                            foreach ($dataService as $v) {
                                echo "<option value='$v[id]' ";
                                echo $v['id'] == $dataSelect->range_flag ? 'selected' : '';
                                echo " >$v[code]</option>";
                            }
                        ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Range</label>
				<div id="lihat_range">
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select id="range_id" class="form-control select2 disabled-form" name="range_id" disabled>
							<option value="">--Pilih Range--</option>
							<?php
                                foreach ($dataRange as $v) {
                                    echo "<option value='$v[id]' ";
                                    echo $v['id'] == $dataSelect->range_id ? 'selected' : '';
                                    echo " >$v[code] - $v[name]</option>";
                                }
                            ?>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Pemeliharaan Air</label>
				<div id="lihat_range">
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select id="pemeliharaan_air_id" class="form-control select2 disabled-form" name="pemeliharaan_air_id">
							<option value="0">--Tidak Ada--</option>
							<?php foreach ($dataPemeliharaanAir as $k => $v):?>
								<option value="<?=$v->id?>" <?=$dataSelect->pemeliharaan_air_id == $v->id?'selected':''?> ><?=$v->name?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
				<div class="col-md-9 col-sm-9 col-xs-12">
					<textarea name="keterangan" class="form-control disabled-form" rows="3" placeholder='Masukkan Keterangan' disabled><?=$dataSelect->description; ?></textarea>
				</div>
			</div>

		</div>
		<div class="clear-fix"></div>
		<div class="col-md-12 col-xs-12">
            <label class="control-label col-md-6 col-sm-6 col-xs-12">Status</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="">
                <label>
                    <input id="status" type="checkbox" class="js-switch disabled-form" name="status" value='1' <?=$dataSelect->active == 1 ? 'checked' : ''; ?> disabled/> Aktif
                </label>
                </div>
            </div>
		</div>
		<div class="clear-fix"></div>

		<div class="col-md-12">
			<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
			<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
		</div>
		<div id="isi_tabel" class="col-md-12">
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

<!-- jQuery -->
<script type="text/javascript">
	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}

	function ajax_minimum() {
		$.ajax({
			type: "GET",
			data: {
				data1: $('#range_flag').val(),
				data2: $('#range_id').val(),
				data3: $('#input_pemakaian').val()
			},
			url: "<?=site_url(); ?>/P_master_sub_golongan/ajax_get_minimum",
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("#harga_pemakaian").val(currency(data));
			}
		});
	}
        
    $(function() {

		$("#minimum_flag").change(function(){
			if($("#minimum_flag")[0].checked){
				$("#label_flag_minimum").html("Anda Menggunakan Minimum (Rp)");
				$("#input_pemakaian").val(null);
				$("#input_pemakaian").attr('placeholder','');
				$("#harga_pemakaian").val(null);
				$("#harga_pemakaian").attr('placeholder','');
				$("#input_pemakaian").attr('disabled',true);
				$("#nilai_minimum").attr('placeholder','Masukkan Minimum (Rp)');
				$("#nilai_minimum").attr('disabled',false);
			}else{
				$("#label_flag_minimum").html("Anda Menggunakan Minimum Pemakaian (m3)");
				$("#nilai_minimum").val(null);
				$("#input_pemakaian").attr('disabled',false);
				$("#nilai_minimum").attr('disabled',true);
				$("#nilai_minimum").attr('placeholder','');
				$("#input_pemakaian").attr('placeholder','Masukkan Minimum Pemakaian');
				$("#harga_pemakaian").attr('placeholder','');

			}
		});
		$("#minimum_flag").trigger('change');

		$("#input_pemakaian").val( currency($("#input_pemakaian").val()) );
		$("#nilai_minimum").val( currency($("#nilai_minimum").val()) );
		$("#administrasi").val( currency($("#administrasi").val()) );
		$("#btn-update").click(function () {
			disableForm = 0;
			$(".disabled-form").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			setTimeout(function(){ $("#btn-update").attr("type", "submit"); }, 100);
			$("#minimum_flag").trigger('change');
		});
		$("#btn-cancel").click(function () {
			disableForm = 1;
			$(".disabled-form").attr("disabled", "")
			$("#btn-cancel").attr("style", "display:none");
			$("#btn-update").val("Edit")
			$("#btn-update").removeAttr("type");
			
		});
		$("#btn-cancel").trigger('click');

		$(".btn-modal").click(function(){
            url = '<?=site_url(); ?>/core/get_log_detail';
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
		
		$("#input_pemakaian").keyup(function () {
			// $("#nilai_minimum").val(currency_to_number(harga_minimum) * currency_to_number($(this).val()));
			ajax_minimum();
		});
		$("#range_flag").change(function () {
			url = '<?=site_url(); ?>/P_master_sub_golongan/lihat_range';
			var range_flag = $("#range_flag").val();
			//console.log(this.value);
			$.ajax({
				type: "post",
				url: url,
				data: {
					range_flag: range_flag
				},
				dataType: "json",
				success: function (data) {
					console.log(data);

					$("#range_id")[0].innerHTML = "";

					$("#range_id").append("<option value='' >Pilih Jenis Range</option>");
					$.each(data, function (key, val) {
						$("#range_id").append("<option value='" + val.id + "'>" + val.name.toUpperCase() + "</option>");
					});

				}


			});
		});

		$("#range_id").change(function () {

			url = '<?=site_url(); ?>/P_master_sub_golongan/lihat_tabel';
			var range = $("#range_id").val();
			var min_use = $("#input_pemakaian").val();
			var action = "lihat_tabel";
			var jenis = "sub_golongan";
			var range_flag = $("#range_flag").val();
			//var loading = '<p align="center"><img src="images/tenor.gif"> </p>';
			// $("#print_range").html(loading);
			$.ajax({
				url: url,
				method: "POST",
				data: {
					range: range,
					action: action,
					jenis: jenis,
					id: range,
					min_use: min_use,
					range_flag: range_flag
				},
				dataType: "json",
				success: function (data) {
					var tmp = "<table class='table table-responsive'>";
					tmp += "<thead>";
					tmp += "<tr>";
					tmp += "<th>Range</th>";
					tmp += "<th>Range Awal</th>";
					tmp += "<th>Range Akhir</th>";
					tmp += "<th>Harga</th>";
					tmp += "<tr>";
					tmp += "<thead>";
					tmp += "<tbody>";

					var no = 0;
					// var nilai = 0;

					$.each(data, function (key, val) {
						console.log(val);
						no = no + 1;

						tmp += "<tr>";
						tmp += "<td> Range" + no + " </td>";
						tmp += "<td>" + val.range_awal + " </td>";
						tmp += "<td>" + val.range_akhir + " </td>";
						tmp += "<td>" + val.harga + " </td>";
						tmp += "<tr>";

						// if ((min_use >= val.range_awal) && (min_use <= val.range_akhir)) {
						// 	nilai = min_use * val.harga;
						// }

					});

					tmp += "<tbody>";
					tmp += "<table>";
					$("#isi_tabel")[0].innerHTML = tmp;

					// $('#nilai_minimum').val(nilai);
					ajax_minimum();
				}
			});
		});
		$("#input_pemakaian").trigger("keyup");

	});
</script>
