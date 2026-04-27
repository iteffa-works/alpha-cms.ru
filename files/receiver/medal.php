<?php
  
/*
------------------------------------------------
AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/
  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('management');

if (isset($_FILES) && ajax() == true) {
  
  //Директория в которую будут загружены файлы
  $uploadDir = ROOT."/files/upload/medal/";

  //Останавливаем выгрузку, если нет файлов
  if (!isset($_FILES['file']['name'])) { file::error(); }
  
  //Подсчет количества отправляемых файлов
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $ExtList = "jpg,jpeg,png,gif,webp";
  
  //Создание массива из форматов
  $AllowFileExt = explode(",", $ExtList);
  
  //Определяем url откуда пришли
  $url = url_check_validate(base64_decode(get('url')));
  
  if ($fileCount > config('MAXFILEUPLOAD')) {
    
    file::error(lg('Нельзя загружать более %d файлов за 1 раз', config('MAXFILEUPLOAD')));
    
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
    $FileNameExt = tabs($_FILES['file']['name'][$i]);
    
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
    
    if ($width > 800) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('ширина изображения не может быть больше 800px. Текущая ширина: %dpx', $width));
    
    }elseif ($height > 800) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('высота изображения не может быть больше 800px. Текущая ширина: %dpx', $height));
    
    }elseif ($xy == false) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('это не изображение'));
    
    }elseif (!in_array($Ext, $AllowFileExt)) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('неверный формат изображения. Допустимые форматы: %s', $ExtList));
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE'))));
    
    }elseif ($FileSize < 1024) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('слишком маленькое изображение'));
    
    }else{
      
      //Сохраняем файл
      if (@copy($TempName, $uploadDir.$FileName.'.'.$Ext)) {
        
        $ID = db::get_add("INSERT INTO `RATING_MEDAL` (`ACT`, `EXT`) VALUES (?, ?)", [0, $Ext]);       
        rename($uploadDir.$FileName.'.'.$Ext, $uploadDir.$ID.'.'.$Ext);
        
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
    
    file::update($url, '#upload-medal');
    
  }

}else{
  
  file::error('Не удалось установить соединение с ресивером');

}