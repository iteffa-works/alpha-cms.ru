<?php  
acms_header('Авторизация', 'guests');

/*
------------------------
Если авторизация закрыта
------------------------
*/

if (config('AUT_ACCESS') == 0){

  html::empty('Извините, авторизация закрыта', 'times');
  acms_footer();
  
}

if (post('ok_aut')){
 
  valid::create(array(
    
    'aut' => [],
    'AUT_SAVE' => ['save', 'number', [0, 1], 'Чужое устройство']
  
  ));
  
  if (ERROR_LOG == 1){
    
    session('captcha', 1);
    redirect('/login/');
    
  }
  
  $us = db::get_string("SELECT `ID`,`MANAGEMENT` FROM `USERS` WHERE `LOGIN` = ? AND `PASSWORD` = ? LIMIT 1", [AUT_LOGIN, AUT_PASSWORD]);
  
  if (config('CTJ') == 1 && $us['MANAGEMENT'] == 0) {
    
    error('На сайте ведутся тех.работы. Попробуйте авторизоваться позже');
    redirect('/login/');
  
  }
  
  $hash = user_hash($us['ID']);
  db::get_set("UPDATE `USERS` SET `IP` = ?, `BROWSER` = ?, `HASH` = ? WHERE `ID` = ? LIMIT 1", [IP, BROWSER, $hash, $us['ID']]);  
  session('HASH', $hash); 
  session('HASH_OUT', (AUT_SAVE == 0 ? null : (TM + 3600)));
  session('captcha', 0);
  
  $save = (AUT_SAVE == 0 ? (TM + 60 * 60 * 24 * 365) : (TM + 3600));
  
  setcookie('DOUBLE', 1, $save, '/');
  setcookie('USER_ID', user_shif($us['ID']), $save, '/');
  setcookie('PASSWORD', cencrypt(post('password'), $us['ID']), $save, '/');
  session('salt', base64_encode(user_shif($us['ID']).','.cencrypt(post('password'), $us['ID'])));

  hooks::challenge('aut', 'aut');  
  hooks::run('aut');
  
  success('Вы успешно авторизовались');    
  redirect('/account/cabinet/');

}

?>
<div class='circle1'></div>
<div class='circle2'></div>  
<div class='circle3'></div>
<form method='post' action='/login/' class='list-tr'>  
<?=hooks::challenge('aut_head', 'aut_head')?>  
<?=hooks::run('aut_head')?>  
<center>
<div class='list-tr-avatar'>
<?=icons('user-o', 50)?>
</div>
<div class='aut-text'><?=lg('Оставайтесь всегда в сети рядом с близкими и друзьями')?></div>  
</center>  
<?=html::input('login', 'Логин', null, config('REG_STR'), null, 'form-control-100', 'text', null, 'user')?>
<?=html::input('password', 'Пароль', null, 24, null, 'form-control-100', 'password', null, 'lock')?>  
<?php if (session('captcha') == 1) : ?>
<?=html::captcha('Введите числа')?>
<?php endif ?>
<?=html::button('button', 'ok_aut', null, 'Войти')?>  
<div class='save_aut'>
<?=html::checkbox('save', 'Чужое устройство', 1, 0)?>
</div>  
<br /> 
<a href='/password/' class='aut'><?=lg('Забыли пароль?')?></a>
<a href='/registration/' style='float: right' class='aut'><?=lg('Регистрация')?></a>  
<?=hooks::challenge('aut_foot', 'aut_foot')?> 
<?=hooks::run('aut_foot')?>
</form>
<?

acms_footer();