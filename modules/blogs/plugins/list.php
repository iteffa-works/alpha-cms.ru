<?php
  
if ($list['PRIVATE'] == 0){
  
  $private = 'globe';
  
}elseif ($list['PRIVATE'] == 1){
  
  $private = 'users';
  
}elseif ($list['PRIVATE'] == 2){
  
  $private = 'lock';
  
}elseif (str($list['PASSWORD']) > 0){
  
  $private = 'key';
  
}

$d = 0;
$list_img = db::get_string("SELECT `OBJECT_ID` FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ? AND `TYPE` = ? ORDER BY `TIME` DESC LIMIT 1", [$list['ID'], 'blogs', 'photos']);
$cat = db::get_string("SELECT `NAME`,`ID` FROM `BLOGS_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$list['ID_CATEGORY']]);
if (isset($list_img['OBJECT_ID'])){
  
  $d = 1;

  if (isset($cat['ID'])){
    
    $cat = "<a class='info blue' style='position: absolute; bottom: 10px; right: 11px;' href='/m/blogs/categories/?id=".$cat['ID']."'>".lg(tabs($cat['NAME']))."</a>";
  
  }else{
    
    $cat = null;
  
  }
  
  $photo = db::get_string("SELECT `SHIF` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list_img['OBJECT_ID']]);
  
  $list_type = '
  <div class="list-menu-img-phone">
  <div class="list-menu-img-phone-2">
  '.($list['COMMUNITY'] == 0 ? '<span style="float: right; color: white">'.icons($private, 17).'</span>' : null).'
  <div class="user-avatar-mini">
  '.($list['COMMUNITY'] == 0 ? '<a href="/id'.$list['USER_ID'].'">'.user::avatar($list['USER_ID'], 45, 0).'</a>' : '<a href="'.communities::url($list['COMMUNITY']).'">'.communities::avatar($list['COMMUNITY'], 45, 0).'</a>').'
  </div>
  <div class="user-login-mini">
  '.($list['COMMUNITY'] == 0 ? user::login($list['USER_ID'], 0, 1, 0, 'white') : '<a href="'.communities::url($list['COMMUNITY']).'"><font color="#B4FFF1">'.icons('users', 15, 'fa-fw').'</font> <font color="white"><b>'.communities::name($list['COMMUNITY']).'</b></font></a>').'
  <br />
  <span class="time" style="color: #D3FDFF">'.ftime($list['TIME']).'</span>
  </div>
  '.$cat.'
  </div>
  <img src="/files/upload/photos/260x600/'.$photo['SHIF'].'.jpg">
  </div>
  ';
  
}

if ($d == 0) {
  
  if (isset($cat['ID'])){
    
    $cat = "<br /><a class='info blue' href='/m/blogs/categories/?id=".$cat['ID']."'>".lg(tabs($cat['NAME']))."</a>";
  
  }else{
    
    $cat = null;
  
  }
  
  $list_type = 
  ($list['COMMUNITY'] == 0 ? '<span style="float: right; color: #424C54">'.icons($private, 17).'</span>' : null).'
  <div class="user-avatar-mini">
  '.($list['COMMUNITY'] == 0 ? '<a href="/id'.$list['USER_ID'].'">'.user::avatar($list['USER_ID'], 45, 0).'</a>' : '<a href="'.communities::url($list['COMMUNITY']).'">'.communities::avatar($list['COMMUNITY'], 45, 0).'</a>').'
  </div>
  <div class="user-login-mini">
  '.($list['COMMUNITY'] == 0 ? user::login($list['USER_ID'], 0, 1, 0) : '<a href="'.communities::url($list['COMMUNITY']).'"><font color="#5DCBB7">'.icons('users', 15, 'fa-fw').'</font> <font color="black"><b>'.communities::name($list['COMMUNITY']).'</b></font></a>').'
  <br />
  <span class="time">'.ftime($list['TIME']).'</span>
  </div>
  '.$cat.'
  ';
  
}

if ($list['SHARE'] > 0){
  
  $share = db::get_string("SELECT `NAME`,`MESSAGE`,`ID`,`COMMUNITY` FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [$list['SHARE']]);
  
  $list_type = '
  <div class="user-avatar-mini">
  <a href="/id'.$list['USER_ID'].'">'.user::avatar($list['USER_ID'], 45, 0).'</a>
  </div>
  <div class="user-login-mini">
  '.user::login($list['USER_ID'], 0, 1, 0).'
  <br />
  <span class="time">'.ftime($list['TIME']).'</span>
  </div>
  <div style="padding: 10px; border-top: 1px #DAE4EB solid; border-left: 1px #DAE4EB solid"><a href="/m/'.($share['COMMUNITY'] == 0 ? "blogs/show" : "communities/show_blog").'/?id='.$share['ID'].'">'.tabs($share['NAME']).'</a><br /><br />'.crop_text(text($share['MESSAGE']),0,250).'<br />
  <a href="/m/'.($share['COMMUNITY'] == 0 ? "blogs/show" : "communities/show_blog").'/?id='.$share['ID'].'">'.lg('Читать полностью').'...</a></div>';
  
}

if ($list['SHARE'] == 0){
  
  $share = "<a class='menu-sw-cont-left-25' href='/m/blogs/share/?id=".$list['ID']."'>".icons('share-square-o', 18, 'fa-fw')." ".db::get_column("SELECT COUNT(`ID`) FROM `BLOGS` WHERE `SHARE` = ? LIMIT 1", [$list['ID']])."</a>";
  $share_width = 25;
  
}else{
  
  $share = null;
  $share_width = 33;
  
}
  
$blogs_list = likes_ajax($list['ID'], 'blogs', $list['USER_ID'], 1).dislikes_ajax($list['ID'], 'blogs').'

<div class="list" style="overflow: hidden">
'.$list_type.'
<a href="'.($list['COMMUNITY'] == 0 ? '/m/blogs/show/?id='.$list['ID'] : '/m/communities/show_blog/?id='.$list['ID']).'"><div class="list-menu-text">'.tabs($list['NAME']).'</div><u>'.lg('Читать запись').'</u></a>

<small style="float: right">'.lg('Комментариев').': <b>'.db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$list['ID'], 'blogs_comments']).'</b></small>

<div id="like'.$list['ID'].'">
'.likes_list($list['ID'], 'blogs', URL_BLOGS).'
<div class="menu-sw-cont">
'.$share.'<a class="menu-sw-cont-left-'.$share_width.'" href="/m/eye/?id='.$list['ID'].'&url='.base64_encode(URL_BLOGS).'&type=blogs&'.TOKEN_URL.'">'.icons('eye', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'blogs']).'</a>'.mlikes($list['ID'], URL_BLOGS, 'blogs', 'menu-sw-cont-left-'.$share_width, 'like'.$list['ID']).mdislikes($list['ID'], URL_BLOGS, 'blogs', 'menu-sw-cont-left-'.$share_width, 'like'.$list['ID']).'
</div>
</div>
</div>

';