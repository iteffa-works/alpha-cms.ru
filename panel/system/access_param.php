<?php  
html::title('Доступ защищен');
acms_header();
access('administration_show');
  
//Доступ по паролю
if (str(config('PASSWORD')) > 0 && cookie('PANEL_PASSWORD') != config('PASSWORD')){
  
  if (post('ok_bls')){
    
    valid::create(array(
      
      'BLS_PASSOWRD' => ['password', 'text', [0,500], 'Пароль', 0],
      'captcha' => []
    
    ));
    
    if (config('PASSWORD') != shif(BLS_PASSOWRD)){
      
      error('Неверный пароль');
      redirect('/admin/system/access_param/');
      
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/system/access_param/');
    
    }
    
    setcookie('PANEL_PASSWORD', shif(BLS_PASSOWRD), TM + 60 * 60 * 24 * 365, '/');    
    redirect('/admin/');
  
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu list-title'>
  <?=icons('lock', 17, 'fa-fw')?> <?=lg('Доступ заморожен')?>
  </div>
    
  <div class='list-menu'>
  <font color='#F986A5'><?=lg('Вы являетесь членом администрации сайта. Некоторые части сайта вместе с панелью управления заморожены и требуют ввода пароля безопасности')?></font><br /><br />
    
  <form method='post' class='ajax-form' action='/admin/system/access_param/'>    
  <?=html::input('password', 'Пароль', 'Введите пароль:', null, null, 'form-control-100', 'text', null, 'key')?>     
  <?=html::captcha('Числа с картинки')?>
  <?=html::button('button ajax-button', 'ok_bls', 'plus', 'Подтвердить')?>
    
  </form>
  </div>
  </div>  
  <?
    
  //Проверка IP при входе
  
}elseif (config('ACCESS') == 2 && user('PANEL_IP') != IP){
  
  if (user('PANEL_CODE_IP') == 0 && str(user('EMAIL')) > 0){
    
    $code = rand(111111,999999);
    
    email(user('EMAIL'), 'Подтверждение смены IP', 'Здравствуйте. У вас сменился IP и необходимо его подтвердить для возвращения доступа в панель управления. Код для разблокировки доступа: '.$code, config('EMAIL'));
    
    db::get_set("UPDATE `USERS` SET `PANEL_CODE_IP` = ? WHERE `ID` = ? LIMIT 1", [$code, user('ID')]);
    
    redirect('/admin/system/access_param/');
  
  }
  
  if (str(user('EMAIL')) > 0){
    
    if (post('ok_code')){
      
      valid::create(array(
        
        'CODE' => ['code', 'number', [0,99999999999], 'Код'],
        'captcha' => []
      
      ));
      
      if (user('PANEL_CODE_IP') != CODE){
        
        error('Неверный код');
        redirect('/admin/system/access_param/');
        
      }
      
      if (ERROR_LOG == 1){
        
        redirect('/admin/system/access_param/');
      
      }
      
      db::get_set("UPDATE `USERS` SET `PANEL_CODE_IP` = '0', `PANEL_IP` = ? WHERE `ID` = ? LIMIT 1", [IP, user('ID')]);

      redirect('/admin/system/access_param/');
      
    }
    
    if (get('get') == 'go'){
      
      $code = rand(111111,999999);
      
      email(user('EMAIL'), 'Подтверждение смены IP', 'Здравствуйте. У вас сменился IP и необходимо его подтвердить для возвращения доступа в панель управления. Код для разблокировки доступа: '.$code, tabs(config('EMAIL')));
      
      db::get_set("UPDATE `USERS` SET `PANEL_CODE_IP` = ? WHERE `ID` = ? LIMIT 1", [$code, user('ID')]);
      
      success('Код успешно отправлен повторно');
      redirect('/admin/system/access_param/');
    
    }
  
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu list-title'>
  <?=icons('lock', 17, 'fa-fw')?> <?=lg('Доступ заморожен')?>
  </div>
  <div class='list-menu'>
  <font color='#F986A5'><?=lg('Вы являетесь членом администрации сайта. Некоторые части сайта вместе с панелью управления заморожены и требуют подтверждения смены вашего IP адреса')?></font><br /><br />
    
  <?php
  if (str(user('EMAIL')) == 0){
    
    ?>
    <?=lg('Отправка кода для разблокировки доступа к панели невозможна, так как вы не указали свой E-mail адрес в профиле. Сделать это можно здесь:')?><br /><br />
    <a href='/account/settings/email/' ajax='no' class='button'><?=icons('gear', 15, 'fa-fw')?> <?=lg('Подтвердить E-mail')?></a>
    <?    
    
  }else{
    
    ?>
    <?=lg('На ваш E-mail')?> <b><?=tabs(user('EMAIL'))?></b> <?=lg('отправлен код для смены IP')?><br /><br />
    <form method='post' class='ajax-form' action='/admin/system/access_param/'>    
    <?=html::input('code', 'Код', 'Введите полученный код:', null, null, 'form-control-30', 'text', null, 'key')?>     
    <?=html::captcha('Числа с картинки')?>
    <?=html::button('button ajax-button', 'ok_code', 'plus', 'Подтвердить')?>
    <a href='/admin/system/access_param/?get=go' class='button-o'><?=lg('Отправить повторно')?></a>
    </form>
    <?
  
  }
    
  ?>
  </div>
  </div>  
  <?

}else{
  
  redirect('/');
  
}

acms_footer();