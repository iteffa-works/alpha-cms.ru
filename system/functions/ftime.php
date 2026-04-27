<?php
  
/*
---------------------------
Функция отображения времени
---------------------------
*/
  
function ftime($time) {
  
  if ($time == null) { 
    
    $time = TM; 
  
  }
  
  $timed = "j M Y, H:i";
  $timep = date($timed, $time);
  $time_p[0] = date("j n Y", $time);
  $time_p[1] = date("H:i", $time);
  
  if ($time_p[0] == date("j n Y")) { 
    
    $timep = date("H:i", $time); 
  
  }
  
  if (user('ID') > 0) {
    
    if ($time_p[0] == date("j n Y", TM + 60 * 60)) { 
      
      $timep = date("H:i", $time); 
    
    }
    
    if ($time_p[0] == date("j n Y", TM - 60 * 60 * (24))) { 
      
      $timep = lg('Вчера в')." ".$time_p[1]; 
    
    }
  
  }else{
    
    if ($time_p[0] == date("j n Y")) { 
      
      $timep = date("H:i", $time); 
    
    }
    
    if ($time_p[0] == date("j n Y", TM - 60 * 60 * 24)) {
      
      $timep = lg('Вчера в')." ".$time_p[1]; 
      
    }
  
  }

  $timep = str_replace("Jan", lg('Янв'), $timep);
  $timep = str_replace("Feb", lg('Фев'), $timep);
  $timep = str_replace("Mar", lg('Мар'), $timep);
  $timep = str_replace("May", lg('Мая'), $timep);
  $timep = str_replace("Apr", lg('Апр'), $timep);
  $timep = str_replace("Jun", lg('Июн'), $timep);
  $timep = str_replace("Jul", lg('Июл'), $timep);
  $timep = str_replace("Aug", lg('Авг'), $timep);
  $timep = str_replace("Sep", lg('Сен'), $timep);
  $timep = str_replace("Oct", lg('Окт'), $timep);
  $timep = str_replace("Nov", lg('Ноя'), $timep);
  $timep = str_replace("Dec", lg('Дек'), $timep);
  
  return $timep;

}

function stime($times) {
  
  $lama = round((TM - $times) / 60);
  
  if ($lama < 1) {
    
    $lama = lg("только что");
  
  }
  
  if ($lama >= 1 && $lama < 60) {
    
    $lama = $lama." ".lg('м. назад');
  
  }
  
  if ($lama >= 60 && $lama < 1440) {
    
    $lama = round($lama / 60);
    $lama = $lama." ".lg('ч. назад');
  
  }
  
  if ($lama >= 1440) {
    
    $lama = round($lama / 60 / 24);
    $lama = $lama." ".lg('д. назад')." (".ftime($times).")";
  
  }
  
  return $lama;

}

/*
---------------------------------
Функция обратного отсчета времени
---------------------------------
*/
 
function otime($timediff){
  
  $oneMinute = 60;
  $oneHour = 60 * 60;
  $oneDay = 60 * 60 * 24;
  $dayfield = floor($timediff / $oneDay);
  $hourfield = floor(($timediff - $dayfield * $oneDay) / $oneHour);
  $minutefield = floor(($timediff - $dayfield * $oneDay - $hourfield * $oneHour) / $oneMinute);
  $secondfield = floor(($timediff - $dayfield * $oneDay - $hourfield * $oneHour - $minutefield * $oneMinute));
  
  $time = null;
  
  if ($hourfield != 0) { $time .= $hourfield." ".lg('ч.')." "; }
  if ($minutefield != 0) { $time .= $minutefield." ".lg('м.')." "; }
  if ($secondfield != 0) { $time .= $secondfield." ".lg('с.')." "; }
  
  return $time;

}