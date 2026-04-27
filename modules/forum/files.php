<?php  
$them = db::get_string("SELECT `ID`,`NAME` FROM `FORUM_THEM` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);   
acms_header(lg('Файлы темы - %s', tabs($them['NAME'])));
is_active_module('PRIVATE_FORUM');

if (!isset($them['ID'])) {
  
  error('Неверная директива');
  redirect('/');
  
}

if (get('get') == 'videos') {
  
  $root = 'videos';
  
}elseif (get('get') == 'music') {
  
  $root = 'music';
  
}elseif (get('get') == 'files') {
  
  $root = 'files';
  
}else{
  
  $root = 'photos';
  
}
  
?> 
<div class='menu-nav-content'>  
<a class='menu-nav <?=($root == 'photos' ? 'h' : null)?>' href='/m/forum/files/?id=<?=$them['ID']?>&<?=TOKEN_URL?>'>
<?=lg('Фото')?>
</a>    
<a class='menu-nav <?=($root == 'videos' ? 'h' : null)?>' href='/m/forum/files/?id=<?=$them['ID']?>&<?=TOKEN_URL?>&get=videos'>
<?=lg('Видео')?>
</a>    
<a class='menu-nav <?=($root == 'music' ? 'h' : null)?>' href='/m/forum/files/?id=<?=$them['ID']?>&<?=TOKEN_URL?>&get=music'>
<?=lg('Музыка')?>
</a>    
<a class='menu-nav <?=($root == 'files' ? 'h' : null)?>' href='/m/forum/files/?id=<?=$them['ID']?>&<?=TOKEN_URL?>&get=files'>
<?=lg('Файлы')?>
</a>  
</div>
<?
  
$column = db::get_column("SELECT COUNT(DISTINCT `ATTACHMENTS`.`ID`) AS `count` FROM `COMMENTS` LEFT JOIN `ATTACHMENTS` ON (`COMMENTS`.`OBJECT_TYPE` = 'forum_comments' AND `COMMENTS`.`OBJECT_ID` = ? AND `ATTACHMENTS`.`ID_POST` = `COMMENTS`.`ID`) WHERE `ATTACHMENTS`.`ACT` = '1'", [$them['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}
  
$data = db::get_string_all("SELECT `ATTACHMENTS`.`TYPE`,`ATTACHMENTS`.`OBJECT_ID` FROM `COMMENTS` LEFT JOIN `ATTACHMENTS` ON (`COMMENTS`.`OBJECT_TYPE` = 'forum_comments' AND `COMMENTS`.`OBJECT_ID` = ? AND `ATTACHMENTS`.`ID_POST` = `COMMENTS`.`ID`) WHERE `ATTACHMENTS`.`ACT` = '1' GROUP BY `ATTACHMENTS`.`ID` ORDER BY `COMMENTS`.`TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$them['ID']]);
while ($list = $data->fetch()){
  
  if ($list['TYPE'] == 'photos') {
    
    $photo = db::get_string("SELECT `ID`,`SHIF`,`NAME` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if (isset($photo['ID'])) {
      
      ?>
      <a href="/m/photos/show/?id=<?=$photo['ID']?>">
      <div class="list-menu hover">
      <div class="files-info-list">
      <div class="files-ext">
      <img src="/files/upload/photos/150x150/<?=$photo['SHIF']?>.jpg" style="max-width: 60px" class="img">
      </div>
      <div class="files-info">
      <b><font color="#484F54"><?=crop_text(tabs($photo['NAME']), 0, 25)?></font></b>
      </div>
      </div>
      </div>
      </a>
      <?
        
    }else{
      
      ?>
      <div class="list-menu">
      <?=lg('Файл был удален')?>
      </div>
      <?
      
    }
    
  }
  
  if ($list['TYPE'] == 'videos') {
    
    $video = db::get_string("SELECT `NAME`,`ID`,`EXT` FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if (isset($video['ID'])) {
      
      ?>
      <a href="/m/videos/show/?id=<?=$video['ID']?>">
      <div class="list-menu hover">
      <div class="files-info-list">
      <div class="files-ext">
      <?=file::ext($video['EXT'])?>
      </div>
      <div class="files-info">
      <b><font color="#484F54"><?=crop_text(tabs($video['NAME']), 0, 25)?></font></b>
      </div>
      </div>
      </div>
      </a>
      <?
        
    }else{
      
      ?>
      <div class="list-menu">
      <?=lg('Файл был удален')?>
      </div>
      <?
      
    }
    
  }
  
  if ($list['TYPE'] == 'music') {
    
    $music = db::get_string("SELECT `ARTIST`,`NAME`,`EXT`,`ID` FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if (isset($music['ID'])) {
      
      ?>
      <a href="/m/music/show/?id=<?=$music['ID']?>">
      <div class="list-menu hover">
      <div class="files-info-list">
      <div class="files-ext">
      <?=file::ext($music['EXT'])?>
      </div>
      <div class="files-info">
      <b><font color="#484F54"><?=crop_text(tabs($music['ARTIST']), 0, 25)?> - <?=crop_text(tabs($music['NAME']), 0, 25)?></font></b>
      </div>
      </div>
      </div>
      </a>
      <?
        
    }else{
      
      ?>
      <div class="list-menu">
      <?=lg('Файл был удален')?>
      </div>
      <?
      
    }
    
  }
  
  if ($list['TYPE'] == 'files') {
    
    $files = db::get_string("SELECT `NAME`,`EXT`,`ID` FROM `FILES` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if (isset($files['ID'])) {
      
      ?>
      <a href="/m/files/show/?id=<?=$files['ID']?>">
      <div class="list-menu hover">
      <div class="files-info-list">
      <div class="files-ext">
      <?=file::ext($files['EXT'])?>
      </div>
      <div class="files-info">
      <b><font color="#484F54"><?=crop_text(tabs($files['NAME']), 0, 25)?></font></b>
      </div>
      </div>
      </div>
      </a>
      <?
        
    }else{
      
      ?>
      <div class="list-menu">
      <?=lg('Файл был удален')?>
      </div>
      <?
      
    }
    
  }

}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/forum/files/?id='.$them['ID'].'&get='.$root.'&', $spage, $page, 'list');

back('/m/forum/show/?id='.$them['ID']);
acms_footer();