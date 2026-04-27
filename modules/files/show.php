<?php
$file = db::get_string("SELECT * FROM `FILES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
acms_header(lg('Файл %s', tabs($file['NAME'])));
is_active_module('PRIVATE_FILES');

if (!isset($file['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

$dir = db::get_string("SELECT * FROM `FILES_DIR` WHERE `ID` = ? LIMIT 1", [$file['ID_DIR']]);
$account['ID'] = $file['USER_ID'];

if (intval($dir['ID']) > 0){
  
  if (MANAGEMENT == 0){
    
    if (access('files', null) == false && $file['SHOW'] == 0 || access('files', null) == false && $file['SHOW'] > 1 && $file['SHOW'] != user('ID')){
      
      require_once (ROOT.'/modules/files/plugins/private.php');
      
    }
  
  }
  
}

/*
---------
Просмотры
---------
*/

if (user('ID') > 0){
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $file['ID'], 'files']) == 0){
    
    db::get_add("INSERT INTO `EYE` (`USER_ID`, `TIME`, `OBJECT_ID`, `TYPE`) VALUES (?, ?, ?, ?)", [user('ID'), TM, $file['ID'], 'files']);
    
    if ($file['TIME'] > TM - 9800) {
      
      db::get_set("UPDATE `FILES` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$file['ID']]);
      db::get_set("UPDATE `DOWNLOADS` SET `RATING` = `RATING` + '1' WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$file['ID'], 'files']);
      
    }
  
  }else{
    
    db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $file['ID'], 'files']);
    
  }

}

likes_ajax($file['ID'], 'files', $file['USER_ID'], 1);
dislikes_ajax($file['ID'], 'files');
$action = '/m/files/show/?id='.$file['ID'];

if (isset($dir['ID'])) {
  
  $dir_p = "<a href='/m/files/users/?id=".$file['USER_ID']."&dir=".$dir['ID']."'>".lg(tabs($dir['NAME']))."</a>";
  
}else{
  
  $dir_p = "<a href='/m/files/users/?id=".$file['USER_ID']."'>".lg('Файлы')."</a>";

}

$download = db::get_string("SELECT `ID_DIR` FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$file['ID'], 'files']);
$download_dir = db::get_string("SELECT `ID`,`NAME` FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [$download['ID_DIR']]);

if (access('downloads', null) == true || $file['USER_ID'] == user('ID') || access('files', null) == true){
  
  if (access('files', null) == true || $file['USER_ID'] == user('ID')){
    
    require_once (ROOT.'/modules/files/plugins/delete.php');
    
  }
  
  ?><div class='list'><?
    
  if (access('files', null) == true || $file['USER_ID'] == user('ID')){
    
    ?>
    <a href='/m/files/edit/?id=<?=$file['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
    <a href='/m/files/show/?id=<?=$file['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
    
  }
  
  if (access('downloads', null) == true || $file['USER_ID'] == user('ID')){
    
    if (intval($dir['PRIVATE']) == 0) {
      
      if (config('PRIVATE_DOWNLOADS') == 1) {
        
        if (isset($download_dir['ID'])) {
          
          ?>
          <a href='/m/downloads/?id=<?=$download_dir['ID']?>&id_file=<?=$file['ID']?>&type=files&<?=TOKEN_URL?>&get=delete_file' class='btn'><?=icons('times', 15, 'fa-fw')?> <?=lg('Удалить из загрузок')?></a>
          <a href='/m/downloads/?id_file=<?=$file['ID']?>&type=files&<?=TOKEN_URL?>' class='btn'><?=icons('arrows', 15, 'fa-fw')?> <?=lg('Переместить в загрузках')?></a>
          <?
          
        }else{
          
          ?>
          <a href='/m/downloads/?id_file=<?=$file['ID']?>&type=files&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить в загрузки')?></a>
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
<?=file::ext($file['EXT'], 'small')?> <b><?=tabs($file['NAME'])?>.<?=tabs($file['EXT'])?></b> <?=($file['ADULT'] == 1 ? '<span class="adult" style="top: -1px">18+</span>' : null)?>
</div>
  
<?php if ($file['USER_ID'] != user('ID')) : ?>
<?php 
$adult_set = $file['ADULT'];  
require (ROOT.'/system/connections/adult.php'); 
?>
<?php endif ?>

<div class='list-menu'>

<div class="files-info-list">
<div class="files-ext">
<?=file::ext($file['EXT'])?>
</div>
<div class="files-info">
<b><font color="#484F54"><?=crop_text(tabs($file['NAME']), 0, 35)?></font></b>
</div>
</div>
  
<?php
$html_screen = null;
$html_screen2 = null;
$hs = 0;
$data4 = db::get_string_all("SELECT * FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$file['ID'], 'files_screen']);
while ($list4 = $data4->fetch()) {
  
  $hs++;
  
  if ($hs == 1) {
    
    $html_screen .= '<a ajax="no" href="/files/upload/files/screen/source/'.$list4['ID'].'.jpg"><img src="/files/upload/files/screen/source/'.$list4['ID'].'.jpg" style="max-width: 250px"></a><br />';
    
  }
  
  if ($hs > 1) {
    
    $html_screen2 .= '<a ajax="no" href="/files/upload/files/screen/source/'.$list4['ID'].'.jpg"><img src="/files/upload/files/screen/'.$list4['ID'].'.jpg" style="max-width: 80px; margin-right: 5px; margin-bottom: 7px"></a>';
    
  }

}

if (str($html_screen) > 0) {
  
  ?>
  <div class='list-menu' style='background-color: #313A3E; padding: 12px; text-align: center; margin-top: -1px; overflow-x: hidden; overflow-y: auto;'>
  <?=$html_screen?>
  <?
    
  if (str($html_screen2) > 0) {  
    
    ?>
    <br />
    <div class='files-main-list'>
    <?=$html_screen2?>
    </div>
    <?
      
  }
  
  ?></div><?
  
}  
?>

<br />
<?=(str($file['MESSAGE']) > 0 ? text($file['MESSAGE']).'<br /><br />' : null)?>
  
<?php
$l1 = db::get_column("SELECT COUNT(`ID`) FROM `FILES` WHERE `ID` > ? AND `USER_ID` = ? AND `ID_DIR` = ?", [$file['ID'], $file['USER_ID'], $file['ID_DIR']]) + 1;
$l2 = db::get_column("SELECT COUNT(`ID`) FROM `FILES` WHERE `USER_ID` = ? AND `ID_DIR` = ?", [$file['USER_ID'], $file['ID_DIR']]);
$back = db::get_string("SELECT `ID` FROM `FILES` WHERE `USER_ID` = ? AND `ID` > ? AND `ID_DIR` = ? ORDER BY `ID` ASC LIMIT 1", [$file['USER_ID'], $file['ID'], $file['ID_DIR']]);
$forward = db::get_string("SELECT `ID` FROM `FILES` WHERE `USER_ID` = ? AND `ID` < ? AND `ID_DIR` = ? ORDER BY `ID` DESC LIMIT 1", [$file['USER_ID'], $file['ID'], $file['ID_DIR']]);

?>  
<center>
<?

if (isset($back['ID'])){
  
  ?>
  <a href='/m/files/show/?id=<?=$back['ID']?>' class='listing-left'>
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
  <a href='/m/files/show/?id=<?=$forward['ID']?>' class='listing-right'>
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
<?=user::avatar($file['USER_ID'], 45)?> 
</div>
<div class='user-login-mini' style='top: 0px; left: 55px;'>
<?=user::login($file['USER_ID'], 0, 1)?><br />
<div class='time'><?=ftime($file['TIME'])?></div>
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
<a ajax='no' href='/file/<?=$file['ID']?>/' download class='download'><?=lg('Скачать')?> <span><?=size_file($file['SIZE'])?></span></a> 
  
<?php $width = 33; ?>
<?php $abuse = null; ?>
<?php if (user('ID') > 0 && user('ID') != $file['USER_ID']) : ?>
<?php $abuse = "<a href='/m/abuse/files/?id=".$file['ID']."' class='menu-sw-cont-left-25'>".icons('flag', 18, 'fa-fw')."</a>"; ?>
<?php $width = 25; ?>
<?php endif ?>
  
<div id='like'>
<?=likes_list($file['ID'], 'files', $action)?>
<div class='menu-sw-cont'>  
<?=$abuse?><a class='menu-sw-cont-left-<?=$width?>' href="/m/eye/?id=<?=$file['ID']?>&url=<?=base64_encode($action)?>&type=files&<?=TOKEN_URL?>"><?=icons('eye', 18, 'fa-fw')?> <?=db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$file['ID'], 'files'])?></a><?=mlikes($file['ID'], $action, 'files', 'menu-sw-cont-left-'.$width)?><?=mdislikes($file['ID'], $action, 'files', 'menu-sw-cont-left-'.$width)?>
</div>
</div>    
</div>  
  
</div> 
<div class='list'>
<b><?=lg('Комментарии')?></b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$file['ID'], 'files_comments'])?></span>
</div>  
<?
 
if (user('ID') == 0 || MANAGEMENT == 0 && $file['PRIVATE_COMMENTS'] == 2 && $file['USER_ID'] != user('ID') || MANAGEMENT == 0 && $file['PRIVATE_COMMENTS'] == 1 && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $file['USER_ID']]) == 0 && $file['USER_ID'] != user('ID')){
  
  $comments_set = 'Извините, для вас комментирование недоступно';
  
}

comments($action, 'files', 1, 'message', $file['USER_ID'], $file['ID']);

back('/m/files/users/?id='.$file['USER_ID'].'&dir='.$file['ID_DIR']); 
  
acms_footer();