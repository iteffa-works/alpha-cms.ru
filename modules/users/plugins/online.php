<?php

$column = db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `DATE_VISIT` > ?", [(TM - config('ONLINE_TIME_USERS'))]); 
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `USERS` WHERE `DATE_VISIT` > ? ORDER BY `DATE_VISIT` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [(TM - config('ONLINE_TIME_USERS'))]);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/users/plugins/users_list.php');
  
}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/users/?get=online&', $spage, $page, 'list');