<?php
$usrpm['app_parent_id'] = isset($usrpm['app_parent_id'])?$usrpm['app_parent_id']:0;
$usrpm['app_id'] = isset($usrpm['app_id'])?$usrpm['app_id']:0;
//dieFont($usrpm['app_parent_id'])
?>

<?php
  //ระบบนำเข้าข้อมูล (Data Importing System)
  if($usrpm['app_id']==101 || $usrpm['app_id']==102 || $usrpm['app_id']==103 || $usrpm['app_id']==104 || uri_seg(2)=='datastandard' || uri_seg(2)=='webservice_demo' || uri_seg(2)=='data_warehouse' || uri_seg(2)=='webservice_list') {
?>
  <a class="navbar-minimalize minimalize-styl-2 dropdown-toggle" data-toggle="dropdown" style=" border: 0;font-size: 17px; padding: 4px 10px 0px 0px;margin-left:10px;  color: #ccc;" href="javascript:void(0)"><i class="fa fa-bars"></i></a>

<?php
  }
?>

<?php 
  //ระบบฐานข้อมูลการส่งเสริมการจ้างงานผู้สูงอายุ
  if(uri_seg(2)=='statistic_importlog' || uri_seg(2)=='statistic_exportlog'){ ?>
<a class="navbar-minimalize minimalize-styl-2 dropdown-toggle" data-toggle="dropdown" style=" border: 0;font-size: 17px; padding: 4px 10px 0px 0px;margin-left:10px;  color: #333;" href="javascript:void(0)"><i class="fa fa-bars"></i></a>
<ul class="dropdown-menu head_menu">
      <li 
      <?php
      if(uri_seg(2)!='statistic_importlog') { ?>
   
    <?php 
    }else if(uri_seg(2)=='statistic_importlog') {
    ?>
          class="active"
      <?php
        }
      ?>
       >
        <a href="<?php echo site_url('manage_transfer/statistic_importlog');?>"><i class="fa fa-upload" aria-hidden="true"></i> ข้อมูลนำเข้า
        </a>
      </li>

      <li 
      <?php
      if(uri_seg(2)!='statistic_exportlog') { ?>
  
    <?php 
    }else if(uri_seg(2)=='statistic_exportlog') {
    ?>
          class="active"
      <?php
        }
      ?>
       >
        <a href="<?php echo site_url('manage_transfer/statistic_exportlog');?>"><i class="fa fa-download" aria-hidden="true"></i> ข้อมูลส่งออก
        </a>
      </li>
  </ul>
  <?php } ?>

                



