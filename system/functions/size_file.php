<?php
  
/*
----------------------------------------
Функция обработки и вывода размера файла
----------------------------------------
*/
  
function size_file($bytes) {
  
  if ($bytes >= 1073741824) {
    
    $bytes = number_format($bytes / 1073741824, 2) . ' '.lg('Гб');
  
  }elseif ($bytes >= 1048576) {
    
    $bytes = number_format($bytes / 1048576, 2) . ' '.lg('Мб');
  
  }elseif ($bytes >= 1024) {
    
    $bytes = number_format($bytes / 1024, 2) . ' '.lg('Кб');
  
  }elseif ($bytes > 1) {
    
    $bytes = $bytes . ' '.lg('байты');
  
  }elseif ($bytes == 1) {
    
    $bytes = $bytes . ' '.lg('байт');
  
  }else{
    
    $bytes = '0 '.lg('байтов');
  
  }
  
  return $bytes;

}