<?php 
html::title('Настройки почты');
acms_header();
access('users');

$mail_set = db::get_string("SELECT * FROM `MAIL_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]);

if (post('ok_set_mess')){
  
  valid::create(array(
    
    'MAIL_PRIVATE' => ['private', 'number', [0, 3], 'Приватность']
  
  ));
  
  db::get_set("UPDATE `MAIL_SETTINGS` SET `PRIVATE` = ? WHERE `ID` = ? LIMIT 1", [MAIL_PRIVATE, $mail_set['ID']]);
  
  success('Изменения успешно приняты');
  redirect('/account/mail/settings/');
  
}

?>
<div class='list'>
<form method='post' class='ajax-form' action='/account/mail/settings/'>
<?

html::select('private', array(
  0 => ['Все', ($mail_set['PRIVATE'] == 0 ? "selected" : null)], 
  1 => ['Только друзья', ($mail_set['PRIVATE'] == 1 ? "selected" : null)], 
  2 => ['Никто', ($mail_set['PRIVATE'] == 2 ? "selected" : null)]
), 'Кто может мне писать', 'form-control-100-modify-select', 'gear');
html::button('button ajax-button', 'ok_set_mess', 'save', 'Сохранить');

?></div><?

back('/account/mail/');
acms_footer();