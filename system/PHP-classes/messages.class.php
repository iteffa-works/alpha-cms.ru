<?php

/*
---------------------------------------
Класс для работы с почтой и сообщениями
---------------------------------------
*/
  
class messages{
  
  //Запись сообщений в базу данных при отправке
  public static function get($my_id, $user_id, $message, $reply_id = 0) {
    
    //$my_id - id пользователя
    //$user_id - id собеседника 
    //$reply_id - id сообщения для ответа
    //$message - сообщение
    
    $tid = intval(substr(TM, -3, 7).rand(111111, 999999));
    
    $id = db::get_add("INSERT INTO `MAIL_MESSAGE` (`USER_ID`, `MY_ID`, `TIME`, `MESSAGE`, `USER`, `REPLY`, `TID`) VALUES (?, ?, ?, ?, ?, ?, ?), (?, ?, ?, ?, ?, ?, ?)", [$user_id, $my_id, TM, $message, $my_id, $reply_id, $tid, $user_id, $my_id, TM, $message, $user_id, $reply_id, $tid]);
    
    define('MESS_ID', $id);
    define('TID', $tid);
  
  }
  
  //Помечение сообщений как прочитанные
  public static function read($my_id, $user_id) {
    
    //$my_id - id пользователя
    //$user_id - id собеседника 
    
    if (db::get_column("SELECT COUNT(*) FROM `MAIL_MESSAGE` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `READ` = '0' LIMIT 1", [$user_id, $my_id]) >= 1){
      
      db::get_set("UPDATE `MAIL_MESSAGE` SET `READ` = '1' WHERE `MY_ID` = ? AND `USER_ID` = ? AND `READ` = '0'", [$user_id, $my_id]);
      
    }
  
  }

}