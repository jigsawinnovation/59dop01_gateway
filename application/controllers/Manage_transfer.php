<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_transfer extends CI_Controller {

	public $db_gateway = null;

	function __construct() {
		parent::__construct();

		$this->load->database();
		$this->db_gateway = $this->load->database('gateway',true);

		$this->load->helper(array('url','form','general','file','html','asset'));
		$this->load->library(array('session','encrypt'));
        $this->load->model(array('admin_model','member_model','common_model','useful_model','webinfo_model','transfer_model','authen_model'));

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

	public function test_importService() {
		require(APPPATH.'libraries/REST_Controller.php');
		$this->load->library('PHPRequests');

		$chk = true;

		if($chk) {
            //$response = Requests::get("{$this->HostBMSO}/ictcmsodopinsterchange/ICTCMSODOPInterChange.RequestRandom.ICTCMSORequestRandomService/data/{$this->UserBMSO}/{$this->PassBMSO}/{$OfficerPID}/{$OfficerCID}");
                        
/*        	$wsrv_info = $this->transfer_model->getOnce_wsrv_info(get_inpost('wsrv_id'));
        	$url = $wsrv_info['wsrv_url'].$wsrv_info['wsrv_req_template'];
        	$url = str_replace("{","",$url);
        	$url = str_replace("}","",$url);*/
              
            //echo json_encode(array('url'=>$url));  

            //$url = str_replace('paraUserName',get_inpost,$url);
            //$url = str_replace('paraPassword',$this->PassBMSO,$url);
            //$url = str_replace('paraOfficerPID',$OfficerPID,$url);
            //$url = str_replace('paraOfficerCID',$OfficerCID,$url);

            //$response = Requests::get($url);
            //$responseData = $response->body; // ได้ข้อมูล json กลับมา
                    
            //$responseData = $this->transfer_model->curlGet($url);
            //echo $responseData;
            echo json_encode(array('code'=>'0','message'=>''));
		}else {
			echo json_encode(array('code'=>'-1','message'=>'ข้อมูลร้องขอไม่ถูกต้อง'));
		}

/*		if(get_inpost('wsrv_protocal')=='get') {
			echo json_encode($_GET);
		}else if(get_inpost('wsrv_protocal')=='post'){
			echo json_encode($_POST);
		}else {
			echo json_encode(array());
		}*/
		
	}	

	public function import($process_action='View') { // Import
		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		$app_id = 101;
		$process_path = 'manage_transfer/import';
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

    		/*-- bootstrap-toggle --*/
    		set_css_asset_head('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.css');
    		set_js_asset_footer('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.js');
    		/*-- End bootstrap-toggle --*/

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/import';

			$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			$data['title'] = $usrpm['app_name'];
		
			$this->template->load('index_page',$data);
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		}
		
	}

	public function export($process_action='View') { // Export
		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		$app_id = 102;
		$process_path = 'manage_transfer/export';
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

			$data['exportwsv_info'] = $this->transfer_model->getAll_exportWSV();

			$this->load->library('template',
				array('name'=>'web_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template

    		/*-- Toastr style --*/
    		set_css_asset_head('../plugins/Static_Full_Version/css/plugins/toastr/toastr.min.css');
    		set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/toastr/toastr.min.js');
    		/*-- End Toastr style --*/
			set_js_asset_footer('export.js'); //Set JS Export.js 

    		/*-- bootstrap-toggle --*/
    		set_css_asset_head('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.css');
    		set_js_asset_footer('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.js');
    		/*-- End bootstrap-toggle --*/

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/export';

			$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			$data['title'] = $usrpm['app_name'];
		
			$this->template->load('index_page',$data);
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		}
		
	}	

	public function datastandard($process_action='View') { // Datastandard
		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		//$app_id = 101;
		$process_path = 'manage_transfer/datastandard';
		/*--END Inizial Data for Check User Permission--*/

		//$this->webinfo_model->LogSave($app_id,$process_action,'Sign In','Success'); //Save Sign In Log
		//$usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

		//if(@$usrpm['perm_status']=='No' || !isset($usrpm['app_id'])){
		//	page500(); 
		//	$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Fail'); //Save Sign In Log
		//}else {
			//$app_name = $usrpm['app_name'];
			$app_name = 'ชุดข้อมูลมาตรฐาน (Data Standard)';
			//$data['usrpm'] = $usrpm;
			$data['usrpm'] = array();
			$data['user_id'] = $user_id;

			//$data['importwsv_info'] = $this->transfer_model->getAll_importWSV();

			$data['info'] = $this->transfer_model->getAll_dataStandard();

			$this->load->library('template',
				array('name'=>'web_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template

			/*-- Load Datatables for Theme --*/
			set_css_asset_head('../plugins/Static_Full_Version/css/plugins/dataTables/datatables.min.css');
			set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/dataTables/datatables.min.js');
			/*-- End Load Datatables for Theme --*/
    		/*-- Toastr style --*/
    		set_css_asset_head('../plugins/Static_Full_Version/css/plugins/toastr/toastr.min.css');
    		set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/toastr/toastr.min.js');
    		/*-- End Toastr style --*/
    		/*-- bootstrap-toggle --*/
    		//set_css_asset_head('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.css');
    		//set_js_asset_footer('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.js');
    		/*-- End bootstrap-toggle --*/

			set_js_asset_footer('datastandard.js'); //Set JS datastandard.js 

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/datastandard';

			//$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$tmp = $this->admin_model->getOnce_Application(99); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			//$data['title'] = $usrpm['app_name'];
			$data['title'] = $app_name;
		
			$this->template->load('index_page',$data);
			//$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		//}
		
	}

	public function data_warehouse($process_action='View') { // Data Warehouse
		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		$app_id = 104;
		$process_path = 'manage_transfer/data_warehouse';
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

			//$data['info'] = $this->transfer_model->getAll_exportWSV();

			$this->load->library('template',
				array('name'=>'web_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template

			/*-- Load Datatables for Theme --*/
			set_css_asset_head('../plugins/Static_Full_Version/css/plugins/dataTables/datatables.min.css');
			set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/dataTables/datatables.min.js');
			/*-- End Load Datatables for Theme --*/
    		/*-- Toastr style --*/
    		set_css_asset_head('../plugins/Static_Full_Version/css/plugins/toastr/toastr.min.css');
    		set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/toastr/toastr.min.js');
    		/*-- End Toastr style --*/
    		/*-- bootstrap-toggle --*/
    		//set_css_asset_head('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.css');
    		//set_js_asset_footer('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.js');
    		/*-- End bootstrap-toggle --*/
			set_js_asset_footer('data_warehouse.js'); //Set JS Export.js 

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/data_warehouse';

			$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			$data['title'] = $usrpm['app_name'];
		
			$this->template->load('index_page',$data);
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		}
		
	}	

	public function webservice_demo($process_action='View') { // Web Service Demo
		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		$app_id = 156;
		$process_path = 'manage_transfer/webservice_demo';
		/*--END Inizial Data for Check User Permission--*/

		$this->webinfo_model->LogSave($app_id,$process_action,'Sign In','Success'); //Save Sign In Log
		$usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

		if(@$usrpm['perm_status']=='No' || !isset($usrpm['app_id'])){
			page500(); 
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Fail'); //Save Sign In Log
		}else {
			$app_name = $usrpm['app_name'];
			$data['usrpm'] = $usrpm;
			$data['usrpm'] = array();
			$data['user_id'] = $user_id;

			//$data['importwsv_info'] = $this->transfer_model->getAll_importWSV();

			$this->load->library('template',
				array('name'=>'web_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template

    		/*-- Toastr style --*/
    		set_css_asset_head('../plugins/Static_Full_Version/css/plugins/toastr/toastr.min.css');
    		set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/toastr/toastr.min.js');
    		/*-- End Toastr style --*/

			set_js_asset_footer('webservice_demo.js'); //Set JS webservice_demo.js 

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/webservice_demo';

			$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$tmp = $this->admin_model->getOnce_Application(99); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			$data['title'] = 'ทดสอบ'.$usrpm['app_name'];
		
			$this->template->load('index_page',$data);
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		}
		
	}

	public function authen_key($process_action='View',$authen_id='') { // ระบบจัดการกุญแจสำหรับยืนยันตัวตน (Authentication Key)
		

		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		$app_id = 103;
		$process_path = 'manage_transfer/authen_key';
		/*--END Inizial Data for Check User Permission--*/
          if(get_session('licen')==''){

			  		   $this->webinfo_model->LogSave($app_id,$process_action,'Sign In','Success'); //Save Sign In Log
			  $usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission
           }else{
           	           $this->webinfo_model->LogSave_authen($app_id,$process_action,'Sign In','Success'); //Save Sign In Log
			  $usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission
           }

		if(@$usrpm['perm_status']=='No' || !isset($usrpm['app_id'])){
			page500(); 
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Fail'); //Save Sign In Log
		}else {
			$app_name = $usrpm['app_name'];
			$data['usrpm'] = $usrpm;
			$data['user_id'] = $user_id;

			$data['authen_info'] = $this->transfer_model->getAll_authenKey();
            $data['importwsv_info'] = $this->transfer_model->getAll_importWSV();
            $data['exportwsv_info'] = $this->transfer_model->getAll_exportWSV();
            $data['prename'] = $this->common_model->custom_query("SELECT * FROM std_prename");
			$this->load->library('template',
				array('name'=>'admin_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template
            
            /*-- datepicker --*/
			set_css_asset_head('../plugins/bootstrap-datepicker1.3.0/css/datepicker.css');
			set_js_asset_head('../plugins/bootstrap-datepicker1.3.0/js/bootstrap-datepicker.js');
			/*-- End datepicker --*/
			
			/*-- Load Datatables for Theme --*/
			set_css_asset_head('../plugins/Static_Full_Version/css/plugins/dataTables/datatables.min.css');
			set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/dataTables/datatables.min.js');
			/*-- End Load Datatables for Theme --*/
    		/*-- Toastr style --*/
    		set_css_asset_head('../plugins/Static_Full_Version/css/plugins/toastr/toastr.min.css');
    		set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/toastr/toastr.min.js');
    		/*-- End Toastr style --*/
			set_js_asset_footer('authen_key.js'); //Set JS authen_key.js

            

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/authen_key';

			$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			$data['title'] = $usrpm['app_name'];

			$data['csrf'] = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);	

			if($process_action=='Added' && get_inpost('bt_submit')!='' && $usrpm['perm_status']=='Yes') {
                      // dieArray($_POST);
				     $passcode = randomSring(10);

				     $data_insert 						= array();
				     $data_insert 						= get_inpost_arr('val');
				     $data_insert['pid'] 				= str_replace("-","",$data_insert['pid']);
				     $data_insert['passcode'] 			= EnCrypt($passcode);
				     $data_insert['active_status'] 		= "Active";
				     $data_insert['date_of_birth'] 		= dateChange($data_insert['date_of_birth'],4);

				     $data_insert['insert_user_id'] 	= getUser();
				     $data_insert['insert_org_id']  	= get_session('org_id');
				     $data_insert['insert_datetime']	= getDatetime();
                     
                     // dieArray($data_insert);
                     $id = $this->authen_model->insert("wsrv_authen_key",$data_insert);
				     
				     $this->load->library('email');
				     $this->email->from('your@example.com', 'บริษัทจิ๊กซอว์อินโนเวชั่น');
				     $this->email->to($data_insert['email_addr']); //ส่งถึงใคร
				     $this->email->subject('รหัสการเข้าใช้งาน ระบบจัดการกุญแจสำหรับยืนยันตัวตน'); //หัวข้อของอีเมล
				     $this->email->message('ท่านสามารถเข้าสู่ระบบด้วยหมายเลขบัตรประจำตัวประชาชนจำนวน 13 หลัก  รหัสการเข้าใช้งานของท่านคือ ='.DeCrypt($data_insert['passcode']));	
                     $this->email->send();
                     // echo $this->email->print_debugger();

				    
				     $data_permisstion = get_inpost_arr('wsrv');

				     
                     foreach ($data_permisstion as $key => $value) {
                         	    $data_permiss = array("authen_id"	=> $id,
                         	    					  "wsrv_id"		=> $value,
                         	    					  "perm_status" => "Yes");
                         	    $this->authen_model->insert("wsrv_permission",$data_permiss);
                     }

                     $this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
				    
                      redirect('manage_transfer/authen_key','refresh');


			}else if($process_action=="View" && $usrpm['perm_status']=='Yes'){

					$authen_info =array("pid"				=>"",
										"passcode"			=>"",
										"user_prename"		=>"",
										"user_firstname"	=>"",
										"user_lastname"		=>"",
										"user_gender"		=>"1",
										"date_of_birth"		=>date("Y-m-d"),
										"user_org"			=>"",
										"user_position"		=>"",
										"tel_no"			=>"",
										"email_addr"		=>"");

					$data["authen_val"] = $authen_info;

			}else if($process_action=='Edit' && $usrpm['perm_status']=='Yes'){
                      if($authen_id==''){
                      	page500(); 
                      	$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Fail');
                      }else{
      					$data['authen_val'] = $this->transfer_model->getOnce_authenKey($authen_id);
      					
                        // dieArray($data['authen_val']);
                      }
			}else if($process_action=='Edited' && get_inpost('bt_submit')!='' && $usrpm['perm_status']=='Yes'){
		       
                 
                 $data_update = array();
                 $data_update = get_inpost_arr('val');
                 $data_update['pid'] 				= str_replace("-","",$data_update['pid']);
                 $data_update['date_of_birth'] 		= dateChange($data_update['date_of_birth'],4);

                 $data_update['update_user_id'] 	= getUser();
                 $data_update['update_org_id']  	= get_session('org_id');
                 $data_update['update_datetime']	= getDatetime();

                 // dieArray($data_update);
                $authen_id = get_inpost('authen_id');

                 $this->authen_model->update("wsrv_authen_key",$data_update,array('authen_id'=>$authen_id));

                 $this->authen_model->delete_where('wsrv_permission','authen_id',$authen_id);

		         $data_permisstion = get_inpost_arr('wsrv');

				   // dieArray($data_permisstion);  
                     foreach ($data_permisstion as $key => $value) {
                         	    $data_permiss = array("authen_id"	=> $authen_id,
                         	    					  "wsrv_id"		=> $value,
                         	    					  "perm_status" => "Yes");
                         	    $this->authen_model->insert("wsrv_permission",$data_permiss);
                     }
                
                $this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
				    
                 redirect('manage_transfer/authen_key','refresh');


		    }else if($process_action=='Delete' && $usrpm['perm_status']=='Yes'){
                    $data_delete = array();
                    $data_delete['delete_user_id']  = getUser();
                    $data_delete['delete_org_id']   = get_session('org_id');
                    $data_delete['delete_datetime'] = getDatetime();

                    $this->authen_model->update("wsrv_authen_key",$data_delete,array('authen_id'=>$authen_id));
                    $this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success');
                    redirect('manage_transfer/authen_key','refresh');
		    }

		    
            // dieArray($data);
			$this->template->load('index_page',$data);
			$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		    
		}
		
	}


	public function statistic_importlog_list($process_action='View') {
		/*-- Initial Data for Check User Permission --*/
		// $user_id = get_session('user_id');
		// $app_id = 3;
		// $process_path = 'manage_transfer/log';
		// /*--END Inizial Data for Check User Permission--*/

		// $this->webinfo_model->LogSave($app_id,$process_action,'Sign In',$user_id); //Save Sign In Log
		// $usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

		// if(@$usrpm['perm_can_view']=='No' || !isset($usrpm['app_id'])){
		// 	page500();
		// }else {

		$this->load->model('statistic_importlog_model','manage_transfer');
		$list = $this->manage_transfer->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $i=>$manage_transfer) {
			$no++;
			$row = array();

            $arr = explode(' ',$manage_transfer->req_datetime);        
			$row[] = dateChange($arr[0],5).' '.$arr[1].'น.'; 

			$row[] = $manage_transfer->pid;
			$row[] = $manage_transfer->name;
			$row[] = $manage_transfer->wsrv_name;
			$row[] = $manage_transfer->owner_org;

			$txt_log_status = '';
            if($manage_transfer->log_status == 'Success'){
              $txt_log_status = '<font color="green">'.$manage_transfer->log_status.'</font>';
            }else{
              $txt_log_status = '<font color="red">'.$manage_transfer->log_status.'</font>';
            } 
            $row[] = $txt_log_status;

            $txt_result = '';
            $tmp = @unserialize($manage_transfer->log_note);
            if(@$tmp['result']=='Success' && isset($tmp['result'])) {
              if(isset($tmp['result']['pid'])) {
              	$txt_result = $tmp['result']['pid'].', ';
          	  }
            }else {
              $txt_result = @$tmp['result']['pid'];
              if(isset($tmp['result']['code'])) {
              	if($tmp['result']['code']!='0') {
              		$txt_result = 'Error code: '.$txt_result.$tmp['result']['code'];
              	}
              }
            }

            $time_txt = '';
            if(strtotime($manage_transfer->req_datetime)!=0) {
            	$time = strtotime($manage_transfer->end_datetime)-strtotime($manage_transfer->req_datetime);
            	if($time>=60) {
            		$time_txt = intval($time/60).'m';
            	}
            	if($time%60>0) {
            		if($time>=60) {
            			$time_txt = intval($time/60).'m '.($time%60).'s';
            		}else {
            			$time_txt = ($time%60).'s';
            		}
            	}
        	}

			$modal_info = array(
				'req_datetime'=>$manage_transfer->req_datetime,
				'end_datetime'=>$time_txt,
				'pid'=>$manage_transfer->pid,
				'name'=>$manage_transfer->name,
				'wsrv_name'=>$manage_transfer->wsrv_name,
				'owner_org'=>$manage_transfer->owner_org,
				'wsrv_via'=>$manage_transfer->wsrv_via,
				'wsrv_url'=>$manage_transfer->wsrv_url,
				'log_status'=>$txt_log_status,
				'txt_result'=>$txt_result
			);

			$row[] = $txt_result;
			$row[] = '<a class="btn btn-default lnk" data-info=\''.json_encode($modal_info).'\'><i class="fa fa-info-circle" aria-hidden="true"></i></a>';
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->manage_transfer->count_all(),
						"recordsFiltered" => $this->manage_transfer->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
		//$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out',$user_id); //Save Sign Out Log
	}
	public function statistic_importlog($process_action='View') { // สถิติการแลกเปลี่ยนข้อมูลระหว่างหน่วยงาน :: ข้อมูลนำเข้า
		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		//$app_id = 101;
		$process_path = 'manage_transfer/statistic_importlog';
		/*--END Inizial Data for Check User Permission--*/

		//$this->webinfo_model->LogSave($app_id,$process_action,'Sign In','Success'); //Save Sign In Log
		//$usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

		//if(@$usrpm['perm_status']=='No' || !isset($usrpm['app_id'])){
		//	page500(); 
		//	$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Fail'); //Save Sign In Log
		//}else {
			//$app_name = $usrpm['app_name'];
			$app_name = 'ข้อมูลนำเข้า';
			//$data['usrpm'] = $usrpm;
			$data['usrpm'] = array();
			$data['user_id'] = $user_id;

			//$data['info'] = $this->transfer_model->getAll_wsrvLog();

			$this->load->library('template',
				array('name'=>'web_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template

			/*-- Load Datatables for Theme --*/
			set_css_asset_head('../plugins/Static_Full_Version/css/plugins/dataTables/datatables.min.css');
			set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/dataTables/datatables.min.js');
			/*-- End Load Datatables for Theme --*/
    		/*-- Toastr style --*/
    		set_css_asset_head('../plugins/Static_Full_Version/css/plugins/toastr/toastr.min.css');
    		set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/toastr/toastr.min.js');
    		/*-- End Toastr style --*/
    		/*-- bootstrap-toggle --*/
    		//set_css_asset_head('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.css');
    		//set_js_asset_footer('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.js');
    		/*-- End bootstrap-toggle --*/

			set_js_asset_footer('import_list.js'); //Set JS import_list.js

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/import_list';

			//$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$tmp = $this->admin_model->getOnce_Application(99); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			//$data['title'] = $usrpm['app_name'];
			$data['title'] = $app_name;
		
			$this->template->load('index_page',$data);
			//$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		//}
		
	}

	public function statistic_exportlog_list($process_action='View') {
		/*-- Initial Data for Check User Permission --*/
		// $user_id = get_session('user_id');
		// $app_id = 3;
		// $process_path = 'manage_transfer/log';
		// /*--END Inizial Data for Check User Permission--*/

		// $this->webinfo_model->LogSave($app_id,$process_action,'Sign In',$user_id); //Save Sign In Log
		// $usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

		// if(@$usrpm['perm_can_view']=='No' || !isset($usrpm['app_id'])){
		// 	page500();
		// }else {

		$this->load->model('statistic_exportlog_model','manage_transfer');
		$list = $this->manage_transfer->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $i=>$manage_transfer) {
			$no++;
			$row = array();

            $arr = explode(' ',$manage_transfer->req_datetime);        
			$row[] = dateChange($arr[0],5).' '.$arr[1].'น.'; 

			$row[] = $manage_transfer->pid;
			$row[] = $manage_transfer->name;
			$row[] = $manage_transfer->wsrv_name;
			$row[] = $manage_transfer->owner_org;

			$txt_log_status = '';
            if($manage_transfer->log_status == 'Success'){
              $txt_log_status = '<font color="green">'.$manage_transfer->log_status.'</font>';
            }else{
              $txt_log_status = '<font color="red">'.$manage_transfer->log_status.'</font>';
            } 
            $row[] = $txt_log_status;

            $txt_result = '';
            $tmp = @unserialize($manage_transfer->log_note);
            if(@$tmp['result']=='Success' && isset($tmp['result'])) {
              if(isset($tmp['result']['pid'])) {
              	$txt_result = $tmp['result']['pid'].', ';
          	  }
            }else {
              $txt_result = @$tmp['result']['pid'];
              if(isset($tmp['result']['code'])) {
              	if($tmp['result']['code']!='0') {
              		$txt_result = 'Error code: '.$txt_result.$tmp['result']['code'];
              	}
              }
            }

            $time_txt = '';
            if(strtotime($manage_transfer->req_datetime)!=0) {
            	$time = strtotime($manage_transfer->end_datetime)-strtotime($manage_transfer->req_datetime);
            	if($time>=60) {
            		$time_txt = intval($time/60).'m';
            	}
            	if($time%60>0) {
            		if($time>=60) {
            			$time_txt = intval($time/60).'m '.($time%60).'s';
            		}else {
            			$time_txt = ($time%60).'s';
            		}
            	}
        	}

			$modal_info = array(
				'req_datetime'=>$manage_transfer->req_datetime,
				'end_datetime'=>$time_txt,
				'pid'=>$manage_transfer->pid,
				'name'=>$manage_transfer->name,
				'wsrv_name'=>$manage_transfer->wsrv_name,
				'owner_org'=>$manage_transfer->owner_org,
				'wsrv_via'=>$manage_transfer->wsrv_via,
				'wsrv_url'=>$manage_transfer->wsrv_url,
				'log_status'=>$txt_log_status,
				'txt_result'=>$txt_result
			);

			$row[] = $txt_result;
			$row[] = '<a class="btn btn-default lnk" data-info=\''.json_encode($modal_info).'\'><i class="fa fa-info-circle" aria-hidden="true"></i></a>';
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->manage_transfer->count_all(),
						"recordsFiltered" => $this->manage_transfer->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
		//$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out',$user_id); //Save Sign Out Log
	}
	public function statistic_exportlog($process_action='View') { // สถิติการแลกเปลี่ยนข้อมูลระหว่างหน่วยงาน :: ข้อมูลส่งออก
		$data = array(); //Set Initial Variable to Views
		/*-- Initial Data for Check User Permission --*/
		$user_id = get_session('user_id');
		//$app_id = 101;
		$process_path = 'manage_transfer/statistic_exportlog';
		/*--END Inizial Data for Check User Permission--*/

		//$this->webinfo_model->LogSave($app_id,$process_action,'Sign In','Success'); //Save Sign In Log
		//$usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

		//if(@$usrpm['perm_status']=='No' || !isset($usrpm['app_id'])){
		//	page500(); 
		//	$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Fail'); //Save Sign In Log
		//}else {
			//$app_name = $usrpm['app_name'];
			$app_name = 'ข้อมูลส่งออก';
			//$data['usrpm'] = $usrpm;
			$data['usrpm'] = array();
			$data['user_id'] = $user_id;

			//$data['info'] = $this->transfer_model->getAll_wsrvLog('Export');

			$this->load->library('template',
				array('name'=>'web_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template

			/*-- Load Datatables for Theme --*/
			set_css_asset_head('../plugins/Static_Full_Version/css/plugins/dataTables/datatables.min.css');
			set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/dataTables/datatables.min.js');
			/*-- End Load Datatables for Theme --*/
    		/*-- Toastr style --*/
    		set_css_asset_head('../plugins/Static_Full_Version/css/plugins/toastr/toastr.min.css');
    		set_js_asset_footer('../plugins/Static_Full_Version/js/plugins/toastr/toastr.min.js');
    		/*-- End Toastr style --*/
    		/*-- bootstrap-toggle --*/
    		//set_css_asset_head('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.css');
    		//set_js_asset_footer('../plugins/bootstrap-toggle2.2.2/bootstrap-toggle.min.js');
    		/*-- End bootstrap-toggle --*/

			set_js_asset_footer('export_list.js'); //Set JS export_list.js 

			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/export_list';

			//$tmp = $this->admin_model->getOnce_Application($usrpm['app_parent_id']); //Used for find root application 
			$tmp = $this->admin_model->getOnce_Application(99); //Used for find root application 
			$data['head_title'] = $tmp['app_name'];
			//$data['title'] = $usrpm['app_name'];
			$data['title'] = $app_name;
		
			$this->template->load('index_page',$data);
			//$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out','Success'); //Save Sign Out Log
		//}
		
	}	
/*
	public function import_log($process_action='View') { // ประวัติรายการเชื่อมต่อ (Log)
		$data = array(); //Set Initial Variable to Views

		/*-- Initial Data for Check User Permission --*/
/*
		$user_id = get_session('user_id');
		$app_id = 3;
		$process_path = 'manage_transfer/log';
		/*--END Inizial Data for Check User Permission--*/
/*
		$this->webinfo_model->LogSave($app_id,$process_action,'Sign In',$user_id); //Save Sign In Log
		$usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission
*/
/*
		if(@$usrpm['perm_can_view']=='No' || !isset($usrpm['app_id'])){
			page500();
		}else {
			$app_name = $usrpm['app_name'];
			$data['usrpm'] = $usrpm;
			$data['user_id'] = $user_id;

			$data['log_info'] = array(
				array('index1'=>'2017-04-10 10.23 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'เข้าใช้งานระบบ','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991'),
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ออกจากระบบ','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูล Log','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-05-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูลชื่อ-นามสกุลภาษาไทย','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')	
					,
				array('index1'=>'2017-05-10 10.40 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูลชื่อ-นามสกุลภาษาอังกฤษ','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-05-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูลชื่อ-นามสกุลภาษาไทย','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')	
					,
				array('index1'=>'2017-05-10 10.40 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูลชื่อ-นามสกุลภาษาอังกฤษ','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')		
					,
				array('index1'=>'2017-07-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูล Log','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-05-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูลชื่อ-นามสกุลภาษาไทย','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')	
					,
				array('index1'=>'2017-05-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูลชื่อ-นามสกุลภาษาไทย','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')	
					,
				array('index1'=>'2017-05-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ดึงข้อมูลชื่อ-นามสกุลภาษาไทย','index5'=>'สำเร็จ','index6'=>'ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')	
					,			
			);

			$this->load->library('template',
				array('name'=>'admin_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template
			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/log';
			$data['head_title'] = 'Gateway';
			$data['title'] = 'ประวัติรายการเชื่อมต่อ (Import Log)';
		
			$this->template->load('index_page',$data);
		}
		$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out',$user_id); //Save Sign Out Log
	}
	*/

/*
	public function export_log($process_action='View') { // ประวัติรายการเชื่อมต่อ (Log)
		$data = array(); //Set Initial Variable to Views

		/*-- Initial Data for Check User Permission --*/
/*
		$user_id = get_session('user_id');
		$app_id = 3;
		$process_path = 'manage_transfer/log';
		/*--END Inizial Data for Check User Permission--*/
/*
		$this->webinfo_model->LogSave($app_id,$process_action,'Sign In',$user_id); //Save Sign In Log
		$usrpm = $this->admin_model->chkOnce_usrmPermiss($app_id,$user_id); //Check User Permission

		if(@$usrpm['perm_can_view']=='No' || !isset($usrpm['app_id'])){
			page500();
		}else {
			$app_name = $usrpm['app_name'];
			$data['usrpm'] = $usrpm;
			$data['user_id'] = $user_id;

			$data['log_info'] = array(
				array('index1'=>'2017-04-10 10.23 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'เข้าใช้งานระบบ','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991'),
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'ออกจากระบบ','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')
					,
				array('index1'=>'2017-04-10 10.37 น.','index2'=>'dopadmin','index3'=>'ICTC DOP Administrator','index4'=>'DOP Admission','index5'=>'สำเร็จ','index6'=>'กรมกิจการผู้สูงอายุ','index7'=>'ศูนย์พัฒนาการจัดสวัสดิการสังคมผู้สูงอาย','index8'=>'','index9'=>'Target PID = 1559900091991')		
			);

			$this->load->library('template',
				array('name'=>'admin_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template
			$data['process_action'] = $process_action;	
			$data['content_view'] = 'content/log';
			$data['head_title'] = 'Gateway';
			$data['title'] = 'ประวัติรายการเชื่อมต่อ (Export Log)';
		
			$this->template->load('index_page',$data);
		}
		$this->webinfo_model->LogSave($app_id,$process_action,'Sign Out',$user_id); //Save Sign Out Log
	}
	*/
}
