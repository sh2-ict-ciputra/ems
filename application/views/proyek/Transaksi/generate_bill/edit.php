<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<link href="<?=base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
<!DOCTYPE html>
<link href="<?=base_url(); ?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<script src="<?=base_url(); ?>vendors/switchery/dist/switchery.min.js"></script>

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
		<button class="btn btn-warning" onClick="window.location.href='<?=site_url()?>/P_transaksi_generate_bill'">
			<i class="fa fa-arrow-left"></i>
			Back
		</button>
		<button class="btn btn-success" onClick="window.location.href='<?=site_url()?>/P_transaksi_generate_bill/add'">
			<i class="fa fa-repeat"></i>
			Refresh
		</button>
	</h2>
</div>
<div class="clearfix"></div>
</div>

<div class="x_content">
	<br>
	<form id="form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post" action="<?=site_url(); ?>/P_transaksi_meter_air/edit?id=<?=$this->input->get('id')?>">
		<b>
			<h2>Informasi Unit</h2>
		</b>
		<div class="col-md-4 col-xs-12">

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Kawasan</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="kawasan" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Blok</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">No Unit</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Virtual Account</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			

			

		</div>
		<div class="col-md-4 col-xs-12">

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Golongan</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Luast Tanah (m2)</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Luast Bangunan (m2)</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Luast Taman (m2)</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>
			
		</div>

		<div class="col-md-4 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Sisa DP Umum</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Sisa DP Restribusi</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<br>
		<div id="pemilik" class="col-md-4 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Pemilik</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Metode Penagihan</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Mobile Phone</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Home Phone</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Email</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>
		</div>
		<div id="penghuni" class="col-md-4 col-xs-12">
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Penghuni</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>	

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Metode Penagihan</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			
			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Mobile Phone</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Home Phone</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-5 col-sm-4 col-xs-12">Email</label>
				<div class="col-md-7 col-sm-8 col-xs-12">
					<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
				</div>
			</div>
					
		</div>


		<div class="x_panel">
			<div class="x_title">
				<b>
					<h2>Tagihan</h2>
				</b>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<a class="collapse-link">
							<i class="fa fa-chevron-down"></i>
						</a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>

			<div class="x_content">

				<!-- -------- Baru ---------- -->

				<div class="col-md-9">

					<div class="col-md-6">

						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">No Tagihan</label>
							<div class="col-md-7 col-sm-8 col-xs-12">
								<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">Deskripsi</label>
							<div class="col-md-7 col-sm-8 col-xs-12">
								<textArea id="description" class="form-control" type="text" name="description" disabled></textArea>
							</div>
						</div>

					</div>

					<div class="col-md-6">

						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">Periode</label>
							<div class="col-md-7 col-sm-8 col-xs-12">
								<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">Status</label>
							<div class="col-md-7 col-sm-8 col-xs-12">
								<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
							</div>
						</div>

					</div>

					<div class="clearfix"></div>

					<br>
					<div id="service_air">
						<h4 align="left"><b>Service Air</b></h4>

						<div class="col-md-6">

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Sewa Meter</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Sub Golongan</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Range</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Meter Awal</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Meter Akhir</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Pemakaian</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Sewa Meter</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">PPN Sewa Meter</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Biaya</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">PPN Air</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<label class="control-label col-md-11 col-sm-11 col-xs-12">
								<hr style="border-top: 2px solid #ccc;"></label>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Sub Total</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Diskon Umum</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Diskon Khusus</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Deposit</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

							<label class="control-label col-md-11 col-sm-11 col-xs-12">
								<hr style="border-top: 2px solid #ccc;"></label>

							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12">Tagihan Air</label>
								<div class="col-md-7 col-sm-8 col-xs-12">
									<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
								</div>
							</div>

						</div>
					</div>
					<div id="service_lingkungan">
						<div>
							<div class="clearfix"></div>

							<br>
							<h4 align="left"><b>Service Lingkungan</b></h4>

							<div class="col-md-6"></div>
							<div class="col-md-6"></div>

							<div class="clearfix"></div>
						</div>
					</div>
					<div id="service_listrik">
						<div>
							<div class="clearfix"></div>

							<br>
							<h4 align="left"><b>Service Listrik</b></h4>

							<div class="col-md-6">

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Sewa Meter</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Sub Golongan</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Range</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Meter Awal</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Meter Akhir</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Pemakaian</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

							</div>

							<div class="col-md-6">

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Sewa Meter</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">PPN Sewa Meter</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Biaya Listrik</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">PPN Listrik</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<label class="control-label col-md-11 col-sm-11 col-xs-12">
									<hr style="border-top: 2px solid #ccc;"></label>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Sub Total</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Diskon Umum</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Diskon Khusus</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Deposit</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

								<label class="control-label col-md-11 col-sm-11 col-xs-12">
									<hr style="border-top: 2px solid #ccc;"></label>

								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12">Tagihan Listrik</label>
									<div class="col-md-7 col-sm-8 col-xs-12">
										<input type="text" class="form-control" required name="nomor_unit" value="" disabled>
									</div>
								</div>

							</div>



						</div>
					</div>

					<div class="clear-fix"></div>

					<div class="col-md-12">
						<input id="btn-update" class="btn btn-success col-md-1 col-md-offset-5" value="Edit">
						<input id="btn-cancel" class="btn btn-danger col-md-1" value="Cancel" style="display:none">
					</div>



					<div id="isi_tabel" class="col-md-12">
					</div>


				</div>
				<div class="col-md-3">
					<div class="form-group">
						<div style="background: #D8D8D8;" class="col-md-16 col-sm-16 col-xs-12">
							<h4>Summary</h4>
							<div class="col-md-6">Tagihan Air</div>
							<div class="col-md-3">267,000</div>
							<div class="col-md-6">Tagihan Air</div>
							<div class="col-md-3">267,000</div>
							<div class="col-md-6">Tagihan PL</div>
							<div class="col-md-3">0</div>
							<div class="col-md-6">D. Tung Air</div>
							<div class="col-md-3">0</div>
							<div class="col-md-6">D. Tung PL</div>
							<div class="col-md-3">0</div>
							<div class="col-md-12">--------------------------------------</div>
							<div class="col-md-6"><b>Total Retrbusi</b></div>
							<div class="col-md-3"><b>267,000</b></div>
							<div class="clearfix"></div>
							<br>
							<div class="col-md-6">B. Penagihan</div>
							<div class="col-md-3">0</div>
							<div class="col-md-6">Tagihan Listrik</div>
							<div class="col-md-3">0</div>
							<div class="col-md-6">Tagihan Lain</div>
							<div class="col-md-3">0</div>
							<div class="col-md-6">Tagihan Sewa</div>
							<div class="col-md-3">0</div>
							<div class="col-md-6">Internet &amp; TV</div>
							<div class="col-md-3">0</div>
							<div class="col-md-6">Lain-Lain</div>
							<div class="col-md-3">0</div>
							<div class="col-md-12">------------------------------------------------</div>
							<div class="col-md-6"><b>Total</b></div>
							<div class="col-md-3"><b>0</b></div>
							<div class="clearfix"></div>
							<br>
							<div class="col-md-6"><b>
									<font color="blue">Grand Total</font>
								</b></div>
							<div class="col-md-3"><b>
									<font color="blue">267,000</font>
								</b></div>
							<div class="clearfix"></div>
							<br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

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

				</tbody>
			</table>
		</div>
	</div>
	<!-- jQuery -->

	<script type="text/javascript">
		$(".select2").select2();

		$(function () {
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
				url = '<?=site_url()?>/core/get_log_detail';
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
