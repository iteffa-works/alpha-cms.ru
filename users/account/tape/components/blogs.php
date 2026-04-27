<?php
  
$blog = db::get_string("SELECT `ID`,`ID_CATEGORY`,`USER_ID`,`NAME`,`TIME`,`COMMUNITY` FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);

$d = 0;
$list_img = db::get_string("SELECT `OBJECT_ID` FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ? AND `TYPE` = ? ORDER BY `TIME` DESC LIMIT 1", [$blog['ID'], 'blogs', 'photos']);
if (isset($list_img['OBJECT_ID'])){
  
  $d = 1;
  
  $cat = db::get_string("SELECT `NAME`,`ID` FROM `BLOGS_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$blog['ID_CATEGORY']]);
  if (isset($cat['ID'])){
    
    $cat = "<a class='info blue' style='position: absolute; bottom: 10px; right: 11px;' href='/m/blogs/categories/?id=".$cat['ID']."'>".lg(tabs($cat['NAME']))."</a>";
  
  }else{
    
    $cat = null;
  
  }
  
  $photo = db::get_string("SELECT `SHIF` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list_img['OBJECT_ID']]);
  
  $list_type = '
  <div class="list-menu-img-phone">
  <div class="list-menu-img-phone-2">
  <div class="user-info-mini">
  <span style="float: right; color: white;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span>
  <div class="user-avatar-mini">
  '.($blog['COMMUNITY'] == 0 ? '<a href="/id'.$blog['USER_ID'].'">'.user::avatar($blog['USER_ID'], 45, 0).'</a>' : '<a href="'.communities::url($blog['COMMUNITY']).'">'.communities::avatar($blog['COMMUNITY'], 45, 0).'</a>').'
  </div>
  <div class="user-login-mini" style="top: 4px; left: 55px;">
  '.($blog['COMMUNITY'] == 0 ? user::login($blog['USER_ID'], 0, 1, 0, 'white') : '<a href="'.communities::url($blog['COMMUNITY']).'"><font color="#B4FFF1">'.icons('users', 15, 'fa-fw').'</font> <font color="white"><b>'.communities::name($blog['COMMUNITY']).'</b></font></a>').'
  <br />
  <span class="time" style="color: #D3FDFF">'.ftime($blog['TIME']).'</span>
  </div>
  </div>
  '.$cat.'
  </div>
  <img src="/files/upload/photos/260x600/'.$photo['SHIF'].'.jpg">
  </div>
  ';
  
}

if ($d == 0) {
  
  $cat = db::get_string("SELECT `NAME`,`ID` FROM `BLOGS_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$blog['ID_CATEGORY']]);
  if (isset($cat['ID'])){
    
    $cat = "<a class='info blue' style='position: absolute; bottom: -8px; right: -1px;' href='/m/blogs/categories/?id=".$cat['ID']."'>".lg(tabs($cat['NAME']))."</a>";
  
  }else{
    
    $cat = null;
  
  }
  
  $list_type = '
  <div class="user-info-mini" style="position: relative">
  <span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span>
  <div class="user-avatar-mini">
  '.($blog['COMMUNITY'] == 0 ? '<a href="/id'.$blog['USER_ID'].'">'.user::avatar($blog['USER_ID'], 45, 0).'</a>' : '<a href="'.communities::url($blog['COMMUNITY']).'">'.communities::avatar($blog['COMMUNITY'], 45, 0).'</a>').'
  </div>
  <div class="user-login-mini" style="top: 4px; left: 55px;">
  '.($blog['COMMUNITY'] == 0 ? user::login($blog['USER_ID'], 0, 1, 0) : '<a href="'.communities::url($blog['COMMUNITY']).'"><font color="#5DCBB7">'.icons('users', 15, 'fa-fw').'</font> <font color="black"><b>'.communities::name($blog['COMMUNITY']).'</b></font></a>').'
  <br />
  <span class="time">'.ftime($blog['TIME']).'</span>
  '.$cat.'
  </div>
  </div>
  ';
  
}

if (isset($blog['ID'])){
  
  $blogs_list = likes_ajax($blog['ID'], 'blogs', $blog['USER_ID'], 1).dislikes_ajax($blog['ID'], 'blogs').'
  
  <div class="list" style="overflow: hidden;">
  '.$list_type.'
  <a href="'.($blog['COMMUNITY'] == 0 ? '/m/blogs/show/?id='.$blog['ID'] : '/m/communities/show_blog/?id='.$blog['ID']).'"><div class="list-menu-text">'.tabs($blog['NAME']).'</div><u>'.lg('Читать запись').'</u></a>
  
  <small style="float: right">'.lg('Комментариев').': <b>'.db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$blog['ID'], 'blogs_comments']).'</b></small>
  
  <br /><br />
  '.($blog['COMMUNITY'] == 0 ? lg('У %s новая запись', user::login($blog['USER_ID'], 0, 1)) : lg('У сообщества %s новая запись', '<font color="black"><b>'.communities::name($blog['COMMUNITY']).'</b></font>')).'
  <br /><span class="time">'.ftime($list['TIME']).'</span>
  <div id="like_blogs'.$blog['ID'].'">
  '.likes_list($blog['ID'], 'blogs', URL_TAPE).'
  <div class="menu-sw-cont">  
  <a class="menu-sw-cont-left-25" href="'.($blog['COMMUNITY'] == 0 ? '/m/blogs/share/?id='.$blog['ID'] : '/m/communities/share_blog/?id='.$blog['ID']).'">'.icons('share-square-o', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `BLOGS` WHERE `SHARE` = ? LIMIT 1", [$blog['ID']]).'</a><a class="menu-sw-cont-left-25" href="/m/eye/?id='.$blog['ID'].'&url='.base64_encode(URL_TAPE).'&type=blogs&'.TOKEN_URL.'">'.icons('eye', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$blog['ID'], 'blogs']).'</a>'.mlikes($blog['ID'], URL_TAPE, 'blogs', 'menu-sw-cont-left-25', 'like_blogs'.$blog['ID']).mdislikes($blog['ID'], URL_TAPE, 'blogs', 'menu-sw-cont-left-25', 'like_blogs'.$blog['ID']).'
  </div>
  </div>
  </div>

  '; 
  
}else{
  
  $blogs_list = '<div class="list">'.lg('Объект уже удален').'<span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span></div>';
  
}

echo $blogs_list;