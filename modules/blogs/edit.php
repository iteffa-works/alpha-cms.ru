<?php  
$blog = db::get_string("SELECT * FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
acms_header(lg('Редактировать - %s', tabs($blog['NAME'])), 'users');
is_active_module('PRIVATE_BLOGS');
get_check_valid();

if (!isset($blog['ID']) || $blog['SHARE'] > 0) {
  
  error('Неверная директива');
  redirect('/m/blogs/');

}

if (access('blogs', null) == false && $blog['USER_ID'] != user('ID')){
  
  error('Нет прав');
  redirect('/m/blogs/');
  
}

if (post('ok_edit_blogs')){
  
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
  
  if ($blog['MESSAGE'] != BLOGS_MESSAGE && db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `USER_ID` = ? AND `COMMUNITY` = ? AND `MESSAGE` = ? LIMIT 1", [user('ID'), 0, BLOGS_MESSAGE]) == 1){
    
    error('Запись с таким содержимым уже существует в вашем блоге');
    redirect('/m/blogs/edit/?id='.$blog['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/blogs/edit/?id='.$blog['ID'].'&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `BLOGS` SET `ID_CATEGORY` = ?, `PASSWORD` = ?, `PASSWORD_SHOW` = ?, `PRIVATE` = ?, `PRIVATE_COMMENTS` = ?, `NAME` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [BLOGS_ID_CATEGORY, $password2, BLOGS_PASSWORD, $private, BLOGS_PRIVATE_COMMENTS, BLOGS_NAME, BLOGS_MESSAGE, $blog['ID']]);
  
  if (access('blogs', null) == true){
    
    logs('Блоги - редактирование записи [url=/m/blogs/show/?id='.$blog['ID'].']'.$blog['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Изменения успешно приняты');
  redirect('/m/blogs/show/?id='.$blog['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/blogs/edit/?id=<?=$blog['ID']?>&<?=TOKEN_URL?>'>
<?
html::input('name', 'Название', null, null, tabs($blog['NAME']), 'form-control-100', 'text', null, 'book');
define('ACTION', '/m/blogs/edit/?id='.$blog['ID'].'&'.TOKEN_URL);
define('TYPE', 'blogs');
define('ID', $blog['ID']);
html::textarea(tabs($blog['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9);  
?><br /><br /><?
$array = array();
$array[0] = ['Без категории', ($blog['ID_CATEGORY'] == 0 ? "selected" : null)];
$data = db::get_string_all("SELECT * FROM `BLOGS_CATEGORIES` ORDER BY `ID` DESC");  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], ($blog['ID_CATEGORY'] == $list['ID'] ? "selected" : null)];

}
html::select('id_cat', $array, 'Категория', 'form-control-100-modify-select', 'folder-open'); 
html::input('password', 'Пароль (по желанию)', null, null, tabs($blog['PASSWORD_SHOW']), 'form-control-100', 'text', null, 'key');
html::select('private', array(
  0 => ['Всем', ($blog['PRIVATE'] == 0 ? "selected" : null)], 
  1 => ['Мне и друзьям', ($blog['PRIVATE'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($blog['PRIVATE'] == 3 ? "selected" : null)]
), 'Доступ', 'form-control-100-modify-select', 'lock');
html::select('private_comments', array(
  0 => ['Всем', ($blog['PRIVATE_COMMENTS'] == 0 ? "selected" : null)], 
  1 => ['Мне и друзьям', ($blog['PRIVATE_COMMENTS'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($blog['PRIVATE_COMMENTS'] == 2 ? "selected" : null)]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
html::button('button ajax-button', 'ok_edit_blogs', 'save', 'Сохранить');  
?>
<a class='button-o' href='/m/blogs/show/?id=<?=$blog['ID']?>'><?=lg('Отмена')?></a>
</form>
</div>
<?

back('/m/blogs/show/?id='.$blog['ID']);
acms_footer();