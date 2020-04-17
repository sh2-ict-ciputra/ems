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
    <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_monitoring_tvi'">
      <i class="fa fa-arrow-left"></i>
      Back
    </button>
    <button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_monitoring_tvi/add'">
      <i class="fa fa-repeat"></i>
      Refresh
    </button>
  </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
  <br>
  <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url()?>/transaksi_lain/p_monitoring_tvi/save_tagihan?id=<?=$this->input->get('id'); ?>">


    

    <div id="view_data">
      <div class="row" style="margin-top: 35px;">
        <div class="col-md-12">
          <div class="col-md-6">

            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text"  id="kawasan_name" name="kawasan_name" value="<?=$dataRegistrasi->kawasan_name; ?>" readonly class="form-control">
              </div>
            </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text"  id="blok_name" name="blok_name"  value="<?=$dataRegistrasi->blok_name; ?>" readonly class="form-control unit">
              </div>
            </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text"  id="unit_name" name="unit_name" value="<?=$dataRegistrasi->unit; ?>" readonly class="form-control unit">
              </div>
            </div>



           
            <div class="form-group two">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pemasangan</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select name="jenis_pemasangan" id="jenis_pemasangan" required="" class="form-control select2">
                  <option value="">--Pilih Jenis Pemasangan--</option>
                  <option value="pemasangan_baru">Pemasangan Baru</option>
                  <option value="pindah_paket">Pindah Paket</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-6">

           
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" name="nomor_registrasi" id="nomor_registrasi" value="<?=$dataRegistrasi->nomor_registrasi; ?>" readonly class="form-control unit">
              </div>
            </div>
             <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Customer</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text"  value="<?=$dataRegistrasi->customer_name; ?>" name="customer" id="customer" readonly class="form-control">
                </div>
              </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" name="nomor_telepon" id="nomor_telepon" value="<?=$dataRegistrasi->homephone; ?>" readonly class="form-control unit">
              </div>
            </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text"  name="nomor_handphone" id="nomor_handphone" value="<?=$dataRegistrasi->mobilephone; ?>"" readonly class="form-control unit">
              </div>
            </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="email" required="" placeholder="Email" id="email"  name="email" value="<?=$dataRegistrasi->email; ?>" readonly class="form-control unit">
              </div>
            </div>

           




          </div>
        </div>
      </div>

    
      <h4 id="label_transaksi" hidden>Transaksi</h4>
      <hr>
      <div class="row">
        <div class="col-md-12">
          <div class="col-md-6">


             <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Billing</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" name="nomor_billing" id="nomor_billing" value="<?=$kode_tagihan?>"  readonly class="form-control unit">
              </div>
            </div>


            <div class="form-group two ">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal </label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class='input-group date '>
                  <input type="text" class="form-control datetimepicker" name="tanggal" value="" 
                  id="tanggal" placeholder="Masukkan Tanggal"> <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>

           

            <div class="form-group two">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Paket</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select id="pilih_paket" required="" name="pilih_paket" class="form-control select2">
                  <option value="" selected="" disabled="">--Pilih Paket--</option>
                  <?php
                        foreach ($dataPaket as $v) {
                            echo("<option value='$v[id] | $v[harga_jual] | $v[description] | $v[biaya_pasang_baru]'>$v[name]</option>");
                        }
                        ?>
                </select>
              </div>
            </div>

           
            <div class="form-group two">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Harga</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" name="total" readonly="" id="total" value=""  class="form-control currency">
              </div>
            </div>
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

    function currency(input) {
    var input = input.toString().replace(/[\D\s\._\-]+/g, "");
    input = input ? parseInt(input, 10) : 0;
    return (input === 0) ? "" : input.toLocaleString("en-US");
  }




  $(function () {
    $('.datetimepicker').datetimepicker({
      viewMode: 'days',
      format: 'MM-DD-YYYY'
    });

  });



  $("#pilih_paket").change(function () {

    var paket = $("#pilih_paket").val();
    var pecah = paket.split('|');
    var data = pecah[0];
    if (data == "") {
      $("#harga_paket").val();
      $("#keterangan_paket").val();
    } else {
      var harga_paket = pecah[1];
      var keterangan = pecah[2];
      var harga_pasang = pecah[3];


     // $("#harga_paket").val(currency(harga_paket));
    //  $("#harga_pasang").val(currency(harga_pasang));


      var total = parseInt(harga_pasang) + parseInt(harga_paket);
      // var diskon = $("#diskon").val();
      // var total_diskon = (diskon / 100) * total;
      // var total_akhir = total - total_diskon;

      // $("#sub_total").val(currency(total));
      // $("#jumlah_diskon").val(currency(total_diskon));
      // $("#total").val(currency(total_akhir));
      // $("#keterangan_paket").val(currency(keterangan));

        $("#total").val(currency(total));
    }

  });



    $("#diskon").keyup(function(){
        if($("#diskon").val()<=100){
            $("#jumlah_diskon").val(currency(parseInt($("#diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))*parseInt($("#sub_total").val().toString().replace(/[\D\s\._\-]+/g, ""))/100));
            console.log(
                parseInt($("#total").val().toString().replace(/[\D\s\._\-]+/g, ""))
            );
            console.log(
                parseInt($("#jumlah_diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))
            );
            $("#total").val(
                currency(
                parseInt($("#sub_total").val().toString().replace(/[\D\s\._\-]+/g, ""))-parseInt($("#jumlah_diskon").val().toString().replace(/[\D\s\._\-]+/g, ""))
                )
            );
        }else{
            $("#diskon").val(100);
        }
    });
</script>
