<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public $db_gateway = null;

	function __construct() {
		parent::__construct();

		$this->load->database();
		$this->db_gateway = $this->load->database('gateway',true);

		$this->load->helper(array('url','form','general','file','html','asset'));
		$this->load->library(array('session','encrypt'));
        $this->load->model(array('admin_model','member_model','common_model','useful_model','webinfo_model'));

		$this->load->library('template',
			array('name'=>'web_template1',
				  'setting'=>array('data_output'=>''))
		);
		
		chkUserLogin(); 		 //Check User Login
	}
	function __deconstruct() {
		$this->db->close();
		$this->db_gateway->close();
	}	
	
	public function index(){

		$data = array();
		$this->template->load('menu',$data);
	}

	public function webservice_list() {

		$data['head_title'] = 'Webservice, Crontab';
		$data['title'] = 'รายการเว็บเซอร์วิส, Crontab';
		$data['content_view'] = 'content/webservice_list';
		$this->template->load('index_page',$data);
	}


	public function test1() {
/*		$this->db_kpy = $this->load->database('kpy',true);
		dieArray($this->db_kpy);
        $results = $this->db_kpy->get('activities')->result_array();
        dieArray($results);*/

        $max_addr = rowArray($this->common_model->custom_query("select max(addr_id) from pers_addr1"));
        $max_addr = $max_addr['max(addr_id)'];

        $max_pers = rowArray($this->common_model->custom_query("select max(pers_id) from pers_info1"));
        $max_pers = $max_pers['max(pers_id)'];

        $max_knwl = rowArray($this->common_model->custom_query("select max(knwl_id) from wisd_info"));
        $max_knwl = $max_knwl['max(knwl_id)'];

        $knwl_id = $max_knwl;

        //$this->db->query("delete from wisd_info"); 
        //$this->db->query("delete from wisd_branch"); 

		$rows = $this->common_model->custom_query("select * from wisd_info_old_tmp limit 20000,10000");

		$tmp = array();
		foreach($rows as $key=>$data) {

			$gender = array('M'=>1,'F'=>2);
			$data['birth_day'] = strlen($data['birth_day'])==2?$data['birth_day']:'0'.$data['birth_day'];
			$data['birth_month'] = strlen($data['birth_month'])==2?$data['birth_month']:'0'.$data['birth_month'];

			$name_arr = explode(' ',$data['name']);
			if(count($name_arr)>1) {
				$data['pers_firstname_th'] = $name_arr[0];
				$data['pers_lastname_th'] = $name_arr[1];
			}else {
				$data['pers_firstname_th'] = $data['name'];
				$data['pers_lastname_th'] = '';
			}

			$marital_status_arr = array("1"=>"โสด","2"=>"สมรส อยู่ด้วยกัน"	,"3"=>"สมรส แยกกันอยู่","4"=>"หย่าร้าง","6"=>"ไม่ได้สมรส แต่อยู่ด้วยกัน","5"=>"หม้าย (คู่สมรสเสียชีวิต)");

			$edu_arr = array(
				"91"=>"001", //ไม่ได้เรียนหนังสือ -- ไม่ได้เรียนหนังสือ
				"93"=>"002", //ประถมศึกษา -- ประถมศึกษาตอนต้น
				"94"=>"002", //ประถมศึกษา -- ประถมศึกษาตอนปลาย
				"95"=>"003", //มัธยมศึกษาตอนต้น -- มัธยมศึกษาตอนต้น
				"96"=>"004", //มัธยมศึกษาตอนปลาย -- มัธยมศึกษาตอนปลาย
				"10"=>"005", // อาชีวศึกษาและประกาศนียบัตรชั้นสูง (ปวช./ปวท./ปกศ.ต้น) -- อาชีวศึกษาและประกาศนียบัตรชั้นสูง (ปวช./ปวท./ปกศ.ต้น)
				"40"=>"006", //ปริญญาตรี -- ปริญญาตรี
				"99999"=>"007", //อื่น ๆ -- อื่นๆ
			);

			$wisd_branch_arr = array(
				"wis_study"=>"001", //การศึกษา -- wis_study การศึกษา
				"wis_medical"=>"002", //การแพทย์และสาธารณสุข -- wis_medical การแพทย์และสาธารณสุข
				"wis_agriculture"=>"003", //การเกษตร -- wis_agriculture การเกษตร
				"wis_natural"=>"004", //ทรัพยากรธรรมชาติและสิ่งแวดล้อม -- wis_natural ทรัพยากรธรรมชาติและสิ่งแวดล้อม
				"wis_science"=>"005", //วิทยาศาสตร์และเทคโนโลยี -- wis_science วิทยาศาสตร์และเทคโนโลยี
				"wis_engineer"=>"006", //วิศวกรรม -- wis_engineer วิศวกรรม
				"wis_architecture"=>"007", //สถาปัตยกรรม -- wis_architecture สถาปัตยกรรม
				"wis_social"=>"008", //พัฒนาสังคม สังคมสงเคราะห์ จัดสวัสดิการชุมชนฯ -- wis_social พัฒนาสังคม สังคมสงเคราะห์  จัดสวัสดิการชุมชนฯ
				"wis_law"=>"009", //กฎหมาย -- wis_law กฎหมาย
				"wis_politics"=>"010", //การเมืองการปกครอง -- wis_politics การเมืองการปกครอง
				"wis_art"=>"011", //ศิลปะ วัฒนธรรม ประเพณี -- wis_art ศิลปะ วัฒนธรรม  ประเพณี
				"wis_religion"=>"012", //ศาสนา จริยธรรม -- wis_religion ศาสนา จริยธรรม
				"wis_commercial"=>"013", //พาณิชย์และบริการ -- wis_commercial พาณิชย์และบริการ
				"wis_security"=>"014", //ความมั่นคง -- wis_security ความมั่นคง
				"wis_management"=>"015", //บริหารจัดการและบริหารธุรกิจ -- wis_management บริหารจัดการและบริหารธุรกิจ
				"wis_publicity"=>"016", //การประชาสัมพันธ์ -- wis_publicity การประชาสัมพันธ์
				"wis_transport"=>"017", //คมนาคมและการสื่อสาร -- wis_transport คมนาคมและการสื่อสาร
				"wis_energy"=>"018", //พลังงาน -- wis_energy พลังงาน
				"wis_foreign"=>"019", //ต่างประเทศ -- wis_foreign ต่างประเทศ
				"wis_materials"=>"020", //อุตสาหกรรม หัตถกรรม จักสาร และสินค้าโอท็อป -- wis_materials อุตสาหกรรม  หัตถกรรม   จักสารและโอท็อป
				"wis_language"=>"021", //ภาษา วรรณคดี วรรณศิลป์ -- wis_language ภาษา  วรรณคดี   วรรณศิลป์
				"wis_rhetoric"=>"022", //วาทศิลป์ -- wis_rhetoric วาทศิลป์
				"wis_other"=>"023", //อื่น ๆ -- wis_other อื่น  ๆ
			);
			$wisd_branch_inarr = array();
			foreach($wisd_branch_arr as $key1=>$value) {
				if(trim($data[$key1])!='') {
					$wisd_branch_inarr[] = array(
						'wisd_code'=>$value,
						'wisd_sp_title'=>trim($data[$key1]),
						'insert_datetime'=>$data['created'],
						'insert_user_id'=>1,
						'insert_org_id'=>96,
						'update_datetime'=>$data['updated'],
						'update_user_id'=>1,
						'update_org_id'=>1,
					);
				}
			}

			$tel_no = '';
			if($data['tel']!='') {
				$tel_no = $data['tel'];
				if($data['mobile']!='') {
					$tel_no = $tel_no.', '.$data['mobile'];
				}
			}
			if($data['mobile']!=''&&$tel_no=='') {
				$tel_no = $data['mobile'];
			}

			$tmp[] = array(
				'pers_info'=>array(
					'info'=>array(
						'pid'=>$data['id_card'],
						'pers_firstname_th'=>$data['pers_firstname_th'],
						'pers_lastname_th'=>$data['pers_lastname_th'],
						'pren_code'=>$data['title'],
						'date_of_birth'=> ($data['birth_year']-543).'-'.$data['birth_month'].'-'.$data['birth_day'],
						'gender_code'=>@$gender[$data['sex_code']],
						'nation_code'=>$data['nationality'],
						'tel_no'=>$tel_no,
						'marital_status'=>@$marital_status_arr[$data['marital_status']],
						'edu_code'=>@$edu_arr[$data['education']],
						'edu_identify'=>$data['education_other'],
						'occupation'=>trim($data['current_occupation'])=='อื่นๆ'?$data['current_occupation_detail']:$data['current_occupation'],
						'insert_user_id'=>1,
						'insert_org_id'=>96,
						'update_datetime'=>$data['updated'],
						'update_user_id'=>1,
						'update_org_id'=>1),
					'reg_addr'=>array(
						'addr_home_no'=>$data['reg_home_no'],
						'addr_moo'=>$data['reg_moo'],
						'addr_lane'=>$data['reg_soi'],
						'addr_sub_district'=>$data['reg_province_id'].$data['reg_amphur_id'].$data['reg_district_id'],
						'addr_district'=>$data['reg_province_id'].$data['reg_amphur_id'].'0000',
						'addr_province'=>$data['reg_province_id'].'000000',
						'addr_zipcode'=>$data['reg_post_code'],
						'insert_user_id'=>1,
						'insert_org_id'=>96,
						'update_datetime'=>$data['updated'],
						'update_user_id'=>1,
						'update_org_id'=>1,
						),
					'pre_addr'=>array(
						'addr_home_no'=>$data['now_home_no'],
						'addr_moo'=>$data['now_moo'],
						'addr_lane'=>$data['now_soi'],
						'addr_sub_district'=>$data['now_province_id'].$data['now_amphur_id'].$data['now_district_id'],
						'addr_district'=>$data['now_province_id'].$data['now_amphur_id'].'0000',
						'addr_province'=>$data['now_province_id'].'000000',
						'addr_zipcode'=>$data['now_post_code'],
						'insert_user_id'=>1,
						'insert_org_id'=>96,
						'update_datetime'=>$data['updated'],
						'update_user_id'=>1,
						'update_org_id'=>1,
						)
				),
				'wisd_info'=>array(
					'insert_datetime'=>$data['created'],
					'insert_user_id'=>1,
					'insert_org_id'=>96,
					'update_datetime'=>$data['updated'],
					'update_user_id'=>1,
					'update_org_id'=>1,
					'wisd_branch'=>$wisd_branch_inarr
				)
			);

			if($data['regis_date']!='') {
				$tmp[$key]['wisd_info']['date_of_reg'] = $data['regis_date'];
			}

			$reg_addr_id = 0;
			$pre_addr_id = 0;

			$pers_tmp = array();
			if($data['id_card']!='') {
				$pers_tmp = rowArray($this->common_model->custom_query("select * from pers_info1 where pid='{$data['id_card']}' AND delete_datetime IS NULL"));
			}	
			$pers_id = 0;

			if(!isset($pers_tmp['pid'])) {
				if( (trim($data['reg_home_no'])==trim($data['now_home_no'])) && (trim($data['reg_moo'])==trim($data['now_moo'])) && (trim($data['reg_soi'])==trim($data['now_soi'])) && (trim($data['reg_soi'])==trim($data['now_soi'])) && ($tmp[$key]['pers_info']['reg_addr']['addr_sub_district']==$tmp[$key]['pers_info']['pre_addr']['addr_sub_district']) ) {
					$reg_addr_id = ++$max_addr;
					$pre_addr_id = $reg_addr_id;
					$this->common_model->insert('pers_addr1',$tmp[$key]['pers_info']['reg_addr']);
				}else {
					$reg_addr_id = ++$max_addr;
					$this->common_model->insert('pers_addr1',$tmp[$key]['pers_info']['reg_addr']);
					$pre_addr_id = ++$max_addr;
					$this->common_model->insert('pers_addr1',$tmp[$key]['pers_info']['pre_addr']);
				}
				$tmp[$key]['pers_info']['info']['reg_addr_id']=$reg_addr_id;
				$tmp[$key]['pers_info']['info']['pre_addr_id']=$pre_addr_id;

				$max_pers++;
				$this->common_model->insert('pers_info1',$tmp[$key]['pers_info']['info']);
				$pers_id = $max_pers;
			}else {
				if($pers_tmp['wsrv_staff_datetime']!='') {
					$pers_id = $pers_tmp['pers_id'];
				}else {
					$this->common_model->update('pers_addr1',array('delete_datetime'=>date("Y-m-d H:i:s"),'delete_org_id'=>96,'delete_user_id'=>1),array('addr_id'=>$pers_tmp['reg_addr_id']));
					$this->common_model->update('pers_addr1',array('delete_datetime'=>date("Y-m-d H:i:s"),'delete_org_id'=>96,'delete_user_id'=>1),array('addr_id'=>$pers_tmp['pre_addr_id']));

					if( (trim($data['reg_home_no'])==trim($data['now_home_no'])) && (trim($data['reg_moo'])==trim($data['now_moo'])) && (trim($data['reg_soi'])==trim($data['now_soi'])) && (trim($data['reg_soi'])==trim($data['now_soi'])) && ($tmp[$key]['pers_info']['reg_addr']['addr_sub_district']==$tmp[$key]['pers_info']['pre_addr']['addr_sub_district']) ) {
						$reg_addr_id = ++$max_addr;
						$pre_addr_id = $reg_addr_id;
						$this->common_model->insert('pers_addr1',$tmp[$key]['pers_info']['reg_addr']);
					}else {
						$reg_addr_id = ++$max_addr;
						$this->common_model->insert('pers_addr1',$tmp[$key]['pers_info']['reg_addr']);
						$pre_addr_id = ++$max_addr;
						$this->common_model->insert('pers_addr1',$tmp[$key]['pers_info']['pre_addr']);
					}
					$tmp[$key]['pers_info']['info']['reg_addr_id']=$reg_addr_id;
					$tmp[$key]['pers_info']['info']['pre_addr_id']=$pre_addr_id;

					$pers_id = $pers_tmp['pers_id'];
					$this->common_model->update('pers_info1',$tmp[$key]['pers_info']['info'],array('pid'=>$pers_tmp['pid']));
				}
			}

			$tmp[$key]['wisd_info']['pers_id'] = $pers_id;
			$wisd_info_insert = $tmp[$key]['wisd_info'];
			unset($wisd_info_insert['wisd_branch']);
			$this->common_model->insert('wisd_info',$wisd_info_insert);
			$knwl_id++;

			if(count($tmp[$key]['wisd_info']['wisd_branch'])>0) {
				foreach($tmp[$key]['wisd_info']['wisd_branch'] as $key2=>$value) {
					$value['knwl_id'] = $knwl_id;
					$this->common_model->insert('wisd_branch',$value);
				}
			}
			//$this->common_model->insert('pers_info_tmp1',);
			//echo $key.':'.$data['id_card'].'<br>';
		}
		//dieArray($tmp);

	}

