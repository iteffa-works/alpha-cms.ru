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
  
  //Директория в которую будут загружены файлы
  $uploadDir = ROOT."/files/upload/photos/source/";
  
  //Останавливаем выгрузку, если нет файлов
  if (!isset($_FILES['file']['name'])) { file::error(); }
  
  //Подсчет количества отправляемых файлов
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = explode(",", strtolower(preg_replace('/\s+/', '', config('PHOTOS_EXT'))));
  
  //Создаем анонимную папку "Вложения", если её ещё нет
  require (ROOT.'/modules/photos/plugins/param.php');
  
  //Определяем анонимную папку "Вложения"
  $dir = db::get_string("SELECT `ID` FROM `PHOTOS_DIR` WHERE `USER_ID` = ? AND `PRIVATE` = '3' AND `NAME` = 'Вложения' AND `ID_DIR` = '0' LIMIT 1", [user('ID')]);
  
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
  
  if (db::get_column("SELECT COUNT(*) FROM `PHOTOS` WHERE `USER_ID` = ?", [user('ID')]) >= config('PHOTOS_LIMIT')) {
    
    file::error('Вы исчерпали лимит на добавление фото');
    
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
    
    //Определение ширины и высоты изображения
    $xy = getimagesize($TempName);  
    $width = $xy[0]; 
    $height = $xy[1];

    if (db::get_column("SELECT COUNT(*) FROM `PHOTOS` WHERE `USER_ID` = ? AND `NAME` = ? LIMIT 1", [user('ID'), $FileName]) > 0) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('изображение с таким названием уже есть в ваших альбомах'));
    
    }elseif ($xy == false) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('это не изображение'));
    
    }elseif ($width < 160) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('ширина изображения не может быть меньше 160px. Текущая ширина: %dpx', $width));
    
    }elseif ($height < 160) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('высота изображения не может быть меньше 160px. Текущая высота: %dpx', $height));
    
    }elseif (!in_array($Ext, $AllowFileExt)) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('неверный формат изображения. Допустимые форматы: %s', strtolower(preg_replace('/\s+/', '', config('PHOTOS_EXT')))));
    
    }elseif (str($type) == 0) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('неизвестная ошибка'));
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE'))));
    
    }elseif (str($FileName) < 1) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('имя не должно быть менее 1 символа'));
    
    }elseif (str($FileName) > 200) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('имя не должно быть более 200 символов'));
    
    }elseif ($FileSize < 1024) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('слишком маленькое изображение'));
    
    }else{
      
      //Сохраняем файл
      if (@copy($TempName, $uploadDir.$FileName.'.'.$Ext)) {
        
        $shif = md5(user('ID').rand(111111,999999).TM);
        
        $ID = db::get_add("INSERT INTO `PHOTOS` (`NAME`, `USER_ID`, `EXT`, `SIZE`, `TIME`, `ID_DIR`, `SHOW`, `SHIF`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [$FileName, user('ID'), $Ext, filesize($TempName), TM, $dir['ID'], intval(get('show')), $shif]);
        
        rename($uploadDir.$FileName.'.'.$Ext, $uploadDir.$shif.'.'.$Ext);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/50x50/'.$shif.'.jpg', 50, 50);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/150x150/'.$shif.'.jpg', 150, 150);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/240x240/'.$shif.'.jpg', 240, 240);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/260x600/'.$shif.'.jpg', 640, 260);
        fd_upload($uploadDir.$shif.'.'.$Ext, 'photos');
        
        if ($id == 0) {
          
          db::get_add("INSERT INTO `ATTACHMENTS` (`USER_ID`, `OBJECT_ID`, `TYPE`, `TYPE_POST`, `TIME`, `ID_POST`, `ACT`) VALUES (?, ?, ?, ?, ?, ?, ?)", [user('ID'), $ID, 'photos', $type, TM, 0, 0]);
          
        }else{
          
          db::get_add("INSERT INTO `ATTACHMENTS` (`USER_ID`, `OBJECT_ID`, `TYPE`, `TYPE_POST`, `TIME`, `ID_POST`, `ACT`) VALUES (?, ?, ?, ?, ?, ?, ?)", [user('ID'), $ID, 'photos', $type, TM, $id, 1]);
          
        }
        
        balls_add('PHOTOS');
        rating_add('PHOTOS');
        
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