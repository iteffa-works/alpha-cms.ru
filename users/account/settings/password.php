<?php  
acms_header('Смена пароля', 'users');

if (post('ok_pass')){
  
  valid::create(array(
    
    'PASS' => ['password', 'password'],
    'PASS_OLD' => ['pass_old', 'text', [0,200], 'Старый пароль', 0],
    'captcha' => []
  
  ));
  
  if (db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? AND `PASSWORD` = ? LIMIT 1", [user('ID'), shif(PASS_OLD)]) == 0) {
    
    error('Старый пароль неверный');
    redirect('/account/settings/password/');
    
  }
  
  if (PASS == PASS_OLD){
    
    error('Новый пароль совпадает со старым');
    redirect('/account/settings/password/');
  
  }
  
  IF (ERROR_LOG == 1){
    
    redirect('/account/settings/password/');
  
  }
  
  $hash = user_hash(user('ID'));
  session('HASH', $hash);
  
  setcookie('DOUBLE', 1, TM + 60 * 60 * 24 * 365, '/');
  setcookie('USER_ID', user_shif(user('ID')), TM + 60 * 60 * 24 * 365, '/');
  setcookie('PASSWORD', cencrypt(PASS, user('ID')), TM + 60 * 60 * 24 * 365, '/');
  session('salt', base64_encode(user_shif(user('ID')).','.cencrypt(PASS, user('ID'))));
  
  db::get_set("UPDATE `USERS` SET `PASSWORD` = ? WHERE `ID` = ? LIMIT 1", [shif(PASS), user('ID')]);
  
  success('Измненения успешно приняты');
  redirect('/account/settings/password/');
  
}

?>
<div class='list'>
<form method='post' class='ajax-form' action='/account/settings/password/'>
<?=html::input('pass_old', 'Старый пароль', null, null, null, 'form-control-100', 'text', null, 'key')?>
<?=html::input('password', 'Новый пароль', null, null, null, 'form-control-100', 'text', null, 'key')?>
<?=html::captcha('Введите числа с картинки')?>
<?=html::button('button ajax-button', 'ok_pass', 'save', 'Сохранить')?>
</form>
</div>
<?

back('/account/settings/');
acms_footer();