/*	public function test() {
		$arr = json_decode('
{"CID":"","PID":"","Random":"","RandomBin":"","RandomBinLength":"","RandomLength":"","code":"ERROR-GETRANDOM","message":"Try to get random from DOPA : Message => [AMI][INKOAMIInterface.GetRandomStringFromDOP] -> Error when try to check return condition from DOPA in Get Random process => RequstRandomProcessStatus = 0 []; ProcessReturnxCheck => 90001 [หมายเลขประจำตัวไม่ถูกต้อง]; StackTrace ->    at DOPIKNOInterfaceClassLibrary.INKOAMIInterface.GetRandomStringFromDOP(String ReqtPID, String ReqtCID) in D:\\Project\\MSO ICTC เชื่อมโยงกรมการปกครอง\\SourceCode\\DOPIKNOInterfaceClassLibrary\\INKOAMIInterface.vb:line 175; Step info = Error when get random string from DOPA"}');
		dieArray($arr);
	}
	*/

/*	public function test() {
		$this->load->model('transfer_model');

     //Demo Samble Data webservice   
        $responseData = json_encode(array(
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
        $responseData_decode = json_decode($responseData);
        //End Demo Samble Data webservice 

                    $responseData = array();
                    foreach ($responseData_decode as $key => $value) {
                        $responseData[$key] = trim($value);
                    }
                    $request['request'] = $responseData;

                    //$request['result']['message'] = $responseData_decode->{'message'};
                    //$request['result']['code'] = $responseData_decode->{'code'};   

                    $result = array('code'=>$request['request']['code'],'message'=>$request['request']['message']);
                    if($result['code']=='0') { //Update Data 
                        $rows = $this->transfer_model->getAll_elementWSV(5,'Element');
                        $addr_update = array();
                        foreach($rows as $key=>$value) {
                            if(trim($value['elem_name'])!='') {
                            	echo $value['elem_name'].'<br>';
                                $addr_update[trim($value['elem_name'])] = trim($request['request'][trim($value['elem_src_name'])]);
                            }
                        }
                        //$this->common_model->update('pers_addr',$addr_update,array('addr_code'=>$responseData->{'HouseID'}));
                        $result['addr_update'] = $addr_update;
                    }

        dieArray($result);         

	}*/


