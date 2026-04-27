<?php
  
/*
------------------------------------------------
Отдача видео на просмотр/скачивание

AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/

require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

$id = intval(get('id'));

$video = db::get_string("SELECT `ID`,`NAME`,`EXT`,`USER_ID`,`SHOW`,`ID_DIR` FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$id]);
$dir = db::get_string("SELECT `ID`,`PRIVATE`,`USER_ID`,`PASSWORD` FROM `VIDEOS_DIR` WHERE `ID` = ? LIMIT 1", [$video['ID_DIR']]);

if (get('type') == 'no_screen'){
  
  file::download(ROOT.'/files/upload/videos/screen/no_video.jpg', HTTP_HOST.'_no_video.jpg', file::mime('jpg'));   
  exit;
  
}

if (get('type') == 'screen'){
  
  if (is_file(ROOT.'/files/upload/videos/screen/240x240/'.$video['ID'].'.jpg')){
    
    file::download(ROOT.'/files/upload/videos/screen/240x240/'.$video['ID'].'.jpg', HTTP_HOST.'_'.$video['ID'].'.jpg', file::mime('jpg'));
    
  }else{
    
    file::download(ROOT.'/files/upload/videos/screen/no_video.jpg', HTTP_HOST.'_no_video.jpg', file::mime('jpg')); 
    
  }
  
  exit;
  
}

//Если данные не опознаны
if (!is_file(ROOT.'/files/upload/videos/source/'.$video['ID'].'.'.$video['EXT']) || !isset($video['ID'])){
  
  file::download(ROOT.'/files/upload/photos/240x240/no_photo.jpg', HTTP_HOST.'_no_photo.jpg', file::mime('jpg')); 
  exit;

}

if (intval($dir['ID']) > 0){
  
  if (MANAGEMENT == 1){
    
    if (access('videos_private_show', null, 1) == false){
      
      if ($video['SHOW'] == 0){
        
        //Если видео в закрытом альбоме
        if ($dir['PRIVATE'] == 3 && $dir['USER_ID'] != user('ID')){
          
          file::download(ROOT.'/files/upload/photos/240x240/no_photo.jpg', HTTP_HOST.'_no_photo.jpg', file::mime('jpg'));
          exit;
        
        }
        
        //Если видео только для владельца альбома
        if ($dir['PRIVATE'] == 2 && $dir['USER_ID'] != user('ID')){
          
          file::download(ROOT.'/files/upload/photos/240x240/no_photo.jpg', HTTP_HOST.'_no_photo.jpg', file::mime('jpg'));
          exit;
        
        }
        
        //Если видео только по паролю
        if ($dir['PRIVATE'] == 4 && str($dir['PASSWORD']) > 0 && $dir['USER_ID'] != user('ID') && !session('DIR_PASSWORD')){
          
          file::download(ROOT.'/files/upload/photos/240x240/no_photo.jpg', HTTP_HOST.'_no_photo.jpg', file::mime('jpg'));
          exit;
        
        }
        
        //Если видео только друзьям
        if ($dir['PRIVATE'] == 1 && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = ? LIMIT 1", [user('ID'), $video['USER_ID'], 0]) == 0 && $dir['USER_ID'] != user('ID')){
          
          file::download(ROOT.'/files/upload/photos/240x240/no_photo.jpg', HTTP_HOST.'_no_photo.jpg', file::mime('jpg'));
          exit;
        
        }
      
      }
    
    }
    
  }
  
}

if (get('get') == 'show'){
  
  readfile(ROOT.'/files/upload/videos/source/'.$video['ID'].'.'.$video['EXT']);
  
}else{
  
  //Отдаем файл браузеру если всё хорошо
  file::download(ROOT.'/files/upload/videos/source/'.$video['ID'].'.'.$video['EXT'], HTTP_HOST.'_'.$video['NAME'].'.'.$video['EXT'], file::mime($video['EXT']));
  
}

exit;