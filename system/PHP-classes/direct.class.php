<?php 

/*
---------------------------------------------
Класс управления вызовами файлов и директорий
---------------------------------------------
*/
  
class direct{
  
  /*
  ------------------------
  Фильтрация данных из GET
  ------------------------
  */
  
  public static function get($get_name) {
    
    //Фильтруем входные данные из гет параметра
    $filter = filter_input(INPUT_GET, $get_name, FILTER_SANITIZE_ENCODED);
    
    //Вырезаем мусорные спецсимволы, возвращаемые FILTER_SANITIZE_ENCODED
    $get = clearspecialchars($filter);
    
    if (str($get) > 0){
      
      $get_data = $get;
      
    }else{
      
      $get_data = 'no_data';
      
    }
    
    return $get_data;
    
  }
  
  /*
  -------------------------------------------
  Проверка на существование вызываемого файла
  -------------------------------------------
  */
  
  public static function e_file($path) {
    
    if (is_file(ROOT.'/'.$path)){
      
      return true;
      
    }else{
      
      return false;
      
    }
    
  }
  
  /*
  -----------------------------------------------
  Проверка на существование вызываемой директории
  -----------------------------------------------
  */
  
  public static function e_dir($path) {
    
    if (is_dir(ROOT.'/'.$path)){
      
      return true;
      
    }else{
      
      return false;
      
    }
    
  }
  
  /*
  -----------------------------------
  Функция вывода компонентов из папки
  -----------------------------------
  */
  
  public static function components($path, $count = 1, $limit = 999999, $ext = 'php') {
    
    global $account, $settings, $comm, $par, $list;
    
    $result = scandir($path, SCANDIR_SORT_ASCENDING);
    
    $s = 0;    
    for ($i = 0; $i < count($result); $i++){
      
      if (preg_match('#\.'.$ext.'$#i',$result[$i])){
        
        $s++;
        
        if ($s >= $limit) {
          
          break;
        
        }
        
        require ($path.$result[$i]);
      
      }
    
    }
    
    if ($s == 0 && $count == 1){
      
      ?>
      <div class='list-menu'>
      <?=lg('Пока пусто')?>
      </div>
      <?
    
    }
    
  }
  
}