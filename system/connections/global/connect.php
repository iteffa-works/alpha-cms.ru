<?php

/*
---------------------------------------------
Подгрузка функций из папки /system/functions/
---------------------------------------------
*/
  
$dir_func_data = opendir(ROOT.'/system/functions');

while ($dir_func = readdir($dir_func_data)){
  
  if (preg_match('#\.php$#i',$dir_func)){
    
    require_once (ROOT.'/system/functions/'.$dir_func);
  
  }

}

/*
------------------------
Автозагрузка PHP классов
------------------------
*/

spl_autoload_register(function($class_name) {
  
  if (is_file(ROOT.'/system/PHP-classes/'.$class_name.'.class.php')){
    
    require_once (ROOT.'/system/PHP-classes/'.$class_name.'.class.php');
    
  }

});

/*
--------------------
Текущая версия сайта
--------------------
*/

require_once (ROOT.'/system/connections/version.php');

/*
-----------------------------------
Текущий язык сайта для пользователя
-----------------------------------
*/

require_once (ROOT.'/system/connections/language.php');
require_once (ROOT.'/system/languages/lg.php');

/*
------------------------------
Подключение прочих компонентов
------------------------------
*/

direct::components(ROOT.'/system/connections/global/connect/', 0);

/*
------------
Токены к url
------------
*/

require_once (ROOT.'/system/connections/token_url.php');