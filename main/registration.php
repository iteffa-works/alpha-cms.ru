<?php
acms_header('Регистрация', 'guests');

/*
------------------------
Если регистрация закрыта
------------------------
*/

if (config('REG_MODE') == 0) {

  html::empty('Извините, регистрация закрыта', 'times');
  acms_footer();
  
}

/*
-------------------------------
Запрет на повторную регистрацию
-------------------------------
*/

if (config('REG_ANTIDOUBLE') == 1 && cookie('DOUBLE') == 1 || config('REG_ANTIDOUBLE') == 2 && cookie('DOUBLE') == 1 || config('REG_ANTIDOUBLE') == 2 && db::get_column("SELECT COUNT(`ID`) FROM `USERS` WHERE `IP` = ? LIMIT 1", [IP]) > 0) {
  
  html::empty('Извините, вы не можете зарегистрироваться, так как уже регистрировались', 'times');
  acms_footer();
  
}

if (post('ok_reg')) { 
  
  valid::create(array(
    
    'REG_LOGIN' => ['login', 'login'],
    'REG_PASSWORD' => ['password', 'password'],
    'REG_SEX' => ['sex', 'number', [1, 2], 'Пол'],
    'REG_EMAIL' => ['email', 'email', [5, 50], 'E-mail', config('REG_MODE')],
    'EMAIL_CHECK' => ['email', (config('REG_MODE') == 2 ? 'email_check' : null)],
    'REG_NAME' => ['name', 'text', [config('REG_NAME'), 15], 'Имя'],
    'REG_SURNAME' => ['surname', 'text', [config('REG_SURNAME'), 15], 'Фамилия'],
    'captcha' => [],
    'rules' => []
    
  ));
  
  session('login', REG_LOGIN);
  session('password', REG_PASSWORD);
  session('name', REG_NAME);
  session('surname', REG_SURNAME);
  session('sex', REG_SEX);
  if (config('REG_MODE') == 2){ session('email', REG_EMAIL); }
  
  if (ERROR_LOG == 1){

    redirect('/registration/');
    
  }
  
  $ID = db::get_add("INSERT INTO `USERS` (`BROWSER`, `IP`, `DATE_CREATE`, `DATE_VISIT`, `LOGIN`, `PASSWORD`, `SEX`) VALUES (?, ?, ?, ?, ?, ?, ?)", [BROWSER, IP, TM, TM, REG_LOGIN, shif(REG_PASSWORD), REG_SEX]);
  
  $us = db::get_string("SELECT * FROM `USERS` WHERE `ID` = ? AND `LOGIN` = ? AND `PASSWORD` = ? LIMIT 1", [$ID, REG_LOGIN, shif(REG_PASSWORD)]);
  $hash = user_hash($us['ID']);
  
  if (config('REG_MODE') == 2){
    
    $code = rand(111111,999999);
    email(tabs(REG_EMAIL), 'Подтверждение E-mail на '.HTTP_HOST, 'Здравствуйте. Вы получили это письмо для подтверждения E-mail адреса на нашем сайте <b>'.HTTP_HOST.'</b>.<br><br> Введите этот код для подтверждения: <b>'.$code.'</b>', tabs(config('EMAIL')));
    db::get_set("UPDATE `USERS` SET `REG_CODE` = ?, `REG_EMAIL` = ?, `REG_OK` = ?, `REG_TIME` = ? WHERE `ID` = ? LIMIT 1", [$code, REG_EMAIL, 1, TM, $us['ID']]);
    
  }
  
  db::get_set("UPDATE `USERS` SET `HASH` = ? WHERE `ID` = ? LIMIT 1", [$hash, $us['ID']]);
  
  setcookie('DOUBLE', 1, TM + 60 * 60 * 24 * 365, '/');
  setcookie('USER_ID', user_shif($us['ID']), TM + 60 * 60 * 24 * 365, '/');
  setcookie('PASSWORD', cencrypt(post('password'), $us['ID']), TM + 60 * 60 * 24 * 365, '/');
  session('salt', base64_encode(user_shif($us['ID']).','.cencrypt(post('password'), $us['ID'])));
  
  session('login', null);
  session('password', null);
  session('name', null);
  session('surname', null);
  session('sex', 0);
  if (config('REG_MODE') == 2){ session('email', null); }

  hooks::challenge('reg', 'reg');  
  hooks::run('reg');
  
  db::get_add("INSERT INTO `NOTIFICATIONS_SETTINGS` (`USER_ID`) VALUES (?)", [$us['ID']]);
  db::get_add("INSERT INTO `MAIL_SETTINGS` (`USER_ID`) VALUES (?)", [$us['ID']]);
  db::get_add("INSERT INTO `USERS_SETTINGS` (`NAME`, `SURNAME`, `USER_ID`, `AVATAR_PHONE`) VALUES (?, ?, ?, ?)", [REG_NAME, REG_SURNAME, $us['ID'], GenAvatar()]);
  
  success('Регистрация прошла успешно');    
  redirect('/account/cabinet/');

}

