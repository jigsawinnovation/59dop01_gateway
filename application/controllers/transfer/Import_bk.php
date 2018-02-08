<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Import extends REST_Controller {

    public $db_gateway = null;

    //public $HostBMSO = 'https://10.1.1.113:1443';
    //public $HostBMSO = 'http://10.6.4.74:1443';
    //public $HostBMSO = 'http://10.6.4.75:1443';
    //public $HostBMSO = 'http://210.1.8.75:1443';

    public $UserBMSO = 'DOPTestUser1';
    public $PassBMSO = 'DOPTestUser1';

	function __construct() {
		parent::__construct();

        $this->load->library('PHPRequests');

		//$this->load->helper(array('url','form','general','file','html','asset'));
		//$this->load->library(array('session','encrypt'));
        //$this->load->model(array('admin_model','common_model','useful_model','webinfo_model','transfer_model'));

		//chkUserLogin(); 		 //Check User Login

        $this->load->helper(array('url','general','html','asset'));
        $this->load->model(array('common_model','useful_model','transfer_model'));
        
        $this->db_gateway = $this->load->database('gateway',true);

        ob_end_clean();
	}

	function __deconstruct() {
		//$this->db->close();
        $this->db_gateway->close();
	}	

/*    function test_get() {
        try {
            //connect via SSL, but don't check cert
            $handle=curl_init('https://10.6.4.74:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestRandom.svc/ICTCMSORequestRandomService/data/DOPTestUser1/DOPTestUser1/3801100193399/40422403150b4e9c'); //พี่อิ๋ว

            //$handle=curl_init('https://10.6.4.74:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestRandom.svc/ICTCMSORequestRandomService/data/DOPTestUser1/DOPTestUser1/3101701933555/0221004350953232'); //พี่แดง

            curl_setopt($handle, CURLOPT_VERBOSE, true);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            $content = curl_exec($handle);
            echo $content; // show target page
        }catch (Exception $e) {
            echo $e->getMessage();
        }
    }
*/	

    public function structure_get() {
        $wsrv_id = $this->get('wsrv_id');
        $wsrv_id = (int) $wsrv_id; 
        if($wsrv_id < 0) {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            // BAD_REQUEST (400) being the HTTP response code    
        }else { 
            
            $data = $this->transfer_model->getAll_elementWSV($wsrv_id,'Parameter');
            $nums = count($data)?true:false;

            if($nums>0){ 
                $this->response($data, 200);
            }else{ 
                $this->response([
                    'status' => FALSE,
                    'message' => 'ไม่พบรายการข้อมูล'
                ], REST_Controller::HTTP_NOT_FOUND);
                // NOT_FOUND (404) being the HTTP response code
            }

        }
    }

    public function index_get()
    {
        //ob_end_clean();
    }

    private function setUrl($wsrv_info=0) {
        $wsrv_info = $this->transfer_model->getOnce_wsrv_info(1);
        $url = $wsrv_info['wsrv_url'].$wsrv_info['wsrv_req_template'];
        $url = str_replace("{","",$url);
        $url = str_replace("}","",$url);
        return $url;
    }

    /* [1] Portal Gateway ฐานข้อมูลทะเบียนราษฐ์ (กรมการปกครอง กระทรวงมหาดไทย) */


    /*-- 1.1] (9080)Request for Random Service : ขอรหัสในการเข้าใช้งาน --*/
/*    public function RequestRandomService_post() {
        try {
            $OfficerPID = get_inpost('OfficerPID');
            $OfficerCID = get_inpost('OfficerCID');

            $response = Requests::get("{$HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestRandom.svc/ICTCMSORequestRandomService/data/{$UserBMSO}/{$PassBMSO}/{$OfficerPID}/{$OfficerCID}");

            //$response = Requests::get("https://210.1.8.75:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestRandom.svc/ICTCMSORequestRandomService/data/DOPTestUser1/DOPTestUser1/3801100193399/40422403150b4e9c");

            $responseData = $response->body; // ได้ข้อมูล json กลับมา
            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }*/

    private function setPersonalInfo_demo($pid='',$cid='',$random_string='',$target_pid='',$user_id='0',$org_id='0') {
        $arr = array();
        if($target_pid!='') {
            $this->load->model(array('personal_model'));
            $tmp = $this->personal_model->getOnce_PersonalInfo_byCode($target_pid); //Get Personal Info by us
            if(isset($tmp['pid'])) {
                if($tmp['wsrv_staff_datetime']=='' || (strtotime($tmp['wsrv_staff_datetime'])<strtotime('-1 month'))) { //Old Data
                    $pers_tmp = $this->personal_model->getOnce_DemoPersonalInfo_byCode($target_pid); //get wsrv
                    if(isset($pers_tmp['pid'])) { //wsrv 
                        $pers_tmp['pre_addr_id'] = $tmp['pre_addr_id'];
                        $pers_tmp['wsrv_staff_pid'] = $pid;
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
                                        $addr_update['wsrv_staff_pid'] = $user_id;
                                        $addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

                                        if(!isset($addr_tmp['addr_id'])) {//No Data then Add
                                            $addr_update['insert_user_id'] = $user_id;
                                            $addr_update['insert_datetime'] = date("Y-m-d H:i:s");
                                            $addr_update['insert_org_id'] = $org_id;
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
                                    $addr_update['wsrv_staff_pid'] = $pid;
                                    $addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");
                                    $addr_update['insert_user_id'] = $user_id;
                                    $addr_update['insert_datetime'] = date("Y-m-d H:i:s");
                                    $addr_update['insert_org_id'] = $org_id;
                                    $addr_addnew_id = $this->common_model->insert('pers_addr',$addr_update);
                                    $pers_tmp['reg_addr_id'] = $addr_addnew_id;
                                }
                            }
                        }
                        //End Update Personal Address

                        unset($pers_tmp['addr_code']);
                        unset($pers_tmp['pers_id']);

                        $this->common_model->update('pers_info',$pers_tmp,array('pers_id'=>$tmp['pers_id'])); // update personal info
                        
                        $tmp = $this->personal_model->getOnce_PersonalInfo_byCode($target_pid); //Get Personal Info by us for Update
                    }
                } //End //Old Data

                if($tmp['date_of_birth']!='') {
                  $date = new DateTime($tmp['date_of_birth']);
                  $now = new DateTime();
                  $interval = $now->diff($date);
                  $age = $interval->y;
                  $tmp['date_of_birth'] = formatDateThai($tmp['date_of_birth']).' (อายุ '.$age.' ปี)';
                  $tmp['age'] = $age;
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

                $pers_tmp = $this->personal_model->getOnce_DemoPersonalInfo_byCode($target_pid); //get wsrv
                //Append to 
                if(isset($pers_tmp['pid'])) { //wsrv 
                    unset($pers_tmp['pre_addr_id']);
                    $pers_tmp['insert_user_id'] = $user_id;
                    $pers_tmp['insert_datetime'] = date("Y-m-d H:i:s");
                    $pers_tmp['insert_org_id'] = $org_id;
                    $pers_tmp['wsrv_staff_pid'] = $pid;
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
                                    $addr_update['wsrv_staff_pid'] = $pid;
                                    $addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

                                    if(!isset($addr_tmp['addr_id'])) {//No Data then Add
                                        $addr_update['insert_user_id'] = $user_id;
                                        $addr_update['insert_datetime'] = date("Y-m-d H:i:s");
                                        $addr_update['insert_org_id'] = $org_id;
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
                                $addr_update['wsrv_staff_pid'] = $pid;
                                $addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");
                                $addr_update['insert_user_id'] = $user_id;
                                $addr_update['insert_datetime'] = date("Y-m-d H:i:s");
                                $addr_update['insert_org_id'] = $org_id;
                                $addr_addnew_id = $this->common_model->insert('pers_addr',$addr_update);
                                $pers_tmp['reg_addr_id'] = $addr_addnew_id;
                            }
                        }
                    }
                    //End Update Personal Address

                    unset($pers_tmp['addr_code']);
                    $this->common_model->insert('pers_info',$pers_tmp); // insert personal info
                    
                    $tmp = $this->personal_model->getOnce_PersonalInfo_byCode($target_pid); //Get Personal Info by us for Update
                    
                    if($tmp['date_of_birth']!='') {
                      $date = new DateTime($tmp['date_of_birth']);
                      $now = new DateTime();
                      $interval = $now->diff($date);
                      $age = $interval->y;
                      $tmp['date_of_birth'] = formatDateThai($tmp['date_of_birth']).' (อายุ '.$age.' ปี)';
                      $tmp['age'] = $age;
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

    public function getPersonalInfo_post() {
        if(get_inpost('pid')!='' && get_inpost('cid')!='' && get_inpost('random_string')!='' && get_inpost('target_pid')!='' && get_inpost('user_id')!='') {
            $pid = get_inpost('pid');
            $cid = get_inpost('cid');
            $random_string = get_inpost('random_string');
            $target_pid = get_inpost('target_pid');
            $user_id = get_inpost('user_id');
            $org_id = get_inpost('org_id');

            $pers_info = $this->setPersonalInfo_demo($pid,$cid,$random_string,$target_pid,$user_id,$org_id);
            if(isset($pers_info['father_pid'])) {
                //$father_pid = $this->setPersonalInfo_demo($pid,$cid,$random_string,$target_pid,$user_id,$org_id);
            }
            if(isset($pers_info['mother_pid'])) {
                //$mather_pid = $this->setPersonalInfo_demo($pid,$cid,$random_string,$target_pid,$user_id,$org_id);
            }
            echo json_encode($pers_info);
        }else {
            echo json_encode(array());
        }
    }

    /*-- 1.1] (9080)Request for Random Service : ขอรหัสในการเข้าใช้งาน --*/
    public function RequestRandomService_get($OfficerPID='', $OfficerCID='') {

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
            );

        $request['param'] = array('OfficerPID'=>$OfficerPID,'OfficerCID'=>$OfficerCID);

        if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],1)) {
                try {
                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestRandom.svc/ICTCMSORequestRandomService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}");
                    
                    $url = $this->setUrl(1);
                   
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา
                    $responseData = $this->transfer_model->curlGet($url);

                    $request['result']['message'] = $responseData->{'message'};
                    $request['result']['code'] = $responseData->{'code'};

                    $request['result'] = $responseData;

                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test

                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,1,serialize($request),'Import','Success'); //Save Log

                    echo $responseData;
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,1,serialize($request),'Import','Fail'); //Save Log
                    echo json_encode($msg);//echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,1,serialize($request),'Import','Fail'); //Save Log  
                echo json_encode($msg);
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,1,serialize($request),'Import','Fail'); //Save Log
            echo json_encode($msg);
        }

    }


    /*-- 1.2] (9081)Request for Authentication Service : การตรวจสอบรหัสการเข้าใช้งานกับ SAS และยืนยันสิทธิ์ --*/
