<?php

/*
-----------------------------------------
Функция уникального хеша для пользователя
-----------------------------------------
*/
  
function user_hash($id){
  
  //$id - id пользователя
  
  $rand = rand(00000,99999);
  $hash = $id."_".$rand;
  
  return md5($hash);
  
}

function user_shif($param) {
  
  $v = str_split($param, 1);
  $s = null;
  
  foreach ($v as $d) {

    if ($d == 0){ $s .= 'Gy7sQa98v'; }
    if ($d == 1){ $s .= 'hT6Eq5vcc'; }
    if ($d == 2){ $s .= 'aHjAAq10B'; }
    if ($d == 3){ $s .= '6QWLz9gFP'; }
    if ($d == 4){ $s .= '6Sk49cBMN'; }
    if ($d == 5){ $s .= 'HtpCoiY57'; }
    if ($d == 6){ $s .= 'Z9qzRQ11J'; }
    if ($d == 7){ $s .= 'VvzrUTY70'; }
    if ($d == 8){ $s .= '9HrINB91a'; }
    if ($d == 9){ $s .= 'Tkjhy8mnB'; }
    
  }
  
  return base64_encode(base64_encode(base64_encode($s)));
  
}

function user_deshif($key) {
  
  $v = str_split(base64_decode(base64_decode(base64_decode($key))), 9);
  $n = null;

  foreach ($v as $d) {
   
    if ($d == 'Gy7sQa98v'){ $n .= 0; }
    elseif ($d == 'hT6Eq5vcc'){ $n .= 1; }
    elseif ($d == 'aHjAAq10B'){ $n .= 2; }
    elseif ($d == '6QWLz9gFP'){ $n .= 3; }
    elseif ($d == '6Sk49cBMN'){ $n .= 4; }
    elseif ($d == 'HtpCoiY57'){ $n .= 5; }
    elseif ($d == 'Z9qzRQ11J'){ $n .= 6; }
    elseif ($d == 'VvzrUTY70'){ $n .= 7; }
    elseif ($d == '9HrINB91a'){ $n .= 8; }
    elseif ($d == 'Tkjhy8mnB'){ $n .= 9; }
    
  }
  
  return $n;
  
}

/*
----------------------------------------------
Функция хеша для подключенных к сайту открытых 
файлов
----------------------------------------------
*/

function front_hash() {
  
  $fh = config('ACMS_VERSION').'_'.config('FRONT_HASH');
  if (config('DEVELOPER') == 1) { $fh = config('ACMS_VERSION').'_'.TM; }
  
  return $fh;
  
}