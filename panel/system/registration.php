<?php  
acms_header('Настройки регистрации', 'management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Настройки регистрации')?>
</div>  
<?
  
if (post('ok_reg_set')) {
  
  valid::create(array(

    'REG_MODE' => ['reg_mode', 'number', [0, 5], 'Режим регистрации'],
    'REG_NAME' => ['name', 'number', [0, 1], 'Требовать ввод имени'],
    'REG_SURNAME' => ['surname', 'number', [0, 1], 'Требовать ввод фамилии'],
    'REG_LOGIN_BAN' => ['reg_login_ban', 'text', [0, 1000], 'Запрещенные логины'],
    'REG_EMAIL_WHITE_LIST' => ['reg_email_white_list', 'text', [0, 1000], 'Разрешенные доменные имена email-сервисов для регистрации'],
    'REG_STR' => ['reg_str', 'number', [3, 15], 'Длина логина'],
    'REG_LANG' => ['reg_lang', 'number', [0, 5], 'Допустимые буквы'],
    'REG_DOUBLE' => ['reg_double', 'number', [0, 5], 'Дубль логина'],
    'REG_ANTIDOUBLE' => ['reg_antidouble', 'number', [0, 5], 'Запрет на повторную регистрацию']
  
  ));
  
  if (ERROR_LOG == 1) {
    
    redirect('/admin/system/registration/');
    
  }
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_NAME', REG_NAME);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_SURNAME', REG_SURNAME);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_LOGIN_BAN', REG_LOGIN_BAN);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_EMAIL_WHITE_LIST', REG_EMAIL_WHITE_LIST);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_MODE', REG_MODE);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_STR', REG_STR);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_LANG', REG_LANG);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_DOUBLE', REG_DOUBLE);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'REG_ANTIDOUBLE', REG_ANTIDOUBLE);
  
  success('Изменения успешно приняты');
  redirect('/admin/system/registration/');

}

?>
<div class='list-body6'>

<div class='list-menu'>
<form method='post' action='/admin/system/registration/' class='ajax-form'>
  
<?=html::select('reg_mode', array(
  1 => ['Упрощенный без E-mail', (config('REG_MODE') == 1 ? "selected" : null)], 
  2 => ['С подтвержением E-mail', (config('REG_MODE') == 2 ? "selected" : null)], 
  0 => ['Закрытый (регистрация недоступна)', (config('REG_MODE') == 0 ? "selected" : null)]
), 'Режим регистрации', 'form-control-100-modify-select', 'lock')?> 
  
<?=html::select('reg_lang', array(
  1 => ['Только буквы английского алфавита', (config('REG_LANG') == 1 ? "selected" : null)], 
  2 => ['Только буквы русского алфавита', (config('REG_LANG') == 2 ? "selected" : null)], 
  3 => ['Буквы любого языка', (config('REG_LANG') == 3 ? "selected" : null)], 
  0 => ['Буквы русского и английского алфавитов', (config('REG_LANG') == 0 ? "selected" : null)]
), 'Допустимые буквы при выборе логина', 'form-control-100-modify-select', 'text-width')?>

<?=html::input('reg_str', 'Количество допустимых символов в логине', 'Количество допустимых символов в логине. От 3 до 15 символов', null, intval(config('REG_STR')), 'form-control-30', null, 'text', 'user-plus')?>
  
<?=html::select('reg_double', array(
  1 => ['Да', (config('REG_DOUBLE') == 1 ? "selected" : null)], 
  0 => ['Нет', (config('REG_DOUBLE') == 0 ? "selected" : null)] 
), 'Дублирование логинов', 'form-control-100-modify-select', 'clone')?> 
  
<?=html::select('reg_antidouble', array(
  1 => ['Проверка COOKIE', (config('REG_ANTIDOUBLE') == 1 ? "selected" : null)], 
  2 => ['Проверка COOKIE + IP', (config('REG_ANTIDOUBLE') == 2 ? "selected" : null)], 
  0 => ['Разрешена (нет запрета)', (config('REG_ANTIDOUBLE') == 0 ? "selected" : null)]
), 'Запрет повторной регистрации', 'form-control-100-modify-select', 'times')?>  
  
<?=html::checkbox('name', 'Требовать ввод имени', 1, config('REG_NAME'))?><br /><br /> 
<?=html::checkbox('surname', 'Требовать ввод фамилии', 1, config('REG_SURNAME'))?><br /><br />
  
<?=html::input('reg_login_ban', 'Запрещенные логины', 'Перечислите через запятую (",") логины, запрещенные для регистрации', null, tabs(config('REG_LOGIN_BAN')), 'form-control-100', null, 'text', 'user-times', 'Логины с их содержанием будут отклоняться при регистрации или смене, проверка чувствительна к верхнему и нижнему регистру в буквах')?>
  
<?=html::input('reg_email_white_list', 'Разрешенные доменные имена email-сервисов для регистрации', 'Перечислите через запятую (",") разрешенные доменные имена почтовых email-сервисов для регистрации или смены email адреса пользователем', null, tabs(config('REG_EMAIL_WHITE_LIST')), 'form-control-100', null, 'text', 'at', 'Если хотите разрешить все, то оставьте это поле пустым. Рекомендуется отсеивать левые почтовые адреса, введя в поле только проверенные сервисы. Это поможет против регистрирующихся спамеров или одних и тех же людей, которые регистрируют много аккаунтов')?>
  
<?=html::button('button ajax-button', 'ok_reg_set', 'save', 'Сохранить изменения')?>

</form>

</div>
</div>
<br />
<?
  
back('/admin/system/');
acms_footer();