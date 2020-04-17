<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>


<!-- switchery -->
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>
<!-- date time picker -->
<script type="text/javascript" src="<?=base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>


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
		  <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/transaksi_lain/p_monitoring_tvi/detail?id=<?=$data_select->id; ?>'">
      <i class="fa fa-arrow-left"></i>
      Back
    </button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain/P_monitoring_tvi/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/transaksi_lain/P_monitoring_tvi/edit_tagihan?id=<?=$this->input->get('id'); ?>">



		 <div id="view_data">
      <div class="row" style="margin-top: 35px;">
        <div class="col-md-12">
          <div class="col-md-6">

            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text"  id="kawasan_name" name="kawasan_name" value="<?=$data_select->kawasan_name; ?>" readonly class="form-control">
                 <input type="hidden" name="registrasi_id"  id="registrasi_id" value="<?=$data_select->id; ?>" >
              </div>
            </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text"  id="blok_name" name="blok_name"  value="<?=$data_select->blok_name; ?>" readonly class="form-control unit">
              </div>
            </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Unit</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text"  id="unit_name" name="unit_name" value="<?=$data_select->unit; ?>" readonly class="form-control unit">
              </div>
            </div>



           
            <div class="form-group two">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pemasangan</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select name="jenis_pemasangan" id="jenis_pemasangan" required="" class="form-control select2 disabled-form" disabled>
                  <option value="" >--Pilih Jenis Pemasangan--</option>
                  <option value="pemasangan_baru" <?=$data_select->jenis_pemasangan=='pemasangan_baru'?'selected':''?> >Pemasangan Baru</option>
                  <option value="pindah_paket" <?=$data_select->jenis_pemasangan== 'pindah_paket'?'selected':''?> >Pindah Paket</option>
                </select>
              </div>
          </div>
          </div>
          <div class="col-md-6">

           
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Registrasi</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" name="nomor_registrasi" id="nomor_registrasi" value="<?=$data_select->nomor_registrasi; ?>" readonly class="form-control unit">
              </div>
            </div>
             <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Customer</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text"  value="<?=$data_select->customer_name; ?>" name="customer" id="customer" readonly class="form-control">
                </div>
              </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Telp</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" name="nomor_telepon" id="nomor_telepon" value="<?=$data_select->homephone; ?>" readonly class="form-control unit">
              </div>
            </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">No Hp</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text"  name="nomor_handphone" id="nomor_handphone" value="<?=$data_select->mobilephone; ?>"" readonly class="form-control unit">
              </div>
            </div>
            <div class="form-group unit">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="email" required="" placeholder="Email" id="email"  name="email" value="<?=$data_select->email; ?>" readonly class="form-control unit">
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
                <input type="text" name="nomor_billing" id="nomor_billing" value="<?=$data_select->kode_bill; ?>"   readonly class="form-control unit">
              </div>
            </div>


            <div class="form-group two ">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal </label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class='input-group date '>                  
                  <input type="text" class="form-control datetimepicker disabled-form" name="tanggal" value="<?=$data_select->tanggal; ?>"  
                  id="tanggal" placeholder="Masukkan Tanggal" disabled> <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>
            </div>

           

            <div class="form-group two">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Paket</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select id="pilih_paket" required="" name="pilih_paket" class="form-control select2 disabled-form" disabled>
                  <option value="" selected="" disabled="">--Pilih Paket--</option>
                         <?php
                              foreach ($dataPaket as $v) {

                                if ($data_select->jenis_paket_id == $v['id']) {  
                                    echo("<option value='$v[id] | $v[harga_jual] | $v[description] | $v[biaya_pasang_baru]' selected>$v[name]</option>");
                                      }
                                else  if ($data_select->jenis_paket_id != $v['id']) 
                                      
                                      
                                      {
                                     echo("<option value='$v[id] | $v[harga_jual] | $v[description] | $v[biaya_pasang_baru]'>$v[name]</option>");
                                }

                              }
                          ?>
                </select>
              </div>
            </div>

           
            <div class="form-group two">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Harga</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" name="total" readonly="" id="total" value="<?=$data_select->total; ?>"  class="form-control currency">
              </div>
            </div>
          </div>
        </div>
      </div>

		

		<div class="col-md-12">
			<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
			<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
		</div>
	</form>
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


