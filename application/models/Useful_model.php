<?php
	class useful_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

    public function getNumRows($table)
    {
        $rs=$this->db->get($table);
        return $rs->num_rows();
    }

	public function getOnceRow($rows){
		if(sizeof($rows)>0)
			return $rows[0];
		else
			return array();
	}

    public function getMaxid($column,$tb_name)
    {
		$this->db->select_max($column);
		$query = $this->db->get($tb_name);
		foreach ($query->result() as $row)
		{}
		$max_id = $row->news_id;

		return $max_id;
    }

	public function get_min($table,$column) {
		$this->db->select_min($column);
		$query = $this->db->get($table);
		$row=$query->row();
		$id=$row->$column;
		return $id;
	}

	public function get_max($table,$column) {
		$this->db->select_max($column);
		$query = $this->db->get($table);
		$row=$query->row();
		$id=$row->$column;
		return $id;
	}

	public function getPrimaryKey($table) {
		$fields = $this->db->field_data($table);
		foreach ($fields as $field)
		{
		   if($field->primary_key == 1) {
				return $field->name;
		   }
		}
	}

	public function searchFor($table, $criteria, $limit=false, $offset=0) {
		if($limit != false) {
			$this->db->limit($limit, $offset);
		}
		$this->db->where($criteria);
		$query = $this->db->get($table);
		$result = $query->result_array();
		return $result;

	}

	public function searchForOrderBy($table, $criteria, $orderBy, $order, $limit=false, $offset=0) {
		if($limit != false) {
			$this->db->limit($limit, $offset);
		}
		$this->db->where($fieldName, $value);
		$this->db->order_by($orderBy, $order);
		$query = $this->db->get($table);
		$result = $query->result_array();
		return $result;
	}

	public function count_where($table, $column, $value) {
			$this->db->where($column, $value);
			$query=$this->db->get($table);
			$num_rows = $query->num_rows();
			return $num_rows;
	}

	public function count_where_and($table, $column1, $value1, $column2, $value2) {
			$this->db->where($column1, $value1);
			$this->db->where($column2, $value2);
			$query=$this->db->get($table);
			$num_rows = $query->num_rows();
			return $num_rows;
	}

	public function count_all_one($table) {
			$this->db->limit(1, 0);
			$query=$this->db->get($table);
			$num_rows = $query->num_rows();
			return $num_rows;
	}

	/*
	public function count_all($table) {
			$query=$this->db->get($table);
			$num_rows = $query->num_rows();
			return $num_rows;
	}*/

	public function platform() {
		return $this->db->platform();
	}

	public function version() {
		return $this->db->version();
	}

	public function count_all($table) {
		return $this->db->count_all($table);
	}
	
	public function last_query() {
		return $this->db->last_query();
	}

	public function transaction($sql=''){
		$this->db->trans_begin();

		$this->db->query($sql);

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
		    $this->db->trans_commit();
		}
	}

    public function getMaxOrder($table,$field,$where=''){
        $row=rowArray($this->common_model->custom_query("select max(".$field.") from ".$table." where ".$where));
        return (int)$row["max(".$field.")"];
    }
    public function getMaxOrder_and_Add($table,$field,$where=''){
        $row=rowArray($this->common_model->custom_query("select max(".$field.") from ".$table." where ".$where));
        settype($row["max(".$field.")"],'integer');
        return ++$row["max(".$field.")"];
    }

}
?>
