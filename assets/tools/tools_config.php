<!-- *************** inspinia-3 Template: CSS *************** -->
<?php echo css_asset('../plugins/Static_Full_Version/css/bootstrap.min.css'); ?>
<?php echo css_asset('../plugins/Static_Full_Version/font-awesome/css/font-awesome.css'); ?>
<?php echo css_asset('../plugins/Static_Full_Version/css/animate.css'); ?>
<?php echo css_asset('../plugins/Static_Full_Version/css/style.css'); ?>
<!-- *************** End inspinia-3 Template: CSS *************** -->

<!-- *************** inspinia-3 Template: JS *************** ----->
<?php echo js_asset('../plugins/Static_Full_Version/js/jquery-3.1.1.min.js'); ?>
<!-- *************** End inspinia-3 Template: JS *************** -->

<?php
  if(isset($this->template->js_assets_head))
    foreach ($this->template->js_assets_head as $key => $data) {
      echo $data;
    }
?>
<?php
  if(isset($this->template->css_assets_head))
    foreach ($this->template->css_assets_head as $key => $data) {
      echo $data;
    }
?>

<?php
  echo css_asset('./fontsset.css');
  echo css_asset('./main.css');
?>

<script>
  var base_url = "<?php echo base_url();?>"; //Set Base URL

  var toastr_type = null; //Set toast type
  var toastr_msg = null; //Set toast message

  //Set Alert Notifications
<?php
  if(isset($msg)) {
?>
  //set inizial toastr
  toastr_type = '<?php echo $msg['msg_type']?>';
  toastr_msg = '<?php echo $msg['msg_title']?>';
<?php 
  }
?>  
  //End Set Alert Notifications


  var frmKey = true;//Set Form key
</script>
<style tyle="text/css">
  .tooltip > .tooltip-inner {font-size:20px; background-color: #f00;}
  .tooltip > .tooltip-arrow { border-bottom-color:#f00; }

  #toast-container > .toast:before {
      font-size: 35px;
      margin: 14px 0em 5px -1.2em;
  }

  .toast-title {
   font-size: 20px;
  }
  .toast-message {
    font-size: 18px;
  }
</style>