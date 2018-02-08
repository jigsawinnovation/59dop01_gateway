<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_login extends CI_Controller {

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
			$this->template->load('login_admin',$data);
		}
	}

}