<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rssfeed extends CI_Controller {

	public $db_gateway = null;

	function __construct() {
		parent::__construct();

		$this->load->database();
		$this->db_gateway = $this->load->database('gateway',true);

		$this->load->helper(array('url','form','general','file','html','asset'));
		$this->load->library(array('session','encrypt'));
        $this->load->model(array('admin_model','member_model','common_model','useful_model','webinfo_model'));

        ob_end_clean();
	}
	function __deconstruct() {
		$this->db->close();
		$this->db_gateway->close();
	}	
	
	public function index(){

	}

	public function generate($table='') {
		$this->output->set_content_type('xml', 'utf-8')->set_output($this->load->view('web_template1/rssxml', array('table'=>$table),TRUE));
	}




}
