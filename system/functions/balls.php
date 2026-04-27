<?php

/*
---------------------------------------
Функция начисления баллов за активность
---------------------------------------
*/
  
function balls_add($data, $id = 0){
  
  $balls = @parse_ini_file(ROOT."/system/config/balls.ini", false);
  
  if ($id == 0) {
    
    db::get_set("UPDATE `USERS` SET `BALLS` = `BALLS` + ? WHERE `ID` = ? LIMIT 1", [$balls[$data], user('ID')]);
    
  }else{
    
    db::get_set("UPDATE `USERS` SET `BALLS` = `BALLS` - ? WHERE `ID` = ? AND `BALLS` >= '0' LIMIT 1", [$balls[$data], $id]);
    
  }
  
}