<?php
class Authen_model extends CI_Model {

    public $da_authen = null; 
    public function __construct(){
        parent::__construct();
		//$this->load->driver('cache');
		$this->db_center = $this->load->database('default',true);
		$this->db_authen = $this->load->database('gateway',true);
    }

    public function query_autent($mysql_query) {	
		$query = $this->db_authen->query($mysql_query);
		return $query->row_array();
	}

	public function insert($table, $data) {
		$this->db_authen->insert($table, $data);
		return $this->db_authen->insert_id();
	}

	 public function getPersonalInfo_authen($pers_id = '') {
        $arr = array();
        if($pers_id!='') {
            $tmp = $this->personal_model->getOnce_PersonalInfo($pers_id);
            if(isset($tmp['pid'])) {
                $tmp['reg_addr'] = array();
                if($tmp['reg_addr_id']!='') {
                    $tmp1 = $this->personal_model->getOnce_PersonalAddress($tmp['reg_addr_id']);
                    if(isset($tmp1['addr_id'])) {
                        $tmp['reg_addr'] = $tmp1;
                        $tmp['reg_add_info'] = "{$tmp1['addr_home_no']} หมู่ {$tmp1['addr_moo']} ต. {$tmp1['addr_sub_district']} อ. {$tmp1['addr_district']} จ. {$tmp1['addr_province']} {$tmp1['addr_zipcode']}";
                    }
                }

                if($tmp['date_of_birth']!='') {
                  $date = new DateTime($tmp['date_of_birth']);
                  $now = new DateTime();
                  $interval = $now->diff($date);
                  $age = $interval->y;
                  $tmp['date_of_birth'] = formatDateThai($tmp['date_of_birth']).' (อายุ '.$age.' ปี)';
                  $tmp['age'] = $age;
                }

            }

            $arr = $tmp;
        }
        return $arr;
    }


	public function chk_authen_diff($pers_id=0){
		return $this->common_model->custom_query("SELECT
                                                        *
			FROM
			diff_info AS A
			LEFT JOIN pers_info AS B ON A.pers_id = B.pers_id
			WHERE
			A.pers_id = {$pers_id}
			AND B.delete_user_id IS NULL
			AND (B.delete_datetime IS NULL || B.delete_datetime = '0000-00-00 00:00:00')                                                                          
			ORDER BY A.date_of_req DESC"                                                   
			);
	}

	public function chk_authen_adm($pers_id=0){
		return $this->common_model->custom_query("SELECT
                                                        *
			FROM
			adm_info AS A
			LEFT JOIN pers_info AS B ON A.pers_id = B.pers_id
			WHERE
			A.pers_id = {$pers_id}

			AND A.delete_user_id IS NULL
			AND (
			A.delete_datetime IS NULL || A.delete_datetime = '0000-00-00 00:00:00'
			)
			ORDER BY A.date_of_adm DESC");
	}

	public function chk_authen_fnrl($pers_id=0){
		return $this->common_model->custom_query("SELECT
                                                        *
			FROM
			fnrl_info AS A
			LEFT JOIN pers_info AS B ON A.pers_id = B.pers_id
			WHERE
			A.pers_id = {$pers_id}

			AND A.delete_user_id IS NULL
			AND (
			A.delete_datetime IS NULL || A.delete_datetime = '0000-00-00 00:00:00'
			)"
			);
	}

	public function chk_authen_edop($pers_id=0){
		return $this->common_model->custom_query("SELECT * FROM edoe_older_emp_reg WHERE pers_id={$pers_id} GROUP BY date_of_reg DESC");

	}

	public function chk_authen_elderly($pers_id=0){
		return $this->common_model->custom_query("SELECT * FROM pers_elderly_foundation WHERE pers_id={$pers_id} GROUP BY year_of_contract DESC");

	}

	public function query_row($mysql_query='') {
		$result = $this->common_model->custom_query($mysql_query);
		return rowArray($result);
	}

	public function Editpermission_getId($authen_id=''){
		$result = $this->db_authen->query("SELECT * FROM wsrv_permission WHERE authen_id={$authen_id} AND perm_status='Yes' ");
		return $result->result_array();
	}

	public function update($table, $data, $condition) {
		$this->db_authen->update($table, $data, $condition);
	}



	  public function delete_where($tb_name,$column,$column_id){
		$this->db_authen->where($column, $column_id);
		$this->db_authen->delete($tb_name);
  }

  public function get_indicator($addr_home_no='',$addr_sub_district=''){
      $result = $this->db_center->query("SELECT
											*
										FROM
											pers_qol_indicators
										WHERE
											addr_home_no = '999/1/10'
										AND addr_sub_district = 110130
										GROUP BY
											addr_home_no,addr_sub_district,occupation,yearly_income_of_family");
	 return $result->result_array();							  
	}

	public function che_yearly_family($yearly_family){

		if($yearly_family!=''){

			if($yearly_family<=38000){
				 return "<h3 style=\"color:green;\">( ผ่านเกณฑ์ )</h3>";
			}else{
				 return "<h3 style=\"color:red;\"> (รายได้ ".number_format($yearly_family)."	บาท ) ไม่ผ่านเกณฑ์ </h3>";
			}

		}else{
			return "<h3 style=\"color:red;\">( ไม่พบข้อมูลรายได้ ) </h3>";
		}

	}





}

?>

