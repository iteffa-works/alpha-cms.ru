<?php
  
/*
------------------------------------------------
Отдача изображений на просмотр/скачивание

AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/

require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

$id = intval(get('id'));
$size = intval(get('size'));

$photo = db::get_string("SELECT `ID`,`NAME`,`EXT`,`USER_ID`,`SHOW`,`ID_DIR` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$id]);

if ($size == 0){
  
  $size = 'source';
  $ext = $photo['EXT'];
  
}elseif ($size == 50){
  
  $size = '50x50';
  $ext = 'jpg';
  
}elseif ($size == 150){
  
  $size = '150x150';
  $ext = 'jpg';
  
}elseif ($size == 240){
  
  $size = '240x240';
  $ext = 'jpg';
  
}elseif ($size == 260){
  
  $size = '260x600';
  $ext = 'jpg';
  
}else{
  
  $size = '240x240';
  $ext = 'jpg';
  
}

//Если данные не опознаны
if (!is_file(ROOT.'/files/upload/photos/'.$size.'/'.$id.'.'.$ext) || !isset($photo['ID'])){
  
  file::download(ROOT.'/files/upload/photos/'.$size.'/no_photo.jpg', HTTP_HOST.'_no_photo.jpg', file::mime('jpg'));  
  exit;

}

//Отдаем файл браузеру если всё хорошо
file::download(ROOT.'/files/upload/photos/'.$size.'/'.$id.'.'.$ext, HTTP_HOST.'_'.$photo['NAME'].'.'.$ext, file::mime($ext));
exit;