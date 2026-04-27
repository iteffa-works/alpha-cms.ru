<?php
  
/*
------------------------
Вход с чужого устройства
------------------------
*/
  
$hash_out = abs(intval(session('HASH_OUT')));

if ($hash_out != 0 && $hash_out < TM) {
  
  session('HASH_OUT', null);
  session('HASH', null);
  session('salt', null);
  
}
  
/*
-----------------------
COOKIE + SESSION + SALT
-----------------------
*/
  
if (config('AUT_MODE') == 0) {
  
  if (!session('salt')) {
    
    if (!empty(cookie('USER_ID')) && !empty(cookie('PASSWORD'))) {

      session('salt', base64_encode(cookie('USER_ID').','.cookie('PASSWORD')));
      redirect(REQUEST_URI);
      
    }
    
  }
  
  $ex_user = explode(',', base64_decode(session('salt')));
  $user_id = (isset($ex_user[0]) ? intval(user_deshif($ex_user[0])) : 0);
  $user_pass = (isset($ex_user[1]) ? esc(shif(cdecrypt($ex_user[1]))) : null);
  
  $us_data = db::get_string("SELECT * FROM `USERS` WHERE `ID` = ? AND `PASSWORD` = ? LIMIT 1", [$user_id, $user_pass]);
  
}

/*
-------------------
IP + SESSION + HASH
-------------------
*/

if (config('AUT_MODE') == 1) {
  
  $us_data = db::get_string("SELECT * FROM `USERS` WHERE `IP` = ? AND `HASH` = ? LIMIT 1", [IP, esc(session('HASH'))]);
  
}

/*
-----------------------------
IP + BROWSER + SESSION + HASH
-----------------------------
*/

if (config('AUT_MODE') == 2) {
  
  $us_data = db::get_string("SELECT * FROM `USERS` WHERE `IP` = ? AND `BROWSER` = ? AND `HASH` = ? LIMIT 1", [IP, BROWSER, esc(session('HASH'))]);
  
}

function user($data) {
  
  global $us_data;
  
  if (isset($us_data['ID'])) {
    
    return tabs($us_data[$data]);
  
  }
  
  return 0;

}

require (ROOT.'/system/connections/array_to_function.php');
require (ROOT.'/system/connections/timezone.php');
require (ROOT.'/system/connections/access.php');

/*
---------------
Доступ в панель
---------------
*/

define('MANAGEMENT', (user('ID') > 0 && user('MANAGEMENT') == 1 ? 1 : 0));

/*
------------------------------
Количество пунктов на страницу
------------------------------
*/

define('PAGE_SETTINGS', (user('ID') > 0 ? intval(settings('STR')) : intval(config('STR_GUESTS'))));

/*
------------------
Подгрузка плагинов
------------------
*/

direct::components(ROOT.'/system/connections/global/users/', 0);