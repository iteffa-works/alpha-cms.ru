<?php

/*
------------------------------------
Функции шифровки и дешифровки данных 
передаваемых в COOKIE
------------------------------------
*/
  
function cencrypt($str, $id = 0){
  
  if (defined('SALT_COOKIE_USER') && function_exists('mcrypt_module_open')) {
    
    $td = mcrypt_module_open('rijndael-256', '', 'ofb', '');
    $ks = @mcrypt_enc_get_key_size($td);
    $key = substr(md5($id.BROWSER), 0, $ks);
    @mcrypt_generic_init($td, $key, base64_decode(SALT_COOKIE_USER));
    $str = @mcrypt_generic($td, $str);
    @mcrypt_generic_deinit($td);
    @mcrypt_module_close($td);
  
  }
  
  $str = base64_encode($str);
  
  return $str;

}

function cdecrypt($str, $id = 0){
  
  $str = base64_decode($str);
  
  if (defined('SALT_COOKIE_USER') && function_exists('mcrypt_module_open')) {
    
    $td = mcrypt_module_open('rijndael-256', '', 'ofb', '');
    $ks = @mcrypt_enc_get_key_size($td);
    $key = substr(md5($id.BROWSER), 0, $ks);
    @mcrypt_generic_init($td, $key, base64_decode(SALT_COOKIE_USER));
    $str = @mdecrypt_generic($td, $str);
    @mcrypt_generic_deinit($td);
    @mcrypt_module_close($td);
  
  }
  
  return $str;

}