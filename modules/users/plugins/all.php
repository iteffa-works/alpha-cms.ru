<?php

$column = db::get_column("SELECT COUNT(*) FROM `USERS`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `USERS` ORDER BY `DATE_CREATE` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/users/plugins/users_list.php');
  
}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/users/?', $spage, $page, 'list');