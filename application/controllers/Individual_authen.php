<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Individual_authen extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->database();
		$this->db_gateway = $this->load->database('gateway',true);

		$this->load->helper(array('url','form','general','file','html','asset'));
		$this->load->library(array('session','encrypt'));
        $this->load->model(array('admin_model','member_model','common_model','useful_model','webinfo_model','transfer_model','authen_model','personal_model'));

	
	
	}

	function __deconstruct() {
		$this->db->close();
		$this->db_gateway->close();
	}

    public function authen_encode(){
      $encode = EnCrypt(get_inpost('id'),20);
      redirect('Individual_authen/authen_info?id='.$encode);
    }

	public function authen_info($process_action='View'){

		 $this->load->library('template',
				array('name'=>'web_template1',
					  'setting'=>array('data_output'=>''))
			); // Set Template

		 // echo encrypt($_GET['id']);
		 if($_GET['id']!=''){
		 	  if(strlen($_GET['id'])==20){
		 	        $pers_id = DeCrypt($_GET['id']);
		 	  }else{
		 	  	   $pers_id = '';
		 	  }

					 if(is_numeric($pers_id)){
					 	 $pid_id = $pers_id;
					 	 $count =strlen($pers_id);
					 	
					 	 if($count==13){
			                
			                $pers_info = $this->authen_model->query_row("SELECT
															*
														FROM
															pers_info
														WHERE
															pid = {$pid_id}
														AND (delete_user_id IS NULL)
														AND (
															delete_datetime IS NULL || delete_datetime = '0000-00-00 00:00:00'
														)
														ORDER BY pers_id LIMIT 1");
			                  if(empty($pers_info)){                
			                  	//********ไม่พบข้อมูลตามเลขบัตรประชาชน**************
			                    $data['profile'] = false;
			                    $data['comment'] = "ไม่พบข้อมูลตามหมายเลข ".$pid_id." นี้ !";
			                  }else{
			                  	//********พบข้อมูลตามเลขบัตรประชาชน************";
			                  	$data['profile'] = true;
			                  	$data['pers_info'] = $this->authen_model->getPersonalInfo_authen($pers_info['pers_id']);
			                     
			                     if(!empty($data['pers_info']['date_of_birth'])){
			                         //******กรณีพบอายุ**************
					                    $str = explode(" ",$data['pers_info']['date_of_birth']);
					                    $age = $str[4];
					                    $data['age'] = $age;
					              }else{
					              	$data['age'] = '';
					              }
			                            // if($age>=60){
			                            	
								                    //$strbri = explode("-",$data['date_of_birth']);
								                     //dieArray($data['pers_info']);
								                     $addr_home_no 			=  $data['pers_info']['reg_addr']['addr_home_no'];
								                     $addr_sub_district 	=  $data['pers_info']['reg_addr']['addr_sub_district'];

								                     $result_indicator = $this->authen_model->get_indicator($addr_home_no,$addr_sub_district);
								                     if(!empty($result_indicator)){
								                     	// dieArray($result_indicator);
								                     	 $result_indicator[0]['yearly_income_of_family'];
								                     	if(count($result_indicator)>1){
								                            //ถ้าพบที่อยู่ จปฐ มากกว่า 1
								                            $data['status_indicator'] = "show";
								                            $data['indicator_info'] = $result_indicator;
								                     	}else{
								                     		//ถ้าพบที่อยู่ จปฐ เท่ากับ 1
								                     		
								                     		$yearly_family = $result_indicator[0]['yearly_income_of_family'];

								                     		$data['status_indicator'] = $this->authen_model->che_yearly_family($yearly_family);
								                     	}

								                     }

								                     
					                 	// }else{
					                 	// 	//******กรณีอายุน้อยกว่ากว่าเม่ากับ 60 ปี ***************
					                 	// 	$data['profile'] = false;
			                    // 			$data['comment'] = "อายุตามเลขบัตรประชาชนนี้คือ ".$age." ปี ซึ่งต้องมีอายุ 60 ปี ขึ้นไปหรือมากกว่า";
					                 	// }
					             // }else{
					             // 	//******กรณีไม่พบอายุ**************
					             // 	$data['profile'] = false;
			               //      	$data['comment'] = "ไม่พบข้อมูลอายุของท่าน";
					             // }


			                 }
			             }else{
			             	
			             	$data['profile'] = false;
			             }
					 }else{
					 	
					 	 $data['profile'] = false;
					 }
		}else{
			$data['profile'] = false;
		}
		 
         
         $data['content_view'] = 'content/individual_info';
		 $this->template->load('index_page_authen',$data);
	}

	public function authencation_key(){

		
		if($_GET['id']==''||$_GET['p']==''){
           page500();
		}else{
			if(strlen($_GET['p'])!=128){
				page500();
			}else{
				echo $_GET['id'],"<br>".$_GET['p']."<br>";
			}
		}
		
	}

}