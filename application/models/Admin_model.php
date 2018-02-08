<?php
class Admin_model extends CI_Model {

    public function __construct(){
        parent::__construct();
		//$this->load->driver('cache');
    }

    public function getLoginPinID($pid='',$passcode=''){ //Used
        $row=rowArray($this->common_model->get_where_custom_and('usrm_user',array('pid'=>$this->db->escape_str($pid),'passcode'=>$this->db->escape_str($passcode))));
        return $row;
    }

    public function getAll_usrmApplication($user_id=0){ //Used for navbar
        return $this->common_model->custom_query("select * from usrm_application as A inner join usrm_permission as B 
            on A.app_id=B.app_id 
            where B.user_id={$user_id} AND A.app_parent_id=0 order by A.app_id ASC");
    }
    
    public function getOnce_Application($app_id=0){ //Used for find application 
        return rowArray($this->common_model->custom_query("select * from usrm_application where app_id={$app_id}"));
    }    

    public function chkOnce_usrmPermiss($app_id=0,$user_id=0) { //Used for check user permission
        //return rowArray($this->common_model->get_where_custom_and("usrm_permission",array('app_id'=>$app_id,'user_id'=>$user_id)));
        //return rowArray($this->common_model->custom_query("select A.*,B.app_name,B.app_parent_id from usrm_permission as A inner join usrm_application as B on A.app_id=B.app_id where A.perm_status='Yes' AND A.app_id={$app_id} AND user_id={$user_id}"));
        $chk1 = rowArray($this->common_model->custom_query("select A.*,B.app_name,B.app_parent_id from usrm_permission as A inner join usrm_application as B on A.app_id=B.app_id where A.perm_status='Yes' AND A.app_id={$app_id} AND user_id={$user_id}"));
        if(isset($chk1['app_id'])) {
            return $chk1;
        }else {
            $tmp = $this->admin_model->getOnce_Application($app_id); //Used for find root application 
            if(!isset($tmp['app_id'])) {
                return array();
            }else {
                $chk2 = rowArray($this->common_model->custom_query("select A.*,B.app_name,B.app_parent_id from usrm_permission as A inner join usrm_application as B on A.app_id=B.app_id where A.perm_status='Yes' AND A.app_id={$tmp['app_parent_id']} AND user_id={$user_id}"));
                if(!isset($chk2['app_id'])) {
                    return array();
                }else {
                    $chk2['app_id'] = $tmp['app_id'];
                    $chk2['app_name'] = $tmp['app_name'];
                    $chk2['app_parent_id'] = $tmp['app_parent_id'];
                    return $chk2;
                }
            }
        }
    }

/*    public function getLogin($user_id='',$user_pass=''){
        $row=$this->common_model->get_where_custom_and('member',array('M_Username'=>$this->db->escape_str($user_id),'M_Password'=>$this->db->escape_str($user_pass)));
        return $row;
    }*/
/*    public function getLoginMD5($user_id='',$user_pass=''){
         $row=$this->common_model->get_where_custom_and('member',array('M_Username'=>$this->db->escape_str($user_id),'M_Password'=>md5($this->db->escape_str($user_pass))));
        return $row;
    }*/

/*    public function getLoginEncrypt($user_id='',$user_pass=''){
        $row=$this->common_model->custom_query("select * from member where M_Username='{$this->db->escape_str($user_id)}' order by M_ID DESC limit 1");
        if(count($row)>0){
            $pass=$this->encrypt->decode($row[0]['M_Password']);
            if($this->db->escape_str($user_pass)==$pass)
                return $row;
        }else
            return array();
    }*/

/*    public function AfterDelete_DeleteImage($table='',$field_name='',$value='',$field_delete='',$path=''){
        $rows=$this->common_model->get_where_custom_field($table,$field_name,$value,$field_delete);
        foreach($rows as $row){
            @unlink($path.$row[$field_delete]);
        }
    }
*/
/*    public function BlockLevel($levels=array()){
        if(get_session('M_GroupUser')==''){
            redirect('login','refresh');
            exit();
        }
        else {
            foreach($levels as $level){
                if($level==get_session('M_GroupUser')){
                    redirect('login','refresh');
                    exit();
                }
            }
        }
    }*/

/*    //get user real ip
     public function kh_getUserIP(){
           $client  = @$_SERVER['HTTP_CLIENT_IP'];
           $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
           $remote  = $_SERVER['REMOTE_ADDR'];

           if(filter_var($client, FILTER_VALIDATE_IP)){
               $ip = $client;
           }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
               $ip = $forward;
           }else{
               $ip = $remote;
           }
           return $ip;
      }

      public function get_http_user_agent(){
        return $_SERVER['HTTP_USER_AGENT'];
      }*/

//      public function getMac(){
        /*
        * Getting MAC Address using PHP
        * Md. Nazmul Basher
        */

/*        ob_start(); // Turn on output buffering
        system('ipconfig /all'); //Execute external program to display output
        $mycom=ob_get_contents(); // Capture the output into a variable
        ob_clean(); // Clean (erase) the output buffer

        $findme = "Physical";
        $pmac = strpos($mycom, $findme); // Find the position of Physical text
        $mac=substr($mycom,($pmac+36),17); // Get Physical Address

        return $mac;
      }*/
/*      public function getCoordinatesFromAddress( $sQuery, $sCountry = 'it' )
      {
          $sURL = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($sQuery).'&sensor=false&region='.$sCountry.'&language='.$sCountry;
          $sData = file_get_contents($sURL);
          return json_decode($sData);
       }*/

/*      public function getAddressFromCoordinates( $dLatitude, $dLongitude, $sCountry = 'it' )
      {
        $sURL = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.urlencode("$dLatitude,$dLongitude").'&sensor=false&region='.$sCountry.'&language='.$sCountry;
        $sData = file_get_contents($sURL);
      	return json_decode($sData);
      }*/
/*      public function get_requesttime(){
        return $_SERVER['REQUEST_TIME'];
      }*/
/*      public function getDeviceType(){
        $mobile_browser = '0';

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');

        if (in_array($mobile_ua,$mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
            $mobile_browser = 0;
            dieFont('windows');
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'mac') > 0) {
                $mobile_browser = 0;
                dieFont('mac');
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'ios') > 0) {
                $mobile_browser = 1;
                dieFont('ios');
        }
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') > 0) {
                $mobile_browser = 1;
                dieFont('android');
        }

        if($mobile_browser == 0)
        {
            //its a mobile browser
        } else {
            //its not a mobile browser
        }
      }*/

/*    public function createCaptcha(){
        $this->load->helper('captcha');
        $vals = array(
            'img_path'      => './assets/captcha/',
            'img_url'       => base_url('assets/captcha/'),
            'font_path'     => "./assets/fonts/ThaiSansNeue/thaisansneue-bold-webfont.ttf",
            'img_width'     => 200,
            'img_height'    => 48,
            'expiration'    => 7200,
            'word_length'   => 5,
            'font_size'     => 28,
            'img_id'        => 'Imageid',
            'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

            // White background and border, black text and red grid
            'colors'        => array(
                'background' => array(219, 242, 250),
                'border' => array(255, 255, 255),
                'text' => array(15, 12, 114),
                'grid' => array(255, 255, 255)
            )
        );

        $cap = create_captcha($vals);
        $data = array(
            'captcha_time'  => $cap['time'],
            'ip_address'    => $this->input->ip_address(),
            'word'          => $cap['word']
        );

        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);
        // echo 'Submit the word you see below:';
        // echo $cap['image'];
        // echo '<input type="text" name="captcha" value="" />';
        return $cap['image'];
    }*/

/*      public function ConfirmCaptcha(){
        // First, delete old captchas
        $expiration = time() - 7200; // Two hour limit
        $this->db->where('captcha_time < ', $expiration)
                ->delete('captcha');

        // Then see if a captcha exists:
        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
        $binds = array(get_inpost('captcha'), $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();

        if($row->count == 0)
          return false;
        else
          return true;
      }*/
      
/*    public function cannot_access(){
        if(getUser()!='')
            dieFont("Cannot Access!");
        else
            redirect('login','refresh');
        exit();
    }*/
    
/*    public function accessBlock($GM_ID,$GP_Value,$P_Process){
        $GM_ID = $GM_ID;
        $GP_Value = $GP_Value;

        $P_ID = '';
        $GP_ID = '';

        $temp = $this->getOnce_group_members($GM_ID); //check group 
        if(!isset($temp['GM_ID']))
            $this->cannot_access();
        else{ 
            $temp = $this->getOnce_permissions_with_process($P_Process); //check permission
            if(!isset($temp['P_ID']))
                $this->cannot_access();
            $P_ID = $temp['P_ID'];

            $temp = $this->getOnce_group_permissions_with_value($GM_ID,$P_ID,$GP_Value); //check access permission
            if(!isset($temp['GP_ID']))
                $this->cannot_access();
            else 
                return $P_ID;
        }
    }*/

/*    public function get_permiss_iniz(&$GM_ID,&$GP_Value,&$P_Process,&$data){
        $GM_ID = get_session('GM_ID');
        if($GM_ID=='')
            $this->cannot_access();
        $data['GM_ID'] = $GM_ID;

        $data['GP_Value'] = $GP_Value;

        $P_Process = $P_Process==''?uri_seg(1).'/'.uri_seg(2):$P_Process; //Default 2 Segment

        $data['P_Process'] = $P_Process;

        $data['P_ID'] = $this->accessBlock($GM_ID,$GP_Value,$P_Process);
        $temp = $this->getOnce_permissions($data['P_ID']);
        $data['Permission'] = $temp;
        $data['Parent_ID'] = $temp['Parent_ID'];
        $data['Root_Parent_ID'] = $temp['Parent_ID'];
        if($temp['Parent_ID'] != 0)
            $data['Root_Parent_ID'] = $this->get_RootParentID_permissions($data['Parent_ID']); 
    } */    

/*    public function getAll_group_members(){
        return $this->common_model->custom_query("select 
            A.GM_ID,A.GM_Name,A.GM_Descript,A.GM_UserAdd,A.GM_DateTimeAdd,A.GM_UserUpdate,A.GM_DateTimeUpdate,A.GM_Allow,
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd,C.M_ThName as M_ThNameUpdate,C.M_EnName as M_EnNameUpdate  
            from group_members as A 
            left join member as B on B.M_ID=A.GM_UserAdd 
            left join member as C on C.M_ID=A.GM_UserUpdate 
            where A.GM_Allow='1' and A.GM_ID!=0");
    }*/
/*    public function getOnce_group_members($GM_ID){
        return rowArray($this->common_model->custom_query("select 
            A.GM_ID,A.GM_Name,A.GM_Descript,A.GM_UserAdd,A.GM_DateTimeAdd,A.GM_UserUpdate,A.GM_DateTimeUpdate,A.GM_Allow,
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd,C.M_ThName as M_ThNameUpdate,C.M_EnName as M_EnNameUpdate  
            from group_members as A 
            left join member as B on B.M_ID=A.GM_UserAdd 
            left join member as C on C.M_ID=A.GM_UserUpdate 
            where A.GM_ID={$GM_ID} AND A.GM_Allow='1'")
        );
    }  */

/*    public function getAll_permissions(){
        return $this->common_model->custom_query("select 
            A.P_ID,A.P_Name,A.P_FontAweIcon,A.P_Process,A.P_Order,A.Parent_ID,A.P_UserAdd,A.P_DateTimeAdd,A.P_UserUpdate,A.P_DateTimeUpdate,A.P_Allow,
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd,C.M_ThName as M_ThNameUpdate,C.M_EnName as M_EnNameUpdate   
            from permissions as A 
            left join member as B on B.M_ID=A.P_UserAdd 
            left join member as C on C.M_ID=A.P_UserAdd 
            where A.P_Allow='1'");
    }*/
/*    public function getOnce_permissions($P_ID){
        return rowArray($this->common_model->custom_query("select 
            A.P_ID,A.P_Name,A.P_FontAweIcon,A.P_Process,A.P_Order,A.Parent_ID,A.P_UserAdd,A.P_DateTimeAdd,A.P_UserUpdate,A.P_DateTimeUpdate,A.P_Allow,
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd,C.M_ThName as M_ThNameUpdate,C.M_EnName as M_EnNameUpdate   
            from permissions as A 
            left join member as B on B.M_ID=A.P_UserAdd 
            left join member as C on C.M_ID=A.P_UserAdd 
            where A.P_ID={$P_ID} AND A.P_Allow='1'")
        );
    } */
/*    public function getOnce_permissions_with_process($P_Process){
        return rowArray($this->common_model->custom_query("select 
            A.P_ID,A.P_Name,A.P_FontAweIcon,A.P_Process,A.P_Order,A.Parent_ID,A.P_UserAdd,A.P_DateTimeAdd,A.P_UserUpdate,A.P_DateTimeUpdate,A.P_Allow,
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd,C.M_ThName as M_ThNameUpdate,C.M_EnName as M_EnNameUpdate   
            from permissions as A 
            left join member as B on B.M_ID=A.P_UserAdd 
            left join member as C on C.M_ID=A.P_UserAdd 
            where A.P_Process='{$P_Process}' AND A.P_Allow='1'")
        );
    }    
*/
/*    public function getAll_permissions_with_parentID($Parent_ID){
        return $this->common_model->custom_query("select 
            A.P_ID,A.P_Name,A.P_FontAweIcon,A.P_Process,A.P_Order,A.Parent_ID,A.P_UserAdd,A.P_DateTimeAdd,A.P_UserUpdate,A.P_DateTimeUpdate,A.P_Allow,
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd,C.M_ThName as M_ThNameUpdate,C.M_EnName as M_EnNameUpdate   
            from permissions as A 
            left join member as B on B.M_ID=A.P_UserAdd 
            left join member as C on C.M_ID=A.P_UserAdd 
            where A.Parent_ID={$Parent_ID} AND A.P_Allow='1' order by A.P_Order ASC"
        );
    }*/

/*    public function get_RootParentID_permissions($P_ID){
        while(1){
            $temp = $this->common_model->get_where_custom_and('permissions',array('P_ID' => $P_ID,'P_Allow'=>'1'));
            if(isset($temp['P_ID']))$P_ID = $temp['Parent_ID'];
            else return $P_ID;
        }
    }*/

/*    public function getAll_group_permissions(){
        return $this->common_model->custom_query("select 
            A.GP_ID,A.GP_Value,A.GM_ID,A.P_ID,A.GP_UserAdd,A.GP_DateTimeAdd,A.GP_Allow, 
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd, 
            C.M_ThName as M_ThNameUpdate,B.M_EnName as M_EnNameUpdate 
            from group_permissions as A 
            left join member as B on B.M_ID=A.GP_UserAdd 
            left join member as C on C.M_ID=A.GP_UserUpdate 
            where A.GP_Allow='1'");
    }*/
/*    public function getOnce_group_permissions($GP_ID,$GM_ID,$P_ID,$GP_Value){
        return rowArray($this->common_model->custom_query("select 
            A.GP_ID,A.GP_Value,A.GM_ID,A.P_ID,A.GP_UserAdd,A.GP_DateTimeAdd,A.GP_Allow, 
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd, 
            C.M_ThName as M_ThNameUpdate,B.M_EnName as M_EnNameUpdate 
            from group_permissions as A 
            left join member as B on B.M_ID=A.GP_UserAdd 
            left join member as C on C.M_ID=A.GP_UserUpdate 
            where A.GP_ID=$GP_ID AND A.GM_ID=$GM_ID AND A.P_ID=$P_ID AND GP_Value=$GP_Value AND GP_Allow='1'")
        );
    } */  
/*    public function getOnce_group_permissions_with_value($GM_ID,$P_ID,$GP_Value){
        return rowArray($this->common_model->custom_query("select 
            A.GP_ID,A.GP_Value,A.GM_ID,A.P_ID,A.GP_UserAdd,A.GP_DateTimeAdd,A.GP_Allow, 
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd, 
            C.M_ThName as M_ThNameUpdate,B.M_EnName as M_EnNameUpdate 
            from group_permissions as A 
            left join member as B on B.M_ID=A.GP_UserAdd 
            left join member as C on C.M_ID=A.GP_UserUpdate 
            where A.GM_ID=$GM_ID AND A.P_ID=$P_ID AND GP_Value=$GP_Value AND GP_Allow='1'")
        );
    }  */ 
/*    public function getOnce_group_permissions_with_P_ID($GM_ID,$P_ID){
        return rowArray($this->common_model->custom_query("select 
            A.GP_ID,A.GP_Value,A.GM_ID,A.P_ID,A.GP_UserAdd,A.GP_DateTimeAdd,A.GP_Allow, 
            B.M_ThName as M_ThNameAdd,B.M_EnName as M_EnNameAdd 
            from group_permissions as A 
            left join member as B on B.M_ID=A.GP_UserAdd 
            where A.GM_ID=$GM_ID AND A.P_ID=$P_ID AND GP_Allow='1'")
        );
    } */

/*    public function getPGP(&$P_ID_arr = array(),$GM_ID = 0,$Parent_ID=0) {
        $process = $this->admin_model->getAll_permissions_with_parentID($Parent_ID);
        if(count($process)>0)
        foreach ($process as $key => $value) {
            $temp = $this->getOnce_group_permissions_with_P_ID($GM_ID,$value['P_ID']);

            if(isset($temp['GP_ID'])) {
                $P_ID_arr[] = 1;
            }else {
                $P_ID_arr[] = 0;
            }
            $this->getPGP($P_ID_arr,$GM_ID,$value['P_ID']);
        }      
    }*/
}

?>
