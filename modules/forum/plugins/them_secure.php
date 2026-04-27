<?php
  
if (get('secure') == "off" && $them['SECURE'] == 1){ 
  
  get_check_valid();
  
  db::get_set("UPDATE `FORUM_THEM` SET `SECURE` = ? WHERE `ID` = ? LIMIT 1", [0, $them['ID']]);
  
  redirect('/m/forum/show/?id='.$them['ID']);
  
}

if (get('secure') == "on" && $them['SECURE'] == 0){
  
  get_check_valid();
  
  db::get_set("UPDATE `FORUM_THEM` SET `SECURE` = ? WHERE `ID` = ? LIMIT 1", [1, $them['ID']]);
  
  redirect('/m/forum/show/?id='.$them['ID']);
  
}