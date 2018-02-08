<?php
	$site = $this->webinfo_model->getSiteInfo(); 
?>
<rss version="2.0">
	<channel>
	<?php
		$row = $this->db_gateway->query("select * from wsrv_std where std_db_table='{$table}'")->result_array();
		
		$temp = $this->common_model->custom_query("select * from {$table}");

		if(!isset($row[0]['std_id'])) {
	?>
		<title><?php echo $site['site_name'];?></title>
		<description>ไม่พบข้อมูล</description>
	<?php
	}else {
	?>
		<title><?php echo $row[0]['std_title'];?></title>
		<table><?php echo $row[0]['std_db_table'];?></table>
		<pubDate><?php echo dateChange($row[0]['rec_update'],5);?></pubDate>
		<description><?php echo $row[0]['std_org'];?></description>
		<author>dop@dmin</author>
		<link> <?php echo site_url('rss_feed/'.$table.'.xml');?></link>
		<?php
			foreach($temp as $key=>$data){
		?>
			<item>
				<?php
				if($table=='std_alley') {
				?>
					<id><?php echo $data['alley_id'];?></id>
					<code><?php echo $data['alley_code'];?></code>
					<name><?php echo $data['alley_name'];?></name>
				<?php
				}else if($table=='std_area') {
				?>
					<id><?php echo $data['area_id'];?></id>
					<code><?php echo $data['area_code'];?></code>
					<nameth><?php echo $data['area_name_th'];?></nameth>
					<nameen><?php echo $data['area_name_en'];?></nameen>
					<type><?php echo $data['area_type'];?></type>
					<region><?php echo $data['region'];?></region>
					<latitude><?php echo $data['region'];?></latitude>
					<longitude><?php echo $data['region'];?></longitude>
					<gshape><?php echo $data['g_shape'];?></gshape>
				<?php
				}else if($table=='std_case_reason') {
				?>
					<id><?php echo $data['case_reason_id'];?></id>
					<code><?php echo $data['case_reason_code'];?></code>
					<name><?php echo $data['case_reason_name'];?></name>
					<type><?php echo $data['case_reason_type'];?></type>
				<?php
				}else if($table=='std_cause_of_death') {
				?>
					<id><?php echo $data['cause_id'];?></id>
					<code><?php echo $data['cause_code'];?></code>
					<death><?php echo $data['cause_of_death'];?></death>
				<?php
				}else if($table=='std_disability') {
				?>
					<id><?php echo $data['dis_id'];?></id>
					<code><?php echo $data['dis_code'];?></code>
					<title><?php echo $data['dis_title'];?></title>
				<?php
				}else if($table=='std_dkm_cate') {
				?>
					<id><?php echo $data['dkm_cate_id'];?></id>
					<parentid><?php echo $data['dkm_cate_parent_id'];?></parentid>
					<code><?php echo $data['dkm_cate_code'];?></code>
					<name><?php echo $data['dkm_cate_name'];?></name>
				<?php
				}else if($table=='std_edu_level') {
				?>
					<id><?php echo $data['edu_id'];?></id>
					<code><?php echo $data['edu_code'];?></code>
					<title><?php echo $data['edu_title'];?></title>
				<?php
				}else if($table=='std_expert') {
				?>
					<id><?php echo $data['exp_id'];?></id>
					<code><?php echo $data['exp_code'];?></code>
					<name><?php echo $data['exp_name'];?></name>
				<?php
				}else if($table=='std_gender') {
				?>
					<id><?php echo $data['gender_id'];?></id>
					<code><?php echo $data['gender_code'];?></code>
					<name><?php echo $data['gender_name'];?></name>
				<?php
				}else if($table=='std_help') {
				?>
					<id><?php echo $data['help_id'];?></id>
					<code><?php echo $data['help_code'];?></code>
					<title><?php echo $data['help_title'];?></title>
				<?php
				}else if($table=='std_help_guide') {
				?>
					<id><?php echo $data['help_guide_id'];?></id>
					<code><?php echo $data['help_guide_code'];?></code>
					<title><?php echo $data['help_guide_title'];?></title>
				<?php
				}else if($table=='std_home_condition') {
				?>
					<id><?php echo $data['hcond_id'];?></id>
					<code><?php echo $data['hcond_code'];?></code>
					<title><?php echo $data['hcond_title'];?></title>
				<?php
				}else if($table=='std_irp') {
				?>
					<id><?php echo $data['qstn_id'];?></id>
					<pid><?php echo $data['qstn_pid'];?></pid>
					<type><?php echo $data['qstn_type'];?></type>
					<title><?php echo $data['qstn_title'];?></title>
					<weight><?php echo $data['dim_weight'];?></weight>
					<score><?php echo $data['ans_full_score'];?></score>
				<?php
				}else if($table=='std_lane') {
				?>
					<id><?php echo $data['lane_id'];?></id>
					<code><?php echo $data['lane_code'];?></code>
					<name><?php echo $data['lane_name'];?></name>
				<?php
				}else if($table=='std_model_school') {
				?>
					<id><?php echo $data['mdl_id'];?></id>
					<code><?php echo $data['mdl_code'];?></code>
					<grp><?php echo $data['mdl_grp'];?></grp>
					<title><?php echo $data['mdl_title'];?></title>
				<?php
				}else if($table=='std_msg') {
				?>
					<id><?php echo $data['msg_id'];?></id>
					<code><?php echo $data['msg_code'];?></code>
					<title><?php echo $data['msg_title'];?></title>
					<type><?php echo $data['msg_type'];?></type>
				<?php
				}else if($table=='std_nationality') {
				?>
					<id><?php echo $data['nation_id'];?></id>
					<code><?php echo $data['nation_code'];?></code>
					<nameth><?php echo $data['nation_name_th'];?></nameth>
					<nameen><?php echo $data['nation_name_en'];?></nameen>
					<source><?php echo $data['rec_source'];?></source>
				<?php
				}else if($table=='std_place_condition') {
				?>
					<id><?php echo $data['pcond_id'];?></id>
					<code><?php echo $data['pcond_code'];?></code>
					<title><?php echo $data['pcond_title'];?></title>
				<?php
				}else if($table=='std_place_type') {
				?>
					<id><?php echo $data['ptype_id'];?></id>
					<code><?php echo $data['ptype_code'];?></code>
					<title><?php echo $data['ptype_title'];?></title>
				<?php
				}else if($table=='std_position_type') {
				?>
					<id><?php echo $data['posi_type_id'];?></id>
					<code><?php echo $data['posi_type_code'];?></code>
					<title><?php echo $data['posi_type_title'];?></title>
					<grp><?php echo $data['posi_grp'];?></grp>
				<?php
				}else if($table=='std_prename') {
				?>
					<id><?php echo $data['pren_id'];?></id>
					<code><?php echo $data['pren_code'];?></code>
					<nameth><?php echo $data['prename_th'];?></nameth>
				<?php
				}else if($table=='std_programs') {
				?>
					<id><?php echo $data['prgm_id'];?></id>
					<code><?php echo $data['prgm_code'];?></code>
					<qstnid><?php echo $data['qstn_id'];?></qstnid>
					<title><?php echo $data['prgm_title'];?></title>
				<?php
				}else if($table=='std_religion') {
				?>
					<id><?php echo $data['relg_id'];?></id>
					<code><?php echo $data['relg_code'];?></code>
					<title><?php echo $data['relg_title'];?></title>
				<?php
				}else if($table=='std_req_channel') {
				?>
					<id><?php echo $data['chn_id'];?></id>
					<code><?php echo $data['chn_code'];?></code>
					<name><?php echo $data['chn_name'];?></name>
				<?php
				}else if($table=='std_road') {
				?>
					<id><?php echo $data['road_id'];?></id>
					<code><?php echo $data['road_code'];?></code>
					<name><?php echo $data['road_name'];?></name>
				<?php
				}else if($table=='std_schl_course') {
				?>
					<id><?php echo $data['crse_id'];?></id>
					<code><?php echo $data['crse_code'];?></code>
					<grp><?php echo $data['crse_grp'];?></grp>
					<cate><?php echo $data['crse_cate'];?></cate>
					<title><?php echo $data['crse_title'];?></title>
					<objective><?php echo $data['crse_objective'];?></objective>
					<hoursperweek><?php echo $data['hours_per_week'];?></hoursperweek>
					<attfile><?php echo $data['att_file'];?></attfile>
					<attlabel><?php echo $data['att_label'];?></attlabel>
					<attsize><?php echo $data['att_size'];?></attsize>
				<?php
				}else if($table=='std_trouble') {
				?>
					<id><?php echo $data['trb_id'];?></id>
					<code><?php echo $data['trb_code'];?></code>
					<title><?php echo $data['trb_title'];?></title>
				<?php
				}else if($table=='std_village_position') {
				?>
					<id><?php echo $data['vpos_id'];?></id>
					<code><?php echo $data['vpos_code'];?></code>
					<title><?php echo $data['vpos_title'];?></title>
				<?php
				}else if($table=='std_wisdom') {
				?>
					<id><?php echo $data['wis_id'];?></id>
					<code><?php echo $data['wis_code'];?></code>
					<title><?php echo $data['wis_name'];?></title>
				<?php
				}
				?>
			</item>

		<?php
		}
		?>

	<?php	
	}
	?>
		
	</channel>
</rss>