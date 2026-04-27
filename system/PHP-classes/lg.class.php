<?php

/*
----------------------------------
Класс для работы со переводом слов
----------------------------------
*/

class lg{
  
  public static function show($text) {
    
    global $lang_array;
    
    $str = mb_strtolower($text, 'UTF-8');
    $translate = $text;
    
    if (isset($lang_array[$str][0])){
      
      $translate = $lang_array[$str][0];
    
    }
    
    return $translate;
  
  }

}