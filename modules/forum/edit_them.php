<?php  
$them = db::get_string("SELECT * FROM `FORUM_THEM` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$scsub = db::get_string("SELECT `PRIVATE`,`ID`,`SECTION_ID` FROM `FORUM_SUB_SECTION` WHERE `ID` = ? LIMIT 1", [$them['SUB_SECTION_ID']]);
acms_header(lg('Редактировать тему - %s', tabs($them['NAME'])), 'users');
is_active_module('PRIVATE_FORUM');
get_check_valid();

if (db::get_column("SELECT COUNT(*) FROM `FORUM_BAN` WHERE `USER_ID` = ? AND `BAN_TIME` > ? AND `BAN` = ? LIMIT 1", [user('ID'), TM, 0]) > 0 || db::get_column("SELECT COUNT(*) FROM `FORUM_BAN` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [user('ID'), 1]) > 0){
  
  error('Данная страница для вас недоступна. У вас имеется активная блокировка на форуме');
  redirect('/');

}

if (!isset($them['ID'])) {
  
  error('Неверная директива');
  redirect('/m/forum/sc/');

}

if (access('forum', null) == false && $them['USER_ID'] != user('ID')){
  
  error('Нет прав');
  redirect('/m/forum/show/?id='.$them['ID']);
  
}

if (post('ok_edit_forum')){
  
  valid::create(array(
    
    'THEM_NAME' => ['name', 'text', [2, 200], 'Название', 0],
    'THEM_ID_SUB' => ['sc_sub', 'number', [0, 99999], 'Подраздел'],
    'THEM_MESSAGE' => ['message', 'text', [10, 10000], 'Содержание', 0]
  
  ));
  
  if (THEM_MESSAGE != $them['MESSAGE'] && db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `MESSAGE` = ? LIMIT 1", [THEM_MESSAGE]) > 0){
    
    error('Тема с таким содержимым уже существует');
    redirect('/m/forum/edit_them/?id='.$them['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/forum/edit_them/?id='.$them['ID'].'&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `FORUM_THEM` SET `NAME` = ?, `MESSAGE` = ?, `EDIT_TIME` = ?, `EDIT_USER_ID` = ?, `SUB_SECTION_ID` = ? WHERE `ID` = ? LIMIT 1", [THEM_NAME, THEM_MESSAGE, TM, user('ID'), THEM_ID_SUB, $them['ID']]);
  db::get_set("UPDATE `COMMENTS` SET `SUB_OBJECT_ID` = ? WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [THEM_ID_SUB, $them['ID'], 'forum_comments']);
  
  if (access('forum', null) == true){
    
    logs('Форум - редактирование темы [url=/m/forum/show/?id='.$them['ID'].']'.$them['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Изменения успешно приняты');
  redirect('/m/forum/show/?id='.$them['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/forum/edit_them/?id=<?=$them['ID']?>&<?=TOKEN_URL?>'>
<?
html::input('name', 'Название', null, null, tabs($them['NAME']), 'form-control-100', 'text', null, 'comments');
define('ACTION', '/m/forum/edit_them/?id='.$them['ID'].'&'.TOKEN_URL);
define('TYPE', 'forum');
define('ID', $them['ID']);
html::textarea(tabs($them['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9);  
?><br /><br /><?
$array = array();

if (access('forum', null) == true && $scsub['PRIVATE'] == 1) {
  
  $sql = null;
  
}elseif ($scsub['PRIVATE'] == 0) {
  
  $sql = null;
  
}else{
  
  $sql = "AND `PRIVATE` = '0'";
  
}

$data = db::get_string_all("SELECT * FROM `FORUM_SUB_SECTION` WHERE `SECTION_ID` = ? ".$sql." ORDER BY `ID` DESC", [$scsub['SECTION_ID']]);  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], ($scsub['ID'] == $list['ID'] ? "selected" : null)];

}
html::select('sc_sub', $array, 'Подраздел', 'form-control-100-modify-select', 'list');

html::button('button ajax-button', 'ok_edit_forum', 'save', 'Сохранить');  
?>
<a class='button-o' href='/m/forum/show/?id=<?=$them['ID']?>'><?=lg('Отмена')?></a>
</form>
</div>
<?

back('/m/forum/show/?id='.$them['ID']);
acms_footer();