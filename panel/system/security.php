<?php  
html::title('Доступ к панели');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Доступ к панели')?>
</div>  
<?
  
if (post('ok_sec')){
  
  $password = esc(post('password'));
  $access = intval(post('access'));
  
  if (user('ACCESS') < 99){
    
    error('Настройки доступа может менять только пользователь с правами 99');
    redirect('/admin/system/security/');
    
  }
  
  if (str(config('ADM_EMAIL')) < 1 && str($password) > 0){
    
    error('Пароль не может быть установлен, так как не указан E-mail адрес ответственного за сайта лица');
    redirect('/admin/system/security/');
    
  }
  
  if (str($password) > 0 && config('PASSWORD') != $password){
    
    email(config('ADM_EMAIL'), 'Смена пароля для доступа в панель управления', 'Здравствуйте. Для вашей панели управления сменился пароль для доступа. <br /><br />Новый пароль: <b>'.$password.'</b>', config('EMAIL_ADM'));
    
  }
  
  if (str($password) > 0){
    
    $p = shif($password);
    
    setcookie('PANEL_PASSWORD', $p, TM + 60 * 60 * 24 * 365);
    
  }else{
    
    $p = null;
  
  }
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'PASSWORD', $p);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'ACCESS', $access);
  
  db::get_set("UPDATE `USERS` SET `PANEL_CODE_IP` = '0', `PANEL_IP` = ? WHERE `ID` = ? LIMIT 1", [IP, user('ID')]);
  
  success('Изменения успешно приняты');
  redirect('/admin/system/security/');

}

?>
<div class='list-body6'>

<div class='list-menu'>
<form method='post' action='/admin/system/security/' class='ajax-form'>
  
<?=html::select('access', array(
  1 => ['Свободный', (config('ACCESS') == 1 ? "selected" : null)], 
  2 => ['Проверка IP', (config('ACCESS') == 2 ? "selected" : null)]
), 'Режим доступа', 'form-control-100-modify-select', 'lock')?> 

<?=html::input('password', 'Пароль', 'Пароль доступа к панели:', null, null, 'form-control-30', null, 'text', 'key')?>
  
<?php if (str(config('PASSWORD')) > 0){ ?>
<font color='#F986A5'><?=icons('lock', 15)?> <?=lg('Установлен пароль')?></font><br /><br />  
<?php } ?>
  
<?=lg('При установке или смене пароля на E-mail адрес ответственного за сайт лица будет отправлено оповещение с паролем')?><br /><br />  
  
<?=html::button('button ajax-button', 'ok_sec', 'save', 'Сохранить изменения')?>

</form>

</div>
</div>
<br />
<?
  
back('/admin/system/');
acms_footer();