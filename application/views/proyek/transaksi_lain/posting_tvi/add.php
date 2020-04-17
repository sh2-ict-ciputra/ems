<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?=base_url()?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url()?>vendors/select2/dist/js/select2.min.js"></script>


<!-- switchery -->
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?=base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>


<!DOCTYPE html>
<div style="float:right">
	<h2>
		<button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_posting_tvi'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_posting_tvi/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/p_posting_tvi/save">


     <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Posting TV Internet</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                     

                      <div class="col-md-12">
                          <select required="" id="pilih_transfer" name="pilih_transfer" class="form-control select2">
                            <option value="" selected="" disabled="">--Pilih No Transfer--</option>
                                      <?php
                                            foreach ($dataTransfer as $v) {
                                                echo("<option value='$v[id]'>$v[customer] => $v[bank] => $v[nomor_rekening] => $v[kode] </option>");
                                            }
                                        ?>
                          </select>
                     </div>

        <div class="clear-fix"></div>
        <br>
        <br>
        <br>

         <div class="clearfix"></div>
          <div class="col-md-6">
            <div class="form-group transfer">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
               <select required="" id="customer" name="customer" class="form-control select2">
                            <option value="" selected="" disabled="">--Pilih Customer--</option>
                                      <?php
                                            foreach ($dataCustomer as $v) {
                                                echo("<option value='$v[id]'>$v[customer_name] </option>");
                                            }
                                        ?>
                          </select>
        </div>
      </div>
      
      <div class="form-group transfer">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Transfer</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="" required name="kode_transfer" id="kode_transfer" readonly>
                        
                        </div>
                        <input type="hidden" name="action" value="Posting">
          
            </div>
      
     
      
      
            </div>
         
      
              <div class="col-md-6">
              <div class="form-group transfer">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                           <div class='input-group date mydatepicker' >
                              <input required="" type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Auto Generated" readonly value="" >
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                        </div>
                   </div>
              </div>
        <div class="form-group transfer">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="" required name="bank" id="bank" readonly  >
                        </div>
                        
            </div>
      <div class="form-group transfer">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Rekening</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="" required name="nomor_rekening" id="nomor_rekening" readonly  >
                        </div>
                        
            </div>
      <div class="form-group transfer">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Rekening</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="" required name="nama_rekening" id="nama_rekening" readonly  >
                        </div>
                        
            </div>
      
        
        
        
        
        
          </div>
      
      <div class="clearfix"></div>
          <div class="col-md-6">
       <div class="form-group transfer">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Bayar Transfer</label>  
                        <div class="col-md-8 col-sm-9 col-xs-12">
                          <input type="text" class="form-control currency" value="" required name="total_bayar" id="total_bayar" readonly>
                        </div>
             <div class="col-md-1 col-sm-9 col-xs-12">
             VS
             </div>
            
                        
            </div>
      
      
      
      
      </div>
      
       <div class="col-md-6">
      
      <div class="form-group transfer">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan Billing</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control currency" value="" placeholder="Auto Generate" required name="total_tagihan" id="total_tagihan" readonly >
                        </div>
                        
            </div>
      
      
      </div>
      
      
    
      
      
      
      
      
      
      
      
      </div>
        <div class="clearfix"></div>
        <br>
        <br>
        
   
        
      </div>
      <div class="clearfix"></div>
      <br>
      <br>
      <div class="col-sm-12 transfer" id="dataisi">
        <div class="card-box table-responsive">
          <table id="example"  class="table table-striped jambo_table bulk_action tableDT">

            <thead>
              <th>No</th>
              <th>Tanggal</th>
              <th>Paket Layanan</th>
              <th>BWIDTH</th>
              <th>Mulai</th>
              <th>Berakhir</th>
              <th>Total Tagihan</th>
              <th>Harga</th>
        <th>TRP-TV</th>
        <th>No Registrasi</th
            </thead>
            <tbody id="isi"></tbody>
          </table>
        </div>
      </div>
    </div>


  





			<div class="col-md-12 col-xs-12">
				<div class="center-margin">
					<button class="btn btn-primary" type="reset">Reset</button>
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
			</div>

        </div>
    </form>
