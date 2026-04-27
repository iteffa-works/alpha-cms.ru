<?php
$comm = db::get_string("SELECT `ID`,`URL` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT `ID`,`ADMINISTRATION` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
html::title('Добавить запись в блог');
acms_header();
access('users');
communities::blocked($comm['ID']);

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (!isset($par['ID'])) {
  
  error('Вы не состоите в сообществе');
  redirect('/public/'.$comm['URL']);

}

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (post('ok_blogs_comm')){
  
  valid::create(array(
    
    'BLOGS_NAME' => ['name', 'text', [2, 200], 'Название', 0],
    'BLOGS_PASSWORD' => ['password', 'text', [0, 12], 'Пароль', 0],
    'BLOGS_PRIVATE' => ['private', 'number', [0, 5], 'Приватность'],
    'BLOGS_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
    'BLOGS_ID_CATEGORY' => ['id_cat', 'number', [0, 99999], 'Категория'],
    'BLOGS_MESSAGE' => ['message', 'text', [10, 20000], 'Содержание', 0]
  
  ));
  
  if (db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `USER_ID` = ? AND `MESSAGE` = ? AND `COMMUNITY` = ? LIMIT 1", [user('ID'), BLOGS_MESSAGE, $comm['ID']]) == 1){
    
    error('Запись с таким содержимым уже существует в блоге сообщества');
    redirect('/m/communities/add_blog/?id='.$comm['ID']);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/communities/add_blog/?id='.$comm['ID']);
  
  }
  
  $ID = db::get_add("INSERT INTO `BLOGS` (`NAME`, `PRIVATE_COMMENTS`, `USER_ID`, `ID_CATEGORY`, `MESSAGE`, `TIME`, `COMMUNITY`) VALUES (?, ?, ?, ?, ?, ?, ?)", [BLOGS_NAME, BLOGS_PRIVATE_COMMENTS, user('ID'), BLOGS_ID_CATEGORY, BLOGS_MESSAGE, TM, $comm['ID']]);
  
  if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", ['blogs', 0]) > 0){
    
    db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [$ID, user('ID'), 'blogs']);
  
  }
  
  /*
  -----------------------------
  Отправляем участникам в ленту
  -----------------------------
  */
  
  $data = db::get_string_all("SELECT `USER_ID` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ? AND `USER_ID` != ?", [$comm['ID'], 1, user('ID')]); 
  while ($list = $data->fetch()){
    
    db::get_add("INSERT INTO `TAPE` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$list['USER_ID'], $ID, user('ID'), TM, 'blogs']);
  
  }
  
  success('Запись успешно создана');
  redirect('/m/communities/show_blog/?id='.$ID);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/communities/add_blog/?id=<?=$comm['ID']?>'>
<?
html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'book');
define('ACTION', '/m/communities/add_blog/?id='.$comm['ID']);
define('TYPE', 'blogs');
define('ID', 0);
html::textarea(null, 'message', 'Введите содержимое', null, 'form-control-textarea', 9);  
?><br /><br /><?
$array = array();
$array[0] = ['Без категории'];
$data = db::get_string_all("SELECT * FROM `BLOGS_CATEGORIES` ORDER BY `ID` DESC");  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], (0 == $list['ID'] ? "selected" : null)];

}
html::select('id_cat', $array, 'Категория', 'form-control-100-modify-select', 'folder-open'); 
html::select('private_comments', array(
  0 => ['Всем', 0], 
  1 => ['Только участники сообщества', 1]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
html::button('button ajax-button', 'ok_blogs_comm', 'plus', 'Добавить');  
?>
<a class='button-o' href='/m/communities/blogs/?id=<?=$comm['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/communities/blogs/?id='.$comm['ID']);
acms_footer();