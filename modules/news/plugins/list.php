<?php

$eye = db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'news']);
$likes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'news', 'like']);
$dislikes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'news', 'dislike']);
$comments = db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$list['ID'], 'news_comments']);

$list_img = db::get_string("SELECT `OBJECT_ID` FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ? AND `TYPE` = ? ORDER BY `TIME` DESC LIMIT 1", [$list['ID'], 'news', 'photos']);
if (isset($list_img['OBJECT_ID'])){
  
  $photo = db::get_string("SELECT `SHIF` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list_img['OBJECT_ID']]);
  
  $news_list = '
  <a href="/m/news/show/?id='.$list['ID'].'">
  <div class="list-menu hover">
  <div class="user-avatar-mini">
  <img src="/files/upload/photos/150x150/'.$photo['SHIF'].'.jpg" style="max-width: 80px; border-radius: 4px;">
  </div>
  <div class="user-login-mini" style="padding-left: 36px; width: 61%">
  '.user::login($list['USER_ID'], 0, 0, 0).'<br />
  <span class="time">'.ftime($list['TIME']).'</span><br />
  '.crop_text(tabs($list['NAME']),0,40).'
  </div>
  <br />
  <div class="list_mini">
  <span>'.icons('comment', 15, 'fa-fw').' '.$comments.'</span>
  <span>'.icons('eye', 16, 'fa-fw').' '.$eye.'</span>
  <span style="float: right; margin-right: -5px;">
  <span>'.icons('thumbs-up', 16, 'fa-fw').' '.$likes.'</span>
  <span>'.icons('thumbs-down', 16, 'fa-fw').' '.$dislikes.'</span>
  </span>
  </div>
  </div>
  </a>
  ';
  
}else{
  
  $news_list = '
  <a href="/m/news/show/?id='.$list['ID'].'">
  <div class="list-menu hover">
  '.user::login($list['USER_ID'], 0, 0, 0).'<br />
  <span class="time">'.ftime($list['TIME']).'</span><br />
  '.crop_text(tabs($list['NAME']),0,45).'
  <br />
  <div class="list_mini">
  <span>'.icons('comment', 15, 'fa-fw').' '.$comments.'</span>
  <span>'.icons('eye', 16, 'fa-fw').' '.$eye.'</span>
  <span style="float: right; margin-right: -5px;">
  <span>'.icons('thumbs-up', 16, 'fa-fw').' '.$likes.'</span>
  <span>'.icons('thumbs-down', 16, 'fa-fw').' '.$dislikes.'</span>
  </span>
  </div>
  </div>
  </a>
  ';
  
}