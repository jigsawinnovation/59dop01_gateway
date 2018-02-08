
<div id="tmp_menu" hidden='hidden'>
 <?php
 $tmp = $this->admin_model->getOnce_Application(103); 
            $tmp1 = $this->admin_model->chkOnce_usrmPermiss(103,$user_id); //Check User Permission
            ?>
            <a data-toggle="modal" data-target="#register" < style="margin-left: 0px; background-color: #2f4250; border: 0;font-size: 17px; padding: 2px 20px 2px 20px;" title="<?php if(isset($tmp['app_name'])){echo $tmp['app_name'];}?>" class="navbar-minimalize minimalize-styl-2 btn btn-primary">
              <i class="fa fa-plus" aria-hidden="true"></i>
            </a>

            <?php
            $tmp = $this->admin_model->getOnce_Application(103); 
            $tmp1 = $this->admin_model->chkOnce_usrmPermiss(103,$user_id); //Check User Permission
            ?>
            &nbsp;
            <a <?php if(!isset($tmp1['perm_status'])) {?>
              readonly 
              <?php }else{?> href="<?php echo site_url('');?>" <?php }?> style="background-color: #2f4250; color: #fff; border: 0;font-size: 17px; padding: 2px 20px 2px 20px; margin-left: 0px;" title="<?php if(isset($tmp['app_name'])){echo $tmp['app_name'];}?>" class="navbar-minimalize minimalize-styl-2 btn btn-primary">
              <i class="fa fa-file-excel-o" aria-hidden="true"></i>
            </a>

            &nbsp;
            <a style="background-color: #2f4250; color: #fff; border: 0;font-size: 17px; padding: 2px 20px 2px 20px;margin-left: 0px;" title="ค้นหา" class="navbar-minimalize minimalize-styl-2 btn btn-primary" data-toggle="modal" data-target="#mySearch">
              <i class="fa fa-filter" aria-hidden="true"></i>
            </a>
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="margin-left: 0px; background-color: #2f4250; border: 0;font-size: 17px; padding: 2px 20px 2px 20px;" href="<?php echo site_url('admin_access');?>"><i class="fa fa-caret-left" aria-hidden="true"></i> </a>
</div>
 <script>
    setTimeout(function(){
      $("#menu_topright").html($("#tmp_menu").html());
    },300);

    function showChart(){
      $("#chart_display").slideToggle();
    }
  </script>
