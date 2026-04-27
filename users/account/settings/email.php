<?php  
html::title('Настройки e-mail');
acms_header();
access('users');

$time = TM - 300;

/*
--------------------
Отмена подтверждения
--------------------
*/

if (get('get') == 'cancel'){
  
  db_filter();
  get_check_valid(); 
  
  db::get_set("UPDATE `USERS` SET `REG_EMAIL` = ?, `REG_CODE` = ? WHERE `ID` = ? LIMIT 1", [null, 0, user('ID')]); 
  
  redirect('/account/settings/email/');
  
}

/*
-----------------
Отправить ещё раз
-----------------
*/

if (get('get') == 'again' && user('REG_TIME') < $time){
  
  db_filter();
  get_check_valid(); 
  
  db::get_set("UPDATE `USERS` SET `REG_EMAIL` = ?, `REG_CODE` = ?, `REG_TIME` = ? WHERE `ID` = ? LIMIT 1", [user('REG_EMAIL'), user('REG_CODE'), TM, user('ID')]);
  email(user('REG_EMAIL'), 'Подтверждение E-mail на '.HTTP_HOST, 'Здравствуйте. Вы получили это письмо для подтверждения E-mail адреса на нашем сайте <b>'.HTTP_HOST.'</b>.<br><br> Введите этот код для подтверждения: <b>'.user('REG_CODE').'</b>', tabs(config('EMAIL'))); 
  
  success('Письмо с кодом отправлены повторно');
  redirect('/account/settings/email/');
  
}

/*
-------------
Отправить код
-------------
*/
 
if (str(user('REG_EMAIL')) == 0){
  
  ?>
  <div class='list-body'>
  <div class='list-menu'>  
  <b><?=lg('Текущий адрес')?>:</b><br /><?=(user('EMAIL') != null ? "<span class='info green'>".tabs(user('EMAIL'))."</span>" : "<span class='info gray'>".lg('не указан')."</span>")?></span>
  </div>
  <?
  
  if (user('REG_TIME') < $time){
    
    if (post('ok_email')){
      
      valid::create(array(
        
        'EMAIL_EMAIL' => ['email', 'email', [5, 50], 'E-mail', 2],
        'EMAIL_PASSWORD_CHECK' => ['password', 'password_check', user('ID')],
        'EMAIL_EMAIL_CHECK' => ['email', 'email_check'],
        'captcha' => []
      
      ));
      
      if (ERROR_LOG == 1){
        
        redirect(REQUEST_URI);
      
      }
      
      $code = rand(111111,999999);
      
      db::get_set("UPDATE `USERS` SET `REG_EMAIL` = ?, `REG_CODE` = ?, `REG_TIME` = ? WHERE `ID` = ? LIMIT 1", [EMAIL_EMAIL, $code, TM, user('ID')]);
      email(tabs(EMAIL_EMAIL), 'Подтверждение E-mail на '.HTTP_HOST, 'Здравствуйте. Вы получили это письмо для подтверждения E-mail адреса на нашем сайте <b>'.HTTP_HOST.'</b>.<br><br> Введите этот код для подтверждения: <b>'.$code.'</b>', tabs(config('EMAIL')));
      
      redirect(REQUEST_URI);
    
    }
    
    ?>
    <div class='list-menu'> 
    <form method='post' class='ajax-form' action='/account/settings/email/'>  
    <?
  
    html::input('email', 'Введите новый e-mail адрес');
    html::input('password', 'Введите пароль от аккаунта', null, null, null, 'form-control-100', 'password');
    html::captcha('Введите числа');
    html::button('button ajax-button', 'ok_email', 'envelope', 'Отправить код');
  
    ?>
    </form></div>
    <?
    
  }else{
    
    ?>
    <div class='list-menu'>  
    <?=lg('Повторная отправка будет возможна через')?> <b><?=otime(user('REG_TIME') - $time)?></b>
    </div>  
    <?
    
  }
  
  ?></div><?
  
}

/*
---------------
Подтвердить код
---------------
*/
 
if (str(user('REG_EMAIL')) > 0){
  
  if (post('ok_email')){
    
    valid::create(array(
      
      'EMAIL_EMAIL_CHECK' => ['email', 'email_check'],
      'captcha' => []
    
    ));
    
    if (ERROR_LOG == 1){
      
      redirect(REQUEST_URI);
    
    }
    
    if (user('REG_CODE') != intval(post('code'))){
      
      error('Неверный код');
      redirect(REQUEST_URI);
      
    }
    
    db::get_set("UPDATE `USERS` SET `EMAIL` = ?, `REG_EMAIL` = ?, `REG_OK` = ?, `REG_CODE` = ?, `REG_TIME` = ? WHERE `ID` = ? LIMIT 1", [user('REG_EMAIL'), null, 0, 0, 0, user('ID')]);
    
    success('Код успешно подтвержден');
    redirect('/account/cabinet/');
    
  }
  
  ?>
  <div class='list-body'>
  <div class='list-menu'>  
  <b><?=lg('Письмо с кодом подтверждения отправлены на адрес')?>:</b><br /><span class='info gray'><?=user('REG_EMAIL')?></span>
  </div>
  <div class='list-menu'> 
  <form method='post' class='ajax-form' action='/account/settings/email/'>      
  <?
    
  html::input('code', 'Введите код', null, null, null, 'form-control-50');
  ?><input type='hidden' name='email' value='<?=user('REG_EMAIL')?>'><?
  html::captcha('Введите числа');
  html::button('button ajax-button', 'ok_email', 'plus', 'Завершить');  
  
  ?>
  <a href='/account/settings/email/?get=cancel&<?=TOKEN_URL?>' class='button-o'><?=lg('Отменить')?></a>    
  </div>    
  <div class='list-menu'>
  <b><?=lg('Не пришел код')?>?</b>
  <? 
  
  if (user('REG_TIME') > $time){
    
    ?>
    <?=lg('Повторная отправка будет возможна через')?> <b><?=otime(user('REG_TIME') - $time)?></b>
    <?
  
  }else{
    
    ?>
    <a href='/account/settings/email/?get=again&<?=TOKEN_URL?>'><?=lg('Отправить ещё раз')?></a>
    <?
    
  }
  
  ?>    
  </div></form></div>
  <?
  
}

back('/account/settings/', 'К настройкам аккаунта');
acms_footer();