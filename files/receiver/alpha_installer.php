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
  $uploadDir = ROOT."/files/upload/alpha_installer/";
  
  //Останавливаем выгрузку, если нет файлов
  if (!isset($_FILES['file']['name'])) { file::error(); }
  
  //Подсчет количества отправляемых файлов
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = 'zip';
  
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
    $FileNameExt = $_FILES['file']['name'][$i];
    
    //Оригинальное название файла без расширения
    $FileName = tprcs(preg_replace('#\.[^\.]*$#', null, $FileNameExt));
    
    //Расширение файла без названия
    $Ext = strtolower(preg_replace('#^.*\.#', null, $FileNameExt));
    
    //Временные файлы
    $TempName = $_FILES['file']['tmp_name'][$i];
    
    //Фактическое название файла на сервере
    $FactName = md5(rand(1111111,9999999));

    if ($Ext != 'zip') {

      file::error('<b>'.$FileNameExt.'</b> - '.lg('неверный формат архива. Допустимые форматы: %s', $AllowFileExt));
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE'))));
    
    }elseif (str($FileName) < 1) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('имя не должно быть менее 1 символа'));
    
    }elseif (str($FileName) > 200) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('имя не должно быть более 200 символов'));
    
    }else{
      
      //Сохраняем файл
      if (@copy($TempName, $uploadDir.$FileName.'.'.$Ext)) {
        
        $ID = db::get_add("INSERT INTO `PANEL_ALPHA_INSTALL` (`SIZE`, `NAME`, `FACT_NAME`, `EXT`, `TIME`) VALUES (?, ?, ?, ?, ?)", [filesize($TempName), $FileName, $FactName, $Ext, TM]);        
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
    
    file::update('/admin/system/alpha_installer/', '#upload-zip');
    
  }

}else{
  
  file::error('Не удалось установить соединение с ресивером');

}