<?php
  
/*
-----------------------------
Выгрузка и хранение файлов на 
привязанных дисках
-----------------------------
*/
  
function fd_upload($path, $type) {
  
  //$path - путь к файлу
  //$type - тип файла
  
  //Появится и заработает в будущих версиях
  
}

function fd_download($id, $type, $ext, $data, $path, $shif) {
  
  //$id - ID файла
  //$type - тип файла
  //$ext - расширение файла
  //$data - данные для корректировки отдачи файла
  //$path - место хранения
  //$shif - шифрованное название файла

  $file = 'file_not_found';
  
  /*
  ----------
  Фотографии
  ----------
  */
  
  if ($type == 'photos') {
    
    if ($data == 'source') {
      
      $file = '/files/upload/photos/source/'.$shif.'.'.$ext;
    
    }
    
    if ($data == '50x50') {
      
      $file = '/files/upload/photos/50x50/'.$shif.'.'.$ext;
    
    }
    
    if ($data == '240x240') {
      
      $file = '/files/upload/photos/240x240/'.$shif.'.'.$ext;
    
    }
    
    if ($data == '150x150') {
      
      $file = '/files/upload/photos/150x150/'.$shif.'.'.$ext;
    
    }
    
    if ($data == '260x600') {
      
      $file = '/files/upload/photos/260x600/'.$shif.'.'.$ext;
    
    }
  
  }
  
  /*
  -----------
  Видеоролики
  -----------
  */
  
  if ($type == 'videos') {
    
    if ($data == 'source') {
      
      $file = '/files/upload/videos/source/'.$id.'.'.$ext;
      
    }
      
    if ($data == 'screen') {
        
      $file = '/files/upload/videos/screen/'.$id.'.'.$ext;
      
    }
      
    if ($data == 'screen_240x240') {
        
      $file = '/files/upload/videos/screen/240x240/'.$id.'.'.$ext;
      
    }
      
  }
    
  /*
  ------
  Музыка
  ------
  */
  
  if ($type == 'music') {
    
    if ($data == 'source') {
      
      $file = '/files/upload/music/source/'.$id.'.'.$ext;
      
    }
      
    if ($data == 'screen') {
        
      $file = '/files/upload/music/screen/'.$id.'.'.$ext;
      
    }
      
    if ($data == 'screen_240x240') {
        
      $file = '/files/upload/music/screen/240x240/'.$id.'.'.$ext;
      
    }
    
    if ($data == 'screen_120x120') {
        
      $file = '/files/upload/music/screen/120x120/'.$id.'.'.$ext;
      
    }
      
  }
  
  /*
  -----
  Файлы
  -----
  */
  
  if ($type == 'files') {
    
    if ($data == 'source') {
      
      $file = '/files/upload/files/source/'.$id.'.'.$ext;
      
    }
      
    if ($data == 'screen') {
        
      $file = '/files/upload/files/screen/source/'.$id.'.'.$ext;
      
    }
      
  }
  
  if (is_file(ROOT.$file)) {
    
    return $file;
    
  }
  
  return 'file_not_found';
  
}