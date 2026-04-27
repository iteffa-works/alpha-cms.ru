<?php 
  
is_active_module('PRIVATE_FILES');  
  
$column = db::get_column("SELECT COUNT(DISTINCT `FILES`.`ID`) AS `count_files` FROM `FILES` LEFT JOIN `FILES_DIR` ON (`FILES_DIR`.`ID` = `FILES`.`ID_DIR` OR `FILES_DIR`.`ID_DIR` = `FILES`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`FILES`.`NAME` LIKE ? OR `FILES`.`MESSAGE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%']); 
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/files.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s в файлах', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
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

$data = db::get_string_all("SELECT `FILES`.`ID`,`FILES`.`EXT`,`FILES`.`NAME` FROM `FILES` LEFT JOIN `FILES_DIR` ON (`FILES_DIR`.`ID` = `FILES`.`ID_DIR` OR `FILES_DIR`.`ID_DIR` = `FILES`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`FILES`.`NAME` LIKE ? OR `FILES`.`MESSAGE` LIKE ?) GROUP BY `FILES`.`ID` ORDER BY `FILES`.`TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%']);
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/files/plugins/list-mini.php');
  echo $files_list_mini;

}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/search/?type=files&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');