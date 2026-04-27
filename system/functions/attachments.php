<?php
  
$ar = (get('base') == 'panel' ? 'ajax="no"' : null);  
  
/*
-------------------------------------
Подключение модуля прикрепления файла
-------------------------------------
*/
  
function music_player($id, $ext, $artist, $name, $duration, $id_play, $id_post) {
  
  global $ar;
  
  if (config('PRIVATE_MUSIC') == 1){
    
    if (config('MUSIC_SCREEN') == 1){
      
      if (is_file(ROOT.'/files/upload/music/screen/120x120/'.$id.'.jpg')){
        
        $img = '<img src="/music/'.$id.'/?type=screen" class="attachments-files-img">';
      
      }else{
        
        $img = file::ext($ext);
      
      }
    
    }else{
      
      $img = file::ext($ext);
    
    }
    
    $adult = db::get_column("SELECT `ADULT` FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$id]);
    
    if (config('MUSIC_PLAYER') == 1) {
      
      return '<div class="files-info-list"><div class="files-ext">
      <button class="music-play" id="music'.$id.'" play="0" onclick="PlayGo(\''.$id.'\', \''.$id_post.'\', \''.$id_play.'\', \'none\', \'none\', 1)"><i class="fa fa-play fa-lg"></i></button>
      '.$img.'</div><div class="files-info"><b><a '.$ar.' href="'.(intval(get('add_dl')) == 0 ? '/m/music/show/?id='.$id : '/m/downloads/?id_file='.$id.'&type=music&'.TOKEN_URL).'">'.crop_text(tabs($name), 0, 25).' '.($adult == 1 ? '<b class="adult">18+</b>' : null).'</a></b><br /><div style="margin-top: 5px;">'.crop_text(tabs($artist), 0, 25).'</div><div style="margin-top: 9px;">'.$duration.'</div></div><a ajax="no" href="/music/'.$id.'/" class="file-download">'.icons('download', 9).'</a></div>';
    
    }else{
      
      return '<div class="files-info-list"><div class="files-ext">
      '.$img.'</div><div class="files-info"><b><a '.$ar.' href="'.(intval(get('add_dl')) == 0 ? '/m/music/show/?id='.$id : '/m/downloads/?id_file='.$id.'&type=music&'.TOKEN_URL).'">'.crop_text(tabs($name), 0, 25).' '.($adult == 1 ? '<b class="adult">18+</b>' : null).'</a></b><br /><div style="margin-top: 5px;">'.crop_text(tabs($artist), 0, 25).'</div><div style="margin-top: 9px;">'.$duration.'</div></div><a ajax="no" href="/music/'.$id.'/" class="file-download">'.icons('download', 9).'</a></div>';
    
    }
    
  }
  
}  
  
function video_player($id, $ext, $name) {
  
  global $ar;
  
  if (config('PRIVATE_VIDEOS') == 1){
    
    if (config('VIDEO_SCREEN') == 1){
      
      if (is_file(ROOT.'/files/upload/videos/screen/'.$id.'.jpg')){
        
        $img = '/video/'.$id.'/?type=screen';
      
      }else{
        
        $img = '/video/'.$id.'/?type=no_screen';
      
      }
    
    }else{
      
      $img = '/video/'.$id.'/?type=no_screen';
    
    }
    
    $adult = db::get_column("SELECT `ADULT` FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$id]);
    
    $v = '<a '.$ar.' href="/m/videos/show/?id='.$id.'" style="position: relative; top: 5px">'.crop_text(tabs($name), 0, 25).' '.($adult == 1 ? '<span class="adult" style="top: -2px">18+</span>' : null).'</a>';
    
    if (config('VIDEO_PLAYER') == 1) {
      
      return "<video poster='".$img."' src='/files/upload/videos/source/".$id.".".$ext."' type='".file::mime($ext)."' style='width: 100%; box-sizing: border-box; height: ".(type_version() ? 180 : 220)."px' controls></video><br />".$v."<br />";
    
    }else{
      
      return "<img src='".$img."' style='box-sizing: border-box; width: 100%; height: ".(type_version() ? 180 : 220)."px'><br />".$v."<br /><br />";
    
    }
    
  }
  
}
  
function attachments_files($id, $type, $size = 190) {
  
  global $ar;
  
  $photos = null;
  $photos_count = 0;
  $pbr = null;
  $videos = null;
  $vbr = null;
  $music = null;
  $music_count = -1;
  $mbr = null;
  $files = null;
  $fbr = null;
  $s = 0;
  $br = null;
  $id_mus = 0;
  $msr = null;
  $ms = null;
  $voices = null;
  $vobr = null;
  $postrs = null;
  $posters = null;
  $data = db::get_string_all("SELECT * FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `ACT` = ? AND `TYPE_POST` = ? ORDER BY `TIME` DESC LIMIT 20", [$id, 1, $type]); 
  while ($list = $data->fetch()){
    
    $s++;
    
    if ($list['TYPE'] == 'photos') {
      
      if (config('PRIVATE_PHOTOS') == 1){
        
        $photo = db::get_string("SELECT `SHIF`,`EXT`,`NAME`,`ID` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
        $photos_count++;
        
        if (!isset($photo['ID'])) {
          
          $photos .= '<br /><small><font color="#8FA4AE">'.icons('paperclip', 17).' '.lg('Вложение удалено владельцем').'</font></small>';
          
        }else{
          
          $adult = db::get_column("SELECT `ADULT` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$photo['ID']]);
          
          if ($photos_count > 1) {
            
            $photos .= '<span style="position: relative; display: inline-block"><img onclick="img_show(\'/files/upload/photos/source/'.$photo['SHIF'].'.'.$photo['EXT'].'\', \'/m/photos/show/?id='.$photo['ID'].'\', \''.tabs(crop_text($photo['NAME'],0,20)).'\')" class="img" src="/files/upload/photos/240x240/'.$photo['SHIF'].'.jpg" style="max-width: 72px">'.($adult == 1 ? '<span class="adult" style="position: absolute; z-index: 1; top: 10px; left: 10px">18+</span>' : null).'</span> ';
          
          }else{
            
            $photos .= '<span style="position: relative; display: inline-block"><img onclick="img_show(\'/files/upload/photos/source/'.$photo['SHIF'].'.'.$photo['EXT'].'\', \'/m/photos/show/?id='.$photo['ID'].'\', \''.tabs(crop_text($photo['NAME'],0,20)).'\')" class="img" src="/files/upload/photos/source/'.$photo['SHIF'].'.'.$photo['EXT'].'" style="box-sizing: border-box; max-width: 100%">'.($adult == 1 ? '<span class="adult" style="position: absolute; z-index: 1; top: 10px; left: 10px">18+</span>' : null).'</span><br />';
          
          }
          
        }
        
        $pbr = '<br />';
        
      }
      
    }
    
    if ($list['TYPE'] == 'videos') {
      
      if (config('PRIVATE_VIDEOS') == 1){
                
        $video = db::get_string("SELECT * FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]); 
        
        if (!isset($video['ID'])) {
          
          $videos .= '<br /><small><font color="#8FA4AE">'.icons('paperclip', 17).' '.lg('Вложение удалено владельцем').'</font></small>';
          
        }else{
          
          if ($video['SIZE'] > 0) {
            
            $videos .= video_player($video['ID'], $video['EXT'], $video['NAME']);
          
          }elseif (isset($video['YOUTUBE']) && str($video['YOUTUBE']) > 0) {
            
            $videos .= '<iframe style="box-sizing: border-box; width: 100%" height="'.(type_version() ? 180 : 220).'" src="https://www.youtube.com/embed/'.$video['YOUTUBE'].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
          
          }
          
        }
        
        $vbr = '<br />';
        
      }
      
    }
    
    if ($list['TYPE'] == 'music') {
      
      $music_count++;
      $id_mus = $list['ID_POST'];
      
      if (config('PRIVATE_MUSIC') == 1){
        
        $mus = db::get_string("SELECT `EXT`,`ID`,`ARTIST`,`NAME`,`DURATION` FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
        
        if (!isset($mus['ID'])) {
          
          $music .= '<br /><small><font color="#8FA4AE">'.icons('paperclip', 17).' '.lg('Вложение удалено владельцем').'</font></small>';
          
        }else{
          
          $music .= music_player($mus['ID'], $mus['EXT'], $mus['ARTIST'], $mus['NAME'], $mus['DURATION'], $music_count, $list['ID_POST']);        
          $mbr = '<br />';
          $msr .= $mus['ID'].",";
          
        }
      
      }
      
    }
    
    if ($list['TYPE'] == 'files') {
      
      if (config('PRIVATE_FILES') == 1){
        
        $file = db::get_string("SELECT `EXT`,`ID`,`NAME` FROM `FILES` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
        
        if (!isset($file['ID'])) {
          
          $files .= '<br /><small><font color="#8FA4AE">'.icons('paperclip', 17).' '.lg('Вложение удалено владельцем').'</font></small>';
          
        }else{
          
          $adult = db::get_column("SELECT `ADULT` FROM `FILES` WHERE `ID` = ? LIMIT 1", [$file['ID']]);
          
          $files .= '<div class="files-info-list"><div class="files-ext">
      <a '.$ar.' href="/m/files/show/?id='.$file['ID'].'">'.file::ext($file['EXT']).'</a></div><div class="files-info"><b><font color="#484F54">'.crop_text(tabs($file['NAME']), 0, 25).'</font></b> '.($adult == 1 ? '<b class="adult">18+</b>' : null).'<br /><a ajax="no" href="/file/'.$file['ID'].'/" class="file-download" style="top: 15px;">'.icons('download', 10).'</a></div></div>'; 
          
        }
        
        $fbr = '<br />';
        
      }
      
    }
    
    if ($list['TYPE'] == 'voices') {
      
      $voice = db::get_string("SELECT * FROM `VOICES` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
      
      if (TM > $list['TIME'] + config('VOICES_INTERVAL')) {
        
        @unlink(ROOT.'/files/upload/voices/'.$voice['NAME']);
        
      }
      
      if (is_file(ROOT.'/files/upload/voices/'.$voice['NAME'])) {
        
        $voices .= '
        <div class="files-info-list">
        <b><font color="#484F54">'.lg('Голосовое сообщение').' ('.lg($voice['DURATION']).')</font></b>
        <br />
        <audio style="margin: 5px; width: 250px" controls><source src="/files/upload/voices/'.$voice['NAME'].'" type="audio/wav"></audio><br />
        '.icons('clock-o', 17, 'fa-fw').' '.lg('удаление').': '.ftime($list['TIME'] + config('VOICES_INTERVAL')).'
        </div>
        '; 
        
      }else{
        
        $voices .= '
        <div class="files-info-list">
        <b><font color="#484F54">'.lg('Голосовое сообщение').'</font></b>
        <br />
        '.icons('clock-o', 17, 'fa-fw').' '.lg('уже удалено').'
        </div>
        '; 
        
      }
             
      $vobr = '<br />';
      
    }
    
    if ($list['TYPE'] == 'posters') {
      
      $posters .= "
      <div class='posters_show'>
      <div class='posters_show_content'>".tabs($list['MESSAGE'])."</div>
      <img src='/style/posters/".tabs($list['FILE'])."'>
      </div>
      ";
      
      $postrs = '<br />';
      
    }
    
    hooks::challenge('at_list_files', 'at_list_files');
    hooks::run('at_list_files');
    
  }
  
  if ($s > 0) {
    
    $br = '<br />';
  
  }
  
  if ($msr > 0) {

    $ms = '<span class="music_post'.$id_mus.'" array="'.$msr.'"></span>';
  
  }
  
  return $br.$photos.$pbr.$videos.$vbr.$music.$mbr.$ms.$files.$fbr.$voices.$vobr.$posters.$postrs;
  
}  
  
function attachments_result(){
  
  if (!defined('ATTACHMENTS_TESTING')) {
    
    define('ATTACHMENTS_TESTING', 0);
    
  }
  
  ?>
  <div class='modal_phone_center modal_center_close' id='modal_center_close_set'></div>
  <div class='modal_center modal_center_open' style='z-index: 999999999999999'>  
  <div id='files-upload-error'></div>
  </div>  
    
  <div class='modal_phone modal_bottom_close' id='modal_bottom_close_set'></div>
  <div class='modal_bottom modal_bottom_open'>
  <div id='attachments_upload'></div>                 
  </div>
  <?
  
}
  
function attachments_delete($id, $link){
  
  return '<span class="attachments_delete" onclick="request(\''.url_request_get($link).'delete='.$id.'&'.TOKEN_URL.'\', \'#upload-attachments-result\', \'1\')">'.icons('times', 12).'</span>';
  
}

function attachments_show($type, $link, $id){
  
  global $ar;
  
  if (intval($id) == 0) {
    
    $data = db::get_string_all("SELECT * FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ACT` = ? AND `TYPE_POST` = ? AND `ID_POST` = ? ORDER BY `TIME` DESC LIMIT 20", [user('ID'), 0, $type, 0]);
    
  }else{
    
    $data = db::get_string_all("SELECT * FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? ORDER BY `TIME` DESC LIMIT 20", [$type, $id]);
    
  }
  
  hooks::run('attachments'); 
  $html = null; 
  while ($list = $data->fetch()){
    
    if ($list['TYPE'] == 'photos'){
      
      $photo = db::get_string("SELECT `NAME`,`ID`,`SHIF` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
      $html .= "<div class='attachments_files_type'>".attachments_delete($list['ID'], $link)."<a ".$ar." href='/m/photos/show/?id=".$photo['ID']."'><img src='/files/upload/photos/150x150/".$photo['SHIF'].".jpg'><br /><small>".tabs(crop_text($photo['NAME'], 0, 7))."</small></a></div>";
      
    }elseif ($list['TYPE'] == 'files'){
      
      $file = db::get_string("SELECT `EXT`,`NAME`,`ID` FROM `FILES` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
      $html .= "<div class='attachments_files_type'>".attachments_delete($list['ID'], $link)."<a ".$ar." href='/m/files/show/?id=".$file['ID']."'>".file::ext($file['EXT'])."<br /><small>".tabs(crop_text($file['NAME'], 0, 7))."</small></a></div>";
      
    }elseif ($list['TYPE'] == 'videos'){
      
      $video = db::get_string("SELECT `EXT`,`NAME`,`ID` FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
      $html .= "<div class='attachments_files_type'>".attachments_delete($list['ID'], $link)."<a ".$ar." href='/m/videos/show/?id=".$video['ID']."'>".file::ext($video['EXT'])."<br /><small>".tabs(crop_text($video['NAME'], 0, 7))."</small></a></div>";
      
    }elseif ($list['TYPE'] == 'music'){
      
      $music = db::get_string("SELECT `EXT`,`ARTIST`,`ID` FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
      $html .= "<div class='attachments_files_type'>".attachments_delete($list['ID'], $link)."<a ".$ar." href='/m/music/show/?id=".$music['ID']."'>".file::ext($music['EXT'])."<br /><small>".tabs(crop_text($music['ARTIST'], 0, 7))."</small></a></div>";
      
    }elseif ($list['TYPE'] == 'voices'){

      $html .= "<div class='attachments_files_type'>".attachments_delete($list['ID'], $link).file::ext('wav')."<br /><small>".lg('Запись')."</small></div>";
      
    }elseif ($list['TYPE'] == 'posters'){
      
      $Ext = strtolower(preg_replace('#^.*\.#', null, $list['FILE']));

      $html .= "<div class='attachments_files_type'>".attachments_delete($list['ID'], $link).file::ext($Ext)."<br /><small>".lg('Постер')."</small></div>";
      
    }
  
  }
  
  hooks::challenge('at_list_files_show', 'at_list_files_show');
  hooks::run('at_list_files_show');
  
  if (str($html) > 0){
    
    ?>      
    <div class='upload-attachments-result'>
    <div class='attachments_files_type'><?=$html?></div>
    </div>
    <?
    
  }
  
}
  
function attachments($type, $link, $id = 0){
  
  //Удаление прикрепленных файлов
  if (get('delete')){
    
    get_check_valid();
    
    if (intval($id) == 0) {
      
      db::get_set("DELETE FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [user('ID'), intval(get('delete'))]);
      
    }else{
      
      db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]);
      
    }
    
  }
  
  ?><div id='upload-attachments-result'><?     
  attachments_show($type, $link, $id);    
  ?></div><?

}