<!DOCTYPE html>
<html>
    <head>
        <?php 
            $site = $this->webinfo_model->getSiteInfo(); 
            $title = $site['site_title'].'(Login)';

            $csrf = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
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
              background: url('<?php echo path($site['site_bg_file']);?>');

            }
        </style>

        <?php  
          echo css_asset('./login.css');
        ?>

        <title><?php echo $title; ?></title>

    </head>

    <body style="background-repeat: no-repeat;background-attachment: fixed;">
<!--
<style>
* { box-sizing: border-box; }
.video-background {
  background: #000;
  position: fixed;
  top: 0; right: 0; bottom: 0; left: 0;
  z-index: -99;
}
.video-foreground,
.video-background iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
}
#vidtop-content {
    top: 0;
    color: #fff;
}
.vid-info { position: absolute; top: 0; right: 0; width: 33%; background: rgba(0,0,0,0.3); color: #fff; padding: 1rem; font-family: Avenir, Helvetica, sans-serif; }
.vid-info h1 { font-size: 2rem; font-weight: 700; margin-top: 0; line-height: 1.2; }
.vid-info a { display: block; color: #fff; text-decoration: none; background: rgba(0,0,0,0.5); transition: .6s background; border-bottom: none; margin: 1rem auto; text-align: center; }
@media (min-aspect-ratio: 16/9) {
  .video-foreground { height: 300%; top: -100%; }
}
@media (max-aspect-ratio: 16/9) {
  .video-foreground { width: 300%; left: -100%; }
}
@media all and (max-width: 600px) {
.vid-info { width: 50%; padding: .5rem; }
.vid-info h1 { margin-bottom: .2rem; }
}
@media all and (max-width: 500px) {
.vid-info .acronym { display: none; }
}
</style>
<div class="video-background">
    <div class="video-foreground">
      <iframe src="https://www.youtube.com/embed/OFv4NQFBIWY?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&playlist=OFv4NQFBIWY" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
</div>
-->

        <div id="wrapper">
            <div class="login">
               

                    <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />

                    <div title="<?php echo strtoupper($site['site_name']);?>" class="row logo">
                        <div class="col-sm-4 col-sm-offset-4 text-center">
                           
                                <h1 class='title' style="font-weight: bold;font-size: 43px;letter-spacing: 5px;border-bottom: 1px solid rgba(255, 255, 255, 0.2);"><?php echo strtoupper($site['site_name']);?></h1>                                                                                                                                                                 
                                <h4 class="sec1_title" style="letter-spacing: 4px;font-size: 24px;color: #9ca4ab !important;"><?php echo $site['site_title'];?></h4>
    
                             
                        </div>
                    </div>
                    <br/>
                 
                
                         <div class="row">

                                   <div class="col-xs-12 col-sm-12">
                                      <form action="<?php echo site_url('admin_access/authen_login');?>" method="post" class="form-signin" autocomplete="off" style="max-width: 100%; border-bottom:0px;">
                                         <div class="row">
                                           <div class="col-xs-12 col-sm-4 col-sm-offset-4 text-center">
                                            <div class="row clearfix" style="border: 10px solid rgba(96, 125, 139, 0.71);background-color: rgba(240, 248, 255, 0.11);">
    

                                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                                    &nbsp;<span id="wrn"><?php echo @$authen_wrn;?></span>
                                                </div>
                                                 <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                                    <h3 class="text-center">หน่วยงานภาคี</h3>
                                                </div>
                                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                                    <!--<div>3-1006-02549-62-4,q631006025496</div>-->
                                                    <input type="text" class="form-control input_idcard" value="" name="authenpid" placeholder="เลขประจำตัวประชาชน" required autofocus="" />
                                                </div>
                                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                                    <input type="password" class="form-control" value="" name="autenpasscode" placeholder="รหัสผ่าน" required />
                                                </div>
                                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                                    <button type="submit" class="btn btn-primary btn-lg"  name="">เข้าสู่ระบบ</button>
                                                </div>
                                                <div class="col-xs-12 col-sm-10 col-sm-offset-1"><br></div>
                                                    
                                                
                                            </div>
                                        </div>
                                        </div>
                                        
                                        </form>
                                    </div>
                             

                                  <!--   <div class="col-xs-12 col-sm-6">
                                        <form action="<?php echo site_url('admin_access/login');?>" method="post" class="form-signin" autocomplete="off" style="max-width: 100%; border-bottom:0px;">
                                           <div class="row">
                                                 <div class="col-xs-12 col-sm-8 col-sm-offset-1 text-center">
                                                        <div class="row clearfix" style="border: 10px solid rgba(96, 125, 139, 0.71);background-color: rgba(240, 248, 255, 0.11);">

                                                            <div class="col-xs-12 col-sm-12  ">
                                                                &nbsp;<span id="wrn"><?php echo @$wrn;?></span>
                                                            </div>           
                                                            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                                                <h3 class="text-center">เจ้าหน้าที่ผู้ดูแลระบบ</h3>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                                                <input type="text" class="form-control input_idcard" value="3101701933555" name="pid" placeholder="เลขประจำตัวประชาชน" required autofocus="" />
                                                            </div>
                                                            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                                                <input type="password" class="form-control" value="0381" name="passcode" placeholder="รหัสผ่าน" required />
                                                            </div>
                                                            <div class="col-xs-12 col-sm-10 col-sm-offset-1 text-center">
                                                                <button type="submit" class="btn btn-primary"  name="bt_submit">เข้าสู่ระบบ</button>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-10 col-sm-offset-1"><br></div>
                                                        </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div> -->
                   
                                  <div class="col-xs-12 col-sm-12">
                                     <div class="row">
                                         <div class="col-xs-12 col-sm-6 col-sm-offset-3 text-center">
                                                
                                           <a id="Older_Infornation" class="btn btn-default" href="<?php echo site_url('individual_authen/authen_info?id=');?>" role="button" style="background-color: rgb(25, 43, 57); border: 3px solid rgba(238, 238, 238, 0.6); font-size: 18px;">ระบบให้บริการข้อมูลทะเบียนประวััติผู้สูงอายุแบบออนไลน์ <br>(Older Information Center)</a>
                                       
                                         </div>
                                     </div>
                                  </div>                

                         </div>
                         <br><br><br>

                         <div class="row">
                             <div class="col-xs-12 col-sm-10 col-sm-offset-1 text-center">
                               <div class="table-responsive">
                                         <table border="1" class="table" style="border: 2px solid rgba(235, 235, 235, 0.59);; font-size: 18px;">
                                             <thead>
                                                 <th class="text-center">รายการมาตราฐาน</th>
                                                 <th class="text-center">จำนวน(รายการ)</th>
                                                 <th class="text-center">แหล่งข้อมูล</th>
                                                 <th class="text-center">ปรับปรุงล่าสุด</th>
                                                 <th>&nbsp;</th>
                                             </thead>
                                             <tbody>
                                               <?php 
                                                    
                                                    foreach ($info as $key => $value) {                    
                                              ?>
                                                 <tr>
                                                    <td class="lnk text-left"><?php echo $value['std_db_table'].' : '.$value['std_title'];?></td>
                                                    <td class="lnk">
                                                        <?php 
                                                        $nums = $this->transfer_model->get_numRowsStd($value['std_db_table']);
                                                        echo number_format($nums);
                                                        ?>  
                                                    </td>
                                                    <td class="lnk text-left"><?php echo $value['std_org'];?></td>
                                                    <td class="lnk"><?php echo dateChange($value['rec_update'],5); ?></td>
                                                     <td>
                                                        <a target="_blank" href="<?php echo site_url('rssfeed/'.$value['std_db_table']);?>.xml" title="Rss Feed" class="btn btn-default" style="background-color: rgba(158, 158, 158, 0.54);"><i class="fa fa-link" aria-hidden="true" style="color: #000"></i></a>
                                                        <a target="_blank" href="<?php echo base_url('assets/file/'.$value['std_db_table']);?>.xls" title="Export Excel" class="btn btn-default" style="background-color: rgba(158, 158, 158, 0.54);"><i class="fa fa-file-excel-o" aria-hidden="true" style="color: #000"></i></a>
                                                     </td>                                                                                               
                                                 </tr>

                                                 <?php  } ?>
                                             </tbody>
                                         </table>
                                </div>
                             </div>
                         </div>
                
                    <br>
                    
                    <div class="row">
                      <div class="col-xs-12 col-sm-4 col-sm-offset-4">
                        <!-- <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button> -->
                        <h2 class="text-center">กรมกิจการผู้สูงอายุ </h2>
                        <h3 class="text-center">กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์</h3>
                        
                        <hr style="border-top: 1px solid rgba(238, 238, 238, 0.31);">
                      </div>
                    </div>
                    
                <h3 class="text-center">พบปัญหาการใช้งานระบบกรุณาติดต่อผู้ดูแลระบบ</h3>
                <h3 class="text-center">© สงวนลิขสิทธ์โดย กรมกิจการผู้สูงอายุ พัฒนาโดย บริษัท จิ๊กซอว์ อินโนเวชั่น จำกัด เวอร์ชั่น 1.0</h3>
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

    <!-- *************** Set Format Plugin *************** -->
    <?php echo js_asset('../plugins/setformat/jquery.number.min.js'); ?>
    <?php echo js_asset('../plugins/setformat/jquery.maskedinput.min.js'); ?>
    <?php echo js_asset('../plugins/setformat/jquery.setformat.js'); ?>
    <!-- *************** End Set Format Plugin *************** -->

    <script>

    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($("input[name='pin_code']").val()=='' || $("input[name='passcode']").val()==''){
                $("#wrn").text("*กรุณากรอกข้อมูลให้ครบถ้วน");
            }else {
                //window.location.replace("<?php echo site_url('admin_access/login');?>");
                setTimeout(function(){
                    $("input[name='bt_submit']").click();
                },400);
            }
        }

    });

    $(document).bind('keyup', function(e){
        if($("input[name='pin_code']").val()!='' && $("input[name='passcode']").val()!=''){
            $("#wrn").text("");
        }
    }); 

    // $('#Older_Infornation').mouseenter(function(){
    //   $('#Older_Infornation').css({'background-color':'rgb(25, 43, 57)','font-size':'16px'});
    // });

    // $('#Older_Infornation').mouseleave(function(){
    //   $('#Older_Infornation').css({'background-color':'rgba(56, 77, 92, 0.6)','font-size':'14px'});
    // });   

    </script>
</html>