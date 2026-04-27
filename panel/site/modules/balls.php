<?php

$balls = @parse_ini_file(ROOT."/system/config/balls.ini", false);

if (post('ok')){
  
  db_filter();
  post_check_valid();
  
  $balls = abs(intval(post('balls')));
  $exchange = abs(intval(post('exchange')));
  $photos_comments = abs(intval(post('photos_comments')));
  $videos_comments = abs(intval(post('videos_comments')));
  $files_comments = abs(intval(post('files_comments')));
  $music_comments = abs(intval(post('music_comments')));
  $blogs_comments = abs(intval(post('blogs_comments')));
  $guestbook_comments = abs(intval(post('guestbook_comments')));
  $photos = abs(intval(post('photos')));
  $videos = abs(intval(post('videos')));
  $files = abs(intval(post('files')));
  $blogs = abs(intval(post('blogs')));
  $music = abs(intval(post('music')));
  $forum = abs(intval(post('forum')));
  $forum_comments = abs(intval(post('forum_comments')));
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'BALLS', $balls);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'EXCHANGE', $exchange);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'PHOTOS_COMMENTS', $photos_comments);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'VIDEOS_COMMENTS', $videos_comments);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'FILES_COMMENTS', $files_comments);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'MUSIC_COMMENTS', $music_comments);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'BLOGS_COMMENTS', $blogs_comments);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'GUESTBOOK_COMMENTS', $guestbook_comments);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'PHOTOS', $photos);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'VIDEOS', $videos);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'FILES', $files);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'BLOGS', $blogs);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'MUSIC', $music);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'FORUM', $forum);
  ini::upgrade(ROOT.'/system/config/balls.ini', 'FORUM_COMMENTS', $forum_comments);
  
  success('Изменения успешно приняты');
  redirect('/admin/site/modules/?mod=balls');
  
}

?>
<div class='list'>
<form method='post' class='ajax-form' action='/admin/site/modules/?mod=balls'>
<font size='+2'><?=lg('Общие настройки')?></font><br /><br />
<?=html::input('exchange', 0, lg('Сколько баллов отдавать за %s при обмене в магазине услуг:', money(1, 2)), null, tabs($balls['EXCHANGE']), 'form-control-30', 'number', null, 'refresh')?>
<?=html::select('balls', array(
  0 => ['Закрыть доступ', (config('BALLS') == 0 ? "selected" : null)], 
  1 => ['Открыть доступ', (config('BALLS') == 1 ? "selected" : null)]
), 'Доступ к баллам для пользователей', 'form-control-100-modify-select', 'lock')?>
<font color='#FF4574'><?=lg('при закрытии доступа баллы на сайте будут полностью отключены для пользователей')?></font><br /><br />
<font size='+2'><?=lg('Настройки начисления баллов')?></font><br /><br />
<?=html::input('photos_comments', 0, 'Баллы за комментарии под фото:', null, tabs($balls['PHOTOS_COMMENTS']), 'form-control-30', 'number', null, 'comments')?>
<?=html::input('videos_comments', 0, 'Баллы за комментарии под видео:', null, tabs($balls['VIDEOS_COMMENTS']), 'form-control-30', 'number', null, 'comments')?>
<?=html::input('files_comments', 0, 'Баллы за комментарии под файлом:', null, tabs($balls['FILES_COMMENTS']), 'form-control-30', 'number', null, 'comments')?>
<?=html::input('music_comments', 0, 'Баллы за комментарии под музыкой:', null, tabs($balls['MUSIC_COMMENTS']), 'form-control-30', 'number', null, 'comments')?>
<?=html::input('blogs_comments', 0, 'Баллы за комментарии под записью в блоге:', null, tabs($balls['BLOGS_COMMENTS']), 'form-control-30', 'number', null, 'comments')?>
<?=html::input('guestbook_comments', 0, 'Баллы за комментарии в общей гостевой:', null, tabs($balls['GUESTBOOK_COMMENTS']), 'form-control-30', 'number', null, 'comments')?>
<?=html::input('forum_comments', 0, 'Баллы за комментарии под темой форума:', null, tabs($balls['FORUM_COMMENTS']), 'form-control-30', 'number', null, 'comments')?>
<?=html::input('photos', 0, 'Баллы за добавление фото:', null, tabs($balls['PHOTOS']), 'form-control-30', 'number', null, 'image')?>
<?=html::input('videos', 0, 'Баллы за добавление видео:', null, tabs($balls['VIDEOS']), 'form-control-30', 'number', null, 'film')?>
<?=html::input('files', 0, 'Баллы за добавление файла:', null, tabs($balls['FILES']), 'form-control-30', 'number', null, 'file')?>
<?=html::input('blogs', 0, 'Баллы за добавление записи в блог:', null, tabs($balls['BLOGS']), 'form-control-30', 'number', null, 'book')?>
<?=html::input('music', 0, 'Баллы за добавление музыки:', null, tabs($balls['MUSIC']), 'form-control-30', 'number', null, 'music')?>
<?=html::input('forum', 0, 'Баллы за добавление темы на форуме:', null, tabs($balls['FORUM']), 'form-control-30', 'number', null, 'comment')?>
<?=html::button('button ajax-button', 'ok', 'save', 'Сохранить изменения')?>
</form>
</div>