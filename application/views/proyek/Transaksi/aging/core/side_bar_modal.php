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
                    <?php
                            foreach($menu['level1'] as $level1){
                                echo("<div class='menu_section'>");
                                    echo("<h3 class='menu_section'>");
                                        echo($level1['name1']);
                                    echo("</h3>");
                                    
                                        foreach($menu['level2'] as $level2){
                                                if($level2['id2'] == $level1['id1']){
                                                    echo("<ul class='nav side-menu'>");
                                                        echo("<li>");
                                                            echo("<a>");
                                                                echo("<i class='fa fa-database'></i>");
                                                                echo($level2['name1']);
                                                                echo("<span class='fa fa-chevron-down'></span>");
                                                            echo("</a>");
                                                            echo("<ul class='nav child_menu'>");
                                                                foreach($menu['level3'] as $level3){
                                                                    if($level3['id2'] == $level2['id1']){
                                                                        echo("<li>");
                                                                            echo("<a");
                                                                                if($level3['url']){
                                                                                    echo(" href='");
                                                                                    echo(site_url());
                                                                                    echo("/$level3[url]");
                                                                                    echo("' >");
                                                                                    echo($level3['name1']);
                                                                                }else{
                                                                                    echo(">");
                                                                                    echo($level3['name1']);
                                                                                    echo("<span class='fa fa-chevron-down'>");
                                                                                }
                                                                            echo("</a>");
                                                                            if($menu['level4']){
                                                                                echo("<ul class='nav child_menu'>");
                                                                                foreach($menu['level4'] as $level4){
                                                                                    if($level4['id2'] == $level3['id1']){
                                                                                            echo("<li class='sub_menu'>");
                                                                                                echo("<a href='");
                                                                                                echo(site_url());
                                                                                                echo("/$level4[url]");
                                                                                                echo("'>");
                                                                                                    echo($level4['name1']);
                                                                                                echo("</>");
                                                                                            echo("</li>");                                                                                    
                                                                                    }
                                                                                }
                                                                                echo("</ul>");
                                                                            }
                                                                        echo("</li>");
                                                                    }
                                                                }
                                                            echo("</ul>");    
                                                        echo("</li>");
                                                    echo("</ul>");                                                    
                                                }
                                                    
                                        }
                                echo("</div>");
                            }
                        ?>
                        </div>
                    </div>
                </h3>
                <!-- /sidebar menu -->

            </div>
            <!-- /menu footer buttons -->