<?php
  
/*
--------------------------------------
Класс для защиты методов GET и POST от
подделок запросов
--------------------------------------
*/
  
class csrf{
  
  //Генерация id токена из сессионной переменной
  public static function token_id(){
    
    if (session('token_id')) { 
      
      return session('token_id');
    
    }else{
      
      $token_id = csrf::random(10);
      session('token_id', $token_id);      
      return $token_id;
    
    }
  
  }
  
  //Значение токена
  public static function token() {
    
    if (session('token_value')) {
      
      return session('token_value'); 
    
    }else{
      
      $token = hash('sha256', csrf::random(500));
      session('token_value', $token);      
      return $token;
    
    }
  
  }
  
  //Проверка на валидность id и значение токена
  public static function check_valid($method) {
    
    if ($method == 'post' || $method == 'get') {
      
      $post = $_POST;
      $get = $_GET;
      
      if (isset(${$method}[csrf::token_id()]) && ${$method}[csrf::token_id()] == csrf::token()) {
        
        return true;
      
      }else{
        
        return false;	
      
      }
    
    }else{
      
      return false;	
    
    }
  
  }
  
  //Генерирация случайной строки  
  public static function random($len){
    
    if (function_exists('openssl_random_pseudo_bytes')) {
      
      $byteLen = intval(($len / 2) + 1);
      $return = substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $len);
    
    }elseif (@is_readable('/dev/urandom')) {
      
      $f = fopen('/dev/urandom', 'r');
      $urandom = fread($f, $len);
      fclose($f);
      
      $return = null;
    
    }
    
    if (empty($return)) {
      
      for ($i = 0; $i < $len; ++$i) {
        
        if (!isset($urandom)) {
          
          if ($i % 2 == 0) {
            
            mt_rand(TM % 2147 * 1000000 + (double)microtime() * 1000000);
          
          }
          
          $rand = 48 + mt_rand() % 64;
        
        }else{
          
          $rand = 48 + ord($urandom[$i]) % 64;
        
        }
        
        if ($rand > 57){
          
          $rand += 7;
          
        }
        
        if ($rand > 90){
          
          $rand += 6;
          
        }
        
        if ($rand == 123){
          
          $rand = 52;
          
        }
        
        if ($rand == 124){
          
          $rand = 53;
          
        }
        
        $return .= chr($rand);
      
      }
    
    }
    
    return $return;
  
  }
  
}