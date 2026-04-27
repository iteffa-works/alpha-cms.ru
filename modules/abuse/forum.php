<?php
$forum = db::get_string("SELECT `ID`,`USER_ID` FROM `FORUM_THEM` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);    
acms_header('Жалоба на тему форума', 'users');
$action = base64_decode(get('action'));

if (!isset($forum['ID'])){
  
  error('Неверная директива');
  redirect('/');
  
}

if (str($action) == 0){
  
  error('Неверная директива');
  redirect('/');
  
}

if (db::get_column("SELECT COUNT(*) FROM `ABUSE` WHERE `USER_ID` = ? AND `OBJECT_TYPE` = ? AND `OBJECT_ID` = ? LIMIT 1", [user('ID'), 'forum', $forum['ID']]) > 2){
  
  error('Вы больше не можете жаловаться на эту тему');
  redirect($action);

}

if ($forum['USER_ID'] == user('ID')){
  
  error('Вы не можете жаловаться на свою тему');
  redirect($action);
  
}

if (post('ok_abuse')){
  
  valid::create(array(
    
    'ABUSE_MESSAGE' => ['message', 'text', [0, 1000], 'Комментарий', 0],
    'ABUSE_REASON' => ['reason', 'number', [0, 6], 'Нарушение']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/m/abuse/forum/?id='.$forum['ID'].'&action='.get('action'));
  
  }
  
  db::get_add("INSERT INTO `ABUSE` (`TIME`, `REASON`, `USER_ID`, `OBJECT_ID`, `MESSAGE`, `OBJECT_TYPE`) VALUES (?, ?, ?, ?, ?, ?)", [TM, ABUSE_REASON, user('ID'), $forum['ID'], ABUSE_MESSAGE, 'forum']);
  
  success('Жалоба успешно отправлена. Модераторы её рассмотрят и примут решение');
  redirect($action);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/abuse/forum/?id=<?=$forum['ID']?>&action=<?=get('action')?>'>
<?=html::select('reason', array(
  1 => ['СПАМ, реклама', 1], 
  2 => ['Мошенничество', 2], 
  3 => ['Нецензурная брань, оскорбления', 3], 
  4 => ['Разжигание ненависти', 4], 
  5 => ['Пропаганда нацизма', 5], 
  6 => ['Пропаганда наркотиков', 6], 
  0 => ['Прочее', 0]
), 'Нарушение', 'form-control-100-modify-select', 'ban')?> 
<?=html::textarea(null, 'message', 'Комментарий', null, 'form-control-textarea', 7, 0)?>
<br /><br />
<?=html::button('button ajax-button', 'ok_abuse', 'flag', 'Отправить жалобу')?>  
<a class='button-o' href='<?=$action?>'><?=lg('Отмена')?></a>
</form>
</div>
<?
  
back($action); 
acms_footer();