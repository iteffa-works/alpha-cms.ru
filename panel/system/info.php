<?php  
html::title('Руководитель');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Руководитель')?>
</div>
  
<div class='list-body6'>
  
<div class='list-menu list-title'> 
<?=lg('Руководитель проекта')?>
</div>
  
<?php
if (post('ok_info')){
  
  valid::create(array(
    
    'ADM_NAME' => ['name', 'text', [1, 200], 'Имя'],
    'ADM_SURNAME' => ['surname', 'text', [1, 200], 'Фамилия'],
    'ADM_EMAIL' => ['email', 'email', [5, 80], 'E-mail', 2]
    
  ));
  
  if (ERROR_LOG == 1){

    redirect('/admin/system/info/');
    
  }

  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'ADM_NAME', ini_data_check(ADM_NAME));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'ADM_SURNAME', ini_data_check(ADM_SURNAME));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'ADM_EMAIL', ini_data_check(ADM_EMAIL)); 
  
  success('Изменения успешно приняты');
  redirect('/admin/system/info/');

}  
?>
  
<div class='list-menu'>  
<form method='post' class='ajax-form' action='/admin/system/info/'>  
<?=html::input('name', null, 'Имя:', null, tabs(config('ADM_NAME')), 'form-control-100', null, null, 'pencil')?>  
<?=html::input('surname', null, 'Фамилия:', null, tabs(config('ADM_SURNAME')), 'form-control-100', null, null, 'pencil')?>
<?=html::input('email', null, 'Личный e-mail:', null, tabs(config('ADM_EMAIL')), 'form-control-100', null, null, 'pencil')?> 
<?=html::button('button ajax-button', 'ok_info', 'save', 'Сохранить изменения')?>  
</form>
</div>
  
</div><br />
<?

back('/admin/system/');
acms_footer();