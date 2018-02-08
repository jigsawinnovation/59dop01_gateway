   
    
     <div id="tmp_menu" hidden='hidden'>
              
                  

                  <a data-toggle="modal" data-target="#register"  class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="margin-left: 0px; background-color: #2f4250; border: 0;font-size: 17px; padding: 2px 20px 2px 20px;">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                  </a>
                  <a target="_blank" href="<?php echo base_url('assets/file/webservice.pdf');?>" class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="background-color: #CC0000; color: #fff; border: 0;font-size: 17px; padding: 2px 20px 2px 20px; margin-left: 0px;">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>  Export PDF
                  </a>
                  <a target="_blank" href="<?php echo base_url('assets/file/webservice.xlsx');?>" class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="background-color: #009966; color: #fff; border: 0;font-size: 17px; padding: 2px 20px 2px 20px;margin-left: 0px;">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel
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

   <div class="row" style="margin: 5px;">

    <p class="text-danger"><font color=red><?php echo validation_errors();?></font></p>

    <div class="panel-group">
        <div class="panel panel-default" style="margin-top: -15px; border: 0">

            <div class="form-group row">
            
              <div class="col-xs-12 col-sm-12">
                
                <?php
                $i=1;
                foreach($importwsv_info as $key=>$row) {
                ?>

                <div class="row panel-heading" style="padding-top: 0px;">
                  <h4 class="text-white" style="padding: 15px; background-color: #336666;//background-color: #3300CC;margin-top: 0px;">
                    <i class="fa fa-download" aria-hidden="true"></i>&nbsp;&nbsp;
                    Import ข้อมูลจาก<?php echo $row['name'];?>
                  </h4>
                </div>
                <div class="panel-heading" style="margin-top: -30px;">
                  <div class="list-group">

                    <?php
                    
                    foreach($row['data'] as $key1=>$row1) {
                    ?>
                    <div style="cursor: pointer" class="row list-group-item">
                      <div class="col-xs-12 col-sm-10" style="padding-top: 10px;">
                        <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-size="normal">

                        <button type="button" data-toggle="modal" data-target="#t<?php echo $row1['wsrv_id'];?>" class="btn btn-success btn-xs"><i class="fa fa-play" aria-hidden="true" style="color:#fff"></i></button>

                        <button type="button" data-toggle="modal" data-target="#l<?php echo $row1['wsrv_id'];?>" class="btn btn-warning btn-xs"><i class="fa fa-cog" aria-hidden="true" style="color:#330000"></i></button>
                          <?php echo $row1['wsrv_definition'];?> (<?php echo $row1['wsrv_name'];?>)
                      </div>
                      <div class="col-xs-12 col-sm-2 text-right">
                        <b style="font-size: 14px">Structure : </b>
                        <a target="_blank" href="<?php echo site_url('transfer/import/structure/wsrv_id/'.$row1['wsrv_id']);?>" title="JSON">
                          <img src="<?php echo base_url('assets/images/json.png');?>" width="40px">
                        </a>
                        <a target="_blank" href="<?php echo site_url('transfer/import/structure/wsrv_id/'.$row1['wsrv_id'].'/format/xml');?>" title="XML">
                          <img src="<?php echo base_url('assets/images/xml.png');?>" width="40px">
                        </a>
                      </div>
                    </div>
                    

                    <!-- Modal -->
                    <div id="t<?php echo $row1['wsrv_id'];?>" class="modal fade" role="dialog">
                      <div class="modal-dialog modal-lg">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header" style="background-color:#397879; color: #fff">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">ทดสอบการใช้งาน Service</h4>
                          </div>
                          <div class="modal-body">
                            <p style="background-color:#1f4eb9; color: #fff; text-align: center;">กำหนดค่าเว็บเซอร์วิส<p>


                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3 text-left" style="font-weight: bold">ลิงค์ (URL)</div>
                                        <div class="col-xs-12 col-sm-9" style="background-color: #fff; word-wrap: break-word;">
                                          <textarea class="form-control" name="wsrv_url[<?php echo $row1['wsrv_id'];?>]" id=""><?php echo $row1['wsrv_url'];?></textarea>
                                        </div>
                                    </div>

                                    <div class="row" style="padding-top: 10px">
                                        <div class="col-xs-12 col-sm-3 text-left" style="font-weight: bold">Protocal</div>
                                        <div class="col-xs-12 col-sm-9">
                                            <select class="form-control" name="wsrv_protocal[<?php echo $row1['wsrv_id'];?>]">
                                              <option value='get'>GET</option>
                                              <option value="post">POST</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" style="padding-top: 10px">
                                        <div class="col-xs-12 col-sm-3 text-left" style="font-weight: bold">การร้องขอ (Request)</div>
                                        <div class="col-xs-12 col-sm-4 text-center" style="font-weight: bold"><u>ชื่อพารามิเตอร์ (Parameter)</u></div>
                                        <div class="col-xs-12 col-sm-5 text-center" style="font-weight: bold"><u>กำหนดค่า (Settings)</u></div>
                                    </div>
                                    
                                    <?php
                                    $tmps = $this->transfer_model->getAll_elementWSV($row1['wsrv_id'],'Parameter');
                                    if(count($tmps)>0) {
                                      foreach($tmps as $row2) {
                                      ?>
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-3" style=" padding: 5px; font-weight: bold">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-4" style="padding: 5px;">
                                            <div class="row">
                                              <div class="col-xs-12 col-sm-6">
                                              {<?php echo $row2['elem_name'];?>}
                                              </div>
                                              <div class="col-xs-12 col-sm-6">
                                              <?php echo $row2['elem_src_name'];?>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-xs-12 col-sm-5" style="padding: 5px;">
                                              <input type="text" class="form-control elem_name<?php echo $row1['wsrv_id'];?>" placeholder="<?php echo $row2['elem_definition'];?>" data-name="<?php echo $row2['elem_name'];?>">
                                          </div>
                                      </div>
                                      <?php
                                      }
                                    }else {
                                    ?>
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-3" style="font-weight: bold">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-4" style="padding: 5px;">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-5" style="padding: 5px;">&nbsp;</div>
                                      </div>
                                    <?php
                                    }
                                    ?>

                                       <div class="row">
                                         <div class="col-xs-12 text-center">
                                            <button type="button" class="btn btn-primary btn-lg" onclick="test_ck(<?php echo $row1['wsrv_id'];?>)"><i class="fa fa-retweet" aria-hidden="true"></i> ทดสอบ
                                            </button>
                                          </div>
                                      </div>

                            <p style="margin-top: 10px;background-color:#169a39; color: #fff; text-align: center;">ผลลัพธ์รูปแบบ JSON<p>
                                    <div class="row" style="padding-top: 10px">
                                        <div class="col-xs-12 col-sm-12" style="font-weight: bold">  <textarea class="form-control" rows="6" name="test_rs[<?php echo $row1['wsrv_id'];?>]" id="" placeholder="Result JSON Structure"></textarea>
                                        </div>
                                    </div>
                                      <br>
                                       <div class="row">
                                         <div class="col-xs-12 text-center">
                                            <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">
                                              <i class="fa fa-ban" aria-hidden="true" data-dismiss="modal"></i> ยกเลิก</button>
                                        </div>
                                      </div>
                          </div>
                          <!--
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                          -->
                        </div>

                      </div>
                    </div> 


                    <!-- Modal -->
                    <div class="modal fade" id="l<?php echo $row1['wsrv_id'];?>" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#397879; color: #fff">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">
                                      <div class="col-xs-12 col-sm-8 text-left">
                                        <i class="fa fa-download" aria-hidden="true"></i>&nbsp;&nbsp;
                                        Import ข้อมูลจาก<?php echo $row['name'];?>
                                      </div>
                                      <div class="text-right" style="float: right; margin-right: 20px; margin-top: -6px;">
                                          <a target="_blank" href="<?php echo base_url('assets/file/webservice_import'.($i++).'.pdf');?>" style="background-color: #c40f15; color: #fff" class="btn"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>  Export PDF</a>   
                                          <!--
                                          <b style="font-size: 14px">, Service Active : </b> 
                                          <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="danger">
                                          -->
                                      </div>
                                    </h4>
                                </div>
                                <div class="modal-body" style="padding-left: 30px">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">ซื่อเซอร์วิส (Service Name)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px; font-weight: bold"><?php echo $row1['wsrv_name'];?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">รหัสร้องขอ (Request Code)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px;">
                                        <?php $tmp = $row1['req_code']==''?'N/A':$row1['req_code'];
                                              echo $tmp;
                                        ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">รหัสตอบกลับ (Reply Code)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px;">
                                        <?php $tmp = $row1['rep_code']==''?'N/A':$row1['rep_code'];
                                              echo $tmp;
                                        ?>
                                        </div>
                                    </div>
                                    <div class="row" style="background-color: #D3D3D3;">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">คำอธิบาย (Definition)</div>
                                        <div class="col-xs-12 col-sm-9" style="background-color: #fff; padding: 15px; word-wrap: break-word;"><?php echo $row1['wsrv_definition'];?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">มาตรฐานการเชื่อม (Protocal)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px;"><?php echo $row1['wsrv_protocal'];?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">ช่องทางเชื่อมต่อ (Port)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px;"><?php echo $row1['wsrv_port'];?></div>
                                    </div>

                                    <div class="row" style="background-color: #D3D3D3;">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">ลิงค์ (URL)</div>
                                        <div class="col-xs-12 col-sm-9" style="background-color: #fff; padding: 15px; word-wrap: break-word;"><?php echo $row1['wsrv_url'];?></div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">เรียกเซอร์วิสผ่านทาง</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px;"><?php echo $row1['wsrv_via'];?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">การร้องขอ (Request)</div>
                                        <div class="col-xs-12 col-sm-9 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">เทมเพลต (Template)</div>
                                    </div>
                                    <div class="row" style="background-color: #D3D3D3;">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">&nbsp;</div>
                                        <div class="col-xs-12 col-sm-9" style="background-color: #fff; padding: 15px; word-wrap: break-word;"><?php echo $row1['wsrv_req_template'];?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">&nbsp;</div>
                                        <div class="col-xs-12 col-sm-4 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">ชื่อพารามิเตอร์ (Parameter)</div>
                                        <div class="col-xs-12 col-sm-5 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">คำอธิบาย (Definition)</div>
                                    </div>
                                    
                                    <?php
                                    $tmps = $this->transfer_model->getAll_elementWSV($row1['wsrv_id'],'Parameter');
                                    if(count($tmps)>0) {
                                      foreach($tmps as $row2) {
                                      ?>
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 5px; font-weight: bold">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-4" style="padding: 5px;">
                                            <div class="row">
                                              <div class="col-xs-12 col-sm-6">
                                              {<?php echo $row2['elem_name'];?>}
                                              </div>
                                              <div class="col-xs-12 col-sm-6">
                                              <?php echo $row2['elem_src_name'];?>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-xs-12 col-sm-5" style="padding: 5px;"><?php echo $row2['elem_definition'];?></div>
                                      </div>
                                      <?php
                                      }
                                    }else {
                                    ?>
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 5px; font-weight: bold">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-4" style="padding: 5px;">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-5" style="padding: 5px;">&nbsp;</div>
                                      </div>
                                    <?php
                                    }
                                    ?>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">การตอบกลับ (Response)</div>
                                        <div class="col-xs-12 col-sm-4 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">ชื่อชุดข้อมูล (Element)</div>
                                        <div class="col-xs-12 col-sm-5 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">คำอธิบาย (Definition)</div>
                                    </div>
                                    <?php
                                    $tmps = $this->transfer_model->getAll_elementWSV($row1['wsrv_id'],'Element');
                                    if(count($tmps)>0) {
                                      foreach($tmps as $row2) {
                                      ?>
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 5px; font-weight: bold">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-4" style="padding: 5px;">
                                            <div class="row">
                                              <div class="col-xs-12 col-sm-6">
                                              <?php echo $row2['elem_name'];?>
                                              </div>
                                              <div class="col-xs-12 col-sm-6">
                                              <?php echo $row2['elem_src_name'];?>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-xs-12 col-sm-5" style="padding: 5px;"><?php echo $row2['elem_definition'];?></div>
                                      </div>
                                      <?php
                                      }
                                    }else {
                                    ?>
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 5px; font-weight: bold">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-4" style="padding: 5px;">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-5" style="padding: 5px;">&nbsp;</div>
                                      </div>
                                    <?php
                                    }
                                    ?> 

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                    <?php
                    }
                    ?>


                  </div>
                </div>

                <?php
                }
                ?>


              </div>

            </div>

        </div>
    </div>
  </div>

         <!-- Modal Add -->
                    <div class="modal fade" id="register" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#00BCD4; color: #fff">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">
                                      <div class="col-xs-12 col-sm-8 text-left">
                                        <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;
                                        เพิ่มข้อมูลการบูรณาการงานผู้สูงอายุ
                                      </div>
                                     
                                    </h4>
                                </div>
                                <div class="modal-body" style="padding-left: 30px">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">ซื่อเซอร์วิส (Service Name)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px; font-weight: bold; padding-top: 5px;"><input class="form-control" type="text" name="" placeholder="<?php echo $row1['wsrv_name'];?>"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">รหัสร้องขอ (Request Code)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px; padding-top: 5px;">
                                             <input type="text" class="form-control" name="" placeholder="<?php $tmp = $row1['req_code']==''?'N/A':$row1['req_code'];  echo $tmp; ?>" value="<?php $tmp = $row1['req_code']==''?'N/A':$row1['req_code'];  echo $tmp; ?>">   
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">รหัสตอบกลับ (Reply Code)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px; padding-top: 5px;">
                                           <input type="text" class="form-control" name="" placeholder="<?php $tmp = $row1['rep_code']==''?'N/A':$row1['rep_code'];  echo $tmp; ?>" value="<?php $tmp = $row1['rep_code']==''?'N/A':$row1['rep_code'];  echo $tmp; ?>">                       
                                        </div>
                                    </div>
                                    <div class="row" style="background-color: #D3D3D3;">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">คำอธิบาย (Definition)</div>
                                        <div class="col-xs-12 col-sm-9" style="background-color: #fff; padding: 15px; word-wrap: break-word; padding-top: 5px;"><input type="text" class="form-control" name="" placeholder="เช่น <?php echo $row1['wsrv_definition'];?>" value=""></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold; padding-top: 5px;">มาตรฐานการเชื่อม (Protocal)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px; padding-top: 0px;"><input type="text" class="form-control" name="" placeholder="เช่น <?php echo $row1['wsrv_protocal'];?>" value="<?php echo $row1['wsrv_protocal'];?>"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">ช่องทางเชื่อมต่อ (Port)</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px; padding-top: 5px;"><input type="text" class="form-control" name="" placeholder="เช่น <?php echo $row1['wsrv_port'];?>" value="<?php echo $row1['wsrv_port'];?>"></div>
                                    </div>

                                    <div class="row" style="background-color: #D3D3D3;">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">ลิงค์ (URL)</div>
                                        <div class="col-xs-12 col-sm-9" style="background-color: #fff; padding: 15px; padding-top:5px; word-wrap: break-word;"><input type="text" class="form-control" name="" placeholder="<?php echo $row1['wsrv_url'];?>" value=""></div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">เรียกเซอร์วิสผ่านทาง</div>
                                        <div class="col-xs-12 col-sm-9" style="padding: 15px; padding-top:5px;"><input type="text" class="form-control" name="" placeholder="<?php echo $row1['wsrv_via'];?>" value="<?php echo $row1['wsrv_via'];?>"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">การร้องขอ (Request)</div>
                                        <div class="col-xs-12 col-sm-9 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">เทมเพลต (Template)</div>
                                    </div>
                                    <div class="row" style="background-color: #D3D3D3;">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">&nbsp;</div>
                                        <div class="col-xs-12 col-sm-9" style="background-color: #fff; padding: 15px; word-wrap: break-word;"><input type="text" class="form-control" name="" placeholder="<?php echo $row1['wsrv_req_template'];?>" value="<?php echo $row1['wsrv_req_template'];?>"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">&nbsp;</div>
                                        <div class="col-xs-12 col-sm-4 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">ชื่อพารามิเตอร์ (Parameter)</div>
                                        <div class="col-xs-12 col-sm-5 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">คำอธิบาย (Definition)</div>
                                    </div>
                                    
                                   
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-4" style="padding: 5px;">
                                            <div class="row">
                                              <div class="col-xs-12 col-sm-5">
                                                   <input class="form-control" type="" name="" placeholder="ชื่อชุดข้อมูล">
                                              </div>
                                              <div class="col-xs-12 col-sm-7">
                                                   <input class="form-control" type="" name="" placeholder="ชื่อชุดข้อมูลต้นทาง">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-xs-12 col-sm-5" style="padding: 5px;"><input class="form-control" type="" name="" placeholder="คำอธิบาย"></div>

                                          
                                      </div>

                      
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">การตอบกลับ (Response)</div>
                                        <div class="col-xs-12 col-sm-4 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">ชื่อชุดข้อมูล (Element)</div>
                                        <div class="col-xs-12 col-sm-5 text-center" style="background-color: #eee;  padding: 15px; font-weight: bold">คำอธิบาย (Definition)</div>
                                    </div>
                                
                                      <div class="row">
                                          <div class="col-xs-12 col-sm-3" style="background-color: #D3D3D3; padding: 15px; font-weight: bold">&nbsp;</div>
                                          <div class="col-xs-12 col-sm-4" style="padding: 5px;">
                                            <div class="row">
                                              <div class="col-xs-12 col-sm-5">
                                                   <input class="form-control" type="" name="" placeholder="ชื่อชุดข้อมูล">
                                              </div>
                                              <div class="col-xs-12 col-sm-7">
                                                   <input class="form-control" type="" name="" placeholder="ชื่อชุดข้อมูลต้นทาง">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-xs-12 col-sm-5" style="padding: 5px;"><input class="form-control" type="" name="" placeholder="คำอธิบาย"></div>
                                      </div>
                                     
                                      <br>
                                       <div class="row">
                                         <div class="col-xs-12 text-center">
                                            <button type="button" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true" data-dismiss="modal"></i> บันทึก</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                              <i class="fa fa-ban" aria-hidden="true" data-dismiss="modal"></i> ยกเลิก</button>
                                       </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->


