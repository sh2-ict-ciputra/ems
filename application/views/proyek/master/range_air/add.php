<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
      <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/P_master_range_air/save">
           

        <div class="col-md-5 col-xs-12">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Range</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" required name="kode_range" placeholder="Masukkan Kode Range">
            </div>  
			</div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control"  required name="nama" placeholder="Masukkan Nama Range">
            </div>
          </div>
           <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Formula</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select name="formula" id="formula" class="form-control select2" placeholder="-- Masukkan Formula --">
                        <option value=""></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        
                    </select>
                </div>
            </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <textarea class="form-control" rows="3" name="keterangan" placeholder='Masukkan Keterangan'></textarea>
            </div>
          </div>
          
        </div>
        <div class="col-md-7 col-xs-12">
          <table class="table table-responsive">
            <thead>
              <tr>
                <th>Range</th>
                <th>Range Awal</th>
                <th>Range Akhir</th>
                <th>Harga HPP</th>
                <th>Harga Range</th>
                <th>Hapus</th>
              </tr>
            </thead>
			 <tbody id="tbody_range">                
                    <input id="idf" value="1" type="hidden" />

                </tbody>
            </table>
            <button type="button" id='btn-add-range' class="btn btn-danger pull-right" ><i class="fa fa-plus"></i> Add Range</button>
        </div>
		<div class="col-md-12">
                <div class="center-margin">
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>

     



<<!-- jQuery -->
<script type="text/javascript">		
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
		            "<td class='no'>"+idf+"</td>"+
					"<td><input type='text' class='form-control'  name='range_awal[]' placeholder='' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' /></td>" +
                    "<td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' name='range_akhir[]' value='0' class='form-control'/></td>" +
					"<td><input type='text' class='form-control' value='0' name='harga_hpp[]' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);'  /></td>" +
					"<td><input type='text' class='form-control' value='0' name='harga_range[]' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);'  /></td>" +
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



</script>

       