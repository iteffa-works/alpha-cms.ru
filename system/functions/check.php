<?php
  
/*
----------------------
Фильтрация HTML цветов
----------------------
*/
  
function color_filter($color) {
  
  $array = array('#7DFFFF', '#241E1E', '#486CF0', '#78BEFF', '#11D1BB', '#C0C6D1', '#2F2E33', '#F4FF7D', '#887DFF', '#FF7DFB', '#45FF83', '#222926', '#FFFE00', '#FF00F6', '#0900FF', '#00E2FF', '#0067FF', '#546E7A', '#FF7900', '#FFE500', '#00FF0A', '#DA00FF', '#3BFFE7', '#FFEB3B', '#FF5F53', '#2196F3', '#FF00F6', '#FFC669', '#00E409', '#FFFE31', '#FFCD31', '#FF317C', '#9B31FF', '#31FFEE', '#97A6B0', '#FF00F6', '#6A1B9A', '#C62828', '#EF6C00', '#F9A825', '#FFAB91', '#2E7D32', '#2196F3', '#00BCD4', '#47E64D', '#55E7FA', '#FDED5C', '#FFAB91', '#F094FF', '#000000', '#3A474C', '#97A6B0', '#804F29', '#62DD9D', '#FF3B2C', '#FF2CE3', '#4448FF', '#F5FF44', '#00FF1D', '#9F00FF');
  
  if (array_search($color, $array) !== false) { return true; }
  return false;
  
}

/*
------------------------------------
Проверка находится ли пользователь в 
панели управления
------------------------------------
*/
  
function is_panel() {
  
  if (get('base') == 'panel') { return true; } 
  return false;

}
  
/*
-----------------------------------
Проверка существует ли пользователь
-----------------------------------
*/
  
function is_user($id = 0, $type = 0) {
  
  global $account;
  
  //Если $id = 0, то управление передается в переменную $account с массивом строк из базы данных
  if ($id == 0 && $type == 0 && !isset($account['ID'])) {
    
    error('Такого пользователя не существует');
    redirect('/');
    
  }
  
  if ($id == 0 && $type == 1 && !isset($account['ID'])) { 
    
    return false; 
  
  }
  
  //Если $id > 0, то управление передается базе данных для проверки существования пользователя
  if ($id != 0 && $type == 0) {
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `USERS` WHERE `ID` = ? LIMIT 1", [$id]) == 0) {
      
      error('Такого пользователя не существует');
      redirect('/');
      
    }
    
  }
  
  if ($type == 1) {
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `USERS` WHERE `ID` = ? LIMIT 1", [$id]) == 0) {
      
      return false;
      
    }
    
  }
  
  return true;

}
  
/*
------------------------------------
Проверка является ли модуль активным
------------------------------------
*/
  
function is_active_module($data, $type = 0){
  
  if (!@config($data)) {
    
    error('Заданы неверные параметры. Обратитесь к администрации');
    redirect('/');
    
  }
  
  if (config($data) == 0) {
    
    if ($type == 0) {
      
      error('Модуль отключен администратором');
      redirect('/');
      
    }else{
      
      return false;
      
    }
    
  }
  
  return true;

}
  
/*
--------------------------------------------
Абсолютный текст с фильтрацией без бб-кодов,
смайлов и т.п.
--------------------------------------------
*/
  
function tabs($text){
  
  return stripslashes(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));

}

/*
--------------------------------
Замена символов и удаление тегов
--------------------------------
*/

function tprcs($text){
  
  $special_chars = array('php', 'cgi', 'pl', 'js', 'html', 'py', 'htaccess', '?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', chr(0));
  
  $text = preg_replace("#\x{00a0}#siu", ' ', $text);
  $text = str_replace($special_chars, '', $text);
  $text = str_replace(array('%20', '+' ), '-', $text);
  $text = preg_replace('/[\r\n\t -]+/', '-', $text);
  $text = trim($text, '.-_');	
  
  return htmlspecialchars(strip_tags($text));
  
}

/*
---------------------------------------------
Полное удаление символов из текста без замены
---------------------------------------------
*/

function clearspecialchars($text) {
  
  $special_chars = array('?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', chr(0));
  
  $text = preg_replace("#\x{00a0}#siu", ' ', $text);
  $text = str_replace($special_chars, '', $text);
  $text = str_replace(array('%20', '+'), '', $text);
  $text = trim($text, '.-_');	
  
  return htmlspecialchars($text);
  
}

/*
--------------------------------------
Фильтрация текста перед записью в базу
данных
--------------------------------------
*/

function esc($text){

  return addslashes($text);

}

/*
----------------------------------------------------
Модификация REQUEST_URI с поиском нужного элемента в 
адресной строке
Нужен для страниц работающих по типу controller.php
в /index.php
----------------------------------------------------
*/

function url_request_validate($data){
  
  if (strpos(REQUEST_URI, $data) !== false){
    
    return true;
    
  }else{
    
    return false;

  }
  
}

/*
--------------------------------
Функция для преобразования URL в
корректный вид
--------------------------------
*/

function url_filter($url, $cyrillic = false) {
  
  $url = filter_var(trim(strip_tags($url)), FILTER_SANITIZE_URL);
  $url = filter_var($url, FILTER_VALIDATE_URL);
  
  if ($url) {
    
    return $url;
    
  }else{
    
    return false;
    
  }

}

/*
--------------------------
Проверка url на валидность
--------------------------
*/

