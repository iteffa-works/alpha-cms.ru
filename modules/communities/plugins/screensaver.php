<?php
  
/*
-----------------
Удаление заставки
-----------------
*/
  
if (get('get') == 'delete_screensaver'){
  
  get_check_valid();
  
  if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
    
    @unlink(ROOT.'/files/upload/communities/screensaver/'.$comm['SCREENSAVER'].'.jpg');
    @unlink(ROOT.'/files/upload/communities/screensaver/850x200/'.$comm['SCREENSAVER'].'.jpg');
    
    db::get_set("UPDATE `COMMUNITIES` SET `SCREENSAVER` = ? WHERE `ID` = ? LIMIT 1", [null, $comm['ID']]);
    
  }

}

/*
--------
Заставка
--------
*/

?>
<div class='screensaver'>
<div id='sreensaver_upgrade'>
<?

if (is_file(ROOT.'/files/upload/communities/screensaver/850x200/'.$comm['SCREENSAVER'].'.jpg')) {
  
  ?><img src='/files/upload/communities/screensaver/850x200/<?=$comm['SCREENSAVER']?>.jpg'><?
  
}else{
  
  ?><img src='/files/upload/communities/screensaver/850x200/no_screensaver.jpg'><?
  
}

if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
  
  ?>
  <a ajax="no" class="screensaver_button" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/screensaver_comm.php?id=<?=$comm['ID']?>', 'attachments_upload')">
  <?=icons('gear', 17)?>
  </a>
  <?
    
}
  
?>
</div>
</div>
<?
  
attachments_result();