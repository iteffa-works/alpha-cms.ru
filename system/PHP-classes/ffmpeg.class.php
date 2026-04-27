<?php 

/*
--------------------------------------------------
Класс для работы с видеофайлами
Работает автономно без установки расширения ffmpeg 
на сервер, если он не установлен
--------------------------------------------------
*/
  
class ffmpeg{
  
  /*
  -------------------
  Получение скриншота
  -------------------
  */
  
  public static function screen($path_video, $path_screen, $duration = "00:00:05") {
    
    //$path_video - путь к видеофайлу
    //$path_screen - путь куда сохранять скриншот
    //$duration - момент кадра который нужно скринить / формат 00(час):00(минута):00(секунда)
    
    //Сначала пытаемся подключиться к ffmpeg если он установлен на сервер
    if (class_exists('ffmpeg_movie')){
      
      //Получаем номер кадра для скриншота
      $frame = 25;
      
      //Путь до видео
      $movie = new ffmpeg_movie($path_video);
      
      $ff_frame = $movie->getFrame($frame);
      
      //Если такой кадр есть
      if ($ff_frame) {

        $gd_image = $ff_frame->toGDImage();
        
        if ($gd_image) {
          
          imagepng($gd_image, $path_screen);
          imagedestroy($gd_image);
        
        }
      
      }
      
    }else{
      
      exec('ffmpeg -i '.$path_video.' -ss '.$duration.' -vframes 1 '.$path_screen);
      
    }
    
  }
  
  /*
  ----------------------
  Получение длительности
  ----------------------
  */
  
  public static function duration($path_video) {
    
    //$path_video - путь к видеофайлу
    
    //Сначала пытаемся подключиться к ffmpeg если он установлен на сервер
    if (class_exists('ffmpeg_movie')){
      
      //Путь до видео
      $movie = new ffmpeg_movie($path_video);
      
      //Получаем длительность видео в секундах
      $sec = $movie->getDuration();
      
      $duration = gmdate("H:i:s", $sec);
      
      return $duration;
      
    }else{
      
      $EX = exec("ffmpeg -i ".$path_video." 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");      
      $duration = mb_substr($EX, 0, 8, 'UTF-8');      
      
      return $duration;
    
    }
    
  }
  
}