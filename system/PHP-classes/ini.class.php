<?php 

/*
------------------------------
Класс для работы с ini файлами
------------------------------
*/
  
class ini{
  
  /*
  -----------------
  Получение массива
  -----------------
  */
  
  public static function parse($path){
    
    if (!file_exists($path)) {
      
      throw new Exception('INI файл: ' . $path . ', не существует!');
    
    }
    
    return parse_ini_file($path);
  
  }
  
  /*
  ------------------------
  Обновление данных строки
  ------------------------
  */
  
  public static function upgrade($path, $key, $value) {
    
    if (!file_exists($path)) {
      
      return 0;
    
    }
    
    $array = ini::parse($path);
    $array[$key] = $value;
    $current = "";
    
    foreach ($array as $key => $value) {
      
      $current .= "\n$key = '$value'\n";
    
    }
    
    file_put_contents($path, $current);
    
    return 1;
  
  }
  
  /*
  -----------------
  Добавление строки
  -----------------
  */
  
  public static function add($path, $key, $value) {
    
    if (!file_exists($path)) {
      
      return 0;
    
    }
    
    $array = ini::parse($path);
    $array[$key] = $value;
    $current = "";
    
    foreach ($array as $key => $value) {
      
      $current .= "\n$key = '$value'\n";
    
    }
    
    file_put_contents($path, $current);
    
    return 1;
  
  }

  /*
  ---------------
  Удаление строки
  ---------------
  */
  
  public static function delete($path, $key, $value) {
    
    if (!file_exists($path)) {
      
      return 0;
    
    }
    
    $array = ini::parse($path);
    unset($array[$key]);
    
    foreach ($array as $key => $value) {
      
      $current .= "\n$key = '$value'\n";
    
    }
    
    file_put_contents($path, $current);
    
    return 1;
  
  }
  
}