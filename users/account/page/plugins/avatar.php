<?php

/*
--------------------------------
Смена аватара на фото из альбома
--------------------------------
*/
  
if (get('avatar_upgrade')){
  
  get_check_valid();
  $au_id = intval(get('avatar_upgrade'));
  
  db::get_set("UPDATE `PHOTOS` SET `SHOW` = ? WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [1, user('ID'), settings('AVATAR')]);
  db::get_set("UPDATE `USERS_SETTINGS` SET `AVATAR` = ? WHERE `USER_ID` = ? LIMIT 1", [$au_id, user('ID')]); 
  
}

/*
-------------------------
Удаление текущего аватара
-------------------------
*/

if (get('avatar_delete')){
  
  get_check_valid();
  $ad_id = intval(get('avatar_delete'));
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `AVATAR` = '0' WHERE `USER_ID` = ? LIMIT 1", [user('ID')]); 
  
}

/*
------------
Вывод автара
------------
*/

if ($account['ID'] == user('ID')){
  
  ?>
  <a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/avatar.php', 'attachments_upload')">
  <?
    
}
  
?>
<span class='avatar_optimize'>  
<span id='avatar_upgrade'>
<?=user::avatar($account['ID'], 85, 0, 1)?>
</span>
<? 
  
if ($account['ID'] == user('ID')){
  
  ?><span class='avatar_button'><?=icons('camera', 14, 'fa-fw')?></span><?
  
}

?></span><?
  
if ($account['ID'] == user('ID')){
  
  ?></a><?
    
}