function url_check_validate($data){
  
  //Функция устарела
  
  return url_filter($data, true);
  
}

/*
---------------------------------
Преобразует get конструкцию в url
---------------------------------
*/

function url_request_get($url){
  
  $data = $url;
  
  $result = $data[strlen($data) - 1];
  
  if ($result == '/'){
    
    $result = $url."?";
    
  }else{
    
    $result = $url."&";

  }

  return $result;  
  
}

/*
------------------------
Фильтрация данных cookie
------------------------
*/

function filter_cookie($data){
  
  return filter_input(INPUT_COOKIE, $data, FILTER_SANITIZE_ENCODED);
  
}

/*
--------------
Обрезка текста
--------------
*/

function crop_text($text, $min, $max){
  
  //$text - текст который нужно обрезать
  //$min - обрезка начала текста
  //$max - обрезка конца
  
  if (str($text) <= $max){
    
    return $text;
    
  }else{
    
    return mb_substr($text, $min, $max, 'UTF-8')." ...";
    
  }

}

/*
------------------------------------
Проверка на валидность id и значения 
токена для get запросов
------------------------------------
*/
  
function get_check_valid() {
  
  if (config('CSRF') == 1 && csrf::check_valid('get') == false) {
    
    error('Неверный ключ запроса');
    redirect('/');
  
  }

}

/*
------------------------------------
Проверка на валидность id и значения 
токена для post запросов
------------------------------------
*/

function post_check_valid() {
  
  if (config('CSRF') == 1 && csrf::check_valid('post') == false) {
    
    error('Неверный ключ запроса');
    redirect('/');
  
  }

}

/*
----------------------------------------
Функция фильтрации записей в базу данных
по заданному интервалу
----------------------------------------
*/
  
function db_filter() {
  
  global $author, $account;
  
  if (isset($account['ID'])){
    
    $id = $account['ID'];
    
  }else{
    
    $id = $author;
    
  }
  
  if (user('ID') > 0 && is_file(ROOT.'/modules/block/block_messages.php')){
    
    if (db::get_column("SELECT COUNT(*) FROM `BLOCK_MESSAGES` WHERE `USER_ID` = ? AND (`BAN_TIME` = ? OR `BAN_TIME` > ?) LIMIT 1", [user('ID'), 0, TM]) > 0){

      error('Администрация запретила писать вам сообщения. Смотрите причину в истории блокировок');
      redirect('/m/block/block_messages_list/?id='.user('ID'));
    
    }
    
  }
  
  if (user('ID') > 0 && $id > 0 && is_dir(ROOT.'/users/account/blacklist/')){
    
    if (db::get_column("SELECT COUNT(*) FROM `BLACKLIST` WHERE `USER_ID` = ? AND `BLACK_LIST_ID` = ? AND (`BAN_TIME` = ? OR `BAN_TIME` > ?) LIMIT 1", [$id, user('ID'), 0, TM]) > 0){
      
      error('Данный пользователь добавил Вас в свой черный список');
      redirect(REQUEST_URI);
    
    }
    
    if (db::get_column("SELECT COUNT(*) FROM `BLACKLIST` WHERE `USER_ID` = ? AND `BLACK_LIST_ID` = ? AND (`BAN_TIME` = ? OR `BAN_TIME` > ?) LIMIT 1", [user('ID'), $id, 0, TM]) > 0){
      
      error('Вы не можете написать, так как добавили данного пользователя в черный список');
      redirect(REQUEST_URI);
    
    }
  
  }
  
  $ar = (get('base') == 'panel' ? '/admin/' : '/');
  
  if (user('ID') > 0){
    
    if (user('DB_FILTER') < TM){
      
      db::get_set("UPDATE `USERS` SET `DB_FILTER` = ? WHERE `ID` = ? LIMIT 1", [(TM + config('DB_INTERVAL')), user('ID')]);
      
    }else{
      
      error('Слишком частое действие. Подождите немного.');
      redirect($ar);
      
    }
    
  }
  
}

/*
--------------------------------------
Проверка данных на наличие запрещенных
символов перед записью в ini файлы
--------------------------------------
*/

function ini_data_check($data){
  
  if (!preg_match("#^([A-zА-я0-9\-\_\.\/\%\+\#\,\:\&\?\!\(\)\"\@\*\[\]\=\;\|\ \p{L}\r\n\—])+$#ui", $data)) {
    
    return 'none';
  
  }else{
    
    return $data;
  
  }

}

/*
--------------------------------
Перевод русских букв в аглийские
--------------------------------
*/

function translit($value){
	
  $converter = array(
    
		'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
		'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
		'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
		'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
		'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
		'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
		'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
 
		'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
		'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
		'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
		'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
		'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
		'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
		'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
    
  );
  
  $value = strtr($value, $converter);
  
  return $value;
  
}

/*
--------------------------
Вывод текста с настройками
--------------------------
*/
  
function text($text, $br = 1, $smiles = 1, $bb = 1, $link = 1) {
  
  //$text - выводимый текст
  //$smiles - вставка смайлов
  //$br - перенос строк
  //$bb - вывод bb кодов
  //$link - обработка и вывод ссылок
  
  $text = stripslashes(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
  $text = smiles($text, $smiles);
  if ($br == 1) { $text = nl2br($text); }
  $text = bb_code($text, $bb);
  $text = links($text, $link);
  
  return $text;

}