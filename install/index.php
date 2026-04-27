<?php
  
/*
------------------------------------------------
AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/

require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/global/config.php');

/*
----------------------
Уведомления об ошибках
----------------------
*/

function _error($text) {
  
  return "<br /><div class='error'>".$text."</div>";
  
}

/*
---------------------------------------------
Подгрузка функций из папки /system/functions/
---------------------------------------------
*/
  
$dir_func_data = opendir(ROOT.'/system/functions');

while ($dir_func = readdir($dir_func_data)){
  
  if (preg_match('#\.php$#i',$dir_func)){
    
    require (ROOT.'/system/functions/'.$dir_func);
  
  }

}

/*
------------------------
Автозагрузка PHP классов
------------------------
*/

spl_autoload_register(function($class_name) {
  
  if (is_file(ROOT.'/system/PHP-classes/'.$class_name.'.class.php')){
    
    require (ROOT.'/system/PHP-classes/'.$class_name.'.class.php');
    
  }

});

/*
--------------------
Текущая версия сайта
--------------------
*/

require (ROOT.'/system/connections/version.php');

/*
-----------------------------------
Текущий язык сайта для пользователя
-----------------------------------
*/

require (ROOT.'/system/connections/language.php');
require (ROOT.'/system/languages/lg.php');

require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/global/users.php');

html::title('Установка программы');
require_once (ROOT.'/install/plugins/header.php');

list ($php_ver1, $php_ver2, $php_ver3) = explode('.', strtok(strtok(phpversion(),'-'),' '), 3);

$php_p = $php_ver1.".".$php_ver2;

if ($php_p == '7.3' || $php_p == '7.2'){
  
  $get = intval(get('get')).'.php';
  
  if (is_file(ROOT.'/install/plugins/step_'.$get)){
    
    require_once (ROOT.'/install/plugins/step_'.$get);
  
  }else{
    
    require_once (ROOT.'/install/plugins/step_1.php');
  
  }
  
}else{
  
  ?>
  <div class='list'>
  <center><b><font size='+1'><?=lg('Для начала установки требуется версия PHP 7.2 или 7.3')?></font></b></center><br />
  <font color='red'><?=lg('Текущая версия')?>: <?=$php_p?></font><br /><br />
  <?=lg('Необходимо в настройках на стороне хоста сменить версию PHP на 7.2')?><br /><br />
  </div>
  <?
  
}

require_once (ROOT.'/install/plugins/footer.php');