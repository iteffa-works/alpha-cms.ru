<?php

if (post('ok')){
  
  db_filter();
  post_check_valid();
  
  $money = abs(post('money'));
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'LOGIN_SUM', $money);

  success('Изменения успешно приняты');
  redirect('/admin/site/modules/?mod=login_shop');
  
}

?>
<div class='list-body'>   
<div class='list-menu'>
<form method='post' class='ajax-form' action='/admin/site/modules/?mod=login_shop'>  
<?php
html::input('money', 0, 'Стоимость смены логина в магазине услуг:', null, config('LOGIN_SUM'), 'form-control-30', 'text', null, 'money');
html::button('button ajax-button', 'ok', 'save', 'Сохранить изменения');
?>
</form>
</div>
</div>