<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  $comm = db::get_string("SELECT `ID`,`URL`,`SCREENSAVER` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
  $par = db::get_string("SELECT `ADMINISTRATION`,`USER_ID`,`ID` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? LIMIT 1", [$comm['ID'], user('ID')]);
  
  if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
    
    ?>
    <div class="modal_bottom_title2">
    <?=lg('Новая заставка')?>
    </div>
      
    <div class="modal-bottom-container" style="height: 267px;">      
    <?=file::upload('/files/receiver/screensaver_comm.php?id='.$comm['ID'])?>
      
    <?php
      
    //Если есть установленная заставка
    if (is_file(ROOT.'/files/upload/communities/screensaver/850x200/'.$comm['SCREENSAVER'].'.jpg')) {
      
      ?>
      <div class='screensaver_set'>
      <a href="/public/<?=$comm['URL']?>?get=delete_screensaver&<?=TOKEN_URL?>" class="screensaver_del"><?=icons('trash', 15, 'fa-fw')?></a>
      <img class='img' src='/files/upload/communities/screensaver/850x200/<?=$comm['SCREENSAVER']?>.jpg'>
      </div>
      <?
  
    }
    
    hooks::challenge('screensaver_comm', 'screensaver_comm');
    hooks::run('screensaver_comm');
    
    ?>
    
    </div>  
    
    <center>
    <span onclick='modal_bottom_close()' class='modal-bottom-button'><?=lg('Отменить')?></span>
    </center>
    <?
      
  }else{
    
    echo lg('Неверная директива');
  
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}