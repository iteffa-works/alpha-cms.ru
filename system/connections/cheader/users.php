<?php

//Обновление о посещении пользователей при переходах по страницам
if (user('ID') > 0 && user('DATE_VISIT_TIME') < TM){
  
  db::get_set("UPDATE `USERS` SET `IP` = ?, `DATE_VISIT` = ?, `DATE_VISIT_TIME` = ?, `VERSION` = ? WHERE `ID` = ? LIMIT 1", [IP, TM, (TM + 15), (type_version() ? "touch" : "web"), user('ID')]);
  
}