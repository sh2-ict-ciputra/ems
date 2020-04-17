<?php
$jenis_service = $_GET['jenis_service'];
$jenis_service = explode("|", $jenis_service);
$nama_service = $jenis_service[0];
    if($nama_service=="1")
    {
        $table = "range_air";
        $table2= "range_air_detail";
        $table3= "range_air_id";
    }
    elseif($nama_service=="3")
    {
        $table = "range_listrik";
        $table2= "range_listrik_detail";
        $table3= "range_listrik_id";
    }
    elseif($nama_service=="2")
    {
        $table = "range_lingkungan";
        $table2= "range_lingkungan_detail";
        $table3= "range_lingkungan_id";
    }
?>
                              <div class="col-md-9 col-sm-9 col-xs-12">
                              <select name="range" id="range" required class="form-control select2">
                          	<option value="">---Pilih Jenis Range---</option>
                             
                             <?php
                            foreach ($table as $v) {
								 echo("<option value='$v[id]|$table2|$table3'>$v[name]</option>");
							}
                           
                             ?>
							 
							
                             </select>
                            </div>
							
							
							
							
							
                            <script>
            $(document).ready(function () {
                $(".select2").select2({
                });
                $("#range").change(function() {
			var range = $("#range").val();
			var min_use = $("#input_pemakaian").val();
			var action = "lihat_range_air";
			var jenis = "sub_golongan";
			var loading = '<p align="center"><img src="images/tenor.gif"> </p>';
			 $("#print_range").html(loading);
			 $.ajax({  
                     url:"master/sub_golongan/ajax.php",  
                     method:"GET",  
                     data:{range:range,action:action,jenis:jenis,id:range, min_use:min_use},  
                     dataType:"text",  
                     success:function(data){
						 
						 
						  var output = data.substring(data.indexOf('geee'), data.indexOf('quuu') );
						 values=output.split('|');
						var one=values[0];
						var two=values[1];
						var three=values[2];
						var fourth = two * three;
						
						$('#nilai_minimum').val(fourth);
						
						 
						var subdata = data.substring(data.indexOf('qeee'), data.indexOf('quuu') );
						
						data2 = data.replace(subdata, "");
						
						data3 = data2.replace('quuu', "");
						 
						 
						 
						 
					$("#print_range").html(data3);
					} 
			 		});	
		  });
            });
        </script>