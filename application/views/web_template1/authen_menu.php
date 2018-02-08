<!DOCTYPE html>
<html>
    <head>
        <?php 
            $site = $this->webinfo_model->getSiteInfo(); 
            $title = $site['site_title'].'(Menu)';
        ?>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="<?php echo $site['deprt_descp'];?>">
        <meta name="keywords" content="<?php echo $site['deprt_keywd'];?>">
        <meta name="author" content="Sonchai058">

        <link rel="shortcut icon" href="<?php echo path($site['site_icon_file']);?>" type="image/x-icon">
        <link rel="icon" href="<?php echo path($site['site_icon_file']);?>" type="image/x-icon">

        <!-- *************** inspinia-3 Template: CSS *************** -->
        <?php echo css_asset('../plugins/Static_Full_Version/css/bootstrap.min.css'); ?>
        <?php echo css_asset('../plugins/Static_Full_Version/font-awesome/css/font-awesome.css'); ?>
        <?php echo css_asset('../plugins/Static_Full_Version/css/animate.css'); ?>
        <?php echo css_asset('../plugins/Static_Full_Version/css/style.css'); ?>
        <!-- *************** End inspinia-3 Template: CSS *************** -->

        <?php
          echo css_asset('./fontsset.css');
        ?>

  
        <?php //Set Background Path?>
        <style tyle="text/css">
            body {
              //background: url('<?php echo path($site['site_bg_file']);?>');
            }
        </style>

        <?php  
          echo css_asset('./menu.css');
        ?>

        <title><?php echo $title; ?></title>

</head>

