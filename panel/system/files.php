<?php  
html::title('Файловая среда');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Файловая среда')?>
</div>
  
<div class='list-body6'>
  
<div class='list-menu list-title'> 
<?=lg('Общие настройки')?>
</div>
  
<?php
if (post('ok_set')){
  
  db_filter();
  post_check_valid();

  $file_access = intval(post('file_access'));
  $video_screen = intval(post('video_screen'));
  $music_screen = intval(post('music_screen'));
  $video_player = intval(post('video_player'));
  $music_player = intval(post('music_player'));
  $photos_ext = ini_data_check(post('photos_ext'));
  $videos_ext = ini_data_check(post('videos_ext'));
  $music_ext = ini_data_check(post('music_ext'));
  $files_ext = ini_data_check(post('files_ext'));  

  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FILE_ACCESS', $file_access);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'VIDEO_SCREEN', $video_screen);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MUSIC_SCREEN', $music_screen);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'VIDEO_PLAYER', $video_player);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MUSIC_PLAYER', $music_player);  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'PHOTOS_EXT', $photos_ext);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'VIDEOS_EXT', $videos_ext);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MUSIC_EXT', $music_ext);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FILES_EXT', $files_ext);  
  
  success('Изменения успешно приняты');
  redirect('/admin/system/files/');

}  
?>
  
<div class='list-menu'>  
<form method='post' class='ajax-form' action='/admin/system/files/'>

<?php
html::select('file_access', array(
  1 => ['Разрешена', (config('FILE_ACCESS') == 1 ? "selected" : null)], 
  0 => ['Запрещена', (config('FILE_ACCESS') == 0 ? "selected" : null)]
), 'Выгрузка файлов на сайте', 'form-control-100-modify-select', 'upload');
?>
  
<?=html::checkbox('video_screen', 'Показ скриншотов к видео', 1, config('VIDEO_SCREEN'))?><br /><br />
<?=html::checkbox('music_screen', 'Показ обложек музыки', 1, config('MUSIC_SCREEN'))?><br /><br />  
<?=html::checkbox('video_player', 'Просмотр видео через AlphaPlayer', 1, config('VIDEO_PLAYER'))?><br /><br />
<?=html::checkbox('music_player', 'Прослушивание музыки через AlphaPlayer', 1, config('MUSIC_PLAYER'))?><br /><br />
  
<?=html::input('photos_ext', null, 'Допустимые форматы для выгрузки изображений (перечисляйте через запятую):', null, config('PHOTOS_EXT'), 'form-control-100', null, null, 'image')?>  
<?=html::input('videos_ext', null, 'Допустимые форматы для выгрузки видео (перечисляйте через запятую):', null, config('VIDEOS_EXT'), 'form-control-100', null, null, 'film')?>
<?=html::input('music_ext', null, 'Допустимые форматы для выгрузки музыки (перечисляйте через запятую):', null, config('MUSIC_EXT'), 'form-control-100', null, null, 'music')?>
<?=html::input('files_ext', null, 'Допустимые форматы для выгрузки файлов (перечисляйте через запятую):', null, config('FILES_EXT'), 'form-control-100', null, null, 'file')?> 
<?=html::button('button ajax-button', 'ok_set', 'save', 'Сохранить изменения')?>
  
</form>
</div>
  
</div>
  
<div class='list-body6'>
  
<div class='list-menu list-title'> 
<?=lg('Лимиты')?>
</div>
  
<?php
if (post('ok_limit')){
  
  db_filter();
  post_check_valid();
  
  $maxsize = intval(post('maxsize'));
  $maxupload = intval(post('maxupload'));
  $photos_limit = intval(post('photos_limit'));
  $photos_dir_limit = intval(post('photos_dir_limit'));
  $videos_limit = intval(post('videos_limit'));
  $videos_dir_limit = intval(post('videos_dir_limit'));
  $music_limit = intval(post('music_limit'));
  $music_dir_limit = intval(post('music_dir_limit'));
  $files_limit = intval(post('files_limit'));
  $files_dir_limit = intval(post('files_dir_limit'));
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MAXFILESIZE', $maxsize);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MAXFILEUPLOAD', $maxupload); 
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'PHOTOS_LIMIT', $photos_limit);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'PHOTOS_DIR_LIMIT', $photos_dir_limit);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'VIDEOS_LIMIT', $videos_limit);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'VIDEOS_DIR_LIMIT', $videos_dir_limit);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MUSIC_LIMIT', $music_limit);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MUSIC_DIR_LIMIT', $music_dir_limit);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FILES_LIMIT', $files_limit);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FILES_DIR_LIMIT', $files_dir_limit);
  
  success('Изменения успешно приняты');
  redirect('/admin/system/files/');

}  
?>
  
