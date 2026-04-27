<?php
  
/*
------------------------------------------------
Загрузка заставки на личную страницу

AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/
  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (isset($_FILES) && ajax() == true) {
  
  //Фактическое название изображения на сервере
  $FactName = user('ID').'_'.rand(11111111,99999999);
  
  //Директория в которую будут загружены файлы
  $uploadDir = ROOT."/files/upload/screensaver/source/";
  
  //Подсчет количества отправляемых изображений
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = array('jpg', 'png', 'gif', 'jpeg');
  
  //Принудительно устанавливаем права 755 на директории
  @chmod(ROOT."/files/upload/screensaver/source/", 0755);
  @chmod(ROOT."/files/upload/screensaver/", 0755);
  @chmod(ROOT."/files/upload/screensaver/no_screen_saver/", 0755);
  @chmod(ROOT."/files/upload/screensaver/850x200/", 0755);
  
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
    $Ext = 'jpg';
    
    //Временные файлы
    $TempName = $_FILES['file']['tmp_name'][$i];
    
    //Определение ширины и высоты изображения
    $xy = getimagesize($TempName);  
    $width = $xy[0]; 
    $height = $xy[1];

    if ($xy == false) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Это не изображение')."</div>";
    
    }elseif ($width < 420) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Ширина изображения не может быть меньше 420px. Текущая ширина: %dpx', $width)."</div>";
    
    }elseif ($height < 160) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Высота изображения не может быть меньше 160px. Текущая высота: %dpx', $height)."</div>";
    
    }elseif (!in_array($Ext, $AllowFileExt)) {

      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Неверный формат')."</div>";
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE')))."</div>";
    
    }else{
      
      //Сохраняем файл
      if (!copy($TempName, $uploadDir.$FileName.'.'.$Ext)) {
        
        $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Не удалось загрузить')."</div>";
      
      }else{
        
        //Удаляем предыдущую заставку, если она была
        @unlink(ROOT.'/files/upload/screensaver/source/'.settings('SCREENSAVER'));
        @unlink(ROOT.'/files/upload/screensaver/850x200/'.settings('SCREENSAVER')); 
        
        crop_image($uploadDir.$FileName.'.'.$Ext, ROOT.'/files/upload/screensaver/850x200/'.$FactName.'.jpg', 850, 200);
        rename($uploadDir.$FileName.'.'.$Ext, $uploadDir.$FactName.'.'.$Ext);                
        db::get_set("UPDATE `USERS_SETTINGS` SET `SCREENSAVER` = ? WHERE `USER_ID` = ? LIMIT 1", [$FactName.'.jpg', user('ID')]);        
        
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
    var toLoad = data+' #sreensaver_upgrade';
    $("#sreensaver_upgrade").load(toLoad);        
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