<?php
  
//Обновление о посещении гостей при переходах по страницам
if (user('ID') == 0){
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `GUESTS` WHERE `IP` = ? LIMIT 1", [IP]) == 0){
    
    db::get_add("INSERT INTO `GUESTS` (`IP`, `BROWSER`, `DATE_VISIT`, `DATE_VISIT_TIME`, `TRANSITIONS`, `DATE_CREATE`) VALUES (?, ?, ?, ?, ?, ?)", [IP, BROWSER, TM, (TM + 15), 1, TM]);
    
  }else{
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `GUESTS` WHERE `IP` = ? AND `DATE_VISIT_TIME` < ? LIMIT 1", [IP, TM]) == 1){
      
      $guests = db::get_string("SELECT `TRANSITIONS` FROM `GUESTS` WHERE `IP` = ? LIMIT 1", [IP]);
      
      db::get_set("UPDATE `GUESTS` SET `DATE_VISIT` = ?, `DATE_VISIT_TIME` = ?, `TRANSITIONS` = ? WHERE `IP` = ? AND `BROWSER` = ? LIMIT 1", [TM, (TM + 15), ($guests['TRANSITIONS'] + 1), IP, BROWSER]);
      
    }
    
  }
  
}