/*    public function RequestAuthenticationService_post() {
        $Username = get_inpost('Username');
        $Password = get_inpost('Password');
        $OfficerPID = get_inpost('OfficerPID');
        $OfficerCID = get_inpost('OfficerCID');
        $RandomStringBin = get_inpost('RandomStringBin');
        $EnvelopString = get_inpost('EnvelopString');
        $EnvelopStringLength = get_inpost('EnvelopStringLength');

        try {
            $response = Requests::get("https://10.1.1.113:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestAuthen.svc/ICTCMSORequestAuthenticationService/data/{$Username}/{$Password}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$EnvelopString}/{$EnvelopStringLength}");

            $responseData = $response->body; // ได้ข้อมูล json กลับมา
            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/
    /*-- 1.2] (9081)Request for Authentication Service : การตรวจสอบรหัสการเข้าใช้งานกับ SAS และยืนยันสิทธิ์ --*/
    public function RequestAuthenticationService_get($OfficerPID='', $OfficerCID='',$RandomStringBin='',$EnvelopString='', $EnvelopStringLength='') {

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
            );
        $request['param'] = array('OfficerPID'=>$OfficerPID,'OfficerCID'=>$OfficerCID,'RandomStringBin'=>$RandomStringBin,'EnvelopString'=>$EnvelopString,'EnvelopStringLength'=>$EnvelopStringLength);

        if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],2)) {
                try {
                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestAuthen.svc/ICTCMSORequestAuthenticationService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$EnvelopString}/{$EnvelopStringLength}");
                    
                    $url = $this->setUrl(2);
                   
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);
                    $url = str_replace('RandomStringBin',$RandomStringBin,$url);
                    $url = str_replace('EnvelopString',$EnvelopString,$url);
                    $url = str_replace('EnvelopStringLength',$EnvelopStringLength,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา
                    $responseData = $this->transfer_model->curlGet($url);
                    
                    $request['result'] = $responseData;

                    $request['result']['message'] = $responseData->{'message'};
                    $request['result']['code'] = $responseData->{'code'};

                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test
                    
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,2,serialize($request),'Import','Success'); //Save Log

                    echo $responseData;
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,2,serialize($request),'Import','Fail'); //Save Log
                    echo json_encode($msg);//echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,2,serialize($request),'Import','Fail'); //Save Log  
                echo json_encode($msg);
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,2,serialize($request),'Import','Fail'); //Save Log
            echo json_encode($msg);
        }

    }

    /*-- 1.3.1] (0101)Request for Personal Information Code Service (Code only) : ค้นหาข้อมูลบุคคล(รหัส)ด้วยเลขประจำตัวประชาชน --*/
