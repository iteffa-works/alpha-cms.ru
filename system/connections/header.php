<?php
  
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
  
if (!is_panel()){
  
  //Подключаем шапку в зависимости от выбранной версии сайта
  require (ROOT.'/style/version/'.version('DIR').'/includes/header.php');
  
  //Подключаем системные оповещения
  require (ROOT.'/system/connections/inform.php');
  
  //Подгрузка плагинов
  direct::components(ROOT.'/system/connections/cheader/', 0);
  
}elseif (is_panel()) {
  
  access('administration_show');
  
  //Подключаем шапку в зависимости от выбранной версии панели управления
  require (ROOT.'/panel/style/'.VERSION.'/includes/header.php');
  
  //Подключаем системные оповещения
  require (ROOT.'/system/connections/inform.php');
  
}

//Бан пользователю
require (ROOT.'/system/connections/block_user.php');

/*
-------------------------------
Подгрузка окна с версиями сайта
-------------------------------
*/

?>
<div class='modal_phone_center modal_center_close' id='version2' onclick="modal_center('version', 'close')"></div>
<div id='version' class='modal_center modal_center_open'>  
<div id='ver_upload'></div>
</div>
<?

/*
------------------------
Подгрузка окна с языками
------------------------
*/
  
?>
<div class='modal_phone_center modal_center_close' id='languages2' onclick="modal_center('languages', 'close')"></div>
<div id='languages' class='modal_center modal_center_open'>  
<div id='lang_upload'></div>
</div>
<?