</div>
</div>


<script type="text/javascript">

  
$(".transfer").hide();



	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? 0 : input.toLocaleString("en-US");
	}


	$(function () {
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			format: 'MM-DD-YYYY'
		});

        $("#isi").on("change",'.total_tagihan',function(){
            data = $(this).parent().parent().children('.data_harga').html();
            if($(this).is(':checked')){
                if($("#total_tagihan").val() == ""){
                  $("#total_tagihan").val(0);
                }else{
                  data = parseInt(data) + parseInt($("#total_tagihan").val());
                }
            }else{
                if($("#total_tagihan").val() == ""){
                  $("#total_tagihan").val(0);
                }else{
                  data = parseInt($("#total_tagihan").val()) - parseInt(data);
                }
            }
            $("#total_tagihan").val(data);  
        });
	
    });



  $("#pilih_transfer").change(function () {
      

    var pilih_transfer = $("#pilih_transfer").val();


    alert(pilih_transfer);


  

      url = '<?=site_url(); ?>/transaksi_lain/p_posting_tvi/lihat_transfer';
      
      //console.log(this.value);
      $.ajax({
        type: "get",
        url: url,
        data: {
          pilih_transfer : pilih_transfer
        },
        dataType: "json",
        success: function (data) {
          console.log(data);



          $(".transfer").show();
        

         
          $("#kode_transfer").val(data.kode);          
          $("#total_bayar").val(data.total_transfer);
          $("#tanggal").val(data.tanggal);
          $("#bank").val(data.bank);
          $("#nomor_rekening").val(data.nomor_rekening);
          $("#nama_rekening").val(data.nama_rekening);
          


        }


      })





  });



$("#customer").change(function () {


                 alert('tess');


                 var customer = $("#customer").val();  

                 alert(customer);

                        


                 url = '<?=site_url(); ?>/transaksi_lain/p_posting_tvi/lihat_registrasi';

                 $.ajax({
                    type: "get",
                    url: url,
                    data: {
                        customer: customer
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
                              "<td>" + data[i].tanggal + "</td>" +
                              "<td>" + data[i].paket_name + "</td>" +
                              "<td>" + data[i].bandwidth + "</td>" +
                              "<td>" + data[i].tanggal_mulai + "</td>" +
                              "<td>" + data[i].tanggal_berakhir + "</td>" +
                              "<td><input type='checkbox' class='total_tagihan flat table-check check' name='pilihan'  value='"+ data[i].id_bill +"' id='pilihan'>" +
                                "</td>"+
                              "<td class='data_harga'>" + data[i].harga + "</td>" +
                              "<td>" + ''   +  "</td>" +
                              "<td>" + data[i].nomor_registrasi + "</td>" +
                            "</tr>");
                        }


                      

                    }

     })
});


function checkFluency(){
 
     var id_arr = [];
            $.each($("input[name='pilihan']:checked"), function(){            
                id_arr.push($(this).val());
            });
      
      
    var jumlah = id_arr.length - 1;
    
    //alert(jumlah);
    
    var id = [];
    
    for (i = 0; i <= jumlah ; i++) { 
    
        id += id_arr[i] + " ";
        }
      
     
    
    //alert(id);
    if (id == 'undefined')
    {
      
       $("#total_tagihan").val('');
    
    }
    else
    {


     url = '<?=site_url(); ?>/transaksi_lain/p_posting_tvi/lihat_tagihan';


    $.ajax({
      type: 'get',
      url: url,
      data: {
          id : id
        },
      success: function(data){
          console.log(data);
        
          $("#total_tagihan").val(data['total']);
      
        
        window.onload;
        
      }
    });
    
    }
}




	

	
	
    
</script>
