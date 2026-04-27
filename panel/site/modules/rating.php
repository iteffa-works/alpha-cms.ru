<?php
  
/*
----------------
Удаление медалей
----------------
*/

if (get('delete_medal')){
  
  get_check_valid();
  
  $medal = db::get_string("SELECT `ID`,`EXT` FROM `RATING_MEDAL` WHERE `ID` = ? LIMIT 1", [intval(get('delete_medal'))]);
  
  if (isset($medal['ID'])){
    
    @unlink(ROOT.'/files/upload/medal/'.$medal['ID'].'.'.$medal['EXT']);  
    db::get_set("DELETE FROM `RATING_MEDAL` WHERE `ID` = ? LIMIT 1", [$medal['ID']]);
    
  }
  
}

/*
----------------------
Редактирование медалей
----------------------
*/

if (get('edit_medal')){
  
  $medal = db::get_string("SELECT * FROM `RATING_MEDAL` WHERE `ID` = ? LIMIT 1", [intval(get('edit_medal'))]);
  
  if (!isset($medal['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=rating');
    
  }
  
  if (post('ok_edit_medal')){
    
    $from = abs(intval(post('from')));
    $before = abs(intval(post('before')));
    
    if ($from > $before){
      
      error('Значение в поле От не может быть больше значения в поле До');
      redirect('/admin/site/modules/?mod=rating&edit_medal='.$medal['ID']);
    
    }
    
    if ($before <= 0){
      
      error('Значение в поле До не может быть меньше 0');
      redirect('/admin/site/modules/?mod=rating&edit_medal='.$medal['ID']);
    
    }
    
    db::get_set("UPDATE `RATING_MEDAL` SET `FROM` = ?, `BEFORE` = ? WHERE `ID` = ? LIMIT 1", [$from, $before, $medal['ID']]);
    
    success('Изменения успешно приняты');
    redirect('/admin/site/modules/?mod=rating&get=medal');
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/modules/?mod=rating&edit_medal=<?=$medal['ID']?>'>
  <?
    
  html::input('from', 'От', 'От какой единицы рейтинга отображать медаль у пользователя', null, $medal['FROM'], 'form-control-30', null, null, 'bar-chart');
  html::input('before', 'До', 'До какой единицы рейтинга отображать медаль у пользователя', null, $medal['BEFORE'], 'form-control-30', null, null, 'bar-chart');  
 
  html::button('button ajax-button', 'ok_edit_medal', 'save', 'Сохранить изменения');
  
  ?>
  </form>
  </div>
  <?
  
  back('/admin/site/modules/?mod=rating&get=medal');
  acms_footer();
  
}

/*
--------------
Список медалей
--------------
*/

if (get('get') == 'medal'){
  
  ?><div id='medal'><?
  
  $column = db::get_column("SELECT COUNT(*) FROM `RATING_MEDAL` WHERE `ACT` = ?", [1]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  ?>
  <div class='list-body'>
    
  <div class='list-menu list-title'>
  <?=lg('Медали')?> <span class='count'><?=$column?></span>
  </div>
    
  <div class='list-menu'>
  <a href='/admin/site/modules/?mod=rating&get=add_medal' class='button'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить медали')?></a>
  </div>    
  <?
  
  if ($column == 0){ 
    
    html::empty('Пока нет медалей');
  
  }
  
  $data = db::get_string_all("SELECT * FROM `RATING_MEDAL` WHERE `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [1]);  
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <img src='/files/upload/medal/<?=$list['ID']?>.<?=$list['EXT']?>'><br />
    <?=lg('От')?>: <?=$list['FROM']?> - <?=lg('до')?>: <?=$list['BEFORE']?>
    <div class='button-optimize-div'></div>
    <a href='/admin/site/modules/?mod=rating&edit_medal=<?=$list['ID']?>' class='button3 button-optimize'><?=ICONS('pencil', 15, 'fa-fw')?></a>
    <button onclick="request('/admin/site/modules/?mod=rating&get=medal&delete_medal=<?=$list['ID']?>&<?=TOKEN_URL?>', '#medal')" class='button2 button-optimize'><?=ICONS('trash', 15, 'fa-fw')?></button>      
    </div>
    <?
    
  }
  
  get_page('/admin/site/modules/?mod=rating&get=medal&', $spage, $page, 'list-menu');
  
  ?></div></div><?  
    
  back('/admin/site/modules/?mod=rating');
  acms_footer();
  
}

/*
------------------
Добавление медалей
------------------
*/
  
if (get('get') == 'add_medal'){
  
  ?>
  <div class='list-body'>
    
  <div class='list-menu list-title'>
  <?=lg('Добавление медалей')?>
  </div>
    
  <div class='list-menu'>    
  <?=attachments_result()?>
  <a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/medal.php?url=<?=base64_encode(REQUEST_URI)?>', 'attachments_upload')" class="button3"><?=icons('upload', 15, 'fa-fw')?> <?=lg('Загрузить')?></a> 
  </div>
    
  <div id='upload-medal'>
  <?
    
  $column = db::get_column("SELECT COUNT(*) FROM `RATING_MEDAL` WHERE `ACT` = ?", [0]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
    
  if ($column == 0){ 
    
    html::empty('Пока ничего не загружено');
  
  }else{
    
    ?>
    <div class='list-menu'>    
    <?=lg('Настройте и сохраните эти медали для рейтинга')?>:
    </div>
    <?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `RATING_MEDAL` WHERE `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [0]);  
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <img src='/files/upload/medal/<?=$list['ID']?>.<?=$list['EXT']?>'>
    <div class='button-optimize-div'></div>
    <button onclick="request('/admin/site/modules/?mod=rating&get=add_medal&delete_medal=<?=$list['ID']?>&<?=TOKEN_URL?>', '#upload-medal')" class='button2 button-optimize'><?=ICONS('trash', 15, 'fa-fw')?></button><br /><br />
      
    <?php
    if (post('ok_save_medal'.$list['ID'])){
      
      $from = abs(intval(post('from'.$list['ID'])));
      $before = abs(intval(post('before'.$list['ID'])));
      
      if ($from > $before){
        
        error('Значение в поле От не может быть больше значения в поле До');
        redirect('/admin/site/modules/?mod=rating&get=add_medal');
      
      }
      
      if ($before <= 0){
        
        error('Значение в поле До не может быть меньше 0');
        redirect('/admin/site/modules/?mod=rating&get=add_medal');
      
      }
      
      db::get_set("UPDATE `RATING_MEDAL` SET `BEFORE` = ?, `FROM` = ?, `ACT` = ? WHERE `ID` = ? LIMIT 1", [$before, $from, 1, $list['ID']]);
      
      success('Медаль успешно сохранена');
      redirect('/admin/site/modules/?mod=rating&get=add_medal');
    
    }      
    ?>
    
    <form method='post' class='ajax-form<?=$list['ID']?>' action='/admin/site/modules/?mod=rating&get=add_medal'>      
    <?=html::input('from'.$list['ID'], 'От', 'От какой единицы рейтинга отображать медаль у пользователя', null, null, 'form-control-30', null, 'bar-chart')?>
    <?=html::input('before'.$list['ID'], 'До', 'До какой единицы рейтинга отображать медаль у пользователя', null, null, 'form-control-30', null, 'bar-chart')?>  
    <?=html::button('button3 ajax-button', 'ok_save_medal'.$list['ID'], 'save', 'Сохранить медаль', $list['ID'])?> 
    </form> 
      
    </div>
    <?
  
  }
  
  get_page('/admin/site/modules/?mod=rating&get=add_medal&', $spage, $page, 'list-menu');
    
  ?></div></div><?
  
  back('/admin/site/modules/?mod=rating&get=medal');
  acms_footer();
  
}

/*
----------------------
Редактирование медалей
----------------------
*/

if (get('edit')){
  
  db_filter();
  post_check_valid();
  
  $medal = db::get_string("SELECT * FROM `RATING_MEDAL` WHERE `ID` = ? LIMIT 1", [intval(get('edit'))]);
  
  if (post('ok')){
    
    $from = intval(post('from'));
    $before = intval(post('before'));
    
    if ($from > $before){
      
      error('Значение в поле От не может быть больше значения в поле До');
      redirect('/admin/site/modules/?mod=rating&get=add_medal&edit='.$medal['ID']);
      
    }
    
    if ($before <= 0){
      
      error('Значение в поле До не может быть меньше 0');
      redirect('/admin/site/modules/?mod=rating&get=add_medal&edit='.$medal['ID']);
    
    }
    
    db::get_set("UPDATE `RATING_MEDAL` SET `FROM` = ?, `BEFORE` = ? WHERE `ID` = ? LIMIT 1", [$from, $before, $medal['ID']]);
    
    success('Изменения успешно приняты');
    redirect('/admin/site/modules/?mod=rating&get=add_medal');
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/modules/?mod=rating&get=add_medal&edit=<?=$medal['ID']?>'>
  <?
  
  html::input('from', null, 'От', 'От какой единицы рейтинга отображать медаль у пользователя', $medal['FROM'], 'form-control-30', null, null, 'rating');  
  html::input('before', null, 'До', 'До какой единицы рейтинга отображать медаль у пользователя', $medal['BEFORE'], 'form-control-30', null, null, 'rating');  
  html::button('button ajax-button', 'ok', 'save', 'Сохранить изменения');
  
  ?>
  </form>
  </div>
  <?
  
  back('/admin/site/modules/?mod=rating&get=add_medal');
  acms_footer();
  
}

/*
------------------
Настройки рейтинга
------------------
*/

$rating = @parse_ini_file(ROOT."/system/config/rating.ini", false);

if (post('ok')){
  
  db_filter();
  post_check_valid();
  
  $money = abs(post('money'));
  $blogs = abs(post('blogs'));
  $blogs_comm = abs(post('blogs_comm'));
  $photos = abs(post('photos'));
  $photos_comm = abs(post('photos_comm'));
  $videos = abs(post('videos'));
  $videos_comm = abs(post('videos_comm'));
  $files = abs(post('files'));
  $files_comm = abs(post('files_comm'));
  $music = abs(post('music'));
  $music_comm = abs(post('music_comm'));
  $guest = abs(post('guest'));
  $forum = abs(post('forum'));
  $forum_comm = abs(post('forum_comm'));
  
  ini::upgrade(ROOT.'/system/config/rating.ini', 'MONEY', $money);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'BLOGS', $blogs);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'BLOGS_COMMENTS', $blogs_comm);  
  ini::upgrade(ROOT.'/system/config/rating.ini', 'PHOTOS', $photos);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'PHOTOS_COMMENTS', $photos_comm);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'VIDEOS', $videos);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'VIDEOS_COMMENTS', $videos_comm);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'FILES', $files);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'FILES_COMMENTS', $files_comm);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'MUSIC', $music);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'MUSIC_COMMENTS', $music_comm);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'GUESTBOOK_COMMENTS', $guest);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'FORUM', $forum);
  ini::upgrade(ROOT.'/system/config/rating.ini', 'FORUM_COMMENTS', $forum_comm);
  
  success('Изменения успешно приняты');
  redirect('/admin/site/modules/?mod=rating');
  
}

?>
<div class='list-body'>
  
<div class='list-menu'>
<a href='/admin/site/modules/?mod=rating&get=medal' class='button'><?=icons('gear', 15, 'fa-fw')?> <?=lg('Управление медалями')?></a>
</div>   
  
<div class='list-menu'>
<form method='post' class='ajax-form' action='/admin/site/modules/?mod=rating'>
  
<?php

html::input('money', 0, 'Стоимость 1 единицы рейтинга в магазине услуг:', null, $rating['MONEY'], 'form-control-30', 'text', null, 'bar-chart');
html::input('blogs', 0, 'Начисление за добавление записи в блоге:', null, $rating['BLOGS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('blogs_comm', 0, 'Начисление за добавление комментария под записью в блоге:', null, $rating['BLOGS_COMMENTS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('photos', 0, 'Начисление за добавление фото:', null, $rating['PHOTOS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('photos_comm', 0, 'Начисление за добавление комментария под фото:', null, $rating['PHOTOS_COMMENTS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('videos', 0, 'Начисление за добавление видео:', null, $rating['VIDEOS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('videos_comm', 0, 'Начисление за добавление комментария под видео:', null, $rating['VIDEOS_COMMENTS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('files', 0, 'Начисление за добавление файла:', null, $rating['FILES'], 'form-control-30', 'text', null, 'bar-chart');
html::input('files_comm', 0, 'Начисление за добавление комментария под файлом:', null, $rating['FILES_COMMENTS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('music', 0, 'Начисление за добавление музыки:', null, $rating['MUSIC'], 'form-control-30', 'text', null, 'bar-chart');
html::input('music_comm', 0, 'Начисление за добавление комментария под музыкой:', null, $rating['MUSIC_COMMENTS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('guest', 0, 'Начисление за добавление комментария в гостевой:', null, $rating['GUESTBOOK_COMMENTS'], 'form-control-30', 'text', null, 'bar-chart');
html::input('forum', 0, 'Начисление за добавление темы на форуме:', null, $rating['FORUM'], 'form-control-30', 'text', null, 'bar-chart');
html::input('forum_comm', 0, 'Начисление за добавление комментария в тему на форуме:', null, $rating['FORUM_COMMENTS'], 'form-control-30', 'text', null, 'bar-chart');
html::button('button ajax-button', 'ok', 'save', 'Сохранить изменения');

?>

</form>
</div>
</div>