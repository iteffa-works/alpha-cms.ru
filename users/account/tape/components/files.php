<?php
  
$file = db::get_string("SELECT * FROM `FILES` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);

if (isset($file['ID'])){
  
  $files_list = likes_ajax($file['ID'], 'files', $file['USER_ID'], 1).dislikes_ajax($file['ID'], 'files').'
  
  <div class="list" style="overflow: hidden;">
  <span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span>
  <a href="/m/files/show/?id='.$file['ID'].'">
  <div class="files-info-list">
  <div class="files-ext">
  '.file::ext($file['EXT']).'
  </div>
  <div class="files-info"><b><font color="#484F54">'.crop_text(tabs($file['NAME']), 0, 25).'</font></b></div></div></a><br />
  '.lg('У').' '.user::login($file['USER_ID'], 0, 1).' '.lg('новый файл').'<br /><span class="time">'.ftime($list['TIME']).'</span>
  <div id="like_files'.$file['ID'].'">
  '.likes_list($file['ID'], 'files', URL_TAPE).'
  <div class="menu-sw-cont">  
  <a class="menu-sw-cont-left-25" href="/m/files/show/?id='.$file['ID'].'">'.icons('comment', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$file['ID'], 'files_comments']).'</a><a class="menu-sw-cont-left-25" href="/m/eye/?id='.$file['ID'].'&url='.base64_encode(URL_TAPE).'&type=files&'.TOKEN_URL.'">'.icons('eye', 18, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$file['ID'], 'files']).'</a>'.mlikes($file['ID'], URL_TAPE, 'files', 'menu-sw-cont-left-25', 'like_files'.$file['ID']).mdislikes($file['ID'], URL_TAPE, 'files', 'menu-sw-cont-left-25', 'like_files'.$file['ID']).'
  </div>
  </div>
  </div>

  ';
  
}else{
  
  $files_list = '<div class="list">'.lg('Объект уже удален').'<span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span></div>';
  
}

echo $files_list;