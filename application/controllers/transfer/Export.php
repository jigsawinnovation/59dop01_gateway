<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');

class Export extends REST_Controller {

    public $db_gateway = null;

    function __construct() {
        parent::__construct();

        $this->load->library('PHPRequests');

        //$this->load->helper(array('url','form','general','file','html','asset'));
        //$this->load->library(array('session','encrypt'));
        //$this->load->model(array('admin_model','common_model','useful_model','webinfo_model','transfer_model'));

        //chkUserLogin();        //Check User Login

        $this->load->helper(array('url','general','html','asset'));
        $this->load->model(array('common_model','useful_model','transfer_model'));
        
        $this->db_gateway = $this->load->database('gateway',true);

        ob_end_clean();
    }

    function __deconstruct() {
        //$this->db->close();
        $this->db_gateway->close();
    }		

    public function structure_get() {
        $wsrv_id = $this->get('wsrv_id');
        $wsrv_id = (int) $wsrv_id; 
        if($wsrv_id < 0) {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            // BAD_REQUEST (400) being the HTTP response code    
        }else { 
            $this->db_gateway = $this->load->database('gateway',true);
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

            $this->db_gateway->close();
        }
    }

    /* [1] Portal Gateway ฐานข้อมูลการให้บริการสงเคราะห์ผู้สูงอายุในภาวะยากลำบาก* (Linkage Center: ปี 2561) */

    /*-- 1.1] (0101)Request for Difficult Support : ข้อมูลประวัติการได้รับการสงเคราะห์ในภาวะยากลำบาก --*/
    public function DOPDifficultSupport_get($Username='',$Password='', $OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetPID='') {

    }
    
    /* End[1] Portal Gateway ฐานข้อมูลการให้บริการสงเคราะห์ผู้สูงอายุในภาวะยากลำบาก* (Linkage Center: ปี 2561) */


    /* [2] Portal Gateway ฐานข้อมูลผู้สูงอายุที่รับบริการในศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอายุ* (Linkage Center: ปี 2562) */

    /*-- 2.1] (0201)Request for Admission : ข้อมูลประวัติการเข้ารับบริการในศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอายุ --*/
    public function DOPAdmission_get($Username='',$Password='', $OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetPID='') {

    }
    /*-- 2.2] (0202)Request for Individual Rehabilitation Plan : ข้อมูลผลการประเมินสมรรถภาพรายบุคคล --*/
    public function DOPIRP_get($Username='',$Password='', $OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetPID='') {

    }   

    /* End[2] Portal Gateway ฐานข้อมูลผู้สูงอายุที่รับบริการในศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอายุ* (Linkage Center: ปี 2562) */


    /* [3] ฐานข้อมูลการสงเคราะห์ในการจัดการศพผู้สูงอายุตามประเพณี* (Linkage Center: ปี 2561) */

    /*-- 3.1] (0301)Request for Funeral Support : ข้อมูลผู้สูงอายุที่ได้รับการสงเคราะห์ในการจัดการศพผู้สูงอายุตามประเพณี --*/
    public function DOPFuneralSupport_get($Username='',$Password='', $OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetPID='') {

    }
    
    /* End[3] Portal Gateway ฐานข้อมูลการสงเคราะห์ในการจัดการศพผู้สูงอายุตามประเพณี* (Linkage Center: ปี 2561) */


    /* [4] Portal Gateway ฐานข้อมูลการปรับสภาพแวดล้อมและสิ่งอำนวยความสะดวกที่เอื้อเฟื้อต่อคนทุกวัย (Linkage Center: ปี 2561) */

    /*-- 4.1] (0401)Request for Home Repair Support : ข้อมูลบ้านของผู้สูงอายุที่ได้รับการสงเคราะห์ในการปรับปรุง --*/
    public function DOPHomeRepairSupport_get($Username='',$Password='', $OfficerPID='', $OfficerCID='', $RandomStringBin='', $TargetPID='') {

    }    
    
    /* End[4] Portal Gateway ฐานข้อมูลการปรับสภาพแวดล้อมและสิ่งอำนวยความสะดวกที่เอื้อเฟื้อต่อคนทุกวัย (Linkage Center: ปี 2561) */


    /* [5] Portal Gateway ฐานข้อมูลคลังปัญญาผู้สูงอายุ* (Linkage Center: ปี 2562) */