/*    public function RequestPersonalInformationCodeService_post() {
        $Username = get_inpost('Username');
        $Password = get_inpost('Password');
        $OfficerPID = get_inpost('OfficerPID');
        $OfficerCID = get_inpost('OfficerCID');
        $RandomStringBin = get_inpost('RandomStringBin');
        $TargetPID = get_inpost('TargetPID');

        try {
            $response = Requests::get("https://10.1.1.113:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformationCode.svc/ICTCMSORequestPersonalInformationCodeService/data/{$Username}/{$Password}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetPID}");

            $responseData = $response->body; // ได้ข้อมูล json กลับมา
            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/
    /*-- 1.3.1] (0101)Request for Personal Information Code Service (Code only) : ค้นหาข้อมูลบุคคล(รหัส)ด้วยเลขประจำตัวประชาชน --*/
    public function RequestPersonalInformationCodeService_get($OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetPID='') {

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
            );

        $request['param'] = array('OfficerPID'=>$OfficerPID, 'OfficerCID'=>$OfficerCID, 'RandomStringBin'=>$RandomStringBin, 'TargetPID'=>$TargetPID);

        if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],3)) {
                try {

                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformationCode.svc/ICTCMSORequestPersonalInformationCodeService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetPID}");
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $url = $this->setUrl(3);
                   
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);
                    $url = str_replace('RandomStringBin',$RandomStringBin,$url);
                    $url = str_replace('TargetPID',$TargetPID,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา
                    $responseData = $this->transfer_model->curlGet($url);

                    $request['result'] = $responseData;

                    $request['result']['message'] = $responseData->{'message'};
                    $request['result']['code'] = $responseData->{'code'};

/*                    if($request['result']['code']=='0') { //Update Data 
                        $rows = $this->transfer_model->getAll_elementWSV(3,'elem_name');
                        $pers_tmp = array();
                        $pers_tmp['wsrv_staff_pid'] = get_session('user_id');
                        $pers_tmp['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

                        $addr_update = array();
                        $addr_update['wsrv_staff_pid'] = get_session('user_id');
                        $addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

                        foreach($rows as $row) {
                            if($row['elem_name']!='') {
                                if(substr($pers_tmp[$row['elem_name']],0,5)!='addr_') {
                                    $pers_tmp[$row['elem_name']] = $responseData->{$row['elem_src_name']};
                                }else {
                                    $addr_update[$row['elem_name']] = $responseData->{$row['elem_src_name']};
                                }
                            }
                        }

                        $this->common_model->update('pers_info',$pers_tmp,array('pid'=>$responseData->{'pid'}));
                        if($responseData->{'houseid'}!='') {
                            $this->common_model->update('pers_addr',$addr_update,array('addr_code'=>$responseData->{'houseid'}));
                        }
                    }*/

                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test
                    
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Success'); //Save Log

                    echo $responseData;
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log
                    echo json_encode($msg);//echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log  
                echo json_encode($msg);
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,3,serialize($request),'Import','Fail'); //Save Log
            echo json_encode($msg);
        }

    }


    /*-- 1.3.2] (----)Request for Personal Information Service (Description only) : ค้นหาข้อมูลบุคคล(คำบรรยาย)ด้วยเลขประจำตัวประชาชน --*/
