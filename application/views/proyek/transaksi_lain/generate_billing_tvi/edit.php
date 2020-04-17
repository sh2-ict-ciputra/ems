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
		<button class="btn btn-warning" onclick="location.href='<?=site_url(); ?>/P_posting_tvi';">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_posting_tvi/edit?id=<?=$this->input->get('id')?>'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_posting_tvi/edit?id=<?=$this->input->get('id'); ?>">



		 <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Posting TV Internet</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                     

                      <div class="col-md-12">
                          <select required="" id="pilih_transfer" name="pilih_transfer" class="form-control select2" disabled>
                            <option value="" selected="" disabled="">--Pilih No Transfer--</option>
                                      <?php
                                            foreach ($dataTransfer as $v) {
                                                echo("<option value='$v[id]'>$v[customer] - $v[bank] - $v[nomor_rekening] - $v[kode] </option>");
                                            }
                                        ?>
                          </select>
                     </div>

        <div class="clear-fix"></div>
        <br>

         <div class="clearfix"></div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
               <select required="" id="customer" name="customer" class="form-control select2" disabled>
                            <option value="" selected="" disabled="">--Pilih Customer--</option>
                                      <?php
                                            foreach ($dataCustomer as $v) {
                                                echo("<option value='$v[id]'>$v[customer] </option>");
                                            }
                                        ?>
                          </select>
        </div>
      </div>
      
      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Transfer</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?=$data_select->kode_transfer; ?>" required name="kode_transfer" readonly>
                        </div>
                        <input type="hidden" name="action" value="Posting">
           
            </div>
      
     
      
      
            </div>
         
      
              <div class="col-md-6">
              <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                           <div class='input-group date mydatepicker' >
                              <input required="" type="text" class="form-control" name="tanggal" placeholder="Auto Generated" readonly value="<?=$data_select->tanggal; ?>"
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                        </div>
                   </div>
              </div>
        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?=$data_select->nama_bank; ?>" required name="bank" readonly  >
                        </div>
                        
            </div>
      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Rekening</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?=$data_select->nomor_rekening; ?>" required name="nomor_rekening" readonly  >
                        </div>
                        
            </div>
      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Rekening</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?=$data_select->nama_rekening; ?>" required name="nama_rekening" readonly  >
                        </div>
                        
            </div>
      
        
        
        
        
        
          </div>
      
      <div class="clearfix"></div>
          <div class="col-md-6">
       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Bayar Transfer</label>  
                        <div class="col-md-8 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?=$data_select->total_biaya_transfer; ?>" required name="total_bayar" id="total_bayar" readonly>
                        </div>
             <div class="col-md-1 col-sm-9 col-xs-12">
             VS
             </div>
            
                        
            </div>
      
      
      
      
      </div>
      
       <div class="col-md-6">
      
      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Tagihan Billing</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?=$data_select->total_tagihan; ?>" required name="total_tagihan" id="total_tagihan" readonly >
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
      <div class="col-sm-12" id="dataisi">
        <div class="card-box table-responsive">
          <table id="example" class="table table-responsive table-stripped table-hover table-bordered">
            <thead>
              <th>No</th>
              <th>Tanggal</th>
              <th>Paket Layanan</th>
              <th>BWIDTH</th>
              <th>Mulai</th>
              <th>Berakhir</th>
              <th>PIL</th>
              <th>Harga</th>
        <th>TRP-TV</th>
        <th>No Registrasi</th
            </thead>
            <tbody id="isi"></tbody>
          </table>
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


<!-- jQuery -->
<script type="text/javascript">
	function currency(inp) {
		var input = inp.val().toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		console.log("test");
		console.log((input === 0) ? "" : input.toLocaleString("en-US"));
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}
	$(function () {
		$(".currency").val(currency($(".currency")));
		$("#btn-update").click(function () {
			$("#tanggal_pembayaran").removeAttr("disabled");
			$("#jenis_pembayaran").removeAttr("disabled");
			$("#nomor_rekening").removeAttr("disabled");
			$("#nama_rekening").removeAttr("disabled");
			$("#total_transfer").removeAttr("disabled");
			$("#keterangan").removeAttr("disabled");
			$("#status").removeAttr("disabled");
			$("#btn-cancel").removeAttr("style");
			$("#btn-update").val("Update");
			$("#btn-update").attr("type", "submit");
		});
		$("#btn-cancel").click(function () {
			$("#tanggal_pembayaran").attr("disabled", "")
			$("#jenis_pembayaran").attr("disabled", "")
			$("#nomor_rekening").attr("disabled", "")
			$("#nama_rekening").attr("disabled", "")
			$("#total_transfer").attr("disabled", "")
			$("#keterangab").attr("disabled", "")
		    $("#status").attr("disabled", "")
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
					$("#dataModal").html("");
					if (data[data.length - 1] == 2) {
						console.log(data[0]);
						for (i = 0; i < data[0].length; i++) {
							$.each(data[1], function (key, val) {
								if (val.name == data[0][i].name) {
									console.log(val.name);
									$("#dataModal").append("<tr><th>" + data[0][i].name + "</th><td>" + val.value + "</td><td>" + data[0]
										[i].value + "</td></tr>");
								}
							});
						}
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
	});

</script>
