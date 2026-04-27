<?php
  
$photo = db::get_string("SELECT * FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);

if (isset($photo['ID'])){
  
  $photos_list = likes_ajax($photo['ID'], 'photos', $photo['USER_ID'], 1).dislikes_ajax($photo['ID'], 'photos').'
  
  <div class="list" style="overflow: hidden;">
  <span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span>
  <a href="/m/photos/show/?id='.$photo['ID'].'">
  <img class="img" src="/files/upload/photos/source/'.$photo['SHIF'].'.'.$photo['EXT'].'" style="max-width: 80%"><br />
  <b><font color="#484F54">'.crop_text(tabs($photo['NAME']), 0, 25).'</font></b>
  </a><br /><br />
  '.lg('У').' '.user::login($photo['USER_ID'], 0, 1).' '.lg('новое фото').'<br /><span class="time">'.ftime($list['TIME']).'</span>
  <div id="like_photos'.$photo['ID'].'">
  '.likes_list($photo['ID'], 'photos', URL_TAPE).'
  <div class="menu-sw-cont">  
  <a class="menu-sw-cont-left-25" href="/m/photos/show/?id='.$photo['ID'].'">'.icons('comment', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$photo['ID'], 'photos_comments']).'</a><a class="menu-sw-cont-left-25" href="/m/eye/?id='.$photo['ID'].'&url='.base64_encode(URL_TAPE).'&type=photos&'.TOKEN_URL.'">'.icons('eye', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$photo['ID'], 'photos']).'</a>'.mlikes($photo['ID'], URL_TAPE, 'photos', 'menu-sw-cont-left-25', 'like_photos'.$photo['ID']).mdislikes($photo['ID'], URL_TAPE, 'photos', 'menu-sw-cont-left-25', 'like_photos'.$photo['ID']).'
  </div>
  </div>
  </div>

  ';
  
}else{
  
  $photos_list = '<div class="list">'.lg('Объект уже удален').'<span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span></div>';
  
}

echo $photos_list;