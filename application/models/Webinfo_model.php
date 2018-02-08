<?php
	class Webinfo_model extends CI_Model {

        function __construct() {
            parent::__construct();
        }

    public function getSiteInfo(){
        return rowArray($this->common_model->custom_query("select 
            A.*,B.user_prename,B.user_firstname,B.user_lastname  
            from db_gateway.site_setting as A 
            left join usrm_user as B on A.update_user_id=B.user_id 
            "));
    }

    public function LogSave($app_id=0,$process_action="",$log_action="",$log_status='') {
        $user_id = get_session('user_id')!=''?get_session('user_id'):0;
        $org_id = get_session('org_id')!=''?get_session('org_id'):0;
        
        return $this->common_model->insert('usrm_log',
            array('app_id'=>$app_id,
                'process_action'=>$process_action,
                'log_action'=>$log_action,
                'user_id'=>$user_id,
                'org_id'=>$org_id,
                'log_datetime'=>getDatetime(),
                'log_status'=>$log_status
                )
        );
    } 

    public function LogSave_authen($app_id=0,$process_action="",$log_action="",$log_status='') {
        $user_id = get_session('user_id')!=''?get_session('user_id'):0;
        $org_id = get_session('org_id')!=''?get_session('org_id'):0;
        
        return $this->common_model->insert('usrm_log',
            array('app_id'=>$app_id,
                'process_action'=>$process_action,
                'log_action'=>$log_action,
                'user_id'=>$user_id,
                'org_id'=>$org_id,
                'log_datetime'=>getDatetime(),
                'log_status'=>$log_status
                )
        );
    } 

}
?>


