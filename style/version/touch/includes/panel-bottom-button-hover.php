<?php
  
if (url_request_validate('/login/') == true){
  
  define('STYLE_NAME', 'aut');
  
}elseif (url_request_validate('/password/') == true){
  
  define('STYLE_NAME', 'password');
  
}elseif (url_request_validate('/account/mail/') == true){
  
  define('STYLE_NAME', 'mail');
  
}elseif (url_request_validate('/account/cabinet/') == true){
  
  define('STYLE_NAME', 'cabinet');
  
}elseif (url_request_validate('/account/journal/') == true){
  
  define('STYLE_NAME', 'journal');
  
}elseif (url_request_validate('/account/tape/') == true){
  
  define('STYLE_NAME', 'tape');
  
}elseif (url_request_validate('/id') == true && url_request_validate('public/id') == false){
  
  define('STYLE_NAME', 'account');
  
}else{
  
  define('STYLE_NAME', 'none');
  
}
  
?>

<style>
#<?=STYLE_NAME?>{
  
  color: #53a5e0;

}
</style>