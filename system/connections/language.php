<?php

/*
-----------------------------------
Текущий язык сайта для пользователя
-----------------------------------
*/
  
if ('RU' == filter_cookie('LANGUAGE') || db::get_column("SELECT COUNT(*) FROM `LANGUAGES` WHERE `FACT_NAME` = ? AND `ACT` = '1' LIMIT 1", [esc(filter_cookie('LANGUAGE'))]) > 0){
  
  define('LANGUAGE', filter_cookie('LANGUAGE'));

}else{
  
  define('LANGUAGE', config('LANGUAGE'));

}