<?php

/*
-------------------------------
Константы и псевдофункции для 
сокращения переменных и функций
-------------------------------
*/

function remove_script($string = null) {
  
  $string = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F\\x7F]+/S', '', $string);
  $parm1 = array('vbscript', 'expression', 'applet', 'xml', 'blink', 'embed', 'object', 'frameset', 'ilayer', 'layer', 'bgsound');
  $parm2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
  $parm = array_merge($parm1, $parm2);
  
  for ($i = 0; $i < sizeof($parm); $i++) {
    
    $pattern = '/';
    
    for ($j = 0; $j < strlen($parm[$i]); $j++) {
      
      if (0 < $j) {
        
        $pattern .= '(';
        $pattern .= '(&#[x|X]0([9][a][b]);?)?';
        $pattern .= '|(&#0([9][10][13]);?)?';
        $pattern .= ')?';
      
      }
      
      $pattern .= $parm[$i][$j];
    
    }
    
    $pattern .= '/i';
    $string = preg_replace($pattern, ' ', $string);
  
  }
  
  return $string;
  
}
  
function _filter($data) {
  
  return remove_script(addslashes(htmlspecialchars($data)));
  
}
  
//Путь от корневой директории
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

//Текущее системное время
define('TM', time());

//Имя файла, к которому выполняется обращение
define('PHP_SELF', _filter($_SERVER['PHP_SELF']));

//Домен сайта
define('HTTP_HOST', _filter($_SERVER['HTTP_HOST']));

//Имя сервера
define('SERVER_NAME', _filter($_SERVER['SERVER_NAME']));

//Откуда пришли
if (isset($_SERVER['HTTP_REFERER'])) {
  
  define('HTTP_REFERER', _filter($_SERVER['HTTP_REFERER']));
  
}else{
  
  define('HTTP_REFERER', 'none');
  
}

//Браузер пользователя
if (isset($_SERVER['HTTP_USER_AGENT'])) {
  
  define('BROWSER', _filter($_SERVER["HTTP_USER_AGENT"]));
  
}else{
  
  define('BROWSER', 'none');
  
}

//IP пользователя
define('IP', _filter(filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP)));

//Определение протокола
if (isset($_SERVER['HTTPS'])){
  
  define('SCHEME', 'https://');
  $scheme = _filter($_SERVER['HTTPS']);
  
}else{
  
  $scheme = null;
  
  if ($scheme && $scheme != 'off'){
    
    define('SCHEME', 'https://');
    
  }else{ 
    
    define('SCHEME', 'http://');
        
  }
  
}

//Полный URL адрес запрашиваемой страницы
if (isset($_SERVER["REQUEST_URI"])){
  
  define('REQUEST_URI', _filter($_SERVER["REQUEST_URI"]));

//Иногда работает не корректно
}else{
  
  define('REQUEST_URI', '/');
  
}

//Переменная $_GET
function get($data, $d = 0){
  
  if (!isset($_GET[$data])){
    
    return isset($_GET[$data]);
    
  }else{

    return ($d == 0 ? remove_script($_GET[$data]) : $_GET[$data]);
    
  }
  
}

//Переменная $_POST
function post($data, $d = 0){
  
  if (!isset($_POST[$data])){
    
    return isset($_POST[$data]);
  
  }else{
    
    return ($d == 0 ? remove_script($_POST[$data]) : $_POST[$data]);
    
  }
  
}

//Переменная $_COOKIE
function cookie($name){
  
  if (!isset($_COOKIE[$name])){
    
    return isset($_COOKIE[$name]);
    
  }else{
    
    return remove_script($_COOKIE[$name]);
    
  }
  
}

//Переменная $_SESSION
function session($data, $param = 'no_data') {
  
  if ($param == 'no_data'){
    
    if (!isset($_SESSION[$data])){
      
      return isset($_SESSION[$data]);
    
    }else{
      
      return (!is_array($_SESSION[$data]) ? remove_script($_SESSION[$data]) : $_SESSION[$data]);
    
    }
    
  }else{
    
    return $_SESSION[$data] = $param;
    
  }
  
}

//Параметры настроек
function config($data, $param = null){
  
  global $config;
  
  if ($param == null){
    
    return _filter($config[$data]);
    
  }else{
    
    return $config[$data] = $param;
  
  }
  
}

//Определение версии сайта
function type_version(){
  
  $mobile_array = array(
  
    'ipad', 
    'iphone', 
    'android', 
    'pocket', 
    'palm', 
    'windows ce', 
    'windowsce', 
    'cellphone', 
    'opera mobi', 
    'ipod', 
    'small', 
    'sharp', 
    'sonyericsson', 
    'symbian', 
    'opera mini', 
    'nokia', 
    'htc_', 
    'samsung', 
    'motorola', 
    'smartphone', 
    'blackberry', 
    'playstation portable', 
    'tablet browser'
  
  );
  
  $agent = strtolower(BROWSER);    
  
  foreach ($mobile_array as $value) {    
    
    if (strpos($agent, $value) !== false){ 
      
      return true; 
    
    }   
  
  }       
  
  return false; 

}

//Редирект
function redirect($url, $refresh = 0){
  
  //$url - ссылка перенаправления
  //$refresh - задержка перенаправления
  
  if ($refresh <= 0){
    
    return header('location: '.$url).exit();
    
  }else{
    
    return header('refresh: '.$refresh.'; url = '.$url).exit();
  
  }
  
}