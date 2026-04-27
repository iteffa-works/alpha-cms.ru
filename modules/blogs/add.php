<?php
acms_header('Добавить запись в блог', 'users');
is_active_module('PRIVATE_BLOGS');

if (post('ok_blogs')){
  
  valid::create(array(
    
    'BLOGS_NAME' => ['name', 'text', [2, 200], 'Название', 0],
    'BLOGS_PASSWORD' => ['password', 'text', [0, 12], 'Пароль', 0],
    'BLOGS_PRIVATE' => ['private', 'number', [0, 5], 'Приватность'],
    'BLOGS_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
    'BLOGS_ID_CATEGORY' => ['id_cat', 'number', [0, 99999], 'Категория'],
    'BLOGS_MESSAGE' => ['message', 'text', [10, 10000], 'Содержание', 0]
  
  ));
  
  if (str(BLOGS_PASSWORD) > 0){
    
    $private = 4;
    $password2 = md5(BLOGS_PASSWORD);
    
  }else{
    
    $private = BLOGS_PRIVATE;
    $password2 = null;
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `USER_ID` = ? AND `COMMUNITY` = ? AND `MESSAGE` = ? LIMIT 1", [user('ID'), 0, BLOGS_MESSAGE]) == 1){
    
    error('Запись с таким содержимым уже существует в вашем блоге');
    redirect('/m/blogs/add/');
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/blogs/add/');
  
  }
  
  $ID = db::get_add("INSERT INTO `BLOGS` (`NAME`, `PRIVATE`, `PRIVATE_COMMENTS`, `USER_ID`, `ID_CATEGORY`, `PASSWORD`, `PASSWORD_SHOW`, `MESSAGE`, `TIME`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [BLOGS_NAME, $private, BLOGS_PRIVATE_COMMENTS, user('ID'), BLOGS_ID_CATEGORY, $password2, BLOGS_PASSWORD, BLOGS_MESSAGE, TM]);
  
  if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", ['blogs', 0]) > 0){
    
    db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [$ID, user('ID'), 'blogs']);
  
  }
  
  balls_add('BLOGS');
  rating_add('BLOGS');
  
  /*
  ------------------------------
  Отправляем подписчикам в ленту
  ------------------------------
  */
  
  if ($private == 0){
    
    $data = db::get_string_all("SELECT `MY_ID` FROM `SUBSCRIBERS` WHERE `USER_ID` = ?", [user('ID')]);    
    while ($list = $data->fetch()){
      
      db::get_add("INSERT INTO `TAPE` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$list['MY_ID'], $ID, user('ID'), TM, 'blogs']);
    
    } 
  
  }
  
  success('Запись успешно создана');
  redirect('/m/blogs/show/?id='.$ID);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/blogs/add/'>
<?
html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'book');
define('ACTION', '/m/blogs/add/');
define('TYPE', 'blogs');
define('ID', 0);
html::textarea(null, 'message', 'Введите содержимое', null, 'form-control-textarea', 9);  
?><br /><br /><?
$array = array();
$array[0] = ['Без категории', 0];
$data = db::get_string_all("SELECT * FROM `BLOGS_CATEGORIES` ORDER BY `ID` DESC");  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], (0 == $list['ID'] ? "selected" : null)];

}
html::select('id_cat', $array, 'Категория', 'form-control-100-modify-select', 'folder-open'); 
html::input('password', 'Пароль (по желанию)', null, null, null, 'form-control-100', 'text', null, 'key');
html::select('private', array(
  0 => ['Всем', 0], 
  1 => ['Мне и друзьям', 1], 
  2 => ['Только мне', 2]
), 'Доступ', 'form-control-100-modify-select', 'lock');
html::select('private_comments', array(
  0 => ['Всем', 0], 
  1 => ['Мне и друзьям', 1], 
  2 => ['Только мне', 2]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
html::button('button ajax-button', 'ok_blogs', 'plus', 'Добавить');  
?>
<a class='button-o' href='/m/blogs/users/?id=<?=user('ID')?>'><?=lg('Отмена')?></a>
</form>
</div>
<?

back('/m/blogs/users/?id='.user('ID'));
acms_footer();