<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  get_check_valid();

  $user_id = intval(get('id'));  
  db::get_set("UPDATE `USERS` SET `MESSAGES_PRINTS` = ? WHERE `ID` = ? LIMIT 1", [user('ID'), $user_id]);
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}