<body>
    <div id="wrapper">

        <div class="row" >
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0; background: transparent !important;">
                <div class="navbar-header" style="margin: 20px auto auto 50px;">
                    <a href="<?php echo site_url('control/main_module');?>" title="<?php echo $site['site_title'];?>">
                        <h1 class="title"><?php echo strtoupper($site['site_name']);?></h1>
                        <h2 class="sec1_title"><?php echo $site['site_title'];?></h2>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right" style="margin: 20px;">
                    <?php
                    $user = get_session('user_firstname').' '.get_session('user_lastname');
                    ?>
                    <li title="<?php echo $user;?>">
                        <a href="#profile"> <span class="m-r-sm text-muted welcome-message"><?php echo $user;?></span> 
                        </a>
                    </li>

                    <li>
                        <a title="Sign Out" href="<?php echo site_url('logout');?>" style="display: inline;">
                            <span>Sign Out</span>
                        </a>
                        |
                        <a href="#help" style="display: inline;">
                            Help
                        </a>
                    </li>
                </ul>

            </nav>
        </div>

        <!--<div class="animated fadeInRight">-->
        <div class="row" style="background: transparent !important;">
            <div class="container" style="min-height: 600px;">

               <div class="row permiss_head">
                    <div class="col-xs-12 col-sm-4 permiss_head-panel" style="border-right:0">
                        <br/>
                        <div class="row">
                            <div class="col-sm-12 item">
                                <a 
                                <?php
                                $tmp = $this->admin_model->getOnce_Application(101);   
                                $tmp1 = $this->admin_model->chkOnce_usrmPermiss(101,get_session('user_id')); //Check User Permission
                                if(!isset($tmp1['perm_status'])) { ?>
                                     class="disabled" 
                                <?php }else{?>href="<?php echo site_url('manage_transfer/import');?>"<?php }?>>
                                    <div class="col-sm-2 icon text-left">
                                        <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-sm-9 txt">
                                    <?php if(isset($tmp['app_name'])){echo $tmp['app_name'];}?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 permiss_head-panel" style="border-right:0">
                        <br/>
                        <div class="row">
                            <div class="col-sm-12 item">
                                <a 
                                <?php
                                $tmp = $this->admin_model->getOnce_Application(102);   
                                $tmp1 = $this->admin_model->chkOnce_usrmPermiss(102,get_session('user_id')); //Check User Permission
                                if(!isset($tmp1['perm_status'])) { ?>
                                     class="disabled" 
                                <?php }else{?>href="<?php echo site_url('manage_transfer/export');?>"<?php }?>>
                                    <div class="col-sm-2 icon text-left">
                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-sm-9 txt">
                                    <?php if(isset($tmp['app_name'])){echo $tmp['app_name'];}?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 permiss_head-panel" style="border-right:0">
                        <br/>
                        <div class="row">
                            <div class="col-sm-12 item">
                                <a href="<?php echo site_url('manage_transfer/datastandard');?>">
                                    <div class="col-sm-2 icon text-left">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-sm-9 txt">
                                        ชุดข้อมูลมาตรฐาน (Data Standard)
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 permiss_head-panel" style="border-right:0">
                        <br/>
                        <div class="row">
                            <div class="col-sm-12 item">
                                <a 
                                <?php
                                $tmp = $this->admin_model->getOnce_Application(103);   
                                $tmp1 = $this->admin_model->chkOnce_usrmPermiss(103,get_session('user_id')); //Check User Permission
                                if(!isset($tmp1['perm_status'])) { ?>
                                     class="disabled" 
                                <?php }else{?>href="<?php echo site_url('manage_transfer/authen_key');?>"<?php }?>>
                                    <div class="col-sm-2 icon text-left">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-sm-9 txt">
                                    <?php if(isset($tmp['app_name'])){echo $tmp['app_name'];}?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 permiss_head-panel" style="border-right:0">
                        <br/>
                        <div class="row">
                            <div class="col-sm-12 item">
                                <a href="<?php echo site_url('manage_transfer/statistic_importlog');?>">
                                    <div class="col-sm-2 icon text-left">
                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-sm-9 txt">
                                        สถิติการแลกเปลี่ยนข้อมูลระหว่างหน่วยงาน
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 permiss_head-panel" style="border-right:0">
                        <br/>
                        <div class="row">
                            <div class="col-sm-12 item">
                                <a 
                                <?php
                                $tmp = $this->admin_model->getOnce_Application(156);   
                                $tmp1 = $this->admin_model->chkOnce_usrmPermiss(156,get_session('user_id')); //Check User Permission
                                if(!isset($tmp1['perm_status'])) { ?>
                                     class="disabled" 
                                <?php }else{?>href="<?php echo site_url('manage_transfer/webservice_demo');?>"<?php }?>>
                                    <div class="col-sm-2 icon text-left">
                                        <i class="fa fa-exchange" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-sm-9 txt">
                                    <?php if(isset($tmp['app_name'])){echo 'ทดสอบ'.$tmp['app_name'];}?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <br/>
        <!--<div class="footer" style="background: transparent !important;">-->
        <div style="background: transparent !important;">
            <div class="pull-right" style="font-size: 14px; padding: 15px; color: #858C92 !important;">
                เวอร์ชั่น 1.0
            </div>
            <div style="font-size: 14px; padding: 15px; color: #858C92 !important;">
                © สงวนลิขสิทธ์โดย<strong> กรมกิจการผู้สูงอายุ</strong> พัฒนาโดย บริษัท จิ๊กซอว์ อินโนเวชั่น จำกัด 
            </div>
        </div>

    </div>
</body>
    <!-- *************** inspinia-3 Template: JS *************** -->
        <!-- Mainly scripts -->
    <?php echo js_asset('../plugins/Static_Full_Version/js/jquery-3.1.1.min.js'); ?>
    <?php echo js_asset('../plugins/Static_Full_Version/js/bootstrap.min.js'); ?>
    <?php echo js_asset('../plugins/Static_Full_Version/js/plugins/metisMenu/jquery.metisMenu.js'); ?>
    <?php echo js_asset('../plugins/Static_Full_Version/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>
        <!-- Custom and plugin javascript -->
    <?php echo js_asset('../plugins/Static_Full_Version/js/inspinia.js'); ?>
    <?php echo js_asset('../plugins/Static_Full_Version/js/plugins/pace/pace.min.js'); ?>
    <?php echo js_asset('../plugins/Static_Full_Version/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>
    <!-- *************** End inspinia-3 Template: JS *************** -->
</html>
