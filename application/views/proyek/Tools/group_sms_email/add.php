<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<script type="text/javascript" src="http://localhost/emsNew/vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?= site_url() ?>/P_master_cara_pembayaran'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.href='<?= site_url() ?>/P_master_cara_pembayaran/add'">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br />

    <form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url() ?>/proyek/tools/p_group_sms_email/save?id=<?= $this->input->get('id'); ?>">

        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Grup</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" name="nama_grup" id="nama_grup" required placeholder="Masukkan Nama Grup" class="form-control">
                    </div>

                </div>


                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Type Grup</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control select2" name="type" id="type">
                            <option value="sms">SMS</option>
                            <option value="email">Email</option>
                        </select>
                    </div>
                </div>
               
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan / Catatan</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan Keterangan "></textarea>
                    </div>
                </div>
            </div>



        </div>
        <div class="clearfix"></div>
        <br>
        <br>
        <div class="col-sm-12" id="dataisi">
            <div class="card-box table-responsive">
                <table id="table_grup" class="table table-striped table-bordered bulk_action">
                    <thead>
                        <th><input type="checkbox" id="check-all" class="flat"></th>
                      
                        <th>Kawasan</th>
                        <th>Blok</th>
                        <th>Unit</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>No Hp</th>
                      
                       
                    </thead>
                    <tbody id="isi">
                        <?php
                       
                        // echo '<PRE>';
                        // print_r($dataCustomer);
                        // echo '</pre>';



                        foreach ($dataCustomer as  $v) {
                            
                            echo "<tr class='even pointer'>";
                            echo "<td><input type='checkbox' class='flat table-check check' name='unit[]' value='" . $v['unit_id'] . "'></td>";
                         
                            echo "<td>$v[kawasan_name]</td>";
                            echo "<td>$v[blok_name]</td>";
                            echo "<td>$v[no_unit]</td>";
                            echo "<td>$v[customer_name]</td>";
                            echo "<td>$v[email]</td>";
                            echo "<td>$v[no_hp]</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
</div>



<div class="form-group">
    <div class="center-margin">
        <button class="btn btn-primary" type="reset">Reset</button>
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</div>
</form>
</div>


<!-- jQuery -->
<script type="text/javascript">

var table_grup = $("#table_grup");
	var table_grup_dt = table_grup.dataTable({
		order: [[1, "asc"]],
		columnDefs: [{
			orderable: !1,
			targets: [0]
		}]
	});



    $("#checkall").change(function() {


        if ($(this).is(':checked')) {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
            $("#labelcheck").text('Uncheck All');
        } else {
            $(".check").removeAttr('checked');
            $("#labelcheck").text('Check All');
        }
    });






    $("#lihat_blok").hide();







    $("#kawasan").change(function() {


        // alert('tess');

        url = '<?= site_url(); ?>/transaksi_lain/P_generate_billing_tvi/lihat_blok';


        var kawasan = $("#kawasan").val();
        //console.log(this.value);
        $.ajax({
            type: "post",
            url: url,
            data: {
                kawasan: kawasan
            },
            dataType: "json",
            success: function(data) {
                console.log(data);

                $("#lihat_blok").show();

                $("#blok")[0].innerHTML = "";
                $("#blok").append("<option value='' >Pilih Blok</option>");
                $("#blok").append("<option value='all' >Semua Blok</option>");
                $.each(data, function(key, val) {
                    $("#blok").append("<option value='" + val.id + "'>" + val.name.toUpperCase() + "</option>");
                });

            }


        })
    });




    $("#blok").change(function() {


        //alert('tess');


        var kawasan = $("#kawasan").val();

        var blok = $("#blok").val();


        url = '<?= site_url(); ?>/transaksi_lain/P_generate_billing_tvi/lihat_unit';


        //console.log(this.value);
        $.ajax({
            type: "post",
            url: url,
            data: {
                kawasan: kawasan,
                blok: blok
            },
            dataType: "json",
            success: function(data) {
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
                        "<td><input type='checkbox' class='flat table-check check' name='unit[]' value='" + data[i].unit_id + "'></td>" +
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
</script> 