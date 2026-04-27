<?php
acms_header('Создать тему', 'users');
is_active_module('PRIVATE_FORUM');

if (db::get_column("SELECT COUNT(*) FROM `FORUM_BAN` WHERE `USER_ID` = ? AND `BAN_TIME` > ? AND `BAN` = ? LIMIT 1", [user('ID'), TM, 0]) > 0 || db::get_column("SELECT COUNT(*) FROM `FORUM_BAN` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [user('ID'), 1]) > 0){
  
  error('Данная страница для вас недоступна. У вас имеется активная блокировка на форуме');
  redirect('/');

}

if (db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `USER_ID` = ? AND `TIME` > ?", [user('ID'), (TM - 86400)]) >= config('FORUM_THEM_LIMIT')){
  
  error(lg('Вы не можете создавать более %d тем в день', config('FORUM_THEM_LIMIT')));
  redirect('/');

}

if (get('sub_sec')) {
  
  $sc = db::get_string("SELECT * FROM `FORUM_SECTION` WHERE `ID` = ? LIMIT 1", [intval(get('sub_sec'))]);
  
  if (!isset($sc['ID'])) {
    
    error('Неверная директива');
    redirect('/m/forum/sc/');
  
  }
  
  ?>
  <div class='list-body'>
  <div class='list-menu'>
  <b><?=lg('Выберите в разделе %s подраздел, куда будет размещена тема', '"'.tabs($sc['NAME']).'"')?>:</b>
  </div>
  <?
  
  $s = 0;  
  $data = db::get_string_all("SELECT `ID`,`NAME` FROM `FORUM_SUB_SECTION` WHERE `SECTION_ID` = ? ORDER BY `ID` DESC", [$sc['ID']]);
  while ($list = $data->fetch()) {
    
    $s = 1;
    ?>
    <a href='/m/forum/add_them/?id=<?=$list['ID']?>&<?=TOKEN_URL?>'>
    <div class='list-menu hover'>
    <?=icons('comment', 15, 'fa-fw')?> <?=tabs($list['NAME'])?>
    </div>
    </a>
    <?
    
  }
  
  if ($s == 0) {
    
    ?>
    <div class='list-menu'>
    <?=lg('Нет подразделов')?>
    </div>
    <?
    
  }
  
  ?></div><?
  
  back('/m/forum/add_them/?get=section&'.TOKEN_URL);
  acms_footer();
  
}

if (get('get') == 'section') {
  
  ?>
  <div class='list-body'>
  <div class='list-menu'>
  <b><?=lg('Выберите раздел форума')?>:</b>
  </div>
  <?
  
  $s = 0;  
  $data = db::get_string_all("SELECT `ID`,`NAME` FROM `FORUM_SECTION` ORDER BY `ID` DESC");
  while ($list = $data->fetch()) {
    
    $s = 1;
    ?>
    <a href='/m/forum/add_them/?sub_sec=<?=$list['ID']?>&<?=TOKEN_URL?>'>
    <div class='list-menu hover'>
    <?=icons('comments', 15, 'fa-fw')?> <?=tabs($list['NAME'])?>
    </div>
    </a>
    <?
    
  }
  
  if ($s == 0) {
    
    ?>
    <div class='list-menu'>
    <?=lg('Нет разделов')?>
    </div>
    <?
    
  }
  
  ?></div><?
  
  back('/m/forum/');
  acms_footer();
  
}

$scsub = db::get_string("SELECT * FROM `FORUM_SUB_SECTION` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);

if (!isset($scsub['ID'])) {
  
  error('Неверная директива');
  redirect('/m/forum/sc/');

}

if (post('ok_forum')){
  
  valid::create(array(
    
    'THEM_NAME' => ['name', 'text', [2, 200], 'Название', 0],
    'THEM_ID_SUB' => ['sc_sub', 'number', [0, 99999], 'Подраздел'],
    'THEM_MESSAGE' => ['message', 'text', [10, 10000], 'Содержание', 0]
  
  ));
  
  if (db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `MESSAGE` = ? LIMIT 1", [THEM_MESSAGE]) > 0){
    
    error('Тема с таким содержимым уже существует');
    redirect('/m/forum/add_them/?id='.$scsub['ID']);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/forum/add_them/?id='.$scsub['ID']);
  
  }
  
  $ID = db::get_add("INSERT INTO `FORUM_THEM` (`NAME`, `USER_ID`, `SUB_SECTION_ID`, `MESSAGE`, `TIME`, `EDIT_TIME`, `EDIT_USER_ID`, `ACT_TIME`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [THEM_NAME, user('ID'), THEM_ID_SUB, THEM_MESSAGE, TM, TM, user('ID'), TM]);
  
  db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [$ID, user('ID'), 'forum']);
  
  balls_add('FORUM');
  rating_add('FORUM');
  
  /*
  ------------------------------
  Отправляем подписчикам в ленту
  ------------------------------
  */
  
  $data = db::get_string_all("SELECT `MY_ID` FROM `SUBSCRIBERS` WHERE `USER_ID` = ?", [user('ID')]);    
  while ($list = $data->fetch()){
    
    db::get_add("INSERT INTO `TAPE` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$list['MY_ID'], $ID, user('ID'), TM, 'forum']);
  
  }
  
  success('Тема успешно создана');
  redirect('/m/forum/show/?id='.$ID);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/forum/add_them/?id=<?=$scsub['ID']?>'>
<?
html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'comments');
define('ACTION', '/m/forum/add_them/?id='.$scsub['ID']);
define('TYPE', 'forum');
define('ID', 0);
html::textarea(null, 'message', 'Введите содержимое', null, 'form-control-textarea', 9);  
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
html::button('button ajax-button', 'ok_forum', 'plus', 'Добавить');  
?>
<a class='button-o' href='/m/forum/sc/?id_sub=<?=$scsub['ID']?>'><?=lg('Отмена')?></a>
</form>
</div>
<?

back('/m/forum/sc/?id_sub='.$scsub['ID']);
acms_footer();