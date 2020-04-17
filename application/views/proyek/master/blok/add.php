<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
            <button class="btn btn-warning" onClick="window.history.back()">
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
      <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="http://192.168.137.1:81/emsNew/index.php/P_master_customer/save">
           

  
          <div class="col-md-6 col-xs-12">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="hidden" name="action" value="Tambah">
              <input type="hidden" name="jenis" value="<?php echo $_GET['jenis']; ?>">
              <input type="text" readonly="" value="<?php echo $kode_kustomer; ?>" name="kode" class="form-control" placeholder="Masukkan Kode"> 
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama </label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukkan Nama Customer" required>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Unit </label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <select name="unit" class="form-control select2" required>
                <option value="t">Unit</option>
                <option value="f">Non Unit</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat Domisili</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <textarea name="alamat_domisili" class="form-control" id="alamat_domisili" rows="3" placeholder='Masukkan Alamat Domisili'  required></textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="email" class="form-control" placeholder="Masukkan Alamat Email" name="email" required>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor NPWP</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" name="nomor_npwp" class="form-control" placeholder="Masukkan Nomor NPWP" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama NPWP</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" name="nama_npwp" id="nama_npwp" class="form-control" placeholder="Masukkan Nama NPWP" >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat NPWP</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <textarea id="alamat_npwp" name="alamat_npwp" class="form-control" rows="3" placeholder='Masukkan Alamat NPWP'  ></textarea>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xs-12">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">NIK</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" name="nik" placeholder="Masukkan NIK" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat KTP</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <textarea name="alamat_ktp" class="form-control" id="alamat_ktp" rows="3" placeholder='Masukkan Alamat Sesuai KTP' required ></textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tempat Lahir </label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" name="tempat_lahir" class="form-control" placeholder="Masukkan Tempat Lahir" required>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Lahir </label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <div class='input-group date mydatepicker' >
                <input type="text" class="form-control" name="tanggal_lahir" placeholder="Masukkan Tanggal Lahir" required>
                <span class="input-group-addon"> 
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>                        
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Home Phone</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" name="home_phone" placeholder="Masukkan Nomor Home Phone" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Office Phone</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" placeholder="Masukkan Nomor Office Phone" name="office_phone"  required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Phone</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control" placeholder="Masukkan Nomor Mobile Phone" name="mobile_phone" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <textarea name="keterangan" class="form-control" rows="3" placeholder='Masukkan Keterangan' required></textarea>
            </div>
          </div>
		  </form>
	
	 <!-- jQuery -->

<script type="text/javascript">
	$(".select2").select2({
		
	});
		 function tambahHobi() {
     var idf = document.getElementById("idf").value;
     var stre;
     stre="<tr id='srow" + idf + "'><td>"+idf+"</td><td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control' value='0' name='range_awal[]' placeholder='' /></td><td><input type='text' name='range_akhir[]' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' value='0' class='form-control'/></td><td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control' value='0' name='harga_hpp[]'  /></td><td><input type='text' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);' class='form-control' value='0' name='harga_range[]'  /></td><td> <a class='btn btn-danger' href='#' style=\"color:#3399FD;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td></tr>";
     $("#divHobi").append(stre);
     idf = (idf-1) + 2;
     document.getElementById("idf").value = idf;
   }
   function hapusElemen(idf) {
     $(idf).remove();
	    var idf = document.getElementById("idf").value;
	   idf = idf-1;
	    document.getElementById("idf").value = idf;
   }
		
    $(document).ready(function() {  
$("#myform").submit(function(event){
   var loading = '<p align="center"><img src="images/giphy.gif"> </p>';
   $("#print_proses").html(loading);
    event.preventDefault(); //prevent default action 
    var post_url = $(this).attr("action"); //get form action url
    var request_method = $(this).attr("method"); //get form GET/POST method
    var form_data = $(this).serialize(); //Encode form elements for submission
    $.ajax({
        url : post_url,
        type: request_method,
        data : form_data
    }).done(function(response){ //
        $("#print_proses").html(response);
    });
});
      
     
  
    });
	</script>