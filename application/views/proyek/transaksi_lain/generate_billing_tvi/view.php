<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
    <div style="float:right">
        <h2>
            <button class="btn btn-primary" onClick="window.location.href='<?=site_url(); ?>/transaksi_lain/p_aktifasi_tvi/add'">
                <i class="fa fa-plus"></i>                
                Tambah
            </button>
            <button class="btn btn-warning" onClick="window.history.back()" disabled>
                <i class="fa fa-arrow-left"></i>
                Back
            </button>
            <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_aktifasi_tvi'">>
                <i class="fa fa-repeat"></i>
                Refresh
            </button>
        </h2>
    </div>
    <div class="clearfix"></div>
</div>

 <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/P_generate_billing_tvi/generate_billing">

    

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select name="kawasan" required id="kawasan" class="form-control select2">
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
            <button id="simpan" type="submit" class="btn btn-success"><i class="fa fa-save"> </i>Proses Generate Bill</button>
            <button type="reset" class="btn btn-primary"><i class="fa fa-repeat"></i> Reset</button>
          </div>
        </div>
   
      <div class="clearfix"></div>
      <br>
      <br>
      <div class="col-sm-12" id="dataisi">
      <div class="card-box table-responsive">
         <table id="table_unit" class="table table-striped table-bordered bulk_action">

            
            <thead>
              <th>No</th>
              <th>Kawasan</th>
              <th>Blok</th>
              <th>Unit</th>
              <th>Customer</th>
              <th>Email</th>
              <th>No Hp</th>
              <th><div class='checkbox checkbox-primary'>
                            <input type='checkbox' id="checkall" checked >
                            <label id='' for='checkall'>
                             <div id='labelcheck'>Uncheck All</div>
                        </label></div></th>


            </thead>
            <tbody id="isi">
            </tbody>
          </table>
        </div>
      </div>
    </div>                    
  </div>
</div>
</form>





<script type="text/javascript"> 

// var table_unit = $("#table_unit");
//   var table_unit_dt = table_unit.dataTable({
//     order: [[1, "asc"]],
//     columnDefs: [{
//       orderable: !1,
//       targets: [0]
//     }]
//   });
//   table_unit.on("draw.dt", function () {
//     $("checkbox input").iCheck({
//       checkboxClass: "icheckbox_flat-green"
//     })
//   })

	
  $("#lihat_blok").hide();



  



$("#kawasan").change(function () {


                // alert('tess');

                url = '<?=site_url(); ?>/transaksi_lain/P_generate_billing_tvi/lihat_blok';


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

                        $("#lihat_blok").show();

                        $("#blok")[0].innerHTML = "";
                        $("#blok").append("<option value='' >Pilih Blok</option>");
                        $("#blok").append("<option value='all' >Semua Blok</option>");
                        $.each(data, function (key, val) {
                            $("#blok").append("<option value='" + val.id + "'>" + val.name.toUpperCase() + "</option>");
                        });

                    }


                })
});




$("#blok").change(function () {


                 //alert('tess');


                 var kawasan = $("#kawasan").val(); 

                 var blok = $("#blok").val();                        


                  url = '<?=site_url(); ?>/transaksi_lain/P_generate_billing_tvi/lihat_unit';

               
                //console.log(this.value);
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        kawasan: kawasan, blok :blok 
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);

                        $("#isi").show();

                        var no = 1;

                        $("#isi")[0].innerHTML = "";
                      
                        for (var i = 0; i < data.length; i++) {
                          $("#isi").append(
                            "<tr class='even pointer'>" +
                              "<td>" + (i + 1) + "</td>" +
                              "<td>" + data[i].kawasan + "</td>" +
                              "<td>" + data[i].blok + "</td>" +
                              "<td>" + data[i].unit + "</td>" +
                              "<td>" + data[i].customer + "</td>" +
                              "<td class='a-right a-right'>" + data[i].email + "</td>" +
                              "<td>" + data[i].no_hp + "</td>" +
                               "<td><input type='checkbox' class='flat table-check check' name='unit[]' value='"+data[i].unit_id+"'></td>"+
                              // "<td class='a-center'>"+
                              //  "<div class='icheckbox_flat-green checked' style='position: relative;'>"+
                              //    "<input type='checkbox' class='flat' name='table_records' style='position: absolute; opacity: 0;'>"+
                              //    "<ins class='iCheck-helper' style='position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;'></ins>"+
                              //  "</div>"+
                              // "</td>"+
                            "</tr>");
                        }


                      

                    }


                })
});



 


 $("#checkall").change(function() 
  {
    
   
    if($(this).is(':checked'))
    {
      $("input:checkbox").prop('checked', $(this).prop("checked"));
      $("#labelcheck").text('Uncheck All');
    }
    else
    {
      $(".check").removeAttr('checked');
      $("#labelcheck").text('Check All');
    }
  });   


</script>