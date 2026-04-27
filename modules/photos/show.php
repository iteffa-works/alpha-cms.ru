<?php
$photo = db::get_string("SELECT * FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
acms_header(lg('Фото %s', tabs($photo['NAME']))); 
is_active_module('PRIVATE_PHOTOS');

if (!isset($photo['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

$dir = db::get_string("SELECT * FROM `PHOTOS_DIR` WHERE `ID` = ? LIMIT 1", [$photo['ID_DIR']]);
$account['ID'] = $photo['USER_ID'];

if (intval($dir['ID']) > 0){
  
  if (MANAGEMENT == 0){
    
    if (access('photos', null) == false && $photo['SHOW'] == 0){
      
      require (ROOT.'/modules/photos/plugins/private.php');
      
    }
  
  }
  
}

/*
---------
Просмотры
---------
*/

if (user('ID') > 0){
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $photo['ID'], 'photos']) == 0){
    
    db::get_add("INSERT INTO `EYE` (`USER_ID`, `TIME`, `OBJECT_ID`, `TYPE`) VALUES (?, ?, ?, ?)", [user('ID'), TM, $photo['ID'], 'photos']);
    
    if ($photo['TIME'] > TM - 9800) {
      
      db::get_set("UPDATE `PHOTOS` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$photo['ID']]);
      db::get_set("UPDATE `DOWNLOADS` SET `RATING` = `RATING` + '1' WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$photo['ID'], 'photos']);
      
    }
  
  }else{
    
    db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $photo['ID'], 'photos']);
    
  }

}

likes_ajax($photo['ID'], 'photos', $photo['USER_ID'], 1);
dislikes_ajax($photo['ID'], 'photos');
$action = '/m/photos/show/?id='.$photo['ID'];

if (isset($dir['ID'])) {
  
  $dir_p = "<a href='/m/photos/users/?id=".$photo['USER_ID']."&dir=".$dir['ID']."'>".lg(tabs($dir['NAME']))."</a>";
  
}else{
  
  $dir_p = "<a href='/m/photos/users/?id=".$photo['USER_ID']."'>".lg('Фото')."</a>";

}

$download = db::get_string("SELECT `ID_DIR` FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$photo['ID'], 'photos']);
$download_dir = db::get_string("SELECT `ID`,`NAME` FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [$download['ID_DIR']]);

if (access('downloads', null) == true || $photo['USER_ID'] == user('ID') || access('photos', null) == true){
  
  if (access('photos', null) == true || $photo['USER_ID'] == user('ID')){
    
    require_once (ROOT.'/modules/photos/plugins/delete.php');
  
  }
  
  ?><div class='list'><?
    
  if (access('photos', null) == true || $photo['USER_ID'] == user('ID')){
    
    ?>
    <a href='/m/photos/edit/?id=<?=$photo['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
    <a href='/m/photos/show/?id=<?=$photo['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
    
  }
  
  if (access('downloads', null) == true || $photo['USER_ID'] == user('ID')){
    
    if (intval($dir['PRIVATE']) == 0) {
      
      if (config('PRIVATE_DOWNLOADS') == 1) {
        
        if (isset($download_dir['ID'])) {
          
          ?>
          <a href='/m/downloads/?id=<?=$download_dir['ID']?>&id_file=<?=$photo['ID']?>&type=photos&<?=TOKEN_URL?>&get=delete_file' class='btn'><?=icons('times', 15, 'fa-fw')?> <?=lg('Удалить из загрузок')?></a>
          <a href='/m/downloads/?id_file=<?=$photo['ID']?>&type=photos&<?=TOKEN_URL?>' class='btn'><?=icons('arrows', 15, 'fa-fw')?> <?=lg('Переместить в загрузках')?></a>
          <?
          
        }else{
          
          ?>
          <a href='/m/downloads/?id_file=<?=$photo['ID']?>&type=photos&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить в загрузки')?></a>
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
<?=file::ext($photo['EXT'], 'small')?> <b><?=tabs($photo['NAME'])?>.<?=tabs($photo['EXT'])?></b> <?=($photo['ADULT'] == 1 ? '<span class="adult" style="top: -1px">18+</span>' : null)?>
</div>
 
<?php if ($photo['USER_ID'] != user('ID')) : ?>
<?php 
$adult_set = $photo['ADULT'];  
require (ROOT.'/system/connections/adult.php'); 
?>
<?php endif ?>

<div class='list-menu'>  
<center>
<img class='img' src='/files/upload/photos/source/<?=$photo['SHIF']?>.<?=$photo['EXT']?>' onclick="img_show('/files/upload/photos/source/<?=$photo['SHIF']?>.<?=$photo['EXT']?>', '/m/photos/show/?id=<?=$photo['ID']?>', '<?=tabs(crop_text($photo['NAME'],0,20))?>')" style='max-width: 90%'>
</center><br />
  
<?=(str($photo['MESSAGE']) > 0 ? text($photo['MESSAGE']).'<br /><br />' : null)?>  
  
<?php
$l1 = db::get_column("SELECT COUNT(`ID`) FROM `PHOTOS` WHERE `ID` > ? AND `USER_ID` = ? AND `ID_DIR` = ?", [$photo['ID'], $photo['USER_ID'], $photo['ID_DIR']]) + 1;
$l2 = db::get_column("SELECT COUNT(`ID`) FROM `PHOTOS` WHERE `USER_ID` = ? AND `ID_DIR` = ?", [$photo['USER_ID'], $photo['ID_DIR']]);
$back = db::get_string("SELECT `ID` FROM `PHOTOS` WHERE `USER_ID` = ? AND `ID` > ? AND `ID_DIR` = ? ORDER BY `ID` ASC LIMIT 1", [$photo['USER_ID'], $photo['ID'], $photo['ID_DIR']]);
$forward = db::get_string("SELECT `ID` FROM `PHOTOS` WHERE `USER_ID` = ? AND `ID` < ? AND `ID_DIR` = ? ORDER BY `ID` DESC LIMIT 1", [$photo['USER_ID'], $photo['ID'], $photo['ID_DIR']]);

?>  
<center>
<?

if (isset($back['ID'])){
  
  ?>
  <a href='/m/photos/show/?id=<?=$back['ID']?>' class='listing-left'>
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
  <a href='/m/photos/show/?id=<?=$forward['ID']?>' class='listing-right'>
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
<?=user::avatar($photo['USER_ID'], 45)?> 
</div>
<div class='user-login-mini' style='top: 0px; left: 55px;'>
<?=user::login($photo['USER_ID'], 0, 1)?><br />
<div class='time'><?=ftime($photo['TIME'])?></div>
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
<a ajax='no' href='/files/upload/photos/source/<?=$photo['SHIF']?>.<?=$photo['EXT']?>' download class='download'><?=lg('Скачать')?> <span><?=size_file($photo['SIZE'])?></span></a>
 
<?php $width = 33; ?>
<?php $abuse = null; ?>
<?php if (user('ID') > 0 && user('ID') != $photo['USER_ID']) : ?>
<?php $abuse = "<a href='/m/abuse/photos/?id=".$photo['ID']."' class='menu-sw-cont-left-25'>".icons('flag', 18, 'fa-fw')."</a>"; ?>
<?php $width = 25; ?>
<?php endif ?>
  
<div id='like'>
<?=likes_list($photo['ID'], 'photos', $action)?>
<div class='menu-sw-cont'>  
<?=$abuse?><a class='menu-sw-cont-left-<?=$width?>' href="/m/eye/?id=<?=$photo['ID']?>&url=<?=base64_encode($action)?>&type=photos&<?=TOKEN_URL?>"><?=icons('eye', 18, 'fa-fw')?> <?=db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$photo['ID'], 'photos'])?></a><?=mlikes($photo['ID'], $action, 'photos', 'menu-sw-cont-left-'.$width)?><?=mdislikes($photo['ID'], $action, 'photos', 'menu-sw-cont-left-'.$width)?>
</div>
</div>    
</div>  
  
</div> 
<div class='list'>
<b><?=lg('Комментарии')?></b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$photo['ID'], 'photos_comments'])?></span>
</div>  
<?
 
if (user('ID') == 0 || MANAGEMENT == 0 && $photo['PRIVATE_COMMENTS'] == 2 && $photo['USER_ID'] != user('ID') || MANAGEMENT == 0 && $photo['PRIVATE_COMMENTS'] == 1 && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $photo['USER_ID']]) == 0 && $photo['USER_ID'] != user('ID')){
  
  $comments_set = 'Извините, для вас комментирование недоступно';
  
}

comments($action, 'photos', 1, 'message', $photo['USER_ID'], $photo['ID']);

back('/m/photos/users/?id='.$photo['USER_ID'].'&dir='.$photo['ID_DIR']); 
  
acms_footer();