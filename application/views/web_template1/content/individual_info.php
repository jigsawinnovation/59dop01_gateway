<div class="form-group row"><!-- form-group row level1-->
     <div class="col-xs-12 col-sm-12">
          <div class="panel-group">
               <div class="panel panel-default" style="border: 1">
                    <?php
                    
                         // $pers_info = array('pid' =>'5-1021-99020-66-5',
                         // 'name'     =>'นาง คูเลาะ เคอหม่า',
                         // 'gender_name' =>'หญิง',
                         // 'nation_name_th'=>'ไทย',
                         // 'relg_title' =>'คริส',
                         // 'date_of_birth' => '18 มี.ค. 2596 (เสียชีวติ 30 เม.ย. 2560) (อายุ 93 ปี)',
                         // 'reg_add_info' =>'54 หมู่ 4 ถนนสุดสนิท ตำบลช้างเคิ่ง อำเภอ แม่แจ่ม จังหวัด เชียงใหม่ 50270');
                    ?> 
                         <div class="panel-body" style="border:0; padding: 20px;background-color: rgba(96, 125, 139, 0.11);">

                              <div class="form-group row">
                                   <div class="col-xs-12 col-sm-3">
                                       <div class="row">
                                           <div class="col-xs-12 col-xs-12">
                                              <?php 
                                                 $paht_img = 'noProfilePic.jpg';
                                                 if(!empty($pers_info['img_file'])){
                                                 $paht_img = $pers_info['img_file'];
                                                 }
                                              ?>
                                              <img src="<?php echo site_url('assets/uploads/profile_img/'.$paht_img); ?>" class="img-responsive" style="margin: 0 auto; width: 70%;">
                                           </div>
                                       </div>
                                   </div>

                                    <div class="col-xs-12 col-sm-9">

                                       <div class="row">
                                              <div class="col-xs-12 col-sm-4" ><h2 style="color: #2c2c2d;"><label>เลขประจำตัวประชาชน</label><h2></div>
                                              <div class="col-xs-12 col-sm-4 "><h2 style="font-weight:500;"><?php echo @$pers_info['pid']; ?></h2></div>
                                              <div class="col-xs-12 col-sm-4" ><h2 style="color: #2c2c2d;"><label><?php echo @$pers_info['name'];?></label></h2></div>
                                       </div>

                                      <div class="row">   
                                              <div class="col-xs-12 col-sm-4" ><h2 style="color: #2c2c2d;"><label>วันเดือนปีเกิด</label></h2></div>
                                              <div class="col-xs-12 col-sm-8" > <h2 style="font-weight:500;" id="date_of_birth"><?php if(empty($pers_info['date_of_birth'])){echo "-"; }else{ echo @$pers_info['date_of_birth'];}?></h2></div>
                                      </div>

                                      <div class="row">      
                                              <div class="col-xs-12 col-sm-1" ><h2 style="color: #2c2c2d;"><label>เพศ</label></h2> </div>
                                              <div class="col-xs-12 col-sm-3"><h2 style="font-weight:500;" id="gender_name"> <?php if(empty($pers_info['gender_name'])){echo "-"; }else{ echo @$pers_info['gender_name']; } ?></h2></div>
                                              <div class="col-xs-12 col-sm-1" ><h2 style="color: #2c2c2d;"><label>สัญชาติ</label></h2>  </div>
                                              <div class="col-xs-12 col-sm-3"><h2 style="font-weight:500;" id="nation_name_th"> <?php if(empty($pers_info['nation_name_th'])){echo "-"; }else{ echo @$pers_info['nation_name_th'];} ?></h2></div>
                                              <div class="col-xs-12 col-sm-1" ><h2 style="color: #2c2c2d;"><label>ศาสนา</label></h2> </div>
                                              <div class="col-xs-12 col-sm-3"><h2 style="font-weight:500;" id="relg_title"> <?php if(empty($pers_info['relg_title'])){echo "-"; }else{ echo @$pers_info['relg_title']; } ?></h2></div>
                                      </div>

                                      <div class="row"> 
                                              <div class="col-xs-12 col-sm-4" ><h2 style="color: #2c2c2d;"><label>ที่อยู่ตามทะเบียนบ้าน</label></h2></div>
                                              <div class="col-xs-12 col-sm-8" > <h2 style="font-weight:500;" id="reg_addr"><?php if(empty($pers_info['reg_add_info'])){echo "-"; }else{ echo @$pers_info['reg_add_info']; } ?>&nbsp;</h2></div>
                                            
                                      </div>
                                    
                                    <?php if((@$age!='')&&(@$age>=60)){ ?>

                                      <div class="row">   
                                              <div class="col-xs-12 col-sm-4" ><h2 style="color: #2c2c2d;"><label>ผลการประเมิน (ล่าสุด)</label></h2></div>
                                              <div class="col-xs-12 col-sm-4" >
                                                     <div class="progress" style="background-color: rgba(96, 125, 139, 0.22);margin-bottom: 0px;margin-top: 10px;">
                                                      <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                                        60%
                                                      </div>
                                                    </div>
                                              </div>
                                              <div class="col-xs-12 col-sm-4" ><h2 style="font-weight:500;">(44 จาก 55 คะแนน)</h2></div>
                                       </div>

                                       <div class="row"> 
                                              <div class="col-xs-12 col-sm-4" ><h2 style="color: #2c2c2d;"><label>สถานะการผ่านเกณฑ์ จปฐ.<br>(รายได้ ไม่เกิน 38,000)</label></h2></div>
                                              <div class="col-xs-12 col-sm-8" >
                                                    <?php 
                                                         if(empty($pers_info['mth_avg_income'])){
                                                                   echo '<h2 style="color:red;">ไม่พบข้อมูลรายได้</h2>';
                                                         }else{
                                                              if($pers_info['mth_avg_income']<=38000){
                                                                   echo '<h2 style="color:green;">ผ่านเกณฑ์ จปฐ.</h2>';
                                                              }else{
                                                                   echo '<h2 style="color:red;">ไม่ผ่านเกณฑ์ จปฐ.</h2>';
                                                              }
                                                         } 
                                                     ?>
                                                      
                                              </div>
                                       </div>

                                       <div class="row"> 
                                              <div class="col-xs-12 col-sm-4" ><h2 style="color: #2c2c2d;"><label>การจัดการงานศพ(สถานะการเสียชีวิต)</label></h2></div>
                                              <div class="col-xs-12 col-sm-8" >
                                                   <?php 
                                                       if(empty($pers_info['date_of_death'])){
                                                              echo '<h2 style="color:green;">ยังไม่เสียชีวิต</h2>';
                                                       }
                                                   ?>
                                              </div>
                                       </div>

                                       <?php } ?>

                                   </div>

                                   
                              </div>
                      
                         </div><!-- close panel-body-->
                    </div><!-- close panel panel-default-->

               </div><!-- close panel-group-->           
          </div><!-- close col-xs-12 col-sm-12--> 