<!--
<h3 style="color: #4e5f4d"><?php echo $title;?></h3>
<hr/>
-->

  <div class="table-responsive">

    <table id="dtable" class="table table-striped table-bordered table-hover dataTables-example" style="margin-top: 0px !important;" >
      <thead style="color: #333; font-wight: bold; font-size: 15px; font-weight: bold;">
        <tr>
            <th>#</th>
            <th>เลขประจำตัวประชาชน</th>
            <th>ชื่อตัว-ชื่อสกุล</th>
            <th>อายุ (ปี)</th>

            <th>หน่วยงาน/แผนก ที่สังกัด</th>
            <th>ชื่อตำแหน่ง</th>
            <th>เบอร์โทรศัพท์ (มือถือ)</th>
            <th>สถานะการเปิดใช้งาน</th>
            <th>&nbsp;</th>
        </tr>
			</thead>
      <tbody>
      <?php
      $number = 1;
       // dieArray($authen_info);
      foreach ($authen_info as $key => $value) {
      ?>
                <tr>
                    <td class="lnk"><?php echo $number;?></td>
                    <td class="lnk"><?php echo $value['pid'];?></td>
                    <td class="lnk"><?php echo $value['user_firstname'].' '.$value['user_lastname'];?></td>
                    <td class="lnk">
                    <?php
                    $age = '';
                    if($value['date_of_birth']!='') {
                      $date = new DateTime($value['date_of_birth']);
                      $now = new DateTime();
                      $interval = $now->diff($date);
                      $age = $interval->y;
                      echo $age;
                    }
                    ?>
                    </td>
                    <td class="lnk"> 
                      <?php echo $value['user_org'];?>                   
                    </td>
                    <td class="lnk">
                      <?php echo $value['user_position'];?> 
                    </td>
                    <td class="lnk">
                      <?php echo $value['tel_no'];?> 
                    </td>
                    <td class="lnk text-center">
                      <?php if($value['active_status']=='Active') { ?>
                        <font class="text-sucsess" color="green"><b><?php echo $value['active_status'];?></b></font>
                      <?php }else{ ?>
                         <font class="text-sucsess" color="#B9B9B9"><b><?php echo $value['active_status'];?></b></font>
                      <?php } ?>
                    </td>
                    <td align="right">

                  <?php
                    $tmp = $this->admin_model->getOnce_Application(103); 
                    $tmp1 = $this->admin_model->chkOnce_usrmPermiss(103,$user_id); //Check User Permission
                  ?>
                  <a <?php if(!isset($tmp1['perm_status'])) {?>
                    readonly 
                  <?php }else{?>  href="<?php echo site_url('manage_transfer/authen_key/Edit/'.$value['authen_id']); ?>" <?php }?> title="<?php if(isset($tmp['app_name'])){echo $tmp['app_name'];}?>" class="btn btn-default">
                      <i class="fa fa-pencil-square" aria-hidden="true" style="color: #000"></i>
                  </a>

                  
                  <a data-toggle="modal" data-target="#prt<?php echo $value['pid'];?>" title="พิมพ์แบบฟอร์ม" class="btn btn-default">
                      <i class="fa fa-file-text" aria-hidden="true" style="color: #000"></i>
                  </a> 
                  <!-- Print Modal -->
                  <div class="modal fade" id="prt<?php echo $value['pid'];?>" role="dialog">
                    <div class="modal-dialog">
                      
                       <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header text-left">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                           <h4 class="modal-title" style="color: #333; font-size: 15px;">พิมพ์แบบฟอร์ม</h4>
                         </div>
                        <div class="modal-body">
                          <div class="row">         
 
                            <?php
                            $tmp = $this->admin_model->getOnce_Application(103);   
                            $tmp1 = $this->admin_model->chkOnce_usrmPermiss(103,get_session('user_id')); //Check User Permission
                            ?>      
                            <div class="col-xs-12 col-sm-12 text-left" style="margin-bottom: 10px;" 
                            <?php
                            if(!isset($tmp1['perm_status'])) { ?>
                                class="disabled" 
                            <?php 
                              }else if($usrpm['app_id']==103) {
                            ?>
                                class="active"
                            <?php
                              }
                            ?>
                             >
                              <a style="color: #333; font-size: 15px; margin-bottom: 50px;" target="_blank" href="<?php echo site_url('report/A4');?>"><i class="fa fa-print" aria-hidden="true"></i> <?php if(isset($tmp1['perm_status'])) {echo $tmp1['app_name']; }?>
                              </a>
                            </div>

                           </div>    
                           <br/>

                        </div>
                      </div>
                        
                    </div>
                   </div>
                   <!-- End Print Modal -->

                  
                   <?php
                    $tmp = $this->admin_model->chkOnce_usrmPermiss(3,$user_id); //Check User Permission
                    if(isset($tmp['perm_status'])) {
                        if($tmp['perm_status']=='Yes') {
                   ?>  
                        <a href="<?php echo site_url('manage_transfer/authen_key/Delete/'.$value['authen_id']); ?>" type="button" data-id=<?php echo $value['authen_id'];?>  title="ลบ" type="button" class="btn btn-default">
                          <span class="glyphicon glyphicon-trash" style="color: #000"></span>
                        </a>
                    <?php }
                    }
                    ?>

                      <!-- Info Modal -->
                      <div class="modal fade" id="<?php echo $value['pid'];?>" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                           <div class="modal-header" style="background-color: #eee">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h3 class="modal-title text-left"><?php echo $value['user_firstname'].' '.$value['user_lastname'];?></h3>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-xs-12 col-sm-3">
                                  <img src="<?php echo path(get_session('user_photo_file'));?>" class="img-responsive">
                                </div>
                                <div class="col-xs-12 col-sm-9">
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-3"><h4>เลขประจำตัวประชาชน</h4></div><div class="col-xs-12 col-sm-3 text-left"><?php echo $value['pid'];?></div>
                                  </div>
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-3"><h4>วันเดือนปีเกิด</h4></div><div class="col-xs-12 col-sm-6 text-left">18 มี.ค. 2496 (อายุ <?php echo $age;?> ปี)</div>
                                  </div>
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-3"><h4>เพศ</h4> ชาย</div>
                                    <div class="col-xs-12 col-sm-3"><h4>สัญชาติ</h4> ไทย</div>
                                    <div class="col-xs-12 col-sm-3"><h4>ศาสนา</h4> อิสลาม</div>
                                  </div>
                                  <div class="row">
                                  &nbsp;
                                  </div>
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-3"><h4>ที่อยู่ตามทะเบียนบ้าน</h4></div><div class="col-xs-12 col-sm-5 text-left"> 124 ถนน รามคำแหง 58/3 แขวง หัวหมาก เขต ปางกะปิ กรุงเทพมหานคร 10240 </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End Info Modal -->
                        



                    </td>  
                </tr>

      <?php
        $number++;
      }
      ?>
      </tbody>
		</table>

  </div>
 
