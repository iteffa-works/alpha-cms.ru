<?php
  
/*
---------------------------------------
Класс для работы с функциями через хуки
---------------------------------------
*/
  
class hooks {
  
  //массив с хуками
  private static $hooks = array();
  
  //вызов функции через его имя
  public static function challenge($name, $function) {
    
    if (is_file(ROOT.'/system/functions/hooks/'.$function.'.php')) {
      
      require_once (ROOT.'/system/functions/hooks/'.$function.'.php');
      self::$hooks[$name][] = $function;
      
    }
  
  }
  
  //запуск функций через определенный хук
  public static function run($name, $variable = false) {
    
    if (isset(self::$hooks[$name])) {
      
      foreach (self::$hooks[$name] as $f) {
        
        if ($variable != false) {
          
          call_user_func_array($f, array($variable));
        
        }else{
          
          call_user_func($f);
        
        }
      
      }
    
    }
  
  }

}