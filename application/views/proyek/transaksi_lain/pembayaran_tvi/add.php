<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>


<!-- switchery -->
<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<style>
 .sisa {
    border:none;
}
</style>

<style>
 .full{
    background-color:#99badd;
}
</style>

<style>
 .notfull{
    background-color:#dd9a99;
}
</style>

<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/p_pembayaran_tvi'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/transaksi_lain/p_pembayaran_tvi/add'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br>
    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url() ?>/transaksi_lain/p_pembayaran_tvi/save">

        <div class="col-md-12">
            <select required="" id="pilih_reg" name="pilih_reg" class="form-control select2">
                <option value="" selected="" disabled="">--Pilih No Registrasi--</option>
                <?php
                foreach ($dataRegistrasi as $v) {
                    echo ("<option value='$v[id]'>$v[customer_name] => $v[nomor_registrasi]</option>");
                }
                ?>
            </select>
        </div>

        <div class="clear-fix"></div>
        <br>

        <div id="view_data">
            <div class="row" style="margin-top: 35px;">
                <div class="col-md-12">
                    <div class="col-md-6">

                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Pembayaran</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="nomor_pembayaran" name="nomor_pembayaran" value="<?= $kode_pay ?>" readonly class="form-control pay">
                                <input type="hidden" name="registrasi_id" id="registrasi_id">
                                <!-- <input type="hidden" name="id_tagihan"  id="id_tagihan" value="" > -->
                            </div>
                        </div>
                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Pembayaran</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class='input-group date '>
                                    <input type="text" class="form-control datetimepicker pay" name="tanggal_pembayaran" id="tanggal_pembayaran" placeholder="Masukkan Tanggal Pembayaran"> <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Cara Pembayaran</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select name="cara_pembayaran" id="cara_pembayaran" required="" class="form-control select2">
                                    <option value="">--Pilih Cara Pembayaran--</option>
                                    <option value="1">Cash</option>
                                    <option value="2">Debit</option>
                                    <option value="3">Payment Getway</option>
                                </select>


                            </div>
                        </div>

                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Layanan</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" id="jenis_layanan" name="jenis_layanan" readonly class="form-control pay">
                            </div>
                        </div>

                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea class="form-control pay" name="keterangan" id="keterangan" placeholder="Masukkan keterangan"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Layanan</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" name="paket_layanan" id="paket_layanan" readonly class="form-control payt">
                            </div>
                        </div>
                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Ref Pembayaran</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" name="nomor_ref_pembayaran" id="nomor_ref_pembayaran" value="" class="form-control pay">
                            </div>
                        </div>
                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">No Fisik Kwitasni</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" name="nomor_fisik_kwitansi" id="nomor_fisik_kwitansi" value="" class="form-control pay">
                            </div>
                        </div>
                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">No Tagihan</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" readonly="" name="nomor_tagihan" id="nomor_tagihan" value="" class="form-control pay">
                            </div>
                        </div>


                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Total</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" readonly="" name="sub_total" id="sub_total" value=0 readonly class="form-control  currency">
                                <input type="hidden" name="sub_total2" id="sub_total2" value=0>
                            </div>
                        </div>
                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Discount ( % ) </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" name="diskon" id="diskon" value=0 class="form-control currency">
                            </div>
                        </div>

                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"> Total Tagihan</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" name="total_tagihan" id="total_tagihan" value=0 readonly class="form-control pay currency">
                                <input type="hidden" name="total_tagihan2" id="total_tagihan2">
                            </div>
                        </div>


                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Total bayar</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" required="" placeholder="Total Bayar" id="total_bayar" name="total_bayar" onkeyup="getHitung();" value=0 class="form-control pay ">
                            </div>
                        </div>


                        <div class="form-group pay">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa Tagihan</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" required="" placeholder="Sisa Tagihan" id="sisa_tagihan" name="sisa_tagihan" value=0 class="form-control pay currency">
                            </div>
                        </div>



                    </div>
                </div>
            </div>



          

<div class="clearfix"></div>
<br>
<br>
<div class="col-sm-12 " id="list_tagihan">
    <div class="card-box table-responsive">
        <table id="example" class="table table-responsive table-stripped table-hover table-bordered">
            <thead>
                <th>No</th>
                <th>Yang Dibayar</th>
                <th>Jenis Tagihan</th>
                <th>Tanggal Tagihan</th>
                <th>Jenis Paket</th>
                <th>Total Tagihan</th>
                <th>Sisa</th>
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



