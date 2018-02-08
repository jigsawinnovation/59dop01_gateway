<?php
//set_session('pers_authen',array('authen_log_id'=>1,'pid'=>'1550700081881','cid'=>'k32kjk324j234','random_string'=>'3239663864316539316431313939353933356334663834636130396234353366')); //for Test
?>
<script>
  //Declear Info Reader PID
  var user_id = '<?php echo get_session('user_id');?>';
  var org_id = '<?php echo get_session('org_id');?>';
  var pers_authen = JSON.parse('<?php echo json_encode(get_session('pers_authen'));?>');
  console.log(pers_authen);
  var reader_status = false;
  var authen_log_id = 0;
  //End Declear Info Reader PID
  var csrf_hash='<?php echo @$csrf['hash'];?>';
</script>

                                    <div class="form-group row">

                                    <?php

                                    echo form_open_multipart('',array('id'=>'form1'));
                                    ?>

                                    <input type="submit" value="submit" name="bt_submit" hidden="hidden">

                                    <p class="text-danger"><font color=red><?php echo validation_errors();?></font></p>

                                    <div class="panel-group">
                                          <div class="panel panel-default" style="border: 0">
                                              <div class="panel-heading"><h4>ข้อมูลผู้ยืนคำขอ (ผู้แจ้งเรื่อง)</h4></div>

                                              <div class="panel-body" style="border:0; padding: 20px;">
                                                  <div class="form-group row">
                                                    <div class="col-xs-12 col-sm-3" style=""><img src="<?php echo path('noProfilePic.jpg');?>" class="img-responsive" style="margin: 0 auto;">
                                                    </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;"><span style="font-weight: bold;">เลขประจำตัวประชาชน</span>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 3px 15px;">
                                                      <input title="เลขประจำตัวประชาชน" placeholder="เลขประจำตัวประชาชน (13 หลัก)" class="form-control input_idcard" type="text" value="" name="diff_info[req_pid]" id="req_pid" autofocus/>
                                                      <input type="hidden" id="req_pers_id" name="diff_info[req_pers_id]" value="">
                                                    </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 1px 15px;">
                                                      <a style="border: 1px #555 solid" title="ตรวจสอบ" class="btn btn-default" id="bt_req_pid">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                        <span>ตรวจสอบ</span>
                                                      </a>
                                                      <script>
                                                        //var req_pers = null;

                                                        var inputpid = "#req_pid";var bt_spid = "#bt_req_pid";var setData = "reqData"; //Declear Name 
                                                        var reqData = function(value) { //Set Structure Display Data
                                                          $("#req_name").html(value.name);
                                                          $("#req_date_of_birth").html(value.date_of_birth);
                                                          $("#req_gender_name").html(value.gender_name);
                                                          $("#req_nation_name_th").html(value.nation_name_th);
                                                          $("#req_relg_title").html(value.relg_title);
                                                          $("#req_pers_id").val(value.pers_id);
                                                          $("#req_reg_addr").text(value.reg_add_info);
                                                        }
                                                        $(bt_spid).click(function(){//On Click for Search
                                                          if($(inputpid).val()!='') {//pid not null
                                                           
                                                           $(bt_spid).attr('disabled',true);

                                                            if(pers_authen!=null) { //Check Personal Authen
                                                              getPersInfo(inputpid,bt_spid,setData); //Get Data 
                                                            }else if(!reader_status) { //Run Reader Personal
                                                              run_readerPers();
                                                              $(bt_spid).attr('disabled',false);
                                                              toastr.warning("ท่านยังไม่ได้ Authen เข้าใช้งานในฐานะเจ้าหน้าที่ ระบบกำลังเชื่อมโยงข้อมูลกับฐานข้อมูลหลัก","Authentications");
                                                            }

                                                          }else { //pid is null
                                                            $(inputpid).select();
                                                          }
                                                        });

                                                      </script>

                                                    </div>
                                                    
                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">ชื่อตัว/ชื่อสกุล</span>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6" style="padding: 11px 15px;" id="req_name"> - </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">วันเดือนปีเกิด</span>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6" style="padding: 11px 15px;" id="req_date_of_birth"> - </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">เพศ</span> <span id="req_gender_name"> - </span>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">สัญชาติ</span> <span id="req_nation_name_th"> - </span>
                                                    </div>
                                                    
                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">ศาสนา</span> <span id="req_relg_title"> - </span>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">ที่อยู่ตามทะเบียนบ้าน</span>
                                                      <span id="req_reg_addr"> - </span>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6" style="padding: 11px 15px;"> - </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">เบอร์โทรศัพท์ (บ้าน)</span> <span id="req_tel_no_home"> - </span>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">เบอร์โทรศัพท์ (มือถือ)</span> <span id="req_tel_no_mobile"> - </span>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">เบอร์โทรสาร (แฟกซ์)</span> <span id="req_tel_no_mobile"> - </span>
                                                    </div>

                                                    <div class="col-sm-offset-3 col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                      <span style="font-weight: bold;">ที่อยู่อีเมล</span> <span id="req_email_addr"> - </span>
                                                    </div>

                                              </div>

                                              <br><br>

                                            <div class="form-group row">
                                              <div class="col-sm-offset-6 col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                <button style="width: 100%" type="button" class="btn btn-success btn-lg" onclick="return opnCnfrom()">
                                                  <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> บันทึก
                                                </button>
                                              </div>
                                              <div class="col-xs-12 col-sm-3" style="padding: 11px 15px;">
                                                <button style="width: 100%; color: #fff" type="button" class="btn btn-muted btn-lg" onclick="window.location.href=''">
                                                  <span class="fa fa-refresh" aria-hidden="true"></span> ล้างค่า
                                                </button>
                                              </div>
                                            </div>

                                          </div>
                                      </div>

                                    <?php
                                    echo form_close();
                                    ?>
                                                                             
                                </div>
                            </div>



