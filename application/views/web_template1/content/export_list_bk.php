<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li>
                    <a href="<?php echo site_url('manage_transfer/statistic_importlog');?>" href="#tab-2" <?php if(uri_seg(2)=='statistic_importlog'){?>aria-expanded="true" <?php }?>><i class="fa fa-download" aria-hidden="true"></i> ข้อมูลนำเข้า</a>
                </li>
                <li class="active">
                    <a href="<?php echo site_url('manage_transfer/statistic_exportlog');?>" data-toggle="tab" href="#tab-1" <?php if(uri_seg(2)=='statistic_exportlog'){?>aria-expanded="true" <?php }else{?> aria-expanded="false"<?php }?>><i class="fa fa-upload" aria-hidden="true"></i> ข้อมูลส่งออก</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="tab-1" <?php if(uri_seg(2)=='statistic_importlog'){?>class="tab-pane active" <?php }else{?> class="tab-pane"<?php }?>>
                    <div class="panel-body">
                        <strong>Tab-1</strong>
                    </div>
                </div>

                <div id="tab-2" <?php if(uri_seg(2)=='statistic_exportlog'){?>class="tab-pane active" <?php }else{?> class="tab-pane"<?php }?>>
                    <div class="panel-body">
                        <div class="row">
                           <!-- <div class="col-xs-6 col-sm-6">
                            <h3 style="color: #4e5f4d"><?php echo $title; ?></h3>
                           </div>
                           <div class="col-xs-6 col-sm-6 text-right"> -->
                           <div class="col-xs-12 col-sm-12 text-right">

                                <a style="color: #000; padding-left: 20px; padding-right: 20px;" title="ส่งออกไฟล์ XLS" class="btn btn-default">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                </a>
                                &nbsp;
                                <a style="color: #000; padding-left: 20px; padding-right: 20px;" title="ค้นหา" class="btn btn-default" data-toggle="modal" data-target="#mySearch">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                </a>

                           </div>
                        </div>
                        
                        <div class="table-responsive">
                          <table id="dtable" class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead style="color: #333; font-wight: bold; font-size: 15px; font-weight: bold;">
                              <tr>
                                  <th>วันที่ทำรายการ</th>
                                  <th>รหัสผู้ใช้งาน</th>
                                  <th>ชื่อผู้ใช้งาน</th>
                                  <th>รายการที่ทำ</th>
                                  <th>ชื่อองค์กร</th>
                                  <th>สถานะ</th>
                                  <th>หมายเหตุ</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($info as $i => $value) { ?>
                              <tr>
                                <td class="lnk"><?php echo dateChange($value['req_datetime'],5); ?></td>
                                <td class="lnk"><?php echo $value['pid']; ?></td>
                                <td class="lnk"><?php echo $value['name']; ?></td>
                                <td class="lnk"><?php echo $value['wsrv_name']; ?></td>
                                <td class="lnk"><?php echo $value['owner_org']; ?></td>
                                <td class="lnk">
                                  <?php if($value['log_status'] == 'Success'){ ?>
                                    <font color="green"><?php echo $value['log_status']; ?></font>
                                  <?php }else{ ?>
                                    <font color="red"><?php echo $value['log_status']; ?></font>
                                  <?php } ?>
                                </td>
                                <td class="lnk">
                                          <?php 
                                            $tmp = unserialize($value['log_note']);
                                            if($value['log_status']=='Success' && isset($tmp['result'])) {
                                              echo @$tmp['result']['pid'];   
                                            }else {
                                              echo @$tmp['result']['pid'].' Error code: '.@$tmp['result']['code'];
                                            }
                                          ?>
                                </td>
                              </tr>

                              <!-- Info Modal -->
                              <div class="modal fade" id="<?php echo $i+1;?>" role="dialog">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                   <div class="modal-header" style="background-color: #eee">
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                      <h3 class="modal-title text-left">รายละเอียด</h3>
                                    </div>
                                    <div class="modal-body">
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>วันที่ทำรายการ</h4></div>
                                        <div class="col-xs-12 col-sm-8"><?php echo formatDateThai($value['req_datetime']);?></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>รหัสผู้ใช้งาน</h4></div>
                                        <div class="col-xs-12 col-sm-8"><?php echo $value['pid']; ?></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>ชื่อผู้ใช้งาน</h4></div>
                                        <div class="col-xs-12 col-sm-8"><?php echo $value['name']; ?></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>รายการที่ทำ</h4></div>
                                        <div class="col-xs-12 col-sm-8"><?php echo $value['wsrv_name']; ?></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>ชื่อองค์กร</h4></div>
                                        <div class="col-xs-12 col-sm-8"><?php echo $value['owner_org']; ?></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>สถานะ</h4></div>
                                        <div class="col-xs-12 col-sm-8">
                                          <?php if($value['log_status'] == 'Success'){ ?>
                                            <font color="green"><?php echo $value['log_status']; ?></font>
                                          <?php }else{ ?>
                                            <font color="red"><?php echo $value['log_status']; ?></font>
                                          <?php } ?>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>หมายเหตุ</h4></div>
                                        <div class="col-xs-12 col-sm-8">
                                          <?php 
                                            //$tmp = unserialize($value['log_note']);
                                            if($value['log_status']=='Success' && isset($tmp['result'])) {
                                              echo $tmp['result']['pid'];                            
                                            }else {
                                              echo $tmp['result']['pid'].' Error code: '.$tmp['result']['code'];
                                            }
                                          ?>
                                          </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- End Info Modal -->

                            <?php } ?>
                            </tbody>
                          </table>
                        </div>

                    </div>
                </div>

            </div>


        </div>
    </div>
</div>


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
      <?php echo form_open(); ?>
        <label for="input1">รายการ:</label>
        <input class="form-control" type="text" name="posi_title"><br>

        <label for="input2">วันที่ทำรายการ:</label>
        <div class="input-group input-daterange" >
            <input type="text" class="form-control" data-date-format="dd-mm-yyyy" name="date_of_post[st]" value="">
            <div class="input-group-addon">ถึง</div>
            <input type="text" class="form-control" data-date-format="dd-mm-yyyy" name="date_of_post[ed]" value="">
        </div><br>
        <script type="text/javascript">
          $('.input-daterange input').each(function() {
              $(this).datepicker({
                autoclose: true, 
                todayHighlight: true
              });
          });
        </script>

        <label for="input3">ชื่อองค์กร:</label>
        <input type="text" class="form-control" name="org_title">

        <input type="submit" name="bt_submit" hidden="hidden">
        <?php echo form_close(); ?>
      </div>
       <div class="modal-footer">
        <button id="searchBtn" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-search" aria-hidden="true"></i> ตกลง</button> 
       </div>
    </div>
      
  </div>
</div>
<!-- End Search Modal -->


