<?php 

/*
---------------------------------
Класс для валидации форм отправки
---------------------------------
*/
  
class valid {
  
  public static $e = 0;
  
  /*
  -----
  Капча
  -----
  */
  
  static function captcha(){
    
    global $captcha_random_seed;
    
    if (!post('captcha') or !post('captcha_key')) {
      
      self::$e = 1;
      error('Вы не ввели числа с картинки');
    
    }
    
    if (md5((int)post('captcha') + $captcha_random_seed) != post('captcha_key')) {
      
      self::$e = 1;
      error('Числа с картинки введены неверно');
    
    }
  
  }
  
  /*
  -------
  Правила
  -------
  */
  
  static function rules(){
    
    if (post('rules') == 0) {
      
      self::$e = 1;
      error('Вы не подтвердили, что обязуетесь соблюдать правила сайта');
    
    }
  
  }
  
  /*
  ---------------
  Проверка пароля
  ---------------
  */
  
  static function password($name, $post){

    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса

    $password = esc(post($post));
    
    define($name, $password);
    
    if (str($password) < 8){
      
      self::$e = 1;
      error('Пароль не может быть меньше 8 символов');
    
    }
    
    if (str($password) > 20){
      
      self::$e = 1;
      error('Пароль не может быть больше 20 символов');
    
    }
    
    if (!preg_match("#^([A-zА-яё0-9\-_@.%+])+$#ui", $password)) {
      
      self::$e = 1;
      error('В пароле присутствуют запрещенные символы. Используйте только латиницу, кириллицу, цифры и символы "_-@.%+"');
    
    }
  
  }
  
  /*
  ---------------------------
  Проверка пароля на верность
  ---------------------------
  */
  