/*	public function test() {
		$json = '{"CID": "",
		"PID": "",
		"Random": "3239663864316539316431313939353933356334663834636130396234353366",
		"RandomBin": "29f8d1e91d11995935c4f84ca09b453f",
		"RandomBinLength": "32",
		"RandomLength": "64",
		"code": "0",
		"message": ""}';

		$obj = json_decode($json);
		print $obj->{'code'}; // 12345
		print $obj->{'RandomBin'}; // 12345
	}
*/
	// private function clr_diffInfo_form1() {
	// 	return array(
	// 			 	'pers_id' => '', 
	// 				'elder_none_inasmuch' => '', 
	// 				'elder_prename' => '', 
	// 				'elder_firstname' => '', 
	// 				'elder_lastname' => '', 
	// 				'elder_home_tel_no' => '',
	// 				'elder_tel_no' => ''
	// 	);
	// }

	// public function registation($process_action='Add',$diff_id=0) { //บันทึกแจ้งขอรับบริการสงเคราะห์
	// 	$data = array(); //Set Initial Variable to Views

	// 	/*-- Initial Data for Check User Permission --*/
	// 	$user_id = get_session('user_id');
	// 	$app_id = 9;
	// 	$process_path = 'main/registation';
	// 	/*--END Inizial Data for Check User Permission--*/

	// 	$this->webinfo_model->LogSave($app_id,$process_action,'Sign In',$user_id); //Save Sign In Log
	// 	$usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

	// 	if(count($usrpm)<1){
	// 		page500();
	// 	}else {
	// 		$app_name = $usrpm['app_name'];
	// 		$data['usrpm'] = $usrpm;
	// 		$data['user_id'] = $user_id;

	// 		$this->load->library('template',
	// 			array('name'=>'admin_template1',
	// 				  'setting'=>array('data_output'=>''))
	// 		); // Set Template

	// 		$data['process_action'] = $process_action;	
	// 		$data['content_view'] = 'content/form1';

	// 		//$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
	// 		//$data['head_title'] = $tmp['app_name'];
	// 		//$data['title'] = $app_name;

	// 		$data['head_title'] = 'Gateway';
	// 		$data['title'] = $usrpm['app_name'];

	// 		$data['csrf'] = array(
	// 			'name' => $this->security->get_csrf_token_name(),
	// 			'hash' => $this->security->get_csrf_hash()
	// 		);			

	// 		if($process_action=='Add' && get_inpost('bt_submit')=='' && $usrpm['perm_can_edit']=='Yes') {
				
	// 			$data['diff_info'] = $this->clr_diffInfo_form1();
	// 			$this->session->set_flashdata('state_form','');
	// 			$data['rd_pers_id'] = '';
	// 			$data['rd_req_pers_id'] = '';

	// 		}else if($process_action=='Added' && get_inpost('bt_submit')!='' && $usrpm['perm_can_edit']=='Yes'){ //Add && Submit Form
	// 			dieArray($_POST);
	// 		}else if($process_action=='View' && get_inpost('bt_submit')=='' && $usrpm['perm_can_view']=='Yes') {
	// 			page500();
	// 		}else if($process_action=='Edit' && get_inpost('bt_submit')=='' && $usrpm['perm_can_edit']=='Yes') {
	// 			page500();
	// 		}else if($process_action=='Edited' && get_inpost('bt_submit')!='' && $usrpm['perm_can_edit']=='Yes') { //Edit && Submit Form
	// 			page500();
	// 		}else if($process_action=='Delete' && $usrpm['perm_can_edit']=='Yes') { //Delete process
	// 			page500();
	// 		}else { 
	// 			page500();
	// 		}

	// 		$this->template->load('index_page',$data);
	// 		$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out',$user_id); //Save Sign Out Log
	// 	}
	// 	//$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out',$user_id); //Save Sign Out Log
	// }
 
 /*
    public function get_data() {
    	$this->load->library('PHPRequests');

        $headers = array('Accept' => 'application/json');
        $options = array('auth' => array('user', 'pass'));
        $response = Requests::get('https://api.github.com/gists', $headers, $options);
 
        echo "<pre>";
        echo $response->status_code."<br>";
        var_dump($response->status_code);
// int(200)
 
        var_dump($response->headers['content-type']);
// string(31) "application/json; charset=utf-8"
 
        var_dump($response->body);
        echo "</pre>";
    }
*/

