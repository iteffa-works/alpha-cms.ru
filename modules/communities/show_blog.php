<?php
$blog = db::get_string("SELECT * FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$comm = db::get_string("SELECT `ID`,`URL` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [$blog['COMMUNITY']]);
$par = db::get_string("SELECT `ID`,`ADMINISTRATION` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
acms_header(lg('Запись - %s', tabs($blog['NAME'])), 'all', text($blog['MESSAGE'], 0, 0, 0, 0));
is_active_module('PRIVATE_COMMUNITIES');
communities::blocked($comm['ID']);

if (!isset($blog['ID']) || !isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/public/'.$comm['URL']);

}

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
    
    db::get_set("UPDATE `COMMUNITIES` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$comm['ID']]);
  
  }else{
    
    db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $blog['ID'], 'blogs']);
    
  }

}

if (user('ID') > 0){
  
  if (access('communities', null) == true || !isset($par['ID']) && $par['ADMINISTRATION'] == 2 || !isset($par['ID']) && $par['ADMINISTRATION'] == 1 || $blog['USER_ID'] == user('ID')){
    
    require_once (ROOT.'/modules/communities/plugins/delete_blog.php');
  
  }
  
  if ($blog['USER_ID'] != user('ID') && $blog['SHARE'] == 0 || access('communities', null) == true || !isset($par['ID']) && $par['ADMINISTRATION'] == 2 || !isset($par['ID']) && $par['ADMINISTRATION'] == 1 || $blog['USER_ID'] == user('ID')){
    
    ?><div class='list'><?
    
  }
  
  if ($blog['USER_ID'] != user('ID') && $blog['SHARE'] == 0){
    
    ?><a href='/m/blogs/share/?id=<?=$blog['ID']?>&get=go' class='btn'><?=icons('mail-forward', 15, 'fa-fw')?> <?=lg('Поделиться')?></a> <?
    
  }
  
  if (access('communities', null) == true || !isset($par['ID']) && $par['ADMINISTRATION'] == 2 || !isset($par['ID']) && $par['ADMINISTRATION'] == 1 || $blog['USER_ID'] == user('ID')){
    
    if ($blog['SHARE'] == 0){
      
      ?><a href='/m/communities/edit_blog/?id=<?=$blog['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a> <?        
        
    }else{
      
      ?><a href='/m/blogs/share/?id=<?=$blog['ID']?>&get=edit' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a> <?
      
    }
    
    ?>
    <a href='/m/communities/show_blog/?id=<?=$blog['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
      
  }
  
}

if ($blog['USER_ID'] != user('ID') && $blog['SHARE'] == 0 || access('communities', null) == true || !isset($par['ID']) && $par['ADMINISTRATION'] == 2 || !isset($par['ID']) && $par['ADMINISTRATION'] == 1 || $blog['USER_ID'] == user('ID')){
  
  ?></div><?
  
}

?>
<div class='list-body'>
<div class='list-menu'>
<div class='user-info-mini'>
<div class='user-avatar-mini'>
<?=communities::avatar($comm['ID'], 45, 0)?> 
</div>
<div class='user-login-mini' style='top: 4px; left: 55px;'>
<a href="/public/<?=$comm['URL']?>"><font color="#5DCBB7"><?=icons('users', 15, 'fa-fw')?></font> <font color="black"><b><?=communities::name($comm['ID'])?></b></font></a> <font color='#7E99A6'>(<?=user::login_mini($blog['USER_ID'])?>)</font>
<br />
<span class='time'><?=ftime($blog['TIME'])?></span>
</div>
</div>  
<br />
<b><?=tabs($blog['NAME'])?></b>
<br />    
<?=attachments_files($blog['ID'], 'blogs', 320)?>
<br />
<?=text($blog['MESSAGE'])?>
  
<?php
hooks::challenge('blogs_foot', 'blogs_foot');
hooks::run('blogs_foot');
likes_ajax($blog['ID'], 'blogs', $blog['USER_ID'], 1);
dislikes_ajax($blog['ID'], 'blogs');
$action = '/m/communities/show_blog/?id='.$blog['ID'];

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
if (user('ID') == 0 || MANAGEMENT == 0 && !isset($par['ID']) && $blog['PRIVATE_COMMENTS'] == 1 && $blog['USER_ID'] != user('ID')){
  
  $comments_set = 'Комментирование доступно только для участников сообщества';
  
}

comments($action, 'blogs_comments', 1, 'message', $blog['USER_ID'], $blog['ID']);

back('/public/'.$comm['URL']);
acms_footer();