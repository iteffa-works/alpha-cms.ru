<?php

$column = db::get_column("SELECT COUNT(*) FROM `DOWNLOADS`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$music_count = -1;
$msr = null;
$data = db::get_string_all("SELECT * FROM `DOWNLOADS` ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  $file = db::get_string("SELECT * FROM `".strtoupper(esc($list['OBJECT_TYPE']))."` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
  
  if ($list['OBJECT_TYPE'] == 'music') {
    
    $music_count++;
    $msr .= $file['ID'].",";
    $id_post = 0;
    
    ?><div class='list-menu'><?
    echo music_player($file['ID'], $file['EXT'], $file['ARTIST'], $file['NAME'], $file['DURATION'], $music_count, $id_post);
    ?></div><?
    
  }
  
  if ($list['OBJECT_TYPE'] == 'photos') {
    
    $list['ID'] = $file['ID'];
    $list['EXT'] = $file['EXT'];
    $list['NAME'] = $file['NAME'];
    $list['SHIF'] = $file['SHIF'];
    
    require (ROOT.'/modules/photos/plugins/list.php');
    echo $photos_list;
  
  }
  
  if ($list['OBJECT_TYPE'] == 'videos') {
    
    $list['ID'] = $file['ID'];
    $list['EXT'] = $file['EXT'];
    $list['NAME'] = $file['NAME'];
    $list['DURATION'] = $file['DURATION'];
    
    require (ROOT.'/modules/videos/plugins/list-mini.php');
    echo $video_list_mini;
  
  }
  
  if ($list['OBJECT_TYPE'] == 'files') {
    
    $list['ID'] = $file['ID'];
    $list['EXT'] = $file['EXT'];
    $list['NAME'] = $file['NAME'];
    
    require (ROOT.'/modules/files/plugins/list-mini.php');
    echo $files_list_mini;
  
  }

}

if (str($msr) > 0) {
  
  ?><span class="music_post<?=$id_post?>" array="<?=$msr?>"></span><?
    
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/downloads/?get=new&', $spage, $page, 'list');