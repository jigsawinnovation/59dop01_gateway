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

    //27d6758f1e34c0422427212e8d3ba4c5

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


/*    public function test_get() {

        $this->response(array(
                                "CID"=>"",
                                "PID"=>"",
                                "Random"=>"3239663864316539316431313939353933356334663834636130396234353366",
                                "RandomBin"=>"29f8d1e91d11995935c4f84ca09b453f",
                                "RandomBinLength"=>"32",
                                "RandomLength"=>"64",
                                "code"=>"0",
                                "message"=>""
                ), 200);

    }*/

    public function index_get()
    {
        //ob_end_clean();
    }

    private function setUrl($wsrv_info=0) {
        $wsrv_info = $this->transfer_model->getOnce_wsrv_info($wsrv_info);
        $url = $wsrv_info['wsrv_url'].$wsrv_info['wsrv_req_template'];
        $url = str_replace("{","",$url);
        $url = str_replace("}","",$url);
        return $url;
    }
    private function setUrl1($wsrv_info=0) {
        $wsrv_info = $this->transfer_model->getOnce_wsrv_info($wsrv_info);
        $url = $wsrv_info['wsrv_url'];
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

    private function setPersonalInfo($pid='',$cid='',$random_string='',$target_pid='',$user_id='0',$org_id='0') {
        header('Access-Control-Allow-Origin: *');  
        
        $arr = array();
        if($target_pid!=0) {
            $target_pid = str_replace("-","",$target_pid);
            $this->load->model(array('personal_model'));
            $tmp = $this->personal_model->getOnce_PersonalInfo_byCode($target_pid); //Get Personal Info by us

            if(isset($tmp['pid'])) {
                if($tmp['wsrv_staff_datetime']=='' || (strtotime($tmp['wsrv_staff_datetime'])<strtotime('-1 month'))) { //Old Data

                    /*- Get Data by Webservice -*/
                    //$pers_tmp = $this->personal_model->getOnce_DemoPersonalInfo_byCode($target_pid); //get wsrv
                    $pers_tmp = array();
                    $addr_update = array();
                    $pinfo_tmp = $this->RequestPersonalInformationCodeService_get($pid, $cid, $random_string, $target_pid); //get wsrv personal info
                    $pinfo_death_tmp = $this->RequestPersonalInformationService_get($pid, $cid, $random_string, $target_pid); //get wsrv personal death info

                    if($pinfo_tmp['code']=='0') {
                        $pers_tmp = $pinfo_tmp['pers_tmp'];
                        $addr_tmp = $pinfo_tmp['addr_update'];
                        $pers_tmp['addr_code'] = $addr_tmp['addr_code'];
                    }
                    if($pinfo_death_tmp['code']=='0') {
                        foreach($pinfo_death_tmp['pers_tmp'] as $key=>$data) {
                           $pers_tmp[$key] = $data;
                        }
                    }

                    /*- End Get Data by Webservice -*/

                    if(isset($pers_tmp['pid'])) { //wsrv 
                        $pers_tmp['pre_addr_id'] = $tmp['pre_addr_id'];
                        $pers_tmp['wsrv_staff_pid'] = $pid;
                        $pers_tmp['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

                        //Update Personal Address
                        $addr_addnew_id = 0;
                        //$addr_tmp = array();
                        if($pers_tmp['addr_code']!=0) {
                            $addr_tmp_old = $this->personal_model->getOnce_strPersonalAddress_byCode($pers_tmp['addr_code']); 
                            if(isset($addr_tmp_old['addr_code'])) {
                                if($addr_tmp_old['wsrv_staff_datetime']=='' || (strtotime($addr_tmp_old['wsrv_staff_datetime'])<strtotime('-1 month'))) {

                                    /*- Get Data by Webservice -*/
                                    //$addr_update = $this->personal_model->getOnce_DemoPersonalAddress_byCode($pers_tmp['addr_code']); //get wsrv
                                    
                                    $addr_update = $addr_tmp;
                                    $addr_update_tmp = $this->RequestHouseInformationService_get($pid, $cid, $random_string, $pers_tmp['addr_code']);
                                    foreach($addr_update_tmp['addr_update'] as $key=>$data) {
                                        $addr_update[$key] = $data;
                                    }
                                    /*- End Get Data by Webservice -*/

                                    if(isset($addr_update['addr_code'])) {
                                        //unset($addr_update['addr_id']);
                                        $addr_update['wsrv_staff_pid'] = $user_id;
                                        $addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

    /*                                        if(!isset($addr_tmp['addr_id'])) {//No Data then Add
                                                $addr_update['insert_user_id'] = $user_id;
                                                $addr_update['insert_datetime'] = date("Y-m-d H:i:s");
                                                $addr_update['insert_org_id'] = $org_id;
                                                $addr_addnew_id = $this->common_model->insert('pers_addr',$addr_update);
                                                $pers_tmp['reg_addr_id'] = $addr_addnew_id;
                                            }else {*/
                                        $this->common_model->update('pers_addr',$addr_update,array('addr_id'=>$addr_tmp_old['addr_id']));
                                        $pers_tmp['reg_addr_id'] = $addr_tmp_old['addr_id'];
                                            //}
                                    }

                                }
                            }else { //No Address Info Then Add
                                /*- Get Data by Webservice -*/
                                //$addr_update = $this->personal_model->getOnce_DemoPersonalAddress_byCode($pers_tmp['addr_code']); //get wsrv
                                $addr_update = $addr_tmp;
                                $addr_update_tmp = $this->RequestHouseInformationService_get($pid, $cid, $random_string, $pers_tmp['addr_code']);
                                foreach($addr_update_tmp['addr_update'] as $key=>$data) {
                                    $addr_update[$key] = $data;
                                }
                                /*- End Get Data by Webservice -*/
                                    
                                if(isset($addr_update['addr_code'])) {
                                    //unset($addr_update['addr_id']);
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
                        //unset($pers_tmp['pers_id']);

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
                //Append to 

                /*- Get Data by Webservice -*/
                //$pers_tmp = $this->personal_model->getOnce_DemoPersonalInfo_byCode($target_pid); //get wsrv
                $pers_tmp = array();
                $addr_update = array();

                $pinfo_tmp = $this->RequestPersonalInformationCodeService_get($pid, $cid, $random_string, $target_pid); //get wsrv personal info
                $pinfo_death_tmp = $this->RequestPersonalInformationService_get($pid, $cid, $random_string, $target_pid); //get wsrv personal death info

                if($pinfo_tmp['code']=='0') {
                    $pers_tmp = $pinfo_tmp['pers_tmp'];
                    $addr_tmp = $pinfo_tmp['addr_update'];
                    $pers_tmp['addr_code'] = $addr_tmp['addr_code'];
                }
                if($pinfo_death_tmp['code']=='0') {
                    foreach($pinfo_death_tmp['pers_tmp'] as $key=>$data) {
                       $pers_tmp[$key] = $data;
                    }
                }

                /*- End Get Data by Webservice -*/

                if(isset($pers_tmp['pid'])) { //wsrv 
                    //unset($pers_tmp['pre_addr_id']);
                    $pers_tmp['insert_user_id'] = $user_id;
                    $pers_tmp['insert_datetime'] = date("Y-m-d H:i:s");
                    $pers_tmp['insert_org_id'] = $org_id;
                    $pers_tmp['wsrv_staff_pid'] = $pid;
                    $pers_tmp['wsrv_staff_datetime'] = date("Y-m-d H:i:s");
                    //unset($pers_tmp['pers_id']);

                    //Update Personal Address
                    $addr_addnew_id = 0;
                    $addr_tmp_old = array();
                    if($pers_tmp['addr_code']!=0) {
                        $addr_tmp_old = $this->personal_model->getOnce_strPersonalAddress_byCode($pers_tmp['addr_code']);
                        if(isset($addr_tmp_old['addr_code'])) {
                            if($addr_tmp_old['wsrv_staff_datetime']=='' || (strtotime($addr_tmp_old['wsrv_staff_datetime'])<strtotime('-1 month'))) {
                                
                                /*- Get Data by Webservice -*/
                                //$addr_update = $this->personal_model->getOnce_DemoPersonalAddress_byCode($pers_tmp['addr_code']); //get wsrv
                                $addr_update_tmp = $this->RequestHouseInformationService_get($pid, $cid, $random_string, $pers_tmp['addr_code']);
                                $addr_update = $addr_tmp;
                                foreach($addr_update_tmp['addr_update'] as $key=>$data) {
                                    $addr_update[$key] = $data;
                                }
                                /*- End Get Data by Webservice -*/

                                if(isset($addr_update['addr_code'])) {
                                    //unset($addr_update['addr_id']);
                                    $addr_update['wsrv_staff_pid'] = $pid;
                                    $addr_update['wsrv_staff_datetime'] = date("Y-m-d H:i:s");

    /*                                    if(!isset($addr_tmp['addr_id'])) {//No Data then Add
                                            $addr_update['insert_user_id'] = $user_id;
                                            $addr_update['insert_datetime'] = date("Y-m-d H:i:s");
                                            $addr_update['insert_org_id'] = $org_id;
                                            $addr_addnew_id = $this->common_model->insert('pers_addr',$addr_update);
                                            $pers_tmp['reg_addr_id'] = $addr_addnew_id;
                                        }else {*/
                                    $this->common_model->update('pers_addr',$addr_update,array('addr_id'=>$addr_tmp_old['addr_id']));
                                    $pers_tmp['reg_addr_id'] = $addr_tmp_old['addr_id'];
                                        //}
                                }

                            }
                        }else { //No Address Info Then Add
                            /*- Get Data by Webservice -*/
                            //$addr_update = $this->personal_model->getOnce_DemoPersonalAddress_byCode($pers_tmp['addr_code']); //get wsrv

                            $addr_update_tmp = $this->RequestHouseInformationService_get($pid, $cid, $random_string, $pers_tmp['addr_code']);

                            $addr_update = $addr_tmp;
                            foreach($addr_update_tmp['addr_update'] as $key=>$data) {
                                $addr_update[$key] = $data;
                            }
                            /*- End Get Data by Webservice -*/

                            if(isset($addr_update['addr_code'])) {
                                //unset($addr_update['addr_id']);
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
        $this->load->database();
        $this->load->model('admin_model');
        $app_id = 156;
        $this->transfer_model->LogSave($app_id,'View','Sign In','Success',get_inpost('user_id'),get_inpost('org_id')); //Save Sign In Log
        $usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,get_inpost('user_id')); //Check User Permission

        if(@$usrpm['perm_status']=='No' || !isset($usrpm['app_id'])) {
            $this->transfer_model->LogSave($app_id,'View','Sign Out','Fail',get_inpost('user_id'),get_inpost('org_id')); //Save Sign In Log
            $this->response(NULL, REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
        }else if(get_inpost('pid')!='' && get_inpost('cid')!='' && get_inpost('random_string')!='' && get_inpost('target_pid')!='' && get_inpost('user_id')!='') {
            $pid = get_inpost('pid');
            $cid = get_inpost('cid');
            $random_string = get_inpost('random_string');
            $target_pid = get_inpost('target_pid');
            $user_id = get_inpost('user_id');
            $org_id = get_inpost('org_id');

            $pers_info = $this->setPersonalInfo($pid,$cid,$random_string,$target_pid,$user_id,$org_id);

            if(isset($pers_info['father_pid'])) {
                //$father_pid = $this->setPersonalInfo($pid,$cid,$random_string,$target_pid,$user_id,$org_id);
            }
            if(isset($pers_info['mother_pid'])) {
                //$mather_pid = $this->setPersonalInfo($pid,$cid,$random_string,$target_pid,$user_id,$org_id);
            }
            $this->transfer_model->LogSave($app_id,'View','Sign Out','Success',get_inpost('user_id'),get_inpost('org_id')); //Save Sign In Log
            $this->response($pers_info,200);
        }else {
            $this->transfer_model->LogSave($app_id,'View','Sign Out','Fail',get_inpost('user_id'),get_inpost('org_id')); //Save Sign In Log
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code 
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
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
        );

        $request['param'] = array('OfficerPID'=>$OfficerPID,'OfficerCID'=>$OfficerCID);

        if($OfficerPID=='' || $OfficerCID=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,1,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id'])) {
            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],1)) {
                try {
                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestRandom.ICTCMSORequestRandomService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}");
                        
                    $url = $this->setUrl(1);
                       
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา
                    
                    $responseData = $this->transfer_model->curlGet($url);
                    $responseData_decode = json_decode($responseData);
                    //Demo Samble Data webservice   
/*                    $responseData = json_encode(array(
                        "CID"=>"",
                        "PID"=>"",
                        "Random"=>"3239663864316539316431313939353933356334663834636130396234353366",
                        "RandomBin"=>"29f8d1e91d11995935c4f84ca09b453f",
                        "RandomBinLength"=>"32",
                        "RandomLength"=>"64",
                        "code"=>"0",
                        "message"=>""
                    ));
                    $responseData_decode = json_decode($responseData);*/
                    //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                           $responseData[$key] = $value;
                    }
                    $request['result'] = $responseData;


                    //$request['result']['message'] = $responseData_decode->{'message'};
                    //$request['result']['code'] = $responseData_decode->{'code'};
                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test

                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,1,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    $this->response($responseData,200);
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,1,serialize($request),'Import','Fail'); //Save Log
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                 $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,1,serialize($request),'Import','Fail'); //Save Log  
                $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,1,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
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
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
        );
        $request['param'] = array('OfficerPID'=>$OfficerPID,'OfficerCID'=>$OfficerCID,'RandomStringBin'=>$RandomStringBin,'EnvelopString'=>$EnvelopString,'EnvelopStringLength'=>$EnvelopStringLength);

        if($OfficerPID=='' || $OfficerCID=='' || $RandomStringBin=='' || $EnvelopString=='' || $EnvelopStringLength=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,2,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],2)) {
                try {
                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestAuthen.ICTCMSORequestAuthenticationService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$EnvelopString}/{$EnvelopStringLength}");
                        
                    $url = $this->setUrl(2);   

                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);
                    $url = str_replace('paraRandomStringBin',$RandomStringBin,$url);
                    $url = str_replace('paraEnvelopStringLength',$EnvelopStringLength,$url); //paraEnvelopStringLength
                    $url = str_replace('paraEnvelopString',$EnvelopString,$url);
                    
                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $responseData = $this->transfer_model->curlGet($url);
                    $responseData_decode = json_decode($responseData);

                    //Demo Samble Data webservice   
/*                    $responseData = json_encode(array(
                        "CID"=>"",
                        "PID"=>"",
                        "AuthenRBUF"=>"908100000984f812a1f0e05ef597c854f208e6569",
                        "AuthenRBUFLength"=>"41",
                        "AuthenRetB"=>"",
                        "AuthenticationString"=>"984f812a1f0e05ef597c854f208e6569",
                        "AuthenticationStringLength"=>"32",
                        "code"=>"0",
                        "message"=>"0000"
                    ));
                    $responseData_decode = json_decode($responseData);*/
                    //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                        $responseData[$key] = $value;
                    }
                    $request['result'] = $responseData;

                    //$request['result']['message'] = $responseData_decode->{'message'};
                    //$request['result']['code'] = $responseData_decode->{'code'};

                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test
                        
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,2,serialize($request),'Import','Success'); //Save Log

                        //echo $responseData;

                    $this->response($responseData,200);
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,2,serialize($request),'Import','Fail'); //Save Log
                        //echo json_encode($msg);//echo 'Caught exception: ',  $e->getMessage(), "\n";
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,2,serialize($request),'Import','Fail'); //Save Log  
                //echo json_encode($msg);
                 $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,2,serialize($request),'Import','Fail'); //Save Log
                //echo json_encode($msg);
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
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
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
            );

        $request['param'] = array('OfficerPID'=>$OfficerPID, 'OfficerCID'=>$OfficerCID, 'RandomStringBin'=>$RandomStringBin, 'TargetPID'=>$TargetPID);

        if($OfficerPID=='' || $OfficerCID=='' || $RandomStringBin=='' || $TargetPID=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log
            //$this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code  
            return $request['result'];  
        }else if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],3)) {
                try {

                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformationCode.svc/ICTCMSORequestPersonalInformationCodeService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetPID}");
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

/*https://10.6.4.74:1443/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformationCode.svc/ICTCMSORequestPersonalInformationCodeService/data/DOPTestUser1/DOPTestUser1/3101701933555/0221004350953232/29f8d1e91d11995935c4f84ca09b453f/1550700081881*/

                    $url = $this->setUrl(3);
                   
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);
                    $url = str_replace('paraRandomStringBin',$RandomStringBin,$url);
                    $url = str_replace('paraTargetPID',$TargetPID,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $responseData = $this->transfer_model->curlGet($url);
                    $responseData_decode = json_decode($responseData);

                     //Demo Samble Data webservice   
/*                        $responseData = json_encode(array(
                            "age"=>"060",
                            "alleyCode"=>"0000",
                            "changenationalityCode"=>"0",
                            "changenationalitydate"=>"00000000",
                            "code"=>"0",
                            "dateofbirth"=>"24990922",
                            "districtCode"=>"01",
                            "fathername"=>"ยิ่งป่า ",
                            "fathernationalityCode"=>"044",
                            "fatherpid"=>"0 ",
                            "firstName"=>"ยลศิลป์ ",
                            "genderCode"=>"1",
                            "homestatusCode"=>"001",
                            "houseid"=>"12990430211",
                            "housenumber"=>"12 ",
                            "lastName"=>"สุชนวนิช ",
                            "message"=>"",
                            "moo"=>"00",
                            "mothername"=>"สุมล ",
                            "mothernationalityCode"=>"044",
                            "motherpid"=>"3449900167806",
                            "moveindate"=>"25410422",
                            "nationalityCode"=>"099",
                            "optFName"=>"ยลศิลป์ ",
                            "optFatherName"=>"ยิ่งป่า ",
                            "optLName"=>"สุชนวนิช ",
                            "optMName"=>" ",
                            "optMotherName"=>"สุมล ",
                            "optPStatCode"=>"00",
                            "personstatusCode"=>"0", ////
                            "pid"=>"3449900204761",
                            "provinceCode"=>"12",
                            "roadCode"=>"0000",
                            "soiCode"=>"1478",
                            "subdistrictCode"=>"02",
                            "titleCode"=>"003"
                        ));
                        $responseData_decode = json_decode($responseData);*/
                        //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                        $responseData[$key] = trim($value);
                    }
                    $request['result'] = $responseData;

                    //$request['result']['message'] = $responseData_decode->{'message'};
                    //$request['result']['code'] = $responseData_decode->{'code'};   

                    $result = array('code'=>$request['result']['code'],'message'=>$request['result']['message']);
                    if($result['code']=='0') { //Update Data 
                        $rows = $this->transfer_model->getAll_elementWSV(3,'Element');
                        $pers_tmp = array();
                        $addr_update = array();
                        foreach($rows as $key=>$value) {
                            if(trim($value['elem_name'])!='') {
                                if(iconv_substr(trim($value['elem_name']),0,5,"UTF-8")!='addr_') {  
                                    $pers_tmp[trim($value['elem_name'])] = trim($request['result'][trim($value['elem_src_name'])]);
                                }else {
                                    $addr_update[trim($value['elem_name'])] = trim($request['result'][trim($value['elem_src_name'])]);
                                }
                            }
                        }

                        $addr_update['addr_sub_district'] = isset($addr_update['addr_sub_district'])==true?$addr_update['addr_sub_district']:'00';
                        $addr_update['addr_district'] = isset($addr_update['addr_district'])==true?$addr_update['addr_district']:'00';
                        $addr_update['addr_province'] = isset($addr_update['addr_province'])==true?$addr_update['addr_province']:'00';

                        $addr_update['addr_sub_district'] = $addr_update['addr_province'].$addr_update['addr_district'].$addr_update['addr_sub_district'].'00';
                        $addr_update['addr_district'] = $addr_update['addr_province'].$addr_update['addr_district'].'0000';
                        $addr_update['addr_province'] = $addr_update['addr_province'].'000000';

                        if(trim($pers_tmp['date_of_birth'])!='') {

                            $year = iconv_substr(trim($pers_tmp['date_of_birth']),0,4,"UTF-8");
                            settype($year, "integer");
                            $year = $year-543;
                            $pers_tmp['date_of_birth'] = $year.'-'.iconv_substr(trim($pers_tmp['date_of_birth']),4,2,"UTF-8").'-'.iconv_substr(trim($pers_tmp['date_of_birth']),6,2,"UTF-8");
                        }

                        $result['pers_tmp'] = $pers_tmp;
                        $result['addr_update'] = $addr_update;
                        //$this->common_model->update('pers_info',$pers_tmp,array('pid'=>$responseData->{'pid'}));
                        //if($responseData->{'houseid'}!='') {
                        //    $this->common_model->update('pers_addr',$addr_update,array('addr_code'=>$responseData->{'houseid'}));
                        //}
                    }

                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test
                    
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    //$this->output->set_content_type('application/json')->set_output($responseData);
                    //$this->response($responseData,200);
                    //dieArray($result);
                    return $result;
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log
                    //$this->output->set_content_type('application/json')->set_output($request['result']);
                    return $request['result'];
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log  
                //$this->output->set_content_type('application/json')->set_output($request['result']);
                return $request['result'];
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,3,serialize($request),'Import','Fail'); //Save Log
            //$this->output->set_content_type('application/json')->set_output($request['result']);
            return $request['result'];
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
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
            );
        $request['param'] = array('OfficerPID'=>$OfficerPID, 'OfficerCID'=>$OfficerCID, 'RandomStringBin'=>$RandomStringBin, 'TargetPID'=>$TargetPID);

        if($OfficerPID=='' || $OfficerCID=='' || $RandomStringBin=='' || $TargetPID=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,4,serialize($request),'Import','Fail'); //Save Log
            //$this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code  
            return $request['result'];  
        }else if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],4)) {
                try {
                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestPersonalInformation.svc/ICTCMSORequestPersonalInformationService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetPID}");
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $url = $this->setUrl(4);
                   
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);
                    $url = str_replace('paraRandomStringBin',$RandomStringBin,$url);
                    $url = str_replace('paraTargetPID',$TargetPID,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา
                    
                    $responseData = $this->transfer_model->curlGet($url);
                    $responseData_decode = json_decode($responseData);

                     //Demo Samble Data webservice   
/*                        $responseData = json_encode(array(
                            "age"=>"087",
                            "alley"=>"",
                            "changenationality"=>" ",
                            "changenationalitydate"=>"00000000",
                            "code"=>"0",
                            "dateofbirth"=>"24730517",
                            "district"=>"เขตจตุจักร",
                            "fathername"=>"อยู่ดวง ",
                            "fathernationality"=>"จีน ",
                            "fatherpid"=>"0 ",
                            "firstName"=>"สุมล ",
                            "gender"=>"หญิง",
                            "homestatus"=>"ผู้อาศัย ",
                            "houseid"=>"12990430211", //10051129281
                            "housenumber"=>"2/236",
                            "lastName"=>"สุชนวนิช ",
                            "message"=>"",
                            "moo"=>"00",
                            "mothername"=>"แชอิน ",
                            "mothernationality"=>"จีน ",
                            "motherpid"=>"0 ",
                            "moveindate"=>"25400205",
                            "nationality"=>"จีน ",
                            "personstatus"=>"ตาย (14/04/2557) ", ////
                            "pid"=>"3449900204761", //3449900167806
                            "province"=>"กรุงเทพมหานคร",
                            "reserve"=>"ท้องถิ่นเขตจตุจักร 255704190000000001สุมล สุชนวนิช อยู่ดวง แชอิน ",
                            "road"=>"",
                            "soi"=>"พหลโยธิน40",
                            "subdistrict"=>"เสนานิคม",
                            "title"=>"นาง "
                        ));
                        $responseData_decode = json_decode($responseData);*/
                        //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                        $responseData[$key] = trim($value);
                    }
                    $request['result'] = $responseData;

                    //$request['result']['message'] = $responseData_decode->{'message'};
                    //$request['result']['code'] = $responseData_decode->{'code'};   
 
                    $result = array('code'=>$request['result']['code'],'message'=>$request['result']['message']);
                    if($result['code']=='0') { //Update Data 
                        $rows = $this->transfer_model->getAll_elementWSV(4,'Element');
                        $pers_tmp = array();
                        foreach($rows as $key=>$value) {
                            if(trim($value['elem_name'])!='') {
                                $pers_tmp[trim($value['elem_name'])] = trim($request['result'][trim($value['elem_src_name'])]);
                            }
                        }

                        if(iconv_substr(trim($pers_tmp['date_of_death']),0,3,"UTF-8")=='ตาย') { //personstatus: "ตาย (14/04/2557) ", ////
                            $year = iconv_substr(trim($pers_tmp['date_of_death']),11,4,"UTF-8");
                            settype($year, "integer");
                            $year = $year-543;
                            $pers_tmp['date_of_death'] = $year.'-'.iconv_substr(trim($pers_tmp['date_of_death']),8,2,"UTF-8").'-'.iconv_substr(trim($pers_tmp['date_of_death']),5,2,"UTF-8");
                        }else {
                            $pers_tmp['date_of_death'] = '';
                        }
                        //$this->common_model->update('pers_info',$pers_tmp,array('pid'=>$responseData->{'pid'}));
                        $result['pers_tmp'] = $pers_tmp;
                    }

                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test

                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    //$this->output->set_content_type('application/json')->set_output($responseData);
                    return $result;
                    //dieArray($result);
                    //$this->response($responseData,200);
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log
                    //$this->output->set_content_type('application/json')->set_output($msg);
                    return $request['result'];
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,3,serialize($request),'Import','Fail'); //Save Log  
                //$this->output->set_content_type('application/json')->set_output($msg);
                return $request['result'];
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,3,serialize($request),'Import','Fail'); //Save Log
            //$this->output->set_content_type('application/json')->set_output($msg);
            return $request['result'];
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
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
            );
        $request['param'] = array('OfficerPID'=>$OfficerPID, 'OfficerCID'=>$OfficerCID, 'RandomStringBin'=>$RandomStringBin, 'TargetHouseID'=>$TargetHouseID);
        if($OfficerPID=='' || $OfficerCID=='' || $RandomStringBin=='' || $TargetHouseID=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,5,serialize($request),'Import','Fail'); //Save Log
            //$this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code  
            return $request['result'];  
        }if(isset($authen_user['authen_id'])) {

            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],5)) {
                try {
                    //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestHouseInformation.svc/ICTCMSORequestHouseInformationService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}/{$RandomStringBin}/{$TargetHouseID}");
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $url = $this->setUrl(5);
                   
                    $url = str_replace('paraUserName',$this->UserBMSO,$url);
                    $url = str_replace('paraPassword',$this->PassBMSO,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraOfficerCID',$OfficerCID,$url);
                    $url = str_replace('paraRandomStringBin',$RandomStringBin,$url);
                    $url = str_replace('paraTargetHouseID',$TargetHouseID,$url);

                    //$response = Requests::get($url);
                    //$responseData = $response->body; // ได้ข้อมูล json กลับมา

                    $responseData = $this->transfer_model->curlGet($url);
                    $responseData_decode = json_decode($responseData);

                     //Demo Samble Data webservice   
/*                        $responseData = json_encode(array(
                            "Alley"=>" ",
                            "BuildingApproveDate"=>"25380809",
                            "BuildingLocation"=>"ไม่ระบุ",
                            "BuildingName"=>" ",
                            "CommunityName"=>" ",
                            "DepartmentName"=>"ท้องถิ่นเทศบาลนครนนทบุรี ",
                            "District"=>"เมืองนนทบุรี ",
                            "DocumentNumberOfApproval"=>" ",
                            "ExpireDate"=>"00000000",
                            "HouseID"=>"12990430211",
                            "HouseManner"=>" ",
                            "HouseNumber"=>"12 ",
                            "HouseSize"=>"000000",
                            "HouseType"=>"บ้าน ",
                            "HousingName"=>" ",
                            "LandHoldingDocument"=>" ",
                            "LandHoldingNumber"=>" ",
                            "LandHoldingSize"=>null,
                            "LivingSize"=>"00000000",
                            "Moo"=>"00",
                            "Province"=>"นนทบุรี ",
                            "Road"=>" ",
                            "Soi"=>"นนทบุรี 6 แยก 6/1 ",
                            "Subdistrict"=>"ตลาดขวัญ ",
                            "TelephoneNumber"=>" ", ////
                            "ZipCode"=>"00000",  ////
                            "code"=>"0",
                            "message"=>""
                        ));
                        $responseData_decode = json_decode($responseData);*/
                        //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                        $responseData[$key] = trim($value);
                    }
                    $request['result'] = $responseData;

                    //$request['result']['message'] = $responseData_decode->{'message'};
                    //$request['result']['code'] = $responseData_decode->{'code'};   

                    $result = array('code'=>$request['result']['code'],'message'=>$request['result']['message']);
                    if($result['code']=='0') { //Update Data 
                        $rows = $this->transfer_model->getAll_elementWSV(5,'Element');
                        $addr_update = array();
                        foreach($rows as $key=>$value) {
                            if(trim($value['elem_name'])!='') {
                                $addr_update[trim($value['elem_name'])] = trim($request['result'][trim($value['elem_src_name'])]);
                            }
                        }
                        //$this->common_model->update('pers_addr',$addr_update,array('addr_code'=>$responseData->{'HouseID'}));
                        $result['addr_update'] = $addr_update;
                    }

                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,4,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    //$this->output->set_content_type('application/json')->set_output($responseData);
                    //$this->response($responseData,200);
                    return $result;
                    //dieArray($result);
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,4,serialize($request),'Import','Fail'); //Save Log
                    //$this->output->set_content_type('application/json')->set_output($msg);
                    return $request['result'];
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,4,serialize($request),'Import','Fail'); //Save Log  
                //$this->output->set_content_type('application/json')->set_output($msg);
                return $request['result'];
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave(0,$OfficerPID,$time_start,4,serialize($request),'Import','Fail'); //Save Log
            //$this->output->set_content_type('application/json')->set_output($msg);
            return $request['result'];
        }

    }

    /*-- 1.5] (N/A)Request for Government Welfare Service :  โครงการลงทะเบียนเพื่อสวัสดิการแห่งรัฐ --*/
    /*-- 1.6] (N/A)Request for Thai Population from Registration Record :  ข้อมูลสถิติประชากรไทยจากการทะเบียน จำแนกรายอายุ --*/
    public function RequestThaiPopulationRegistrationRecord_get($year_of_stat=''){
        $year_of_stat = date("Y");

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID('3100602549624');
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
        );

        $request['param'] = array('year_of_stat'=>$year_of_stat);

        if($year_of_stat=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,7,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id'])) {
            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],7)) {
                try {                 

                    $url = $this->setUrl1(7);
                    $headers = array('Content-Type' => 'application/json');
                    $data = array(
                        "year_of_stat"=>$year_of_stat
                    );
                    $response = Requests::post($url, $headers, json_encode($data));
                    $responseData = $response->body; // ได้ข้อมูล json กลับมา
                    // แปลงข้อมูลกลับ และให้เป็น array
                    $responseData_decode = json_decode($responseData,true);

                    //Demo Samble Data webservice   
/*                    $responseData[] = json_encode(array(
                        "pid"=>"1550700081881",
                        "addr_code"=>"",
                        "occupation"=>"2006",
                        "year_of_survey"=>"โปรแกรมเมอร์",
                        "yearly_income_of_family"=>"28,000",
                        "code"=>"0",
                        "message"=>""
                    ));
                    $responseData_decode = json_decode($responseData);*/
                    //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                           $responseData[$key] = $value;
                    }
                    $request['result'] = $responseData;


                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,7,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    $this->response($responseData,200);

                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,7,serialize($request),'Import','Fail'); //Save Log
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                 $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,7,serialize($request),'Import','Fail'); //Save Log  
                $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,7,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
        }   

    }

    /* End[1] Portal Gateway ฐานข้อมูลทะเบียนราษฐ์ (กรมการปกครอง กระทรวงมหาดไทย) */


    /* [2] Portal Gateway ฐานข้อมูลกลางความจำเป็นพื้นฐาน (จปฐ.) (กรมการพัฒนาชุมชน กระทรวงมหาดไทย) */
    
    /*-- 2.1] (N/A)Request for The Discrininant Indications of Rural People of the Quality of Lite : ข้อมูลดัชนีจำแนกตัวชี้วัดคุณภาพชีวิตของประชาชนในชนบท ตามเกณฑ์ความจำเป็นพื้นฐาน (จปฐ.) --*/
    public function RequestElderyJPTH_post() {
        $Username = get_inpost('Username'); //dopjpth
        $Password = get_inpost('Password'); //dpuser
        $OfficerPID = get_inpost('OfficerPID');
        $addr_home_no = get_inpost('addr_home_no');
        $addr_sub_district = get_inpost('addr_sub_district');

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
        );

        $request['param'] = array('Username'=>$Username, 'Password'=>$Password, 'OfficerPID'=>$OfficerPID, 'addr_home_no'=>$addr_home_no, 'addr_sub_district'=>$addr_sub_district);

        if($Username=='' || $Password=='' || $OfficerPID=='' || $addr_home_no=='' || $addr_sub_district=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,8,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id'])) {
            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],8)) {
                try {                 

                    $url = $this->setUrl1(8);
/*                    str_replace("-","",get_inpost('pid')),get_inpost('passcode')
                    $url = str_replace('paraUserName',$Username,$url);
                    $url = str_replace('paraPassword',$Password,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('addr_home_no',$addr_home_no,$url);
                    $url = str_replace('addr_sub_district',$addr_sub_district,$url);
*/

                    $headers = array('Content-Type' => 'application/json');
                    $data = array(
                        'paraUserName'=>$Username,
                        'paraPassword'=>$Password,
                        "paraOfficerPID"=>$OfficerPID,
                        "addr_home_no"=>$addr_home_no,
                        "addr_sub_district"=>$addr_sub_district,
                    );
                    $response = Requests::post($url, $headers, json_encode($data));
                    $responseData = $response->body; // ได้ข้อมูล json กลับมา
                    // แปลงข้อมูลกลับ และให้เป็น array
                    $responseData_decode = json_decode($responseData,true);

                    //Demo Samble Data webservice   
/*                    $responseData[] = json_encode(array(
                        "pid"=>"1550700081881",
                        "addr_code"=>"",
                        "occupation"=>"2006",
                        "year_of_survey"=>"โปรแกรมเมอร์",
                        "yearly_income_of_family"=>"28,000",
                        "code"=>"0",
                        "message"=>""
                    ));
                    $responseData_decode = json_decode($responseData);*/
                    //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                           $responseData[$key] = $value;
                    }
                    $request['result'] = $responseData;


                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,8,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    $this->response($responseData,200);
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,8,serialize($request),'Import','Fail'); //Save Log
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                 $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,8,serialize($request),'Import','Fail'); //Save Log  
                $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,8,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
        }

    }
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
    public function RequestJobVacancy_get($is_non_profit_job='', $data_form='', $date_to='', $province_id=0){
        $data_form = date("01mY"); //set inizial
        $date_to=date("tmY"); //set inizial

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID('3100602549624');
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
        );

        $request['param'] = array('is_non_profit_job'=>$is_non_profit_job, 'data_form'=>$data_form, 'date_to'=>$date_to, 'province_id'=>$province_id);


        if($data_form=='' || $date_to=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,9,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id'])) {
            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],9)) {
                try {                 

                    $url = $this->setUrl1(9);
                    $headers = array('Content-Type' => 'application/json');
                    $data = array(
                        "is_non_profit_job"=>$is_non_profit_job,
                        "data_form"=>$data_form,
                        "date_to"=>$date_to,
                        "province_id"=>$province_id,
                    );
                    $response = Requests::post($url, $headers, json_encode($data));
                    $responseData = $response->body; // ได้ข้อมูล json กลับมา
                    // แปลงข้อมูลกลับ และให้เป็น array
                    $responseData_decode = json_decode($responseData,true);

                    //Demo Samble Data webservice   
/*                    $responseData[] = json_encode(array(
                        "pid"=>"1550700081881",
                        "addr_code"=>"",
                        "occupation"=>"2006",
                        "year_of_survey"=>"โปรแกรมเมอร์",
                        "yearly_income_of_family"=>"28,000",
                        "code"=>"0",
                        "message"=>""
                    ));
                    $responseData_decode = json_decode($responseData);*/
                    //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                           $responseData[$key] = $value;
                    }
                    $request['result'] = $responseData;


                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,9,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    $this->response($responseData,200);

                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,9,serialize($request),'Import','Fail'); //Save Log
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                 $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,9,serialize($request),'Import','Fail'); //Save Log  
                $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,9,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
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
    public function RequestOlderEmploymentRegistration_get($eldery_pid=''){
        $authen_user = $this->transfer_model->getOnce_authenKey_byPID('3100602549624');
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
        );

        $request['param'] = array('eldery_pid'=>$eldery_pid);

        if($eldery_pid=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,10,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id'])) {
            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],10)) {
                try {                 

                    $url = $this->setUrl1(10);
                    $headers = array('Content-Type' => 'application/json');
                    $data = array(
                        "eldery_pid"=>$eldery_pid
                    );
                    $response = Requests::post($url, $headers, json_encode($data));
                    $responseData = $response->body; // ได้ข้อมูล json กลับมา
                    // แปลงข้อมูลกลับ และให้เป็น array
                    $responseData_decode = json_decode($responseData,true);

                    //Demo Samble Data webservice   
/*                    $responseData[] = json_encode(array(
                        "pid"=>"1550700081881",
                        "addr_code"=>"",
                        "occupation"=>"2006",
                        "year_of_survey"=>"โปรแกรมเมอร์",
                        "yearly_income_of_family"=>"28,000",
                        "code"=>"0",
                        "message"=>""
                    ));
                    $responseData_decode = json_decode($responseData);*/
                    //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                           $responseData[$key] = $value;
                    }
                    $request['result'] = $responseData;


                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,10,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    $this->response($responseData,200);

                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,10,serialize($request),'Import','Fail'); //Save Log
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                 $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,10,serialize($request),'Import','Fail'); //Save Log  
                $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,10,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
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
    public function RequestElderyFoundation_post() {
        $Username = $this->post('Username');
        $Password = $this->post('Password');
        $OfficerPID = $this->post('OfficerPID');
        $TargetPID = $this->post('TargetPID');

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
        );

        $request['param'] = array('Username'=>$Username,'Password'=>$Password, 'OfficerPID'=>$OfficerPID, 'TargetPID'=>$TargetPID);

        if($Username=='' || $Password=='' || $OfficerPID=='' || $TargetPID=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,13,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id'])) {
            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],13)) {
                try {                     
                    //Link1
                    //$response = Requests::get("http://www.fmop.dop.go.th/dopapi/web/borrowers/view?user=dopuser&password=dpuser&id=3959900370861");

                    $url = $this->setUrl(13);
                       
                    $url = str_replace('paraUserName',$Username,$url);
                    $url = str_replace('paraPassword',$Password,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraTargetPID',$TargetPID,$url);

                    $response = Requests::get($url);
                    $responseData = $response->body; // ได้ข้อมูล json กลับมา
                    $responseData_decode = json_decode($responseData,true);

                    //Demo Samble Data webservice   
/*                    $responseData = json_encode(array(
                        "loan_history"=>"มีประวัติ",
                        "province_code_approv"=>"55",
                        "year_contract"=>"1991",
                        "status"=>"ยังมีสัญญา",
                        "code"=>"0",
                        "message"=>""
                    ));
                    $responseData_decode = json_decode($responseData);
                    //End Demo Samble Data webservice 
*/

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                           $responseData[$key] = $value;
                    }
                    $request['result'] = $responseData;

                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,13,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    $this->response($responseData,200);
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,13,serialize($request),'Import','Fail'); //Save Log
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                 $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,13,serialize($request),'Import','Fail'); //Save Log  
                $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,13,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
        }

    }

    /*-- 6.2] (N/A)Request for Thai Eldery Foundation : ข้อมูลสถิติการกู้ยืมกองทุนผู้สูงอายุ --*/
    public function RequestThaiElderyFoundation_post() {
        $Username = $this->post('Username');
        $Password = $this->post('Password');
        $OfficerPID = $this->post('OfficerPID');
        $TargetPID = $this->post('TargetPID');

        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($OfficerPID);
        $time_start = date('Y-m-d H:i:s');
        $request['url_template'] = $_REQUEST;
        $request['server_req'] = array('SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_HTTPS'=>$_SERVER['REDIRECT_HTTPS'],
            'REDIRECT_STATUS'=>$_SERVER['REDIRECT_STATUS'],
            'HTTPS'=>@$_SERVER['HTTPS'],
            'HTTP_USER_AGENT'=>@$_SERVER['HTTP_USER_AGENT'],
            'SERVER_NAME'=>$_SERVER['SERVER_NAME'],
            'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
            'REDIRECT_QUERY_STRING'=>$_SERVER['REDIRECT_QUERY_STRING'],
            'REQUEST_METHOD'=>$_SERVER['REQUEST_METHOD'],
            'REQUEST_TIME_FLOAT'=>$_SERVER['REQUEST_TIME_FLOAT'],
            'REQUEST_TIME'=>$_SERVER['REQUEST_TIME'],
        );

        $request['param'] = array('Username'=>$Username,'Password'=>$Password, 'OfficerPID'=>$OfficerPID, 'TargetPID'=>$TargetPID);

        if($Username=='' || $Password=='' || $OfficerPID=='' || $TargetPID=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,14,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id'])) {
            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],14)) {
                try {                     
                    //Link2
                    //$response = Requests::get("http://www.fmop.dop.go.th/dopapi/web/borrowers/datum/?user=dopuser&?password=dpuser?3959900370861");

                    $url = $this->setUrl(14);
                       
                    $url = str_replace('paraUserName',$Username,$url);
                    $url = str_replace('paraPassword',$Password,$url);
                    $url = str_replace('paraOfficerPID',$OfficerPID,$url);
                    $url = str_replace('paraTargetPID',$TargetPID,$url);

                    $response = Requests::get($url);
                    $responseData = $response->body; // ได้ข้อมูล json กลับมา
                    $responseData_decode = json_decode($responseData,true);                    

/*                    //Demo Samble Data webservice   
                    $responseData = json_encode(array(
                        "budgetyear"=>"1991",
                        "gender_code"=>"2",
                        "age_borrowers"=>"26",
                        "tambon"=>"00",
                        "ampur"=>"00",
                        "province"=>"00",
                        "postal_code"=>"00000",
                        "category_occupation"=>"โปรแกรมเมอร์",
                        "revenue"=>"12000",
                        "considered"=>"22122560",
                        "result_code"=>"0",
                        "result"=>"ผ่าน",
                        "credit_line"=>"20000",
                        "province_approv"=>"55",
                        "year_contract"=>"1991",
                        "contract_status"=>"ยังมีสัญญา",
                        "code"=>"0",
                        "message"=>""
                    ));
                    $responseData_decode = json_decode($responseData);
                    //End Demo Samble Data webservice 
*/

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                           $responseData[$key] = $value;
                    }
                    $request['result'] = $responseData;

                    //$request['result']['message'] = $responseData_decode->{'message'};
                    //$request['result']['code'] = $responseData_decode->{'code'};
                    //$responseData = json_encode(array('message'=>'ok','code'=>''));// Demo for test

                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,14,serialize($request),'Import','Success'); //Save Log

                    //echo $responseData;
                    $this->response($responseData,200);
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,14,serialize($request),'Import','Fail'); //Save Log
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                 $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,14,serialize($request),'Import','Fail'); //Save Log  
                $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,14,serialize($request),'Import','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
        }

    }
    
    public function RequestThaiElderyFoundation_get($Username='', $Password='', $OfficerPID='', $TargetPID=''){

        try {

            //Link1
            //$response = Requests::get("http://www.fmop.dop.go.th/dopapi/web/borrowers/view?user=dopuser&password=dpuser&id=3959900370861");

            //Link2
            $response = Requests::get("http://www.fmop.dop.go.th/dopapi/web/borrowers/datum/?user=dopuser&?password=dpuser?3959900370861");

            //"http://www.fmop.dop.go.th/dopapi/web/borrowers/view/3959900370861" //Old link
            $responseData = $response->body; // ได้ข้อมูล json กลับมา

            //$this->set62(json_decode($responseData));

            echo $responseData;
        }catch (Exception $e) {
            echo json_encode(array('message'=>$e->getMessage(),'code'=>'-1'));//echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    

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
