            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="<?=base_url()?>/images/user.png" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Admin</span>
                            <h2><?=ucwords($this->session->userdata('name'))?></h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br>

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>Global</h3>
                            <ul class="nav side-menu">
                                <li>
                                    <a>
                                        <i class="fa fa-database"></i>Master 
                                        <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu">
                                        <li>
                                            <a href="<?=site_url()?>/g_master_pt">PT</a>
                                        </li>
                                        <li>
                                            <a>
                                                Accounting<span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <li class="sub_menu"><a href="level2.html">Coa</a></li>
                                            </ul>
                                        </li>   
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="menu_section">
                            <h3>Proyek</h3>
                            <ul class="nav side-menu">
                                <li>
                                    <a>
                                        <i class="fa fa-database"></i>Master 
                                        <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu">
                                        <li>
                                            <a href="<?=site_url()?>/p_master_pt">PT</a>
                                        </li>
                                        <li>
                                            <a>
                                                Accounting<span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <li class="sub_menu"><a href="<?=site_url()?>/P_master_mappingCoa">Mapping COA</a></li>
                                                <li><a href="<?=site_url()?>/P_master_bank">Bank</a></li>
                                                <li><a href="<?=site_url()?>/P_master_cara_pembayaran">Cara Pembayaran</a></li>
                                                <li><a href="<?=site_url()?>/P_master_metode_penagihan">Metode Penagihan</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a>
                                                Service<span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <li class="sub_menu"><a href="<?=site_url()?>/P_master_service">Service</a></li>
                                                <li><a <a href="<?=site_url()?>/P_master_paket_service">Paket Service</a></li>
                                                <li><a <a href="<?=site_url()?>/P_master_transaksi_lo">Transaksi LO</a></li>
                                                <li><a <a href="<?=site_url()?>/P_master_grup_tvi">Grup TV Internet</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a>
                                                Pemeliharaan Meter<span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <li class="sub_menu"><a href="<?=site_url()?>/P_master_pemeliharaan_meter_air">Meter Air</a></li>
                                                <li><a href="<?=site_url()?>/P_master_pemeliharaan_meter_listrik">Meter Listrik</a></li>
                                            </ul>
                                        </li>

                                        <li>
                                            <a>
                                                Range<span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <li class="sub_menu"><a href="<?=site_url()?>/P_master_range_air">Range Air</a></li>
                                                <li><a href="<?=site_url()?>/P_master_range_lingkungan">Lingkungan</a></li>
                                                <li><a href="<?=site_url()?>/P_master_range_listrik">Range Listrik</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a>
                                                Golongan<span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <li class="sub_menu"><a href="<?=site_url()?>/P_master_golongan">Golongan</a></li>
                                                <li><a href="<?=site_url()?>/P_master_sub_golongan">Sub Golongan</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="<?=site_url()?>/P_master_customer">Customer</a>
                                        </li>
                                        <li>
                                            <a>
                                                Town Planning<span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <li class="sub_menu"><a href="<?=site_url()?>/P_master_proyek">Proyek</a></li>
                                                <li><a href="<?=site_url()?>/P_master_kawasan">Kawasan</a></li>
                                                <li><a href="<?=site_url()?>/P_master_blok">Blok</a></li>
                                                <li><a href="<?=site_url()?>/P_master_unit">Unit</a></li>
                                                
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="<?=site_url()?>/P_master_unit_virtual">Unit Virtual</a>
                                        </li>
                                        
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                <!-- /sidebar menu -->

            </div>
            <!-- /menu footer buttons -->