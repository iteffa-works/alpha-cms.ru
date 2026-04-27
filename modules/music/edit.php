<?php  
$music = db::get_string("SELECT * FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('Редактировать - %s', tabs($music['FACT_NAME'])));
acms_header();
access('users');
get_check_valid();

if (!isset($music['ID'])) {
  
  error('Неверная директива');
  redirect('/m/music/');

}

if (access('music', null) == false && $music['USER_ID'] != user('ID')){
  
  error('Нет прав');
  redirect('/m/music/');
  
}

if (config('PRIVATE_MUSIC') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (post('ok_edit_music')){
  
  valid::create(array(
    
    'MUSIC_NAME' => ['name', 'text', [2, 200], 'Название композиции', 0],
    'MUSIC_FACT_NAME' => ['fact_name', 'text', [2, 200], 'Фактическое название', 0],
    'MUSIC_ALBUM' => ['album', 'text', [0, 200], 'Альбом', 0],
    'MUSIC_ARTIST' => ['artist', 'text', [0, 200], 'Альбом', 0],
    'MUSIC_GENRE' => ['genre', 'text', [0, 200], 'Жанр', 0],
    'MUSIC_MESSAGE' => ['message', 'text', [0, 5000], 'Описание', 0],
    'MUSIC_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
    'MUSIC_ID_ALBUM' => ['id_album', 'number', [0, 99999], 'Альбом'],
    'MUSIC_ADULT' => ['adult', 'number', [0, 1], 'Метка 18+']
  
  ));
  
  if ($music['FACT_NAME'] != MUSIC_FACT_NAME && db::get_column("SELECT COUNT(*) FROM `MUSIC` WHERE `USER_ID` = ? AND `FACT_NAME` = ? AND `ID_DIR` = ? LIMIT 1", [$music['USER_ID'], MUSIC_FACT_NAME, MUSIC_ID_ALBUM]) == 1){
    
    error('Музыка с таким фактическим названием уже существует в данном альбоме');
    redirect('/m/music/edit/?id='.$music['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/music/edit/?id='.$music['ID'].'&'.TOKEN_URL);
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `MUSIC_DIR` WHERE `ID` = ? AND `PRIVATE` != ? LIMIT 1", [MUSIC_ID_ALBUM, 0]) == 1){
    
    db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$music['ID'], 'music']);
    
  }
  
  db::get_set("UPDATE `MUSIC` SET `ADULT` = ?, `ID_DIR` = ?, `PRIVATE_COMMENTS` = ?, `NAME` = ?, `MESSAGE` = ?, `FACT_NAME` = ?, `GENRE` = ?, `ALBUM` = ?, `ARTIST` = ? WHERE `ID` = ? LIMIT 1", [MUSIC_ADULT, MUSIC_ID_ALBUM, MUSIC_PRIVATE_COMMENTS, MUSIC_NAME, MUSIC_MESSAGE, MUSIC_FACT_NAME, MUSIC_GENRE, MUSIC_ALBUM, MUSIC_ARTIST, $music['ID']]);
  
  if (access('music', null) == true){
    
    logs('Музыка - редактирование записи [url=/m/music/show/?id='.$music['ID'].']'.$music['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Изменения успешно приняты');
  redirect('/m/music/show/?id='.$music['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/music/edit/?id=<?=$music['ID']?>&<?=TOKEN_URL?>'>
<?
html::input('name', 'Название композиции', null, null, tabs($music['NAME']), 'form-control-100', 'text', null, 'music');
html::input('artist', 'Артист', null, null, tabs($music['ARTIST']), 'form-control-100', 'text', null, 'music');
html::input('genre', 'Жанр', null, null, tabs($music['GENRE']), 'form-control-100', 'text', null, 'music');
html::input('album', 'Альбом', null, null, tabs($music['ALBUM']), 'form-control-100', 'text', null, 'music');
html::input('fact_name', 'Фактическое название', null, null, tabs($music['FACT_NAME']), 'form-control-100', 'text', null, 'music');
html::textarea(tabs($music['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0);  
?><br /><br /><?
$array = array();
$array[0] = ['Без альбома', ($music['ID_DIR'] == 0 ? "selected" : null)];
$data = db::get_string_all("SELECT * FROM `MUSIC_DIR` WHERE `USER_ID` = ? ORDER BY `ID` DESC", [$music['USER_ID']]);  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], ($music['ID_DIR'] == $list['ID'] ? "selected" : null)];

}
html::select('id_album', $array, 'Альбом', 'form-control-100-modify-select', 'folder'); 
html::select('private_comments', array(
  0 => ['Всем', ($music['PRIVATE_COMMENTS'] == 0 ? "selected" : null)], 
  1 => ['Мне и друзьям', ($music['PRIVATE_COMMENTS'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($music['PRIVATE_COMMENTS'] == 2 ? "selected" : null)]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
html::checkbox('adult', 'Метка <span class="adult">18+</span>', 1, $music['ADULT']);
?><br /><br /><?
html::button('button ajax-button', 'ok_edit_music', 'save', 'Сохранить');  
?>
<a class='button-o' href='/m/music/show/?id=<?=$music['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/music/show/?id='.$music['ID']);
acms_footer();