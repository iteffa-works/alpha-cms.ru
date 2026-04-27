<?php

$column = db::get_column("SELECT COUNT(DISTINCT `PHOTOS`.`ID`) AS `count_photos` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0'");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

define('URL_PHOTOS', '/m/photos/?get='.$root);
$data = db::get_string_all("SELECT `PHOTOS`.`ID`,`PHOTOS`.`EXT`,`PHOTOS`.`NAME`,`PHOTOS`.`SHIF`,`PHOTOS`.`ADULT` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0' GROUP BY `PHOTOS`.`ID` ORDER BY `PHOTOS`.`TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/photos/plugins/list-mini.php');
  echo $photos_list_mini;
  
}

if ($column > 0){ 
  
  ?><br /><br /></div><?
  
}

get_page(URL_PHOTOS.'&', $spage, $page, 'list');