<?php
  
is_active_module('PRIVATE_VIDEOS');  
  
$column = db::get_column("SELECT COUNT(DISTINCT `VIDEOS`.`ID`) AS `count_videos` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`VIDEOS`.`NAME` LIKE ? OR `VIDEOS`.`MESSAGE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%']); 
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/videos.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s в видеофайлах', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
</div>
<?php endif ?>
<?

if ($column == 0){ 
  
  html::empty('Поиск не дал результатов', 'search');
  
}else{
  
  ?>
  <div class='list-body'>
  <?
  
}

$data = db::get_string_all("SELECT `VIDEOS`.`ID`,`VIDEOS`.`EXT`,`VIDEOS`.`NAME`,`VIDEOS`.`DURATION` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`VIDEOS`.`NAME` LIKE ? OR `VIDEOS`.`MESSAGE` LIKE ?) GROUP BY `VIDEOS`.`ID` ORDER BY `VIDEOS`.`TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%']);
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/videos/plugins/list-mini.php');
  echo $video_list_mini;

}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/search/?type=videos&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');