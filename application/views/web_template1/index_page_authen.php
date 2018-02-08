<!DOCTYPE html>
<html>
    <head>
        <?php 
            $site = $this->webinfo_model->getSiteInfo(); 
            // $title = $title!=''?$title:$site['site_title'].'(Index Page)';
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

    <title><?php echo "ข้อมูลทะเบียนประวัติผู้สูงอายุ";?></title>

</head>

<body class="pace-done mini-navbar" style="background-color: #F7F8FA !important;">

  <div id="wrapper">

    <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">

                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="background-color: rgba(0,0,0,0.4); border: 0;font-size: 19px; padding: 3px 20px 3px 20px;" href="<?php echo site_url('');?>"><i class="fa fa-caret-left" aria-hidden="true"></i> </a>
            <ul class="nav navbar-top-links navbar-right">
              <li class="margin-top: -5px; margin-bottom: 0px;">
                <a ><span class="m-r-sm text-muted welcome-message" style="font-size: 15px; color:#4e5f4d;">กรมกิจการผู้สูงอายุ กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์</span></a>
              </li>
            </ul>

            </div>
        

        </nav>
    </div>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12" style="padding-right: 30px;">

            <h2 class="head_txt1" style="font-size: 21px">ระบบให้บริการข้อมูลทะเบียนประวััติผู้สูงอายุแบบออนไลน์ (Older Information Center)</h2>

            <ol class="breadcrumb text-right" style="position: absolute; top: 29px; right: 20px;">
                <!--<li><a href="index.html">สรุปภาพรวม</a></li>
                    <li><a href="index.html">ด้านสวัสดิการผู้สูงอายุ</a></li>
                -->
                <li class="active" style="font-size: 16px">
                    <strong></strong>
                </li>
            </ol>

        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content p-md" style="min-height: 550px;background: #f7f8f9;">   
                       
                        <?php
                      
                        if($this->template->content_view_set==0)
                            $this->load->view($this->template->name.'/'.$content_view);
                        ?>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row border-top border-bottom" style="background-color: #fff">
            <div class="col-lg-12" style="padding-top: 15px; padding-bottom: 15px;">
                <div class="pull-right" style="font-size: 14px; padding: 15px; color: #858C92 !important;">
                    เวอร์ชั่น 1.1
                </div>
                <div style="font-size: 14px; padding: 15px; color: #858C92 !important;">
                    © สงวนลิขสิทธ์โดย<strong> กรมกิจการผู้สูงอายุ</strong> พัฒนาโดย บริษัท จิ๊กซอว์ อินโนเวชั่น จำกัด 
                </div>
            </div>
        </div>

    </div>


    </div><!-- End id="wrapper"-->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="margin-top: 200px;">
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgba(3, 169, 244, 0.78)">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ระบบให้บริการข้อมูลทะเบียนประวััติผู้สูงอายุแบบออนไลน์</h4>
      </div>
      <div class="modal-body" style="background-color: rgba(96, 125, 139, 0.11);">
         
          <div class="row form-group">
             <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                 <label style="color:red;" class="text-center"><h3><?php echo @$comment; ?></h3></label>
             </div>

             <div class="col-xs-12 col-sm-10" style="padding-right: 0px;">
                <label >กรอกหมายเลขบัตรประจำตัวประชาชนและรหัสผ่านเจ้าหน้าที่</label>
                <input type="text" class="form-control input_idcard" value="" name="user_id" placeholder="หมายเลขประชาชนจำนวน 13 หลัก" autofocus id="user_id">
             </div>
             <div class="col-xs-12 col-sm-1" style="padding-left: 5px;"><label>&nbsp;</label><a id="btn_cheusrpid" href="webrun:C:\\ReaderCardID\\ReaderCardID.exe" type="button" title="ตรวจสอบผ่านเครื่องอ่านบัตร" class="btn btn-info"  ><i class="fa fa-id-card-o" aria-hidden="true"></i></a></div>

              <div class="col-xs-12 col-sm-10">
                  <input style="margin-top: 5px; width: 150px" type="password" class="form-control" value="" name="autenpasscode" placeholder="รหัสผ่าน" required />
              </div>

             <div class="col-xs-12 col-sm-10 " style="padding-right: 0px;">
                <label >กรอกหมายเลขบัตรประจำตัวประชาชนเพื่อตรวจสอบ</label>
                <input type="text" class="form-control input_idcard" value="" name="authen_id" placeholder="หมายเลขประชาชนจำนวน 13 หลัก">
             </div>

             <div class="col-xs-12 col-sm-1" style="padding-left: 5px;"><label>&nbsp;</label><a id="btn_chepid" href="webrun:C:\\ReaderCardID\\ReaderCardID.exe" type="button" title="ตรวจสอบผ่านเครื่องอ่านบัตร" class="btn btn-info"  ><i class="fa fa-id-card-o" aria-hidden="true"></i></a></div>
          </div>
          <form id="form_id" method="post" action="<?php echo site_url('individual_authen/authen_encode'); ?>">
             <input type="hidden" value="" name="id" >
           </form>
      </div>
      <div class="modal-footer" style="background-color: rgba(96, 125, 139, 0.17);">
        <button type="button" id="btn_authen" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> ตรวจสอบ</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
        
      </div>
      
    </div>
  </div>
</div>

 

</body>

<script type="text/javascript">
     $(function(){
          <?php if($profile==false){?>
               $('#myModal').modal({
                                backdrop : false,
                                show:true
                             });
               

               $('#wrapper').css({
                                 'filter':'blur(3px)',
                                 'opacity':'0.5'
                                  });
          <?php }?>
          
           
          $('#myModal').on('hide.bs.modal', function (e) {
            
               window.location.href ='<?php echo site_url('admin_access'); ?>';
          });

          $('#btn_authen').click(function(){
              var pid = $('input[name=authen_id]').val();
              
              if($('input[name=user_id]').val()==''){
                  alert('กรุณากรอกหมายเลขบัตรประจำตัวประชาชนให้ครบ 13 หลัก');
                  $('input[name=user_id]').focus();
              }else if(pid==''){
                  alert('กรุณากรอกหมายเลขบัตรประจำตัวประชาชนให้ครบ 13 หลัก');
                  $('input[name=authen_id]').focus();
              }else{
                var pid_arr = pid.split("-");
                var pid_int = parseInt((pid_arr[0]+pid_arr[1]+pid_arr[2]+pid_arr[3]+pid_arr[4]));
               
                $('input[name=id]').val(pid_int); 

                $('form').submit();
              }
                  
          });

          $('#btn_chepid').click(function(){
              $('input[name=authen_id]').focus();
          });

          $('#btn_cheusrpid').click(function(){
              $('input[name=user_id]').focus();
          });

      });
</script>

<?php $this->load->file('assets/tools/tools_script.php'); ?>

</html>
