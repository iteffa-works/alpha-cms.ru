<?php
$title = config('TITLE');
html::title('Настройки E-mail');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Настройки E-mail')?>
</div>  
<?
  
if (post('ok_email_set')){
  
  valid::create(array(
    
    'EMAIL' => ['email', 'email', [5, 50], 'E-mail', 2],
    'EMAIL_ADM' => ['email_adm', 'email', [5, 50], 'E-mail адмнистрации', 2]
  
  ));
  
  if (ERROR_LOG == 1) {
    
    redirect('/admin/system/email/');
    
  }
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'EMAIL', ini_data_check(EMAIL));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'EMAIL_ADM', ini_data_check(EMAIL_ADM));
  
  success('Изменения успешно приняты');
  redirect('/admin/system/email/');

}

?>
<div class='list-body6'>

<div class='list-menu'>
<form method='post' action='/admin/system/email/' class='ajax-form'>
<?=html::input('email', null, 'E-mail адрес сайта:', null, tabs(config('EMAIL')), 'form-control-100', null, 'text', 'at')?>
<?=html::input('email_adm', null, 'E-mail адрес администрации:', null, tabs(config('EMAIL_ADM')), 'form-control-100', null, 'text', 'at')?>  
<?=html::button('button ajax-button', 'ok_email_set', 'save', 'Сохранить изменения')?>
</form>
</div>
</div>
<br />
<?
  
back('/admin/system/');
acms_footer();