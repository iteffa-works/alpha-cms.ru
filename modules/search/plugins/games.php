<?php
  
is_active_module('PRIVATE_GAMES');  
  
$column = db::get_column("SELECT COUNT(*) FROM `GAMES` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ? OR `LINK` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%']);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/games.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s в онлайн играх', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
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

$data = db::get_string_all("SELECT * FROM `GAMES` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ? OR `LINK` LIKE ?) ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%']);
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/games/plugins/list.php');
  echo $games_list;

}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/search/?type=games&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');