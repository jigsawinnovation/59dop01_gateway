<!-- *************** inspinia-3 Template: JS *************** ----->
      <!-- Mainly scripts -->
<?php echo js_asset('../plugins/Static_Full_Version/js/bootstrap.min.js'); ?>
<?php echo js_asset('../plugins/Static_Full_Version/js/plugins/metisMenu/jquery.metisMenu.js'); ?>
<?php echo js_asset('../plugins/Static_Full_Version/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>
      <!-- Custom and plugin javascript -->
<?php echo js_asset('../plugins/Static_Full_Version/js/inspinia.js'); ?>
<?php echo js_asset('../plugins/Static_Full_Version/js/plugins/pace/pace.min.js'); ?>
<?php echo js_asset('../plugins/Static_Full_Version/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>
<!-- *************** End inspinia-3 Template: JS *************** -->

<!-- *************** Set Format Plugin *************** -->
<?php echo js_asset('../plugins/setformat/jquery.number.min.js'); ?>
<?php echo js_asset('../plugins/setformat/jquery.maskedinput.min.js'); ?>
<?php echo js_asset('../plugins/setformat/jquery.setformat.js'); ?>
<!-- *************** End Set Format Plugin *************** -->

<?php
  if(isset($this->template->js_assets_footer))
  foreach ($this->template->js_assets_footer as $key => $data) {
    echo $data;
  }
?>
<?php
	if(isset($this->template->css_assets_footer))
    foreach ($this->template->css_assets_footer as $key => $data) {
      echo $data;
    }
?>

<script>

$(document).ready(function(){
  setTimeout(function(){
    $("div.wrapper.wrapper-content").removeClass("animated");
    $("div.wrapper.wrapper-content").removeClass("fadeInRight");
  },1000);
});

//alert toastr message
if(toastr_msg!=null) {
  setTimeout(function() {
      toastr.options = {
          closeButton: true,
          progressBar: true,
          showMethod: 'slideDown',
          timeOut: 3000
      };
      switch(toastr_type) {
        case 'Success': toastr.success(toastr_msg, "หน้าต่างแจ้งเตือน"); break; 
        case 'Error': toastr.error(toastr_msg, "หน้าต่างแจ้งเตือน"); break; 
        case 'Warning': toastr.warning(toastr_msg, "หน้าต่างแจ้งเตือน"); 
      }
  }, 1500);
}

</script>