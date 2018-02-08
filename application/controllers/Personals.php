<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personals extends CI_Controller {

	public $db_gateway = null;

	function __construct() {
		parent::__construct();
		
		$this->load->database();
		$this->db_gateway = $this->load->database('gateway',true);

		$this->load->helper(array('url','form','general','file','html','asset'));
		$this->load->library(array('session','encrypt'));
        $this->load->model(array('admin_model','member_model','common_model','useful_model','webinfo_model','transfer_model'));


        ob_end_clean();
	}
	function __deconstruct() {
		$this->db->close();
	}

/*	function index() {

	}
*/

	public $db_dopa = null; //Demo for test

	public function setSessionWSRV() {
		$this->load->model(array('personal_model'));
		$tmp = $this->personal_model->getOnce_authenApp(get_inpost('authen_log_id'));
		if(count($tmp)>0) {
            set_session('pers_authen',array('authen_log_id'=>$tmp['authen_log_id'],'pid'=>$tmp['pid'],'cid'=>$tmp['cid'],'random_string'=>$tmp['random_string']));
		}
		echo json_encode($tmp);
	}
	public function setLogWSRV() {
		$id = $this->common_model->insert('authen_app_log',array('user_id'=>get_inpost('user_id'),'org_id'=>get_inpost('org_id'),'uniqkey_id'=>get_inpost('uniq_id'),'req_datetime'=>date('Y-m-d H:i:s')));
		echo json_encode(array('authen_log_id'=>$id));
	}

	public function setPersonalInfo_demo($pid='') {
		$arr = array();
		if($pid!='') {
			$this->load->model(array('personal_model'));
			$tmp = $this->personal_model->getOnce_PersonalInfo_byCode($pid); //Get Personal Info by us
			if(isset($tmp['pid'])) {
				if($tmp['wsrv_staff_datetime']=='' || (strtotime($tmp['wsrv_staff_datetime'])<strtotime('-1 month'))) { //Old Data
					//Update
					//$pers_authen = get_session('pers_authen');
					$pers_tmp = $this->personal_model->getOnce_DemoPersonalInfo_byCode($pid); //get wsrv
					if(isset($pers_tmp['pid'])) { //wsrv 
						$pers_tmp['pre_addr_id'] = $tmp['pre_addr_id'];
						$pers_tmp['wsrv_staff_pid'] = get_session('user_id');
						$pers_tmp['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

						//Update Personal Address
						$addr_addnew_id = 0;
						$addr_tmp = array();
						if($pers_tmp['addr_code']!='') {
							$addr_tmp = $this->personal_model->getOnce_strPersonalAddress_byCode($pers_tmp['addr_code']); 
							if(isset($addr_tmp['addr_code'])) {
								if($addr_tmp['wsrv_staff_datetime']=='' || (strtotime($addr_tmp['wsrv_staff_datetime'])<strtotime('-1 month'))) {
									$addr_update = $this->personal_model->getOnce_DemoPersonalAddress_byCode($pers_tmp['addr_code']); //get wsrv
									if(isset($addr_update['addr_code'])) {
										unset($addr_update['addr_id']);
										$addr_update['wsrv_staff_pid'] = get_session('user_id');
										$addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

										if(!isset($addr_tmp['addr_id'])) {//No Data then Add
											$addr_update['insert_user_id'] = get_session('user_id');
											$addr_update['insert_datetime'] = date("Y-m-d H:i:s");
											$addr_addnew_id = $this->common_model->insert('pers_addr',$addr_update);
											$pers_tmp['reg_addr_id'] = $addr_addnew_id;
										}else {
											$this->common_model->update('pers_addr',$addr_update,array('addr_id'=>$addr_tmp['addr_id']));
											$pers_tmp['reg_addr_id'] = $addr_tmp['addr_id'];
										}
									}
								}
							}else { //No Address Info Then Add
								$addr_update = $this->personal_model->getOnce_DemoPersonalAddress_byCode($pers_tmp['addr_code']); //get wsrv
								if(isset($addr_update['addr_code'])) {
									unset($addr_update['addr_id']);
									$addr_update['wsrv_staff_pid'] = get_session('user_id');
									$addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");
									$addr_update['insert_user_id'] = get_session('user_id');
									$addr_update['insert_datetime'] = date("Y-m-d H:i:s");
									$addr_addnew_id = $this->common_model->insert('pers_addr',$addr_update);
									$pers_tmp['reg_addr_id'] = $addr_addnew_id;
								}
							}
						}
						//End Update Personal Address

						unset($pers_tmp['addr_code']);
						unset($pers_tmp['pers_id']);

						$this->common_model->update('pers_info',$pers_tmp,array('pers_id'=>$tmp['pers_id'])); // update personal info
						
						$tmp = $this->personal_model->getOnce_PersonalInfo_byCode($pid); //Get Personal Info by us for Update
					}
				} //End //Old Data

			    if($tmp['date_of_birth']!='') {
			      $date = new DateTime($tmp['date_of_birth']);
			      $now = new DateTime();
			      $interval = $now->diff($date);
			      $age = $interval->y;
			      $tmp['date_of_birth'] = formatDateThai($tmp['date_of_birth']).' (อายุ '.$age.' ปี)';
			      $tmp['age'] = $age;
			      $tmp['date_of_death'] = $tmp['date_of_death']!=''?formatDateThai($tmp['date_of_death']):'';
			    }
				$tmp['reg_addr'] = array();
				if($tmp['reg_addr_id']!='') {
					$tmp1 = $this->personal_model->getOnce_PersonalAddress($tmp['reg_addr_id']);
					if(isset($tmp1['addr_id'])) {
						$tmp['reg_addr'] = $tmp1;
						$tmp['reg_add_info'] = "{$tmp1['addr_home_no']} หมู่ {$tmp1['addr_moo']} ต. {$tmp1['addr_sub_district']} อ. {$tmp1['addr_district']} จ. {$tmp1['addr_province']} {$tmp1['addr_zipcode']}";
					}
				}

			}else { //No Data Info 

				$pers_tmp = $this->personal_model->getOnce_DemoPersonalInfo_byCode($pid); //get wsrv
				//Append to 
				if(isset($pers_tmp['pid'])) { //wsrv 
					unset($pers_tmp['pre_addr_id']);
					$pers_tmp['insert_user_id'] = get_session('user_id');
					$pers_tmp['insert_datetime'] = date("Y-m-d H:i:s");
					$pers_tmp['wsrv_staff_pid'] = get_session('user_id');
					$pers_tmp['wsrv_staff_datetime'] = date("Y-m-d H:i:s");
					unset($pers_tmp['pers_id']);

					//Update Personal Address
					$addr_addnew_id = 0;
					$addr_tmp = array();
					if($pers_tmp['addr_code']!='') {
						$addr_tmp = $this->personal_model->getOnce_strPersonalAddress_byCode($pers_tmp['addr_code']);
						if(isset($addr_tmp['addr_code'])) {
							if($addr_tmp['wsrv_staff_datetime']=='' || (strtotime($addr_tmp['wsrv_staff_datetime'])<strtotime('-1 month'))) {
								$addr_update = $this->personal_model->getOnce_DemoPersonalAddress_byCode($pers_tmp['addr_code']); //get wsrv
								if(isset($addr_update['addr_code'])) {
									unset($addr_update['addr_id']);
									$addr_update['wsrv_staff_pid'] = get_session('user_id');
									$addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

									if(!isset($addr_tmp['addr_id'])) {//No Data then Add
										$addr_update['insert_user_id'] = get_session('user_id');
										$addr_update['insert_datetime'] = date("Y-m-d H:i:s");
										$addr_addnew_id = $this->common_model->insert('pers_addr',$addr_update);
										$pers_tmp['reg_addr_id'] = $addr_addnew_id;
									}else {
										$this->common_model->update('pers_addr',$addr_update,array('addr_id'=>$addr_tmp['addr_id']));
										$pers_tmp['reg_addr_id'] = $addr_tmp['addr_id'];
									}
								}
							}
						}else { //No Address Info Then Add
							$addr_update = $this->personal_model->getOnce_DemoPersonalAddress_byCode($pers_tmp['addr_code']); //get wsrv
							if(isset($addr_update['addr_code'])) {
								unset($addr_update['addr_id']);
								$addr_update['wsrv_staff_pid'] = get_session('user_id');
								$addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");
								$addr_update['insert_user_id'] = get_session('user_id');
								$addr_update['insert_datetime'] = date("Y-m-d H:i:s");
								$addr_addnew_id = $this->common_model->insert('pers_addr',$addr_update);
								$pers_tmp['reg_addr_id'] = $addr_addnew_id;
							}
						}
					}
					//End Update Personal Address

					unset($pers_tmp['addr_code']);
					$this->common_model->insert('pers_info',$pers_tmp); // insert personal info
					
					$tmp = $this->personal_model->getOnce_PersonalInfo_byCode($pid); //Get Personal Info by us for Update
					
				    if($tmp['date_of_birth']!='') {
				      $date = new DateTime($tmp['date_of_birth']);
				      $now = new DateTime();
				      $interval = $now->diff($date);
				      $age = $interval->y;
				      $tmp['date_of_birth'] = formatDateThai($tmp['date_of_birth']).' (อายุ '.$age.' ปี)';
				      $tmp['age'] = $age;
				      $tmp['date_of_death'] = $tmp['date_of_death']!=''?formatDateThai($tmp['date_of_death']):'';
				    }
					$tmp['reg_addr'] = array();
					if($tmp['reg_addr_id']!='') {
						$tmp1 = $this->personal_model->getOnce_PersonalAddress($tmp['reg_addr_id']);
						if(isset($tmp1['addr_id'])) {
							$tmp['reg_addr'] = $tmp1;
							$tmp['reg_add_info'] = "{$tmp1['addr_home_no']} หมู่ {$tmp1['addr_moo']} ต. {$tmp1['addr_sub_district']} อ. {$tmp1['addr_district']} จ. {$tmp1['addr_province']} {$tmp1['addr_zipcode']}";
						}
					}

				} // End if(isset($pers_tmp['pid'])) { //wsrv 

			}

			$arr = $tmp;
		}
		return $arr;
	}

	public function getPersonalInfo() {
		 header('Access-Control-Allow-Origin: *');  

		//$pid = get_inpost('pid');
		$pid = get_inpost('target_pid');
		$pers_info = $this->setPersonalInfo_demo($pid);
		if(isset($pers_info['father_pid'])) {
			//$father_pid = $this->setPersonalInfo_demo($pers_info['father_pid']);
		}
		if(isset($pers_info['mother_pid'])) {
			//$mather_pid = $this->setPersonalInfo_demo($pers_info['mother_pid']);
		}
		echo json_encode($pers_info);
	}


	public function get_Area_option(){
		$code = get_inpost('code');
		$type = get_inpost('type');
		// dieArray($_POST);
		if($type == 'Amphur'){
			$subCode = substr($code,0,2);
		}else if($type == 'Tambon'){
			$subCode = substr($code,0,4);
		}
		
		$opRows = $this->common_model->custom_query("
			SELECT area_code,area_name_th FROM std_area 
			WHERE area_type = '{$type}' 
			AND area_code LIKE '{$subCode}%'
			AND area_name_th NOT LIKE '%*'
			ORDER BY area_code ASC
		");

		echo json_encode($opRows);
		// dieArray($opRows);
	}

	public function getLane($code=''){
		$subCode = substr($code,0,2);
		$rows = $this->common_model->custom_query("SELECT lane_code AS id , lane_name AS 'text' FROM std_lane WHERE lane_code LIKE '{$subCode}%' AND lane_name LIKE '%{$_GET['q']}%' AND lane_name NOT LIKE '%*'");
		echo json_encode($rows);
	}

	public function getRoad($code=''){
		$subCode = substr($code,0,2);
		$rows = $this->common_model->custom_query("SELECT road_code AS id , road_name AS 'text' FROM std_road WHERE road_code LIKE '{$subCode}%' AND road_name LIKE '%{$_GET['q']}%' AND road_name NOT LIKE '%*'");
		echo json_encode($rows);
	}


}