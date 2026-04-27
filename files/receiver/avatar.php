<?php
  
/*
------------------------------------------------
Загрузка аватара

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
  
  //Подсчет количества отправляемых изображений
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = explode(",", strtolower(preg_replace('/\s+/', '', config('PHOTOS_EXT'))));
  
  //Создаем анонимную папку "Вложения", если её ещё нет
  require_once(ROOT.'/modules/photos/plugins/param.php');
  
  //Определяем анонимную папку "Вложения"
  $dir = db::get_string("SELECT `ID` FROM `PHOTOS_DIR` WHERE `USER_ID` = ? AND `PRIVATE` = '3' AND `NAME` = 'Вложения' AND `ID_DIR` = '0' LIMIT 1", [user('ID')]);
  
  //Принудительно устанавливаем права 755 на директории
  @chmod(ROOT."/files/upload/photos/source/", 0755);
  @chmod(ROOT."/files/upload/photos/50x50/", 0755);
  @chmod(ROOT."/files/upload/photos/150x150/", 0755);
  @chmod(ROOT."/files/upload/photos/240x240/", 0755);
  @chmod(ROOT."/files/upload/photos/260x600/", 0755);
  
  if ($fileCount > 1){
    
    ?>
    <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Нельзя загружать более 1 изображения')?></div>
    <?
    exit;
    
  }
  
  if (config('FILE_ACCESS') == 0) {
    
    ?>
    <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Выгрузка файлов на сайте приостановлена администрацией')?></div>
    <?
    exit;
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `PHOTOS` WHERE `USER_ID` = ?", [user('ID')]) >= config('PHOTOS_LIMIT')){

    ?>
    <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Вы исчерпали лимит на добавление фото')?></div>
    <?
    exit;
    
  }
  
  /*
  ---------------------
  Мультивыгрузка файлов
  ---------------------
  */
  
  $error = null; 
  $s = 0;
  for ($i = 0; $i < $fileCount; $i++) {
    
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
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Изображение с таким названием уже есть в ваших альбомах')."</div>";
    
    }elseif ($xy == false) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Это не изображение')."</div>";
    
    }elseif ($width < 160) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Ширина изображения не может быть меньше 160px. Текущая ширина: %dpx', $width)."</div>";
    
    }elseif ($height < 160) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Высота изображения не может быть меньше 160px. Текущая высота: %dpx', $height)."</div>";
    
    }elseif (!in_array($Ext, $AllowFileExt)) {

      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Неверный формат')."</div>";
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE')))."</div>";
    
    }elseif (str($FileName) < 1) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Имя не должно быть менее 1 символа')."</div>";
    
    }elseif (str($FileName) > 200) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Имя не должно быть более 200 символов')."</div>";
    
    }else{
      
      //Сохраняем файл
      if (!copy($TempName, $uploadDir.$FileName.'.'.$Ext)) {
        
        $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Не удалось загрузить')."</div>";
      
      }else{
        
        $shif = md5(user('ID').rand(111111,999999).TM);
        
        $ID = db::get_add("INSERT INTO `PHOTOS` (`NAME`, `USER_ID`, `EXT`, `SIZE`, `TIME`, `ID_DIR`, `SHOW`, `SHIF`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [$FileName, user('ID'), $Ext, filesize($TempName), TM, $dir['ID'], 1, $shif]);
        
        rename($uploadDir.$FileName.'.'.$Ext, $uploadDir.$shif.'.'.$Ext);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/50x50/'.$shif.'.jpg', 50, 50);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/150x150/'.$shif.'.jpg', 150, 150);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/240x240/'.$shif.'.jpg', 240, 240);
        crop_image($uploadDir.$shif.'.'.$Ext, ROOT.'/files/upload/photos/260x600/'.$shif.'.jpg', 600, 260);
        fd_upload($uploadDir.$shif.'.'.$Ext, 'photos');
        
        db::get_set("UPDATE `USERS_SETTINGS` SET `AVATAR` = ? WHERE `USER_ID` = ? LIMIT 1", [$ID, user('ID')]);
        
        balls_add('PHOTOS');
        rating_add('PHOTOS');
        
        $s++;
        
      }
      
    }
    
  }
  
  /*
  --------------------------------
  Действия после успешной загрузки
  --------------------------------
  */
  
  if ($s > 0) {
    
    ?> 
    <script>
    var data = "/id<?=user('ID')?>";
    var toLoad = data+' #avatar_upgrade';
    $("#avatar_upgrade").load(toLoad);        
    modal_bottom_close();
    </script>
    <?
    
  }
  
  /*
  ----------------------------
  Уведомление о наличии ошибок
  ----------------------------
  */  
  
  if (str($error) > 0) {
    
    ?> 
    <script>
    $('#files-upload-error').html("<div class='modal_title'><?=lg('Некоторые изображения не были загружены')?> (<?=$s?> <?=lg('из')?> <?=$fileCount?>)</div><div class='modal-scroll'><?=$error?></div><div class='modal_foot'><span onclick='modal_center_close()' class='button'><?=lg('Понятно, хорошо')?></span></div>");      
    modal_bottom_close();
    modal_center_open();
    </script>
    <?
    
  }

}else{
  
  ?>
  <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Не удалось установить соединение с ресивером')?></div>
  <?

}