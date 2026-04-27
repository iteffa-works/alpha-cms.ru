<?php
$blog = db::get_string("SELECT * FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
acms_header(($blog['SHARE'] == 0 ? lg('Запись - %s', tabs($blog['NAME'])) : lg('Репост')), 'all', text($blog['MESSAGE'], 0, 0, 0, 0));
is_active_module('PRIVATE_BLOGS');

if (!isset($blog['ID']) || $blog['COMMUNITY'] > 0) {
  
  error('Неверная директива');
  redirect('/m/blogs/');

}

require (ROOT.'/modules/blogs/plugins/private.php');

/*
---------
Просмотры
---------
*/

if (user('ID') > 0){
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $blog['ID'], 'blogs']) == 0){
    
    db::get_add("INSERT INTO `EYE` (`USER_ID`, `TIME`, `OBJECT_ID`, `TYPE`) VALUES (?, ?, ?, ?)", [user('ID'), TM, $blog['ID'], 'blogs']);
    
    if ($blog['TIME'] > TM - 9800) {
      
      db::get_set("UPDATE `BLOGS` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$blog['ID']]);
      
    }
  
  }else{
    
    db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $blog['ID'], 'blogs']);
    
  }

}

if (access('blogs', null) == true || $blog['USER_ID'] == user('ID')){
  
  require_once (ROOT.'/modules/blogs/plugins/delete.php');
  
}

if (user('ID') > 0){
  
  if ($blog['USER_ID'] != user('ID') && $blog['SHARE'] == 0 || access('blogs', null) == true || $blog['USER_ID'] == user('ID')){
    
    ?><div class='list'><?
      
  }
    
  if ($blog['USER_ID'] != user('ID') && $blog['SHARE'] == 0){
    
    ?><a href='/m/blogs/share/?id=<?=$blog['ID']?>&get=go' class='btn'><?=icons('mail-forward', 15, 'fa-fw')?> <?=lg('Поделиться')?></a> <?
   
  }
    
  if (access('blogs', null) == true || $blog['USER_ID'] == user('ID')){
    
    if ($blog['SHARE'] == 0){
      
      ?><a href='/m/blogs/edit/?id=<?=$blog['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a> <?        
        
    }else{
      
      ?><a href='/m/blogs/share/?id=<?=$blog['ID']?>&get=edit' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a> <?
      
    }
    
    ?>
    <a href='/m/blogs/show/?id=<?=$blog['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
  
  }
  
  if ($blog['USER_ID'] != user('ID') && $blog['SHARE'] == 0 || access('blogs', null) == true || $blog['USER_ID'] == user('ID')){
    
    ?></div><?
      
  }
  
}

?><div class='list-body'><?

if ($blog['PRIVATE'] == 0){
  
  $private = 'globe';
  
}elseif ($blog['PRIVATE'] == 1){
  
  $private = 'users';
  
}elseif ($blog['PRIVATE'] == 2){
  
  $private = 'lock';
  
}elseif (str($blog['PASSWORD']) > 0){
  
  $private = 'key';
  
}

?>
<div class='list-menu'>
<span style='float: right; color: #8394A0;'><?=icons($private, 17)?></span>
<div class='user-info-mini'>
<div class='user-avatar-mini'>
<?=user::avatar($blog['USER_ID'], 45, 1)?> 
</div>
<div class='user-login-mini' style='top: 4px; left: 55px;'>
<?=user::login($blog['USER_ID'], 0, 1)?><br />
<span class='time'><?=ftime($blog['TIME'])?></span>
</div>
</div>  
<br />
<?  
if ($blog['SHARE'] == 0){
  
  ?>
  <b><?=tabs($blog['NAME'])?></b>
  <br />    
  <?=attachments_files($blog['ID'], 'blogs', 320)?>
  <?
    
}else{
  
  $share = db::get_string("SELECT `NAME`,`MESSAGE`,`ID`,`COMMUNITY` FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [$blog['SHARE']]);
  
  ?>
  <div style='padding: 10px; border-top: 1px #DAE4EB solid; border-left: 1px #DAE4EB solid'>
  <a href='/m/<?=($share['COMMUNITY'] == 0 ? "blogs/show" : "communities/show_blog")?>/?id=<?=$share['ID']?>'><?=tabs($share['NAME'])?></a>
  <br /><br />
  <?=crop_text(text($share['MESSAGE']),0,250)?><br />
  <a href='/m/<?=($share['COMMUNITY'] == 0 ? "blogs/show" : "communities/show_blog")?>/?id=<?=$share['ID']?>'><?=lg('Читать полностью')?>...</a>
  </div>
  <?
  
} 
?>
  
<br />
<?=text($blog['MESSAGE'])?>    
  
<?php
hooks::challenge('blogs_foot', 'blogs_foot');
hooks::run('blogs_foot');
likes_ajax($blog['ID'], 'blogs', $blog['USER_ID'], 1);
dislikes_ajax($blog['ID'], 'blogs');
$action = '/m/blogs/show/?id='.$blog['ID'];

$cat = db::get_string("SELECT `NAME`,`ID` FROM `BLOGS_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$blog['ID_CATEGORY']]);
if (isset($cat['ID'])){
  
  ?>
  <br /><br />
  <a class='info blue' href='/m/blogs/categories/?id=<?=$cat['ID']?>'>
  <?=lg(tabs($cat['NAME']))?>
  </a>
  <?
  
}

if ($blog['SHARE'] == 0){
  
  $share = "<a class='menu-sw-cont-left-25' href='/m/blogs/share/?id=".$blog['ID']."'>".icons('share-square-o', 18, 'fa-fw')." ".db::get_column("SELECT COUNT(`ID`) FROM `BLOGS` WHERE `SHARE` = ? LIMIT 1", [$blog['ID']])."</a>";
  $share_width = 25;
  
}else{
  
  $share = null;
  $share_width = 33;
  
}

?>
<div id='like'>
<?=likes_list($blog['ID'], 'blogs', $action)?>
<div class='menu-sw-cont'> 
<?=$share?><a class='menu-sw-cont-left-<?=$share_width?>' href="/m/eye/?id=<?=$blog['ID']?>&url=<?=base64_encode($action)?>&type=blogs&<?=TOKEN_URL?>"><?=icons('eye', 18, 'fa-fw')?> <?=db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$blog['ID'], 'blogs'])?></a><?=mlikes($blog['ID'], $action, 'blogs', 'menu-sw-cont-left-'.$share_width)?><?=mdislikes($blog['ID'], $action, 'blogs', 'menu-sw-cont-left-'.$share_width)?>
</div>
</div>
  
</div>
  
</div>
  
<div class='list'>
<b><?=lg('Комментарии')?></b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$blog['ID'], 'blogs_comments'])?></span>
</div>
  
<?  
if (user('ID') == 0 || MANAGEMENT == 0 && $blog['PRIVATE_COMMENTS'] == 2 && $blog['USER_ID'] != user('ID') || MANAGEMENT == 0 && $blog['PRIVATE_COMMENTS'] == 1 && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $blog['USER_ID']]) == 0 && $blog['USER_ID'] != user('ID')){
  
  $comments_set = 'Извините, для вас комментирование недоступно';
  
}

comments($action, 'blogs_comments', 1, 'message', $blog['USER_ID'], $blog['ID']);

back('/m/blogs/', 'Ко всем записям');
acms_footer();