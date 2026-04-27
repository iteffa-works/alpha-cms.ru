<?php

$column = db::get_column("SELECT COUNT(*) FROM `USERS`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
$i = 0;
$rnum = $limit + $i;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `USERS` ORDER BY `RATING` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  $rnum++;  
  require (ROOT.'/modules/users/plugins/users_list.php');
  
}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/users/?get=rating&', $spage, $page, 'list');