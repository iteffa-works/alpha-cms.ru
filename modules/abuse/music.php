<?php
$file = db::get_string("SELECT * FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);    
acms_header('Жалоба на аудио', 'users');

if (!isset($file['ID'])){
  
  error('Неверная директива');
  redirect('/');
  
}

$type = 'music';

if (db::get_column("SELECT COUNT(*) FROM `ABUSE` WHERE `USER_ID` = ? AND `OBJECT_TYPE` = ? AND `OBJECT_ID` = ? LIMIT 1", [user('ID'), $type, $file['ID']]) > 2){
  
  error('Вы больше не можете жаловаться на этот объект');
  redirect('/m/'.$type.'/show/?id='.$file['ID']);

}

if ($file['USER_ID'] == user('ID')){
  
  error('Вы не можете жаловаться на свой файл');
  redirect('/m/'.$type.'/show/?id='.$file['ID']);
  
}

if (post('ok_abuse')){
  
  valid::create(array(
    
    'ABUSE_MESSAGE' => ['message', 'text', [0, 1000], 'Комментарий', 0],
    'ABUSE_REASON' => ['reason', 'number', [0, 6], 'Нарушение']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/m/abuse/'.$type.'/?id='.$file['ID']);
  
  }
  
  db::get_add("INSERT INTO `ABUSE` (`TIME`, `REASON`, `USER_ID`, `OBJECT_ID`, `MESSAGE`, `OBJECT_TYPE`) VALUES (?, ?, ?, ?, ?, ?)", [TM, ABUSE_REASON, user('ID'), $file['ID'], ABUSE_MESSAGE, $type]);
  
  success('Жалоба успешно отправлена. Модераторы её рассмотрят и примут решение');
  redirect('/m/'.$type.'/show/?id='.$file['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/abuse/<?=$type?>/?id=<?=$file['ID']?>'>
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
<a class='button-o' href='/m/<?=$type?>/show/?id=<?=$file['ID']?>'><?=lg('Отмена')?></a>
</form>
</div>
<?
  
back('/m/'.$type.'/show/?id='.$file['ID'], 'К песне'); 
acms_footer();