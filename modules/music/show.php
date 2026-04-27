<?php
$music = db::get_string("SELECT * FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
acms_header(lg('Музыка %s', tabs($music['FACT_NAME'])));
is_active_module('PRIVATE_MUSIC');

if (!isset($music['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

$dir = db::get_string("SELECT * FROM `MUSIC_DIR` WHERE `ID` = ? LIMIT 1", [$music['ID_DIR']]);
$account['ID'] = $music['USER_ID'];

if (intval($dir['ID']) > 0){
  
  if (MANAGEMENT == 0){
    
    if (access('music', null) == false && $music['SHOW'] == 0 || access('music', null) == false && $music['SHOW'] > 1 && $music['SHOW'] != user('ID')){
      
      require_once (ROOT.'/modules/music/plugins/private.php');
      
    }
  
  }
  
}

/*
---------
Просмотры
---------
*/

if (user('ID') > 0){
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $music['ID'], 'music']) == 0){
    
    db::get_add("INSERT INTO `EYE` (`USER_ID`, `TIME`, `OBJECT_ID`, `TYPE`) VALUES (?, ?, ?, ?)", [user('ID'), TM, $music['ID'], 'music']);
    
    if ($music['TIME'] > TM - 9800) {
      
      db::get_set("UPDATE `MUSIC` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$music['ID']]);
      db::get_set("UPDATE `DOWNLOADS` SET `RATING` = `RATING` + '1' WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$music['ID'], 'music']);
      
    }
  
  }else{
    
    db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $music['ID'], 'music']);
    
  }

}

likes_ajax($music['ID'], 'music', $music['USER_ID'], 1);
dislikes_ajax($music['ID'], 'music');
$action = '/m/music/show/?id='.$music['ID'];

if (isset($dir['ID'])) {
  
  $dir_p = "<a href='/m/music/users/?id=".$music['USER_ID']."&dir=".$dir['ID']."'>".lg(tabs($dir['NAME']))."</a>";
  
}else{
  
  $dir_p = "<a href='/m/music/users/?id=".$music['USER_ID']."'>".lg('Музыка')."</a>";

}

$download = db::get_string("SELECT `ID_DIR` FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$music['ID'], 'music']);
$download_dir = db::get_string("SELECT `ID`,`NAME` FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [$download['ID_DIR']]);

if (access('downloads', null) == true || $music['USER_ID'] == user('ID') || access('music', null) == true){
  
  if (access('music', null) == true || $music['USER_ID'] == user('ID')){
    
    require_once (ROOT.'/modules/music/plugins/delete.php');
  
  }
  
  ?><div class='list'><?
    
  if (access('music', null) == true || $music['USER_ID'] == user('ID')){
    
    ?>
    <a href='/m/music/edit/?id=<?=$music['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
    <a href='/m/music/show/?id=<?=$music['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
    
  }
  
  if (access('downloads', null) == true || $music['USER_ID'] == user('ID')){
    
    if (intval($dir['PRIVATE']) == 0) {
      
      if (config('PRIVATE_DOWNLOADS') == 1) {
        
        if (isset($download_dir['ID'])) {
          
          ?>
          <a href='/m/downloads/?id=<?=$download_dir['ID']?>&id_file=<?=$music['ID']?>&type=music&<?=TOKEN_URL?>&get=delete_file' class='btn'><?=icons('times', 15, 'fa-fw')?> <?=lg('Удалить из загрузок')?></a>
          <a href='/m/downloads/?id_file=<?=$music['ID']?>&type=music&<?=TOKEN_URL?>' class='btn'><?=icons('arrows', 15, 'fa-fw')?> <?=lg('Переместить в загрузках')?></a>
          <?
          
        }else{
          
          ?>
          <a href='/m/downloads/?id_file=<?=$music['ID']?>&type=music&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить в загрузки')?></a>
          <?
          
        }
      
      }
    
    }
    
    ?></div><?
    
  }
  
}

?>
<div class='list-body'>
  
<div class='list-menu'>
<?=file::ext($music['EXT'], 'small')?> <b><?=tabs($music['FACT_NAME'])?>.<?=tabs($music['EXT'])?></b> <?=($music['ADULT'] == 1 ? '<span class="adult" style="top: -1px">18+</span>' : null)?>
</div>
  
<?php if ($music['USER_ID'] != user('ID')) : ?>
<?php 
$adult_set = $music['ADULT'];  
require (ROOT.'/system/connections/adult.php'); 
?>
<?php endif ?>

<div class='list-menu'>
<center>
  
<?php
if (config('MUSIC_SCREEN') == 1){
  
  if (is_file(ROOT.'/files/upload/music/screen/240x240/'.$music['ID'].'.jpg')){
    
    ?>
    <img src="/files/upload/music/screen/<?=$music['ID']?>.jpg" class="img" style="max-width: 80%">
    <?
  
  }
  
}
?>  
</center>
  
<?php
if (config('MUSIC_PLAYER') == 1) {
  
  ?>
  <div class="files-info-list">
  <div class="files-ext">
  <button class="music-play" id="music<?=$music['ID']?>" play="0" onclick="PlayGo('<?=$music['ID']?>', '0', '0', 'none', 'none', 1)">
  <i class="fa fa-play fa-fw fa-lg"></i>
  </button>
  <?=file::ext($music['EXT'])?>
  </div>
  <div class="files-info">
  <b><?=crop_text(tabs($music['NAME']), 0, 37)?></b>
  <br />
  <div style="margin-top: 5px;">
  <?=crop_text(tabs($music['ARTIST']), 0, 37)?>
  </div>
  <div style="margin-top: 9px;">
  <?=tabs($music['DURATION'])?>
  </div>
  </div>
  <span class="music_post0" array="<?=$music['ID']?>,"></span>
  </div>
  <?
  
}else{
  
  ?>
  <div class="files-info-list">
  <div class="files-ext">
  <?=file::ext($music['EXT'])?>
  </div>
  <div class="files-info">
  <b><?=crop_text(tabs($music['NAME']), 0, 37)?></b>
  <br />
  <div style="margin-top: 5px;">
  <?=crop_text(tabs($music['ARTIST']), 0, 37)?>
  </div>
  <div style="margin-top: 9px;">
  <?=tabs($music['DURATION'])?>
  </div>
  </div>
  </div>
  <?
    
}  
?>

<?=(str($music['GENRE']) > 0 ? '<br />'.lg('Жанр').': '.lg(tabs($music['GENRE'])) : null)?>
<?=(str($music['ALBUM']) > 0 ? '<br />'.lg('Альбом').': '.lg(tabs($music['ALBUM'])) : null)?> 
<?=(str($music['BITRATE']) > 0 ? '<br />'.lg('Качество').': '.tabs($music['BITRATE']).' kbps' : null)?>
<br /><br />  
<?=(str($music['MESSAGE']) > 0 ? text($music['MESSAGE']).'<br /><br />' : null)?>
  
<?php
$l1 = db::get_column("SELECT COUNT(`ID`) FROM `MUSIC` WHERE `ID` > ? AND `USER_ID` = ? AND `ID_DIR` = ?", [$music['ID'], $music['USER_ID'], $music['ID_DIR']]) + 1;
$l2 = db::get_column("SELECT COUNT(`ID`) FROM `MUSIC` WHERE `USER_ID` = ? AND `ID_DIR` = ?", [$music['USER_ID'], $music['ID_DIR']]);
$back = db::get_string("SELECT `ID` FROM `MUSIC` WHERE `USER_ID` = ? AND `ID` > ? AND `ID_DIR` = ? ORDER BY `ID` ASC LIMIT 1", [$music['USER_ID'], $music['ID'], $music['ID_DIR']]);
$forward = db::get_string("SELECT `ID` FROM `MUSIC` WHERE `USER_ID` = ? AND `ID` < ? AND `ID_DIR` = ? ORDER BY `ID` DESC LIMIT 1", [$music['USER_ID'], $music['ID'], $music['ID_DIR']]);

?>  
<center>
<?

if (isset($back['ID'])){
  
  ?>
  <a href='/m/music/show/?id=<?=$back['ID']?>' class='listing-left'>
  <?=icons('chevron-left', 18, 'fa-fw')?> <?=lg('Назад')?>
  </a>
  <?
  
}else{
  
  ?>
  <span class='listing-left' style='color: #CADDE6'>
  <?=icons('chevron-left', 18, 'fa-fw')?> <?=lg('Назад')?>
  </span>
  <?
  
}

?>
<span class='listing-center'>
<?=$l1?> <?=lg('из')?> <?=$l2?>
</span>
<?

if (isset($forward['ID'])){
  
  ?>
  <a href='/m/music/show/?id=<?=$forward['ID']?>' class='listing-right'>
  <?=lg('Вперед')?> <?=icons('chevron-right', 18, 'fa-fw')?>
  </a>
  <?

}else{
  
  ?>
  <span class='listing-right' style='color: #CADDE6'>
  <?=lg('Вперед')?> <?=icons('chevron-right', 18, 'fa-fw')?>
  </span>
  <?
  
}

?>
</center>  
  
</div>
  
<div class='list-menu'>
<div class='user-info-mini'>
<div class='user-avatar-mini'>
<?=user::avatar($music['USER_ID'], 45)?> 
</div>
<div class='user-login-mini' style='top: 0px; left: 55px;'>
<?=user::login($music['USER_ID'], 0, 1)?><br />
<div class='time'><?=ftime($music['TIME'])?></div>
<?=lg('Альбом')?>: <font color='#D9D273'><?=icons('folder', 17, 'fa-fw')?></font> <?=$dir_p?> 
  
<?php
if (isset($download_dir['ID']) && config('PRIVATE_DOWNLOADS') == 1) {
  
  ?>
  <br />
  <?=lg('Загрузки')?>: <font color='#D9D273'><?=icons('folder', 17, 'fa-fw')?></font> <a href='/m/downloads/?id=<?=$download_dir['ID']?>'><?=lg(tabs($download_dir['NAME']))?></a>
  <?
  
}
?>
  
</div>
</div>
  
<br />
<a ajax='no' href='/music/<?=$music['ID']?>/' download class='download'><?=lg('Скачать')?> <span><?=size_file($music['SIZE'])?></span></a> 
  
<?php $width = 33; ?>
<?php $abuse = null; ?>
<?php if (user('ID') > 0 && user('ID') != $music['USER_ID']) : ?>
<?php $abuse = "<a href='/m/abuse/music/?id=".$music['ID']."' class='menu-sw-cont-left-25'>".icons('flag', 18, 'fa-fw')."</a>"; ?>
<?php $width = 25; ?>
<?php endif ?>  
  
<div id='like'>
<?=likes_list($music['ID'], 'music', $action)?>
<div class='menu-sw-cont'>  
<?=$abuse?><a class='menu-sw-cont-left-<?=$width?>' href="/m/eye/?id=<?=$music['ID']?>&url=<?=base64_encode($action)?>&type=music&<?=TOKEN_URL?>"><?=icons('eye', 18, 'fa-fw')?> <?=db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$music['ID'], 'music'])?></a><?=mlikes($music['ID'], $action, 'music', 'menu-sw-cont-left-'.$width)?><?=mdislikes($music['ID'], $action, 'music', 'menu-sw-cont-left-'.$width)?>
</div>
</div>    
</div>  
  
</div> 
<div class='list'>
<b><?=lg('Комментарии')?></b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$music['ID'], 'music_comments'])?></span>
</div>  
<?
 
if (user('ID') == 0 || MANAGEMENT == 0 && $music['PRIVATE_COMMENTS'] == 2 && $music['USER_ID'] != user('ID') || MANAGEMENT == 0 && $music['PRIVATE_COMMENTS'] == 1 && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $music['USER_ID']]) == 0 && $music['USER_ID'] != user('ID')){
  
  $comments_set = 'Извините, для вас комментирование недоступно';
  
}

comments($action, 'music', 1, 'message', $music['USER_ID'], $music['ID']);

back('/m/music/users/?id='.$music['USER_ID'].'&dir='.$music['ID_DIR']); 
  
acms_footer();