    /*-- 5.1] (0501)Request for Older Wisdom : ข้อมูลทะเบียนประวัติผู้สูงอายุที่มีภูมิปัญญา --*/
    public function DOPOlderWisdom_post($Username='',$Password='', $TargetPID='') {

        $Username = get_inpost('Username');
        $Password = get_inpost('Password');
        $TargetPID = get_inpost('TargetPID');


        if($Username=='') {
            dieArray($_POST);
        }
        
        
        $authen_user = $this->transfer_model->getOnce_authenKey_byPID($Username);

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

        $request['param'] = array('Username'=>$Username,'Password'=>$Password, 'TargetPID'=>$TargetPID);

        if($Username=='' || $Password=='' || $TargetPID=='') {
            $msg = array('message'=>'BAD_REQUEST (400)','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,20,serialize($request),'Export','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code   
        }else if(isset($authen_user['authen_id']) && $authen_user['passcode']==$Password) { //check password
            if($this->transfer_model->chk_wsrvPermission($authen_user['authen_id'],20)) {
                try {                     
                    $rows = $this->common_model->custom_query("select A.date_of_reg,B.pid,C.wisd_code as knw_sp_cate,C.wisd_sp_title as knw_sp_title,C.wisd_sp_file as knw_sp_file,C.wisd_sp_url as knw_sp_url,D.addr_province as reg_province from wisd_info as A inner join pers_info as B on A.pers_id=B.pers_id inner join wisd_branch as C on A.knwl_id=C.knwl_id left join pers_addr as D on B.pre_addr_id=D.addr_id where B.pid='{$TargetPID}' AND (A.delete_datetime IS NULL AND B.delete_datetime IS NULL AND C.delete_datetime IS NULL) ");

                    $responseData = array();
    
                    $Element = $this->transfer_model->getAll_elementWSV(20,"Element");
                    $Element_SRC = array();
                     
                    foreach ($Element as $key => $value) {
                        $Element_SRC[] = $value['elem_name'];
                    }

                    if(count($rows)>0) {    
                        foreach($rows as $key=>$data) {
                            $tmp = array();
                            foreach ($Element_SRC as $key => $value) {
                                if($value=='code') {
                                    $tmp['code'] = '0';
                                }else if($value=='message') {
                                    $tmp['message'] = '';
                                }else {
                                    $tmp[$value] = $data[$value];
                                }
                            }
                            $responseData[] = $tmp;  
                        }

                        $request['result'] = $responseData;
                        $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,20,serialize($request),'Export','Success'); //Save Log
                    }else {
                        $msg = array('message'=>'Not Found Data','code'=>'-1');
                        $request['result'] = $msg;
                        $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,20,serialize($request),'Export','Fail'); //Save Log
                        $this->response($request['result'], REST_Controller::HTTP_BAD_REQUEST);// BAD_REQUEST (400) being the HTTP response code                           
                    }
                    //echo $responseData;
                    $this->response($responseData,200);
                }catch (Exception $e) {
                    $msg = array('message'=>$e->getMessage(),'code'=>'-1');
                    $request['result'] = $msg;
                    $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,20,serialize($request),'Export','Fail'); //Save Log
                    $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
                }
            }else {
                $msg = array('message'=>'Authen ID Permission Invalid','code'=>'-1');
                $request['result'] = $msg;
                 $this->transfer_model->wsrv_logSave($authen_user['authen_id'],$authen_user['pid'],$time_start,20,serialize($request),'Export','Fail'); //Save Log  
                $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
            }
        }else {
            $msg = array('message'=>'Authen ID Invalid','code'=>'-1');
            $request['result'] = $msg;
            $this->transfer_model->wsrv_logSave('',$OfficerPID,$time_start,20,serialize($request),'Export','Fail'); //Save Log
            $this->response($request['result'], REST_Controller::HTTP_NOT_FOUND);// NOT_FOUND (404) being the HTTP response code
        }

    }     
    
    /* End[5] Portal Gateway ฐานข้อมูลคลังปัญญาผู้สูงอายุ* (Linkage Center: ปี 2562) */


    /* [6] Portal Gateway ฐานข้อมูลคลังปัญญาผู้สูงอายุ* (Linkage Center: ปี 2562) */

    /*-- 6.1] (0601)Request for Care Valunteer : ข้อมูลทะเบียนอาสาสมัครดูแลผู้สูงอายุ (อผส.) --*/
    public function DOPCareValunteer_get($Username='',$Password='', $OfficerPID='', $OfficerCID='', $RandomStringBin='') {

    }     
            
    /* End[6] Portal Gateway ฐานข้อมูลคลังปัญญาผู้สูงอายุ* (Linkage Center: ปี 2562) */


    /* [7] Portal Gateway ฐานข้อมูลโรงเรียนผู้สูงอายุ */

    /*-- 7.1] (0701)Request for Older School : ข้อมูลโรงเรียนผู้สูงอายุ --*/
    public function DOPOlderSchool_get($Username='',$Password='', $OfficerPID='', $OfficerCID='', $RandomStringBin='') {

    }           
    /*-- 7.2] (0702)Request for Older Education : ข้อมูลประวัติการเข้าเรียนโรงเรียนผู้สูงอายุ --*/
    public function DOPOlderEducation_get($Username='',$Password='', $OfficerPID='', $OfficerCID='', $RandomStringBin='') {

    }    

    /* End[7] Portal Gateway ฐานข้อมูลโรงเรียนผู้สูงอายุ */

}
