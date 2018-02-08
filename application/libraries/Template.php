<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Template {

    protected $CI;

    public $name;
    public $setting;

    public $data_output;

    public $content_view_set=0; //0:content view set main template 1:content view set module

    public $js_assets_head;
    public $js_assets_footer;
    public $css_assets_head;
    public $css_assets_footer;


    // public $labelDB = array(); //modified for this work

    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    public function __construct($params=array())
    {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();

        $this->name=$params['name'];
        $this->setting=$params['setting'];
        $this->data_output=$params['setting']['data_output'];

// **************************************************************** modified for this work *************************
/* 
       $rows =  $this->CI->admin_model->getAll_permissions();
        foreach($rows as $key=>$data) {
            $temp = explode('/',$data['P_Process']);
            if(count($temp)>0) {
                $this->labelDB[$temp[count($temp)-1]] = unserialize($data['P_Name']); 
                $this->labelDB[$temp[count($temp)-1]]['P_Process'] = $data['P_Process'];
                $this->labelDB[$temp[count($temp)-1]]['P_FontAweIcon'] = $data['P_FontAweIcon'];
                $this->labelDB[$temp[count($temp)-1]]['P_ID'] = $data['P_ID'];
            }
        }
*/
// **************************************************************** modified for this work *************************        
        
    }

    /*
    public function head(){
    	$this->CI->parser->parse($this->name.'/'.$this->index_page, array('test'=>'Description test','test1'=>'Description test1')); 
    }

    public function load($name='',$data=array()){
       	if($name=='')
        	die('file does not exist!');
 	
 		//$this->head();

        //dieFont($this->name.'/'.$name);

		$this->CI->parser->parse($this->name.'/'.$name, $data);   	
    }
        
    public function once_view($name='',$data=array()){
        if($name=='') //modifield additional
        	die('file does not exist!');

   		$this->load->library('parser');  
		$this->parser->parse($this->name.'/'.$name, $data);   	
    }
    */
    public function load($view,$data=NULL,$module_name = NULL){
        $content_view_path='';
        if(!is_null($view)) 
        {
            if(file_exists(APPPATH.'views/'.$this->name.'/'.$view)) 
            {
                $content_view_path = $this->name.'/'.$view;
            }
            else if(file_exists(APPPATH.'views/'.$this->name.'/'.$view.'.php')) 
            {
                $content_view_path = $this->name.'/'.$view.'.php';
            }
            else if(file_exists(APPPATH.'views/'.$view)) 
            {
                $content_view_path = $view;
            }
            else if(file_exists(APPPATH.'views/'.$view.'.php')) 
            {
                $content_view_path = $view.'.php';
            }
            else
            {
                //show_error('Unable to load the requested file: ' . $tpl_name.'/'.$view_name.'.php');
                dieFont('Unable to load the requested file: ' .$view.'.php');
            }
        }else{
             dieFont('Unable to load the requested file: ' .$view.'.php');
        }

        if($module_name!=NULL){
            $this->content_view_set=1;
        }

        $this->CI->load->view($content_view_path, $data);   
    }


}

?>