<!-- Trigger the modal with a button -->
<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->

<!-- Delete Modal -->
<div id="dltModel" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color: #333; font-size: 15px;">หน้าต่างแจ้งเตือนเพื่อยืนยัน</h4>
      </div>
      <div class="modal-body">
        <?php $str = getMsg('034');?>
        <p><?php echo $str;?></p>
        <!--<p>ยืนยันการลบ?</p>-->
      </div>
      <div class="modal-footer">
        <button id="btnYes" type="button" class="btn btn-danger">ตกลง</button>
        <button style="margin-bottom: 5px;" type="button" aria-hidden="true" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
      </div>
    </div>
  </div>
</div>
<!-- End Delete Model -->

<!-- Search Modal -->
<div class="modal fade" id="mySearch" role="dialog">
  <div class="modal-dialog">
    
     <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title" style="color: #333; font-size: 15px;">ค้นหา</h4>
       </div>
      <div class="modal-body">
        <label for="email">เลขประจำตัวประชาชน:</label>
         <input type="text" class="form-control">
    
         <label for="email">ชื่อตัว-ชื่อสกุล:</label>
         <input type="text" class="form-control">

          <label for="email">หน่วยงาน/แผนก ที่สังกัด:</label>
         <input type="text" class="form-control">
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-search" aria-hidden="true"></i> ตกลง</button> 
       </div>
    </div>
      
  </div>
 </div>
 <!-- End Search Modal -->

 <!-- Modal register-->
