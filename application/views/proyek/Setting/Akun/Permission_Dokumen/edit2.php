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
        <div class="com-lg-4 col-md-4 col-xs-4">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Dokumen</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" value="<?= $data->name ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Dokumen</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" value="<?= $data->code ?>" required readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <textarea class="form-control" rows="4" readonly><?= $data->description ?></textarea>
                </div>
            </div>
        </div>

        <div class="com-lg-8 col-md-8 col-xs-8">
            <div class="x_title">
                <div class="col-md-6">
                    <h2> Mengetahui </h2>
                </div>
                <div class="clearfix"></div>
            </div>

            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Range</th>
                        <th>Jabatan User</th>
                        <th>Jarak Aprrove (Hari)</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody id="tbody_range_mengetahui">

                    <?php
                    if ($data_range) :
                        foreach ($data_range as $i => $v) : ?>
                    <tr id='srow_mengetahui<?= ++$i ?>' class='rows'>
                        <td class='no'><?= $i ?></td>
                        <td id='jabatan_range_mengetahui'>
                            <input type="text" class="input_jabatan" name="jabatan_user_mengetahui[]" val="" hidden>
                            <select class="form-control multipleSelect multiple" multiple="multiple">
                                <?php foreach ($data_jabatan as $v2) : ?>
                                <option value='<?= $v2->id ?>' <?= array_search($v2->id, explode(",", $v->jabatan_id)) !== false ? 'selected' : '' ?>><?= $v2->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type='text' class='form-control jarak_approve_mengetahui' name='jarak_approve_mengetahui[]' placeholder='Masukkan Jarak Approve' required value=<?=$v->jarak_approve?>></td>
                        <td> <a class='btn btn-danger disabled-form' style="color:white;" onclick="hapusElemen('#srow_mengetahui<?= $i ?>'); return false;"><i class='fa fa-trash'></i> </a></td>
                    </tr>
                    <?php endforeach;
                    else : ?>
                    <tr id='srow_mengetahui<?= ++$i ?>' class='rows'>
                        <td class='no'><?= $i ?></td>
                        <td id='jabatan_range_mengetahui'>
                            <select name='jabatan_user_mengetahui[]' class='form-control select2'>
                                <?php foreach ($data_jabatan as $v2) : ?>
                                <option value='<?= $v2->id ?>'><?= $v2->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type='text' class='form-control jarak_approve_mengetahui' name='jarak_approve_mengetahui[]' placeholder='Masukkan Jarak Approve' required value=0></td>
                        <td> <a class='btn btn-danger disabled-form' style="color:white;" onclick="hapusElemen('#srow_mengetahui<?= $i ?>'); return false;"><i class='fa fa-trash'></i> </a></td>
                    </tr>
                    <?php endif; ?>

                </tbody>
            </table>
            <button type="button" id='btn-add-range-mengetahui' class="btn btn-danger pull-right"><i class="fa fa-plus"></i> Add Range</button>
            <div class="clearfix"></div>
            <div class="x_title">
                <div class="col-md-6">
                    <h2> Wewenang </h2>
                </div>
                <div class="clearfix"></div>
            </div>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Range</th>
                        <th class="col-md-2">Jabatan User</th>
                        <th>Nilai Range Awal (Rp.)</th>
                        <th>Nilai Range Akhir (Rp.)</th>
                        <th>Jarak Aprrove (Hari)</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody id="tbody_range">

                    <?php
                    if ($data_range) :
                        foreach ($data_range as $i => $v) : ?>
                    <tr id='srow<?= ++$i ?>' class='rows'>
                        <td class='no'><?= $i ?></td>
                        <td id='jabatan_range'>
                            <input type="text" class="input_jabatan" name="jabatan_user[]" val="" hidden>
                            <select class="form-control multipleSelect multiple" multiple="multiple">

                                <?php foreach ($data_jabatan as $v2) : ?>

                                <option value='<?= $v2->id ?>' <?= array_search($v2->id, explode(",", $v->jabatan_id)) !== false ? 'selected' : '' ?>><?= $v2->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type='text' class='form-control range_awal' name='range_awal[]' placeholder='Masukkan Range Awal' required value=0 readonly></td>
                        <td><input type='text' class='form-control range_akhir' name='range_akhir[]' placeholder='Masukkan Range Akhir' required value='<?= $v->nilai_akhir ?>'></td>
                        <td><input type='text' class='form-control jarak_approve' name='jarak_approve[]' placeholder='Masukkan Jarak Approve' required value=<?= $v->jarak_approve?>></td>
                        <td> <a class='btn btn-danger disabled-form' style="color:white;" onclick="hapusElemen('#srow<?= $i ?>'); return false;"><i class='fa fa-trash'></i> </a></td>
                    </tr>
                    <?php endforeach;
                    else : ?>
                    <tr id='srow<?= ++$i ?>' class='rows'>
                        <td class='no'><?= $i ?></td>
                        <td id='jabatan_range'>
                            <select name='jabatan_user[]' class='form-control select2'>
                                <?php foreach ($data_jabatan as $v2) : ?>
                                <option value='<?= $v2->id ?>'><?= $v2->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type='text' class='form-control range_awal' name='range_awal[]' placeholder='Masukkan Range Awal' required value=0 readonly></td>
                        <td><input type='text' class='form-control range_akhir' name='range_akhir[]' placeholder='Masukkan Range Akhir' required value=''></td>
                        <td><input type='text' class='form-control jarak_approve' name='jarak_approve[]' placeholder='Masukkan Jarak Approve' required value=0></td>

                        <td> <a class='btn btn-danger disabled-form' style="color:white;" onclick="hapusElemen('#srow<?= $i ?>'); return false;"><i class='fa fa-trash'></i> </a></td>
                    </tr>
                    <?php endif; ?>

                </tbody>
            </table>
            <button type="button" id='btn-add-range' class="btn btn-danger pull-right"><i class="fa fa-plus"></i> Add Range</button>
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
        function formatNumber(data) {
            data = data + '';
            data = data.replace(/,/g, "");

            data = parseInt(data) ? parseInt(data) : 0;
            data = data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
            return data;

        }

        function unformatNumber(data) {
            data = data + '';
            return parseInt(data.replace(/,/g, ""));
        }

        function notif(title, text, type) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                styling: 'bootstrap3'
            });
        }

        function hapusElemen(idf) {
            $(idf).remove();
            var idf = document.getElementById("idf").value;
            idf = idf - 1;
            document.getElementById("idf").value = idf;
            $(".range_akhir").trigger("keyup");

        }
        function jabatan() {

            $(".multiple").each(function(i) {
                $(".input_jabatan").eq(i).val(($(".multiple").eq(i).val() + "").replace(/,/g, ","));
                console.log(i);
                console.log(i);

            });

        }

        function set_value() {
            $(".rows").each(function(i) {
                $(".range_akhir").eq(i).attr("readonly", false);
                if (unformatNumber($(".range_akhir").eq(i).val()) <= unformatNumber($(".range_awal").eq(i).val()))
                    $(".range_akhir").eq(i).val(formatNumber(unformatNumber($(".range_awal").eq(i).val()) + 1));
                if (i == 0) {
                    $(".range_awal").eq(i).val(formatNumber(0));
                } else if (i == ($(".rows").length - 1)) {
                    $(".range_akhir").eq(i).val("Tak hingga");
                    $(".range_akhir").eq(i).attr("readonly", true);
                    $(".range_awal").eq(i).val(formatNumber(parseInt(unformatNumber($(".range_akhir").eq(i - 1).val()) + 1)));
                } else {
                    $(".range_awal").eq(i).val(formatNumber(unformatNumber($(".range_akhir").eq(i - 1).val()) + 1));
                }
                if (i != ($(".rows").length - 1) && $(".range_akhir").eq(i).val() == $(".range_akhir").eq(($(".rows").length - 1)).val()) {
                    $(".range_akhir").eq(i).val(formatNumber(0));
                }

            });
            jabatan();

        }
        $(function() {
            jabatan();

            $("#submit").click(function() {
                $.ajax({
                    type: "GET",
                    data: $("#form").serialize(),
                    url: "<?= site_url() ?>/Setting/Akun/P_permission_Dokumen/ajax_save?id_dokumen=<?= $this->input->get("id") ?>",
                    dataType: "json",
                    success: function(data) {
                        if (data.status)
                            notif('Sukses', data.message, 'success');
                        else
                            notif('Gagal', data.message, 'danger');
                    }
                });
            });
            var jabatan_range = $("#jabatan_range").html().replace(/selected=\"\"/g, "");
            var jabatan_range_mengetahui = $("#jabatan_range_mengetahui").html().replace(/selected=\"\"/g, "");
            $("body").on("keyup", ".range_akhir", function(event) {
                $(this).val(formatNumber($(this).val()));
                set_value();

            })
            $("body").on("keydown", ".range_akhir", function(event) {
                if (event.keyCode == 38)
                    $(this).val(formatNumber(parseInt(unformatNumber($(this).val())) + 1));
                if (event.keyCode == 39)
                    $(this).val(formatNumber(parseInt(unformatNumber($(this).val())) + 100));
                if (event.keyCode == 40)
                    $(this).val(formatNumber(parseInt(unformatNumber($(this).val())) - 1));
                if (event.keyCode == 37)
                    $(this).val(formatNumber(parseInt(unformatNumber($(this).val())) - 100));

                set_value();

            });
            $("body").on("change", ".multiple", function() {
                jabatan();
            });
            $("#btn-add-range").click(function() {
                if ($(".no").html()) {
                    idf = parseInt($(".no").last().html()) + 1;
                } else {
                    idf = 1;
                }
                var str = "<tr id='srow" + idf + "' class='rows'>";
                str += "<td class='no'>" + idf + "</td>";

                str += "<td>" + jabatan_range + "</td>";

                str += "<td><input class='form-control range_awal' name='range_awal_mengetahui[]' readonly></td>";
                str += "<td><input class='form-control range_akhir' name='range_akhir_mengetahui[]'></td>";
                str += "<td><input type='text' class='form-control jarak_approve_mengetahui' name='jarak_approve[]' placeholder='Masukkan Jarak Approve' required value=1></td>";

                str += "<td> <a class='btn btn-danger' style=\"color:white;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>";
                str += "</tr>";
                $("#tbody_range").append(str);
                $('.multiple').select2({
                    width: '100%',
                    tags: true,
                    tokenSeparators: [',', ' ']
                });
                $('select.select2').select2('destroy');
                $(".range_akhir").trigger("keyup");
                $('.multiple').select2({
                    width: '100%',
                    tags: true,
                    tokenSeparators: [',', ' ']
                });
            });

            $("#btn-add-range-mengetahui").click(function() {
                if ($(".no").html()) {
                    idf = parseInt($(".no").last().html()) + 1;
                } else {
                    idf = 1;
                }
                var str = "<tr id='srow" + idf + "' class='rows'>";
                str += "<td class='no'>" + idf + "</td>";

                str += "<td>" + jabatan_range_mengetahui + "</td>";

                str += "<td><input type='text' class='form-control jarak_approve_mengetahui' name='jarak_approve_mengetahui[]' placeholder='Masukkan Jarak Approve' required value=1></td>";
                str += "<td> <a class='btn btn-danger' style=\"color:white;\" onclick='hapusElemen(\"#srow" + idf + "\"); return false;'><i class='fa fa-trash'></i> </a></td>";
                str += "</tr>";
                $("#tbody_range_mengetahui").append(str);
                $('.multiple').select2({
                    width: '100%',
                    tags: true,
                    tokenSeparators: [',', ' ']
                });
                $('select.select2').select2('destroy');
                $(".range_akhir").trigger("keyup");
                $('.multiple').select2({
                    width: '100%',
                    tags: true,
                    tokenSeparators: [',', ' ']
                });
            });

            $('.multiple').select2({
                width: '100%',
                tags: true,
                tokenSeparators: [',', ' ']
            });
            $(".range_akhir").trigger("keyup");

        });
    </script>