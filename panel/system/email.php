<?php
acms_header('Настройки E-mail', 'management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Настройки E-mail')?>
</div>  
<?
  
if (post('ok_email_set')) {
  
  valid::create(array(
    
    'EMAIL' => ['email', 'text', [0, 50], 'E-mail', 0],
    'EMAIL_PASSWORD' => ['pass', 'text', [0, 50], 'Пароль', 0],
    'EMAIL_HOST' => ['host', 'text', [0, 50], 'Хост', 0],
    'EMAIL_NAME' => ['name', 'text', [0, 50], 'Имя', 0],
    'EMAIL_PORT' => ['port', 'number', [0, 100000], 'Порт'],
    'EMAIL_PROTOCOL' => ['protocol', 'text', [0, 10], 'Протокол', 0],
  
  ));
  
  if (ERROR_LOG == 1) {
    
    redirect('/admin/system/email/');
    
  }
  
  if (strpos(EMAIL_HOST, 'ssl://') !== false) {
    
    error('Ссылка на хост не должна начинаться с протокола ssl://');
    redirect('/admin/system/email/');
    
  }
  
  if (strpos(EMAIL_HOST, 'https://') !== false) {
    
    error('Ссылка на хост не должна начинаться с протокола https://');
    redirect('/admin/system/email/');
    
  }
  
  if (strpos(EMAIL_HOST, 'tls://') !== false) {
    
    error('Ссылка на хост не должна начинаться с протокола tls://');
    redirect('/admin/system/email/');
    
  }
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'EMAIL_PROTOCOL', ini_data_check(EMAIL_PROTOCOL));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'EMAIL', ini_data_check(EMAIL));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'EMAIL_PASSWORD', ini_data_check(EMAIL_PASSWORD));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'EMAIL_HOST', ini_data_check(EMAIL_HOST));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'EMAIL_NAME', ini_data_check(EMAIL_NAME));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'EMAIL_PORT', ini_data_check(EMAIL_PORT));
  
  success('Изменения успешно приняты');
  redirect('/admin/system/email/');

}

?>
<div class='list-body6'>

<div class='list-menu'>
<form method='post' action='/admin/system/email/' class='ajax-form'>
<?=html::input('email', 'mail@example.com', 'SMTP Логин (должен совпадать с адресом почтового ящика):', null, tabs(config('EMAIL')), 'form-control-100', 'text', null, 'at')?>
<?=html::input('host', 'smtp.example.com', 'SMTP Хост:', null, tabs(config('EMAIL_HOST')), 'form-control-100', 'text', null, 'database')?>
<?=lg('Выберите протокол безопасности')?>:
<?=html::select('protocol', array(
  'ssl' => ['SSL', (config('EMAIL_PROTOCOL') == 'ssl' ? "selected" : null)], 
  'tls' => ['TLS', (config('EMAIL_PROTOCOL') == 'tls' ? "selected" : null)], 
  'none' => ['Нет', (config('EMAIL_PROTOCOL') == 'none' ? "selected" : null)]
), 'Протокол безопасности', 'form-control-100-modify-select', 'lock')?>
<?=html::input('pass', null, 'SMTP Пароль:', null, tabs(config('EMAIL_PASSWORD')), 'form-control-100', 'text', null, 'key')?>
<?=html::input('name', 'Например: Администрация AlphaCMS', 'Имя отправителя писем:', null, tabs(config('EMAIL_NAME')), 'form-control-100', 'text', null, 'user')?>
<?=html::input('port', 'Например 465', 'SMTP порт:', null, tabs(config('EMAIL_PORT')), 'form-control-50', 'number', null, 'globe')?>
<?=html::button('button ajax-button', 'ok_email_set', 'save', 'Сохранить изменения')?>
</form>
</div>
</div>
<br />
<?
  
back('/admin/system/');
acms_footer();