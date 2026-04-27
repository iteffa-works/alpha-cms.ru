<?php
  
is_active_module('PRIVATE_MUSIC');  
  
$column = db::get_column("SELECT COUNT(DISTINCT `MUSIC`.`ID`) AS `count_music` FROM `MUSIC` LEFT JOIN `MUSIC_DIR` ON (`MUSIC_DIR`.`ID` = `MUSIC`.`ID_DIR` OR `MUSIC_DIR`.`ID_DIR` = `MUSIC`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`MUSIC`.`NAME` LIKE ? OR `MUSIC`.`FACT_NAME` LIKE ? OR `MUSIC`.`ARTIST` LIKE ? OR `MUSIC`.`GENRE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%']); 
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/music.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s в аудиофайлах', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
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

define('URL_MUSIC', '/m/search/?type=music');
$music_count = -1;
$msr = null;
$data = db::get_string_all("SELECT `MUSIC`.`ID`,`MUSIC`.`EXT`,`MUSIC`.`NAME`,`MUSIC`.`ARTIST`,`MUSIC`.`DURATION` FROM `MUSIC` LEFT JOIN `MUSIC_DIR` ON (`MUSIC_DIR`.`ID` = `MUSIC`.`ID_DIR` OR `MUSIC_DIR`.`ID_DIR` = `MUSIC`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`MUSIC`.`NAME` LIKE ? OR `MUSIC`.`FACT_NAME` LIKE ? OR `MUSIC`.`ARTIST` LIKE ? OR `MUSIC`.`GENRE` LIKE ?) GROUP BY `MUSIC`.`ID` ORDER BY `MUSIC`.`TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%']);
while ($list = $data->fetch()){
  
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

get_page('/m/search/?type=music&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');