/*    public function RequestPersonalInformationService_post() {
        $Username = get_inpost('Username');
        $Password  = get_inpost('Password');
        $OfficerPID  = get_inpost('OfficerPID');
        $OfficerCID  = get_inpost('OfficerCID');
        $RandomStringBin  = get_inpost('RandomStringBin');
        $TargetPID  = get_inpost('TargetPID');

        try {
            $response = Requests::get("https://10.1.1.113:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformation.svc/ICTCMSORequestPersonalInformationService/data/{$Username}/{$Password}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetPID}");

            $responseData = $response->body; // ได้ข้อมูล json กลับมา
            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/
    /*-- 1.3.2] (----)Request for Personal Information Service (Description only) : ค้นหาข้อมูลบุคคล(คำบรรยาย)ด้วยเลขประจำตัวประชาชน --*/

    public function RequestPersonalInformationService_get($OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetPID='') {

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
            );
        $request['param'] = array('OfficerPID'=>$OfficerPID, 'OfficerCID'=>$OfficerCID, 'RandomStringBin'=>$RandomStringBin, 'TargetPID'=>$TargetPID);

        if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],4)) {
                try {
                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformation.svc/ICTCMSORequestPersonalInformationService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetPID}");
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $url = $this->setUrl(4);
                   
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);
                    $url = str_replace('RandomStringBin',$RandomStringBin,$url);
                    $url = str_replace('TargetPID',$TargetPID,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา
                    $responseData = $this->transfer_model->curlGet($url);

                    $request['result'] = $responseData;

                    $request['result']['message'] = $responseData->{'message'};
                    $request['result']['code'] = $responseData->{'code'};

/*                    if($request['result']['code']=='0') { //Update Data 
                        $rows = $this->transfer_model->getAll_elementWSV(4,'elem_name');
                        $pers_tmp = array();
                        $pers_tmp['wsrv_staff_pid'] = get_session('user_id');
                        $pers_tmp['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

                        foreach($rows as $row) {
                            if($row['elem_name']!='') {
                                $pers_tmp[$row['elem_name']] = $responseData->{$row['elem_src_name']};
                            }
                        }

                        if(substr($pers_tmp['date_of_death'],0,3)=='ตาย') { //personstatus: "ตาย (14/04/2557) ", ////
                            $year = substr($pers_tmp['date_of_death'],11,4);
                            settype($year, "integer");
                            $year = $year-543;
                            $pers_tmp['date_of_death '] = $year.'-'.substr($pers_tmp['date_of_death'],8,2).'-'.substr($pers_tmp['date_of_death'],5,2);
                        }else {
                            $pers_tmp['date_of_death '] = '';
                        }
                        $this->common_model->update('pers_info',$pers_tmp,array('pid'=>$responseData->{'pid'}));
                    }*/

                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test

                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Success'); //Save Log

                    echo $responseData;
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log
                    echo json_encode($msg);//echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log  
                echo json_encode($msg);
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,3,serialize($request),'Import','Fail'); //Save Log
            echo json_encode($msg);
        }

    }

    /*-- 1.3.3] (----)Request for Personal Information Service (Description only) by Name : ค้นหาข้อมูลบุคคล(คำบรรยาย)ด้วยชื่อและนามสกุล --*/
