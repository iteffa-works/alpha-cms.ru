<?php
  
/*
------------------------------------------------
Загрузка фото в альбомы

AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/
  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (isset($_FILES) && ajax() == true) {
  
  //Директория в которую будут загружены файлы
  $uploadDir = ROOT."/files/upload/photos/source/";
  
  //Подсчет количества отправляемых файлов
  $fileCount = count($_FILES['file']['name']);
  
  //Создаем анонимную папку "Вложения", если её ещё нет
  require_once(ROOT.'/modules/photos/plugins/param.php');
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = explode(",", strtolower(preg_replace('/\s+/', '', config('PHOTOS_EXT'))));
  
  //Определяем папку
  $dir = db::get_string("SELECT `ID`,`PRIVATE` FROM `PHOTOS_DIR` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [user('ID'), intval(get('dir'))]);
  
  if ($fileCount > config('MAXFILEUPLOAD')){
    
    file::error(lg('Нельзя загружать более %d файлов за 1 раз', config('MAXFILEUPLOAD')));
    
  }
  
  if (config('FILE_ACCESS') == 0) {
    
    file::error('Выгрузка файлов на сайте приостановлена администрацией');
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `PHOTOS` WHERE `USER_ID` = ?", [user('ID')]) >= config('PHOTOS_LIMIT')) {
    
    file::error('Вы исчерпали лимит на добавление фото');
    
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

    if (db::get_column("SELECT COUNT(*) FROM `PHOTOS` WHERE `USER_ID` = ? AND `NAME` = ? LIMIT 1", [user('ID'), $FileName]) > 0){
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('изображение с таким названием уже есть в ваших альбомах'));
    
    }elseif ($xy == false) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('это не изображение'));
    
    }elseif ($width < 160) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('ширина изображения не может быть меньше 160px. Текущая ширина: %dpx', $width));
    
    }elseif ($height < 160) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('высота изображения не может быть меньше 160px. Текущая высота: %dpx', $height));
    
    }elseif (!in_array($Ext, $AllowFileExt)) {

      file::error('<b>'.$FileNameExt.'</b> - '.lg('неверный формат изображения. Допустимые форматы: %s', strtolower(preg_replace('/\s+/', '', config('PHOTOS_EXT')))));
    
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
        
        $ID = db::get_add("INSERT INTO `PHOTOS` (`NAME`, `USER_ID`, `EXT`, `SIZE`, `TIME`, `ID_DIR`, `SHOW`, `SHIF`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [$FileName, user('ID'), $Ext, filesize($TempName), TM, intval($dir['ID']), 1, $shif]);        
        
        rename($uploadDir.$FileName.'.'.$Ext, $uploadDir.$shif.'.'.$Ext);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/50x50/'.$shif.'.jpg', 50, 50);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/150x150/'.$shif.'.jpg', 150, 150);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/240x240/'.$shif.'.jpg', 240, 240);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/260x600/'.$shif.'.jpg', 640, 260);        
        fd_upload($uploadDir.$shif.'.'.$Ext, 'photos');
        
        balls_add('PHOTOS');
        rating_add('PHOTOS');
        
        /*
        ------------------------------
        Отправляем подписчикам в ленту
        ------------------------------
        */
        
        if (intval($dir['PRIVATE']) == 0){
          
          $data = db::get_string_all("SELECT `MY_ID`,`USER_ID` FROM `SUBSCRIBERS` WHERE `USER_ID` = ?", [user('ID')]);
          while ($list = $data->fetch()){
            
            db::get_add("INSERT INTO `TAPE` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$list['MY_ID'], $ID, $list['USER_ID'], TM, 'photos']);
          
          } 
        
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
    
    file::update('/m/photos/users/?id='.user('ID').'&dir='.intval($dir['ID']), '#photos_upgrade');
    
  }

}else{
  
  file::error('Не удалось установить соединение с ресивером');

}