    </div>

    <!-- Bootstrap -->
    <script src="<?=base_url()?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?=base_url()?>vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?=base_url()?>vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="<?=base_url()?>vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="<?=base_url()?>vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?=base_url()?>vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="<?=base_url()?>vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="<?=base_url()?>vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="<?=base_url()?>vendors/Flot/jquery.flot.js"></script>
    <script src="<?=base_url()?>vendors/Flot/jquery.flot.pie.js"></script>
    <script src="<?=base_url()?>vendors/Flot/jquery.flot.time.js"></script>
    <script src="<?=base_url()?>vendors/Flot/jquery.flot.stack.js"></script>
    <script src="<?=base_url()?>vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="<?=base_url()?>vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="<?=base_url()?>vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="<?=base_url()?>vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="<?=base_url()?>vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="<?=base_url()?>vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="<?=base_url()?>vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="<?=base_url()?>vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?=base_url()?>vendors/moment/min/moment.min.js"></script>
    <script src="<?=base_url()?>vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="<?=base_url()?>js/custom.js"></script>
    <!-- dataTables -->
    <script type="text/javascript" src="<?=base_url()?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>

    <!-- PNotify -->
    <script src="<?=base_url()?>vendors/pnotify/dist/pnotify.js"></script>
    <script src="<?=base_url()?>vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="<?=base_url()?>vendors/pnotify/dist/pnotify.nonblock.js"></script>
    <!-- <?php   
        echo("<pre>");
            print_r(permission());
        echo("</pre>");
        
    ?> -->
    <script>
        $('.modal').modal({ 
            keyboard: false,
            show:false
        });
        // Jquery draggable
        $('.modal-dialog').draggable({
            // handle: ".modal-header"
        });
        $('.modal-dialog').resizable({
            handle: ".modal-header"
        });
        // $('#modal-iframe-large').on('show.bs.modal', function () {
        //     $(this).find('.modal-body').css({
        //         'max-height':'100%'
        //     });
        // });
        $(function() {

            
            // Setup - add a text input to each footer cell
            
            $('.tableDT tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Filter '+title+'" />' );
            } );
        
            // DataTable

            // Apply the search
            var table = $('.tableDT').DataTable();
            var table2 = $('.bulk_action').dataTable();
            if(<?=permission()?permission()->create == 0?1:0:0?>){
                var index = 0;
                $.each($("button"),function(k,v){
                    if ($("button").eq(k).html() == 'Tambah')
                        index = k;
                })
                console.log(index);
                $("button").eq(index).hide()
            }
            if(<?=permission()?permission()->delete == 0?1:0:0?>){
                var index = 0;
                $.each($("table").children('thead').children().children(),function(k,v){
                    if ($("table").children('thead').children().children().eq(k).html() == 'Delete')
                        index = k;
                })
                console.log(index);
                table2.fnSetColumnVis(index,false);
            }
            if(<?=permission()?permission()->update == 0?1:0:0?>){
                var index = 0;
                $.each($("table").children('thead').children().children(),function(k,v){
                    if ($("table").children('thead').children().children().eq(k).html() == 'Action')
                        index = k;
                })
                console.log(index);
                table2.fnSetColumnVis(index,false);
            }
            
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

            $(".right_col").css('height', document.getElementById('content').clientHeight+170);

            $('#content').resize(function() {
                $(".right_col").css('height', document.getElementById('content').clientHeight+170);
            });
        });
        
        $("#changeJP").click(function(){
            url = '<?=site_url()?>/core/get_jabatan';
            $.ajax({
                url: url,
                dataType: "json",
                success: function(data){
                    var items = []; 
                    $("#changeJP").attr("style","display:none");
                    $("#saveJP").removeAttr('style');
                    $("#jabatan").removeAttr('disabled');
                    $("#jabatan")[0].innerHTML = "";
                    $("#project")[0].innerHTML = "";
                    $("#jabatan").append("<option value='' selected disabled>Pilih Jabatan</option>");
                    $.each(data, function(key, val){
                        $("#jabatan").append("<option value='" + val.id + "'>" + val.name.toUpperCase()+ "</option>");   
                    });
                }
            });

        });
        $("#jabatan").change(function(){
            url = '<?=site_url()?>/core/get_project';
            console.log(this.value);
            $.ajax({
                type: "post",
                url: url,
                data: {jabatan:this.value},
                dataType: "json",
                success: function(data){
                    console.log(data);
                    $("#project").removeAttr('disabled');
                    $("#project")[0].innerHTML = "";
                    $("#project").append("<option value='' selected disabled>Pilih Project</option>");
                    $.each(data, function(key, val){
                        $("#project").append("<option value='" + val.id + "'>" + val.name.toUpperCase()+ "</option>");   
                    });
                    // $("#project").removeAttr('disabled');
                    // var items = []; 
                    // $("#changeJP").attr("style","display:none");
                    // $("#saveJP").removeAttr('style');
                    // $("#jabatan").removeAttr('disabled');
                    // $("#project").removeAttr('disabled');
                    // $("#jabatan")[0].innerHTML = "";
                    // $("#project")[0].innerHTML = "";
                    // $("#jabatan").append("<option value='' selected disabled>Pilih Jabatan</option>");
                    // $.each(data, function(key, val){
                    //     $("#jabatan").append("<option value='" + val.id + "'>" + val.name.toUpperCase()+ "</option>");   
                    // });
                }
            });
        });

        $(document).ajaxStart(function(){
            $("#loading").show();
        });

        $(document).ajaxComplete(function(){
            $("#loading").hide();
        });
    </script>
</body>
</html>