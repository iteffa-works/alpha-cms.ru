<?php

if (post('ok_private_account')){
  
  valid::create(array(

    'SET_PRIVATE_ACCOUNT' => ['private_account', 'number', [0, 3], 'Приватность аккаунта']
  
  ));
  
  if (SET_PRIVATE_ACCOUNT == 2){
    
    db::get_set("UPDATE `USERS_SETTINGS` SET `FRIENDS_PRIVATE_ADD` = ? WHERE `USER_ID` = ? LIMIT 1", [1, user('ID')]);
  
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/account/settings/private/');
  
  }
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `PRIVATE_ACCOUNT` = ? WHERE `USER_ID` = ? LIMIT 1", [SET_PRIVATE_ACCOUNT, user('ID')]);
  
  success('Изменения успешно приняты');
  redirect('/account/settings/private/');
  
}  
  
?>

<div class='list-menu'>
<form method='post' class='ajax-form' action='/account/settings/private/'>
<?=html::select('private_account', array(
  0 => ['Всем', (settings('PRIVATE_ACCOUNT') == 0 ? "selected" : null)], 
  1 => ['Только авторизованным', (settings('PRIVATE_ACCOUNT') == 1 ? "selected" : null)], 
  2 => ['Только друзьям', (settings('PRIVATE_ACCOUNT') == 2 ? "selected" : null)], 
  3 => ['Только мне', (settings('PRIVATE_ACCOUNT') == 3 ? "selected" : null)]
), 'Кому видна моя страница', 'form-control-100-modify-select', 'user-secret')?>
<?=html::button('button ajax-button', 'ok_private_account', 'save', 'Сохранить изменения')?>
</form>
</div>