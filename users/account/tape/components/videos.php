<?php
  
$video = db::get_string("SELECT * FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);

if (isset($video['ID'])){
  
  $videos_list = likes_ajax($video['ID'], 'videos', $video['USER_ID'], 1).dislikes_ajax($video['ID'], 'videos').'
  
  <div class="list" style="overflow: hidden;">
  <span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span>
  '.video_player($video['ID'], $video['EXT'], $video['NAME'], 305).'<br />
  '.lg('У').' '.user::login($video['USER_ID'], 0, 1).' '.lg('новое видео').'<br /><span class="time">'.ftime($list['TIME']).'</span>
  <div id="like_videos'.$video['ID'].'">
  '.likes_list($video['ID'], 'videos', URL_TAPE).'
  <div class="menu-sw-cont">  
  <a class="menu-sw-cont-left-25" href="/m/videos/show/?id='.$video['ID'].'">'.icons('comment', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$video['ID'], 'videos_comments']).'</a><a class="menu-sw-cont-left-25" href="/m/eye/?id='.$video['ID'].'&url='.base64_encode(URL_TAPE).'&type=videos&'.TOKEN_URL.'">'.icons('eye', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$video['ID'], 'videos']).'</a>'.mlikes($video['ID'], URL_TAPE, 'videos', 'menu-sw-cont-left-25', 'like_videos'.$video['ID']).mdislikes($video['ID'], URL_TAPE, 'videos', 'menu-sw-cont-left-25', 'like_videos'.$video['ID']).'
  </div>
  </div>
  </div>

  ';
  
}else{
  
  $videos_list = '<div class="list">'.lg('Объект уже удален').'<span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span></div>';
  
}

echo $videos_list;