<?php    
html::title('Редактирование статуса');
acms_header();
access('users');

if (post('ok_status')) {
  
  valid::create(array(

    'STATUS' => ['status', 'text', [0, 100], 'Статус', 0]
    
  ));
  
  if (ERROR_LOG == 1){

    redirect('/account/status/');
    
  }
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `STATUS` = ? WHERE `USER_ID` = ? LIMIT 1", [STATUS, user('ID')]);
  
  success('Изменения успешно приняты');
  redirect('/id'.user('ID'));
  
}

?>
<div class='list'>
<form class='ajax-form' action='/account/status/'>
<?=html::textarea(settings('STATUS'), 'status', 'Мою собаку зовут Тоби', null, 'form-control-textarea', 9, 0)?>
<br /><br />
<?=html::button('button ajax-button', 'ok_status', 'save', 'Сохранить')?>
<a class='button-o' href='/id<?=user('ID')?>'><?=lg('Отмена')?></a>  
</form>
</div>
<?

back('/id'.user('ID'));
acms_footer();