<?php  
require_once ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  require_once (ROOT.'/users/account/mail/plugins/mail.php');
  
  ?>
  <div class="dialog_container">
  <?
  
  $s = 0;
  $data = db::get_string_all("SELECT * FROM `MAIL` WHERE `MY_ID` = ? ORDER BY `TIME` DESC LIMIT 10", [user('ID')]);
  while ($list = $data->fetch()){
    
    $s++;
    
    require (ROOT.'/users/account/mail/plugins/list_kont.php');
    
  }
  
  if ($s == 0) {
    
    ?>
    <div class='list3'> 
    <span><?=icons('comments', 84)?></span>
    <div><?=lg('Нет контактов')?></div>
    </div>
    <?
    
  }
  
  ?>
  </div>
  <div class='list-menu'> 
  <a href='/account/mail/'><u><?=lg('Все сообщения')?></u></a>
  <span onclick="dialog_modal('close')" style='float: right'><?=icons('times', 19)?></span>
  </div>
  <?
    
  if (user('MESSAGES_PRINTS') > 0){
    
    db::get_set("UPDATE `USERS` SET `MESSAGES_PRINTS` = '0' WHERE `ID` = ? LIMIT 1", [user('ID')]);
  
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}