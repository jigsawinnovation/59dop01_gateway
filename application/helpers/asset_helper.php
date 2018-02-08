<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
  * General Asset Helper
  *
  * Helps generate links to asset files of any sort. Asset type should be the
  * name of the folder they are stored in.
  *
  * @access   public
  * @param    string    the name of the file or asset
  * @param    string    the asset type (name of folder)
  * @param    string    optional, module name
  * @return   string    full url to asset
*/
/*
function other_asset_url($asset_name, $module_name = NULL, $asset_type = NULL)
{
  $obj =& get_instance();
  $base_url = $obj->config->item('base_url');

  $asset_location = $base_url.'assets/';

  if(!empty($module_name)):
    //$asset_location .= 'modules/'.$module_name.'/';
    $asset_location = apppath(APPPATH).'modules/'.$module_name.'/'.'assets/';
  endif;

  $asset_location .= $asset_type.'/'.$asset_name;

  return $asset_location;

}
*/

function other_asset_url($asset_name, $module_name = NULL, $asset_type = NULL)
{
  $obj =& get_instance();
  $base_url = $obj->config->item('base_url');

  $asset_location = $base_url.'assets/';

  if(!empty($module_name)):
    $asset_location .= 'modules/'.$module_name.'/';
  endif;

  $asset_location .= $asset_type.'/'.$asset_name;

  return $asset_location;

}

/*
  * Parse HTML Attributes
  *
  * Turns an array of attributes into a string
  *
  * @access   public
  * @param    array   attributes to be parsed
  * @return   string    string of html attributes
*/
function _parse_asset_html($attributes = NULL)
{

  if(is_array($attributes)):
    $attribute_str = '';

    foreach($attributes as $key => $value):
      $attribute_str .= ' '.$key.'="'.$value.'"';
    endforeach;

    return $attribute_str;
  endif;

  return '';
}
/*
  * CSS Asset Helper
  *
  * Helps generate CSS asset locations.
  *
  * @access   public
  * @param    string    the name of the file or asset
  * @param    string    optional, module name
  * @return   string    full url to css asset
*/
function css_asset_url($asset_name, $module_name = NULL)
{
  return other_asset_url($asset_name, $module_name, 'css');
}
/*
  * CSS Asset HTML Helper
  *
  * Helps generate JavaScript asset locations.
  *
  * @access   public
  * @param    string    the name of the file or asset
  * @param    string    optional, module name
  * @param    string    optional, extra attributes
  * @return   string    HTML code for JavaScript asset
*/
function css_asset($asset_name, $module_name = NULL, $attributes = array())
{
  $attribute_str = _parse_asset_html($attributes);

  return '<link href="'.css_asset_url($asset_name, $module_name).'" rel="stylesheet" type="text/css"'.$attribute_str.' />';
}
/*
  * Image Asset Helper
  *
  * Helps generate CSS asset locations.
  *
  * @access   public
  * @param    string    the name of the file or asset
  * @param    string    optional, module name
  * @return   string    full url to image asset
*/
function image_asset_url($asset_name, $module_name = NULL)
{
  return other_asset_url($asset_name, $module_name, 'images');
}
function asset_url_uploads($asset_name, $module_name = NULL)
{
  return other_asset_url($asset_name, $module_name, 'uploads');
}
function asset_url_images($asset_name, $module_name = NULL)
{
  return other_asset_url($asset_name, $module_name, 'images');
}
/*
  * Image Asset HTML Helper
  *
  * Helps generate image HTML.
  *
  * @access   public
  * @param    string    the name of the file or asset
  * @param    string    optional, module name
  * @param    string    optional, extra attributes
  * @return   string    HTML code for image asset
*/


function image_asset($asset_name, $module_name = '', $attributes = array()) //use choice 1
{
  $attribute_str = _parse_asset_html($attributes);

  return '<img src="'.image_asset_url($asset_name, $module_name).'"'.$attribute_str.' />';
}
function image($asset_name, $module_name = '', $attributes = array()){ //use choice 2
  return image_asset($asset_name, $module_name, $attributes);
}


function image_asset_uploads($asset_name, $module_name = '', $attributes = array()) //use choice 1
{
  $attribute_str = _parse_asset_html($attributes);

  return '<img src="'.asset_url_uploads($asset_name, $module_name).'"'.$attribute_str.' />';
}
function image_uploads($asset_name, $module_name = '', $attributes = array()) //use choice 2
{
  return image_asset_uploads($asset_name, $module_name, $attributes);
}