/*    public function RequestPersonalInformationServiceByNameService_post() {
        $Username = get_inpost('Username');
        $Password = get_inpost('Password');
        $OfficerPID = get_inpost('OfficerPID');
        $OfficerCID  = get_inpost('OfficerCID');
        $RandomStringBin = get_inpost('RandomStringBin');
        $TargetFirstName = get_inpost('TargetFirstName');
        $TargetLastName = get_inpost('TargetLastName');

        try {
            $response = Requests::get("https://10.1.1.113:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformationByName.svc/ICTCMSORequestPersonalInformationByNameService/data/{$Username}/{$Password}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetFirstName}/{$TargetLastName}");

            $responseData = $response->body; // ได้ข้อมูล json กลับมา
            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/
    /*-- 1.3.3] (----)Request for Personal Information Service (Description only) by Name : ค้นหาข้อมูลบุคคล(คำบรรยาย)ด้วยชื่อและนามสกุล --*/
/*    public function RequestPersonalInformationServiceByNameService_get($OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetFirstName='', $TargetLastName='') {
        try {
            $response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformationByName.svc/ICTCMSORequestPersonalInformationByNameService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetFirstName}/{$TargetLastName}");

            $responseData = $response->body; // ได้ข้อมูล json กลับมา
            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/


    /*-- 1.3.4] (----)Request for Personal Name in English : ค้นหาข้อมูลชื่อนามสกุลในภาษาอังกฤษโดยใช้ หมายเลขบัตรประชาชน --*/


    /*-- 1.3.5] (0302)Request for Personal Image Service :   ค้นหาข้อมูลรูปภาพของบุคคลโดยใช้หมายเลขบัตรประชาชน --*/
/*    public function RequestPersonalImageService_post() {
        $Username = get_inpost('Username');
        $Password = get_inpost('Password');
        $OfficerPID = get_inpost('OfficerPID');
        $OfficerCID = get_inpost('OfficerCID');
        $RandomStringBin = get_inpost('RandomStringBin');
        $TargetPID = get_inpost('TargetPID');

        try {
            $response = Requests::get("https://10.1.1.113:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalImage.svc/ICTCMSORequestPersonalImageService/data/{$Username}/{$Password}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetPID}");

            $responseData = $response->body; // ได้ข้อมูล json กลับมา
            echo $responseData;   
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/
    /*-- 1.3.5] (0302)Request for Personal Image Service :   ค้นหาข้อมูลรูปภาพของบุคคลโดยใช้หมายเลขบัตรประชาชน --*/
/*    public function RequestPersonalImageService_get($OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetPID='') {

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = $_SERVER;
        $request['param'] = array('OfficerPID'=>$OfficerPID, 'OfficerCID'=>$OfficerCID, 'RandomStringBin'=>$RandomStringBin, 'TargetPID'=>$TargetPID);

        if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],5)) {
                try {
            $response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalImage.svc/ICTCMSORequestPersonalImageService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetPID}");
                    $responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $request['result'] = $responseData;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,5,serialize($request),'Import','Success'); //Save Log

                    echo $responseData;
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,5,serialize($request),'Import','Fail'); //Save Log
                    echo json_encode($msg);//echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,5,serialize($request),'Import','Fail'); //Save Log  
                echo json_encode($msg);
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,5,serialize($request),'Import','Fail'); //Save Log
            echo json_encode($msg);
        }

    }
*/
    /*-- 1.4] (0105)Request for House Information Service :  ค้นหาข้อมูลบ้านด้วยเลขรหัสประจำบ้าน --*/
