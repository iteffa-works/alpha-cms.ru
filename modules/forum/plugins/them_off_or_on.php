<?php
  
if (get('them') == "off" && $them['ACTIVE'] == 1){ 
  
  get_check_valid();
  
  db::get_set("UPDATE `FORUM_THEM` SET `ACTIVE_TIME` = ?, `ACTIVE_USER_ID` = ?, `ACTIVE` = ? WHERE `ID` = ? LIMIT 1", [TM, user('ID'), 0, $them['ID']]);
  
  redirect('/m/forum/show/?id='.$them['ID']);
  
}

if (get('them') == "on" && $them['ACTIVE'] == 0){
  
  get_check_valid();
  
  db::get_set("UPDATE `FORUM_THEM` SET `ACTIVE_TIME` = ?, `ACTIVE_USER_ID` = ?, `ACTIVE` = ? WHERE `ID` = ? LIMIT 1", [null, 0, 1, $them['ID']]);
  
  redirect('/m/forum/show/?id='.$them['ID']);
  
}