<script type="text/javascript">
  function currency(input) {
    var input = input.toString().replace(/[\D\s\._\-]+/g, "");
    input = input ? parseInt(input, 10) : 0;
    return (input === 0) ? "" : input.toLocaleString("en-US");
  }




  $("#btn-update").click(function () {
    disableForm = 0;
    $(".disabled-form").removeAttr("disabled");
    $("#btn-cancel").removeAttr("style");
    $("#btn-update").val("Update");
    $("#btn-update").attr("type", "submit");
  });
  $("#btn-cancel").click(function () {
    disableForm = 1;
    $(".disabled-form").attr("disabled", "")
    $("#btn-cancel").attr("style", "display:none");
    $("#btn-update").val("Edit")
    $("#btn-update").removeAttr("type");
  });


  $(".btn-modal").click(function () {
    url = '<?=site_url(); ?>/core/get_log_detail';
    console.log($(this).attr('data-transfer'));
    console.log($(this).attr('data-type'));
    $.ajax({
      type: "POST",
      data: {
        id: $(this).attr('data-transfer'),
        type: $(this).attr('data-type')
      },
      url: url,
      dataType: "json",
      success: function (data) {
        console.log(data);
        // var items = []; 
        // $("#changeJP").attr("style","display:none");
        // $("#saveJP").removeAttr('style');
        // $("#jabatan").removeAttr('disabled');
        // $("#jabatan")[0].innerHTML = "";
        // $("#project")[0].innerHTML = "";
        // $("#jabatan").append("<option value='' selected disabled>Pilih Jabatan</option>");
        console.log($(this).attr('data-type'));
        $("#dataModal").html("");
        if (data[data.length - 1] == 2) {
          console.log(data[0]);
          for (i = 0; i < data[0].length; i++) {
            var tmpj = 0;
            for (j = 0; j < data[0].length; j++) {
              if (data[1][j] != null) {
                if (data[1][j].name == data[0][i].name) {
                  $("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + data[1][j].value + "</td><td>" + data[
                      0]
                    [i].value + "</td></tr>");
                  tmpj++;
                }

              }
            }
            if (tmpj == 0) {
              $("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td></td><td>" + data[0]
                [i].value + "</td></tr>");
            }
          }

          //  if(data[1].length > data[0].length){
          //    $.each(data[1], function (key, val) {
          //      if (val.name == data[0][i].name) {
          //        console.log(val.name);
          //        $("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0]
          //          [i].value + "</td></tr>");
          //      }
          //    });
          //  }else{
          //    $.each(data[0], function (key, val) {
          //      if (val.name == data[1][i].name) {
          //        console.log(val.name);
          //        $("#dataModal").append("<tr><th>" + data[1][i].name + "</th><td>" + val.value + "</td><td>" + data[1]
          //          [i].value + "</td></tr>");
          //      }
          //    });
          //  }
          // }
        } else {
          $.each(data, function (key, val) {
            if (data[data.length - 1] == 1) {
              console.log(data);
              if (val.name)
                $("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td></td><td>" + val.value +
                  "</td></tr>");
            } else if (data[data.length - 1] == 2) {

            } else if (data[data.length - 1] == 3) {
              console.log(data);
              if (val.name)
                $("#dataModal").append("<tr><th>" + val.name.toUpperCase() + "</th><td>" + val.value +
                  "</td><td></td></tr>");
            }
          });
        }

      }
    });

  });
  $('.select2').select2({
    width: 'resolve'
  });

  $(document).keydown(function (e) {
    return (e.which || e.keyCode) != 116;
  });

  $(document).keydown(function (e) {
    if (e.ctrlKey) {
      return (e.which || e.keyCode) != 82;
    }
  });


  $(function () {
    $('.datetimepicker').datetimepicker({
      viewMode: 'days',
      format: 'MM-DD-YYYY'
    });

  });

  $(".datetimepicker").val("<?=$data_select->tanggal; ?>")


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




</script>
