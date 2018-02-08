<?php
$usrpm['app_parent_id'] = isset($usrpm['app_parent_id'])?$usrpm['app_parent_id']:0;
$usrpm['app_id'] = isset($usrpm['app_id'])?$usrpm['app_id']:0;
//dieFont($usrpm['app_parent_id'])
?>

<ul class="dropdown-menu head_menu" style="font-size: 18px">

      <?php
      $tmp = $this->admin_model->getOnce_Application(101);   
      $tmp1 = $this->admin_model->chkOnce_usrmPermiss(101,get_session('user_id')); //Check User Permission
      ?>      
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
       	<a href="<?php echo site_url('manage_transfer/import');?>" style="font-size: 22px;"><i class="fa <?php if(!isset($tmp1['perm_status'])) { ?>fa-lock<?php }?>" aria-hidden="true"></i> <?php if(isset($tmp1['perm_status'])) {echo $tmp1['app_name']; }?>
       	</a>
      </li>

      <?php
      $tmp = $this->admin_model->getOnce_Application(102);   
      $tmp1 = $this->admin_model->chkOnce_usrmPermiss(102,get_session('user_id')); //Check User Permission
      ?>      
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
        <a href="<?php echo site_url('manage_transfer/export');?>" style="font-size: 22px;"><i class="fa <?php if(!isset($tmp1['perm_status'])) { ?>fa-lock<?php }?>" aria-hidden="true"></i> <?php if(isset($tmp1['perm_status'])) {echo $tmp1['app_name']; }?>
        </a>
      </li>
    
      <li 
      <?php if(uri_seg(2)=='datastandard') { ?>
          class="active"
      <?php
        }
      ?>
       >
        <a href="<?php echo site_url('manage_transfer/datastandard');?>" style="font-size: 22px;"><i class="fa <?php if(!isset($tmp1['perm_status'])) { ?>fa-lock<?php }?>" aria-hidden="true"></i> ชุดข้อมูลมาตรฐาน (Data Standard)
        </a>
      </li>

      <?php
      $tmp = $this->admin_model->getOnce_Application(103);   
      $tmp1 = $this->admin_model->chkOnce_usrmPermiss(103,get_session('user_id')); //Check User Permission
      ?>      
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
        <a href="<?php echo site_url('manage_transfer/authen_key');?>" style="font-size: 22px;"><i class="fa <?php if(!isset($tmp1['perm_status'])) { ?>fa-lock<?php }?>" aria-hidden="true"></i> <?php if(isset($tmp1['perm_status'])) {echo $tmp1['app_name']; }?>
        </a>
      </li>

      <li 
      <?php if(uri_seg(2)=='statistic_importlog') { ?>
          class="active"
      <?php
        }
      ?>
       >
        <a href="<?php echo site_url('manage_transfer/statistic_importlog');?>" style="font-size: 22px;"><i class="fa <?php if(!isset($tmp1['perm_status'])) { ?>fa-lock<?php }?>" aria-hidden="true"></i> สถิติข้อมูลนำเข้า
        </a>
      </li>
      <li 
      <?php if(uri_seg(2)=='statistic_exportlog') { ?>
          class="active"
      <?php
        }
      ?>
       >
        <a href="<?php echo site_url('manage_transfer/statistic_exportlog');?>" style="font-size: 22px;"><i class="fa <?php if(!isset($tmp1['perm_status'])) { ?>fa-lock<?php }?>" aria-hidden="true"></i> สถิติข้อมูลส่งออก
        </a>
      </li>

      <?php
      $tmp = $this->admin_model->getOnce_Application(104);   
      $tmp1 = $this->admin_model->chkOnce_usrmPermiss(104,get_session('user_id')); //Check User Permission
      ?>      
      <li 
      <?php
      if(!isset($tmp1['perm_status'])) { ?>
      class="disabled" 
    <?php 
    }else if($usrpm['app_id']==104) {
    ?>
          class="active"
      <?php
        }
      ?>
       >
        <a href="<?php echo site_url('manage_transfer/data_warehouse');?>" style="font-size: 22px;"><i class="fa <?php if(!isset($tmp1['perm_status'])) { ?>fa-lock<?php }?>" aria-hidden="true"></i> <?php if(isset($tmp1['perm_status'])) {echo $tmp1['app_name']; }?>
        </a>
      </li>

      <li style="border: 1px #eee solid;"></li>
    
      <li>
        <a href="<?php echo site_url('');?>" style="font-size: 22px;"><i class="fa" aria-hidden="true"></i> 
          เมนูหลัก
        </a>
      </li>
</ul>
                



