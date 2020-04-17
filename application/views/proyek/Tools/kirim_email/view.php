<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
            <button class="btn btn-primary" onClick="window.location.href='<?=site_url(); ?>/P_master_cara_pembayaran/add'">
                <i class="fa fa-plus"></i>                
                Tambah
            </button>
            <button class="btn btn-warning" onClick="window.history.back()" disabled>
                <i class="fa fa-arrow-left"></i>
                Back
            </button>
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_master_cara_pembayaran'">>
                <i class="fa fa-repeat"></i>
                Refresh
            </button>
        </h2>
    </div>
    <div class="clearfix"></div>


          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Email</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select type="text" id="jenis_email" required="required" class="select2 form-control col-md-7 col-xs-12" name="jenis_email" style="width:100%" placeholder="--Pilih Jenis Email--">
              
                     <option value="" selected="" disabled="">--Pilih Jenis Email-</option>
                    <?php
                        foreach ($dataJenisEmail as $v) {
                            echo("<option value='$v[type]'>$v[type]</option>");
                        }
                    ?>
                </select>
               </div>
            </div>
          </div>
            <div class="col-md-6">
            <div id="template_email" class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Template Email</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select  id="kawasan" required="required" class="select2 form-control col-md-7 col-xs-12" name="kawasan" style="width:100%" placeholder="--Pilih Kawasan--">
                    <option value="">--Pilih Template Email--</option>
                    <?php
                        foreach ($dataKawasan as $v) {
                            echo("<option value='$v[id]'>$v[name]</option>");
                        }
                    ?>
                </select>     
            </div>
            </div>
      </div> 
    
    <div class="clearfix"></div>    
    <div class="col-md-6">
       <div id='informasi'class="form-group">
             <label class="control-label col-md-3 col-sm31 col-xs-12">Pesan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                       <textarea name="pesan" class="form-control"></textarea>
                </div>           
        </div>    
    </div>
       
        
         
          <div class="clearfix"></div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select  id="kawasan" required="required" class="select2 form-control col-md-7 col-xs-12" name="kawasan" style="width:100%" placeholder="--Pilih Kawasan--">
                    <option value="">--Pilih Kawasan--</option>
                    <option value="all">--Semua Kawasan--</option>
                    <?php
                        foreach ($dataKawasan as $v) {
                            echo("<option value='$v[id]'>$v[name]</option>");
                        }
                    ?>
                 </select>
                  </div>
                </div>
          </div>


          

        <div class="col-md-6">
            <div id="lihat_blok" class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select id="blok" class="form-control select2" name="blok">
                   <option value="">--Pilih Blok--</option>
                 </select>    
            </div>
            </div>
        </div> 


      


        <div class="clearfix"></div>
        <br>
        <br>
        
   
        <div class="form-group">
          <div class="col-md-6 col-sm-9 col-xs-12 col-md-offset-3">
            <button id="simpan" type="submit" class="btn btn-success"><i class="fa fa-save"> </i>Kirim Email</button>
            <button type="reset" class="btn btn-primary"><i class="fa fa-repeat"></i> Reset</button>
          </div>
        </div>
     


      </div>
      <div class="clearfix"></div>
      <br>
      <br>
      <div class="col-sm-12" id="dataisi">
        <div class="card-box table-responsive">
          <table id="example" class="table table-responsive table-stripped table-hover table-bordered">
            <thead>
              <th>No</th>
              <th>Kawasan</th>
              <th>Blok</th>
              <th>Unit</th>
              <th>Customer</th>
              <th>Email</th>
              <th>No Hp</th>
              <th><div class='checkbox checkbox-primary'>
                            <input type='checkbox' id="checkall" checked>
                            <label id='' for='checkall'>
                             <div id='labelcheck'>Uncheck All</div>
                        </label></div></th>
            </thead>
            <tbody id="isi"></tbody>
          </table>
                </div>
            </div>
    </div>                    
    </div>
</div>
</form>



<script type="text/javascript"> 


  $(document).ready(function() {
      
   // $("#informasi").hide(); 
  //  $("#template_email").hide();
    $("#jenis_email").change(function() 
    {
        var type    = $("#jenis_email").val();

       if ( type == 'informasi_umum' )
       {
           
        $("#informasi").show();
        $("#template_email").hide()     
           
       }
       
       else if ( type == 'info_tagihan' )
       {
           
        
        var text;
        text = '<tr align=\"center\"><td colspan=\"8\">Silahkan Pilih Template Email Untuk Menampilkan Data</td></tr>';

            $("#informasi").hide();
            $("#template_email").show();
            var loading     = '<p align="center"><img src="images/tenor.gif"> </p>';
            var url         = 'tools/kirim_email/lihat_template_email.php';
            $("#template_email").html(loading);
                $.ajax(
            {  
            url:url,  
            method:"GET",  
            data:{type:type},  
            dataType:"text",  
            success:function(data)
            {  
              $("#template_email").html(data);
            } 
            });  
            
       }
        });
      
    





$("#kawasan").change(function () {


                //  alert('tess');

                url = '<?=site_url(); ?>/proyek/tools/P_kirim_email/lihat_blok';
                var kawasan = $("#kawasan").val();
                //console.log(this.value);
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        kawasan: kawasan
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);

                        $("#blok")[0].innerHTML = "";
                        $("#blok").append("<option value='' >Pilih Blok</option>");
                        $("#blok").append("<option value='' >Semua Blok</option>");
                        $.each(data, function (key, val) {
                            $("#blok").append("<option value='" + val.id + "'>" + val.name.toUpperCase() + "</option>");
                        });

                    }


                });
            })


 });

</script>