  static function password_check($name, $post, $user_id){

    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса
    //$user_id - id пользователя
    
    $password = shif(esc(post($post)));
    $user_id = abs(intval($user_id));
    
    define($name, $password);
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `USERS` WHERE `PASSWORD` = ? AND `ID` = ? LIMIT 1", [$password, $user_id]) == 0) {      
      
      self::$e = 1;
      error('Неверный пароль');
    
    }
  
  }
  
  /*
  ---------------
  Проверка логина
  ---------------
  */
  
  static function login($name, $post, $double) {

    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса
    //$double - проверка логина на занятость

    $login = esc(post($post));
    
    define($name, $login);
    
    if (str($login) > config('REG_STR')){
      
      self::$e = 1;
      error('Слишком длинный логин');
    
    }
    
    if (str($login) < 3){
      
      self::$e = 1;
      error('Логин не может быть меньше 3 символов');
    
    }
    
    if (str(config('REG_LOGIN_BAN')) > 0) {
      
      $login_ban = explode(",", preg_replace('/\s+/', '', config('REG_LOGIN_BAN')));
      $lb_s = 0;
      
      foreach ($login_ban as $lb_result) {
        
        if (strpos(mb_strtolower($login, 'utf8'), mb_strtolower($lb_result, 'utf8')) !== false) {
          
          $lb_s = 1;
          
        }
      
      }
      
      if ($lb_s == 1) {
        
        self::$e = 1;
        error('Вы ввели запрещенный логин. Пожалуйста, придумайте другой логин');
        
      }
      
    }
    
    if ($double == 0 && config('REG_DOUBLE') == 0 && db::get_column("SELECT COUNT(`ID`) FROM `USERS` WHERE `LOGIN` = ? LIMIT 1", [$login]) == 1) {      
      
      self::$e = 1;
      error('Логин уже занят. Придумайте другой');
    
    }
    
    if (config('REG_LANG') == 1){ 
      
      if (!preg_match("#^([A-z0-9\-_.])+$#ui", $login)) {
        
        self::$e = 1;
        error('В логине присутствуют запрещенные символы. Только латиница, символы "_-." и цифры');
        
      }
      
      if (!preg_match("~([A-z])~", $login)){
        
        self::$e = 1;
        error('В логине должна содержаться хотябы одна буква (только латиница)');
      
      }
      
    }
    
    if (config('REG_LANG') == 2){ 
      
      if (!preg_match("#^([А-яё0-9\-_.])+$#ui", $login)){        
        
        self::$e = 1;
        error('В логине присутствуют запрещенные символы. Только кириллица, символы "_-." и цифры');
        
      }
      
      if (!preg_match("~([А-яё])~", $login)){
        
        self::$e = 1;
        error('В логине должна содержаться хотябы одна буква (только кириллица)');
      
      }
    
    }
    
    if (config('REG_LANG') == 3){ 
      
      if (!preg_match('/^[A-zА-я0-9\p{L}\-_.]+$/u', $login)){      
        
        self::$e = 1;
        error('В логине присутствуют запрещенные символы. Только буквы любого языка, символы "_-." и цифры');
        
      }
      
      if (!preg_match("~(?=.*?\pL)~", $login)){
        
        self::$e = 1;
        error('В логине должна содержаться хотябы одна буква');
      
      }
    
    }
    
    if (config('REG_LANG') == 0){
      
      if (!preg_match("#^([A-zА-яё0-9\-_.])+$#ui", $login)){
        
        self::$e = 1;
        error('В логине присутствуют запрещенные символы. Только кириллица, латиница, символы "_-." и цифры');
        
      }
      
      if (!preg_match("~([A-zА-яё])~", $login)){
        
        self::$e = 1;
        error('В логине должна содержаться хотябы одна буква (кириллица или латиница)');
      
      }
    
    }
    
    if (substr_count($login, '.') > 1) {
      
      self::$e = 1;
      error('Запрещено использовать символ "." два или более раза');
    
    }
    
    if (substr_count($login, '-') > 1) {
      
      self::$e = 1;
      error('Запрещено использовать символ "-" два или более раза');
    
    }
    
    if (substr_count($login, '_') > 1) {
      
      self::$e = 1;
      error('Запрещено использовать символ "_" два или более раза');
    
    }
  
  }
  
  /*
  ----------------
  Текстовые данные
  ----------------
  */
  
  static function text($name, $post, $from, $before, $fact_name, $copy = 1, $attach = 0){
    
    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса
    //$from - допустимое количество символов ОТ
    //$before - допустимое количество символов ДО
    //$fact_name - фактическое имя объекта для обозначения при сообщениях об ошибке
    //$copy - флажок включения/выключения сверки недавно добавленных содержимых
    //$attach - отключение проверки на минимальное количество символов если прикреплен файл

    $text = esc(post($post));
    $from = tabs(esc($from));
    $before = tabs(esc($before));
    $fact_name = tabs(esc($fact_name));    

    define($name, $text);
    
    if ($copy == 1 && session($post) == $text){ 
      
      self::$e = 1;
      error(lg('В поле "%s" используется содержание, которое использовалось недавно', $fact_name)); 
    
    }
    
    if ($copy == 1){
      
      session($post, $text);
      
    }
    
    if ($before > 0 && $from < $before){
      
      if (str($text) < $from && $attach == 0){ 
        
        self::$e = 1;
        error(lg('В поле "%s" количество символов не может быть меньше %d', $fact_name, $from));
      
      }
      
      if (str($text) > $before){ 
        
        self::$e = 1;
        error(lg('В поле "%s" количество символов не может быть больше %d', $fact_name, $before));
      
      }
      
    }
    
  }
  
  /*
  ------
  Ссылки
  ------
  */
  
  static function link($name, $post, $from, $before, $fact_name){
    
    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса
    //$from - допустимое количество символов ОТ
    //$before - допустимое количество символов ДО
    //$fact_name - фактическое имя объекта для обозначения при сообщениях об ошибке
    
    $link = esc(post($post));
    $from = abs(intval($from));
    $before = abs(intval($before)); 
    
    define($name, $link);
    
    if (str($link) > 0 && url_filter($link) == false){ 
      
      self::$e = 1;
      error('Не корректно указана ссылка'); 
    
    }
    
    if ($before > 0 && $from < $before){
      
      if (str($link) < $from){ 
        
        self::$e = 1;
        error(lg('В поле "%s" количество символов не может быть меньше %d', $fact_name, $from));
      
      }
      
      if (str($link) > $before){ 
        
        self::$e = 1;
        error(lg('В поле "%s" количество символов не может быть больше %d', $fact_name, $before));
      
      }
      
    }
    
  }
  
  /*
  ---------------------
  Неотрицательные числа
  ---------------------
  */
  
  static function number($name, $post, $from, $before, $fact_name){
    
    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса
    //$from - допустимое количество чисел ОТ
    //$before - допустимое количество чисел ДО
    //$fact_name - фактическое имя объекта для обозначения при сообщениях об ошибке
    
    $number = abs(intval(post($post)));
    $from = abs(intval($from));
    $before = abs(intval($before));

    define($name, $number);
    
    if ($before > 0 && $from < $before){
      
      if ($number < $from){ 
        
        self::$e = 1;
        error(lg('В поле "%s" значение не может быть меньше %d', $fact_name, $from));
      
      }
      
      if ($number > $before){ 

        self::$e = 1;
        error(lg('В поле "%s" значение не может быть больше %d', $fact_name, $before));
      
      }
      
    }
    
  }
  
  /*
  ----------------
  Абсолютные числа
  ----------------
  */
  
  static function number_abs($name, $post, $from, $before, $fact_name){
    
    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса
    //$from - допустимое количество чисел ОТ
    //$before - допустимое количество чисел ДО
    //$fact_name - фактическое имя объекта для обозначения при сообщениях об ошибке
    
    $number = abs(post($post));
    $from = abs($from);
    $before = abs($before);

    define($name, $number);
    
    if ($before > 0 && $from < $before){
      
      if ($number < $from){ 
        
        self::$e = 1;
        error(lg('В поле "%s" значение не может быть меньше %d', $fact_name, $from));
      
      }
      
      if ($number > $before){ 

        self::$e = 1;
        error(lg('В поле "%s" значение не может быть больше %d', $fact_name, $before));
      
      }
      
    }
    
  }
  
  /*
  -----------
  Целые числа
  -----------
  */
  
  static function number_int($name, $post, $from, $before, $fact_name){
    
    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса
    //$from - допустимое количество чисел ОТ
    //$before - допустимое количество чисел ДО
    //$fact_name - фактическое имя объекта для обозначения при сообщениях об ошибке
    
    $number = intval(post($post));
    $from = intval($from);
    $before = intval($before);

    define($name, $number);
    
    if ($before > 0 && $from < $before){
      
      if ($number < $from){ 
        
        self::$e = 1;
        error(lg('В поле "%s" значение не может быть меньше %d', $fact_name, $from));
      
      }
      
      if ($number > $before){ 

        self::$e = 1;
        error(lg('В поле "%s" значение не может быть больше %d', $fact_name, $before));
      
      }
      
    }
    
  }
  
  /*
  ----------------------------
  Проверка e-mail на занятость
  ----------------------------
  */
  
  static function email_check($name, $post){

    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса

    $email = esc(post($post));
    
    define($name, $email);
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `USERS` WHERE `EMAIL` = ? LIMIT 1", [$email]) == 1) {      
      
      self::$e = 1;
      error('Этот e-mail адрес уже зарегистрирован на сайте');
    
    }
  
  }
  
  /*
  ------
  E-mail
  ------
  */
  
  static function email($name, $post, $from, $before, $fact_name, $reg_mode = 2){
    
    //$name - имя объекта из массива
    //$post - имя для передачи POST запроса
    //$from - допустимое количество символов ОТ
    //$before - допустимое количество символов ДО
    //$reg_mode - режим регистрации через e-mail
    //$fact_name - фактическое имя объекта для обозначения при сообщениях об ошибке

    $email = esc(post($post));
    $from = abs(intval($from));
    $before = abs(intval($before));
    $reg_mode = abs(intval($reg_mode));

    define($name, $email);
    
    if ($reg_mode == 2) {
      
      if (str(config('REG_EMAIL_WHITE_LIST')) > 0) {
        
        $email_white_list = explode(",", preg_replace('/\s+/', '', config('REG_EMAIL_WHITE_LIST')));
        $ewl_s = 1;
        
        foreach ($email_white_list as $ewl_result) {
          
          $ewl_domain_check = explode("@", $email);
          
          if ($ewl_domain_check[1] == $ewl_result) {
            
            $ewl_s = 0;
          
          }
        
        }
        
        if ($ewl_s == 1) {
          
          self::$e = 1;
          error(lg('Вы ввели не разрешенный почтовый адрес. Разрешенные доменные имена почтовых адресов: %s', tabs(config('REG_EMAIL_WHITE_LIST'))));
        
        }
      
      }
      
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ 
        
        self::$e = 1;
        error('Не корректно указан E-mail'); 
      
      }
      
      if ($before > 0 && $from < $before){
        
        if (str($email) < $from){ 
          
          self::$e = 1;
          error(lg('В поле "%s" количество символов не может быть меньше %d', $fact_name, $from));
        
        }
        
        if (str($email) > $before){ 
          
          self::$e = 1;
          error(lg('В поле "%s" количество символов не может быть больше %d', $fact_name, $before));
        
        }
      
      }
      
    }
    
  }
  
  /*
  -----------
  Авторизация
  -----------
  */
  
  static function aut(){
    
    global $captcha_random_seed;
    
    $login = esc(post('login'));
    $password = shif(esc(post('password')));
    
    define('AUT_LOGIN', $login);
    define('AUT_PASSWORD', $password);
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `USERS` WHERE `LOGIN` = ? AND `PASSWORD` = ? LIMIT 1", [AUT_LOGIN, AUT_PASSWORD]) == 0){ 
      
      self::$e = 1;
      error('Неправильный логин или пароль');
    
    }
    
    if (session('captcha') == 1){
      
      self::captcha();
    
    }
  
  }
  
  /*
  ----------------------------------------------
  Создаем образ с проверкой POST данных из формы
  ----------------------------------------------
  */
  
  public static function create($data){
    
    db_filter();
    post_check_valid();
    hooks::run('post_valid');
    
    foreach ($data as $name => $valid){
      
      if (!isset($valid[1])){ $valid[1] = 'none'; }
      if (!isset($valid[0])){ $valid[0] = 'none'; }
      if (!isset($valid[2])){ $valid[2] = 'none'; }
      if (!isset($valid[2][0])){ $valid[2][0] = 'none'; }
      if (!isset($valid[2][1])){ $valid[2][1] = 'none'; }
      if (!isset($valid[3])){ $valid[3] = 'none'; }
      if (!isset($valid[4])){ $valid[4] = 'none'; }
      if (!isset($valid[5])){ $valid[5] = 'none'; }
      if (!isset($name)){ $name = 'none'; }
      
      //Текстовые данные
      if ($valid[1] == 'text'){
        
        self::text($name, $valid[0], $valid[2][0], $valid[2][1], $valid[3], $valid[4], $valid[5]);
        
      }
      
      //Ссылки
      if ($valid[1] == 'link'){        
        
        self::link($name, $valid[0], $valid[2][0], $valid[2][1], $valid[3]);
        
      }
      
      //Авторизация
      if ($name == 'aut'){

        self::aut();
        
      }
      
      //Капча
      if ($name == 'captcha'){
        
        self::captcha();
        
      }
      
      //Правила
      if ($name == 'rules'){

        self::rules();
        
      }
      
      //Логин
      if ($valid[1] == 'login'){

        self::login($name, $valid[1], intval($valid[2]));
        
      }
      
      //Пароль
      if ($valid[1] == 'password'){

        self::password($name, $valid[0], $valid[1]);
        
      }
      
      //Проверка пароля на верность
      if ($valid[1] == 'password_check'){

        self::password_check($name, $valid[0], $valid[2]);
        
      }
      
      //Проверка e-mail на занятость
      if ($valid[1] == 'email_check'){

        self::email_check($name, $valid[0]);
        
      }
      
      //E-mail
      if ($valid[1] == 'email'){
        
        self::email($name, $valid[0], $valid[2][0], $valid[2][1], $valid[3], $valid[4]);
        
      }
      
      //Неотрицательные числа
      if ($valid[1] == 'number'){
        
        self::number($name, $valid[0], $valid[2][0], $valid[2][1], $valid[3]);
        
      }
      
      //Абсолютные числа
      if ($valid[1] == 'number_abs'){
        
        self::number_abs($name, $valid[0], $valid[2][0], $valid[2][1], $valid[3]);
        
      }
      
      //Целые числа
      if ($valid[1] == 'number_int'){
        
        self::number_int($name, $valid[0], $valid[2][0], $valid[2][1], $valid[3]);
        
      }
    
    }
    
    //Регистрируем статус ошибки как временную константу
    if (self::$e == 1){ 
      
      define('ERROR_LOG', 1); 
    
    }else{ 
      
      define('ERROR_LOG', 0); 
    
    }
    
  }
  
}