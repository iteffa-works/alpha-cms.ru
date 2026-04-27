<?php
$them = db::get_string("SELECT * FROM `FORUM_THEM` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$scsub = db::get_string("SELECT * FROM `FORUM_SUB_SECTION` WHERE `ID` = ? LIMIT 1", [$them['SUB_SECTION_ID']]);
$sc = db::get_string("SELECT `NAME`,`ID` FROM `FORUM_SECTION` WHERE `ID` = ? LIMIT 1", [$scsub['SECTION_ID']]);
acms_header(lg('Тема - %s', tabs($them['NAME'])), 'all', text($them['MESSAGE'], 0, 0, 0, 0));
is_active_module('PRIVATE_FORUM');

if (db::get_column("SELECT COUNT(*) FROM `FORUM_BAN` WHERE `USER_ID` = ? AND `BAN_TIME` > ? AND `BAN` = ? LIMIT 1", [user('ID'), TM, 0]) > 0 || db::get_column("SELECT COUNT(*) FROM `FORUM_BAN` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [user('ID'), 1]) > 0){
  
  error('Данная страница для вас недоступна. У вас имеется активная блокировка на форуме');
  redirect('/');

}

if (!isset($them['ID'])) {
  
  error('Неверная директива');
  redirect('/m/forum/sc/');

}

if (!isset($scsub['ID'])){
  
  error('Подраздел не найден');
  redirect('/m/forum/sc/');
  
}

if (!isset($sc['ID'])){
  
  error('Раздел не найден');
  redirect('/m/forum/sc/');
  
}

require_once (ROOT.'/modules/forum/plugins/private_sub_section.php');
require_once (ROOT.'/modules/forum/plugins/top.php');

/*
---------
Просмотры
---------
*/

if (user('ID') > 0){
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $them['ID'], 'forum']) == 0){
    
    db::get_add("INSERT INTO `EYE` (`USER_ID`, `TIME`, `OBJECT_ID`, `TYPE`) VALUES (?, ?, ?, ?)", [user('ID'), TM, $them['ID'], 'forum']);
    
    if ($them['TIME'] > TM - 9800) {
      
      db::get_set("UPDATE `FORUM_THEM` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$them['ID']]);
      
    }
  
  }else{
    
    db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $them['ID'], 'forum']);
    
  }

}

if (access('forum', null) == true || $them['USER_ID'] == user('ID')){
  
  require_once (ROOT.'/modules/forum/plugins/delete.php');
  
  ?>
  <div class='list'>
  <a href='/m/forum/edit_them/?id=<?=$them['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  <a href='/m/forum/show/?id=<?=$them['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
  <?
    
  if (access('forum', null) == true){
    
    require_once (ROOT.'/modules/forum/plugins/them_off_or_on.php');
    require_once (ROOT.'/modules/forum/plugins/them_ban.php');
    require_once (ROOT.'/modules/forum/plugins/them_secure.php');
    
    if ($them['ACTIVE'] == 1){
      
      ?>
      <a href='/m/forum/show/?id=<?=$them['ID']?>&them=off&<?=TOKEN_URL?>' class='btn'><?=icons('lock', 15, 'fa-fw')?> <?=lg('Закрыть')?></a>
      <?
      
    }else{
      
      ?>
      <a href='/m/forum/show/?id=<?=$them['ID']?>&them=on&<?=TOKEN_URL?>' class='btn'><?=icons('unlock', 15, 'fa-fw')?> <?=lg('Открыть')?></a>
      <?
      
    }
    
    ?>
    <a href='/m/block/forum/?id=<?=$them['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('ban', 15, 'fa-fw')?> <?=lg('Заблокировать автора')?></a>
    <a href='/m/block/forum_list/?id=<?=$them['USER_ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('ban', 15, 'fa-fw')?> <?=lg('История блокировок автора')?></a>  
    <?
      
    if ($them['BAN'] == 0){
      
      ?>
      <a href='/m/forum/show/?id=<?=$them['ID']?>&them=ban_on&<?=TOKEN_URL?>' class='btn'><?=icons('ban', 15, 'fa-fw')?> <?=lg('Заблокировать тему')?></a>
      <?
      
    }else{
      
      ?>      
      <a href='/m/forum/show/?id=<?=$them['ID']?>&them=ban_off&<?=TOKEN_URL?>' class='btn'><?=icons('ban', 15, 'fa-fw')?> <?=lg('Разблокировать тему')?></a>
      <?
      
    }
    
    if ($them['SECURE'] == 0){
      
      ?>
      <a href='/m/forum/show/?id=<?=$them['ID']?>&secure=on&<?=TOKEN_URL?>' class='btn'><?=icons('thumb-tack', 15, 'fa-fw')?> <?=lg('Закрепить тему')?></a>
      <?
      
    }else{
      
      ?>      
      <a href='/m/forum/show/?id=<?=$them['ID']?>&secure=off&<?=TOKEN_URL?>' class='btn'><?=icons('thumb-tack', 15, 'fa-fw')?> <?=lg('Открепить тему')?></a>
      <?
      
    }
    
  }
    
  ?></div><?
  
}

if ($them['BAN'] == 1){
  
  html::empty('Тема заблокирована администрацией', 'ban');
  back('/m/forum/sc/?id_sub='.$scsub['ID']);
  acms_footer();
  
}

