<?php

/*
-------------------------
Удаление текущего аватара
-------------------------
*/
  
if (get('get') == 'avatar_delete'){
  
  get_check_valid();
  
  if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
    
    db::get_set("UPDATE `COMMUNITIES` SET `AVATAR` = '0' WHERE `ID` = ? LIMIT 1", [$comm['ID']]);
    
    @unlink(ROOT.'/files/upload/communities/avatar/'.$comm['AVATAR'].'.jpg');
    @unlink(ROOT.'/files/upload/communities/avatar/100x100/'.$comm['AVATAR'].'.jpg');
    
  }
  
}

/*
------------
Вывод автара
------------
*/

if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
  
  ?>
  <a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/avatar_comm.php?id=<?=$comm['ID']?>', 'attachments_upload')">
  <?
    
}
  
?>
<span class='avatar_optimize'>  
<span id='avatar_upgrade'>
<?
  
echo communities::avatar($comm['ID'], 80, 1);

?></span><? 
  
if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
  
  ?><span class='avatar_button'><?=icons('camera', 14, 'fa-fw')?></span><?
  
}

?></span><?
  
if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
  
  ?></a><?
    
}