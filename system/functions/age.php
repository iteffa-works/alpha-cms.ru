<?php

/*
--------------------------------------------------
Oпределение возраста пользователя по дате рождения
--------------------------------------------------
*/
  
function age($u, $y, $m, $d) { 
  
  //$u - юзер 
  //$y - год 
  //$m - месяц 
  //$d - день
  
  //Oпределяем настройки пользователя
  $user = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$u]);
  
  if (str($user['D_R']) > 0 && str($user['M_R']) > 0 && str($user['G_R']) > 0){
    
    if ($m > date('m') || $m == date('m') && $d > date('d')){
      
      return (date('Y') - $y - 1);
      
      $int = date('Y') - $y - 1;
    
    }else{
      
      return (date('Y') - $y);
      
      $int = date('Y') - $y;
    
    }
  
  } 

}

/*
-----------------------------------------
Функция вычисления наименования возраста. 
Например: год, года, лет
-----------------------------------------
*/

function _age($user, $int, $expressions) {    
  
  //Oпределяем настройки пользователя
  $user = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", $user);
  
  if (str($user['D_R']) > 0 && str($user['M_R']) > 0 && str($user['G_R']) > 0){
    
    if (count($expressions) < 3) {
      
      $expressions[2] = $expressions[1];
    
    }
    
    $count = $int % 100;
    
    if ($count >= 5 && $count <= 20) {
      
      $result = 2;
    
    }else{
      
      $count = $count % 10;
    
    }
    
    if ($count == 1) {
      
      $result = 0;
    
    }elseif ($count >= 2 && $count <= 4) {
      
      $result = 1;
    
    }else{
      
      $result = 2;
    
    }
    
    return $int . ' ' . $expressions[$result];
  
  }

}