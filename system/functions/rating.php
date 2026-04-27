<?php

/*
---------------------------
Функция начисления рейтинга
---------------------------
*/
  
function rating_add($data, $id = 0){
  
  $rating = @parse_ini_file(ROOT."/system/config/rating.ini", false);
  
  if ($id == 0) {
    
    db::get_set("UPDATE `USERS` SET `RATING` = `RATING` + ? WHERE `ID` = ? LIMIT 1", [$rating[$data], user('ID')]);
    
  }else{
    
    db::get_set("UPDATE `USERS` SET `RATING` = `RATING` - ? WHERE `ID` = ? AND `RATING` >= '0' LIMIT 1", [$rating[$data], $id]);
    
  }
  
}