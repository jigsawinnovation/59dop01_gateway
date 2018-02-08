
  <div class="row"> 
  		<div class="col-xs-12">

		<?php
			$addr_gps ='13.5847536,100.85180889999992'; // Old Data $diff_info['addr_gps']

			if($addr_gps=='') {
				$addr_gps ='0,0'; // Set Default Data
			}
			$arr = explode(',',$addr_gps);
		?>

		<script type="text/javascript">
			var latitude = '<?php echo $arr[0];?>';
			var longitude = '<?php echo $arr[1];?>';
			var marker_img = '<?php echo path('map-marker.png','webconfig');?>';
		</script>
		  <!-- Trigger the modal with a button -->
		  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modal_marker">
		  	<i class="fa fa-map-marker" aria-hidden="true"></i> Search Location
		   </button>

			&nbsp;
	    	<input type="hidden" name="addr_gps" value="<?php echo $addr_gps;?>" id="addr_gps"> 
	    	<span id="addr_gpg_txt">(<?php if($addr_gps!='0,0') { echo $addr_gps;}?>)</span> 

	   </div>
   </div>


  <!-- Modal -->
  <div class="modal fade" id="modal_marker" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: rgb(56,145,209);color: white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-map-marker" aria-hidden="true"></i> Search Location</h4>
        </div>
        <div class="modal-body">

			<form name="form_search" method="post" action="">

			<b>Location</b>
				<div class="row">
					<div class="col-xs-12 col-sm-10">
			  			<input name="namePlace" class="form-control" size="70" type="text" id="namePlace" size="30" />
			  			<input type="hidden" name="address" id="namePlace2">
					</div>
					<div class="col-xs-12 col-sm-2">
			  			<input type="button" class="btn btn-default" style="width: 100%" name="SearchPlace" id="SearchPlace" value="Search" />
			  		</div>
				</div>
			 </form>

			<hr />

			<form id="form_get_detailMap" name="form_get_detailMap" method="post" action="">
				<div class="row">
					<div class="col-xs-6 col-sm-5">
						Latitude <input class="form-control" name="lat_value" type="text" id="lat_value" value="0" size="20" readonly /> 
					</div>
					<div class="col-xs-6 col-sm-5">
						Longitude <input class="form-control" name="lon_value" type="text" id="lon_value" value="0" size="20" readonly />
					</div>
					<div class="col-xs-12 col-sm-2">
						<input type="button" class="btn btn-default" style="margin-top: 15px; width: 100%" name="button" id="button" onclick="select_location();" value="Save" />
			  		</div>
			  	</div>
			</form>  

			<div class="row">
				<div class="col-xs-12 col-sm-12">
					<!-- show map -->
					<div id="map_canvas" style="width:100%;height:400px;margin:auto;margin-top:10px;"></div>
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