/*    public function RequestHouseInformationService_post() {
        $Username = get_inpost('Username');
        $Password = get_inpost('Password');
        $OfficerPID = get_inpost('OfficerPID');
        $OfficerCID = get_inpost('OfficerCID');
        $RandomStringBin = get_inpost('RandomStringBin');
        $TargetHouseID = get_inpost('TargetHouseID');

        try {
            $response = Requests::get("https://10.1.1.113:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestHouseInformation.svc/ICTCMSORequestHouseInformationService/data/{$Username}/{$Password}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetHouseID}");

            $responseData = $response->body; // ได้ข้อมูล json กลับมา
            echo $responseData;   
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    } 
*/ 
    /*-- 1.4] (0105)Request for House Information Service :  ค้นหาข้อมูลบ้านด้วยเลขรหัสประจำบ้าน --*/
    public function RequestHouseInformationService_get($OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetHouseID='') {

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
            );
        $request['param'] = array('OfficerPID'=>$OfficerPID, 'OfficerCID'=>$OfficerCID, 'RandomStringBin'=>$RandomStringBin, 'TargetHouseID'=>$TargetHouseID);

        if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],5)) {
                try {
                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestHouseInformation.svc/ICTCMSORequestHouseInformationService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetHouseID}");
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $url = $this->setUrl(5);
                   
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);
                    $url = str_replace('RandomStringBin',$RandomStringBin,$url);
                    $url = str_replace('TargetHouseID',$TargetHouseID,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา
                    $responseData = $this->transfer_model->curlGet($url);
                    $request['result'] = $responseData;

                    $request['result']['message'] = $responseData->{'message'};
                    $request['result']['code'] = $responseData->{'code'};

/*                    if($request['result']['code']=='0') { //Update Data 
                        $rows = $this->transfer_model->getAll_elementWSV(5,'elem_name');

                        $addr_update = array();
                        $addr_update['wsrv_staff_pid'] = get_session('user_id');
                        $addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

                        foreach($rows as $row) {
                            if($row['elem_name']!='') {
                                $addr_update[$row['elem_name']] = $responseData->{$row['elem_src_name']};
                            }
                        }
                        $this->common_model->update('pers_addr',$addr_update,array('addr_code'=>$responseData->{'HouseID'}));
                    }*/

                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,4,serialize($request),'Import','Success'); //Save Log

                    echo $responseData;
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,4,serialize($request),'Import','Fail'); //Save Log
                    echo json_encode($msg);//echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,4,serialize($request),'Import','Fail'); //Save Log  
                echo json_encode($msg);
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave(0,$OfficerPID,$time_start,4,serialize($request),'Import','Fail'); //Save Log
            echo json_encode($msg);
        }

    }

    /*-- 1.5] (N/A)Request for Government Welfare Service :  โครงการลงทะเบียนเพื่อสวัสดิการแห่งรัฐ --*/
    /*-- 1.6] (N/A)Request for Thai Population from Registration Record :  ข้อมูลสถิติประชากรไทยจากการทะเบียน จำแนกรายอายุ --*/

    /* End[1] Portal Gateway ฐานข้อมูลทะเบียนราษฐ์ (กรมการปกครอง กระทรวงมหาดไทย) */


    /* [2] Portal Gateway ฐานข้อมูลกลางความจำเป็นพื้นฐาน (จปฐ.) (กรมการพัฒนาชุมชน กระทรวงมหาดไทย) */
    
    /*-- 2.1] (N/A)Request for The Discrininant Indications of Rural People of the Quality of Lite : ข้อมูลดัชนีจำแนกตัวชี้วัดคุณภาพชีวิตของประชาชนในชนบท ตามเกณฑ์ความจำเป็นพื้นฐาน (จปฐ.) --*/

    /* End[2] Portal Gateway ฐานข้อมูลทะเบียนราษฐ์ (กรมการปกครอง กระทรวงมหาดไทย) */


    /* [3] Portal Gateway ฐานข้อมูลศูนย์บริการจัดหางานผู้สูงอายุ (กรมการจัดหางาน กระทรวงแรงงาน)* */

    /*-- 3.1] (N/A)Request for Job Vacancy : ข้อมูลตำแหน่งว่าง --*/
    private function set31($data=array()) {
        // org_runno       รหัสทะเบียนรายการของสถานประกอบการ
        // org_title       ชื่อสถานประกอบการ
        // org_type        ประเภทกิจการ
        // posi_type       ประเภทงานที่ต้องการ(Code)
        // posi_title      ชื่อตำแหน่งงาน
        // job_responsibility      ลักษณะงาน
        // posi_workday        วันทำงาน (จันทร์ - ศุกร์, จันทร์ - เสาร์, อื่น ๆ)
        // posi_workday_identify       วันทำงาน อื่น ๆ (ระบุ)
        // posi_worktime       เวลาทำงาน
        // posi_quota      จำนวนที่เปิดรับสมัคร (อัตรา)
        // age_between     อายุระหว่าง (ปี)
        // wage_rate       อัตราค่าจ้าง (บาท)
        // wage_per        อัตราค่าจ้าง ต่อ (เดือน, วัน, ชั่วโมง, ชิ้นงาน)
        // posi_gender     ระบุเพศ (ชาย, หญิง, ไม่ระบุ)
        // edu_code        รหัสระดับการศึกษา(Code)
        // addr_province       สถานที่ปฏิบัติงาน รหัสพื้นที่ (จังหวัด)(Code)
        // posi_experience     ประสบการณ์ทำงาน (ปี)
        // date_of_post        วันที่เปิดรับสมัคร (YYYYMMDD)
        // date_of_close       วันที่ปิดรับสมัคร (YYYYMMDD)
    }
    public function RequestJobVacancy_get($Username='', $Password='', $OfficerPID='', $TargetPID=''){

        try {
            $response = Requests::get("");
            $responseData = $response->body; // ได้ข้อมูล json กลับมา

            //$this->set31(json_decode($responseData));

            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    /*-- 3.2] (N/A)Request for Older Employment Registration : ข้อมูลผู้สูงอายุที่ขึ้นทะเบียนจัดหางาน --*/
    private function set32($data=array()) {
        // pid     เลขประจำตัวประชาชน (13 หลัก) ของผู้ขึ้นทะเบียน
        // date_of_reg     วันที่ขึ้นทะเบียนจัดหางาน (YYYYMMDD)
        // exp_code        รหัสสาขาความเชี่ยวชาญ(Code)
        // prev_work       งานที่เคยทำก่อนอายุ 60 ปี
        // org_type        ประเภทงานที่ต้องการ (มีรายได้, ไม่มีรายได้)
        // reg_status      สถานะการได้รับงาน (ได้งานทำแล้ว, ยังไม่ได้งาน)
    }
    public function RequestOlderEmploymentRegistration_get($Username='', $Password='', $OfficerPID='', $TargetPID=''){

        try {
            $response = Requests::get("");
            $responseData = $response->body; // ได้ข้อมูล json กลับมา

            //$this->set32(json_decode($responseData));

            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    /* End[3] Portal Gateway ฐานข้อมูลศูนย์บริการจัดหางานผู้สูงอายุ (กรมการจัดหางาน กระทรวงแรงงาน)* */


    /* [4] Portal Gateway ฐานข้อมูลอาสาสมัครดูแลผู้สูงอายุ (กรมพัฒนาสังคมและสวัสดิการ กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์) */
    
    /*-- 4.1] (N/A)Request for Elder Care Calunteers : ข้อมูลทะเบียนอาสาสมัครดูลผู้สูงอายุ (อผส.) --*/

    /* End[4] Portal Gateway ฐานข้อมูลอาสาสมัครดูแลผู้สูงอายุ (กรมพัฒนาสังคมและสวัสดิการ กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์) */


    /* [5] Portal Gateway ฐานข้อมูลคลังปัญญาผู้สูงอายุ (กรมกิจการผู้สูงอายุ กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์) */
    
    /*-- 5.1] (N/A)Request for Eldery Wisdom : ข้อมูลประวัติการขึ้นทะเบียนคลังปัญญาผู้สูงอายุ --*/

    /* End[5] Portal Gateway ฐานข้อมูลคลังปัญญาผู้สูงอายุ (กรมกิจการผู้สูงอายุ กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์) */


    /* [6] Portal Gateway ฐานข้อมูลกองทุนผู้สูงอายุ (กรมกิจการผู้สูงอายุ กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์) */

    /*-- 6.1] (N/A)Request for Eldery Foundation : ข้อมูลประวัติการกู้ยืมกองทุน และสถานะสัญญา --*/
    private function set61($data=array()) {
        //pid     เลขประจำตัวประชาชน (13 หลัก) ของผู้รับบริการ
        //loan_history        ประวัติการกู้ยืมกองทุน (มีประวัติ, ไม่มีประวัติ)
        //apprv_province  province_code_approv    รหัสพื้นที่ (จังหวัดที่อนุมัติ)(Code)
        //year_of_contract    year_contract   ปีที่ทำสัญญา (ค.ศ.) (YYYY)
        //contract_status status  สถานะสัญญา (ยังมีสัญญา, ปิดสัญญาแล้ว)
    }
    public function RequestElderyFoundation_get($Username='', $Password='', $OfficerPID='', $TargetPID=''){

        try {
            //$response = @Requests::get("https://gateway.dop.go.th/transfer/import/RequestRandomService/{$OfficerPID}/{$OfficerCID}");
            $response = Requests::get("http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861");
            //"http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861"
            $responseData = $response->body; // ได้ข้อมูล json กลับมา

            //$this->set61(json_decode($responseData));

            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
/*    public function RequestElderyFoundation_post() {
        $Username = get_inpost('Username');
        $Password = get_inpost('Password');
        $OfficerPID = get_inpost('OfficerPID');
        $TargetPID = get_inpost('TargetPID');

        try {
            //$response = @Requests::get("https://gateway.dop.go.th/transfer/import/RequestRandomService/{$OfficerPID}/{$OfficerCID}");
            $response = @Requests::get("http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861");
            //"http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861"
            $responseData = $response->body; // ได้ข้อมูล json กลับมา

            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/

    /*-- 6.2] (N/A)Request for Thai Eldery Foundation : ข้อมูลสถิติการกู้ยืมกองทุนผู้สูงอายุ --*/
    private function set62($data=array()) {
        // year_of_req budgetyear  ปีที่ยื่นคำร้อง (ค.ศ.) (YYYY)
        // gender_code gender_code เพศ (0 ไม่ทราบ, 1 ชาย, 2 หญิง, 9 ไม่สามารถระบุได้)(Code)
        // age_of_brrw age_borrowers   อายุ (ปี) ของผู้ยื่นคำร้อง
        // addr_sub_district   tambon  ที่อยู่ปัจจุบัน รหัสพื้นที่ (ตำบล)(Code)
        // addr_district   ampur   ที่อยู่ปัจจุบัน รหัสพื้นที่ (อำเภอ)(Code)
        // addr_province   province    ที่อยู่ปัจจุบัน รหัสพื้นที่ (จังหวัด)(Code)
        // addr_zipcode    postal_code ที่อยู่ปัจจุบัน รหัสไปรษณีย์
        // occupation  category_occupation อาชีพ
        // revenue revenue รายได้ (บาท/ปี)
        // date_of_cons    considered  วันที่พิจารณา
        // cons_result_code    result_code รหัสปัญหาผลการพิจารณา(Code)
        // cons_result result  ผลการพิจารณา
        // credit_line credit_line วงเงินอนุมัติ (บาท)
        // apprv_province  province_approv รหัสพื้นที่ (จังหวัดที่อนุมัติ)(Code)
        // year_of_contract    year_contract   ปีที่ทำสัญญา (ค.ศ.) (YYYY)
        // contract_status     สถานะสัญญา (ยังมีสัญญา, ปิดสัญญาแล้ว) 
    }
    public function RequestThaiElderyFoundation_get($Username='', $Password='', $OfficerPID='', $TargetPID=''){

        try {
            //$response = @Requests::get("https://gateway.dop.go.th/transfer/import/RequestRandomService/{$OfficerPID}/{$OfficerCID}");
            $response = Requests::get("http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861");
            //"http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861"
            $responseData = $response->body; // ได้ข้อมูล json กลับมา

            //$this->set62(json_decode($responseData));

            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
/*    public function RequestThaiElderyFoundation_post() {
        $Username = get_inpost('Username');
        $Password = get_inpost('Password');
        $OfficerPID = get_inpost('OfficerPID');
        $TargetPID = get_inpost('TargetPID');

        try {
            //$response = @Requests::get("https://gateway.dop.go.th/transfer/import/RequestRandomService/{$OfficerPID}/{$OfficerCID}");
            $response = @Requests::get("http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861");
            //"http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861"
            $responseData = $response->body; // ได้ข้อมูล json กลับมา

            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/

    /* End[6] Portal Gateway ฐานข้อมูลกองทุนผู้สูงอายุ (กรมกิจการผู้สูงอายุ กระทรวงการพัฒนาสังคมและความมั่นคงของมนุษย์) */


    //Example
/*    public function RequestDOPolderWisdom_get($Username='',$Password='', $OfficerPID='') {
        try {
            $responseData = json_encode(array('result'=>'success'));
            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }  
    public function RequestDOPolderWisdom_post() {
        try {
            $Username = get_inpost('Username');
            $Password = get_inpost('Password');
            $OfficerPID = get_inpost('OfficerPID');

            $responseData = json_encode(array('result'=>'success'));
            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
*/


}
