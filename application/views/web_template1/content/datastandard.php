  
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
            <th>รายการมาตรฐาน</th>
            <th>จำนวน (รายการ)</th>
            <th>แหล่งข้อมูล</th>
            <th>ปรับปรุงล่าสุด</th>
            <th>&nbsp;</th>
        </tr>
			</thead>
      <tbody>
      <?php
      $number = 1;
      foreach ($info as $key => $value) {
      ?>
                <tr>
                    <td class="lnk"><?php echo $number;?></td>
                    <td class="lnk"><?php echo $value['std_db_table'].' : '.$value['std_title'];?></td>
                    <td class="lnk">
                    <?php 
                      $nums = $this->transfer_model->get_numRowsStd($value['std_db_table']);
                      echo number_format($nums);
                    ?>  
                    </td>
                    <td class="lnk"><?php echo $value['std_org'];?></td>
                    <td class="lnk"><?php echo dateChange($value['rec_update'],5); ?></td>
                    <td align="right">

                  
                  <a target="_blank" href="<?php echo site_url('rssfeed/'.$value['std_db_table']);?>.xml" title="Rss Feed" class="btn btn-default">
                      <i class="fa fa-link" aria-hidden="true" style="color: #000"></i>
                  </a> 
                  
                  
                  <a target="_blank" href="<?php echo base_url('assets/file/'.$value['std_db_table']);?>.xls" title="Export Excel" class="btn btn-default">
                      <i class="fa fa-file-excel-o" aria-hidden="true" style="color: #000"></i>
                  </a> 

                      <!-- Info Modal -->
                      <div class="modal fade" id="n<?php echo $number;?>" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                           <div class="modal-header" style="color: #fff; background-color: #293bb5;">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h3 class="modal-title text-left"><?php echo $value['std_db_table'].' : '.$value['std_title'];?></h3>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                  <div class="col-xs-12 col-sm-4"><h4>รายการมาตรฐาน</h4></div>
                                  <div class="col-xs-12 col-sm-8 text-left"> <?php echo $value['std_title'];?> </div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-12 col-sm-4"><h4>จำนวน (รายการ)</h4></div>
                                  <div class="col-xs-12 col-sm-8 text-left"> <?php echo number_format($nums);?> </div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-12 col-sm-4"><h4>แหล่งข้อมูล</h4></div>
                                  <div class="col-xs-12 col-sm-8 text-left"> <?php echo $value['std_org'];?> </div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-12 col-sm-4"><h4>ปรับปรุงล่าสุด</h4></div>
                                  <div class="col-xs-12 col-sm-8 text-left"> <?php echo formatDateThai($value['rec_update']);?> </div>
                              </div>
                              <div class="row" style="background-color: #96bbf3; color: #381616;">
                                  <div class="col-xs-12 col-sm-3 text-center" style="padding: 5px;"><h4>ชื่อชุดข้อมูล (Element)</h4></div>
                                  <div class="col-xs-12 col-sm-3 text-center" style="padding: 5px;"><h4>คำอธิบาย (Definition)</h4></div>
                                  <div class="col-xs-12 col-sm-3 text-center" style="padding: 5px;"><h4>ชนิดข้อมูล</h4></div>
                                  <div class="col-xs-12 col-sm-3 text-center" style="padding: 5px;"><h4>ตัวอย่างข้อมูล</h4></div>
                              </div>
                              <?php
                              $rows = $this->transfer_model->getAll_stdElement($value['std_id']);
                              foreach($rows as $key=>$row) {
                              ?>
                              <div class="row" style="background-color: #f7f8fc">
                                  <div class="col-xs-12 col-sm-3 text-left" style="padding: 3px;"><h4><?php echo $row['elem_name'];?></h4></div>
                                  <div class="col-xs-12 col-sm-3 text-left" style="padding: 3px;"><?php echo $row['elem_definition'];?></div>
                                  <div class="col-xs-12 col-sm-3 text-left" style="padding: 3px;"><?php echo $row['elem_data_type'];?></div>
                                  <div class="col-xs-12 col-sm-3 text-left" style="padding: 3px;"><?php echo $row['elem_data_example'];?></div>
                              </div>
                              <?php
                              }
                              ?>
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

