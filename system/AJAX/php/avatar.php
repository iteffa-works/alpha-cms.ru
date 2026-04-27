<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  ?>
  <div class="modal_bottom_title2">
  <?=lg('Новый аватар')?>
  </div>
    
  <div class="modal-bottom-container" style="height: 267px;">      
  <?=file::upload('/files/receiver/avatar.php')?>
    
  <?php
  $photo = db::get_string("SELECT `SHIF` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [settings('AVATAR')]);
    
  //Если есть установленный аватар  
  if (is_file(ROOT.'/files/upload/photos/150x150/'.$photo['SHIF'].'.jpg')) {
    
    ?>
    <br />
    <center>
    <a href='/id<?=user('ID')?>?avatar_delete=<?=settings('AVATAR')?>&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить текущий')?></a>
    </center>
    <br />  
    <?
  
  }
  
  ?><div id="photos_list" class="modal-bottom-container2"><?                                  
    
  $data = db::get_string_all("SELECT `ID`,`SHIF` FROM `PHOTOS` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT 24", [user('ID')]);
  while ($list = $data->fetch()){
    
    ?><a href="/id<?=user('ID')?>?avatar_upgrade=<?=$list['ID']?>&<?=TOKEN_URL?>" class="img-avatar-optimize"><img src='/files/upload/photos/150x150/<?=$list['SHIF']?>.jpg' class='attachments-photos-img'></a><?
    
  }
  
  ?></div><?
    
  if (db::get_column("SELECT COUNT(*) FROM `PHOTOS` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]) > 24) {
    
    ?><center><button onclick="show_more('/system/AJAX/php/avatar_list.php', '#show_more', '#photos_list', 24, 'append')" class="button" id="show_more" count_show="24" count_add="24" name_show="<?=lg('Показать еще')?>" name_hide="<?=lg('Конец')?>"><?=lg('Показать еще')?></button></center><br /><? 
    
  }
    
  ?>
  
  </div>  
    
  <center>
  <span onclick='modal_bottom_close()' class='modal-bottom-button'><?=lg('Отменить')?></span>
  </center>
  <?
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}