<?php

/*
----------------------------------
Функция записи логов администрации
----------------------------------
*/
  
function logs($title, $id){
  
  db::get_add("INSERT INTO `PANEL_LOGS` (`NAME`, `USER_ID`, `TIME`) VALUES (?, ?, ?)", [esc($title), user('ID'), TM]);
  
}