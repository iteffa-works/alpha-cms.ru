<?php
$account = db::get_string("SELECT * FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$settings = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$account['ID']]);  
  
html::title(lg('Редактировать аккаунт %s', user::login_mini($account['ID'])));
acms_header();
access('users_edit');
get_check_valid();

if (!isset($account['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

if ($account['ACCESS'] == 99){
  
  error('Нельзя редактировать аккаунт с правами 99');
  redirect('/id'.$account['ID']);

}

if (post('ok_edit_user')){
  
  valid::create(array(
    
    'EDIT_LOGIN' => ['login', 'login', 1],
    'EDIT_NAME' => ['name', 'text', [0, 120], 'Имя', 0],
    'EDIT_SURNAME' => ['surname', 'text', [0, 120], 'Фамилия', 0],
    'EDIT_BALLS' => ['balls', 'number', [0, 99999999999999], 'Баллы'],
    'EDIT_MONEY' => ['money', 'number_abs', [0, 99999999999999], 'Деньги'],
    'EDIT_RATING' => ['rating', 'number_abs', [0, 99999999999999], 'Рейтинг'],
    'EDIT_EMAIL' => ['email', 'email', [5, 100], 'E-mail']
    
  ));
  
  if (str(post('password')) > 0){
    
    $password = shif(esc(post('password')));
  
  }else{
    
    $password = $account['PASSWORD'];
  
  }
  
  if (ERROR_LOG == 1){

    redirect('/account/page/edit/?id='.$account['ID'].'&'.TOKEN_URL);
    
  }
  
  db::get_set("UPDATE `USERS` SET `RATING` = ?, `BALLS` = ?, `MONEY` = ?, `PASSWORD` = ?, `LOGIN` = ?, `EMAIL` = ? WHERE `ID` = ? LIMIT 1", [EDIT_RATING, EDIT_BALLS, EDIT_MONEY, $password, EDIT_LOGIN, EDIT_EMAIL, $account['ID']]);    
  db::get_set("UPDATE `USERS_SETTINGS` SET `NAME` = ?, `SURNAME` = ? WHERE `USER_ID` = ? LIMIT 1", [EDIT_NAME, EDIT_SURNAME, $account['ID']]);
  
  logs('Редактирование аккаунтов - редактирование [url=/id'.$account['ID'].']'.$account['LOGIN'].'[/url]', user('ID'));
  
  success('Изменения успешно приняты');    
  redirect('/id'.$account['ID']);

}

?>
<div class='list'>
<form method='post' action='/account/page/edit/?id=<?=$account['ID']?>&<?=TOKEN_URL?>' class='ajax-form'>
<?
html::input('login', 'Логин', null, config('REG_STR'), tabs($account['LOGIN']), 'form-control-100', 'text', null, 'user');
html::input('password', 'Пароль', null, 24, null, 'form-control-100', 'password', null, 'lock');
html::input('email', 'E-mail', null, 80, tabs($account['EMAIL']), 'form-control-100', 'text', null, 'at');
html::input('name', 'Имя', null, null, tabs($settings['NAME']), 'form-control-100', 'text', null, 'user');
html::input('surname', 'Фамилия', null, null, tabs($settings['SURNAME']), 'form-control-100', 'text', null, 'user');
html::input('rating', 'Рейтинг', null, null, $account['RATING'], 'form-control-50', 'text', null, 'bar-chart');
html::input('money', 'Деньги', null, null, $account['MONEY'], 'form-control-50', 'text', null, 'money');
html::input('balls', 'Баллы', null, null, $account['BALLS'], 'form-control-50', 'text', null, 'database');
html::button('button ajax-button', 'ok_edit_user', 'save', 'Сохранить');
?>
<a class='button-o' href='/id<?=$account['ID']?>'><?=lg('Отмена')?></a>
</form>
</div>
<?

back('/id'.$account['ID']);  
acms_footer();