<div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header" >
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title" id="myModalLabel">เพิ่มบัญชีผู้ใช้</h4>
      </div>
      <div class="modal-body" >
            <ul class="nav nav-tabs " role="tablist">
                 <li id="tab_prifile" role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">บัญชีผู้ใช้งาน</a></li>
                 <li id="tab_permission" role="presentation" data-disabled="disabled">
                     <a href="#permission_authen" aria-controls="permission_authen" role="tab" data-toggle="tab" >สิทธิการใช้งาน</a>                          
                 </li>
                
            </ul>
            
            <br>

            <?php
            
            if($process_action=='View'){
              $process = "Added";
            }else{              
              $process ="Edited";

            }

            echo form_open('manage_transfer/authen_key/'.$process);
            if($process=="Edited"){
            ?>
              <input type="hidden" name="authen_id" value="<?php echo $authen_val['authen_id']; ?>">
            <?php } ?>
            <!-- Tab panes -->
       <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="profile">   
                <div class="row form-group">
                  <div class="col-xs-12 col-sm-12">
                        <label>คำนำหน้านาม</label>
                        <select class="form-control"  name="val[user_prename]" id="user_prename_add">
                          <?php foreach($prename as $key=>$value) {?>
                          <option value="<?php echo $value['pren_code']; ?>" <?php if($authen_val['user_prename']==$value['pren_code']){ echo "selected"; } ?> ><?php echo $value['prename_th']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                  <div class="col-xs-12 col-sm-12">
                   <label>เพศ</label>
                     
                    <div class="radio">
                       <label><input style="cursor: pointer;" type="radio" name="val[user_gender]" id="optionsRadios1" value="1" <?php if($authen_val['user_gender']==1){echo "checked";} ?>>ชาย</label>
                       <label><input style="cursor: pointer;" type="radio" name="val[user_gender]" id="optionsRadios1" value="2" <?php if($authen_val['user_gender']==2){echo "checked";} ?>>หญิง</label>    
                    </div>
                  

                  </div>
                </div>
                               
                <div class="row form-group">                   
                    <div class="col-xs-12 col-sm-6">
                        <label>ชื่อตัว</label>
                        <input type="text" class="form-control" name="val[user_firstname]" placeholder="ระบุชื่อตัว" id="user_firstname_add" value="<?php echo $authen_val['user_firstname']; ?>" required>
                    </div>
                      <div class="col-xs-12 col-sm-6">
                        <label>ชื่อสกุล</label>
                        <input type="text" class="form-control" name="val[user_lastname]" placeholder="ระบุชื่อสกุล" id="user_lastname_add" value="<?php echo $authen_val['user_lastname']; ?>" required>
                    </div>
                </div>

                 <div class="row form-group">
                      <div class="col-xs-12 col-sm-12">
                          <label>เลขประจำตัวประชาชน</label>
                          <input type="text" class="form-control input_idcard" name="val[pid]" placeholder="เลขประจำตัวประชาชน (13 หลัก)" id="pid_add" value="<?php echo $authen_val['pid']; ?>" required>
                      </div>
                </div>

                  <div class="row form-group">
                      <div class="col-xs-12 col-sm-12">
                          <label>วัน/เดือน/ปีแกิด</label>
                          <div id="datetimepicker1" class="input-group date" data-date-format="dd-mm-yyyy">
                              <input title="วันที่แจ้งเรื่อง" placeholder="เลือกวันที่" class="form-control" type="text" name="val[date_of_birth]" id="date_of_birth_add" value="<?php echo dateChange($authen_val['date_of_birth'],4); ?>" required/>
                              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>

                        <script type="text/javascript">
                       
                        $(function () {
                            $("#datetimepicker1").datepicker({
                               autoclose: true,
                               todayHighlight: true
                            });
                        });
                        </script>
                      </div>
                  </div>

                  <div class="row form-group">
                      <div class="col-xs-12 col-sm-12">
                          <label>หน่วยงาน/แผนกที่สังกัด</label>
                          <input type="text" class="form-control" name="val[user_org]" placeholder="ระบุหน่วยงาน/แผนกที่สังกัด" id="user_org_add" value="<?php echo $authen_val['user_org']; ?>">
                      </div>
                  </div>

                  <div class="row form-group">
                      <div class="col-xs-12 col-sm-12">
                          <label>ชื่อตำแหน่ง</label>
                          <input type="text" class="form-control" name="val[user_position]" placeholder="ระบุชื่อตำแหน่ง" id="user_position_add" value="<?php echo $authen_val['user_position']; ?>">
                      </div>
                  </div>

                  <div class="row form-group">
                      <div class="col-xs-12 col-sm-12">
                          <label>เบอร์โทรศัพท์ (มือถือ)</label>
                          <input type="tel" class="form-control" name="val[tel_no]" placeholder="ตัวอย่าง 08XXXXXXXX" id="tel_no_add" value="<?php echo $authen_val['tel_no']; ?>">
                      </div>
                  </div>

                   <div class="row form-group">
                      <div class="col-xs-12 col-sm-12">
                          <label>ที่อยู่อีเมล</label>
                          <input type="email" class="form-control" name="val[email_addr]" placeholder="ตัวอย่าง me@mail.com" id="email_addr_add" value="<?php echo $authen_val['email_addr']; ?>">
                      </div>
                  </div>

               </div><!-- End tabpanel home-->

               <div role="tabpanel" class="tab-pane" id="permission_authen">

                 <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          Import (นำเข้าข้อมูล)
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                      <div class="panel-body">

                           <?php
                           $i=1;
                           foreach($importwsv_info as $key=>$row) {
                            ?>
                            
                            <div class="row form-group">
                                <div class="col-xs-12 col-sm-12">
                                   <label><u><?php echo $row['name'];?></u></label>
                                </div>
                            </div>
                           
                            <?php

                            foreach($row['data'] as $key1=>$row1) {
                              ?>
                            <div class="row form-group">
                                <div class="col-xs-12 col-sm-12">
                                      <div class="checkbox">
                                        <label>
                                          <input style="cursor: pointer;"  id="importwsv<?php echo $row1['wsrv_id'];?>" type="checkbox" name="wsrv[]" value="<?php echo $row1['wsrv_id'];?>"> <?php echo $row1['wsrv_definition'];?> (<?php echo $row1['wsrv_name'];?>)
                                        </label>
                                      </div>
                                 </div>
                            </div>
                            <?php } //End foreach($row['data'] as $key1=>$row1)?>

                            <?php } ?>

                        
                       
                      </div><!-- End panel-body-->
                    </div>
                  </div><!-- End id=headingOne-->

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                      <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          Export (นำออกข้อมูล)
                        </a>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                      <div class="panel-body">
                            <?php
                           $j=1;
                           foreach($exportwsv_info as $key2=>$row2) {
                            ?>
                            
                            <div class="row form-group">
                                <div class="col-xs-12 col-sm-12">
                                   <label><u><?php echo $row2['name'];?></u></label>
                                </div>
                            </div>
                           
                            <?php

                            foreach($row2['data'] as $key3=>$row3) {
                              ?>
                            <div class="row form-group">
                                <div class="col-xs-12 col-sm-12">
                                      <div class="checkbox">
                                        <label>
                                          <input style="cursor: pointer;" id="importwsv<?php echo $row3['wsrv_id'];?>" type="checkbox" name="wsrv[]" value="<?php echo $row3['wsrv_id'];?>"><?php echo $row3['wsrv_definition'];?> (<?php echo $row1['wsrv_name'];?>)
                                        </label>
                                      </div>
                                 </div>
                            </div>
                            <?php } //End foreach($row['data'] as $key1=>$row1)?>

                            <?php } ?>

                                     <?php 
                                if($process=="Edited"){
                                $permission_byid = $this->authen_model->Editpermission_getId($authen_val['authen_id']); 
                                if(!empty($permission_byid)){

                                 foreach ($permission_byid as $key_per => $val_wsrv) {

                             ?>
                                  <script type="text/javascript">
                                      $("#importwsv<?php echo $val_wsrv['wsrv_id']; ?>").prop('checked',true);
                                  </script>

                              <?php     
                              }
                           }
                         }
                           ?>

                      </div>
                    </div>
                  </div><!-- End id=headingTwo-->
                </div><!-- End id=accordion-->

                    
               </div><!-- End tabpanel profile -->

            </div>
            <button type="submit" id="form_add" style="display: none;"></button>
            <input type="hidden" name="bt_submit" value="sub" >
            <!-- End Tab panes -->
            <?php echo form_close();?>

          
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-warning" style="margin-bottom: 0px;" id="btn_pnext">ถัดไป</button>
        <button type="button" class="btn btn-primary" style="margin-bottom: 0px;" id="btn_sub">บันทึก</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" id="Add_cencel_modal">ยกเลิก</button>
        
        
        
      </div>
    </div>
  </div>
</div>
<!-- End modal register-->

<script type="text/javascript">
   
   $(function(){
       $('#register').modal({
                            backdrop:false,
                            <?php if($process!="Edited"){ ?>
                            show:false
                            <?php }else{ ?>
                             show:true 
                            <?php } ?>
                          });
       <?php if($process=="Edited"){ ?>
            $("#Add_cencel_modal").click(function(){
                 window.location.href = "<?php echo site_url('manage_transfer/authen_key'); ?>";
            });
        <?php } ?>
   });
       $("#btn_sub").hide();

       $("#tab_permission").click(function(){
          $("#btn_pnext").html("ก่อนหน้า");
          $("#btn_sub").show();
       });

       $("#tab_prifile").click(function(){
          $("#btn_pnext").html("ถัดไป");
          $("#btn_sub").hide();
       });

        $("#btn_pnext").click(function(){
            if($(this).html()=="ถัดไป"){
               $("#tab_permission").children().click();
            }else{
               $("#tab_prifile").children().click();
            }
        });

        $("#btn_sub").click(function(){
           
           var prename        =  $("#user_prename_add").val();
           var firstname      =  $("#user_firstname_add").val();
           var lastname       =  $("#user_lastname_add").val();
           var pid            =  $("#pid_add").val();
           var date_of_birth  =  $("#date_of_birth_add").val();
           var user_org       =  $("#user_org_add").val();
           var position       =  $("#user_position_add").val();
           var tel_no         =  $("#tel_no_add").val();
           var email          =  $("#email_addr_add").val();
                               
           
           
           if(prename==''||firstname==''||lastname==''||pid==''||date_of_birth==''||user_org==''||tel_no==''||email==''){
               
               $("#tab_prifile").children().click();

               if(prename==''){
                 $("#user_prename_add").focus();
               }else if(firstname==''){
                 $("#user_firstname_add").focus();
               }else if(lastname==''){
                 $("#user_lastname_add").focus();
               }else if(pid==''){
                 $("#pid_add").focus();
               }else if(date_of_birth==''){
                 $("#date_of_birth_add").focus();
               }else if(user_org==''){
                 $("#user_org_add").focus();
               }else if(position==''){
                 $("#user_position_add").focus();
               }else /*if(tel_no==''){           
                 $("#tel_no_add").focus();
               }else*/  if(email==''){
                 $("#email_addr_add").focus();
               } 
               
               

           }else{

               
               if(isNaN(tel_no)){
                 alert("กรุณากรอกเบอร์โทรศัพท์(มือถือ) เป็นตัวเลข");
                 $("#tab_prifile").children().click();
                 $("#tel_no_add").focus();
               }else if(email.indexOf('@') <= 0){
                 alert("รูปแบบอีเมลไม่ถูกต้อง!");
                 $("#tab_prifile").children().click();
                 $("#email_addr_add").focus();
               }else{
                 var len_permiss = $(":checkbox:checked").length; 
                  if(len_permiss==0){
                     alert("กรุณากำหนดสิทธิการใช้งาน อย่างน้อย 1 สิทธิการใช้งาน");
                  }else{
                     $('#form_add').click();
                  }
                  
               }
               
           }
                      
        });
   
   

</script>