</div><!-- close form-group row level1-->

<div class="from-group row">
 <?php if(@$age>=60){ ?> 
 <div class="col-xs-12 col-sm-12">
      <?php $fa = array('fa fa-heart','fa fa-hospital-o','fa fa-suitcase','fa fa-credit-card','fa fa-paint-brush','fa fa-lightbulb-o','fa fa-umbrella','fa fa-pause','fa fa-archive','fa fa-bookmark'); ?>
      <div class="panel-group">
       <div class="panel panel-default" style="border: 1">
           <div class="panel-body" style="border:0; padding: 20px;background-color: rgba(96, 125, 139, 0.11);">
          
          <!-- เงินสงเคราะห์ในภาวะยากลำบาก -->
           <?php 
                if(empty($pers_info['pers_id'])){
                  $pers_id = 0;
                }else{
                   $pers_id = $pers_info['pers_id'];
                }

                $authen_diff = $this->authen_model->chk_authen_diff($pers_id);

                if(!empty($authen_diff)){
                   foreach ($authen_diff as $key => $value_audiff){
                       if(!empty($value_audiff['date_of_pay'])){
                       $year_diff = dateChange($value_audiff['date_of_visit'], 5);
                       $year_visit = substr($year_diff, 6)                         
            ?>              
                        <div class="row">
                          <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle "><i class="fa fa-heart" style="position: relative;top: -4px;"></i></button></div>
                          <div class="col-xs-12 col-sm-10">
                             <h2 style="color: rgb(3, 3, 3);"><label><?php echo dateChange($value_audiff['date_of_pay'], 5); ?></label> <label><?php echo "ได้รับเงินสงเคราะห์ในภาวะยากลำบาก จำนวน ".number_format($value_audiff['pay_amount'])." บาท (ปีงบประมาณ ".$year_visit.")"; ?></label></h2>
                             <h3 style="font-size: 20px;">( ศูนย์พัฒนาการจัดสวัสดีการสังคมผู้สูงอายุ <?php echo $value_audiff['req_org']; ?>)</h3>
                         </div>
                       </div><br>
                       <?php } //end if(!empty($value_audiff['date_of_pay'])) ?>
               <?php } //end foreach ($authen_diff as $key => $value_audiff)?>
          <?php } //end if(!empty($authen_diff))?>

          <!-- รับเการสงเคราะห์ศูนย์พัฒนาการจัดสวัสดีการสังคม -->
          <?php 
               $authen_adm = $this->authen_model->chk_authen_adm($pers_id);
                if(!empty($authen_adm)){
                    foreach ($authen_adm as $key => $value_auadm){                  
          ?>
                      <div class="row">
                          <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle btn-sm "><i class="fa fa-hospital-o" style="position: relative;top: -7px; font-size: 18px;"></i></button></div>
                          <div class="col-xs-12 col-sm-10">
                             <h2 style="color: rgb(3, 3, 3);"><label><?php echo dateChange($value_auadm['date_of_req'], 5); ?></label> <label><?php echo "เข้ารับการสงเคราะห์ในศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอายุ (จำหน่าย ".dateChange($value_auadm['date_of_dis'], 5).")"; ?></label></h2>
                             <h3 style="font-size: 20px;">( ศูนย์พัฒนาการจัดสวัสดีการสังคมผู้สูงอายุ <?php $value_auadm['req_org']; ?>)</h3>
                         </div>
                       </div><br>
                <?php } // end foreach ?>
          <?php } //end if ?>
        
         <!-- กรมการจัดหางาน กระทรวงแรงงาน-->
         <?php 
               $authen_edop = $this->authen_model->chk_authen_edop($pers_id);
               // dieArray($authen_edop);
                if(!empty($authen_edop)){
                    foreach ($authen_edop as $key => $value_edop){                  
          ?>
         <div class="row">
             <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle btn-sm "><i class="fa fa-suitcase" style="position: relative;top: -7px;font-size: 18px;"></i></button></div>
             <div class="col-xs-12 col-sm-10">
                 <h2 style="color: rgb(3, 3, 3);"><label><?php echo dateChange($value_edop['date_of_reg'], 5); ?></label> <label style="color:green;"><?php echo "ขึ้นทะเบียนจัดหางาน"; ?></label>&nbsp;&nbsp;<label <?php if($value_edop['reg_status']=="ได้งานทำแล้ว"){ echo "style=\"color:green;\""; }else{ echo "style=\"color:red;\""; } ?> >(<?php echo $value_edop['reg_status']; ?>)</label></h2>
                 <h3 style="font-size: 20px;"><?php if(!empty($value_edop['rec_source'])) { echo "( ".$value_edop['rec_source']." )"; }else{ echo "&nbsp;"; } ?></h3>
            </div>
       </div><br>
            <?php } // end foreach ?>
       <?php } //end if ?>

        <!-- กู้ยืมกองทุนผู้สูงอายุ-->
        <?php 
               $authen_elderly = $this->authen_model->chk_authen_elderly($pers_id);
               // dieArray($authen_edop);
                if(!empty($authen_elderly)){
                    foreach ($authen_elderly as $key => $value_elderly){                  
          ?>
                <div class="row">
                        <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle btn-sm "><i class="fa fa-credit-card" style="position: relative;top: -6px; font-size: 18px;"></i></button></div>
                        <div class="col-xs-12 col-sm-10">
                            <h2 style="color: rgb(3, 3, 3);"><label><?php if(!empty($value_elderly['year_of_contract'])){echo "ปีที่ทำสัญญา ".($value_elderly['year_of_contract']+543); } ?></label> <label style="color:green;"><?php echo "กู้ยืมกองทุนผู้สูงอายุ "; ?></label>&nbsp;&nbsp;<label <?php if($value_elderly['contract_status']=="ยังมีสัญญา"){ echo "style=\"color:green;\""; }else{ echo "style=\"color:red;\""; } ?> ><?php if(!empty($value_elderly['contract_status'])){ echo "( สถานะ ".$value_elderly['contract_status']." )";} ?></label></h2>
                            <h3 style="font-size: 20px;">( สำนักงานพัฒนาสังคมและความมั่นคงของมนุษย์ )</h3>
                       </div>
                </div><br>
                    <?php } // end foreach ?>
       <?php } //end if ?>

         <!-- ได้รับการปรับปรุงสิ่งแวดล้อมและสิ่งอำนวยความสะดวก-->
                <div class="row">
                        <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle btn-sm "><i class="fa fa-paint-brush" style="position: relative;top: -7px; font-size: 18px;"></i></button></div>
                        <div class="col-xs-12 col-sm-10">
                            <h2 style="color: rgb(3, 3, 3);"><label><?php echo dateChange(date("Y-m-d"), 5); ?></label> <label ><?php echo "ได้รับการปรับปรุงสิ่งแวดล้อมและสิ่งอำนวยความสะดวก งบประมาณ ".number_format(20000)." บาท"; ?></label></h2>
                            <h3 style="font-size: 20px;">( สำนักงานพัฒนาสังคมและความมั่นคงของมนุษย์ จังหวัดเชียงใหม่ )</h3>
                       </div>
                </div><br>

           <!-- ขึ้นทะเบียนคลังปัญญาผู้สูงอายุ-->
                <div class="row">
                        <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle btn-sm "><i class="fa fa-lightbulb-o" style="position: relative;top: -4px;"></i></button></div>
                        <div class="col-xs-12 col-sm-10">
                            <h2 style="color: rgb(3, 3, 3);"><label><?php echo dateChange(date("Y-m-d"), 5); ?></label> <label ><?php echo "ขึ้นทะเบียนคลังปัญญาผู้สูงอายุ (สาขาวิชาการเกษตร)"; ?></label></h2>
                            <h3 style="font-size: 20px;">( สำนักงานพัฒนาสังคมและความมั่นคงของมนุษย์ จังหวัดเชียงใหม่ )</h3>
                       </div>
                </div><br>


            <!-- ได้รับการเข้าร่วมอบรม "หลักสูตรการเตรียมความพร้อมก่อนวัยสูงอายุ"-->
                <div class="row">
                        <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle btn-sm "><i class="fa fa-umbrella" style="position: relative;top: -4px;"></i></button></div>
                        <div class="col-xs-12 col-sm-10">
                            <h2 style="color: rgb(3, 3, 3);"><label><?php echo dateChange(date("Y-m-d"), 5); ?></label> <label ><?php echo "ได้รับการเข้าร่วมอบรม \"หลักสูตรการเตรียมความพร้อมก่อนวัยสูงอายุ\""; ?></label></h2>
                            <h3 style="font-size: 20px;">( กรมกิจการผู้สูงอายุ กระทรวงการพัฒนาสังคมและความมั่นคงมนุษย์ )</h3>
                       </div>
                </div><br>

             <!-- ได้รับวุฒิบัตรการสำเร็จการศึกษาหลักสูตรโรงเรียนผู้สูงอายุ"-->
                <div class="row">
                        <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle btn-sm "><i class="fa fa-pause" style="position: relative;top: -6px;font-size: 18px;"></i></button></div>
                        <div class="col-xs-12 col-sm-10">
                            <h2 style="color: rgb(3, 3, 3);"><label><?php echo dateChange(date("Y-m-d"), 5); ?></label> <label ><?php echo "ได้รับวุฒิบัตรการสำเร็จการศึกษาหลักสูตรโรงเรียนผู้สูงอายุ"; ?></label></h2>
                            <h3 style="font-size: 20px;">( โรงเรียนผู้สูงอายุจังหวัดเชียงใหม่ )</h3>
                       </div>
                </div><br>


          <!-- ได้รับเงินสงเคราะห์ในการจัดงานศพ -->
          <?php 
              $authen_fnrl = $this->authen_model->chk_authen_fnrl($pers_id);
              if(!empty($authen_fnrl)){
                    foreach ($authen_fnrl as $key => $value_aufnrl){ 
          ?>
                   <div class="row">
                          <div class="col-xs-12 col-sm-1 col-sm-offset-1"><button type="button" class="btn btn-primary btn-circle btn-sm "><i class="fa fa-bookmark" style="position: relative;top: -6px;font-size: 18px;"></i></button></div>
                          <div class="col-xs-12 col-sm-10">
                             <h2 style="color: rgb(3, 3, 3);"><label><?php echo dateChange($value_aufnrl['date_of_pay'], 5); ?></label> <label><?php echo "ได้รับเงินสงเคราะห์ในการจัดงานศพ จำนวน ".number_format($value_aufnrl['pay_amount'])." บาท"; ?></label></h2>
                             <h3 style="font-size: 20px;">( ศูนย์พัฒนาการจัดสวัสดีการสังคมผู้สูงอายุ <?php echo $value_aufnrl['req_org']; ?>)</h3>
                         </div>
                       </div><br>

                <?php } // end foreach ?>
          <?php } //end if ?>

    <div class="col-sm-12 text-center">
     <button style="height: 40px;width: 200px !important;" type="button" class="btn btn-primary btn-save" onclick="window.open('https://gateway.dop.go.th/Individual_authen/authen_info?id=','_self');"><i class="fa fa-search" aria-hidden="true"></i>  ค้นหาข้อมูลทะเบียนประวัติ</button>
     <button style="background-color:#a72929; border: 1px #a72929 solid; height: 40px;width: 200px !important;" type="button" class="btn btn-primary btn-save" onclick="window.open('https://center.dop.go.th/report/J1/pdf/?id=239','_blank');"><i class="fa fa-print" aria-hidden="true"></i> ส่งออกไฟล์ PDF</button>
   </div>


  </div>
</div>
</div>


</div><!-- close class="col-sm-12"-->
<?php }else{ //End if(@$age>=60) ?>
      <?php if(@$age!=''){ $alert = "อายุตามหมายเลขบัตรประชาชนนี้ อายุ ".@$age." ปี ซึ่งต้องมากกว่าหรือเท่ากับ อายุ 60 ปี";}else{ $alert = "ระบบไม่สามารถค้นหาอายุ ตามหมายเลขบัตรประชาชนนี้ได้";} ?>
      <div class="col-xs-12 col-sm-12">
            <div class="alert alert-danger" role="alert"><h2>ขออภัย <?php echo @$alert; ?></h2></div>
      </div>
<?php }?>
</div><!-- close class="form-group row"--> 

