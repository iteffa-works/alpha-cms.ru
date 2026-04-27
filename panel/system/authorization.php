<?php  
html::title('Настройки авторизации');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Настройки авторизации')?>
</div>  
<?
  
if (post('ok_aut_set')){
  
  valid::create(array(

    'AUT_ACCESS' => ['aut_access', 'number', [0, 5], 'Доступность авторизации'],
    'AUT_MODE' => ['aut_mode', 'number', [0, 5], 'Режим авторизации']
  
  ));
  
  if (ERROR_LOG == 1) {
    
    redirect('/admin/system/authorization/');
    
  }
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'AUT_ACCESS', AUT_ACCESS);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'AUT_MODE', AUT_MODE);
  
  success('Изменения успешно приняты');
  redirect('/admin/system/authorization/');

}

?>
<div class='list-body6'>

<div class='list-menu'>
<form method='post' action='/admin/system/authorization/' class='ajax-form'>
  
<?=html::select('aut_access', array(
  1 => ['Открыта', (config('AUT_ACCESS') == 1 ? "selected" : null)], 
  0 => ['Закрыта', (config('AUT_ACCESS') == 0 ? "selected" : null)]
), 'Доступность авторизации', 'form-control-100-modify-select', 'key')?> 
  
<?=html::select('aut_mode', array(
  1 => ['IP + SESSION + HASH', (config('AUT_MODE') == 1 ? "selected" : null)], 
  2 => ['IP + BROWSER + SESSION + HASH', (config('AUT_MODE') == 2 ? "selected" : null)], 
  0 => ['COOKIE + SESSION + SALT (рекомендуется)', (config('AUT_MODE') == 0 ? "selected" : null)]
), 'Режим авторизации', 'form-control-100-modify-select', 'key')?>
  
<?=html::button('button ajax-button', 'ok_aut_set', 'save', 'Сохранить изменения')?>

</form>
  
<br /><br />
<?=lg('После внесения изменений, возможно, потребуется переавторизация на сайте')?>

</div>
</div>
<br />
<?
  
back('/admin/system/');
acms_footer();