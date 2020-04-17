<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- select2 -->
<link href="<?= base_url() ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url() ?>vendors/select2/dist/js/select2.min.js"></script>
<!-- flat -->
<link href="<?= base_url() ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">

<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-warning" onClick="window.location.href = '<?= substr(current_url(), 0, strrpos(current_url(), "/")) ?>'">
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
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Level<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <select type="text" class="form-control select2" id="level_id" name="level_id" placeholder="Masukkan Nama Level">
                        <?php foreach ($data2 as $v) : ?>
                            <option value="<?= $v->id ?>" <?=$this->input->get("id")==$v->id?'selected':''?>><?= "$v->name" ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <table class="col-md-12 data-view table table-striped jambo_table">
                <thead>
                    <th>Menu</th>
                    <th>Read</th>
                    <th>Create</th>
                    <th>Update</th>
                    <th>Delete</th>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>

        <div class="clearfix"></div>

        <div class="form-group">
            <div class="center-margin">
                <a id="submit" type="submit" class="btn btn-success">Submit</a>
            </div>
        </div>
    </form>


    <script>
        function notif(title, text, type) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                styling: 'bootstrap3'
            });
        }
        function tableICheck() {
            $("input.flat").iCheck({
                checkboxClass: "icheckbox_flat-green",
                radioClass: "iradio_flat-green"
            })
        }
        function get_permission(id){
            // $('table').DataTable().destroy();
            $.ajax({
                type: "POST",
                data: {id:id},
                url: "<?= site_url() ?>/Setting/Akun/Permission_menu/ajax_permission_menu",
                dataType: "json",
                success: function(data){
                    $('table').DataTable().destroy();
                    $('tbody').html('');

                    $.each(data,function(k,v){
                        data = "<tr><td id='"+v.id+"'>"+v.menu+"</td>";
                        tmp = v.read ? 'checked' :'';
                        data += 
                                "<td><input type='checkbox' class='read flat table-check' value='1' "+tmp+"></td>";        
                        
                        tmp = v.create ? 'checked' :'';
                        data += 
                                "<td><input type='checkbox' class='create flat table-check' value='1' "+tmp+"></td>";        
                        
                        tmp = v.update ? 'checked' :'';
                        data += 
                                "<td><input type='checkbox' class='update flat table-check' value='1' "+tmp+"></td>";        
                        
                        tmp = v.delete ? 'checked' :'';
                        data += 
                                "<td><input type='checkbox' class='delete flat table-check' value='1' "+tmp+"></td></tr>";
                        $('tbody').append(data);
                    });
                },
                complete:function(){
                    $('table').DataTable({
                        "ordering": false,
                        "pageLength": 100
                    });
                    tableICheck();
                }
            });
        }
        $(function() {
            // $("table").on("ifChanged", "#check-all-bayar", function() {
            $("table").on("ifChanged",".read",function(){
                if(!$(this).is(":checked")){
                    $(this).parents("tr").find(".create").iCheck("uncheck");
                    $(this).parents("tr").find(".update").iCheck("uncheck");
                    $(this).parents("tr").find(".delete").iCheck("uncheck");
                }
                
            });
            $("table").on("ifChanged",".create,.update,.delete",function(){
                if($(this).is(":checked"))
                    $(this).parents("tr").find(".read").iCheck("check");
            });

            get_permission(<?=$this->input->get('id')?>);
            DT = $('table').DataTable({
                    "ordering": false,
                    "pageLength": 100

                    });
            $("#level_id").change(function(){
                get_permission($(this).val());
            })
            $('.select2').select2();

            $("#submit").click(function() {
                var arr = new Array();
                $.each($("tbody").children(),function(k,v){
                    var obj = new Object();
                    obj.menu_id = $("tbody").children().eq(k).children().eq(0).attr("id");
                    obj.read = $("tbody").children().eq(k).children().eq(1).children().children("input").is(":checked")?1:0;
                    obj.create = $("tbody").children().eq(k).children().eq(2).children().children("input").is(":checked")?1:0;
                    obj.update = $("tbody").children().eq(k).children().eq(3).children().children("input").is(":checked")?1:0;
                    obj.delete = $("tbody").children().eq(k).children().eq(4).children().children("input").is(":checked")?1:0;
                    arr.push(obj);
                })
                console.log(arr);
                $.ajax({
                    type: "POST",
                    data: {
                        id  : $("#level_id").val(),
                        data: arr
                    },
                    url: "<?= site_url() ?>/Setting/Akun/Permission_menu/ajax_save",
                    dataType: "json",
                    success: function(data) {
                        
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
            });
        });
    </script>