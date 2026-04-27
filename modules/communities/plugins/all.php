<?php
$column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES` WHERE `BAN` = '0'");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES` WHERE `BAN` = '0' LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/communities/plugins/list.php');
  echo $comm_list;
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/communities/?', $spage, $page, 'list');