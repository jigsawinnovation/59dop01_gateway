<?php
	class Files_model extends CI_Model {

        function __construct() {
            parent::__construct();
        }

        public function getOnceImg($field_name='',$folder_name=''){
            if(isset($_FILES[$field_name]['name'])){
            $this->load->library('upload');
                if($_FILES[$field_name]['name']!=''){
                    $config=array();
                    $config['upload_path'] = $folder_name;
                    $config['allowed_types'] ='gif|jpeg|jpg|png';
                    $config['file_name'] = RandomNameFileEncode();

                    $this->upload->initialize($config);
                    if($this->upload->do_upload($field_name))
                    {
                        $data_upload = $this->upload->data();
                        $field_name=$data_upload['file_name'];
                        return $field_name;
                    }
                }
            }
            return '';
        }

        public function getMultiImg($field_name='',$folder_name=''){
            if($_FILES[$field_name]['name'][0]!='')
            {
                $error=array();
                $name_array=array();

                $count = count($_FILES[$field_name]['size']);

                if($count>0){
                    $this->load->library('upload');
                    for($i=0; $i<$count; $i++){
                        $_FILES['userfile']['name']=$_FILES[$field_name]['name'][$i];
                        $_FILES['userfile']['type'] = $_FILES[$field_name]['type'][$i];
                        $_FILES['userfile']['tmp_name'] = $_FILES[$field_name]['tmp_name'][$i];
                        $_FILES['userfile']['error'] = $_FILES[$field_name]['error'][$i];
                        $_FILES['userfile']['size'] = $_FILES[$field_name]['size'][$i];

                        $config=array();
                        $config['upload_path'] = $folder_name;
                        $config['allowed_types'] = 'gif|jpeg|jpg|png';
                        $config['file_name'] = RandomNameFileEncode();

                        $this->upload->initialize($config);
                        if(!$this->upload->do_upload('userfile')){
                            $error[] = $this->upload->display_errors("<span class='error'>", "</span>");
                        }
                        $data_upload = $this->upload->data();

                        $arr=@explode('.',$_FILES['userfile']['name']);
                        $filename="file";
                        if(count($arr)>0){
                            unset($arr[count($arr)-1]);
                            $filename=implode($arr);
                        }
                        $name_array[] = array('name'=>$filename,'file'=>$data_upload['file_name']);
                    }
                    //$field_name=serialize($name_array);
                    return $name_array;
                }
            }
            return '';
        }

        public function do_upload($field_name='',$folder_name='',$ext='gif|jpeg|jpg|png|tiff|doc|docx|txt|odt|xls|xlsx|pdf|ppt|pptx|pps|ppsx|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|flv|avi|mpg|ogv|3gp|3g2|zip|csv'){
            if(isset($_FILES[$field_name]['name'])){
                $this->load->library('upload');
                if($_FILES[$field_name]['name']!=''){
                    $config=array();
                    $config['upload_path']      = $folder_name;
                    $config['allowed_types']    = $ext;
                    $config['file_name']        = RandomNameFileEncode();

                    $this->upload->initialize($config);
                    if($this->upload->do_upload($field_name))
                    {
                        $data_upload = $this->upload->data();
                        $field_name=$data_upload['file_name'];
                        return $field_name;
                    }
                }
            }
            return '';
        }

        public function do_multi_upload($field_name='',$folder_name='',$ext='gif|jpeg|jpg|png|tiff|doc|docx|txt|odt|xls|xlsx|pdf|ppt|pptx|pps|ppsx|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|flv|avi|mpg|ogv|3gp|3g2|zip|csv'){
            if($_FILES[$field_name]['name'][0]!='')
            {
                $error=array();
                $name_array=array();

                $count = count($_FILES[$field_name]['size']);

                if($count>0){
                    $this->load->library('upload');
                    for($i=0; $i<$count; $i++){
                        $_FILES['userfile']['name']     = $_FILES[$field_name]['name'][$i];
                        $_FILES['userfile']['type']     = $_FILES[$field_name]['type'][$i];
                        $_FILES['userfile']['tmp_name'] = $_FILES[$field_name]['tmp_name'][$i];
                        $_FILES['userfile']['error']    = $_FILES[$field_name]['error'][$i];
                        $_FILES['userfile']['size']     = $_FILES[$field_name]['size'][$i];

                        $config=array();
                        $config['upload_path'] = $folder_name;
                        $config['allowed_types'] = $ext;
                        $config['file_name'] = RandomNameFileEncode();

                        $this->upload->initialize($config);
                        if(!$this->upload->do_upload('userfile')){
                            $error[] = $this->upload->display_errors("<span class='error'>", "</span>");
                        }
                        $data_upload = $this->upload->data();

                        $arr=@explode('.',$_FILES['userfile']['name']);
                        $filename="file";
                        if(count($arr)>0){
                            unset($arr[count($arr)-1]);
                            $filename=implode($arr);
                        }
                        $name_array[] = array('name'=>$filename,'file'=>$data_upload['file_name']);
                    }
                    //$field_name=serialize($name_array);
                    return $name_array;
                }
            }
            return '';
        }
}
?>
