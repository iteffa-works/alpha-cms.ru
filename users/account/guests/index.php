<?php  
html::title('Гости');
acms_header();
access('users');

require_once (ROOT.'/users/account/guests/plugins/delete.php');

?>
<div class='list'>
<a href='/account/guests/?get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Очистить')?></a>
</div>
<?
  
$column = db::get_column("SELECT COUNT(`ID`) FROM `USERS_GUESTS` WHERE `MY_ID` = ?", [user('ID')]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `USERS_GUESTS` WHERE `MY_ID` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID')]);
while ($list = $data->fetch()){
  
  $dop = '
  <br />
  <span class="time">
  '.lg('Посл. посещение').': '.ftime($list['TIME']).'<br />
  '.lg('Кол. посещений').': '.$list['COUNT'].'
  </span>
  ';
  
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;
  
}

if ($column > 0){
  
  ?></div><?
  
}
  
get_page('/account/guests/?', $spage, $page, 'list');
back('/account/cabinet/');
acms_footer();