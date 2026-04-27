<?php
  
/*
------------------------------------------------
Отдача музыки на просмотр/скачивание

AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/

require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

$id = intval(get('id'));

$file = db::get_string("SELECT `ID`,`NAME`,`EXT`,`USER_ID`,`SHOW`,`ID_DIR` FROM `FILES` WHERE `ID` = ? LIMIT 1", [$id]);
$dir = db::get_string("SELECT `ID`,`PRIVATE`,`USER_ID`,`PASSWORD` FROM `FILES_DIR` WHERE `ID` = ? LIMIT 1", [$file['ID_DIR']]);

//Если данные не опознаны
if (!is_file(ROOT.'/files/upload/files/source/'.$file['ID'].'.'.$file['EXT']) || !isset($file['ID'])){
  
  exit;

}

if (intval($dir['ID']) > 0){
  
  if (MANAGEMENT == 1){
    
    if (access('files_private_show', null, 1) == false){
      
      if ($file['SHOW'] == 0){
        
        //Если музыка в закрытом альбоме
        if ($dir['PRIVATE'] == 3 && $dir['USER_ID'] != user('ID')){

          exit;
        
        }
        
        //Если музыка только для владельца альбома
        if ($dir['PRIVATE'] == 2 && $dir['USER_ID'] != user('ID')){

          exit;
        
        }
        
        //Если музыка только по паролю
        if ($dir['PRIVATE'] == 4 && str($dir['PASSWORD']) > 0 && $dir['USER_ID'] != user('ID') && !session('DIR_PASSWORD')){

          exit;
        
        }
        
        //Если музыка только друзьям
        if ($dir['PRIVATE'] == 1 && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = ? LIMIT 1", [user('ID'), $file['USER_ID'], 0]) == 0 && $dir['USER_ID'] != user('ID')){

          exit;
        
        }
      
      }
    
    }
    
  }
  
}

if (get('get') == 'show'){
  
  readfile(ROOT.'/files/upload/files/source/'.$file['ID'].'.'.$file['EXT']);
  
}else{
  
  //Отдаем файл браузеру если всё хорошо
  file::download(ROOT.'/files/upload/files/source/'.$file['ID'].'.'.$file['EXT'], HTTP_HOST.'_'.$file['NAME'].'.'.$file['EXT'], file::mime($file['EXT']));
  
}

exit;