/*    public function get_data($id=''){
		$this->load->library('PHPRequests');

        if($id=='') {
        	$response = Requests::get('http://localhost/dop_gateway/transfer/data');
    	}else {
    		$response = Requests::get('http://localhost/dop_gateway/transfer/data/index/id/'.$id);
    	}

        $responseData = $response->body; // ได้ข้อมูล json กลับมา
        // แปลงข้อมูลกลับ และให้เป็น array
        $arrData = json_decode($responseData,true);
        echo "<pre>";
        print_r($arrData); // ทดสอบแสดงข้อมูลจากตัวแปร array
        echo "</pre>";
    }
*/

/*    public function get_data(){
        $url = 'http://localhost/learnci/api/news/index';
        $headers = array('Content-Type' => 'application/json');
        $data = array(
            'id' => 3,
            'topic'=>"ใหม่ หัวข้อข่าวที่ 3 อัพเดท"
        );
        $response = Requests::post($url, $headers, json_encode($data));
        $responseData = $response->body; // ได้ข้อมูล json กลับมา
        // แปลงข้อมูลกลับ และให้เป็น array
        $arrData = json_decode($responseData,true);
        echo "<pre>";
        print_r($arrData); // ทดสอบแสดงข้อมูลจากตัวแปร array
        echo "</pre>";
    }
*/