function path_asset_uploads($asset_name, $module_name = '') //use choice 1
{
  return asset_url_uploads($asset_name, $module_name);
}
function path_uploads($asset_name, $module_name = '') //use choice 2
{
  return path_asset_uploads($asset_name, $module_name);
}
function path($asset_name, $module_name = '') //use choice 3
{
  return asset_url_images($asset_name, $module_name);
}
/*
  * JavaScript Asset URL Helper
  *
  * Helps generate JavaScript asset locations.
  *
  * @access   public
  * @param    string    the name of the file or asset
  * @param    string    optional, module name
  * @return   string    full url to JavaScript asset
*/
function js_asset_url($asset_name, $module_name = NULL, $user = NULL)
{
  if($user == NULL)
    return other_asset_url($asset_name, $module_name, 'js');
  else
    return other_asset_url($asset_name, $module_name, $user);
}
/*
  * JavaScript Asset HTML Helper
  *
  * Helps generate JavaScript asset locations.
  *
  * @access   public
  * @param    string    the name of the file or asset
  * @param    string    optional, module name
  * @return   string    HTML code for JavaScript asset
*/
function js_asset($asset_name, $module_name = NULL, $user = NULL)
{
  if($user == NULL){
    return '<script type="text/javascript" src="'.js_asset_url($asset_name, $module_name).'"></script>';
  }
  else
    return '<script type="text/javascript" src="'.js_asset_url($asset_name, $module_name, $user).'"></script>';
}

class my_asset{
  public $ci;
  public function __construct(){
    $this->ci=&get_instance();
  }
  public function get_ci(){
    return $this->ci;
  }
}

function set_js_asset_head($js_asset, $module_name = NULL){
  $obj=new my_asset();

  if(!is_array($js_asset)){
    if($module_name == NULL)
      $obj->get_ci()->template->js_assets_head[]=js_asset($js_asset);
    else
      $obj->get_ci()->template->js_assets_head[]=js_asset($js_asset,$module_name);
  }else{
    foreach($js_asset as $key=>$data){
      if($module_name == NULL)
        $obj->get_ci()->template->js_assets_head[]=js_asset($data);
      else
        $obj->get_ci()->template->js_assets_head[]=js_asset($data,$module_name);   
    }
  }
}

function set_js_asset_footer($js_asset, $module_name = NULL){
  $obj=new my_asset();

  if(!is_array($js_asset)){
    if($module_name == NULL)
      $obj->get_ci()->template->js_assets_footer[]=js_asset($js_asset);
    else
      $obj->get_ci()->template->js_assets_footer[]=js_asset($js_asset,$module_name);
  }else{
      foreach($js_asset as $key=>$data){  
        if($module_name == NULL)
          $obj->get_ci()->template->js_assets_footer[]=js_asset($data);
        else
          $obj->get_ci()->template->js_assets_footer[]=js_asset($data,$module_name);      
      }  
  }
}

function set_css_asset_head($css_asset, $module_name = NULL){
  $obj=new my_asset();

  if(!is_array($css_asset)){
    if($module_name == NULL)
      $obj->get_ci()->template->css_assets_head[]=css_asset($css_asset);
    else
      $obj->get_ci()->template->css_assets_head[]=css_asset($css_asset,$module_name);
  }else{
    foreach($css_asset as $key=>$data){  
      if($module_name == NULL)
        $obj->get_ci()->template->css_assets_head[]=css_asset($data);
      else
        $obj->get_ci()->template->css_assets_head[]=css_asset($data,$module_name);
    }
  }
}

function set_css_asset_footer($css_asset, $module_name = NULL){
  $obj=new my_asset();

  if(!is_array($css_asset)){
    if($module_name == NULL)
      $obj->get_ci()->template->css_assets_footer[]=css_asset($css_asset);
    else
      $obj->get_ci()->template->css_assets_footer[]=css_asset($css_asset,$module_name);
  }else{
    foreach($css_asset as $key=>$data){
      if($module_name == NULL)
        $obj->get_ci()->template->css_assets_footer[]=css_asset($data);
      else
        $obj->get_ci()->template->css_assets_footer[]=css_asset($data,$module_name);
    }
  }
}

function set_js_link_head($src='') {
  $obj=new my_asset();
  $obj->get_ci()->template->js_assets_head[]='<script type="text/javascript" src="'.$src.'"></script>';
}
function set_js_link_footer($src='') {
  $obj=new my_asset();
  $obj->get_ci()->template->js_assets_footer[]='<script type="text/javascript" src="'.$src.'"></script>';
}

function set_css_link_head($src='') {
  $obj=new my_asset();
  $obj->get_ci()->template->css_assets_head[]='<link href="'.$src.'" rel="stylesheet" type="text/css" />';
}
function set_css_link_footer($src='') {
  $obj=new my_asset();
  $obj->get_ci()->template->js_assets_footer[]='<link href="'.$src.'" rel="stylesheet" type="text/css" />';
}

?>