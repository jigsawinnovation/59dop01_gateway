<?php
class Member_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function getAll_Member() {
        return $this->common_model->custom_query("select * from usrm_user");
    }
    public function getOnce_Member($user_id=0) {
        return rowArray($this->common_model->custom_query("select * from usrm_user where user_id=".$user_id));
    }  

    /*
    public function getAll_members() {
        return $this->common_model->custom_query("select 
            A.M_ID,A.M_Img,A.M_Username,A.M_Password,A.M_TName,A.M_ThName,A.M_EnName,A.M_Sex,A.M_Birthdate,A.M_npID,A.M_Tel,A.M_Email,A.M_Address,A.GM_ID,A.M_UserAdd,A.M_DateTimeAdd,A.M_DateTimeUpdate,A.M_UserUpdate,A.M_Type,A.M_Allow, 
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd,C.M_ThName as M_ThNameUpdate,C.M_EnName as M_EnNameUpdate,A.M_Position,A.M_UnitName,
            D.GM_Name 
            from member as A 
            left join member as B on B.M_ID=A.M_UserAdd 
            left join member as C on C.M_ID=A.M_UserUpdate 
            left join group_members as D on D.GM_ID=A.GM_ID 
            where A.M_Type='1' AND A.M_Allow='1' AND A.GM_ID!=0 order by D.GM_ID asc,A.M_DateTimeUpdate DESC");
    }
    */
    /*
    public function getOnce_members($M_ID=0) {
        return rowArray($this->common_model->custom_query("select 
            A.M_ID,A.M_Img,A.M_Username,A.M_Password,A.M_TName,A.M_ThName,A.M_EnName,A.M_Sex,A.M_Birthdate,A.M_npID,A.M_Tel,A.M_Email,A.M_Address,A.GM_ID,A.M_UserAdd,A.M_DateTimeAdd,A.M_DateTimeUpdate,A.M_UserUpdate,A.M_Type,A.M_Allow, 
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd,C.M_ThName as M_ThNameUpdate,C.M_EnName as M_EnNameUpdate,A.M_Position,A.M_UnitName,
            D.GM_Name 
            from member as A 
            left join member as B on B.M_ID=A.M_UserAdd 
            left join member as C on C.M_ID=A.M_UserUpdate 
            left join group_members as D on D.GM_ID=A.GM_ID 
            where A.M_Type='1' AND A.M_Allow='1' AND A.M_ID={$M_ID}")
        );
    } 
    */
    /* 
    public function getUsername_with_MID($M_ID=0) {
        return rowArray($this->common_model->get_where_custom_field('member','M_ID',$M_ID,'M_ID'));
    } 
    */
    /*
    public function getUsername_with_npID($M_npID='') {
        return rowArray($this->common_model->get_where_custom_field('member','M_npID',$M_npID,'M_ID'));
    } 
    */

}

?>
