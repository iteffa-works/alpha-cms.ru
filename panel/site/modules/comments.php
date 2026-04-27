<?php

if (post('ok')){
  
  db_filter();
  post_check_valid();
  
  $type = abs(intval(post('type')));
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'COMMENTS_SET', $type);
  
  success('Изменения успешно приняты');
  redirect('/admin/site/modules/?mod=comments');
  
}

?>
<div class='list-body'>
<div class='list-menu'>
<form method='post' class='ajax-form' action='/admin/site/modules/?mod=comments'>
<?=html::select('type', array(
  0 => ['Древовидный формат', (config('COMMENTS_SET') == 0 ? "selected" : null)], 
  1 => ['Обычный формат', (config('COMMENTS_SET') == 1 ? "selected" : null)]
), 'Формат комментариев на сайте', 'form-control-100-modify-select', 'comments')?>
<?=html::button('button ajax-button', 'ok', 'save', 'Сохранить изменения')?>
</form>
</div>
</div>