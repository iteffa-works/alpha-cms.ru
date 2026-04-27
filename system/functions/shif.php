<?php
  
/*
----------------
Функция шифровки
----------------
*/
  
function shif($str){
  
  $key = config('SHIF');
  $str1 = md5((string)$str);
  $str2 = md5($key);
  
  return md5($key.$str1.$str2.$key);
  
}