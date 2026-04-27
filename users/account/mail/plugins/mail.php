<?php
  
session('COUNT_MESS', 30);
  
$data = db::get_string_all("SELECT * FROM `MAIL_MESSAGE` WHERE (`USER_ID` = ? OR `MY_ID` = ?) AND `READ` = '0' AND `USER` = ? AND `UPDATE` = '0' ORDER BY `TIME` DESC", [user('ID'), user('ID'), user('ID')]);

while ($list = $data->fetch()){
  
  if (db::get_column("SELECT COUNT(*) FROM `MAIL` WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [$list['USER_ID'], $list['MY_ID']]) == 0){
    
    $account = db::get_string("SELECT `LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$list['USER_ID']]);
    
    db::get_add("INSERT INTO `MAIL` (`USER_ID`, `MY_ID`, `TIME`, `LOGIN`) VALUES (?, ?, ?, ?)", [$list['USER_ID'], $list['MY_ID'], $list['TIME'], $account['LOGIN']]);
    
  }else{
    
    db::get_add("UPDATE `MAIL` SET `TIME` = ? WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [$list['TIME'], $list['USER_ID'], $list['MY_ID']]);
    
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `MAIL` WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [$list['MY_ID'], $list['USER_ID']]) == 0){
    
    $account = db::get_string("SELECT `LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$list['MY_ID']]);
    
    db::get_add("INSERT INTO `MAIL` (`USER_ID`, `MY_ID`, `TIME`, `LOGIN`) VALUES (?, ?, ?, ?)", [$list['MY_ID'], $list['USER_ID'], $list['TIME'], $account['LOGIN']]);
    
  }else{
    
    db::get_set("UPDATE `MAIL` SET `TIME` = ? WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [$list['TIME'], $list['MY_ID'], $list['USER_ID']]);
    
  }
  
  db::get_set("UPDATE `MAIL_MESSAGE` SET `UPDATE` = '1' WHERE `UPDATE` = '0' AND `ID` = ? LIMIT 1", [$list['ID']]);
  
}

if (db::get_column("SELECT COUNT(*) FROM `MAIL` WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [user('ID'), user('ID')]) == 1){
  
  db::get_set("DELETE FROM `MAIL` WHERE `USER_ID` = ? AND `MY_ID` = ?", [user('ID'), user('ID')]);
  db::get_set("DELETE FROM `MAIL_MESSAGE` WHERE `USER_ID` = ? AND `MY_ID` = ?", [user('ID'), user('ID')]);
  redirect('/account/mail/');
  
}