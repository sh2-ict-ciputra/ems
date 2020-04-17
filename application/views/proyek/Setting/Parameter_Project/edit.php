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

        <div class="com-lg-6 col-md-6 col-xs-12">
            <div class="form-group">

                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" value="<?= $data->name ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" class="form-control" value="<?= $data->code ?>" required readonly>
                </div>
            </div>
        </div>
        <div class="com-lg-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <textarea name="description" class="form-control" rows="4"><?= $data->description ?></textarea>
                </div>
            </div>
        </div>


        <div class="com-lg-12 col-md-12 col-xs-12">

            <div class="form-group">
                <label class="control-label" style="width:12%; float:left">Value</label>
                <?php if (stripos($data->code, 'ttd') !== FALSE) :?>
                <div class="col-md-3 col-sm-3">
                    <input id="file" type="file" class="form-control" name="file">
                </div>
                <?php elseif($data->code == 'user_approve_void_pembayaran'):?>
                    <select id="file" type="file" class="form-control" name="file">
                    
                <?php else : ?>
                <div class="col-md-11 col-sm-11 col-xs-11" style="width:88%">

                    <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a data-edit="white-space: pre;">
                                        <p style="font-size:17px">Tab</p>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a data-edit="fontSize 5">
                                        <p style="font-size:17px">Huge</p>
                                    </a>
                                </li>
                                <li>
                                    <a data-edit="fontSize 3">
                                        <p style="font-size:14px">Normal</p>
                                    </a>
                                </li>
                                <li>
                                    <a data-edit="fontSize 1">
                                        <p style="font-size:11px">Small</p>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                            <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                            <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                            <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                            <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                            <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                            <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                            <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                            <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                            <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                            <div class="dropdown-menu input-append">
                                <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                                <button class="btn" type="button">Add</button>
                            </div>
                            <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
                            <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                            <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                        </div>
                    </div>
                    <!-- <div id="editor-one" class="editor-wrapper"></div> -->
                    <div id="editor-one" class="editor-wrapper placeholderText" contenteditable="true"><?= $data->value ?></div>
                    <textarea name="value" id="descr" style="display:none;"></textarea>
                </div>
                <?php
                endif;
                ?>


            </div>
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
        function notif(title, text, type) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                styling: 'bootstrap3'
            });
        }

        $(function() {

            $("#submit").click(function() {
                console.log($("#form").serialize() + encodeURI($("#editor-one").html()));
                if(<?=(stripos($data->code, 'ttd') !== FALSE)?>+0!=0){
                    var data2 = new FormData();

                    $('input[type="file"]').each(function($i) {
                        data2.append($(this).prop("id"), $(this)[0].files[0]);
                    });
                    data = $("#form").serialize();
                    url = "<?= site_url() ?>/Setting/P_parameter_project/ajax_save_img?id=<?= $this->input->get("id") ?>"+"&"+data;
                    $.ajax({
                        type: "POST",
                        data: data2,
                        url: url,
                        dataType: "json",
                        cache: false,
                        contentType: false,
                        processData: false,
                        
                        success: function(data) {
                            console.log($("#form").serialize() + encodeURI($("#editor-one").html()));
                            if (data.status)
                                notif('Sukses', data.message, 'success');
                            else
                                notif('Gagal', data.message, 'danger');
                        }
                    });
                }else{
                    url = "<?= site_url() ?>/Setting/P_parameter_project/ajax_save?id=<?= $this->input->get("id") ?>";

                    $.ajax({
                        type: "POST",
                        data: $("#form").serialize() + encodeURI($("#editor-one").html()),
                        url: url,
                        dataType: "json",
                        success: function(data) {
                            console.log($("#form").serialize() + encodeURI($("#editor-one").html()));
                            if (data.status)
                                notif('Sukses', data.message, 'success');
                            else
                                notif('Gagal', data.message, 'danger');
                        }
                    });
                }


            });
        });
    </script>