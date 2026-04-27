<?php
$account = db::get_string("SELECT `ID` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);    
html::title('Жалоба на пользователя');
acms_header();
access('users');

if (!isset($account['ID'])){
  
  error('Неверная директива');
  redirect('/');
  
}

if (db::get_column("SELECT COUNT(*) FROM `ABUSE` WHERE `USER_ID` = ? AND `OBJECT_TYPE` = ? AND `OBJECT_ID` = ? LIMIT 1", [user('ID'), 'users', $account['ID']]) > 2){
  
  error('Вы больше не можете жаловаться на этого пользователя');
  redirect('/id'.$account['ID']);

}

if ($account['ID'] == user('ID')){
  
  error('Вы не можете жаловаться на себя');
  redirect('/id'.$account['ID']);
  
}

if (post('ok_abuse')){
  
  valid::create(array(
    
    'ABUSE_MESSAGE' => ['message', 'text', [0, 1000], 'Комментарий', 0],
    'ABUSE_REASON' => ['reason', 'number', [0, 6], 'Нарушение']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/m/abuse/users/?id='.$account['ID']);
  
  }
  
  db::get_add("INSERT INTO `ABUSE` (`TIME`, `REASON`, `USER_ID`, `OBJECT_ID`, `MESSAGE`, `OBJECT_TYPE`) VALUES (?, ?, ?, ?, ?, ?)", [TM, ABUSE_REASON, user('ID'), $account['ID'], ABUSE_MESSAGE, 'users']);
  
  success('Жалоба успешно отправлена. Модераторы её рассмотрят и примут решение');
  redirect('/id'.$account['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/abuse/users/?id=<?=$account['ID']?>'>
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
<a class='button-o' href='/id<?=$account['ID']?>'><?=lg('Отмена')?></a>
</form>
</div>
<?
  
back('/id'.$account['ID']);  
acms_footer();