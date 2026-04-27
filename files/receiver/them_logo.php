<?php
  
/*
------------------------------------------------
Загрузка логотипа к теме

AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/
  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('management');

if (isset($_FILES) && ajax() == true) {
  
  //Определяем тему
  $them = db::get_string("SELECT * FROM `PANEL_THEMES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
  
  //Директория в которую будут загружены файлы
  $uploadDir = ROOT."/style/version/".$them['DIR']."/logo/";
  
  //Подсчет количества отправляемых файлов
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = explode(",", strtolower(preg_replace('/\s+/', '', config('PHOTOS_EXT'))));
  
  //Принудительно устанавливаем права 755 на директории
  @chmod(ROOT."/style/version/".$them['DIR']."/logo/", 0755);
  
  if ($fileCount > 1) {
    
    file::error(lg('Нельзя загружать более %d файлов за 1 раз', 1));
    
  }
  
  if (!isset($them['ID'])) {
    
    file::error('Тема не найдена');
    
  }
  
  /*
  ---------------------
  Мультивыгрузка файлов
  ---------------------
  */
  
  $error = null; 
  $s = 0;
  for ($i = 0; $i < $fileCount; $i++) {
    
    //Фактическое название логотипа на сервере
    $FactName = "logo_".rand(11111,99999);
    
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

    if ($xy == false) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('это не изображение'));
    
    }elseif (!in_array($Ext, $AllowFileExt)) {

      file::error('<b>'.$FileNameExt.'</b> - '.lg('неверный формат изображения. Допустимые форматы: %s', strtolower(preg_replace('/\s+/', '', config('PHOTOS_EXT')))));
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      file::error('<b>'.$FileNameExt.'</b> - '.lg('размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE'))));
    
    }else{
      
      //Сохраняем файл
      if (@copy($TempName, $uploadDir.$FactName.'.'.$Ext)) {
        
        //Удаляем предыдущий логотип
        @unlink(ROOT.'/style/version/'.$them['DIR'].'/logo/'.$them['LOGO']);
        db::get_set("UPDATE `PANEL_THEMES` SET `LOGO` = ?, `LOGO_MAX` = '140' WHERE `ID` = ? LIMIT 1", [$FactName.".".$Ext, $them['ID']]);
        
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
    
    file::update('/admin/site/themes/?them_edit='.$them['ID'], '#logo');
    
  }

}else{
  
  file::error('Не удалось установить соединение с ресивером');

}