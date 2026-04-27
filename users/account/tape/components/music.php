<?php
  
$music = db::get_string("SELECT * FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);

if (isset($music['ID'])){
  
  $music_list = likes_ajax($music['ID'], 'music', $music['USER_ID'], 1).dislikes_ajax($music['ID'], 'music').'
  
  <div class="list" style="overflow: hidden;">
  <span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span>
  '.music_player($music['ID'], $music['EXT'], $music['ARTIST'], $music['NAME'], $music['DURATION'], 0, $list['ID']).'<span class="music_post'.$list['ID'].'" array="'.$music['ID'].',"></span><br />
  '.lg('У').' '.user::login($music['USER_ID'], 0, 1).' '.lg('новая музыка').'<br /><span class="time">'.ftime($music['TIME']).'</span>
  <div id="like_music'.$music['ID'].'">
  '.likes_list($music['ID'], 'music', URL_TAPE).'
  <div class="menu-sw-cont">  
  <a class="menu-sw-cont-left-25" href="/m/music/show/?id='.$music['ID'].'">'.icons('comment', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$music['ID'], 'music_comments']).'</a><a class="menu-sw-cont-left-25" href="/m/eye/?id='.$music['ID'].'&url='.base64_encode(URL_TAPE).'&type=music&'.TOKEN_URL.'">'.icons('eye', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$music['ID'], 'music']).'</a>'.mlikes($music['ID'], URL_TAPE, 'music', 'menu-sw-cont-left-25', 'like_music'.$music['ID']).mdislikes($music['ID'], URL_TAPE, 'music', 'menu-sw-cont-left-25', 'like_music'.$music['ID']).'
  </div>
  </div>
  </div>

  ';
  
}else{
  
  $music_list = '<div class="list">'.lg('Объект уже удален').'<span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span></div>';
  
}

echo $music_list;