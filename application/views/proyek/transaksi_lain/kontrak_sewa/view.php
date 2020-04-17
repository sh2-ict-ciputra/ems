<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/transaksi_lain/P_kontrak_sewa/add'">
            <i class="fa fa-plus"></i>
            Tambah
        </button>
        <button class="btn btn-warning" onClick="window.history.back()" disabled>
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
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table class="table table-striped jambo_table bulk_action tableDT">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Unit</th>
                            <th>No. Unit</th>
                            <th>Penyewa</th>
                            <th>Sumber Data Dari</th>
                            <th>Luas Tanah</th>
                            <th>Luas Bangunan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
					<tfoot id="tfoot" style="display: table-header-group">
                        <tr>
                            <th>No</th>
                            <th>Nama Unit</th>
                            <th>No. Unit</th>
                            <th>Penyewa</th>
                            <th>Sumber Data Dari</th>
                            <th>Luas Tanah</th>
                            <th>Luas Bangunan</th>
                            <th>Status</th>
                            <th hidden>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                                       
                            
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>