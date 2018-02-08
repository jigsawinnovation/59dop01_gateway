<ul class="dropdown-menu" style="margin-left: 92px; margin-top: -22px; font-size: 15px; background-color: rgba(0,0,0,0.6); !important; color: #fff">

<?php
	if(@$usrpm['app_parent_id']==101) {
?>             
      <?php
      //$tmp = $this->admin_model->getOnce_Application(101);   
      //$tmp1 = $this->admin_model->chkOnce_usrmPermiss(101,get_session('user_id')); //Check User Permission
      ?>      
<!--
      <li 
      <?php
      if(!isset($tmp1['perm_status'])) { ?>
		  class="disabled" 
	  <?php 
		}else if($usrpm['app_id']==101) {
	  ?>
          class="active"
      <?php
      	}
      ?>
       >
       	<a href="<?php echo site_url('difficult/assist_list');?>"><i class="fa fa-table" aria-hidden="true"></i> <?php if(isset($tmp1['perm_status'])) {echo $tmp1['app_name']; }?>
       	</a>
      </li>
-->
<?php
	}
?>

<?php
  if(@$usrpm['app_parent_id']==102) {
?>             
      <?php
      //$tmp = $this->admin_model->getOnce_Application(102);   
      //$tmp1 = $this->admin_model->chkOnce_usrmPermiss(102,get_session('user_id')); //Check User Permission
      ?>      
<!--
      <li 
      <?php
      if(!isset($tmp1['perm_status'])) { ?>
      class="disabled" 
    <?php 
    }else if($usrpm['app_id']==102) {
    ?>
          class="active"
      <?php
        }
      ?>
       >
        <a href="<?php echo site_url('difficult/assist_list');?>"><i class="fa fa-table" aria-hidden="true"></i> <?php if(isset($tmp1['perm_status'])) {echo $tmp1['app_name']; }?>
        </a>
      </li>
-->
<?php
  }
?>

<?php
  if(@$usrpm['app_parent_id']==103) {
?>             
      <?php
      //$tmp = $this->admin_model->getOnce_Application(103);   
      //$tmp1 = $this->admin_model->chkOnce_usrmPermiss(103,get_session('user_id')); //Check User Permission
      ?>      
<!--
      <li 
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
        <a href="<?php echo site_url('difficult/assist_list');?>"><i class="fa fa-table" aria-hidden="true"></i> <?php if(isset($tmp1['perm_status'])) {echo $tmp1['app_name']; }?>
        </a>
      </li>
-->
<?php
  }
?>  

<?php
  if(uri_seg(2)=='statistic_importlog' || uri_seg(2)=='statistic_exportlog') {
?>             
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
<?php
  }
?>  

</ul>
                



