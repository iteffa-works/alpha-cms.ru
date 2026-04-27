<?php
  
/*
------------------------------------------------
AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/
  
require_once ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (isset($_FILES) && ajax() == true) {
  
  //Подключаем библиотеку getID3
  require ROOT.'/system/libs/getid3/getid3.php';
  $getID3 = new getID3();
  
  //Директория в которую будут загружены файлы
  $uploadDir = ROOT."/files/upload/music/source/";
  
  //Останавливаем выгрузку, если нет файлов
  if (!isset($_FILES['file']['name'])) { file::error(); }
  
  //Подсчет количества отправляемых файлов
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = explode(",", strtolower(preg_replace('/\s+/', '', config('MUSIC_EXT'))));
  
  //Создаем анонимную папку "Вложения", если её ещё нет
  require (ROOT.'/modules/music/plugins/param.php');
  
  //Определяем анонимную папку "Вложения"
  $dir = db::get_string("SELECT `ID` FROM `MUSIC_DIR` WHERE `USER_ID` = ? AND `PRIVATE` = '3' AND `NAME` = 'Вложения' AND `ID_DIR` = '0' LIMIT 1", [user('ID')]);
  
  //Определяем тип
  $type = tabs(esc(get('type')));
  $id = intval(get('id'));
  
  //Определяем url откуда пришли
  $url = url_check_validate(base64_decode(get('url')));
  
  if ($fileCount > config('MAXFILEUPLOAD')){
    
    file::error(lg('Нельзя загружать более %d файлов за 1 раз', config('MAXFILEUPLOAD')));
    
  }
  
  if (config('FILE_ACCESS') == 0) {
    
    file::error('Выгрузка файлов на сайте приостановлена администрацией');
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `MUSIC` WHERE `USER_ID` = ?", [user('ID')]) >= config('MUSIC_LIMIT')) {
    
    file::error('Вы исчерпали лимит на добавление музыки');
    
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ACT` = ? AND `TYPE_POST` = ? LIMIT 20", [user('ID'), 0, $type]) + $fileCount > 20){
    
    file::error('Нельзя прикреплять более 20 файлов к 1 сообщению');
    
  }
  
  /*
  ---------------------
  Мультивыгрузка файлов
  ---------------------
  */
  
  $error = null; 
  $s = 0;
  for ($i = 0; $i < $fileCount; $i++) {
    
    //Размер файла
    $FileSize = tabs($_FILES['file']['size'][$i]);
    
    //Оригинальное название файла
    $FileNameExt = $_FILES['file']['name'][$i];
    
    //Оригинальное название файла без расширения
    $FileName = tprcs(preg_replace('#\.[^\.]*$#', null, $FileNameExt));
    
    //Расширение файла без названия
    $Ext = strtolower(preg_replace('#^.*\.#', null, $FileNameExt));
    
    //Временные файлы
    $TempName = $_FILES['file']['tmp_name'][$i];
    
    if (db::get_column("SELECT COUNT(*) FROM `MUSIC` WHERE `USER_ID` = ? AND `FACT_NAME` = ? LIMIT 1", [user('ID'), $FileName]) > 0){
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('музыка с таким названием уже есть в ваших альбомах'));
    
    }elseif (!in_array($Ext, $AllowFileExt)) {

      file::error('<b>'.$FileNameExt.'</b> - '.lg('неверный формат музыки. Допустимые форматы: %s', strtolower(preg_replace('/\s+/', '', config('MUSIC_EXT')))));
    
    }elseif (str($type) == 0) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('неизвестная ошибка'));
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE'))));
    
    }elseif (str($FileName) < 1) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('имя не должно быть менее 1 символа'));
    
    }elseif (str($FileName) > 200) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('имя не должно быть более 200 символов'));
    
    }elseif ($FileSize < 1024) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('слишком маленький файл'));
    
    }else{
      
      //Сохраняем файл
      if (@copy($TempName, $uploadDir.$FileName.'.'.$Ext)) {
        
        $getID3_file = $getID3->analyze($uploadDir.$FileName.'.'.$Ext);
        
        $id3_name = 'Неизвестная композиция';
        $id3_artist = 'Неизвестный';
        $id3_genre = 'Неизвестный жанр';
        $id3_album = 'Без альбома';
        
        if (isset($getID3_file['tags']['id3v2']['title'][0])){
          
          $id3_name = esc($getID3_file['tags']['id3v2']['title'][0]);
        
        }
        
        if (isset($getID3_file['title'])){
          
          $id3_name = esc($getID3_file['title']); 
        
        }
        
        if (isset($getID3_file['tags']['id3v2']['artist'][0])){
          
          $id3_artist = esc($getID3_file['tags']['id3v2']['artist'][0]); 
        
        }
        
        if (isset($getID3_file['artist'])){
          
          $id3_artist = esc($getID3_file['artist']); 
        
        }
        
        if (isset($getID3_file['tags']['id3v2']['genre'][0])){
          
          $id3_genre = esc($getID3_file['tags']['id3v2']['genre'][0]);
        
        }
        
        if (isset($getID3_file['genre'])){
          
          $id3_genre = esc($getID3_file['genre']); 
        
        }
        
        if (isset($getID3_file['tags']['id3v2']['album'][0])){
          
          $id3_album = esc($getID3_file['tags']['id3v2']['album'][0]); 
        
        }
        
        if (isset($getID3_file['album'])){
          
          $id3_album = esc($getID3_file['album']); 
        
        }
        
        $ID = db::get_add("INSERT INTO `MUSIC` (`ALBUM`, `GENRE`, `ARTIST`, `NAME`, `FACT_NAME`, `USER_ID`, `EXT`, `SIZE`, `TIME`, `ID_DIR`, `SHOW`, `DURATION`, `BITRATE`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$id3_album, $id3_genre, $id3_artist, $id3_name, $FileName, user('ID'), $Ext, filesize($TempName), TM, $dir['ID'], intval(get('show')), esc($getID3_file['playtime_string']), round($getID3_file['bitrate'] / 1000)]);  
        
        if ($id == 0) {
          
          db::get_add("INSERT INTO `ATTACHMENTS` (`USER_ID`, `OBJECT_ID`, `TYPE`, `TYPE_POST`, `TIME`, `ID_POST`, `ACT`) VALUES (?, ?, ?, ?, ?, ?, ?)", [user('ID'), $ID, 'music', $type, TM, 0, 0]);
          
        }else{
          
          db::get_add("INSERT INTO `ATTACHMENTS` (`USER_ID`, `OBJECT_ID`, `TYPE`, `TYPE_POST`, `TIME`, `ID_POST`, `ACT`) VALUES (?, ?, ?, ?, ?, ?, ?)", [user('ID'), $ID, 'music', $type, TM, $id, 1]);
          
        }
        
        rename($uploadDir.$FileName.'.'.$Ext, $uploadDir.$ID.'.'.$Ext); 
        
        if (isset($getID3_file['comments']['picture'][0])){
          
          $Image = 'data:'.$getID3_file['comments']['picture'][0]['image_mime'].';charset=utf-8;base64,'.base64_encode($getID3_file['comments']['picture'][0]['data']);
          copy($Image, ROOT.'/files/upload/music/screen/'.$ID.'.jpg');
          crop_image(ROOT.'/files/upload/music/screen/'.$ID.'.jpg', ROOT.'/files/upload/music/screen/120x120/'.$ID.'.jpg', 120, 120);
          crop_image(ROOT.'/files/upload/music/screen/'.$ID.'.jpg', ROOT.'/files/upload/music/screen/240x240/'.$ID.'.jpg', 240, 240);
          
          balls_add('MUSIC');
          rating_add('MUSIC');
        
        }
        
        $s++;
      
      }else{

        file::error();
        
      }
      
    }
    
  }
  
  /*
  --------------------------------
  Действия после успешной загрузки
  --------------------------------
  */
  
  if ($s > 0) {
    
    file::update($url, '#upload-attachments-result');
    
  }

}else{
  
  file::error('Не удалось установить соединение с ресивером');

}