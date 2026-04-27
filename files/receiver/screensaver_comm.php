<?php
  
/*
------------------------------------------------
Загрузка заставки на страницу сообщества

AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/
  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

$comm = db::get_string("SELECT `ID`,`SCREENSAVER`,`URL` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);

if (isset($_FILES) && ajax() == true && isset($comm['ID'])) {
  
  //Фактическое название изображения на сервере
  $FactName = rand(11111111,99999999);
  
  //Директория в которую будут загружены файлы
  $uploadDir = ROOT."/files/upload/communities/screensaver/";
  
  //Подсчет количества отправляемых изображений
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = array('jpg', 'png', 'gif', 'jpeg');
  
  //Принудительно устанавливаем права 755 на директории
  @chmod(ROOT."/files/upload/communities/screensaver/", 0755);
  @chmod(ROOT."/files/upload/communities/screensaver/850x200/", 0755);
  
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
    $Ext = strtolower(preg_replace('#^.*\.#', null, $FileNameExt));
    
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
        @unlink(ROOT.'/files/upload/communities/screensaver/'.$comm['SCREENSAVER'].'.jpg');
        @unlink(ROOT.'/files/upload/communities/screensaver/850x200/'.$comm['SCREENSAVER'].'.jpg');
        
        crop_image($uploadDir.$FileName.'.'.$Ext, ROOT.'/files/upload/communities/screensaver/850x200/'.$FactName.'.jpg', 850, 200);
        rename($uploadDir.$FileName.'.'.$Ext, $uploadDir.$FactName.'.jpg');  
        db::get_set("UPDATE `COMMUNITIES` SET `SCREENSAVER` = ? WHERE `ID` = ? LIMIT 1", [$FactName, $comm['ID']]);        
        
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
    var data = "/public/<?=$comm['URL']?>";
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