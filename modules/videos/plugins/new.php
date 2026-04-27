<?php

$column = db::get_column("SELECT COUNT(DISTINCT `VIDEOS`.`ID`) AS `count_videos` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `PRIVATE` = '0'");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

define('URL_VIDEOS', '/m/videos/?get='.$root);
$data = db::get_string_all("SELECT `VIDEOS`.`ID`,`VIDEOS`.`EXT`,`VIDEOS`.`NAME`,`VIDEOS`.`DURATION` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `PRIVATE` = '0' GROUP BY `VIDEOS`.`ID` ORDER BY `VIDEOS`.`TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/videos/plugins/list-mini.php');
  echo $video_list_mini;
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page(URL_VIDEOS.'&', $spage, $page, 'list');