  
    <div id="tmp_menu" hidden='hidden'>
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

  <div class="table-responsive">

    <table id="dtable" class="table table-striped table-bordered table-hover dataTables-example" >
      <thead style="color: #333; font-wight: bold; font-size: 15px; font-weight: bold;">
        <tr>
            <th>#</th>
            <th>รายการข้อมูล</th>
            <th>แหล่งข้อมูล</th>
            <th>จำนวน (รายการ)</th>
            <th>ปรับปรุงล่าสุด</th>
            <th>&nbsp;</th>
        </tr>
			</thead>
      <tbody>
      
      <?php

      //
      $tmp = $this->db_gateway->query("select count(rec_update),rec_update from dtawh_edoe_job_vacancy")->result_array();
      $info[0] = $tmp[0];
      $info[0]['title'] = 'Data Warehouse ข้อมูลส่งเสริมการจ้างงาน (ตำแหน่งงานว่าง)';
      $info[0]['num'] = $tmp[0]['count(rec_update)'];
      $info[0]['src'] = 'กรมการจัดหางาน กระทรวงแรงงาน';
      $info[0]['update'] = formatDateThai($tmp[0]['rec_update']);
      if($tmp[0]['rec_update']!='') {
        $arr=explode(' ',$tmp[0]['rec_update']);
        $info[0]['update'] = formatDateThai($tmp[0]['rec_update']).' '.$arr[1];
      }

      $tmp = $this->db_gateway->query("select count(rec_update),rec_update from dtawh_pers_qol_indicators")->result_array();
      $info[1] = $tmp[0];
      $info[1]['title'] = 'Data Warehouse ข้อมูลกลางทะเบียนประวัติ (ดัชนีจำแนกตัวชี้วัดคุณภาพชีวิตของประชาชนในชนบท ตามเกณฑ์ความจำเป็นพื้นฐาน (จปฐ.))';
      $info[1]['num'] = $tmp[0]['count(rec_update)'];
      $info[1]['src'] = 'กรมการพัฒนาชุมชน กระทรวงมหาดไทย';
      $info[1]['update'] = formatDateThai($tmp[0]['rec_update']);
      if($tmp[0]['rec_update']!='') {
        $arr=explode(' ',$tmp[0]['rec_update']);
        $info[0]['update'] = formatDateThai($tmp[0]['rec_update']).' '.$arr[1];
      }

      $tmp = $this->db_gateway->query("select count(rec_update),rec_update from dtawh_stat_thai_elderly_foundation")->result_array();
      $info[2] = $tmp[0];
      $info[2]['title'] = 'Data Warehouse ข้อมูลสถิติการกู้ยืมกองทุนผู้สูงอายุ';
      $info[2]['num'] = $tmp[0]['count(rec_update)'];
      $info[2]['src'] = 'กรมกิจการผู้สูงอายุ กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์';
      $info[2]['update'] = formatDateThai($tmp[0]['rec_update']);
      if($tmp[0]['rec_update']!='') {
        $arr=explode(' ',$tmp[0]['rec_update']);
        $info[0]['update'] = formatDateThai($tmp[0]['rec_update']).' '.$arr[1];
      }

      $tmp = $this->db_gateway->query("select count(stat_id) from dtawh_stat_thai_population")->result_array();
      $info[3] = $tmp[0];
      $info[3]['title'] = 'Data Warehouse ข้อมูลสถิติประชากรไทยจากการทะเบียน จำแนกรายอายุ';
      $info[3]['num'] = $tmp[0]['count(stat_id)'];
      $info[3]['src'] = 'กรมการปกครอง กระทรวงมหาดไทย';
      $info[3]['update'] = "";

      $number = 1;
      foreach ($info as $key => $value) {
      ?>

                <tr>
                    <td class="lnk"><?php echo $number;?></td>
                    <td class="lnk"><?php echo $value['title'];?></td>
                    <td class="lnk"><?php echo $value['src'];?></td>
                    <td class="lnk"><?php echo number_format($value['num']);?></td>
                    <td class="lnk"><?php echo $value['update'];?></td>
                    <td align="right">
                  
                  <a target="_blank" href="<?php echo base_url('assets/file/'.'dtawh'.$number);?>.xls" title="Export Excel" class="btn btn-default">
                      <i class="fa fa-file-excel-o" aria-hidden="true" style="color: #000"></i>
                  </a> 

                      <!-- Info Modal -->
                      <div class="modal fade" id="n<?php echo $number;?>" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                           <div class="modal-header" style="color: #fff; background-color: #293bb5;">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h3 class="modal-title text-left"><?php echo $value['std_db_table'].' : '.$value['title'];?></h3>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                  <div class="col-xs-12 col-sm-4"><h4>รายการข้อมูล</h4></div>
                                  <div class="col-xs-12 col-sm-8 text-left"> <?php echo $value['std_title'];?> </div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-12 col-sm-4"><h4>แหล่งข้อมูล</h4></div>
                                  <div class="col-xs-12 col-sm-8 text-left"> <?php echo $value['src'];?> </div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-12 col-sm-4"><h4>จำนวน (รายการ)</h4></div>
                                  <div class="col-xs-12 col-sm-8 text-left"> <?php echo number_format($value['num']);?> </div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-12 col-sm-4"><h4>ปรับปรุงล่าสุด</h4></div>
                                  <div class="col-xs-12 col-sm-8 text-left"> <?php echo $value['update'];?> </div>
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

