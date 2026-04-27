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

$cat = db::get_string("SELECT `NAME` FROM `BLOGS_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$list['ID_CATEGORY']]);
if (isset($cat['NAME'])){
  
  $cat = "<br /><span class='info blue' style='margin-top: 5px'>".lg(tabs($cat['NAME']))."</span>";

}else{
  
  $cat = null;
  
}

$eye = db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'blogs']);
$likes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'blogs', 'like']);
$dislikes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'blogs', 'dislike']);
$comments = db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$list['ID'], 'blogs_comments']);
$share = db::get_column("SELECT COUNT(`ID`) FROM `BLOGS` WHERE `SHARE` = ? LIMIT 1", [$list['ID']]);

$list_img = db::get_string("SELECT `OBJECT_ID` FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ? AND `TYPE` = ? ORDER BY `TIME` DESC LIMIT 1", [$list['ID'], 'blogs', 'photos']);
if (isset($list_img['OBJECT_ID'])){
  
  $photo = db::get_string("SELECT `SHIF` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list_img['OBJECT_ID']]);
  
  $blogs_list_mini = '
  <a href="'.($list['COMMUNITY'] == 0 ? '/m/blogs/show/?id='.$list['ID'] : '/m/communities/show_blog/?id='.$list['ID']).'">
  <div class="list-menu hover">
  '.($list['COMMUNITY'] == 0 ? '<span style="float: right;">'.icons($private, 17).'</span>' : null).'
  <div class="user-avatar-mini">
  <img src="/files/upload/photos/150x150/'.$photo['SHIF'].'.jpg" style="max-width: 80px; border-radius: 4px;">
  </div>
  <div class="user-login-mini" style="padding-left: 36px; width: 57%">
  '.($list['COMMUNITY'] == 0 ? user::login($list['USER_ID'], 0, 0, 0) : '<b><font color="#5DCBB7">'.icons('users', 15, 'fa-fw').'</font> '.communities::name($list['COMMUNITY']).'</b>').'
  <br />
  <span class="time">'.ftime($list['TIME']).'</span><br />
  '.crop_text(tabs($list['NAME']),0,40).'
  '.$cat.'
  </div>
  <br />
  <div class="list_mini">
  <span>'.icons('comment', 15, 'fa-fw').' '.$comments.'</span>
  <span>'.icons('eye', 16, 'fa-fw').' '.$eye.'</span>
  <span>'.icons('share-square-o', 15, 'fa-fw').' '.$share.'</span>
  <span style="float: right; margin-right: -5px;">
  <span>'.icons('thumbs-up', 16, 'fa-fw').' '.$likes.'</span>
  <span>'.icons('thumbs-down', 16, 'fa-fw').' '.$dislikes.'</span>
  </span>
  </div>
  </div>
  </a>
  ';
  
}else{
  
  $blogs_list_mini = '
  <a href="'.($list['COMMUNITY'] == 0 ? '/m/blogs/show/?id='.$list['ID'] : '/m/communities/show_blog/?id='.$list['ID']).'">
  <div class="list-menu hover">
  '.($list['COMMUNITY'] == 0 ? '<span style="float: right;">'.icons($private, 17).'</span>' : null).'
  '.($list['COMMUNITY'] == 0 ? user::login($list['USER_ID'], 0, 0, 0) : '<b><font color="#5DCBB7">'.icons('users', 15, 'fa-fw').'</font> '.communities::name($list['COMMUNITY']).'</b>').'
  <br />
  <span class="time">'.ftime($list['TIME']).'</span><br />
  '.crop_text(tabs($list['NAME']),0,45).'
  '.$cat.'
  <br />
  <div class="list_mini">
  <span>'.icons('comment', 15, 'fa-fw').' '.$comments.'</span>
  <span>'.icons('eye', 16, 'fa-fw').' '.$eye.'</span>
  <span>'.icons('share-square-o', 16, 'fa-fw').' '.$share.'</span>
  <span style="float: right; margin-right: -5px;">
  <span>'.icons('thumbs-up', 16, 'fa-fw').' '.$likes.'</span>
  <span>'.icons('thumbs-down', 16, 'fa-fw').' '.$dislikes.'</span>
  </span>
  </div>
  </div>
  </a>
  ';
  
}