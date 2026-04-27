<?php

$column = db::get_column("SELECT COUNT(DISTINCT `MUSIC`.`ID`) AS `count_music` FROM `MUSIC` LEFT JOIN `MUSIC_DIR` ON (`MUSIC_DIR`.`ID` = `MUSIC`.`ID_DIR` OR `MUSIC_DIR`.`ID_DIR` = `MUSIC`.`ID_DIR`) WHERE `PRIVATE` = '0'");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

define('URL_MUSIC', '/m/music/?get='.$root);
$music_count = -1;
$msr = null;
$data = db::get_string_all("SELECT `MUSIC`.`ID`,`MUSIC`.`EXT`,`MUSIC`.`NAME`,`MUSIC`.`ARTIST`,`MUSIC`.`DURATION` FROM `MUSIC` LEFT JOIN `MUSIC_DIR` ON (`MUSIC_DIR`.`ID` = `MUSIC`.`ID_DIR` OR `MUSIC_DIR`.`ID_DIR` = `MUSIC`.`ID_DIR`) WHERE `PRIVATE` = '0' GROUP BY `MUSIC`.`ID` LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  $music_count++;
  $msr .= $list['ID'].",";
  $id_post = 0;
    
  ?><div class='list-menu'><?
  echo music_player($list['ID'], $list['EXT'], $list['ARTIST'], $list['NAME'], $list['DURATION'], $music_count, $id_post);
  ?></div><?
  
}

if (str($msr) > 0) {
  
  ?><span class="music_post<?=$id_post?>" array="<?=$msr?>"></span><?
    
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page(URL_MUSIC.'&', $spage, $page, 'list');