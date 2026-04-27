<?php  
  
is_active_module('PRIVATE_PHOTOS');  
  
$column = db::get_column("SELECT COUNT(DISTINCT `PHOTOS`.`ID`) AS `count_photos` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`PHOTOS`.`NAME` LIKE ? OR `PHOTOS`.`MESSAGE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%']); 
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/photos.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s в фотографиях', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
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

$data = db::get_string_all("SELECT `PHOTOS`.`ID`,`PHOTOS`.`EXT`,`PHOTOS`.`NAME`,`PHOTOS`.`SHIF` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`PHOTOS`.`NAME` LIKE ? OR `PHOTOS`.`MESSAGE` LIKE ?) GROUP BY `PHOTOS`.`ID` ORDER BY `PHOTOS`.`TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%']);
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/photos/plugins/list.php');
  echo $photos_list;

}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/search/?type=photos&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');