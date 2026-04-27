<?php

/*
---------------------
Функция перевода фраз
---------------------
*/

if (LANGUAGE != 'RU') {
  
  $lang_array = array();
  $lang_show = db::get_string_all("SELECT `PHRASE`,`TRANSLATE` FROM `LANGUAGES_SHOW` WHERE `TYPE` = ?", [LANGUAGE]);
  while ($lang_list = $lang_show->fetch()) {
    
    $phrase = mb_strtolower(stripslashes($lang_list['PHRASE']), 'UTF-8');
    $lang_array[$phrase] = array($lang_list['TRANSLATE']);
  
  }
    
}

function lg($text = null, ...$params) {
  
  if ($params == null){
    
    return lg::show($text);
    
  }else{
    
    return vsprintf(lg::show($text), $params);
    
  }
  
}