$reg_info = array(

  1 => lg('Например: %s, 3-%d символов, только латиница, символы "_-." и цифры', '<b>Ivan_Ivanov</b>', config('REG_STR')),
  2 => lg('Например: %s, 3-%d символов, только кириллица, символы "_-." и цифры', '<b>Иван_Иванов</b>', config('REG_STR')),
  3 => lg('Например: %s, 3-%d символов, буквы любого языка, символы "_-." и цифры', '<b>Ivan_Ivanov</b>', config('REG_STR')),
  0 => lg('Например: %s или %s, 3-%d символов, только латиница, кириллица, символы "_-." и цифры', '<b>Иван_Иванов</b>', '<b>Ivan_Ivanov</b>', config('REG_STR')),
  
);

?>
<div class='circle1'></div>
<div class='circle2'></div>  
<div class='circle3'></div>    
<form method='post' action='/registration/' class='list-tr'>
<?php hooks::challenge('reg_head', 'reg_head'); ?>
<?php hooks::run('reg_head'); ?>  
<center>
<div class='list-tr-avatar'>
<?=icons('id-card-o', 40)?>
</div>
<div class='aut-text'><?=lg('Пройдите легкую и быструю регистрацию для получения доступа ко всем возможностям')?></div>
</center>
<?=html::input('login', 'Придумайте логин', null, config('REG_STR'), tabs(session('login')), 'form-control-100', 'text', null, 'user', $reg_info[config('REG_LANG')])?>
<?=html::input('password', 'Придумайте пароль', null, 24, tabs(session('password')), 'form-control-100', 'password', null, 'lock', 'Придумайте сложный пароль, состоящий из латиницы, кириллицы, цифр или символов "_-@.%+". От 10 до 25 символов')?>
<?php if (config('REG_MODE') == 2) : ?>
<?=html::input('email', 'Укажите свой e-mail', null, 50, tabs(session('email')), 'form-control-100', 'text', null, 'at')?>
<?php endif ?>
<?php if (config('REG_NAME') == 1) : ?>
<?=html::input('name', 'Ваше имя', null, 15, tabs(session('name')), 'form-control-100', 'text', null, 'id-card')?>
<?php endif ?>
<?php if (config('REG_SURNAME') == 1) : ?>
<?=html::input('surname', 'Ваша фамилия', null, 15, tabs(session('surname')), 'form-control-100', 'text', null, 'id-card')?>
<?php endif ?>
<?=html::select('sex', array(
  1 => ['Мужской', (session('sex') == 1 ? "selected" : null)], 
  2 => ['Женский', (session('sex') == 2 ? "selected" : null)]
), 'Выберите пол', 'form-control-100-modify-select', 'venus-mars')?>
<?=html::captcha('Введите числа')?>
<?=html::checkbox('rules', 'я обязуюсь соблюдать', 1, 1)?>
<a href="/m/rules/" style='position: relative; bottom: 5px;'><?=lg('правила сайта')?></a><br /><br />
<?=html::button('button', 'ok_reg', 'plus', 'Зарегистрироваться')?>
<br />  
<a href='/password/' class='aut'><?=lg('Забыли пароль?')?></a>
<a href='/login/' style='float: right;' class='aut'><?=lg('Уже есть аккаунт?')?></a>    
<?php hooks::challenge('reg_foot', 'reg_foot'); ?> 
<?php hooks::run('reg_foot'); ?>
</form>
<?

acms_footer();