<?php
  
$ex = explode(' ', SEARCH);
if (isset($ex[0])) { $ex0 = $ex[0]; }else{ $ex0 = 'none'; }
if (isset($ex[1])) { $ex1 = $ex[1]; }else{ $ex1 = 'none'; }
  
$column = db::get_column("SELECT COUNT(*) FROM `USERS` LEFT JOIN `USERS_SETTINGS` ON (`USERS_SETTINGS`.`USER_ID` = `USERS`.`ID`) WHERE (`USERS`.`LOGIN` LIKE ? OR `USERS_SETTINGS`.`NAME` LIKE ? OR `USERS_SETTINGS`.`SURNAME` LIKE ? OR `USERS_SETTINGS`.`NAME` LIKE ? OR `USERS_SETTINGS`.`SURNAME` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.$ex0.'%', '%'.$ex1.'%']);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

require (ROOT.'/modules/search/plugins/form/users.php');

?> 
<?php if (str(SEARCH) > 0) : ?>
<div class='list'>
<?=lg('Результатов поиска по запросу %s в списке пользователей', '"<b>'.SEARCH.'</b>"')?>: <span class='info gray'><?=$column?></span>
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

define('URL_FRSCB', '/m/search/?type=users&page='.tabs(get('page')));
require_once (ROOT.'/modules/users/plugins/friends_and_subscribers.php');
$data = db::get_string_all("SELECT `USERS`.`ID` FROM `USERS` LEFT JOIN `USERS_SETTINGS` ON (`USERS_SETTINGS`.`USER_ID` = `USERS`.`ID`) WHERE (`USERS`.`LOGIN` LIKE ? OR `USERS_SETTINGS`.`NAME` LIKE ? OR `USERS_SETTINGS`.`SURNAME` LIKE ? OR `USERS_SETTINGS`.`NAME` LIKE ? OR `USERS_SETTINGS`.`SURNAME` LIKE ?) ORDER BY `USERS`.`DATE_VISIT` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.$ex0.'%', '%'.$ex1.'%']);
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/users/plugins/users_list.php');

}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/search/?type=users&', $spage, $page, 'list');

back('/m/search/', 'К общему поиску');