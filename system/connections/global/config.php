<?php
 
ob_start(); 

/*
-------------
Запуск сессий
-------------
*/ 

@session_name('SID');
@session_start();
$sessID = addslashes(session_id());

if (!preg_match('#[A-z0-9]{32}#i', $sessID)) {
  
  $sessID = md5(mt_rand(000000, 999999));

}

/*
-------------------------------
Константы и псевдофункции для 
сокращения переменных и функций
-------------------------------
*/

require_once ($_SERVER['DOCUMENT_ROOT'].'/system/connections/redefinition.php');

/*
------------------------------------
Подключение файла конфигурации сайта
------------------------------------
*/

$config = @parse_ini_file(ROOT."/system/config/global/settings.ini", false);

/*
---------------------
Ошибки интерпретатора
---------------------
*/

//Включение показа ошибок для всех
if (config('INTERPRETATOR') == 1){

  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  
}else{
  
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
  error_reporting(0);
  
}

/*
-------------------------
Подключение к базе данных
-------------------------
*/

//Хост
define('DB_HOST', config('DB_HOST'));

//Имя базы
define('DB_NAME', config('DB_NAME'));

//Пользователь
define('DB_USER', config('DB_USER'));

//Пароль от базы
define('DB_PASSWORD', config('DB_PASSWORD'));

/*
-----------
Инсталлятор
-----------
*/

if (strlen(session('no_install')) == 0 && is_dir(ROOT.'/install/')){
  
  if (PHP_SELF != '/install/index.php'){
    
    header('location: /install/');
    exit;
    
  }

}

/*
--------------------
Совсем простая капча
--------------------
*/

$captha_length = 5;
$captcha_random_seed = "152639487";

if (isset($_REQUEST['image'])) {
  
 function write_image_number($num_c) {
   
   $number_c = "R0lGODlhCgAMAIABAFNTU////yH5BAEAAAEALAAAAAAKAAwAAAI";
   
   if ($num_c == "0") { $len_c = "63"; $number_c.="WjIFgi6e+QpMP0jin1bfv2nFaBlJaAQA7";}
   if ($num_c == "1") { $len_c = "61"; $number_c.="UjA1wG8noXlJsUnlrXhE/+DXb0RUAOw==";}
   if ($num_c == "2") { $len_c = "64"; $number_c.="XjIFgi6e+QpMPRlbjvFtnfFnchyVJUAAAOw==";}
   if ($num_c == "3") { $len_c = "64"; $number_c.="XjIFgi6e+Qovs0RkTzXbj+3yTJnUlVgAAOw==";}
   if ($num_c == "4") { $len_c = "64"; $number_c.="XjA9wG8mWFIty0amczbVJDVHg9oSlZxQAOw==";}
   if ($num_c == "5") { $len_c = "63"; $number_c.="WTIAJdsuPHovSKGoprhs67mzaJypMAQA7";}
   if ($num_c == "6") { $len_c = "63"; $number_c.="WjIFoB6vxmFw0pfpihI3jOW1at3FRAQA7";}
   if ($num_c == "7") { $len_c = "61"; $number_c.="UDI4Xy6vtAIzTyPpg1ndu9oEdNxUAOw==";}
   if ($num_c == "8") { $len_c = "63"; $number_c.="WjIFgi6e+QpMP2slSpJbn7mFeWDlYAQA7";}
   if ($num_c == "9") { $len_c = "64"; $number_c.="XjIFgi6e+QpMP0jinvbT2FGGPxmlkohUAOw==";}
   
   header("Content-type: image/gif"); 
   header("Content-length: $len_c");
   
   echo base64_decode($number_c); 
 
 }
  
  // Вывод закодированных изображений на экран
  if (array_key_exists('image', $_REQUEST)) { 
    
    $num_c = $_REQUEST['image'];
    
    for ($i = 0; $i < 10; $i++) { 
      
      if (md5($i + $captcha_random_seed) == $num_c) { 
        
        write_image_number($i); 
        exit;
      
      } 
    
    }
  
  }
  
  exit;

}

$captcha_key = '';