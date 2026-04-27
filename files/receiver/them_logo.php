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
  
  if ($fileCount > 1){
    
    ?>
    <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Нельзя загружать более %d файлов за 1 раз', 1)?></div>
    <?
    exit;
    
  }
  
  if (!isset($them['ID'])){
    
    ?>
    <div class='file-error'><?=icons('exclamation-triangle', 16)?> <?=lg('Тема не найдена')?></div>
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
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Это не изображение')."</div>";
    
    }elseif (!in_array($Ext, $AllowFileExt)) {

      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Неверный формат')."</div>";
    
    }elseif (filesize($TempName) > config('MAXFILESIZE')) {
      
      $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Размер превышает установленные ограничения. Размер должен быть не больше %s', size_file(config('MAXFILESIZE')))."</div>";
    
    }else{
      
      //Сохраняем файл
      if (!copy($TempName, $uploadDir.$FileName.'.'.$Ext)) {
        
        $error .= "<div class='file-info'>".icons('exclamation-triangle', 16)." <b>".$FileNameExt."</b> - ".lg('Не удалось загрузить')."</div>";
      
      }else{
        
        //Удаляем предыдущий логотип
        @unlink(ROOT.'/style/version/'.$them['DIR'].'/logo/'.$them['LOGO']);
        db::get_set("UPDATE `PANEL_THEMES` SET `LOGO` = ?, `LOGO_MAX` = '140' WHERE `ID` = ? LIMIT 1", [$FactName.".".$Ext, $them['ID']]);
        rename($uploadDir.$FileName.'.'.$Ext, $uploadDir.$FactName.'.'.$Ext);
        
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
    var data = "/admin/site/themes/?them_edit=<?=$them['ID']?>";
    var toLoad = data+' #logo';
    $("#logo").load(toLoad);        
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