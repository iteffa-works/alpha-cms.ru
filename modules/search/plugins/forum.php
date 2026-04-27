<?php
  
is_active_module('PRIVATE_FORUM');  
  
$column = db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ?) AND `BAN` = '0'", ['%'.SEARCH.'%', '%'.SEARCH.'%']);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/forum.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s на форуме', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
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

$data = db::get_string_all("SELECT * FROM `FORUM_THEM` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ?) AND `BAN` = '0' ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%']);
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/forum/plugins/list.php');
  echo $forum_list;

}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/search/?type=forum&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');