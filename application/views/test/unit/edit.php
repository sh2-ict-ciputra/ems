<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?= base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>

<link href="<?= base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

<script type="text/javascript" src="<?= base_url(); ?>vendors/moment/min/moment.min.js"></script>

<link href="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="<?= base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<!DOCTYPE html>

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
		<button class="btn btn-warning" onClick="window.location.href = '<?=substr(current_url(),0,strrpos(current_url(),"/"))?>'">
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



		<div class="x_content">
		<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?= site_url(); ?>/P_master_unit/edit?id=<?= $this->input->get('id'); ?>">

			<br />
			<div class="title" id="print_proses"></div>
			<div class="col-md-6 col-xs-12">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Kawasan</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select id="kawasan_flag" name="kawasan_flag" class="form-control select2 disabled-form" required disabled>
							<option value="">--Pilih Kawasan--</option>
							<?php
							foreach ($dataKawasan as $key => $v) {
								echo "<option value='$v[id]' ";
								echo ($v['id'] == $dataSelect->kawasan) ? 'selected' : '';
								echo ">$v[name]</option>";
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Blok</label>
					<div id="lihat_blok">
						<div class="col-md-9 col-sm-9 col-xs-12">
							<select id="blok" class="form-control select2 disabled-form" name="blok" disabled>
								<option value="">--Pilih Blok--</option>
								<?php
								foreach ($dataBlok as $key => $v) {
									echo "<option value='$v[id]' ";
									echo ($v['id'] == $dataSelect->blok) ? 'selected' : '';
									echo ">$v[name]</option>";
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">No Unit</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control disabled-form" required name="nomor_unit" value="<?= $dataSelect->no_unit ?>" placeholder="-- Masukkan Nomor Unit --" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Pemilik</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="pemilik" required="" class="form-control select2 disabled-form" disabled>
							<option value="">--Pilih Pemilik--</option>
							<?php
							foreach ($dataCustomer as $key => $v) {
								echo "<option value='$v[id]' ";
								echo ($v['id'] == $dataSelect->pemilik) ? 'selected' : '';
								echo ">$v[name]</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Penghuni</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="penghuni" class="form-control select2 disabled-form" disabled>
							<option value="">--Pilih Penghuni--</option>
							<?php
							foreach ($dataCustomer as $key => $v) {
								echo "<option value='$v[id]' ";
								echo ($v['id'] == $dataSelect->penghuni) ? 'selected' : '';
								echo ">$v[name]</option>";
							}
							?>
						</select>
					</div>
				</div>


				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Unit</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="jenis_unit" class="form-control select2 disabled-form" disabled>
							<option value="">--Pilih Jenis Unit--</option>
							<option value="1" <?= $dataSelect->jenis_unit == 1 ? 'selected' : '' ?>>Rental</option>
							<option value="2" <?= $dataSelect->jenis_unit == 2 ? 'selected' : '' ?>>Umum</option>
							<option value="3" <?= $dataSelect->jenis_unit == 3 ? 'selected' : '' ?>>Non Proyek</option>

						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Purpose Use</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="produk_kategori" class="form-control select2 disabled-form" disabled>
							<option value="">--Pilih Purpose Use--</option>
							<?php
							foreach ($dataProductCategory as $key => $v) {
								echo "<option value='$v[id]' ";
								echo ($v['id'] == $dataSelect->produk_kategori) ? 'selected' : '';
								echo ">$v[name]</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Golongan</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="golongan" id="golongan" required="" class="form-control select2 disabled-form" disabled>
							<option value="">--Pilih Golongan--</option>
							<?php
							foreach ($dataGolongan as $key => $v) {
								echo "<option value='$v[id]' ";
								echo ($v['id'] == $dataSelect->golongan) ? 'selected' : '';
								echo ">$v[description]</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Status Jual</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="status_jual" id="status_jual" class="form-control select2 disabled-form" disabled>
							<option value="">--Pilih Status Jual--</option>
							<option value="0" <?= $dataSelect->status_jual == 0 ? 'selected' : '' ?>>Non Saleable</option>
							<option value="1" <?= $dataSelect->status_jual == 1 ? 'selected' : '' ?>>Saleable</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Status Tagihan</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<div class="">
							<label>
								<input id="status_tagihan" type="checkbox" class="js-switch disabled-form" name="status_tagihan" value='1' disabled <?= $dataSelect->status_tagihan == 1 ? 'checked' : ''; ?> /> Aktif
							</label>
						</div>
					</div>
				</div>
				
			</div>
			<div class="col-md-6 col-xs-12">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Tanah (m2)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control disabled-form currency" required name="luas_tanah" value="<?= $dataSelect->luas_tanah ?>" placeholder="Masukkan Luas Tanah" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Bangunan (m2)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control disabled-form currency" required name="luas_bangunan" value="<?= $dataSelect->luas_bangunan ?>" placeholder="Masukkan Luas Bangunan" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Taman (m2)</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" class="form-control disabled-form currency" required name="luas_taman" value="<?= $dataSelect->luas_taman ?>" placeholder="Masukkan Luas Taman" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Virtual Account</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text"
						 class="form-control disabled-form" required name="virtual_account" value="<?= $dataSelect->virtual_account ?>" required="" placeholder="Masukkan Nomor Virtual Account" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">PT</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="pt" id="pt" required="" class="form-control select2 disabled-form" disabled>
							<option value="">--Pilih PT--</option>
							<?php
							foreach ($dataPT as $key => $v) {
								echo "<option value='$v[id]' ";
								echo ($v['id'] == $dataSelect->pt) ? 'selected' : '';
								echo ">$v[name]</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Metode Penagihan</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<select name="metode_tagihan[]" class="form-control multipleSelect js-example-basic-multiple disabled-form" multiple="multiple" placeholder="-- Masukkan Metode Penagihan --" disabled>
							<option value=""></option>
							<?php
							foreach ($dataMetodePenagihan as $v) {
								echo "<option value='$v[id]' ";
								foreach ($dataSelectMetodePenagihan as $x) {
									if ($v['id'] == $x['metode_penagihan']) {
										echo 'selected';
									}
								}
								echo ">$v[name]</option>";
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kirim Tagihan</label>
                    <div class="col-lg-4 col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
								<input id="kirim_tagihan" type="checkbox" class="js-switch" name="kirim_tagihan[]" value='1'
								<?=$dataSelect->kirim_tagihan==1||$dataSelect->kirim_tagihan==3?"checked":""?>
								> Pemilik
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
								<input id="kirim_tagihan" type="checkbox" class="js-switch" name="kirim_tagihan[]" value='2'
								<?=$dataSelect->kirim_tagihan==2||$dataSelect->kirim_tagihan==3?"checked":""?>
								> Penghuni
                            </label>
                        </div>
                    </div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal ST</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<div class='input-group date '>
							<input type="text" class="form-control datetimepicker disabled-form" name="tgl_st" id="tgl_st" placeholder="<?= $dataSelect->tanggal_st ?>" value="<?= $dataSelect->tanggal_st ?>" disabled>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon</label>
					<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
						<div class="">
							<label>
								<input id="flag_diskon" type="checkbox" class="js-switch disabled-form" disabled name="flag_diskon" value='1' <?= $dataSelect->diskon_flag == 1 ? 'checked' : ''; ?> /> Aktif
							</label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Status Unit</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<div class="">
							<label>
								<input id="active" type="checkbox" class="js-switch disabled-form" name="active" value='1' <?= $dataSelect->active
																																== 1 ? 'checked' : ''; ?> disabled /> Aktif
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="clearfix"></div>
			<div class="" id="range" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
					<li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Informasi Air</a>
					</li>
					<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Informasi PL</a>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
						<p>
							<div class="col-md-6">

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Meter Air Aktif</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="">
											<label>
												<input id="meter_air_aktif" type="checkbox" class="js-switch disabled-form" name="meter_air_aktif" value='1' <?= $dataSelect->air_meter_aktif== 1 ? 'checked' : ''; ?> disabled /> Aktif
											</label>
										</div>
									</div>
								</div>
								<div id="view_meter_air">
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Aktif</label>
										<div class="col-md-4 col-sm-9 col-xs-12">
											<div class='input-group date '>
												<input type="text" class="form-control datetimepicker  disabled-form" name="tanggal_aktif_air" id="tanggal_aktif_air" placeholder="<?= $dataSelect->air_tanggal_aktif ?>" value="<?= $dataSelect->air_tanggal_aktif ?>" <?= ($dataSelect->air_meter_aktif) ? '' : 'disabled' ?>>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
										<div class="col-md-1 col-sm-9 col-xs-12">
											<div class="input-group date mydatepicker disabled-form" style="width: -webkit-fill-available;margin-top: -7px;font-size: xx-large;height: 40px;text-align: center">
												-
											</div>
										</div>
										<div class="col-md-4 col-sm-9 col-xs-12">
											<div class='input-group date mydatepicker'>
												<input type="text" class="form-control datetimepicker" id="tanggal_putus_air" name="tanggal_putus_air" placeholder="<?= $dataSelect->air_tanggal_putus ?>" value="<?= $dataSelect->air_tanggal_putus ?>" <?= isset($dataSelect->air_tanggal_putus) ? '' : '
												disabled' ?> <?= ($dataSelect->air_meter_aktif) ? ' disabled' : '' ?>>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Golongan</label>
										<div class="col-md-9 col-sm-9 col-xs-12" id="isi_air">

											<select name="sub_golongan_air" class="form-control select2 disabled-form" id="sub_golongan_air" disabled>
												<option value="">--Pilih Sub Golongan-</option>
												<?php
												foreach ($dataSubGol as $v) {
													if ($v['range_flag'] == 2) {
														echo "<option value='$v[id]' selected";
														echo ">$v[name] : $v[code]</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Pemeliharaan Meter Air</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<select name="pemeliharaan_meter_air" class="form-control select2 disabled-form" disabled>
												<option value="">--Pilih Pemeliharaan Meter Air--</option>
												<?php
												foreach ($dataPemeliharaanMeterAir as $key => $v) {
													echo "<option value='$v[id]' selected";
													echo ($v['id'] == $dataSelect->air_pemeliharaan_meter_air) ? 'selected' : '';
													echo ">$v[code]</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Nilai Penyambungan</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<input type="number" class="form-control disabled-form" name="nilai_penyambungan" value="<?= $dataSelect->air_nilai_penyambungan ?>" placeholder="Masukkan Nilai Penyambungan" disabled>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div id="view_meter_air2">
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Angka Meter Sekarang</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<input type="number" class="form-control disabled-form" name="angka_meter_air" value="<?= $dataSelect->air_angka_meter_sekarang ?>" placeholder="Masukkan Angka Meter Sekarang" disabled>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">ID Barcode Meter</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<input type="text" class="form-control disabled-form" name="barcode_id" value="<?= $dataSelect->air_id_barcode_meter ?>" placeholder="" disabled>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">No Meter Air</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<input type="text" class="form-control disabled-form" name="nomor_meter_air" value="<?= $dataSelect->air_no_meter_air ?>" placeholder="Masukkan Nomor Meter Air" disabled>
										</div>
									</div>
								</div>
							</div>
						</p>
					</div>
					<!-- Area Untuk PL -->
					<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
						<p>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">PL Aktif</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="">
											<label>
												<input id="meter_pl_aktif" type="checkbox" class="js-switch disabled-form" name="meter_pl_aktif" value='1' <?= $dataSelect->pl_aktif == 1 ? 'checked' : ''; ?> disabled /> Aktif
											</label>
										</div>
									</div>
								</div>

								<div id="view_meter_pl">
									<input type="hidden" name="check_pl" value="" id="check_pl">
									<div id="view_pl">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Aktif</label>
											<div class="col-md-4 col-sm-9 col-xs-12">
												<div class='input-group date '>
													<input type="text" class="form-control datetimepicker disabled-form" name="pl_tanggal_aktif" id="tanggal_aktif_pl" placeholder="<?= $dataSelect->pl_tangal_aktif ?>" value="<?= $dataSelect->pl_tangal_aktif ?>" <?= ($dataSelect->pl_aktif) ? '' : 'disabled' ?>>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
											<div class="col-md-1 col-sm-9 col-xs-12">
												<div class="input-group date mydatepicker" style="width: -webkit-fill-available;margin-top: -7px;font-size: xx-large;height: 40px;text-align: center">
													-
												</div>
											</div>
											<div class="col-md-4 col-sm-9 col-xs-12">
												<div class='input-group date mydatepicker'>
													<input type="text" class="form-control datetimepicker " name="tanggal_non_aktif_pl" id='tanggal_putus_pl' placeholder="<?= $dataSelect->pl_tangal_putus ?>" value="<?= $dataSelect->pl_tangal_putus ?>" <?= isset($dataSelect->pl_tangal_putus) ? '' : 'disabled' ?> <?= ($dataSelect->pl_aktif) ? 'disabled' : '' ?>>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Golongan</label>
											<div class="col-md-9 col-sm-9 col-xs-12" id="isi_pl">
												<select name="sub_golongan_lingkungan" class="form-control select2 disabled-form" id="sub_golongan_lingkungan" style="width:100%" disabled>
													<option value="">--Pilih Sub Golongan-</option>
													<?php
													foreach ($dataSubGol as $v) {
														if ($v['range_flag'] == 1) {
															echo "<option value='$v[id]' ";
															echo ($v['id'] == $dataSelect->pl_sub_golongan) ? 'selected' : '';
															echo ">$v[name] : $v[code]</option>";
														}
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Mandiri</label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<div class='input-group date mydatepicker'>
												<input type="text" class="form-control datetimepicker disabled-form" id='tanggal_mandiri_pl' name="tanggal_mandiri_pl" placeholder="<?= $dataSelect->pl_tangal_mandiri ?>" value="<?= $dataSelect->pl_tangal_mandiri ?>" disabled>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</p>
					</div>

				</div>
			</div>
			<div class="col-md-12">
				<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
				<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
			</div>

	</form>
</div>
</div>
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
	$.each($(".currency"), function(index, currency) {
		currency.value = parseInt(currency.value.toString().replace(/[\D\s\._\-]+/g, ""), 10).toLocaleString("en-US");
	});

	$("#btn-update").click(function() {
		disableForm = 0;
		$(".disabled-form").removeAttr("disabled");
		$("#btn-cancel").removeAttr("style");
		$("#btn-update").val("Update");
		$("#btn-update").attr("type", "submit");
	});
	$("#btn-cancel").click(function() {
		disableForm = 1;
		$(".disabled-form").attr("disabled", "")
		$("#btn-cancel").attr("style", "display:none");
		$("#btn-update").val("Edit")
		$("#btn-update").removeAttr("type");
	});


	$(".btn-modal").click(function() {
		url = '<?= site_url(); ?>/core/get_log_detail';
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
			success: function(data) {
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
					$.each(data, function(key, val) {
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

	$(document).keydown(function(e) {
		return (e.which || e.keyCode) != 116;
	});

	$(document).keydown(function(e) {
		if (e.ctrlKey) {
			return (e.which || e.keyCode) != 82;
		}
	});
</script>


<script type="text/javascript">
	function currency(input) {
		var input = input.toString().replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt(input, 10) : 0;
		return (input === 0) ? "" : input.toLocaleString("en-US");
	}


	$(function() {
		$("#sub_golongan_lingkungan").select2();
		$('.datetimepicker').datetimepicker({
			viewMode: 'days',
			format: 'MM-DD-YYYY'
		});
		$("#meter_air_aktif").change(function() {
			if ($("#meter_air_aktif").is(':checked')) {
				$("#tanggal_aktif_air").attr('disabled', false);
				$("#tanggal_putus_air").attr('disabled', true);
				$("#tanggal_putus_air").val('');
			} else {
				$("#tanggal_aktif_air").attr('disabled', true);
				$("#tanggal_putus_air").attr('disabled', false);
				$("#tanggal_aktif_air").val('');
			}
		})
		$("#meter_pl_aktif").change(function() {
			if ($("#meter_pl_aktif").is(':checked')) {
				$("#tanggal_aktif_pl").attr('disabled', false);
				$("#tanggal_putus_pl").attr('disabled', true);
				$("#tanggal_putus_pl").val('');
			} else {
				$("#tanggal_aktif_pl").attr('disabled', true);
				$("#tanggal_putus_pl").attr('disabled', false);
				$("#tanggal_aktif_pl").val('');
			}
		})
		$("#meter_listrik_aktif").change(function() {
			if ($("#meter_listrik_aktif").is(':checked')) {
				$("#tanggal_aktif_listrik").attr('disabled', false);
				$("#tanggal_putus_listrik").attr('disabled', true);
				$("#tanggal_putus_listrik").val('');
			} else {
				$("#tanggal_aktif_listrik").attr('disabled', true);
				$("#tanggal_putus_listrik").attr('disabled', false);
				$("#tanggal_aktif_listrik").val('');
			}
		})


		$('.js-example-basic-multiple').select2({
			placeholder: '-- Masukkan Metode Penagihan --',
			tags: true,
			tokenSeparators: [',', ' ']
		});

		$("#kawasan_flag").change(function() {


			//	alert('tess');

			url = '<?= site_url(); ?>/P_master_unit/lihat_blok';
			var kawasan_flag = $("#kawasan_flag").val();
			//console.log(this.value);
			$.ajax({
				type: "post",
				url: url,
				data: {
					kawasan_flag: kawasan_flag
				},
				dataType: "json",
				success: function(data) {
					console.log(data);

					$("#blok")[0].innerHTML = "";

					$("#blok").append("<option value='' >Pilih Blok</option>");
					$.each(data, function(key, val) {
						$("#blok").append("<option value='" + val.id + "'>" + val.name.toUpperCase() + "</option>");
					});

				}


			});
		});

		$("#golongan").change(function() {

			// url = '<?= site_url(); ?>/P_master_unit/lihat_blok';
			url = '<?= site_url(); ?>/P_master_unit/get_sub_golongan';

			var jenis_golongan_id = $("#golongan").val();
			//console.log(this.value);
			$.ajax({
				type: "post",
				url: url,
				data: {
					jenis_golongan_id: jenis_golongan_id
				},
				dataType: "json",
				success: function(data) {
					console.log(data);

					$("#sub_golongan_air")[0].innerHTML = "";
					$("#sub_golongan_air").append("<option value='' >Pilih Sub Gol Air</option>");
					$("#sub_golongan_lingkungan")[0].innerHTML = "";
					$("#sub_golongan_lingkungan").append("<option value='' >Pilih Sub Gol Lingkungan</option>");
					$.each(data, function(key, val) {
						if (val.range_flag == 2)
							$("#sub_golongan_air").append("<option value='" + val.id + "'>" + val.name.toUpperCase() + "</option>");
						else if (val.range_flag == 1)
							$("#sub_golongan_lingkungan").append("<option value='" + val.id + "'>" + val.name.toUpperCase() +
								"</option>");
					});

				}


			});
		});



	});
</script>