<?php
  
/*
--------------
Доступ к сайту
--------------
*/
  
//Доступ только для пользователей
if (config('ACCESS') == 0 && user('ID') == 0){
  
  if (url_request_validate('/m/info') == false && url_request_validate('/registration') == false && url_request_validate('/login') == false && url_request_validate('/shopping') == false && url_request_validate('/password') == false && url_request_validate('/services') == false && url_request_validate('/?get=cookie') == false && url_request_validate('/m/rules') == false && url_request_validate('/m/ads') == false && url_request_validate('/m/reklama') == false && url_request_validate('/m/feedback') == false && url_request_validate('/system/AJAX/php/version.php') == false && url_request_validate('/system/AJAX/php/languages.php') == false && url_request_validate('/languages') == false && url_request_validate('/version') == false && url_request_validate('/system/AJAX/php/ulogin.php') == false && url_request_validate('/m/ulogin') == false && url_request_validate('/?ref=') == false){
    
    redirect('/login/');
    
  }
  
}