/*public function get_data($Username='',$Password='',$OfficerPID=''){
		$this->load->library('PHPRequests');

    	$response = Requests::get('http://gateway.dop.go.th/transfer/import/RequestDOPolderWisdom/{$Username}/{$Password}/{$PID}');
    
        $responseData = $response->body; // ได้ข้อมูล json กลับมา
        // แปลงข้อมูลกลับ และให้เป็น array
        $arrData = json_decode($responseData,true);
        echo "<pre>";
        print_r($arrData); // ทดสอบแสดงข้อมูลจากตัวแปร array
        echo "</pre>";
}

public function get_data(){
        $url = 'http://gateway.dop.go.th/transfer/import/RequestDOPolderWisdom';
        $headers = array('Content-Type' => 'application/json');
        $data = array(
            'Username' => "test",
            'Password'=> "1234",
            'OfficerPID' => '1550700081881'
        );
        $response = Requests::post($url, $headers, json_encode($data));
        $responseData = $response->body; // ได้ข้อมูล json กลับมา
        // แปลงข้อมูลกลับ และให้เป็น array
        $arrData = json_decode($responseData,true);
        echo "<pre>";
        print_r($arrData); // ทดสอบแสดงข้อมูลจากตัวแปร array
        echo "</pre>";
}

public function get_data($OfficerPID='',$OfficerCID=''){
		$this->load->library('PHPRequests');

    	$response = @Requests::get("https://gateway.dop.go.th/transfer/import/RequestRandomService/{$OfficerPID}/{$OfficerCID}");
    
        $responseData = $response->body; // ได้ข้อมูล json กลับมา
        // แปลงข้อมูลกลับ และให้เป็น array
        $arrData = json_decode($responseData,true);
        echo "<pre>";
        print_r($arrData); // ทดสอบแสดงข้อมูลจากตัวแปร array
        echo "</pre>";


}
*/

