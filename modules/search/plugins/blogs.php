<?php
  
is_active_module('PRIVATE_BLOGS');
  
$column = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ?) AND `PRIVATE` = '0'", ['%'.SEARCH.'%', '%'.SEARCH.'%']);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/blogs.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s в блогах', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
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

$data = db::get_string_all("SELECT * FROM `BLOGS` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ?) AND `PRIVATE` = '0' ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%']);
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/blogs/plugins/list_mini.php');
  echo $blogs_list_mini;

}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/search/?type=blogs&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');