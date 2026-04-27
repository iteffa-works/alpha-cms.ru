<?php

if (post('ok')){
  
  db_filter();
  post_check_valid();
  
  $act = abs(post('act'));
  $top = abs(post('top'));
  $them_limit = intval(post('them_limit'));
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FORUM_ACT_SUM', $act);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FORUM_TOP_SUM', $top);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FORUM_THEM_LIMIT', $them_limit);
  
  success('Изменения успешно приняты');
  redirect('/admin/site/modules/?mod=forum');
  
}

?>
<div class='list-body'>
<div class='list-menu'>
<form method='post' class='ajax-form' action='/admin/site/modules/?mod=forum'>  
<?=html::input('act', 0, 'Стоимость подъема темы:', null, abs(config('FORUM_ACT_SUM')), 'form-control-30', 'number', null, 'money')?>
<?=html::input('top', 0, 'Стоимость 1 дня в ТОПе:', null, abs(config('FORUM_TOP_SUM')), 'form-control-30', 'number', null, 'money')?>
<?=html::input('them_limit', 0, 'Сколько тем пользователь может создавать в день:', null, intval(config('FORUM_THEM_LIMIT')), 'form-control-30', 'number', null, 'comments')?>
<?=html::button('button ajax-button', 'ok', 'save', 'Сохранить изменения')?>
</form>
</div>
</div>