<script>
  function test_ck(wsrv_id) {
    //console.log($("textarea[name='wsrv_url["+wsrv_id+"]']").val());
    //console.log($("select[name='wsrv_protocal["+wsrv_id+"]']").val());
    var wsrv_url = $("textarea[name='wsrv_url["+wsrv_id+"]']").val();
    var wsrv_protocal = $("select[name='wsrv_protocal["+wsrv_id+"]']").val();
    var test_rs = $("textarea[name='test_rs["+wsrv_id+"]']").val();

    var elem_name = [];
    var elem_value = [];
    $.each($(".elem_name"+wsrv_id), function( index, value ) {
      elem_name[index] = $(value).data("name");
      elem_value[index] = $(value).val();
    });
    //console.log(elem_name);
    //console.log($("textarea[name='test_rs["+wsrv_id+"]']").val());

    if(wsrv_id=='undefined') {
      console.log('Undefined Value..');
    }else {
        $.ajax({
        url: 'https://gateway.dop.go.th/manage_transfer/test_importService',
        type: 'POST',
        dataType: 'json',
        data: {
            'wsrv_id':wsrv_id,
            'wsrv_url': wsrv_url,
            'wsrv_protocal': wsrv_protocal,
            'elem_name': elem_name,
            'elem_value':elem_value,
          //'csrf_dop': csrf_hash
        },
        success: function (value) { //Result True
            //console.log("success");console.dir(value);
            console.log(value);
            if(value.code=='0') {
              toastr.success("ทดสอบเซอร์วิสเสร็จสิ้น","หน้าต่างแจ้งเตือน");
            }else {
              toastr.warning("ไม่พบข้อมูลเซอร์วิส","หน้าต่างแจ้งเตือน");
            }
            $("textarea[name='test_rs["+wsrv_id+"]']").html('{code:"'+value.code+'",'+'message:"'+value.message+'"}');
          },
          error:function() { //Result Error
            toastr.error("ทดสอบเซอร์วิสล้มเหลว","หน้าต่างแจ้งเตือน");
          },
        });
    }
  }
</script>