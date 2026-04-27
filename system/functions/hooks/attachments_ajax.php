<?php
  
function attachments_ajax() {
  
  global $type,$id,$url;
  
  define('AT_TYPE', $type);
  define('AT_ID', $id);
  define('AT_URL', $url);
  
  direct::components(ROOT.'/system/AJAX/php/attachments/components/', 0);
  
}