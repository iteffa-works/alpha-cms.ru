<?php

/*
-------------------------------
Класс для работы с базой данных
-------------------------------
*/  
  
class db{
  
  //Объект PDO
  public static $DB = null;
  
  public static $ST = null;
  
  //SQL запрос
  public $QUERY = '';
  
  /*
  --------------------------------------
  Подключение драйвера PDO к базе данных
  --------------------------------------
  */
  
  public static function connect($status = 1){
    
    if (!self::$DB) {
      
      try {
        
        self::$DB = new PDO(
          
          'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', 
          DB_USER, 
          DB_PASSWORD,
          array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'")
        
        );
        
        self::$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
      
      } catch (PDOException $e) {
        
        if ($status == 1){
          
          ?>
          Нет подключения к базе данных<br /><br />
          Параметры ошибки:<br /> <?=$e->getMessage()?>
          <hr>
          <?
          
        }
        
      }
      
    }
    
    return self::$DB;
    
  }
  
  /*
  --------------------------------------
  Получение 1 строки из таблицы
  --------------------------------------
  */
  
  public static function get_string($query, $param = array()){
    
    if (self::connect(0)){
      
      self::$ST = self::connect()->prepare($query);
      self::$ST->execute((array) $param);
      
      return self::$ST->fetch(PDO::FETCH_ASSOC);
    
    }
    
  } 
  
  /*
  --------------------------------------
  Получение всех строк из таблицы
  --------------------------------------
  */
  
  public static function get_string_all($query, $param = array()){
    
    if (self::connect(0)){
      
      self::$ST = self::connect()->prepare($query);
      self::$ST->execute((array) $param);
      
      return self::$ST;
      
    }
    
  }
  
  /*
  ------------------------------
  Получение 1 столбца из таблицы
  ------------------------------
  */
  
  public static function get_column($query, $param = array()){
    
    if (self::connect(0)){
      
      self::$ST = self::connect()->prepare($query);
      self::$ST->execute((array) $param);
      
      return self::$ST->fetchColumn();
      
    }
    
  }
  
  /*
  ---------------------------
  Добавление строки в таблицу
  ---------------------------
  */
  
  public static function get_add($query, $param = array()){
    
    if (self::connect(0)){
      
      self::$ST = self::connect()->prepare($query);
      
      return (self::$ST->execute((array) $param)) ? self::connect()->lastInsertId() : 0;
      
    }
    
  }
  
  /*
  -----------------------------------
  Изменение/удаление строки в таблице
  -----------------------------------
  */
  
  public static function get_set($query, $param = array()){
    
    if (self::connect(0)){
      
      self::$ST = self::connect()->prepare($query);
      
      return self::$ST->execute((array) $param);
      
    }
    
  }
  
  /*
  ---------------------------------------------
  Выполнение запроса в базу данных из SQL файла
  ---------------------------------------------
  */
  
  public static function get_sql_file($path_file){

    $file = file_get_contents($path_file);    
    $data = explode(';' , $file);
    
    if ($file){
      
      foreach ($data as $el){
        
        db::get_add($el);
        
      }
      
      return 1;
    
    }else{
      
      return 0;
    
    }
    
  }

}