?>
<div class='list-body'>
<div class='list-menu'>
<div class='user-info-mini'>
<div class='user-avatar-mini'>
<?=user::avatar($them['USER_ID'], 45, 1)?> 
</div>
<div class='user-login-mini' style='top: 4px; left: 55px;'>
<?=user::login($them['USER_ID'], 0, 1)?><br />
<span class='time'><?=ftime($them['TIME'])?></span>
</div>
</div>  
<br />
<b><?=($them['SECURE'] == 1 ? icons('thumb-tack', 15, 'fa-fw') : null)?> <?=tabs($them['NAME'])?></b>
<br />    
<?=attachments_files($them['ID'], 'forum', 320)?>
<br />
<?=text($them['MESSAGE'])?>
  
<?php
hooks::challenge('forum_them_foot', 'forum_them_foot');
hooks::run('forum_them_foot');
likes_ajax($them['ID'], 'forum', $them['USER_ID'], 1);
dislikes_ajax($them['ID'], 'forum');
$action = '/m/forum/show/?id='.$them['ID'];

?>
<br /><br />
<?=lg('Подраздел')?>:  
<a href='/m/forum/sc/?id_sub=<?=$scsub['ID']?>'>
<?=lg(tabs($scsub['NAME']))?>
</a>  
<br />
<?=lg('Раздел')?>:  
<a href='/m/forum/sc/?id=<?=$sc['ID']?>'>
<?=lg(tabs($sc['NAME']))?>  
</a>
<br /><br />
<?php if (user('ID') > 0 && $them['USER_ID'] == user('ID')) : ?>
<a href='/m/forum/show/?id=<?=$them['ID']?>&get=act&<?=TOKEN_URL?>' class='btn-o'><?=icons('arrow-up', 15, 'fa-fw')?> <?=lg('Поднять тему')?></a>
<a href='/m/forum/show/?id=<?=$them['ID']?>&get=top&<?=TOKEN_URL?>' class='btn-o'><?=icons('trophy', 15, 'fa-fw')?> <?=lg('В ТОП')?></a>
<?php endif ?> 
<?php if (user('ID') > 0 && $them['USER_ID'] != user('ID')) : ?>
<a href='/m/abuse/forum/?id=<?=$them['ID']?>&action=<?=base64_encode("/m/forum/show/?id=".$them['ID'])?>' class='btn-o'><?=icons('flag', 15, 'fa-fw')?> <?=lg('Пожаловаться')?></a>
<?php endif ?> 
<a href='/m/forum/files/?id=<?=$them['ID']?>' class='btn-o'><?=icons('file', 15, 'fa-fw')?> <?=lg('Файлы темы')?></a>
</div>
  
<?php if ($them['ACTIVE'] == 0 || $them['EDIT_TIME'] > 0) : ?>    
<div class='list-menu'><small>
<?php if ($them['EDIT_TIME'] > 0) : ?>
<?=icons('pencil', 12, 'fa-fw')?> <?=lg('Последний раз тему редактировал')?> <a href='/id<?=$them['EDIT_USER_ID']?>'><?=user::login_mini($them['EDIT_USER_ID'])?></a> - <?=ftime($them['EDIT_TIME'])?>
<br />
<?php endif ?>
<?php if ($them['ACT_TIME'] > 0) : ?>
<?=icons('clock-o', 12, 'fa-fw')?> <?=lg('Последняя активность в теме')?>: <?=ftime($them['ACT_TIME'])?>
<br />
<?php endif ?>
<?php if ($them['ACTIVE'] == 0) : ?>
<?=icons('lock', 12, 'fa-fw')?> <?=lg('Тема закрыта')?> <a href='/id<?=$them['ACTIVE_USER_ID']?>'><?=user::login_mini($them['ACTIVE_USER_ID'])?></a> - <?=ftime($them['ACTIVE_TIME'])?>
<br />
<?php endif ?>
<?php if ($them['TOP'] > TM) : ?>
<?=icons('trophy', 12, 'fa-fw')?> <?=lg('Тема размещена в ТОПЕ до')?>: <?=ftime($them['TOP'])?>
<br />
<?php endif ?>
</small>
<?php endif ?>

<div id='like'>
<?=likes_list($them['ID'], 'forum', $action)?>
<div class='menu-sw-cont'>  
<a class='menu-sw-cont-left-33' href="/m/eye/?id=<?=$them['ID']?>&url=<?=base64_encode($action)?>&type=forum&<?=TOKEN_URL?>"><?=icons('eye', 18, 'fa-fw')?> <?=db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$them['ID'], 'forum'])?></a><?=mlikes($them['ID'], $action, 'forum', 'menu-sw-cont-left-33')?><?=mdislikes($them['ID'], $action, 'forum', 'menu-sw-cont-left-33')?>
</div>
</div>
  
</div>
  
</div>
  
<div class='list'>
<b><?=lg('Комментарии')?></b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$them['ID'], 'forum_comments'])?></span>
</div>
  
<?  
if (user('ID') == 0 || $them['ACTIVE'] == 0){
  
  $comments_set = 'Извините, для вас комментирование недоступно';
  
}

comments($action, 'forum_comments', 1, 'message', $them['USER_ID'], $them['ID'], $scsub['ID']);

back('/m/forum/sc/?id_sub='.$scsub['ID']);
acms_footer();