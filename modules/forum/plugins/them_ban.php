<?php 
  
if (get('them') == "ban_on" && $them['BAN'] == 0){
  
  get_check_valid();
  
  db::get_set("UPDATE `FORUM_THEM` SET `BAN` = '1' WHERE `ID` = ? LIMIT 1", [$them['ID']]);
  
  redirect('/m/forum/show/?id='.$them['ID']);
  
}

if (get('them') == "ban_off" && $them['BAN'] == 1){
  
  get_check_valid();
  
  db::get_set("UPDATE `FORUM_THEM` SET `BAN` = '0' WHERE `ID` = ? LIMIT 1", [$them['ID']]);
  
  redirect('/m/forum/show/?id='.$them['ID']);
  
}