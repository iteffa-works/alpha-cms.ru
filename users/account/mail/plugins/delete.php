<?php
  
if (get('get') == 'delete_all_ok') { 
  
  get_check_valid();
  
  $data = db::get_string_all("SELECT * FROM `MAIL_MESSAGE` WHERE (`USER_ID` = ? OR `MY_ID` = ?) AND (`USER_ID` = ? OR `MY_ID` = ?) AND `USER` = ?", [user('ID'), user('ID'), ACCOUNT_ID, ACCOUNT_ID, user('ID')]);
  while($list = $data->fetch()) {
    
    if (db::get_column("SELECT * FROM `MAIL_MESSAGE` WHERE `TID` = ?", [$list['TID']]) > 1) {
      
      db::get_set("DELETE FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ID_POST` = ? LIMIT 1", [user('ID'), $list['TID']]);
      
    }
    
    db::get_set("DELETE FROM `MAIL_MESSAGE` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
    
  }
  
  redirect('/account/mail/messages/?id='.ACCOUNT_ID.'&'.TOKEN_URL);
  
}
  
if (get('get') == 'delete_all') { 
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите очистить переписку навсегда')?>?<br /><br />
  <a href='/account/mail/messages/?id=<?=ACCOUNT_ID?>&get=delete_all_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Очистить')?></a>
  <a href='/account/mail/messages/?id=<?=ACCOUNT_ID?>&<?=TOKEN_URL?>' class='button-o'><?=lg('Отмена')?></a> 
  </div>
  <?
    
  back('/account/mail/messages/?id='.ACCOUNT_ID.'&'.TOKEN_URL);
  acms_footer();
  
}  
  
if (get('delete_my')) { 
  
  get_check_valid();
  $delete = db::get_string("SELECT `ID`,`TID` FROM `MAIL_MESSAGE` WHERE `USER` = ? AND `ID` = ? LIMIT 1", [user('ID'), intval(get('delete_my'))]);
  
  if (isset($delete['ID'])) {
    
    if (db::get_column("SELECT * FROM `MAIL_MESSAGE` WHERE `TID` = ?", [$delete['TID']]) > 1) {
      
      db::get_set("DELETE FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ID_POST` = ? LIMIT 1", [user('ID'), $delete['TID']]);
      
    }
    
    db::get_set("DELETE FROM `MAIL_MESSAGE` WHERE `ID` = ? LIMIT 1", [$delete['ID']]);
    
  }  
  
}

if (get('delete_user')) { 
  
  get_check_valid();
  $delete = db::get_string("SELECT `ID`,`TID`,`TIME` FROM `MAIL_MESSAGE` WHERE `USER` = ? AND `TID` = ? LIMIT 1", [user('ID'), intval(get('delete_user'))]);
  
  if (isset($delete['ID']) && $delete['TIME'] > TM - 600) {
    
    db::get_set("DELETE FROM `MAIL_MESSAGE` WHERE `TID` = ? LIMIT 2", [$delete['TID']]);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ID_POST` = ? LIMIT 1", [user('ID'), $delete['TID']]);
    
  }  
  
}