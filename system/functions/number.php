<?php
  
/*
----------------------------
Функция формата вывода чисел
----------------------------
*/ 
  
function num_format($number, $format){
  
  //$number - число
  //$format - формат вывода. 1 - "1 234,56", 2 - "1,235", 3 - "1234.57"
  
  if ($format == 1){
    
    return number_format($number, 2, ',', ' ');
    
  }
  
  if ($format == 2){
    
    return number_format($number);
    
  }
  
  if ($format == 3){
    
    return number_format($number, 2, '.', '');
    
  }
  
}
  
/*
----------------------------------
Функция склонения слов после чисел
----------------------------------
*/

function num_decline($number, $titles, $show_number = 0){
  
  //$number - число
  //$titles - варианты склонения
  //$show_number - вывод склонения вместе с числом если 1, если 0 - то нет
  
  if (is_string($titles)) {
    
    $titles = preg_split('/, */', $titles);
    
  }
  
  // когда указано 2 элемента
  if (empty($titles[2])) {
    
    $titles[2] = $titles[1];
    
  }
  
  $cases = [2, 0, 1, 1, 1, 2];
  $intnum = abs((int)strip_tags($number));
  
  $title_index = ($intnum % 100 > 4 && $intnum % 100 < 20) ? 2 : $cases[min($intnum % 10, 5)];
  
  if ($show_number == 1){
    
    $show_number = $number.' ';
    
  }else{
    
    $show_number = null;

  }
  
  return $show_number.lg($titles[$title_index]);

}