<a href='/account/mail/messages/?id=<?=$list['USER_ID']?>&<?=TOKEN_URL?>'>

<?php
  
$message = db::get_string("SELECT * FROM `MAIL_MESSAGE` WHERE (`USER_ID` = ? OR `MY_ID` = ?) AND (`USER_ID` = ? OR `MY_ID` = ?) AND `USER` = ? ORDER BY `TIME` DESC LIMIT 1", [$list['USER_ID'], $list['USER_ID'], $list['MY_ID'], $list['MY_ID'], $list['MY_ID']]); 

if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", ['ok_message', $message['TID']]) > 0) {
  
  $file = '<b>'.icons('paperclip', 17, 'fa-fw').' '.lg('Файл').'</b>';

}else{
  
  $file = null;

}

//Удаляем диалог если в нем нет писем
if (!isset($message['ID'])){
  
  db::get_set("DELETE FROM `MAIL` WHERE `ID` = ? LIMIT 1", [$list['ID']]);

}
  
/*
-----------
Отправитель
-----------
*/
  
if ($message['MY_ID'] == user('ID')){
  
  if ($message['READ'] == 0){ $mess_eye = "<span class='mail-mess-eye'>".icons('eye-slash', 14, 'fa-fw')."</span>"; }else{ $mess_eye = null; }
  
  ?>       
  <div class='list-menu hover'>
  <div class='mail-list-avatar'>  
  <?=user::avatar($list['USER_ID'], 60, 1)?>  
  </div>    
  <div class='mail-list-info'>  
  <?=user::login($list['USER_ID'])?>      
  <span style='float: right;'><font color='#68B4F0'><?=icons('arrow-circle-right', 17, 'fa-fw')?></font></span><br />    
  <span class='time'><?=stime($list['TIME'])?></span><br />      
  <div class='mail-list-text'>
  <?php if (user('MESSAGES_PRINTS') == $list['USER_ID']) { ?>
  <span class='mp'><?=lg('печатает')?> ...</span>
  <?php }else{ ?>
  <?=(str($message['MESSAGE']) > 0 ? crop_text(text($message['MESSAGE'], 0, 0, 0, 0), 0, 20) : '...').$file?><?=$mess_eye?>
  <?php } ?>
  </div></div></div>
  <?
  
}
  
/*
----------
Получатель
----------
*/
  
if ($message['USER_ID'] == user('ID')){
  
  if ($message['READ'] == 0){
    
    $count = db::get_column("SELECT COUNT(`ID`) FROM `MAIL_MESSAGE` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `READ` = '0' AND `USER` = ?", [$message['USER_ID'], $message['MY_ID'], user('ID')]);
    
    $e_mess = "mail-e"; 
    $count = "<span class='mail-count'>+".$count."</span>"; 
  
  }else{ 
    
    $e_mess = null; 
    $count = null;
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [$list['USER_ID'], 1]) > 0){
    
    $message = icons('lock', 17, 'fa-fw')." ".('Автор сообщения заблокирован');
  
  }else{
    
    $message = (str($message['MESSAGE']) > 0 ? crop_text(text($message['MESSAGE'], 0, 0, 0, 0), 0, 20) : '...').$file;
  
  }
  
  ?>
  <div class='list-menu hover <?=$e_mess?>'>    
  <div class='mail-list-avatar'>  
  <?=user::avatar($list['USER_ID'], 60, 1)?>  
  </div>    
  <div class='mail-list-info'>  
  <?=user::login($list['USER_ID'])?> <?=$count?>
  <span style='float: right;'><font color='#FF776D'><?=ICONS('arrow-circle-left', 17, 'fa-fw')?></font></span><br />
  <span class='time'><?=stime($list['TIME'])?></span><br />        
  <div class='mail-list-text'>
  <?php if (user('MESSAGES_PRINTS') == $list['USER_ID']) { ?>
  <span class='mp'><?=lg('печатает')?> ...</span> 
  <?php }else{ ?>
  <?=$message?>
  <?php } ?>
  </div></div></div>
  <?
  
}
  
?></a><?