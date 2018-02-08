<!DOCTYPE html>
<html>
    <head>
        <?php 
            $site = $this->webinfo_model->getSiteInfo(); 
            $title = $title!=''?$title:$site['site_title'].'(Index Page)';
        ?>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="<?php echo $site['deprt_descp'];?>">
        <meta name="keywords" content="<?php echo $site['deprt_keywd'];?>">
        <meta name="author" content="Sonchai058">

        <link rel="shortcut icon" href="<?php echo path($site['site_icon_file']);?>" type="image/x-icon">
        <link rel="icon" href="<?php echo path($site['site_icon_file']);?>" type="image/x-icon">

  <?php $this->load->file('assets/tools/tools_config.php');?>

  <?php //Set Background Path?>
  <style tyle="text/css">
    body {
        background: url('<?php echo path($site['site_bg_file']);?>');
    }
  </style>
  
    <title><?php echo $title;?></title>

</head>

<body class="pace-done mini-navbar">

  <div id="wrapper">

    <div class="row border-bottom">
        <nav class="navbar navbar-fixed-top" role="navigation" style="min-height: 44px; margin-bottom: 0;background-color: #fff;border-bottom: 1px #60aed2 solid !important;">
            <div class="navbar-header">
                <a href="<?php echo site_url('');?>" title="<?php echo $site['site_title'];?>">
                    <h1 class="title" style="margin-top: 7px;margin-bottom: 8px; float: left; margin-left: 10px; color: #2f4250; font-size: 26px;">DOP <font style="color: #e9e9e9">|</font></h1>
                    <h2 class="sec1_title" style="margin-top: 7px;margin-bottom: 8px; float: left; margin-left: 10px; color: #1664fa; font-size: 26px;">Data Gateway</h2>
                </a>
            </div>
            <style type="text/css">
                .nav.navbar-right > li > a{
                    padding-top: 9px;
                    padding-bottom: 0px;
                }
                .navbar-top-links li a{
                    padding-left: 0px;
                    padding-right: 0px;
                    min-height: 44px;
                }
                .navbar-top-links li:last-child{
                    margin-right: 20px;
                }
    
            </style>
            <ul class="nav navbar-top-links navbar-right" style="height: 44px;">
             
                <?php
                  //$user = get_session('user_firstname').' '.get_session('user_lastname');
                  $user = get_session('user_firstname');
                ?>
                <li title="<?php echo $user;?>" style="margin-top: -5px; margin-bottom: 0px;">
                  <a style="position: relative;padding-top: 5px;"  href="#" title="<?php echo $user;?>">
                     <?php if(get_session('licen')==''){ ?>
                      <img src="<?php echo 'https://center.dop.go.th/'.get_session('user_photo_file');?>" class="profile img-circle border-1" style="border: 2px #eee solid; position: absolute; left: -25px; top: 11px;" width="26" height="26">  
                     <?php } ?> 
                      &nbsp;
                      <span class="m-r-sm text-muted welcome-message" style="font-weight: initial; font-size: 26px; color:#1664fa;"><?php echo $user;?><font style="padding-left: 10px;color: #e9e9e9">|</font></span>

                  </a>
                  <script type="text/javascript">

                      function onmodal_edit(){
                           $('#edit_profile').modal('show'); 
                      }
                  </script>
                </li>

                <li>
                    <a title="เมนูหลัก" style="display: inline; color:#9a6a36" dropdown-toggle" data-toggle="dropdown">
                        <i style="font-size: 20px; color: #2f4250 !important" class="fa fa-windows" aria-hidden="true"></i>
                    </a>
                    <?php $this->load->view($this->template->name.'/head_menu_list');?>
                </li>
                <!-- Message Box -->
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i style="font-size: 22px; color: #2f4250 !important" class="fa fa-comments" aria-hidden="true"></i>  <span class="label label-danger" style="top:4px;right: 4px;">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="<?php echo path('noProfilePic.jpg');?>">
                                </a>
                                <div class="media-body">
                                    <small class="pull-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="<?php echo path('noProfilePic.jpg');?>">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="<?php echo path('noProfilePic.jpg');?>">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html">
                                    <i class="fa fa-envelope text-warning"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- End Message Box -->
                <li>
                    <a title="คู่มือช่วยเหลือ" href="" style="display: inline; color:#9a6a36">
                        <i style="font-size: 22px; color: #2f4250 !important" class="fa fa-life-ring" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a title="ออกจากระบบ" href="<?php echo site_url('logout');?>" style="display: inline; color:#9a6a36">
                        <i style="font-size: 24px; color: #2f4250 !important" class="fa fa-power-off" aria-hidden="true"></i>
                    </a>
                </li>
                
            </ul>

        </nav>
    </div>
    <style type="text/css">
        .minimalize-styl-2{
            margin: 5px 5px 5px 20px;
        }
    </style>
    <div class="row wrapper border-bottom white-bg page-heading" style="margin-top: 44px; padding: 0 !important; background-color: #a3c3d8;border-bottom: 1px #60aed2 solid !important;">
        <div class="col-xs-12 col-sm-8" style="padding-right: 30px;">

            
                <!-- *Permission Menu -->
              <?php $this->load->view($this->template->name.'/head_menu');?>
                <!-- End *Permission Menu -->  

            <ol class="breadcrumb" style="background-color: #a3c3d8; font-size: 18px;padding-left: 0px; margin-top: 6px; margin-bottom: 0px;">
                <li style="color: #333;font-size: 20px; font-weight: bold"><a href=""><?php echo $head_title;?></a></li>
                <li class="active" style="color: #1664FA;font-size: 20px; font-weight: bold"><?php echo $title;?></li>
            </ol>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div style="float: right" id="menu_topright">
                <!--
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="margin-top: 11px; margin-left: 0px; background-color: #2f4250; border: 0;font-size: 17px; padding: 5px 20px 3px 20px;" href="<?php echo site_url('control/main_module');?>"><i class="fa fa-plus" aria-hidden="true"></i> </a>
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="margin-top: 11px; margin-left: 0px; background-color: #2f4250; border: 0;font-size: 17px; padding: 5px 20px 3px 20px;" href="<?php echo site_url('control/main_module');?>"><i class="fa fa-filter" aria-hidden="true"></i> </a>
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="margin-top: 11px; margin-left: 0px; background-color: #2f4250; border: 0;font-size: 17px; padding: 5px 20px 3px 20px;" href="<?php echo site_url('control/main_module');?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="margin-top: 11px; margin-left: 0px; background-color: #2f4250; border: 0;font-size: 17px; padding: 5px 20px 3px 20px;" href="<?php echo site_url('control/main_module');?>"><i class="fa fa-caret-left" aria-hidden="true"></i> </a>
                -->
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight" style="padding: 0px !important">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins" style="margin-bottom:0px">
                    <div class="ibox-content p-md" style="min-height: 550px; background-color: #f1f5f7">   
                    <style type="text/css">
                        .dataTables_wrapper{
                            padding-bottom: 0px;
                        }
                        #dtable_info ,#dtable_paginate{
                            font-size: 16px !important;
                        }
                    </style>
                    <?php
                      
                        if($this->template->content_view_set==0)
                            $this->load->view($this->template->name.'/'.$content_view);
                    ?>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row border-top border-bottom" style="background-color: #fff">
            <div class="col-lg-12" style="padding: 3px 22px;">
                <div class="pull-right" style="font-size: 16px; color: #2f4050 !important; padding-right:30px">
                    เวอร์ชั่น 1.1
                </div>
                <div style="font-size: 16px; color: #2f4050 !important; padding-left:30px">
                    © สงวนลิขสิทธิ์โดย <strong>กรมกิจการผู้สูงอายุ</strong> | พัฒนาโดย <strong>บริษัท จิ๊กซอว์ อินโนเวชั่น จำกัด</strong>
                </div>
            </div>
        </div>

    </div>


    </div>
 

</body>

<?php $this->load->file('assets/tools/tools_script.php'); ?>



</html>
