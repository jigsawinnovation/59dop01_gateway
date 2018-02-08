<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistic_exportlog_model extends CI_Model {

	var $table = 'wsrv_log';
	var $column_order = array('A.req_datetime','A.pid','name','C.wsrv_name','C.owner_org','A.log_status','A.log_note'); //set column field database for datatable orderable
	var $column_search = array('A.pid','B.wsrv_name','B.owner_org'); //set column field database for datatable searchable 
	//var $column_search = array('A.req_datetime','A.pid',"CONCAT(C.user_firstname, ' ', C.user_lastname)",'B.wsrv_name','B.owner_org','A.log_status','A.log_note'); //set column field database for datatable searchable 
	var $order = array('A.req_datetime' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db_gateway = $this->load->database('gateway',true);
		$this->db_gateway->select("A.*");
		$this->db_gateway->select("B.*");
		$this->db_gateway->select("C.*");
		//$this->db_gateway->select("CONCAT(C.user_firstname, ' ', C.user_lastname) as name");
		$this->db_gateway->select("C.user_firstname as name");

		$this->db_gateway->from($this->table." as A");
		$this->db_gateway->join('wsrv_info as B', 'A.wsrv_id = B.wsrv_id', 'left');
		$this->db_gateway->join('wsrv_authen_key as C', 'A.req_authen_id = C.authen_id', 'left');

		$this->db_gateway->where("log_type =",'Export');// เพิ่ม where log_type = Import

		//$this->column_search = array_diff($this->column_search, array('D_DateTimeAdd', 'D_DateTimeUpdate'));

		// $i = 0;
	
		// foreach ($this->column_search as $item) // loop column 
		// {

		// 	if($_POST['search']['value']) // if datatable send POST for search
		// 	{
				
		// 		if($i===0) // first loop
		// 		{
		// 			$this->db_gateway->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
		// 			$this->db_gateway->like($item, $_POST['search']['value']);
		// 		}
		// 		else
		// 		{
		// 			$this->db_gateway->or_like($item, $_POST['search']['value']);
		// 		}

		// 		if(count($this->column_search) - 1 == $i) //last loop
		// 			$this->db_gateway->group_end(); //close bracket
				
		// 	}
		// 	$i++;
		// }

/*		if($_POST['search']['value']){
			foreach ($allow as $key => $val) {
				if(stristr($val,$_POST['search']['value']) == true){
					//$this->db_gateway->or_where("D_Allow",$key);
				}
			}
		}
*/

			foreach ($_POST['columns'] as $colId => $col) {
				// dieArray($_POST);
			if($col['search']['value']) // if datatable send POST for search
			{
				$arr = @explode('/', $col['search']['value']);
				// if(count($arr)==3) {
				// dieArray($arr);
					if(count($arr) > 2){	
						$this->db_gateway->like($col['name'], dateChange($col['search']['value'],0));
						// $this->db->like($col['name'], $col['search']['value']);
						// dieFont(dateChange($col['search']['value'],1));
					}else{
						$this->db_gateway->like($col['name'], $col['search']['value']);
					}
				// }
			}
		}
		
		// dieArray($this->db_gateway);
		if(isset($_POST['order'])) // here order processing
		{
			$this->db_gateway->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db_gateway->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1){
			$this->db_gateway->limit($_POST['length'], $_POST['start']);
		}
		//$this->db_gateway->where("log_type =",'Import');// เพิ่ม where log_type = Import
		$query = $this->db_gateway->get(); 
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		//$this->db_gateway->where("log_type =",'Import');// เพิ่ม where log_type = Import
		$query = $this->db_gateway->get(); 
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db_gateway->from($this->table);
		//$this->db_gateway->where("log_type =",'Import');// เพิ่ม where log_type = Import
		return $this->db_gateway->count_all_results(); 
	}

}
