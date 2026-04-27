<?php
acms_header('История входов', 'users');
  
$column = db::get_column("SELECT COUNT(`ID`) FROM `USERS_VISITS` WHERE `USER_ID` = ?", [user('ID')]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `USERS_VISITS` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID')]);
while ($list = $data->fetch()){
  
  ?>
  <div class='list-menu'>
  <b><?=lg('Время')?>:</b> <?=ftime($list['TIME'])?><br />
  <b><?=lg('Устройство и браузер')?>:</b> <?=tabs($list['BROWSER'])?><br />
  <b><?=lg('IP')?>:</b> <?=tabs($list['IP'])?><br />
  <b><?=lg('Местоположение')?>:</b> <?=tabs($list['LOCATION'])?><br />
  </div>
  <?
  
}

if ($column > 0){
  
  ?></div><?
  
}
  
get_page('/account/visits/?', $spage, $page, 'list');
back('/account/cabinet/');
acms_footer();