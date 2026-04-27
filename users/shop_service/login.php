<?php  
acms_header('Смена логина', 'users');
resources(config('TITLE'), config('LOGIN_SUM'), 'за смену логина');

if (post('ok_lg')){
  
  valid::create(array(
    
    'LOGIN' => ['login', 'login']
    
  ));
  
  if (ERROR_LOG == 1){

    redirect('/shopping/login/');
    
  }
  
  if (user('MONEY') < config('LOGIN_SUM')){
    
    error('Недостаточно денег на счету');
    redirect('/shopping/login/');
  
  }
  
  db::get_set("UPDATE `USERS` SET `LOGIN` = ?, `MONEY` = ? WHERE `ID` = ? LIMIT 1", [LOGIN, (user('MONEY') - config('LOGIN_SUM')), user('ID')]);
  money_data(user('ID'), config('LOGIN_SUM'), 0, lg('Смена логина'), 2);
    
  success('Логин успешно сменен');
  redirect('/shopping/login/');

}

$reg_info = array(

  1 => lg('Например: %s, 3-%d символов, только латиница, символы "_-." и цифры', '<b>Ivan_Ivanov</b>', config('REG_STR')),
  2 => lg('Например: %s, 3-%d символов, только кириллица, символы "_-." и цифры', '<b>Иван_Иванов</b>', config('REG_STR')),
  3 => lg('Например: %s, 3-%d символов, буквы любого языка, символы "_-." и цифры', '<b>Ivan_Ivanov</b>', config('REG_STR')),
  0 => lg('Например: %s или %s, 3-%d символов, только латиница, кириллица, символы "_-." и цифры', '<b>Иван_Иванов</b>', '<b>Ivan_Ivanov</b>', config('REG_STR')),
  
);

?>
<div class='list'>
<form method='post' class='ajax-form' action='/shopping/login/'>
<?=html::input('login', 'Придумайте логин', null, config('REG_STR'), tabs(session('login')), 'form-control-100', 'text', null, 'user', $reg_info[config('REG_LANG')])?>
<?=html::button('button ajax-button', 'ok_lg', 'pencil', 'Сменить логин')?>
</form>
</div>
<?

back('/shopping/');
acms_footer();