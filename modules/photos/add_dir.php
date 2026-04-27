<?php 
$dir = db::get_string("SELECT `ID` FROM `PHOTOS_DIR` WHERE `ID` = ? AND `USER_ID` = ? AND `PRIVATE` != ? LIMIT 1", [intval(get('dir')), user('ID'), 3]);  
html::title('Создать альбом');
acms_header(); 
get_check_valid();
access('users');

if (config('PRIVATE_PHOTOS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if ($dir['ID'] > 0){
  
  $url = '/m/photos/users/?id='.user('ID').'&dir='.$dir['ID'];
  
}else{
  
  $url = '/m/photos/users/?id='.user('ID');
  
}

if (post('ok_photos_album')){
  
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
  
  if (db::get_column("SELECT COUNT(*) FROM `PHOTOS_DIR` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]) >= config('PHOTOS_DIR_LIMIT')){
    
    error('Вы исчерпали лимит на создание альбомов');
    redirect('/m/photos/add_dir/?dir='.$dir['ID'].'&'.TOKEN_URL);
    
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `PHOTOS_DIR` WHERE `USER_ID` = ? AND `NAME` = ? AND `ID_DIR` = ? LIMIT 1", [user('ID'), ALBUM_NAME, intval($dir['ID'])]) == 1){
    
    error('Альбом с таким названием уже существует в этой директории');
    redirect('/m/photos/add_dir/?dir='.$dir['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/photos/add_dir/?dir='.$dir['ID'].'&'.TOKEN_URL);
  
  }
  
  db::get_set("INSERT INTO `PHOTOS_DIR` (`NAME`, `PRIVATE`, `USER_ID`, `ID_DIR`, `PASSWORD`, `PASSWORD_SHOW`) VALUES (?, ?, ?, ?, ?, ?)", [ALBUM_NAME, $private, user('ID'), intval($dir['ID']), $password, ALBUM_PASSWORD]);
  
  success('Альбом успешно создан');
  redirect($url);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/photos/add_dir/?dir=<?=$dir['ID']?>&<?=TOKEN_URL?>'>
<?=html::input('name', 'Введите название', null, null, null, 'form-control-100', 'text', null, 'folder')?>
<?=html::select('private', array(
  0 => ['Всем', 0], 
  1 => ['Мне и друзьям', 1], 
  2 => ['Только мне', 2]
), 'Доступ', 'form-control-100-modify-select', 'lock')?>  
<?=html::input('password', 'Пароль', null, null, null, 'form-control-100', 'text', null, 'key')?>
<?=html::button('button ajax-button', 'ok_photos_album', 'plus', 'Добавить')?>  
<a class='button-o' href='<?=$url?>'><?=lg('Отмена')?></a>
<form>
</div>
<?
  
back($url);  
acms_footer();