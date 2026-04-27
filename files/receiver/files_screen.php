<?php
  
/*
------------------------------------------------
Загрузка скриншотов к файлам

AlphaCMS - универсальный движок для вашего сайта
E-mail администрации проекта: adm@alpha-cms.ru
Официальный сайт поддержки: alpha-cms.ru
Руководитель проекта: adm (ID 1)
------------------------------------------------
*/
  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

$id = intval(get('id'));
$url = url_check_validate(base64_decode(get('url')));
$file = db::get_string("SELECT `ID` FROM `FILES` WHERE `ID` = ? LIMIT 1", [$id]);

if (isset($file['ID']) && isset($_FILES) && ajax() == true) {
  
  //Директория в которую будут загружены файлы
  $uploadDir = ROOT."/files/upload/files/screen/source/";
  
  //Подсчет количества отправляемых файлов
  $fileCount = count($_FILES['file']['name']);
  
  //Разрешенные форматы для выгрузки
  $AllowFileExt = explode(",", strtolower(preg_replace('/\s+/', '', 'jpg,png,gif,jpeg')));
  
  //Принудительно устанавливаем права 755 на директории
  @chmod(ROOT."/files/upload/files/screen/source/", 0755);
  @chmod(ROOT."/files/upload/files/screen/", 0755);
  
  if ($fileCount > config('MAXFILEUPLOAD')){
    
    ?>
    <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Нельзя загружать более %d файлов за 1 раз', config('MAXFILEUPLOAD'))?></div>
    <?
    exit;
    
  }
  
  if (config('FILE_ACCESS') == 0) {
    
    ?>
    <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Выгрузка файлов на сайте приостановлена администрацией')?></div>
    <?
    exit;
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ID_POST` = ? AND `TYPE_POST` = ? LIMIT 30", [user('ID'), $file['ID'], 'files_screen']) + $fileCount > 30){

    ?>
    <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Нельзя прикреплять более 30 скриншотов к 1 файлу')?></div>
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
    
    }elseif ($width < 120) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Ширина изображения не может быть меньше 120px. Текущая ширина: %dpx', $width)."</div>";
    
    }elseif ($height < 120) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Высота изображения не может быть меньше 120px. Текущая высота: %dpx', $height)."</div>";
    
    }elseif (!in_array($Ext, $AllowFileExt)) {

      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Неверный формат')."</div>";
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE')))."</div>";
    
    }else{
      
      //Сохраняем файл
      if (!copy($TempName, $uploadDir.$FileName.'.jpg')) {
        
        $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Не удалось загрузить')."</div>";
      
      }else{ 
        
        $ID = db::get_add("INSERT INTO `ATTACHMENTS` (`USER_ID`, `OBJECT_ID`, `TYPE`, `TYPE_POST`, `TIME`, `ID_POST`, `ACT`) VALUES (?, ?, ?, ?, ?, ?, ?)", [user('ID'), $file['ID'], 'files_screen', 'files_screen', TM, $file['ID'], 1]);
        
        rename($uploadDir.$FileName.'.jpg', $uploadDir.$ID.'.jpg');
        crop_image($uploadDir.$ID.'.jpg', ROOT.'/files/upload/files/screen/'.$ID.'.jpg', 240, 240);
        
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
    var data = "<?=$url?>";
    var toLoad = data+' #upload-screen';
    $("#upload-screen").load(toLoad);        
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