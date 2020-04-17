<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?=base_url()?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?=base_url()?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>

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
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_diskon/save">
        
    <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" class="form-control"  required name="nama" placeholder="Masukkan Nama Golongan Diskon">
            </div>
          </div>

        <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Product Category 
                <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select type="text" id="product_category" required="required" class="select2 form-control col-md-7 col-xs-12" name="product_category_id">
                    <option selected disabled>--Pilih Product Category--</option>
                    <?php
                        foreach($dataProductCategory as $key => $v){
                            echo("<option value='$v[product_category_id]'>$v[product_category_name]</option>");
                        }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <textArea id="description" class="form-control col-md-7 col-xs-12" type="text" name="description"></textArea>
            </div>
        </div>
        
        <div class="ln_solid"></div>

        <div class="col-md-12">
            <p>  
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="col-md-3">Service</th>
                        <th class="col-md-3">Paket Service</th>
                        <th class="col-md-2">Parameter</th>
                        <th class="col-md-2">Nilai</th>
                        <th class="col-md-1">Minimum Bulan</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody id="tbody_diskon_detail">                
                    <input id="idf" value="1" type="hidden" />

                </tbody>
            </table>
            <button id='btn-add-diskon-detail' type="button" class="btn btn-danger pull-right"><i class="fa fa-plus"></i> Add Diskon Service</button>
            </p>
        </div>

        <div class="col-md-12">
            <div class="center-margin">
                <button class="btn btn-primary" type="reset">Reset</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>

    </form>
</div>

<script>
    $(function() {
        $('.select2').select2();
        $('#btn-add-diskon-detail').click(function(){
            var row = "<tr>"
            +"</tr>";
        });

    });

    $("#btn-add-diskon-detail").click(function(){
        if($(".no").html()){
            idf = parseInt($(".no").last().html()) + 1;
        }else{
            idf = 1;
        }
        var str = "<tr id='srow" + idf + "'>"+
                    "<td class='no'>"+idf+"</td>"+
                    "<td>"+
                        "<select class='service select2 form-control col-md-7 col-xs-12' name='service[]'>"+
                            "<option value=''>--Pilih Jenis Service--</option>"+
                            "<?php foreach ($dataService as $key => $v){ ?>"+
                                "<option value='<?=$v['id']?>'><?=$v['code']?> - <?=$v['name']?> </option>"+
                            "<?php } ?>"+
                        "</select>"+
                    "</td>"+
                    "<td>"+
                        "<select class='paket_service select2 form-control col-md-7 col-xs-12' name='service[]'>"+
                            "<option value='' selected disabled>-- Pilih Service Dahulu --</option>"+
                        "</select>"+
                    "</td>"+
                    "<td>"+
                        "<select class='form-control select2' name='parameter[]' placeholder='-- Masukkan parameter --' required>"+
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
    $("#tbody_diskon_detail").on("change",".service", function(){
            console.log('yahoo');
            url = '<?=site_url(); ?>/P_master_diskon/get_paket_service';
			var thisRow = $(this);
			var id = thisRow.val();
			
			$.ajax({
				type: "post",
				url: url,
				data: {
					service_id: id
				},
				dataType: "json",
				success: function (data) {
					console.log(data);
					console.log(thisRow);
                    // thisRow.parent().parent().children().children(".jumlah-satuan").val(1);
					// thisRow.parent().parent().children().children(".satuan").val(data.satuan);
					// thisRow.parent().parent().children().children(".harga-satuan").val(data.harga);
					// thisRow.parent().parent().children().children(".harga-registrasi").val(data.biaya_registrasi);
					// thisRow.parent().parent().children().children(".total").val(data.harga+data.biaya_registrasi);
					data.forEach(v => {
                        thisRow.parent().parent().children("td").children(".paket_service").append("<option value='" + v.id + "'>" + v.code +" - "+ v.name + "</option>");		
                    });
							
				}
			});
        });
    function hapusElemen(idf) {
        $(idf).remove();
        var idf = document.getElementById("idf").value;
        idf = idf-1;
        document.getElementById("idf").value = idf;
    }

</script>