<?php 
html::title('Редактирование сообщения');
acms_header();
access('users');

get_check_valid();

$mess = db::get_string("SELECT * FROM `MAIL_MESSAGE` WHERE `TID` = ? AND `USER` = ? LIMIT 1", [intval(get('id')), user('ID')]); 

if (!isset($mess['ID'])) {
  
  error('Неверная директива');
  redirect('/account/mail/');
  
}

if (post('ok_mess_edit')){
  
  $at = db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", ['ok_message', $mess['TID']]);
  
  if ($at > 0){
    
    $limit = 0;
  
  }else{
    
    $limit = 1;
  
  }
  
  valid::create(array(
    
    'MESSAGE' => ['message', 'text', [$limit, 5000], 'Сообщение', $limit]
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/account/mail/edit/?id='.$mess['TID'].'&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `MAIL_MESSAGE` SET `MESSAGE` = ?, `EDIT_TIME` = ? WHERE `TID` = ? LIMIT 2", [MESSAGE, TM, $mess['TID']]);
  
  if ($at > 0){
    
    db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [$mess['TID'], user('ID'), 'ok_message']);
  
  }
  
  redirect('/account/mail/messages/?id='.$mess['USER_ID'].'&'.TOKEN_URL);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/account/mail/edit/?id=<?=$mess['TID']?>&<?=TOKEN_URL?>'>
<? 
define('ACTION', '/account/mail/edit/?id='.$mess['TID'].'&'.TOKEN_URL);
define('TYPE', 'ok_message');
define('ID', $mess['TID']);
html::textarea(tabs($mess['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9);  
?><br /><br /><?
html::button('button ajax-button OnBottom', 'ok_mess_edit', 'save', 'Сохранить');  
?>
<a class='button-o OnBottom' href='/account/mail/messages/?id=<?=$mess['USER_ID']?>&<?=TOKEN_URL?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

acms_footer();