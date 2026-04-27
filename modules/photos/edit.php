<?php  
$photo = db::get_string("SELECT * FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('Редактировать - %s', tabs($photo['NAME'])));
acms_header();
access('users');
get_check_valid();

if (!isset($photo['ID'])) {
  
  error('Неверная директива');
  redirect('/m/photos/');

}

if (access('photos', null) == false && $photo['USER_ID'] != user('ID')){
  
  error('Нет прав');
  redirect('/m/photos/');
  
}

if (config('PRIVATE_PHOTOS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (post('ok_edit_photos')){
  
  valid::create(array(
    
    'PHOTOS_NAME' => ['name', 'text', [2, 200], 'Название', 0],
    'PHOTOS_MESSAGE' => ['message', 'text', [0, 5000], 'Описание', 0],
    'PHOTOS_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
    'PHOTOS_ID_ALBUM' => ['id_album', 'number', [0, 99999], 'Альбом'],
    'PHOTOS_ADULT' => ['adult', 'number', [0, 1], 'Метка 18+']
  
  ));
  
  if ($photo['NAME'] != PHOTOS_NAME && db::get_column("SELECT COUNT(*) FROM `PHOTOS` WHERE `USER_ID` = ? AND `NAME` = ? AND `ID_DIR` = ? LIMIT 1", [$photo['USER_ID'], PHOTOS_NAME, PHOTOS_ID_ALBUM]) == 1){
    
    error('Фото с таким названием уже существует в данном альбоме');
    redirect('/m/photos/edit/?id='.$photo['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/photos/edit/?id='.$photo['ID'].'&'.TOKEN_URL);
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `PHOTOS_DIR` WHERE `ID` = ? AND `PRIVATE` != ? LIMIT 1", [PHOTOS_ID_ALBUM, 0]) == 1){
    
    db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$photo['ID'], 'photos']);
    
  }
  
  db::get_set("UPDATE `PHOTOS` SET `ADULT` = ?, `ID_DIR` = ?, `PRIVATE_COMMENTS` = ?, `NAME` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [PHOTOS_ADULT, PHOTOS_ID_ALBUM, PHOTOS_PRIVATE_COMMENTS, PHOTOS_NAME, PHOTOS_MESSAGE, $photo['ID']]);
  
  if (access('photos', null) == true){
    
    logs('Фото - редактирование записи [url=/m/photos/show/?id='.$photo['ID'].']'.$photo['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Изменения успешно приняты');
  redirect('/m/photos/show/?id='.$photo['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/photos/edit/?id=<?=$photo['ID']?>&<?=TOKEN_URL?>'>
<?
html::input('name', 'Название', null, null, tabs($photo['NAME']), 'form-control-100', 'text', null, 'camera');
html::textarea(tabs($photo['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0);  
?><br /><br /><?
$array = array();
$array[0] = ['Без альбома', ($photo['ID_DIR'] == 0 ? "selected" : null)];
$data = db::get_string_all("SELECT * FROM `PHOTOS_DIR` WHERE `USER_ID` = ? ORDER BY `ID` DESC", [$photo['USER_ID']]);  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], ($photo['ID_DIR'] == $list['ID'] ? "selected" : null)];

}
html::select('id_album', $array, 'Альбом', 'form-control-100-modify-select', 'folder'); 
html::select('private_comments', array(
  0 => ['Всем', ($photo['PRIVATE_COMMENTS'] == 0 ? "selected" : null)], 
  1 => ['Мне и друзьям', ($photo['PRIVATE_COMMENTS'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($photo['PRIVATE_COMMENTS'] == 2 ? "selected" : null)]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
html::checkbox('adult', 'Метка <span class="adult">18+</span>', 1, $photo['ADULT']);
?><br /><br /><?
html::button('button ajax-button', 'ok_edit_photos', 'save', 'Сохранить');  
?>
<a class='button-o' href='/m/photos/show/?id=<?=$photo['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/photos/show/?id='.$photo['ID']);
acms_footer();