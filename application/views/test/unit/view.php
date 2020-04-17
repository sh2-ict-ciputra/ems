<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<div style="float:right">
    <h2>
        <button class="btn btn-primary" onClick="window.location.href='<?=site_url()?>/P_master_unit/add'">
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
                <table class="table table-striped jambo_table bulk_action" id="tableDTServerSite">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <th>Nama Kawasan</th>
                            <th>Nama Blok</th>
                            <th>No. Unit</th>
                            <th>Nama Pemilik</th>
                            <th>Sumber Data Dari</th>
                            <th>Action</th>
                            <!-- <th>Delete</th> -->
                        </tr>
                    </thead>
					<tfoot id="tfoot" style="display: table-header-group">
                        <tr>
                            <!-- <th>No</th> -->
                            <th>Nama Kawasan</th>
                            <th>Nama Blok</th>
                            <th>No. Unit</th>
                            <th>Nama Pemilik</th>
                            <th>Sumber Data Dari</th>
                            <th>Action</th>
                            <!-- <th>Delete</th> -->
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

<script>
    $(document).ready(function() {
        $('#tableDTServerSite tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Filter '+title+'" />' );
        } );


        // var table = $('#tableDTServerSite').DataTable();
            
        // Apply the search
        var table = 
        $('#tableDTServerSite').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": "<?=site_url("erems_test/P_master_unit/ajax_get_view")?>"
        });

        table.columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );
        } );
    });
    
</script>