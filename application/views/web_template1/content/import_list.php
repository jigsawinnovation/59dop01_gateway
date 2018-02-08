<script>
  var dtable_url = '<?php echo site_url('manage_transfer/statistic_importlog_list');?>';
  var csrf_hash='<?php echo @$csrf['hash'];?>';
</script>


<div id="tmp_menu" hidden='hidden'>

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

<div class="row">
    <div class="col-lg-12">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="<?php echo site_url('manage_transfer/statistic_importlog');?>" data-toggle="tab" href="#tab-1" <?php if(uri_seg(2)=='statistic_importlog'){?>aria-expanded="true" <?php }else{?> aria-expanded="false"<?php }?>><i class="fa fa-download" aria-hidden="true"></i> ข้อมูลนำเข้า</a>
                </li>
                <li>
                    <a href="<?php echo site_url('manage_transfer/statistic_exportlog');?>" href="#tab-2" <?php if(uri_seg(2)=='statistic_exportlog'){?>aria-expanded="true" <?php }?>><i class="fa fa-upload" aria-hidden="true"></i> ข้อมูลส่งออก</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="tab-1" <?php if(uri_seg(2)=='statistic_importlog'){?>class="tab-pane active" <?php }else{?> class="tab-pane"<?php }?>>
                    <div class="panel-body">
                  
                        
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
                                  <th> </th>
                              </tr>
                            </thead>
                            <tbody>

                            </tbody>
                          </table>
                        </div>

                    </div>
                </div>

                <div id="tab-2" <?php if(uri_seg(2)=='statistic_exportlog'){?>class="tab-pane active" <?php }else{?> class="tab-pane"<?php }?>>
                    <div class="panel-body">
                        <strong>Tab-2</strong>
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>


                              <!-- Info Modal -->
                              <div class="modal fade" id="info" role="dialog">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                   <div class="modal-header" style="background-color:#459597; color: #fff">
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                      <h3 class="modal-title text-left">รายละเอียด</h3>
                                    </div>
                                    <div class="modal-body">
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>วันที่ทำรายการ</h4></div>
                                        <div class="col-xs-12 col-sm-8 req_datetime"></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>ระยะเวลาประมวลผล</h4></div>
                                        <div class="col-xs-12 col-sm-8 end_datetime"></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>รหัสผู้ใช้งาน</h4></div>
                                        <div class="col-xs-12 col-sm-8 pid"></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>ชื่อผู้ใช้งาน</h4></div>
                                        <div class="col-xs-12 col-sm-8 name"></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>รายการที่ทำ</h4></div>
                                        <div class="col-xs-12 col-sm-8 wsrv_name"></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>ชื่อองค์กร</h4></div>
                                        <div class="col-xs-12 col-sm-8 owner_org"></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>ลิงค์ (URL)</h4></div>
                                        <div class="col-xs-12 col-sm-8 wsrv_url" style="word-wrap: break-word;"></div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>เรียกเซอร์วิสผ่านทาง</h4></div>
                                        <div class="col-xs-12 col-sm-8 wsrv_via"></div>
                                      </div>
                                      
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>สถานะ</h4></div>
                                        <div class="col-xs-12 col-sm-8 log_status">
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-4"><h4>หมายเหตุ</h4></div>
                                        <div class="col-xs-12 col-sm-8 txt_result">
                                          </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- End Info Modal -->

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
        
        <label for="col1_filter">รหัสผู้ใช้งาน:</label>
        <input data-column="1" type="text" class="form-control column_filter" id="col1_filter">

        <label for="col3_filter">รายการ:</label>
        <input data-column="3" class="form-control column_filter" type="text" name="posi_title" id="col3_filter">
        
        <label for="col4_filter">ชื่อองค์กร:</label>
        <input data-column="4" type="text" class="form-control column_filter" name="org_title" id="col4_filter">

        <label for="input2">วันที่ทำรายการ:</label>
        <div class="input-group input-daterange" >
            <input type="text" class="form-control" data-date-format="dd-mm-yyyy" name="date_of_post[st]" value="">
            <div class="input-group-addon">ถึง</div>
            <input type="text" class="form-control" data-date-format="dd-mm-yyyy" name="date_of_post[ed]" value="">
        </div><br>
        <script type="text/javascript">
/*          $('.input-daterange input').each(function() {
              $(this).datepicker({
                autoclose: true, 
                todayHighlight: true
              });
          });*/
        </script>

        

        <input type="submit" name="bt_submit" hidden="hidden">
        
      </div>
       <div class="modal-footer">
        <button id="filter" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-search" aria-hidden="true"></i> ตกลง</button> 
       </div>
    </div>
      
  </div>
</div>
<!-- End Search Modal -->


