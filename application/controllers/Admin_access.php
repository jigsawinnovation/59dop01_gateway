<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_access extends CI_Controller {

	public $db_gateway = null;

	function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->db_gateway = $this->load->database('gateway',true);
		$this->load->helper(array('url','form','general','file','html','asset','general_helper'));
		$this->load->library(array('session','encrypt'));
		$this->load->model(array('admin_model','member_model','common_model','useful_model','webinfo_model','transfer_model','authen_model'));

		$this->load->library('template',
			array('name'=>'web_template1',
				'setting'=>array('data_output'=>''))
			);
	}
	function __deconstruct() {
		$this->db->close();
		$this->db_gateway->close();
	}
	
	public function index(){
		$this->isLogin();
	}

	public function isLogin(){
		if(get_session('pid')!=""){
			redirect('main','refresh');
		} else {
			$data = array('wrn'=>' ');
			$data['info'] = $this->transfer_model->getAll_dataStandard();
			$this->template->load('login',$data);
		}
	}

	public function login(){

		if(get_inpost('pid')!='' && get_inpost('passcode')!='') {

			/* Checking User by Web Service -----------------------------------------------------------------------------------:WS:*/
			$result = true;
			if($result) {

				$row=$this->admin_model->getLoginPinID(str_replace("-","",get_inpost('pid')),get_inpost('passcode'));

				if(isset($row['pid'])) { //Check Empty Result

					set_session('user_id',$row['user_id']);
					set_session('pid',$row['pid']);
					set_session('user_firstname',$row['user_firstname']);
					set_session('user_lastname',$row['user_lastname']);
					set_session('user_position',$row['user_position']);
					set_session('licen','');

					set_session('org_id',$row['org_id']);
					$tmp = rowArray($this->common_model->get_where_custom_and('usrm_org',array('org_id'=>$row['org_id'])));
					if(isset($tmp['org_title'])) {
						set_session('org_title',$tmp['org_title']);
					}else {
						set_session('org_title','Not affiliated');
					}
					
					if($row['user_photo_file']!='') {
						set_session('user_photo_file',$row['user_photo_file']);
						set_session('user_photo_label',$row['user_photo_label']);
					}
					else {
						set_session('user_photo_file','noProfilePic.jpg');
					}

					$this->common_model->insert('usrm_log',
						array(
							'app_id'=> 0,
							'process_action'=>'Authen',
							'log_action'=>'Sign In',
							'user_id'=>$row['user_id'],
							'org_id'=>$row['org_id'],
							'log_datetime'=> getDatetime(),
							'log_status'=>'Success'
							)
						);				
					
					redirect('admin_access','refresh');
				}

			}

		}

		$this->template->load('login',array('wrn'=>'ผู้ใช้งานนี้ไม่สามารถเข้าสู่ระบบได้, กรุณาติดต่อผู้ดูแลระบบ!'));
		//die(print_r($this->session->all_userdata()));
	}

	public function authen_login(){
        
		$user = get_inpost('authenpid');
		$pass = get_inpost('autenpasscode');
        

		if($user!='' && $pass!=''){

           $pid = str_replace("-","",$user);

           $row = $this->authen_model->query_autent("SELECT *  FROM  wsrv_authen_key WHERE pid={$pid} AND active_status = 'Active' AND delete_user_id IS NULL AND delete_org_id IS NULL AND delete_datetime IS NULL");
		   
				   if(!empty($row)){

		               $pass_che = DeCrypt($row['passcode']);

		               if($pass===$pass_che){

					               	set_session('user_id',$row['authen_id']);
					               	set_session('pid',$row['pid']);
					               	set_session('user_firstname',$row['user_firstname']);
					               	set_session('user_lastname',$row['user_lastname']);
					               	set_session('user_position',$row['user_position']);
                                    set_session('licen','authen');
					               	
					               	$this->authen_model->insert('authen_key_log',
											array(
												'app_id'=> 0,
												'process_action'=>'Authen',
												'log_action'=>'Sign In',
												'authen_id'=>$row['authen_id'],
												'org_id'=>"",
												'log_datetime'=> getDatetime(),
												'log_status'=>'Success'
												)
											);			
			
                                    redirect('admin_access','refresh');	              		                  
		               }else{
		               	 $data['info'] 		= $this->transfer_model->getAll_dataStandard();
						 $data['authen_wrn'] = 'รหัสผ่านไม่ถูกต้อง!';
						 $this->template->load('login',$data);
		               }
				   }else{
				   	$data['info'] 		= $this->transfer_model->getAll_dataStandard();
					$data['authen_wrn'] = 'ไม่พบข้อมูลผู้ใช้ในระบบ!';
					$this->template->load('login',$data);
				   }
		}else{
         
         $data['info'] 		= $this->transfer_model->getAll_dataStandard();
		 $data['authen_wrn'] = 'ผู้ใช้งานนี้ไม่สามารถเข้าสู่ระบบได้,กรุณาติดต่อผู้ดูแลระบบ!';
		 $this->template->load('login',$data);
		}

	
		

	
	}

	public function logout(){
       
        if(get_session('licen')==''){
        	$mode = "admin";
        }else{
        	$mode = "authen";
        }
		 

		$this->session->sess_destroy();
		
		$this->common_model->insert('usrm_log',
			array(
				'app_id'=> 0,
				'process_action'=>'Authen',
				'log_action'=>'Sign Out',
				'user_id'=>"'".get_session('user_id')."'",
				'org_id'=>"'".get_session('org_id')."'",
				'log_datetime'=>getDatetime(),
				'log_status'=>'Success'
				)
			);

        if($mode=="authen"){
			redirect('admin_access','refresh');
	    }else{
        	redirect('admin_login','refresh');
	    }
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
