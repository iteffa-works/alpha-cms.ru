<?php
$video = db::get_string("SELECT * FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
acms_header(lg('Видео %s', tabs($video['NAME']))); 
is_active_module('PRIVATE_VIDEOS');

if (!isset($video['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

$dir = db::get_string("SELECT * FROM `VIDEOS_DIR` WHERE `ID` = ? LIMIT 1", [$video['ID_DIR']]);
$account['ID'] = $video['USER_ID'];

if (intval($dir['ID']) > 0){
  
  if (MANAGEMENT == 0){
    
    if (access('videos', null) == false && $video['SHOW'] == 0 || access('videos', null) == false && $video['SHOW'] > 1 && $video['SHOW'] != user('ID')){
      
      require_once (ROOT.'/modules/videos/plugins/private.php');
      
    }
  
  }
  
}

/*
---------
Просмотры
---------
*/

if (user('ID') > 0){
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $video['ID'], 'videos']) == 0){
    
    db::get_add("INSERT INTO `EYE` (`USER_ID`, `TIME`, `OBJECT_ID`, `TYPE`) VALUES (?, ?, ?, ?)", [user('ID'), TM, $video['ID'], 'videos']);
    
    if ($video['TIME'] > TM - 9800) {
      
      db::get_set("UPDATE `VIDEOS` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$video['ID']]);
      db::get_set("UPDATE `DOWNLOADS` SET `RATING` = `RATING` + '1' WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$video['ID'], 'videos']);
      
    }
  
  }else{
    
    db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $video['ID'], 'videos']);
    
  }

}

likes_ajax($video['ID'], 'videos', $video['USER_ID'], 1);
dislikes_ajax($video['ID'], 'videos');
$action = '/m/videos/show/?id='.$video['ID'];

if (isset($dir['ID'])) {
  
  $dir_p = "<a href='/m/videos/users/?id=".$video['USER_ID']."&dir=".$dir['ID']."'>".lg(tabs($dir['NAME']))."</a>";
  
}else{
  
  $dir_p = "<a href='/m/videos/users/?id=".$video['USER_ID']."'>".lg('Видео')."</a>";

}

$download = db::get_string("SELECT `ID_DIR` FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$video['ID'], 'videos']);
$download_dir = db::get_string("SELECT `ID`,`NAME` FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [$download['ID_DIR']]);

if (access('downloads', null) == true || $video['USER_ID'] == user('ID') || access('videos', null) == true){
  
  if (access('videos', null) == true || $video['USER_ID'] == user('ID')){
    
    require_once (ROOT.'/modules/videos/plugins/delete.php');
    
  }
  
  ?><div class='list'><?
    
  if (access('videos', null) == true || $video['USER_ID'] == user('ID')){
    
    ?>
    <a href='/m/videos/edit/?id=<?=$video['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
    <a href='/m/videos/show/?id=<?=$video['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
    
  }
  
  if (access('downloads', null) == true || $video['USER_ID'] == user('ID')){
    
    if (intval($dir['PRIVATE']) == 0) {
      
      if (config('PRIVATE_DOWNLOADS') == 1) {
        
        if (isset($download_dir['ID'])) {
          
          ?>
          <a href='/m/downloads/?id=<?=$download_dir['ID']?>&id_file=<?=$video['ID']?>&type=videos&<?=TOKEN_URL?>&get=delete_file' class='btn'><?=icons('times', 15, 'fa-fw')?> <?=lg('Удалить из загрузок')?></a>
          <a href='/m/downloads/?id_file=<?=$video['ID']?>&type=videos&<?=TOKEN_URL?>' class='btn'><?=icons('arrows', 15, 'fa-fw')?> <?=lg('Переместить в загрузках')?></a>
          <?
          
        }else{
          
          ?>
          <a href='/m/downloads/?id_file=<?=$video['ID']?>&type=videos&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить в загрузки')?></a>
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
<?=file::ext($video['EXT'], 'small')?> <b><?=tabs($video['NAME'])?>.<?=tabs($video['EXT'])?></b> <?=($video['ADULT'] == 1 ? '<span class="adult" style="top: -1px">18+</span>' : null)?>
</div>
  
<?php if ($video['USER_ID'] != user('ID')) : ?>
<?php 
$adult_set = $video['ADULT'];  
require (ROOT.'/system/connections/adult.php'); 
?>
<?php endif ?>

<div class='list-menu'>
<center>  
<?=video_player($video['ID'], $video['EXT'], $video['NAME'], 305)?>  
</center>

<br />
<?=(str($video['MESSAGE']) > 0 ? text($video['MESSAGE']).'<br /><br />' : null)?>
  
<?php
$l1 = db::get_column("SELECT COUNT(`ID`) FROM `VIDEOS` WHERE `ID` > ? AND `USER_ID` = ? AND `ID_DIR` = ?", [$video['ID'], $video['USER_ID'], $video['ID_DIR']]) + 1;
$l2 = db::get_column("SELECT COUNT(`ID`) FROM `VIDEOS` WHERE `USER_ID` = ? AND `ID_DIR` = ?", [$video['USER_ID'], $video['ID_DIR']]);
$back = db::get_string("SELECT `ID` FROM `VIDEOS` WHERE `USER_ID` = ? AND `ID` > ? AND `ID_DIR` = ? ORDER BY `ID` ASC LIMIT 1", [$video['USER_ID'], $video['ID'], $video['ID_DIR']]);
$forward = db::get_string("SELECT `ID` FROM `VIDEOS` WHERE `USER_ID` = ? AND `ID` < ? AND `ID_DIR` = ? ORDER BY `ID` DESC LIMIT 1", [$video['USER_ID'], $video['ID'], $video['ID_DIR']]);

?>  
<center>
<?

if (isset($back['ID'])){
  
  ?>
  <a href='/m/videos/show/?id=<?=$back['ID']?>' class='listing-left'>
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
  <a href='/m/videos/show/?id=<?=$forward['ID']?>' class='listing-right'>
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
<?=user::avatar($video['USER_ID'], 45)?> 
</div>
<div class='user-login-mini' style='top: 0px; left: 55px;'>
<?=user::login($video['USER_ID'], 0, 1)?><br />
<div class='time'><?=ftime($video['TIME'])?></div>
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
<a ajax='no' href='/video/<?=$video['ID']?>/' download class='download'><?=lg('Скачать')?> <span><?=size_file($video['SIZE'])?></span></a> 
  
<?php $width = 33; ?>
<?php $abuse = null; ?>
<?php if (user('ID') > 0 && user('ID') != $video['USER_ID']) : ?>
<?php $abuse = "<a href='/m/abuse/videos/?id=".$video['ID']."' class='menu-sw-cont-left-25'>".icons('flag', 18, 'fa-fw')."</a>"; ?>
<?php $width = 25; ?>
<?php endif ?>
  
<div id='like'>
<?=likes_list($video['ID'], 'videos', $action)?>
<div class='menu-sw-cont'>  
<?=$abuse?><a class='menu-sw-cont-left-<?=$width?>' href="/m/eye/?id=<?=$video['ID']?>&url=<?=base64_encode($action)?>&type=videos&<?=TOKEN_URL?>"><?=icons('eye', 18, 'fa-fw')?> <?=db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$video['ID'], 'videos'])?></a><?=mlikes($video['ID'], $action, 'videos', 'menu-sw-cont-left-'.$width)?><?=mdislikes($video['ID'], $action, 'videos', 'menu-sw-cont-left-'.$width)?>
</div>
</div>    
</div>  
  
</div> 
<div class='list'>
<b><?=lg('Комментарии')?></b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$video['ID'], 'videos_comments'])?></span>
</div>  
<?
 
if (user('ID') == 0 || MANAGEMENT == 0 && $video['PRIVATE_COMMENTS'] == 2 && $video['USER_ID'] != user('ID') || MANAGEMENT == 0 && $video['PRIVATE_COMMENTS'] == 1 && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $video['USER_ID']]) == 0 && $video['USER_ID'] != user('ID')){
  
  $comments_set = 'Извините, для вас комментирование недоступно';
  
}

comments($action, 'videos', 1, 'message', $video['USER_ID'], $video['ID']);

back('/m/videos/users/?id='.$video['USER_ID'].'&dir='.$video['ID_DIR']); 
  
acms_footer();