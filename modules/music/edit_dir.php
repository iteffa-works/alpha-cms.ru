<?php 
$dir = db::get_string("SELECT * FROM `MUSIC_DIR` WHERE `ID` = ? AND `PRIVATE` != ? LIMIT 1", [intval(get('dir')), 3]);  
html::title('Редактировать альбом');
acms_header(); 
get_check_valid();
access('users');

if (config('PRIVATE_MUSIC') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (!isset($dir['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

if (post('ok_edit_music_album')){
  
  valid::create(array(
    
    'ALBUM_NAME' => ['name', 'text', [1, 100], 'Название', 0],
    'ALBUM_PASSWORD' => ['password', 'text', [0, 12], 'Пароль', 0],
    'ALBUM_PRIVATE' => ['private', 'number', [0, 5], 'Приватность']
  
  ));
  
  if (str(ALBUM_PASSWORD) > 0){

    $password = md5(ALBUM_PASSWORD);
    $private = 4;
      
  }else{
    
    $password = null;
    $private = ALBUM_PRIVATE;
  
  }
  
  if ($dir['NAME'] != ALBUM_NAME && db::get_column("SELECT COUNT(*) FROM `MUSIC_DIR` WHERE `USER_ID` = ? AND `NAME` = ? AND `ID_DIR` = ? LIMIT 1", [$dir['USER_ID'], ALBUM_NAME, $dir['ID']]) == 1){
    
    error('Альбом с таким названием уже существует в этой директории');
    redirect('/m/music/edit_dir/?dir='.$dir['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/music/edit_dir/?dir='.$dir['ID'].'&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `MUSIC_DIR` SET `PRIVATE` = ?, `NAME` = ?, `PASSWORD` = ?, `PASSWORD_SHOW` = ? WHERE `ID` = ? LIMIT 1", [$private, ALBUM_NAME, $password, ALBUM_PASSWORD, $dir['ID']]);
  
  if (access('music', null) == true){
    
    logs('Музыка - редактирование альбома [url=/m/music/users/?id='.$dir['USER_ID'].'&dir='.$dir['ID'].']'.$dir['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Изменения успешно приняты');
  redirect('/m/music/users/?id='.$dir['USER_ID'].'&dir='.$dir['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/music/edit_dir/?dir=<?=$dir['ID']?>&<?=TOKEN_URL?>'>
<?=html::input('name', 'Введите название', null, null, tabs($dir['NAME']), 'form-control-100', 'text', null, 'folder')?>
<?=html::select('private', array(
  0 => ['Всем', ($dir['PRIVATE'] == 0 ? "selected" : null)], 
  1 => ['Мне и друзьям', ($dir['PRIVATE'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($dir['PRIVATE'] == 2 ? "selected" : null)]
), 'Доступ', 'form-control-100-modify-select', 'lock')?>  
<?=html::input('password', 'Пароль', null, null, tabs($dir['PASSWORD_SHOW']), 'form-control-100', 'text', null, 'key')?>
<?=html::button('button ajax-button', 'ok_edit_music_album', 'save', 'Сохранить')?>  
<a class='button-o' href='/m/music/users/?id=<?=$dir['USER_ID']?>&dir=<?=$dir['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?
  
back('/m/music/users/?id='.$dir['USER_ID'].'&dir='.$dir['ID']);  
acms_footer();