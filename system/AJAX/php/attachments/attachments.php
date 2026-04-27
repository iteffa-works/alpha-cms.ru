<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true && get('param') && get('rtype') && get('ptype') && get('link')){
  
  $get = tabs(get('param'));
  $ptype = esc(get('ptype'));
  $type = esc(get('rtype'));
  $link = tabs(get('link'));
  $id = intval(get('id'));
  $param = explode(',', $get);
  
  if ($id == 0) {
    
    $count = count($param) + db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ACT` = ? AND `TYPE_POST` = ?", [user('ID'), 0, $ptype]);
    
  }else{
    
    $count = count($param) + db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `TYPE_POST` = ?", [user('ID'), $ptype]);
    
  }
  
  if ($count > 20){
    
    ?>
    <script>
    $('#files-upload-error').html("<div class='modal_title'><?=lg('Ошибка')?></div><div class='modal-scroll'><div class='file-info'><?=icons('exclamation-triangle', 16)?> <?=lg('Нельзя прикреплять более 20 файлов к 1 сообщению')?></div></div><div class='modal_foot'><span onclick='modal_center_close()' class='button'><?=lg('Понятно, хорошо')?></span></div>"); 
    modal_bottom_close();
    modal_center_open();
    </script>
    <?

    attachments_show($ptype, $link, $id);
    exit;
    
  }
  
  if ($type == 'photos'){
    
    $_type = 'PHOTOS';
    
  }elseif ($type == 'videos'){
    
    $_type = 'VIDEOS';
    
  }elseif ($type == 'music'){
    
    $_type = 'MUSIC';
    
  }elseif ($type == 'files'){
    
    $_type = 'FILES';
    
  }
  
  if ($id == 0) {
    
    foreach ($param as $file){
      
      if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `TYPE_POST` = ? AND `ACT` = ? AND `TYPE` = ? AND `OBJECT_ID` = ? AND `ID_POST` = ? LIMIT 1", [user('ID'), $ptype, 0, $type, intval($file), $id]) == 0 && db::get_column("SELECT COUNT(*) FROM `".$_type."` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [user('ID'), intval($file)]) == 1){
        
        db::get_add("INSERT INTO `ATTACHMENTS` (`USER_ID`, `TYPE_POST`, `TIME`, `TYPE`, `OBJECT_ID`, `ID_POST`) VALUES (?, ?, ?, ?, ?, ?)", [user('ID'), $ptype, TM, $type, intval($file), 0]);
      
      }
      
    }
  
  }else{
    
    foreach ($param as $file){
      
      if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `TYPE` = ? AND `OBJECT_ID` = ? AND `ID_POST` = ? LIMIT 1", [$ptype, $type, intval($file), $id]) == 0 && db::get_column("SELECT COUNT(*) FROM `".$_type."` WHERE `ID` = ? LIMIT 1", [intval($file)]) == 1){
        
        db::get_add("INSERT INTO `ATTACHMENTS` (`USER_ID`, `TYPE_POST`, `TIME`, `TYPE`, `OBJECT_ID`, `ACT`, `ID_POST`) VALUES (?, ?, ?, ?, ?, ?, ?)", [user('ID'), $ptype, TM, $type, intval($file), 1, $id]);
      
      }
      
    }
    
  }
  
  ?>
  <script>
  modal_bottom_close();
  </script>
  <?
  
  attachments_show($ptype, $link, $id);
  
}else{
  
  echo lg('Не удалось установить соединение');

}