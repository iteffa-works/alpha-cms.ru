<?php
  
if (user('ID') > 0){
  
  /*
  ---------------------
  Добавить в подписчики
  ---------------------
  */
  
  if (get('subscribe_ok')){
    
    //db_filter();
    get_check_valid();
    $so_id = intval(get('subscribe_ok'));
    
    //Определение данных
    $subscribe = db::get_string("SELECT `ID` FROM `SUBSCRIBERS` WHERE `MY_ID` = ? AND `USER_ID` = ? LIMIT 1", [user('ID'), $so_id]);
    
    if (!isset($subscribe['ID']) && user('ID') != $so_id && $so_id > 0){ 
      
      db::get_add("INSERT INTO `SUBSCRIBERS` (`USER_ID`, `MY_ID`) VALUES (?, ?)", [$so_id, user('ID')]);
      
      if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `SUBSCRIBERS` = '1' LIMIT 1", [$so_id]) == 1){ 
        
        db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?)", [$so_id, user('ID'), TM, 'subscribe_success']);
      
      }
    
    }
    
  }
  
  /*
  ----------------------
  Удалить из подписчиков
  ----------------------
  */
  
  if (get('subscribe_delete')){
    
    //db_filter();
    get_check_valid();
    $sd_id = intval(get('subscribe_delete'));
    
    //Определение данных
    $subscribe = db::get_string("SELECT `ID` FROM `SUBSCRIBERS` WHERE `MY_ID` = ? AND `USER_ID` = ? LIMIT 1", [user('ID'), $sd_id]);
    
    if (isset($subscribe['ID']) && user('ID') != $sd_id && $sd_id > 0){ 
      
      db::get_set("DELETE FROM `SUBSCRIBERS` WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [$sd_id, user('ID')]);
    
    }
    
  }
  
  /*
  -----------------
  Удалить из друзей
  -----------------
  */
  
  if (get('friends_delete')){
    
    //db_filter();
    get_check_valid();
    $fd_id = intval(get('friends_delete'));
    
    //Определение данных
    $frend = db::get_string("SELECT `ID` FROM `FRIENDS` WHERE (`USER_ID` = ? AND `MY_ID` = ? OR `USER_ID` = ? AND `MY_ID` = ?) AND `ACT` = '0' LIMIT 1", [user('ID'), $fd_id, $fd_id, user('ID')]);
    
    if (isset($frend['ID'])){ 
      
      db::get_set("DELETE FROM `FRIENDS` WHERE (`USER_ID` = ? AND `MY_ID` = ? OR `MY_ID` = ? AND `USER_ID` = ?) AND `ACT` = '0'", [$fd_id, user('ID'), $fd_id, user('ID')]);
      db::get_set("DELETE FROM `SUBSCRIBERS` WHERE (`USER_ID` = ? AND `MY_ID` = ? OR `MY_ID` = ? AND `USER_ID` = ?) LIMIT 2", [$fd_id, user('ID'), $fd_id, user('ID')]);
      
      if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `FRIENDS` = '1' LIMIT 1", [$fd_id]) == 1){
        
        db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?)", [$fd_id, user('ID'), TM, 'friends_delete']);
        
      }
    
    }
  
  }
  
  /*
  ---------------------------
  Отменить предложение дружбы
  ---------------------------
  */
  
  if (get('friends_cancel')){
    
    //db_filter();
    get_check_valid();
    $fc_id = intval(get('friends_cancel'));
    
    //Определение данных
    $frend = db::get_string("SELECT `USER_ID`,`ID` FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1' LIMIT 1", [user('ID'), $fc_id]);
    
    if (isset($frend['ID']) && user('ID') != $frend['USER_ID'] && $frend['USER_ID'] > 0){ 
      
      db::get_set("DELETE FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1' LIMIT 2", [user('ID'), $frend['USER_ID']]);      
      db::get_set("DELETE FROM `SUBSCRIBERS` WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 2", [$frend['USER_ID'], user('ID')]);
      
      if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `FRIENDS` = '1' LIMIT 1", [$fc_id]) == 1){
        
        db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?)", [$frend['USER_ID'], user('ID'), TM, 'friends_cancel']);
        
      }
    
    }
  
  }
  
  /*
  -----------------
  Предложить дружбу
  -----------------
  */
  
  if (get('friends_add')){
    
    //db_filter();
    get_check_valid();
    $fr_set = db::get_string("SELECT `USER_ID`,`FRIENDS_PRIVATE_ADD` FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [intval(get('friends_add'))]);
    
    if ($fr_set['FRIENDS_PRIVATE_ADD'] == 1){
      
      //Определение данных
      $frend = db::get_string("SELECT `ID`,`USER_ID` FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1' LIMIT 1", [user('ID'), $fr_set['USER_ID']]);
      
      if (!isset($frend['ID']) && user('ID') != $fr_set['USER_ID'] && $fr_set['USER_ID'] > 0){ 
        
        db::get_set("DELETE FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ?", [$fr_set['USER_ID'], user('ID')]);
        db::get_add("INSERT INTO `FRIENDS` (`USER_ID`, `MY_ID`, `TIME`) VALUES (?, ?, ?)", [$fr_set['USER_ID'], user('ID'), TM]);
        
        if (db::get_column("SELECT COUNT(*) FROM `SUBSCRIBERS` WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [$fr_set['USER_ID'], user('ID')]) == 0){
          
          db::get_add("INSERT INTO `SUBSCRIBERS` (`USER_ID`, `MY_ID`) VALUES (?, ?)", [$fr_set['USER_ID'], user('ID')]);
        
        }
        
        if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `FRIENDS` = '1' LIMIT 1", [$fr_set['USER_ID']]) == 1){
          
          db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?)", [$fr_set['USER_ID'], user('ID'), TM, 'friends_add']);
          
        }
        
      }
    
    }
  
  }
  
  /*
  ---------------
  Принятие заявки
  ---------------
  */
  
  if (get('friends_ok')){
    
    //db_filter();
    get_check_valid();
    $fo_id = intval(get('friends_ok'));
    
    //Определение данных
    $frend = db::get_string("SELECT `ID`,`MY_ID` FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1' LIMIT 1", [$fo_id, user('ID')]);
    
    if (user('ID') != $frend['MY_ID'] && isset($frend['ID'])){
      
      db::get_set("UPDATE `FRIENDS` SET `ACT` = '0' WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1' LIMIT 2", [$frend['MY_ID'], user('ID')]);      
      db::get_add("INSERT INTO `FRIENDS` (`USER_ID`, `MY_ID`, `TIME`, `ACT`) VALUES (?, ?, ?, ?)", [$frend['MY_ID'], user('ID'), TM, 0]);
      
      if (db::get_column("SELECT COUNT(*) FROM `SUBSCRIBERS` WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 2", [$frend['MY_ID'], user('ID')]) == 0){
        
        db::get_add("INSERT INTO `SUBSCRIBERS` (`USER_ID`, `MY_ID`) VALUES (?, ?)", [$frend['MY_ID'], user('ID')]);
        
      }
      
      if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `FRIENDS` = '1' LIMIT 1", [$frend['MY_ID']]) == 1){
        
        db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?)", [$frend['MY_ID'], user('ID'), TM, 'friends_success']);
        
      }
    
    }
    
  }
  
  /*
  -----------------
  Отклонение заявки
  -----------------
  */
  
  if (get('friends_no')){
    
    //db_filter();
    get_check_valid();
    $fn_id = intval(get('friends_no'));
    
    //Определение данных
    $frend = db::get_string("SELECT `ID` FROM `FRIENDS` WHERE (`MY_ID` = ? AND `USER_ID` = ? OR `MY_ID` = ? AND `USER_ID` = ?) AND `ACT` = '1' LIMIT 1", [user('ID'), $fn_id, $fn_id, user('ID')]);
    
    if (isset($frend['ID'])){ 
      
      db::get_set("DELETE FROM `FRIENDS` WHERE (`USER_ID` = ? AND `MY_ID` = ? OR `MY_ID` = ? AND `USER_ID` = ?) AND `ACT` = '1' LIMIT 2", [$fn_id, user('ID'), $fn_id, user('ID')]);
      
      if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `FRIENDS` = '1' LIMIT 1", [$fn_id]) == 1){
        
        db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?)", [$fn_id, user('ID'), TM, 'friends_no']);
        
      }
    
    }
  
  }

}