<script type="text/javascript">
    //$(".pay").hide();
    $(".pay").hide();
    $("#list_tagihan").hide();




    function currency(input) {
        var input = input.toString().replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt(input, 10) : 0;
        return (input === 0) ? 0 : input.toLocaleString("en-US");
    }


    function getHitung() {


        var total_tagihan = $("#total_tagihan2").val();

        var total_bayar = $("#total_bayar").val();


        var sisa = parseInt(total_tagihan) - parseInt(total_bayar);



        $("#sisa_tagihan").val(currency(sisa));

         
        //  data = 0
        //  $.each($(".data_harga"), function(k,v){  



        //   data = data +  1;

        //  console.log(data);




        //  });


        var jumlah = $(".data_harga").length;


        for (var i = 1; i <= jumlah; i++) {

           
            var tagihan =   $("#tagihan" + i).html();


            $("#pilihan" + i).prop('checked', false);


            $("#sisa" + i).val(currency(tagihan));


        }

    

           for (var i = 1; i <= jumlah; i++) {


           


             if ( i == 1)

             {
            var tagihan =   $("#tagihan" + i).html();

            var sisa  = total_bayar - tagihan ;


             if (  sisa >= 0 )

             {

          

                 $("#pilihan" + i).prop('checked', true);
                 if($("#pilihan" + i).is(":checked")){
                    $("#pilihan" + i).parent().removeClass("notfull"); 
                    $("#pilihan" + i).parent().addClass("full"); 
                    $(this).parent().css('background-color', '#000099');

                   
                }else{
                    $(this).parent().css('background-color', '#FFFFFF');

                 }

                 $("#pilihan" + i ).css('background-color', '#000099');
                 $("#sisa" + i).val(0);

               }

               else

               {

              


                $("#pilihan" + i).prop('checked', true);

                if($("#pilihan" + i).is(":checked")){
                    $("#pilihan" + i).parent().removeClass("full"); 
                    $("#pilihan" + i).parent().addClass("notfull"); 

                   
                }else{
                    $("#pilihan" + i).parent().removeClass("white");  
                 } 

                $("#pilihan" + i ).css('background-color','#990000');
                $("#sisa" + i).val(currency(Math.abs(sisa)));

                 break;



               }

          

        

            }

             else

             {

                 var tagihan =   $("#tagihan" + i).html();

                 var sisa  = sisa - tagihan ;


              


                 if ( sisa >= 0 )

                  {




                 $("#pilihan" + i).prop('checked', true);

                 if($("#pilihan" + i).is(":checked")){
                    $("#pilihan" + i).parent().removeClass("notfull"); 
                    $("#pilihan" + i).parent().addClass("full"); 
                    $(this).parent().css('background-color', '#000099');
                 }
                else
                {
                  
                  $(this).parent().css('background-color', '#FFFFFF');

                 }
                 $("#pilihan" + i ).css('background-color', '#000099');
                 $("#sisa" + i).val(0); //'MERAH'

                 }

               else 

               {

                 $("#pilihan" + i).prop('checked', true);

                 if($("#pilihan" + i).is(":checked")){
                    $("#pilihan" + i).parent().removeClass("full"); 
                    $("#pilihan" + i).parent().addClass("notfull"); 

                  
                }else{
                    $("#pilihan" + i).parent().removeClass("white");  
                 }

                 $("#pilihan" + i ).css('background-color', '#990000');
                 $("#sisa" + i).val(currency(Math.abs(sisa))); //'MERAH'


                 break;


               }


                


    

         }



      
    }



    }



    $(function() {
        // total_harga = 0;
        // $(".data_harga").each(function(k,v){
        //     total_harga += parseInt(v.innerHTML);
        //     console.log(total_harga);   
        // })
        $('.datetimepicker').datetimepicker({
            viewMode: 'days',
            format: 'MM-DD-YYYY'
        });


        $("#isi").on("change", '.total_tagihan', function() {
            data = $(this).parent().parent().children('.data_harga').html();
            if ($(this).is(':checked')) {
                if ($("#sub_total").val() == "") {
                    $("#sub_total").val(0);
                } else {
                    data = parseInt(data) + parseInt($("#sub_total2").val());



                    $("#sub_total").val(currency(data));

                    $("#sub_total2").val(data);

                    var sub_total = $("#sub_total2").val();

                    var diskon = $("#diskon").val();

                    var total_diskon = (diskon / 100) * sub_total;

                    var total_akhir = sub_total - total_diskon;

                    $("#total_tagihan").val(currency(total_akhir));


                    $("#total_bayar").val(currency(total_akhir));





                }
            } else {
                if ($("#sub_total").val() == "") {
                    $("#sub_total").val(0);
                } else {
                    data = parseInt($("#sub_total2").val()) - parseInt(data);



                    $("#sub_total").val(currency(data));

                    $("#sub_total2").val(data);


                    var sub_total = $("#sub_total2").val();

                    var diskon = $("#diskon").val();

                    var total_diskon = (diskon / 100) * sub_total;

                    var total_akhir = sub_total - total_diskon;

                    $("#total_tagihan").val(currency(total_akhir));


                    $("#total_bayar").val(currency(total_akhir));






                }
            }

        });


    });

    $("#pilih_reg").change(function() {


        var pilih_reg = $("#pilih_reg").val();




        url = '<?= site_url(); ?>/transaksi_lain/p_pembayaran_tvi/lihat_reg';

        //console.log(this.value);
        $.ajax({
            type: "get",
            url: url,
            data: {
                pilih_reg: pilih_reg
            },
            dataType: "json",
            success: function(data) {
                console.log(data);



                $(".pay").show();




                // $("#id_tagihan").val(data.id_tagihan);
                $("#registrasi_id").val(data.registrasi_id);

                $("#jenis_layanan").val(data.jenis_layanan);
                $("#nomor_tagihan").val(data.nomor_tagihan);

                $("#paket_layanan").val(data.paket_name);
                //	$("#sub_total").val(data.total);

                var diskon = $("#diskon").val();

                // var sub_total = $("#sub_total").val();

                //  var total_diskon = (diskon / 100) * sub_total;

                //  var total_akhir = sub_total - total_diskon;

                // 	$("#total_tagihan").val(currency(total_akhir));


                //	$("#total_bayar").val(currency(total_akhir));


                var registrasi_id = $("#registrasi_id").val();



            }


        })



        var registrasi_id = pilih_reg;

        url = '<?= site_url(); ?>/transaksi_lain/p_pembayaran_tvi/lihat_tagihan';


        //var registrasi_id = $("#registrasi_id").val();

        //var registrasi_id = 1249;

        alert(registrasi_id);



        $.ajax({
            type: "post",
            url: url,
            data: {
                registrasi_id: registrasi_id
            },
            dataType: "json",
            success: function(data) {
                console.log(data);

                $("#list_tagihan").show();

                $("#isi").show();

                var no = 1;

                $("#isi")[0].innerHTML = "";
                total = 0;
                for (var i = 0; i < data.length; i++) {

                    if ($(".no").html()) {
					idf = parseInt($(".no").last().html()) + 1;
				   } else {
					idf = 1;
				   }
                    $("#isi").append(
                         "<tr class='even pointer'    id='srow" + idf + "'>" +
                        "<td class='no' >" + (i + 1) + "</td>" +
                        "<td hidden><input name='id_tagihan[]' value='" + data[i].id_tagihan + "'> </td>" +
                        "<td><input type='checkbox' class='total_tagihan flat table-check check blue' name='pilihan'  value='" + data[i].id_tagihan + "'  checked id='pilihan" + idf + "' disabled >" +
                        "</td>" +
                        "<td>" + data[i].flag_type + "</td>" +
                        "<td>" + data[i].tanggal_tagihan + "</td>" +
                        "<td>" + data[i].jenis_paket + "</td>" +
                        "<td  class='data_harga' id='tagihan" + idf + "' >" + data[i].total_tagihan + "</td>" +
                        "<td> <input type='text' class='form-control sisa' name='sisa[]' id='sisa" + idf + "' readonly placeholder='Masukkan Sisa' ></td>" +
                        "</tr>");
                    total += parseInt(data[i].total_tagihan);
                }


                $("#sub_total").val(currency(total));            


                $("#total_tagihan").val(currency(total));


                $("#total_tagihan2").val(total);




            }












        })


    });



    // $("#diskon").keyup(function(){
    //     if($("#diskon").val()<=100){
    //         $("#jumlah_diskon").val(currency(parseInt($("#diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))*parseInt($("#sub_total").val().toString().replace(/[\D\s\._\-]+/g, ""))/100));
    //         console.log(
    //             parseInt($("#total").val().toString().replace(/[\D\s\._\-]+/g, ""))
    //         );
    //         console.log(
    //             parseInt($("#jumlah_diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))
    //         );
    //         $("#total").val(
    //             currency(
    //             parseInt($("#sub_total").val().toString().replace(/[\D\s\._\-]+/g, ""))-parseInt($("#jumlah_diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))
    //             )
    //         );
    //     }else{
    //         $("#diskon").val(100);
    //     }
    // });
</script>