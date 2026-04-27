<?php
  
/*
-------------------------------
Класс для работы с ZIP архивами
-------------------------------
*/
  
class zip {
    
  /*
  ---------------------
  Распаковка ZIP архива
  ---------------------
  */
  
  public static function unpack($path_file, $path){
    
    $zip = new ZipArchive;
    
    if ($zip->open($path_file) === true){
      
      $zip->extractTo($path);
      $zip->close();
      
      return 1;
      
    }else{
      
      return 0;
    
    }
    
  }
  
  /*
  -------------------------------
  Переименовать файл в ZIP архиве
  -------------------------------
  */
  
  public static function rename_file($path_zip_file, $path_file, $path_file_rename){
    
    $zip = new ZipArchive;
    
    if ($zip->open($path_zip_file) === true){
      
      $zip->renameName($path_file, $path_file_rename);
      
      $zip->close();
        
      return 1;
    
    }else{
      
      return 0;
    
    }
    
  }

}