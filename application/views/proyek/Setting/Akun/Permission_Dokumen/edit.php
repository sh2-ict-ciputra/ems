<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<!DOCTYPE html>

<style>
    .range_akhir,
    .range_awal {
        text-align: right;
    }

    .range_akhir {
        color: transparent;
        text-shadow: 0 0 0 black;

        &:focus {
            outline: none;
        }
    }
</style>

<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href = '<?= site_url('Setting/P_setting_approval') ?>'">
            <i class="fa fa-arrow-left"></i>
            Back
        </button>
        <button class="btn btn-success" onClick="window.location.reload()">
            <i class="fa fa-repeat"></i>
            Refresh
        </button>
    </h2>
</div>
<div class="clearfix"></div>
</div>
<div class="x_content">
    <br>
    <form id="form" class="form-horizontal form-label-left">
        <div class="com-lg-4 col-md-4 col-xs-4">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Dokumen</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" value="Semua Dokumen" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Dokumen</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" value="Semua Kode Dokumen" required readonly>
                </div>
            </div>
            <!-- <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <textarea class="form-control" rows="4" readonly><?= $data->description ?></textarea>
                </div>
            </div> -->
        </div>

        <div class="com-lg-8 col-md-8 col-xs-8">
            <div class="clearfix"></div>
            <div class="x_title">
                <div class="col-md-6">
                    <h2> Mengetahui</h2>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <select id="select-mengetahui" type="text" class="form-control select2" name="mengetahui[]" multiple='multiple'>
                    </select>
                </div>
            </div>

            <div class="clearfix"></div>
            <br>
            <br>
            <div class="x_title">
                <div class="col-md-6">
                    <h2> Wewenang - Berjenjang </h2>
                </div>
                <div class="clearfix"></div>
            </div>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Group User</th>
                        <th>Nilai Range Awal<br> (Rp.)</th>
                        <th>Nilai Range Akhir (Rp.)</th>
                        <th>Jarak Aprrove (Hari)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tbody_range">
                </tbody>
            </table>
            <button type="button" id='btn-add-wewenang' class="btn btn-danger pull-right"><i class="fa fa-plus"></i> Add Wewenang</button>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <div class="center-margin">
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <a id="submit" type="submit" class="btn btn-success">Submit</a>
                </div>
            </div>
        </div>
    </form>
    <script>
        console.log(<?php echo(json_encode($data_selected)); ?>);
        var selected = <?php echo(json_encode($data_selected)); ?>;

        var option_grup_user = 
            "<option></option>"+
            "<?php foreach ($data_group_user as $k => $v):?>"+
            "               <option value='<?=$v->id?>'><?=$v->name?></option>"+
            "           <?php endforeach;?>";
        var row =
            "<tr class=''>" +
            "   <td class=''>" +
            "       <select name='wewenang[]' class='wewenang form-control select2' multiple='multiple' style='width:16vw'>" +
                        option_grup_user +
            "       </select>" +
            "   </td>" +
            "   <td class=''>" +
            "       <input type='text' class='range-awal text-right form-control number-format' name='range_awal[]' readonly required>" +
            "   </td>" +
            "   <td class=''>" +
            "       <input type='text' class='range-akhir text-right form-control number-format' name='range_akhir[]' readonly required>" +
            "   </td>" +
            "   <td class=''>" +
            "       <input type='text' class='jarak-approve text-right form-control number-format' name='jarak_approve[]' value=0 required>" +
            "   </td>" +
            "   <td class=''>" +
            "           <a class='btn btn-danger btn-delete-wewenang'>" +
            "               Hapus" +
            "           </a>" +
            "   </td>" +
            "</tr>" ;
            function change_name_wewenang(){
                $.each($("#tbody_range").children(), function( k, v ) {
                    $(this).find(".wewenang").attr('name',"wewenang["+k+"][]");
                })
            }
            function ini_select2(){
                $(".select2").select2({
                    placeholder:'-- Pilih Group User --'
                });
            }
            function destroy_select2(){
                $('.select2').select2().each(function(i,item){
                    $(item).select2("destroy");
                });
            }
            function refresh_range(){
                $.each($("#tbody_range").children(), function( k, v ) {
                    if(k > 0){
                        $(this).find('.range-awal').val(formatC(unformatC($(this).prev().find('.range-akhir').val())+1));
                    }
                    if(unformatC($(this).find('.range-akhir').val()) < unformatC($(this).find('.range-awal').val()) || $(this).find('.range-akhir').val() == 'Tak Hingga'){
                        $(this).find('.range-akhir').val(formatC(unformatC($(this).find('.range-awal').val())+1));
                    }
                    $(this).find('.range-akhir').attr("readonly",false);
                    if(k == 0){
                        $(this).find('.range-awal').val(0);
                        $(this).find('.range-akhir').attr("readonly",false);
                    }
                    if(k == ($("#tbody_range").children().length - 1)){
                        $(this).find('.range-akhir').val('Tak Hingga')
                        $(this).find('.range-akhir').attr("readonly",true);
                    }
                });
            }
            $(function() {
                $("#tbody_range").append(row);
                $("#select-mengetahui").append(option_grup_user);
                // $(".select2").select2({
                //     // width:'resolve'
                // });
                ini_select2();
                refresh_range();

                $("#btn-add-wewenang").click(function(){
                    $("#tbody_range").append(row);
                    ini_select2();
                    refresh_range();
                    change_name_wewenang();
                })
                $("body").on("click",".btn-delete-wewenang",function(){
                    $(this).parents('tr').remove();
                    refresh_range();
                    change_name_wewenang();
                });
                $("body").on("keyup",".number-format",function(){
                    $(this).val(formatC($(this).val()));
                })
                $("body").on("change",".number-format",function(){
                    refresh_range();
                })
                $("#submit").click(function(){
                    $.ajax({
                        type: "get",
                        url: '<?= site_url(); ?>/Setting/P_setting_approval/ajax_save',
                        data: $("form").serialize(),
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            // // parent.find(".jumlah-satuan").val(1);
                            // $("#satuan").html(data.satuan);
                            // $("#harga_satuan").val(data.harga);
                            // $("#biaya_registrasi").val(data.biaya_registrasi);
                            // $("#biaya_pemasangan").val(data.biaya_pemasangan);
                            // // $("#harga_bulan_pertama").val(data.harga + data.biaya_registrasi);
                            // $("#min_berlangganan").val(data.minimal_langganan);
                            // // parent.find(".pemasangan").val(data.biaya_pemasangan);
                            // // parent.find(".harga-bulanan").val(data.harga);
                            // $(".total").val(data.harga + data.biaya_registrasi);
                            // if(DateDiff.inMonths(date_awal,date_akhir) < data.minimal_langganan)
                            //     $("#harga_satuan").val(data.biaya_satuan_tanpa_registrasi);
                            // else
                            //     $("#harga_satuan").val(data.biaya_satuan_registrasi);
                        }
                    });
                })
                if(selected.mengetahui)
                    if(selected.mengetahui.detail)
                        if(selected.mengetahui.detail.group_user)
                            $("#select-mengetahui").val(selected.mengetahui.detail.group_user).trigger('change');

                $.each(selected.wewenang.detail, function( k, v ) {
                    if(k == 0)
                        $("#tbody_range").html("");
                    $("#btn-add-wewenang").trigger("click");
                });
                $.each(selected.wewenang.detail, function( k, v ) {
                    $("[name = 'wewenang["+k+"][]']").val(v.group_user).trigger('change');
                    $("body").find(".range-awal").eq(k).val(v.nilai_awal).trigger('keyup').trigger('change');
                    $("body").find(".range-akhir").eq(k).val(v.nilai_akhir).trigger('keyup').trigger('change');
                    $("body").find(".jarak-approve").eq(k).val(v.jarak_approve).trigger('change');
                });
            });
    </script>