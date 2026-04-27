<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  $comm = db::get_string("SELECT `ID`,`URL`,`AVATAR` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
  $par = db::get_string("SELECT `ADMINISTRATION`,`USER_ID`,`ID` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? LIMIT 1", [$comm['ID'], user('ID')]);
  
  if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
    
    ?>
    <div class="modal_bottom_title2">
    <?=lg('Новый аватар')?>
    </div>
    
    <div class="modal-bottom-container" style="height: 267px;">      
    <?=file::upload('/files/receiver/avatar_comm.php?id='.$comm['ID'])?>
    
    <?php
    
    //Если есть установленный аватар  
    if (is_file(ROOT.'/files/upload/communities/avatar/100x100/'.$comm['AVATAR'].'.jpg')) {
    
      ?>
      <br />
      <center>
      <a href='/public/<?=$comm['URL']?>?get=avatar_delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить текущий')?></a>
      </center>
      <br />  
      <?
  
    }
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