/*public function get_data($OfficerPID='',$OfficerCID=''){
		$this->load->library('PHPRequests');

    	$response = @Requests::get("https://gateway.dop.go.th/transfer/import/RequestRandomService/{$OfficerPID}/{$OfficerCID}");
    
        $responseData = $response->body; // ได้ข้อมูล json กลับมา
        // แปลงข้อมูลกลับ และให้เป็น array
        $arrData = json_decode($responseData,true);
        echo "<pre>";
        print_r($arrData); // ทดสอบแสดงข้อมูลจากตัวแปร array
        echo "</pre>";
}	

public function test() {
	dieArray(unserialize(''));
}*/

	public function test($process_action='View') {
		$this->load->model('transfer_model');

		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		$app_id = 101;
		$process_path = 'main/test';
		/*--END Inizial Data for Check User Permission--*/

		$this->webinfo_model->LogSave($app_id,$process_action,'Sign In','Success'); //Save Sign In Log
		$usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

		if(@$usrpm['perm_status']=='No' || !isset($usrpm['app_id'])){
			page500(); 
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Fail'); //Save Sign In Log
		}else {
			$app_name = $usrpm['app_name'];
			$data['usrpm'] = $usrpm;
			$data['user_id'] = $user_id;

			$data['importwsv_info'] = $this->transfer_model->getAll_importWSV();

			$this->load->library('template',
				array('name'=>'web_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template

    		/*-- Toastr style --*/
    		set_css_asset_head('../plugins/Static_Full_Version/css/plugins/toastr/toastr.min.css');
    		set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/toastr/toastr.min.js');
    		/*-- End Toastr style --*/
			set_js_asset_footer('import.js'); //Set JS Import.js 

			set_js_asset_footer('mapmarker.js'); //Set JS mapmarker.js --

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/maptest';

			$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			$data['title'] = $usrpm['app_name'];
		
			$this->template->load('index_page',$data);
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		}
	}


/*	public $db_center_test = null;*/

/*	public function testdb() {

		$this->db_center_test = $this->load->database('center_test',true);

        //$this->db_gateway->where('wsrv_id', $wsrv_id);
        $results = $this->db_center_test->get('tmp_pers_addr')->result_array();

		//$rows = array(array('province_name'=>'น่าน','amphur'=>'เวียงสา','tambon'=>'กลางเวียง')
		//	);

		$area = $this->common_model->custom_query("select * from std_area");		

		$arr_tmp = array();
		foreach($area as $key=>$data) {
			$arr_tmp[trim($data['area_name_th'])] = $data['area_code'];
		} 

		foreach ($results as $key => $value) {
			echo $value['addr_id'].'<br>';
			echo 'addr_province'.@$arr_tmp[$value['addr_province']].'<br>';
			echo 'addr_district'.@$arr_tmp[$value['addr_district']].'<br>';
			echo 'addr_sub_district'.@$arr_tmp[$value['addr_sub_district']].'<br>';
			echo '<hr>';
		}

/*		foreach($rows as $key=>$data) {


			$data = strpos($data[''],$data['area_name_th'])


		}*/

		/*
	}*/

/*	public function testdb1() {
		$this->db_center_test = $this->load->database('center_test',true);
		//$this->db_center_test = $this->load->database('gateway',true);

        //$this->db_gateway->where('wsrv_id', $wsrv_id);
        $results = $this->db_center_test->get('tmp_pers_info')->result_array();

        $rs = array();
        foreach($results as $key=>$data) {
        	unset($results[$key]['full_name']);
        	$rs[] = $results[$key];
        }

        dieArray($rs);

        $this->common_model->insert_batch('pers_info',$rs);

	}*/


/*	public function testdb1() {

		$this->db_center_test = $this->load->database('center_test',true);
		//$this->db_center_test = $this->load->database('gateway',true);

        //$this->db_gateway->where('wsrv_id', $wsrv_id);
        $results = $this->db_center_test->get('tmp_fnrl')->result_array();

		$this->common_model->insert_batch('fnrl_info',$results);
	}*/

/*	public function testdb1() {
		$this->db_center_test = $this->load->database('center_test',true);
		//$this->db_center_test = $this->load->database('gateway',true);

        //$this->db_gateway->where('wsrv_id', $wsrv_id);
        $results = $this->db_center_test->get('tmp_impv_place')->result_array();

        foreach($results as $key=>$data) {
			unset($results[$key]['place_condition']);
		}

		$this->common_model->insert_batch('impv_place_info',$results);
	}*/

/*	public function im_place() {
		$this->db_center = $this->load->database('center',true);

		$area = $this->common_model->custom_query("select area_code,area_name_th from std_area where area_type='Province'");	

		$arr_tmp = array();
		foreach($area as $key=>$data) {
			$arr_tmp[trim($data['area_name_th'])] = $data['area_code'];
		} 

		$csv = array_map('str_getcsv', file('./assets/file/place.csv'));
		unset($csv[0]);

		$tmp = array();

		$pers_in_run = 60366;
		$addr_in_run = 60365;

		foreach($csv as $key=>$data) {

			$province_code = '';
			if(isset($arr_tmp[@iconv( 'TIS-620' , 'UTF-8' ,$data[8])])) {
				$province_code = $arr_tmp[@iconv( 'TIS-620' , 'UTF-8' ,$data[8])];
			}else {
				if(@iconv( 'TIS-620' , 'UTF-8' ,$data[8])=='กรุงเทพฯ') {
					$province_code = $arr_tmp['กรุงเทพมหานคร'];
				}else if(@iconv( 'TIS-620' , 'UTF-8' ,$data[8])=='นคครพนม') {
					$province_code = $arr_tmp['นครพนม'];
				}
			}
			$addr_info = array('addr_id'=>$addr_in_run,'addr_home_no'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[3]),'addr_moo'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[4]),'addr_road'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[5]),'addr_sub_district'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[6]),'addr_district'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[7]),'addr_province'=>$province_code);
			
			$addr_id = $this->db_center->insert('pers_addr', $addr_info); //insert address

			$pers_info = array('pers_id'=>$pers_in_run,'pid'=>str_replace(" ","",@iconv( 'TIS-620' , 'UTF-8' ,$data[2])),'pre_addr_id'=>$addr_in_run);

			$pers_id = $this->db_center->insert('pers_info', $pers_info); //insert personalinfo

			$impv_place_info = array('pers_id'=>$pers_in_run,'ptype_code_remark'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[1]),'case_budget'=>str_replace(",","",@iconv( 'TIS-620' , 'UTF-8' ,$data[9])),'date_of_finish'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[10]),'place_condition'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[11]),'insert_user_id'=>1,'insert_org_id'=>96,'insert_datetime'=>date("Y-m-d H:i:s"));


			$impv_place_id = $this->db_center->insert('tmp_impv_place', $impv_place_info); //insert personalinfo

			$tmp[] = array('addr_info'=>$addr_info,'pers_info'=>$pers_info,'impv_place_info'=>$impv_place_info);
			
			$addr_in_run = $addr_in_run+1;
			$pers_in_run = $pers_in_run+1;
		}
		dieArray($tmp);

	}
	public function im_place_replace() {
		$this->db_center = $this->load->database('center',true);

		$query = $this->db_center->query("select * from tmp_impv_place");
		$rows = $query->result_array();
		$tmp = array();
		foreach($rows as $data) {
			unset($data['place_condition']);
			$tmp[] = $data;
		}
		$this->db_center->insert_batch("impv_place_info", $tmp);
	}

	public function im_home() {
		$this->db_center = $this->load->database('center',true);

		$area = $this->common_model->custom_query("select area_code,area_name_th from std_area where area_type='Province'");	

		$arr_tmp = array();
		foreach($area as $key=>$data) {
			$arr_tmp[trim($data['area_name_th'])] = $data['area_code'];
		} 

		$csv = array_map('str_getcsv', file('./assets/file/home.csv'));
		unset($csv[0]);

		$tmp = array();

		$pers_in_run = 60431;
		$addr_in_run = 60430;

		foreach($csv as $key=>$data) {

			$province_code = '';
			if(isset($arr_tmp[@iconv( 'TIS-620' , 'UTF-8' ,$data[8])])) {
				$province_code = $arr_tmp[@iconv( 'TIS-620' , 'UTF-8' ,$data[8])];
			}else {
				if(@iconv( 'TIS-620' , 'UTF-8' ,$data[8])=='กรุงเทพฯ') {
					$province_code = $arr_tmp['กรุงเทพมหานคร'];
				}else if(@iconv( 'TIS-620' , 'UTF-8' ,$data[8])=='นคครพนม') {
					$province_code = $arr_tmp['นครพนม'];
				}
			}
			$addr_info = array('addr_id'=>$addr_in_run,'addr_home_no'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[3]),'addr_moo'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[4]),'addr_road'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[5]),'addr_sub_district'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[6]),'addr_district'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[7]),'addr_province'=>$province_code);
			
			$addr_id = $this->db_center->insert('pers_addr', $addr_info); //insert address

			$arr = explode(' ',trim(@iconv( 'TIS-620' , 'UTF-8' ,$data[1])));
			$pers_firstname_th = '';
			$pers_lastname_th = '';
			if(count($arr)>1) {
				$pers_firstname_th = $arr[0];
				$pers_lastname_th = $arr[1];
			}else {
				$pers_firstname_th = trim(@iconv( 'TIS-620' , 'UTF-8' ,$data[1]));
			}

			$pren_code = '';
			if(iconv_substr($pers_firstname_th,0,6,"UTF-8")=='นางสาว') {
				$pren_code = "004";
				$pers_firstname_th = str_replace("นางสาว","",$pers_firstname_th);
			}
			if(iconv_substr($pers_firstname_th,0,3,"UTF-8")=='นาง') {
				$pren_code = "005";
				$pers_firstname_th = str_replace("นาง","",$pers_firstname_th);
			}
			if(iconv_substr($pers_firstname_th,0,3,"UTF-8")=='นาย') {
				$pren_code = "002";
				$pers_firstname_th = str_replace("นาย","",$pers_firstname_th);
			}

			$pers_info = array('pers_id'=>$pers_in_run,'pren_code'=>$pren_code,'pers_firstname_th'=>$pers_firstname_th,'pers_lastname_th'=>$pers_lastname_th,'pid'=>str_replace(" ","",@iconv( 'TIS-620' , 'UTF-8' ,$data[2])),'pre_addr_id'=>$addr_in_run);

			//$pers_id = $this->db_center->insert('pers_info', $pers_info); //insert personalinfo

			$impv_home = array('pers_id'=>$pers_in_run,'case_budget'=>str_replace(",","",@iconv( 'TIS-620' , 'UTF-8' ,$data[9])),'date_of_finish'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[10]),'home_condition'=>@iconv( 'TIS-620' , 'UTF-8' ,$data[11]),'insert_user_id'=>1,'insert_org_id'=>96,'insert_datetime'=>date("Y-m-d H:i:s"));

			//$impv_place_id = $this->db_center->insert('tmp_impv_home', $impv_home); //insert personalinfo

			$tmp[] = array('addr_info'=>$addr_info,'pers_info'=>$pers_info,'tmp_impv_home'=>$impv_home);
			
			$addr_in_run = $addr_in_run+1;
			$pers_in_run = $pers_in_run+1;
		}
		dieArray($tmp);

	}
	public function im_home_replace() {
		$this->db_center = $this->load->database('center',true);

		$query = $this->db_center->query("select * from tmp_impv_home");
		$rows = $query->result_array();
		$tmp = array();
		foreach($rows as $data) {
			unset($data['home_condition']);
			$tmp[] = $data;
		}
		$this->db_center->insert_batch("impv_home_info", $tmp);
	}*/

/*	public function schl_qlc_info() {*/
		//$area = $this->common_model->custom_query("select area_code,area_name_th from std_area where area_type='Province'");	

/*		$arr_tmp = array();
		foreach($area as $key=>$data) {
			$arr_tmp[trim($data['area_name_th'])] = $data['area_code'];
		} */

/*		$rows = $this->common_model->custom_query("select * from schl_qlc_info");
		foreach ($rows as $key => $value) {*/
			//if(isset($arr_tmp[trim($value['addr_district'])])) {
				//$this->common_model->update('schl_qlc_info',array('insert_user_id'=>1,'insert_datetime'=>date("Y-m-d H:i:s"),'insert_org_id'=>96,'addr_province'=>$arr_tmp[trim($value['addr_province'])]),array('qlc_id'=>$value['qlc_id']));

			//	echo $arr_tmp[trim($value['addr_district'])].'<br>';
			//}else {
			//	echo $value['addr_district'].'<br>';
			//}
/*			$area = $this->common_model->custom_query("select * from `std_area` where `std_area`.`area_code` like '".substr($value['addr_province'], 0,2)."%' AND area_type='Amphur' AND area_name_th like '%".trim($value['addr_district'])."%'");
			if(count($area)>0) {
				echo $area[0]['area_code'].'<br/>';
				$this->common_model->update('schl_qlc_info',array('addr_district'=>$area[0]['area_code']),array('qlc_id'=>$value['qlc_id']));
			}*/
/*			$area = $this->common_model->custom_query("select * from `std_area` where `std_area`.`area_code` like '".substr($value['addr_district'], 0,4)."%' AND area_type='Tambon' AND area_name_th like '%".trim($value['addr_sub_district'])."%'");
			if(count($area)>0) {
				echo $area[0]['area_code'].'<br/>';
				$this->common_model->update('schl_qlc_info',array('addr_sub_district'=>$area[0]['area_code']),array('qlc_id'=>$value['qlc_id']));
			}

		}

	}*/
/*	public function schl_qlc_info_csv() {
		$csv = array_map('str_getcsv', file('./assets/file/schl_qlc_info_sum1.csv'));

		$tmp = array();

		foreach($csv as $key=>$data) {
			dieArray($data);
			$tmp[] = trim(@iconv( 'TIS-620' , 'UTF-8' ,trim($data[0])));
		}
		dieArray($tmp);

	}*/

/*	public function schl_qlc_info() {
		$rows = $this->common_model->custom_query("select * from schl_qlc_info_copy");
		$tmp = array();
		foreach($rows as $key=>$data) {
			$tmp[] = array('qlc_name'=>$data['qlc_name']);
 			$this->common_model->update('schl_qlc_info',array('qlc_name'=>$data['qlc_name']),array('qlc_id'=>$data['qlc_id']));
		}
		dieArray($tmp);

	}*/

/*	public function cal_npid($npid='') {
		if(strlen($npid)>=12 && strlen($npid)<14) {
			$sum = 0;
			for($i=0;$i<12;$i++) {
				$sum +=$npid[$i]*(13-$i);
			}
			if($sum%11<=1) {
				echo 'Last Digit is'.(1-($sum%11));
			}else {
				echo 'Last Digit is'.(11-($sum%11));
			}
		}else {
			echo 'Length Invalid.';
		}
	}*/

	public function pren_code() {
		//$rows = $this->common_model->custom_query("select * from pers_info where pren_code='' AND trim(pers_firstname_th) like 'พระครู%'");

		$rows = $this->common_model->custom_query("select * from pers_info where trim(pers_firstname_th) like 'นาย%'");
		//dieArray(count($rows));

		$data_update = array();
		foreach($rows as $key=>$data) {
			//$name = @explode(" ",iconv_substr(trim($data['pers_firstname_th']), 6,strlen(trim($data['pers_firstname_th'])),"UTF-8"));

/*			$pers_lastname_th = '';
			if($data['pers_lastname_th']!='') {
				$pers_lastname_th = $data['pers_lastname_th'];
			}else {
				$tmp = $name;
				unset($tmp[0]);
				$pers_lastname_th = isset($name[1])?implode($tmp, " "):"";
			}
*/

			//$data_update[] = @array('pren_code'=>'004','pers_firstname_th'=>trim($name[0]),'pers_lastname_th'=>trim($name[1]),'gender_code'=>'2');
			$data_update = @array('pren_code'=>'003','pers_firstname_th'=>iconv_substr(trim($data['pers_firstname_th']), 3,strlen(trim($data['pers_firstname_th'])),"UTF-8"),'pers_lastname_th'=>$data['pers_lastname_th'],'gender_code'=>'1');
			
			$this->common_model->update('pers_info',$data_update,array('pers_id'=>$data['pers_id']));
		}
		dieArray($data_update);
		
	}

	public function pid() {
		$rows = $this->common_model->custom_query("select A.pers_id,A.pid,B.pid as pid2 from pers_info as A inner join tmp_pers_info as B on A.pers_id=B.pers_id where A.pid like '%-%'");
		//$rows = $this->common_model->custom_query("select A.pers_id,A.pid,B.pid as pid2,A.tel_no from pers_info as A inner join tmp_pers_info as B on A.pers_id=B.pers_id where A.pid like '%-%'");
		//dieArray(count($rows));
		$data_update = array();
		foreach ($rows as $key => $value) {

/*			if(iconv_substr($value['pid'],3,1,"UTF-8")=='-') {
				$data_update = array('pid'=>'-','tel_no'=>$value['pid']);
				$this->common_model->update('pers_info',$data_update,array('pers_id'=>$value['pers_id']));
			}*/
			if(iconv_substr($value['pid'],1,1,"UTF-8")=='-') {
				//$data_update[] = array('old_pid'=>$value['pid'],'old1_pid'=>$value['pid2'],'new_pid'=>str_replace("-","",$value['pid2']));
				$data_update = array('pid'=>str_replace("-","",$value['pid2']));
				$this->common_model->update('pers_info',$data_update,array('pers_id'=>$value['pers_id']));
			}

			//$data_update = array('pid'=>str_replace(" ","",$value['pid2']));

			//$this->common_model->update('pers_info',$data_update,array('pers_id'=>$value['pers_id']));
		}
		dieArray($data_update);
	}

	public function firstname(){
		$rows = $this->common_model->custom_query("select *,trim(pers_firstname_th) as pers_firstname_th from pers_info where trim(pers_firstname_th) like '% %'");
		//dieArray(count($rows));
		$data_update = array();
		foreach ($rows as $key => $value) {
			$tmp = explode(' ',$value['pers_firstname_th']);
			if(count($tmp)>1) {
				$strtmp = $tmp;
				unset($strtmp[0]);
				$data_update  = array(
					'pers_firstname_th'=>$tmp[0],
					'pers_lastname_th'=>implode("",$strtmp)
					);
				$this->common_model->update('pers_info',$data_update,array('pers_id'=>$value['pers_id']));
			}
		}
		//dieArray($data_update);
	}

	public function lastname() {
		$rows = $this->common_model->custom_query("select A.pers_id,B.pers_id,A.pid,B.pid,A.pren_code,B.pren_code,A.pers_firstname_th,A.pers_lastname_th,A.gender_code,B.gender_code,trim(B.full_name) as full_name,B.pers_firstname_th,B.pers_lastname_th from pers_info as A inner join tmp_pers_info as B on A.pers_id=B.pers_id where A.pers_firstname_th=''");
		//dieArray(count($rows));
		//dieArray($rows[1]);

		$data_update = array();
		foreach ($rows as $key => $value) {
			$tmp = explode(' ',$value['full_name']);
			if(count($tmp)>1) {
				$strtmp = $tmp;
				unset($strtmp[count($tmp)-1]);
				$data_update = array(
					'pers_firstname_th'=> @implode("",$strtmp),
					'pers_lastname_th'=> $tmp[count($tmp)-1],
				);
				$this->common_model->update('pers_info',$data_update,array('pers_id'=>$value['pers_id']));
			}
		}
		//dieArray($data_update);	
	}

	public function chkpid($pid='') {
		$tmps = $this->common_model->custom_query("select * from pers_info where pid='{$pid}'");
		$pers_id = '';
		foreach($tmps as $data) {
			$pers_id = $pers_id.$data['pers_id'].',';
		}
		//$pers_id = "102,468,3560";
		$pers_id = iconv_substr($pers_id,0,strlen($pers_id)-1,"UTF-8");

		if($pers_id=="") {
			dieFont("No pid.");
		}

		$rows['pers_info_addr_reg'] = $this->common_model->custom_query("select * from pers_info left join pers_addr on reg_addr_id=addr_id where pers_id in ({$pers_id})");

		$rows['pers_info_addr_pre'] = $this->common_model->custom_query("select * from pers_info left join pers_addr on pre_addr_id=addr_id where pers_id in ({$pers_id})");

		$rows['pers_family'] = $this->common_model->custom_query("select * from pers_family where pers_id in ({$pers_id})");
		$rows['pers_elderly_foundation'] = $this->common_model->custom_query("select * from pers_elderly_foundation where pers_id in ({$pers_id})");
		$rows['pers_disability'] = $this->common_model->custom_query("select * from pers_disability where pers_id in ({$pers_id})");
		$rows['pers_qol_indicators'] = $this->common_model->custom_query("select * from pers_qol_indicators where pers_id in ({$pers_id})");
		$rows['diff_info'] = $this->common_model->custom_query("select * from diff_info where pers_id in ({$pers_id})");

		$rows['fnrl_info'] = $this->common_model->custom_query("select * from fnrl_info where pers_id in ({$pers_id})");

		$rows['impv_home_info'] = $this->common_model->custom_query("select * from impv_home_info where pers_id in ({$pers_id})");

		$rows['impv_place_info'] = $this->common_model->custom_query("select * from impv_place_info where pers_id in ({$pers_id})");

		$rows['adm_info'] = $this->common_model->custom_query("select * from adm_info where pers_id in ({$pers_id})");
		$rows['adm_irp'] = $this->common_model->custom_query("select * from adm_irp where pers_id in ({$pers_id})");

		$rows['wisd_info'] = $this->common_model->custom_query("select * from wisd_info where pers_id in ({$pers_id})");

		$rows['volt_info'] = $this->common_model->custom_query("select * from volt_info where pers_id in ({$pers_id})");
		$rows['volt_info_elderly_care'] = $this->common_model->custom_query("select * from volt_info_elderly_care where pers_id in ({$pers_id})");

		$rows['prep_trn_trainee'] = $this->common_model->custom_query("select * from prep_trn_trainee where pers_id in ({$pers_id})");

		$rows['schl_info_student'] = $this->common_model->custom_query("select * from schl_info_student where pers_id in ({$pers_id})");

		$rows['edoe_older_emp_reg'] = $this->common_model->custom_query("select * from edoe_older_emp_reg where pers_id in ({$pers_id})");

		dieArray($rows);
	}

}
