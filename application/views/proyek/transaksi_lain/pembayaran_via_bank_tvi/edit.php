<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>

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
		<button class="btn btn-warning" onclick="location.href='<?=site_url(); ?>/transaksi_lain/P_pembayaran_via_bank_tvi';">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/transaksi_lain//P_pembayaran_via_bank_tvi/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/transaksi_lain/P_pembayaran_via_bank_tvi/edit?id=<?=$this->input->get('id'); ?>">



		  <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Pembayaran Via Bank TV Internet</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                      <div class="title" id="print_proses"></div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?=$data_select->kode; ?>" required name="kode" id="kode" readonly>
                        </div>
                     
                      </div>

                     
                      <div class="form-group two ">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Pembayaran </label>
							<div class="col-md-6 col-sm-9 col-xs-12">
								<div class='input-group date '>
									<input type="text" class="form-control datetimepicker disabled-form" name="tanggal_pembayaran" id="tanggal_pembayarans" value="<?=$data_select->tanggal_pembayaran; ?>"
									 placeholder="Masukkan Tanggal Pembayaran" disabled> <span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                         <select required="" id="bank" name="bank" class="form-control select2 disabled-form" disabled>
							<option value="" selected="" disabled="">--Pilih Bank--</option>
							<?php
			                        foreach ($dataBank as $v) {

			                          if ($data_select->bank_id == $v['id']) { 	
			                            echo("<option value='$v[id]' selected>$v[name]</option>");
                                      } else {
                                        echo("<option value='$v[id]' >$v[name]</option>");
			                          }

			                        }
			                    ?>


			         	</select>
                        </div>
                      </div>

                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pembayaran</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <select name="jenis_pembayaran"id="jenis_pembayaran" class="form-control select2 disabled-form" disabled>
                            <option value="">--Pilih Jenis Pembayaran--</option>
                            <option value="va" <?=$data_select->jenis_pembayaran=='va'?'selected':''?>    >Virtual Account</option>
                            <option value="transfer"   <?=$data_select->jenis_pembayaran=='transfer'?'selected':''?> >Transfer</option>
                            <option value="auto_debet" <?=$data_select->jenis_pembayaran=='auto_debet'?'selected':''?>>Auto Debet</option>
                          </select>

                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Rekening</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <input type="text" class="form-control disabled-form" required name="nomor_rekening" id="nomr_rekening"  value="<?=$data_select->nomor_rekening; ?>" placeholder="Masukkan Nomor Rekening" disabled>
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Rekening</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <input type="text" class="form-control disabled-form" required name="nama_rekening" id="nama_rekening" value="<?=$data_select->nama_rekening; ?>" placeholder="Masukkan Nama Rekening" disabled>
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Transfer</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <input type="text" class="form-control currency disabled-form" required name="total_transfer" id="total_trnafer" value="<?=$data_select->total_transfer; ?>" placeholder="Masukkan Total Transfer" disabled>
                        </div>
                      </div>

                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                        <div class="col-md-6 col-sm-9 col-xs-12">
                          <textarea class="form-control disabled-form" name="keterangan" id="keterangan" value="<?=$data_select->keterangan; ?>" placeholder="Masukkan Keterangan" disabled></textarea>
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


<!-- jQuery -->
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

					// 	if(data[1].length > data[0].length){
					// 		$.each(data[1], function (key, val) {
					// 			if (val.name == data[0][i].name) {
					// 				console.log(val.name);
					// 				$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0]
					// 					[i].value + "</td></tr>");
					// 			}
					// 		});
					// 	}else{
					// 		$.each(data[0], function (key, val) {
					// 			if (val.name == data[1][i].name) {
					// 				console.log(val.name);
					// 				$("#dataModal").append("<tr><th>" + data[1][i].name + "</th><td>" + val.value + "</td><td>" + data[1]
					// 					[i].value + "</td></tr>");
					// 			}
					// 		});
					// 	}
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
