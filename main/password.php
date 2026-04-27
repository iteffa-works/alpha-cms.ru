<?php  
acms_header('Восстановление доступа', 'guests');

//Установка нового пароля
if (get('id') && get('hash')){
  
  $us = db::get_string("SELECT `ID`,`LOGIN`,`EMAIL_HASH` FROM `USERS` WHERE `ID` = ? AND `EMAIL_HASH` = ? LIMIT 1", [intval(get('id')), esc(get('hash'))]);
  
  if (isset($us['ID'])){
    
    if (post('ok_pass')){
      
      valid::create(array(
        
        'PASS_PASSWORD' => ['password', 'password'],
        'captcha' => []
      
      ));
      
      if (ERROR_LOG == 1){
        
        redirect('/password/?id='.$us['ID'].'&hash='.$us['EMAIL_HASH']);
      
      }
      
      $hash = user_hash($us['ID']);
      
      db::get_set("UPDATE `USERS` SET `HASH` = ?, `EMAIL_HASH` = ?, `PASSWORD` = ? WHERE `ID` = ? LIMIT 1", [$hash, null, shif(PASS_PASSWORD), $us['ID']]);
      
      setcookie('DOUBLE', 1, TM + 60 * 60 * 24 * 365, '/');
      setcookie('USER_ID', user_shif($us['ID']), TM + 60 * 60 * 24 * 365, '/');
      setcookie('PASSWORD', cencrypt(post('password'), $us['ID']), TM + 60 * 60 * 24 * 365, '/');
      session('salt', base64_encode(user_shif($us['ID']).','.cencrypt(post('password'), $us['ID'])));
      
      success('Доступ успешно восстановлен');    
      redirect('/account/');
    
    }
    
    ?>
    <div class='circle1'></div>
    <div class='circle2'></div>  
    <div class='circle3'></div>        
    <form method='post' action='/password/?id=<?=intval(get('id'))?>&hash=<?=tabs(get('hash'))?>' class='list-tr'>      
    <center>
    <div class='list-tr-avatar'><?=icons('unlock', 40)?></div>
    <div class='aut-text'><?=lg('Теперь осталось придумать новый пароль для входа')?></div>
    </center>      
    <?=html::input('login', 'Логин', null, config('REG_STR'), tabs($us['LOGIN']), 'form-control-100', 'text', 'disabled=disabled', 'user')?> 
    <?=html::input('password', 'Придумайте пароль', null, 24, null, 'form-control-100', 'password', null, 'lock', 'Придумайте сложный пароль, состоящий из латиницы, кириллицы, цифр или символов "_-@.%+". От 8 до 20 символов')?>
    <?=html::captcha('Введите числа')?>
    <?=html::button('button', 'ok_pass', 'unlock', 'Восстановить доступ')?>      
    <br />      
    <a href='/registration/' class='aut'><?=lg('Регистрация')?></a>
    <a href='/login/' style='float: right;' class='aut'><?=lg('Авторизация')?></a>      
    </form>
    <?
    
  }else{
    
    error('Неизвестная ошибка');
    redirect('/password/');
  
  }

}else{
  
  $time = TM - 300;
  
  if (get('type') == 'login') {
    
    //Отправка сообщения для восстановления доступа  
    if (post('ok_pass')) {
      
      valid::create(array(
        
        'PASS_LOGIN' => ['login', 'text', [0,20], 'Логин', 0],
        'captcha' => []
      
      ));
      
      $code = md5(mt_rand(0000000,9999999));
      
      $us = db::get_string("SELECT `ID`,`LOGIN`,`REG_TIME`,`EMAIL` FROM `USERS` WHERE `LOGIN` = ? LIMIT 1", [PASS_LOGIN]);
      
      if (!isset($us['ID'])){
        
        error('Не удалось найти пользователя');
        redirect('/password/?type=login');
      
      }
      
      session('login', PASS_LOGIN);
      
      if (ERROR_LOG == 1){
        
        redirect('/password/?type=login');
      
      }
      
      if (str($us['EMAIL']) == 0){
        
        error('К указанному логину не привязан e-mail адрес');
        redirect('/password/?type=login');
      
      }
      
      if ($us['REG_TIME'] < $time) {
        
        email($us['EMAIL'], 'Восстановление доступа к аккаунту на '.HTTP_HOST, 'Здравствуйте. Вы получили это письмо для восстановления доступа к аккаунту на нашем сайте <b>'.HTTP_HOST.'</b>.<br><br> Ваш логин: <b>'.PASS_LOGIN.'</b><br /><br />Перейдите по ссылке для установки нового пароля:<br /><a href="'.SCHEME.HTTP_HOST.'/password/?id='.$us['ID'].'&hash='.$code.'">'.SCHEME.HTTP_HOST.'/password/?id='.$us['ID'].'&hash='.$code.'</a>', tabs(config('EMAIL')));
        
        db::get_set("UPDATE `USERS` SET `EMAIL_HASH` = ?, `REG_TIME` = ? WHERE `ID` = ? LIMIT 1", [$code, TM, $us['ID']]);
        
        session('login', null);
        
        success(lg('Письмо успешно отправлено на адрес %s. Перейдите к письму и следуйте дальнейшим инструкциям', substr_replace(tabs($us['EMAIL']), '****', 0, 4)));
        redirect('/password/?type=login');
        
      }else{
        
        error(lg('Повторная отправка будет возможна через %s', otime($us['REG_TIME'] - $time)));
        redirect('/password/?type=login');
        
      }
      
    }
    
    ?>
    <div class='circle1'></div>
    <div class='circle2'></div>  
    <div class='circle3'></div>        
    <form method='post' class='ajax-form list-tr' action='/password/?type=login'>
    <center>
    <div class='list-tr-avatar'><?=icons('unlock', 40)?></div>
    <div class='aut-text'><?=lg('Введите свой логин, к которому привязан ваш e-mail адрес. На него будет отправлено письмо для восстановления пароля')?></div>
    </center> 
    <?=html::input('login', 'Логин', null, config('REG_STR'), tabs(session('login')), 'form-control-100', 'text', null, 'user', 'Укажите логин, к которому нужно восстановить доступ')?> 
    <?=html::captcha('Введите числа')?> 
    <?=html::button('button ajax-button', 'ok_pass', 'envelope', 'Отправить письмо')?><a href='/password/' class='button-o'><?=lg('Не помню логин')?></a>
    <br />      
    <a href='/registration/' class='aut'><?=lg('Регистрация')?></a>
    <a href='/login/' style='float: right' class='aut'><?=lg('Авторизация')?></a>  
    </form>
    <?
    
  }else{
    
    //Отправка сообщения для восстановления доступа  
    if (post('ok_pass')) {
      
      valid::create(array(
        
        'PASS_EMAIL' => ['email', 'text', [0,50], 'E-mail', 0],
        'captcha' => []
      
      ));
      
      $code = md5(mt_rand(0000000,9999999));
      
      $us = db::get_string("SELECT `ID`,`LOGIN`,`REG_TIME` FROM `USERS` WHERE `EMAIL` = ? LIMIT 1", [PASS_EMAIL]);
      
      if (!isset($us['ID'])){
        
        error('Не удалось найти пользователя с таким E-mail');
        redirect('/password/');
      
      }
      
      session('email', PASS_EMAIL);
      
      if (ERROR_LOG == 1){
        
        redirect('/password/');
      
      }
      
      if ($us['REG_TIME'] < $time) {
        
        email(PASS_EMAIL, 'Восстановление доступа к аккаунту на '.HTTP_HOST, 'Здравствуйте. Вы получили это письмо для восстановления доступа к аккаунту на нашем сайте <b>'.HTTP_HOST.'</b>.<br><br> Ваш логин: <b>'.$us['LOGIN'].'</b><br /><br />Перейдите по ссылке для установки нового пароля:<br /><a href="'.SCHEME.HTTP_HOST.'/password/?id='.$us['ID'].'&hash='.$code.'">'.SCHEME.HTTP_HOST.'/password/?id='.$us['ID'].'&hash='.$code.'</a>', tabs(config('EMAIL')));
        
        db::get_set("UPDATE `USERS` SET `EMAIL_HASH` = ?, `REG_TIME` = ? WHERE `ID` = ? LIMIT 1", [$code, TM, $us['ID']]);
        
        session('email', null);
        
        success('Письмо успешно отправлено на указанный адрес. Перейдите к письму и следуйте дальнейшим инструкциям');
        redirect('/password/');
        
      }else{
        
        error(lg('Повторная отправка будет возможна через %s', otime($us['REG_TIME'] - $time)));
        redirect('/password/');
        
      }
      
    }
    
    ?>
    <div class='circle1'></div>
    <div class='circle2'></div>  
    <div class='circle3'></div>        
    <form method='post' class='ajax-form list-tr' action='/password/'>
    <center>
    <div class='list-tr-avatar'><?=icons('unlock', 40)?></div>
    <div class='aut-text'><?=lg('Отправьте письмо с ссылкой для восстановления доступа на e-mail адрес, привязанный к вашему логину')?></div>
    </center>     
    <?=html::input('email', 'Укажите e-mail', null, 100, tabs(session('login')), 'form-control-100', 'text', null, 'at', 'Укажите e-mail, привязанный к логину')?>
    <?=html::captcha('Введите числа')?> 
    <?=html::button('button ajax-button', 'ok_pass', 'envelope', 'Отправить письмо')?><a href='/password/?type=login' class='button-o'><?=lg('Не помню e-mail')?></a>
    <br />      
    <a href='/registration/' class='aut'><?=lg('Регистрация')?></a>
    <a href='/login/' style='float: right' class='aut'><?=lg('Авторизация')?></a> 
    </form>
    <?
    
  }
  
}

acms_footer();