<div class='list-menu'>
<form method='post' class='ajax-form2' action='/admin/system/files/'>

<?php
html::select('maxupload', array(
  1 => ['1', (config('MAXFILEUPLOAD') == 1 ? "selected" : null)], 
  2 => ['2', (config('MAXFILEUPLOAD') == 2 ? "selected" : null)],
  3 => ['3', (config('MAXFILEUPLOAD') == 3 ? "selected" : null)], 
  4 => ['4', (config('MAXFILEUPLOAD') == 4 ? "selected" : null)],
  5 => ['5', (config('MAXFILEUPLOAD') == 5 ? "selected" : null)], 
  6 => ['6', (config('MAXFILEUPLOAD') == 6 ? "selected" : null)],
  7 => ['7', (config('MAXFILEUPLOAD') == 7 ? "selected" : null)], 
  8 => ['8', (config('MAXFILEUPLOAD') == 8 ? "selected" : null)],
  9 => ['9', (config('MAXFILEUPLOAD') == 9 ? "selected" : null)], 
  10 => ['10', (config('MAXFILEUPLOAD') == 10 ? "selected" : null)],
  11 => ['11', (config('MAXFILEUPLOAD') == 11 ? "selected" : null)], 
  12 => ['12', (config('MAXFILEUPLOAD') == 12 ? "selected" : null)],
  13 => ['13', (config('MAXFILEUPLOAD') == 13 ? "selected" : null)], 
  14 => ['14', (config('MAXFILEUPLOAD') == 14 ? "selected" : null)],
  15 => ['15', (config('MAXFILEUPLOAD') == 15 ? "selected" : null)], 
  16 => ['16', (config('MAXFILEUPLOAD') == 16 ? "selected" : null)],
  17 => ['17', (config('MAXFILEUPLOAD') == 17 ? "selected" : null)], 
  18 => ['18', (config('MAXFILEUPLOAD') == 18 ? "selected" : null)],
  19 => ['19', (config('MAXFILEUPLOAD') == 19 ? "selected" : null)], 
  20 => ['20', (config('MAXFILEUPLOAD') == 20 ? "selected" : null)]
), 'Кол-во выгружаемых файлов за раз', 'form-control-100-modify-select', 'upload');

