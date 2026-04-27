<?php
  
/*
-------------------------------
Функция записи входов в аккаунт
-------------------------------
*/
  
function visits($user_id, $msg = 1) {
  
  //$user_id - ID аккаунта
  //$msg - отправка письма пользователю о новом входе в аккаунт, если 1
  
  $location = @unserialize(file_get_contents('http://ip-api.com/php/'.IP.'?lang=ru'));
  $city = db::get_string("SELECT `NAME`,`ID_COUNTRY` FROM `CITY` WHERE `NAME` = ? LIMIT 1", [(isset($location['city']) ? esc($location['city']) : null)]);
  $country = db::get_string("SELECT `NAME` FROM `COUNTRY` WHERE `ID` = ? LIMIT 1", [esc($city['ID_COUNTRY'])]);
  $ct = 'Москва';
  $cn = 'Россия';
  
  if (str($city['NAME']) > 0 && str($country['NAME']) > 0) {
    
    $ct = tabs($city['NAME']);
    $cn = tabs($country['NAME']);
  
  }
  
  $location = $ct.', '.$cn;
  
  db::get_add("INSERT INTO `USERS_VISITS` (`USER_ID`, `BROWSER`, `IP`, `LOCATION`, `TIME`) VALUES (?, ?, ?, ?, ?)", [$user_id, BROWSER, IP, $location, TM]);
  
  if ($msg == 1) {
    
    $mess = lg('
    Выполнен вход в ваш аккаунт.
    
    [b]Время:[/b] %s
    [b]Устройство и браузер:[/b] %s
    [b]IP:[/b] %s
    [b]Местоположение:[/b] %s
    
    Если это были не вы, то примите меры по защите аккаунта в [url=/account/settings/password/]настройках[/url].
    ', ftime(TM), BROWSER, IP, $location);
    messages::get(config('SYSTEM'), $user_id, $mess);
    
  }
  
}