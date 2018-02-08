<?php
	class Transfer_model extends CI_Model {

        function __construct() {
            parent::__construct();
        }

    public function getOnce_wsrv_info($wsrv_id=0) {
        $this->db_gateway->where('wsrv_id', $wsrv_id);
        $results = $this->db_gateway->get('wsrv_info')->result_array();
        return rowArray($results);
    }

    public function getAll_ownerOrg($wsrv_type='Import') {
        $query = $this->db_gateway->query("select owner_org from wsrv_info where wsrv_type='{$wsrv_type}' GROUP BY owner_org order by wsrv_id ASC");
        return $query->result_array();
    }
    public function getAll_importWSV()
    {
        $rows = $this->getAll_ownerOrg('Import');
        $data = array();
        foreach($rows as $row) {
            $this->db_gateway->where('owner_org', $row['owner_org']);
            $this->db_gateway->where('wsrv_type', 'Import');
            $data[] = array('name'=>$row['owner_org'],'data'=>$this->db_gateway->get('wsrv_info')->result_array());
        }
        return $data;
    }
    public function getAll_exportWSV()
    {
        $rows = $this->getAll_ownerOrg('Export');
        $data = array();
        foreach($rows as $row) {
            $this->db_gateway->where('owner_org', $row['owner_org']);
            $this->db_gateway->where('wsrv_type', 'Export');
            $data[] = array('name'=>$row['owner_org'],'data'=>$this->db_gateway->get('wsrv_info')->result_array());
        }
        return $data;
    }

    public function getAll_elementWSV($wsrv_id=0,$elem_type='Parameter')
    {
        $this->db_gateway->where('wsrv_id', $wsrv_id);
        $this->db_gateway->where('elem_type', $elem_type);
        $result = $this->db_gateway->get('wsrv_element')->result_array();
        return $result;
    }

    public function getAll_authenKey() {
        $query = $this->db_gateway->query("select *  
                from wsrv_authen_key as A   
                where (A.delete_user_id IS NULL && A.delete_datetime IS NULL) 
                order by A.insert_datetime DESC,
                         A.update_datetime DESC");
        return $query->result_array();
    }
    public function getOnce_authenKey($authen_id=0) {
        $query = $this->db_gateway->query("select *  
                from wsrv_authen_key as A   
                where (A.delete_user_id IS NULL && A.delete_datetime IS NULL) and 
                authen_id = {$authen_id}
                ");
        return rowArray($query->result_array());
    }
    public function getOnce_authenKey_byPID($pid=0) {
        $query = $this->db_gateway->query("select *  
                from wsrv_authen_key as A   
                where (A.delete_user_id IS NULL && A.delete_datetime IS NULL) and 
                pid = {$pid}
                ");
        return rowArray($query->result_array());
    }

    public function getAll_wsrvLog($wsrv_type='Import') {
        $query = $this->db_gateway->query("select A.*,B.*,C.*,CONCAT(C.user_firstname, ' ', C.user_lastname) as name   
                from wsrv_log as A 
                    left join wsrv_info as B on A.wsrv_id=B.wsrv_id 
                    left join wsrv_authen_key as C on A.req_authen_id=C.authen_id  
                where A.log_type='{$wsrv_type}' 
                order by A.req_datetime DESC 
                ");
        return $query->result_array();
    }

    public function getAll_dataStandard() {
        $result = $this->db_gateway->get('wsrv_std')->result_array();
        return $result;    
    }

    public function getAll_stdElement($std_id=0) {
        $this->db_gateway->where('std_id', $std_id);
        $result = $this->db_gateway->get('wsrv_std_element')->result_array();
        return $result;
    }

    public function get_numRowsStd($table='') {
        if($table=='')
            return 0;
        else {
            $tmp = rowArray($this->common_model->custom_query("select COUNT(*) from {$table}"));
            return $tmp['COUNT(*)'];
        }

    }

    public function chk_wsrvPermission($authen_id=0,$wsrv_id=0) {
        $this->db_gateway->where('authen_id', $authen_id);
        $this->db_gateway->where('wsrv_id', $wsrv_id);
        $result = rowArray($this->db_gateway->get('wsrv_permission')->result_array());
        if(isset($result['perm_status'])) {
            if($result['perm_status']=='Yes')
                return true;
            else
                return false;
        }else {
            return false;
        }
    }

    public function wsrv_logSave($req_authen_id='',$pid='',$req_datetime='',$wsrv_id=0,$log_note='',$log_type='',$log_status='Fail') {
        $this->db_gateway->insert(
            'wsrv_log', 
            array('req_authen_id'=>$req_authen_id,
                'pid'=>$pid,
                'req_datetime'=>$req_datetime,
                'end_datetime'=>date("Y-m-d H:i:s"),
                'wsrv_id'=>$wsrv_id,
                'log_note'=>$log_note,
                'log_type'=>$log_type,
                'log_status'=>$log_status,
                'process_time'=>strtotime(date("Y-m-d H:i:s"))-strtotime($req_datetime)
            )
        );
        return $this->db_gateway->insert_id();
    }

    public function curlGet($url='') {
        // connect via SSL, but don't check cert
        $handle=curl_init($url);
        curl_setopt($handle, CURLOPT_VERBOSE, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($handle);
        return $content; // show target page
    }
    
    public function LogSave($app_id=0,$process_action="",$log_action="",$log_status='',$user_id=0,$org_id=0) {
        if($user_id==0) {
            $user_id = get_session('user_id');
            $org_id = get_session('org_id');
        }

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


