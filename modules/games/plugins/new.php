<?php

$column = db::get_column("SELECT COUNT(*) FROM `GAMES`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока нет игр на сайте');
  
}else{
  
  ?>
  <div class='list-body'>
  <div class='list-menu'><b><?=lg('Новые онлайн игры')?>:</b></div>
  <?
  
}

$data = db::get_string_all("SELECT * FROM `GAMES` ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/games/plugins/list.php');
  echo $games_list;
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/games/?get=new&', $spage, $page, 'list');