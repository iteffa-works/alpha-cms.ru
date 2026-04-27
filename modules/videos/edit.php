<?php  
$video = db::get_string("SELECT * FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('Редактировать - %s', tabs($video['NAME'])));
acms_header();
access('users');
get_check_valid();

if (!isset($video['ID'])) {
  
  error('Неверная директива');
  redirect('/m/videos/');

}

if (access('videos', null) == false && $video['USER_ID'] != user('ID')){
  
  error('Нет прав');
  redirect('/m/videos/');
  
}

if (config('PRIVATE_VIDEOS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (post('ok_edit_videos')){
  
  valid::create(array(
    
    'VIDEOS_NAME' => ['name', 'text', [2, 200], 'Название', 0],
    'VIDEOS_MESSAGE' => ['message', 'text', [0, 5000], 'Описание', 0],
    'VIDEOS_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
    'VIDEOS_ID_ALBUM' => ['id_album', 'number', [0, 99999], 'Альбом'],
    'VIDEOS_ADULT' => ['adult', 'number', [0, 1], 'Метка 18+']
  
  ));
  
  if ($video['NAME'] != VIDEOS_NAME && db::get_column("SELECT COUNT(*) FROM `VIDEOS` WHERE `USER_ID` = ? AND `NAME` = ? AND `ID_DIR` = ? LIMIT 1", [$video['USER_ID'], VIDEOS_NAME, VIDEOS_ID_ALBUM]) == 1){
    
    error('Видео с таким названием уже существует в данном альбоме');
    redirect('/m/videos/edit/?id='.$video['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/videos/edit/?id='.$video['ID'].'&'.TOKEN_URL);
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `VIDEOS_DIR` WHERE `ID` = ? AND `PRIVATE` != ? LIMIT 1", [VIDEOS_ID_ALBUM, 0]) == 1){
    
    db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$video['ID'], 'videos']);
    
  }
  
  db::get_set("UPDATE `VIDEOS` SET `ADULT` = ?, `ID_DIR` = ?, `PRIVATE_COMMENTS` = ?, `NAME` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [VIDEOS_ADULT, VIDEOS_ID_ALBUM, VIDEOS_PRIVATE_COMMENTS, VIDEOS_NAME, VIDEOS_MESSAGE, $video['ID']]);
  
  if (access('videos', null) == true){
    
    logs('Видео - редактирование записи [url=/m/videos/show/?id='.$video['ID'].']'.$video['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Изменения успешно приняты');
  redirect('/m/videos/show/?id='.$video['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/videos/edit/?id=<?=$video['ID']?>&<?=TOKEN_URL?>'>
<?
html::input('name', 'Название', null, null, tabs($video['NAME']), 'form-control-100', 'text', null, 'camera');
html::textarea(tabs($video['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0);  
?><br /><br /><?
$array = array();
$array[0] = ['Без альбома', ($video['ID_DIR'] == 0 ? "selected" : null)];
$data = db::get_string_all("SELECT * FROM `VIDEOS_DIR` WHERE `USER_ID` = ? ORDER BY `ID` DESC", [$video['USER_ID']]);  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], ($video['ID_DIR'] == $list['ID'] ? "selected" : null)];

}
html::select('id_album', $array, 'Альбом', 'form-control-100-modify-select', 'folder'); 
html::select('private_comments', array(
  0 => ['Всем', ($video['PRIVATE_COMMENTS'] == 0 ? "selected" : null)], 
  1 => ['Мне и друзьям', ($video['PRIVATE_COMMENTS'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($video['PRIVATE_COMMENTS'] == 2 ? "selected" : null)]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
html::checkbox('adult', 'Метка <span class="adult">18+</span>', 1, $video['ADULT']);
?><br /><br /><?
html::button('button ajax-button', 'ok_edit_videos', 'save', 'Сохранить');  
?>
<a class='button-o' href='/m/videos/show/?id=<?=$video['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/videos/show/?id='.$video['ID']);
acms_footer();