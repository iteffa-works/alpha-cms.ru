<?php
  
if (user('ID') > 0) {
  
  ?>
  <div class='list'>
  <a href='/m/forum/add_them/?get=section&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15)?> <?=lg('Создать тему')?></a>
  </div>
  <?
  
}  

$column = db::get_column("SELECT COUNT(*) FROM `FORUM_THEM`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `FORUM_THEM` ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/forum/plugins/list.php');
  echo $forum_list;
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/forum/?get=new&', $spage, $page, 'list');