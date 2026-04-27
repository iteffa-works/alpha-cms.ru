<?php
  
is_active_module('PRIVATE_COMMUNITIES');
  
$column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ? OR `URL` LIKE ? OR `RULES` LIKE ? OR `INTERESTS` LIKE ? OR `MOTTO` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%']);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/communities.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s в сообществах', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
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

$data = db::get_string_all("SELECT * FROM `COMMUNITIES` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ? OR `URL` LIKE ? OR `RULES` LIKE ? OR `INTERESTS` LIKE ? OR `MOTTO` LIKE ?) ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%']);
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/communities/plugins/list.php');
  echo $comm_list;

}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/search/?type=communities&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');