<?php
  
/*
-----------------
Удаление заставки
-----------------
*/
  
if (get('get') == 'delete_screensaver'){
  
  get_check_valid();
  
  @unlink(ROOT.'/files/upload/screensaver/source/'.$settings['SCREENSAVER']);
  @unlink(ROOT.'/files/upload/screensaver/850x200/'.$settings['SCREENSAVER']);
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `SCREENSAVER` = ? WHERE `USER_ID` = ? LIMIT 1", [null, user('ID')]);

}

/*
---------------------------
Заставка на личной странице
---------------------------
*/

?>
<div class='screensaver'>
<div id='sreensaver_upgrade'>
<?

if (is_file(ROOT.'/files/upload/screensaver/850x200/'.$settings['SCREENSAVER'])) {
  
  ?><img src='/files/upload/screensaver/850x200/<?=$settings['SCREENSAVER']?>'><?
  
}else{
  
  if ($account['SEX'] == 2){
    
    ?><img src='/files/upload/screensaver/no_screen_saver/woman.jpg'><?
    
  }else{
    
    ?><img src='/files/upload/screensaver/no_screen_saver/man.jpg'><?
  
  }
  
}

if ($account['ID'] == user('ID')){
  
  ?>
  <a ajax="no" class="screensaver_button" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/screensaver.php', 'attachments_upload')">
  <?=icons('gear', 17)?>
  </a>
  <?
    
}elseif (user('ID') > 0){
  
  ?>
  <a class="screensaver_button" href="/account/gifts/give/?id=<?=$account['ID']?>">
  <?=icons('gift', 17)?>
  </a>
  <?
  
}
  
?></div><div style='position: relative;'><?
  
require_once (ROOT.'/users/account/page/plugins/status.php');
  
?></div></div><?
  
attachments_result();