html::select('maxsize', array(
  5242880 => ['5 MB', (config('MAXFILESIZE') == 5242880 ? "selected" : null)], 
  10485760 => ['10 MB', (config('MAXFILESIZE') == 10485760 ? "selected" : null)],
  15728640 => ['15 MB', (config('MAXFILESIZE') == 15728640 ? "selected" : null)], 
  20971520 => ['20 MB', (config('MAXFILESIZE') == 20971520 ? "selected" : null)],
  26214400 => ['25 MB', (config('MAXFILESIZE') == 26214400 ? "selected" : null)], 
  31457280 => ['30 MB', (config('MAXFILESIZE') == 31457280 ? "selected" : null)],
  36700160 => ['35 MB', (config('MAXFILESIZE') == 36700160 ? "selected" : null)], 
  41943040 => ['40 MB', (config('MAXFILESIZE') == 41943040 ? "selected" : null)],
  47185920 => ['45 MB', (config('MAXFILESIZE') == 47185920 ? "selected" : null)], 
  52428800 => ['50 MB', (config('MAXFILESIZE') == 52428800 ? "selected" : null)],
  57671680 => ['55 MB', (config('MAXFILESIZE') == 57671680 ? "selected" : null)], 
  62914560 => ['60 MB', (config('MAXFILESIZE') == 62914560 ? "selected" : null)],
  68157440 => ['65 MB', (config('MAXFILESIZE') == 68157440 ? "selected" : null)], 
  73400320 => ['70 MB', (config('MAXFILESIZE') == 73400320 ? "selected" : null)],
  78643200 => ['75 MB', (config('MAXFILESIZE') == 78643200 ? "selected" : null)], 
  83886080 => ['80 MB', (config('MAXFILESIZE') == 83886080 ? "selected" : null)],
  89128960 => ['85 MB', (config('MAXFILESIZE') == 89128960 ? "selected" : null)], 
  94371840 => ['90 MB', (config('MAXFILESIZE') == 94371840 ? "selected" : null)],
  99614720 => ['95 MB', (config('MAXFILESIZE') == 99614720 ? "selected" : null)], 
  104857600 => ['100 MB', (config('MAXFILESIZE') == 104857600 ? "selected" : null)],
  125829120 => ['120 MB', (config('MAXFILESIZE') == 125829120 ? "selected" : null)], 
  146800640 => ['140 MB', (config('MAXFILESIZE') == 146800640 ? "selected" : null)],
  167772160 => ['160 MB', (config('MAXFILESIZE') == 167772160 ? "selected" : null)], 
  188743680 => ['180 MB', (config('MAXFILESIZE') == 188743680 ? "selected" : null)],
  209715200 => ['200 MB', (config('MAXFILESIZE') == 209715200 ? "selected" : null)], 
  262144000 => ['250 MB', (config('MAXFILESIZE') == 262144000 ? "selected" : null)],
  314572800 => ['300 MB', (config('MAXFILESIZE') == 314572800 ? "selected" : null)], 
  367001600 => ['350 MB', (config('MAXFILESIZE') == 367001600 ? "selected" : null)],
  419430400 => ['400 MB', (config('MAXFILESIZE') == 419430400 ? "selected" : null)], 
  471859200 => ['450 MB', (config('MAXFILESIZE') == 471859200 ? "selected" : null)],
  524288000 => ['500 MB', (config('MAXFILESIZE') == 524288000 ? "selected" : null)],
  1073741824 => ['1 GB', (config('MAXFILESIZE') == 1073741824 ? "selected" : null)],
  2147483648 => ['2 GB', (config('MAXFILESIZE') == 2147483648 ? "selected" : null)]
), 'Макс. размер выгрузки файлов', 'form-control-100-modify-select', 'upload');
?>

*<?=lg('рекомендуется уточнить максимальный размер для выгрузки файлов на сервере')?><br /><br />
 
<?=html::input('photos_limit', null, 'Лимит на количество добавляемых пользователем изображений:', null, config('PHOTOS_LIMIT'), 'form-control-50', null, null, 'image')?>  
<?=html::input('photos_dir_limit', null, 'Лимит на количество добавляемых пользователем альбомов для изображений:', null, config('PHOTOS_DIR_LIMIT'), 'form-control-50', null, null, 'folder')?>  
<?=html::input('videos_limit', null, 'Лимит на количество добавляемых пользователем видео:', null, config('VIDEOS_LIMIT'), 'form-control-50', null, null, 'film')?>  
<?=html::input('videos_dir_limit', null, 'Лимит на количество добавляемых пользователем альбомов для видео:', null, config('VIDEOS_DIR_LIMIT'), 'form-control-50', null, null, 'folder')?>   
<?=html::input('music_limit', null, 'Лимит на количество добавляемых пользователем музыки:', null, config('MUSIC_LIMIT'), 'form-control-50', null, null, 'music')?>  
<?=html::input('music_dir_limit', null, 'Лимит на количество добавляемых пользователем альбомов для музыки:', null, config('MUSIC_DIR_LIMIT'), 'form-control-50', null, null, 'folder')?>   
<?=html::input('files_limit', null, 'Лимит на количество добавляемых пользователем файлов:', null, config('FILES_LIMIT'), 'form-control-50', null, null, 'file')?>  
<?=html::input('files_dir_limit', null, 'Лимит на количество добавляемых пользователем альбомов для файлов:', null, config('FILES_DIR_LIMIT'), 'form-control-50', null, null, 'folder')?> 
<?=html::button('button ajax-button', 'ok_limit', 'save', 'Сохранить изменения', 2)?>
  
</form>
</div>
  
</div>
<?

back('/admin/system/');
acms_footer();